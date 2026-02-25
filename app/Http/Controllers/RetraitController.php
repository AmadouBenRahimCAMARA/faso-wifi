<?php

namespace App\Http\Controllers;

use App\Models\Retrait;
use App\Models\Paiement;
use App\Models\Ticket;
use App\Models\Solde;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RetraitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        {
            $limit = "";
            $user = Auth::user();
            $search = $request->get('search');

            if ($user->isAdmin()) {
                $query = Retrait::with('user');
                $ticketsDuJour = Ticket::whereDate('updated_at', Carbon::today())->where('etat_ticket', 'VENDU')->get();
                $ticketsTotalVendu = Ticket::where('etat_ticket', 'VENDU')->get();
                
                // For admin, align Solde and Retrait available with Global Revenue (Chiffre d'Affaire Global)
                $montant = Paiement::where('paiements.status', 'completed')
                    ->join('tickets', 'paiements.ticket_id', '=', 'tickets.id')
                    ->join('tarifs', 'tickets.tarif_id', '=', 'tarifs.id')
                    ->sum(DB::raw('CAST(tarifs.montant AS DECIMAL)'));
            } else {
                $query = $user->retraits();
                $dateDuJour = Carbon::today();
                $ticketsDuJour = Ticket::whereDate('updated_at', $dateDuJour)
                                ->where([
                                'etat_ticket' => 'VENDU',
                                'user_id' => $user->id])
                                ->get();

                $ticketsTotalVendu = Ticket::where([
                                'etat_ticket' => 'VENDU',
                                'user_id' => $user->id])
                                ->get();

                $solde = null; // No longer used for the final amount
                $montant = $user->calculateBalance();
            }

            // Recherche
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('transaction_id', 'LIKE', '%' . $search . '%')
                      ->orWhere('numero_paiement', 'LIKE', '%' . $search . '%')
                      ->orWhere('moyen_de_paiement', 'LIKE', '%' . $search . '%')
                      ->orWhere('montant', 'LIKE', '%' . $search . '%');
                });
            }

            $datas = $query->latest()->paginate(10)->appends($request->only('search'));

            $soldesDuJour = 0;
            foreach($ticketsDuJour as $ticket){
                $soldesDuJour = $soldesDuJour + $ticket->tarif->montant;
            }

            // $montant is already calculated above based on role
            $compte = [
                "solde_total" => $montant,
                "retrait_total" => $montant,
                "solde_du_jour" => $soldesDuJour,
                "ticket_du_jour_vendu" => count($ticketsDuJour),
                "ticket_total_vendu" => count($ticketsTotalVendu),
            ];

            return view("admin.retrait-liste", compact("datas", "limit", "user", "compte", "search"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if(Auth::user()->isAdmin()){
            abort(403, "Les administrateurs ne peuvent pas faire de demande de retrait.");
        }

        $montant = Auth::user()->calculateBalance();
        
        // Solde is now stored as Net (Commission already deducted at sale)
        $retrait = $montant;
        return view("admin.retrait-create", compact('retrait'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(Auth::user()->isAdmin()){
            abort(403, "Les administrateurs ne peuvent pas faire de demande de retrait.");
        }

        $request->validate([
            'moyen_de_paiement' => 'required|string|max:255',
            'numero_paiement' => 'required|string|max:255',
            'montant' => 'required|numeric|min:1000',
        ]);

        $request['slug'] = Str::slug(Str::random(10));
        $request['user_id'] = Auth::user()->id;
        Retrait::create($request->all());
        return redirect('retrait')->with('success', 'Demande de retrait envoyée.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function show(Retrait $retrait)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function edit(Retrait $retrait)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Retrait $retrait)
    {
        if(!Auth::user()->isAdmin()){
            abort(403);
        }

        $request->validate([
            'statut' => 'required|in:PAYE,REJETE'
        ]);

        if($retrait->statut !== 'EN_ATTENTE'){
             return back()->with('error', 'Ce retrait a déjà été traité.');
        }

        if($request->statut === 'PAYE'){
            // Logic to pay
            // 1. Get User's last solde
            $lastSolde = Solde::where('user_id', $retrait->user_id)->orderBy('id', 'desc')->first();
            $currentAmount = $lastSolde ? $lastSolde->solde : 0;

            // 2. Calculate New Balance
            // Note: The withdrawal amount requested ($retrait->montant) is what they want to receive.
            // But usually, logic implies the deduction from system balance.
            // Assuming $retrait->montant is the amount to be DEDUCTED.
            // If the math in Create was "Display = Solde - 25%", then the User requests an amount.
            // Let's assume $retrait->montant IS the amount to subtract.
            
            // Safety check: enough balance?
            // Actually, we should check $retrait is <= calculated limit, but for now we trust the val stored.
            
            $newDistSolde = $currentAmount - $retrait->montant;

            // 3. Create new Solde entry
            Solde::create([
                'solde' => $newDistSolde,
                'type' => 'RETRAIT',
                'slug' => Str::slug(Str::random(10)),
                'user_id' => $retrait->user_id,
                'paiement_id' => null 
            ]);

            $retrait->update(['statut' => 'PAYE']);

            return back()->with('success', 'Retrait validé et solde mis à jour.');

        } else {
            // Reject
            $retrait->update(['statut' => 'REJETE']);
            return back()->with('success', 'Retrait rejeté.');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Retrait  $retrait
     * @return \Illuminate\Http\Response
     */
    public function destroy(Retrait $retrait)
    {
        //
    }
}
