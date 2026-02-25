<?php
include("bdd.php");

if (isset($_SESSION['pseudo'])){
    $ban = $bdd->prepare("SELECT ban, role FROM utilisateur WHERE pseudo = :pseudo");
    $ban->execute([
        'pseudo'=>$_SESSION['pseudo']
    ]);
    $banresult = $ban->fetch();
    if($banresult['ban'] == 1){
        echo "<h1> Vous avez été banni de notre site, cliquez <a href='debanstatut.php'>ici</a> pour demander un debanissement </h1>";
        echo "<a href='deconnexion.php'>Se deconnecter</a>";
        exit;
    }
    $_SESSION['role'] = $banresult['role'];
}