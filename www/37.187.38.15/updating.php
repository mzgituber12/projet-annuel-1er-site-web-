<?php session_start();
include('bdd.php');

$update = $bdd->prepare("UPDATE message SET id2vu = 1 WHERE id_utilisateur_envoyeur = ? AND id_utilisateur_destinataire = ? AND id2vu = 0");
$update->execute([
    $_GET['id'],
    $_SESSION['id']
]);

?>