<?php
session_start();
$bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
$data = json_decode(file_get_contents("php://input"), true);

if ($data) {
    $joueur1 = $data['joueur1'];
    $joueur2 = $data['joueur2'];
    $id_combat = $data['id_combat'];

    var_dump($joueur1);
    var_dump($joueur2);

    $statement = $bdd->prepare("SELECT statut tour_actuel gagnant FROM COMBAT  
    WHERE id_combat = ?");
    $statement->execute([$id_combat]);

} else {
    echo "Aucune donnée reçue.";
}
?>