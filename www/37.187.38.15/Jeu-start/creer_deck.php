<?php
session_start();
ini_set('display_errors', 1);

if (!isset($_SESSION['id'])) {
    header('location:index.php');
    exit;
}

$id_utilisateur = $_SESSION['id'];
$message = "";

try {
    include('../bdd.php');

    $statement = $bdd->prepare("SELECT id_equipe, nom FROM EQUIPE WHERE id_utilisateur = ?");
    $statement->execute([$id_utilisateur]);
    $equipes = $statement->fetchAll(PDO::FETCH_ASSOC);

    $id_equipe = $_SESSION['equipe'] ?? $equipes[0]['id_equipe'] ?? null;

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id_equipe'])) {

    $id_equipe_post = $_POST['id_equipe'];

    $statement = $bdd->prepare("SELECT COUNT(*) FROM EQUIPE WHERE id_equipe = ? AND id_utilisateur = ?");
    $statement->execute([$id_equipe_post, $id_utilisateur]);
    $count = $statement->fetchColumn();

    if ($count == 0) {
        die("Erreur : Cette équipe ne vous appartient pas.");
    }

    $statement = $bdd->prepare("SELECT COUNT(*) FROM CARTEEQUIPE WHERE id_equipe = ?");
    $statement->execute([$id_equipe_post]);
    $nombre_cartes = $statement->fetchColumn();

    if ($nombre_cartes != 6) {
        $message = 'Le deck doit contenir exactement 6 cartes';
    } else {
        $_SESSION['equipe'] = $id_equipe_post;
        $id_equipe = $id_equipe_post;

        $statement = $bdd->prepare("SELECT id_deck FROM DECK WHERE id_utilisateur = ? LIMIT 1");
        $statement->execute([$id_utilisateur]);
        $deck = $statement->fetch(PDO::FETCH_ASSOC);

        if (!$deck) {
            $statement = $bdd->prepare("INSERT INTO DECK (id_utilisateur, id_equipe) VALUES (?, ?)");
            $statement->execute([$id_utilisateur, $id_equipe]);
            $id_deck = $bdd->lastInsertId();
        } else {
            $id_deck = $deck['id_deck'];
        }


        $statement = $bdd->prepare("DELETE FROM CARTEDECK WHERE id_deck = ?");
        $statement->execute([$id_deck]);

        $statement = $bdd->prepare("INSERT INTO CARTEDECK (id_deck, id_carte) SELECT ?, id_carte FROM CARTEEQUIPE WHERE id_equipe = ?");
        $statement->execute([$id_deck, $id_equipe]);

        $message = "Deck actualisé avec succès !";
    }
}
} catch (Exception $e) {
    echo "Erreur lors de la création du deck : " . $e->getMessage();
}

?>
<!DOCTYPE html>
<html>
<head>
    <link rel="icon" href="../Logo.png" type="image/png">
    <title>Créer un Deck</title>
</head>
<body>
    <style>
        .case { width: 150px; height: 200px; border: 2px dashed lightgray; display: flex; align-items: center; justify-content: center; font-size: 14px; }
        .carte { border: 1px solid #ccc; padding: 0.5rem; width: 150px; text-align: center; border-radius: 8px; cursor: pointer; }
        .grille-case { display: flex; justify-content: center; gap: 10px; margin: 20px 0; }
    </style>
    <?php include("headerjeu.php"); 
    
    if (!isset($cartes_deck)) {
    $statement = $bdd->prepare("SELECT id_deck FROM DECK WHERE id_utilisateur = ? LIMIT 1");
    $statement->execute([$id_utilisateur]);
    $deck = $statement->fetch(PDO::FETCH_ASSOC);

    if ($deck) {
        $id_deck = $deck['id_deck'];
        $statement = $bdd->prepare("
    SELECT C.nom_carte, C.image, CE.place
    FROM CARTE C 
    INNER JOIN CARTEDECK CD ON C.id_carte = CD.id_carte 
    INNER JOIN CARTEEQUIPE CE ON C.id_carte = CE.id_carte AND CE.id_equipe = ?
    WHERE CD.id_deck = ?
    ORDER BY CE.place
");
$statement->execute([$id_equipe, $id_deck]);
$cartes_deck = $statement->fetchAll(PDO::FETCH_ASSOC);
    }
}

?>

    <h2>Charger son nouveau deck</h2>
    <?php if (isset($message)){
        echo $message;
    }?>
    <a href='jeu-strat-index.php'>Retour au menu de jeu</a>
    <form method="post">

        <br>
        <label for="id_equipe">Sélectionner une équipe :</label>
        <select id="id_equipe" name="id_equipe" required>
            <?php foreach ($equipes as $equipe) { ?>
            <option value="<?= $equipe['id_equipe'] ?>" <?= ($equipe['id_equipe'] == $id_equipe) ? 'selected' : '' ?>>
                <?= htmlspecialchars($equipe['nom']) ?>
                </option>
            <?php } ?>
        </select>
        <br>
        <button type="submit">Actualiser le deck</button>
    </form>

    
    <div class="grille-case">
    <?php

    for ($i = 0; $i < 5; $i++) {
        echo '<div class="case">';
        if (isset($cartes_deck[$i])) {
            echo '<img src="../carte/' . htmlspecialchars($cartes_deck[$i]['image']) . '" 
                  alt="' . htmlspecialchars($cartes_deck[$i]['nom_carte']) . '" class="carte">';
        } else {
            echo 'Vide';
        }
        echo '</div>';
    }

    echo '<div class="case ter" style="margin-left: 90px;">';
        if (isset($cartes_deck[5])) {
        echo '<img src="../carte/' . htmlspecialchars($cartes_deck[5]['image']) . '" 
          alt="' . htmlspecialchars($cartes_deck[5]['nom_carte']) . '" class="carte terrain">';
    } else {
        echo 'Vide';
    }
    echo '</div>';
   
    ?>
    
</div>
</body>
</html>
