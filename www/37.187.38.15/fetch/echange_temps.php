<?php session_start();
include('../bdd.php');

$verifier=$bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$verifier->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
$resultverifier=$verifier->fetch();

if ($resultverifier['id_utilisateur_1'] == $_SESSION['id']){

    $actualisation = $bdd->prepare("UPDATE echange SET temps_j1 = CURRENT_TIMESTAMP() WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $actualisation->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
    ]);

} else if ($resultverifier['id_utilisateur_2'] == $_SESSION['id']){

    $actualisation = $bdd->prepare("UPDATE echange SET temps_j2 = CURRENT_TIMESTAMP() WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $actualisation->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
    ]);

}