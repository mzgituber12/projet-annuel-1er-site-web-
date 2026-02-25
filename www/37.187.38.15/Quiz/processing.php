<?php
include('includes/check_session.php');
include('../bdd.php');
session_start();
?>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-SgOJa3DmI69IUzQ2PVdRZhwQ+dy64/BUtbMJw1MZ8t5HZApcHrRKUc4W0kG879m7" crossorigin="anonymous">

<?php


if ($_GET['message'] == 'add_quiz'){
    if (!empty($_POST['title']) && !empty($_POST['category']) && isset($_FILES['image'])) {
        $title = $_POST['title'];
        $category = $_POST['category'];


        $image = basename($_FILES['image']['name']);
        $imagePath = 'image_quiz/' . $image;
        if (!move_uploaded_file($_FILES['image']['tmp_name'], $imagePath)) {
            header('location:add_quiz.php?message=Erreur lors de l\'upload');
            exit;
        }


        if (strlen($title) > 50 || strlen($title) < 1){
            header('location:add_quiz.php?message=Le titre du quiz est trop long');
            exit;
        }

            $insertQuiz = "INSERT INTO quiz (title, category, image) VALUES (:title, :category, :image)";
            $statement = $bdd->prepare($insertQuiz);
            $statement->execute([
                'title' => $title,
                'category' => $category,
                'image' => $image,

            ]);

            $select = $bdd->query("SELECT id FROM quiz ORDER BY id DESC LIMIT 1");
            $results= $select->fetch();

            header('Location: update_quiz.php?id=' . $results['id']);
            exit;
        } else {
            header('Location: add_quiz.php?message=Champs manquants');
            exit;
        }
    }


if ($_GET['message'] == 'newtitle') {
    
    $id_quiz = $_GET['id'];
    $newtitle = $_POST['newtitle'];
    
    if (strlen($newtitle) > 50 || strlen($newtitle) < 1) {
        header("Location: update_quiz.php?message=Le titre est trop long ou trop court");
        exit;
    }

    $update = $bdd->prepare("UPDATE quiz SET title = :title WHERE id = :id");
    $update->execute([
        'title' => $newtitle,
        'id' => $id_quiz,
    ]);
    header("Location: update_quiz.php?id=$id_quiz&message=Titre mis à jour avec succès");
    exit;
}


if ($_GET['message'] == 'deletequiz') {
    session_start();

    $id_quiz = $_POST['id'];

    $delete  = $bdd->prepare("DELETE FROM quiz WHERE id = :id");
    $delete->execute([
        'id' => $id_quiz,
    ]);
    header("Location: quiz_list.php?id=$id_quiz&message=Le quiz a été supprimé avec succes");
    exit;
}


if ($_GET['message'] == 'addquestion'){
    session_start();

            $id_quiz = $_GET['id'];
            $content = $_POST['addquestion'];


            $insertquestions = "INSERT INTO questions (content, id_quiz) VALUES (:content, :id_quiz)";
            $stmt = $bdd->prepare($insertquestions);
            $stmt->execute([
                'content' => $content,
                'id_quiz' => $id_quiz
            ]);

            $selectquestion = "SELECT id FROM questions WHERE content = :content AND id_quiz =  :id_quiz";
            $stmt = $bdd->prepare($selectquestion);
            $stmt->execute([
                'content' => $content,
                'id_quiz' => $id_quiz
            ]);
            header('Location: update_quiz.php?message=Question ajoutée avec succès&id='. $id_quiz .'');
            exit;

        }

if ($_GET['message'] == 'newtitleq') {
            session_start();
            
            $id_quiz = $_GET['id'];
            $id_question = $_GET['id_question'];
            $newcontent = $_POST['newcontent'];
            
            if (strlen($newcontent) > 255 || strlen($newcontent) < 1) {
                header("Location: update_question.php?message=Le titre de la question est trop long&id=$id_quiz&id_question=$id_question");
                exit;
            }
        
            $update = $bdd->prepare("UPDATE questions SET content = :newcontent WHERE id = :id_question");
            $update->execute([
                'newcontent' => $newcontent,
                'id_question' => $id_question,
            ]);
            header("Location: update_question.php?id_question=$id_question&id=$id_quiz&message=Titre mis à jour avec succès");
            exit;
        }

if ($_GET['message'] == 'deletequestion') {
            session_start();
            $id_quiz = $_GET['id'];
            $id_question = $_GET['id_question'];
        
            $delete  = $bdd->prepare("DELETE FROM questions WHERE id = :id");
            $delete->execute([
                'id' => $id_question,
            ]);
            header("Location: update_quiz.php?id=$id_quiz&id_question=$id_question&message=La question a été supprimée avec succes");
            exit;
        }



