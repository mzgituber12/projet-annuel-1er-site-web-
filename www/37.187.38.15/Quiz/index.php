<?php
session_start();
include_once("../parametres/theme.php");
include('../bdd.php');?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

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
    <title>Accueil</title>
</head>
<header><?php include('includes/header.php');?></header>  
<body>
<?php
    if ($_SESSION['role'] == 'admin'){

    echo'<div class="d-grid gap-2 col-6 mx-auto m-5">
<a href="add_quiz.php" class="btn btn-outline-info btn-lg px-5 mb-3" role="button">Créer un Quiz</a>
<a href="quiz_list.php?message=histoire" class="btn btn-outline-warning btn-lg px-5 mb-3">Liste des quiz Histoire</a>
<a href="quiz_list.php?message=geographie" class="btn btn-outline-success btn-lg px-5 mb-3">Liste des quiz Geographie</a>
<a href="quiz_list.php?message=all" class="btn btn-outline-danger btn-lg px-5 mb-3  ">Liste de tous les quiz</a>
</div>';
    } else {
        echo'<div class="d-grid gap-2 col-6 mx-auto">
<a href="quiz_list.php?message=histoire" class="btn btn-outline-warning btn-lg px-5">Liste des quiz Histoire</a>
<a href="quiz_list.php?message=geographie" class="btn btn-outline-success btn-lg px-5">Liste des quiz Geographie</a>
<a href="quiz_list.php?message=all" class="btn btn-outline-danger btn-lg px-5">Liste de tous les quiz</a>
</div>';



    }
    
?>
<?php include('includes/footer.php')?>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
