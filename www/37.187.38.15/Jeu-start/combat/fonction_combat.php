<?php
session_start();
function initialiser_combat($id_combat, $id_joueur1, $id_joueur2, $bdd) {
    $infos_joueur1 = Collectee_Info($bdd, $id_joueur1);
    $infos_joueur2 = Collectee_Info($bdd, $id_joueur2);
    $statement = $bdd->prepare("SELECT tour_actuel FROM COMBAT WHERE id_combat = ?");
    $statement->execute([$id_combat]);
    $tours = $statement->fetchColumn();
    $combat_data = [
        "tour" => $tours,
        "joueurs" => [
            "1" => [
                "id" => $id_joueur1,
                "pseudo" => $infos_joueur1['pseudo'],
                "cartes" => []
            ],
            "2" => [
                "id" => $id_joueur2,
                "pseudo" => $infos_joueur2['pseudo'],
                "cartes" => []
            ]
        ]
    ];

    foreach ($infos_joueur1['cartes'] as $carte) {
        $attaques = [];
        foreach ($carte['attaques'] as $attaque) {
            $attaques[] = [
                "id_attaque" => $attaque['id_attaque'],
                "nom" => $attaque['nom'],
                "degats" => $attaque['degats'],
                "effet" => $attaque['effet'],
                "portee" => $attaque['portee']
            ];
        }
    
        $combat_data["joueurs"]["1"]["cartes"][] = [
            "id_carte" => $carte['id_carte'],
            "nom" => $carte['nom_carte'],
            "pv" => $carte['pv'],
            "ko" => false,
            "statut" => $carte['statut'],
            "image" => $carte['image'],
            "attaques" => $attaques
        ];
    }

    foreach ($infos_joueur2['cartes'] as $carte) {
        $attaques = [];
        foreach ($carte['attaques'] as $attaque) {
            $attaques[] = [
                "id_attaque" => $attaque['id_attaque'],
                "nom" => $attaque['nom'],
                "degats" => $attaque['degats'],
                "effet" => $attaque['effet'],
                "portee" => $attaque['portee']
            ];
        }
    
        $combat_data["joueurs"]["2"]["cartes"][] = [
            "id_carte" => $carte['id_carte'],
            "nom" => $carte['nom_carte'],
            "pv" => $carte['pv'],
            "ko" => false,
            "statut" => $carte['statut'],
            "image" => $carte['image'],
            "attaques" => $attaques
        ];
    }
    $dossier = "donnees_combat";
    if (!is_dir($dossier)) {
        mkdir($dossier, 0777, true);
    }

    $fichier = "$dossier/combat_$id_combat.json";
    file_put_contents($fichier, json_encode($combat_data, JSON_PRETTY_PRINT));
    return $fichier;
}
function Collectee_Info($bdd, $id_utilisateur) {

    $statement = $bdd->prepare("SELECT pseudo FROM UTILISATEUR WHERE id_utilisateur = ?");
    $statement->execute([$id_utilisateur]);
    $pseudo = $statement->fetchColumn();

    $infos = [
        'pseudo' => $pseudo,
        'cartes' => []
    ];

    $statement = $bdd->prepare("
        SELECT c.id_carte, c.nom_carte, c.image, c.rarete, c.talent, c.pays, c.religion, c.statut,
               s.pv, s.atk, s.def, s.vit, s.esq, s.prs
        FROM CARTEDECK d
        JOIN carte c ON d.id_carte = c.id_carte
        JOIN stats_carte s ON c.id_carte = s.id_carte
        JOIN DECK dk ON d.id_deck = dk.id_deck 
        WHERE dk.id_utilisateur = ?
    ");
    $statement->execute([$id_utilisateur]);
    $cartes = $statement->fetchAll(PDO::FETCH_ASSOC);
    
    if (empty($cartes)) {
        
        return $infos;
    }
    if (count($cartes) < 6) {
        return $infos;
    }

    foreach ($cartes as $carte) {
        $carte_info = $carte;

        if ($carte['statut'] == 'heros') {
            $statement = $bdd->prepare("
                SELECT ac.id_attaque, ac.nom, ac.degats, ac.effet, ac.portee
                FROM attacher a
                JOIN attaque_carte ac ON a.id_attaque = ac.id_attaque
                WHERE a.id_carte = ?
            ");
            $statement->execute([$carte['id_carte']]);
            $attaques = $statement->fetchAll(PDO::FETCH_ASSOC);

            if (count($attaques) !== 2) {
                die("carte non jouable");
            }

            $carte_info['attaques'] = $attaques;
        }

        $infos['cartes'][] = $carte_info;

    }
    
    return $infos;
}
function definir_carte_active($cartes){
    foreach ($cartes as $carte) {
        if ($carte['statut'] == 'heros' && $carte['pv'] > 0) {
            
            return $carte;
        }
    }
    return null;
}
function trouver_terrain($cartes) {
    foreach ($cartes as $carte) {
        if ($carte['statut'] === 'terrain') {
            return $carte['image'];
        }
    }
    return 'img/terrain_defaut.jpg'; 
}
function getNomAttaque(PDO $bdd, int $id_attaque): string {
    $req = $bdd->prepare("SELECT nom FROM attaque_carte WHERE id_attaque = ?");
    $req->execute([$id_attaque]);
    $attaque = $req->fetch(PDO::FETCH_ASSOC);
    return $attaque ? $attaque['nom'] : "Attaque inconnue";
}

function attaque($id_attaquant, $id_ja, $id_cible, $id_jc, $id_attaque, $bdd) {
    $id_combat = $_SESSION['id_combat'];
    file_put_contents('debug.log', "attaque() - attaquant: $id_attaquant, cible: $id_cible, attaque: $id_attaque\n", FILE_APPEND);
    $req = $bdd->prepare("SELECT nom AS nom_attaque, degats, effet, portee FROM attaque_carte WHERE id_attaque = ?");
    $req->execute([$id_attaque]);
    $attaque = $req->fetch(PDO::FETCH_ASSOC);

    if (!$attaque) return ["erreur" => "Attaque introuvable"];

    $degats = calcul_degats([$attaque], $id_attaquant, $id_cible, $bdd);

    $combat_data = json_decode(file_get_contents("donnees_combat/combat_$id_combat.json"), true);

    function get_nom_carte_json($combat_data, $id_carte) {
        foreach ($combat_data["joueurs"] as $joueur) {
            foreach ($joueur["cartes"] as $carte) {
                if ($carte["id_carte"] == $id_carte) {
                    return $carte["nom"] ?? "Inconnu";
                }
            }
        }
        return "Inconnu";
    }
    $nom_attaquant = get_nom_carte_json($combat_data, $id_attaquant);
    $nom_cible = get_nom_carte_json($combat_data, $id_cible);

    return [
        "degats" => $degats,
        "attaque" => $attaque['nom_attaque'],
        "cible" => $nom_cible,
        "joueur_cible" => $id_jc,
        "joueur_attaquant" => $id_ja,
        "attaquant" => $nom_attaquant
    ];
}

function calcul_degats($attaque, $id_attaquant, $id_cible, $bdd) {

    $stats_attaquant = get_stats_carte($id_attaquant, $bdd);
    $stats_cible = get_stats_carte($id_cible, $bdd);

    if (!$stats_attaquant || !$stats_cible) return 0;


   /*$chance_toucher = $stats_attaquant['prs'] - $stats_cible['esq'];
    var_dump($stats_attaquant['prs']);
    echo"<br>";
    var_dump($stats_cible['esq']);

    if (rand(1, 100) > $chance_toucher) {
        return 0; 
    }
    */
    if (is_null($attaque[0]['degats'])) {
        $id=1;
    }else{
        $id=0;
    }

    $puissance = $attaque[$id]['degats'];
    $atk = $stats_attaquant[$id]['atk'];
    $def = $stats_cible[$id]['def'];

    $degats = $puissance + $atk - $def;

    return max(0, $degats);
}

function get_stats_carte($id_carte, $bdd) {
    $req = $bdd->prepare("SELECT pv, atk, def, vit, esq, prs FROM stats_carte WHERE id_carte = ?");
    $req->execute([$id_carte]);
    return $req->fetch(PDO::FETCH_ASSOC);
}

function mettre_a_jour_PV($id_joueur, $id_carte, $degats, $id_combat) {
    $json_mAj = __DIR__ ."/donnees_combat/combat_$id_combat.json";

    if (!file_exists($json_mAj)) return false;

    $combat_data = json_decode(file_get_contents($json_mAj), true);

    if ($combat_data["joueurs"][1]["id"] == $id_joueur) $id =1;
    if ($combat_data["joueurs"][2]["id"] == $id_joueur) $id =2;
        foreach ($combat_data["joueurs"][$id]["cartes"] as &$carte) {
            if ($carte["id_carte"] == $id_carte) {
                $carte["pv"] = max(0, $carte["pv"] - $degats);
                file_put_contents($json_mAj, json_encode($combat_data, JSON_PRETTY_PRINT));
                return $carte["pv"];
            }
        }

    return false; // Carte non trouvée
}

function K0($id_carte, $id_combat) {
    $fichier = "donnees_combat/combat_$id_combat.json";
    
    if (!file_exists($fichier)) return false;

    $combat_data = json_decode(file_get_contents($fichier), true);
    $carte_ko = false;

    foreach (["1", "2"] as $numero_joueur) {
        foreach ($combat_data["joueurs"][$numero_joueur]["cartes"] as $index => $carte) {
            
            if ($carte["id_carte"] == $id_carte) {
                if ($carte["pv"] <= 0 && !$carte["ko"]) {
                    
                    $combat_data["joueurs"][$numero_joueur]["cartes"][$index]["ko"] = true;
                    $carte_ko = true;
                    var_dump($combat_data["joueurs"][$numero_joueur]["cartes"][$index]["ko"]);
                }
                break;
            }
        }
    }

    if ($carte_ko) {
        file_put_contents($fichier, json_encode($combat_data, JSON_PRETTY_PRINT));
    }

    return $carte_ko;
}

function changer_tour($id_combat, $bdd) {
    $req = $bdd->prepare("SELECT tour_actuel FROM combat WHERE id_combat = ?");
    $req->execute([$id_combat]);
    $combat = $req->fetch(PDO::FETCH_ASSOC);

    if (!$combat) return false;

    $tour_suivant = ($combat['tour_actuel'] >= 100) ? 1 : $combat['tour_actuel'] + 1;

    $update = $bdd->prepare("UPDATE combat SET tour_actuel = ? WHERE id_combat = ?");
    $update->execute([$tour_suivant, $id_combat]);

    $json_mAj = "donnees_combat/combat_$id_combat.json";
    if (file_exists($json_mAj)) {
        $combat_data = json_decode(file_get_contents($json_mAj), true);

        
        $combat_data['tour'] = $tour_suivant;

        
        file_put_contents($json_mAj, json_encode($combat_data, JSON_PRETTY_PRINT));
    }
    return $tour_suivant;
}
function fin_combat($joueur_gagnant, $joueur_perdant, $bdd, $id_combat) {
    $requete = $bdd->prepare("UPDATE COMBAT SET gagnant = ? WHERE id_combat = ?");
    $requete->execute([$joueur_gagnant, $id_combat]);
    
    $requete = $bdd->prepare("UPDATE COMBAT SET statut = 'terminé' WHERE id_combat = ?");
            $requete->execute([$id_combat]);
    $requete2 = $bdd->prepare("UPDATE UTILISATEUR SET nb_victoire = nb_victoire + 1 WHERE id_utilisateur = ?");
    $requete2->execute([$_SESSION['id']]);
    $requete2 = $bdd->prepare("UPDATE UTILISATEUR SET nb_victoire_temp = nb_victoire_temp + 1 WHERE id_utilisateur = ?");
    $requete2->execute([$_SESSION['id']]);

    $requete3 = $bdd->prepare("UPDATE UTILISATEUR SET monnaie = monnaie + 600 WHERE id_utilisateur = ?");
    $requete3->execute([$_SESSION['id']]);

    $requete3 = $bdd->prepare("UPDATE UTILISATEUR SET experience = experience + 25 WHERE id_utilisateur = ?");
    $requete3->execute([$_SESSION['id']]);


    $requete4 = $bdd->prepare("SELECT experience, niveau FROM UTILISATEUR WHERE id_utilisateur = ?");
    $requete4->execute([$_SESSION['id']]);
    $utilisateur = $requete4->fetch(PDO::FETCH_ASSOC);

    if ($utilisateur) {
        $experience = (int)$utilisateur['experience'];
        $niveau = (int)$utilisateur['niveau'];

    if ($experience >= 100 * $niveau) {
        $niveau++;
        $experience = 0;

        $requete5 = $bdd->prepare("UPDATE UTILISATEUR SET niveau = ?, experience = ? WHERE id_utilisateur = ?");
        $requete5->execute([$niveau, $experience, $_SESSION['id']]);
    }
}
    $fichier = __DIR__ . "/donnees_combat/combat_$id_combat.json";
    
    if (file_exists($fichier)) {
    unlink($fichier);
    }

    return true;
}
function verifier_deck_vide($combat_data, $numero_joueur) {
    $numero_joueur = (string) $numero_joueur;
    if ($combat_data["joueurs"][1]["id"] == $numero_joueur) {
    
    if (!isset($combat_data["joueurs"][1]["cartes"])) {
        return true; 
    }

    
    foreach ($combat_data["joueurs"][1]["cartes"] as $carte) {
        if (isset($carte["ko"]) && !$carte["ko"] && $carte["statut"] != "terrain") {
            return false; 
        }
    }
    return true; 
    }elseif ($combat_data["joueurs"][2]["id"] == $numero_joueur){
    if (!isset($combat_data["joueurs"][2]["cartes"])) {
        return true; 
    }

    
    foreach ($combat_data["joueurs"][2]["cartes"] as $carte) {
        if (isset($carte["ko"]) && !$carte["ko"] && $carte["statut"] != "terrain") {
            return false; 
        }
    }

    return true; 

    }else{
        return true;
    }   
}
