<?php
header('Content-Type: application/json');
session_start();

if (!isset($_SESSION['id'])) {
    echo json_encode(['success' => false, 'message' => 'Utilisateur non authentifié.']);
    exit;
}

$data = json_decode(file_get_contents("php://input"), true);

if (!isset($data['cartes']) || !is_array($data['cartes']) || count($data['cartes']) > 6) {
    echo json_encode(['success' => false, 'message' => 'Vous devez sélectionner jusqu\'à 6 cartes.']);
    exit;
}

if (!isset($data['id_equipe']) || !is_numeric($data['id_equipe'])) {
    echo json_encode(['success' => false, 'message' => 'Aucune équipe sélectionnée ou ID invalide.']);
    exit;
}

$id_utilisateur = $_SESSION['id'];
$id_equipe = intval($data['id_equipe']);

try {
    $bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
    ]);

    $cartes_normales = [];
    $carte_terrain = null;

    foreach ($data['cartes'] as $carte) {
        if (!isset($carte['id'], $carte['type']) || !is_numeric($carte['id']) || !in_array($carte['type'], ['normal', 'terrain'])) {
            echo json_encode(['success' => false, 'message' => 'Données de carte invalides.']);
            exit;
        }
        if ($carte['type'] === 'terrain') {
            $carte_terrain = $carte; 
        } else {
            $cartes_normales[] = $carte; 
        }
    }
  
    if (!empty($cartes_normales)) {
    $delete_normal = $bdd->prepare("DELETE FROM CARTEEQUIPE WHERE id_equipe = ? AND type = 'normal'");
    $delete_normal->execute([$id_equipe]);
    }

    if (!empty($cartes_normales)) {
        $insert_normal = $bdd->prepare("
            INSERT INTO CARTEEQUIPE (id_equipe, id_carte, place, type) 
            VALUES (?, ?, ?, ?)
        ");
        foreach ($cartes_normales as $index => $carte) {
            $insert_normal->execute([$id_equipe, intval($carte['id']), $index + 1, 'normal']);
        }
    }

   
    if ($carte_terrain !== null) {
        $delete_terrain = $bdd->prepare("DELETE FROM CARTEEQUIPE WHERE id_equipe = ? AND type = 'terrain'");
        $delete_terrain->execute([$id_equipe]);

        $insert_terrain = $bdd->prepare("
            INSERT INTO CARTEEQUIPE (id_equipe, id_carte, place, type) 
            VALUES (?, ?, ?, ?)
        ");
        $insert_terrain->execute([$id_equipe, intval($carte_terrain['id']), 6, 'terrain']);
    }



    echo json_encode(['success' => true, 'message' => 'Équipe mise à jour avec succès !']);
} catch (Exception $e) {
    echo json_encode(['success' => false, 'message' => 'Erreur lors de la mise à jour : ' . $e->getMessage()]);
}
?>