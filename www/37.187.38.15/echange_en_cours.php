<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
    <title>Echange</title>
    <style>
        .inventaire {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .carte{
            border: 1px solid #ccc;
            padding: 0.5rem;
            width: 150px;
            text-align: center;
            border-radius: 8px;
        }
        .carte img{
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>
    <?php session_start();
    include('bdd.php');

if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
} 

$verifier=$bdd->prepare("SELECT * FROM echange WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$verifier->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
$resultverifier=$verifier->fetch();

if (!$resultverifier || ($resultverifier['etat'] != 'en_cours' && $resultverifier['etat'] != 'j1' && $resultverifier['etat'] != 'j2')){
    if (isset($_SESSION['echange_deja_effek'])){
        echo "<h1>Echange deja effectué</h1>";
        echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
        exit;
    } else if (isset($_SESSION['echange_cancel'])){
        echo "<h1>Echange annulé</h1>";
        echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
        exit;
    }
    header('location:index.php');
    exit;
    }



$_SESSION['idechange'] = $resultverifier['id_echange'];

?>

<div id='gggg'>

</div>


<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8" meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
    <title>Echange</title>
    <style>
        .inventaire {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        .carte{
            border: 1px solid #ccc;
            padding: 0.5rem;
            width: 150px;
            text-align: center;
            border-radius: 8px;
        }
        .carte img{
            width: 100%;
            height: auto;
        }
    </style>
</head>
<body>

<div id='gggg'>

</div>


<script>

    async function echange(){
        const x = await fetch(`fetch/fetch_echange_en_cours.php?user=<?= urlencode($_GET['user']) ?>&iduser=<?= intval($_GET['iduser']) ?>`);
        const y = await x.text()
        document.getElementById('gggg').innerHTML = y

        if (lastCardIndex !== null){
            const elements = document.getElementsByClassName('carte');
            stylex = elements[lastCardIndex];
            stylex.style.border = "2px solid red";
            stylex.style.outline = "2px solid red";
        }
    }

    async function annuler(){
            const confirmation = confirm("Souhaitez-vous vraiment annuler l'échange ?");
            if (confirmation){
                const annuler = await fetch(`fetch/fetch_echange_en_cours.php?annuler&user=<?= urlencode($_GET['user']) ?>&iduser=<?= intval($_GET['iduser']) ?>`);
            }
        }

    async function accepter(){
            const confirmation = confirm("Souhaitez-vous vraiment accepter l'échange ?");
            if (confirmation){
                const accepter = await fetch(`fetch/fetch_echange_en_cours.php?accepter&user=<?= urlencode($_GET['user']) ?>&iduser=<?= intval($_GET['iduser']) ?>`);
            }
        }

    async function unaccepter(){
            const accepter = await fetch(`fetch/fetch_echange_en_cours.php?unaccepter&user=<?= urlencode($_GET['user']) ?>&iduser=<?= intval($_GET['iduser']) ?>`);
        }

let lastCardClass = null;
let lastCardIndex = null;

    async function tacarte(x, z){
        const yourcard = await fetch(`fetch/fetch_echange_en_cours.php?tacarte=${x}&user=<?= urlencode($_GET['user']) ?>&iduser=<?= intval($_GET['iduser']) ?>`);
        let allCards = document.getElementsByClassName("carte");
        for (let i = 0; i < allCards.length; i++) {
            allCards[i].style.border = "none";
            allCards[i].style.outline = "none";
            }
        if (allCards.length > z){
                stylex = allCards[z];
                stylex.style.border = "2px solid red";
                stylex.style.outline = "2px solid red";
                lastCardIndex = z;
            }
        echange()
    }

echange()
setInterval(echange, 400);







async function actualise_time(){
            const del = await fetch("fetch/echange_temps.php?iduser=" + encodeURIComponent(<?= json_encode($_GET['iduser']) ?>))
        }

[
  "mousemove",
  "mousedown",
  "contextmenu",
  'wheel',
  "scroll",

  "keydown",        
           
  "touchstart",
  "touchmove",

].forEach(evt =>
  document.addEventListener(evt, actualise_time)
);

[
  "mousemove",
  "mousedown",
  "contextmenu",
  'wheel',
  "scroll",

  "keydown",        
           
  "touchstart",
  "touchmove",

  "focus",
  "DOMContentLoaded",

].forEach(evt =>
  document.addEventListener(evt, actualise_time)
);

document.addEventListener("visibilitychange", () => {
  if (document.visibilityState === "visible") {
    actualise_time()
  }
});


</script>
        
</body>
</html>
        
</body>
</html>




