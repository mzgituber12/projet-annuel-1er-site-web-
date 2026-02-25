<?php
session_start();
include_once("../parametres/theme.php");
include('includes/check_session.php');
include('../bdd.php');
include('includes/header.php');
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">
<link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">

<?php

$nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
$nom->execute([
        'id'=>$_GET['id']
    ]);
$nomresult = $nom->fetch();

$question = $bdd->prepare("SELECT content FROM questions WHERE id = :id AND id_quiz = :quiz");
$question->execute([
    'id'=>$_GET['id_question'],
    'quiz'=>$_GET['id']
]);
$resultquestion = $question->fetch();

?>

<h1> Modifier le quiz : <?= $nomresult['title'] ?> </h1>
<h2> Ajouter des reponses a la question : <?= $resultquestion['content'] ?> </h2>

<?php if (isset($_GET['message'])){
    echo "<h3>" . htmlspecialchars($_GET['message']) . "</h3>";
} ?>

<br>

<div>
    <form method='post' action="processing.php?message=addreponse&id_question=<?= $_GET['id_question'] ?>&id=<?= $_GET['id'] ?>">
    <p>
    <label class="fs-5">Entrez le contenu de la réponse :</label>
        <input type="text" name="reponse" placeholder='Reponse' required>
        <input type="submit" value="Modifier" class="btn btn-success">
    </p><p>
        <label class="fs-5">Entrer 1 si la réponse est correcte ou 0 sinon </label>
        <input type="text" name="correct" required>
        <input type="submit" value="Ajouter" class="btn btn-success">
</p>
</form>
</div>
<div>
<p><a href='update_question.php?id_question=<?= $_GET['id_question'] ?>&id=<?= $_GET['id'] ?>' class="btn btn-outline-warning">Retour a la question</a></p>

<p><a href='quiz.php?look&id=<?= $_GET['id'] ?>' class="btn btn-outline-info">Retour a la liste des questions/réponses de ce quiz</a></p>
<p><a href="quiz_list.php" class="text-decoration-none"><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px"> Liste des quiz</a></p>

</div>
<div>
<p><a href="update_quiz.php?id=<?= $_GET['id'] ?>" class="text-decoration-none"><img src="https://cdn-icons-png.flaticon.com/512/60/60710.png" width="25" height="25"> Retour</a></p>
<br>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>