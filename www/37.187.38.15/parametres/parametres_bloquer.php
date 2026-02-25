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
    <title>Comptes bloqués</title>
</head>
<body>
    <?php

    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }
    
    include('header_parametres.php');

    echo "<h1 class='text-center m-4'> Utilisateurs que vous avez bloqué </h1>";

    include("../bdd.php");
    
    $bloquerparam = "SELECT UTILISATEUR.pseudo, UTILISATEUR.id_utilisateur FROM UTILISATEUR
    JOIN AMITIER ON UTILISATEUR.id_utilisateur = AMITIER.id_amis_demande OR UTILISATEUR.id_utilisateur = AMITIER.id_amis_recoit
    WHERE AMITIER.etat = 'bloqué' AND (AMITIER.id_amis_demande = :idrecoit)";
    $blokparamstatement = $bdd->prepare($bloquerparam);
    $blokparamstatement->execute([
        'idrecoit' => $_SESSION['id']
    ]);
    $blokparamresults = $blokparamstatement->fetchAll(PDO::FETCH_ASSOC);

    $bloquerorigin = "SELECT * FROM AMITIER WHERE id_amis_demande = :id";
    $statementorigin = $bdd->prepare($bloquerorigin);
    $statementorigin->execute([
        'id' => $_SESSION['id']
    ]);
    $blokorigin = $statementorigin->fetchAll(PDO::FETCH_ASSOC);

    if($blokparamresults){
        foreach($blokparamresults as $amisblok){
            if($_SESSION['id'] != $amisblok['id_utilisateur'] && $blokorigin)
            echo "<a href=../profil.php?user=" . htmlspecialchars($amisblok['pseudo']) . ">" . $amisblok['pseudo'] . "</a><br>";
            }       
        } else {
            echo "<h5 class='mx-4'> Vous n'avez bloqué personne </h5>";
    }

    ?>
</body>
</html>