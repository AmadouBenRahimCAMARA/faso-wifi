<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta
      name="viewport"
      content="width=device-width, initial-scale=1, shrink-to-fit=no"
    />
    <meta name="description" content="" />
    <meta name="author" content="" />
    <title>Acheter mon ticket wifi zone</title>
    <!-- Favicon-->
    <link rel="icon" type="image/x-icon" href="{{ asset('assets/img/logo.png') }}" />
    <!-- Bootstrap Icons-->
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css"
      rel="stylesheet"
    />
    <!-- Google fonts-->
    <link
      href="https://fonts.googleapis.com/css?family=Merriweather+Sans:400,700"
      rel="stylesheet"
    />
    <link
      href="https://fonts.googleapis.com/css?family=Merriweather:400,300,300italic,400italic,700,700italic"
      rel="stylesheet"
      type="text/css"
    />
    <!-- SimpleLightbox plugin CSS-->
    <link
      href="https://cdnjs.cloudflare.com/ajax/libs/SimpleLightbox/2.1.0/simpleLightbox.min.css"
      rel="stylesheet"
    />
    <!-- Core theme CSS (includes Bootstrap)-->
    <link href="css/styles.css" rel="stylesheet" />
  </head>
  <body id="page-top">
    <!-- Navigation-->
    <nav
      class="navbar navbar-expand-lg navbar-light fixed-top py-3 shadow"
      id="mainNav"
    >
      <div class="container px-4 px-lg-5">
        <a class="navbar-brand text-black" href="#page-top">ENUMERA WIFI ZONE</a>
        <button
          class="navbar-toggler navbar-toggler-right"
          type="button"
          data-bs-toggle="collapse"
          data-bs-target="#navbarResponsive"
          aria-controls="navbarResponsive"
          aria-expanded="false"
          aria-label="Toggle navigation"
        >
          <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarResponsive">
          <ul class="navbar-nav ms-auto my-2 my-lg-0">
            <li class="nav-item">
              <a class="nav-link text-black" href="index.html#about">A propos</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-black" href="index.html#services">Nos Tarifs</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-black" href="index.html#contact">Contact</a>
            </li>
            <li class="nav-item">
              <a class="nav-link text-black" href="{{ route('connexion') }}">{{ __('Connexion') }}</a>
          </li>
          </ul>
        </div>
      </div>
    </nav>
    
    <!-- Services-->
    <section class="page-section">
      <div class="container px-4 px-lg-5">
        <h2 class="text-center mt-0">Achat de mon ticket</h2>
        <p class="text-muted mb-0 text-center">
          Obtenez votre ticket et naviguez librement
        </p>
        <hr class="divider" />
        <form method="POST" action="{{route("apiPaiement")}}" class="row gx-4 gx-lg-5">
          @csrf
          <div class="col-12 col-md-6 mx-auto">
            <div class="form-floating mt-5">
              <select class="form-select" name="tarif_id" id="">
                @foreach ($tarifs as $item)
                    <option value="{{$item->id}}">{{$item->forfait}}</option>
                @endforeach
              </select>
              <label for="phone">Sélectionnez un tarif</label>
              <div class="invalid-feedback" data-sb-feedback="phone:required">
                Votre numéro de téléphone est obligatoire
              </div>
            </div>
          </div>
          <div class="col-12 text-center mt-5">
            <button type="submit" class="btn btn-primary btn-xl">Continuer</button>
          </div>
        </form>
      </div>
    </section>

    <!-- Call to action-->
    <section class="page-section bg-dark text-white">
      <div class="container px-4 px-lg-5 text-center">
        <h2 class="mb-4">Récupérer mon dernier ticket acheter</h2>
        <a
          class="btn btn-light btn-xl"
          href="{{route("recuperation")}}"
          >Obtenir mon ticket</a
        >
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
                <input
                  class="form-control"
                  id="name"
                  type="text"
                  placeholder="Nom Prénom"
                  data-sb-validations="required"
                />
                <label for="name">Nom Prénom</label>
                <div class="invalid-feedback" data-sb-feedback="name:required">
                  Votre nom et prénom sont obligatoires
                </div>
              </div>
              <!-- Email address input-->
              <div class="form-floating mb-3">
                <input
                  class="form-control"
                  id="email"
                  type="email"
                  placeholder="name@example.com"
                  data-sb-validations="required,email"
                />
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
                <input
                  class="form-control"
                  id="phone"
                  type="tel"
                  placeholder="(123) 456-7890"
                  data-sb-validations="required"
                />
                <label for="phone">Numéro de téléphone</label>
                <div class="invalid-feedback" data-sb-feedback="phone:required">
                  Votre numéro de téléphone est obligatoire
                </div>
              </div>
              <!-- Message input-->
              <div class="form-floating mb-3">
                <textarea
                  class="form-control"
                  id="message"
                  type="text"
                  placeholder="Enter your message here..."
                  style="height: 10rem"
                  data-sb-validations="required"
                ></textarea>
                <label for="message">Message</label>
                <div
                  class="invalid-feedback"
                  data-sb-feedback="message:required"
                >
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
                <button
                  class="btn btn-primary btn-xl disabled"
                  id="submitButton"
                  type="submit"
                >
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
