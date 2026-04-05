<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta content="width=device-width, initial-scale=1.0" name="viewport">

    <title>WiLink Tickets</title>
    <meta content="" name="description">
    <meta content="" name="keywords">

    <!-- Favicons -->
    <link href="{{ asset('assets/img/logo.png') }}" rel="icon">
    <link href="assets/img/apple-touch-icon.png" rel="apple-touch-icon">

    <!-- Google Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap"
        rel="stylesheet">

    <!-- Vendor CSS Files -->
    <link href="{{ asset('visitor/assets/vendor/aos/aos.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/bootstrap/css/bootstrap.min.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/bootstrap-icons/bootstrap-icons.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/boxicons/css/boxicons.min.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/glightbox/css/glightbox.min.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/remixicon/remixicon.css') }}" rel="stylesheet">
    <link href="{{ asset('visitor/assets/vendor/swiper/swiper-bundle.min.css') }}" rel="stylesheet">

    <!-- Template Main CSS File -->
    <link href="{{ asset('visitor/assets/css/style.css') }}?v={{ time() }}" rel="stylesheet">
    <link href="{{ asset('css/modern_ov.css') }}?v={{ time() }}" rel="stylesheet">

    <!-- =======================================================
  * Template Name: Presento - v3.9.1
  * Template URL: https://bootstrapmade.com/presento-bootstrap-corporate-template/
  * Author: BootstrapMade.com
  * License: https://bootstrapmade.com/license/
  ======================================================== -->
</head>

<body>

    <!-- ======= Header ======= -->
    <header id="header" class="fixed-top d-flex align-items-center">
        <div class="container d-flex align-items-center">
            <h1 class="logo me-auto">
                <a href="{{ route('index') }}" class="d-flex align-items-center">
                    <img src="{{ asset('assets/img/logo.png') }}" alt=""
                        style="height: 40px; margin-right: 10px; border-radius: 50%;">
                    WiLink Tickets<span>.</span>
                </a>
            </h1>

            <nav id="navbar" class="navbar order-last order-lg-0">
                <ul>
                    <li><a class="nav-link scrollto" href="{{ route('index') }}#hero">Accueil</a></li>
                    <li><a class="nav-link scrollto @if (Route::currentRouteName() == 'recuperation') active @endif"
                            href="{{ route('recuperation') }}">Récupérer mon ticket</a></li>
                    <li><a class="nav-link scrollto" href="{{ route('index') }}#about">A propos</a></li>
                    <li><a class="nav-link scrollto" href="{{ route('index') }}#contact">Contact</a></li>
                    @guest
                    @else
                        <li class="nav-item dropdown">
                            <a id="navbarDropdown" class="nav-link dropdown-toggle" href="#" role="button"
                                data-bs-toggle="dropdown" aria-haspopup="true" aria-expanded="false" v-pre>
                                {{ Auth::user()->nom . ' ' . Auth::user()->prenom }}
                            </a>

                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                                <a class="dropdown-item" href="{{ route('wifi.index') }}">
                                    {{ __('Mon compte') }}
                                </a>
                                <a class="dropdown-item" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                         document.getElementById('logout-form').submit();">
                                    {{ __('Déconnexion') }}
                                </a>

                                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </div>
                        </li>
                    @endguest
                </ul>
                <i class="bi bi-list mobile-nav-toggle"></i>
            </nav><!-- .navbar -->
            @guest
                @if (Route::has('login'))
                    <a href="{{ route('connexion') }}" class="get-started-btn scrollto">Se connecter</a>
                @endif
            @else
            @endguest

        </div>
    </header><!-- End Header -->

    @yield('content')

    <!-- ======= Footer ======= -->
    <footer id="footer">
        <div class="footer-top">
            <div class="container">
                <div class="row g-4">

                    <div class="col-lg-4 col-md-6 footer-contact">
                        <h3>WiLink Tickets<span style="color: var(--success);">.</span></h3>
                        <p class="mt-3">
                            La solution leader pour la vente de tickets WiFi au Burkina Faso. Digitalisez votre activité
                            dès aujourd'hui.
                        </p>
                        <div class="contact-info mt-4">
                            <p class="mb-2"><i class="ri-map-pin-line me-2" style="color: var(--success);"></i>
                                Bobo-Dioulasso, Secteur 22</p>
                            <p class="mb-2"><i class="ri-phone-line me-2" style="color: var(--success);"></i> (+226) 54
                                78 19 78</p>
                            <p class="mb-0"><i class="ri-mail-line me-2" style="color: var(--success);"></i>
                                info.wilink.ticket@gmail.com</p>
                        </div>
                    </div>

                    <div class="col-lg-2 col-md-6 footer-links">
                        <h4>Navigation</h4>
                        <ul>
                            <li><a href="{{ route('index') }}#hero">Accueil</a></li>
                            <li><a href="{{ route('recuperation') }}">Récupérer ticket</a></li>
                            <li><a href="#about">À propos</a></li>
                            <li><a href="#contact">Contact</a></li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6 footer-links">
                        <h4>Administratif</h4>
                        <ul class="text-white-50">
                            <li><i class="bx bx-chevron-right me-1"></i> RCCM : BFBBD 01202581301633</li>
                            <li><i class="bx bx-chevron-right me-1"></i> IFU : 00277389V</li>
                        </ul>
                    </div>

                    <div class="col-lg-3 col-md-6">
                        <h4>Suivez-nous</h4>
                        <p>Restez connecté avec l'actualité de WiLink Tickets.</p>
                        <div class="social-links mt-3">
                            <a href="#"><i class="bx bxl-facebook"></i></a>
                            <a href="#"><i class="bx bxl-whatsapp"></i></a>
                            <a href="#"><i class="bx bxl-linkedin"></i></a>
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <div class="container footer-bottom">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-3">
                <div class="copyright">
                    &copy; {{ date('Y') }} <strong><span>WiLink Tickets</span></strong>. Tous droits réservés.
                </div>
                <div class="credits" style="font-size: 0.8rem; opacity: 0.6;">
                    Propulsé par <span class="fw-bold">WiLink International SARL</span>
                </div>
            </div>
        </div>
    </footer><!-- End Footer -->

    <a href="#" class="back-to-top d-flex align-items-center justify-content-center"><i
            class="bi bi-arrow-up-short"></i></a>

    <!-- Vendor JS Files -->
    <script src="{{ asset('visitor/assets/vendor/purecounter/purecounter_vanilla.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/aos/aos.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/glightbox/js/glightbox.min.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/isotope-layout/isotope.pkgd.min.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/swiper/swiper-bundle.min.js') }}"></script>
    <script src="{{ asset('visitor/assets/vendor/php-email-form/validate.js') }}"></script>

    <!-- Template Main JS File -->
    <script src="{{ asset('visitor/assets/js/main.js') }}"></script>

</body>

</html>