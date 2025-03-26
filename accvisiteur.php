<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
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


    <header>
        <!-- la navbar -->
        <div class="container-fluid">
            <div class="row " style="background-color:#ffffff;">
                <div class="col-sm-4 " >
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <img src="logo/logooo2.png" alt="" width="40" height="40">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                            <span class="navbar-toggler-icon"></span>
                          </button>
                          <div class="collapse navbar-collapse" id="navbarNav">
                          <ul class="navbar-nav">
                                <li class="nav-item">
                                    <a class="nav-link active" href="accvisiteur.php">Accueil</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link " href="produitvisit.php">Produits</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link" href="Panier.php">Panier</a>
                                </li>
                            </ul>
                          </div>
                        </div>
                      </nav>
                </div>
                <div class="col-sm-5 "><a href="accvisiteur.php"><img src="logo/logooo.png" alt="" class="img-fluid pt-3"  ></a></div>
                <div  id="con" class="col-sm-3 d-flex justify-content-end"><a href="login.php" style="text-decoration: none;"><button type="button" class="btn btn-outline-primary ">Connexion</button></a></div>

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
        <div id="div2" class="container">
                <h2 id="menue"> <img src="logo/logooo2.png" alt="" width="40" height="40"> Le Menu Du Chef :</h2>

                <?php
                $req = $bdd->query("SELECT * FROM plat ORDER BY id_plat ");
                $i = 0;
                for ($i = 0; $i <= 5; $i++) {
                  $data = $req->fetch(PDO::FETCH_OBJ);
                    if ($i % 3 == 0) {
                        if ($i >0){
                        echo '</div>';}
                        echo '<div class="row p-5">';
                    }
                    $id_plat = $data->id_plat;
                    $nom_plat = $data->nom_plat;
                    $prix = $data->prix;
                    $image = $data->image_plat;
                    $imageData = base64_encode($image);
                ?>

                    <div class="col-sm-4">
                        <div class="card h-100">
                            <img src="data:image/jpeg;base64,<?php echo $imageData; ?>" class="card-img-top h-100" alt="<?php echo $nom_plat; ?>">
                            <div class="card-body">
                                <h5><?php echo $nom_plat; ?></h5>
                                <h5>Prix : <?php echo $prix; ?> €</h5>
                            </div>
                            
                        </div>
                    </div>

                <?php
                    
                }
               
                ?>
            </div>
                                

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