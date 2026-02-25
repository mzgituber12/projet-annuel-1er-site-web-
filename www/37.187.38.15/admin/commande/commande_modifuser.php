<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[3]) || !isset($comm[2])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

include('../../bdd.php');

$statement = $bdd->prepare('SELECT * FROM UTILISATEUR WHERE pseudo = :pseudo');
$statement->execute([
    'pseudo'=>$comm[2]
]);
$results=$statement->fetch();
if (empty($results)){
    header('location: ../indexad.php?message=Cet utilisateur n\'existe pas');
    exit;
}

if (isset($_GET['modif'])){

    if (!empty($_POST['pseudo']) && strpos($_POST['pseudo'], '/') !== false){
        header('location: commande_modifuser.php?message=Le pseudo ne peut pas contenir de "/"');
            exit;
    }

    if(!empty($_POST['mdp']) && strlen($_POST['mdp']) < 5){
        header('location: commande_modifuser.php?message=Le mot de passe doit avoir au minimum 5 caracteres');
        exit;
    }

    if ($_POST['victoires'] != '' && !is_numeric($_POST['victoires'])){
        header('location: commande_modifuser.php?message=Nombre de victoires ou de goodies invalide');
        exit;
    }

    if ($_POST['goodies'] != '' && !is_numeric($_POST['goodies'])){
        header('location: commande_modifuser.php?message=Nombre de victoires ou de goodies invalide');
        exit;
    }

    if ($_FILES['photo']['error'] == 0){

        $acceptable =['image/png'];
        if(!in_array($_FILES['photo']['type'], $acceptable)){
        header('location: commande_modifuser.php?message=L\'image doit etre un png');
        exit;
    }

    $maxSize = 500 * 1024;
    if($_FILES['photo']['size']> $maxSize){
        header('location: ../parametres/parametres_perso_modif.php?photo=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }

    $filename = $results['id_utilisateur'] . "_user.png";
    $deplacement = '../../photo_profil/'; 
    $telechargement = $deplacement . basename($filename);

    if (!move_uploaded_file($_FILES['photo']['tmp_name'], $telechargement)) {
        header('location: ../parametres/parametres_perso_modif.php?photo=Erreur lors du téléchargement du fichier.');
        exit;
    }
    }

    if (isset($_POST['pseudo']) && !empty($_POST['pseudo'])){

        $v = 'pseudo';
        $x = $_POST['pseudo'];
        include ("commande_modifuser2.php");
        $comm[2] = $_POST['pseudo'];

    } 
    
    if (isset($_POST['email']) && !empty($_POST['email'])){
        $v = 'email';
        $x = $_POST['email'];
        include ("commande_modifuser2.php");

    } 
    
    if (isset($_POST['mdp']) && !empty($_POST['mdp'])){
        
        $v = 'mot_de_passe';
        $x = hash('sha512', $_POST['mdp']);
        include ("commande_modifuser2.php");
    }

    if (isset($_FILES['photo']) && $_FILES['photo']['error'] == 0) {
        $v = 'photo_profil';
        $x = $filename;
        include ("commande_modifuser2.php");

    } else if ($_POST['photo_2'] == 'none'){
        $v = 'photo_profil';
        $x = '';
        unlink('../../photo_profil/' . $results['photo_profil']);
        include ("commande_modifuser2.php");
    }

    if (isset($_POST['a_propos']) && !empty($_POST['a_propos'])){
        $v = 'a_propos';
        $x = $_POST['a_propos'];
        include ("commande_modifuser2.php");
    }

    if ($_POST['victoires'] != '' && isset($_POST['victoires'])){
        $v = 'nb_victoire';
        $x = $_POST['victoires'];
        include ("commande_modifuser2.php");
    }

    if ($_POST['goodies'] != '' && isset($_POST['goodies'])){
        $v = 'goodies';
        $x = $_POST['goodies'];
        include ("commande_modifuser2.php");
    }

    if ($_SESSION['profilcomm'] == 'xx'){
        $_SESSION['profilcomm'] = '';
        header('location:../../profil.php?message=Données modifiés avec succes&user=' . $comm[2]);
        exit;
        }

    header('location:../indexad.php?message=Commande effectuée avec succes');
    exit;
}

if ($results['photo_profil']){
    $photo = "<br><img src='../../photo_profil/" . htmlspecialchars($results['photo_profil']) . "' alt='Photo de profil' width='40' style='border-radius: 50%'>";
} else {
    $photo = "Aucune photo de profil pour cet utilisateur";
}

include('headerad_com.php');
?>

<h1>Modification de l'utilisateur <?= $comm[2] ?></h1>

<?php 
include('../../getmessage.php');
?>

    <h3> Laissez vide si vous ne souhaitez pas modifier les parties correspondantes </h3>

    <h3> Données actuelles concernant l'utilisateur' </h3>

    <?= "
    <p>Pseudo actuel : " . $results['pseudo'] . "</p>
    <p>Email actuel : " . $results['email'] . "</p>
    <p>Mot de passe : </p>
    <p>Photo de profil actuelle : " . $photo . "</p>
    <p>'A propos' actuel : " . $results['a_propos'] . "</p>
    
    <br>
    
    <p> Nombre de victoires actuels : " . $results['nb_victoire'] . "</p>
    <p> Nombre de goodies actuels : " . $results['goodies'] . "</p>" ?>

    

    <h3> Nouvelles données concernant l'utilisateur </h3>

<form method="post" action="commande_modifuser.php?modif" enctype ="multipart/form-data">
    <p><label>Inserer le pseudo</label>
    <input type ="text" name="pseudo"></p>
    <p><label>Inserer la nouvelle adresse email</label>
    <input type ="email" name="email"></p>
    <p><label>Inserer le mot de passe</label>
    <input type ="password" name="mdp"></p>
    <p><label>Inserer ou modifier la photo de profil</label>
    <input type ="file" name="photo" accept ="image/png"></p>
    <p><label>Pas de photo de profil</label>
    <input type ="checkbox" name="photo_2" value="none"></p>
    <p><label>Modifier le 'à propos'</label>
    <input type ="text" name="a_propos"></p>
    <br>
    <p><label>Modifier le nombre de victoires</label>
    <input type ="text" name="victoires"></p>
    <p><label>Modifier le nombre de goodies</label>
    <input type ="text" name="goodies"></p>
    <input type="submit" value='Modifier'>
</form>