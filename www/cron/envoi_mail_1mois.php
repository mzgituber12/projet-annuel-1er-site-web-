<?php 
include("/var/www/37.187.38.15/bdd.php");

$dateconnexion = date('Y-m-d H:i:s', strtotime('-1 month'));

require ('/var/www/37.187.38.15/vendor/autoload.php');
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

    $allusers = $bdd->prepare('SELECT pseudo, email FROM utilisateur WHERE derniere_connexion <= :connexion AND abonne = 1 AND ban = 0 AND statut = 1 AND mailenvoyé = 0');
    $allusers->execute([
        'connexion'=>$dateconnexion
    ]);
    $users = $allusers->fetchall();

    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = 'battlepast23@gmail.com';
    $mail->Password = 'duxw kkku xafh aufx';
    $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    $mail->Port = 587;
    $mail->setFrom('battlepast23@gmail.com', 'BATTLEPAST NEWSLETTER');

    foreach($users as $user){

        $update = $bdd->prepare('UPDATE utilisateur SET mailenvoyé = 1 WHERE pseudo = :pseudo');
        $update->execute(['pseudo'=>$user['pseudo']]);

    $mail->isHTML(true);
    $mail->Subject = $user['email'] . ', revenez sur Battlepast';
    $mail->Body = '<h1>' . $user['email'] . '<h1>' .
                '<p> Ca fait 1 mois que vous n\'etes pas revenu sur notre site, nous vous attendons sur Battlepast </p>' ;

        $mail->addAddress($user['email']);
        $mail->send();
        $mail->clearAddresses();
    }
    
?>