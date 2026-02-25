<?php session_start();

if (isset($_GET['message']) && $_GET['message'] === 'sananes'): ?>
  <style>
    .easter-egg {
      position: fixed;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      animation: clignote 0.5s infinite;
      z-index: 9999;
      
    }

    @keyframes clignote {
      0% { 
        opacity: 1; 
    }
      50% { 
        opacity: 0; 
    }
      100% { 
        opacity: 1; 
    }
    }
  </style>
    <div style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <h1 
    style="color: rgb(180, 0, 0);position: fixed;font-size: 3em;text-align: center;">
  Je t'ai attrapé petit tricheur 😈<br> Tu as utilisé ChatGPT !<br>Tu vas devoir me donner 8000 euros<br>et recommencer l'année.</h1>
</div>
  <img src="son_egg/sananes.gif" alt="Sananes" class="easter-egg" width="1800">

  <audio autoplay loop>
    <source src="son_egg/game_over3.mp3" type="audio/mpeg">
  </audio>
  <audio autoplay loop>
    <source src="son_egg/game_over.mp3" type="audio/mpeg">
  </audio>
  <audio autoplay loop>
    <source src="son_egg/game_over4.mp3" type="audio/mpeg">
  </audio>
  <audio autoplay loop>
    <source src="son_egg/game_over2.mp3" type="audio/mpeg">
  </audio>
<?php 
exit;
endif;
include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Boutique</title>
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <style>
    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        font-family: Arial, sans-serif;
        border: 1px solid black;
    }

    th {
        background-color: #4CAF50;
        color: white; 
        padding: 12px;
        text-align: left; 
        font-size: 16px; 
        border: 1px solid black;
    }

    td {
        padding: 10px 15px; 
        text-align: left; 
        border-bottom: 1px solid #ddd;
        border: 1px solid black;
    }

    td:first-child {
        font-weight: bold; 
    }
</style>
</head>
<body>
    <?php

    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }

    include ("bdd.php");
    include("header.php");
    echo '<h1 class="milonga-regular m-4">Boutique</h1>';
    include('getmessage.php');
     
    
    $user = $bdd->prepare("SELECT monnaie, goodies, boutiquetemp1, boutiquetemp2, boutiquetemp3, boutiquetemp1_1, boutiquetemp2_1, boutiquetemp3_1 FROM UTILISATEUR WHERE id_utilisateur = :id");
    $user->execute([
        'id'=>$_SESSION['id']
    ]);
    $results = $user->fetch();
    echo '<h3 style="margin-top:7px; margin-left:13px"> Vous avez ' . $results['monnaie'] . ' Coins et ' . $results['goodies'] . ' Goodies </h3>';    

    echo '<div style="text-align:center">';
    echo '<h3 class="text-center milonga-regular m-4"> Boutique quotidienne </h3>';


    date_default_timezone_set('Europe/Paris');
    
    echo "<h2 id='time'>" . 23-date("H") . "h " . 59-date("i") . "min " . 59 - date("s") . "sec </h2>";
    ?>


    <script>
    function updateTime() {
        const a = document.getElementById('time');
        const currentTime = new Date();
        let hourLeft = 23 - currentTime.getHours();
        const minutesLeft = 59 - currentTime.getMinutes();
        const secondsLeft = 59 - currentTime.getSeconds();

        if (hourLeft == 24){
            hourLeft = 0
        }
        
        a.innerHTML = hourLeft + "h " + minutesLeft + "min " + secondsLeft + "sec ";
    }

    setInterval(updateTime, 1000);
    </script>

    <?php

    if ($results['boutiquetemp1_1'] == 1){
        $coffre1 = 'Commun';
        $price1 = '100';
    }

    if ($results['boutiquetemp2_1'] == 1){
        $coffre2 = 'Commun';
        $price2 = '100';
    } else if ($results['boutiquetemp2_1'] == 2){
        $coffre2 = 'Rare';
        $price2 = '250';
    } else {
        $coffre2 = 'Super Rare';
        $price2 = '600';
    }

    if ($results['boutiquetemp3_1'] == 1){
        $coffre3 = 'Commun';
        $price3 = '100';
    } else if ($results['boutiquetemp3_1'] == 2){
        $coffre3 = 'Rare';
        $price3 = '250';
    } else if ($results['boutiquetemp3_1'] == 3){
        $coffre3 = 'Super Rare';
        $price3 = '600';
    } else if ($results['boutiquetemp3_1'] == 4){
        $coffre3 = 'Epique';
        $price3 = '1700';
    } else {
        $coffre3 = 'Legendaire';
        $price3 = '4000';
    }

    ?>

