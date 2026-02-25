<?php 
session_start();
include('../pasadmin.php');

include('../../bdd.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (!isset($_POST['nomattaque']) ||
empty($_POST['nomattaque']) ||
!isset($_POST['degats']) ||
empty($_POST['degats']) ||
!isset($_POST['portee']) ||
empty($_POST['portee'])
){
    header('location: commande_attaque.php?message=Vous devez au moins inserer l\'attaque, les dégats et la portée');
    exit;
}

if (!ctype_digit($_POST['degats'])){
    header('location: commande_attaque.php?message=Les valeurs rentrées sont invalides');
    exit;
}

$array = ['', 'bruler','geler','paralyser','empoisonner','apeurer','guerire'];

if (!empty($_POST['effet1']) || !empty($_POST['effet2']) || !empty($_POST['effet3'])){
    if (!in_array($_POST['effet1'], $array) || !in_array($_POST['effet2'], $array) || !in_array($_POST['effet3'], $array)){
        header('location: commande_attaque.php?message=Les effets rentrées sont invalides');
    exit;
    }
}

$nomattaque = $_POST['nomattaque'];
$degats = $_POST['degats'];
$portee = $_POST['portee'];
$effet1 = $_POST['effet1'];
$effet2 = $_POST['effet2'];
$effet3 = $_POST['effet3'];

$query = 'INSERT INTO ATTAQUE_CARTE (nom,degats,portee,effet,effet2,effet3) VALUES (:nom,:degats,:portee,:effet1,:effet2,:effet3)';
$statement = $bdd->prepare($query); 
$statement -> execute([
    'nom'=> $nomattaque,
    'degats'=> $degats,
    'portee'=> $portee,
    'effet1'=> $effet1,
    'effet2'=> $effet2,
    'effet3'=> $effet3 
]);
header('location: ../indexad.php?message=Commande réussite');
exit;