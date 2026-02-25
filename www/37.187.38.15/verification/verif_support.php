<?php

session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

if (!isset($_POST['messagerie']) ||
    empty($_POST['messagerie'])
    ){
    header('location: ../support.php?message=Vous devez remplir le champ.');
    exit;
}

$_SESSION['messagerie'] = $_POST['messagerie'];

function supportLog($pseudo, $email, $messagerie){
    $stream = fopen('../log/support_log.txt', 'a+');
    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' | Adresse email : ' . $email . ' | Message : ' . $messagerie . "\n";
    fputs($stream, $line);
    fclose($stream);
}

supportLog($_SESSION['pseudo'], $_SESSION['email'], $_SESSION['messagerie']);

header('location: ../support.php?message=Votre message a bien été envoyé, nous vous répondrons par mail très prochainement !');
exit;

?>