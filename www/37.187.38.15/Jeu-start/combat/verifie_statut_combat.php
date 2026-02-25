<?php
session_start();


if (isset($_SESSION['id_combat'])) {
    $id_combat = $_SESSION['id_combat'];

    $bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
    // Prépare la requête pour vérifier si le combat a commencé
    $statement = $bdd->prepare("SELECT statut FROM COMBAT WHERE statut = 'comence' AND id_combat = ?");
    $statement->execute([$id_combat]);
    $test_combat = $statement->fetchAll(PDO::FETCH_ASSOC);

    // Vérifie si le combat a commencé
    $combat_commence = empty($test_combat) ? false : true;

    // Retourne une réponse JSON
    echo json_encode(['combat_commence' => $combat_commence]);
} else {
    // Si 'id_combat' n'existe pas dans la session, retourne une erreur JSON
    echo json_encode(['error' => 'ID combat non défini dans la session']);
}
?>