@extends('layouts.admin')
@section('content')
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                <h3 class="text-dark mb-0">Super Admin Dashboard</h3>
            </div>
        </nav>
        <div class="container-fluid">
            <div class="row">
                <!-- Total Revenue -->
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-primary py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-primary fw-bold text-xs mb-1"><span>Chiffre d'Affaires Global</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ number_format($totalRevenue, 0, ',', ' ') }} FCFA</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-money-bill-wave fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Tickets Sold -->
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-success py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-success fw-bold text-xs mb-1"><span>Tickets Vendus (Total)</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $totalTicketsSold }}</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-ticket-alt fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Users -->
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-info py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-info fw-bold text-xs mb-1"><span>Utilisateurs Inscrits</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $totalUsers }}</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-users fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total Wifis -->
                <div class="col-md-6 col-xl-3 mb-4">
                    <div class="card shadow border-start-warning py-2">
                        <div class="card-body">
                            <div class="row align-items-center no-gutters">
                                <div class="col me-2">
                                    <div class="text-uppercase text-warning fw-bold text-xs mb-1"><span>Points WiFi Actifs</span></div>
                                    <div class="text-dark fw-bold h5 mb-0"><span>{{ $totalWifis }}</span></div>
                                </div>
                                <div class="col-auto"><i class="fas fa-wifi fa-2x text-gray-300"></i></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recent Payments Table -->
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Dernières Transactions (Globales)</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" id="dataTable" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>ID Transaction</th>
                                    <th>Date</th>
                                    <th>Vendeur</th>
                                    <th>Montant</th>
                                    <th>Moyen de Paiement</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($recentPayments as $payment)
                                <tr>
                                    <td>{{ $payment->transaction_id }}</td>
                                    <td>{{ $payment->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $payment->ticket->user->nom ?? 'Inconnu' }} {{ $payment->ticket->user->prenom ?? '' }}</td>
                                    <td>{{ $payment->ticket->tarif->montant ?? 0 }} FCFA</td>
                                    <td>{{ $payment->moyen_de_paiement }}</td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
