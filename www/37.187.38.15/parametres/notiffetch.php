<?php session_start();

include('../bdd.php');

$mapping = [
    'message' => 'messagenotif',
    'echange' => 'echangenotif',
    'mail' => 'actunotif',
    'ami' => 'aminotif'
];

foreach ($mapping as $getKey => $dbColumn) {
    if (isset($_GET[$getKey])) {
        $value = $_GET['value'] === 'actif' ? 1 : 0;

        $stmt = $bdd->prepare("UPDATE UTILISATEUR SET $dbColumn = ? WHERE id_utilisateur = ?");
        $stmt->execute([$value, $_SESSION['id']]);

        exit;
    }
}