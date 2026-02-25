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
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    }
if (isset($_GET['id_question'])) {
    $id_question = $_GET['id_question'];
    }

$quiz = $bdd->prepare("SELECT title FROM quiz WHERE id = :id");
$quiz->execute([
    'id'=>$id
]);
$resultquiz = $quiz->fetch();

$question = $bdd->prepare("SELECT content FROM questions WHERE id = :id AND id_quiz = :quiz");
$question->execute([
    'id'=>$id_question,
    'quiz'=>$id
]);
$resultquestion = $question->fetch();

$question = $bdd->prepare("SELECT id, content FROM answers WHERE id = :id");
$question->execute([
    'id'=>$_GET['id_reponse']
]);
$resultresponse = $question->fetch();

$id_reponse = $resultresponse['id']
    
    ?>

<body>

<h1> Modifier le quiz : <?= $resultquiz['title'] ?> </h1>
<h2> Modifier les reponses a la question : <?= $resultquestion['content'] ?> </h2>
<h3> Modifier la reponse : <?= $resultresponse['content'];?> </h3>

<?php if (isset($_GET['message'])){
    echo "<h4>" . htmlspecialchars($_GET['message']) . "</h4>";
} ?>

<br>

<form method="post" action="processing.php?message=deletereponse&id_question=<?= $id_question?>&id_reponse=<?= $id_reponse?>&id=<?= $id ?>" onsubmit='return confirm("Etes vous sur de vouloir supprimer la reponse ?")'>
        <input type="hidden" name="id" value="<?= $id_reponse ?>">
        <input type="submit" value="Supprimer la réponse" class="btn btn-danger">
    </form>
    
<form method="post" action="processing.php?message=newreponse&id_question=<?= $id_question ?>&id_reponse=<?= $id_reponse ?>&id=<?= $id ?>">
        <div>
            <label>Entrez le nouveau nom de la réponse</label>
            <input type='text' name='newcontent' placeholder='Réponse' required>
            <input type='submit' value='Modifier' class="btn btn-success">
        </div>
    </form>
    <form method="post" action="processing.php?message=newcorrect&id_question=<?= $id_question ?>&id_reponse=<?= $id_reponse ?>&id=<?= $id ?>">
        <div>
            <label> Modifier la validité de la reponse (0 si faux 1 si correct) <label>
            <input type='text' name='newc' required>
            <input type='submit' value='Modifier' class="btn btn-success">
        </div>
    </form>

<p><a href='quiz.php?look&id=<?= $_GET['id'] ?>' class="btn btn-outline-info">Retour a la liste des questions/réponses de ce quiz</a></p>
<p><a href="quiz_list.php" class="text-decoration-none"><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px"> Liste des quiz</a></p>
</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>