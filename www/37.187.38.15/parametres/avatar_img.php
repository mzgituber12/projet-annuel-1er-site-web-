<?php

if (isset($_SESSION['id'])) {
    $stmt = $bdd->prepare("SELECT avatar FROM utilisateur WHERE id_utilisateur = :id");
    $stmt->execute(['id' => $_SESSION['id']]);
    $result = $stmt->fetch(PDO::FETCH_ASSOC);

    if (!empty($result['avatar'])) {
        $avatar_url = $result['avatar'];
    }
}
?>
