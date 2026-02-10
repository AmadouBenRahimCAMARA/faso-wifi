@extends('layouts.admin')

@section('content')
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid"><button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop"
                    type="button"><i class="fas fa-bars"></i></button>
                <form class="d-none d-sm-inline-block me-auto ms-md-3 my-2 my-md-0 mw-100 navbar-search"
                      method="GET" action="{{ route('ticket.index') }}">
                    {{-- Préserver les filtres actifs dans la recherche --}}
                    <input type="hidden" name="filter" value="{{ $filter }}">
                    @if($wifi_id)<input type="hidden" name="wifi_id" value="{{ $wifi_id }}">@endif
                    @if($tarif_id)<input type="hidden" name="tarif_id" value="{{ $tarif_id }}">@endif
                    @if($user_id)<input type="hidden" name="user_id" value="{{ $user_id }}">@endif
                    <div class="input-group">
                        <input class="bg-light form-control border-0 small" type="text"
                            name="search" value="{{ $search }}"
                            placeholder="Rechercher par identifiant ou mot de passe...">
                        <button class="btn btn-primary py-0" type="submit"><i class="fas fa-search"></i></button>
                    </div>
                </form>
            </div>
        </nav>
        <div class="container-fluid">
            <h3 class="text-dark mb-4">Tickets</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <!-- Titre et bouton Ajouter -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <p class="text-primary m-0 fw-bold">Liste des tickets</p>
                        <div>
                            <button type="button" class="btn btn-danger me-2 d-none" id="bulk-delete-btn" data-bs-toggle="modal"
                                    data-bs-target="#bulkDeleteModal">
                                    <i class="fas fa-trash d-md-none"></i>
                                    <span class="d-none d-md-inline">Supprimer la sélection</span>
                            </button>
                            <a href="{{ route('ticket.create') }}" class="btn btn-primary">
                                <i class="fas fa-plus d-md-none"></i>
                                <span class="d-none d-md-inline">Ajouter</span>
                            </a>
                        </div>
                    </div>
                    
                    <!-- Filtres d'état - Responsive Grid -->
                    <div class="row g-2">
                        <div class="col-6 col-md-auto">
                            <a href="{{ route('ticket.index', array_merge(request()->only(['wifi_id','tarif_id','user_id','search']), ['filter' => 'en_vente'])) }}" 
                               class="btn w-100 {{ $filter === 'en_vente' ? 'btn-success' : 'btn-outline-success' }}">
                                <i class="fas fa-wifi me-1"></i>
                                <span class="d-sm-none">Vente</span>
                                <span class="d-none d-sm-inline">En vente</span>
                                <span class="badge bg-light text-dark ms-1">{{ $counts['en_vente'] }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-auto">
                            <a href="{{ route('ticket.index', array_merge(request()->only(['wifi_id','tarif_id','user_id','search']), ['filter' => 'en_cours'])) }}" 
                               class="btn w-100 {{ $filter === 'en_cours' ? 'btn-warning' : 'btn-outline-warning' }}">
                                <i class="fas fa-hourglass-half me-1"></i>
                                <span class="d-sm-none">Cours</span>
                                <span class="d-none d-sm-inline">En cours</span>
                                <span class="badge bg-light text-dark ms-1">{{ $counts['en_cours'] }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-auto">
                            <a href="{{ route('ticket.index', array_merge(request()->only(['wifi_id','tarif_id','user_id','search']), ['filter' => 'vendu'])) }}" 
                               class="btn w-100 {{ $filter === 'vendu' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                                <i class="fas fa-check me-1"></i>
                                <span class="d-sm-none">Vendu</span>
                                <span class="d-none d-sm-inline">Vendus</span>
                                <span class="badge bg-light text-dark ms-1">{{ $counts['vendu'] }}</span>
                            </a>
                        </div>
                        <div class="col-6 col-md-auto">
                            <a href="{{ route('ticket.index', array_merge(request()->only(['wifi_id','tarif_id','user_id','search']), ['filter' => 'tous'])) }}" 
                               class="btn w-100 {{ $filter === 'tous' ? 'btn-primary' : 'btn-outline-primary' }}">
                                <i class="fas fa-list me-1"></i>
                                <span>Tous</span>
                                <span class="badge bg-light text-dark ms-1">{{ $counts['tous'] }}</span>
                            </a>
                        </div>
                    </div>

                    <!-- Filtres avancés -->
                    <form method="GET" action="{{ route('ticket.index') }}" id="advancedFilterForm" class="mt-3">
                        <input type="hidden" name="filter" value="{{ $filter }}">
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
                                    <a href="{{ route('ticket.index', ['filter' => $filter]) }}" class="btn btn-sm btn-outline-danger w-100">
                                        <i class="fas fa-times me-1"></i>Réinitialiser
                                    </a>
                                @endif
                            </div>
                        </div>
                    </form>

                    {{-- Barre de recherche mobile (visible seulement sur petit écran) --}}
                    <form method="GET" action="{{ route('ticket.index') }}" class="d-sm-none mt-3">
                        <input type="hidden" name="filter" value="{{ $filter }}">
                        @if($wifi_id)<input type="hidden" name="wifi_id" value="{{ $wifi_id }}">@endif
                        @if($tarif_id)<input type="hidden" name="tarif_id" value="{{ $tarif_id }}">@endif
                        @if($user_id)<input type="hidden" name="user_id" value="{{ $user_id }}">@endif
                        <div class="input-group">
                            <input class="form-control form-control-sm" type="text" name="search" value="{{ $search }}"
                                placeholder="Identifiant ou mot de passe...">
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
                                    <th>
                                        <input type="checkbox" id="check-all" class="form-check-input">
                                    </th>
                                    <th>#</th>
                                    <th>Wifi</th>
                                    <th>Forfait</th>
                                    <th>Montant</th>
                                    <th>Identifiant</th>
                                    <th>Mot de passe</th>
                                    <th>Durée</th>
                                    <th>Etat</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                            @foreach ($datas as $idx => $values)
                                    <tr>
                                        <td>
                                            <input type="checkbox" class="form-check-input ticket-checkbox" name="ids[]"
                                                value="{{ $values->slug }}">
                                        </td>
                                        <td>{{ $datas->firstItem() + $idx }}</td>
                                        <td>{{ $values->tarif->wifi->nom ?? '—' }}</td>
                                        <td>{{ $values->tarif->forfait ?? '—' }}</td>
                                        <td>{{ ($values->tarif->montant ?? 0) . ' FCFA' }}</td>
                                        <td>{{ $values->user }}</td>
                                        <td>{{ $values->password }}</td>
                                        <td>{{ $values->dure }}</td>
                                        <td>
                                            @if($values->etat_ticket === 'EN_VENTE')
                                                <span class="badge bg-success">En vente</span>
                                            @elseif($values->etat_ticket === 'EN_COURS')
                                                <span class="badge bg-warning text-dark">En cours</span>
                                            @else
                                                <span class="badge bg-secondary">Vendu</span>
                                            @endif
                                        </td>
                                        @if(Auth::user()->isAdmin())
                                        <td>{{ $values->owner->nom ?? '' }} {{ $values->owner->prenom ?? '' }}</td>
                                        @endif
                                        <td>
                                            <div class="d-flex">
                                                <button type="button" class="btn btn-sm btn-primary me-1" data-bs-target="#view{{$values->slug}}"
                                                    data-bs-toggle="modal">Voir</button>
                                                <button type="button" class="btn btn-sm btn-danger" data-bs-target="#delete{{$values->slug}}"
                                                    data-bs-toggle="modal">Suppr.</button>
                                            </div>
                                        </td>
                                    </tr>

                                    {{-- Modal Suppression --}}
                                    <div class="modal fade" id="delete{{$values->slug}}" tabindex="-1" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h1 class="modal-title fs-5">Suppression des données</h1>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    Voulez-vous vraiment supprimer les données ?
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Annuler</button>
                                                    <form class="d-inline-block" action="{{ route('ticket.destroy', $values->slug) }}" method="POST">
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
                                                    <div><span>Wifi : </span><span class="fw-bold">{{ $values->tarif->wifi->nom ?? '—' }}</span></div>
                                                    <div><span>Forfait : </span><span class="fw-bold">{{ $values->tarif->forfait ?? '—' }}</span></div>
                                                    <div><span>Montant : </span><span class="fw-bold">{{ ($values->tarif->montant ?? 0) . ' FCFA' }}</span></div>
                                                    <div><span>Identifiant : </span><span class="fw-bold">{{ $values->user }}</span></div>
                                                    <div><span>Mot de passe : </span><span class="fw-bold">{{ $values->password }}</span></div>
                                                    <div><span>Etat du ticket : </span><span class="fw-bold">{{ $values->etat_ticket }}</span></div>
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