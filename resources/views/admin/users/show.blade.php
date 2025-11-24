@extends('layouts.admin')

@section('content')
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid">
                <h3 class="text-dark mb-0">Détails Utilisateur</h3>
            </div>
        </nav>

        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-4">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">Informations Personnelles</h6>
                        </div>
                        <div class="card-body text-center">
                            <img class="img-profile rounded-circle mb-3" src="{{ asset('assets/img/avatars/avatar1.jpeg') }}" style="width: 100px; height: 100px;">
                            <h4>{{ $user->nom }} {{ $user->prenom }}</h4>
                            <p class="text-muted">{{ $user->email }}</p>
                            <hr>
                            <div class="text-start">
                                <p><strong>Téléphone:</strong> {{ $user->phone }}</p>
                                <p><strong>Pays:</strong> {{ $user->pays }}</p>
                                <p><strong>Inscrit le:</strong> {{ $user->created_at->format('d/m/Y H:i') }}</p>
                                <p><strong>Rôle:</strong> 
                                    @if($user->isAdmin())
                                        <span class="badge bg-danger">Admin</span>
                                    @else
                                        <span class="badge bg-secondary">Vendeur</span>
                                    @endif
                                </p>
                                <p><strong>Status:</strong> 
                                    @if($user->status == 'active')
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-danger">Bloqué</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow mb-4">
                        <div class="card-header py-3">
                            <h6 class="m-0 fw-bold text-primary">Statistiques</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-4 mb-4">
                                    <div class="card border-left-primary shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-primary text-uppercase mb-1">Points WiFi</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->wifis->count() }}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-wifi fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="card border-left-success shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-success text-uppercase mb-1">Tickets Vendus</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">{{ $user->tickets->count() }}</div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-receipt fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="col-md-4 mb-4">
                                    <div class="card border-left-info shadow h-100 py-2">
                                        <div class="card-body">
                                            <div class="row no-gutters align-items-center">
                                                <div class="col mr-2">
                                                    <div class="text-xs font-weight-bold text-info text-uppercase mb-1">Revenus Générés</div>
                                                    <div class="h5 mb-0 font-weight-bold text-gray-800">
                                                        {{ $user->tickets->sum(function($ticket) { return $ticket->tarif ? $ticket->tarif->montant : 0; }) }} FCFA
                                                    </div>
                                                </div>
                                                <div class="col-auto">
                                                    <i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <h5 class="mt-4">Derniers Paiements</h5>
                            <div class="table-responsive">
                                <table class="table table-bordered">
                                    <thead>
                                        <tr>
                                            <th>Date</th>
                                            <th>Montant</th>
                                            <th>Ticket</th>
                                            <th>Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @forelse($user->paiements()->latest()->take(5)->get() as $paiement)
                                        <tr>
                                            <td>{{ $paiement->created_at->format('d/m/Y') }}</td>
                                            <td>{{ $paiement->montant }} FCFA</td>
                                            <td>{{ $paiement->ticket ? $paiement->ticket->code : 'N/A' }}</td>
                                            <td><span class="badge bg-success">Payé</span></td>
                                        </tr>
                                        @empty
                                        <tr>
                                            <td colspan="4" class="text-center">Aucun paiement récent</td>
                                        </tr>
                                        @endforelse
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="d-flex justify-content-end mb-4">
                <a href="{{ route('admin.users') }}" class="btn btn-secondary me-2">Retour</a>
                <a href="{{ route('admin.users.edit', $user->id) }}" class="btn btn-primary">Modifier</a>
            </div>
        </div>
    </div>
</div>
@endsection
