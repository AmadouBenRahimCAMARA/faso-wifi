@extends('layouts.faso')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">
        <div class="container" data-aos="fade-up" data-aos-delay="100">
            <div class="row align-items-center">
                <div class="col-xl-7">
                    <h1 class="animate__animated animate__fadeInUp">La vente de tickets WiFi, <br><span style="color: #10b981;">réinventée.</span></h1>
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
        <!-- ======= About Section ======= -->
        <section id="about" class="about" style="padding: 100px 0; background: #f0f7ff;">
            <div class="container" data-aos="fade-up">
                <div class="about-box-floating modern-card p-5" style="border-radius: 30px; background: #fff; box-shadow: 0 15px 40px rgba(30, 58, 138, 0.08) !important;">
                    <div class="row align-items-center">
                        <div class="col-lg-6" data-aos="fade-right" data-aos-delay="100">
                            <div class="pe-lg-5">
                                <h3 class="fw-bold mb-4" style="color: #1e3a8a;">À propos de WiLink Tickets</h3>
                                <p class="lead mb-4" style="font-size: 1.1rem; color: #334155;">
                                    <span class="fw-bold" style="color: #2563eb;">WiLink Tickets</span> est une plateforme innovante conçue pour révolutionner la vente de tickets WiFi Zone au Burkina Faso.
                                </p>
                                <p class="mb-4" style="color: #64748b;">
                                    Nous permettons aux gérants de WiFi Zone de digitaliser entièrement leur activité. Plus besoin de présence physique constante : vos clients achètent leurs tickets en toute autonomie, 24h/24 et 7j/7.
                                </p>
                                <div class="d-flex align-items-center gap-3">
                                    <div class="stats-mini p-3 rounded-5" style="background: #eff6ff; border: 1px solid #dbeafe;">
                                        <h4 class="fw-bold mb-0" style="color: #1e40af;">100%</h4>
                                        <small style="color: #64748b;">Sécurisé</small>
                                    </div>
                                    <div class="stats-mini p-3 rounded-5" style="background: #f0fdf4; border: 1px solid #dcfce7;">
                                        <h4 class="fw-bold mb-0" style="color: #15803d;">Fiable</h4>
                                        <small style="color: #64748b;">Disponibilité</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 mt-5 mt-lg-0" data-aos="fade-left" data-aos-delay="200">
                            <div class="p-4 rounded-5" style="background: #f0fdf4; border: 1px dashed #10b981; color: #1e293b;">
                                <h5 class="fw-bold mb-3" style="color: #065f46;">Un processus simplifié :</h5>
                                <ul class="list-unstyled">
                                    <li class="mb-3 d-flex align-items-start">
                                        <i class="ri-checkbox-circle-fill me-3" style="color: #10b981; font-size: 1.25rem;"></i>
                                        <span>Inscrivez-vous et configurez votre WiFi Zone en 2 minutes.</span>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <i class="ri-checkbox-circle-fill me-3" style="color: #10b981; font-size: 1.25rem;"></i>
                                        <span>Importez vos tickets et fixez vos tarifs librement.</span>
                                    </li>
                                    <li class="mb-3 d-flex align-items-start">
                                        <i class="ri-checkbox-circle-fill me-3" style="color: #10b981; font-size: 1.25rem;"></i>
                                        <span>Suivez vos revenus et demandez vos retraits instantanément.</span>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End About Section -->

        <!-- ======= Features Section ======= -->
        <section id="features" class="features section-bg" style="background: #fff; padding: 100px 0;">
            <div class="container" data-aos="fade-up">
                <div class="section-title text-center mb-5">
                    <h2>Pourquoi nous choisir ?</h2>
                    <p>Découvrez les outils qui boostent votre activité WiFi Zone</p>
                </div>
                <div class="row g-4">
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: #f0f7ff; border: 1px solid #e0f2fe;">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(30, 58, 138, 0.1); border-radius: 24px;">
                                <i class="ri-rocket-line" style="font-size: 2.5rem; color: var(--primary);"></i>
                            </div>
                            <h4 class="fw-bold mb-3" style="color: var(--primary);">Vente Instantanée</h4>
                            <p class="text-muted">Vos clients achètent leurs tickets en quelques clics via Orange Money, Moov Money ou Ligdicash.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: #f0fdf4; border: 1px solid #dcfce7; border-radius: 40px !important;">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(16, 185, 129, 0.1); border-radius: 24px;">
                                <i class="ri-shield-check-line" style="font-size: 2.5rem; color: var(--success);"></i>
                            </div>
                            <h4 class="fw-bold mb-3" style="color: var(--success-dark);">Sécurité Totale</h4>
                            <p class="text-muted">Transactions ultra-sécurisées et gestion rigoureuse de vos revenus via notre registre financier.</p>
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="feature-item p-5 text-center modern-card h-100" style="background: #f0f7ff; border: 1px solid #e0f2fe;">
                            <div class="icon-box mb-4 mx-auto d-flex align-items-center justify-content-center" style="width: 80px; height: 80px; background: rgba(59, 130, 246, 0.1); border-radius: 24px;">
                                <i class="ri-pie-chart-2-line" style="font-size: 2.5rem; color: var(--primary-light);"></i>
                            </div>
                            <h4 class="fw-bold mb-3" style="color: var(--primary);">Statistiques Live</h4>
                            <p class="text-muted">Suivez vos ventes, vos retraits et votre bilan en temps réel depuis votre tableau de bord.</p>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Statistics Section ======= -->
        <section id="stats" class="stats section-bg" style="background: #f8fafc; padding: 100px 0; border-top: 1px solid #f1f5f9; border-bottom: 1px solid #f1f5f9;">
            <div class="container">
                <div class="row text-center g-4">
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="500" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 3rem; color: #1e3a8a;">0</span>
                        <p class="mb-0 fw-semibold" style="color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">Vendeurs Actifs</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="10000" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 3rem; color: #1e3a8a;">0</span>
                        <p class="mb-0 fw-semibold" style="color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">Tickets Vendus</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="50" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 3rem; color: #1e3a8a;">0</span>
                        <p class="mb-0 fw-semibold" style="color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">WiFi Zones</p>
                    </div>
                    <div class="col-6 col-lg-3">
                        <span data-purecounter-start="0" data-purecounter-end="24" data-purecounter-duration="1" class="purecounter fw-bold d-block mb-2" style="font-size: 3rem; color: #1e3a8a;">0</span>
                        <p class="mb-0 fw-semibold" style="color: #64748b; text-transform: uppercase; letter-spacing: 1px; font-size: 0.8rem;">Support 24/7</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact" style="padding: 100px 0;">
            <div class="container" data-aos="fade-up">

                <div class="section-title text-center mb-5">
                    <h2 style="color: #334155 !important;">Contactez-nous</h2>
                    <p style="color: #64748b !important;">Une question ? Notre équipe est là pour vous accompagner dans votre succès.</p>
                </div>

                <div class="row g-5" data-aos="fade-up" data-aos-delay="100">

                    <div class="col-lg-5">
                        <div class="info-card p-5 modern-card h-100" style="background: #fff;">
                            <div class="info-item d-flex mb-4">
                                <i class="bx bx-map me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: #334155;">Localisation</h4>
                                    <p class="mb-0" style="color: #64748b;">Secteur 22, Bobo-Dioulasso, Burkina Faso</p>
                                </div>
                            </div>
                            <div class="info-item d-flex mb-4">
                                <i class="bx bx-envelope me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: #334155;">Email</h4>
                                    <p class="mb-0" style="color: #64748b;">info.wilink.ticket@gmail.com</p>
                                </div>
                            </div>
                            <div class="info-item d-flex">
                                <i class="bx bx-phone-call me-3" style="font-size: 1.5rem; color: var(--primary);"></i>
                                <div>
                                    <h4 class="fw-bold mb-1" style="color: #334155;">Téléphone</h4>
                                    <p class="mb-0" style="color: #64748b;">(+226) 54 78 19 78</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-7">
                        <div class="contact-card p-5 modern-card" style="background: #fff; border-radius: 35px !important;">
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
                                        <button type="submit" class="btn-outline-pro w-100 py-3">Envoyer le message</button>
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
