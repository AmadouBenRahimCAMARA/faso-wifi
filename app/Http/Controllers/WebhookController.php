<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Paiement;
use App\Models\Solde;
use App\Models\Ticket;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class WebhookController extends Controller
{
    public function handle(Request $request)
    {
        // 1. Log the incoming request for debugging
        Log::info('Ligdicash Webhook received', $request->all());

        // 2. Validate basic structure
        if (!$request->has('token') || !$request->has('status')) {
            return response()->json(['status' => 'error', 'message' => 'Invalid payload'], 400);
        }

        $token = $request->input('token');
        $status = $request->input('status');

        // 3. Find the payment associated with this transaction/token
        // Note: ligdicash sends 'token' which corresponds to 'invoiceToken' we got earlier, 
        // OR 'custom_data.transaction_id' if we passed it.
        // Let's assume we can match via 'invoiceToken' if we stored it, or 'transaction_id'.
        
        // In the Controller::payinWithRedirection, we get a response that has a token.
        // Let's verify what Ligdicash sends back. It usually sends the payload of the invoice.
        
        // For robustness, we will try to find by transaction_id mostly, if available in custom_data
        $transactionId = $request->input('custom_data.transaction_id');
        
        if ($transactionId) {
             $paiement = Paiement::where('transaction_id', $transactionId)->first();
        } else {
             // Fallback logic if needed, but we passed transaction_id in custom_data
             return response()->json(['status' => 'error', 'message' => 'Transaction ID not found'], 404);
        }

        if (!$paiement) {
            Log::error('Paiement not found for transaction: ' . $transactionId);
            return response()->json(['status' => 'error', 'message' => 'Paiement not found'], 404);
        }

        // 4. Verification Check (Double Check with API)
        // Ideally, we should verify the status directly with Ligdicash API to prevent spoofing
        // But for this step, we trust the signature/payload if we assume HTTPS privacy or if we implement signature check.
        // To be safe, let's call statusPayin like in the Controller.
        
        $verifiedPayin = $this->statusPayin($token);
        
        if (!$verifiedPayin || !isset($verifiedPayin->status)) {
             Log::error('Ligdicash verification failed for token: ' . $token);
             return response()->json(['status' => 'error', 'message' => 'Verification failed'], 500);
        }
        
        $verifiedStatus = trim($verifiedPayin->status);

        // 5. Update Database
        if ($verifiedStatus == 'completed' && $paiement->status != 'completed') {
            
            DB::beginTransaction();
            try {
                // Update Paiement
                $paiement->status = 'completed';
                $paiement->moyen_de_paiement = $verifiedPayin->operator_name ?? 'Ligdicash';
                $paiement->numero = $verifiedPayin->customer ?? '';
                $paiement->save();

                // Update Ticket
                $ticket = Ticket::find($paiement->ticket_id);
                if ($ticket && $ticket->etat_ticket != 'VENDU') {
                    $ticket->etat_ticket = 'VENDU';
                    $ticket->save();

                    // Update Solde (Credit Vendor)
                    // Use lockForUpdate to prevent race conditions
                    $lastSolde = Solde::where('user_id', $paiement->user_id)
                        ->orderBy('id', 'desc')
                        ->lockForUpdate()
                        ->first();
                    
                    $montantCompte = $lastSolde ? $lastSolde->solde : 0;
                    
                    Solde::create([
                        "solde" => $montantCompte + $ticket->tarif->montant,
                        "type" => "PAIEMENT",
                        "slug" => Str::slug(Str::random(10)),
                        "user_id" => $paiement->user_id,
                        "paiement_id" => $paiement->id
                    ]);
                }
                
                DB::commit();
                Log::info('Payment completed and processed for transaction: ' . $transactionId);

            } catch (\Exception $e) {
                DB::rollBack();
                Log::error('Error processing payment: ' . $e->getMessage());
                return response()->json(['status' => 'error', 'message' => 'Internal Server Error'], 500);
            }

        } else if ($verifiedStatus == 'completed' && $paiement->status == 'completed') {
             Log::info('Payment already processed for transaction: ' . $transactionId);
        } else {
             $paiement->status = 'failed'; // or pending
             $paiement->save();
        }

        return response()->json(['status' => 'success']);
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
}
