<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    echo $hashed_password;
    try {
        $bdd = new PDO("mysql:host=localhost;dbname=restaurant;charset=utf8", 'koceila', '123456789');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        $stmt = $bdd->prepare("SELECT * FROM client WHERE email = :email");
        $stmt->execute(['email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['mot_de_passe'])) {
            // Connexion réussie, par exemple : démarrer une session
            $_SESSION['id'] = $user['id_client']; 
            $_SESSION['nom'] = $user['nom_client'];
            $_SESSION['MOHAND'] = "tazmalt";
            header("Location: accueil.php");
            exit();
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } catch (PDOException $e) {
        $error = "Erreur : " . htmlspecialchars($e->getMessage());
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="stylesheet" href="../css/style.css">

</head>
<body>
    <!-- Header -->
    <header class="py-3">
        <div class="container-fluid">
            <div class="row align-items-center bg-white">
                <div class="col-12 col-sm-5">
                    <nav class="navbar navbar-expand-lg bg-white">
                        <div class="container-fluid">
                            <a class="navbar-brand" href="../accvisiteur.php">
                                <img src="../logo/logooo2.png" alt="Logo" width="40" height="40">
                            </a>
                            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                                <span class="navbar-toggler-icon"></span>
                            </button>
                            <div class="collapse navbar-collapse" id="navbarNav">
                                <ul class="navbar-nav">
                                    <li class="nav-item">
                                        <a class="nav-link active" aria-current="page" href="../accvisiteur.php">Accueil</a>
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
                <div class="col-12 col-sm-7 text-start">
                    <a href="../accvisiteur.php">
                        <img src="../logo/logooo.png" alt="Logo" class="logo-center img-fluid">
                    </a>
                </div>
            </div>
        </div>
    </header>

    

    <!-- Formulaire de connexion -->
    <main class="container my-5">
        <div class="login-form">
            <h1 class="text-center mb-4">Connexion</h1>
            <?php if (isset($error)): ?>
                <p class="text-danger text-center"><?php echo htmlspecialchars($error); ?></p>
            <?php endif; ?>
            <form method="POST" action="">
                <div class="mb-3">
                    <label for="email" class="form-label">Email</label>
                    <input type="email" class="form-control" id="email" name="email" placeholder="Votre email" required>
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Mot de passe</label>
                    <input type="password" class="form-control" id="password" name="password" placeholder="Votre mot de passe" required>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    <p class="mt-3">Vous n'avez pas encore de compte ? <a href="inscription.php">Inscrivez-vous</a> dès maintenant !</p>
                </div>
            </form>
        </div>
    </main>

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
                        <span class="d-none d-sm-inline ms-2">30 Rue Esquirol, 75013 Paris</span>
                    </a>
                </div>
            </div>
        </div>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>