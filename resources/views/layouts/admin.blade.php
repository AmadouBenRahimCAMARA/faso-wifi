<!DOCTYPE html>
<html data-bs-theme="light" lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <title>Dashboard - Brand</title>
    <link rel="stylesheet" href="{{ asset('assets/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i&amp;display=swap">
    <link rel="stylesheet" href="{{ asset('assets/fonts/fontawesome-all.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/styles.css') }}">

</head>

<body id="page-top">
    @if(session()->has('impersonator_id'))
        <div class="alert alert-danger text-center mb-0 fw-bold rounded-0" role="alert" style="z-index: 9999; position: relative;">
            MODE USURPATION : Vous êtes connecté en tant que {{ Auth::user()->nom }} {{ Auth::user()->prenom }}
            <a href="{{ route('stop.impersonation') }}" class="btn btn-sm btn-outline-danger ms-3 bg-white text-danger">Arrêter l'usurpation</a>
        </div>
    @endif
    <div id="wrapper">
        <nav class="navbar align-items-start sidebar sidebar-dark accordion p-0 navbar-dark" style="background-color: #2196F3 !important;">
            <div class="container-fluid d-flex flex-column p-0"><a
                    class="navbar-brand d-flex justify-content-center align-items-center sidebar-brand m-0"
                    href="{{ route('index') }}">
                    <div class="sidebar-brand-icon rotate-n-15"><i class="fas fa-wifi"></i></div>
                    <div class="sidebar-brand-text mx-3"><span>WiLink Tickets</span></div>
                </a>
                <hr class="sidebar-divider my-0">
                <ul class="navbar-nav text-light" id="accordionSidebar">
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'home') active @endif"
                            href="{{ route('home') }}"><i class="fas fa-tachometer-alt"></i><span>Accueil</span></a>
                    </li>

                    @if(Auth::user()->isAdmin())
                    <hr class="sidebar-divider">
                    <div class="sidebar-heading text-light p-2">Administration</div>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'admin.dashboard') active @endif"
                            href="{{ route('admin.dashboard') }}"><i class="fas fa-chart-line"></i><span>Global Stats</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'admin.users') active @endif"
                            href="{{ route('admin.users') }}"><i class="fas fa-users"></i><span>Utilisateurs</span></a>
                    </li>
                    <hr class="sidebar-divider">
                    @endif

                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'wifi.index') active @endif"
                            href="{{ route('wifi.index') }}"><i class="fas fa-wifi"></i><span>Mes Wifi Zone</span></a>
                    </li>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'tarifs.index') active @endif"
                            href="{{ route('tarifs.index') }}"><i class="fas fa-hand-holding-usd"></i><span>Mes
                                tarifs</span></a></li>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'ticket.index') active @endif"
                            href="{{ route('ticket.index') }}"><i class="fas fa-receipt"></i><span>Mes
                                tickets</span></a></li>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'paiement.index') active @endif"
                            href="{{ route('paiement.index') }}"><i
                                class="fas fa-credit-card"></i><span>Paiements</span></a></li>
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'retrait.index') active @endif"
                            href="{{ route('retrait.index') }}"><i
                                class="fas fa-money-bill-wave"></i><span>Retraits</span>
                                @if(isset($pendingRetraitsCount) && $pendingRetraitsCount > 0 && Auth::user()->isAdmin())
                                    <span class="badge bg-danger ms-2">{{ $pendingRetraitsCount }}</span>
                                @endif
                                </a></li>

                    @if(!Auth::user()->isAdmin())
                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'bilan.index') active @endif"
                            href="{{ route('bilan.index') }}"><i class="fas fa-file-invoice-dollar"></i><span>Mon Bilan</span></a>
                    </li>
                    @endif

                    <li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'retrait.index') active @endif"
                            href="{{ route('logout') }}" onclick="event.preventDefault();
                            document.getElementById('logout-form').submit();"><i
                                class="fas  fa-power-off"></i><span>Deconnection</span></a></li>
                    <!--li class="nav-item"><a class="nav-link @if (Route::currentRouteName() == 'paiement.index') active @endif" href="{{ route('paiement.index') }}"><i class="fas fa-comment-alt"></i><span>Api SMS</span></a></li-->
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </ul>
                <div class="text-center d-none d-md-inline"><button class="btn rounded-circle border-0"
                        id="sidebarToggle" type="button"></button></div>
            </div>
        </nav>
        <div class="d-flex flex-column" id="content-wrapper">

            @yield('content')


        </div><a class="border rounded d-inline scroll-to-top" href="#page-top"><i class="fas fa-angle-up"></i></a>
    </div>
    <script>
        function get() {
            // Code to be executed when the DOM is ready
            ///acheter-mon-ticket/{slug}/telecharger-mon-recu
            const xhttp = new XMLHttpRequest();
            const nbre = document.getElementById('view').value
            xhttp.onload = function() {
                //console.log(xhttp.responseText)
                window.location.reload()
            }
            xhttp.open("GET", "{{ App::make('url')->to('/') }}/view-number/" + nbre, true);
            xhttp.send();
            //document.getElementById("demo").innerHTML = xhttp.responseText;
        }
    </script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
    <script src="assets/js/theme.js"></script>
</body>

</html>
