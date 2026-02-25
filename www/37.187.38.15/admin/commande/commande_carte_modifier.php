<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[4]) || !isset($comm[3])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

include('../../bdd.php');

$statement = $bdd->prepare('SELECT * FROM CARTE WHERE id_carte = :id');
$statement->execute([
    'id'=>$comm[3]
]);
$results=$statement->fetch();

if (empty($results)){
    header('location: ../indexad.php?message=La carte avec cet id n\'existe pas');
    exit;
}

$stats = $bdd->prepare('SELECT * FROM STATS_CARTE WHERE id_carte = :id');
$stats->execute([
    'id'=>$comm[3]
]);
$statresult=$stats->fetch();

$query = 'SELECT CARTE.id_carte, ATTAQUE_CARTE.id_attaque, ATTACHER.type_attaque, ATTAQUE_CARTE.nom
                FROM CARTE
                JOIN ATTACHER ON CARTE.id_carte = ATTACHER.id_carte
                JOIN ATTAQUE_CARTE ON ATTACHER.id_attaque = ATTAQUE_CARTE.id_attaque
                WHERE CARTE.id_carte = :id';
    $statement2 = $bdd->prepare($query);
    $statement2->execute([
        'id'=>$comm[3]
    ]);
    $liens = $statement2->fetchall(PDO::FETCH_ASSOC);

