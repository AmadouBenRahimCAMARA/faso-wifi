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
            <h3 class="text-dark mb-4">Wifi</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex">
                        <p class="text-primary m-0 fw-bold me-auto">Liste des reseaux wifi</p>
                        <a href="{{ route('wifi.create') }}" class="btn btn-primary">Ajouter</a>
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
                                    <th>#</th>
                                    <th>Nom</th>
                                    <th>Description</th>
                                    <th>Date d'ajout</th>
                                    @if(Auth::user()->isAdmin())
                                    <th>Vendeur</th>
                                    @endif
                                    <th>Code d'integration</th>
                                    <th>Action</th>
                                </tr>
                            </thead>

                            @foreach ($datas as $idx => $values)
                                <tbody>
                                    <tr>
                                        <th>{{ $idx + 1 }}</th>
                                        <th>{{ $values->nom }}</th>
                                        <th>{{ $values->description }}</th>
                                        <th>{{ date_format($values->created_at, 'd/m/Y H:i:s') }}</th>
                                        @if(Auth::user()->isAdmin())
                                        <th>{{ $values->user->nom }} {{ $values->user->prenom }}</th>
                                        @endif
                                        <td><button class="btn btn-primary btn-fixed-width" data-bs-toggle="modal" data-bs-target="#modalCopier" onclick="copyCode('{{$values->slug}}')">copier le code</button></td>
                                        <th class="d-flex justify-content-start align-items-center">
                                            <a href="" class="btn btn-primary btn-fixed-width me-1 mb-1"
                                                data-bs-target="#view{{ $values->slug }}" data-bs-toggle="modal">Voir</a>
                                            <a href="{{ route('wifi.edit', $values->slug) }}"
                                                class="btn btn-warning btn-fixed-width me-1 mb-1">Editer</a>

                                            <button href="" class="btn btn-danger btn-fixed-width me-1 mb-1"
                                                data-bs-target="#delete{{ $values->slug }}"
                                                data-bs-toggle="modal">Supprimer</button>


                                            <div class="modal fade" id="delete{{ $values->slug }}" tabindex="-1"
                                                aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content bg-white text-dark">
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
                                                                action="{{ route('wifi.destroy', $values->slug) }}"
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
                                            <div class="modal fade" id="view{{ $values->slug }}" tabindex="-1"
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
                                                                <span>Nom : </span>
                                                                <span class="fw-bold">{{ $values->nom }}</span>
                                                            </div>
                                                            <div>
                                                                <span>Description : </span>
                                                                <span class="fw-bold">{{ $values->description }}</span>
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
                    <div class="modal fade" id="modalCopier" tabindex="-1"
                        aria-labelledby="exampleModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-dialog-centered">
                            <div class="modal-content bg-white text-dark">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModalLabel">
                                        Code d'integration
                                    </h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"
                                        aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    Le code d'intégration a bien été copier
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-primary"
                                        data-bs-dismiss="modal">Fermer</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
        function copyCode(slug) {
            var copyText = `
            <style> .myButton { box-shadow: 0px 10px 14px -7px #0b2341; background: linear-gradient(to bottom, #0b2341 5%, #0b2341
    100%); background-color: #0b2341; border-radius: 8px; border: 1px solid #0b2341; display: inline-block; cursor:
    pointer; color: #ffffff; font-family: Arial; font-size: 19px; font-weight: bold; padding: 13px 32px;
    text-decoration: none; text-shadow: 0px 1px 0px #0b2341; margin: 2px; } .myButton:hover { background:
    linear-gradient(to bottom, #0b2341 5%, #cab900 100%); background-color: #cab900; } .myButton:active { position:
    relative; top: 1px; } .img-a { border-radius: 50%; width: 23px; height: 23px; } </style>
    <div style="text-align: center;"> <a target="_blank" href="{{App::make('url')->to('/')}}/acheter-mon-ticket/${slug}"
            class="myButton"> Payer mon ticket en ligne </a> </div>
    <div style="text-align: center;"> <a target="_blank" href="{{App::make('url')->to('/')}}/recuperer-mon-ticket"
            class="myButton"> Récupérer mon ticket </a> </div>
            `
            navigator.clipboard.writeText(copyText);
        }
    </script>
@endsection
