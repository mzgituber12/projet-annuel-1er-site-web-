<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[4])){
    header('location:../indexad.php?message=Commande%20erronée');
    exit;
}

if ($comm[2] == 'modifier'){
        header('location:commande_attaque_modifier.php');
        exit;
    }

if ($comm[2] == 'creer'){
    if(isset($comm[3])){
        header('location:../indexad.php?message=Commande%20erronée');
        exit;
    }

    include('headerad_com.php');

    include('../../getmessage.php')
    
?>

<h1>Creer une attaque</h1>

<form method="post" action="commande_attaque_creer.php">     
    <p><label>Ecrire le nom de l'attaque</label>
    <input type ="text" name="nomattaque"></p>
    <p><label>Ecrire les dégats de celle-ci</label>
    <input type ="text" name="degats"></p>
    <p><label>Ecrire les cases à sa portée(ex : A1,B1,A3,B3)</label>
    <input type ="text" name="portee"></p>

    <h3> Les effets autorisés : bruler, geler, paralyser, empoisonner, apeurer, guerire </h3>
    <h3> Facultatif, 3 effets au maximum </h3>
    <p><label>Ecrire l'effet de l'attaque</label>
    <input type ="text" name="effet1"></p>
    <p><label>Ecrire le deuxieme effet de l'attaque</label>
    <input type ="text" name="effet2"></p>
    <p><label>Ecrire le troisieme effet de l'attaque</label>
    <input type ="text" name="effet3"></p>
    <input type="submit">
</form>
<?php
exit;
}

if ($comm[2] == 'supprimer'){
    if(!isset($comm[3]) || isset($comm[4])){
        header('location:../indexad.php?message=Commande%20erronée');
        exit;
    }

    include('../../bdd.php');

    $id = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
    $id->execute([
        'id'=>$comm[3]
    ]);
    $results = $id->fetch();

    if (!$results){
        header('location:../indexad.php?message=Attaque introuvable');
        exit;
    }

    $supp = $bdd->prepare("DELETE FROM ATTAQUE_CARTE WHERE id_attaque = :id");
    $supp->execute([
        'id'=>$comm[3]
    ]);

    header('location:../indexad.php?message=Commande effectuée avec succes');
    exit;
}

header('location:../indexad.php?message=Commande%20erronée');
exit;
?>