<?php session_start(); 
include_once("theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Document</title>
</head>
<body>
    <?php
    include("header_parametres.php");
    
    if(!isset($_SESSION['pseudo'])){
        header('location:../index.php');
        exit;
    }

    ?>

<div style='margin-left:5px;'>
    <h1>Suggerer du contenu à ajouter</h1>
    <?php if (isset($_GET['message']) && ($_GET['message'] == 'Veuillez remplir tous les champs obligatoires !' || $_GET['message'] == 'La rareté est invalide !' || $_GET['message'] == 'Cet item existe déja !')){
        echo "<h2>" . htmlspecialchars($_GET['message']) . "</h2>";
    }
    ?>
    <form method='post' action='../verification/verif_contenu_add.php'>
        <p> Que souhaitez vous ajouter ? </p>
        <p> Combattant <input name='a' type='radio' value='Combattant' onclick='autres()' checked></p>
        <p> Terrain <input name='a' type='radio' value='Terrain' onclick='autres()'></p>
        <p> Autre chose <input name='a' type='radio' value='Autre chose' onclick='autres()'></p>
        <p> Choisissez son nom : </p>
        <input type='text' name='nom'>
        <div id='rareté'>   
        <p> Choisissez sa rareté (commun, rare, super rare, epique, légendaire) : </p><input type="text" name="rareté">   
        </div>
        <div id='effet'>   
        </div>
        <div id='talent'>
        <p> Choisissez son talent : </p><input type="text" name="talent">   
        </div>
        <div id='precisions'>
        <p> Informations complémentaires (facultatif) : </p><textarea name="infos" rows="12" style="width: 50%;"></textarea>
        </div>
        <br>
        <input type='submit' value='Envoyer'>
    </form>
</div>

        <script>
            function autres(){
                const a = document.getElementsByName('a')
                const x = document.getElementById('rareté')
                const x2 = document.getElementById('effet')
                const x3 = document.getElementById('talent')

                for (let i=0; i<a.length; i++){
                    if (a[i].value != 'Autre chose' && a[i].checked){
                        x.innerHTML = '<p> Choisissez sa rareté (commun, rare, super rare, epique, légendaire) : </p>' + '<input type="text" name="rareté">'
                        x2.innerHTML = ''
                        if (a[i].value == 'Combattant'){
                            x3.innerHTML = '<p> Choisissez son talent : </p><input type="text" name="talent">'
                        } else {
                            x3.innerHTML = ''
                        }
                        break
                    } else {
                        x.innerHTML = ''
                        x2.innerHTML = '<p> Choisissez son effet : </p><input type="text" name="effet">'
                        x3.innerHTML = ''
                    }
                } 
            }

        </script>
     
    </form>
</body>
</html>