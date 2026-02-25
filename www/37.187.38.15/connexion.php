<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f8f9fa;
        }
        .form-container {
            background-color: #d9d9d9;
            padding: 2rem;
            border-radius: 10px;
        }
        .form-title {
            font-weight: 300;
            font-size: 2rem;
            margin-left: 2rem;
            margin-top: 1rem;
        }
        .forgot-link {
            font-size: 0.8rem;
        }
    </style>
</head>
<body>

<?php
session_start();

if (isset($_SESSION['email'])) {
    header("location:index.php");
    exit;
}

include("header.php");
?>

<h1 class="form-title">Connectez-vous !</h1>

<?php include('getmessage.php'); ?>

<div class="container d-flex justify-content-center align-items-center my-4">
    <form method="post" action="verification/verif_connexion.php" class="form-container w-100" style="max-width: 600px;">
        <div class="mb-3">
            <label for="pseudo" class="form-label">Nom d'utilisateur :</label>
            <input type="text" class="form-control" id="pseudo" name="pseudo" value="<?= isset($_COOKIE['pseudo']) ? htmlspecialchars(urldecode($_COOKIE['pseudo'])) : "" ?>">
        </div>
        <div class="mb-3">
            <label for="mdp" class="form-label">
                Mot de passe :
                <br>
                <a href='mdp_oublie.php' class="forgot-link">Mot de passe oublié ?</a>
            </label>
            <input type="password" class="form-control" id="mdp" name="mdp">
        </div>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div>
                <span>Vous n'avez pas de compte ?</span> <a href="inscription.php">Inscrivez-vous</a>
            </div>
            <button type="submit" class="btn btn-primary">Envoyer</button>
        </div>
    </form>
</div>

</body>
</html>