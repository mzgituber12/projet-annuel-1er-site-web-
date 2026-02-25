<?php session_start();
include('../pasadmin.php');

$commande = $_SESSION['commande'];
$comm = explode('/',$commande);

if (isset($comm[4]) || !isset($comm[3])){
    header('location: ../indexad.php?message=Commande erronée');
    exit;
}

include('../../bdd.php');

$statement = $bdd->prepare('SELECT * FROM ATTAQUE_CARTE WHERE id_attaque = :id');
$statement->execute([
    'id'=>$comm[3]
]);
$results=$statement->fetch();
if (empty($results)){
    header('location: ../indexad.php?message=L\'attaque avec cet id n\'existe pas');
    exit;
}

if (isset($_GET['modif'])){

    if(!empty($_POST['degats'])){
    if (!ctype_digit($_POST['degats'])){
        header('location: commande_attaque_modifier.php?message=Les valeurs rentrées sont invalides');
        exit;
    }
}

    $effetAuto = ['', 'none', 'bruler','geler','paralyser','empoisonner','apeurer','guerire'];
    if (!in_array($_POST['effet'], $effetAuto) || !in_array($_POST['effet2'], $effetAuto) || !in_array($_POST['effet3'], $effetAuto)) {
        header('location: commande_attaque_modifier.php?message=Effets invalides');
        exit;
    }
    if (isset($_POST['nom']) && !empty($_POST['nom'])){
        $v = 'nom';
        $x = $_POST['nom'];
        include ("commande_attaque_modifier2.php");
    } if (isset($_POST['degats']) && !empty($_POST['degats'])){
        $v = 'degats';
        $x = $_POST['degats'];
        include ("commande_attaque_modifier2.php");
    } if (isset($_POST['portee']) && !empty($_POST['portee'])){
        $v = 'portee';
        $x = $_POST['portee'];
        include ("commande_attaque_modifier2.php");

    } if (isset($_POST['effet']) && !empty($_POST['effet']) && $_POST['effet_2'] != 'none'){
        $v = 'effet';
        $x = $_POST['effet'];
        include ("commande_attaque_modifier2.php");
    } else if ($_POST['effet_2'] == 'none'){
        $v = 'effet';
        $x = '';
        include ("commande_attaque_modifier2.php");
    }

    if (isset($_POST['effet2']) && !empty($_POST['effet2']) && $_POST['effet2_2'] != 'none'){
        $v = 'effet2';
        $x = $_POST['effet2'];
        include ("commande_attaque_modifier2.php");
    } else if ($_POST['effet2_2'] == 'none'){
            $v = 'effet2';
            $x = '';
            include ("commande_attaque_modifier2.php");
    }
    
    if (isset($_POST['effet3']) && !empty($_POST['effet3']) && $_POST['effet3_2'] != 'none'){
        $v = 'effet3';
        $x = $_POST['effet3'];
        include ("commande_attaque_modifier2.php");
    } else if ($_POST['effet3_2'] == 'none'){
        $v = 'effet3';
        $x = '';
        include ("commande_attaque_modifier2.php");
    }
    header('location:../indexad.php?message=Commande effectuée avec succes');
    exit;
    }

?>

<form method="post" action="commande_attaque_modifier.php?modif" enctype ="multipart/form-data">

<?php
$effets = $results['effet'];

if (!empty($results['effet2'])) {
    $effets .= ", " . $results['effet2'];
}

if (!empty($results['effet3'])) {
    $effets .= ", " . $results['effet3'];
}

if (empty($effets)) {
    $effets = 'Aucun effet';
}

include('headerad_com.php');

echo "<h1>Modification de l'attaque " . $comm[3] . "</h1>";

include('../../getmessage.php');

echo '<h3> Laissez vide si vous ne souhaitez pas modifier les parties correspondantes </h3>
<h3> Données actuelles concernant l\'attaque </h3><p>Nom actuel : ' . $results['nom'] . '</p>
<p>Degats actuels : ' . $results['degats'] . '</p>
<p>Portée actuelle : ' . $results['portee'] .'</p>
<p>Effets actuels : ' . $effets . "</p>" ?>

<h3> Nouvelles données concernant l'attaque </h3>

    <p><label>Inserer le nom</label>
    <input type ="text" name="nom"></p>
    <p><label>Inserer les degats</label>
    <input type ="text" name="degats"></p>
    <p><label>Inserer la portee</label>
    <input type ="text" name="portee"></p>

    <h3> Les effets autorisés : bruler, geler, paralyser, empoisonner, apeurer, guerire </h3>

    <p><label>Inserer l'effet de l'attaque</label>
    <input type ="text" name="effet"></p>
    <p><label>Pas d'effets</label>
    <input type ="checkbox" name="effet_2" value="none"></p>
    <p><label>Inserer le deuxieme effet de l'attaque</label>
    <input type ="text" name="effet2"></p>
    <p><label>Pas d'effets 2</label>
    <input type ="checkbox" name="effet2_2" value="none"></p>
    <p><label>Inserer le troisieme effet de l'attaque</label>
    <input type ="text" name="effet3"></p>
    <p><label>Pas d'effets 3</label>
    <input type ="checkbox" name="effet3_2" value="none"></p>
    <input type="submit">
</form>