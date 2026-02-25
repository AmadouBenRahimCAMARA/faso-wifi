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
        $wifi_id = $request->get('wifi_id');
        $tarif_id = $request->get('tarif_id');
        $user_id = $request->get('user_id');

        $isAdmin = Auth::user()->isAdmin();

        if ($isAdmin) {
            $query = Paiement::with(['ticket.owner', 'ticket.tarif.wifi']);
        } else {
            $query = Auth::user()->paiements()->with(['ticket.tarif.wifi']);
        }

        // --- Advanced Filters ---
        if ($wifi_id) {
            $query->whereHas('ticket.tarif', function($q) use ($wifi_id) {
                $q->where('wifi_id', $wifi_id);
            });
        }
        if ($tarif_id) {
            $query->whereHas('ticket', function($q) use ($tarif_id) {
                $q->where('tarif_id', $tarif_id);
            });
        }
        if ($isAdmin && $user_id) {
            $query->where('user_id', $user_id);
        }

        // --- Search ---
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('transaction_id', 'LIKE', '%' . $search . '%')
                  ->orWhere('numero', 'LIKE', '%' . $search . '%')
                  ->orWhere('moyen_de_paiement', 'LIKE', '%' . $search . '%');
            });
        }

        $params = $request->only(['search', 'wifi_id', 'tarif_id', 'user_id']);
        $datas = $query->latest()->paginate($limit)->appends($params);

        // Data for filter selects
        if ($isAdmin) {
            $wifis = \App\Models\Wifi::orderBy('nom')->get();
            $users = \App\Models\User::where('is_admin', false)->orderBy('nom')->get();
        } else {
            $wifis = Auth::user()->wifis()->orderBy('nom')->get();
            $users = collect();
        }

        // Tarifs filtered by wifi if selected
        if ($wifi_id) {
            $tarifQuery = \App\Models\Tarif::where('wifi_id', $wifi_id);
            if (!$isAdmin) {
                $tarifQuery->where('user_id', Auth::id());
            }
            $tarifs = $tarifQuery->orderBy('forfait')->get();
        } else {
            if ($isAdmin) {
                $tarifs = \App\Models\Tarif::orderBy('forfait')->get();
            } else {
                $tarifs = \App\Models\Tarif::where('user_id', Auth::id())->orderBy('forfait')->get();
            }
        }

        return view("admin.paiement-liste", compact(
            "datas", "limit", "search", "wifis", "tarifs", "users", 
            "wifi_id", "tarif_id", "user_id"
        ));
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
