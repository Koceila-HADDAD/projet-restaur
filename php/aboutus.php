<?php

session_start();

if (isset($_SESSION['id'])) {
    $etat="accueil.php";
   
}
else{
    $etat="../accvisiteur.php";
  

}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- Header -->
    <header class="py-3">
        <div class="container-fluid">
            <div class="row align-items-center bg-white">
                <div class="col-12 col-sm-4">
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="accvisiteur.php">
                                <img src="../logo/logooo2.png" alt="Logo" width="40" height="40">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                    <a class="nav-link active" aria-current="page" href="<?php echo $etat; ?>">Accueil</a>

                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produitvisit.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="Panier.php">Panier</a>
                                    </li>
                                    <li>
                                    <a class="nav-link d-none d-md-none" href="login.php" class="text-decoration-none"> Connexion
                                    </a>  
                       
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-12 col-sm-5 text-center">
                    <a href="../accvisiteur.php">
                        <img src="../logo/logooo.png" alt="Logo" class="logo-center img-fluid">
                    </a>
                </div>
                <div class="col-12 col-sm-3 text-center text-sm-end d-none d-md-none">
                    <a href="login.php" class="text-decoration-none">
                        <button type="button" class="btn btn-outline-primary mt-2 mt-sm-0">Connexion</button>
                    </a>
                </div>
            </div>
        </div>
    </header>
    
    <!-- Section À Propos -->
    <section class="container my-5 text-center">
        <h1 class="text-primary">À Propos de Bleu Blanc Saveur</h1>
        <p class="lead">Un restaurant gastronomique qui sublime les saveurs françaises.</p>
    </section>
    
    <!-- Petite Histoire -->
    <section class="container my-5 text-center">
        <h2>Notre Histoire</h2>
        <p>Fondé en 1995, Bleu Blanc Saveur est né de la passion d'un chef visionnaire souhaitant offrir une expérience culinaire unique. Inspiré par les saveurs authentiques de la cuisine française, notre restaurant s'est imposé comme une référence gastronomique à Paris.</p>
    </section>
    
    <!-- Section Mission -->
    <section class="container my-5 text-center">
        <div class="row">
            <div class="col-md-4">
                <i class="fas fa-utensils fa-3x text-primary"></i>
                <h3 class="mt-3">Notre Passion</h3>
                <p>Des plats raffinés préparés avec des ingrédients frais et locaux.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-wine-glass-alt fa-3x text-success"></i>
                <h3 class="mt-3">Notre Expérience</h3>
                <p>Un cadre élégant pour une expérience culinaire inoubliable.</p>
            </div>
            <div class="col-md-4">
                <i class="fas fa-star fa-3x text-warning"></i>
                <h3 class="mt-3">Notre Engagement</h3>
                <p>Offrir une cuisine d’exception et un service irréprochable.</p>
            </div>
        </div>
    </section>
    
    <!-- Footer -->
    <footer class="container-fluid">
        <div class="row text-center text-sm-start">
            <div class="col-12 col-sm-4 mb-3 mb-sm-0">
                <img src="../photos/footer.png" alt="Footer Image" class="img-fluid d-none d-sm-block">
            </div>
            <div class="col-6 col-sm-4 mb-3 mb-sm-0 d-flex align-items-center justify-content-center">
                <ul style="list-style-type: none; padding: 0;">
                    <li><strong>DISCOVER :</strong></li>
                    <li><a href="aboutus.php" class="link-zoom">About us</a></li>
                    <li><a href="#" class="link-zoom">Nos Chefs</a></li>
                    <li><a href="#" class="link-zoom">Nos Plats</a></li>
                    <li><a href="#" class="link-zoom">Événements</a></li>
                </ul>
            </div>
            <div class="col-6 col-sm-4 social-links d-flex align-items-center justify-content-center flex-wrap">
                <div class="mb-2">
                    <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila" class="d-flex align-items-center">
                        <img src="../icone/fb.png" alt="Facebook" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Facebook</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="https://www.instagram.com/restaurant_parisien/" class="d-flex align-items-center">
                        <img src="../icone/inst.png" alt="Instagram" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Instagram</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="tel:+33758428417" class="d-flex align-items-center">
                        <img src="../icone/tel.png" alt="Téléphone" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">+33758428417</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="mailto:koceila.haddad@outlook.com" class="d-flex align-items-center">
                        <img src="../icone/email.jpg" alt="Email" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Koceila.haddad@outlook.com</span>
                    </a>
                </div>
                <div>
                    <a href="https://maps.app.goo.gl/uJyLGFWHdaoNxB3X7" class="d-flex align-items-center">
                        <img src="../icone/maps.jpg" alt="Adresse" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">30 Rue Esquirol, 75013 Paris</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>