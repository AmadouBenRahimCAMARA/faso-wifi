@extends('layouts.faso')

@section('content')
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="{{route('index')}}">Accueil</a></li>
                    <li>Connexion</li>
                </ol>
                <!--h2>Connexion</h2-->

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card modern-card border-0" style="background: #ffffff; box-shadow: 0 15px 40px rgba(30, 58, 138, 0.08);">
                            <div class="card-header py-4 border-bottom-0 text-center" style="background: #10b981 !important;">
                                <h4 class="fw-bold mb-2" style="color: #ffffff !important;">Connexion</h4>
                                <p class="mb-0" style="color: rgba(255,255,255,0.9) !important; font-size: 0.95rem;">Veuillez vous identifier en utilisant votre adresse e-mail <br>ainsi que votre mot de passe</p>
                            </div>

                            <div class="card-body p-4 p-md-5 pt-0">
                                <form method="POST" action="{{ route('login') }}" class="mt-4">
                                    @csrf

                                    <div class="row mb-3">
                                        <label for="email" class="col-md-12 col-form-label">{{ __('Email') }}</label>

                                        <div class="col-md-12">
                                            <input id="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror" name="email"
                                                value="{{ old('email') }}" required autocomplete="email" autofocus>

                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <label for="password"
                                            class="col-md-12 col-form-label">{{ __('Mot de passe') }}</label>

                                        <div class="col-md-12">
                                            <input id="password" type="password"
                                                class="form-control @error('password') is-invalid @enderror" name="password"
                                                required autocomplete="current-password">

                                            @error('password')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="form-check">
                                                <input class="form-check-input" type="checkbox" name="remember"
                                                    id="remember" {{ old('remember') ? 'checked' : '' }}>

                                                <label class="form-check-label" for="remember">
                                                    {{ __('Se souvenir de moi') }}
                                                </label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row mb-0 mt-4">
                                        <div class="col-md-12 text-center">
                                            <button type="submit" class="btn-premium w-100 py-3 mb-3">
                                                {{ __('Se connecter') }}
                                            </button>

                                            @if (Route::has('password.request'))
                                                <a class="text-muted text-decoration-none" href="{{ route('password.request') }}" style="font-size: 0.9rem;">
                                                    {{ __('Mot de passe oublié ?') }}
                                                </a>
                                            @endif
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main><!-- End #main -->
@endsection
