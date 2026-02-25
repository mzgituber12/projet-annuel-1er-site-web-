<?php session_start();

if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }
include("header_parametres.php");
include("../bdd.php");

$verif = $bdd->prepare("SELECT messagenotif, actunotif, echangenotif, aminotif FROM utilisateur WHERE id_utilisateur = ?");
$verif->execute([
    $_SESSION['id']
]);
$result = $verif->fetch();

include_once("theme.php");?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>"> 
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifications</title>
        <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
</head>
<body>

<?php

echo '<div style="margin-left:10px">';
echo "<h1 style='margin-bottom:17px'> Notifications </h1>";
echo "<h3> Activer les notifications lorsque vous recevez un message : </h3>";
echo "<label>Activé</label>
<input onclick='message(\"actif\")' type='radio' name='message' value='1'" . 
     ($result['messagenotif'] == 1 ? " checked" : "") . 
     "><br>";
echo "<label>Desactivé</label>
<input onclick='message(\"inactif\")' type='radio' name='message' value='0'" . 
     ($result['messagenotif'] == 0 ? " checked" : "") . 
     "><br>";

echo "<h3> Activer les notifications lorsque vous recevez un échange : </h3>";
echo "<label>Activé</label>
<input onclick='echange(\"actif\")' type='radio' name='echange' value='1'" . 
     ($result['echangenotif'] == 1 ? " checked" : "") . 
     "><br>";
echo "<label>Desactivé</label>
<input onclick='echange(\"inactif\")' type='radio' name='echange' value='0'" . 
     ($result['echangenotif'] == 0 ? " checked" : "") . 
     "><br>";

echo "<h3> Activer les notifications par mail de chaque actualité mise en ligne sur le site : </h3>";
echo "<label>Activé</label>
<input onclick='mail(\"actif\")' type='radio' name='actu' value='1'" . 
     ($result['actunotif'] == 1 ? " checked" : "") . 
     "><br>";
echo "<label>Desactivé</label>
<input onclick='mail(\"inactif\")' type='radio' name='actu' value='0'" . 
     ($result['actunotif'] == 0 ? " checked" : "") . 
     "><br>";

echo "<h3> Activer les notifications lorsque vous recevez une demande d'ami </h3>";
echo "<label>Activé</label>
<input onclick='ami(\"actif\")' type='radio' name='ami' value='1'" . 
     ($result['aminotif'] == 1 ? " checked" : "") . 
     "><br>";
echo "<label>Desactivé</label>
<input onclick='ami(\"inactif\")' type='radio' name='ami' value='0'" . 
     ($result['aminotif'] == 0 ? " checked" : "") . 
     "><br>";

echo "</div>";

?>

<script>
    async function message(a){
        const result = await fetch("notiffetch.php?message&value=" + a)
    }

    async function echange(a){
        const result = await fetch("notiffetch.php?echange&value=" + a)
    }

    async function mail(a){
        const result = await fetch("notiffetch.php?mail&value=" + a)
    }

    async function ami(a){
        const result = await fetch("notiffetch.php?ami&value=" + a)
    }

</script>
</body>
</html>