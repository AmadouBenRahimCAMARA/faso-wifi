@extends('layouts.admin')
@section('content')
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3"
                    id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search">
                    <div class="input-group"><input class="bg-light form-control border-0 small" type="text"
                            placeholder="Recherche..."><button class="btn btn-primary py-0" type="button"><i
                                class="fas fa-search"></i></button></div>
                </form>
                <ul class="navbar-nav flex-nowrap ms-auto">
                    <li class="nav-item dropdown d-sm-none no-arrow"><a class="dropdown-toggle nav-link"
                            aria-expanded="false" data-bs-toggle="dropdown" href="#"><i
                                class="fas fa-search"></i></a>
                        <div class="dropdown-menu dropdown-menu-end p-3 animated--grow-in"
                            aria-labelledby="searchDropdown">
                            <form class="me-auto navbar-search w-100">
                                <div class="input-group"><input class="bg-light form-control border-0 small"
                                        type="text" placeholder="Recherche...">
                                    <div class="input-group-append"><button class="btn btn-primary py-0"
                                            type="button"><i class="fas fa-search"></i></button></div>
                                </div>
                            </form>
                        </div>
                    </li>

                    <div class="d-none d-sm-block topbar-divider"></div>
                    <li class="nav-item dropdown no-arrow">
                        <div class="nav-item dropdown no-arrow"><a class="dropdown-toggle nav-link"
                                aria-expanded="false" data-bs-toggle="dropdown" href="#"></a>
                            <div class="dropdown-menu shadow dropdown-menu-end animated--grow-in"><a
                                    class="dropdown-item" href="#"><i
                                        class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Profile</a><a
                                    class="dropdown-item" href="#"><i
                                        class="fas fa-cogs fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Settings</a><a
                                    class="dropdown-item" href="#"><i
                                        class="fas fa-list fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Activity
                                    log</a>
                                <div class="dropdown-divider"></div><a class="dropdown-item" href="#"><i
                                        class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i>&nbsp;Logout</a>
                            </div>
                        </div>
                    </li>
                </ul>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="d-sm-flex justify-content-between align-items-center mb-4">
                <h3 class="text-dark mb-0">Dashboard</h3>
            </div>
            <div class="row">
                @if(!Auth::user()->isAdmin())
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Solde disponible</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$datas["solde_total"]}} FCFA</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
                @endif
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Recette du jour
                                            (annual)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$datas["solde_du_jour"]}} FCFA</span></div>
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
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$datas["ticket_du_jour_vendu"]}}</span></div>
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
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{$datas["ticket_total_vendu"]}}</span></div>
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
                    <p class="text-primary m-0 fw-bold">Vente du jour</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-nowrap">

                            <!--
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label
                                    class="form-label">Voir&nbsp;<select id="view"
                                        class="d-inline-block form-select form-select-sm" onchange="get()">
                                        <option value="10" selected={{ session()->get('view') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" selected={{ session()->get('view') == '25' ? 'selected' : '' }}>25 </option>
                                        <option value="50" selected={{ session()->get('view') == '50' ? 'selected' : '' }}>50 </option>
                                        <option value="100" selected={{ session()->get('view') == '100' ? 'selected' : '' }}>100 </option>

                                    </select>&nbsp;</label></div>
                            -->
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
                                    <th>Numéro</th>
                                    <th>Code acheter</th>
                                    <th>Moyen de paiement</th>
                                    <th>Montant</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            <tbody>
                            @foreach ($paiements as $idx => $values)
                                    <tr>
                                        <td>{{ $values->transaction_id }}</td>
                                        <td>{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</td>
                                        <td>{{ $values->numero }}</th>
                                        <td>
                                            <span>ID: {{ App\Models\Ticket::find($values->ticket_id)->user }}</span> <br>
                                            <span>CODE: {{ App\Models\Ticket::find($values->ticket_id)->password }}</span>
                                        </td>
                                        <td>{{ $values->moyen_de_paiement }}</td>
                                        <td>{{ App\Models\Ticket::find($values->ticket_id)->tarif->montant . ' FCFA' }}</td>
                                        <td>
                                            <a href="" class="btn btn-primary"
                                                data-bs-target="#view{{ $values->slug }}" data-bs-toggle="modal">Voir</a>
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
                                {{ $paiements->links() }}
                            </nav>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
@foreach ($paiements as $idx => $values)
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
                    <span class="fw-bold">{{ $values->numero }}</span>
                </div>
                <div>
                    <span>Moyen de paiement : </span>
                    <span
                        class="fw-bold">{{ $values->moyen_de_paiement }}</span>
                </div>
                <div>
                    <span>Montant : </span>
                    <span
                        class="fw-bold">{{ App\Models\Ticket::find($values->ticket_id)->tarif->montant . ' FCFA' }}</span>
                </div>
                <div>
                    <span>Code acheter : </span>
                    <div class="fw-bold ps-4">
                        <span>ID:
                            {{ App\Models\Ticket::find($values->ticket_id)->user }}</span>
                        <br>
                        <span>CODE:
                            {{ App\Models\Ticket::find($values->ticket_id)->password }}</span>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    data-bs-dismiss="modal">Fermer</button>
            </div>
        </div>
    </div>
</div>
@endforeach

@endsection
