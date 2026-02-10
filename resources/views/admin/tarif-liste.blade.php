@extends('layouts.admin')

@section('content')
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop"
                    type="button"><i class="fas fa-bars"></i></button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search"
                      method="GET" action="{{ route('tarifs.index') }}">
                    @if($wifi_id)<input type="hidden" name="wifi_id" value="{{ $wifi_id }}">@endif
                    <div class="input-group">
                        <input class="bg-light form-control border-0 small" type="text"
                            name="search" value="{{ $search }}"
                            placeholder="Rechercher par forfait, montant ou description...">
                        <button class="btn btn-primary py-0" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </nav>
        <div class="container-fluid">
            <h3 class="text-dark mb-4">Tarifs</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-primary m-0 fw-bold">Liste des tarifs</p>
                        <a href="{{ route('tarifs.create') }}" class="btn btn-primary">
                            <i class="fas fa-plus d-md-none"></i>
                            <span class="d-none d-md-inline">Ajouter</span>
                        </a>
                    </div>

                    <!-- Filtre Zone WiFi -->
                    <form method="GET" action="{{ route('tarifs.index') }}" id="tarifFilterForm">
                        @if($search)<input type="hidden" name="search" value="{{ $search }}">@endif
                        <div class="row g-2 align-items-end">
                            <div class="col-12 col-sm-6 col-lg-3">
                                <label class="form-label small text-muted mb-1"><i class="fas fa-wifi me-1"></i>Zone WiFi</label>
                                <select name="wifi_id" class="form-select form-select-sm" onchange="this.form.submit()">
                                    <option value="">Toutes les zones</option>
                                    @foreach($wifis as $w)
                                        <option value="{{ $w->id }}" {{ $wifi_id == $w->id ? 'selected' : '' }}>{{ $w->nom }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-12 col-sm-6 col-lg-auto">
                                @if($wifi_id || $search)
                                    <a href="{{ route('tarifs.index') }}" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Barre de recherche mobile --}}
                    <form method="GET" action="{{ route('tarifs.index') }}" class="d-sm-none mt-3">
                        @if($wifi_id)<input type="hidden" name="wifi_id" value="{{ $wifi_id }}">@endif
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="text" name="search" value="{{ $search }}"
                                placeholder="Forfait, montant ou description...">
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
                                    <th>#</th>
                                    <th>Wifi</th>
                                    <th>Forfait</th>
                                    <th>Montant</th>
                                    <th>Description</th>
                                    <th>Date d'ajout</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($datas as $idx => $values)
                                    <tr>
                                        <td>{{ $datas->firstItem() + $idx }}</td>
                                        <td>{{ $values->wifi->nom ?? '—' }}</td>
                                        <td>{{ $values->forfait }}</td>
                                        <td>{{ $values->montant . ' FCFA' }}</td>
                                        <td>{{ $values->description }}</td>
                                        <td>{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</td>
                                        @if(Auth::user()->isAdmin())
                                        <td>{{ $values->user->nom ?? '' }} {{ $values->user->prenom ?? '' }}</td>
                                        @endif
                                        <td>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-target="#view{{$values->slug}}"
                                                    data-bs-toggle="modal">Voir</button>
                                                <a href="{{ route('tarifs.edit', $values->slug) }}"
                                                    class="btn btn-sm btn-warning me-1">Editer</a>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-target="#delete{{$values->slug}}"
                                                    data-bs-toggle="modal">Suppr.</button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Suppression --}}
                                    <div class="modal fade" id="delete{{$values->slug}}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-white text-dark">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Suppression des données</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Voulez-vous vraiment supprimer les données ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                                    <form class="d-inline-block" action="{{ route('tarifs.destroy', $values->slug) }}" method="POST">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="btn btn-danger">Continuer</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Modal Voir --}}
                                    <div class="modal fade" id="view{{$values->slug}}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content bg-white text-dark">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Informations</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div><span>Wifi : </span><span class="fw-bold">{{ $values->wifi->nom ?? '—' }}</span></div>
                                                    <div><span>Forfait : </span><span class="fw-bold">{{ $values->forfait }}</span></div>
                                                    <div><span>Montant : </span><span class="fw-bold">{{ $values->montant }} FCFA</span></div>
                                                    <div><span>Description : </span><span class="fw-bold">{{ $values->description }}</span></div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Fermer</button>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-6 align-self-center">
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
@endsection
