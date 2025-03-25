<?php
session_start(); // Démarrer la session pour stocker les infos de l'utilisateur

// Connexion à la base de données (modifie les valeurs selon ton serveur)
$host = 'localhost';
$dbname = 'nom_de_ta_base';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Vérifier si le formulaire est soumis
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);

    // Vérifier si les champs ne sont pas vides
    if (!empty($email) && !empty($password)) {
        // Préparer la requête SQL pour récupérer l'utilisateur
        $stmt = $pdo->prepare("SELECT * FROM utilisateurs WHERE email = :email");
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password'])) {
            // Authentification réussie, stocker les infos en session
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_email'] = $user['email'];
            $_SESSION['user_name'] = $user['nom'];

            // Rediriger vers la page d'accueil ou tableau de bord
            header("Location: dashboard.php");
            exit;
        } else {
            $error = "Email ou mot de passe incorrect.";
        }
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-4">
                <div class="card shadow p-4">
                    <h2 class="text-center">Connexion</h2>
                    <?php if (isset($error)) { echo "<p class='text-danger text-center'>$error</p>"; } ?>
                    <form method="POST" action="">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Mot de passe</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100">Se connecter</button>
                    </form>
                    <p class="text-center mt-3"><a href="register.php">Créer un compte</a></p>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
