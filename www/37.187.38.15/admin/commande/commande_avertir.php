<?php 
session_start();
include('../pasadmin.php');
include('../../bdd.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (!isset($comm[2]) || isset($comm[3])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

$user=$bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo");
$user->execute(['pseudo'=>$comm[2]]);
$verif = $user->fetch();

if (!$verif){
    header('location: ../indexad.php?message=Cet utilisateur n\'existe pas');
    exit;
}

if (isset($_GET['good']) && isset($_POST['content'])){

    if (empty($_POST['content'])){
        header('location: commande_avertir.php?message=Vous n\'avez pas donné de raison d\'avertissement');
        exit;
    }

     $statement = $bdd->prepare("INSERT INTO account_statut (id_utilisateur, contenu) VALUES (?, ?)");
     $statement->execute([$verif['id_utilisateur'], $_POST['content']]);

     header('location: ../indexad.php?message=Commande effectuée avec succes');
     exit;
    }

include('headerad_com.php')

?> <h1>Avertir un utilisateur</h1>

<?php include('../../getmessage.php') ?>

<form method="post" action="commande_avertir.php?good" enctype ="multipart/form-data">     
    <p><label>Inserer la raison pour laquelle l'utilisateur sera alerté</label>
    <input type ="text" name="content"></p>
    <input type ="submit" value="Envoyer"></p>
</form>