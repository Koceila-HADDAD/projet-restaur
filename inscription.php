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
                                <a class="nav-link active" aria-current="page" href="#">Acceuil</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">Produits</a>
                              </li>
                              <li class="nav-item">
                                <a class="nav-link" href="#">Panier</a>
                              </li>
                            </ul>
                          </div>
                        </div>
                      </nav>
                </div>
                <div class="col-sm-5 "><a href="accvisiteur.html"><img src="logo/logooo.png" alt="" class="img-fluid"  ></a></div>
                <div  id="con" class="col-sm-3 d-flex justify-content-end"><a href="login.php" style="text-decoration: none;"><button type="button" class="btn btn-outline-primary ">Connexion</button></a></div>

            </div>
        </div>
        <br>
        <div class="container bg-light">
        <h1>Inscription :</h1>
        <div class="container">
        <form method="POST" action="">
            <div class="row">
                <div class="col-sm-4"><label for="Nom" class="form-label">Nom</label></div>
                <div class="col-sm-8"><input type="text" class="form-control" id="Nom" name="nom" placeholder="Votre nom" required></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-4"><label for="Prenom" class="form-label">Prénom</label></div>
                <div class="col-sm-8"><input type="text" class="form-control" id="Prenom" name="prenom" placeholder="Votre prénom" required></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-4"><label for="Email" class="form-label">Email</label></div>
                <div class="col-sm-8"><input type="email" class="form-control" id="Email" name="email" placeholder="Votre email" required></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-4"><label for="Phone" class="form-label">N° Tel</label></div>
                <div class="col-sm-8"><input type="tel" class="form-control" id="Phone" name="phone" placeholder="Votre numéro de téléphone" pattern="[0-9]{10}" maxlength="10" required></div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-4"><label for="password" class="form-label">Mot de passe</label></div>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" 
                        pattern="(?=.*\d)(?=.*[A-Z]).{8,}" 
                        title="Doit contenir au moins 8 caractères, une majuscule et un chiffre" required>
                </div>
            </div>
            <br>

            <div class="row">
                <div class="col-sm-4"><label for="confirmPassword" class="form-label">Confirmer le mot de passe</label></div>
                <div class="col-sm-8">
                    <input type="password" class="form-control" id="confirmPassword" name="confirmPassword" placeholder="Confirmez votre mot de passe" required>
                    <small id="passwordError" class="text-danger"></small>
                </div>
            </div>
            <br>

            <div class="mb-3 form-check">
                <input type="checkbox" class="form-check-input" id="exampleCheck1" name="check" required>
                <label class="form-check-label" for="exampleCheck1">Check me out</label>
            </div>
            <button type="submit" class="btn btn-primary">Submit</button>
        </form>

        <?php
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
            $nom = $_POST['nom'];
            $prenom = $_POST['prenom'];
            $email = $_POST['email'];
            $phone = $_POST['phone'];
            $password = $_POST['password'];
            $confirmPassword = $_POST['confirmPassword'];

            if ($password !== $confirmPassword) {
                echo "<p class='text-danger'>Les mots de passe ne correspondent pas.</p>";
            } else {
                
                $username = "koceila";        
                $password_db = "123456789";        
                $dbname = "restaurant"; 

                try {
                    $bdd = new PDO("mysql:host=localhost;dbname=restaurant", 'koceila', '123456789');
                    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

                    // Hacher le mot de passe 
                    // $hashed_password = password_hash($password, PASSWORD_DEFAULT);

                    $req = $bdd->query("INSERT INTO client (nom_client, prenom_client, email, telephone, mot_de_passe) 
                                            VALUES ('$nom', '$prenom', '$email', $phone, '$password')");
  
                    echo "<p class='text-success'>Inscription réussie !</p>";

                   
                   

                } catch (PDOException $e) {
                    echo "<p class='text-danger'>Erreur : " . $e->getMessage() . "</p>";
                }

                $conn = null;
            }
        }
        ?>
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
                    <div class="col-sm-4 text-center">
                        <ul style="list-style-type: none;">DISCOVER :
                            <li><a href="aboutus.html" class="link-zoom"> About us</a></li>
                            <li><a href="" class="link-zoom"> Nos Chefs</a></li>
                            <li><a href="" class="link-zoom"> Nos Plats</a></li>
                            <li><a href="" class="link-zoom"> Evenements</a></li>
                        </ul>
                        
                    </div>
                    <div class="col-sm-4">
                        <div class="row">
                            
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