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
            line-height: 1.5;
            font-family: Poppins, Arial, Helvetica, sans-serif;
            background-color: #f8f9fa;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: #fff;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .header img {
            max-width: 100px;
            height: auto;
        }

        .header h1 {
            font-size: 1.2rem;
            text-transform: uppercase;
            margin: 10px 0;
            color: var(--primary-color);
        }

        .header p {
            font-size: 0.9rem;
            color: #555;
        }

        .content {
            margin-bottom: 20px;
        }

        .facture-title {
            text-align: center;
            font-weight: bold;
            text-transform: uppercase;
            margin: 20px 0;
            font-size: 1.1rem;
            color: #333;
        }

        .info-text {
            font-size: 0.85rem;
            color: #666;
            text-align: center;
            margin-bottom: 20px;
            font-style: italic;
        }

        .details-box {
            border: 1px solid #ddd;
            border-radius: 8px;
            overflow: hidden;
        }

        .detail-row {
            display: flex;
            justify-content: space-between;
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
        }

        .detail-row:last-child {
            border-bottom: none;
        }

        .detail-label {
            font-weight: 600;
            color: #333;
        }

        .detail-value {
            text-align: right;
            color: #000;
        }

        .credentials-box {
            background-color: #eef2ff;
            border: 2px solid var(--secondary-color);
            border-radius: 8px;
            padding: 15px;
            margin-top: 20px;
            text-align: center;
        }

        .credentials-title {
            color: var(--primary-color);
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 10px;
            font-size: 1rem;
        }

        .credential-item {
            margin: 10px 0;
            font-size: 1.2rem;
        }

        .credential-label {
            font-size: 0.9rem;
            color: #555;
            display: block;
        }

        .credential-value {
            font-weight: bold;
            color: #000;
            font-family: monospace;
            font-size: 1.4rem;
            background: #fff;
            padding: 5px 10px;
            border-radius: 4px;
            display: inline-block;
            margin-top: 5px;
            border: 1px solid #ccc;
        }

        .btn-download {
            display: block;
            width: 100%;
            text-align: center;
            background-color: var(--secondary-color);
            color: #fff;
            padding: 12px;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            text-transform: uppercase;
            margin-top: 20px;
            transition: background-color 0.3s;
        }

        .btn-download:hover {
            background-color: var(--primary-color);
        }

        .footer {
            text-align: center;
            font-size: 0.8rem;
            color: #888;
            margin-top: 20px;
        }

        @media (max-width: 480px) {
            .container {
                margin: 0;
                border-radius: 0;
                box-shadow: none;
                padding: 15px;
            }
            
            .credential-value {
                font-size: 1.2rem;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <img src="{{ asset('assets/img/logo.png') }}" alt="LOGO WiLink Tickets" />
            <h1>WiLink Tickets</h1>
            <p>+226 54 78 19 78 / 56 36 80 34</p>
            <p>Bobo-Dioulasso, Burkina Faso</p>
        </div>

        <div class="content">
            <div class="facture-title">
                Facture {{ 'FW-' . date('dmYHis') . '-TICKET' }}
            </div>

            <p class="info-text">
                Ce reçu est personnel. Ne partagez pas vos identifiants.
            </p>

            <!-- Identifiants en premier pour la visibilité -->
            <div class="credentials-box">
                <div class="credentials-title">VOS IDENTIFIANTS DE CONNEXION</div>
                
                <div class="credential-item">
                    <span class="credential-label">Identifiant (User)</span>
                    <span class="credential-value">{{ $data->user }}</span>
                </div>

                <div class="credential-item">
                    <span class="credential-label">Mot de passe (Pass)</span>
                    <span class="credential-value">{{ $data->password }}</span>
                </div>
            </div>

            <div style="height: 20px;"></div>

            <div class="details-box">
                <div class="detail-row">
                    <span class="detail-label">Wifi Zone</span>
                    <span class="detail-value">{{ $data->tarif->wifi->nom }}</span>
                </div>
                <!--div class="detail-row">
                    <span class="detail-label">Transaction ID</span>
                    <span class="detail-value">{{ $paiement->transaction_id }}</span>
                </div-->
                <div class="detail-row">
                    <span class="detail-label">Date</span>
                    <span class="detail-value">{{ date_format($paiement->created_at, 'd/m/Y H:i') }}</span>
                </div>
                <div class="detail-row">
                    <span class="detail-label">Montant</span>
                    <span class="detail-value">{{ $data->tarif->montant }} Fcfa</span>
                </div>
            </div>
        </div>

        <a href="{{ url('/telecharger-mon-recu/'.$data->slug) }}" class="btn-download">
            Télécharger le reçu (PDF)
        </a>

        <div class="footer">
            <p>&copy; {{ date('Y') }} WiLink Tickets. Tous droits réservés.</p>
        </div>
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
