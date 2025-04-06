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
                                    <a class="nav-link active" href="accueil.php">Accueil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="produits.php">Produits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="panier.php">Panier</a>
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
    <!-- la photo de couverture  -->
    <div class="container-fluid" >
            <div class="row" >
                
                <div class="image-container">
                    <img src="photos/arrplan.jpg" alt="Image de fond">
                    <div class="texte ">    Bleu Blanc Saveur vous invite à une expérience gastronomique raffinée,
                         alliant tradition et créativité. Dans un cadre élégant, notre chef sublime des produits d'exception pour éveiller vos sens.
                        Laissez-vous emporter par une cuisine authentique et audacieuse.
                    </div>
                    <div class="prog">
                        Horaires d'ouverture : mardi-dimanche : 11h00-15h00 / 18h30-23h30<br>
                                              lundi : fermé
                    </div>
                </div>
                  
            </div>
           
         </div>
        <!-- le menue  -->
       <!-- Section du menu -->
<div id="div2" class="container">
    <h2 id="menue"><img src="logo/logooo2.png" alt="" width="40" height="40"> Le Menu Du Chef :</h2>

    <?php
    // Récupère les 6 premiers plats de la table 'plat'
    $req = $bdd->query("SELECT * FROM plat ORDER BY id_plat LIMIT 6");
    $i = 0;
    while ($data = $req->fetch(PDO::FETCH_OBJ)) { // Boucle sur les résultats
        if ($i % 3 == 0) {
            if ($i > 0) {
                echo '</div>'; // Ferme la ligne précédente
            }
            echo '<div class="row p-5">'; // Ouvre une nouvelle ligne toutes les 3 cartes
        }
        $id_plat = $data->id_plat; // ID du plat actuel
        $nom_plat = $data->nom_plat; // Nom du plat actuel
        $prix = $data->prix; // Prix du plat actuel
        $image = $data->image_plat; // Image binaire du plat actuel
        $imageData = base64_encode($image); // Encodage en base64
    ?>

    <div class="col-sm-4">
        <div class="card h-100">
            <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" class="card-img-top h-100" alt="<?php echo $nom_plat; ?>">
            <div class="card-body">
                <h5><?php echo $nom_plat; ?></h5>
                <h5>Prix : <?php echo $prix; ?> €</h5>
                <div class="card-footer text-center">
                    <!-- Formulaire avec un champ caché pour transmettre l'ID du plat -->
                    <form method="POST" action="">
                        <input type="hidden" name="id_plat" value="<?php echo $id_plat; ?>">
                        <button type="submit" class="btn btn-primary">Ajouter au panier</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <?php
        $i++;
    }
    if ($i > 0) {
        echo '</div>'; // Ferme la dernière ligne
    }
    ?>

    <?php
    // Gestion de l'ajout au panier (POST)
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Récupère l'ID du plat depuis le formulaire
        $id = isset($_POST['id_plat']) ? $_POST['id_plat'] : null;
        $id_client = $_SESSION['id']; // ID du client depuis la session
        if (!empty($id)) { // Vérifie que l'ID n'est pas vide
            // Requête préparée pour éviter les injections SQL
            $req = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id");
            $req->execute(['id' => $id]);
            $data = $req->fetch(PDO::FETCH_OBJ);
            if ($data) { // Vérifie si le plat existe
                $id_plat = $data->id_plat;
                $nom_plat = $data->nom_plat;
                $prix = $data->prix;
                $image = $data->image_plat;
                $imageData = base64_encode($image);
                // inserer dans la table panier
                $req = $bdd->query("INSERT INTO panier (id_plat, id_client) 
                                            VALUES ('$id_plat', '$id_client')");

            } 
        } 
    }
    ?>
