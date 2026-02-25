<?php 
include("/var/www/37.187.38.15/bdd.php");

    $victoiretemp = "UPDATE UTILISATEUR SET temps_passé_semaine = 0";
    $statement = $bdd->prepare($victoiretemp);
    $statement->execute();
    
?>