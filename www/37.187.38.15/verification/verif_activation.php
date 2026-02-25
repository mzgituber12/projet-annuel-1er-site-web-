<?php
session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

function activationLog($pseudo, $email){
    $stream = fopen('../log/activation_log.txt', 'a+');
    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' | Adresse email : ' . $email . ', a vérifié son compte' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

$token=$_POST['token'];
if ($_POST['token'] != $_SESSION['token']){
    header('location: ../activation_du_compte.php?message=reussit&failtoken&captcha=' . $_SESSION['captcha']);
    exit;
};
date_default_timezone_set('Europe/Paris');
$temps = date('Y-m-d H:i:s');

    include("../bdd.php");

    $stmt = $bdd->prepare("DELETE FROM TOKENS WHERE expiration <= ?");
    $stmt->execute([$temps]);

    $stmt = $bdd->prepare("SELECT expiration FROM TOKENS WHERE id_utilisateur = ? AND value = ? AND expiration > ?");
    $stmt->execute([$_SESSION['id'], $token, $temps]);

    if ($stmt->fetchColumn()) {
        $stmt = $bdd->prepare("UPDATE UTILISATEUR SET statut = '1' WHERE id_utilisateur = ?");
        $stmt->execute([$_SESSION['id']]);
        $stmt = $bdd->prepare("DELETE FROM TOKENS WHERE id_utilisateur = ?");
        $stmt->execute([$_SESSION['id']]);

        activationLog($_SESSION['pseudo'], $_SESSION['email']);

        $_SESSION['statut'] = 1;
        $_SESSION['token'] = "";
        header('location: ../deconnexion.php?tokenreussit');
        exit;
}