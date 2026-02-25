<?php session_start();
include_once("parametres/theme.php");

?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <link rel="icon" href="Logo.png" type="image/png">
    <title>Profil</title>
</head>
<body>

<?php

include("header.php");

ini_set('display_errors', 1);

include('bdd.php');

if(isset($_GET['a_propos']) && isset($_POST['bio'])){
    $update = $bdd->prepare("UPDATE UTILISATEUR SET a_propos = :propos WHERE id_utilisateur = :id");
    $update->execute([
        'propos'=>$_POST['bio'],
        'id'=>$_SESSION['id']
    ]);
}

?>

    <script>
        function suppami(){
            const a=document.getElementById('suppr')
            const b=document.getElementById('Oui2')
            const c=document.getElementById('Non2')
            const d=document.getElementById('suppno')
            b.style.display = 'inline-block'
            c.style.display = 'inline-block'
            d.style.display = 'none'
            a.innerHTML = "Etes vous sur de vouloir supprimer cet ami ?"
            b.innerHTML = 'Oui'
            c.innerHTML = 'Non'
            d.innerHTML = ' '
        }
        function suppnon(){
            const a=document.getElementById('suppr')
            const b=document.getElementById('Oui2')
            const c=document.getElementById('Non2')
            const d=document.getElementById('suppno')
            b.style.display = 'none'
            c.style.display = 'none'
            d.style.display = 'inline-block'
            a.innerHTML = "Vous êtes amis avec " + "<?php echo htmlspecialchars($_GET['user']); ?>";
            b.innerHTML = ' '
            c.innerHTML = ' '
            d.innerHTML = 'Supprimer l\'ami'
        }
        function suppoui(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Supprimer l'ami"
        }

        function deblock(){
            const aa=document.getElementById('debloquerid')
            const ab=document.getElementById('Oui3')
            const ac=document.getElementById('Non3')
            const ad=document.getElementById('deblono')
            ab.style.display = 'inline-block'
            ac.style.display = 'inline-block'
            ad.style.display = 'none'
            aa.innerHTML = "Etes vous sur de vouloir débloquer cet utilisateur ?"
            ab.innerHTML = 'Oui'
            ac.innerHTML = 'Non'
            ad.innerHTML = ' '
        }
        function deblocknon(){
            const aa=document.getElementById('debloquerid')
            const ab=document.getElementById('Oui3')
            const ac=document.getElementById('Non3')
            const ad=document.getElementById('deblono')
            ab.style.display = 'none'
            ac.style.display = 'none'
            ad.style.display = 'inline-block'
            aa.innerHTML = "Vous avez bloqué cet utilisateur"
            ab.innerHTML = ' '
            ac.innerHTML = ' '
            ad.innerHTML = 'Debloquer'
        }

        function deblockoui(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Debloquer"
        }

        function bloquer(){
            const ba=document.getElementById('bloquerid')
            const bb=document.getElementById('Oui')
            const bc=document.getElementById('Non')
            const bd=document.getElementById('butblockno')
            bb.style.display = 'inline-block'
            bc.style.display = 'inline-block'
            bd.style.display = 'none'
            ba.innerHTML = "Etes vous sur de vouloir bloquer cet utilisateur ?"
            bb.innerHTML = 'Oui'
            bc.innerHTML = 'Non'
            bd.innerHTML = ' '
        }
        function bloquernon(){
            const ba=document.getElementById('bloquerid')
            const bb=document.getElementById('Oui')
            const bc=document.getElementById('Non')
            const bd=document.getElementById('butblockno')
            bb.style.display = 'none'
            bc.style.display = 'none'
            bd.style.display = 'inline-block'
            ba.innerHTML = "Bloquer l'utilisateur"
            bb.innerHTML = ' '
            bc.innerHTML = ' '
            bd.innerHTML = 'Bloquer'
        }
        function bloqueroui(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Bloquer"
        }

        function accepterami(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Accepter l'ami"
        }
        function refuserami(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Refuser l'ami"
        }
        function annulerami(){
            const user = "<?php echo htmlspecialchars($_GET['user']) ?>"
            window.location.href="profil_ami.php?user=" + user + "&message=Annuler la demande d'ami"
        }

    </script>

