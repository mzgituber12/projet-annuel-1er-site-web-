<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require 'fonction_combat.php';

try {
    $bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
} catch (PDOException $e) {
    
    exit;
}

$data = json_decode(file_get_contents('php://input'), true);
$action = $data['action'] ?? '';
$id_combat = $_SESSION['id_combat'] ?? null;
$tour = (int)($data['tour'] ?? 0);

if (!$id_combat) {
    
    exit;
}

$combat = $bdd->prepare("SELECT * FROM combat WHERE id_combat = ?");
$combat->execute([$id_combat]);
$combat_data = $combat->fetch(PDO::FETCH_ASSOC);
if (!$combat_data) {
    
    exit;
}

switch ($action) {
    case 'lancer_combat':
        
        exit;

    case 'choisir_attaque':
        $id_attaque = $data['id_attaque'] ?? null;
        

        if (!$id_attaque) {
            exit;
        }

        $combat_json_path = "donnees_combat/combat_$id_combat.json";
        if (!file_exists($combat_json_path)) {
            exit;
        }

        $combat_data_json = json_decode(file_get_contents($combat_json_path), true);
        $id_utilisateur = $_SESSION['id'] ?? null;
        if (!$id_utilisateur) {
            
            exit;
        }

        $id_ja = $combat_data_json['joueurs'][1]['id'] == $id_utilisateur ? 1 : 2;
        $id_jc = 3 - $id_ja; 

        $carte_actif_ja = definir_carte_active($combat_data_json['joueurs'][$id_ja]['cartes']);
        $carte_actif_jc = definir_carte_active($combat_data_json['joueurs'][$id_jc]['cartes']);
        $id_attaquant = $carte_actif_ja['id_carte'];
        $id_cible = $carte_actif_jc['id_carte'];

        
        $resultat = attaque($id_attaquant, $id_ja, $id_cible, $id_jc, $id_attaque, $bdd);
        $_SESSION['result'] = $resultat;
        $resultat2 = mettre_a_jour_PV($combat_data_json['joueurs'][$id_jc]['id'], $id_cible, $resultat['degats'], $id_combat);
        $_SESSION['result2'] = $resultat2;

        changer_tour($id_combat, $bdd);
        $requete = $bdd->prepare("UPDATE COMBAT SET degats = ? WHERE id_combat = ?");
        $requete->execute([$resultat['degats'], $id_combat]);
        
        $_SESSION['attaque-tour'] = 0;
        if ($resultat2 ==0) {
            K0($id_cible, $id_combat);

        }
        $_SESSION['result3'] = definir_carte_active($combat_data_json['joueurs'][$id_jc]['cartes']);

        if (verifier_deck_vide($combat_data_json, $combat_data_json['joueurs'][$id_jc]['id']) == true){
            $_SESSION['gagnant'] = "vrai";
            $requete = $bdd->prepare("UPDATE COMBAT SET statut = 'terminé' WHERE id_combat = ?");
            $requete->execute([$id_combat]);
            $requete = $bdd->prepare("UPDATE COMBAT SET gagnant = ? WHERE id_combat = ?");
            $requete->execute([$id_ja, $id_combat]);
        }
        
        exit;

    default:
        exit;
}