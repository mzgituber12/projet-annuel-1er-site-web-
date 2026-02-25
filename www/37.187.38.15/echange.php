<?php session_start(); 
include_once("parametres/theme.php");?>
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
    <title>Echange</title>
</head>
<body>
    <?php session_start();

    if (isset($_SESSION['echange_deja_effek'])){
        echo "<h1>Echange deja effectué</h1>";
        echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
        exit;
    }

    if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
        }

    include('bdd.php');

    $iduser=$bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo");
    $iduser->execute(['pseudo'=>$_GET['user']]);
    $result=$iduser->fetch();

    if (!$result){
    header('location:index.php?message=L\'utilisateur ' . htmlspecialchars($_GET['user']) . ' n\'existe pas');
    exit;
    }

    if ($result['id_utilisateur'] != $_GET['iduser']){
        header('location:index.php?message=Une erreur est survenue');
        exit;
    }

    $verifami = $bdd->prepare("SELECT * FROM amitier WHERE (id_amis_demande = :id1 AND id_amis_recoit = :id2) OR (id_amis_demande = :id2 AND id_amis_recoit = :id1) ");
    $verifami->execute(['id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']]);
    $verif=$verifami->fetchall();
    $x = 0;

    foreach($verif as $verifamis){
    if ($verifamis['etat'] == 'amis'){
    $x += 1;
    }
    if ($x == 0){
    header('location:index.php?message=Vous devez être ami avec l\'utilisateur ' . htmlspecialchars($_GET['user']) . ' pour lui envoyer une demande d\'echange');
    exit;
    }
    }



     echo "<div id='echange'>
     </div>"; 
     
     ?>


    <script>

        async function verifierechange(){
            const verif = await fetch("fetch/fetch_echange.php?user=" + encodeURIComponent(<?= json_encode($_GET['user']) ?>) + "&iduser=" + encodeURIComponent(<?= json_encode($_GET['iduser']) ?>))
            const result = await verif.text()
            document.getElementById('echange').innerHTML = result
        }

        async function refuser() {
            const del = await fetch("fetch/fetch_echange.php?refuser&user=" + encodeURIComponent(<?= json_encode($_GET['user']) ?>) + "&iduser=" + encodeURIComponent(<?= json_encode($_GET['iduser']) ?>))
            const deleted = await del.text()
            document.getElementById('echange').innerHTML = deleted
        }

        verifierechange()
        setInterval(verifierechange, 500);






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