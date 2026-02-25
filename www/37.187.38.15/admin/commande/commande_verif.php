<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[3]) || !isset($comm[2])){
    header("location:../indexad.php?message=Commande erronée");
    exit;
}

include("../../bdd.php");

$user = $bdd->prepare("SELECT id_utilisateur, statut FROM utilisateur WHERE pseudo = :pseudo");
$user->execute([
    'pseudo'=>$comm[2]
]);
$result = $user->fetch(PDO::FETCH_ASSOC);

if (!$result){
    header("location:../indexad.php?message=Utilisateur introuvable");
    exit;
}

if ($comm[1] == "suppverif"){
    if ($result['statut'] == 0){
        header("location:../indexad.php?message=L'utilisateur n'est pas verifié");
        exit;
    }
    $verif = $bdd->prepare("UPDATE utilisateur SET statut = 0 WHERE pseudo = :pseudo");
$verif->execute([
    'pseudo'=>$comm[2]
]);

if ($_SESSION['profilcomm'] == 'xx'){
    $_SESSION['profilcomm'] = '';
    header('location:../../profil.php?user=' . $comm[2] . '&message=L\'utilisateur n\'est à présent plus vérifié');
    exit;
    }

header("location:../indexad.php?message=Commande effectuée avec succes");
exit;
}

if ($result['statut'] == 1){
    header("location:../indexad.php?message=L'utilisateur est déjà verifié");
    exit;
}
$verif = $bdd->prepare("UPDATE utilisateur SET statut = 1 WHERE pseudo = :pseudo");
$verif->execute([
    'pseudo'=>$comm[2]
]);

if ($_SESSION['profilcomm'] == 'xx'){
    $_SESSION['profilcomm'] = '';
    header('location:../../profil.php?user=' . $comm[2] . '&message=L\'utilisateur est à présent vérifié');
    exit;
    }

header("location:../indexad.php?message=Commande effectuée avec succes");
exit;