<?php
session_start();
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('Europe/Paris');

try {
    $bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
    $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    http_response_code(500);
    echo json_encode(["erreur" => "Connexion à la base impossible", "details" => $e->getMessage()]);
    exit;
}

$id_combat = $_GET['id_combat'] ?? null;
if (!$id_combat || !is_numeric($id_combat)) {
    echo json_encode(["peut_lancer" => false, "erreur" => "id_combat invalide"]);
    exit;
}

try {
    $statement = $bdd->prepare("SELECT id_utilisateur, date_ping FROM COMBAT_PRESENCE WHERE id_combat = ?");
    $statement->execute([$id_combat]);
    $presences = $statement->fetchAll(PDO::FETCH_ASSOC);

    $temps = time();
    $connectes = 0;
    foreach ($presences as $presence) {
        if (strtotime($presence['date_ping']) >= $temps - 30) {
            $connectes++;
        }
    }
    if ($connectes < 2) {
        echo json_encode(["peut_lancer" => false, "erreur" => "Pas assez de joueurs connectés"]);
        exit;
    }

    $statement_combat = $bdd->prepare("SELECT id_joueur1, id_joueur2 FROM COMBAT WHERE id_combat = ?");
    $statement_combat->execute([$id_combat]);
    $combat = $statement_combat->fetch(PDO::FETCH_ASSOC);

    if (!$combat || !isset($combat['id_joueur1']) || !isset($combat['id_joueur2'])) {
        echo json_encode(["peut_lancer" => false, "erreur" => "Combat introuvable ou incomplet"]);
        exit;
    }
    
    $verif_carte1 = $bdd->prepare("SELECT COUNT(*) 
                                FROM CARTEDECK cd
                                JOIN DECK d ON cd.id_deck = d.id_deck
                                WHERE d.id_utilisateur = ?");
    $verif_carte1->execute([$combat['id_joueur1']]);
    $nb1 = $verif_carte1->fetchColumn();

    $verif_carte2 = $bdd->prepare("SELECT COUNT(*) 
                                FROM CARTEDECK cd
                                JOIN DECK d ON cd.id_deck = d.id_deck
                                WHERE d.id_utilisateur = ?");
    $verif_carte2->execute([$combat['id_joueur2']]);
    $nb2 = $verif_carte2->fetchColumn();

    $peut_lancer = ($nb1 >= 6 && $nb2 >= 6);
    echo json_encode([
        "peut_lancer" => $peut_lancer,
        "cartes_joueur1" => $nb1,
        "cartes_joueur2" => $nb2
    ]);
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(["peut_lancer" => false, "erreur" => "Erreur serveur", "details" => $e->getMessage()]);
    exit;
}