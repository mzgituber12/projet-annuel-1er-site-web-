<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');

$id_utilisateur = $_SESSION['id'];
$id_combat = $_GET['id_combat'] ?? null;
$_SESSION['id_combat'] = $id_combat;
$_SESSION['statut'] = 'normal';

if (!$id_combat || !is_numeric($id_combat)) die("ID de combat invalide");

function Collectee_Info($bdd, $id_utilisateur) {
    
    $statement = $bdd->prepare("SELECT pseudo id_utilisateur FROM UTILISATEUR WHERE id_utilisateur = ?");
    $statement->execute([$id_utilisateur]);
    $pseudo = $statement->fetchColumn();

    $infos = [
        'pseudo' => $pseudo,
        'cartes' => []
    ];

    $statement = $bdd->prepare("
        SELECT c.id_carte, c.nom_carte, c.image, c.rarete, c.talent, c.pays, c.religion, c.statut,
               s.pv, s.atk, s.def, s.vit, s.esq, s.prs
        FROM CARTEDECK d
        JOIN carte c ON d.id_carte = c.id_carte
        JOIN stats_carte s ON c.id_carte = s.id_carte
        JOIN DECK dk ON d.id_deck = dk.id_deck 
        WHERE dk.id_utilisateur = ?
    ");
    $statement->execute([$id_utilisateur]);
    $cartes = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartes)) {
        
        return $infos;
    }
    if (count($cartes) < 6) {
        return $infos;
    }

    foreach ($cartes as $carte) {
        $carte_info = $carte;

        /*if ($carte['statut'] == 'heros') {
            $statement = $bdd->prepare("
                SELECT ac.nom, ac.degats, ac.effet, ac.portee
                FROM attacher a
                JOIN attaque_carte ac ON a.id_attaque = ac.id_attaque
                WHERE a.id_carte = ?
            ");
            $statement->execute([$carte['id_carte']]);
            $attaques = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($attaques) !== 2) {
                die("carte non jouable");
            }

            $carte_info['attaques'] = $attaques;
        }*/

        $infos['cartes'][] = $carte_info;

    }
    
    return $infos;
}
function afficher_cartes($infos_joueur, $classes_rarete) {
    foreach ($infos_joueur['cartes'] as $carte) {
        $choix_rarete = ($carte['statut'] === 'terrain') ? 'terrain' : (in_array($carte['rarete'], $classes_rarete) ? $carte['rarete'] : 'commun');
        ?>
        <div class="carte2 <?= $choix_rarete ?>">
            <img src="../../carte/<?= $carte['image'] ?>" alt="<?= $carte['nom_carte'] ?>">
            <p><strong><?= $carte['nom_carte'] ?></strong></p>
            <p>Rareté: <?= $carte['rarete'] ?></p>
        </div>
        <?php
    }
}

$combat = $bdd->prepare("SELECT * FROM combat WHERE id_combat = ?");
$combat->execute([$id_combat]);
$combat_data = $combat->fetch(PDO::FETCH_ASSOC);

if (!$combat_data) die("Combat introuvable");

if ($id_utilisateur !== (int)$combat_data['id_joueur1'] && $id_utilisateur !== (int)$combat_data['id_joueur2']) {
    die("Vous ne participez pas à ce combat");
}

if (strtotime($combat_data['date_expiration']) > time()) {
    die("Combat expiré");
}

$infos_joueur1 = Collectee_Info($bdd, $combat_data['id_joueur1']);
$infos_joueur2 = Collectee_Info($bdd, $combat_data['id_joueur2']);

$pret = count($infos_joueur1['cartes']) >= 6 && count($infos_joueur2['cartes']) >= 6;

?>
<script>
setInterval(() => {
    fetch("presence_combat.php", {
        method: "POST",
        headers: {"Content-Type": "application/json"},
        body: JSON.stringify({
            id_combat: <?= $id_combat ?>,
            id_utilisateur: <?= $id_utilisateur ?>
        })
    });
}, 900);

setInterval(() => {
    window.location.reload();
}, 20000);

