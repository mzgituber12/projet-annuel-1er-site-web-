<?php 
session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

$verif = $bdd->prepare("SELECT nb FROM CONTIENT_COFFRE WHERE id_coffre = :id AND id_inventaire = :inventaire");
    $verif->execute([
        'id'=>$id,
        'inventaire'=>$_SESSION['inventaire']
    ]);
    $results = $verif->fetch();
    if (!$results['nb'] || $results['nb'] == 0){
        header('location:../inventaire.php');
        exit;
    }

    $remove = $bdd->prepare("UPDATE CONTIENT_COFFRE SET nb = nb-1 WHERE id_inventaire = :inventaire AND id_coffre = :id");
    $remove->execute([
        'inventaire'=>$_SESSION['inventaire'],
        'id'=>$id
    ]);

    $verif->execute([
        'id'=>$id,
        'inventaire'=>$_SESSION['inventaire']
    ]);
    $results = $verif->fetch();
    if ($results['nb'] == 0){
        $supp =$bdd->prepare("DELETE FROM CONTIENT_COFFRE WHERE id_inventaire = :inventaire AND id_coffre = :id");
        $supp->execute([
            'inventaire'=>$_SESSION['inventaire'],
            'id'=>$id
        ]);
    }