<?php session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

function changerpseudoLog($pseudo, $pseudo2){
    $stream = fopen('../log/changer_pseudo_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo2 . ' a changé son nom d\'utilisateur : Ancien nom d\'utilisateur : ' . $pseudo . " | Nouveau nom d'utilisateur : " . $pseudo2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function changeremailLog($email, $email2){
    $stream = fopen('../log/changer_email_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $_SESSION['pseudo'] . ' a changé son email : Ancien email ' . $email . " | Nouvel email : " . $email2 . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function changerpasswordLog(){
    $stream = fopen('../log/changer_password_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $_SESSION['pseudo'] . ' a changé son mdp : ' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

function changerphotoLog(){
    $stream = fopen('../log/changer_photo_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $_SESSION['pseudo'] . ' a changé sa photo de profil : ' . "\n";
    fputs($stream, $line);
    fclose($stream);
}

include("../bdd.php");

if (isset($_GET['user'])){

    if (!isset($_POST['pseudoactu']) || empty($_POST['pseudoactu']) ||  !isset($_POST['pseudonouv']) || empty($_POST['pseudonouv'])){
        header("location:../parametres/parametres_perso_modif.php?pseudo=Au moins un des deux champs est vide");
        exit;
    }

    if ($_POST['pseudoactu'] !== $_SESSION['pseudo']){
        header("location:../parametres/parametres_perso_modif.php?pseudo=Votre pseudo actuel est incorrect");
        exit;
    }

    $allusers ='SELECT id_utilisateur FROM UTILISATEUR WHERE pseudo = :pseudo';
    $statement = $bdd->prepare($allusers);
    $statement -> execute([
        'pseudo' => $_POST['pseudonouv']
    ]);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)){
        header("location:../parametres/parametres_perso_modif.php?pseudo=Le nouveau pseudo souhaité est deja utilisé");
        exit;
    }

    $changerpseudo = "UPDATE UTILISATEUR SET pseudo=:pseudo WHERE email=:email";
    $statement = $bdd->prepare($changerpseudo);
    $statement -> execute([
        'pseudo' => $_POST['pseudonouv'],
        'email' => $_SESSION['email'],
    ]);

    changerpseudoLog($_SESSION['pseudo'], $_POST['pseudonouv']);

    $_SESSION['pseudo'] = $_POST['pseudonouv'];

    header("location:../parametres/parametres_perso.php?message=Pseudo changé");
    exit;

}



else if (isset($_GET['email'])){

    if (!isset($_POST['emailactu']) || empty($_POST['emailactu']) ||  !isset($_POST['emailnouv']) || empty($_POST['emailnouv'])){
        header("location:../parametres/parametres_perso_modif.php?email=Au moins un des deux champs est vide");
        exit;
    }

    if ($_POST['emailactu'] !== $_SESSION['email']){
        header("location:../parametres/parametres_perso_modif.php?email=Votre email actuel est incorrect");
        exit;
    }

    $allusers ='SELECT id_utilisateur FROM UTILISATEUR WHERE email = :email';
    $statement = $bdd->prepare($allusers);
    $statement -> execute([
        'email' => $_POST['emailnouv']
    ]);
    $results = $statement->fetchAll(PDO::FETCH_ASSOC);

    if (!empty($results)){
        header("location:../parametres/parametres_perso_modif.php?email=Le nouvel email souhaité est deja utilisé");
        exit;
    }

    $changerpseudo = "UPDATE UTILISATEUR SET email=:email WHERE pseudo=:pseudo";
    $statement = $bdd->prepare($changerpseudo);
    $statement -> execute([
        'email' => $_POST['emailnouv'],
        'pseudo' => $_SESSION['pseudo'],
    ]);

    changeremailLog($_SESSION['email'], $_POST['emailnouv']);

    $_SESSION['email'] = $_POST['emailnouv'];

    header("location:../parametres/parametres_perso.php?message=Email changé");
    exit;

}



else if (isset($_GET['password'])){

    if (!isset($_POST['passwordactu']) || empty($_POST['passwordactu']) ||  !isset($_POST['passwordnouv']) || empty($_POST['passwordnouv'])){
        header("location:../parametres/parametres_perso_modif.php?password=Au moins un des trois champs est vide");
        exit;
    }

    if ($_POST['passwordactu'] !== $_SESSION['mdp']){
        header("location:../parametres/parametres_perso_modif.php?password=Votre mot de passe actuel est incorrect");
        exit;
    }

    if (strlen($_POST['passwordnouv']) < 5){
        header("location:../parametres/parametres_perso_modif.php?password=Le nouveau mot de passe tapé est trop court");
        exit;
    }

    $test_lettre = 0;
for ($i = 0; $i < strlen($_POST['passwordnouv']); $i++) {
    if (ctype_alpha($_POST['passwordnouv'][$i])) {
        $test_lettre = 1;
    }
}
if ($test_lettre == 0){
    header("location:../parametres/parametres_perso_modif.php?message=Le mot de passe tapé manque d'au moins une lettre, choisissez un mot de passe plus long.");
    exit;
}

    if ($_POST['passwordnouv'] !== $_POST['passwordnouv2']){
        header("location:../parametres/parametres_perso_modif.php?password=Les deux mots de passes ne correspondent pas");
        exit;
    }

    $changermdp = "UPDATE UTILISATEUR SET mot_de_passe=:mdp WHERE pseudo=:pseudo AND email=:email";
    $statement = $bdd->prepare($changermdp);
    $statement -> execute([
        'mdp' => hash('sha512', $_POST['passwordnouv']),
        'pseudo' => $_SESSION['pseudo'],
        'email' => $_SESSION['email'],
    ]);

    changerpasswordLog($_SESSION['pseudo']);

    session_destroy();

    header("location:../index.php?message=Mot de passe changé, veuillez vous reconnecter !");
    exit;

}

else if (isset($_GET['message']) && $_GET['message'] === 'urlnew') {

    if (empty($_GET['avatarurl'])){
        header("location:../parametres/parametres.php?message=L'avatar a été inchangé");
        exit;
    }

        $avanew = $_GET['avatarurl'];
        $id = $_SESSION['id'];

        $updateAvatar = "UPDATE utilisateur SET avatar = :avatar WHERE id_utilisateur = :id";
        $statement = $bdd->prepare($updateAvatar);
        $statement->execute([
            'avatar' => $avanew,
            'id' => $id
        ]);
        header("location:../parametres/parametres.php?message=Avatar changé avec succes");
        exit;
}



else if (isset($_GET['photo'])){

    if (isset($_POST['supp_pdp']) && $_POST['supp_pdp'] == 'supp'){
        unlink('../photo_profil/' . $_SESSION['id'] . '_user.png');
        $suppimage = $bdd->prepare("UPDATE UTILISATEUR SET photo_profil = :photo WHERE id_utilisateur=:id");
        $suppimage->execute([
        'photo'=>'',
        'id' =>$_SESSION['id']
    ]);
    header("location:../profil.php?user=$_SESSION[pseudo]&message=Photo de profil supprimée");
    exit;
    }

    if ($_FILES['newphoto']['error'] == 4){
        header("location:../parametres/parametres_perso.php?message=Photo de profil inchangée");
        exit;
    }

    $acceptable =['image/png'];
    if(!in_array($_FILES['newphoto']['type'], $acceptable)){
        header('location: ../parametres/parametres_perso_modif.php?photo=L\'image doit etre un png');
        exit;
    }

    $maxSize = 500 * 1024;
    if($_FILES['newphoto']['size']> $maxSize){
        header('location: ../parametres/parametres_perso_modif.php?photo=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }

    $nomimage = $bdd->prepare("SELECT id_utilisateur FROM UTILISATEUR WHERE id_utilisateur=:id");
    $nomimage->execute([
        'id' =>$_SESSION['id']
    ]);
    $results = $nomimage->fetch();
    $filename = $results['id_utilisateur'] . "_user.png"; 

    $deplacement = '../photo_profil/'; 
    $telechargement = $deplacement . basename($filename);

    if (!move_uploaded_file($_FILES['newphoto']['tmp_name'], $telechargement)) {
        header('location: ../parametres/parametres_perso_modif.php?photo=Erreur lors du téléchargement du fichier.');
        exit;
    }

    $query = $bdd->prepare("UPDATE UTILISATEUR SET photo_profil = :photo WHERE id_utilisateur=:id");
        $query->execute([
        'photo'=>$filename,
        'id'=>$_SESSION['id']
    ]);

    changerphotoLog($_SESSION['pseudo']);

    header("location:../profil.php?user=$_SESSION[pseudo]&message=Photo de profil changée");
    exit;

}