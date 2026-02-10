<?php

namespace App\Http\Controllers;

use App\Models\Paiement;
use App\Models\Ticket;
use App\Models\Solde;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Carbon;

class PaiementController extends Controller
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
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $limit = session()->has('view') ? session()->get('view') : 10;
        $search = $request->get('search');

        if (Auth::user()->isAdmin()) {
            $query = Paiement::with(['ticket.owner', 'ticket.tarif']);
        } else {
            $query = Auth::user()->paiements()->with('ticket.tarif');
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'LIKE', '%' . $search . '%')
                  ->orWhere('numero', 'LIKE', '%' . $search . '%')
                  ->orWhere('moyen_de_paiement', 'LIKE', '%' . $search . '%');
            });
        }

        $datas = $query->latest()->paginate($limit)->appends($request->only('search'));

        return view("admin.paiement-liste", compact("datas", "limit", "search"));
    }

    

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function show(Paiement $paiement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function edit(Paiement $paiement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Paiement $paiement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paiement  $paiement
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paiement $paiement)
    {
        //
    }
}
