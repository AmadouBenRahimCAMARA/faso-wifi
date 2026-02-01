<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Support\Facades\Session;
use App\Models\Tarif;
use App\Models\Wifi;
use App\Models\Ticket;
use App\Models\Paiement;
use App\Models\Solde;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;



class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;


    public function acheter($slug){

        $wifi = Wifi::where('slug',$slug)->first();
        $tarifs = [];
        if($wifi){
            //dd($wifi->tarifs);
            $tarifs = $wifi->tarifs()->get();
        }else{
        }
        return view('achat-ticket', compact('tarifs'));

    }

    public function apiPaiement(Request $request){
        $tarif = Tarif::find($request->tarif_id);
        
        $transaction_id = 'FW'.date('Y').date('m').date('d').'.'.date('h').date('m').'.C'.rand(5,100000);
        $amount = $tarif->montant;

        DB::beginTransaction();
        try {
            // Select available ticket OR one reserved > 15min ago (expired reservation)
            $ticket = Ticket::where('tarif_id', $tarif->id)
                ->where(function($query) {
                    $query->where('etat_ticket', 'EN_VENTE')
                          ->orWhere(function($q) {
                              $q->where('etat_ticket', 'EN_COURS')
                                ->where('updated_at', '<', now()->subMinutes(15));
                          });
                })
                ->lockForUpdate() // Lock row to prevent double selection
                ->first();

            if(!$ticket){
               DB::rollBack();
               return redirect('/')->with('error', 'Aucun ticket disponible.');
            }

            // Reserve the ticket
            $ticket->etat_ticket = 'EN_COURS';
            $ticket->update(); // Explicit update to touch updated_at
            
            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect('/')->with('error', 'Erreur lors de la réservation du ticket.');
        }

        if(!$ticket){
           return redirect('/')->with('error', 'Aucun ticket disponible.');
        }

        // Create Paiement immediately with PENDING status
        $paiement = Paiement::create([
            'transaction_id' => $transaction_id,
            'ticket_id' => $ticket->id,
            'user_id' => $ticket->user_id, // Owner of the ticket (Vendor)
            'slug' => Str::random(10),
            'moyen_de_paiement' => 'Mobile Money', 
            'status' => 'pending',
            'numero' => '' // Will be filled on confirmation
        ]);

        session([
            'transaction_id' => $transaction_id,
            'ticket_id' => $ticket->id
        ]);

        $redirectPayin = $this->payinWithRedirection($transaction_id, $amount);

        if(isset($redirectPayin->response_code) and $redirectPayin->response_code=="00") {
            session([
                'invoiceToken' => $redirectPayin->token
            ]);
            return redirect($redirectPayin->response_text);
        } else {
            // Failed to initiate: mark as failed
            $paiement->status = 'failed';
            $paiement->save();
            return $this->showPaymentError($redirectPayin);
        }
    }

    public function statutPaiement(Request $request){
        $transaction_id = session('transaction_id');
        
        if (!$transaction_id) {
             return redirect('/');
        }
        
        $paiement = Paiement::where('transaction_id', $transaction_id)->first();
        if (!$paiement) {
             return redirect('/');
        }

        // 1. Check DB Status first (Webhook might have already processed it)
        if ($paiement->status == 'completed') {
             return redirect()->route("recu", $paiement->ticket->slug);
        }

        // 2. If not completed, check with Ligdicash manually (Fallback)
        $invoiceToken = session('invoiceToken');
        if($invoiceToken) {
            $payin = $this->statusPayin($invoiceToken);

            if (isset($payin) && trim($payin->status) == 'completed') {
                // Process completion (Duplicate logic from Webhook to ensure consistency)
                // Use transaction to avoid race condition with Webhook
                DB::transaction(function () use ($paiement, $payin) {
                    $paiement->refresh(); // Reload to be sure
                    
                    // Strict check: Transaction is valid ONLY if phone and operator are present
                    $hasValidInfo = !empty($payin->customer) && !empty($payin->operator_name);

                    if ($paiement->status != 'completed' && $hasValidInfo) {
                        $paiement->status = 'completed';
                        $paiement->moyen_de_paiement = $payin->operator_name ?? 'Ligdicash';
                        $paiement->numero = $payin->customer ?? '';
                        $paiement->save();

                        $ticket = $paiement->ticket;
                        if ($ticket && $ticket->etat_ticket != 'VENDU') {
                            $ticket->etat_ticket = 'VENDU';
                            $ticket->save();

                            $lastSolde = Solde::where('user_id', $paiement->user_id)
                                ->orderBy('id', 'desc')
                                ->lockForUpdate()
                                ->first();
                            
                            $montantCompte = $lastSolde ? $lastSolde->solde : 0;
                            
                            // Apply 10% Commission at the source (Exempt if Admin)
                            $owner = $ticket->owner;
                            $is_admin = $owner && $owner->isAdmin();
                            $netAmount = $is_admin ? $ticket->tarif->montant : ($ticket->tarif->montant * 0.90);

                            Solde::create([
                                "solde" => $montantCompte + $netAmount,
                                "type" => "PAIEMENT",
                                "slug" => Str::slug(Str::random(10)),
                                "user_id" => $paiement->user_id,
                                "paiement_id" => $paiement->id
                            ]);
                        }
                    }
                });

                return redirect()->route("recu", $paiement->ticket->slug);
            } elseif (isset($payin) && trim($payin->status) == 'nocompleted') {
                 $paiement->status = 'failed';
                 $paiement->save();
                 return $this->showPaymentError($payin, 'Le client a annulé le paiement');
            }
        }

        return view('paiement.payin_pending'); // Need a view for "Waiting..." or just error
    }

    public function recuperationView(){
        return view('mon-ticket');
    }

    public function recuperationPost(Request $request){

        $paiement = Paiement::where("transaction_id", $request->monTicket)->first();
        if($paiement){
            return redirect()->route("recu",$paiement->ticket->slug);
        }else{
            return view('mon-ticket-error');
        }
    }

    public function recu($slug)
    {
        $datas = [];
        $data = Ticket::where("slug", $slug)->first(); // Ticket

        if(!$data || $data->etat_ticket !== 'VENDU'){
            // Ticket not sold yet? Redirect to home
             return redirect('/')->with('error', 'Ce ticket n\'est pas disponible.');
        }

        $paiement = Paiement::where("ticket_id",$data->id)->first();
        
        return view("paiement.recu",compact("datas","data","paiement"));
    }

    public function downloadRecu($slug)
    {
        //dd(Paiement::all());
        $paiement = [];
        $datas = [];
        $data = Ticket::where("slug", $slug)->first();
        if($data){
            //dd($data);
            $paiement = Paiement::where("ticket_id",$data->id)->first();
            //dd($paiement);
        }

        $pdf = Pdf::loadView('paiement.recu-v1', compact("datas","data","paiement"));
        return $pdf->download('TICKET-FASOWIFI-' . date('dmYHis') . '.pdf');
    }

    public function viewNumber($number){

        session()->put('view', $number);

        return "ok";
    }

    // Private helpers
    private function payinWithRedirection($transaction_id, $amount) {
        $url = url('/');
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => "https://app.ligdicash.com/pay/v01/redirect/checkout-invoice/create",
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => "",
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => "POST",
            CURLOPT_POSTFIELDS =>'{
                      "commande": {
                        "invoice": {
                          "items": [
                            {
                              "name": "Achat de Ticket Wifi",
                              "description": "Ticket Wifi Faso-Wifi",
                              "quantity": 1,
                              "unit_price": "'.$amount.'",
                              "total_price": "'.$amount.'"
                            }
                          ],
                          "total_amount": "'.$amount.'",
                          "devise": "XOF",
                          "description": "Achat de connexion Wifi",
                          "customer": "",
                          "customer_firstname":"",
                          "customer_lastname":"",
                          "customer_email":""
                        },
                        "store": {
                          "name": "Faso-Wifi",
                          "website_url": "'.$url.'"
                        },
                        "actions": {
                          "cancel_url": "'.$url.'/status",
                          "return_url": "'.$url.'/status",
                          "callback_url": "'.$url.'/api/ligdicash/callback"
                        },
                        "custom_data": {
                          "transaction_id": "'.$transaction_id.'"
                        }
                      }
                    }',
            CURLOPT_HTTPHEADER => array(
                "Apikey: " . config('services.ligdicash.api_key'),
                "Authorization: Bearer " . config('services.ligdicash.token'),
                "Accept: application/json",
                "Content-Type: application/json"
            ),
        ));

        $response = json_decode(curl_exec($curl));
        curl_close($curl);
        return $response;
    }

    private function statusPayin($invoiceToken) {
        $curl = curl_init();

        curl_setopt_array($curl, [
            CURLOPT_URL => 'https://app.ligdicash.com/pay/v01/redirect/checkout-invoice/confirm/?invoiceToken=' . $invoiceToken,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 30,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => [
                "Apikey: " . config('services.ligdicash.api_key'),
                "Authorization: Bearer " . config('services.ligdicash.token'),
        ]]);
        $response = json_decode(curl_exec($curl));
        curl_close($curl);

        return $response;
    }

    private function showPaymentError($response, $prefix='') {
         // A simple error view or dd for now to match legacy behavior
         echo $prefix . '<br><br>';
         echo 'response_code=' . ($response->response_code ?? 'N/A');
         echo '<br><br>';
         echo 'response_text=' . ($response->response_text ?? 'N/A');
         echo '<br><br>';
         echo 'description=' . ($response->description ?? 'N/A');
         exit; // Or return view('paiement.error', compact('response'));
    }
}
