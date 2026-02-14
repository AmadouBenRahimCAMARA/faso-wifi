<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Ticket;
use Illuminate\Support\Facades\Log;

class CleanupTickets extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tickets:cleanup';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Libérer les tickets réservés (EN_COURS) depuis plus de 5 minutes';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $count = Ticket::where('etat_ticket', 'EN_COURS')
            ->where('updated_at', '<', now()->subMinutes(5))
            ->update(['etat_ticket' => 'EN_VENTE']);

        if ($count > 0) {
            $this->info("{$count} tickets ont été libérés (remis EN_VENTE).");
            Log::info("CleanupTickets: {$count} tickets expired and reset to EN_VENTE.");
        } else {
            $this->info("Aucun ticket à nettoyer.");
        }

        return 0;
    }
}
