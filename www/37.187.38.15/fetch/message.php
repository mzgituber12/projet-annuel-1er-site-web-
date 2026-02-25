<?php
session_start();

include('../bdd.php');
date_default_timezone_set('Europe/Paris');

$update = $bdd->prepare("UPDATE message SET id2vu = 1 WHERE (id_utilisateur_envoyeur = :id2 AND id_utilisateur_destinataire = :id1)");
$update->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['id']
]);


$message = $bdd->prepare("SELECT * FROM message WHERE (id_utilisateur_envoyeur = :id1 AND id_utilisateur_destinataire = :id2) OR (id_utilisateur_envoyeur = :id2 AND id_utilisateur_destinataire = :id1) ORDER BY date_envoi DESC");
    $message->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['id']
    ]);
    $verifmsg=$message->fetchall();

    $photo_profil = $bdd->prepare("SELECT photo_profil, pseudo FROM utilisateur WHERE id_utilisateur = :id1");
    $photo_profil->execute([
        'id1'=>$_SESSION['id'],
    ]);
    $pdp=$photo_profil->fetch();

    $photo_profil->execute([
        'id1'=>$_GET['id']
    ]);
    $pdp2=$photo_profil->fetch();

    if (!$verifmsg){
        exit;
    } else {
        $derniere_date = null;
        foreach($verifmsg as $verif2){
            if ($verif2['id_utilisateur_envoyeur'] == $_SESSION['id']){
                $sender = 'Toi';
                if (!empty($pdp['photo_profil'])){
                    $sender = $sender . ' <img src="photo_profil/' . $pdp['photo_profil'] . '" alt="Photo de profil" width="40" style="border-radius: 50%">';
                }
            } else {
                $sender = $pdp2['pseudo'];
                if (!empty($pdp2['photo_profil'])){
                    $sender = $sender . ' <img src="photo_profil/' . $pdp2['photo_profil'] . '" alt="Photo de profil" width="40" style="border-radius: 50%">';
                }
            }

            $dateactu = new DateTime();
            $date_envoi = new DateTime($verif2['date_envoi']);
            $jour_envoi = $date_envoi->format('d/m/Y');

            if ($dateactu->format('d/m/Y') === $jour_envoi) {
                $date = 'Aujourd\'hui : ' . $date_envoi->format('H:i:s');
            } else {
                if ($derniere_date != $jour_envoi){
                    echo '<p style="color: white;">-------------</p>';
                    $derniere_date = $jour_envoi;
                }
                $date = $jour_envoi . ' : ' . $date_envoi->format('H:i:s') ;
            }

            if ($verif2['type'] == 'image'){
                $content = "<img src='message_image/" . $verif2['contenu'] . "' style='width:160px; height:auto'>";
            } else {
                $content = $verif2['contenu'];
            }
        echo "<p>" . $date . " | " . $sender . ' : ' . $content. "</p>";

        }
    }
