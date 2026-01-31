<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use PDF;
use App\Models\Paiement;
use App\Models\Retrait;
use App\Models\Solde;
use Carbon\Carbon;

class BilanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(Request $request)
    {
        $user = Auth::user();
        if($user->isAdmin()){
             return redirect()->route('admin.dashboard');
        }

        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $stats = $this->calculateStats($user, $start_date, $end_date);

        return view('admin.bilan', compact('stats', 'start_date', 'end_date'));
    }

    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        if($user->isAdmin()){
             abort(403);
        }

        $start_date = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
        $end_date = $request->input('end_date', Carbon::now()->endOfMonth()->format('Y-m-d'));

        $stats = $this->calculateStats($user, $start_date, $end_date);

        $pdf = PDF::loadView('pdf.bilan', compact('stats', 'start_date', 'end_date', 'user'));
        
        return $pdf->download('Bilan_'.$user->nom.'_'.$start_date.'_au_'.$end_date.'.pdf');
    }

    private function calculateStats($user, $start, $end)
    {
        // Convert to Carbon for comparison
        $start = Carbon::parse($start)->startOfDay();
        $end = Carbon::parse($end)->endOfDay();

        // 1. Total Chiffre d'Affaires (Somme des tickets vendus par ce User)
        // Les paiements sont liés aux tickets, et les tickets au User (via owner)
        // Mais plus simple: Auth::user()->tickets() -> sum(tarif.montant) WHERE etat_ticket = VENDU
        // Ou via Paiement qui a le montant réel payé.
        // Utilisons les paiements reçus pour les tickets de ce vendeur.
        
        $paiements = Paiement::whereHas('ticket', function($q) use ($user){
            $q->where('user_id', $user->id);
        })->whereBetween('created_at', [$start, $end])->get();

        $chiffreAffaires = 0;
        foreach($paiements as $p){
            // Le montant est dans le tarif du ticket
             $chiffreAffaires += $p->ticket->tarif->montant;
        }

        // 2. Commission (10%)
        $commission = $chiffreAffaires * 0.10;

        // 3. Net Percu (Ce qui revient au vendeur)
        $netPercu = $chiffreAffaires - $commission;

        // 4. Retraits Effectués (PAYE)
        $retraits = Retrait::where('user_id', $user->id)
                           ->where('statut', 'PAYE')
                           ->whereBetween('updated_at', [$start, $end]) // Date de validation
                           ->get()
                           ->sum('montant');

        // 5. Solde Actuel (Indépendant de la période, c'est l'état à l'instant T)
        $lastSolde = Solde::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $soldeActuel = $lastSolde ? $lastSolde->solde : 0;
        
        // Ensure soldeActuel is what is withdrawable (Net)?? 
        // Based on RetraitController logic: `retrait = montant - (montant * 0.10)` was OLD logic?
        // Wait, store logic implies Solde in database tracks the GROSS or NET?
        // Analyzing RetraitController logic:
        // Update: `$newDistSolde = $currentAmount - $retrait->montant;`
        // Creates Solde record.
        // If Solde tracks actual money available, then $soldeActuel IS the withdrawable amount.
        // The commission is deducted when? 
        // Actually, the previous dev's logic in Create was confusing: `$retrait = $montant - (25 * $montant)/100;`
        // This implies the `soldes` table stores the GROSS amount, and we only let them withdraw 75% of it.
        // BUT, if we want to change commission to 10%, we should change that display logic.
        // So `soldeActuel` (Gross) * 0.90 = Withdrawable.
        
        // HOWEVER, "Solde disponible doit etre directement le montant possible à retirer".
        // This means the `solde` shown to user should be NET.
        // If DB stores Gross, we calculate Net here.
        
        $soldeNetDisponible = $soldeActuel - ($soldeActuel * 0.10); // Applying the 10% on the remaining balance.

        return [
            'chiffre_affaires' => $chiffreAffaires,
            'commission' => $commission,
            'net_percu' => $netPercu,
            'total_retraits' => $retraits,
            'solde_actuel_brut' => $soldeActuel,
            'solde_net_disponible' => $soldeNetDisponible
        ];
    }
}
