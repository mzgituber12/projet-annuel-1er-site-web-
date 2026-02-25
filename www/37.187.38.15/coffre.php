<?php
    session_start();
    
    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }
    
    include("bdd.php");
    $statement = $bdd->prepare("SELECT * FROM COFFRE WHERE id_coffre = :id");
    $statement->execute([
        'id'=>$_GET['id']
    ]);
    $resultscof = $statement->fetch();


    $query2 = 'SELECT CONTIENT_COFFRE.nb 
                FROM CONTIENT_COFFRE
                JOIN COFFRE ON CONTIENT_COFFRE.id_coffre = COFFRE.id_coffre
                WHERE COFFRE.id_coffre = :id AND CONTIENT_COFFRE.id_inventaire = :idinv';
    $statement3 = $bdd->prepare($query2);
    $statement3->execute([
        'id'=>$_GET['id'],
        'idinv'=>$_SESSION['inventaire']
    ]);
    $liens2 = $statement3->fetch(PDO::FETCH_ASSOC);

    if (!$liens2['nb']){
    $nombre = '0';
    } else {
    $nombre = $liens2['nb'];
    }

include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $resultscof['nom_coffre'] ?></title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
</head>
<body>
    <?php     
    include('header.php');
    echo "<div style='margin-left:1.1%; margin-top:6px'>";
    echo "<h1>" . htmlspecialchars($resultscof['nom_coffre']) . "</h1>";
    echo "<p>Nom : " . htmlspecialchars($resultscof['nom_coffre']) . "</p>";
    echo "<p>Rareté : " . htmlspecialchars($resultscof['rarete']) . "</p>";
    echo "<p>Description : " . htmlspecialchars($resultscof['description']) . "</p>";
    echo "<p>Id : " . htmlspecialchars($resultscof['id_coffre']) . "</p>";
    echo "<img src='coffre_image/" . htmlspecialchars($resultscof['image']) . "' alt='" . htmlspecialchars($resultscof['nom_coffre']) . "' width='150' height='150'>";
    echo "<p> Vous en avez " . $nombre . "</p>";

    if ($nombre > 0){
        echo "<p><a href='coffre_ouvrir.php?coffre=" . $resultscof['nom_coffre'] ."'>Ouvrir</a></p>";
    }
    echo "</div>";
    ?>
</body>
</html>