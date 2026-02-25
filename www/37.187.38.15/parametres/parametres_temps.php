<?php session_start(); 
include_once("theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Gestion du temps</title>
</head>
<body>
    <?php

    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }
    
    include("header_parametres.php") ?>

    <h1> Gestion du temps </h1>

    <?php include('../getmessage.php');
    include('../bdd.php');

    if (!isset($_SESSION['pseudo'])){
        header('location: index.php');
    }

    $dateactuelle = new DateTime();
    $dateinscription = $bdd->prepare("SELECT date_inscription, temps_passé_site, temps_passé_semaine, temps_passé_total FROM utilisateur WHERE id_utilisateur = :id");
    $dateinscription->execute(['id'=>$_SESSION['id']]);
    $inscription = $dateinscription->fetch();

    $date_inscription = new DateTime($inscription['date_inscription']);

    $total = $inscription['temps_passé_site']; 
    $totalsemaine = $inscription['temps_passé_semaine']/7; 
    $totaltjr = $inscription['temps_passé_total'];


$compteur = 0;
if ($total >= 3600) {
    $compteur = floor($total / 3600);
    $total -= $compteur * 3600; 
}

$compteur2 = 0;
if ($total >= 60) {
    $compteur2 = floor($total / 60);
    $total -= $compteur2 * 60;
}

$compteur3 = $total;



$compteur4 = 0;
if ($totalsemaine >= 3600) {
    $compteur4 = floor($totalsemaine / 3600);
    $totalsemaine -= $compteur4 * 3600; 
}

$compteur5 = 0;
if ($totalsemaine >= 60) {
    $compteur5 = floor($totalsemaine / 60);
    $totalsemaine -= $compteur5 * 60;
}

$compteur6 = (int)$totalsemaine;



$compteur7 = 0;
if ($totaltjr >= 3600) {
    $compteur7 = floor($totaltjr / 3600);
    $totaltjr -= $compteur7 * 3600; 
}

$compteur8 = 0;
if ($totaltjr >= 60) {
    $compteur8 = floor($totaltjr / 60);
    $totaltjr -= $compteur8 * 60;
}

$compteur9 = $totaltjr;


$interval = $date_inscription->diff($dateactuelle);

echo "<p>Compte créé le " . $date_inscription->format('d/m/Y') . "</p>";
echo "<p>Age du compte (en jours) : " . $interval->days . " jours </p>";

echo "<p>Temps passé sur le site aujourd'hui : ";

if ($compteur > 0) {
    echo $compteur . ' heures ';
}
if ($compteur2 > 0) {
    echo $compteur2 . ' minutes ';
}
if ($compteur3 >= 0) {
    echo $compteur3 . ' secondes ';
}

echo "</p><p>Temps moyen passé chaque jour sur le site cette semaine : ";

if ($compteur4 > 0) {
    echo $compteur4 . ' heures ';
}
if ($compteur5 > 0) {
    echo $compteur5 . ' minutes ';
}
if ($compteur6 >= 0) {
    echo $compteur6 . ' secondes ';
}

echo "</p><p>Temps passé sur le site depuis la création du compte : ";

if ($compteur7 > 0) {
    echo $compteur7 . ' heures ';
}
if ($compteur8 > 0) {
    echo $compteur8 . ' minutes ';
}
if ($compteur9 >= 0) {
    echo $compteur9 . ' secondes ';
}

echo "</p>";
include("footer_parametres.php") ?>