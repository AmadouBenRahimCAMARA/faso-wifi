@extends('layouts.admin')
@section('content')
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                <h3 class="text-dark mb-0">Détail du message</h3>
            </div>
        </nav>
        <div class="container-fluid">
            <a href="{{ route('admin.messages') }}" class="btn btn-outline-secondary mb-3">
                <i class="fas fa-arrow-left"></i> Retour à la liste
            </a>

            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 fw-bold text-primary">{{ $message->subject }}</h6>
                    <small class="text-muted">Reçu le {{ $message->created_at->format('d/m/Y à H:i') }}</small>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Nom :</strong> {{ $message->name }}
                        </div>
                        <div class="col-md-6">
                            <strong>Email :</strong> <a href="mailto:{{ $message->email }}">{{ $message->email }}</a>
                        </div>
                    </div>
                    <hr>
                    <div class="mt-3">
                        <strong>Message :</strong>
                        <p class="mt-2 p-3 bg-light rounded" style="white-space: pre-wrap;">{{ $message->message }}</p>
                    </div>
                </div>
                <div class="card-footer text-end">
                    <a href="mailto:{{ $message->email }}?subject=Re: {{ $message->subject }}" class="btn btn-success">
                        <i class="fas fa-reply"></i> Répondre par email
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
