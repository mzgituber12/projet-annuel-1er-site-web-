<?php 
session_start();
if (!isset($_SESSION['id'])) {
    header('location:index.php');
    exit;
}

$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
$id_utilisateur = $_SESSION['id'];


$statement = $bdd->prepare("SELECT id_equipe, nom FROM EQUIPE WHERE id_utilisateur = ?");
$statement->execute([$id_utilisateur]);
$equipes = $statement->fetchAll(PDO::FETCH_ASSOC);


if (!$equipes) {
    $bdd->prepare("INSERT INTO EQUIPE (id_utilisateur, nom) VALUES (?, 'Équipe principale')")->execute([$id_utilisateur]);
    $statement = $bdd->prepare("SELECT id_equipe, nom FROM EQUIPE WHERE id_utilisateur = ?");
    $statement->execute([$id_utilisateur]);
    $equipes = $statement->fetchAll(PDO::FETCH_ASSOC);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['id_equipe'])) {
    $_SESSION['id_equipe'] = $_POST['id_equipe'];
    header("Location: ".$_SERVER['PHP_SELF']); 
    exit();
}

$id_equipe = isset($_SESSION['id_equipe']) ? $_SESSION['id_equipe'] : $equipes[0]['id_equipe'];
$cartes_Equipe = [];
if ($id_equipe) {
    $statement = $bdd->prepare("SELECT c.id_carte, c.nom_carte, c.image FROM CARTEEQUIPE ce JOIN CARTE c ON ce.id_carte = c.id_carte WHERE ce.id_equipe = ? ORDER BY ce.place");
    $statement->execute([$id_equipe]);
    $cartes_Equipe = $statement->fetchAll(PDO::FETCH_ASSOC);
    
}

$statement = $bdd->prepare("SELECT id_inventaire FROM INVENTAIRE WHERE id_utilisateur = ?");
$statement->execute([$id_utilisateur]);
$id_inventaire = $statement->fetchColumn() ?: null;

$cartes = [];
$cartes_Terrain = [];

