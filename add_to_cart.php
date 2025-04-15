<?php
session_start();




// Connexion à la base de données

    $bdd = new PDO('mysql:host=localhost;dbname=restaurant;charset=utf8', 'koceila', '123456789');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);


// Récupérer les données JSON de la requête
$input = file_get_contents('php://input');
$data = json_decode($input, true);

// Valider les données
if (!isset($data['id_plat']) || !isset($data['id_client'])) {
    echo json_encode(['success' => false, 'message' => 'Données invalides']);
    exit();
}

$id_plat = $data['id_plat'];
$id_client = $data['id_client'];

// Vérifier que le plat existe
$req = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id");
$req->execute(['id' => $id_plat]);
$plat = $req->fetch(PDO::FETCH_OBJ);

// Ajouter au panier

    $req = $bdd->prepare("INSERT INTO panier (id_plat, id_client, quantite) VALUES (:id_plat, :id_client, 1)");
    $req->execute(['id_plat' => $id_plat, 'id_client' => $id_client]);
    echo json_encode(['success' => true, 'message' => 'Plat ajouté au panier']);

?>