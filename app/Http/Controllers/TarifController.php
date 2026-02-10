<?php

namespace App\Http\Controllers;

use App\Models\Tarif;
use App\Models\Wifi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class TarifController extends Controller
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
        $isAdmin = Auth::user()->isAdmin();
        $wifi_id = $request->get('wifi_id');
        $search = $request->get('search');

        if ($isAdmin) {
            $query = Tarif::with(['user', 'wifi']);
        } else {
            $query = Auth::user()->tarifs()->with('wifi');
        }

        // Filtre par zone WiFi
        if ($wifi_id) {
            $query->where('wifi_id', $wifi_id);
        }

        // Recherche par forfait ou description
        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('forfait', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%')
                  ->orWhere('montant', 'LIKE', '%' . $search . '%');
            });
        }

        $queryParams = $request->only(['wifi_id', 'search']);
        $datas = $query->latest()->paginate(10)->appends($queryParams);

        // Données pour le select WiFi
        if ($isAdmin) {
            $wifis = Wifi::orderBy('nom')->get();
        } else {
            $wifis = Auth::user()->wifis()->orderBy('nom')->get();
        }

        return view("admin.tarif-liste", compact("datas", "wifis", "wifi_id", "search"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->isAdmin()) {
            $wifi = Wifi::all(); // Admin can see all Wifis
        } else {
            $wifi = Auth::user()->wifis()->get();
        }
        return view("admin.tarif-create", compact('wifi'));
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
            'forfait' => 'required|string|max:255',
            'montant' => 'required|string|max:255',
            'wifi_id' => 'required|string|max:255',
            'description' => 'required|string|max:1025',
        ]);

        $request['slug'] = Str::slug(Str::random(10));
        $request['user_id'] = Auth::user()->id;
        Tarif::create($request->all());
        return redirect('tarifs');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function show(Tarif  $tarif)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $wifi = Auth::user()->wifis()->get();
        $data = Tarif::where("slug", $slug)->first();
        if($data){
            return view("admin.tarif-edit",compact("data","wifi"));
        }else{
            return view('admin.404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        $data = Tarif::where('slug', $slug)->first();
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
        return redirect('tarifs');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Tarif  $tarif
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        $data = Tarif::where("slug", $slug)->first();
        if($data){
            $data->delete();
            return redirect("tarifs");
        }else{
            return view('admin.404');
        }
    }
}
