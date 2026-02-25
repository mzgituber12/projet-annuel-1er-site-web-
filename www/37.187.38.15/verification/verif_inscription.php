<?php

function inscriptionLog($pseudo, $email){
    $stream = fopen('../log/inscription_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' | Adresse email : ' . $email . ' s\'est inscrit' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

setcookie('inscripseudo', urlencode($_POST['pseudo']), time() + (60 * 60), "/");
setcookie('inscriemail', urlencode($_POST['email']), time() + (60 * 60), "/");

if (!isset($_POST['pseudo']) ||
    !isset($_POST['email']) ||
    !isset($_POST['mdp']) ||
    !isset($_POST['mdp2']) ||
    empty($_POST['pseudo']) ||
    empty($_POST['email']) ||
    empty($_POST['mdp']) ||
    empty($_POST['mdp2'])
    ){
    header('location: ../inscription.php?message=Vous devez remplir tous les champs obligatoires.');
    exit;
}
if(!filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)) {
    header('location: ../inscription.php?message=L\'adresse email saisie est invalide.');
    exit;
}
if($_POST['mdp'] !== $_POST['mdp2']){
    header('location: ../inscription.php?message=Les deux mots de passes ne correspondent pas.');
    exit;
}

if (strlen($_POST['mdp']) < 5){
    header("location:../inscription.php?message=Le mot de passe tapé est trop court, choisissez un mot de passe plus long.");
    exit;
}
$test_lettre = 0;
for ($i = 0; $i < strlen($_POST['pseudo']); $i++) {
    if (ctype_alpha($_POST['pseudo'][$i])) {
        $test_lettre = 1;
    }
}
if ($test_lettre == 0){
    header("location:../inscription.php?message=Votre nom d'utilisateur doit avoir au minimum une lettre.");
    exit;
}

if (!empty($_FILES['pdp']) && $_FILES['pdp']['error'] !== 4){
    $acceptable =['image/png'];
    if(!in_array($_FILES['pdp']['type'], $acceptable)){
        header('location: ../inscription.php?message=L\'image doit etre un png');
        exit; 
        }
    $maxSize = 500 * 1024;
    if($_FILES['pdp']['size']> $maxSize){
        header('location: ../inscription.php?message=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }
}

include("../bdd.php");

$q ='SELECT id_utilisateur FROM UTILISATEUR WHERE pseudo = :pseudo';
$statement = $bdd->prepare($q);
$statement -> execute([
    'pseudo' => $_POST['pseudo']
]);
$results = $statement->fetchAll(); 
if(!empty($results)){
    header('location: ../inscription.php?message=Le pseudo est déjà utilisée.');
    exit;
}

$q ='SELECT id_utilisateur FROM UTILISATEUR WHERE email = :email';
$statement = $bdd->prepare($q);
$statement -> execute([
    'email' => $_POST['email']
]);
$results = $statement->fetchAll(); 
if(!empty($results)){
    header('location: ../inscription.php?message=L\'adresse email est déjà utilisée.');
    exit;
}


$query = 'INSERT INTO UTILISATEUR (pseudo,email,mot_de_passe) VALUES (:pseudo,:email,:mdp)';
$statement = $bdd->prepare($query); 
$statement -> execute([
    'pseudo'=>$_POST['pseudo'],
    'email'=>$_POST['email'],
    'mdp'=>hash('sha512',$_POST['mdp'])
    
]);

if (!$statement){
    header('location: ../inscription.php?message=Erreur lors de l\'enregistrement');
    exit;
}




$id = 'SELECT * FROM UTILISATEUR WHERE pseudo=:pseudo';
$statement2 = $bdd->prepare($id);
$statement2 -> execute([
    'pseudo'=>$_POST['pseudo']
]);
$results2 = $statement2->fetch(PDO::FETCH_ASSOC);

$statement = $bdd->prepare("INSERT INTO INVENTAIRE (id_utilisateur) VALUES (?)");
$statement->execute([$results2['id_utilisateur']]);

session_start();
$_SESSION['id'] = $results2['id_utilisateur'];
$_SESSION['email'] = $_POST['email'];
$_SESSION['pseudo'] = $_POST['pseudo'];
$_SESSION['role'] = "joueur";
$_SESSION['mdp'] = hash('sha512', $_POST['mdp']);
$_SESSION['statut'] = $results2['statut'];

$statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp1_1 = 1 where id_utilisateur = :id");
$statement->execute([
'id'=>$_SESSION['id']
        ]);

        $rand2 = rand(0,100);
        if ($rand2 <= 80){
            $id2 = 1;
        } else if ($rand2 <= 98){
            $id2 = 2;
        } else {
            $id2 = 3;
        }
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp2_1 = $id2 where id_utilisateur = :id");
        $statement->execute([
        'id'=>$_SESSION['id']
        ]);

        $rand3 = rand(0,100);
        if ($rand3 <= 50){
        $id3 = 1;
        } else if ($rand3 <= 80){
        $id3 = 2;
        } else if ($rand3 <= 90){
        $id3 = 3;
        } else if ($rand3 <= 98){
        $id3 = 4;
        } else {
        $id3 = 5;
        }
        $statement = $bdd->prepare("UPDATE UTILISATEUR SET boutiquetemp3_1 = $id3 where id_utilisateur = :id");
        $statement->execute([
        'id'=>$_SESSION['id']
        ]);

$inventaire = $bdd->prepare("SELECT id_inventaire FROM inventaire WHERE id_utilisateur = :id");
$inventaire->execute([
    'id'=>$_SESSION['id']
]);
$resultsinv=$inventaire->fetch();

$_SESSION['inventaire'] = $resultsinv['id_inventaire'];

$invinsert = $bdd->prepare("INSERT INTO CONTIENT (id_inventaire, id_carte) VALUES (?, ?)");

$invinsert->execute([
    $_SESSION['inventaire'],
    1
]);

$invinsert->execute([
    $_SESSION['inventaire'],
    3
]);

$invinsert->execute([
    $_SESSION['inventaire'],
    4
]);

$invinsert->execute([
    $_SESSION['inventaire'],
    5
]);

$invinsert->execute([
    $_SESSION['inventaire'],
    6
]);

$invinsert->execute([
    $_SESSION['inventaire'],
    7
]);

inscriptionLog($_SESSION['pseudo'], $_SESSION['email']);
setcookie('pseudo', urlencode($_SESSION['pseudo']), time() + (24 * 60 * 60), "/");
setcookie('inscripseudo', urlencode($_POST['pseudo']), time() - 3600, "/");
setcookie('inscriemail', urlencode($_POST['email']), time() - 3600, "/");

if ($_FILES['pdp']['error'] !== 4){
    $filename = $_SESSION['id'] . "_user.png";
    $deplacement = '../photo_profil/'; 
    $telechargement = $deplacement . basename($filename);

    if (!move_uploaded_file($_FILES['pdp']['tmp_name'], $telechargement)) {
        header('location: index.php?message=Erreur lors du téléchargement du fichier, votre photo n\'a pas été enregistrée.');
        exit;
    }
    $add = $bdd->prepare('UPDATE UTILISATEUR SET photo_profil = :photo WHERE id_utilisateur = :id');
    $add->execute([
        'photo'=>$filename,
        'id'=>$_SESSION['id']
    ]);
}

$_SESSION['heure_connexion'] = time();

header('location: ../inscription.php?message=Compte crée avec succes !');
exit;

