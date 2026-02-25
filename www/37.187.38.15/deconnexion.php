<?php
session_start();
session_destroy(); 

if (isset($_GET['tokenreussit'])){
    header('location: index.php?message=Compte verifié avec succes, veuillez vous reconnecter pour continuer !');
    exit;
}

header('location: index.php?message=Vous vous êtes déconnecté');
exit;