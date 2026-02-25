<?php session_start();

function raisondanslog(){
$file = fopen('log/debannir_log.txt', 'a');
fwrite($file, date('Y-m-d H:i:s') . " : Nom d'utilisateur : " . $_SESSION['pseudo'] . " | Adresse email : " . $_SESSION['email'] . " a demandé à se faire debannir pour le motif suivant : \n" . $_POST['raison'] . "\n");
fclose($file);
}

include("bdd.php");

if (!isset($_SESSION['pseudo'])){
    header('location:index.php');
    exit;
}

if ($_POST['email'] != $_SESSION['email']){
        header('location:debanstatut.php?message=L\'adresse email saisie est invalide');
        exit;
}

if (!isset($_POST['raison']) || strlen($_POST['raison']) <= 4){
    header('location:debanstatut.php?message=Votre motif de bannissement est vide ou trop court');
    exit;
}

    $ban = $bdd->prepare("SELECT ban, role FROM utilisateur WHERE pseudo = :pseudo");
    $ban->execute([
        'pseudo'=>$_SESSION['pseudo']
    ]);
    $banresult = $ban->fetch();
    $_SESSION['role'] = $banresult['role'];

    if($banresult['ban'] == 0){
        header('location:index.php');
        exit;
    }

    if (!isset($_SESSION['esban']) || $_SESSION['esban'] != 1){
    raisondanslog();
    }
    $_SESSION['esban'] = 1;

    echo '<h1>Votre demande a bien été reçue, nous vous tiendrons informé par mail prochainement</h1>';
    echo '<a href="deconnexion.php">Se deconnecter</a>';

?>