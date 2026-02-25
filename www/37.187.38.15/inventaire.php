<?php session_start(); 
include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <link rel="icon" href="Logo.png" type="image/png">
    <title>Inventaire</title>
    <style>
        .inventaire {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .carte {
            border: 1px solid #ccc;
            padding: 0.5rem;
            width: 150px;
            text-align: center;
            border-radius: 8px;
        }
        .carte img {
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php
    
if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
}

include("header.php");
include("bdd.php");

$statement = $bdd->prepare("
        SELECT CARTE.*, CONTIENT.nb
        FROM CONTIENT
        JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
        WHERE CONTIENT.id_inventaire = :inv
    ");
    $statement->execute([
        'inv'=>$_SESSION['inventaire']
    ]);
    $cartes = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement2 = $bdd->prepare("
    SELECT COFFRE.*, CONTIENT_COFFRE.nb
    FROM CONTIENT_COFFRE
    JOIN COFFRE ON CONTIENT_COFFRE.id_coffre = COFFRE.id_coffre
    WHERE CONTIENT_COFFRE.id_inventaire = :inv
");
$statement2->execute([
    'inv'=>$_SESSION['inventaire']
]);
$cartes2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

if (isset($_GET['coffres'])){

    ?>
    <div style="margin-left: 1%; margin-top:3px">
    <h1>Inventaire de vos coffres</h1>
    <h4><a href='inventaire.php'>Voir vos cartes</a></h4>

    <div class="inventaire">
        <?php 
        if ($cartes2) { 
            foreach ($cartes2 as $carte2) { 
                $rarete = $carte2['rarete'];
                $classesRarete = ['commun', 'rare', 'epique', 'legendaire'];
                $choixRarete = in_array($rarete, $classesRarete) ? $rarete : 'super_rare';
                ?>
                <a href="coffre.php?id=<?= $carte2['id_coffre'] ?>" style="text-decoration: none; color: inherit">
                    <div class="carte <?= $choixRarete ?>">
                        <img src="coffre_image/<?= $carte2['image'] ?>" alt="<?= $carte2['nom_coffre'] ?>">
                        <p><strong><?php echo $carte2['nom_coffre'] ?></strong></p>
                        <p>Rareté: <?php echo $carte2['rarete'] ?></p>
                        <p>Nombre de fois possédée: <?php echo $carte2['nb'] ?></p>
                    </div>
                </a>
            <?php } 
        } else { ?>
            <p>Aucun coffre dans votre inventaire.</p>
        <?php } 

echo "</div>";
        echo "</div>";
        
        include("footer.php");
        exit;
} ?>

<div style='margin-left:1%; margin-top:2px'>

    <h1>Inventaire de vos cartes</h1>
    <h4><a href='inventaire.php?coffres'>Voir vos coffres</a></h4>

    <p><form method='POST' action='inventaire.php'>
        <label>Rechercher une carte dans votre inventaire</label><br>
        <input type='text' name='recherche'>
        <input type='submit' value='Rechercher'>
    </form></p>

    <p><form method='POST' action='inventaire.php'>
        <label>Rechercher des cartes en fonction de leur rareté dans votre inventaire</label><br>
        <label>Commun</label>
        <input type='checkbox' name='rarete[]' value='commun'><br>
        <label>Rare</label>
        <input type='checkbox' name='rarete[]' value='rare'><br>
        <label>Super Rare</label>
        <input type='checkbox' name='rarete[]' value='super rare'><br>
        <label>Epique</label>
        <input type='checkbox' name='rarete[]' value='epique'><br>
        <label>Legendaire</label>
        <input type='checkbox' name='rarete[]' value='legendaire'><br>
        <input type='submit' value='Rechercher'>
    </form></p>

    <?php 
    if (isset($_POST['recherche'])){
        $recherchecarte = $bdd->prepare("
                SELECT CARTE.*, CONTIENT.nb
                FROM CONTIENT
                JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
                WHERE CARTE.nom_carte LIKE :carte AND CONTIENT.id_inventaire = :inv
            ");
        $recherchecarte->execute([
            'carte'=>'%' . $_POST['recherche'] . '%',
            'inv'=>$_SESSION['inventaire'],
        ]);
        $cartes = $recherchecarte->fetchall(PDO::FETCH_ASSOC);
        }

    if (isset($_POST['rarete'])) {

        $rarete = array_map(function($item) {
            return "'" . addslashes($item) . "'";  
        }, $_POST['rarete']);

        $placeholders = implode(', ', $rarete);

        $recherchecarte = $bdd->prepare("
            SELECT CARTE.*, CONTIENT.nb
            FROM CONTIENT
            JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
            WHERE CARTE.rarete IN ($placeholders) AND CONTIENT.id_inventaire = :inv
        ");

        $recherchecarte->execute([
            'inv'=>$_SESSION['inventaire']
        ]);
    
        $cartes = $recherchecarte->fetchAll(PDO::FETCH_ASSOC);
    }

echo "<div class='inventaire'>";

        if ($cartes) { 
            foreach ($cartes as $carte) { 
                $rarete = $carte['rarete'];
                $classesRarete = ['commun', 'rare', 'epique', 'legendaire'];
                $choixRarete = in_array($rarete, $classesRarete) ? $rarete : 'super_rare';
                ?>
                <a href="carte.php?id=<?= $carte['id_carte'] ?>" style="text-decoration: none; color: inherit">
                    <div class="carte <?= $choixRarete ?>">
                        <img src="carte/<?= $carte['image'] ?>" alt="<?php $carte['nom_carte'] ?>">
                        <p><strong><?php echo $carte['nom_carte'] ?></strong></p>
                        <p>Rareté: <?php echo $carte['rarete'] ?></p>
                        <p>Nombre de fois possédée: <?php echo $carte['nb'] ?></p>
                    </div>
                </a>
            <?php } 
        } else { ?>
            <p>Aucune carte trouvée.</p>
        <?php } 

        echo "</div>";
        echo "</div>";
        
        include("footer.php");?>
</body>
</html>