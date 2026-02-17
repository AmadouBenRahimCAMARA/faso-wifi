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
            <h3 class="text-dark mb-4">Demande de retrait</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex">
                        <p class="text-primary m-0 fw-bold me-auto">Demande d'un nouveau retrait</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form method="POST" action="{{ route('retrait.store') }}">
                                @csrf
                                <div class="row mb-3">
                                    <label for="moyen_de_paiement" class="col-12 col-form-label">{{ __('Wifi') }}</label>
                                    <div class="col-md-6">
                                        <select class="form-select" id="moyen_de_paiement"
                                            class="form-control @error('moyen_de_paiement') is-invalid @enderror" name="moyen_de_paiement"
                                            value="{{ old('moyen_de_paiement') }}" required autocomplete="moyen_de_paiement" autofocus>
                                            <option value="">Sélectionnez un moyen de paiement</option>
                                            <option value="ORANGE_MONEY">Orange Money</option>
                                            <option value="MOOV_MONEY">Moov Money</option>
                                        </select>

                                        @error('moyen_de_paiement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="numero_paiement"
                                        class="col-12 col-form-label">{{ __('Numéro de paiement') }}</label>
                                    <div class="col-md-6">
                                        <input id="numero_paiement" type="text"
                                            class="form-control @error('numero_paiement') is-invalid @enderror" name="numero_paiement"
                                            value="{{ old('numero_paiement') }}" required autocomplete="numero_paiement" autofocus>

                                        @error('numero_paiement')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="montant" class="col-12 col-form-label">{{ __('Montant à retirer') }}
                                        <br><small class="text-muted">(Solde disponible : {{ $retrait }} FCFA - Minimum : 1000 FCFA)</small></label>
                                    <div class="col-md-6">
                                        <input id="montant" type="number" max={{ $retrait }}
                                            class="form-control @error('montant') is-invalid @enderror" name="montant"
                                            value="{{ old('montant') }}" required  autofocus>

                                        @error('montant')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>


                                <div class="row mb-0">
                                    <div class="col-md-6 offset-md-4">
                                        <button type="submit" class="btn btn-primary">
                                            {{ __('Enregistrer') }}
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
