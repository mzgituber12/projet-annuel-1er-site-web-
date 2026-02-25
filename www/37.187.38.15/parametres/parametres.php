<?php session_start();
include_once("theme.php");
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styletext.css">
    <title>Parametres</title>
</head>
<body>
    <?php

    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }
    
    include("header_parametres.php");

    echo "<h2 class='text-center milonga-regular m-4'>Parametres</h2>";
    
    include('../getmessage.php') ?>

<div style="margin-top:14.5px">
<p><a href="parametres_perso.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block link-opacity-50-hover link-underline-opacity-0"><i class="bi bi-person-lines-fill"></i>  Changer ses informations personnelles</a></p>
<p><a href="parametres_bloquer.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-person-slash"></i>  Comptes bloqués</a></p>
<p><a href="parametres_contenu.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-person-vcard"></i>  Suggérer du contenu à ajouter</a></p>
<p><a href="parametres_couleur.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-circle-half"></i>  Changer le thème visuel</a></p>
<p><a href="parametres_notif.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-bell"></i>  Notifications</a></p>
<p><a href="parametres_statutcompte.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-hourglass-split"></i>  Statut du compte</a></p>
<p><a href="parametres_temps.php" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-clock-history"></i>  Gestion du temps</a></p>
<p onclick="supprimercompte()">
  <a href="#" class="text-reset text-decoration-none mx-5 mb-3 d-inline-block"><i class="bi bi-person-dash"></i>  Désactiver ou supprimer le compte</a>
</p>
<div style="margin-left:30px">
<p id="supp1"></p>
<form method="post" action="parametres_suppression.php">
  <input type="password" id="supp2" name="mdp2" class="champ1" style="display:none">
  <input type="submit" id="supp3" name="envoyer" class="champ1" value="Envoyer" style="display:none">
</form>
</div>
</div>


    <?php include("footer_parametres.php"); ?>

    <script> 

    function supprimercompte(){
        const a = document.getElementById("supp1")
        const b = document.getElementById("supp2")
        const c = document.getElementById("supp3")
        a.innerHTML = "Veuillez taper votre mot de passe pour acceder à cette section"
        b.style.display = "inline-block";
        c.style.display = "inline-block";
    }

    </script>
    
</body>
</html>