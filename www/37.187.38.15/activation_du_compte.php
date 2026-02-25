<?php session_start(); 
include_once("parametres/theme.php");?>
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
    <title>Activation du compte</title>
</head>
<body>
    <?php

    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }

    include("header.php");
    echo "<h1>Activation du compte</h1>";

    if ((!isset($_GET['captcha']) || $_GET['captcha'] !== $_SESSION['captcha'])){

        echo "<h2> Etape 1 : Captcha</h2>";

        if (isset($_GET['captcha'])){
            echo "<h3> La suite de caracteres saisies est invalide </h3>";
        }

    $list = ['a', 'b', 'c', 'd', 'e', 'f', 'g', 'h', 'i', 'j', 'k', 'l', 'm', 'n', 'o', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'y', 'z',
    'A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O', 'P', 'Q', 'R', 'S', 'T', 'U', 'V', 'W', 'X', 'Y', 'Z',
    '1', '2', '3', '4', '5', '6', '7', '8', '9', '0'];

    $_SESSION['captcha'] = $list[rand()%62] . $list[rand()%62] . $list[rand()%62] . $list[rand()%62] . $list[rand()%62] .  $list[rand()%62];
    
    echo  $_SESSION['captcha'] ;

    ?>

    <form method="get" action='activation_du_compte.php'>
        <p>Veuillez entrer la suite de caracteres ci dessus</p>
        <input type='text' name='captcha' placeholder="XXXXXX">
        <input type='submit' value='Envoyer'>
    </form>
    
    <?php 
        exit;
    }
    ?>

    <h2> Etape 2 : Code de verification</h2>
    
    <button onclick="window.location.href='verification/creation_token.php'">Créer son code de vérification</button>
    <?php
    if(isset($_GET['message'])){
        if(isset($_GET['failtoken'])){
            echo '<h3> Le code de vérification saisi est invalide </h3>';
        }
        if($_GET['message'] == 'reussit'){
            echo '
        <form action="verification/verif_activation.php" method="POST">
            <label for="token">Entrez votre code de vérification envoyé par mail:</label>
            <input type="text" id="token" name="token" required>
            <button type="submit">Valider</button>
        </form>';
        }

    }
    ?>
</body>
</html>