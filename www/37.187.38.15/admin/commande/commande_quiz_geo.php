<?php 
session_start();
include('../pasadmin.php');

if (!isset($_SESSION['commande']) ||
empty($_SESSION['commande'])
){
header('location: ../indexad.php?message=Commande erroné ou commande vide.');
exit;
}

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);
if (!isset($comm[2]) 
){
header('location: ../indexad.php?message=Commande incomplete.');
exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Préparation quizz</title>
</head>
<body>
<?php

if ($comm[2] == 'quizz')
{
?>
<form method="post" action="creer_quizz_geo.php" enctype ="multipart/form-data">
    <label>Ajouter le nom du quizz</label>
    <input type ="text" name="titre"> 
    <label>inserer l'image du quizz</label>
    <input type ="file" name="imageq" accept ="image/png, image/jpeg, image/gif">
    <input type="submit">
</form>
<?php
exit;
}
header('location: ../indexad.php?message=Commande incomplete.');
exit;
?>

</body>
</html>