<?php

    echo '<h1>Profil</h1>';
    include('getmessage.php');

    $user = $_GET['user'];

    $all="SELECT * FROM UTILISATEUR WHERE pseudo = :pseudo";
    $statement = $bdd->prepare($all);
    $statement->execute([
        'pseudo'=>$user
    ]);
    $result = $statement->fetch(PDO::FETCH_ASSOC);
    
    if ($result){

        if (isset($_SESSION['role']) && $_SESSION['role'] == 'admin' && $_SESSION['pseudo'] != $result['pseudo']){
            ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/supprimer/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Supprimer cet utilisateur">
            </form>
            </p>

            <?php if ($result['ban'] == 0){
                ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/ban/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Bannir cet utilisateur">
            </form>
            </p>
            <?php } else { ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/deban/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Debannir cet utilisateur">
            </form>
            </p>
            <?php
            }
            ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/modifier/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Modifier les données de cet utilisateur">
            </form>
            </p>
            <?php if ($result['statut'] == 0){
                ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/verif/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Verifier l'email de cet utilisateur">
            </form>
            </p>
            <?php } else { ?>
            <p>
            <form method='post' action='admin/traitement_commande.php'>
                <input type='text' name='commande' value='commande/suppverif/<?= $_GET['user'] ?>' style='display: none'>
                <input type='text' name='profilcomm' value='xx' style='display: none'>
                <input type='submit' value="Supprimer la vérification de mail de cet utilisateur">
            </form>
            </p>
            <?php
            }
        }

        if ($result['ban'] == 1){
            echo "<h3> L'utilisateur est banni du site, vous ne pouvez pas consulter ses informations </h3>";
            exit;
        }

        if (isset($_SESSION['pseudo']) && $_SESSION['pseudo'] !== $_GET['user'] && $_SESSION['role'] !== 'admin' && $result['role'] !== 'admin'){

            $ami ="SELECT * FROM AMITIER WHERE id_amis_demande = :iddemande AND id_amis_recoit = :idrecoit";
        $amistatement = $bdd->prepare($ami);
        $amistatement->execute([
        'iddemande'=>$_SESSION['id'],
        'idrecoit'=>$result['id_utilisateur']
        ]);
        $amiresult=$amistatement->fetch(PDO::FETCH_ASSOC);

        $ami2 ="SELECT * FROM AMITIER WHERE id_amis_demande = :idrecoit AND id_amis_recoit = :iddemande";
        $amistatement2 = $bdd->prepare($ami2);
        $amistatement2->execute([
        'iddemande'=>$_SESSION['id'],
        'idrecoit'=>$result['id_utilisateur']
        ]);
        $amiresult2=$amistatement2->fetch(PDO::FETCH_ASSOC);

            if ($amiresult && $amiresult['etat'] == 'bloqué'){
                echo "<h2 id=debloquerid> Vous avez bloqué " . htmlspecialchars($_GET['user']) . " </h2>";
                echo "<button id='deblono' onclick='deblock()'>Debloquer</button>";
                echo "<button id='Oui3' onclick='deblockoui()' style='display: none'></button>";
                echo "<button id='Non3' onclick='deblocknon()' style='display: none'></button>";
                echo "<p><a href='index.php'> Retour a l'accueil </a></p>";
                exit;
            }else if($amiresult2 && $amiresult2['etat'] == 'bloqué'){
                echo "<h2> " . htmlspecialchars($_GET['user']) . " vous a bloqué </h2>";
                echo "<a href='index.php'> Retour a l'accueil </a>";
                exit;

            } else if ($amiresult && $amiresult['etat'] == 'amis'){   

                echo "<h2 id='suppr'> Vous êtes amis avec " . htmlspecialchars($_GET['user']) . " </h2>";

                echo "<button id='suppno' onclick='suppami()'>Supprimer l'ami</button>";
                echo "<button id='Oui2' onclick='suppoui()' style='display: none'></button>";
                echo "<button id='Non2' onclick='suppnon()' style='display: none'></button>";

                echo"<p><a href='discussion.php?user=" . $user . "'>Envoyer un message a " . $user . "</a></p>";
                
            } else if ($amiresult2 && $amiresult2['etat'] == 'amis'){
                echo "<h2 id='suppr'> Vous êtes amis avec " . htmlspecialchars($_GET['user']) . " </h2>";

                echo "<button id='suppno' onclick='suppami()'>Supprimer l'ami</button>";
                echo "<button id='Oui2' onclick='suppoui()' style='display: none'></button>";
                echo "<button id='Non2' onclick='suppnon()' style='display: none'></button>";

                echo"<p><a href='discussion.php?user=" . $user . "'>Envoyer un message a " . $user . "</a></p>";
                    
            } else if ($amiresult && $amiresult['etat'] == 'en attente'){
                if($_GET['etat'] == 'now'){
                    echo "<h2> Une demande d'ami a été envoyée à " . htmlspecialchars($_GET['user']) . " </h2>";
                }else{
                    echo "<h2> Une demande d'ami a déjà été envoyée à " . htmlspecialchars($_GET['user']) . " </h2>";
                }
                echo "<button id='annulerdemande' onclick='annulerami()'>Annuler la demande d'ami</button>";
            }else if($amiresult2 && $amiresult2['etat'] == 'en attente'){
                    echo "<h2> Vous avez reçu une demande d'ami de " . htmlspecialchars($_GET['user']) . "</h2>";
                    echo "<button id='Oui4' onclick='accepterami()'>Accepter</button>";
                    echo "<button id='Non4' onclick='refuserami()'>Refuser</button>";

            } else {
                if(isset($_GET['etat']) && $_GET['etat'] == 'supp'){
                    echo "<h2>" . htmlspecialchars($_GET['user']) . " a été supprimé de votre liste d'amis </h2>";
                } else if (isset($_GET['etat']) && $_GET['etat'] == 'deblok'){
                    echo "<h2> Vous avez débloqué " . htmlspecialchars($_GET['user']) . " </h2>";
                }
                echo "<h2> Envoyer une demande d'ami à " . htmlspecialchars($_GET['user']) . " </h2>";
                echo "<form method='post' action='profil_ami.php?user=" . htmlspecialchars($_GET['user']) . "&message=Demande d&#39;ami'>";
                echo "<input type='submit' value='Envoyer une demande d&#39;ami'>";
                echo "</form>";
            }
        }


        ?>

        <div id='echange'>

        </div>

        <script>
            async function echanger() {
            const user = '<?= $_GET['user'] ?>';
            const x = await fetch(`fetch/discussion_echange.php?user=${user}`)
            const y = await x.text()
            document.getElementById('echange').innerHTML = y
        }

        echanger()
        setInterval(echanger, 1000)
        </script>

        <?php

    echo "<p> Nom d'utilisateur : " . htmlspecialchars($user) . "</p>";
    if ($result['role'] == 'admin') {
        echo '<p>ADMINISTRATEUR</p>';
    }
    echo "<p> Photo de profil : ";

    $getpdp = $bdd->prepare("SELECT photo_profil FROM UTILISATEUR WHERE id_utilisateur = :id");
    $getpdp->execute([
        'id'=>$result['id_utilisateur']
    ]);
    $results=$getpdp->fetch();

    if (!empty($results['photo_profil'])){
        echo "<img src='photo_profil/" . $results['photo_profil'] . "' alt='Photo de profil' width='40' style='border-radius: 50%'>";
    } else {
        echo "Pas de photo de profil";
    }

    if (isset($_SESSION['id']) && $_SESSION['id'] == $result['id_utilisateur']){
    echo "<br><a href='parametres/parametres_perso_modif.php?photo'>Modifier la photo de profil</a>";
    }

    echo "</p>";
    echo "<p> Nombre d'amis : " . htmlspecialchars($result['nombre_amis']) . "</p>";

    $a_propos = $result['a_propos'];
    echo "<p> A propos de l'utilisateur  : <span id='apropos'><br>" . htmlspecialchars($a_propos) . "</span>"

    ?> <form method='post' action='profil.php?a_propos&user=<?= $_SESSION['pseudo'] ?>'>
    <input type='text' placeholder="<?= htmlspecialchars($a_propos) ?>" id='bio' name='bio' style='display: none'>
    <input type='submit' value='Envoyer' id='bio2' style='display: none'>
    </form>

    <?php
    if (isset($_SESSION['pseudo']) && $user == $_SESSION['pseudo']){

        ?>

<script>

function modifbio(){
    const apropos = document.getElementById('apropos')
    const bio = document.getElementById('bio')
    const modifier = document.getElementById('modif_apropos')
    const annuler = document.getElementById('annuler_modif')
    modifier.innerHTML = ''
    apropos.innerHTML = ''
    bio.style.display = 'inline-block'
    bio2.style.display = 'inline-block'
    annuler.innerHTML = 'Annuler'
}

function annulerbio(){
    const annuler = document.getElementById('annuler_modif')
    const modifier = document.getElementById('modif_apropos')
    const bio = document.getElementById('bio')
    const apropos = document.getElementById('apropos')
    annuler.innerHTML = ''
    modifier.innerHTML = 'Modifier'
    bio.style.display = 'None'
    bio2.style.display = 'None'
    apropos.innerHTML = <?= json_encode("<br>" . $a_propos) ?>
}

</script>

        <?php

        echo "<span id='modif_apropos' onclick='modifbio()' style='color: blue; text-decoration: underline'>Modifier</span>";
        echo "<span id='annuler_modif' onclick='annulerbio()' style='color: blue; text-decoration: underline'></span>";
    } echo "</p>";

    echo "<p> Niveau : " . htmlspecialchars($result['niveau']) . "</p>";
    echo "<p> Nombre de coins : " . htmlspecialchars($result['monnaie']) . "</p>";
    echo "<p> Nombre de goodies : " . htmlspecialchars($result['goodies']) . "</p>";
    $date= explode('-', explode(' ', $result['date_inscription'])[0]);
    echo "<p> Date d'inscription : " . htmlspecialchars($date[2] . '/' . $date[1] . '/' . $date[0]) . "</p>";

    $heros = $bdd->prepare("
    SELECT count(CONTIENT.id_carte) AS nb_heros
    FROM CONTIENT
    JOIN CARTE ON CARTE.id_carte = CONTIENT.id_carte
    JOIN INVENTAIRE ON INVENTAIRE.id_inventaire = CONTIENT.id_inventaire
    WHERE CARTE.statut = 'heros' AND INVENTAIRE.id_utilisateur = :inventaire");
    $heros->execute([
        'inventaire'=>$result['id_utilisateur']
    ]);
    $resultheros = $heros->fetch();

    echo "<p> Nombre de heros débloqués : " . $resultheros['nb_heros'] . "</p>";

    $terrain = $bdd->prepare("
    SELECT count(CONTIENT.id_carte) AS nb_terrain
    FROM CONTIENT
    JOIN CARTE ON CARTE.id_carte = CONTIENT.id_carte
    JOIN INVENTAIRE ON INVENTAIRE.id_inventaire = CONTIENT.id_inventaire
    WHERE CARTE.statut = 'terrain' AND INVENTAIRE.id_utilisateur = :inventaire");
    $terrain->execute([
        'inventaire'=>$result['id_utilisateur']
    ]);
    $resultterrain = $terrain->fetch();

    echo "<p> Nombre de terrains débloqués : " . $resultterrain['nb_terrain'] . "</p>";
    echo "<p> Nombre de parties : " . htmlspecialchars($result['nb_partie']) . "</p>";
    echo "<p> Nombre de victoires : " . htmlspecialchars($result['nb_victoire']) . "</p>";
    echo "<p> Nombre de quiz complétés (Histoire) : " . htmlspecialchars($result['vh']) . "</p>";
    echo "<p> Nombre de quiz complétés (Géo) : " . htmlspecialchars($result['vg']) . "</p>";
    echo "<p> Nombre de questions répondus : </p>";
    echo "<p> Ratio de bonne réponses : </p>";
    echo "<p> Identifiant utilisateur : " . htmlspecialchars($result['id_utilisateur']) . "</p>";

    if ($result['statut'] == 1){
        echo "<h4> L'utilisateur est vérifié </h4>";
    } else {
        echo "<h4> L'utilisateur n'est pas vérifié </h4>";
    }

    if ((isset($_SESSION['pseudo']) && $user == $_SESSION['pseudo']) || (isset($_SESSION['role']) && $_SESSION['role'] == 'admin')){
    echo '<form method="POST" action="envoie_PDF.php?user=' . $result['pseudo'] . '">
        <button type="submit" name="extraire_pdf">Extraire les données en PDF</button>
        </form>';
    }

    if (isset($_SESSION['pseudo']) && $user != $_SESSION['pseudo'] && $_SESSION['role'] !== 'admin' && $result['role'] !== 'admin'){
    echo "<h2 id=bloquerid>Bloquer l'utilisateur</h2>";
    echo "<button id='butblockno' onclick='bloquer()'>Bloquer</button>";
    echo "<button id='Oui' onclick='bloqueroui()' style='display: none'></button>";
    echo "<button id='Non' onclick='bloquernon()' style='display: none'></button>";
    }

    } else {
        echo "<h2>Aucun utilisateur trouvé</h2>";
        echo '<p><a href="index.php">Retour à l\'accueil</a></p>'; 
    }

    include('footer.php');
    
    ?>
</body>
</html>