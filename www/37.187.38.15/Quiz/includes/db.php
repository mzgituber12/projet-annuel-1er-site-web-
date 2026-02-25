<?php

try{
    $bdd = new PDO('mysql:host=localhost:3306;dbname=base_site',
    'root',
    'root',
    [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]
);
}
catch (Exception $e){
    die('Erreur PDO :' . $e->getMessage());
}

?>