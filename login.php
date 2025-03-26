<?php
session_start(); // Doit être la première ligne exécutée

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];

    try {
        $bdd = new PDO("mysql:host=localhost;dbname=restaurant", 'koceila', '123456789');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $bdd->query("SELECT * FROM client WHERE email ='$email' AND mot_de_passe ='$password'");
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user) {
            $_SESSION['id'] = $user['id_client']; 
            $_SESSION['nom'] = $user['nom_client'];
            header("Location: accueil.php");
            exit();
        } else {
            echo "<p class='text-danger mt-2'>Email ou mot de passe incorrect.</p>";
        }
        
    } catch (PDOException $e) {
        echo "<p class='text-danger'>Erreur : " . $e->getMessage() . "</p>";
    }
    $conn = null;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
</head>
<body>
    <header>
        <div class="container-fluid">
            <div class="row" style="background-color:#ffffff;">
                <div class="col-sm-4">
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <img src="logo/logooo2.png" alt="" width="40" height="40">
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="accvisiteur.php">Accueil</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="produitvisit.php">Produits</a>
                                    </li>
                                    <li class="nav-item">
                                        <a class="nav-link" href="Panier.php">Panier</a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </nav>
                </div>
                <div class="col-sm-5"><a href="accvisiteur.html"><img src="logo/logooo.png" alt="" class="img-fluid"></a></div>
            </div>
        </div>
        <br>
    </header>
    <div class="container bg-light mt-5">
        <h1>Connexion :</h1>
        <div class="container py-5">
            <form method="POST" action="">
                <div class="row mb-3">
                    <div class="col-sm-4"><label for="Email" class="form-label">Email</label></div>
                    <div class="col-sm-8">
                        <input type="email" class="form-control" id="Email" name="email" placeholder="Votre email" required>
                    </div>
                </div>
                <div class="row mb-3">
                    <div class="col-sm-4"><label for="password" class="form-label">Mot de passe</label></div>
                    <div class="col-sm-8">
                        <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                    </div>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary">Se connecter</button>
                    <br>
                    <br>
                    <p>Vous n'avez pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a> dès maintenant pour profiter de tous nos services !</p>
                </div>
            </form>
        </div>
    </div>
    <br>
    <div id="div3" class="container-fluid">
        <br>
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
                        <div class="col-sm-4 text-end">
                            <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"><img src="icone/fb.png" alt="" width="25" height="25"></a>
                        </div>
                        <div class="col-sm-8"> Facebook</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end">
                            <a href="https://www.facebook.com/search/top?q=restaurant%20dar%20leila"><img src="icone/inst.png" alt="" width="25" height="25"></a>
                        </div>
                        <div class="col-sm-8"> Instagram</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end">
                            <a href="tel:+33758428417"><img src="icone/tel.png" alt="" width="25" height="25"></a>
                        </div>
                        <div class="col-sm-8">+33758428417</div>
                    </div>
                    <div class="row">
                        <div class="col-sm-4 text-end">
                            <a href="mailto:koceila.haddad@outlook.com"><img src="icone/email.jpg" alt="" width="25" height="25"></a>
                        </div>
                        <div class="col-sm-8"> Koceila.haddad@outlook.com</div>
                        <div class="col-sm-4 text-end">
                            <a href="https://maps.app.goo.gl/uJyLGFWHdaoNxB3X7"><img src="icone/maps.jpg" alt="" width="25" height="25"></a>
                        </div>
                        <div class="col-sm-8"> 30 Rue Esquirol, 75013 Paris</div>
                    </div>
                </div>
            </div>
        </footer>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>