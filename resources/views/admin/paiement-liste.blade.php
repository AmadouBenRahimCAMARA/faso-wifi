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
            <h3 class="text-dark mb-4">Paiements</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <p class="text-primary m-0 fw-bold">Historique des paiements</p>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6 text-nowrap">
                            
                            <div id="dataTable_length" class="dataTables_length" aria-controls="dataTable"><label
                                    class="form-label">Voir&nbsp;<select id="view"
                                        class="d-inline-block form-select form-select-sm" onchange="get()">
                                        <option value="10" selected={{ session()->get('view') == '10' ? 'selected' : '' }}>10</option>
                                        <option value="25" selected={{ session()->get('view') == '25' ? 'selected' : '' }}>25 </option>
                                        <option value="50" selected={{ session()->get('view') == '50' ? 'selected' : '' }}>50 </option>
                                        <option value="100" selected={{ session()->get('view') == '100' ? 'selected' : '' }}>100 </option>

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
                                            @if($values->status == 'completed')
                                                <span class="badge bg-success">Payé</span>
                                            @elseif($values->status == 'failed')
                                                <span class="badge bg-danger">Échoué</span>
                                            @else
                                                <span class="badge bg-warning text-dark">En attente</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isAdmin())
                                        <th>{{ $values->ticket->owner->nom }} {{ $values->ticket->owner->prenom }}</th>
                                        @endif
                                        <td class="d-flex justify-content-start align-items-center">
                                            <a href="" class="btn btn-primary btn-fixed-width me-1 mb-1"
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
