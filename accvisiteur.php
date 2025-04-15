<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bleu, Blanc, Saveur</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">

</head>
<body>
    <!-- Connexion à la base de données -->
    <?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=restaurant;charset=utf8', 'koceila', '123456789');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    } catch (Exception $e) {
        die('Erreur de connexion à la base de données : ' . $e->getMessage());
    }
    ?>

    <!-- Header -->
    <header class="py-3">
        <div class="container-fluid">
            <div class="row align-items-center bg-white">
                <div class="col-12 col-sm-4">
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="accvisiteur.php">
                                <img src="logo/logooo2.png" alt="Logo" width="40" height="40">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="accvisiteur.php">Accueil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produitvisit.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="Panier.php">Panier</a>
                                    </li>
                                    <li>
                                        <a  class="nav-link d-block d-sm-none" href="login.php" style="text-decoration: none;">
                                            Connexion
                                        </a>
                
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-12 col-sm-5 text-center">
                    <a href="accvisiteur.php">
                        <img src="logo/logooo.png" alt="Logo" class="logo-center img-fluid">
                    </a>
                </div>
                <div class="col-12 col-sm-3 text-center text-sm-end d-none d-md-block">
                    <a href="login.php" style="text-decoration: none;">
                        <button type="button" class="btn btn-outline-primary mt-2 mt-sm-0">Connexion</button>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Image de couverture -->
    <section class="container-fluid my-4">
        <div class="row">
            <div class="image-container">
                <img src="photos/arrplan.jpg" alt="Image de fond" class="img-fluid d-none d-sm-block">
                <div class="texte d-none d-sm-block">
                    Bleu Blanc Saveur vous invite à une expérience gastronomique raffinée, alliant tradition et créativité. Dans un cadre élégant, notre chef sublime des produits d'exception pour éveiller vos sens. Laissez-vous emporter par une cuisine authentique et audacieuse.
                </div>
                <div class="prog d-none d-sm-block">
                    Horaires d'ouverture : mardi-dimanche : 11h00-15h00 / 18h30-23h30<br>
                    lundi : fermé
                </div>
            </div>
        </div>
    </section>

    <!-- Menu -->
    <section class="container my-4">
        <h2 class="text-center mb-4">
            <img src="logo/logooo2.png" alt="Logo" width="40" height="40"> Le Menu Du Chef
        </h2>
        <?php
        try {
            $req = $bdd->query("SELECT id_plat, nom_plat, prix, image_plat FROM plat ORDER BY id_plat LIMIT 6");
            if ($req->rowCount() === 0) {
                echo '<p class="text-center">Aucun plat disponible pour le moment.</p>';
            } else {
                echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">';
                while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                    $nom_plat = htmlspecialchars($data->nom_plat);
                    $prix = htmlspecialchars($data->prix);
                    $imageData = base64_encode($data->image_plat);
        ?>
                    <div class="col">
                        <div class="card h-100">
                            <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" class="card-img-top" alt="<?php echo $nom_plat; ?>">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo $nom_plat; ?></h5>
                                <h5>Prix : <?php echo $prix; ?> €</h5>
                            </div>
                        </div>
                    </div>
        <?php
                }
                echo '</div>';
            }
            $req->closeCursor();
        } catch (Exception $e) {
            echo '<p class="text-center text-danger">Erreur lors du chargement du menu : ' . htmlspecialchars($e->getMessage()) . '</p>';
        }
        ?>
    </section>

    <!-- Carrousel -->
    <section class="container my-4">
        <h2 class="text-center mb-4">
            <img src="logo/logooo2.png" alt="Logo" width="40" height="40"> Événements
        </h2>
        <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="true">
            <div class="carousel-indicators">
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active" aria-current="true" aria-label="Slide 1"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1" aria-label="Slide 2"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2" aria-label="Slide 3"></button>
                <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3" aria-label="Slide 4"></button>
            </div>
            <div class="carousel-inner rounded-3">
                <div class="carousel-item active">
                    <img src="evenements2/ev1.jpg" class="d-block w-100" alt="Événement 1">
                </div>
                <div class="carousel-item">
                    <img src="evenements2/ev2.jpg" class="d-block w-100" alt="Événement 2">
                </div>
                <div class="carousel-item">
                    <img src="evenements2/ev3.jpg" class="d-block w-100" alt="Événement 3">
                </div>
                <div class="carousel-item">
                    <img src="evenements2/ev4.jpg" class="d-block w-100" alt="Événement 4">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Précédent</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Suivant</span>
            </button>
        </div>
    </section>

    <!-- Footer -->
    <footer class="container-fluid">
        <div class="row text-center text-sm-start">
            <div class="col-12 col-sm-4 mb-3 mb-sm-0">
                <img src="photos/footer.png" alt="Footer Image" class="img-fluid d-none d-sm-block">
            </div>
            <div class="col-6 col-sm-4 mb-3 mb-sm-0 d-flex align-items-center justify-content-center">
                <ul style="list-style-type: none; padding: 0;">
                    <li><strong>DISCOVER :</strong></li>
                    <li><a href="aboutus.html" class="link-zoom">About us</a></li>
                    <li><a href="#" class="link-zoom">Nos Chefs</a></li>
                    <li><a href="#" class="link-zoom">Nos Plats</a></li>
                    <li><a href="#" class="link-zoom">Événements</a></li>
                </ul>
            </div>
            <div class="col-6 col-sm-3 social-links d-flex align-items-center justify-content-center flex-wrap">
                <div class="mb-2">
                    <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila" class="d-flex align-items-center">
                        <img src="icone/fb.png" alt="Facebook" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Facebook</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="https://www.instagram.com/restaurant_parisien/" class="d-flex align-items-center">
                        <img src="icone/inst.png" alt="Instagram" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Instagram</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="tel:+33758428417" class="d-flex align-items-center">
                        <img src="icone/tel.png" alt="Téléphone" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">+33758428417</span>
                    </a>
                </div>
                <div class="mb-2">
                    <a href="mailto:koceila.haddad@outlook.com" class="d-flex align-items-center">
                        <img src="icone/email.jpg" alt="Email" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">Koceila.haddad@outlook.com</span>
                    </a>
                </div>
                <div>
                    <a href="https://maps.app.goo.gl/uJyLGFWHdaoNxB3X7" class="d-flex align-items-center">
                        <img src="icone/maps.jpg" alt="Adresse" width="25" height="25">
                        <span class="d-none d-sm-inline ms-2">30 Rue Esquirol, 75013 Paris</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>