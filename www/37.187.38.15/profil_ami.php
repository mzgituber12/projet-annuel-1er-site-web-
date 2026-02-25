<?php session_start();

function Debloquerlog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/debloquer_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a débloqué ' . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function Bloquerlog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/bloquer_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a bloqué ' . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function Accepter_Amilog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/accepter_ami_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a accepté la demande d\'amis de ' . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function Supprimer_Amilog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/supprimer_ami_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a enlevé ' . $pseudo2 . " de ses amis \n";
    fputs($stream, $line);
    fclose($stream);
}

function Demande_Amilog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/demander_ami_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a demandé ' . $pseudo2 . " en amis \n";
    fputs($stream, $line);
    fclose($stream);
}

function Refuser_Amilog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/refuser_ami_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a refusé la demande d\'amis de ' . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function Annuler_Amilog($pseudo1, $pseudo2){
    $stream = fopen('log/ami_log/annuler_ami_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' ' . $pseudo1 . ' a annulé sa demande d\'ami envoyée à ' . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

include('bdd.php');

    $date = date('Y-m-d H:i:s'); 
    $user = $_GET['user'];

    $all="SELECT * FROM UTILISATEUR WHERE pseudo = :pseudo";
    $statement = $bdd->prepare($all);
    $statement->execute(
        ['pseudo'=>$user]
    );
    $result = $statement->fetch();





if ($_GET['message'] == "Demande d'ami"){
    $verifajtami = "SELECT * FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $verifajtstatement = $bdd->prepare($verifajtami);
    $verifajtstatement->execute([
        'demande'=>$result['id_utilisateur'],
        'recoit'=>$_SESSION['id']
    ]);
    $resultverifajt = $verifajtstatement->fetch();
    if($resultverifajt){
        $verifajt2 = "UPDATE AMITIER SET etat = 'amis' WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
        $verifajt2statement = $bdd->prepare($verifajt2);
        $verifajt2statement->execute([
        'demande'=>$result['id_utilisateur'],
        'recoit'=>$_SESSION['id']
    ]);

    Accepter_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user);
    exit;
    };


    $ajtami = "INSERT INTO AMITIER (id_amis_demande, id_amis_recoit, date_demande) VALUES (:id1, :id2, :dateauj)";
    $ajtstatement = $bdd->prepare($ajtami);
    $ajtstatement->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$result['id_utilisateur'],
        'dateauj'=>$date,
    ]);

    Demande_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user .'&etat=now');
    exit;
};

if ($_GET['message'] == "Annuler la demande d'ami"){
    $annami = "DELETE FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $annstatement = $bdd->prepare($annami);
    $annstatement->execute([
        'demande'=>$_SESSION['id'],
        'recoit'=>$result['id_utilisateur']
    ]);

    Annuler_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user .'&etat=now');
    exit;
};

if ($_GET['message'] == "Refuser l'ami"){
    $refami = "DELETE FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $refstatement = $bdd->prepare($refami);
    $refstatement->execute([
        'recoit'=>$_SESSION['id'],
        'demande'=>$result['id_utilisateur'],
    ]);

    Refuser_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user);
    exit;
};



if ($_GET['message'] == "Supprimer l'ami"){
    $delami = "DELETE FROM AMITIER WHERE (id_amis_demande = :demande AND id_amis_recoit = :recoit) OR (id_amis_demande = :recoit AND id_amis_recoit = :demande)";
    $ajtstatement = $bdd->prepare($delami);
    $ajtstatement->execute([
        'demande'=>$_SESSION['id'],
        'recoit'=>$result['id_utilisateur']
    ]);

    $delete = $bdd->prepare("DELETE FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $delete->execute([
            'id1'=>$_SESSION['id'],
            'id2'=>$result['id_utilisateur']
    ]);

    Supprimer_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user .'&etat=supp');
    exit;
};



if ($_GET['message'] == "Debloquer"){
    $debloquer = "DELETE FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $debstatement = $bdd->prepare($debloquer);
    $debstatement->execute([
        'demande'=>$_SESSION['id'],
        'recoit'=>$result['id_utilisateur']
    ]);

    Debloquerlog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user .'&etat=deblok');
    exit;
};



if ($_GET['message'] == "Accepter l'ami"){
    $accepterami = "UPDATE AMITIER SET etat = 'amis' WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $accstatement = $bdd->prepare($accepterami);
    $accstatement->execute([
        'demande'=>$result['id_utilisateur'],
        'recoit'=>$_SESSION['id']
    ]);

    Accepter_Amilog($_SESSION['pseudo'], $result['pseudo']);

    header('location:profil.php?user=' . $user);
    exit;
};



if ($_GET['message'] == "Bloquer"){

    $deja = "SELECT * FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $dejastatement = $bdd->prepare($deja);
    $dejastatement->execute([
        'demande'=>$_SESSION['id'],
        'recoit'=>$result['id_utilisateur']
    ]);
    $dejaresults = $dejastatement->fetch();

    $deja2 = "SELECT * FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $dejastatement2 = $bdd->prepare($deja2);
    $dejastatement2->execute([
        'demande'=>$result['id_utilisateur'],
        'recoit'=>$_SESSION['id']
    ]);
    $dejaresults2 = $dejastatement2->fetch();


    if ($dejaresults) {
        if($dejaresults['etat'] == 'bloqué'){
            header('location:profil.php?user=' . $user);
            exit;
        }
    $bloquer = "UPDATE AMITIER SET etat = 'bloqué' WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $bloquerstatement = $bdd->prepare($bloquer);
    $bloquerstatement->execute([
        'demande'=>$_SESSION['id'],
        'recoit'=>$result['id_utilisateur']
    ]);

    } else if ($dejaresults2) {
        if($dejaresults2['etat'] == 'bloqué'){
            header('location:profil.php?user=' . $user);
            exit;
        }
    $bloquer3 = "DELETE FROM AMITIER WHERE id_amis_demande = :demande AND id_amis_recoit = :recoit";
    $bloquerstatement3 = $bdd->prepare($bloquer3);
    $bloquerstatement3->execute([
        'demande'=>$result['id_utilisateur'],
        'recoit'=>$_SESSION['id']
    ]);
    $bloquersuite = "INSERT INTO AMITIER (id_amis_demande, id_amis_recoit, date_demande, etat) VALUES (:id1, :id2, :dateauj, 'bloqué')";
    $statementsuite = $bdd->prepare($bloquersuite);
    $statementsuite->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$result['id_utilisateur'],
        'dateauj'=>$date,
    ]);

    } else {
    $bloquer2 = "INSERT INTO AMITIER (id_amis_demande, id_amis_recoit, date_demande, etat) VALUES (:id1, :id2, :dateauj, 'bloqué')";
    $bloquerstatement2 = $bdd->prepare($bloquer2);
    $bloquerstatement2->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$result['id_utilisateur'],
        'dateauj'=>$date,
    ]);
}

$delete = $bdd->prepare("DELETE FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$delete->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$result['id_utilisateur']
]);

Bloquerlog($_SESSION['pseudo'], $result['pseudo']);

header('location:profil.php?user=' . $user);
exit;

};

?>