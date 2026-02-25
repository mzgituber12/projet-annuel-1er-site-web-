<?php 
session_start();
include_once("../parametres/theme.php");
include('../bdd.php');
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
    <title>Liste des questions du quiz</title>
</head>
<?php include('includes/header.php');
echo '<body>';

include('../bdd.php');

$nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
$nom->execute([
    'id'=>$_GET['id']
]);
$nomresult = $nom->fetch();



if (isset($_GET['score'])){
    echo "<h1>Quiz : " . htmlspecialchars($nomresult['title']) . "</h1>";
    echo "<h2>Vous avez eu " . $_GET['score'] . " bonnes réponses sur " . $_GET['total'] . " questions</h2>";
    ?>
    <p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>
    <?php
    exit;
}



if (isset($_GET['look'])){
    
if($_SESSION['role'] != 'admin' && $nomresult['id_user'] != $_SESSION['id']){
        header('location:index.php');
        exit;
}

echo "<h1> Liste des questions et reponses du quiz : " . $nomresult['title'] . "</h1>";

if (isset($_GET['message'])){
    echo "<h2>" . htmlspecialchars($_GET['message']) . "</h2>";
}

$id_q = "SELECT * FROM questions WHERE id_quiz = :id_quiz";
            $statement = $bdd->prepare($id_q);
            $statement->execute([
                'id_quiz' => $_GET['id'],
            ]);
            $result=$statement->fetchAll();

$id_r = "SELECT answers.content, answers.id_question, answers.id, answers.correct FROM answers JOIN questions on answers.id_question = questions.id WHERE questions.id_quiz = :quiz";
            $statement = $bdd->prepare($id_r);
            $statement->execute([
                'quiz' => $_GET['id'],
            ]);
            $responses=$statement->fetchAll();

            $compteur = 0;

            echo "<p>";
foreach ($result as $result2){
    $compteur += 1;
                echo 'Question ' . $compteur . ' : <a href="update_question.php?id_question=' . $result2['id'] . '&id=' . $_GET['id'] . ' ">' . $result2['content'] . '</a><br>';

                foreach ($responses as $response){
                    if ($response['id_question'] == $result2['id']){
                        echo '<a href="update_answer.php?id_reponse=' . $response['id'] . '&id_question=' . $response['id_question'] . '&id=' . $_GET['id'] . ' ">' . $response['content'] . '</a>';
                        if ($response['correct'] == 1){
                            echo " (Bonne réponse)";
                        }
                        echo '<br>';
                    }
                }
                echo "<br>";
}
?>
<div>
<p><a href="update_quiz.php?id=<?= $_GET['id'] ?>" class='link-opacity-50-hover text-decoration-none'><img src="https://cdn-icons-png.flaticon.com/512/60/60710.png" width="25" height="25">Retour</a></p>
<p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>
</div>
<?php
}

if (isset($_GET['play'])){

    if (!isset($_GET['id'])){
        header('location:index.php');
        exit;
    }
    
        $nom = $bdd->prepare('SELECT title FROM quiz WHERE id = :id');
        $nom->execute(['id' => $_GET['id']]);
        $nomresult = $nom->fetch();
    
        echo "<h1>Quiz : " . htmlspecialchars($nomresult['title']) . "</h1>";
    
        if (isset($_GET['score']) && isset($_GET['total'])) {
            echo '<div class="alert alert-success">';
            echo 'Votre score : ' . intval($_GET['score']) . ' / ' . intval($_GET['total']);
        }
    
        $questions_statement = $bdd->prepare("SELECT * FROM questions WHERE id_quiz = :id_quiz");
        $questions_statement->execute(['id_quiz' => $_GET['id']]);
        $questions = $questions_statement->fetchAll();
    
        $reponse_statement = $bdd->prepare("SELECT id, content, correct, id_question FROM answers WHERE id_question IN (
            SELECT id FROM questions WHERE id_quiz = :id_quiz
        )");
        $reponse_statement->execute(['id_quiz' => $_GET['id']]);
        $reponses = $reponse_statement->fetchAll();
    
        $reponse_par_question = [];
        foreach ($reponses as $reponse) {
            $reponse_par_question[$reponse['id_question']][] = $reponse;
        }
    
        echo '<form action="processing.php?message=valid" method="POST">';
        echo '<input type="hidden" name="id_quiz" value="' . intval($_GET['id']) . '">';
    
        foreach ($questions as $question) {
            echo '<div class="mb-4">';
            echo '<div class="question fw-bold">' . htmlspecialchars($question['content']) . '</div>';
    
            if (isset($reponse_par_question[$question['id']])) {
                foreach ($reponse_par_question[$question['id']] as $reponse) {
                    echo '<div class="form-check">';
                    echo '<input class="form-check-input" required type="radio" name="question_' . $question['id'] . '" value="' . $reponse['id'] . '" id="answer_' . $reponse['id'] . '">';
                    echo '<label class="form-check-label" for="answer_' . $reponse['id'] . '">' . htmlspecialchars($reponse['content']) . '</label>';
                    echo '</div>';
                }
            }
    
            echo '</div>';
        }
    
        echo '<button type="submit" name="submit_quiz" class="btn btn-primary">Valider le quiz</button>';
        echo '</form>';

        ?>

<p><a href="quiz_list.php" class='link-opacity-50-hover text-decoration-none'><img src="https://www.svgrepo.com/show/337399/list-add.svg" height="25px" width="25px" >Liste des quiz</a></p>

        <?php

}

?>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>