<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>mail</title>
</head>
<body>
<?php
session_start();

if (!isset($_SESSION['id'])) {
    header("Location: login.php");
    exit();
}

try {
    $bdd = new PDO('mysql:host=localhost;dbname=restaurant', 'koceila', '123456789');
    $bdd->exec('SET NAMES utf8');
} catch (Exception $e) {
    die('Erreur:' . $e->getMessage());
}
$id_client = $_SESSION['id'];
$email_cl = $_SESSION['email'];
$carte = $_SESSION['carte'];

$derniers_4_chiffres = substr($carte, -4);
$totalmail = isset($_SESSION['total']) ? $_SESSION['total'] : 0;
////////////////////////////////////////////////////////////////////////////////////////////////////////////:
 
$req2 = $bdd->prepare("SELECT id_paiement FROM paiements WHERE id_client=:id_client ORDER BY id_paiement DESC LIMIT 1 ");
$req2->execute(['id_client' => $id_client ]);
$data = $req2->fetch(PDO::FETCH_OBJ);
$facture = $data->id_paiement;









//////////////////////////////////////////////////////////////////////////////////////////////////////////////


require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bleublancsaveur@gmail.com';
    $mail->Password = 'lqfz oqxe djjj qtwt'; 
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;
    $mail->CharSet = 'UTF-8';

    // Contenu de l'email
    $mail->setFrom('bleublancsaveur@gmail.com', 'Bleu Blanc Saveur');
    $mail->addAddress($email_cl); 
    $mail->Subject = 'Votre facture - Bleu Blanc Saveur';
    $mail->isHTML(true);
    $date = new DateTime('now', new DateTimeZone('Europe/Paris'));
    
    $body = '
    <h2 style="color: #003087;">Bleu Blanc Saveur</h2>
    <p>Restaurant Français - 30 Rue Esquirol 75013 Paris</p>
    <hr>
    <h3>FACTURE</h3>
    <p><strong>Date :</strong> ' . $date->format('d/m/Y H:i') . '</p>
    <p><strong>Numéro de facture :</strong>' . $facture . '  </p>
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background-color: #f2f2f2;">
                <th style="padding: 8px; border: 1px solid #ddd;">Description</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Quantité</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Prix unitaire</th>
                <th style="padding: 8px; border: 1px solid #ddd;">Prix totale</th>



            </tr>
        </thead>
        <tbody>';
 
   
    $req = $bdd->prepare("SELECT id_panier,id_plat,id_client,quantite FROM panier WHERE id_client = :id_client  GROUP BY id_plat ");
    $req->execute(['id_client' => $id_client]);
    $items_html = '';
    $totale = 0;

    while ($data = $req->fetch(PDO::FETCH_OBJ)) {
        $id_plat = $data->id_plat;
        $quantite = $data->quantite;

    ///////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
                    
    $req_count = $bdd->query("SELECT COUNT(*) as nombre_rep FROM panier WHERE id_client = $id_client AND id_plat = $id_plat ");
                
    $result_rep = $req_count->fetch(PDO::FETCH_ASSOC);
    $nombre_rep = $result_rep['nombre_rep'];
    $quantite=$quantite +  $nombre_rep - 1; 

    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        $req2 = $bdd->prepare("SELECT * FROM plat WHERE id_plat = :id_plat");
        $req2->execute(['id_plat' => $id_plat]);
        $plat_data = $req2->fetch(PDO::FETCH_OBJ);

       
            $nom_plat = $plat_data->nom_plat;
            $prix_plat = $plat_data->prix;
            $totale += $prix_plat;
            
            // Ajouter chaque élément au tableau HTML
            $items_html .= '
            <tr>
                <td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($nom_plat) . '</td>
                <td style="padding: 8px; border: 1px solid #ddd;">' . htmlspecialchars($quantite) .'</td>
                <td style="padding: 8px; border: 1px solid #ddd;">' . number_format($prix_plat, 2) . ' €</td>
                <td style="padding: 8px; border: 1px solid #ddd;">' . number_format($prix_plat*$quantite) . ' €</td>

            </tr>';
        
    }

    // Ajouter les éléments au corps de l'email
    $body .= $items_html . '
        </tbody>
    </table>
    <hr>
    <p><strong>Total TTC :</strong> ' . number_format($totalmail, 2) . ' €</p>
    <hr>
    <p>Merci de votre visite chez Bleu Blanc Saveur !</p>
    <p>Paiement reçu par carte bancaire.**** **** **** '.$derniers_4_chiffres.'</p>';

    $mail->Body = $body;

    // Version texte brut
    $mail->AltBody = "Bleu Blanc Saveur\nRestaurant Français - 123 Rue des Saveurs, 75001 Paris\nFACTURE\nDate: " . date('d/m/Y H:i') . "\nNuméro de facture: #00125\nTotal TTC: " . number_format($totalmail, 2) . "€\nMerci de votre visite !";

    // Envoyer l'email
    $mail->send();
    echo 'Facture envoyée avec succès !';

    // Vider le panier après envoi
    $req_vider_panier = $bdd->prepare("DELETE FROM panier WHERE id_client = :id_client");
    $req_vider_panier->execute(['id_client' => $id_client]);

} catch (Exception $e) {
    echo "Erreur : " . $mail->ErrorInfo;
}
?>

</body>
</html>