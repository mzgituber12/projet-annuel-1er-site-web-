<?php session_start();

if (!isset($_SESSION['id'])) {
    die("Erreur : Utilisateur non connecté.");
}

function supprimercompteLog(){
    $stream = fopen('../log/supprimer_compte_log.txt', 'a+');

    if (!$stream) {
        die("Erreur : Impossible d'ouvrir le fichier de log.");
    }

    $line = date('Y/m/d - H:i:s') . ' : Nom d\'utilisateur : ' . $_SESSION['pseudo'] . ' a supprimé son compte ' . "\n";
    fputs($stream, $line);
    fclose($stream);
}


supprimercompteLog();

include("../bdd.php");

$final = "DELETE FROM UTILISATEUR WHERE pseudo = :pseudo";
$statement = $bdd->prepare($final);
$statement -> execute([
    'pseudo' => $_SESSION['pseudo']
]
);

session_destroy();

header('location:../index.php?message=Compte supprimé avec succes !');

?>