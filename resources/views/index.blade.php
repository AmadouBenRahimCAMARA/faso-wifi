@extends('layouts.faso')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row align-items-center">
                <div class="col-xl-7">
                    <h1 class="animate__animated animate__fadeInUp">La vente de tickets WiFi, <br><span style="color: var(--success);">réinventée.</span></h1>
                    <h2 class="animate__animated animate__fadeInUp animate__delay-1s">WiLink Tickets simplifie votre activité. Vendez, gérez et suivez vos tickets WiFi Zone en toute sécurité, depuis n'importe où.</h2>
                    <div class="d-flex gap-3 mt-4 animate__animated animate__fadeInUp animate__delay-2s">
                        <a href="{{ route('inscription') }}" class="get-started-btn">Démarrer gratuitement</a>
                        <a href="#about" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold scrollto">En savoir plus</a>
                    </div>
                </div>
                <div class="col-xl-5 d-none d-xl-block" data-aos="zoom-in" data-aos-delay="200">
                    <div class="hero-image-modern">
                        <!-- L'image ou illustration modernisée ici -->
                        <img src="{{ asset('assets/img/hero-img.png') }}" class="img-fluid floating-anim" alt="">
                    </div>
                </div>
            </div>
        </div>
    </section><!-- End Hero -->

        <!-- ======= Features Section ======= -->
        <section id="features" class="features section-bg" style="background: #fff; padding: 100px 0;">
            <div class="container" data-aos="fade-up">
                <div class="section-title text-center mb-5">
                    <h2>Pourquoi nous choisir ?</h2>
                    <p>Découvrez les outils qui boostent votre activité WiFi Zone</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: var(--bg-slate);">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(30, 58, 138, 0.1); border-radius: 20px;">
                                <i class="ri-rocket-line" style="font-size: 2.5rem; color: var(--primary);"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Vente Instantanée</h4>
                            <p class="text-muted">Vos clients achètent leurs tickets en quelques clics via Orange Money, Moov Money ou Ligdicash.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: var(--bg-slate);">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 20px;">
                                <i class="ri-shield-check-line" style="font-size: 2.5rem; color: var(--success);"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Sécurité Totale</h4>
                            <p class="text-muted">Transactions ultra-sécurisées et gestion rigoureuse de vos revenus via notre registre financier.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: var(--bg-slate);">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 20px;">
                                <i class="ri-pie-chart-2-line" style="font-size: 2.5rem; color: var(--primary-light);"></i>
                            </div>
                            <h4 class="fw-bold mb-3">Statistiques Live</h4>
                            <p class="text-muted">Suivez vos ventes, vos retraits et votre bilan en temps réel depuis votre tableau de bord.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Statistics Section ======= -->
        <section id="stats" class="stats section-bg" style="background: var(--primary); padding: 80px 0; color: #fff;">
            <div class="container">
                <div class="row text-center g-4">
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 2.5rem;">0</span>
                        <p class="mb-0 opacity-75">Vendeurs Actifs</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="10000" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 2.5rem;">0</span>
                        <p class="mb-0 opacity-75">Tickets Vendus</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 2.5rem;">0</span>
                        <p class="mb-0 opacity-75">WiFi Zones</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 2.5rem;">0</span>
                        <p class="mb-0 opacity-75">Support 24/7</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact" style="padding: 100px 0;">
            <div class="container" data-aos="fade-up">

                <div class="section-title text-center mb-5">
                    <h2>Contactez-nous</h2>
                    <p>Une question ? Notre équipe est là pour vous accompagner dans votre succès.</p>
                </div>

                <div class="row g-5" data-aos="fade-up" data-aos-delay="100">

                    <div class="col-lg-5">
                        <div class="info-card p-5 modern-card h-100" style="background: #fff;">
                            <div class="info-item d-flex mb-4">
                                <i class="bx bx-map me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1">Localisation</h4>
                                    <p class="text-muted mb-0">Secteur 22, Bobo-Dioulasso, Burkina Faso</p>
                                </div>
                            </div>
                            <div class="info-item d-flex mb-4">
                                <i class="bx bx-envelope me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1">Email</h4>
                                    <p class="text-muted mb-0">info.wilink.ticket@gmail.com</p>
                                </div>
                            </div>
                            <div class="info-item d-flex">
                                <i class="bx bx-phone-call me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1">Téléphone</h4>
                                    <p class="text-muted mb-0">(+226) 54 78 19 78</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="contact-card p-5 modern-card" style="background: #fff;">
                            <form action="{{ route('contact.send') }}" method="post" role="form" class="php-email-form">
                                @csrf
                                <div class="row g-3">
                                    <div class="col-md-6 form-group">
                                        <label class="form-label fw-semibold">Nom Complet</label>
                                        <input type="text" name="name" class="form-control" id="name" placeholder="Ex: Jean Dupont" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <label class="form-label fw-semibold">Email</label>
                                        <input type="email" class="form-control" name="email" id="email" placeholder="Ex: jean@example.com" required>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label class="form-label fw-semibold">Sujet</label>
                                        <input type="text" class="form-control" name="subject" id="subject" placeholder="Comment pouvons-nous vous aider ?" required>
                                    </div>
                                    <div class="col-12 form-group">
                                        <label class="form-label fw-semibold">Message</label>
                                        <textarea class="form-control" name="message" rows="5" placeholder="Détaillez votre demande ici..." required></textarea>
                                    </div>
                                    <div class="col-12 text-center mt-4">
                                        <button type="submit" class="get-started-btn w-100 py-3">Envoyer le message</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->
@endsection