if ($_GET['message'] == 'addreponse'){
    session_start();
            $id_quiz =  $_GET['id'];
            $id_question = $_GET['id_question'];
            $content = $_POST['reponse'];
            $correct = $_POST['correct'];
            
            if ($correct == '1'){
            $verif = $bdd->prepare("SELECT id FROM answers WHERE correct = 1 AND id_question = :question");
            $verif->execute([
                'question'=>$id_question
            ]);
            $verification = $verif->fetchAll();
            if ($verification) {
                header("location:add_answer.php?message=Ce quiz possede deja une réponse correcte&id_question=$id_question&id=$id_quiz");
                exit;
            }
            }
            $insertreponses = "INSERT INTO answers (content, correct, id_question) VALUES (:content, :correct,  :id_question)";
            $stmt = $bdd->prepare($insertreponses);
            $stmt->execute([
                'content' => $content,
                'correct' => $correct,
                'id_question' => $id_question
            ]);
        
            header('Location: update_question.php?message=Reponse ajoutée avec succès&id_question='. $id_question .'&id='. $id_quiz.'');
            exit;
        
        }
        if ($_GET['message'] == 'newreponse') {
            session_start();
            
            $id_quiz = $_GET['id'];
            $id_question = $_GET['id_question'];
            $id_reponse = $_GET['id_reponse'];
            $newcontent = $_POST['newcontent'];
            
            if (strlen($newcontent) > 255 || strlen($newcontent) < 1) {
                header("Location: update_answer.php?message=Le titre est trop long ou trop court&id=$id_quiz&id_question=$id_question&id_reponse=$id_reponse");
                exit;
            }
        
            $update = $bdd->prepare("UPDATE answers SET content = :newcontent WHERE id = :id_reponse");
            $update->execute([
                'newcontent' => $newcontent,
                'id_reponse' => $id_reponse,
            ]);
            header("Location: update_answer.php?id=$id_quiz&id_question=$id_question&id_reponse=$id_reponse&message=Titre mis à jour avec succès");
            exit;
        }
        if ($_GET['message'] == 'newcorrect') {
            session_start();
            
            $id_quiz = $_GET['id'];
            $id_question = $_GET['id_question'];
            $id_reponse = $_GET['id_reponse'];
            $newc = $_POST['newc'];
            
            if ($newc !=1  && $newc !=0) {

                var_dump($newc);
                exit;
                header("Location: update_answer.php?message=La validiter de la reponse doit etre 1 ou 0?id=$id_quiz&id_question=$id_question&id_reponse=$id_reponse");
                exit;
            }
            $verif = $bdd->prepare("SELECT id FROM answers WHERE correct = 1 AND id != :id_reponse");
            $verif->execute([
                'id_reponse' => $id_reponse,
            ]);
            $verification = $verif->fetchAll();
            if ($verification) {
                header("Location: update_answer.php?message=il existe déjà une reponse vrai&id=$id_quiz&id_question=$id_question&id_reponse=$id_reponse");
                exit;
            }
            $update = $bdd->prepare("UPDATE answers SET correct = :correct WHERE id = :id_reponse");
            $update->execute([
                'correct' => $newc,
                'id_reponse' => $id_reponse,
            ]);
            header("Location: update_answer.php?id=$id_quiz&id_question=$id_question&id_reponse=$id_reponse&message=Validité de la réponse mise à jour avec succès");
            exit;
        }

        if ($_GET['message'] == 'deletereponse') {
            session_start();
            $id_quiz = $_GET['id'];
            $id_reponse = $_GET['id_reponse'];
        
            $delete  = $bdd->prepare("DELETE FROM answers WHERE id = :id");
            $delete->execute([
                'id' => $id_reponse,
            ]);
            header("Location: quiz.php?id=$id_quiz&message=La réponse a ete supprimée avec succes");
            exit;
        }

if ($_GET['message'] == 'valid'){
    if (isset($_POST['submit_quiz'])) {
        foreach ($POST as $key => $value) {
            if (strpos($key, 'question') === 0 && empty($value)) {
                header('Location: quiz.php?id=' . $_POST['id_quiz'] . '&error=il_manque_au_moins_une_question');
                exit;
            }
        }
        $id_quiz = $_POST['id_quiz'];



        $reponses = [];
        foreach ($_POST as $index => $value) {
            $reponses[] = intval($value); 
        }

        if (empty($reponses)) {
            header('Location: quiz.php?id=' . $id_quiz . '&error=no_answer');
            exit;
        }

        $placeholders = implode(',', array_fill(0, count($reponses), '?'));
        $verif_score = 'SELECT COUNT(id) as score FROM answers WHERE id IN (' . $placeholders . ') AND correct = 1';
        $statement = $bdd->prepare($verif_score);
        $statement->execute($reponses);
        $result = $statement->fetch();
        $score = $result['score'];

        $verif_score2 = 'SELECT COUNT(id) as score FROM questions WHERE id_quiz = :id';
        $statement = $bdd->prepare($verif_score2);
        $statement->execute([
            'id'=>$id_quiz
        ]);
        $results = $statement->fetch();
        $score2 = $results['score'];

        header('Location: quiz.php?id=' . $id_quiz . '&score=' . $score . '&total=' . $score2);
        exit;
    } else {
        header('Location: index.php?il y a eu une erreur lors du quiz');
        exit;
    }
}


















