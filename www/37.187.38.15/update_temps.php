<?php session_start();

include('bdd.php');

$tempsPasse = time() - $_SESSION['heure_connexion'];

$stmt = $bdd->prepare('UPDATE utilisateur SET temps_passé_site = temps_passé_site + ?, temps_passé_semaine = temps_passé_semaine + ?, temps_passé_total = temps_passé_total + ? WHERE id_utilisateur = ?');
$stmt->execute([$tempsPasse, $tempsPasse, $tempsPasse, $_SESSION['id']]);
$_SESSION['heure_connexion'] = time();

?>