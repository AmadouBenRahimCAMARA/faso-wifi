<?php

namespace App\Http\Controllers;

use App\Models\Wifi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class WifiController extends Controller
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
        $search = $request->get('search');
        $user_id = $request->get('user_id');

        if ($isAdmin) {
            $query = Wifi::with('user');
        } else {
            $query = Auth::user()->wifis();
        }

        if ($isAdmin && $user_id) {
            $query->where('user_id', $user_id);
        }

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->where('nom', 'LIKE', '%' . $search . '%')
                  ->orWhere('description', 'LIKE', '%' . $search . '%');
            });
        }

        $queryParams = $request->only(['search', 'user_id']);
        $datas = $query->latest()->paginate(10)->appends($queryParams);

        if ($isAdmin) {
            $users = \App\Models\User::where('is_admin', false)->orderBy('nom')->get();
        } else {
            $users = collect();
        }

        return view("admin.wifi-liste", compact("datas", "search", "users", "user_id"));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("admin.wifi-create");
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
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:1025',
        ]);
        $request['slug'] = Str::slug(Str::random(10));
        $request['user_id'] = Auth::user()->id;
        $wifi = wifi::create($request->all());

        return redirect('wifi');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Wifi  $wifi
     * @return \Illuminate\Http\Response
     */
    public function show(Wifi $wifi)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Wifi  $wifi
     * @return \Illuminate\Http\Response
     */
    public function edit($slug)
    {
        $query = Wifi::where("slug", $slug);
        if (!Auth::user()->isAdmin()) {
            $query->where('user_id', Auth::id());
        }
        $data = $query->first();

        if($data){
            return view("admin.wifi-edit",compact("data"));
        }else{
            return view('admin.404');
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Wifi  $wifi
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $slug)
    {
        if (Auth::user()->isAdmin()) {
             $data = Wifi::where('slug', $slug)->first();
        } else {
             $data = Auth::user()->wifis()->where('slug', $slug)->first();
        }

        if(!$data){
            return view("admin.404");
        }
        $request->validate([
            'nom' => 'required|string|max:255',
            'description' => 'required|string|max:1025',
        ]);
        $data->update($request->all());
        return redirect('wifi');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Wifi  $wifi
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug)
    {
        if (Auth::user()->isAdmin()) {
             $data = Wifi::where('slug', $slug)->first();
        } else {
             $data = Auth::user()->wifis()->where('slug', $slug)->first();
        }

        if($data){
            $data->delete();
            return redirect("wifi");
        }else{
            return view('admin.404');
        }
    }
}
