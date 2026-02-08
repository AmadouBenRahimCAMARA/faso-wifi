<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Tarif;
use App\Models\Wifi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use App\Imports\TicketsImport;
use Excel;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;



class TicketController extends Controller
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
        // Filtre par défaut : EN_VENTE (tickets disponibles)
        $filter = $request->get('filter', 'en_vente');
        
        if (Auth::user()->isAdmin()) {
            $baseQuery = Ticket::query();
            $query = Ticket::with('owner');
        } else {
            $baseQuery = Auth::user()->tickets();
            $query = Auth::user()->tickets();
        }
        
        // Calculer les compteurs pour chaque état
        $counts = [
            'en_vente' => (clone $baseQuery)->where('etat_ticket', 'EN_VENTE')->count(),
            'en_cours' => (clone $baseQuery)->where('etat_ticket', 'EN_COURS')->count(),
            'vendu' => (clone $baseQuery)->where('etat_ticket', 'VENDU')->count(),
            'tous' => (clone $baseQuery)->count(),
        ];
        
        // Appliquer le filtre
        if ($filter === 'en_vente') {
            $query->where('etat_ticket', 'EN_VENTE');
        } elseif ($filter === 'vendu') {
            $query->where('etat_ticket', 'VENDU');
        } elseif ($filter === 'en_cours') {
            $query->where('etat_ticket', 'EN_COURS');
        }
        // Si 'tous', pas de filtre sur etat_ticket
        
        $datas = $query->latest()->paginate(10)->appends(['filter' => $filter]);
        
        return view("admin.ticket-liste", compact("datas", "filter", "counts"));
    }

    public function create()
    {
        $user = Auth::user();
        if ($user->isAdmin()) {
            $wifis = Wifi::all();
            $tarifs = Tarif::all();
        } else {
            $wifis = $user->wifis()->get();
            $tarifs = $user->tarifs()->get();
        }
        return view("admin.ticket-create", compact('wifis', 'tarifs'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //dd($request->all());
        Session::put('tarif_id', $request->tarif_id);

        $request->validate([
             'fichier' => 'required|file|mimes:xlsx,xls,csv,txt|max:10240', // 10MB max
        ]);

        $file = $request->file('fichier'); 
        // Use Laravel's secure storage instead of public path
        // This stores in storage/app/tickets which is NOT accessible via web unless symlinked
        $filename = Str::random(20) . '.' . $file->getClientOriginalExtension();
        $path = $file->storeAs('tickets', $filename);
        
        try {
            Excel::import(new TicketsImport, storage_path("app/{$path}"));
        } catch (\Exception $e) {
            // Log error if needed
            return back()->with('error', 'Erreur lors de l\'importation : ' . $e->getMessage());
        } finally {
            Storage::delete($path); // Clean up
        }

        /*$request->validate([
            'forfait' => 'required|string|max:255',
            'montant' => 'required|string|max:255',
            'wifi_id' => 'required|string|max:255',
            'description' => 'required|string|max:1025',
        ]);

        $request['slug'] = Str::slug(Str::random(10));
        Ticket::create($request->all());*/
        return redirect('ticket');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function show(Ticket  $ticket)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Ticket  $Ticket
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $tarif = Auth::user()->tarifs()->get();
        // IDOR Fix: Check allow based on user ownership (or if admin)
        $query = Ticket::where("slug", $slug);
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id()); // Assuming relation exists via owner but ticket has user_id directly? 
            // Checking Ticket model usage in other methods suggests direct relationship might need verification, 
            // but commonly tickets belong to users. Let's verify Model structure if possible.
            // Based on index(): $datas = Auth::user()->tickets()... implies user_id on tickets.
        }
        $data = $query->first();

        if($data){
            return view("admin.tarif-edit",compact("data","tarif"));
        }else{
            return view('admin.404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $query = Ticket::where('slug', $slug);
        if (!Auth::user()->isAdmin()) {
             // Assuming tickets are linked to users. The previous index code used Auth::user()->tickets()
             // effectively implying a relationship. However, to be purely safe let's rely on the relationship if we can,
             // or add the where clause if we are sure of the column 'user_id' or relation.
             // Looking at Index: Auth::user()->tickets()
             // So adding:
             $query->whereHas('owner', function($q) {
                 $q->where('id', Auth::id());
             }); 
             // OR simpler if logic allows:
             // $data = Auth::user()->tickets()->where('slug', $slug)->first();
             // Let's stick to the safer query builder approach compatible with both admin handling.
        }
        
        // Simpler IDOR fix consistent with index logic:
        if (Auth::user()->isAdmin()) {
             $data = Ticket::where('slug', $slug)->first();
        } else {
             $data = Auth::user()->tickets()->where('slug', $slug)->first();
        }

        if(!$data){
            return view("admin.404");
        }
        $request->validate([
            'forfait' => 'required|string|max:255',
            'montant' => 'required|string|max:255',
            'wifi_id' => 'required|string|max:255',
            'description' => 'required|string|max:1025',
        ]);
        $data->update($request->all());
        return redirect('tickets');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Ticket  $ticket
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if (Auth::user()->isAdmin()) {
             $data = Ticket::where('slug', $slug)->first();
        } else {
             $data = Auth::user()->tickets()->where('slug', $slug)->first();
        }

        if($data){
            $data->delete();
            return redirect("ticket");
        }else{
            return view('admin.404');
        }
    }

    /**
     * Remove multiple resources from storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function bulkDestroy(Request $request)
    {
        $request->validate([
            'ids' => 'required|array',
            'ids.*' => 'exists:tickets,slug',
        ]);

        $ids = $request->ids;

        if (Auth::user()->isAdmin()) {
            Ticket::whereIn('slug', $ids)->delete();
        } else {
             Auth::user()->tickets()->whereIn('slug', $ids)->delete();
        }

        return redirect()->back()->with('success', 'Tickets sélectionnés supprimés avec succès.');
    }

}
