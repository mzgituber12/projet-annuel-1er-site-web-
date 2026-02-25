<?php session_start();

include('../bdd.php');

$iduser=$bdd->prepare("SELECT id_utilisateur, pseudo FROM utilisateur WHERE pseudo = :pseudo");
$iduser->execute(['pseudo'=>$_GET['user']]);
$result=$iduser->fetch();

if ($result['pseudo'] == $_SESSION['pseudo'] || !isset($_SESSION['pseudo'])){
    exit;
}

$echange = $bdd->prepare("SELECT * FROM echange WHERE id_utilisateur_1 = :id AND id_utilisateur_2 = :id2");

    $echange->execute([
        'id'=>$_SESSION['id'],
        'id2'=>$result['id_utilisateur']
    ]);
    $resultechange = $echange->fetch();



    $echange->execute([
        'id'=>$result['id_utilisateur'],
        'id2'=>$_SESSION['id']
    ]);
    $resultechange2 = $echange->fetch();



    if ($resultechange['etat'] == 'en_attente'){
        $a = "<p><a href='verification/verif_echange.php?annuler&user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur']  . "'>Annuler la demande d'échange envoyée à " . $_GET['user'] . "</a></p>";
    }

    $array = ['en_cours', 'j1', 'j2'];
    if (in_array($resultechange['etat'], $array) || in_array($resultechange2['etat'], $array)) {
        $a = "<p><a href='echange_en_cours.php?user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur']  . "' target='_blank'>Rejoindre votre échange en cours avec " . $_GET['user'] . "</a></p>";
    }

    

    if ($resultechange2['etat'] == 'en_attente'){
        $a = "<p><a href='verification/verif_echange.php?user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur'] . "' target='_blank'>Accepter la demande d'échange de " . $_GET['user'] . "</a></p>";
        $r = "<p><a href='verification/verif_echange.php?annuler&user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur'] . "'>Refuser la demande d'échange de " . $_GET['user'] . "</a></p>";
        $f = "<h3>Vous avez reçu une demande d'échange de " . $_GET['user'] . "</h3>";
    }

    if (!$resultechange && !$resultechange2){
        $a = "<p><a href='verification/verif_echange.php?user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur'] . "' target='_blank'>Envoyer une demande d'échange à " . $_GET['user'] . "</a></p>";
    }

    if (isset($f)){
        echo $f;
    }
    
    echo $a;

    if (isset($r)){
        echo $r;
    }

    ?>