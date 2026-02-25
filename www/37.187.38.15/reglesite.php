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
    <title>Règlement du Site</title>
</head>
<body>
<?php
include("header.php");
?>
    <div style="margin-left:15px; margin-top:3px" class="container">
        <h1>Règlement du Site</h1>
         <section>
        <h2>Règles Générales</h2>
        <p>En utilisant ce site, vous acceptez de respecter les règles suivantes :</p>
        <ul>
            <li>Ne pas poster de contenu offensant, haineux ou illégal.</li>
            <li>Respecter les autres membres et éviter tout harcèlement.</li>
            <li>Ne pas publier de fausses informations ou de contenu trompeur.</li>
            <li>Respecter les droits d'auteur et ne pas partager de contenu protégé sans autorisation.</li>
        </ul>
        </section>
        <h2>Règles précise</h2>
        <section>
        <h2>1. Inscription et Compte Utilisateur</h2>
        <ul>
            <li>L'inscription et la verification de mail sont obligatoire pour participer aux quiz, aux affrontements et aux échanges de cartes.</li>
            <li>Chaque utilisateur doit fournir des informations véridiques lors de l'inscription. L'utilisation de fausses informations peut entraîner une suspension du compte.</li>
        </ul>
    </section>

    <section>
        <h2>2. Utilisation du Site</h2>
        <ul>
            <li>Comme dit plus haut, le site doit être utilisé dans un cadre respectueux des autres utilisateurs. Tout comportement inapproprié, comme les insultes, les menaces ou les harcèlements, entraînera des sanctions.</li>
            <li>Les utilisateurs doivent se conformer aux règles spécifiques des quiz, des combats, et des échanges de cartes. Toute tentative de tricherie ou d'exploitation des failles du système sera sanctionnée.</li>
        </ul>
    </section>

    <section>
        <h2>3. Quiz et Affrontements</h2>
        <ul>
            <li>La participation aux quiz est libre, mais les résultats doivent être obtenus de manière honnête. L'utilisation de moyens externes pour tricher est interdite.</li>
            <li>Les combats stratégiques se déroulent selon les règles du jeu. Chaque joueur est tenu de respecter les délais de réponse et de jouer de manière juste et de ne pas abuser des limites permise à un but malveillant.</li>
        </ul>
    </section>

    <section>
        <h2>4. Cartes et Echanges</h2>
        <ul>
            <li>Les cartes obtenues via les quiz ou les coffrets doivent être utilisées dans les limites du cadre imposé par le jeu et ne peuvent être revendues contre de l'argent réel.</li>
            <li>Les échanges de cartes entre utilisateurs doivent se faire dans le respect des règles du site sans chercher à le déséquillibrer.</li>
        </ul>
    </section>
        </ul>
    </div>

    <?php include("footer.php")?>
</body>
</html>
