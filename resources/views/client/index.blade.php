<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Bienvenue sur Enumera wifi zone</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />
    <!-- Bootstrap Icons-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css" rel="stylesheet" />
    <!-- Google fonts-->
    <link href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700" rel="stylesheet" />
    <link href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic"
        rel="stylesheet" type="text/css" />
    <!-- SimpleLightbox plugin CSS-->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css" rel="stylesheet" />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
</head>

<body id="page-top">
    <!-- Navigation-->
    <nav class="navbar navbar-expand-lg navbar-light fixed-top py-3" id="mainNav">
        <div class="container px-4 px-lg-5">
            <a class="navbar-brand" href="#page-top">ENUMERA WIFI ZONE</a>
            <button class="navbar-toggler navbar-toggler-right" type="button" data-bs-toggle="collapse"
                data-bs-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false"
                aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarResponsive">
                <ul class="navbar-nav ms-auto my-2 my-lg-0">
                    <li class="nav-item">
                        <a class="nav-link" href="#about">A propos</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#services">Nos Tarifs</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#contact">Contact</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('connexion') }}">{{ __('Connexion') }}</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Masthead-->
    <header class="masthead">
        <div class="container px-4 px-lg-5 h-100">
            <div class="row gx-4 gx-lg-5 h-100 align-items-center justify-content-center text-center">
                <div class="col-lg-8 align-self-end">
                    <h1 class="text-white font-weight-bold">
                        Bienvenue sur Enumera WiFi Zone
                    </h1>
                    <hr class="divider" />
                </div>
                <div class="col-lg-8 align-self-baseline">
                    <p class="text-white-75 mb-5">
                        Votre destination pour des connexions WiFi fluides et instantanées
                    </p>
                    <a class="btn btn-primary btn-xl" href="{{ route('acheter') }}">Acheter mon ticket</a>
                </div>
            </div>
        </div>
    </header>
    <!-- About-->
    <section class="page-section bg-primary" id="about">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 text-center">
                    <h2 class="text-white mt-0">Qui sommes-nous ?</h2>
                    <hr class="divider divider-light" />
                    <p class="text-white-75 mb-4">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit. Labore
                        necessitatibus tenetur nulla corrupti ex eligendi qui ipsa
                        obcaecati. Excepturi natus voluptatibus esse sint perspiciatis
                        maxime facilis corporis eligendi dolor vitae?
                    </p>
                    <a class="btn btn-light btn-xl" href="{{ route('acheter') }}">Acheter mon tichet</a>
                </div>
            </div>
        </div>
    </section>
    <!-- Services-->
    <section class="page-section" id="services">
        <div class="container px-4 px-lg-5">
            <h2 class="text-center mt-0">Nos Tarifs</h2>
            <hr class="divider" />
            <div class="row gx-4 gx-lg-5">
                <div class="col-lg-4 col-md-4 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-gem fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">1 jour à 100F</h3>
                        <p class="text-muted mb-0">
                            Profitez d'une journée de connexion continue pour seulement 100F
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 text-center">
                    <div class="mt-5">
                        <div class="mb-2">
                            <i class="bi-laptop fs-1 text-primary"></i>
                        </div>
                        <h3 class="h4 mb-2">7 jours à 500F</h3>
                        <p class="text-muted mb-0">
                            Plongez dans une semaine de connectivité ininterrompue pour
                            seulement 500F
                        </p>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 text-center">
                    <div class="mt-5">
                        <div class="mb-2"><i class="bi-globe fs-1 text-primary"></i></div>
                        <h3 class="h4 mb-2">30 jours à 2000F</h3>
                        <p class="text-muted mb-0">
                            Soyez connecté en permanence pendant tout un mois avec notre
                            offre à seulement 2000F
                        </p>
                    </div>
                </div>
                <!--div class="col-lg-3 col-md-6 text-center">
            <div class="mt-5">
              <div class="mb-2"><i class="bi-heart fs-1 text-primary"></i></div>
              <h3 class="h4 mb-2">Made with Love</h3>
              <p class="text-muted mb-0">
                Is it really open source if it's not made with love?
              </p>
            </div>
          </div-->
                <div class="col-12 text-center mt-5">
                    <a class="btn btn-primary btn-xl" href="{{ route('acheter') }}">Payer mon ticket</a>
                </div>
            </div>
        </div>
    </section>

    <!-- Call to action-->
    <section class="page-section bg-dark text-white">
        <div class="container px-4 px-lg-5 text-center">
            <h2 class="mb-4">Récupérer mon dernier ticket acheter</h2>
            <a class="btn btn-light btn-xl" href="{{ route('recuperation') }}">Obtenir mon ticket</a>
        </div>
    </section>
    <!-- Contact-->
    <section class="page-section" id="contact">
        <div class="container px-4 px-lg-5">
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-8 col-xl-6 text-center">
                    <h2 class="mt-0">Contact</h2>
                    <hr class="divider" />
                    <p class="text-muted mb-5">
                        Lorem ipsum dolor sit amet consectetur adipisicing elit.
                        Asperiores, a laboriosam dicta voluptate consequuntur delectus

                    </p>
                </div>
            </div>
            <div class="row gx-4 gx-lg-5 justify-content-center mb-5">
                <div class="col-lg-6">
                    <!-- * * * * * * * * * * * * * * *-->
                    <!-- * * SB Forms Contact Form * *-->
                    <!-- * * * * * * * * * * * * * * *-->
                    <!-- This form is pre-integrated with SB Forms.-->
                    <!-- To make this form functional, sign up at-->
                    <!-- https://startbootstrap.com/solution/contact-forms-->
                    <!-- to get an API token!-->
                    <form id="contactForm" data-sb-form-api-token="API_TOKEN">
                        <!-- Name input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="name" type="text" placeholder="Nom Prénom"
                                data-sb-validations="required" />
                            <label for="name">Nom Prénom</label>
                            <div class="invalid-feedback" data-sb-feedback="name:required">
                                Votre nom et prénom sont obligatoires
                            </div>
                        </div>
                        <!-- Email address input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="email" type="email" placeholder="name@example.com"
                                data-sb-validations="required,email" />
                            <label for="email">Email</label>
                            <div class="invalid-feedback" data-sb-feedback="email:required">
                                Votre email est obligatoire
                            </div>
                            <div class="invalid-feedback" data-sb-feedback="email:email">
                                Votre email n'est pas valide
                            </div>
                        </div>
                        <!-- Phone number input-->
                        <div class="form-floating mb-3">
                            <input class="form-control" id="phone" type="tel" placeholder="(123) 456-7890"
                                data-sb-validations="required" />
                            <label for="phone">Numéro de téléphone</label>
                            <div class="invalid-feedback" data-sb-feedback="phone:required">
                                Votre numéro de téléphone est obligatoire
                            </div>
                        </div>
                        <!-- Message input-->
                        <div class="form-floating mb-3">
                            <textarea class="form-control" id="message" type="text" placeholder="Enter your message here..."
                                style="height: 10rem" data-sb-validations="required"></textarea>
                            <label for="message">Message</label>
                            <div class="invalid-feedback" data-sb-feedback="message:required">
                                Votre message est obligatoire
                            </div>
                        </div>
                        <!-- Submit success message-->
                        <!---->
                        <!-- This is what your users will see when the form-->
                        <!-- has successfully submitted-->
                        <div class="d-none" id="submitSuccessMessage">
                            <div class="text-center mb-3">
                                <div class="fw-bolder">Votre message a été envoyé avec succès</div>
                            </div>
                        </div>
                        <!-- Submit error message-->
                        <!---->
                        <!-- This is what your users will see when there is-->
                        <!-- an error submitting the form-->
                        <div class="d-none" id="submitErrorMessage">
                            <div class="text-center text-danger mb-3">
                                Erreur lors de l'envoi du message !
                            </div>
                        </div>
                        <!-- Submit Button-->
                        <div class="d-grid">
                            <button class="btn btn-primary btn-xl disabled" id="submitButton" type="submit">
                                Envoyer
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            <div class="row gx-4 gx-lg-5 justify-content-center">
                <div class="col-lg-4 text-center mb-5 mb-lg-0">
                    <i class="bi-phone fs-2 mb-3 text-muted"></i>
                    <div>(+226)54 78 19 78</div>
                </div>
            </div>
        </div>
    </section>
    <!-- Footer-->
    <footer class="bg-light py-5">
        <div class="container px-4 px-lg-5">
            <div class="small text-center text-muted">
                Copyright &copy; 2024 - Enumera
            </div>
        </div>
    </footer>
    <!-- Bootstrap core JS-->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>
    <!-- SimpleLightbox plugin JS-->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.js"></script>
    <!-- Core theme JS-->
    <script src="js/scripts.js"></script>
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <!-- * *                               SB Forms JS                               * *-->
    <!-- * * Activate your form at https://startbootstrap.com/solution/contact-forms * *-->
    <!-- * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * * *-->
    <script src="https://cdn.startbootstrap.com/sb-forms-latest.js"></script>
</body>

</html>
