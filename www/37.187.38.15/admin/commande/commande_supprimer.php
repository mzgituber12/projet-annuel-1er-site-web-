<?php session_start();

include('pasadmin.php');
include('../../bdd.php');

$commande = $_SESSION['commande'];
$comm = explode('/', $commande);

if (!isset($comm[2]) || isset($comm[3])){
    header('location:../indexad.php?message=Commande erronée');
    exit;
}

if (isset($_GET['valid']) && isset($_POST['choice'])){
    if ($_POST['choice'] == 'Oui'){
        $supprimer = $bdd->prepare("DELETE FROM UTILISATEUR WHERE pseudo = :pseudo");
        $supprimer->execute([
            'pseudo'=> $comm[2]
        ]);

        if ($_SESSION['profilcomm'] == 'xx'){
        $_SESSION['profilcomm'] = '';
        header('location:../../index.php?message=Compte supprimé avec succes');
        exit;
        }
        
        header('location:../indexad.php?message=Commande effectuée avec succes');
        exit;
    } else {

        if ($_SESSION['profilcomm'] == 'xx'){
            $_SESSION['profilcomm'] = '';
            header('location:../../profil.php?user=' . $comm[2]);
            exit;
            }

        header('location:../indexad.php');
        exit;
    }
}

$select = $bdd->prepare("SELECT pseudo, role FROM UTILISATEUR WHERE pseudo = :pseudo");
$select->execute([
    'pseudo'=> $comm[2]
]);
$results = $select->fetch();
if (!$results){
    header('location:../indexad.php?message=Utilisateur introuvable');
    exit;
}
if ($results['role'] == 'admin'){
    header('location:../indexad.php?message=Vous ne pouvez pas supprimer le compte d\'un utilisateur admin');
    exit;
}

include('headerad_com.php');

echo "<h1> Etes vous sur de vouloir supprimer le compte de " . $comm[2] . " ? </h1>";
?>
<form method='post', action='commande_supprimer.php?valid'>
    <input type='submit' name='choice' value='Oui'>
    <input type='submit' name='choice' value='Non'>
</form>