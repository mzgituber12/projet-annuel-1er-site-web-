<?php session_start();
include_once("theme.php");
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styletext.css">
    <title >Changer ses informations personelles</title>
</head>
<body>
    <?php
    
    include('header_parametres.php');

if(!isset($_SESSION['pseudo'])){
    header('location:../index.php');
    exit;
}

    echo "<h1 class='text-center m-4 milonga-regular'>Changer mes informations personnelles</h1>";

    include("../bdd.php");

    if (isset($_GET['message'])){
        echo "<h2>" . $_GET['message'] . "</h2>";
    }

    $user="SELECT * FROM UTILISATEUR WHERE pseudo=:pseudo";
    $statement = $bdd->prepare($user);
    $statement->execute(['pseudo'=>$_SESSION['pseudo']]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);

    echo "<div style='margin-left:23px; margin-top:5px;'>";
    echo "<p>Nom d'utilisateur : " . $result['pseudo'];
    echo "<br><a href='parametres_perso_modif.php?pseudo'>Modifier le nom d'utilisateur</a></p>";
    echo "<p>Adresse email : " . $result['email'];
    echo "<br><a href='parametres_perso_modif.php?email'>Modifier l'email</a></p>";
    echo "<p>Mot de passe :";
    echo "<br><a href='parametres_perso_modif.php?password'>Modifier le mot de passe</a></p>";
    echo "<p>Photo de profil : ";

    if (!empty($result['photo_profil'])){
        echo "<img src='../photo_profil/" . $result['photo_profil'] . "' alt='Photo de profil' width='40' style='border-radius: 50%'>";
    } else {
        echo "Pas de photo de profil";
    }

    echo "<br><a href='parametres_perso_modif.php?photo'>Modifier la photo de profil</a></p>";
    echo "<p>Avatar :";
    echo "<br><a href='parametres_perso_modif.php?avatar'>Modifier l'avatar</a></p>";
    echo "</div>";
    ?>
    
<?php include("footer_parametres.php"); ?>
</body>
</html>