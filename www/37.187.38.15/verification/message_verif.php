<?php session_start();

if (!isset($_SESSION['id'])) {
    header('location:../index.php');
    exit;
}

if ((!isset($_POST['msg']) || empty($_POST['msg'])) && (!isset($_FILES['fichier']) || $_FILES['fichier']['error'] == 4)){
    header('location:../discussion.php?message=Vous n\'avez selectionné aucun fichier et le message envoyé ne peut pas être vide&user=' . $_GET['user'] );
    exit;
}

include('../bdd.php');

$inserermsg = $bdd->prepare('INSERT INTO message (contenu, id_utilisateur_envoyeur, id_utilisateur_destinataire, type, id1vu, id2vu) VALUES (:contenu, :id1, :id2, :type, 1, 0)');

if (!empty($_POST['msg'])){
$inserermsg->execute([
    'contenu'=>$_POST['msg'],
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['id'],
    'type'=>'texte'
]);
}

if (!empty($_FILES['fichier']) && $_FILES['fichier']['error'] == 0){

    $acceptable =['image/png', 'image/jpeg', 'image/gif'];
    if(!in_array($_FILES['fichier']['type'], $acceptable)){
        header('location:../discussion.php?message=Seuls les fichiers JPG, PNG et GIF sont autorisés&user=' . $_GET['user'] );
        exit;
        }
    $maxSize = 4000 * 1024;
    if($_FILES['fichier']['size'] > $maxSize){
        header('location:../discussion.php?message=Le fichier que vous souhaitez envoyer est trop grand&user=' . $_GET['user'] );
        exit;
    }

    $inserermsg->execute([
        'contenu'=>'x',
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['id'],
        'type'=>'image'
    ]);

    $fileInfo = pathinfo($_FILES['fichier']['name']);
    $extension = strtolower($fileInfo['extension']);

    $filename = $bdd->lastInsertId() . "_discut." . $extension;
    $deplacement = '../message_image/'; 
    $telechargement = $deplacement . basename($filename);

    if (!move_uploaded_file($_FILES['fichier']['tmp_name'], $telechargement)) {
        header('location:../discussion.php?message=Erreur, votre fichier n\'a pas été enregistré sur le serveur&user=' . $_GET['user'] );
        exit;
    }
    $add = $bdd->prepare('UPDATE MESSAGE SET contenu = :content WHERE id_message = :id');
    $add->execute([
        'content'=>$filename,
        'id'=>$bdd->lastInsertId()
    ]);
}

header('location:../discussion.php?user=' . $_GET['user'] );
exit;
