<?php session_start();
include('../pasadmin.php');

require __DIR__ . '/../../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[3]) || !isset($comm[2])){
    header("location:../indexad.php?message=Commande erronée");
    exit;
}

include("../../bdd.php");

$user = $bdd->prepare("SELECT role, ban, id_utilisateur, email FROM utilisateur WHERE pseudo = :pseudo");
$user->execute([
    'pseudo'=>$comm[2]
]);
$result = $user->fetch(PDO::FETCH_ASSOC);

if (!$result){
    header("location:../indexad.php?message=Utilisateur introuvable");
    exit;
}




if($comm[1] == 'ban'){

if ($result['ban'] == 1){
    header("location:../indexad.php?message=Utilisateur deja banni");
    exit;
}
if ($comm[2] == 'admin'){
    header("location:../indexad.php?message=Vous ne pouvez pas vous bannir vous même");
    exit;
}
if ($result['role'] == 'admin'){
    header("location:../indexad.php?message=Vous ne pouvez pas bannir un utilisateur admin");
    exit;
}

$verif = $bdd->prepare("UPDATE utilisateur SET ban = 1 WHERE pseudo = :pseudo");
$verif->execute([
    'pseudo'=>$comm[2]
]);



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
$mail->Subject = 'Bannissement de Battlepast';
$mail->Body = '<h1>' . $comm[2] . ', vous avez été banni du site Battlepast</h1>'
                . '<p>Nous sommes navrés de vous annoncer que vous avez été banni du site Battlepast le ' . date('d/m/Y')  . '<br>
                Vous pouvez demander un debanissement de notre site en vous connectant à votre compte et en cliquant sur
                le lien qui vous dirigera vers un formulaire à remplir pour proceder a une reverification manuelle et complète de votre
                compte </p>
                <p><a href="https://37.187.38.15/">Cliquez ici pour demander un debanissement</a></p>
                <h3>L\'équipe de Battlepast</h3>';
    
$mail->addAddress($result['email']);
$mail->send();
$mail->clearAddresses();





$verif = $bdd->prepare("INSERT INTO account_statut (id_utilisateur, contenu) VALUES (:id, :content)");
$verif->execute([
    'id'=>$result['id_utilisateur'],
    'content'=>'L\'utilisateur ' . $comm[2] . ' a été banni du site'
]);

if ($_SESSION['profilcomm'] == 'xx'){
    $_SESSION['profilcomm'] = '';
    header('location:../../profil.php?user=' . $comm[2] . '&message=Compte banni avec succes');
    exit;
    }

header("location:../indexad.php?message=Commande effectuée avec succes");
exit;
}




if($comm[1] == 'deban'){

    if ($result['ban'] == 0){
        header("location:../indexad.php?message=Utilisateur pas banni");
        exit;
    }
    
    $verif = $bdd->prepare("UPDATE utilisateur SET ban = 0 WHERE pseudo = :pseudo");
    $verif->execute([
        'pseudo'=>$comm[2]
    ]);



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
    $mail->Subject = 'Debannissement de Battlepast';
    $mail->Body = '<h1>' . $comm[2] . ', vous avez été débanni du site Battlepast </h1>'
                . '<p>Nous sommes heureux de vous annoncer que vous avez été débanni du site Battlepast le ' . date('d/m/Y')  . '<br>
                Nous sommes navrés que vous n\'ayez pas pu utiliser le site pendant un certain temps, nous devons effectuer ce genre d\'action pour proteger notre communauté</p>
                <p><a href="https://37.187.38.15/">Cliquez ici pour acceder au site</a></p>
                <h3>L\'équipe de Battlepast</h3>';
    
    $mail->addAddress($result['email']);
    $mail->send();
    $mail->clearAddresses();



    if ($_SESSION['profilcomm'] == 'xx'){
        $_SESSION['profilcomm'] = '';
        header('location:../../profil.php?user=' . $comm[2] . '&message=Compte debanni avec succes');
        exit;
        }
    
    header("location:../indexad.php?message=Commande effectuée avec succes");
    exit;
}