<?php

namespace App\Http\Controllers;

use App\Models\Retrait;
use App\Models\Paiement;
use App\Models\Ticket;
use App\Models\Solde;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class RetraitController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        {
            $limit = "";
            $user = Auth::user();

            if ($user->isAdmin()) {
                $datas = Retrait::with('user')->latest()->paginate(10);
                $ticketsDuJour = Ticket::whereDate('updated_at', Carbon::today())->where('etat_ticket', 'VENDU')->get();
                $ticketsTotalVendu = Ticket::where('etat_ticket', 'VENDU')->get();
                $solde = Solde::orderBy('id', 'desc')->first(); // Global latest solde
            } else {
                $datas = $user->retraits()->latest()->paginate(10);
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

                $solde = Solde::where('user_id', $user->id)->orderBy('id', 'desc')->first();
            }

            $soldesDuJour = 0;
            foreach($ticketsDuJour as $ticket){
                $soldesDuJour = $soldesDuJour + $ticket->tarif->montant;
            }

            $montant = isset($solde) ? $solde->solde : 0;
            $compte = [
                "solde_total" => $montant,
                "retrait_total" => $montant - (25 * $montant)/100,
                "solde_du_jour" => $soldesDuJour,
                "ticket_du_jour_vendu" => count($ticketsDuJour),
                "ticket_total_vendu" => count($ticketsTotalVendu),
            ];

            return view("admin.retrait-liste",compact("datas","limit","user","compte"));
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $dateDuJour = Carbon::today(); // Récupère la date d'aujourd'hui
        $solde = Solde::whereDate('updated_at', $dateDuJour)->orderBy('id', 'desc')->first();
        $montant = isset($solde) ? $solde->solde : 0;
        $retrait = $montant - (25 * $montant)/100;
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
        $request->validate([
            'moyen_de_paiement' => 'required|string|max:255',
            'numero_paiement' => 'required|string|max:255',
            'montant' => 'required|string|max:255',
        ]);

        $request['slug'] = Str::slug(Str::random(10));
        $request['user_id'] = Auth::user()->id;
        Retrait::create($request->all());
        return redirect('retrait');
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
        //
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
