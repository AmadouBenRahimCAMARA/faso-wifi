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
        
        // Since we now credit the NET amount in DB, the Solde IS the Net Available.
        $soldeNetDisponible = $soldeActuel;

        return [
            'chiffre_affaires' => $chiffreAffaires,
            'commission' => $commission,
            'net_percu' => $netPercu,
            'total_retraits' => $retraits,
            'solde_actuel_brut' => $soldeActuel, // Variable name kept for compat but it is Net
            'solde_net_disponible' => $soldeNetDisponible
        ];
    }
}
