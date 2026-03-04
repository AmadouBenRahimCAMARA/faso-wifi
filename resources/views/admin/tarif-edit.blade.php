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
            <h3 class="text-dark mb-4">Édition du tarif</h3>
            <div class="card shadow">
                <div class="card-header py-3">
                    <div class="d-flex">
                        <p class="text-primary m-0 fw-bold me-auto">Modification du tarif existant</p>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form method="POST" action="{{ route('tarifs.update', $data->slug) }}">
                                @csrf
                                @method("put")
                                <div class="row mb-3">
                                    <label for="wifi_id" class="col-12 col-form-label">{{ __('Wifi') }}</label>
                                    <div class="col-md-6">
                                        <select id="wifi_id" class="form-select @error('wifi_id') is-invalid @enderror" name="wifi_id"
                                            required autocomplete="wifi_id" autofocus>
                                            <option value="">Sélectionnez un wifi</option>
                                            @foreach ($wifi as $item)
                                                <option value="{{ $item->id }}"
                                                    {{ $data->wifi_id == $item->id ? 'selected' : '' }}>
                                                    {{ $item->nom }}</option>
                                            @endforeach

                                        </select>

                                        @error('wifi_id')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="forfait" class="col-12 col-form-label">{{ __('Forfait') }}</label>
                                    <div class="col-md-6">
                                        <input id="forfait" type="text"
                                            class="form-control @error('forfait') is-invalid @enderror" name="forfait"
                                            value="{{ $data->forfait }}" required autocomplete="forfait" autofocus>

                                        @error('forfait')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="montant" class="col-12 col-form-label">{{ __('Montant') }}</label>
                                    <div class="col-md-6">
                                        <input id="montant" type="number"
                                            class="form-control @error('montant') is-invalid @enderror" name="montant"
                                            value="{{ $data->montant }}" required autocomplete="montant" autofocus>

                                        @error('montant')
                                            <span class="invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label for="description" class="col-12 col-form-label">{{ __('Description') }}</label>
                                    <div class="col-md-6">
                                        <textarea id="description" type="text" class="form-control @error('description') is-invalid @enderror"
                                            name="description" required autocomplete="description" autofocus>{{ $data->description }}</textarea>

                                        @error('description')
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
