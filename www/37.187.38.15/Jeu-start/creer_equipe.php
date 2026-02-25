<?php
session_start();
if (!isset($_SESSION['id'])) {
    header('location:index.php');
    exit;
}
$id_utilisateur = $_SESSION['id'];

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['nom_equipe'])) {
    $nom_equipe = $_POST['nom_equipe'];
    
    if (empty($nom_equipe)) {
        header("Location: selectionequipe.php?message=Le nom de l'équipe ne peut pas être vide");
        exit;
    }

    try {
        $bdd = new PDO('mysql:host=localhost;dbname=base_site', 'phpmyadmin', 'ciscoadmin123');
        $bdd->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $statement = $bdd->prepare("INSERT INTO EQUIPE (id_utilisateur, nom) VALUES (?, ?)");
        $statement->execute([$id_utilisateur, $nom_equipe]);

        $_SESSION['id_equipe'] = $bdd->lastInsertId();

        header("Location: selectionequipe.php?message=Equipe crée avec succes");
        exit;
    } catch (Exception $e) {
        header("Location: selectionequipe.php?message=Erreur lors de la création de l'équipe : " . $e->getMessage());
        exit;
    }
} else {
    echo "Requête invalide.";
}
?>