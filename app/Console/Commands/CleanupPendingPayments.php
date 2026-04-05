<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class CleanupPendingPayments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'ticket:cleanup';

    protected $description = 'Clean up pending payments older than 5 minutes and check Ligdicash status';

    public function handle()
    {
        // Nettoyer les paiements en attente depuis plus de 5 minutes
        $paiements = \App\Models\Paiement::where('status', 'pending')
                        ->where('created_at', '<', now()->subMinutes(5))
                        ->get();

        foreach ($paiements as $paiement) {
            
            // Verrouiller la transaction pour éviter les accès concurrents
            \Illuminate\Support\Facades\DB::transaction(function () use ($paiement) {
                $lockedPaiement = \App\Models\Paiement::where('id', $paiement->id)->lockForUpdate()->first();
                
                // On vérifie qu'il est toujours en pending
                if ($lockedPaiement && $lockedPaiement->status === 'pending') {
                    $shouldFail = true;

                    if ($lockedPaiement->provider_token) {
                        $payin = $this->statusPayin($lockedPaiement->provider_token);

                        if ($payin && isset($payin->status)) {
                            $verifiedStatus = trim($payin->status);
                            $hasValidInfo = !empty($payin->customer) && !empty($payin->operator_name);

                            if ($verifiedStatus === 'completed' && $hasValidInfo) {
                                // Le paiement a finalement réussi
                                $lockedPaiement->status = 'completed';
                                $lockedPaiement->moyen_de_paiement = $payin->operator_name ?? 'Ligdicash';
                                $lockedPaiement->numero = $payin->customer ?? '';
                                $lockedPaiement->save();

                                $ticket = \App\Models\Ticket::find($lockedPaiement->ticket_id);
                                if ($ticket && $ticket->etat_ticket != 'VENDU') {
                                    $ticket->etat_ticket = 'VENDU';
                                    $ticket->save();

                                    $user = \App\Models\User::find($lockedPaiement->user_id);
                                    if ($user) {
                                        $newTotalBalance = $user->calculateBalance();
                                        \App\Models\Solde::create([
                                            "solde" => $newTotalBalance,
                                            "type" => "PAIEMENT",
                                            "slug" => \Illuminate\Support\Str::slug(\Illuminate\Support\Str::random(10)),
                                            "user_id" => $lockedPaiement->user_id,
                                            "paiement_id" => $lockedPaiement->id
                                        ]);
                                    }
                                }
                                $shouldFail = false;
                                $this->info("Paiement #{$lockedPaiement->id} validé avec succès.");
                            } elseif ($verifiedStatus === 'pending') {
                                // Toujours en attente, on laisse pour le prochain cycle
                                // SECURITY FIX: Empêcher un blocage infini si Ligdicash ne répond jamais "failed"
                                if ($lockedPaiement->created_at < now()->subHours(24)) {
                                    $shouldFail = true;
                                    $this->warn("Paiement #{$lockedPaiement->id} expiré (bloqué en pending chez Ligdicash depuis 24h).");
                                } else {
                                    $shouldFail = false;
                                }
                            }
                        }
                    }

                    if ($shouldFail) {
                        $lockedPaiement->status = 'failed';
                        $lockedPaiement->save();

                        // Remettre le ticket en vente
                        $ticket = \App\Models\Ticket::find($lockedPaiement->ticket_id);
                        if ($ticket && $ticket->etat_ticket === 'EN_COURS') {
                            $ticket->etat_ticket = 'EN_VENTE';
                            $ticket->save();
                            $this->info("Ticket #{$ticket->id} (Paiement #{$lockedPaiement->id}) remis en vente.");
                        }
                    }
                }
            });
        }

        return \Illuminate\Console\Command::SUCCESS;
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
