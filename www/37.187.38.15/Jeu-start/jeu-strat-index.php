<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('location:index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="icon" href="../Logo.png" type="image/png">
    <title>Battlepast</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
    <style>
        body {
            background-color: #f8f9fa;
        }
        .menu_bouton {
            min-height: 1rem;
        }
        .btn-lg-custom {
            font-size: 2.4rem !important;
            padding: 2rem 3rem !important;
        }
        .btn-xl-custom {
            font-size: 2.4rem !important;
            padding: 2rem 3rem !important;
        }
    </style>
</head>
<body>
    <?php include("headerjeu.php"); ?>

    <h1>Combats</h1>

    <div style="height: 3rem;"></div>

    <div class="container d-flex flex-column justify-content-center align-items-center menu_bouton">

        <div class="row mb-4 w-200 justify-content-center">
            <div class="col-auto mb-3">
                <a href="selectionequipe.php" class="btn btn-primary btn-lg-custom">Gestion d'équipe</a>
            </div>
            <div class="col-auto">
                <a href="creer_deck.php" class="btn btn-primary btn-lg-custom">Sélectionner son deck</a>
            </div>
        </div>
        <div class="row w-100 justify-content-center">
            <div class="col-auto">
                <a href="combat/index_combat.php" class="btn btn-success btn-xl-custom">JOUER</a>
            </div>
        </div>
    </div>

</body>
</html>