if (isset($_GET['modif'])){

    if(
        (!empty($_POST['pv']) && !is_numeric($_POST['pv'])) || 
    (!empty($_POST['atk']) && !is_numeric($_POST['atk'])) || 
    (!empty($_POST['def']) && !is_numeric($_POST['def'])) ||
    (!empty($_POST['vit']) && !is_numeric($_POST['vit'])) ||
    (!empty($_POST['esq']) && !is_numeric($_POST['esq'])) ||
    (!empty($_POST['prs']) && !is_numeric($_POST['prs']))
    ){
        header('location: commande_carte_modifier.php?message=Les statistiques rentrés sont invalides');
        exit;
    }

    if (!empty($_POST['attaque2_2']) && $_POST['attaque2_2'] == 'none'){
        $_POST['attaque2'] = '';
}

    if (!empty($_POST['attaquetalent_2']) && $_POST['attaquetalent_2'] == 'none'){
        $_POST['attaquetalent'] = '';
}

    if(
        (!empty($_POST['attaque1']) && !is_numeric($_POST['attaque1'])) || 
    (!empty($_POST['attaque2']) && !is_numeric($_POST['attaque2'])) || 
    (!empty($_POST['attaquetalent']) && !is_numeric($_POST['attaquetalent']))
    ){
        header('location: commande_carte_modifier.php?message=Les id d\'attaques rentrées sont invalides');
        exit;
    }

    $rareteAuto = ['', 'commun', 'rare', 'super rare', 'epique', 'legendaire'];
    if (!in_array($_POST['rarete'], $rareteAuto)) {
        header('location: commande_carte_modifier.php?message=Valeur de rareté invalide');
        exit;
    }

        $statement = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
        $statement->execute([
            'id'=>$_POST['attaque1']
        ]);
        $results1 = $statement->fetch();
    if (!empty($_POST['attaque1'])){
        if (!$results1){
            header('location: commande_carte_modifier.php?message=Attaque 1 introuvable');
            exit;
        }
    }
    
    
        $statement2 = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
        $statement2->execute([
            'id'=>$_POST['attaque2']
        ]);
        $results2 = $statement2->fetch();
    if (!empty($_POST['attaque2'])){
        if (!$results2){
            header('location: commande_carte_modifier.php?message=Attaque 2 introuvable');
            exit;
        }
    }
    
        $statement3 = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
        $statement3->execute([
            'id'=>$_POST['attaquetalent']
        ]);
        $results3 = $statement3->fetch();
    if (!empty($_POST['attaquetalent'])){
        if (!$results3){
            header('location: commande_carte_modifier.php?message=Attaque talent introuvable');
            exit;
        }
    }

    $total = $bdd->prepare("SELECT id_attaque, type_attaque FROM attacher WHERE id_carte = :carte AND type_attaque = :attaque");
    $total->execute([
        'carte'=>$comm[3],
        'attaque'=>'Attaque 1'
    ]);
    $resultstotal1 = $total->fetch();

    $total->execute([
        'carte'=>$comm[3],
        'attaque'=>'Attaque 2'
    ]);
    $resultstotal2 = $total->fetch();

    $total->execute([
        'carte'=>$comm[3],
        'attaque'=>'Talent'
    ]);
    $resultstotal3 = $total->fetch();

    if (!empty($_POST['attaque1'])){
        if (($_POST['attaque1'] == $_POST['attaque2']) ||
            ($_POST['attaque1'] == $_POST['attaquetalent'])){
                header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
                exit;
            }
        }

    if (!empty($_POST['attaque2'])){
            if (($_POST['attaque2'] == $_POST['attaquetalent'])){
                    header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
                    exit;
                }
            }

    $liste = [$_POST['attaque1'], $_POST['attaque2'], $_POST['attaquetalent']];

    if (!empty($liste[0]) && empty($liste[1]) && isset($resultstotal2['id_attaque'])){
        if ($liste[0] == $resultstotal2['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }

    }
    if (!empty($liste[0]) && empty($liste[2]) && isset($resultstotal3['id_attaque'])){
        if ($liste[0] == $resultstotal3['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
        
    }
    if (!empty($liste[1]) && empty($liste[0])){
        if ($liste[1] == $resultstotal1['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
        
    }
    if (!empty($liste[1]) && empty($liste[2]) && isset($resultstotal3['id_attaque'])){
        if ($liste[1] == $resultstotal3['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
        
    }
    if (!empty($liste[2]) && empty($liste[0])){
        if ($liste[2] == $resultstotal1['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
        
    }
    if (!empty($liste[2]) && empty($liste[1]) && isset($resultstotal2['id_attaque'])){
        if ($liste[2] == $resultstotal2['id_attaque']){
            header('location: commande_carte_modifier.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
        
    }

    if(isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $acceptable =['image/png'];
        if(!in_array($_FILES['image']['type'], $acceptable)){
            header('location: commande_carte_modifier.php?message=L\'image doit etre un png');
            exit;
            
        }
    $maxSize = 500 * 1024;
    if($_FILES['image']['size']> $maxSize){
        header('location: commande_carte_modifier.php?message=L\'image ne doit pas dépasser 500 Ko.');
        exit;
    }
    $filename =  $results['id_carte'] . '.png';
    
    $deplacement = '../../carte/'; 
    $telechargement = $deplacement . basename($filename);
    
    if (!move_uploaded_file($_FILES['image']['tmp_name'], $telechargement)) {
        header('location: commande_carte_modifier.php?message=Erreur lors du téléchargement du fichier.');
        exit;
    }

}

    if (isset($_POST['terrain']) && !empty($_POST['terrain'])){
        $v = 'statut';
        $x = $_POST['terrain'];
        include ("commande_carte_modifier2.php");
    } else {
        $v = 'statut';
        $x = 'heros';
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['nom']) && !empty($_POST['nom'])){
        $v = 'nom_carte';
        $x = $_POST['nom'];
        include ("commande_carte_modifier2.php");
    } if (isset($_FILES['image']) && $_FILES['image']['error'] == 0){
        $v = 'image';
        $x = $filename;
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['rarete']) && !empty($_POST['rarete'])){
        $v = 'rarete';
        $x = $_POST['rarete'];
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['talent']) && !empty($_POST['talent'])){
        $v = 'talent';
        $x = $_POST['talent'];
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['pays']) && !empty($_POST['pays'])){
        $v = 'pays';
        $x = $_POST['pays'];
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['religion']) && !empty($_POST['religion'])){
        $v = 'religion';
        $x = $_POST['religion'];
        include ("commande_carte_modifier2.php");
    } if (isset($_POST['description']) && !empty($_POST['description'])){
        $v = 'description';
        $x = $_POST['description'];
        include ("commande_carte_modifier2.php");
    }

    if (isset($_POST['pv']) && !empty($_POST['pv'])){
        $v = 'pv';
        $x = $_POST['pv'];
        include ("commande_carte_modifier3.php");
    } if (isset($_POST['atk']) && !empty($_POST['atk'])){
        $v = 'atk';
        $x = $_POST['atk'];
        include ("commande_carte_modifier3.php");
    } if (isset($_POST['def']) && !empty($_POST['def'])){
        $v = 'def';
        $x = $_POST['def'];
        include ("commande_carte_modifier3.php");
    } if (isset($_POST['vit']) && !empty($_POST['vit'])){
        $v = 'vit';
        $x = $_POST['vit'];
        include ("commande_carte_modifier3.php");
    } if (isset($_POST['esq']) && !empty($_POST['esq'])){
        $v = 'esq';
        $x = $_POST['esq'];
        include ("commande_carte_modifier3.php");
    } if (isset($_POST['prs']) && !empty($_POST['prs'])){
        $v = 'prs';
        $x = $_POST['prs'];
        include ("commande_carte_modifier3.php");
    }

    $statement = $bdd->prepare("UPDATE ATTACHER SET id_attaque = :attaque WHERE id_carte = :carte && type_attaque = :type");
    $insert = $bdd->prepare("INSERT INTO ATTACHER (id_carte,id_attaque,type_attaque) VALUES (:carte,:attaque,:type)");

    if (!empty($_POST['attaque1'])){
        $statement->execute([
            'attaque'=>$_POST['attaque1'],
            'carte'=>$comm[3],
            'type'=>'Attaque 1'
        ]);
    }

    if (!empty($_POST['attaque2'])){
        if (isset($resultstotal2['id_attaque'])){
        $statement->execute([
            'attaque'=>$_POST['attaque2'],
            'carte'=>$comm[3],
            'type'=>'Attaque 2'
        ]);
    } else {
        $insert->execute([
            'carte'=>$comm[3],
            'attaque'=>$_POST['attaque2'],
            'type'=>'Attaque 2'
        ]);
    }
    }
    if (!empty($_POST['attaquetalent'])){
        if (isset($resultstotal3['id_attaque'])){
        $statement->execute([
            'attaque'=>$_POST['attaquetalent'],
            'carte'=>$comm[3],
            'type'=>'Talent'
        ]);
    } else {
        $insert->execute([
            'carte'=>$comm[3],
            'attaque'=>$_POST['attaquetalent'],
            'type'=>'Talent'
        ]);
    }
    }

    if (!empty($_POST['attaque2_2']) && $_POST['attaque2_2'] == 'none'){
        $delete = $bdd->prepare("DELETE FROM attacher WHERE id_carte = :carte AND type_attaque = 'Attaque 2'");
        $delete->execute([
            'carte'=>$comm[3]
        ]);
}

    if (!empty($_POST['attaquetalent_2']) && $_POST['attaquetalent_2'] == 'none'){
        $delete = $bdd->prepare("DELETE FROM attacher WHERE id_carte = :carte AND type_attaque = 'Talent'");
        $delete->execute([
            'carte'=>$comm[3]
        ]);
}

header('location: ../indexad.php?message=Commande effectuée avec succes');
exit;
}

?>

<form method="post" action="commande_carte_modifier.php?modif" enctype ="multipart/form-data">

<?php

include('headerad_com.php');

include('../../getmessage.php');

echo "<h1>Modification de la carte" . $comm[3] . "</h1>
<h3> Laissez vide si vous ne souhaitez pas modifier les parties correspondantes </h3>";

echo '<h3> Données actuelles concernant la carte </h3><p>' .

$results['statut'] . '</p>
<p>Nom actuel : ' . $results['nom_carte'] . '</p>
<p>Image actuelle :<br> <img src="../../carte/' . $results['image'] . '" style="max-width: 360; height: auto"></p>
<p>Rarete actuelle : ' . $results['rarete'] . '</p>
<p>Talent actuel : ' . $results['talent'] . '</p>
<p>Pays actuel : ' . $results['pays'] . '</p>
<p>Religion actuelle : ' . $results['religion'] . '</p>
<p>Description actuelle : ' . $results['description'] . '</p>

<h3> Statistiques </h3>

<p>Nombre de points de vie actuel : ' . $statresult['pv'] . '</p>
<p>Attaque actuelle : ' . $statresult['atk'] . '</p>
<p>Defense actuelle : ' . $statresult['def'] . '</p>
<p>Vitesse actuelle : ' . $statresult['vit'] . '</p>
<p>Esquive actuelle : ' . $statresult['esq'] . '</p>
<p>Precision actuelle : ' . $statresult['prs'] . '</p>

<h3> Attaques </h3>';

foreach($liens as $attak){
echo '</p>' . $attak['type_attaque'] . ' : ' . $attak['nom'] . '</p>';
}

?>


<h3> Nouvelles données concernant la carte </h3>

    <h3> N'oubliez pas de cocher si votre carte est un terrain </h3>

    <p><label>Terrain ?</label>
    <input type ="checkbox" name="terrain" value='terrain'> </p>

    <p><label>Inserer le nom</label>
    <input type ="text" name="nom"></p>
    <p><label>Inserer l'image de la carte</label>
    <input type ="file" name="image" accept ="image/png"></p>
    <p><label>Inserer la rarete de la carte(commun, rare, super rare, epique, legendaire)</label>
    <input type ="text" name="rarete"></p>
    <p><label>Inserer le talent de cette carte</label>
    <input type ="text" name="talent"></p>
    <p><label>Inserer le pays de la carte</label>
    <input type ="text" name="pays"></p>
    <p><label>Inserer la religion de la carte</label>
    <input type ="text" name="religion"></p>
    <p><label>Inserer la description de la carte</label>
    <input type ="text" name="description"></p>

    <h3> Statistiques </h3>
    
    <p><label>Inserer son nombre de points de vie</label>
    <input type ="text" name="pv"></p>
    <p><label>Inserer son attaque</label>
    <input type ="text" name="atk"></p>
    <p><label>Inserer sa défense</label>
    <input type ="text" name="def"></p>
    <p><label>Inserer sa vitesse</label>
    <input type ="text" name="vit"></p>
    <p><label>Inserer son esquive</label>
    <input type ="text" name="esq"></p>
    <p><label>Inserer sa precision</label>
    <input type ="text" name="prs"></p> 

    <h3> Attaques </h3>

    <p><label>Inserer l'id de sa 1ere attaque</label>
    <input type ="text" name="attaque1"></p> 
    <p><label>Inserer l'id de sa 2eme attaque</label>
    <input type ="text" name="attaque2"></p>
    <p><label>Pas d'attaque 2</label>
    <input type ="checkbox" name="attaque2_2" value="none"></p>
    <p><label>Inserer l'id de son attaque talent</label>
    <input type ="text" name="attaquetalent"></p>
    <p><label>Pas d'attaque talent</label>
    <input type ="checkbox" name="attaquetalent_2" value="none"></p>
    <input type="submit" value="Envoyer">
</form>