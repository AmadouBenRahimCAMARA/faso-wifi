@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg border-0 rounded-lg mt-5">
                <div class="card-header bg-primary text-white text-center font-weight-light my-4">
                    <h3 class="font-weight-light my-4">{{ __('Vérification du Compte') }}</h3>
                </div>
                <div class="card-body">
                    @if (session('error'))
                        <div class="alert alert-danger" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif
                    @if (session('message'))
                        <div class="alert alert-success" role="alert">
                            {{ session('message') }}
                        </div>
                    @endif

                    <p>{{ __('Un code de vérification a été envoyé à votre adresse email.') }}</p>
                    <p>{{ __('Veuillez entrer ce code ci-dessous pour activer votre compte.') }}</p>

                    <form method="POST" action="{{ route('verification.verify.post') }}">
                        @csrf

                        <div class="form-floating mb-3">
                            <input id="verification_code" type="text" class="form-control @error('verification_code') is-invalid @enderror" name="verification_code" required autofocus placeholder="Ex: 123456">
                            <label for="verification_code">{{ __('Code de Vérification') }}</label>
                            @error('verification_code')
                                <span class="invalid-feedback" role="alert">
                                    <strong>{{ $message }}</strong>
                                </span>
                            @enderror
                        </div>

                        <div class="d-flex align-items-center justify-content-between mt-4 mb-0">
                            <button type="submit" class="btn btn-primary w-100">
                                {{ __('Vérifier') }}
                            </button>
                        </div>
                    </form>
                    <div class="mt-3 text-center">
                        <a class="small" href="{{ route('verification.resend') }}">{{ __('Renvoyer le code') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
