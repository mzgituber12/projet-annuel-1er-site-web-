<?php
session_start();


$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');

$id_utilisateur = $_SESSION['id'];
$id_combat = $_GET['id_combat'] ?? null;
$_SESSION['id_combat'] = $id_combat;



if (!$id_combat || !is_numeric($id_combat)) die("ID de combat invalide");

include("fonction_combat.php");

$requete = $bdd->prepare("SELECT id_joueur1, id_joueur2, tour_actuel, statut, degats FROM combat WHERE id_combat = ?");
$requete->execute([$id_combat]);
$resultat = $requete->fetch(PDO::FETCH_ASSOC);

$id_joueur1 = $resultat['id_joueur1'];
$id_joueur2 = $resultat['id_joueur2'];

if($resultat['tour_actuel'] ==1) initialiser_combat($id_combat, $id_joueur1, $id_joueur2, $bdd);

$combat_data = json_decode(file_get_contents("donnees_combat/combat_$id_combat.json"), true);

$infos_joueur1 = $combat_data['joueurs']['1'];
$infos_joueur2 = $combat_data['joueurs']['2'];
$carte_active_joueur1 = definir_carte_active($infos_joueur1['cartes']);
$carte_active_joueur2 = definir_carte_active($infos_joueur2['cartes']);

$fond_terrain_joueur1 = trouver_terrain($infos_joueur1['cartes']);
$fond_terrain_joueur2 = trouver_terrain($infos_joueur2['cartes']);

$nom_joueur1 = htmlspecialchars($infos_joueur1['pseudo']);
$nom_joueur2 = htmlspecialchars($infos_joueur2['pseudo']);

if ($carte_active_joueur1 != null){
if (!isset($carte_active_joueur1['attaques'][0]) || !isset($carte_active_joueur1['attaques'][1])) {
    die("Attaques non définies pour la carte active !");
}}

$image_carte1 = $carte_active_joueur1['image'];
$image_carte2 = $carte_active_joueur2['image'];

$attaque1_nom = $carte_active_joueur1['attaques'][0]['nom'];
$attaque2_nom = $carte_active_joueur2['attaques'][1]['nom'];

$tour_actuel = (int) $combat_data['tour_actuel'];

$resultat1 = $_SESSION['result'];
$resultat2 = $_SESSION['result2'];


?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Combat</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: sans-serif;
      margin: 0;
      /*background-color: #111;
      color: white;*/
    }

    .combat-container {
      width: 100%;
      max-width: 900px;
      margin: auto;
      padding-top: 20px;
    }

    .nom-joueurs {
      display: flex;
      justify-content: space-between;
      margin: 0 20px;
      font-size: 1.2em;
      font-weight: bold;
    }

    .terrain {
      display: flex;
      height: 300px;
      border: 3px solid #444;
      background-size: cover;
      background-position: center;
      position: relative;
    }

    .terrain .zone-joueur {
      width: 50%;
      display: flex;
      align-items: center;
      position: relative;
      background-size: cover;
      background-position: center;
    }
    .zone-joueur {
    display: flex;
    flex-direction: column;
    align-items: center; 
    justify-content: start; 
    padding: 10px;
    }
    .zone-joueur p {
    margin-bottom: 10px;
    font-weight: bold;
    color: white; 
    text-shadow: 1px 1px 2px black; 
    }


    .zone-joueur img.carte {
      height: 150px;
      border-radius: 10px;
      box-shadow: 0 0 10px black;
    }

    .actions {
      background-color: #222;
      border-top: 2px solid #555;
      padding: 15px;
      text-align: center;
    }

    .actions h2 {
      margin-bottom: 10px;
    }

    .actions button {
      background-color: #444;
      color: white;
      border: none;
      padding: 10px 20px;
      margin: 10px;
      font-size: 1em;
      border-radius: 8px;
      cursor: pointer;
    }

    .actions button:hover {
      background-color: #666;
    }

    .popup-victoire {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    }

    .popup-contenu {
     background-color: #222;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 0 20px gold;
    } 

    .gagnant-nom {
    color: gold;
    font-size: 2.5em;
    font-weight: bold;
    text-shadow: 2px 2px 4px #000;
    }

    .bouton-retour {
      display: inline-block;
      margin-top: 20px;
     padding: 12px 24px;
     background-color: gold;
     color: black;
     text-decoration: none;
      font-weight: bold;
      border-radius: 8px;
      transition: 0.3s;
      }

    .bouton-retour:hover {
      background-color: #ffcc00;
      }

      .popup-defaite {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.85);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    }


    .popup-abandon {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 0, 0, 0.7); /* Rouge semi-transparent */
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 9999;
    }

    .popup-abandon .popup-contenu {
    background-color: white;
    padding: 30px;
    border-radius: 10px;
    text-align: center;
    box-shadow: 0 0 15px rgba(0, 0, 0, 0.3);
    }

    .popup-abandon h1 {
    color: #c00;
    }

    .popup-defaite .popup-contenu {
    background-color: #330000;
    padding: 40px;
    border-radius: 20px;
    text-align: center;
    box-shadow: 0 0 20px crimson;
    }

    .perdant-nom {
    color: crimson;
    font-size: 2.5em;
    font-weight: bold;
    text-shadow: 2px 2px 4px black;
    }

    .bouton-retour {
      display: inline-block;
    margin-top: 20px;
    padding: 12px 24px;
    background-color: crimson;
    color: white;
    text-decoration: none;
    font-weight: bold;
    border-radius: 8px;
    transition: 0.3s;
    }

    .bouton-retour:hover {
    background-color: darkred;
    }
    .tour {
    display: flex;
    align-items: center;      
    justify-content: center;  
    margin-bottom: 10px;
    font-weight: bold;
    text-shadow: 1px 1px 2px black;
    height: 100px; 
    }

    .tour p {
      margin: 0; 
    }
  </style>
  <script>

  const tourActuel = <?= $tour_actuel ?>;

  async function choisirAttaque(id_attaque) {
  try {
    const response = await fetch('https://37.187.38.15/Jeu-start/combat/action_combat.php', {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json'
      },
      body: JSON.stringify({
        action: 'choisir_attaque',
        id_attaque: id_attaque,
        tour: tourActuel
      })
    });

    if (!response.ok) {
      console.error('Réponse serveur non OK :', response.status);
      return;
    }

    
    console.log("Attaque envoyée avec succès");
    location.reload(); 
  } catch (error) {
    console.error('Erreur fetch attaque:', error);
  }
} 
</script>
</head>
<body>
  
