<?php
// Le code PHP reste inchangé
session_start();
if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}
try {
    $bdd = new PDO('mysql:host=localhost;dbname=restaurant', 'koceila', '123456789');
    $bdd->exec('SET NAMES utf8');
} catch (Exception $e) {
    die('Erreur:' . $e->getMessage());
}

$id_client = $_SESSION['id'];

// Gestion de la suppression
if (isset($_GET['supprimer']) && is_numeric($_GET['supprimer'])) {
    $id_plat = $_GET['supprimer'];
    $req_suppr = $bdd->prepare("DELETE FROM panier WHERE id_plat = :id_plat AND id_client = :id_client");
    $req_suppr->execute(['id_plat' => $id_plat, 'id_client' => $id_client]);
    header("Location: Panier.php");
    exit();
}
if (isset($_GET['ajouter']) && is_numeric($_GET['ajouter'])) {
    $id_panier = $_GET['ajouter'];
    $req_ajout = $bdd->prepare("UPDATE panier SET quantite = quantite + 1 WHERE id_panier = :id_panier AND id_client = :id_client");
    $req_ajout->execute(['id_panier' => $id_panier, 'id_client' => $id_client]);
    header("Location: Panier.php");
    exit();
}
if (isset($_GET['retirer']) && is_numeric($_GET['retirer'])) {
    $id_panier = $_GET['retirer'];
    $req_ret = $bdd->prepare("UPDATE panier SET quantite = quantite - 1 WHERE id_panier = :id_panier AND id_client = :id_client");
    $req_ret->execute(['id_panier' => $id_panier, 'id_client' => $id_client]);
    header("Location: Panier.php");
    
    $req_verif = $bdd->query("SELECT quantite FROM panier WHERE id_panier = $id_panier AND id_client = $id_client");
    $result = $req_verif->fetch(PDO::FETCH_ASSOC);
    $quantite = $result['quantite'];
    if ($quantite == 0) {
        $req_vider_panier = $bdd->query("DELETE FROM panier WHERE id_panier = $id_panier AND id_client = $id_client");
    }
    exit();
}

if (isset($_POST['valider_commande'])) {
    $type_livraison = $_POST['type_livraison'];
    $adresse_livraison = $_POST['adresse_livraison'];
    $prix_totale = $_POST['prix_totale'];

    $req_commande = $bdd->prepare("INSERT INTO commande (id_client, type_livraison, adrs_livraison, prix_totale) VALUES (:id_client, :type_livraison, :adrs_livraison, :prix_totale)");
    $req_commande->execute([
        'id_client' => $id_client,
        'type_livraison' => $type_livraison,
        'adrs_livraison' => $adresse_livraison,
        'prix_totale' => $prix_totale
    ]);
    $_SESSION['total'] = $prix_totale;

    header("Location: confirmation.php"); 
    exit();
}

