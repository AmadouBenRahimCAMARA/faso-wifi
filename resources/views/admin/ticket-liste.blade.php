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
            <h3 class="text-dark mb-4">Tickets</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex">
                        <p class="text-primary m-0 fw-bold me-auto">Liste des tickets</p>
                         <button type="button" class="btn btn-danger me-2 d-none" id="bulk-delete-btn" data-bs-toggle="modal"
                                data-bs-target="#bulkDeleteModal">
                                Supprimer la sélection
                        </button>
                        <a href="{{ route('ticket.create') }}" class="btn btn-primary">Ajouter</a>
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
                                    <th>
                                        <input type="checkbox" id="check-all" class="form-check-input">
                                    </th>
                                    <th>#</th>
                                    <th>Wifi</th>
                                    <th>Forfait</th>
                                    <th>Montant</th>
                                    <th>identifient</th>
                                    <th>Mot de passe</th>
                                    <th>Durée</th>
                                    <th>Etat</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>

                            @foreach ($datas as $idx => $values)
                                <tbody>
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input ticket-checkbox" name="ids[]"
                                                value="{{ $values->slug }}">
                                        </td>
                                        <th>{{ $idx + 1 }}</th>
                                        <th>{{ App\Models\Tarif::find($values->tarif_id)->wifi()->first()->nom }}</th>
                                        <th>{{ App\Models\Tarif::find($values->tarif_id)->forfait }}</th>
                                        <th>{{ App\Models\Tarif::find($values->tarif_id)->montant ." FCFA"}}</th>
                                        <th>{{ $values->user }}</th>
                                        <th>{{ $values->password}}</th>
                                        <th>{{ $values->dure }}</th>
                                        <th>{{ $values->etat_ticket ==="EN_VENTE" ? "En vente" : "Vendu" }}</th>
                                        @if(Auth::user()->isAdmin())
                                        <th>{{ $values->owner->nom }} {{ $values->owner->prenom }}</th>
                                        @endif
                                        <!--th>{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</th-->
                                        <th class="d-flex justify-content-start align-items-center">
                                            <button type="button" class="btn btn-primary btn-fixed-width me-1 mb-1" data-bs-target="#view{{$values->slug}}"
                                                data-bs-toggle="modal">Voir</button>
                                            <!--a href="{{ route('tarifs.edit', $values->slug) }}"
                                                class="btn btn-warning btn-fixed-width me-1 mb-1">Editer</a-->

                                            <button type="button" class="btn btn-danger btn-fixed-width me-1 mb-1" data-bs-target="#delete{{$values->slug}}"
                                                data-bs-toggle="modal">Supprimer</button>
                                        </th>

                                            <div class="modal fade" id="delete{{$values->slug}}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">Suppression
                                                                des données
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            Voulez-vous vraiment supprimer les données ?
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">Annuler</button>
                                                            <form class="d-inline-block"
                                                                action="{{ route('ticket.destroy', $values->slug) }}"
                                                                method="POST">
                                                                @csrf
                                                                <button type="submit" class="btn btn-danger">Continuer
                                                                </button>
                                                                @method('delete')
                                                            </form>

                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal fade" id="view{{$values->slug}}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-white text-dark">
                                                        <div class="modal-header">
                                                            <h1 class="modal-title fs-5" id="exampleModalLabel">
                                                                Informations
                                                            </h1>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                                aria-label="Close"></button>
                                                        </div>
                                                        <div class="modal-body">
                                                            <div>
                                                                <span>Wifi : </span>
                                                                <span class="fw-bold">{{ App\Models\Tarif::find($values->tarif_id)->wifi()->first()->nom }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Forfait : </span>
                                                                <span class="fw-bold">{{ App\Models\Tarif::find($values->tarif_id)->forfait }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Montant : </span>
                                                                <span class="fw-bold">{{ App\Models\Tarif::find($values->tarif_id)->montant ." FCFA"}}</span>
                                                            </div>
                                                            <div>
                                                                <span>identifient : </span>
                                                                <span class="fw-bold">{{$values->user}}</span>
                                                            </div>
                                                            <div>
                                                                <span>Mot de passe : </span>
                                                                <span class="fw-bold">{{$values->password}}</span>
                                                            </div>
                                                            <div>
                                                                <span>Etat du ticket : </span>
                                                                <span class="fw-bold">{{$values->etat_ticket}}</span>
                                                            </div>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-primary"
                                                                data-bs-dismiss="modal">Fermer</button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </th>
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
    </div>

    <!-- Bulk Delete Confirmation Modal -->
    <div class="modal fade" id="bulkDeleteModal" tabindex="-1" aria-labelledby="bulkDeleteModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="bulkDeleteModalLabel">Suppression groupée</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Voulez-vous vraiment supprimer les tickets sélectionnés ?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Annuler</button>
                    <form id="bulk-delete-form" action="{{ route('ticket.bulkDestroy') }}" method="POST">
                        @csrf
                        <div id="bulk-delete-inputs"></div>
                        <button type="submit" class="btn btn-danger">Supprimer</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const checkAll = document.getElementById('check-all');
            const checkboxes = document.querySelectorAll('.ticket-checkbox');
            const bulkDeleteBtn = document.getElementById('bulk-delete-btn');
            const bulkDeleteFormInputs = document.getElementById('bulk-delete-inputs');

            function updateBulkDeleteBtn() {
                const checkedCount = document.querySelectorAll('.ticket-checkbox:checked').length;
                if (checkedCount > 0) {
                    bulkDeleteBtn.classList.remove('d-none');
                } else {
                    bulkDeleteBtn.classList.add('d-none');
                }
            }

            checkAll.addEventListener('change', function() {
                checkboxes.forEach(cb => cb.checked = checkAll.checked);
                updateBulkDeleteBtn();
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', function() {
                    updateBulkDeleteBtn();
                    // If one is unchecked, uncheck "Select All"
                    if (!this.checked) {
                        checkAll.checked = false;
                    }
                });
            });

            // Populate form on modal open
            const bulkDeleteModal = document.getElementById('bulkDeleteModal');
            bulkDeleteModal.addEventListener('show.bs.modal', function() {
                bulkDeleteFormInputs.innerHTML = ''; // Clear previous
                checkboxes.forEach(cb => {
                    if (cb.checked) {
                        const input = document.createElement('input');
                        input.type = 'hidden';
                        input.name = 'ids[]';
                        input.value = cb.value;
                        bulkDeleteFormInputs.appendChild(input);
                    }
                });
            });
        });
    </script>
@endsection