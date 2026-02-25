<?php session_start();
include('pasadmin.php');

if (!isset($_POST['commande']) ||
    empty($_POST['commande'])
    ){
    header('location: indexad.php?message=Vous devez remplir le champ.');
    exit;
}

$commande = $_POST['commande'];
$_SESSION['commande'] = $_POST['commande'];
$comm = explode('/',$commande);

if (isset($_POST['profilcomm'])){
    $_SESSION['profilcomm'] = 'xx';
}

if ($comm[0] != 'commande')
    {
    header("location: indexad.php?message=Cette commande n'existe pas");
    exit;
}
else if ($comm[1] == 'ban' || $comm[1] == 'deban'){
    header("location:commande/commande_ban.php");
    exit;
}
else if ($comm[1] == 'actu')
    {
        header("location: commande/commande_actu.php");
        exit;
}
else if ($comm[1] == 'avertir')
    {
        header("location: commande/commande_avertir.php");
        exit;
}
else if ($comm[1] == 'attaque')
    {
    header("location: commande/commande_attaque.php");
    exit;
}
else if ($comm[1] == 'modifier')
    {
    header("location: commande/commande_modifuser.php");
    exit;
}
else if ($comm[1] == 'supprimer')
    {
    header("location: commande/commande_supprimer.php");
    exit;
}
else if ($comm[1] == 'verif' || $comm[1] == 'suppverif'){
    header("location: commande/commande_verif.php");
    exit;
}
else if ($comm[1] == 'hist')
    {
    header("location: commande/commande_quiz_hist.php");
    exit;
}
else if ($comm[1] == 'geo')
    {
    header("location: commande/commande_quiz_geo.php");
    exit;
}
else if ($comm[1] == 'carte')
    {
    header("location: commande/commande_carte.php");
    exit;
}
else if ($comm[1] == 'admin' ||$comm[1] == 'suppadmin')
    {
    header("location: commande/commande_admin.php");
    exit;
}
else if ($comm[1] == 'inventaire')
{
    header("location: commande/commande_don_carte.php");
    exit;
}


else {
    header("location: indexad.php?message=Cette commande n'existe pas");
    exit;
}
