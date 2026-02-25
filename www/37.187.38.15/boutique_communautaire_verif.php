<?php session_start();
include ('bdd.php');

if (!isset($_SESSION['id'])){
    header('location:index.php');
    exit;
}

$SELECT = $bdd->prepare("SELECT monnaie, boutiquetemp1_1, boutiquetemp2_1, boutiquetemp3_1 FROM utilisateur WHERE id_utilisateur = :id");
$SELECT->execute([
    "id"=>$_SESSION['id']
]);
$results = $SELECT->fetch();


if (isset($_GET['achat'])){

    $verif = $bdd->prepare("SELECT id_vente FROM VENTE_CARTE WHERE id_vente = :vente");
    $verif->execute([
        'vente'=>$_GET['idvente']
    ]);
    $verif2 = $verif->fetch();
    if (!$verif2){
        header('location:boutique.php?message=Une erreur est survenue');
        exit;
    }

    if ($results['monnaie'] <= $_GET['prix']){
        header('location:boutique.php?message=Vous n\'avez pas assez de coins');
        exit;
    }

    $prix = $bdd->prepare("UPDATE UTILISATEUR SET monnaie = monnaie - :monnaie WHERE id_utilisateur = :id");
    $prix->execute([
        'monnaie'=>$_GET['prix'],
        'id'=>$_SESSION['id']
    ]);

    $prix = $bdd->prepare("UPDATE UTILISATEUR SET monnaie = monnaie + :monnaie WHERE pseudo = :pseudo");
    $prix->execute([
        'monnaie'=>$_GET['prix'],
        'pseudo'=>$_GET['user']
    ]);

    $inventaire = $bdd->prepare('SELECT nb FROM CONTIENT WHERE id_inventaire = :id AND id_carte = :carte');
    $inventaire->execute([
        'id'=>$_SESSION['inventaire'],
        'carte'=>$_GET['achat']
    ]);
    $inv = $inventaire->fetch();
    if ($inv){
        $nb = $inv['nb'] + 1;
        $update = $bdd->prepare("UPDATE CONTIENT SET nb = :nb WHERE id_inventaire = :id AND id_carte = :carte");
        $update->execute([
            'nb'=>$nb,
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_GET['achat']
        ]);
    } else {
        $update = $bdd->prepare("INSERT INTO CONTIENT VALUES (:id, :carte, 1)");
        $update->execute([
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_GET['achat']
        ]);
    }

    $suppvente = $bdd->prepare("DELETE FROM VENTE_CARTE WHERE id_vente = :vente");
    $suppvente->execute([
        'vente'=>$_GET['idvente']
    ]);

    header('location:boutique.php?message=Achat effectué avec succes');
    exit;
}

if (isset($_GET['annuler'])){

    $verif = $bdd->prepare("SELECT id_vente FROM VENTE_CARTE WHERE id_vente = :vente");
    $verif->execute([
        'vente'=>$_GET['id']
    ]);
    $verif2 = $verif->fetch();
    if (!$verif2){
        header('location:boutique.php?message=Une erreur est survenue');
        exit;
    }

    $inventaire = $bdd->prepare('SELECT nb FROM CONTIENT WHERE id_inventaire = :id AND id_carte = :carte');
    $inventaire->execute([
        'id'=>$_SESSION['inventaire'],
        'carte'=>$_GET['carte']
    ]);
    $inv = $inventaire->fetch();
    if ($inv){
        $nb = $inv['nb'] + 1;
        $update = $bdd->prepare("UPDATE CONTIENT SET nb = :nb WHERE id_inventaire = :id AND id_carte = :carte");
        $update->execute([
            'nb'=>$nb,
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_GET['carte']
        ]);
    } else {
        $update = $bdd->prepare("INSERT INTO CONTIENT VALUES (:id, :carte, 1)");
        $update->execute([
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_GET['carte']
        ]);
    }

    $suppvente = $bdd->prepare("DELETE FROM VENTE_CARTE WHERE id_vente = :vente");
    $suppvente->execute([
        'vente'=>$_GET['id']
    ]);

    header('location:boutique.php?message=Carte retirée avec succes');
    exit;
}




if (empty($_POST['carte'])){
    header('location:boutique_communautaire.php?message=Vous devez selectionner une carte à vendre');
    exit;
}

if (empty($_POST['price'])){
    header('location:boutique_communautaire.php?message=Vous devez indiquer un prix');
    exit;
}

if (!is_numeric($_POST['price'])){
    header('location:boutique_communautaire.php?message=Le prix est invalide');
    exit;
}

$transac = $bdd->prepare("INSERT INTO vente_carte (id_vendeur, id_carte, prix) VALUES (:iduser, :idcarte, :prix)");
$transac->execute([
    'iduser'=>$_SESSION['id'],
    'idcarte'=>$_POST['carte'],
    'prix'=>$_POST['price'],
]);

$inventaire = $bdd->prepare('SELECT nb FROM CONTIENT WHERE id_inventaire = :id');
    $inventaire->execute([
        'id'=>$_SESSION['inventaire']
    ]);
    $inv = $inventaire->fetch();
    if ($inv['nb'] > 1){
        $nb = $inv['nb'] - 1;
        $update = $bdd->prepare("UPDATE CONTIENT SET nb = :nb WHERE id_inventaire = :id AND id_carte = :carte");
        $update->execute([
            'nb'=>$nb,
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_POST['carte']
        ]);
    } else {
        $update = $bdd->prepare("DELETE FROM CONTIENT WHERE id_inventaire = :id AND id_carte = :carte");
        $update->execute([
            'id'=>$_SESSION['inventaire'],
            'carte'=>$_POST['carte']
        ]);
    }

header('location:boutique.php?message=Carte mise en vente dans la boutique communautaire');
exit;

?>