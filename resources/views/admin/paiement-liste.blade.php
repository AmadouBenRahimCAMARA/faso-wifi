@extends('layouts.admin')

@section('content')
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop"
                    type="button"><i class="fas fa-bars"></i></button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search"
                      method="GET" action="{{ route('paiement.index') }}">
                    <input type="hidden" name="wifi_id" value="{{ $wifi_id }}">
                    <input type="hidden" name="tarif_id" value="{{ $tarif_id }}">
                    <input type="hidden" name="user_id" value="{{ $user_id }}">
                    <div class="input-group">
                        <input class="bg-light form-control border-0 small" type="text"
                            name="search" value="{{ $search }}"
                            placeholder="Rechercher par transaction, numéro ou moyen de paiement...">
                        <button class="btn btn-primary py-0" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </nav>
        <div class="container-fluid">
            <h3 class="text-dark mb-4">Paiements</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <p class="text-primary m-0 fw-bold">Historique des paiements</p>
                    </div>

                    {{-- Filtres avancés --}}
                    <form method="GET" action="{{ route('paiement.index') }}" id="advancedFilterForm" class="mt-3">
                        @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif

                        <div class="row g-2 align-items-end">
                            {{-- Filtre Zone WiFi --}}
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label small text-muted mb-1"><i class="fas fa-wifi me-1"></i>Zone WiFi</label>
                                <select name="wifi_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Toutes les zones</option>
                                    @foreach($wifis as $w)
                                        <option value="{{ $w->id }}" {{ $wifi_id == $w->id ? 'selected' : '' }}>{{ $w->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Filtre Tarif --}}
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label small text-muted mb-1"><i class="fas fa-tag me-1"></i>Tarif</label>
                                <select name="tarif_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Tous les tarifs</option>
                                    @foreach($tarifs as $t)
                                        <option value="{{ $t->id }}" {{ $tarif_id == $t->id ? 'selected' : '' }}>{{ $t->forfait }} — {{ $t->montant }} FCFA</option>
                                    @endforeach
                                </select>
                            </div>
                            {{-- Filtre Vendeur (admin uniquement) --}}
                            @if(Auth::user()->isAdmin())
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label small text-muted mb-1"><i class="fas fa-user me-1"></i>Vendeur</label>
                                <select name="user_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Tous les vendeurs</option>
                                    @foreach($users as $u)
                                        <option value="{{ $u->id }}" {{ $user_id == $u->id ? 'selected' : '' }}>{{ $u->nom }} {{ $u->prenom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            @endif
                            {{-- Bouton Réinitialiser --}}
                            <div class="col-12 col-sm-6 col-lg-auto">
                                @if($wifi_id || $tarif_id || $user_id || $search)
                                    <a href="{{ route('paiement.index') }}" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Barre de recherche mobile --}}
                    <form method="GET" action="{{ route('paiement.index') }}" class="d-sm-none mt-3">
                        @if($wifi_id)<input type="hidden" name="wifi_id" value="{{ $wifi_id }}">@endif
                        @if($tarif_id)<input type="hidden" name="tarif_id" value="{{ $tarif_id }}">@endif
                        @if($user_id)<input type="hidden" name="user_id" value="{{ $user_id }}">@endif
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="text" name="search" value="{{ $search }}"
                                placeholder="Transaction, numéro...">
                            <button class="btn btn-sm btn-primary" type="submit"><i class="fas fa-search"></i></button>
                        </div>
                    </form>
                </div>
                <div class="card-body">
                    <div class="table-responsive table mt-2" id="dataTable" role="grid"
                        aria-describedby="dataTable_info">
                        <table class="table my-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Transaction id</th>
                                    <th>Date</th>
                                    <th>Numéro</th>
                                    <th>Code acheter</th>
                                    <th>Moyen de paiement</th>
                                    <th>Montant</th>
                                    <th>Statut</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($datas as $idx => $values)
                                    <tr>
                                        <td><span class="small">{{ $values->transaction_id }}</span></td>
                                        <td>{{ date_format($values->created_at, 'd/m/Y H:i') }}</td>
                                        <td>{{ $values->numero }}</td>
                                        <td>
                                            @if($values->ticket)
                                                <span class="small">ID: {{ $values->ticket->user }}</span> <br>
                                                <span class="fw-bold">CODE: {{ $values->ticket->password }}</span>
                                            @else
                                                <span class="text-muted small">N/A</span>
                                            @endif
                                        </td>
                                        <td>{{ $values->moyen_de_paiement }}</td>
                                        <td class="fw-bold text-nowrap">
                                            {{ $values->ticket->tarif->montant ?? '0' }} FCFA
                                        </td>
                                        <td>
                                            @if($values->status == 'completed')
                                                <span class="badge bg-success">Payé</span>
                                            @elseif($values->status == 'failed')
                                                <span class="badge bg-danger">Échoué</span>
                                            @else
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isAdmin())
                                        <td>{{ $values->ticket->owner->nom ?? 'Admin' }}</td>
                                        @endif
                                        <td>
                                            <button type="button" class="btn btn-primary btn-sm"
                                                data-bs-target="#view{{ $values->slug }}" data-bs-toggle="modal">Voir</button>
                                        </td>
                                    </tr>
                            @endforeach
                            </tbody>

                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6 align-self-center">
                            <!--p id="dataTable_info" class="dataTables_info" role="status" aria-live="polite">Showing 1 to 10 of 27</p-->
                        </div>
                        <div class="col-md-6">
                            <nav class="d-lg-flex justify-content-lg-end dataTables_paginate paging_simple_numbers">
                                {{ $datas->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@foreach ($datas as $idx => $values)
<div class="modal fade" id="view{{ $values->slug }}" tabindex="-1"
    aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5" id="exampleModalLabel">
                    Informations
                </h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"
                    aria-label="Close"></button>
            </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <span class="text-muted small">Transaction ID :</span><br>
                        <span class="fw-bold">{{ $values->transaction_id }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Date :</span><br>
                        <span class="fw-bold">{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Numéro Client :</span><br>
                        <span class="fw-bold">{{ $values->numero }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Moyen de paiement :</span><br>
                        <span class="fw-bold">{{ $values->moyen_de_paiement }}</span>
                    </div>
                    <div class="mb-2">
                        <span class="text-muted small">Montant :</span><br>
                        <span class="fw-bold text-success">{{ $values->ticket->tarif->montant ?? '0' }} FCFA</span>
                    </div>
                    <hr>
                    <div class="bg-light p-2 rounded">
                        <span class="text-muted small text-uppercase">Tickets Credentials :</span><br>
                        @if($values->ticket)
                            <div class="ms-2">
                                <span>ID: <span class="fw-bold text-primary">{{ $values->ticket->user }}</span></span><br>
                                <span>CODE: <span class="fw-bold text-primary">{{ $values->ticket->password }}</span></span>
                            </div>
                        @else
                            <span class="text-danger small">Ticket introuvable</span>
                        @endif
                    </div>
                </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
</div>
@endforeach

@endsection
