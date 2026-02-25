<?php 
session_start();
include('../pasadmin.php');
include("../../bdd.php");

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[3])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

if (!isset($_SESSION['commande']) ||
empty($_SESSION['commande'])
){
header('location: ../indexad.php?message=Commande erroné ou commande vide.');
exit;
}

if (isset($_GET['modif'])){

if (!isset($_POST['rarete']) ||
empty($_POST['rarete']) ||
!isset($_POST['nom']) ||
empty($_POST['nom']) ||
!isset($_POST['talent']) ||
empty($_POST['talent']) ||
!isset($_POST['pays']) ||
empty($_POST['pays']) ||
!isset($_POST['religion']) ||
empty($_POST['religion']) ||
!isset($_POST['description']) ||
empty($_POST['description']) ||
$_FILES['image']['error'] == 4 ||
!isset($_POST['pv']) ||
empty($_POST['pv']) ||
!isset($_POST['atk']) ||
empty($_POST['atk']) ||
!isset($_POST['def']) ||
empty($_POST['def']) ||
!isset($_POST['vit']) ||
empty($_POST['vit']) ||
!isset($_POST['esq']) ||
empty($_POST['esq']) ||
!isset($_POST['prs']) ||
empty($_POST['prs']) ||
!isset($_POST['attaque1']) ||
empty($_POST['attaque1']) 
){
    header('location: commande_carte_creer.php?message=Au moins un des champs obligatoires est vide');
    exit;
}

if (!is_numeric($_POST['pv']) ||
!is_numeric($_POST['atk']) ||
!is_numeric($_POST['def']) ||
!is_numeric($_POST['vit']) ||
!is_numeric($_POST['esq']) ||
!is_numeric($_POST['prs'])
){
    header('location: commande_carte_creer.php?message=Les statistiques rentrés sont invalides');
    exit;
}

$nom = $bdd->prepare("SELECT nom_carte FROM CARTE WHERE nom_carte = :nom");
$nom->execute([
    'nom'=>$_POST['nom']
]);
if($nom->fetch()){
    header('location: commande_carte_creer.php?message=Une autre carte possede deja ce nom');
    exit;
}

$rarete = $_POST['rarete'];
$talent = $_POST['talent'];
$pays = $_POST['pays'];
$religion = $_POST['religion'];
$description = $_POST['description'];
$nom_carte = $_POST['nom'];

$pv = $_POST['pv'];
$atk = $_POST['atk'];
$def = $_POST['def'];
$vit = $_POST['vit'];
$esq = $_POST['esq'];
$prs = $_POST['prs'];

$attaque1 = $_POST['attaque1'];

$attaque2 = $_POST['attaque2'] ?? '';
$attaque3 = $_POST['attaque3'] ?? '';

if(isset($_POST['terrain']) && $_POST['terrain'] == 'terrain'){
$statut = 'terrain';
} else {
$statut = 'heros';
}

$rareteAuto = ['commun', 'rare', 'super rare', 'epique', 'legendaire'];
if (!in_array($rarete, $rareteAuto)) {
    header('location: commande_carte_creer.php?message=Valeur de rareté invalide');
    exit;
}

$acceptable =['image/png'];
if(!in_array($_FILES['image']['type'], $acceptable)){
    header('location: commande_carte_creer.php?message=L\'image doit etre un png');
    exit;
        
    }
$maxSize = 500 * 1024;
if($_FILES['image']['size']> $maxSize){
    header('location: commande_carte_creer.php?message=L\'image ne doit pas dépasser 500 Ko.');
    exit;
}

if (!empty($_POST['attaque1'])){
    $statement = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
    $statement->execute([
        'id'=>$_POST['attaque1']
    ]);

    if (!$statement->fetch()){
        header('location: commande_carte_creer.php?message=Attaque 1 introuvable');
        exit;
    }
}

if (!empty($_POST['attaque2'])){
    $statement2 = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
    $statement2->execute([
        'id'=>$_POST['attaque2']
    ]);
    if (!$statement2->fetch()){
        header('location: commande_carte_creer.php?message=Attaque 2 introuvable');
        exit;
    }
}

if (!empty($_POST['attaquetalent'])){
    $statement3 = $bdd->prepare("SELECT id_attaque FROM ATTAQUE_CARTE WHERE id_attaque = :id");
    $statement3->execute([
        'id'=>$_POST['attaquetalent']
    ]);
    if (!$statement3->fetch()){
        header('location: commande_carte_creer.php?message=Attaque talent introuvable');
        exit;
    }
}

if (!empty($_POST['attaque1'])){
    if (($_POST['attaque1'] == $_POST['attaque2']) ||
        ($_POST['attaque1'] == $_POST['attaquetalent'])){
            header('location: commande_carte_creer.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
            exit;
        }
    }

if (!empty($_POST['attaque2'])){
        if (($_POST['attaque2'] == $_POST['attaquetalent'])){
                header('location: commande_carte_creer.php?message=Une carte ne peut pas avoir plusieurs fois la même attaque');
                exit;
            }
        }



$query = 'INSERT INTO CARTE (nom_carte,rarete,talent,pays,religion,description, statut) VALUES (:nom,:rarete,:talent,:pays,:religion,:description,:statut)';
$statement = $bdd->prepare($query); 
$statement -> execute([
    'nom'=> $nom_carte,
    'rarete'=> $rarete,
    'talent'=> $talent,
    'pays'=> $pays,
    'religion'=> $religion,
    'description'=> $description,
    'statut'=> $statut
]);


$nomimage = $bdd->prepare("SELECT id_carte FROM CARTE WHERE nom_carte=:nom");
$nomimage->execute([
    'nom' =>$nom_carte
]);
$results = $nomimage->fetch();
$filename = $results['id_carte'] . ".png"; 

$deplacement = '../../carte/'; 
$telechargement = $deplacement . basename($filename);

if (!move_uploaded_file($_FILES['image']['tmp_name'], $telechargement)) {
    header('location: commande_carte_creer.php?message=Erreur lors du téléchargement du fichier.');
    exit;
}


$query = $bdd->prepare("UPDATE CARTE SET image = :image WHERE nom_carte=:nom");
$query->execute([
    'image'=>$filename,
    'nom'=>$nom_carte
]);

$query = 'INSERT INTO STATS_CARTE (id_carte,pv,atk,def,vit,esq,prs) VALUES (:idcarte,:pv,:atk,:def,:vit,:esq,:prs)';
    $statement = $bdd->prepare($query); 
    $statement -> execute([
    'idcarte'=> $results['id_carte'],
    'pv'=> $pv,
    'atk'=> $atk,
    'def'=> $def,
    'vit'=> $vit,
    'esq'=> $esq,
    'prs'=> $prs
]);

$statement = $bdd->prepare("INSERT INTO ATTACHER (id_carte, id_attaque, type_attaque) VALUES (:idcarte, :idattaque, :type)");
$statement->execute([
    'idcarte'=>$results['id_carte'],
    'idattaque'=>$_POST['attaque1'],
    'type'=>'Attaque 1'
]);

if (!empty($_POST['attaque2'])){
$statement->execute([
    'idcarte'=>$results['id_carte'],
    'idattaque'=>$_POST['attaque2'],
    'type'=>'Attaque 2'
]);
}

if (!empty($_POST['attaquetalent'])){
    $statement->execute([
        'idcarte'=>$results['id_carte'],
        'idattaque'=>$_POST['attaquetalent'],
        'type'=>'Talent'
    ]);
}

header('location: ../indexad.php?message=Commande effectuée avec succes');
exit;

}

include('headerad_com.php');

echo "<h1>Creer une carte</h1>";

if(isset($_GET['message'])){
    echo "<h2>" . htmlspecialchars($_GET['message']) . "</h2>";
}
?>

<form method="post" action="commande_carte_creer.php?modif" enctype ="multipart/form-data">
<p><label>Terrain ?</label>
<input type ="checkbox" name="terrain" value='terrain'></p>
<p><label>Inserer le nom de cette carte</label>
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

<p><label>Inserer l'id de sa 1ere attaque (obligatoire)</label>
<input type ="text" name="attaque1"></p> 
<p><label>Inserer l'id de sa 2eme attaque (facultatif)</label>
<input type ="text" name="attaque2"></p>
<p><label>Inserer l'id de son attaque talent (facultatif)</label>
<input type ="text" name="attaquetalent"></p>
<input type="submit" value="Envoyer">
</form>