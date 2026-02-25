<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
$id_utilisateur = $_SESSION['id'];
$statement = $bdd->prepare("SELECT id_deck FROM DECK WHERE id_utilisateur = ?");
$statement->execute([$id_utilisateur]);
$id_deck = $statement->fetchColumn();

$statement = $bdd->prepare
("SELECT cd.id_carte 
    FROM CARTEDECK cd
    INNER JOIN DECK d ON cd.id_deck = d.id_deck
    WHERE cd.id_deck = ?
");
    $statement->execute([$id_deck]);
    $deck = $statement->fetchAll(PDO::FETCH_ASSOC);
    if (count($deck) !== 6) {
        header("Location: index_combat.php?message=Deck incomplet");
} 

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $expiration = date('Y-m-d H:i:s', time() + (5 * 60)); 
    if (isset($_POST['combat_public'])) {

        $statement = $bdd->prepare("SELECT id_combat FROM combat WHERE statut = 'en attente' AND code IS NULL LIMIT 1");
        $statement->execute();
        $combat = $statement->fetch(PDO::FETCH_ASSOC);
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET nb_partie = nb_partie + 1 WHERE id_utilisateur = ?");
        $statement->execute([$_SESSION['id']]);
        if ($combat) {
            $id_combat = $combat['id_combat'];
        } else {
            
            $statement = $bdd->prepare("INSERT INTO combat (id_joueur1, statut, date_expiration, degats) VALUES (?, 'en attente', ?, 0)");
            $statement->execute([$id_utilisateur, $expiration]);
            $id_combat = $bdd->lastInsertId();
        }
        header("Location: combat.php?id_combat=" . $id_combat);
        exit;
    }

    if (isset($_POST['combat_prive'])) {
        $code = rand(100000, 999999); 
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET nb_partie = nb_partie + 1 WHERE id_utilisateur = ?");
        $statement->execute([$_SESSION['id']]);
        $statement = $bdd->prepare("INSERT INTO combat (id_joueur1, statut, code, date_expiration) VALUES (?, 'en attente', ?, ?)");
        $statement->execute([$id_utilisateur, $code, $expiration]);
        echo "Partagez ce code avec votre adversaire : <strong>" . $code . "</strong>";
    }
}

include("header_combat.php");
?>

<a href='index_combat.php'>Retour au menu des combats</a>
<br>
<form method="post">
    <button type="submit" name="combat_public">Créer un combat public</button>
    <button type="submit" name="combat_prive">Créer un combat privé</button>
</form>
