<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">
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
                <div class="col-12 col-sm-3">
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="accueil.php">
                                <img src="../logo/logooo2.png" alt="Logo" width="40" height="40">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active" href="accueil.php">Accueil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produits.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="panier.php">Panier</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-12 col-sm-5 text-center">
                    <a href="accueil.php">
                        <img src="../logo/logooo.png" alt="Logo" class="logo-center img-fluid">
                    </a>
                </div>
                <div class="col-12 col-sm-2 text-center text-sm-end">
                    <p class="mt-3">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?>!</p>
                </div>
                <div class="col-12 col-sm-2 text-center text-sm-end">
                    <a href="logout.php">
                        <button type="button" class="btn btn-outline-danger mt-2 mt-sm-0">Déconnexion</button>
                    </a>
                </div>
            </div>
        </div>
    </header>

    <!-- Image de couverture -->
    <section class="container-fluid my-4">
        <div class="row">
            <div class="image-container">
                <img src="../photos/arrplan.jpg" alt="Image de fond" class="img-fluid d-none d-sm-block">
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
            <img src="../logo/logooo2.png" alt="Logo" width="40" height="40"> Le Menu Du Chef
        </h2>
        <?php
        $req = $bdd->query("SELECT * FROM plat ORDER BY id_plat LIMIT 6");
        if ($req->rowCount() === 0) {
            echo '<p class="text-center">Aucun plat disponible pour le moment.</p>';
        } else {
            echo '<div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">';
            while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                $id_plat = $data->id_plat;
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
                            <div class="card-footer text-center">
                                <button class="btn btn-primary" onclick="addToCart(<?php echo $id_plat; ?>)">Ajouter au panier</button>
                            </div>
                        </div>
                    </div>
                </div>
        <?php
            }
            echo '</div>';
        }
        $req->closeCursor();
        ?>
    </section>

    <!-- Carrousel -->
    <section class="container my-4">
        <h2 class="text-center mb-4">
            <img src="../logo/logooo2.png" alt="Logo" width="40" height="40"> Événements
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
                    <img src="../photos/evenements2/ev1.jpg" class="d-block w-100" alt="Événement 1">
                </div>
                <div class="carousel-item">
                    <img src="../photos/evenements2/ev2.jpg" class="d-block w-100" alt="Événement 2">
                </div>
                <div class="carousel-item">
                    <img src="../photos/evenements2/ev3.jpg" class="d-block w-100" alt="Événement 3">
                </div>
                <div class="carousel-item">
                    <img src="../photos/evenements2/ev4.jpg" class="d-block w-100" alt="Événement 4">
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

    <!-- Commentaires -->
    <section class="container my-4">
        <h2 class="text-center mb-4">
            <img src="../logo/logooo2.png" alt="Logo" width="40" height="40"> Commentaires
        </h2>
        <div class="comment-section">
            <div class="comment-form w-100">
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['commentaire'])) {
                    $text = $_POST['commentaire'];
                    $utilisateur = $_SESSION['id'];
                    $maintenant = new DateTime("now", new DateTimeZone("Europe/Paris"));
                    $req = $bdd->prepare("INSERT INTO commentaire (texte, id_client, date_com) VALUES (:texte, :id_client, :date_com)");
                    $req->execute([
                        ':texte' => $text,
                        ':id_client' => $utilisateur,
                        ':date_com' => $maintenant->format("Y-m-d H:i"),
                    ]);
                }
                ?>
                <form method="POST" action="">
                    <div class="mb-3">
                        <textarea class="form-control" id="commentaire" name="commentaire" rows="5" placeholder="Votre commentaire..."></textarea>
                    </div>
                    <div class="d-flex justify-content-center">
                        <button type="submit" class="btn btn-primary w-50">Envoyer</button>
                    </div>
                </form>
            </div>
            <div class="comment-list w-100">
                <?php
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $comments_per_page = 3;
                $offset = ($page - 1) * $comments_per_page;

                $total_comments_query = $bdd->query("SELECT COUNT(*) as total FROM commentaire");
                $total_comments = $total_comments_query->fetch(PDO::FETCH_OBJ)->total;
                $total_pages = ceil($total_comments / $comments_per_page);

                $req = $bdd->query("SELECT * FROM commentaire ORDER BY date_com DESC LIMIT $offset, $comments_per_page");
                while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                    $id_cl = $data->id_client;
                    $text = htmlspecialchars($data->texte);
                    $date_com = htmlspecialchars($data->date_com);
                ?>
                    <div class="comment-item">
                        <div class="row">
                            <div class="col-6">
                                <?php
                                $req2 = $bdd->query("SELECT nom_client FROM client WHERE id_client=$id_cl");
                                $data2 = $req2->fetch(PDO::FETCH_OBJ);
                                $nom = htmlspecialchars($data2->nom_client);
                                echo '<p class="fw-bold">' . $nom . '</p>';
                                ?>
                            </div>
                            <div class="col-6 text-end">
                                <p class="fw-bold"><?php echo $date_com; ?></p>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-12">
                                <p><?php echo $text; ?></p>
                            </div>
                        </div>
                    </div>
                    <hr>
                <?php } ?>
                <div class="pagination-buttons d-flex justify-content-center mt-3">
                    <?php
                    if ($page > 1) {
                        $prev_page = $page - 1;
                        echo "<a href='?page=$prev_page' class='btn btn-outline-primary mx-2'>Précédent</a>";
                    }
                    if ($page < $total_pages) {
                        $next_page = $page + 1;
                        echo "<a href='?page=$next_page' class='btn btn-primary mx-2'>Suivant</a>";
                    }
                    ?>
                </div>
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
                    <li><a href="nos-chefs.php" class="link-zoom">Nos Chefs</a></li>
                    <li><a href="produitvisit.php" class="link-zoom">Nos Plats</a></li>
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
                        <span class="d-none d-sm-inline ms-2">30 Rue Esquirol, 75013 berhasil Paris</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <!-- notifications -->
    <div class="toast-container position-fixed bottom-0 end-0 p-3">
        <div id="cartToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="toast-header">
                <strong class="me-auto">Notification</strong>
                <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Fermer"></button>
            </div>
            <div class="toast-body">
                Plat ajouté au panier avec succès !
            </div>
        </div>
    </div>

    <!-- JavaScript pour l'ajout au panier -->
    <script>
    function addToCart(id_plat) {
        const id_client = <?php echo json_encode($_SESSION['id']); ?>;
        const data = {
            id_plat: id_plat,
            id_client: id_client
        };

        fetch('add_to_cart.php', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        })
        .then(response => response.json())
        .then(result => {
            const toastElement = document.getElementById('cartToast');
            const toastBody = toastElement.querySelector('.toast-body');
            const toast = new bootstrap.Toast(toastElement);

            if (result.success) {
                toastBody.textContent = 'Plat ajouté au panier avec succès !';
                toast.show();
            } else {
                toastBody.textContent = 'Erreur : ' + result.message;
                toast.show();
            }
        })
        .catch(error => {
            console.error('Erreur :', error);
            const toastElement = document.getElementById('cartToast');
            const toastBody = toastElement.querySelector('.toast-body');
            const toast = new bootstrap.Toast(toastElement);
            toastBody.textContent = 'Une erreur s\'est produite. Veuillez réessayer.';
            toast.show();
        });
    }
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>