<?php
session_start();
 
include("header_combat.php");
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');

$statement= $bdd->prepare('SELECT pseudo FROM UTILISATEUR WHERE id_utilisateur = ?');
$statement->execute([$_SESSION['id']]);
$resultat = $statement->fetch(PDO::FETCH_ASSOC);
$_SESSION['pseudo'] = $resultat['pseudo'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Battlepast</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <main>

    <?php include('../../getmessage.php') ?>
<a href="rejoindre_combat.php" class="btn btn-primary btn-dynamic">creer une partie</a>
<a href="trouver_combat.php" class="btn btn-primary btn-dynamic">rechercher ou rejoindre une partie</a>
<a href="../jeu-strat-index.php" class="btn btn-primary btn-dynamic">index du jeu</a>
<?php   

?>
</main>
</body>
</html>