<script>
    function popup(x, y) {
        const confirmation = confirm(`Voulez-vous vraiment acheter : ${x} pour ${y} coins ?`);
        if (confirmation) {
            window.location.href = `boutique_verif.php?coffre=${encodeURIComponent(x)}`;
        } else {
            return;
        }
    }

    function popup2(x, y, z) {
        const confirmation = confirm(`Voulez-vous vraiment acheter : ${x} pour ${y} coins ?`);
        if (confirmation) {
            window.location.href = `boutique_verif.php?coffre=${encodeURIComponent(x)}&number=${encodeURIComponent(z)}`;
        } else {
            return;
        }
    }
</script>

<?php

$numb1 = "numb1";
$numb2 = "numb2";
$numb3 = "numb3";
$_SESSION['price1'] = $price1;
$_SESSION['price2'] = $price2;
$_SESSION['price3'] = $price3;

echo "<div class='d-flex justify-content-center'>";
if (isset($_SESSION['pseudo']) && $results['boutiquetemp1'] == 0) {
    echo 'Vendu';
} else {
echo "<div style='display:inline-block; margin: 10px; text-align:center;'>
        <img src='coffre_image/{$coffre1}.png' alt='Coffre {$coffre1}' onclick='popup2(\"Coffre {$coffre1}\", {$price1}, \"{$numb1}\")' style='width:150px; cursor:pointer;'>
        <br>{$price1} coins
      </div>";
}

