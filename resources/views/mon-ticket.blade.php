@extends('layouts.faso')

@section('content')
    <main id="main">

        <!-- ======= Breadcrumbs ======= -->
        <section class="breadcrumbs">
            <div class="container">

                <ol>
                    <li><a href="{{ route('index') }}">Accueil</a></li>
                    <li>Récupérer mon ticket</li>
                </ol>
                <!--h2>Connexion</h2-->

            </div>
        </section><!-- End Breadcrumbs -->

        <section class="inner-page">
            <div class="container" data-aos="fade-up">
                <div class="row justify-content-center">
                    <div class="col-md-6">
                        <div class="card modern-card border-0" style="background: #ffffff; box-shadow: 0 15px 40px rgba(30, 58, 138, 0.08);">
                            <div class="card-header py-4 border-bottom-0 text-center" style="background: #059669;">
                                <h4 class="fw-bold mb-2" style="color: #ffffff;">Récupérer mon ticket</h4>
                                <p class="mb-0" style="color: rgba(255,255,255,0.8); font-size: 0.95rem;">Renseignez l'id de votre ticket afin de pouvoir le récupérer</p>
                            </div>

                            <div class="card-body p-4 p-md-5 pt-0">
                                <form method="POST" action="{{ route('recuperationPost') }}">
                                    @csrf
                                    <div class="row mb-4">
                                        <div class="col-md-12">
                                            <label for="monTicket" class="form-label fw-semibold" style="color: #1e293b;">{{ __('Identifiant de Transaction') }}</label>
                                            <input id="monTicket" type="text"
                                                class="form-control form-control-lg @error('monTicket') is-invalid @enderror"
                                                name="monTicket" value="{{ old('monTicket') }}" required
                                                autocomplete="monTicket" autofocus placeholder="Ex: TXN-123456789">

                                            @error('monTicket')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                    </div>
                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" class="btn-premium w-100 py-3">Continuer</button>
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