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
    <title>Message</title>
    <style>

    #msg {
        border-radius: 6px;
        padding: 8px;
        transition: background-color 0.3s, color 0.3s;
    }

    input[type="file"],
    input[type="submit"] {
        margin-top: 5px;
    }
</style>
</head>
<body>
    <?php session_start();

    include('bdd.php');

    if (!isset($_SESSION['id'])){
    header('location:index.php');
    exit;
    }

$iduser=$bdd->prepare("SELECT id_utilisateur FROM utilisateur WHERE pseudo = :pseudo");
$iduser->execute(['pseudo'=>$_GET['user']]);
$resultuser=$iduser->fetch();

if (!$resultuser){
    header('location:index.php?message=L\'utilisateur ' . $_GET['user'] . ' n\'existe pas');
    exit;
}

$verifami = $bdd->prepare("SELECT * FROM amitier WHERE (id_amis_demande = :id1 AND id_amis_recoit = :id2) OR (id_amis_demande = :id2 AND id_amis_recoit = :id1) ");
$verifami->execute([
'id1'=>$_SESSION['id'],
'id2'=>$resultuser['id_utilisateur']]);
$verif=$verifami->fetch();

if (!$verif || $verif['etat'] != 'amis'){
    header('location:index.php?message=Vous devez être ami avec l\'utilisateur ' . $_GET['user'] . ' pour lui envoyer un message');
    exit;
}

include("header.php");

    echo "<div style='margin-left:10px'>";
    echo "<h1 style='padding:1.5px 0px 3px 5px; font-size:45px;'> Votre discution avec " . $_GET['user'] . "</h1>";

    include("getmessage.php");

    echo "<p><a href='profil.php?user=" . $_GET['user'] . "'>Consulter le profil de " . $_GET['user'] . "</a></p>";
    echo "<p style='color: blue; text-decoration: underline' onclick='bloquer()'>Bloquer " . $_GET['user'] . "</p>"; ?>

<script>
function bloquer() {
    const user = "<?= addslashes($_GET['user']) ?>";
    const confirmer = confirm("Êtes-vous sûr de vouloir bloquer " + user + " ?");
    if (confirmer) {
        window.location.href = "profil_ami.php?user=" + encodeURIComponent(user) + "&message=Bloquer";
    }
}
</script>

<div id='echange'>
        
</div> 

</div>


<script>
async function echanger() {
    const user = '<?= $_GET['user'] ?>';
    const x = await fetch(`fetch/discussion_echange.php?user=${user}`)
    const y = await x.text()
    document.getElementById('echange').innerHTML = y
}

echanger()
setInterval(echanger, 1000);
</script>


<?php $message2 = $bdd->prepare("SELECT * FROM message WHERE (id_utilisateur_envoyeur = :id1 AND id_utilisateur_destinataire = :id2) OR (id_utilisateur_envoyeur = :id2 AND id_utilisateur_destinataire = :id1) ORDER BY date_envoi DESC");
    $message2->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$resultuser['id_utilisateur']
    ]);
    $verifmsg2=$message2->fetchall();

    if (!$verifmsg2) {
    echo "<h3>Envoyez votre 1er message à " . htmlspecialchars($_GET['user']) . "</h3>";
} else {
   echo '<div id="messages" style="
    margin-left: 10px;
    margin-right: 10px;
    max-height: 450px;
    overflow-y: auto;
    padding: 10px;
    border: 1px solid var(--bs-border-color);
    border-radius: 10px;
    display: flex;
    flex-direction: column-reverse;
"></div>';

}
    ?>

    <script>

    async function chargermessage() {
        const id = '<?= $resultuser['id_utilisateur'] ?>';
        const response = await fetch(`fetch/message.php?id=${id}`);
        const data = await response.text();
        document.getElementById('messages').innerHTML = data;
}

    chargermessage()
    setInterval(chargermessage, 1000);
    </script>

    
<div style="margin-left:10px">

    <form method='post' enctype="multipart/form-data" action='verification/message_verif.php?id=<?= $resultuser['id_utilisateur']?>&user=<?= $_GET['user'] ?>'>
        <p><label style="margin-top: 8px;">Envoyer un message</label><br>
        <div style="display: flex; align-items: flex-start; gap: 8px;">
            <textarea id="msg" name="msg" rows="1" 
            style="width: 40%; font-size: 18px; overflow: hidden; resize: none;"></textarea>

        <style>
            @media (max-width: 600px) {
            #msg {
                width: 70% !important;
            }
        }
        </style>
        </div>
        <br>
        <input type="file" name="fichier" id="file" accept="image/jpeg, image/png, image/gif"><br>
        <input type="submit" value="Envoyer" style="margin-top: 15px;">
    </form>
    <p style='margin-top: 8.5px;'><a href='listeamis.php'>Retour à la liste d'amis</a></p>

    <script>
const textarea = document.getElementById("msg");

textarea.addEventListener("input", () => {
  textarea.style.height = "auto";
  textarea.style.height = textarea.scrollHeight + "px";
});
</script>

</div>

</body>
</html>