<?php session_start();
    
    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }
    
include('bdd.php');

$random = rand(1,100);

$_SESSION['opencoffre'] = $_GET['coffre'];

if(isset($_GET['coffre'])){

if($_GET['coffre'] == 'Coffre commun'){

    $id=1;
    
if ($random == 1){
    $rarete = 'legendaire';
    $_SESSION['coffre'] = '<h1> WOAW, VOUS AVEZ OBTENU UNE CARTE LEGENDAIRE !!! </h1>';
} else if ($random <= 5){
    $rarete = 'epique';
    $_SESSION['coffre'] = '<h1> INCROYABLE, vous avez obtenu une carte épique </h1>';
} else if ($random <= 18){
    $rarete = 'super rare';
    $_SESSION['coffre'] = '<h1> Bravo, vous avez obtenu une carte super rare </h1>';
} else if ($random <= 40){
    $rarete = 'rare';
    $_SESSION['coffre'] = '<h1> Pas mal, vous avez obtenu une carte rare </h1>';
} else {
    $rarete = 'commun';
    $_SESSION['coffre'] = '<h1> Vous avez obtenu une carte commun </h1>';
}

} else if ($_GET['coffre'] == 'Coffre rare'){

    $id=2;

    if ($random <= 3){
        $rarete = 'legendaire';
        $_SESSION['coffre'] = '<h1> WOAW, VOUS AVEZ OBTENU UNE CARTE LEGENDAIRE !!! </h1>';
    } else if ($random <= 14){
        $rarete = 'epique';
        $_SESSION['coffre'] = '<h1> INCROYABLE, vous avez obtenu une carte épique </h1>';
    } else if ($random <= 38){
        $rarete = 'super rare';
        $_SESSION['coffre'] = '<h1> Bravo, vous avez obtenu une carte super rare </h1>';
    } else {
        $rarete = 'rare';
        $_SESSION['coffre'] = '<h1> Pas mal, vous avez obtenu une carte rare </h1>';
    }

} else if ($_GET['coffre'] == 'Coffre super rare'){

    $id=3;

    if ($random <= 8){
        $rarete = 'legendaire';
        $_SESSION['coffre'] = '<h1> WOAW, VOUS AVEZ OBTENU UNE CARTE LEGENDAIRE !!! </h1>';
    } else if ($random <= 30){
        $rarete = 'epique';
        $_SESSION['coffre'] = '<h1> INCROYABLE, vous avez obtenu une carte épique </h1>';
    } else {
        $rarete = 'super rare';
        $_SESSION['coffre'] = '<h1> Bravo, vous avez obtenu une carte super rare </h1>';
    }
    
} else if ($_GET['coffre'] == 'Coffre epique'){

    $id=4;

    if ($random <= 24){
        $rarete = 'legendaire';
        $_SESSION['coffre'] = '<h1> WOAW, VOUS AVEZ OBTENU UNE CARTE LEGENDAIRE !!! </h1>';
    } else {
        $rarete = 'epique';
        $_SESSION['coffre'] = '<h1> INCROYABLE, vous avez obtenu une carte épique </h1>';
    }

} else if ($_GET['coffre'] == 'Coffre legendaire'){

    $id=5;

        $rarete = 'legendaire';
        $_SESSION['coffre'] = '<h1> WOAW, VOUS AVEZ OBTENU UNE CARTE LEGENDAIRE !!! </h1>';
} else {
    header('location:inventaire.php');
}

$_SESSION['idcoffre'] = $id;

include('verification/verif_coffre.php');

$all_cards = $bdd->prepare("SELECT nom_carte, rarete FROM CARTE WHERE rarete = ? AND (statut = 'heros' OR statut = 'terrain')");
$all_cards->execute([$rarete]);
$results = $all_cards->fetchall();

$nom_cartes = array_column($results, 'nom_carte');

$id = $bdd->prepare("SELECT id_carte, nom_carte FROM CARTE WHERE nom_carte = ?");
$id->execute([$nom_cartes[array_rand($nom_cartes)]]);
$results2 = $id->fetch(PDO::FETCH_ASSOC);

$select = $bdd->prepare("SELECT nb FROM CONTIENT WHERE id_inventaire = ? AND id_carte = ?");
$select->execute([$_SESSION['inventaire'], $results2['id_carte']]);
$verif = $select->fetch(PDO::FETCH_ASSOC);

$nb = $verif['nb'] ?? 0; 

if ($verif) {    
    $nb += 1;
    $update = $bdd->prepare("UPDATE CONTIENT SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
    $update->execute([$nb, $_SESSION['inventaire'], $results2['id_carte']]);
} else {
    $insert = $bdd->prepare("INSERT INTO CONTIENT (id_inventaire, id_carte, nb) VALUES (?, ?, ?)");
    $insert->execute([$_SESSION['inventaire'], $results2['id_carte'], 1]);
}

$_SESSION['carteobtenue'] = $results2['nom_carte'];

$_SESSION['coffre2'] = "<h2><a href='../carte.php?id=" . $results2['id_carte'] . "'>" . $results2['nom_carte'] . "</a></h2>";

$_SESSION['verificate'] = 'yes';

header('location:coffre_results.php');
exit;
}

?>
