<?php
session_start();
include_once("../parametres/theme.php");
include('includes/check_session.php');
include('includes/header.php');
include('../bdd.php');?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
<body>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">

<?php 
if (isset($_GET['id'])){
$id = $_GET['id'];
} 
$nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
$nom->execute([
    'id'=>$_GET['id']
]);
$nomresult = $nom->fetch();

?>

<h1 class='text-center text-uppercase shadow-sm p-3 mb-5 bg-body-tertiary rounded'> Modifier le quiz : <?= $nomresult['title'] ?> </h1>
<?php if (isset($_GET['message'])){
    echo "<h2>" . htmlspecialchars($_GET['message']) . "</h2>";
} ?>
<div class="container mt-5 ms-3">
<form method="post" action="processing.php?message=deletequiz" onsubmit='return confirm("Etes vous sur de vouloir supprimer le quiz ?")'>
    <div>
        <input type="hidden" name="id" value="<?= $_GET['id'] ?>">
        <input type="submit" value="Supprimer le quiz" class="btn btn-danger">
    </div>
</form>
<form method="post" action="processing.php?message=newtitle&id=<?= $_GET['id'] ?>">
  <div class="mb-3">
    <label for="exampleInputEmail1" class="form-label fs-2">Entrez le nouveau titre :</label>
    <input type="text" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" name='newtitle' placeholder='. . . . . . . . . . . . . . .' required>
    <br>
    <input type='submit' value='Modifier' class="btn btn-success">
</div>
</form>
<div>
<p><a href='add_question.php?id=<?= $_GET['id'] ?>' class='link-opacity-50-hover text-decoration-none'>Ajouter une question a ce quiz</a></p>
<p><a href='quiz.php?look&id=<?= $_GET['id'] ?>' class='link-opacity-50-hover text-decoration-none'>Liste des questions/réponses de ce quiz</a></p>
<p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>
