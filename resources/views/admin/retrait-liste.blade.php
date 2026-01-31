@extends('layouts.admin')

@section('content')

    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop"
                    type="button"><i class="fas fa-bars"></i></button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group"><input class="bg-light form-control border-0 small" type="text"
                            placeholder="Rechercher ..."><button class="btn btn-primary py-0" type="button"><i
                                class="fas fa-search"></i></button></div>
                </form>
            </div>
        </nav>
        <div class="container-fluid">
            <h3 class="text-dark mb-4">Retrait</h3>
            <div class="row">
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Solde disponible</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$compte["solde_total"]}} FCFA</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Retrait disponible</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$compte["retrait_total"]}} FCFA</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Toutes vos Recettes
                                            </span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>0 FCFA</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div-->
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Nombre de ticket du jour vendu
                                            </span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$compte["ticket_du_jour_vendu"]}}</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Nombre de ticket total vendu
                                            </span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$compte["ticket_total_vendu"]}}</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex">
                        <p class="text-primary m-0 fw-bold me-auto">Liste des retraits</p>
                        @if(!Auth::user()->isAdmin())
                        <a href="{{ route('retrait.create') }}" class="btn btn-primary">Demander un retrait</a>
                        @endif
                    </div>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-nowrap">
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label
                                    class="form-label">Voir&nbsp;<select class="d-inline-block form-select form-select-sm">
                                        <option value="10" selected="">10</option>
                                        <option value="25">25</option>
                                        <option value="50">50</option>
                                        <option value="100">100</option>
                                    </select>&nbsp;</label></div>
                        </div>
                        <!--div class="col-md-6">
                                        <div class="text-md-end dataTables_filter" id="dataTable_filter"><label class="form-label"><input type="search" class="form-control form-control-sm" aria-controls="dataTable" placeholder="Search"></label></div>
                                    </div-->
                    </div>
                    <div class="table-responsive table mt-2" id="dataTable" role="grid"
                        aria-describedby="dataTable_info">
                        <table class="table my-0" id="dataTable">
                            <thead>
                                <tr>
                                    <th>Transaction id</th>
                                    <th>Date</th>
                                    <th>Numéro de paiement</th>
                                    <th>Moyen de paiement</th>
                                    <th>Montant</th>
                                    <th>Status</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            @foreach ($datas as $idx => $values)
                                <tbody>
                                    <tr>
                                        <td>{{ $values->transaction_id }}</td>
                                        <td>{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</td>
                                        <td>{{ $values->numero_paiement }}</th>
                                            <td>{{ $values->moyen_de_paiement }}</th>

                                        <td>{{ $values->montant." FCFA" }}</td>
                                        <td>{{ $values->statut  }}</td>
                                        @if(Auth::user()->isAdmin())
                                        <th>{{ $values->user->nom }} {{ $values->user->prenom }}</th>
                                        @endif
                                        <td class="d-flex justify-content-start align-items-center">
                                            <!--a href="" class="btn btn-primary btn-fixed-width me-1 mb-1"
                                                data-bs-target="#view{{ $values->slug }}" data-bs-toggle="modal">Voir</a-->

                                            @if(Auth::user()->isAdmin() && $values->statut == 'EN_ATTENTE')
                                                <!-- Validation Forms -->
                                                <form action="{{ route('retrait.update', $values->id) }}" method="POST" class="me-1">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="statut" value="PAYE">
                                                    <button type="submit" class="btn btn-success btn-sm" onclick="return confirm('Confirmez-vous le paiement de ce retrait ? Cela déduira le montant du solde du vendeur.')">Valider</button>
                                                </form>
                                                
                                                <form action="{{ route('retrait.update', $values->id) }}" method="POST">
                                                    @csrf
                                                    @method('PUT')
                                                    <input type="hidden" name="statut" value="REJETE">
                                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Voulez-vous rejeter ce retrait ?')">Rejeter</button>
                                                </form>
                                            @elseif(!Auth::user()->isAdmin() && $values->statut == 'EN_ATTENTE')
                                                 <span class="badge bg-warning text-dark">En attente</span>
                                            @elseif($values->statut == 'PAYE')
                                                 <span class="badge bg-success">Payé</span>
                                            @elseif($values->statut == 'REJETE')
                                                 <span class="badge bg-danger">Rejeté</span>
                                            @endif
                                        </td>
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
                                                            <div>
                                                                <span>Transaction id : </span>
                                                                <span class="fw-bold">{{ $values->transaction_id }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Date : </span>
                                                                <span
                                                                    class="fw-bold">{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Numéro : </span>
                                                                <span class="fw-bold">{{ $values->numero_paiement }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Moyen de paiement : </span>
                                                                <span
                                                                    class="fw-bold">{{ $values->moyen_de_paiement }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Montant : </span>
                                                                <span
                                                                    class="fw-bold">{{$values->montant." FCFA"}}</span>
                                                            </div>
                                                            <div>
                                                                <span>Statut : </span>
                                                                <span
                                                                    class="fw-bold">{{$values->statut}}</span>
                                                            </div>

                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tbody>
                            @endforeach

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
@endsection
