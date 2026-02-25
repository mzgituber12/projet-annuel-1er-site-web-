<?php session_start();
include_once("../parametres/theme.php");
include('pasadmin.php');
?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Lexique des commandes</title>
</head>
<body>
    <?php include("headerad.php") ?>
    <h1>Lexique des commandes</h1>

    <h3> Verifier l'email d'un utilisateur : </h3>
    <p> commande/verif/pseudoutilisateur </p>

    <h3> Supprimer la verification d'email d'un utilisateur : </h3>
    <p> commande/suppverif/pseudoutilisateur </p>

    <h3> Mettre un utilisateur admin : </h3>
    <p> commande/admin/pseudoutilisateur </p>

    <h3> Enlever un utilisateur de ses permissions d'admin : </h3>
    <p> commande/suppadmin/pseudoutilisateur </p>

    <h3> Bannir un utilisateur : </h3>
    <p> commande/ban/pseudoutilisateur <p>

    <h3> Debannir un utilisateur : </h3>
    <p> commande/deban/pseudoutilisateur <p>

    <h3> Avertir un utilisateur : </h3>
    <p> commande/avertir/pseudoutilisateur <p>

    <h3> Modifier les données du compte d'un utilisateur : </h3>
    <p> commande/modifier/pseudoutilisateur </p>

    <h3> Supprimer le compte d'un utilisateur : </h3>
    <p> commande/supprimer/pseudoutilisateur </p>

    <h3> Creer une carte : </h3>
    <p> commande/carte/creer </p>

    <h3> Modifier une carte : </h3>
    <p> commande/carte/modifier/idcarte </p>

    <h3> Supprimer une carte : </h3>
    <p> commande/carte/supprimer/idcarte </p>

    <h3> Creer une attaque : </h3>
    <p> commande/attaque/creer </p>

    <h3> Modifier une attaque : </h3>
    <p> commande/attaque/modifier/idattaque </p>

    <h3> Supprimer une attaque : </h3>
    <p> commande/attaque/supprimer/idattaque </p>

    <h3> Creer une actualité : </h3>
    <p> commande/actu/creer </p>

    <h3> Modifier une actualité : </h3>
    <p> commande/actu/modifier/idactualite </p>

    <h3> Supprimer une actualité : </h3>
    <p> commande/actu/supprimer/idactualité </p>

    <h3> Ajouter des cartes à l'inventaire d'un utilisateur : </h3>
    <p> commande/inventaire/ajouter/idcarte/pseudoutilisateur </p>

    <h3> Supprimer des cartes de l'inventaire d'un utilisateur : </h3>
    <p> commande/inventaire/supprimer/idcarte/pseudoutilisateur </p>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>