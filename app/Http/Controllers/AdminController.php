<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Ticket;
use App\Models\Paiement;
use App\Models\Wifi;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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
            ->sum(DB::raw('CAST(tarifs.montant AS DECIMAL)'));

        $totalTicketsSold = Ticket::where('etat_ticket', 'VENDU')->count();
        $totalUsers = User::count();
        $totalWifis = Wifi::count();

        $recentPayments = Paiement::with(['ticket.tarif', 'ticket.owner'])
            ->latest()
            ->take(10)
            ->get();
        
        // Count pending withdrawals
        $pendingRetraitsCount = 0;
        try {
            $pendingRetraitsCount = \App\Models\Retrait::where('statut', 'EN_ATTENTE')->count();
        } catch (\Exception $e) {}

        return view('admin.super_dashboard', compact('totalRevenue', 'totalTicketsSold', 'totalUsers', 'totalWifis', 'recentPayments', 'pendingRetraitsCount'));
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
            'password' => 'nullable|string|min:8',
        ]);

        $user->nom = $request->nom;
        $user->prenom = $request->prenom;
        $user->phone = $request->phone;
        $user->email = $request->email;
        
        // Prevent self-demotion or self-banning via role change (though role doesn't affect status directly here)
        if ($user->id !== Auth::id()) {
            if ($request->role === 'admin') {
                $user->is_admin = true;
            } else {
                $user->is_admin = false;
            }
        }

        if ($request->filled('password')) {
            $user->password = bcrypt($request->password);
        }

        $user->save();

        return redirect()->route('admin.users')->with('success', 'Utilisateur mis à jour avec succès');
    }

    public function toggleUserStatus($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent blocking self
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas modifier votre propre statut.');
        }

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

    public function impersonate($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent impersonating self or other admins (optional)
        if ($user->id === Auth::id()) {
            return redirect()->back()->with('error', 'Vous ne pouvez pas vous usurper vous-même.');
        }

        // Store original admin id
        session()->put('impersonator_id', Auth::id());
        
        // Log in as the user
        Auth::loginUsingId($id);

        return redirect()->route('home')->with('success', "Vous êtes connecté en tant que " . $user->nom);
    }

    public function stopImpersonate()
    {
        if (session()->has('impersonator_id')) {
            // Log back in as admin
            Auth::loginUsingId(session('impersonator_id'));
            session()->forget('impersonator_id');
            return redirect()->route('admin.users')->with('success', 'Restauration de la session administrateur.');
        }
        return redirect()->route('home');
    }
}
