<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[4]) || !isset($comm[3])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

include('../../bdd.php');

$statement = $bdd->prepare('SELECT nom_carte FROM CARTE WHERE id_carte = :id');
$statement->execute([
    'id'=>$comm[3]
]);
$results=$statement->fetch();
if (empty($results)){
    header('location: ../indexad.php?message=La carte avec cet id n\'existe pas');
    exit;
}

$suppimage = $bdd->prepare("SELECT image FROM CARTE WHERE id_carte = :id");
$suppimage->execute([
    'id'=>$comm[3]
]);
$resultssupp = $suppimage->fetch();

if ($resultssupp){
    unlink('../../carte/' . $resultssupp['image']);
}

$supprimer="DELETE FROM CARTE WHERE id_carte = :id";
$statement = $bdd->prepare($supprimer);
$statement->execute([
    'id'=>$comm[3]
]);

header('location: ../indexad.php?message=Carte supprimée avec succes');
exit;

