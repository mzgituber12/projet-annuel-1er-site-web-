<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reinitialisation du mot de passe</title>
</head>
<body>
<?php session_start();

if (isset($_SESSION['pseudo'])){
header("location:index.php");
exit;
}

    include("header.php");

    echo "<h1>Reinitialisation du mot de passe</h1>";

    if (isset($_GET['success']) && isset($_SESSION['token'])){
       include('getmessage.php');
        echo '<p> Un code de verification a été envoyé a l\'adresse email ' . $_SESSION['email_mdp_oubli'] . '. Veuillez entrer le code pour changer le mot de passe de votre compte</p>';
        echo '<form method="post" action="verification/verif_mdp_oublie.php?codeverif">
        <label>Code de vérification</label>
        <input type="text" name="code">
        <input type="submit" value="Envoyer">
        </form>';
        exit;
    }

    if (isset($_GET['codesuccess']) && isset($_SESSION['codesuccess'])){
        if (isset($_GET['message'])){
            echo '<h4>' . htmlspecialchars($_GET['message']) . '</h4>';
        }
        echo "<form method='post' action='verification/verif_mdp_oublie.php?resetmdp'>
        <label>Choisissez un nouveau mot de passe :</label>
        <input type='password' name='passchange'>
        <label>Repetez le nouveau mot de passe :</label>
        <input type='password' name='passchangeconf'>
        <input type='submit' value='Confirmer'>";
        exit;
    }
    ?>

    <?php if (isset($_GET['message'])){
        echo '<h4>' . htmlspecialchars($_GET['message']) . '</h4>';
    }
    ?>
    <p>Pour pouvoir reinitialiser votre mot de passe, nous avons besoin d'avoir le nom d'utilisateur ainsi que l'adresse email associée à votre compte</p>
    <form method="post" action="verification/verif_mdp_oublie.php">
        <label>Nom d'utilisateur</label>
        <input type="text" id="pseudo_mdp_oubli" name="pseudo_mdp_oubli">
        <label>Adresse email</label>
        <input type="email" id="email_mdp_oubli" name="email_mdp_oubli">
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>