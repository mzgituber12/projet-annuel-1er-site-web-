<?php 
session_start();
include('../pasadmin.php');

if (!isset($_SESSION['commande']) ||
empty($_SESSION['commande'])
){
header('location: ../indexad.php?message=Commande erronée.');
exit;
}

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (!isset($comm[2]) 
){
header('location: ../indexad.php?message=Commande erronée.');
exit;
}


if ($comm[2] == 'supprimer'){
    header('location: commande_carte_supprimer.php');
    exit;
}

if ($comm[2] == 'modifier'){
    header('location: commande_carte_modifier.php');
    exit;
}

if ($comm[2] == 'creer'){
    header('location: commande_carte_creer.php');
    exit;
}

header('location: ../indexad.php?message=Commande erronée');
exit;
?>

</body>
</html>