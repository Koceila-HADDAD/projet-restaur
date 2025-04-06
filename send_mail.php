<?php
// Inclure les fichiers PHPMailer manuellement
require 'phpmailer/PHPMailer.php';
require 'phpmailer/SMTP.php';
require 'phpmailer/Exception.php';

// Importer les namespaces
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Créer une instance de PHPMailer
$mail = new PHPMailer(true);

try {
    // Configuration SMTP (exemple avec Gmail)
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'bleublancsaveur@gmail.com'; // Remplacez par votre email
    $mail->Password = 'lqfz oqxe djjj qtwt'; // Mot de passe d'application Gmail
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

    // Contenu de l'email
    $mail->setFrom('bleublancsaveur@gmail.com', 'Bleu Blanc Saveur');
    $mail->addAddress('koceila.haddad@outlook.com'); // Email du destinataire
    $mail->Subject = 'Test depuis WAMP';
    $mail->Body = 'Ceci est un test depuis WAMP !';

    // Envoyer l'email
    $mail->send();
    echo 'Email envoyé avec succès !';
} catch (Exception $e) {
    echo "Erreur : " . $mail->ErrorInfo;
}
?>