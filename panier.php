<?php
session_start();

// Vérifier si l'utilisateur est connecté
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
    $id_panier = $_GET['supprimer'];
    $req_suppr = $bdd->prepare("DELETE FROM panier WHERE id_panier = :id_panier AND id_client = :id_client");
    $req_suppr->execute(['id_panier' => $id_panier, 'id_client' => $id_client]);
    header("Location: Panier.php");
    exit();
}

// Gestion de la validation de la commande
if (isset($_POST['valider_commande'])) {
    $type_livraison = $_POST['type_livraison'];
    $adresse_livraison = $_POST['adresse_livraison'];
    $prix_totale = $_POST['prix_totale'];

    // Insérer dans la table commande
    $req_commande = $bdd->prepare("INSERT INTO commande (id_client, type_livraison, adrs_livraison, prix_totale) VALUES (:id_client, :type_livraison, :adrs_livraison, :prix_totale)");
    $req_commande->execute([
        'id_client' => $id_client,
        'type_livraison' => $type_livraison,
        'adrs_livraison' => $adresse_livraison,
        'prix_totale' => $prix_totale
        

    ]);
    $_SESSION['total'] = $prix_totale;
   

    // Optionnel : Vider le panier après validation
    //$req_vider_panier = $bdd->prepare("DELETE FROM panier WHERE id_client = :id_client");
    //$req_vider_panier->execute(['id_client' => $id_client]);

    // Rediriger vers une page de confirmation ou recharger
    header("Location: confirmation.php"); // Créez une page confirmation.php si besoin
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
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <style>
        .dropdown-menu {
            top: 100% !important;
            transform: translateY(0) !important;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container-fluid">
        <div class="row" style="background-color:#ffffff;">
            <div class="col-sm-3">
                <nav class="navbar navbar-expand-lg bg-white">
                    <div class="container-fluid">
                        <img src="logo/logooo2.png" alt="" width="40" height="40">
                        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                            <span class="navbar-toggler-icon"></span>
                        </button>
                        <div class="collapse navbar-collapse" id="navbarNav">
                            <ul class="navbar-nav">
                                <li class="nav-item"><a class="nav-link" href="accueil.php">Accueil</a></li>
                                <li class="nav-item"><a class="nav-link" href="produits.php">Produits</a></li>
                                <li class="nav-item"><a class="nav-link active" href="Panier.php">Panier</a></li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-sm-5">
                <a href="accueil.php"><img src="logo/logooo.png" alt="" class="img-fluid pt-3"></a>
            </div>
            <div class="col-sm-2 d-flex justify-content-end">
                <p class="me-3 mt-3">Bienvenue, <?php echo htmlspecialchars($_SESSION['nom']); ?>!</p>
            </div>
            <div class="col-sm-2 d-flex justify-content-end mt-2">
                <a href="logout.php"><button type="button" class="btn btn-outline-danger">Déconnexion</button></a>
            </div>
        </div>
    </div>
    <br>
    <div class="container">
        <h2>Votre Panier</h2>
        <hr>
        <br>
    </div>
    <div class="container d-flex mb-5">
        <div class="container w-50">
            <?php
            $req = $bdd->prepare("SELECT * FROM panier WHERE id_client = :id_client");
           
            $req->execute(['id_client' => $id_client]);
            $totale = 0;
            while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                $id_panier = $data->id_panier;
                $id_plat = $data->id_plat;
                $panier_items[] = $data;
                $req2 = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id_plat");
                $req2->execute(['id_plat' => $id_plat]);
                $plat_data = $req2->fetch(PDO::FETCH_OBJ);
                if ($plat_data) {
                    $nom_plat = $plat_data->nom_plat;
                    $prix = $plat_data->prix;
                    $image = $plat_data->image_plat;
                    $imageData = base64_encode($image);
                    $totale = $totale + $prix;
                   
                    

                    

                    
            ?>
                    <div class="row">
                        <div class="col-md-4 ps-2">
                            <p><img src="data:image/jpeg;base64,<?php echo $imageData; ?>" width="140" height="120" class="rounded-4" alt="<?php echo htmlspecialchars($nom_plat); ?>"></p>
                        </div>
                        <div class="col-md-8 border-end">
                            <div class="row"><h5><?php echo htmlspecialchars($nom_plat); ?></h5></div>
                            <div class="row"><h5>Prix : <?php echo htmlspecialchars($prix); ?> €</h5></div>
                            <div class="row">
                                <div class="col">
                                    <a href="Panier.php?supprimer=<?php echo $id_panier; ?>" class="btn btn-danger btn-sm mt-2" onclick="return confirm('Voulez-vous vraiment supprimer ce plat du panier ?');"><i class="bi bi-trash"></i></a>
                                </div>
                            </div>
                        </div>
                    </div>
            <?php
                }
                
            }

            
            
            ?>
        </div>
        <div class="container w-50">
            <form method="POST" action="Panier.php">
                <div class="row">
                    <div class="col-md-12">
                        <label for="type-livraison"><h3>Type de livraison</h3></label>
                        <select class="form-control form-control-lg" id="type-livraison" name="type_livraison">
                            <option value="domicile">À domicile</option>
                            <option value="surplace">Sur place</option>
                        </select>
                    </div>
                </div>
                <br>
                <div class="row">
                    <div class="col-md-12">
                        <div class="mb-3 position-relative">
                            <label for="adresse" class="form-label"><h3>Adresse</h3></label>
                            <input type="text" class="form-control form-control-lg" id="adresse" name="adresse_livraison" placeholder="Entrez votre adresse" autocomplete="off">
                            <div class="dropdown">
                                <ul class="dropdown-menu w-100" id="suggestions" style="max-height: 100px; overflow-y: auto;"></ul>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <h1>Totale : <?php echo $totale . '€'; ?></h1>
                        <input type="hidden" name="prix_totale" value="<?php echo $totale; ?>">
                    </div>
                </div>
                <div class="row">
                    <div class="col-md-12">
                        <button type="submit" name="valider_commande" class="btn btn-success btn-lg mt-3">Valider la commande</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div id="div3" class="container-fluid">
        <footer>
            <div class="row">
                <div class="col-sm-4">
                    <img src="photos/footer.png" alt="" class="img-fluid">
                </div>
                <div class="col-sm-4 d-flex align-items-center justify-content-center">
                    <ul style="list-style-type: none;">DISCOVER :
                        <li><a href="aboutus.html" class="link-zoom">About us</a></li>
                        <li><a href="#" class="link-zoom">Nos Chefs</a></li>
                        <li><a href="#" class="link-zoom">Nos Plats</a></li>
                        <li><a href="#" class="link-zoom">Evenements</a></li>
                    </ul>
                </div>
                <div class="col-sm-4">
                    <div class="row pt-3">
                        <div class="col-sm-4 text-end"><a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"><img src="icone/fb.png" alt="" width="25" height="25"></a></div>
                        <div class="col-sm-8">Facebook</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end"><a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"><img src="icone/inst.png" alt="" width="25" height="25"></a></div>
                        <div class="col-sm-8">Instagram</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end"><a href="tel:+33758428417"><img src="icone/tel.png" alt="" width="25" height="25"></a></div>
                        <div class="col-sm-8">+33758428417</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end"><a href="mailto:koceila.haddad@outlook.com"><img src="icone/email.jpg" alt="" width="25" height="25"></a></div>
                        <div class="col-sm-8">Koceila.haddad@outlook.com</div>
                        <div class="col-sm-4 text-end"><a href="https://maps.app.goo.gl/uJyLGFWHdaoNxB3X7"><img src="icone/maps.jpg" alt="" width="25" height="25"></a></div>
                        <div class="col-sm-8">30 Rue Esquirol, 75013 Paris</div>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <script src="adresse.js"></script>
</body>
</html>