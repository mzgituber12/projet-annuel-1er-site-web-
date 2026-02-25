<?php 

session_start();

function mdp_oubliLog($pseudo, $email){
    $stream = fopen('../log/mdp_oubli_log.txt', 'a+');
    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' | Adresse email : ' . $email . ', a reinitialisé son mot de passe' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

require '../vendor/autoload.php';
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

error_reporting(E_ALL);
ini_set('display_errors', 1);

include("../bdd.php");

if ((isset($_POST['passchange']) || isset($_POST['passchangeconf'])) && isset($_GET['resetmdp'])){
    if ($_POST['passchange'] != $_POST['passchangeconf']){
        header('location:../mdp_oublie.php?codesuccess&message=les deux mots de passes ne correspondent pas');
        exit;
    }
    if (strlen($_POST['passchange']) < 5){
        header("location:../mdp_oublie.php?codesuccess&message=Le nouveau mot de passe tapé est trop court");
        exit;
    }

    $statement = $bdd->prepare("UPDATE UTILISATEUR SET mot_de_passe = :password WHERE id_utilisateur = :id");
    $statement->execute([
        'password'=>hash('sha512', $_POST['passchange']),
        'id'=>$_SESSION['id_temp']
    ]);

    mdp_oubliLog($_SESSION['pseudotemp'], $_SESSION['emailtemp']);

    session_destroy();
    header("location:../index.php?message=Mot de passe changé, connectez vous pour continuer");
    exit;
}

if (isset($_GET['codeverif']) && isset($_POST['code'])){
    if($_POST['code'] != $_SESSION['token']){
        header('location:../mdp_oublie.php?success&message=Le code saisi est invalide');
        exit;
    }

    $statement = $bdd->prepare("DELETE FROM TOKENS WHERE id_utilisateur = ?");
    $statement->execute([$_SESSION['id_temp']]);

    unset($_SESSION['token']);
    $_SESSION['codesuccess'] = "Oui";

    header('location:../mdp_oublie.php?codesuccess');
    exit;
}

if (!isset($_POST['email_mdp_oubli']) || !isset($_POST['pseudo_mdp_oubli']) || empty($_POST['email_mdp_oubli']) || empty($_POST['pseudo_mdp_oubli'])){
    header('location: ../mdp_oublie.php?message=Tous les champs doivent être remplis');
    exit;
}

$nom = "SELECT pseudo, email, id_utilisateur FROM UTILISATEUR WHERE email = :email";
$statement = $bdd->prepare($nom);
$statement->execute([
    'email'=>$_POST['email_mdp_oubli']
]);
$results = $statement->fetch(PDO::FETCH_ASSOC);

$email = "SELECT email, pseudo FROM UTILISATEUR WHERE pseudo = :pseudo";
$statement2 = $bdd->prepare($email);
$statement2->execute([
    'pseudo'=>$_POST['pseudo_mdp_oubli']
]);
$results2 = $statement2->fetch(PDO::FETCH_ASSOC);

if($results['pseudo'] != $results2['pseudo'] || $results['email'] != $results2['email']){
    header('location: ../mdp_oublie.php?message=Le pseudo et l\'adresse mail ne sont associés à aucun compte, ou ils ne sont pas associés au même compte.');
    exit;
}

function generateToken($length = 20) {
    return bin2hex(random_bytes($length / 2)); 
}

$token = generateToken();
$_SESSION['token'] = $token;
$_SESSION['id_temp'] = $results['id_utilisateur'];

$_SESSION['pseudotemp'] = $results['pseudo'];
$_SESSION['emailtemp'] = $results['email'];

$expiration = date('Y-m-d H:i:s', time() + (5 * 60)); 

$stmt = $bdd->prepare("INSERT INTO TOKENS (value, id_utilisateur, expiration) VALUES (?, ?, ?)");
$stmt->execute([$token, $results['id_utilisateur'], $expiration]);

$mail = new PHPMailer(true);
    try {
        $mail->isSMTP();

        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'battlepast23@gmail.com'; 
        $mail->Password = 'duxw kkku xafh aufx'; 
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS; 
        $mail->Port = 587; 

        $mail->setFrom('battlepast23@gmail.com', 'Reinitialisation de mot de passe');
        $mail->addAddress($results['email']);

        $mail->isHTML(true);
        $mail->Subject = 'Votre code de verification';
        $mail->Body = "Bonjour,<br><br>Votre code de vérification est : <strong>$token</strong><br>Ce code est valable 5 minutes.";

        if ($mail->send()) {
            $_SESSION['email_mdp_oubli'] = $_POST['email_mdp_oubli'];
            header('location: ../mdp_oublie.php?success');
            exit;
        } else {
            echo "Erreur lors de l'envoi de l'email.";
        }

    } catch (Exception $e) {
        echo "Erreur lors de l'envoi de l'email : " . $mail->ErrorInfo;
}

?>