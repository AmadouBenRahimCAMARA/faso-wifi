@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0">Mon Bilan d'Activité</h3>
        <a href="{{ route('bilan.download', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-primary btn-sm d-none d-sm-inline-block">
            <i class="fas fa-download fa-sm text-white-50"></i> Télécharger le PDF
        </a>
    </div>

    <!-- Optional Filter Form -->
    <div class="card shadow mb-4 collapse" id="filterCard">
        <div class="card-header py-3 d-flex justify-content-between align-items-center">
            <h6 class="m-0 fw-bold text-primary">Filtrer par période personnalisée</h6>
            <button class="btn btn-sm btn-close" type="button" data-bs-toggle="collapse" data-bs-target="#filterCard"></button>
        </div>
        <div class="card-body">
            <form method="GET" action="{{ route('bilan.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label for="start_date" class="form-label">Date de début</label>
                    <input type="date" class="form-control" id="start_date" name="start_date" value="{{ $start_date }}">
                </div>
                <div class="col-md-4">
                    <label for="end_date" class="form-label">Date de fin</label>
                    <input type="date" class="form-control" id="end_date" name="end_date" value="{{ $end_date }}">
                </div>
                <div class="col-md-4 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1"><i class="fas fa-filter me-2"></i> Appliquer</button>
                    <a href="{{ route('bilan.index') }}" class="btn btn-secondary">Effacer</a>
                </div>
            </form>
        </div>
    </div>

    <!-- Stats Header Info -->
    <div class="alert alert-info d-flex justify-content-between align-items-center shadow-sm mb-4">
        <div>
            <i class="fas fa-info-circle me-2"></i>
            @if($start_date || $end_date)
                Affichage des statistiques pour la période du <strong>{{ $start_date ?? 'Début' }}</strong> au <strong>{{ $end_date ?? 'Aujourd\'hui' }}</strong>.
            @else
                Affichage de votre bilan <strong>Total (Depuis la création du compte)</strong>.
            @endif
        </div>
        <button class="btn btn-outline-primary btn-sm" type="button" data-bs-toggle="collapse" data-bs-target="#filterCard">
            <i class="fas fa-calendar-alt me-1"></i> Filtrer
        </button>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Chiffre d'Affaires -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-primary py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Total Vendu (Brut)</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-shopping-cart fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Commission -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-warning py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>Commission Retenue</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['commission'], 0, ',', ' ') }} FCFA</span></div>
                            <div class="text-xs text-muted">Plateforme (10%)</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-cut fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Gain Net -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-success py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Gain Net Total</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['net_percu'], 0, ',', ' ') }} FCFA</span></div>
                            <div class="text-xs text-muted">Argent gagné ({{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} - 10%)</div>
                        </div>
                        <div class="col-auto"><i class="fas fa-check-circle fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Total Retraits -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-info py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-info fw-bold text-xs mb-1"><span>Retraits Effectués</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['total_retraits'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-hand-holding-usd fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Solde Disponible Area -->
    <div class="row mt-4">
        <div class="col-lg-8 mx-auto">
            <div class="card shadow mb-4 border-bottom-success">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-success text-center">Votre Caisse (Argent Retirable)</h6>
                </div>
                <div class="card-body text-center">
                    <div class="text-xs text-uppercase mb-1">Solde Actuel Disponible</div>
                    <h1 class="display-3 fw-bold text-success">{{ number_format($stats['solde_net_disponible'], 0, ',', ' ') }} FCFA</h1>
                    <p class="text-muted mb-0">Argent disponible après ventes et retraits déjà effectués.</p>
                    
                    <div class="mt-4">
                        <a href="{{ route('retrait.create') }}" class="btn btn-success btn-lg px-5">
                            <i class="fas fa-money-bill-wave me-2"></i> Demander un retrait
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
