<?php session_start(); 
include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Liste des amis</title>
</head>
<body>
    <?php
    
    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }
    
    include("header.php");

    echo '<div style="margin-left:8px; margin-top:4px">';

    echo '<h1>Liste d\'amis</h1>';

    include("bdd.php");

    $intro = "DELETE FROM AMITIER WHERE id_amis_demande = id_amis_recoit";
    $introstatement = $bdd->prepare($intro);
    $introstatement->execute();

    $dejaamis = "SELECT UTILISATEUR.pseudo, UTILISATEUR.id_utilisateur FROM UTILISATEUR
    JOIN AMITIER ON UTILISATEUR.id_utilisateur = AMITIER.id_amis_demande OR UTILISATEUR.id_utilisateur = AMITIER.id_amis_recoit
    WHERE AMITIER.etat = 'amis' AND (AMITIER.id_amis_recoit = :idrecoit OR AMITIER.id_amis_demande = :idrecoit)";
    $dejastatement = $bdd->prepare($dejaamis);
    $dejastatement->execute([
        'idrecoit' => $_SESSION['id']
    ]);
    $dejaresults = $dejastatement->fetchAll(PDO::FETCH_ASSOC);


    $notif = $bdd->prepare("SELECT messagenotif FROM utilisateur WHERE id_utilisateur = ?");
    $notif->execute([$_SESSION['id']]);
    $notifresult = $notif->fetch();

    ?>

    <script>
        async function actuheader(x){
            const f = await fetch('updating.php?id=' + x)
        }
    </script>

    <?php

   if ($dejaresults) {
    echo "<h2> Envoyer un message à vos amis </h2>";
    foreach ($dejaresults as $amisgood) {
        if ($_SESSION['id'] != $amisgood['id_utilisateur']) {
            echo "<div style='margin-bottom: 6px;'>";
            echo "<a onclick='actuheader(" . json_encode($amisgood['id_utilisateur']) . ")' href='discussion.php?user=" . htmlspecialchars($amisgood['pseudo']) . "'>" . htmlspecialchars($amisgood['pseudo']) . "</a>";

            $nonvu = $bdd->prepare("SELECT id_message FROM message WHERE id_utilisateur_envoyeur = ? AND id_utilisateur_destinataire = ? AND id2vu = 0");
            $nonvu->execute([
                $amisgood['id_utilisateur'],
                $_SESSION['id']
            ]);
            $nonvufetch = $nonvu->fetchAll();
            if (!empty($nonvufetch) && $notifresult['messagenotif'] == 1) {
                echo "<span style='
                    display: inline-block;
                    margin-left: 9px;
                    width: 12px;
                    height: 12px;
                    background-color: green;
                    border-radius: 50%;
                    vertical-align: middle;
                '></span>";
            }

            echo "</div>";
        }
    }
} else {
    echo "<h3> Vous n'avez pas encore d'amis </h3>";
}

    $demandes_amis = "SELECT UTILISATEUR.pseudo FROM UTILISATEUR
    JOIN AMITIER ON UTILISATEUR.id_utilisateur = AMITIER.id_amis_demande
    WHERE AMITIER.etat = 'en attente' AND AMITIER.id_amis_recoit = :idrecoit";
    $demandes_statement = $bdd->prepare($demandes_amis);
    $demandes_statement->execute([
        'idrecoit' => $_SESSION['id']
    ]);
    $demandes_results = $demandes_statement->fetchAll();

    $demandes_ech = "SELECT UTILISATEUR.pseudo FROM UTILISATEUR
    JOIN ECHANGE ON UTILISATEUR.id_utilisateur = ECHANGE.id_utilisateur_1
    WHERE ECHANGE.id_utilisateur_2 = :idrecoit";
    $demandes_echh = $bdd->prepare($demandes_ech);
    $demandes_echh->execute([
        'idrecoit' => $_SESSION['id']
    ]);
    $demandes_echanges = $demandes_echh->fetchAll();

    echo "<h2> Demandes d'amis </h2>";
    if($demandes_results){
        foreach($demandes_results as $demandes_amis){
            echo "<a href=profil.php?user=" . htmlspecialchars($demandes_amis['pseudo']) .">" . $demandes_amis['pseudo'] . "</a> <span style='
                        display: inline-block;
                        margin-left: 4px;
                        width: 12px;
                        height: 12px;
                        background-color: green;
                        border-radius: 50%;
                        transform: translate(4px, 1.5px);
                    '></span><br>";
        }
    }else{
        echo "<p> Aucune demande d'ami en attente </p>";
    }

    echo "<h2> Demandes d'echanges </h2>";
    if($demandes_echanges){
        foreach($demandes_echanges as $demandes_echanges2){
            echo "<a href=discussion.php?user=" . htmlspecialchars($demandes_echanges2['pseudo']) .">" . $demandes_echanges2['pseudo'] . "</a><span style='
                        display: inline-block;
                        margin-left: 4px;
                        width: 12px;
                        height: 12px;
                        background-color: orange;
                        border-radius: 50%;
                        transform: translate(4px, 1.5px);
                    '></span><br>";
        }
    }else{
        echo "<p> Aucune demande d'échange en attente </p>";
    }

    echo "</div>";

    ?>
</body>
</html>