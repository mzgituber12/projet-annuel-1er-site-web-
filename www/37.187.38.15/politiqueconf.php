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
    <title>Politique de Confidentialité</title>
</head>
<body>
    <?php   
    include("header.php"); 
    ?>
    <div style="margin-left:15px; margin-top:3px">
    <h1 class="milonga-regular text-center m-4">Avis de confidentialité</h1>
    <h2 class="milonga-regular mx-1 mb-5">Dernière mise à jour : 7 février 2025</h2>
    <h3 class="milonga-regular mx-5">Notre engagement de confidentialité</h3>
    <p><strong>historia&geo.com</strong> respecte vos droits à la vie privée et reconnaît l'importance de la protection de vos informations personnelles 
    (ou « données personnelles »). Notre Avis de confidentialité décrit les types d'informations personnelles que nous recueillons, 
    pourquoi nous les recueillons, comment nous les partageons et quels choix nous vous donnons. Nous reconnaissons également 
    la nécessité de protéger tout particulièrement les enfants qui visitent et/ou utilisent nos Services.</p>
    
    <h3 class="milonga-regular mx-5">Informations que nous collectons</h3>
    <p>Nous recueillons différentes catégories d'informations, notamment :</p>
    <ul>
        <li>Informations d'identification (nom, adresse e-mail...).</li>
        <li>Données de connexion et d'utilisation (adresse IP, type d'appareil, historique de navigation sur notre site).</li>
        <li>Interactions avec notre service client.</li>
    </ul>
    
    <h3 class="milonga-regular mx-5">Utilisation des informations</h3>
    <p>Nous utilisons les données collectées afin de :</p>
    <ul>
        <li>Fournir et améliorer nos services.</li>
        <li>Gérer votre compte et vos préférences.</li>
        <li>Assurer la sécurité de notre plateforme.</li>
        <li>Personnaliser votre expérience sur notre site.</li>
        <li>Se conformer aux obligations légales et réglementaires.</li>
    </ul>
    
    <h3 class="milonga-regular mx-5">Partage des informations</h3>
    <p>Nous ne vendons ni ne louons vos données personnelles. Cependant, nous pouvons partager vos informations avec :</p>
    <ul>
        <li>Nos prestataires de services pour le bon fonctionnement du site.</li>
        <li>Les autorités compétentes si requis par la loi.</li>
        <li>Des partenaires sous réserve de votre consentement.</li>
    </ul>
    
    <h3 class="milonga-regular mx-5">Vos droits et choix</h3>
    <p>Vous disposez de plusieurs droits concernant vos données personnelles :</p>
    <ul>
        <li>Accéder, corriger ou supprimer vos informations.</li>
        <li>Restreindre ou vous opposer à certains traitements.</li>
        <li>Retirer votre consentement à tout moment.</li>
        <li>Déposer une réclamation auprès d’une autorité de protection des données.</li>
    </ul>
    
    <h3 class="milonga-regular mx-5">Protection des données</h3>
    <p>Nous mettons en place des mesures de sécurité pour protéger vos informations contre tout accès non autorisé, altération ou destruction.</p>
    
    <h3 class="milonga-regular mx-5">Modifications de cette politique</h3>
    <p>Nous pouvons mettre à jour cette politique de confidentialité. Toute modification sera publiée sur cette page.</p>
    
    <h3 class="milonga-regular mx-5">Contact</h3>

    <?php

    if (isset($_SESSION['email'])){
        $s = "<p>Pour toute question, contactez-nous en cliquant <a href='support.php'>ici</a> ou par mail à battlepast@gmail.com.</p>";
    } else {
        $s = "<p>Pour toute question, contactez-nous par mail à battlepast@gmail.com.</p>";
    }

    echo $s;

    echo "</div>";
    

    include("footer.php")?>
</body>
</html>