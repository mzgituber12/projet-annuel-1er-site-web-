<?php session_start();
include('bdd.php');

$SELECT = $bdd->prepare("SELECT monnaie, boutiquetemp1_1, boutiquetemp2_1, boutiquetemp3_1 FROM utilisateur WHERE id_utilisateur = :id");
$SELECT->execute([
    "id"=>$_SESSION['id']
]);
$results = $SELECT->fetch();

if (isset($_GET['number'])){
if ($_GET['number'] == 'numb1'){
    if ($results['monnaie'] >= $_SESSION['price1']){
        $boutiquetemp = 'boutiquetemp1';
        $price = $_SESSION['price1'];
        $coffre = $results['boutiquetemp1_1'];
        include('boutique_verif2.php');
        header('location:boutique.php?message=Achat effectué avec succes, le coffre se trouve desormais dans votre inventaire');
        exit;
    }else{
        header('location:boutique.php?message=Vous n\'avez pas assez de coins');
        exit;
    }
}

if ($_GET['number'] == 'numb2'){
    if ($results['monnaie'] >= $_SESSION['price2']){
        $boutiquetemp = 'boutiquetemp2';
        $price = $_SESSION['price2'];
        $coffre = $results['boutiquetemp2_1'];
        include('boutique_verif2.php');
        header('location:boutique.php?message=Achat effectué avec succes, le coffre se trouve desormais dans votre inventaire');
        exit;
    }else{
        header('location:boutique.php?message=Vous n\'avez pas assez de coins');
        exit;
    }
}

if ($_GET['number'] == 'numb3'){
    if ($results['monnaie'] >= $_SESSION['price3']){
        $boutiquetemp = 'boutiquetemp3';
        $price = $_SESSION['price3'];
        $coffre = $results['boutiquetemp3_1'];
        include('boutique_verif2.php');
        header('location:boutique.php?message=Achat effectué avec succes, le coffre se trouve desormais dans votre inventaire');
        exit;
    }else{
        header('location:boutique.php?message=Vous n\'avez pas assez de coins');
        exit;
    }
}
}

if (!isset($_GET['number'])){
    if ($_GET['coffre'] == 'Coffre Commun'){
        $price = 250;
        $coffre = 1;
    } else if ($_GET['coffre'] == 'Coffre Rare'){
        $price = 600;
        $coffre = 2;
    } else if ($_GET['coffre'] == 'Coffre Super Rare'){
        $price = 1700;
        $coffre = 3;
    } else if ($_GET['coffre'] == 'Coffre Epique'){
        $price = 4000;
        $coffre = 4;
    } else if ($_GET['coffre'] == 'Coffre Legendaire'){
        $price = 10000;
        $coffre = 5;
    } else if ($_GET['coffre'] == 'Goodies'){
        $price = $_SESSION['pricegoodies'];
    } else {
        header('location:boutique.php?message=Erreur de saisie dans l\'url');
        exit;
    }

    if ($results['monnaie'] >= $price){

        if ($_GET['coffre'] == 'Goodies'){
            $goodies=$bdd->prepare("UPDATE UTILISATEUR SET goodies = goodies + 1, monnaie = monnaie - :monnaie WHERE id_utilisateur = :id");
            $goodies->execute([
                'monnaie'=>$_SESSION['pricegoodies'],
                'id'=>$_SESSION['id']
            ]);

            header('location:boutique.php?message=Achat effectué avec succes');
            exit;
        } else {
        include('boutique_verif2.php');
        header('location:boutique.php?message=Achat effectué avec succes, le coffre se trouve desormais dans votre inventaire');
        exit;
        }

    } else {
        header('location:boutique.php?message=Vous n\'avez pas assez de coins');
        exit;
    }
}

header('location:boutique.php');
exit;

?>