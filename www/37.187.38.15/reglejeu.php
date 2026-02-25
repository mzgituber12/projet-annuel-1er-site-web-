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
    <title>Regles du Jeu</title>
</head>
<body>
    <?php
    include("header.php"); 
    ?>
    <div style="margin-left:15px; margin-top:3px">
    <h1>Regles du Jeu</h1>
    
    <h2>Présentation</h2>
    <p>Battlepast est un jeu de stratégie en 1 contre 1 où chaque joueur possède une carte terrain qu'il doit défendre impérativement à l'aide des cartes héros presente dans son deck
    </p>
    
    <h2>Types de cartes</h2>
    <p>Il y'a 2 types de cartes : les cartes héros et les cartes terrains. <br>Les cartes héros ont trois rôles distincts :</p>
    <ul>
        <li><strong>Tank</strong> : Résistant, absorbe les dégâts.</li>
        <li><strong>Combattant</strong> : Attaque puissante, équilibré.</li>
        <li><strong>Support</strong> : Apporte des bonus aux alliés.</li>
    </ul>
    
    <h2>Conditions de victoire</h2>
    <p>Un joueur peut gagner de deux manières :</p>
    <ul>
        <li>En détruisant la carte terrain de l'adversaire.</li>
        <li>En éliminant les 5 cartes héros de l'adversaire.</li>
    </ul>
    
    <h2>Disposition du plateau de jeu</h2>
    <p>Le plateau de jeu est composé de 6 cases (3 en longueur, 2 en largeur). Si une des trois colonnes de largeur est vide, les joueurs peuvent attaquer directement la carte terrain.</p>
    
    <h2>Bonus et malus de position</h2>
    <ul>
        <li>Carte placée devant : Bonus de dégâts, malus de défense.</li>
        <li>Carte placée derrière : Bonus de défense, malus de dégâts.</li>
    </ul>
    
    <h2>Rareté des cartes</h2>
    <p>Les cartes ont cinq niveaux de rareté :</p>
    <ul>
        <li><strong>Légendaire</strong></li>
        <li><strong>Épique</strong></li>
        <li><strong>Super Rare</strong></li>
        <li><strong>Rare</strong></li>
        <li><strong>Commun</strong></li>
</ul>

    <h2>Obtention des cartes</h2>
    <p>Les cartes peuvent être obtenus en ouvrant des coffres ou en étant achetés dans la boutique communautaire lorsqu'ils sont mis en ventes par les joueurs.<br>
    Lorsque vous créez un compte, vous commencez avec 5 héros et 1 terrain, tous de rareté commun.
    </p>

    <h2>Boutique</h2>
    <p>La boutique est un endroit qui permet d'acheter des coffres utiles à l'obtention de cartes. Chaque jour, la boutique quotidienne propose des coffres différents.
    <br>Lors d'événements spéciaux, la boutique quotidienne peut proposer des coffres limités ne pouvant être achetés que pendant ces périodes la. 
    </p>

    <h2>Boutique communautaire</h2>
    <p>La boutique communautaire est un endroit ou les joueurs peuvent acheter les cartes mis en ventes par d'autres joueurs a des prix fixés par les vendeurs. 
        Si une carte mise en vente n'a pas été achetée apres 24h, la mise en vente de cette carte est interrompue et le vendeur récupere automatiquement la carte dans son inventaire
    </p>

    <h2>Coins</h2>
    <p>Les joueurs peuvent obtenir une monnaie virtuelle appelée coin en gagnant des combats ou en reussissant des quizz. 
        Cette monnaie leur sera utile pour acheter des coffres ou des cartes dans la boutique communautaire</p>

    <h2>Quizz</h2>
    <p>Les utilisateurs peuvent obtenir des récompenses variées en répondant a des quizz tres diversifiés portant sur les thèmes de l'histoire et de la géographie.</p>
</div>

    <?php include("footer.php")?>
</body>
</html>