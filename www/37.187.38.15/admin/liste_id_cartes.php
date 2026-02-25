<?php session_start();
include('pasadmin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Liste des cartes</title>
</head>
<body>

<?php include("headerad.php");
include("../bdd.php")
?>

<h1>Liste des cartes</h1>

<form method='get'action=liste_id_cartes.php>
    <input type='text' name='carte' placeholder='Rechercher une carte'>
    <input type='submit' value='Envoyer'>
</form>

<?php 
$ban = $bdd->prepare("SELECT id_carte, nom_carte FROM CARTE WHERE nom_carte LIKE :search");
$ban->execute([
    'search'=>'%' . $_GET['carte'] . '%'
]);
$results = $ban->fetchall();

if ($results){
    foreach($results as $result){
        echo "<a href='../carte.php?id=" . ($result['id_carte']) . "'>" . htmlspecialchars($result["id_carte"]) . " -> " . htmlspecialchars($result["nom_carte"]) . "</a><br>";
    }
} else {
    echo "Aucune carte trouvée";
}

?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
