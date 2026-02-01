<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

use App\Models\Ticket;
use App\Models\Solde;
use App\Models\Paiement;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $dateDuJour = Carbon::today(); // Récupère la date d'aujourd'hui

        if (Auth::user()->isAdmin()) {
            $paiements = Paiement::latest()->paginate(10);
            $ticketsDuJour = Ticket::whereDate('updated_at', $dateDuJour)->where('etat_ticket', 'VENDU')->get();
            $ticketsTotalVendu = Ticket::where('etat_ticket', 'VENDU')->get();
            $solde = Solde::orderBy('id', 'desc')->first(); // This might need more thought for a global admin view
        } else {
            $paiements = Paiement::whereDate('updated_at', $dateDuJour)
                        ->where('user_id', Auth::user()->id)
                        ->where('status', 'COMPLETED')
                        ->latest()->paginate(10);

            $ticketsDuJour = Ticket::whereDate('updated_at', $dateDuJour)
                            ->where([
                                'etat_ticket' => 'VENDU',
                                'user_id' => Auth::user()->id
                            ])
                            ->get();

            $ticketsTotalVendu = Ticket::where([
                                'etat_ticket' => 'VENDU',
                                'user_id' => Auth::user()->id
                            ])
                            ->get();

            $solde = Solde::where('user_id', Auth::user()->id)->orderBy('id', 'desc')->first();
        }
        //dd($solde);
        $soldesDuJour = 0;
        foreach($ticketsDuJour as $ticket){
            $soldesDuJour = $soldesDuJour + $ticket->tarif->montant;
        }
        $montant = isset($solde) ? $solde->solde : 0;
        $datas = [
            "solde_total" => $montant,
            "retrait_total" => $montant - (25 * $montant)/100,
            "solde_du_jour" => $soldesDuJour,
            "ticket_du_jour_vendu" => count($ticketsDuJour),
            "ticket_total_vendu" => count($ticketsTotalVendu),
        ];
       // dd(count($ticketsDuJour));
        return view('admin.index',compact('datas','paiements'));
    }
    public function stopImpersonate()
    {
        if (session()->has('impersonator_id')) {
            // Log back in as admin
            Auth::loginUsingId(session('impersonator_id'));
            session()->forget('impersonator_id');
            return redirect()->route('admin.users')->with('success', 'Restauration de la session administrateur.');
        }
        return redirect()->route('home');
    }
}
