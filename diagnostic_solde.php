<?php

use App\Models\User;
use App\Models\Solde;
use App\Models\Paiement;
use App\Models\Ticket;
use Illuminate\Support\Facades\DB;

// Usage: php artisan tinker diagnostic.php --user_id=X

function runDiagnostic($userId) {
    $user = User::find($userId);
    if (!$user) {
        echo "User not found.\n";
        return;
    }

    echo "--- Diagnostic for User: " . $user->nom . " (" . $user->id . ") ---\n";

    // 1. Total Completed Payments
    $payments = Paiement::where('user_id', $userId)->where('status', 'completed')->get();
    $totalGross = 0;
    foreach($payments as $p) {
        $totalGross += (float)$p->ticket->tarif->montant;
    }
    echo "Total Gross Revenue (from Payments): " . $totalGross . " FCFA\n";

    // 2. Expected Net Revenue (assuming 10% commission)
    $totalExpectedNet = $totalGross * 0.90;
    echo "Expected Total Net (90%): " . $totalExpectedNet . " FCFA\n";

    // 3. Analyze Soldes Table
    $soldes = Solde::where('user_id', $userId)->orderBy('id', 'asc')->get();
    echo "Number of Solde entries: " . $soldes->count() . "\n";

    $runningBalance = 0;
    $totalCredited = 0;
    $totalDebited = 0;
    $duplicatePayments = [];
    $seenPayments = [];

    foreach($soldes as $s) {
        if ($s->type == 'PAIEMENT') {
            // Calculate what was actually added in this step
            $diff = $s->solde - $runningBalance;
            $totalCredited += $diff;
            
            if ($s->paiement_id) {
                if (isset($seenPayments[$s->paiement_id])) {
                    $duplicatePayments[] = $s->paiement_id;
                }
                $seenPayments[$s->paiement_id] = true;
            }
        } elseif ($s->type == 'RETRAIT') {
            $diff = $runningBalance - $s->solde;
            $totalDebited += $diff;
        }
        $runningBalance = $s->solde;
    }

    echo "Total actually credited to Solde: " . $totalCredited . " FCFA\n";
    echo "Total actually debited from Solde: " . $totalDebited . " FCFA\n";
    echo "Current Final Solde: " . $runningBalance . " FCFA\n";

    if (count($duplicatePayments) > 0) {
        echo "WARNING: Found duplicate credits for Payment IDs: " . implode(', ', array_unique($duplicatePayments)) . "\n";
    }

    echo "--- Verification ---\n";
    if ($totalCredited > $totalExpectedNet) {
        echo "Mismatch: Credited amount (" . $totalCredited . ") is HIGHER than expected net (" . $totalExpectedNet . ")\n";
        echo "Difference: " . ($totalCredited - $totalExpectedNet) . " FCFA\n";
    } else {
        echo "Credits are within expected bounds.\n";
    }
}

// Check if we are in tinker or just running a snippet
if (isset($argv[1])) {
    runDiagnostic($argv[1]);
} else {
    echo "Please provide a user_id as an argument.\n";
}
