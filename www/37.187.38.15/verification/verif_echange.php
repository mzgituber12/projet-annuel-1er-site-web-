<?php session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

unset($_SESSION['echange_accepter']);
unset($_SESSION['echange_deja_effek']);
unset($_SESSION['echange_refus']);
unset($_SESSION['echange_annuler']);

unset($_SESSION['echange_cancel']);
unset($_SESSION['effectuée_change']);

    include('../bdd.php');

    $iduser=$bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo");
    $iduser->execute(['pseudo'=>$_GET['user']]);
    $result=$iduser->fetch();

    if (!$result){
    header('location:../index.php?message=L\'utilisateur ' . $_GET['user'] . ' n\'existe pas');
    exit;
    }

    if ($result['id_utilisateur'] != $_GET['iduser']){
        header('location:../index.php?message=Une erreur est survenue');
        exit;
    }

    $verifami = $bdd->prepare("SELECT * FROM amitier WHERE (id_amis_demande = :id1 AND id_amis_recoit = :id2) OR (id_amis_demande = :id2 AND id_amis_recoit = :id1) ");
    $verifami->execute(['id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']]);
    $verif=$verifami->fetchall();
    $x = 0;

    foreach($verif as $verifamis){
    if ($verifamis['etat'] == 'amis'){
    $x += 1;
    }
    if ($x == 0){
    header('location:../index.php?message=Vous devez être ami avec l\'utilisateur ' . $_GET['user'] . ' pour lui envoyer une demande d\'echange');
    exit;
    }
    }

    $echange = $bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $echange->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['iduser']
    ]);
    $verif = $echange->fetch();

    $echange11 = $bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $echange11->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['iduser']
    ]);
    $verifdemande = $echange11->fetch();

    if (isset($_GET['annuler'])){
        $delete = $bdd->prepare("DELETE FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
        $delete->execute([
            'id1'=>$_SESSION['id'],
            'id2'=>$_GET['iduser']
        ]);
        header('location:../discussion.php?user=' . $_GET['user'] . '&message=Echange refusé');
        exit;
    }

    if ($verifdemande && $verifdemande['etat'] == 'en_attente'){
        $updatedemande = $bdd->prepare("UPDATE echange SET etat = 'en_cours' WHERE (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
        $updatedemande->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['iduser']
        ]);
        $updatedemande = $updatedemande->fetch();
        header("location:../echange.php?user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur']);
        exit;
    }

    if ($verif){
        if ($verif['etat'] == 'en_cours'){
            echo"<h1>Vous echangez déjà avec l'utilisateur " . $_GET['user'] . "</h1>";
            exit;
        } else {
            echo"<h1>Vous avez deja envoyé une demande d'échange à l'utilisateur " . $_GET['user'] . "</h1>";
            exit;
        }
    }

    $echange = $bdd->prepare("INSERT INTO echange (id_utilisateur_1, id_utilisateur_2) VALUES (?, ?)");
    $echange->execute([
        $_SESSION['id'],
        $_GET['iduser']
    ]);

    header("location:../echange.php?user=" . $_GET['user'] . "&iduser=" . $result['id_utilisateur']);