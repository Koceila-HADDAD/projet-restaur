<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <style>
        .dropdown-menu {
            top: 100% !important;
            transform: translateY(0) !important;
            margin-top: 0.5rem;
        }
    </style>
</head>
<body>
    <!-- Connexion à la base de données -->
    <?php
    try {
        $bdd = new PDO('mysql:host=localhost;dbname=restaurant', 'koceila', '123456789') or die(print_r($bdd->errorInfo()));
        $bdd->exec('SET NAMES utf8');
    } catch (Exception $e) {
        die('Erreur:' . $e->getMessage());
    }
    ?>

    <div class="container mt-5 bg-light">
        <h1>Remplir le formulaire</h1>

        <!-- Traitement du formulaire -->
        <?php
        session_start();

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $type_livraison = htmlspecialchars($_POST['type_livraison']);
            $commentaire = htmlspecialchars($_POST['commentaire'] ?? '');
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $carte_bancaire = htmlspecialchars($_POST['carte_bancaire']);

            // Validation supplémentaire (exemple)
            if (!preg_match('/^\d{16}$/', $carte_bancaire)) {
                echo '<div class="alert alert-danger">Numéro de carte invalide. Veuillez entrer 16 chiffres.</div>';
            } else {
                // Insertion dans la table paiements
                $req = $bdd->prepare("INSERT INTO paiements (nom, prenom, type_livraison, commentaire, email, carte_bancaire) VALUES (:nom, :prenom, :type_livraison, :commentaire, :email, :carte_bancaire)");
                $req->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'type_livraison' => $type_livraison,
                    'commentaire' => $commentaire,
                    'email' => $email,
                    'carte_bancaire' => $carte_bancaire
                ]);

                // Redirection après succès
                header("Location: confirmation.php");
                exit();
            }
        }
        ?>

        <!-- Formulaire -->
        <form method="POST" action="" class="mt-4">
            <!-- Nom -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="nom" class="form-label">Nom</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="nom" name="nom" placeholder="Entrez votre nom" required>
                </div>
            </div>

            <!-- Prénom -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="prenom" class="form-label">Prénom</label>
                </div>
                <div class="col-md-8">
                    <input type="text" class="form-control" id="prenom" name="prenom" placeholder="Entrez votre prénom" required>
                </div>
            </div>

            
        

            <!-- Adresse email -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="email" class="form-label">Adresse email</label>
                </div>
                <div class="col-md-8">
                    <input type="email" class="form-control" id="email" name="email" placeholder="Entrez votre email" required>
                </div>
            </div>

            <!-- Numéro de carte bancaire -->
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="carte_bancaire" class="form-label">Numéro de carte bancaire</label>
                </div>
                <div class="col-md-8">
                    <input type="number" class="form-control" id="carte_bancaire" name="carte_bancaire" placeholder="Entrez le numéro de votre carte (16 chiffres)" pattern="\d{16}" maxlength="16" required>
                    <small class="form-text text-muted">Entrez uniquement les 16 chiffres sans espaces.</small>
                </div>
            </div>

            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="carte_bancaire" class="form-label">Code sécurité</label>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" id="carte_bancaire" name="carte_bancaire" placeholder="Entrez le numéro de votre carte (16 chiffres)" pattern="\d{16}" maxlength="16" required>

                </div>
                    <div class="col-md-3 text-end">
                        <label for="carte_bancaire" class="form-label ">Experation</label>


                    </div>
                <div class="col-md-3">
                    <input type="month" class="form-control" id="exp" name="carte_bancaire"  pattern="\d{16}" maxlength="16" required>

                </div>
            </div>
            <div class="row mb-3">
                            <label for="adresse" class="form-label">Adresse</label>
                            <input type="text" class="form-control" id="adresse" name="adresse_livraison" placeholder="Entrez votre adresse" autocomplete="off">
                            <div class="dropdown">
                                <ul class="dropdown-menu w-100" id="suggestions" style="max-height: 100px; overflow-y: auto;"></ul>
                            </div>
            </div>
                
            

           

            <!-- Bouton Payer -->
            <div class="row mb-3">
                <div class="col-md-12">
                    <button type="submit" class="btn btn-success w-45">Payer</button>
                </div>
            </div>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="adresse.js"></script>

</body>
</html>