if ($id_inventaire) {
    $statement = $bdd->prepare("SELECT c.id_carte, c.nom_carte, c.image, c.rarete, c.statut, ct.nb FROM CONTIENT ct JOIN CARTE c ON ct.id_carte = c.id_carte WHERE ct.id_inventaire = ?");
    $statement->execute([$id_inventaire]);
    while ($carte = $statement->fetch(PDO::FETCH_ASSOC)) {
    if ($carte['statut'] == 'heros') {
        $cartes[] = $carte;
    } elseif ($carte['statut'] == 'terrain') {
        $cartes_Terrain[] = $carte;
    }
}
}
if ($id_equipe && $id_inventaire) {
    $statement = $bdd->prepare("
        SELECT c.id_carte, c.nom_carte, c.image, c.rarete, c.statut, ct.nb
        FROM CARTEEQUIPE ce
        JOIN CONTIENT ct ON ce.id_carte = ct.id_carte
        JOIN CARTE c ON ct.id_carte = c.id_carte
        WHERE ce.id_equipe = ? AND ct.id_inventaire = ? AND c.statut = 'terrain'
    ");
    $statement->execute([$id_equipe, $id_inventaire]);
    $carteter = $statement->fetch(PDO::FETCH_ASSOC);
} else {
    $carteter = [];
}

?>





<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../styles/stylecarte.css">
    <link rel="icon" href="../Logo.png" type="image/png">
    <title>Sélection des cartes</title>
    <style>
        .inventaire { display: flex; flex-wrap: wrap; gap: 1rem; }
        .carte { border: 1px solid #ccc; padding: 0.5rem; width: 150px; text-align: center; border-radius: 8px; cursor: pointer; }
        .case { width: 150px; height: 200px; border: 2px dashed lightgray; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .grille-case { display: flex; justify-content: center; gap: 10px; margin: 20px 0; }
        .carte.terrain { background-color: #d4efdf; }
        .ter { margin: 0 0 0 5rem; }
    </style>
</head>
<body>
    <?php include("headerjeu.php"); ?>
    <h1>Gérez vos équipes</h1>
    <?php include('../getmessage.php') ?>
    <a href='jeu-strat-index.php'>Retour au menu de jeu</a>


    <div style="display: flex; gap: 20px;">
<form method="POST" action="">
    <label for="id_equipe">Sélectionner une équipe :</label>
    <select name="id_equipe" id="select_equipe" onchange="this.form.submit()">
        <?php foreach ($equipes as $equipe) { ?>
            <option value="<?= $equipe['id_equipe'] ?>" <?= (isset($id_equipe) && $equipe['id_equipe'] == $id_equipe) ? 'selected' : '' ?>>
                <?= htmlspecialchars($equipe['nom']) ?>
            </option>
        <?php } ?>
    </select>
</form>

<form method="POST" action="supprimer_equipe.php" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette équipe ?');">
    <input type="hidden" name="id_equipe" value="<?= htmlspecialchars($id_equipe) ?>">
    <button type="submit" id="delete_button">Supprimer l'équipe sélectionnée</button>
</form>
    </div>
    
    <form method="POST" action="creer_equipe.php">
        <label for="nom_equipe">Nom de l'équipe :</label>
        <input type="text" name="nom_equipe" id="nom_equipe" required>
        <button type="submit">Créer une nouvelle équipe</button>
    </form>


    <div class="grille-case">
    <?php 
 
$idsCartesTerrain = array_column($cartes_Terrain, 'id_carte');

$cartesNormales = [];
foreach ($cartes_Equipe as $carte) {
    if (!in_array($carte['id_carte'], $idsCartesTerrain)) {
        $cartesNormales[] = $carte; 
    }
}


    for ($i = 0; $i < 5; $i++) {
        if (isset($cartesNormales[$i])) {
            $carte = $cartesNormales[$i];
            echo '<div class="case">';
            echo '<img src="../carte/' . htmlspecialchars($carte['image']) . '" alt="' . htmlspecialchars($carte['nom_carte']) . '" class="carte">';
            echo '</div>';
        } else {
            echo '<div class="case">Vide</div>';
        }
    }


    echo '<div class="case ter">';
    if (!empty($carteter)) {
        echo '<img src="../carte/' . htmlspecialchars($carteter['image']) . '" alt="' . htmlspecialchars($carteter['nom_carte']) . '" class="carte terrain">';
    } else {
        echo 'Vide';
    }
    echo '</div>';
    ?>
</div>



<script>
    let cartes_selectionnees = [];
    let cartes_Terrain = <?= json_encode(array_column($cartes_Terrain, 'id_carte')) ?>;

   function cliquer(element, id_Carte) {
    const estTerrain = estCarteTerrain(id_Carte);
    const dejaSelectionnee = cartes_selectionnees.includes(id_Carte);

    if (dejaSelectionnee) {
        element.style.border = "";
        cartes_selectionnees = cartes_selectionnees.filter(id => id !== id_Carte);
        return;
    }

    if (estTerrain) {
        cartes_selectionnees = cartes_selectionnees.filter(id => !estCarteTerrain(id));
        deselectionnerCartes('.carte.terrain');
        cartes_selectionnees.push(id_Carte);
        element.style.border = "3px solid green";
        return;
    }

    let cartesHeros = cartes_selectionnees.filter(id => !estCarteTerrain(id));
    if (cartesHeros.length >= 5) {
        alert("Vous ne pouvez sélectionner que 5 cartes héros et 1 carte terrain.");
        return;
    }

    cartes_selectionnees.push(id_Carte);
    element.style.border = "3px solid green";
}

    function deselectionnerCartes(selector) {
        document.querySelectorAll(selector).forEach(elem => {
            elem.style.border = "";
        });
    }

    function validerEquipe() {
    if (cartes_selectionnees.length === 0) {
        return alert("Vous devez sélectionnez 5 cartes héros et 1 carte terrain.");
    }

    let cartesHeros = cartes_selectionnees.filter(id => !estCarteTerrain(id));
    let cartesTerrain = cartes_selectionnees.filter(id => estCarteTerrain(id));

    if (cartesHeros.length !== 5) {
        return alert("Vous devez sélectionnez 5 cartes héros et 1 carte terrain.");
    }

    if (cartesTerrain.length !== 1) {
        return alert("Vous devez sélectionnez 5 cartes héros et 1 carte terrain.");
    }

    let idEquipe = document.getElementById("select_equipe").value;
    fetch('verif_selection.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
            id_equipe: idEquipe,
            cartes: cartes_selectionnees.map(id => ({
                id,
                type: estCarteTerrain(id) ? 'terrain' : 'normal'
            }))
        })
    })
    .then(response => response.json())
    .then(data => {
        alert(data.message);
        if (data.success) location.reload();
    })
    .catch(() => alert("Erreur lors de la communication avec le serveur."));
}

    function estCarteTerrain(idCarte) {
        return cartes_Terrain.includes(idCarte);
    }
</script>


<h2>Vos cartes</h2>
<div class="inventaire">
    <?php if ($cartes) { 
        foreach ($cartes as $carte) { 
            $rarete = $carte['rarete'];
            $classes_rarete = ['commun', 'rare', 'super_rare', 'epique', 'legendaire'];
            $choix_rarete = in_array($rarete, $classes_rarete) ? $rarete : 'commun';
            ?>
            <div class="carte <?= $choix_rarete ?>" onclick="cliquer(this, <?= $carte['id_carte'] ?>)">
                <img src="../carte/<?= $carte['image'] ?>" alt="<?= $carte['nom_carte'] ?>">
                <p><strong><?= $carte['nom_carte'] ?></strong></p>
                <p>Rareté: <?= $carte['rarete'] ?></p>
                <p>Nombre de fois possédée: <?= $carte['nb'] ?></p>
            </div>
        <?php } 
    } else { ?>
        <p>Aucune carte débloquée.</p>
    <?php } ?>
</div>

<h2>Cartes Terrain</h2>
<div class="inventaire">
    <?php if ($cartes_Terrain) { 
        foreach ($cartes_Terrain as $carte) { ?>
            <div class="carte terrain" onclick="cliquer(this, <?= $carte['id_carte'] ?>)">
                <img src="../carte/<?= $carte['image'] ?>" alt="<?= $carte['nom_carte'] ?>">
                <p><strong><?= $carte['nom_carte'] ?></strong></p>
                <p>Nombre de fois possédée: <?= $carte['nb'] ?></p>
            </div>
        <?php } 
    } else { ?>
        <p>Aucune carte terrain débloquée.</p>
    <?php } ?>
</div>
<br>

<button type="button" onclick="validerEquipe()">Valider les cartes sélectionnées</button>

</form>

</body>
</html>
