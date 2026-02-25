<?php
session_start();
include('../pasadmin.php');
include("../../bdd.php");

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[5]) || !isset($comm[4])){
header('location: ../indexad.php?message=Commande erronée');
exit;
}

$inventaire = $bdd->prepare("SELECT id_utilisateur FROM UTILISATEUR WHERE pseudo = :pseudo");
$inventaire->execute([
    'pseudo'=>$comm[4]
]);
$user = $inventaire->fetch();

if (!$user) {
    header('location: ../indexad.php?message=Utilisateur introuvable.');
    exit;
}

$statement = $bdd->prepare("SELECT id_inventaire FROM INVENTAIRE WHERE id_utilisateur = ?");
$statement->execute([$user['id_utilisateur']]);
$id_inventaire = $statement->fetchColumn();

$statement = $bdd->prepare('SELECT id_carte FROM CARTE WHERE id_carte = ? ');
$statement->execute([$comm[3]]);
$carte = $statement->fetch(PDO::FETCH_ASSOC);

if (!$carte) {
    header('location: ../indexad.php?message=La carte avec cet id n\'existe pas');
    exit;
}

$select = $bdd->prepare("SELECT nb FROM CONTIENT WHERE id_inventaire = ? AND id_carte = ?");
$select->execute([$id_inventaire, $carte['id_carte']]);
$verif = $select->fetch(PDO::FETCH_ASSOC);

$nb = $verif['nb'] ?? 0;



if ($comm[2] == 'retirer' && isset($_POST['nombre'])){

    if (!is_numeric($_POST['nombre'])){
        header('location:commande_don_carte.php?message=La valeur insérée doit être un nombre');
        exit;
    }

    if ($_POST['nombre'] > $nb){
        header('location:commande_don_carte.php?message=L\'utilisateur ne possede pas assez de ce type de carte');
        exit;
    }

    $nb -= $_POST['nombre'];

    if ($verif) {    
        $update = $bdd->prepare("UPDATE CONTIENT SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
        $update->execute([$nb, $id_inventaire, $carte['id_carte']]);
    }
    $update = $bdd->query("UPDATE CONTIENT SET nb = 0 WHERE nb < 0");
    $delete = $bdd->query("DELETE FROM CONTIENT WHERE nb = 0");
    header('location:../indexad.php?message=Commande effectuée avec succes');
    exit;
}



if ($comm[2] == 'ajouter' && isset($_POST['nombre'])){

    if (!is_numeric($_POST['nombre'])){
        header('location:commande_don_carte.php?message=La valeur insérée doit être un nombre');
        exit;
    }
    $nb += $_POST['nombre'];
    
    if ($verif) {    
        $update = $bdd->prepare("UPDATE CONTIENT SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
        $update->execute([$nb, $id_inventaire, $carte['id_carte']]);
    } else {
        $insert = $bdd->prepare("INSERT INTO CONTIENT (id_inventaire, id_carte, nb) VALUES (?, ?, ?)");
        $insert->execute([$id_inventaire, $carte['id_carte'], $nb]);
    }
    
    if ($carte) {
        header('location:../indexad.php?message=Commande effectuée avec succes');
        exit;
    }
}

include('headerad_com.php');

if ($comm[2] == 'ajouter'){
    echo "<h1> Ajouter la carte " . $comm[3] . " à l'inventaire de " . $comm[4] . "</h1>";
} else {
    if ($nb == 0){
        header('location:../indexad.php?message=Cet utilisateur ne possede pas cette carte');
        exit;
    }
    echo "<h1> Supprimer la carte " . $comm[3] . " de l'inventaire de " . $comm[4] . "</h1>";
}

include("../../getmessage.php");

echo "<h3>" . $comm[4] . " a " . $nb . " fois la carte " . $comm[3] . "</h3>";

echo "<form method='post' action='commande_don_carte.php'>
<label>Choisir le nombre de carte à " . $comm[2] . "</label>
<input type='text' name='nombre'>
<input type='submit' value='Envoyer'>
</form>";


