 <?php session_start(); 
include_once("parametres/theme.php");?>

<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Contactez nous</title>
    <link rel="stylesheet" type="text/css" href="styles/stylesupport.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
</head>
<body>

   <?php
    include("header.php");

    echo '<h1 style="margin-left:12px">Contactez Nous</h1>';
    
    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    } 
    
    if (isset($_SESSION['pseudo']) && (!isset($_GET['message']) || $_GET['message'] != 'Votre message a bien été envoyé, nous vous répondrons par mail très prochainement !')){
       echo '<div class=container>';
       echo '<form method="post" action="verification/verif_support.php">';
            echo '<p>Nom d\'utilisateur : ' . $_SESSION['pseudo'] . '</p>';
            echo '<p>Adresse email : ' . $_SESSION['email'] . '</p>';
            echo '<p>Votre message : <br>
            <textarea name="messagerie" required rows="18" style="width: 50%;"></textarea></p>';
            echo '<input type="submit" value="Envoyer">';
        echo '</form>';
    echo '</div>';

    } else {
        echo '<h2 style="margin-left:12px">'.htmlspecialchars($_GET['message']).'</h2>';
        echo '<a style="margin-left:12px" href="index.php">Retour à l\'accueil</a>';
    }

    include("footer.php");
    
?>


</body>
</html>