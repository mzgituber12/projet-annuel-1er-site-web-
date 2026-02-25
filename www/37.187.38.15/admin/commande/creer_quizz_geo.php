<?php
session_start();
include('../pasadmin.php');

if ($_SESSION['role'] !='admin'){
    header('location: ../../index.php?message=Vous n\'etes pas un admin');
}

if (!isset($_SESSION['commande']) ||
empty($_SESSION['commande'])
){
header('location: ../indexad.php?message=Commande erroné ou commande vide.');
exit;
}

if (!isset($_POST['titre']) ||
empty($_POST['titre'])
){
    header('location: commande_carte.php.php?message=champ du titre vide');
}
$titre = $_POST['titre'];

if (!isset($_FILES['imageq']) ||
empty($_FILES['imageq'])
){
    $titre = $_FILES['imageq'];
}

if(isset($_FILES['imageq']) && $_FILES['imageq']['error'] == 0){
    $acceptable =[
        'image/jpeg',
        'image/png',
        'image/gif'
    ];
    if(!in_array($_FILES['imageq']['type'], $acceptable)){
        header('location: commande_carte.php?message=L\'image doit etre un png ou un jpeg ou un gif');
        exit;
        
    }
$maxSize = 500 * 1024;
if($_FILES['imageq']['size']> $maxSize){
    header('location: commande_carte.php?message=L\'image ne doit pas dépasser 500 Ko.');
    exit;
}

$filename = $_FILES['imageq']['name']; 
$deplacement = '../../image_quizz/'; 
$telechargement = $deplacement . basename($filename);

if (!move_uploaded_file($_FILES['imageq']['tmp_name'], $telechargement)) {
    header('location: commande_carte.php?message=Erreur lors de du téléchargement du fichier.');
    exit;
}
}


$categorie ='geo';

include("../../bdd.php");

$query = 'INSERT INTO QUIZZ (titre, categorie, image) VALUES (:titre, :categorie, :imageq)';
$statement = $bdd->prepare($query); 
$statement -> execute([
    'titre'=> $titre,
    'categorie'=> $categorie,
    'imageq'=> $filename
]);
header('location: ../indexad.php?message=Commande réussite');
exit;
?>