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

        $ticket = Ticket::where([
          "etat_ticket" => "EN_VENTE",
          "tarif_id" => $tarif->id
        ])->first();

        if($ticket){
           session(['ticket_id' => $ticket->id]);
        }else{
           return redirect('/');
        }

        $redirectPayin = $this->payinWithRedirection($transaction_id, $amount);

        if(isset($redirectPayin->response_code) and $redirectPayin->response_code=="00") {
            session([
                'total' => $amount,
                'tid' => $transaction_id,
                'invoiceToken' => $redirectPayin->token
            ]);
            return redirect($redirectPayin->response_text);
        } else {
            return $this->showPaymentError($redirectPayin);
        }
    }

    public function statutPaiement(Request $request){
        $invoiceToken = session('invoiceToken');
        
        if (!$invoiceToken) {
             return redirect('/');
        }
        
        // $idcompte = session('idForum');
        // $idparticipant = session('idParticipant');
        session(['first_time' => 1]);
        $montant = session('total');
        $total = session('total');
        $tid = session('tid');
        $ticket_id = session('ticket_id');

        $ticket = Ticket::find($ticket_id);
        $payin = $this->statusPayin($invoiceToken);

        if (isset($payin)) {
            if (trim($payin->status) == 'completed') {
                $from_data = [
                    'transaction_id' => $tid,
                    'ticket_id' => $ticket_id,
                    'numero' => $payin->customer,
                    'slug' => Str::random(10),
                    'moyen_de_paiement' => $payin->operator_name,
                ];

                session(['paiement' => $from_data]); // Store array directly, detailed serialization removed

                return redirect()->route("recu", $ticket->slug);

            } elseif (trim($payin->status) == 'nocompleted') {
                return $this->showPaymentError($payin, 'Le client a annulé le paiement');
            } elseif (trim($payin->status) == 'pending') {
                return $this->showPaymentError($payin, 'Paiement en attente');
            } else {
                return $this->showPaymentError($payin, 'Erreur inconnue');
            }
        } else {
            return redirect('/');
        }
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
        //dd(Paiement::all());
        $paiement = [];
        $datas = [];
        $data = Ticket::where("slug", $slug)->first();

        if($data){
            if($data->etat_ticket === "VENDU"){
                $paiement = Paiement::where("ticket_id",$data->id)->first();
            }else{
                $from_data = session('paiement');
                
                if (!$from_data) {
                    return redirect('/'); // Security: No session data
                }
                
                $from_data['user_id'] = $data->user_id;


                //dd(session('first_time'));
                $paiement = Paiement::create($from_data);
               // $ticket = App\Models\Ticket::find($ticket_id);
                $data->etat_ticket = "VENDU";
                //$ticket->vente_date = lluminate\Support\Carbon::now();
                $data->save();

                $solde = Solde::where('user_id', $paiement->user_id)->orderBy('id', 'desc')->first();
                $montantCompte = 0;
                if($solde){
                    $montantCompte = $solde->solde;
                }

                Solde::create([
                    "solde" => $montantCompte + $data->tarif->montant,
                    "type" => "PAIEMENT",
                    "slug" => Str::slug(Str::random(10)),
                    "user_id" => $data->user_id,
                    "paiement_id" => $paiement->id
                ]);
            }


            //$paiement = Paiement::where("ticket_id",$data->id)->first();
            //dd($paiement);
        }

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
                          "callback_url": "'.$url.'/status"
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
