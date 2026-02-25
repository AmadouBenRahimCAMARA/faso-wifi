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

        // Show All Time by default or if filter is removed
        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $stats = $this->calculateStats($user, $start_date, $end_date);

        return view('admin.bilan', compact('stats', 'start_date', 'end_date'));
    }

    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        if($user->isAdmin()){
             abort(403);
        }

        $start_date = $request->input('start_date');
        $end_date = $request->input('end_date');

        $stats = $this->calculateStats($user, $start_date, $end_date);

        $pdf = PDF::loadView('pdf.bilan', compact('stats', 'start_date', 'end_date', 'user'));
        
        return $pdf->download('Bilan_'.$user->nom.'_'.now()->format('d_m_Y').'.pdf');
    }

    private function calculateStats($user, $start = null, $end = null)
    {
        $query = Paiement::whereHas('ticket', function($q) use ($user){
            $q->where('user_id', $user->id);
        })->where('status', 'completed');

        // Apply filters only if provided
        if ($start) {
            $query->where('created_at', '>=', Carbon::parse($start)->startOfDay());
        }
        if ($end) {
            $query->where('created_at', '<=', Carbon::parse($end)->endOfDay());
        }

        $paiements = $query->get();

        $chiffreAffairesTotal = 0;
        foreach($paiements as $p){
             $chiffreAffairesTotal += $p->ticket->tarif->montant;
        }

        // Total Commission
        $commissionTotal = $chiffreAffairesTotal * 0.10;

        // Total Net
        $netTotal = $chiffreAffairesTotal - $commissionTotal;

        // Retraits Effectués (PAYE)
        $retraitQuery = Retrait::where('user_id', $user->id)->where('statut', 'PAYE');
        if ($start) $retraitQuery->where('updated_at', '>=', Carbon::parse($start)->startOfDay());
        if ($end) $retraitQuery->where('updated_at', '<=', Carbon::parse($end)->endOfDay());
        
        $totalRetraits = $retraitQuery->get()->sum('montant');

        // Solde Actuel (Toujours le même, c'est ce qui reste en poche)
        $lastSolde = Solde::where('user_id', $user->id)->orderBy('id', 'desc')->first();
        $soldeActuel = $lastSolde ? $lastSolde->solde : 0;

        return [
            'chiffre_affaires' => $chiffreAffairesTotal,
            'commission' => $commissionTotal,
            'net_percu' => $netTotal,
            'total_retraits' => $totalRetraits,
            'solde_net_disponible' => $soldeActuel
        ];
    }
}
