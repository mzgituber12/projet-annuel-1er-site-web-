<?php session_start();
include ("bdd.php");

if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
}


$nombre = $bdd->prepare("SELECT count(id_vendeur) AS vente
FROM vente_carte
WHERE id_vendeur = :id");

$nombre->execute([
    'id'=>$_SESSION['id']
]);

$resultnombre = $nombre->fetch();
if ($resultnombre['vente'] >= 3){
    header('location:boutique.php?message=Vous avez deja mis 3 cartes de votre inventaire en vente');
    exit;
}

include("header.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique communautaire</title>
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
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

<h1>Mettre en vente une carte dans la boutique communautaire</h1>

<?php include ('getmessage.php') ?>

<h2>Vos cartes</h2>

<?php 

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

?>

<p><form method='POST' action='boutique_communautaire.php'>
        <label>Rechercher une carte dans votre inventaire</label><br>
        <input type='text' name='recherche'>
        <input type='submit' value='Rechercher'>
    </form></p>

    <p><form method='POST' action='boutique_communautaire.php'>
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

    ?>

    <script>
        function carte(x, el){
            const select = document.getElementById('carte')
            select.value = x
            const allCartes = document.getElementsByClassName('carte');
        for (let i = 0; i < allCartes.length; i++) {
            allCartes[i].style.outline = 'none';
        }

        el.style.outline = '2px solid black';
        }
    </script>

    <?php

        if ($cartes) { 
            echo "<h3>Selectionner une carte</h3>";
            echo "<div class='inventaire'>";
            foreach ($cartes as $carte) { 
                $rarete = $carte['rarete'];
                $classesRarete = ['commun', 'rare', 'epique', 'legendaire'];
                $choixRarete = in_array($rarete, $classesRarete) ? $rarete : 'super_rare';
                ?>
                    <div onclick="carte(<?= $carte['id_carte'] ?>, this)" class="carte <?= $choixRarete ?>">
                        <img src="carte/<?= $carte['image'] ?>" alt="<?php $carte['nom_carte'] ?>">
                        <p><strong><?php echo $carte['nom_carte'] ?></strong></p>
                        <p>Rareté: <?php echo $carte['rarete'] ?></p>
                        <p>Nombre de fois possédée: <?php echo $carte['nb'] ?></p>
                    </div>
            <?php } 
            echo '<div style="display:none"><p><form method="post" action="boutique_communautaire_verif.php">
                <label> Carte a vendre : </label>
                <input type="text" id="carte" name="carte" readonly>
                </div>
            </div><br>
                <label>Entrez le prix de la carte à vendre</label>
                <input type="text" name="price">
                <input type="submit" value="Envoyer"></p>
            </form>';
        } else { ?>
            <p>Aucune carte trouvée.</p>
        <?php } 

        
        include("footer.php");?>
    
</body>
</html>