</div>
        <br>
        <!-- le caroussel -->
        <div id="div2" class="container">
            <h2 id="menue"> <img src="logo/logooo2.png" alt="" width="40" height="40"> Evenements :</h2>
            <div id="carouselExampleIndicators" class="carousel slide" data-bs-ride="carousel" style="height: 50vh;">
                <!-- Indicateurs -->
                <div class="carousel-indicators">
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="0" class="active"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="1"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="2"></button>
                    <button type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide-to="3"></button>
                </div>
            
                <div class="carousel-inner h-100 rounded-3">
                    <div class="carousel-item active h-100 ">
                        <img src="evenements2/ev1.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Image 1">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="evenements2/ev2.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Image 2">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="evenements2/ev3.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Image 3">
                    </div>
                    <div class="carousel-item h-100">
                        <img src="evenements2/ev4.jpg" class="d-block w-100 h-100 object-fit-cover" alt="Image 4">
                    </div>
                </div>
            
                <!-- Flèches -->
                <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                </button>
                <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleIndicators" data-bs-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                </button>
            </div>
                        
            <br>
        </div>

        <!-- les commentaires  -->
        <div class="container"><h2 id="menue"> <img src="logo/logooo2.png" alt="" width="40" height="40"> Commentaires :</h2></div>

        <div class="container d-flex">
            <div class="container w-50 px-5">
                <?php
                    if ($_SERVER["REQUEST_METHOD"] == "POST") {
                        $text = $_POST['commentaire'];
                        $utilisateur=$_SESSION['id'];
                        $maintenant = new DateTime("now", new DateTimeZone("Europe/Paris"));                       
                        $req = $bdd->prepare("INSERT INTO commentaire (texte, id_client, date_com) VALUES (:texte, :id_client, :date_com)");
                    $req->execute([
                        ':texte' => $text,
                        ':id_client' => $utilisateur,
                        ':date_com' => $maintenant->format("Y-m-d H:i"),]);
                    
                    
                        }
                ?>
                <form method="POST" action="">
                <div class="row">
                    
                    <textarea class="form-control" id="commentaire" name="commentaire" rows="5"></textarea>
                    
                </div>
                <div class="row d-flex justify-content-center mt-2">
                <button type="submit" class="btn btn-primary w-50">Envoyer</button>


                </div>
                </form>
            </div>
         <div class="container w-50 border-start ">
                <?php
                    // Pagination
                    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                    $comments_per_page = 3;
                    $offset = ($page - 1) * $comments_per_page;

                    // Compter le total des commentaires
                    $total_comments_query = $bdd->query("SELECT COUNT(*) as total FROM commentaire");
                    $total_comments = $total_comments_query->fetch(PDO::FETCH_OBJ)->total;
                    $total_pages = ceil($total_comments / $comments_per_page);

                    // Récupérer les 5 commentaires de la page actuelle
                    $req = $bdd->query("SELECT * FROM commentaire ORDER BY date_com DESC LIMIT $offset, $comments_per_page");
                    while ($data = $req->fetch(PDO::FETCH_OBJ)) {
                        $id_cl = $data->id_client;
                        $text = $data->texte;
                        $date_com = $data->date_com;
                ?>
                <div class="row">
                    <div class="col-6">
                        <?php 
                        $req2 = $bdd->query("SELECT nom_client FROM client WHERE id_client=$id_cl");
                        $data2 = $req2->fetch(PDO::FETCH_OBJ);
                        $nom = $data2->nom_client;
                        echo '<p style="font-weight: bold !important;">' . htmlspecialchars($nom, ENT_QUOTES, 'UTF-8') . '</p>';
                        ?>
                    </div>
                    <div class="col-6">
                        <?php
                        echo '<p class="text-end" style="font-weight: bold !important;">' . htmlspecialchars($date_com, ENT_QUOTES, 'UTF-8') . '</p>';
                        ?>
                    </div>
                </div>
                <div class="row px-5 py-2 m-2 bg-light border">
                    <?php echo $text;  ?>                    
                </div>
                <hr>
                <?php }?>
                <!-- Liens de pagination -->
                <div class="row justify-content-center mt-3">
                        <?php
                        if ($page > 1) {
                            $prev_page = $page - 1;
                            echo "<a href='?page=$prev_page' class='btn btn-outline-primary mx-2'>Précédent</a>";
                        }
                        if ($page < $total_pages) {
                            $next_page = $page + 1;
                            echo "<a href='?page=$next_page' class='btn btn-primary mx-2'>Afficher la suite</a>";
                        }
                        ?>
                </div>
                    
            </div>



        </div>
        <br>
        <!-- le footer -->
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