if (isset($_SESSION['pseudo']) && $results['boutiquetemp2'] == 0) {
    echo '<br> Vendu';
} else {
echo "<div style='display:inline-block; margin: 10px; text-align:center;'>
        <img src='coffre_image/{$coffre2}.png' alt='Coffre {$coffre2}' onclick='popup2(\"Coffre {$coffre2}\", {$price2}, \"{$numb2}\")' style='width:150px; cursor:pointer;'>
        <br>{$price2} coins
      </div>";}

if (isset($_SESSION['pseudo']) && $results['boutiquetemp3'] == 0) {
    echo '<br> Vendu';
} else {
    echo "<div style='display:inline-block; margin: 10px; text-align:center;'>
        <img src='coffre_image/{$coffre3}.png' alt='Coffre {$coffre3}' onclick='popup2(\"Coffre {$coffre3}\", {$price3}, \"{$numb3}\")' style='width:150px; cursor:pointer;'>
        <br>{$price3} coins
      </div>";
}
echo "</div>";

$_SESSION['pricegoodies'] = 200 + (200 * $results['goodies']);

echo '<h3 class="text-center milonga-regular m-4"> Boutique permanente </h3>';

echo "<div class='d-flex justify-content-center'>";
echo "<div style='display: inline-block; text-align: center; margin: 10px;'>
  <img src='coffre_image/Commun.png' alt='Coffre Commun' onclick='popup(\"Coffre Commun\", 250)' style='width:150px; cursor:pointer;'>
  <br>250 coins
</div>";

echo "<div style='display: inline-block; text-align: center; margin: 10px;'>
  <img src='coffre_image/Rare.png' alt='Coffre Rare' onclick='popup(\"Coffre Rare\", 600)' style='width:150px; cursor:pointer;'>
  <br>600 coins
</div>";

echo "<div style='display: inline-block; text-align: center; margin: 10px;'>
  <img src='coffre_image/Super Rare.png' alt='Coffre Super Rare' onclick='popup(\"Coffre Super Rare\", 1700)' style='width:150px; cursor:pointer;'>
  <br>1700 coins
</div>";

echo "<div style='display: inline-block; text-align: center; margin: 10px;'>
  <img src='coffre_image/Epique.png' alt='Coffre Epique' onclick='popup(\"Coffre Epique\", 4000)' style='width:150px; cursor:pointer;'>
  <br>4000 coins
</div>";

echo "<div style='display: inline-block; text-align: center; margin: 10px;'>
  <img src='coffre_image/Legendaire.png' alt='Coffre Legendaire' onclick='popup(\"Coffre Legendaire\", 10000)' style='width:150px; cursor:pointer;'>
  <br>10000 coins
</div>
</div>";
echo "<br>";

echo "<button onclick='popup(\"Goodies\", $_SESSION[pricegoodies])'>Goodies</button> -> " . $_SESSION['pricegoodies'] . " coins";




echo '<h3 class="text-center milonga-regular m-4"> Boutique communautaire </h3>';
?>
<form action='boutique_communautaire.php'>
    <input type='submit' value='Vendre une carte' class="btn btn-warning">
</form>
</div>

<script>
function annuler(z, a) {
    if (confirm("Voulez-vous vraiment annuler la vente de cette carte ?")) {
        window.location.href = `boutique_communautaire_verif.php?carte=${a}&annuler&id=${z}`; 
    }
}

function acheter(x, y, z, a, b) {
    if (confirm(`Voulez-vous vraiment acheter : ${x} pour ${y} coins ?`)) {
        window.location.href = `boutique_communautaire_verif.php?achat=${a}&user=${z}&prix=${y}&idvente=${b}`;
    }
}
</script>

<?php 

$commu = $bdd->query("SELECT * FROM vente_carte");
$resultcommu = $commu->fetchall();

echo "<br>
<table>
<tr>
<th>Vendeur</th>
<th>Carte</th>
<th>Prix</th>
<th>Temps restant</th>
<th></th>
</tr>";
foreach ($resultcommu as $result){

    $date1 = new DateTime($result['date_mise_en_vente']); 
    $date2 = new DateTime(); 

    $date1->modify('+24 hours');

    if ($date2 >= $date1) {
        $datetotal = "0 min";
    } else {
        $diff = $date2->diff($date1);

        if ($diff->days == 0 && $diff->h == 0) {
            $datetotal = $diff->i . " min";
        } else {
            $datetotal = $diff->h . " h " . $diff->i . " min";
        }
    }

    $nom=$bdd->prepare("SELECT nom_carte, id_carte FROM CARTE WHERE id_carte = :id");
    $nom->execute([
        'id'=>$result['id_carte']
    ]);
    $nom2 = $nom->fetch();

    $vente=$bdd->prepare("SELECT pseudo FROM UTILISATEUR WHERE id_utilisateur = :id");
    $vente->execute([
        'id'=>$result['id_vendeur']
    ]);
    $vente2 = $vente->fetch();

    if ($_SESSION['id'] == $result['id_vendeur']){
        $x = '<span onclick="annuler(\'' . $result['id_vente'] . '\', \'' . $nom2['id_carte'] . '\')">Annuler</span>';
    } else {
        $x = '<span onclick="acheter(\'' . $nom2['nom_carte'] . '\', ' . $result["prix"] . ', \'' . $vente2['pseudo'] . '\', \'' . $nom2['id_carte'] . '\', \'' . $result['id_vente'] . '\')">Acheter</span>';
    }

    echo "<tr><td>" . $vente2['pseudo'] . '</td><td>' . $nom2['nom_carte'] . '</td><td>' . $result['prix'] . '</td><td>' . $datetotal . '</td><td>' . $x . "</td></tr>";
}
    echo "</table>";

include("footer.php") ?>
</body>
</html>
