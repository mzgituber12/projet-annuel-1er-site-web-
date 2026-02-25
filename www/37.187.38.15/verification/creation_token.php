<?php
session_start();
date_default_timezone_set('Europe/Paris');

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}
require '../vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$id = $_SESSION['id'];
$email = $_SESSION['email'];

    include("../bdd.php");

    function generateToken($length = 20) {
        return bin2hex(random_bytes($length / 2)); 
    }
    
    $token = generateToken();
    $_SESSION['token'] = $token;
    $expiration = date('Y-m-d H:i:s', time() + (5 * 60)); 

    $stmt = $bdd->prepare("INSERT INTO TOKENS (value, id_utilisateur, expiration) VALUES (?, ?, ?)");
    $stmt->execute([$token, $id, $expiration]);

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'battlepast23@gmail.com'; 
        $mail->Password = 'duxw kkku xafh aufx'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        $mail->setFrom('battlepast23@gmail.com', 'Service de Verification');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Votre code de verification';
        $mail->Body = "Bonjour,<br><br>Votre code de vérification est : <strong>$token</strong><br>Ce code est valable 5 minutes.";

        if ($mail->send()) {
            header('location: ../activation_du_compte.php?message=reussit&captcha=' . $_SESSION['captcha']);
            exit;
        } else {
            echo "Erreur lors de l'envoi de l'email.";
        }

    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
    }

?>