<?php 
session_start();
include('../bdd.php');
include('avatar_img.php');
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
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Modifier ses informations personnelles</title>
</head>
<body>

<?php

include('header_parametres.php');

if(!isset($_SESSION['pseudo'])){
    header('location:../index.php');
    exit;
}

if (isset($_GET['pseudo'])){

    echo '<h1> Changer le pseudo </h1>';
    if (isset($_GET['pseudo'])){
        echo "<h2>" . htmlspecialchars($_GET['pseudo']) . "</h2>";
    }
    echo '<form method = "post" action = "../verification/verif_parametres_perso.php?user">';
        echo '<div class="user">
                <div class="pseudo">
                    <p class="pseudoactuel">Tapez votre nom d\'utilisateur actuel :</p>
                    <input type="text" id="1" name="pseudoactu" class="pseudoactu">
                </div>
                <div class="pseudo2">
                    <p class="pseudonouveau">Tapez le nouveau nom d\'utilisateur que vous souhaitez avoir :</p>
                    <input type="text" id="2" name="pseudonouv" class="pseudonouv">
                </div>';
             echo '<br><input type="submit" value="Envoyer" class="champ3"></p>';
        echo '</div>';
    echo '</form>';

} 



else if (isset($_GET['email'])){

    echo '<h1> Changer l\'email </h1>';
    if (isset($_GET['email'])){
        echo "<h2>" . htmlspecialchars($_GET['email']) . "</h2>";
    }
    if ($_SESSION['statut'] == 1){
    echo "<h3> Si vous changez votre email, vous devrez à nouveau activer votre compte </h3>";
    }
    echo '<form method = "post" action = "../verification/verif_parametres_perso.php?email">';
        echo '<div class="user">
                <div class="email">
                    <p class="emailactuel">Tapez votre email actuel :</p>
                    <input type="email" id="1" name="emailactu" class="emailactu">
                </div>
                <div class="email2">
                    <p class="emailnouveau">Tapez le nouvel email que vous souhaitez avoir :</p>
                    <input type="email" id="2" name="emailnouv" class="emailnouv">
                </div>';
             echo '<br><input type="submit" value="Envoyer" class="champ3"></p>';
        echo '</div>';
    echo '</form>';
    
} 



else if (isset($_GET['password'])){

    echo '<h1> Changer le mot de passe </h1>';
    if (isset($_GET['password'])){
        echo "<h2>" . htmlspecialchars($_GET['password']) . "</h2>";
    }
    echo '<form method = "post" action = "../verification/verif_parametres_perso.php?password">';
        echo '<div class="user">
                <div class="password">
                    <p class="passwordactuel">Tapez votre mot de passe actuel :</p>
                    <input type="password" id="1" name="passwordactu" class="passwordactu">
                </div>
                <div class="password2">
                    <p class="passwordnouveau">Tapez le nouveau mot de passe que vous souhaitez avoir :</p>
                    <input type="password" id="2" name="passwordnouv" class="passwordnouv">
                </div>
                <div class="password3">
                    <p class="passwordnouveau">Retapez une deuxieme fois le nouveau mot de passe :</p>
                    <input type="password" id="3" name="passwordnouv2" class="passwordnouv2">
                </div>';
             echo '<br><input type="submit" value="Envoyer" class="champ3"></p>';
        echo '</div>';
    echo '</form>';
}



if (isset($_GET['photo'])){

    echo '<h1> Changer la photo de profil </h1>';
    if (isset($_GET['photo'])){
        echo "<h2>" . htmlspecialchars($_GET['photo']) . "</h2>";
    }
    echo '<form method = "post" action = "../verification/verif_parametres_perso.php?photo" enctype="multipart/form-data">';
        echo '<div class="photo">
                <div class="photo">
                    <p class="photo">Choisissez une nouvelle photo de profil :</p>
                    <input type="file" id="4" name="newphoto" accept ="image/png">
                </div>';
                echo '<br><label>Supprimer la photo de profil</label>
                <input type="checkbox" name ="supp_pdp" value="supp">';
             echo '<br><input type="submit" value="Envoyer" class="champ3"></p>';
        echo '</div>';
    echo '</form>';

}