<?php 


$combat_data = json_decode(file_get_contents("donnees_combat/combat_$id_combat.json"), true);

if ($combat_data['joueurs'][1]['id'] == $id_utilisateur){ 
  $carte_active_joueur = $carte_active_joueur1; 
  $idn = 1;
  $ida = 2;
}
if ($combat_data['joueurs'][2]['id'] == $id_utilisateur){ 
  $carte_active_joueur = $carte_active_joueur2; 
  $idn = 2;
  $ida = 1;
}

if ($resultat['statut'] == 'comence') $statut_t = false; 


if ($resultat['statut'] == 'terminé')  $statut_t = true;


if ($id_utilisateur == $id_joueur1) $id_ad = $id_joueur2;
if ($id_utilisateur == $id_joueur2) $id_ad = $id_joueur1;
 


 $pseudo = $_SESSION['pseudo'];


if (isset($_POST['abandon'])) {
    $requete = $bdd->prepare("UPDATE COMBAT SET degats = -1 WHERE id_combat = ?");
    $requete->execute([$id_combat]);
    $_SESSION['statut'] = 'abandon';
    header("Location: index_combat.php");
    }elseif(!isset($_POST['abandon']) && $resultat['degats'] == -1){
      $idadverse = $combat_data['joueurs'][$ida]['id'];
        fin_combat($id_utilisateur, $idadverse, $bdd, $id_combat);
      }
    if ($resultat['degats'] == -1){
    ?>
    <div class="popup-abandon">
  <div class="popup-contenu">
    <h1>⚠️ <span class="gagnant-nom"><?=  $_SESSION['statut'] == 'abandon'? $combat_data['joueurs'][$ida]['pseudo']: $pseudo ?> #<?=$_SESSION['statut'] == 'abandon'?$idadverse:$id_utilisateur ?></span> l'emporte par abandon !</h1>
    <a href="index_combat.php?" class="bouton-retour">Retour au combat</a>
  </div>
</div>
<?php
exit;
}

if (!$carte_active_joueur){
  ?>
  <div class="popup-defaite">
  <div class="popup-contenu">
    <h1 style="color:white!important">💀 Défaite de <span class="perdant-nom"><?= $pseudo ?> #<?= $id_utilisateur ?></span>...</h1>
    <a href="index_combat.php" class="bouton-retour">Retour au combat</a>
  </div>
</div>
<?php

exit;
}




if ( verifier_deck_vide($combat_data, $id_ad) || definir_carte_active($id_ad) == NULL && $tour_actuel >1){
      fin_combat($id_utilisateur, $id_ad, $bdd, $id_combat);
  ?>
    <div class="popup-victoire">
      <div class="popup-contenu">
        <h1 style="color:white!important">🎉 Victoire de <span class="gagnant-nom"><?=  $combat_data['joueurs'][$idn]['pseudo'] ?> #<?=$id_utilisateur ?></span> !</h1>
        <a href="index_combat.php?" class="bouton-retour">Retour au combat</a>
      </div>
    </div>
  <?php
  $_SESSION['pseudo'] = $combat_data['joueurs'][$idn]['pseudo'];
  exit;
  }