async function verifierCombat() {
    fetch('verifie_statut_combat.php')
        .then(response => response.json())
        .then(data => {
            console.log(data); 

            if (data.error) {
                console.error('Erreur :', data.error);
                return;
            }

            if (data.combat_commence) {
                window.location.reload();
            }

            const boutonLancer = document.getElementById('bouton-lancer');
            if (boutonLancer) {
                boutonLancer.disabled = !data.pret;
            }
        })
        .catch(error => {
            console.error('Erreur lors de la récupération de l\'état du combat :', error);
        });
}

setInterval(verifierCombat, 900);

async function verifierEtatCombat() {
    const res = await fetch("etat_pour_combat.php?id_combat=<?= $id_combat ?>");
    const data = await res.json();
    console.log(data);

    if (data.peut_lancer) {
        document.getElementById("bouton-lancer").disabled = false;
    }else {
    document.getElementById("bouton-lancer").disabled = true;
}

}
setInterval(verifierEtatCombat, 900);

</script>



<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" type="text/css" href="../../styles/stylecarte.css">
    <title>combat</title>
</head>
<body>
    <style>
        .inventaire {
            display: flex;
            flex-wrap: wrap;
            gap: 1rem;
        }
        
        .carte2 img {
            width: 100%;
            height: auto;
        }
        .terrain {
            border-color: green;
             background-color: rgba(43, 230, 90, 0.3);
            }
        .carte2 {
            border: 1px solid #ccc;
            padding: 0.5rem;
            width: 150px;
            text-align: center;
            border-radius: 8px;
            overflow: hidden; 
            background-color: white;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }
        .carte2:hover {
            transform: scale(1.05);
        }
        .commun {
            border-color: gray;
            background-color: rgba(220, 220, 220, 0.3);
        }

        .rare {
            border-color: red;
            background-color: rgba(163, 95, 79, 0.3);
        }

        .super_rare {
            border-color: blue;
            background-color: rgba(69, 103, 175, 0.3);
        }

        .epique {
            border-color: purple;
            background-color: rgba(109, 84, 142, 0.3);
        }
        .legendaire {
            border-color: gold;
            background-color: rgba(197, 173, 65, 0.3);
            border-width: 0.125rem;
            animation: glow 1.5s infinite alternate;
        }

    </style>

    <?php
    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['lancer_combat'])) {
        $statement = $bdd->prepare("UPDATE COMBAT SET statut = 'comence' WHERE id_combat = ?");
        $statement ->execute([$id_combat]);
    }
    $statement = $bdd->prepare("SELECT statut FROM COMBAT  WHERE statut = 'comence' AND id_combat = ?");
    $statement ->execute([$id_combat]);
    $test_combat = $statement->fetchAll(PDO::FETCH_ASSOC);
    $combat_commence = empty($test_combat) ? false : true;

if (!$combat_commence) {
?> 
    <div id="zone-precombat">
    <h2>Préparation au combat</h2>
    <p><?= $infos_joueur1['pseudo'] ?> vs <?= $infos_joueur2['pseudo'] ?></p>
    <div class="inventaire">
    <?php
    $classes_rarete = ['commun', 'rare', 'super_rare', 'epique', 'legendaire'];
    echo "<p>". $infos_joueur1['pseudo'] ."</p>";
    afficher_cartes($infos_joueur1, $classes_rarete);
    echo"</div>";
    echo"<div class='inventaire'>";
    echo "<p>". $infos_joueur2['pseudo']."</p>";
    afficher_cartes($infos_joueur2, $classes_rarete);
    echo "</div>";
    ?>
    <br>
    <?php
    if ($pret){ ?>
    <form method="post">
    <button id="bouton-lancer" type="submit" name="lancer_combat" disabled>Lancer le combat</button>
    </form> 
    <?php }else{?>
        <p>En attente d’un joueur...</p>
    <?php } ?>
</div>
<?php }else {

header("location: combat_commencer.php?id_combat=" . $id_combat);


} ?>
</body>
</html>