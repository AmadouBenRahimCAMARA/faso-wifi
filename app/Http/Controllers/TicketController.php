<?php

namespace App\Http\Controllers;

use App\Models\Ticket;
use App\Models\Tarif;
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
    public function index()
    {
        if (Auth::user()->isAdmin()) {
            $datas = Ticket::with('owner')->latest()->paginate(10);
        } else {
            $datas = Auth::user()->tickets()->latest()->paginate(10);
        }
        return view("admin.ticket-liste",compact("datas"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (Auth::user()->isAdmin()) {
            $tarifs = Tarif::all(); // Admin can see all Tarifs
        } else {
            $tarifs = Auth::user()->tarifs()->get();
        }
        return view("admin.ticket-create", compact('tarifs'));
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


        $file = $request->file('fichier'); // Retrieve the uploaded file from the request
        $fileName = Str::random(10) . '.' . $request->fichier->getClientOriginalExtension();
        // Enregistrer l'image dans le dossier public/images
        $filePath = $request->fichier->move(public_path('files'), $fileName);
        Excel::import(new TicketsImport, $filePath);
        Storage::delete($filePath);

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
        $data = Ticket::where("slug", $slug)->first();
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
        $data = Ticket::where('slug', $slug)->first();
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
        $data = Ticket::where("slug", $slug)->first();

        if($data){
            $data->delete();
            return redirect("ticket");
        }else{
            return view('admin.404');
        }
    }

}
