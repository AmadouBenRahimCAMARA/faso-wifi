<?php

namespace App\Http\Controllers;

use App\Models\Retrait;
use App\Models\Paiement;
use App\Models\Ticket;
use App\Models\Solde;
use App\Models\User;
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
                
                // For admin: Calculate global revenue EXCLUDING user #6 (problematic reseller)
                $totalRevenue = Paiement::where('paiements.status', 'completed')
                    ->join('tickets', 'paiements.ticket_id', '=', 'tickets.id')
                    ->join('tarifs', 'tickets.tarif_id', '=', 'tarifs.id')
                    ->where('tickets.user_id', '!=', 6) // Exclude reseller #6
                    ->sum(DB::raw('CAST(tarifs.montant AS DECIMAL)'));
                
                $totalRetraitsPayes = Retrait::where('statut', 'PAYE')
                    ->where('user_id', '!=', 6) // Exclude reseller #6
                    ->sum(DB::raw('CAST(montant AS DECIMAL)'));
                
                $montant = $totalRevenue - $totalRetraitsPayes;
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

        $soldeDisponible = Auth::user()->calculateBalance();

        $request->validate([
            'moyen_de_paiement' => 'required|string|max:255',
            'numero_paiement' => 'required|string|max:255',
            'montant' => [
                'required',
                'numeric',
                'min:1000',
                'max:' . $soldeDisponible,
            ],
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

        if($request->statut === 'PAYE'){
            // 🔒 UTILISER UNE TRANSACTION AVEC VERROUILLAGE pour éviter la race condition
            try {
                DB::transaction(function () use ($retrait, $request) {
                    // Re-verrouiller le retrait pour éviter le double traitement
                    $lockedRetrait = Retrait::where('id', $retrait->id)->lockForUpdate()->first();
                    
                    if ($lockedRetrait->statut !== 'EN_ATTENTE') {
                        throw new \Exception('Ce retrait a déjà été traité.');
                    }
                    
                    // ✅ VÉRIFIER CONTRE calculateBalance() qui est la source de vérité
                    $owner = User::find($lockedRetrait->user_id);
                    if (!$owner) {
                        throw new \Exception('Utilisateur introuvable.');
                    }
                    
                    $soldeDisponible = $owner->calculateBalance();
                    
                    if ($lockedRetrait->montant > $soldeDisponible) {
                        throw new \Exception(
                            "Solde insuffisant. Disponible: {$soldeDisponible} FCFA, Demandé: {$lockedRetrait->montant} FCFA"
                        );
                    }
                    
                    // Créer l'entrée Solde avec le nouveau solde après retrait
                    Solde::create([
                        'solde' => $soldeDisponible - $lockedRetrait->montant,
                        'type' => 'RETRAIT',
                        'slug' => Str::slug(Str::random(10)),
                        'user_id' => $lockedRetrait->user_id,
                        'paiement_id' => null 
                    ]);
                    
                    $lockedRetrait->update(['statut' => 'PAYE']);
                });
                
                return back()->with('success', 'Retrait validé et solde mis à jour.');
                
            } catch (\Exception $e) {
                return back()->with('error', '❌ ' . $e->getMessage());
            }
        } else {
            // Reject
            DB::transaction(function () use ($retrait) {
                $lockedRetrait = Retrait::where('id', $retrait->id)->lockForUpdate()->first();
                if ($lockedRetrait->statut !== 'EN_ATTENTE') {
                    throw new \Exception('Ce retrait a déjà été traité.');
                }
                $lockedRetrait->update(['statut' => 'REJETE']);
            });
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
