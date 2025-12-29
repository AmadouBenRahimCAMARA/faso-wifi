@extends('layouts.faso')
@section('content')
    <!-- ======= Hero Section ======= -->
    <section id="hero" class="d-flex align-items-center">

        <div class="container" data-aos="zoom-out" data-aos-delay="100">
            <div class="row">
                <div class="col-xl-6">
                    <h1>Bienvenue sur WiLink Tickets</h1>
                    <h2>Votre Plateforme de Vente de Tickets WiFi Simplifiée</h2>
                    <a href="{{ route('inscription') }}" class="btn-get-started scrollto">Créer mon compte</a>
                </div>
            </div>
        </div>

    </section><!-- End Hero -->

    <main id="main">

        <!-- ======= About Section ======= -->
        <section id="about" class="about">
            <div class="container" data-aos="fade-up">
                <div class="about-box-floating">
                    <div class="row">
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                            <h3>A propos de WiLink Tickets</h3>
                            <p class="">
                                <span class="fw-bold">WiLink Tickets</span> est une plateforme innovante, conçue pour révolutionner la façon dont
                                les vendeurs de WiFi Zone vendent leurs tickets de connexion en ligne. Grâce à <span
                                    class="fw-bold">WiLink Tickets
                                   </span>, la vente de tickets devient plus simple, plus pratique et plus
                                sécurisée que
                                jamais.
                            </p>
                            <p class="fst-italic1">
                                Avec <span class="fw-bold">WiLink Tickets</span>, vous pouvez gérer votre activité de vente de tickets WiFi Zone de
                                manière efficace et sans tracas. Plus besoin pour vos clients de se déplacer
                                physiquement pour acheter des tickets, tout se fait en ligne.
                            </p>
                        </div>
                        <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                            <p>
                                Pour les vendeurs de WiFi Zone, <span class="fw-bold">WiLink Tickets</span> offre un processus d'inscription rapide et
                                convivial :
                            </p>
                            <ul>
                                <li><i class="ri-check-double-line"></i>Inscrivez-vous en quelques étapes simples.</li>
                                <li><i class="ri-check-double-line"></i>Connectez-vous à votre espace membre
                                    personnalisé.</li>
                                <li><i class="ri-check-double-line"></i>Enregistrez rapidement votre WiFizone et
                                    configurez vos paramètres.</li>
                                <li><i class="ri-check-double-line"></i>Créez des tarifs flexibles et adaptables à vos
                                    besoins commerciaux.</li>
                                <li><i class="ri-check-double-line"></i>Importez facilement vos tickets pour une gestion
                                    efficace des stocks.</li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section><!-- End About Section -->

        <!-- ======= Contact Section ======= -->
        <section id="contact" class="contact">
            <div class="container" data-aos="fade-up">

                <div class="section-title">
                    <h2>Contact</h2>
                    <p>N'hésitez pas à nous contacter pour toute question</p>
                </div>

                <div class="row" data-aos="fade-up" data-aos-delay="100">

                    <div class="col-lg-6">

                        <div class="row">
                            <div class="col-md-12">
                                <div class="info-box">
                                    <i class="bx bx-map"></i>
                                    <h3>Notre adresse</h3>
                                    <p>Secteur 22, Bobo-Dioulasso, Burkina Faso</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box mt-4">
                                    <i class="bx bx-envelope"></i>
                                    <h3>Email</h3>
                                    <p>info@wilink-tickets.com</p>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="info-box mt-4">
                                    <i class="bx bx-phone-call"></i>
                                    <h3>Téléphone</h3>
                                    <p>(+226)54 78 19 78</p>

                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-6">
                        <form action="forms/contact.php" method="post" role="form" class="php-email-form">
                            <div class="row">
                                <div class="col form-group">
                                    <input type="text" name="name" class="form-control" id="name"
                                        placeholder="Nom prénom" required>
                                </div>
                                <div class="col form-group">
                                    <input type="email" class="form-control" name="email" id="email"
                                        placeholder="Email" required>
                                </div>
                            </div>
                            <div class="form-group">
                                <input type="text" class="form-control" name="subject" id="subject"
                                    placeholder="Sujet" required>
                            </div>
                            <div class="form-group">
                                <textarea class="form-control" name="message" rows="5" placeholder="Message" required></textarea>
                            </div>
                            <div class="my-3">
                                <div class="loading">Loading</div>
                                <div class="error-message"></div>
                                <div class="sent-message">Your message has been sent. Thank you!</div>
                            </div>
                            <div class="text-center"><button type="submit">Envoyer</button></div>
                        </form>
                    </div>

                </div>

            </div>
        </section><!-- End Contact Section -->

    </main><!-- End #main -->
@endsection
