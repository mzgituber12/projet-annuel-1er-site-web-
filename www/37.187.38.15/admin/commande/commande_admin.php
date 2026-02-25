<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[3]) || !isset($comm[2])){
    header("location:../indexad.php?message=Commande erronée");
    exit;
}

include('../../bdd.php');

$user = $bdd->prepare('SELECT id_utilisateur, pseudo, role FROM utilisateur WHERE pseudo=:pseudo');
$user->execute([
    'pseudo'=>$comm[2]
]);
$results = $user->fetch();
if (!$results){
    header('location:../indexad.php?message=Utilisateur introuvable');
    exit;
}

if($comm[1] == 'admin'){
    if ($results['role'] == 'admin'){
        header('location: ../indexad.php?message=L\'utilisateur est deja admin');
        exit;
    }

$statement = $bdd->prepare('UPDATE UTILISATEUR SET role = "admin" WHERE id_utilisateur = :id');
$statement->execute([
    ':id'=>$results['id_utilisateur']
]);

} else if ($comm[1] == 'suppadmin'){
    if ($results['role'] == 'joueur'){
        header('location: ../indexad.php?message=L\'utilisateur n\'est pas admin');
        exit;
    }
    if ($results['pseudo'] == $_SESSION['pseudo']){
        header('location:../indexad.php?message=Vous ne pouvez pas vous retirer vos permissions d\'admin');
        exit;
    }

$statement = $bdd->prepare('UPDATE UTILISATEUR SET role = "joueur" WHERE id_utilisateur = :id');
$statement->execute([
    ':id'=>$results['id_utilisateur']
]);
}

header('location: ../indexad.php?message=Commande effectuée avec succes');
exit;
?>