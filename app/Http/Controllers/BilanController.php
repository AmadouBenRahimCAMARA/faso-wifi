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

        $stats = $this->calculateStats($user);

        return view('admin.bilan', compact('stats'));
    }

    public function downloadPdf(Request $request)
    {
        $user = Auth::user();
        if($user->isAdmin()){
             abort(403);
        }

        $stats = $this->calculateStats($user);

        $pdf = PDF::loadView('pdf.bilan', compact('stats', 'user'));
        
        return $pdf->download('Bilan_'.$user->nom.'_'.now()->format('d_m_Y').'.pdf');
    }

    private function calculateStats($user)
    {
        $paiements = Paiement::whereHas('ticket', function($q) use ($user){
            $q->where('user_id', $user->id);
        })->where('status', 'completed')->get();

        $chiffreAffairesTotal = 0;
        foreach($paiements as $p){
             $chiffreAffairesTotal += $p->ticket->tarif->montant;
        }

        // Total Commission
        $commissionTotal = $chiffreAffairesTotal * 0.10;

        // Total Net
        $netTotal = $chiffreAffairesTotal - $commissionTotal;

        // Retraits Effectués (PAYE)
        $totalRetraits = Retrait::where('user_id', $user->id)
            ->where('statut', 'PAYE')
            ->get()
            ->sum('montant');

        // Solde Actuel (Ce qui reste en poche)
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
