<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inscription</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: #d9d9d9;
            padding: 2rem;
            border-radius: 15px;
        }
        .form-title {
            font-weight: 300;
            font-size: 1.8rem;
            margin-left: 2rem;
            margin-top: 1rem;
        }
        .form-footer {
            font-size: 0.9rem;
        }
    </style>
</head>
<body>

<?php
session_start();

if (isset($_SESSION['email'])) {
    if ($_GET['message'] == 'Compte crée avec succes !') {
        include("header.php");
        echo "<h1>" . htmlspecialchars($_GET['message']) . "</h1>";
        echo "<a href='index.php'>Retour à l'accueil</a>";
    } else {
        header("location:index.php");
    }
    exit;
}

include("header.php");
?>

<h1 class="form-title">Inscrivez-vous</h1>

<?php include('getmessage.php'); ?>

<div class="container d-flex justify-content-center align-items-center my-4">
    <form method="post" action="verification/verif_inscription.php" enctype="multipart/form-data" class="form-container w-100" style="max-width: 700px;">
        <div class="mb-3">
            <label for="pseudo" class="form-label">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo"
                value="<?= isset($_COOKIE['inscripseudo']) ? htmlspecialchars(urldecode($_COOKIE['inscripseudo'])) : '' ?>">
        </div>

        <div class="mb-3">
            <label for="email" class="form-label">Email :</label>
            <input type="email" class="form-control" id="email" name="email"
                value="<?= isset($_COOKIE['inscriemail']) ? htmlspecialchars(urldecode($_COOKIE['inscriemail'])) : '' ?>">
        </div>

        <div class="mb-3">
            <label for="mdp" class="form-label">Mot de passe :</label>
            <input type="password" class="form-control" id="mdp" name="mdp">
        </div>

        <div class="mb-3">
            <label for="mdp2" class="form-label">Répétez le mot de passe :</label>
            <input type="password" class="form-control" id="mdp2" name="mdp2">
        </div>

        <div class="mb-4">
            <label for="pdp" class="form-label">Photo de profil (facultatif) :</label>
            <input type="file" class="form-control" id="pdp" name="pdp" accept="image/png">
        </div>

        <div class="d-flex justify-content-between align-items-center">
            <div class="form-footer">
                Vous avez déjà un compte ? <a href="connexion.php">Connectez-vous</a>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</div>

</body>
</html>