<?php
session_start();
include_once("../parametres/theme.php");
include('includes/check_session.php');
include('../bdd.php');
include('includes/header.php');
?>
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
    <title>Ajouter une question</title>
</head>
<body>



<?php

$nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
$nom->execute([
    'id'=>$_GET['id']
]);
$nomresult = $nom->fetch();

?>

<h1 class='text-center text-uppercase shadow-sm p-3 mb-5 bg-body-tertiary rounded'> Ajouter une question au quiz : <?= $nomresult['title'] ?> </h1>
<div class="container mt-5 ms-3">
<form method="post" action="processing.php?message=addquestion&id=<?= $_GET['id'] ?>">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label fs-2"></label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name='addquestion' placeholder='. . . . . . . . . . . . . . .' required>
    <br>
    <input type='submit' value='Ajouter' class="btn btn-success">
</div>
</form>
<div>
    <p><a href="update_quiz.php?id=<?= $_GET['id'] ?>" class='link-opacity-50-hover text-decoration-none'><img src="https://cdn-icons-png.flaticon.com/512/60/60710.png" width="25" height="25">Retour</a></p>
    <p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>
    </div>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
