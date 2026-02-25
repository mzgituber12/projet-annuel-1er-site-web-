<?php
include("/var/www/37.187.38.15/bdd.php");

    $allvente = $bdd->query("SELECT * FROM vente_carte");
    $all = $allvente->fetchall();

    foreach($all as $vente){

        date_default_timezone_set('Europe/Paris');
        $date1 = new DateTime($vente['date_mise_en_vente']); 
        $date2 = new DateTime(); 

        $date1->modify('+24 hours');

        if ($date2 >= $date1) {

            $inventaire = $bdd->prepare(
            "SELECT contient.id_inventaire FROM contient
            JOIN inventaire ON inventaire.id_inventaire = contient.id_inventaire
            WHERE inventaire.id_utilisateur = :id");
            $inventaire->execute(['id'=>$vente['id_vendeur']]);
            $inv = $inventaire->fetch();

            $inventaire = $bdd->prepare('SELECT nb FROM CONTIENT WHERE id_inventaire = :id AND id_carte = :carte');
            $inventaire->execute([
            'id'=>$inv['id_inventaire'],
            'carte'=>$vente['id_carte']
            ]);
            $nombre = $inventaire->fetch();

            if ($nombre){
                $nb = $nombre['nb'] + 1;
                $update = $bdd->prepare("UPDATE CONTIENT SET nb = :nb WHERE id_inventaire = :id AND id_carte = :carte");
                $update->execute([
                'nb'=>$nb,
                'id'=>$inv['id_inventaire'],
                'carte'=>$vente['id_carte']
                ]);
            } else {
                $update = $bdd->prepare("INSERT INTO CONTIENT VALUES (:id, :carte, 1)");
                $update->execute([
                'id'=>$inv['id_inventaire'],
                'carte'=>$vente['id_carte']
                ]);
            }

        $suppvente = $bdd->prepare("DELETE FROM VENTE_CARTE WHERE id_vente = :vente");
        $suppvente->execute([
            'vente'=>$vente['id_vente']
        ]);

        }
    }