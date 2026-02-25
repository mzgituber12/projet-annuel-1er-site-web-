<?php
session_start();
include_once("../parametres/theme.php");
include('includes/check_session.php');
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
    <title>Ajouter un Quiz</title>
</head>
<?php



echo "<h1>Creer un quiz</h1>";

if (isset($_GET['message'])){
    echo "<h2>" . htmlspecialchars($_GET['message']) . "</h2>";
}
?>
<body>

<div class="container mt-5 ms-4">
    <form method="post" action='processing.php?message=add_quiz' enctype="multipart/form-data" class="w-100 mx-auto">
      <select class="form-select form-select-sm" name="category" aria-label="Small select example">
        <option value="" selected disabled>Selectionner la Categorie</option>
        <option value="histoire">histoire</option>
        <option value="geographie">geographie</option>
      </select>
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label">Choisissez le titre de votre Quiz : </label>
    <input type="text" class="form-control" id="exampleInputEmail1" name='title' required>
  </div>
  <div class="image">
    <p class="image">Choisissez un image pour le quiz :</p>
    <input type="file" id="imagequiz" name="image" accept ="image/png">
  </div>
  <button type="submit" class="btn btn-success" value='Envoyer'>Confirmer</button>
</form>
<br>
<p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>