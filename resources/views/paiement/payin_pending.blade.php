@extends('layouts.app')

@section('content')
<div class="container py-5 text-center">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-sm p-5">
                <h2 class="mb-4">Paiement en attente de confirmation...</h2>
                <div class="d-flex justify-content-center">
                    <div class="spinner-border text-primary" role="status" style="width: 3rem; height: 3rem;">
                        <span class="visually-hidden">Chargement...</span>
                    </div>
                </div>
                <p class="mt-4 lead">Votre paiement est en cours de traitement.<br>Cette page s'actualisera automatiquement dès la confirmation.</p>
                
                <p class="text-muted mt-3"><small>Si rien ne se passe après quelques instants, <a href="{{ route('statutPaiement') }}">cliquez ici pour actualiser</a>.</small></p>
            </div>
        </div>
    </div>
</div>

<script>
    setTimeout(function(){
       window.location.reload();
    }, 3000); // Reload every 3 seconds
</script>
@endsection