if (isset($_GET['avatar'])){ 
  $query_string = parse_url($avatar_url, PHP_URL_QUERY);
  parse_str($query_string, $params_array);
    ?> 
          <h1 class="text-center m-4">Personnalise ton Avatar</h1>

<img id="avatar-preview" src="<?= htmlspecialchars($avatar_url) ?>" alt="Mon avatar" width="130" class="d-block mx-auto">

  <br>
  <br>
  
  

  <?php
$topType = $params_array['topType'] ?? '';
$eyeType = $params_array['eyeType'] ?? '';
$skinColor = $params_array['skinColor'] ?? '';
$accessoriesType = $params_array['accessoriesType'] ?? '';
$hairColor = $params_array['hairColor'] ?? '';
$facialHairType = $params_array['facialHairType'] ?? '';
$clotheType = $params_array['clotheType'] ?? '';
$mouthType = $params_array['mouthType'] ?? '';
$eyebrowType = $params_array['eyebrowType'] ?? '';
$avatarStyle = $params_array['avatarStyle'] ?? '';
?>

<div class="d-flex align-items-center gap-2 mx-3 mb-4">
  <label>Cheveux :
    <select id="topType" name="topType">
      <option value="ShortHairShortFlat" <?= ($topType === 'ShortHairShortFlat') ? 'selected' : '' ?>>Court plat</option>
      <option value="LongHairStraight" <?= ($topType === 'LongHairStraight') ? 'selected' : '' ?>>Long droit</option>
      <option value="NoHair" <?= ($topType === 'NoHair') ? 'selected' : '' ?>>Sans cheveux</option>
      <option value="ShortHairDreads01" <?= ($topType === 'ShortHairDreads01') ? 'selected' : '' ?>>Dreadlocks courts</option>
      <option value="LongHairCurly" <?= ($topType === 'LongHairCurly') ? 'selected' : '' ?>>Long bouclé</option>
    </select>
  </label>

  <label>Yeux :
    <select id="eyeType" name="eyeType">
      <option value="Happy" <?= ($eyeType === 'Happy') ? 'selected' : '' ?>>Heureux</option>
      <option value="Squint" <?= ($eyeType === 'Squint') ? 'selected' : '' ?>>Plissés</option>
      <option value="Surprised" <?= ($eyeType === 'Surprised') ? 'selected' : '' ?>>Surpris</option>
      <option value="Wink" <?= ($eyeType === 'Wink') ? 'selected' : '' ?>>Clin d'oeil</option>
      <option value="Cry" <?= ($eyeType === 'Cry') ? 'selected' : '' ?>>Triste</option>
    </select>
  </label>

  <label>Couleur de peau :
    <select id="skinColor" name="skinColor">
      <option value="Light" <?= ($skinColor === 'Light') ? 'selected' : '' ?>>Clair</option>
      <option value="Brown" <?= ($skinColor === 'Brown') ? 'selected' : '' ?>>Brun</option>
      <option value="DarkBrown" <?= ($skinColor === 'DarkBrown') ? 'selected' : '' ?>>Foncé</option>
      <option value="Black" <?= ($skinColor === 'Black') ? 'selected' : '' ?>>Noir</option>
      <option value="Tanned" <?= ($skinColor === 'Tanned') ? 'selected' : '' ?>>Bronzé</option>
    </select>
  </label>
</div>

<div class="d-flex align-items-center gap-2 mx-3 mb-4">
  <label>Accessoires :
    <select id="accessoriesType" name="accessoriesType">
      <option value="Blank" <?= ($accessoriesType === 'Blank') ? 'selected' : '' ?>>Aucun</option>
      <option value="Kurt" <?= ($accessoriesType === 'Kurt') ? 'selected' : '' ?>>Lunettes</option>
      <option value="Sunglasses" <?= ($accessoriesType === 'Sunglasses') ? 'selected' : '' ?>>Lunettes de Soleil</option>
      <option value="Prescription02" <?= ($accessoriesType === 'Prescription02') ? 'selected' : '' ?>>Lunettes Classiques</option>
    </select>
  </label>

  <label>Couleur de cheveux :
    <select id="hairColor" name="hairColor">
      <option value="Black" <?= ($hairColor === 'Black') ? 'selected' : '' ?>>Noir</option>
      <option value="Brown" <?= ($hairColor === 'Brown') ? 'selected' : '' ?>>Brun</option>
      <option value="Blonde" <?= ($hairColor === 'Blonde') ? 'selected' : '' ?>>Blond</option>
      <option value="BlondeGolden" <?= ($hairColor === 'BlondeGolden') ? 'selected' : '' ?>>Blond Clair</option>
      <option value="PastelPink" <?= ($hairColor === 'PastelPink') ? 'selected' : '' ?>>Rose Clair</option>
      <option value="Blue" <?= ($hairColor === 'Blue') ? 'selected' : '' ?>>Bleu</option>
      <option value="Red" <?= ($hairColor === 'Red') ? 'selected' : '' ?>>Rouge</option>
      <option value="SilverGray" <?= ($hairColor === 'SilverGray') ? 'selected' : '' ?>>Gris Argenté</option>
      <option value="Auburn" <?= ($hairColor === 'Auburn') ? 'selected' : '' ?>>Auburn</option>
    </select>
  </label>

  <label>Barbe :
    <select id="facialHairType" name="facialHairType">
      <option value="Blank" <?= ($facialHairType === 'Blank') ? 'selected' : '' ?>>Aucune</option>
      <option value="BeardMedium" <?= ($facialHairType === 'BeardMedium') ? 'selected' : '' ?>>Barbe Moyenne</option>
      <option value="BeardLight" <?= ($facialHairType === 'BeardLight') ? 'selected' : '' ?>>Barbe Courte</option>
      <option value="BeardMajestic" <?= ($facialHairType === 'BeardMajestic') ? 'selected' : '' ?>>Barbe Longue</option>
      <option value="MoustacheFancy" <?= ($facialHairType === 'MoustacheFancy') ? 'selected' : '' ?>>Moustache</option>
    </select>
  </label>
</div>

<div class="d-flex align-items-center gap-2 mx-3 mb-4">
  <label>Vêtement :
    <select id="clotheType" name="clotheType">
      <option value="BlazerShirt" <?= ($clotheType === 'BlazerShirt') ? 'selected' : '' ?>>Costume</option>
      <option value="Overall" <?= ($clotheType === 'Overall') ? 'selected' : '' ?>>Salopette</option>
      <option value="CollarSweater" <?= ($clotheType === 'CollarSweater') ? 'selected' : '' ?>>Chemisette</option>
      <option value="Hoodie" <?= ($clotheType === 'Hoodie') ? 'selected' : '' ?>>Sweat à capuche</option>
      <option value="GraphicShirt" <?= ($clotheType === 'GraphicShirt') ? 'selected' : '' ?>>T-shirt graphique</option>
    </select>
  </label>

  <label>Bouche :
    <select id="mouthType" name="mouthType">
      <option value="Eating" <?= ($mouthType === 'Eating') ? 'selected' : '' ?>>Mange</option>
      <option value="Tongue" <?= ($mouthType === 'Tongue') ? 'selected' : '' ?>>Tire la langue</option>
      <option value="Grimace" <?= ($mouthType === 'Grimace') ? 'selected' : '' ?>>Grimace</option>
      <option value="Smile" <?= ($mouthType === 'Smile') ? 'selected' : '' ?>>Sourire</option>
      <option value="Serious" <?= ($mouthType === 'Serious') ? 'selected' : '' ?>>Sérieux</option>
    </select>
  </label>

  <label>Sourcils :
    <select id="eyebrowType" name="eyebrowType">
      <option value="Default" <?= ($eyebrowType === 'Default') ? 'selected' : '' ?>>Classique</option>
      <option value="Angry" <?= ($eyebrowType === 'Angry') ? 'selected' : '' ?>>En colère</option>
      <option value="FlatNatural" <?= ($eyebrowType === 'FlatNatural') ? 'selected' : '' ?>>Naturel</option>
      <option value="RaisedExcited" <?= ($eyebrowType === 'RaisedExcited') ? 'selected' : '' ?>>Étonné</option>
      <option value="SadConcerned" <?= ($eyebrowType === 'SadConcerned') ? 'selected' : '' ?>>Triste</option>
    </select>
  </label>
</div>

<div class="d-flex align-items-center gap-2 mx-3 mb-4">
  <label>Fond derrière l'avatar :
    <select id="avatarStyle" name="avatarStyle">
      <option value="Circle" <?= ($avatarStyle === 'Circle') ? 'selected' : '' ?>>Avec cercle</option>
      <option value="Transparent" <?= ($avatarStyle === 'Transparent') ? 'selected' : '' ?>>Sans fond</option>
    </select>
  </label>
</div>




<form action="../verification/verif_parametres_perso.php?avatar" method="get" class="mx-5">
  <input type="hidden" name="message" value="urlnew">
  <input type="hidden" id="nouvurl" name="avatarurl">
  <br>
  <input type="submit" value="Enregistrer l'avatar" class="btn btn-outline-success">
</form>


<script src="avatar.js"></script>
<?php }

include("footer_parametres.php");

?>

</body>
</html>