<?php session_start();

if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
}

    include("bdd.php");
    $statement = $bdd->prepare("SELECT * FROM CARTE WHERE id_carte = :id");
    $statement->execute([
        'id'=>$_GET['id']
    ]);
    $resultscard = $statement->fetch();

    $query = 'SELECT CARTE.id_carte, ATTAQUE_CARTE.id_attaque, ATTACHER.type_attaque, ATTAQUE_CARTE.nom, ATTAQUE_CARTE.degats, ATTAQUE_CARTE.portee
                FROM CARTE
                JOIN ATTACHER ON CARTE.id_carte = ATTACHER.id_carte
                JOIN ATTAQUE_CARTE ON ATTACHER.id_attaque = ATTAQUE_CARTE.id_attaque
                WHERE CARTE.id_carte = :id';
    $statement2 = $bdd->prepare($query);
    $statement2->execute([
        'id'=>$_GET['id']
    ]);
    $liens = $statement2->fetchall(PDO::FETCH_ASSOC); 

    $query2 = 'SELECT CONTIENT.nb 
                FROM CONTIENT
                JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
                WHERE CARTE.id_carte = :id AND CONTIENT.id_inventaire = :idinv';
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

    $stats = 'SELECT *
                FROM STATS_CARTE
                JOIN CARTE ON STATS_CARTE.id_carte = CARTE.id_carte
                WHERE CARTE.id_carte = :id';
    $stats_tement = $bdd->prepare($stats);
    $stats_tement->execute([
        'id'=>$_GET['id']
    ]);
    $resulstats = $stats_tement->fetch(PDO::FETCH_ASSOC);

include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $resultscard['nom_carte'] ?></title>
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
    echo "<h1>" . htmlspecialchars($resultscard['nom_carte']) . "</h1>";
    echo "<p>Nom : " . htmlspecialchars($resultscard['nom_carte']) . "</p>";
    echo "<p>Rareté : " . htmlspecialchars($resultscard['rarete']) . "</p>";
    echo "<p>Talent : " . htmlspecialchars($resultscard['talent']) . "</p>";
    echo "<p>Pays : " . htmlspecialchars($resultscard['pays']) . "</p>";
    echo "<p>Description : " . htmlspecialchars($resultscard['description']) . "</p>";
    echo "<p>Id : " . htmlspecialchars($resultscard['id_carte']) . "</p>";
    echo "<img src='carte/" . htmlspecialchars($resultscard['image']) . "' alt='" . htmlspecialchars($resultscard['nom_carte']) . "' style='width:360px ; height: auto'>";

    foreach($liens as $attak){
        echo '</p>' . $attak['type_attaque'] . ' : ' . $attak['nom'] . ' -> ' . $attak['degats'] . ' degats -> ' . $attak['portee'] . ' portée</p>';
        }

    echo "<p> Vous en avez " . $nombre . "</p>";
    echo "<h2>Statistiques</h2>";
    echo "<p>Points de Vie : " . htmlspecialchars($resulstats['pv']) . "</p>";
    echo "<p>Attaque : " . htmlspecialchars($resulstats['atk']) . "</p>";
    echo "<p>Defense : " . htmlspecialchars($resulstats['def']) . "</p>";
    echo "<p>Vitesse : " . htmlspecialchars($resulstats['vit']) . "</p>";
    echo "<p>Esquive : " . htmlspecialchars($resulstats['esq']) . "</p>";
    echo "<p>Precision : " . htmlspecialchars($resulstats['prs']) . "</p>";
    echo "</div>"
    ?>
</body>
</html>