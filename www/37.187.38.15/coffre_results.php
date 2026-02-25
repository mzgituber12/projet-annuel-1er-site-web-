<?php session_start();

if (!isset($_SESSION['pseudo']) || $_SESSION['verificate'] != 'yes'){
    header('location:index.php');
    exit;
}

$_SESSION['verificate'] = "";

include('bdd.php');
include('header.php');

$verif=$bdd->prepare("SELECT image FROM carte WHERE nom_carte = :nom");
$verif->execute([
    'nom'=>$_SESSION['carteobtenue']
]);
$results=$verif->fetch();

$coffre = $bdd->prepare("SELECT nb FROM contient_coffre WHERE id_inventaire = :id AND id_coffre = :coffre");
$coffre->execute([
    'id'=>$_SESSION['inventaire'],
    'coffre'=>$_SESSION['idcoffre']
]);
$resultscoffre = $coffre->fetch();

include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ouverture de Coffre</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
</head>
<body>
    
</body>
</html>

<?php

echo "<div style='margin-left:1%; margin-top:3px'>";
echo "<h1>Coffre ouvert</h1>";

echo $_SESSION['coffre'];
echo $_SESSION['coffre2'];
echo '<img src="carte/' . $results['image'] . '">';

if ($resultscoffre){
echo "<p>Il vous en reste " . $resultscoffre['nb'] . "</p>";
echo "<p><a href='coffre_ouvrir.php?coffre=" . $_SESSION['opencoffre'] . "'>Ouvrir 1 de plus</a></p>";
}
echo "<p><a href='../inventaire.php'>Retour à l'inventaire</a><p>";
echo"</div>";

exit;

?>