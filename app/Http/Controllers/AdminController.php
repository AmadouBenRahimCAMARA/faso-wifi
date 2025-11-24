<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Paiement;
use App\Models\Wifi;
use Illuminate\Support\Facades\Auth;

class AdminController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'admin']);
    }

    public function dashboard()
    {
        // Global Statistics for Super Admin
        $totalRevenue = Paiement::join('tickets', 'paiements.ticket_id', '=', 'tickets.id')
            ->join('tarifs', 'tickets.tarif_id', '=', 'tarifs.id')
            ->sum('tarifs.montant');

        $totalTicketsSold = Ticket::where('etat_ticket', 'VENDU')->count();
        $totalUsers = User::count();
        $totalWifis = Wifi::count();

        $recentPayments = Paiement::with(['ticket.tarif', 'ticket.user'])
            ->latest()
            ->take(10)
            ->get();

        return view('admin.super_dashboard', compact('totalRevenue', 'totalTicketsSold', 'totalUsers', 'totalWifis', 'recentPayments'));
    }

    public function users()
    {
        $users = User::withCount(['wifis', 'tickets'])->latest()->paginate(15);
        return view('admin.users.index', compact('users'));
    }

    public function show($id)
    {
        $user = User::with(['wifis', 'tickets', 'paiements'])->findOrFail($id);
        return view('admin.users.show', compact('user'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $request->validate([
            'nom' => 'required|string|max:255',
            'prenom' => 'required|string|max:255',
            'phone' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,'.$user->id,
            'role' => 'required|in:admin,user',
        ]);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->phone = $request->phone;
        $user->email = $request->email;
        
        if ($request->role === 'admin') {
            $user->is_admin = true;
        } else {
            $user->is_admin = false;
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        if ($user->status === 'banned') {
            $user->status = 'active';
            $message = 'Utilisateur débloqué avec succès';
        } else {
            $user->status = 'banned';
            $message = 'Utilisateur bloqué avec succès';
        }
        
        $user->save();

        return redirect()->back()->with('success', $message);
    }
}
