<?php
include("/var/www/37.187.38.15/bdd.php");

    $allechange = $bdd->query("SELECT * FROM echange");
    $all = $allechange->fetchall();

    foreach($all as $echange){

        date_default_timezone_set('Europe/Paris');
        $date1 = new DateTime($echange['temps_j1']);
        $date2 = new DateTime($echange['temps_j2']); 
        $date3 = new DateTime(); 

        $date1->modify('+4 minutes 30 seconds');
        $date2->modify('+4 minutes 30 seconds');

        if ($date3 >= $date1 || $date3 >= $date2) {

        $suppech = $bdd->prepare("DELETE FROM ECHANGE WHERE id_echange = :echange");
        $suppech->execute([
            'echange'=>$echange['id_echange']
        ]);

        }
    }