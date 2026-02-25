<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');


if (!isset($_SESSION['id'])) {
    header("Location: ../../index.php?message=Vous n'êtes pas connecté");
    exit;
}

$id_utilisateur = $_SESSION['id'];

$statement = $bdd->prepare("SELECT cd.id_deck 
FROM CARTEDECK cd 
INNER JOIN DECK d ON cd.id_deck = d.id_deck 
WHERE d.id_utilisateur = ?");
$statement->execute([$id_utilisateur]); 
$deck = $statement->fetchAll(PDO::FETCH_ASSOC); 

if (!$deck || count($deck) !== 6) {
    header("Location: index_combat.php?message=Deck incomplet");
    exit;
}

$expiration = date('Y-m-d H:i:s', time());

$statement = $bdd->prepare("DELETE FROM COMBAT WHERE date_expiration > ? AND statut = 'en attente'");
$statement->execute([$expiration]);
$statement = $bdd->prepare("SELECT id_combat, id_joueur1 FROM combat WHERE statut = 'en attente' AND code IS NULL");
$statement->execute();

$combats_publics = $statement->fetchAll(PDO::FETCH_ASSOC);


if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['rejoindre_combat'])) {
    $code_acces = $_POST['code_acces'] ?? '';

    if (!empty($code_acces)) {
        $statement = $bdd->prepare("SELECT id_combat FROM combat WHERE statut = 'en attente' AND code = ? LIMIT 1");
        $statement->execute([$code_acces]);
    } elseif (isset($_POST['combat_public_id'])) {
        $statement = $bdd->prepare("SELECT id_combat FROM combat WHERE statut = 'en attente' AND id_combat = ? AND code IS NULL LIMIT 1");
        $statement->execute([$_POST['combat_public_id']]);
    } else {
        $statement = $bdd->prepare("SELECT id_combat FROM combat WHERE statut = 'en attente' AND code IS NULL LIMIT 1");
        $statement->execute();
    }

    $combat = $statement->fetch(PDO::FETCH_ASSOC);

    if ($combat) {
        $id_combat = $combat['id_combat'];

        $statement = $bdd->prepare("UPDATE combat SET id_joueur2 = ?, statut = 'en cours', tour_actuel = 1 WHERE id_combat = ?");
        $statement->execute([$id_utilisateur, $id_combat]);
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET nb_partie = nb_partie + 1 WHERE id_utilisateur = ?");
        $statement->execute([$_SESSION['id']]);
        header("Location: combat.php?id_combat=" . $id_combat);
        exit;
    } else {
        echo "<p style='color:red;'>Aucun combat disponible.</p>";
    }
}
include("header_combat.php");
?>
<script>
  function apparition() {
    const apparaitre = document.getElementById('input_code');
    apparaitre.style.display = apparaitre.style.display === 'none' ? 'block' : 'none';
  }
</script>
<a href='index_combat.php'>Retour au menu des combats</a>
<br>
<h2>Rejoindre un combat privé</h2>
<button onclick="apparition()">Entrer un code d'accès</button>

<form method="post" id="input_code" style="display: none; margin-top: 10px;">
  <input type="text" name="code_acces" placeholder="Entrer un code d'accès">
  <button type="submit" name="rejoindre_combat">Rejoindre</button>
</form>

<h2>Combats publics en attente</h2>
<?php if (!empty($combats_publics)) { ?>
    <ul>
        <?php foreach ($combats_publics as $combat) { ?>
            <li>
                Combat ID: <?= htmlspecialchars($combat['id_combat']); ?> 
                <form method="post" style="display:inline;">
                    <input type="hidden" name="combat_public_id" value="<?= htmlspecialchars($combat['id_combat']); ?>">
                    <button type="submit" name="rejoindre_combat">Rejoindre</button>
                </form>
            </li>
        <?php } ?>
    </ul>
<?php } else { ?>
    <p>Aucun combat public disponible.</p>
<?php } ?>
