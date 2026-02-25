<?php 

if (isset($_GET['number'])){

$all = $bdd->prepare("UPDATE utilisateur SET $boutiquetemp = 0, monnaie = monnaie-$price WHERE id_utilisateur = :id");
$all->execute([
    'id'=>$_SESSION['id']
]);

} else if (!isset($_GET['number'])){
    $all = $bdd->prepare("UPDATE utilisateur SET monnaie = monnaie-$price WHERE id_utilisateur = :id");
    $all->execute([
    'id'=>$_SESSION['id']
]);

}

$selectinv=$bdd->prepare("SELECT nb FROM CONTIENT_COFFRE WHERE id_inventaire=:inventaire AND id_coffre=:coffre");
$selectinv->execute([
    'inventaire'=>$_SESSION['inventaire'],
    'coffre'=>$coffre
]);
$resultscarte = $selectinv->fetch();

if (!$resultscarte){
    $statement = $bdd->prepare('INSERT INTO CONTIENT_COFFRE VALUES (:id, :coffre, 1)');
    $statement->execute([
        'id'=>$_SESSION['inventaire'],
        'coffre'=>$coffre
    ]);
} else {
    $compteur = $resultscarte['nb'] + 1;
    $statement = $bdd->prepare('UPDATE CONTIENT_COFFRE SET nb = :nb WHERE id_inventaire = :id AND id_coffre = :coffre');
    $statement->execute([
        'nb'=>$compteur,
        'id'=>$_SESSION['inventaire'],
        'coffre'=>$coffre
    ]);
}