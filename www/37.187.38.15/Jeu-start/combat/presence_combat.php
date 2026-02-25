<?php
session_start();
date_default_timezone_set('Europe/Paris');
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');

$donnees = json_decode(file_get_contents("php://input"), true);
$id_combat = $donnees['id_combat'] ?? null;
$id_utilisateur = $donnees['id_utilisateur'] ?? null;

if (!$id_combat || !$id_utilisateur) {
    http_response_code(400);
    echo json_encode(["status" => "erreur", "message" => "Paramètres manquants"]);
    exit;
}

$date_now = date("Y-m-d H:i:s");

$statement = $bdd->prepare("INSERT INTO COMBAT_PRESENCE (id_combat, id_utilisateur, date_ping)
                      VALUES (?, ?, ?)
                      ON DUPLICATE KEY UPDATE date_ping = ?");
$statement->execute([$id_combat, $id_utilisateur, $date_now, $date_now]);


echo json_encode(["status" => "ok"]);