?>
<div class="combat-container">

<div class="tour">
<p> Tour : <?= $combat_data['tour'] ?> </p>
  </div>

  <div class="nom-joueurs">
    <div><?= $nom_joueur1 ?></div>
    <div><?= $nom_joueur2 ?></div>
  </div>
  <div class="terrain">
    <div class="zone-joueur" style="background-image: url('../../carte/<?= $fond_terrain_joueur1 ?>');">
      <p><?= $carte_active_joueur1['nom'] ?> | PV : <?= $carte_active_joueur1['pv'] ?></p>
      <img class="carte" src="../../carte/<?= $image_carte1 ?>" alt="Carte Joueur 1">
    </div>
    <div class="zone-joueur" style="background-image: url('../../carte/<?= $fond_terrain_joueur2 ?>');">
    <p><?= $carte_active_joueur2['nom'] ?> | PV : <?= $carte_active_joueur2['pv'] ?></p>
      <img class="carte" src="../../carte/<?= $image_carte2 ?>" alt="Carte Joueur 2">
    </div>
  </div>
<?php

$est_son_tour = (($combat_data['tour'] % 2 != 0 && $idn == 1) || ($combat_data['tour'] % 2 == 0 && $idn == 2));
if ($est_son_tour){ 
  ?>
  <div class="actions">
    <h2>Actions</h2>
    <button id="attaque1" onclick="choisirAttaque(<?= $carte_active_joueur['attaques'][0]['id_attaque'] ?>)"><?= htmlspecialchars($attaque1_nom) ?></button>
    <button id="attaque2" onclick="choisirAttaque(<?= $carte_active_joueur['attaques'][1]['id_attaque'] ?>)"><?= htmlspecialchars($attaque2_nom) ?></button>
  </div>
  <?php }
   ?>
  <script>
  var estSonTour = <?= $est_son_tour ? 'true' : 'false' ?>;
  var statut_ter = <?= $statut_t ? 'true' : 'false' ?>;
  console.log(statut_ter);
  if (!estSonTour && !statut_ter) {
  console.log("Ce n'est pas ton tour, la page va se recharger toutes les 2 secondes...");
  const intervalId = setInterval(() => {
      location.reload(); 
    }, 2000);
}else {
    console.log("C'est ton tour !");
  }
</script>
</div>

<style>
        @keyframes shake {
            0% { transform: translate(-50%, -50%) translateX(0); }
            25% { transform: translate(-50%, -50%) translateX(-50px); }
            50% { transform: translate(-50%, -50%) translateX(50px); }
            75% { transform: translate(-50%, -50%) translateX(-25px); }
            100% { transform: translate(-50%, -50%) translateX(0); }
        }

        #popup-degat {
            display: none;
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #ff2c2c;
            color: white;
            font-size: 2rem;
            padding: 40px 60px;
            border-radius: 15px;
            box-shadow: 0 0 30px rgba(255, 0, 0, 0.8);
            z-index: 9999;
            text-align: center;
            animation: shake 0.4s ease-in-out;
        }
    </style>

    <?php
 $resultats = $_SESSION['result'];

if ($est_son_tour){
  $_SESSION['attaque-tour'] =0;
  } 
$afficherPopup = true;
if($combat_data['tour'] == 1) $afficherPopup = false;
var_dump( $_SESSION['attaque-tour']);
if ($afficherPopup && isset($_SESSION['attaque-tour'])){ 
  if ($_SESSION['attaque-tour'] == 0){
  ?>
    <div id="popup-degat">💥 -<?=$resultat['degats']?> PV !</div>
    <script>
        const popup = document.getElementById('popup-degat');
        popup.style.display = 'block';
        setTimeout(() => {
            popup.style.display = 'none';
        }, 3000);
    </script>
<?php 
$_SESSION['attaque-tour'] +=1;
} 
} 
if (!$statut_t && $est_son_tour) {
     ?>
  <script>
    setTimeout(function() {
      console.log("Requête envoyée avec succès");
      choisirAttaque(<?= $carte_active_joueur['attaques'][0]['id_attaque'] ?>);
    }, 30000);
  </script>
 <?php
}
if ($est_son_tour){
  
?>
<h1>Cliquer deux fois pour abandonner (le premier clique scellera toute fois votre sort)</h1>
<form method="post">
    <button type="submit" name="abandon" class="btn btn-primary btn-dynamic">Abandonner</button>
</form>
<?php
} 
?>
</body>
</html>





