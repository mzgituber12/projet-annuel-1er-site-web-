<?php 
include("/var/www/37.187.38.15/bdd.php");

    $allusers = $bdd->query("SELECT id_utilisateur FROM utilisateur");
    $all = $allusers->fetchall();

    foreach($all as $users){

        $statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp1_1 = 1, boutiquetemp1 = 1 where id_utilisateur = :id");
        $statement->execute([
        'id'=>$users['id_utilisateur']
        ]);

        $rand2 = rand(0,100);
        if ($rand2 <= 80){
            $id2 = 1;
        } else if ($rand2 <= 98){
            $id2 = 2;
        } else {
            $id2 = 3;
        }
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp2_1 = $id2, boutiquetemp2 = 1 where id_utilisateur = :id");
        $statement->execute([
        'id'=>$users['id_utilisateur']
        ]);

        $rand3 = rand(0,100);
        if ($rand3 <= 50){
        $id3 = 1;
        } else if ($rand3 <= 80){
        $id3 = 2;
        } else if ($rand3 <= 90){
        $id3 = 3;
        } else if ($rand3 <= 98){
        $id3 = 4;
        } else {
        $id3 = 5;
        }
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp3_1 = $id3, boutiquetemp3 = 1 where id_utilisateur = :id");
        $statement->execute([
        'id'=>$users['id_utilisateur']
        ]);

    }