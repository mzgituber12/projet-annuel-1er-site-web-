<?php session_start();
include('pasadmin.php');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Liste des banissements</title>
</head>
<body>

<?php include("headerad.php");
include("../bdd.php")
?>

<h1> Liste des personnes bannies </h1>

<?php 
$ban = $bdd->query("SELECT pseudo FROM UTILISATEUR WHERE ban = 1");
$results = $ban->fetchall();

if ($results){
    foreach($results as $result){
        echo "<a href='../profil.php?user=" . ($result['pseudo']) . "'>" . htmlspecialchars($result["pseudo"]) . "</a><br>";
    }
} else {
    echo "<h3> Vous n'avez banni personne </h3>";
}

?>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
