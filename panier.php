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
    <title>Panier</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<!-- le code php -->
<?php
try{
    $bdd = new PDO('mysql:host=localhost;dbname=restaurant', 'koceila', '123456789') or die(print_r($bdd->errorInfo()));
    $bdd->exec('SET NAMES utf8');
    }
    
    catch(Exception $e){
    die('Erreur:'.$e->getMessage());
    } ?>


<?php
// Après la connexion $bdd
$id_client = $_SESSION['id'];
$req = $bdd->prepare("SELECT COUNT(*) as nombre_plats FROM panier WHERE id_client = :id_client");
$req->execute(['id_client' => $id_client]);
$result = $req->fetch(PDO::FETCH_ASSOC);
$nombre_plats = $result['nombre_plats'];
?>

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
                                <li class="nav-item">
                                    <a class="nav-link " href="accueil.php">Accueil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="produits.php">Produits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link active" href="Panier.php">Panier</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </nav>
            </div>
            <div class="col-sm-5">
                <a href="accueil.php"><img src="logo/logooo.png" alt="" class="img-fluid pt-3" ></a>
            </div>
            <div class="col-sm-2 d-flex justify-content-end">
                <p class="me-3 mt-3">Bienvenue, <?php echo ($_SESSION['nom']); ?>!</p>
            </div>
            <div class="col-sm-2 d-flex justify-content-end mt-2">
                <a href="logout.php"><button type="button" class="btn btn-outline-danger text-center ">Déconnexion</button></a>



            </div>
        </div>
    </div>
    <br>

    
    
    <div class="container">
    <h2> Votre Panier</h2>
    <hr>
    <br>
    <?php
            // Récupère tous les plats du panier pour le client connecté
            $id_client = $_SESSION['id']; // Assurez-vous que cette variable est définie via session_start()
            $req = $bdd->prepare("SELECT * FROM panier WHERE id_client = :id_client"); // Requête préparée pour la sécurité
            $req->execute(['id_client' => $id_client]);

            // Parcours de tous les enregistrements du panier
            while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                $id_panier = $data->id_panier; // ID du panier
                $id_plat = $data->id_plat; // ID du plat dans le panier

                // Récupère les détails du plat depuis la table 'plat'
                $req2 = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id_plat"); // Requête préparée
                $req2->execute(['id_plat' => $id_plat]);
                $plat_data = $req2->fetch(PDO::FETCH_OBJ);

                
                    $id_plat = $plat_data->id_plat;
                    $nom_plat = $plat_data->nom_plat;
                    $prix = $plat_data->prix;
                    $image = $plat_data->image_plat;
                    $imageData = base64_encode($image);
    ?>

            <div class="row">
                <div class="col-md-2 ps-2">
                    <p><img src="data:image/jpeg;base64,<?php echo $imageData; ?>"width="140" height="120" class="rounded-4" alt="<?php echo $nom_plat; ?>"></p>
                </div>
                <div class="col-md-4 border-end">
                    <div class="row"><h5><?php echo $nom_plat; ?></h5></div>
                    <div class="row"><h5>Prix : <?php echo $prix; ?> €</h5></div>
                </div>
                <div class="col-md-6"><p>col 3</p></div>
            </div>

    <?php
        } 
    
    
    
    ?>
</div>




    <div id="div3" class="container-fluid">
    <footer>
                <div class="row">
                    <div class="col-sm-4">
                        <img src="photos/footer.png" alt="" class="img-fluid">
                       
                    </div>
                    <div class="col-sm-4 d-flex align-items-center justify-content-center">
                        <ul style="list-style-type: none;">DISCOVER :
                            <li><a href="aboutus.html" class="link-zoom"> About us</a></li>
                            <li><a href="" class="link-zoom"> Nos Chefs</a></li>
                            <li><a href="" class="link-zoom"> Nos Plats</a></li>
                            <li><a href="" class="link-zoom"> Evenements</a></li>
                        </ul>
                        
                    </div>
                    <div class="col-sm-4 ">
                        <div class="row pt-3 ">
                            
                            <div class="col-sm-4 text-end ">
                                <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"> <img src="icone/fb.png" alt=""  width="25" height="25"></a>
                            </div>
                            <div class="col-sm-8 "> Facebook</div>
                        </div>
                        <div class="row">
                           
                            <div class="col-sm-4 text-end ">
                                <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"> <img src="icone/inst.png" alt=""  width="25" height="25"></a>
                            </div>
                            <div class="col-sm-8"> Instagram</div>
                        </div>
                        <div class="row">
                            
                            <div class="col-sm-4 text-end">
                                <a href="tel:+33758428417"> <img src="icone/tel.png" alt=""  width="25" height="25"></a>
                            </div>
                            <div class="col-sm-8 ">+33758428417 </div>
                        </div>
                        <div class="row">
                           
                            <div class="col-sm-4 text-end ">
                                <a href="mailto:koceila.haddad@outlook.com"> <img src="icone/email.jpg" alt=""  width="25" height="25"></a>
                            </div>
                            <div class="col-sm-8"> Koceila.haddad@outlook.com</div>
                            <div class="col-sm-4 text-end ">
                                <a href="https://maps.app.goo.gl/uJyLGFWHdaoNxB3X7"> <img src="icone/maps.jpg" alt=""  width="25" height="25"></a>
                            </div>
                            <div class="col-sm-8"> 30 Rue Esquirol, 75013 Paris</div>
                        </div>

                    </div>
                </div>

            </footer>
        </div>

    </header>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>   
</body>
</html>
