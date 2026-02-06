@extends('layouts.admin')
@section('content')
<div class="d-flex flex-column" id="content-wrapper">
    <div id="content">
        <nav class="navbar navbar-expand bg-white shadow mb-4 topbar static-top navbar-light">
            <div class="container-fluid">
                <button class="btn btn-link d-md-none rounded-circle me-3" id="sidebarToggleTop" type="button"><i class="fas fa-bars"></i></button>
                <h3 class="text-dark mb-0">Messagerie Contact</h3>
            </div>
        </nav>
        <div class="container-fluid">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 fw-bold text-primary">Messages reçus</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered" width="100%" cellspacing="0">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Nom</th>
                                    <th>Sujet</th>
                                    <th>Statut</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($messages as $msg)
                                <tr class="{{ !$msg->is_read ? 'table-warning' : '' }}">
                                    <td>{{ $msg->created_at->format('d/m/Y H:i') }}</td>
                                    <td>{{ $msg->name }}</td>
                                    <td>{{ Str::limit($msg->subject, 50) }}</td>
                                    <td>
                                        @if($msg->is_read)
                                            <span class="badge bg-secondary">Lu</span>
                                        @else
                                            <span class="badge bg-danger">Non lu</span>
                                        @endif
                                    </td>
                                    <td>
                                        <a href="{{ route('admin.messages.show', $msg->id) }}" class="btn btn-sm btn-primary">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="text-center text-muted">Aucun message pour le moment.</td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                    <div class="d-flex justify-content-center">
                        {{ $messages->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
