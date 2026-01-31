<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>pdf</title>
    <style>
        :root {
            --primary-color: #07298f;
            --secondary-color: #2465dd;
        }

        *,
        *::before,
        *::after {
            padding: 0;
            margin: 0;
            box-sizing: border-box;
        }

        body {
            width: 100%;
            font-size: 16px;
            line-height: 170%;
            font-family: Poppins, Arial, Helvetica, sans-serif;
        }

        .container {
            width: 75%;
            min-width: 500px;
            margin: 32px auto;
            margin-bottom: 0;
            background-color: #fff;
        }

        .text-right {
            text-align: right;
        }

        .text-center {
            text-align: center;
        }

        .text-italic {
            font-style: italic;
        }

        .text-14 {
            font-size: 14px;
        }

        .text-uppercase {
            text-transform: uppercase;
        }

        .fw-bold {
            font-weight: bold;
        }

        .table {
            width: 80%;
            min-width: 300px;
            margin: 0 auto;
            margin-top: 40px;
        }

        .py-2 {
            padding: 16px 0;
        }

        .pt-2 {
            padding-top: 16px;
        }

        .ps-5 {
            padding-left: 80px;
        }

        .ms-3 {
            margin-left: 24px;
        }

        .d-inline-block {
            display: inline-block !important;
        }

        .d-block {
            display: block !important;
        }

        .bg-primary {
            background-color: blue;
        }

        .text-primary{
            color: #2465dd;
        }

        .table-container {
            width: 100%;
            max-width: 100%;
            display: table;
            table-layout: fixed;
        }

        .column {
            display: table-cell;
        }

        .border>.column {
            border: 1px solid black;
            border-left: none;
            border-right: none;
            padding: 0 8px;
        }

        .mt-5 {
            margin-top: 40px;
        }

        .my-3 {
            margin: 24px 0;
        }

        .mx-auto {
            margin: 0 auto !important;
        }

        .w-100 {
            width: 100%;
        }

        .postion-relative {
            position: relative;
        }

        .postion-absolute-top {
            position: absolute;
            top: 0;
        }

        .cursor{
            cursor: pointer;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <div class="table-container py-21">
                <div class="column">
                    <div class="ps-51">
                    </div>
                </div>
                <div class="column postion-relative">
                    <p class="text-center text-uppercase postion-absolute-top1 pt-2 w-100">
                        <img width="120px" src="{{ asset('assets/img/logo.png') }}" alt="LOGO WiLink Tickets" /> <br />
                        WiLink Tickets<br />
                        <span class="text-uppercase">+226 54 78 19 78 / +226 56 36 80 34 / +226 65 86 33 36 </span><br />
                        <span>info@wilink-ticket.com </span><br />
                        <span class="text-uppercase">Bobo-Dioulasso, Burkina Faso </span><br />
                    </p>
                </div>
                <div class="column">
                    <div class="text-right">
                    </div>
                </div>
            </div>
        </div>
        <div class="content">
            <p class="text-uppercase text-center fw-bold my-3">
                Facture {{ 'FW-' . date('dmYHis') . '-TICKET' }}
            </p>
            <div class="table">
                <p class="text-14 ps-4">
                    <span class="ms-3 d-inline-block">Ce reçu est la preuve de la facture de votre paiement de ticket wifi, il est à usage unique et non transférable et ne devra en aucun cas être partagé à qui que ce soit.
                    </span>
                    <br />
                </p>
            </div>
            <p class="text-uppercase text-center fw-bold my-3">Réference de la facture</p>

            <div class="table">
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">Wifi zone</p>
                    </div>
                    <div class="column">
                        <p class="text-right">{{ $data->tarif->wifi->nom }}</p>
                    </div>
                </div>
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">Transaction id</p>
                    </div>
                    <div class="column">
                        <p class="text-right">{{ $paiement->transaction_id }}</p>
                    </div>
                </div>
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">Date de paiement</p>
                    </div>
                    <div class="column">
                        <p class="text-right">{{ date_format($paiement->created_at, 'd/m/Y H:i:s') }}</p>
                    </div>
                </div>
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">Montant</p>
                    </div>
                    <div class="column">
                        <p class="text-right">{{ $data->tarif->montant }} Fcfa</p>
                    </div>
                </div>
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">identifiant</p>
                    </div>
                    <div class="column fw-bold">
                        <p class="text-right">{{ $data->user }}</p>
                    </div>
                </div>
                <div class="table-container border">
                    <div class="column">
                        <p class=" fw-bold">Mot de passe</p>
                    </div>
                    <div class="column fw-bold">
                        <p class="text-right">{{ $data->password }}</p>
                    </div>
                </div>


            </div>
        </div>
        <div class="table">
            <p class="text-14 ps-4">
                <span class="ms-3 d-inline-block">Tous droits réservés à WiLink Tickets!
                </span>
                <br />
                <a href="{{ url('/telecharger-mon-recu/'.$data->slug) }}" class="text-uppercase text-center fw-bold my-3 text-primary cursor d-block" style="text-decoration:none;">Télécharger le reçu</a>
            </p>
        </div>
        <!--div class="footer text-14">
        <p class="text-center mt-5">
          <span>Email : info@gmail.com</span>
          <span>Téléphone: +226 xx xx xx xx / +226 xx xx xx xx</span>
        </p>
        <p class="text-center text-14">
          &copy; [Année en cours] [Nom de l'organisation]. Tous droits réservés.
        </p>
      </div-->
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Code to be executed when the DOM is ready
            ///acheter-mon-ticket/{slug}/telecharger-mon-recu
            const xhttp = new XMLHttpRequest();
            xhttp.onload = function() {
                console.log(xhttp.responseText)
            }
            xhttp.open("GET", "{{App::make('url')->to('/')}}/telecharger-mon-recu/{{ $data->slug }}",true);
            xhttp.send();
            //document.getElementById("demo").innerHTML = xhttp.responseText;
        });

        function reload(){
            window.location.reload()
        }
    </script>
</body>

</html>
