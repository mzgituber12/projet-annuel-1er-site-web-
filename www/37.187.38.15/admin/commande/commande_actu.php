<?php 
session_start();
include('../pasadmin.php');
include('../../bdd.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

require __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

if (isset($_GET['creer']) && (isset($_POST['titrea']) || isset($_POST['contenuea']))){

    if (empty($_POST['titrea']) || empty($_POST['contenuea'])){
        header('location: commande_actu.php?message=L\'actualité doit admettre au moins un titre et un contenu');
        exit;
    }
    $titrea = $_POST['titrea'];
    $contenuea = $_POST['contenuea'];
    
     $statement = $bdd->prepare("INSERT INTO ACTUALITE (titre, contenu) VALUES (?, ?)");
     $statement->execute([$titrea, $contenuea]);
    
     $id_actualite = $bdd->lastInsertId();
    
    
     $statement = $bdd->query("SELECT id_utilisateur FROM UTILISATEUR WHERE abonne = 1");
     $abonnes = $statement->fetchAll(PDO::FETCH_COLUMN);

     if(isset($_FILES['imagea']) && $_FILES['imagea']['error'] == 0){

        $acceptable =[
            'image/jpeg',
            'image/png',
            'image/gif'
        ];
        if(!in_array($_FILES['imagea']['type'], $acceptable)){
            header('location: commande_actu.php?message=L\'image doit etre un png, jpeg ou gif');
            exit;
        }
    $maxSize = 500 * 1024;
    if($_FILES['imagea']['size']> $maxSize){
        header('location: commande_actu.php?message=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }
    $file_info = pathinfo($_FILES['imagea']['name']);
    $filename = $id_actualite . 'actu.' . $file_info['extension'];

    $deplacement = '../../imageactu/'; 
    $telechargement = $deplacement . basename($filename);
    
    if (!move_uploaded_file($_FILES['imagea']['tmp_name'], $telechargement)) {
        header('location: commande_actu.php?message=Erreur lors de du téléchargement du fichier.');
        exit;
    }

    $photo = $bdd->prepare('UPDATE actualite SET image = :image WHERE id_actualite = :id');
    $photo->execute([
        'image'=>$filename,
        'id'=>$id_actualite
    ]);
    }

     $statement = $bdd->prepare("INSERT INTO ENVOI_ACTUALITE (id_actualite, id_utilisateur) VALUES (?, ?)");
     foreach ($abonnes as $id_utilisateur) {
         $statement->execute([$id_actualite, $id_utilisateur]);
     }
    
    $statement = $bdd->query("SELECT email FROM UTILISATEUR WHERE abonne = 1");
    $abonnes_emails = $statement->fetchAll(PDO::FETCH_COLUMN);
    
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'battlepast23@gmail.com';
    $mail->Password = 'duxw kkku xafh aufx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('battlepast23@gmail.com', 'BATTLEPAST NEWSLETTER');
    
    $mail->isHTML(true);
    $mail->Subject = 'Nouvelle actualité : ' . $titrea;
    $mail->Body = '<h1>' . htmlspecialchars($titrea) . '</h1>'
                . '<p>' . nl2br(htmlspecialchars($contenuea)) . '</p>'
                . '<img src="https://37.187.38.15/imageactu/' . htmlspecialchars($filename) . '" style="max-width:100%;">';
    
    foreach ($abonnes_emails as $email) {
        $mail->addAddress($email);
        $mail->send();
        $mail->clearAddresses();
    }
    header('location: ../indexad.php?message=Commande effectuée avec succes');
    exit;
}




if ($comm[2] == 'creer'){

    if (isset($comm[3])){
        header('location: ../indexad.php?message=Commande erronée');
        exit();
    }

include('headerad_com.php')

?> <h1>Creer une actualité</h1>

<?php include('../../getmessage.php') ?>

<form method="post" action="commande_actu.php?creer" enctype ="multipart/form-data">     
    <p><label>Inserer le titre de l'actualité</label>
    <input type ="text" name="titrea"></p>
    <p><label>Inserer le contenu de l'actualité</label>
    <input type ="text" name="contenuea"></p>
    <p><label>Inserer l'image de l'actualité</label>
    <input type ="file" name="imagea" accept ="image/png, image/jpeg, image/gif"></p>
    <input type="submit">
</form> <?php

exit;
}




if (isset($_GET['modifier'])){

    if(empty($_POST['titrea']) && empty($_POST['contenuea']) && $_POST['imagedelete'] != 'none' && $_FILES['imagea']['error'] == 4){
        header('location: ../indexad.php?message=Actualité inchangée');
        exit;
    }

    $titrea = $_POST['titrea'];
    $contenuea = $_POST['contenuea'];
    
    if(isset($_FILES['imagea']) && $_FILES['imagea']['error'] == 0){

        $acceptable =[
            'image/jpeg',
            'image/png',
            'image/gif'
        ];
        if(!in_array($_FILES['imagea']['type'], $acceptable)){
            header('location: commande_actu.php?message=L\'image doit etre un png, jpeg ou gif');
            exit;
        }
    $maxSize = 500 * 1024;
    if($_FILES['imagea']['size']> $maxSize){
        header('location: commande_actu.php?message=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }

    $deleteimage = $bdd->query("SELECT image FROM actualite WHERE id_actualite = $comm[3]");
    $print=$deleteimage->fetch();

    unlink('../../imageactu/' . $print['image']);

    $file_info = pathinfo($_FILES['imagea']['name']);
    $filename = $comm[3] . 'actu.' . $file_info['extension'];
    $deplacement = '../../imageactu/'; 
    $telechargement = $deplacement . basename($filename);
    
    if (!move_uploaded_file($_FILES['imagea']['tmp_name'], $telechargement)) {
        header('location: commande_actu.php?message=Erreur lors de du téléchargement du fichier.');
        exit;
    }
    }
    
    if (!empty($_POST['titrea'])){
        $v = 'titre';
        $x = $_POST['titrea'];
        include ("commande_actu_2.php");
    }
    if (!empty($_POST['contenuea'])){
        $v = 'contenu';
        $x = $_POST['contenuea'];
        include ("commande_actu_2.php");
    }
    if($_FILES['imagea']['error'] == 0){
        $v = 'image';
        $x = $filename;
        include ("commande_actu_2.php");
    }

    if (isset($_POST['imagedelete']) && $_POST['imagedelete'] == 'none'){
        unlink('../../imageactu/' . $filename);
        $v = 'image';
        $x = '';
        include ("commande_actu_2.php");
    }
    
    header('location: ../indexad.php?message=Commande effectuée avec succes');
    exit;
}





if ($comm[2] == 'modifier'){
    
    if (!isset($comm[3])){
        header('location: ../indexad.php?message=Commande erronée');
        exit();
    }

    $select = $bdd->prepare("SELECT * FROM actualite WHERE id_actualite = ?");
    $select->execute([
        $comm[3]
    ]);
    $results = $select->fetch();

    include('headerad_com.php')

?> <h1>Modification de l'actualite <?= $comm[3] ?></h1>

<h3> Laissez vide si vous ne souhaitez pas modifier les parties correspondantes </h3>

<?php include('../../getmessage.php');

if ($results['image']){

    $image = "<br><img src='../../imageactu/" . $results['image'] . "' style='max-width:360px; height:auto;'>";
} else {
    $image = "Aucune image pour cette actualité";
}

echo '<h3> Données actuelles concernant l\'actualité </h3><p>Titre actuel : ' . $results['titre'] . '</p>
<p>Contenu actuel : ' . $results['contenu'] . '</p>
<p>Image actuelle : ' . $image . '</p>' ?>

<h3> Nouvelles données concernant l'actualité </h3>
<form method="post" action="commande_actu.php?modifier" enctype ="multipart/form-data">     
    <p><label>Inserer le nouveau titre de l'actualité</label>
    <input type ="text" name="titrea"></p>
    <p><label>Inserer le nouveau contenu de l'actualité</label>
    <input type ="text" name="contenuea"></p>
    <p><label>Inserer la nouvelle image de l'actualité</label>
    <input type ="file" name="imagea" accept ="image/png, image/jpeg, image/gif"></p>
    <p><label>Supprimer l'image ?</label>
    <input type ="checkbox" name="imagedelete" value="none"></p>
    <input type="submit">
</form> <?php
exit;
}



if ($comm[2] == 'supprimer'){

    if (!isset($comm[3])){
        header('location: ../indexad.php?message=Commande erronée');
        exit();
    }

    $deleteimage = $bdd->query("SELECT image FROM actualite WHERE id_actualite = $comm[3]");
    $print=$deleteimage->fetch();

    unlink('../../imageactu/' . $print['image']);

    $stmt = $bdd->prepare("DELETE FROM ACTUALITE WHERE id_actualite = :id_actu");
    $stmt->execute(['id_actu' => $comm[3]]);
    header('location: ../indexad.php?message=Commande effectuée avec succes');
    exit();
}

header('location: ../indexad.php?message=Commande erronée.');
exit;