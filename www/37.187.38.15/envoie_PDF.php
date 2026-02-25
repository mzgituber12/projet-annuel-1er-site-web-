<?php
session_start();
date_default_timezone_set('Europe/Paris');
    
if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
}

require_once(__DIR__ . '/vendor/autoload.php');

include('bdd.php');

if ($_SESSION['role'] == 'admin'){

$all="SELECT * FROM UTILISATEUR WHERE pseudo = :pseudo";
$statement = $bdd->prepare($all);
$statement->execute([
    'pseudo' => $_GET['user']
]);
$result = $statement->fetch(PDO::FETCH_ASSOC);

} else {

$all="SELECT * FROM UTILISATEUR WHERE id_utilisateur = :id";
$statement = $bdd->prepare($all);
$statement->execute([
    'id' => $_SESSION['id']
]);
$result = $statement->fetch(PDO::FETCH_ASSOC);

}

if ($result) {
    $nom = htmlspecialchars($result['pseudo']);
    $photo_profil = !empty($result['photo_profil']) ? "<img src='photo_profil/" . htmlspecialchars($result['photo_profil']) . "' alt='Photo de profil' width='40' style='border-radius: 50%'>" : "Pas de photo de profil";
    $a_propos = htmlspecialchars($result['a_propos']);
    $niveau = htmlspecialchars($result['niveau']);
    $monnaie = htmlspecialchars($result['monnaie']);
    $goodies = htmlspecialchars($result['goodies']);
    $date_inscription = explode('-', explode(' ', $result['date_inscription'])[0]);
    $date_inscription = $date_inscription[2] . '/' . $date_inscription[1] . '/' . $date_inscription[0];
    $nb_partie = htmlspecialchars($result['nb_partie']);
    $nb_victoire = htmlspecialchars($result['nb_victoire']);
    $vh = htmlspecialchars($result['vh']);
    $vg = htmlspecialchars($result['vg']);
    
    $html = "
        <h1 style='color: navy;'>Profil de {$nom}</h1>
        <p><strong>Nom d'utilisateur :</strong> {$nom}</p>
        <p><strong>Photo de profil :</strong> {$photo_profil}</p>
        <p><strong>A propos de l'utilisateur :</strong><br> {$a_propos}</p>
        <p><strong>Niveau :</strong> {$niveau}</p>
        <p><strong>Nombre de coins :</strong> {$monnaie}</p>
        <p><strong>Nombre de goodies :</strong> {$goodies}</p>
        <p><strong>Date d'inscription :</strong> {$date_inscription}</p>
        <p><strong>Nombre de parties :</strong> {$nb_partie}</p>
        <p><strong>Nombre de victoires :</strong> {$nb_victoire}</p>
        <p><strong>Nombre de quiz complétés (Histoire) :</strong> {$vh}</p>
        <p><strong>Nombre de quiz complétés (Géo) :</strong> {$vg}</p>
        <hr>
        <p style='font-size: 10px; color: gray;'>PDF généré le " . date('d/m/Y à H:i') . "</p>
    ";

    $mpdf = new \Mpdf\Mpdf();

    $mpdf->WriteHTML($html);

    $mpdf->Output("profil_{$nom}.pdf", 'D');
} else {
    echo "<p>Aucun utilisateur trouvé</p>";
}
?>