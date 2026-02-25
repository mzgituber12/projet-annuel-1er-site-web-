<?php 
session_start();
include_once("../parametres/theme.php");?>
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
    <title>Liste des quiz</title>
</head>
<body>
    <?php
    include('includes/header.php');
      
    echo "<h2>Vous pouvez modifier vos quiz</h2>";
    echo "<h3>Les admins peuvent modifier tous les quiz</h3>";
    if (isset($_GET['message'])){
        echo "<h4>" . htmlspecialchars($_GET['message']) . "</h4>";
    }
    include('../bdd.php');
    $category = $_GET['message'] ?? 'all';
    if ($category == 'histoire' || $category == 'geographie') {
        $query = "SELECT * FROM quiz WHERE category = :category";
        $statement = $bdd->prepare($query);
        $statement->execute([
            'category' => $category
        ]);
    } else {
        $statement = $bdd->query("SELECT * FROM quiz");
    }
    $result=$statement->fetchAll();
            echo '<table class="table table-striped table-bordered">';
            echo '<thead class="table-dark">';
            if ($_SESSION['role'] == 'admin'){
            echo '<tr>
        <th>Image des Quiz</th>
        <th>Titre des Quiz</th>
        <th>Jouer aux Quiz</th>
        <th>Info sur le Quiz</th>
        <th>Modifier le Quiz</th>
      </tr>';
echo '</thead>';
echo '<tbody>';
} else {
    echo '<tr>
        <th>Image des Quiz</th>
        <th>Titre des Quiz</th>
        <th>Jouer aux Quiz</th>
      </tr>';
echo '</thead>';
echo '<tbody>';

}

foreach ($result as $result2) {
    if ($_SESSION['role'] == 'admin'){

    echo '<tr>';
    echo '<td><img src="image_quiz/' . htmlspecialchars($result2['image'] ?? '') . '" width="100" alt="Image du quiz"></td>';
    echo '<td>' . $result2['id'] . ' -> ' . $result2['title'] . '</a><br>';
    echo '<td><a href="quiz.php?play&id=' . $result2['id'] . '" class="btn btn-sm btn-primary">Jouer</a></td>';
    echo '<td><a href="quiz.php?look&id=' . $result2['id'] . '" class="btn btn-sm btn-primary">Apercu</a></td>';
    echo '<td><a href="update_quiz.php?id=' . $result2['id'] . '" class="btn btn-sm btn-primary">Modifier</a></td>';
    echo '</tr>';
} else {
    echo '<tr>';
    echo '<td><img src="image_quiz/' . htmlspecialchars($result2['image'] ?? '') . '" width="100" alt="Image du quiz"></td>';
    echo '<td>' . $result2['id'] . ' -> ' . $result2['title'] . '<br>';
    echo '<td><a href="quiz.php?play&id=' . $result2['id'] . '" class="btn btn-sm btn-primary">Jouer</a></td>';
    echo '</tr>';
}
}
echo '</tbody>';
echo '</table>';
    ?>
</body>
</html>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/js/bootstrap.bundle.min.js" integrity="sha384-k6d4wzSIapyDyv1kpU366/PK5hCdSbCRGRCMv+eplOQJWyd1fbcAu9OCUj5zNLiq" crossorigin="anonymous"></script>