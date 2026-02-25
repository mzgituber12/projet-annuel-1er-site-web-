<?php 
session_start();
include_once("../parametres/theme.php");
include('includes/header.php');
include('../bdd.php');
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

$nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
$nom->execute([
        'id'=>$_GET['id']
    ]);
$nomresult = $nom->fetch();
    
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
    
    ?>

<body>

<h1> Modifier le quiz : <?= $resultquiz['title'] ?> </h1>
<h2> Modifier la question : <?= $resultquestion['content'] ?> </h2>
<?php if (isset($_GET['message'])){
    echo "<h3>" . htmlspecialchars($_GET['message']) . "</h3>";
}

echo "<br>"

?>
<form method="post" action="processing.php?message=deletequestion&id=<?= $_GET['id'] ?>&id_question=<?= $id_question?>" onsubmit='return confirm("Etes vous sur de vouloir supprimer la question ?")'>
    <div>
        <input type="hidden" name="id_question" value="<?= $id_question ?>">
        <input type="submit" value="Supprimer la question" class="btn btn-danger">
    </div>
</form>
<div class="d-flex align-items-center gap-3">
    <form method='post' action="processing.php?message=newtitleq&id=<?= $_GET['id'] ?>&id_question=<?= $_GET['id_question'] ?>">
    <div>
        <label>Entrez le nouveau nom de la question :</label>
        <input type="text" name="newcontent" placeholder='Question' required>
        <input type="submit" value="Modifier" class="btn btn-success">
    </div>
</form>
</div>
<div>
<p><a href='add_answer.php?id_question=<?= $id_question ?>&id=<?=$id?>' class="btn btn-outline-warning">Ajouter une réponse a cette question</a></p>

<p><a href='quiz.php?look&id=<?= $_GET['id'] ?>' class="btn btn-outline-info">Retour a la liste des questions/réponses de ce quiz</a></p>
<p><a href="quiz_list.php" class="text-decoration-none"><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px"> Liste des quiz</a></p>

</div>
<div>
<a href="update_quiz.php?id=<?= $_GET['id'] ?>" class='text-decoration-none'><img src="https://cdn-icons-png.flaticon.com/512/60/60710.png" width="25" height="25"> Retour</a>
<br>
</div>

</body>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>