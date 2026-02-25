<?php session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

function addContenuLog($pseudo, $a, $nom, $rareté, $effet, $talent, $infos){
    $stream = fopen('../log/addcontenu_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    if($a == 'Combattant'){
        if(!empty($infos)){
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $rareté . ' | '  . $talent . ' | ' . $infos . "\n";;
        } else {
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $rareté . ' | '  . $talent . "\n";;   
        }
    }
    else if($a == 'Terrain'){
        if(!empty($infos)){
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $rareté . ' | '  . $infos . "\n";;
        } else {
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $rareté . "\n";;   
        }
    }
    else if($a == 'Autre chose'){
        if(!empty($infos)){
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $effet . ' | '  . $infos . "\n";;
        } else {
            $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $pseudo . ' souhaite ajouter l\'item suivant : ' . "\n" .
            $a . ' | ' . $nom . ' | ' . $effet . "\n";
        }
    }
    fputs($stream, $line);
    fclose($stream);

}

if (empty($_POST['nom'])){
    header("location:../parametres/parametres_contenu.php?message=Veuillez remplir tous les champs obligatoires !");
    exit;
}

    if ($_POST['a'] == 'Combattant'){
        if(empty($_POST['talent'])){
            header("location:../parametres/parametres_contenu.php?message=Veuillez remplir tous les champs obligatoires !");
            exit;
        }
    }
    
    if ($_POST['a'] == 'Combattant' || $_POST['a'] == 'Terrain'){
        
        if (empty($_POST['rareté'])){
            header("location:../parametres/parametres_contenu.php?message=Veuillez remplir tous les champs obligatoires !");
            exit;
        }
        if ($_POST['rareté'] !== 'commun' && $_POST['rareté'] !== 'rare' && $_POST['rareté'] !== 'super-rare' && $_POST['rareté'] !== 'super rare' && $_POST['rareté'] !== 'epic'
        && $_POST['rareté'] !== 'epique' && $_POST['rareté'] !== 'épique' && $_POST['rareté'] !== 'légendaire' && $_POST['rareté'] !== 'legendaire'){
            header("location:../parametres/parametres_contenu.php?message=La rareté est invalide !");
            exit;
        }
}

if ($_POST['a'] == 'Autre chose'){
    if (empty($_POST['effet'])){
        header("location:../parametres/parametres_contenu.php?message=Veuillez remplir tous les champs obligatoires !");
        exit;
    }
}

include("../bdd.php");

$nom_carte = "SELECT id_carte FROM CARTE WHERE nom_carte = :nom";
$statement = $bdd->prepare($nom_carte);
$statement->execute([
    'nom' => $_POST['nom']
]);
$nom = $statement->fetchAll();
if (!empty($nom)){
    header("location:../parametres/parametres_contenu.php?message=Cet item existe déja !");
    exit;
}

addContenuLog($_SESSION['pseudo'], $_POST['a'], $_POST['nom'], $_POST['rareté'], $_POST['effet'], $_POST['talent'], $_POST['infos']);

header("location:../parametres/parametres.php?message=Votre suggestion d'ajout a bien été envoyée aux modérateurs");
exit;

?>