$req = $bdd->prepare("SELECT COUNT(*) as nombre_plats FROM panier WHERE id_client = :id_client");
$req->execute(['id_client' => $id_client]);
$result = $req->fetch(PDO::FETCH_ASSOC);
$nombre_plats = $result['nombre_plats'];
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panier</title>
    <link rel="stylesheet" href="../css/style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
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
                                        <a class="nav-link" href="accueil.php">Accueil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produits.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link active" href="panier.php">Panier</a>
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

    <!-- Contenu principal -->
    <main class="container my-5">
        <h2 class="text-center mb-4">Votre Panier</h2>
        <?php if ($nombre_plats == 0): ?>
            <p class="text-center">Votre panier est vide.</p>
        <?php else: ?>
            <div class="row g-4">
                <div class="col-12 col-md-6">
                    <?php
                    $req = $bdd->prepare("SELECT id_panier, id_plat, id_client, quantite FROM panier WHERE id_client = :id_client GROUP BY id_plat");
                    $req->execute(['id_client' => $id_client]);
                    $totale = 0;
                    while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                        $id_panier = $data->id_panier;
                        $id_plat = $data->id_plat;
                        $quantite = $data->quantite;

                        $panier_items[] = $data;

                        $req_count = $bdd->query("SELECT COUNT(*) as nombre_rep FROM panier WHERE id_client = $id_client AND id_plat = $id_plat");
                        $result_rep = $req_count->fetch(PDO::FETCH_ASSOC);
                        $nombre_rep = $result_rep['nombre_rep'];
                        $quantite = $quantite + $nombre_rep - 1;

                        $req2 = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id_plat");
                        $req2->execute(['id_plat' => $id_plat]);
                        $plat_data = $req2->fetch(PDO::FETCH_OBJ);
                        if ($plat_data) {
                            $nom_plat = $plat_data->nom_plat;
                            $prix = $plat_data->prix;
                            $image = $plat_data->image_plat;
                            $imageData = base64_encode($image);
                            $totale = $totale + $prix * $quantite;
                    ?>
                            <div class="cart-item mb-4 p-3 bg-light rounded-3">
                                <div class="row align-items-center">
                                    <div class="col-12 col-md-4 mb-3 mb-md-0">
                                        <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" class="rounded-3" alt="<?php echo htmlspecialchars($nom_plat); ?>">
                                    </div>
                                    <div class="col-12 col-md-8">
                                        <h5><?php echo htmlspecialchars($nom_plat); ?></h5>
                                        <h5>Prix : <?php echo htmlspecialchars($prix * $quantite); ?> €</h5>
                                        <h5>Quantité : <?php echo htmlspecialchars($quantite); ?></h5>
                                        <div class="mt-2">
                                            <a href="Panier.php?supprimer=<?php echo $id_plat; ?>" class="btn btn-danger btn-sm me-2" onclick="return confirm('Voulez-vous vraiment supprimer ce plat du panier ?');"><i class="bi bi-trash"></i></a>
                                            <a href="Panier.php?ajouter=<?php echo $id_panier; ?>" class="btn btn-primary btn-sm me-2"><i class="bi bi-plus"></i></a>
                                            <a href="Panier.php?retirer=<?php echo $id_panier; ?>" class="btn btn-primary btn-sm"><i class="bi bi-dash"></i></a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                    <?php
                        }
                    }
                    ?>
                </div>
                <div class="col-12 col-md-6">
                    <div class="cart-form">
                        <form method="POST" action="Panier.php">
                            <div class="mb-3">
                                <label for="type_livraison" class="form-label"><h3>Type de livraison</h3></label>
                                <select class="form-control form-control-lg" id="type_livraison" name="type_livraison">
                                    <option value="domicile">À domicile</option>
                                    <option value="surplace">Sur place</option>
                                </select>
                            </div>
                            <div class="mb-3 position-relative">
                                <label for="adresse_livraison" class="form-label"><h3>Adresse</h3></label>
                                <input type="text" class="form-control form-control-lg" id="adresse_livraison" name="adresse_livraison" placeholder="Entrez votre adresse" autocomplete="off">
                                <div class="dropdown">
                                    <ul class="dropdown-menu w-100" id="suggestions" style="max-height: 100px; overflow-y: auto;"></ul>
                                </div>
                            </div>
                            <div class="mb-3">
                                <h1>Total : <?php echo $totale; ?> €</h1>
                                <input type="hidden" name="prix_totale" value="<?php echo $totale; ?>">
                            </div>
                            <button type="submit" name="valider_commande" class="btn btn-success btn-lg w-100">Valider la commande</button>
                        </form>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </main>

    <!-- Footer -->
    <footer class="footer container-fluid">
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
                        <span class="d-none d-sm-inline ms-2">30 Rue Esquirol, 75013 Paris</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/adresse.js"></script>
    <script>
        $(document).ready(function() {
            // Vérifier si les éléments existent
            const typeLivraison = $('#type_livraison');
            const adresseInput = $('#adresse_livraison');
            
            if (!typeLivraison.length || !adresseInput.length) {
                console.error("Erreur : Éléments type_livraison ou adresse_livraison introuvables.");
                return;
            }

            // Fonction pour gérer l'état du champ d'adresse
            function toggleAdresse() {
                if (typeLivraison.val() === 'surplace') {
                    adresseInput.prop('disabled', true).val('');
                    console.log("Champ d'adresse désactivé.");
                } else {
                    adresseInput.prop('disabled', false);
                    console.log("Champ d'adresse activé.");
                }
            }

            // Exécuter au chargement de la page
            toggleAdresse();

            // Écouter les changements dans le menu déroulant
            typeLivraison.on('change', toggleAdresse);
        });
    </script>
</body>
</html>