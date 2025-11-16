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
    <link
        href="https://fonts.googleapis.com/css?family=Open+Sans:300,300i,400,400i,600,600i,700,700i|Raleway:300,300i,400,400i,500,500i,600,600i,700,700i|Poppins:300,300i,400,400i,500,500i,600,600i,700,700i"
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
    <link href="{{ asset('visitor/assets/css/style.css') }}" rel="stylesheet">

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
                    <img src="{{ asset('assets/img/logo.png') }}" alt="" style="height: 40px; margin-right: 10px; border-radius: 50%;">
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
                                <a class="dropdown-item" href="{{ route('logout') }}"
                                    onclick="event.preventDefault();
                                                     document.getElementById('logout-form').submit();">
                                    {{ __('Deconnection') }}
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
                <div class="row">

                    <div class="col-lg-4 col-md-6 footer-contact">
                        <h3>WiLink Tickets<span>.</span></h3>
                        <p>
                            BURKINA FASO <br>
                            Bobo-Dioulasso<br>
                            Secteur 22 <br><br>
                            <strong>Téléphone:</strong> +226 20660249<br>
                            <strong>Email:</strong> info@wilinktickets.com<br>
                        </p>
                    </div>

                    <div class="col-lg-4 col-md-6 footer-links">
                        <h4>Liens utiles</h4>
                        <ul>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ route('index') }}#hero">Accueil</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ route('recuperation') }}">Récupérer mon ticket</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ route('index') }}#about">A propos</a></li>
                            <li><i class="bx bx-chevron-right"></i> <a href="{{ route('index') }}#contact">Contact</a></li>
                        </ul>
                    </div>



                    <div class="col-lg-4 col-md-6 footer-newsletter">
                        <h4>Nos réferences</h4>
                        <p>RCCM : BFBBD 2018A0714</p>
                        <p>IFU : 00105455A</p>
                        <!--div class="social-links text-center text-md-end pt-3 pt-md-0">
                            <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                            <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                            <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                            <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                            <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
                        </div-->
                    </div>

                </div>
            </div>
        </div>

        <!--div class="container d-md-flex py-4">


            <div class="social-links text-center text-md-end pt-3 pt-md-0">
                <a href="#" class="twitter"><i class="bx bxl-twitter"></i></a>
                <a href="#" class="facebook"><i class="bx bxl-facebook"></i></a>
                <a href="#" class="instagram"><i class="bx bxl-instagram"></i></a>
                <a href="#" class="google-plus"><i class="bx bxl-skype"></i></a>
                <a href="#" class="linkedin"><i class="bx bxl-linkedin"></i></a>
            </div>
        </div-->
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
