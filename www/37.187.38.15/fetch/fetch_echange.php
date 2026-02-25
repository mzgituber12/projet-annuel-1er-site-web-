<?php session_start();
include('../bdd.php');

$verif = $bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$verif->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);

$xx = $verif->fetch();

if (isset($_GET['refuser'])){
    $verif = $bdd->prepare("DELETE FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $verif->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
}



if ($_SESSION['id'] == $xx['id_utilisateur_1'] && $xx['etat'] == 'j1'){

    $update = $bdd->prepare("UPDATE echange SET etat = 'en_cours' WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $update->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);

} else if ($_SESSION['id'] == $xx['id_utilisateur_2'] && $xx['etat'] == 'j2'){

    $update = $bdd->prepare("UPDATE echange SET etat = 'en_cours' WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $update->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
}



$verifid1 = $bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$verifid1->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);

$yy=$verifid1->fetch();

if ($xx['etat'] == 'en_cours' || $xx['etat'] == 'j1' || $xx['etat'] == 'j2' || $yy['etat'] == 'en_attente' && !isset($_SESSION['echange_refus']) && !isset($_SESSION['echange_deja_effek']) && !isset($_SESSION['echange_annuler'])){

    if ($xx['etat'] != 'j1' && $xx['etat'] != 'j2'){
    $update = $bdd->prepare("UPDATE echange SET etat = 'en_cours' WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $update->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
    }

    $_SESSION['echange_accepter'] = "good";

    if (!isset($_SESSION['echange_refus'])){

    echo "<h1>Demande d'échange acceptée";
    echo "<h3><a href = 'echange_en_cours.php?user=" . $_GET['user'] . "&iduser=" . $_GET['iduser'] . "'> Cliquez ici pour accéder à la page d'échange. </a></h3>";
    echo "<p> Si vous fermez la page pendant un certain temps, l'échange sera annulé</p>";
    exit;

    }
} 

if ($xx && !isset($_SESSION['echange_refus']) && !isset($_SESSION['echange_deja_effek']) && !isset($_SESSION['echange_annuler'])){
    echo "<h1>Demande d'échange envoyée, en attente de réponse de " . htmlspecialchars($_GET['user']);
    echo "<h3 onclick='gobackdiscut()'><a href = 'verification/verif_echange.php?annuler&iduser=" . $_GET['iduser'] . "&user=" . $_GET['user'] . "'> Annuler la demande d'échange </a></h3>";
    echo "<p> Si vous fermez la page pendant un certain temps, l'échange sera annulé</p>";

} else {

    if(isset($_SESSION['echange_accepter'])){
        $_SESSION['echange_annuler'] = 'good';

        echo "<h1>Echange annulé</h1>";
        echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
        exit;
    }
    
    $_SESSION['echange_refus'] = 'good';

    echo "<h1>Demande d'échange refusée";
    echo "<h3><a href = 'discussion.php?user=" . $_GET['user'] . "'> Retour aux messages </a></h3>";

}
?>