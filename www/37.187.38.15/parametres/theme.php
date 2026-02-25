<?php
if (!isset($_SESSION)) session_start();
include_once(__DIR__ . '/../bdd.php');

$theme = 'light';

if (isset($_SESSION['id'])) {
    $id = $_SESSION['id'];
    $stmt = $bdd->prepare("SELECT theme FROM utilisateur WHERE id_utilisateur = :id");
    $stmt->execute([
        'id' => $id
    ]);
    $res = $stmt->fetch(PDO::FETCH_ASSOC);
    if (!empty($res['theme'])) {
        $theme = strtolower($res['theme']);
    }
}
?>

