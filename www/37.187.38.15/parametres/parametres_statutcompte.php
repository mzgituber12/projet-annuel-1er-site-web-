<?php session_start(); 
include_once("theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Statut du compte</title>
</head>
<body>
    <?php
    
    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }
    include("header_parametres.php");
    include("../bdd.php")?>

    <h1> Statut de votre compte </h1>

<?php

$warn = $bdd->prepare("SELECT * FROM account_statut WHERE id_utilisateur = :id");
$warn->execute(['id' => $_SESSION['id']]);
$warns = $warn->fetchAll();

if ($warns) {
    $compteur=count($warns);
    echo "<h3> Vous avez " . $compteur . " signalements</h3>";
    foreach($warns as $statut){
        $dateTime = new DateTime($statut['date']);
        $date_formatee = $dateTime->format('d/m/Y');
        echo "<p>" . $date_formatee . " : " . $statut['contenu'] . "</p>";
    }
} else {
    echo "<h3>Aucun signalement recensé concernant votre compte</h3>";
}

include("footer_parametres.php") ?>
</body>
</html>