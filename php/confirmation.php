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
    $id_client=$_SESSION['id'];
} catch (Exception $e) {
    die('Erreur:' . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confirmation</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/style.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <link rel="icon" type="image/png" href="../logo/favicon.ico">
    
    <style>
        .position-relative {
            position: relative;
        }
        .custom-dropdown {
            position: absolute;
            top: calc(100% + 0.5rem); /* Juste en dessous de l'input */
            width: 100%;
            display: none; /* Caché par défaut */
            border-radius: 0.25rem;
            overflow-y: auto;
            max-height: 100px;
        }
        .custom-dropdown.show {
            display: block !important; /* Affiche quand actif */
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
      

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
            $_SESSION['email']=$email;
            $carte_bancaire = htmlspecialchars($_POST['carte_bancaire']);
            $_SESSION['carte']=$carte_bancaire;
            $id_client = $_SESSION['id'];
            
            // Validation supplémentaire (exemple)
            if (!preg_match('/^\d{16}$/', $carte_bancaire)) {
                echo '<div class="alert alert-danger">Numéro de carte invalide. Veuillez entrer 16 chiffres.</div>';
            } else {
                // Insertion dans la table paiements
                $req = $bdd->prepare("INSERT INTO paiements (nom_paiement, prenom_paiement, email_paiement, carte_bancaire, id_client) VALUES (:nom, :prenom, :email, :carte_bancaire, :id_client)");
                $req->execute([
                    'nom' => $nom,
                    'prenom' => $prenom,
                    'email' => $email,
                    'carte_bancaire' => $carte_bancaire,
                    'id_client' => $id_client,
                ]);

                
                    include 'mail.php';
                // Redirection après succès

                
                header("Location: accueil.php");
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
                    <label for="code_securite" class="form-label">Code sécurité</label>
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" id="code_securite" name="code_securite" placeholder="Entrez le numéro de votre code_securite (3 chiffres)" pattern="\d{3}" maxlength="3" required>

                </div>
                    <div class="col-md-3 text-end">
                        <label for="carte_bancaire" class="form-label ">Experation</label>


                    </div>
                <div class="col-md-3">
                    <input type="month" class="form-control" id="exp" name="exp"  required>

                </div>
            </div>
            <div class="row mb-3">
                <div class="col-md-4">
                    <label for="adresse" class="form-label">Adresse</label>
                </div>
                <div class="col-md-8">
                    <div class="position-relative">
                        <input type="text" class="form-control" id="adresse" name="adresse_livraison" placeholder="Entrez votre adresse" autocomplete="off">
                        <ul class="dropdown-menu custom-dropdown" id="suggestions"></ul>
                    </div>
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
    <script>
        $(document).ready(function() {
            let timeoutId;
            const $input = $('#adresse');
            const $suggestions = $('#suggestions');

            $input.on('input', function() {
                clearTimeout(timeoutId);
                const query = $(this).val();

                if (query.length < 3) {
                    $suggestions.empty().removeClass('show');
                    return;
                }

                timeoutId = setTimeout(function() {
                    $.ajax({
                        url: 'https://api-adresse.data.gouv.fr/search/',
                        data: { q: query, limit: 5 },
                        success: function(data) {
                            $suggestions.empty();
                            if (data.features.length > 0) {
                                data.features.forEach(function(feature) {
                                    const adresse = feature.properties.label;
                                    const $item = $('<li>')
                                        .append($('<a>')
                                            .addClass('dropdown-item')
                                            .text(adresse)
                                            .attr('href', '#')
                                            .data('adresse', feature.properties)
                                        );
                                    $suggestions.append($item);
                                });
                                $suggestions.addClass('show');
                            } else {
                                $suggestions.removeClass('show');
                            }
                        },
                        error: function() {
                            $suggestions.removeClass('show');
                        }
                    });
                }, 300);
            });

            $suggestions.on('click', '.dropdown-item', function(e) {
                e.preventDefault();
                const adresseData = $(this).data('adresse');
                $input.val(adresseData.label);
                $suggestions.removeClass('show');
                console.log('Adresse complète:', adresseData.label);
                console.log('Code postal:', adresseData.postcode);
                console.log('Ville:', adresseData.city);
            });

            $(document).on('click', function(e) {
                if (!$(e.target).closest('#adresse, #suggestions').length) {
                    $suggestions.removeClass('show');
                }
            });

            $input.on('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                }
            });
        });
    </script>
</body>
</html>