<?php

function connexionLog($pseudo, $email){
    $stream = fopen('../log/connexion_log.txt', 'a+');
    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' | Adresse email : ' . $email . ', s\'est connecté' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

setcookie('pseudo', urlencode($_POST['pseudo']), time() + (24 * 60 * 60), "/");

if (!isset($_POST['pseudo']) ||
    !isset($_POST['mdp']) ||
    empty($_POST['pseudo']) ||
    empty($_POST['mdp']
    )
    ){
    header('location: ../connexion.php?message=Au moins un des deux champs est vide.');
    exit;
}

include("../bdd.php");

$q ='SELECT id_utilisateur, email, role, statut, abonne FROM UTILISATEUR WHERE pseudo = :pseudo AND mot_de_passe = :mdp';
$statement= $bdd->prepare($q);
$statement->execute([
'pseudo'=>$_POST['pseudo'],
'mdp' =>hash('sha512',$_POST['mdp'])
     
]);
$results = $statement->fetch(PDO::FETCH_ASSOC);

if (!$results) {
    header('location: ../connexion.php?message=Le nom d\'utilisateur ou le mot de passe saisi est incorrect.');
    exit;
}

session_start();
$_SESSION['id'] = $results['id_utilisateur'];
$_SESSION['email'] = $results['email'];
$_SESSION['role'] = $results['role'];
$_SESSION['mdp'] = hash('sha512', $_POST['mdp']);
$_SESSION['pseudo'] = $_POST['pseudo'];
$_SESSION['statut'] = $results['statut'];
$_SESSION['abonne'] = $results['abonne'];

$inventaire = $bdd->prepare("SELECT id_inventaire FROM inventaire WHERE id_utilisateur = :id");
$inventaire->execute([
    'id'=>$_SESSION['id']
]);
$resultsinv=$inventaire->fetch();

$_SESSION['inventaire'] = $resultsinv['id_inventaire'];
$_SESSION['heure_connexion'] = time();

$insert = $bdd->prepare('UPDATE utilisateur SET derniere_connexion = current_timestamp(), mailenvoyé = 0 WHERE id_utilisateur = :id');
$insert->execute([
    'id'=>$_SESSION['id']
]);

connexionLog($_SESSION['pseudo'], $_SESSION['email']);
header('location: ../index.php');


?>