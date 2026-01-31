@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <div class="d-sm-flex justify-content-between align-items-center mb-4">
        <h3 class="text-dark mb-0">Mon Bilan d'Activité</h3>
        <a href="{{ route('bilan.download', ['start_date' => $start_date, 'end_date' => $end_date]) }}" class="btn btn-primary btn-sm d-none d-sm-inline-block">
            <i class="fas fa-download fa-sm text-white-50"></i> Télécharger le PDF
        </a>
    </div>

    <!-- Filter Form -->
    <div class="card shadow mb-4">
        <div class="card-header py-3">
            <h6 class="m-0 fw-bold text-primary">Filtrer par période</h6>
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
                <div class="col-md-4 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100"><i class="fas fa-filter me-2"></i> Filtrer</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="row">
        <!-- Chiffre d'Affaires -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-primary py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Chiffre d'Affaires</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['chiffre_affaires'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-calendar fa-2x text-gray-300"></i></div>
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
                            <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>Commission (10%)</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['commission'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-percent fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Net Perçu -->
        <div class="col-md-6 col-xl-3 mb-4">
            <div class="card shadow border-start-success py-2">
                <div class="card-body">
                    <div class="row align-items-center no-gutters">
                        <div class="col me-2">
                            <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Gain Net (Période)</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['net_percu'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-dollar-sign fa-2x text-gray-300"></i></div>
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
                            <div class="text-uppercase text-info fw-bold text-xs mb-1"><span>Retraits Validés</span></div>
                            <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($stats['total_retraits'], 0, ',', ' ') }} FCFA</span></div>
                        </div>
                        <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Solde Disponible Area -->
    <div class="row mt-4">
        <div class="col-12">
            <div class="card shadow mb-4 border-bottom-success">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-success">Solde Disponible (Temps Réel)</h6>
                </div>
                <div class="card-body text-center">
                    <h1 class="display-4 fw-bold text-success">{{ number_format($stats['solde_net_disponible'], 0, ',', ' ') }} FCFA</h1>
                    <p class="text-muted">Ceci est le montant net que vous pouvez retirer (Commission déduite).</p>
                    <a href="{{ route('retrait.create') }}" class="btn btn-success btn-lg mt-3">Demander un retrait</a>
                </div>
            </div>
        </div>
    </div>

</div>
@endsection
