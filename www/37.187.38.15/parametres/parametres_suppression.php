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
    <title>Suppression ou Desactivation</title>
</head>
<body>
    <?php

    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }

    if (hash('sha512', $_POST['mdp2']) !== $_SESSION['mdp']){
        header('location:parametres.php?message=Le mot de passe est incorrect');
        exit;
    }

    if ($_SESSION['role'] == 'admin'){
        header('location:parametres.php?message=Vous etes administrateur du site, vous ne pouvez pas supprimer votre compte');
        exit;
    }

    include('header_parametres.php');

        ?>

        <h1> Supprimer ou Desactiver votre compte </h1>

    <button onclick="supprimer()">Supprimer votre compte</button>
    <p id="supprimer"></p>
    <button id="oui" onclick="confirmer()" style="display: none">Confirmer</button>
    <button id="non" onclick="annuler()" style="display: none">Annuler</button>

    <?php include("footer_parametres.php") ?>

    <script>
        function supprimer(){
            const supp = document.getElementById("supprimer")
            const non = document.getElementById("non")
            const oui = document.getElementById("oui")
            supp.innerHTML = "Etes vous sur de vouloir supprimer votre compte ? Toutes vos données seront supprimées."
            non.innerHTML = "Annuler"
            oui.innerHTML = "Confirmer"
            non.style.display = "inline-block";
            oui.style.display = "inline-block";
        }

        function annuler(){
            const supp = document.getElementById("supprimer")
            const annul = document.getElementById("non")
            const annul2 = document.getElementById("oui")
            supp.innerHTML = ""
            annul.innerHTML = ""
            annul2.innerHTML = ""
            non.style.display = "none";
            oui.style.display = "none";
        }

        function confirmer(){
            window.location.href = "../verification/verif_parametres_suppression.php";
        }
    </script>

</body>
</html>