<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['id_equipe'])) {
    $id_equipe = intval($_POST['id_equipe']);

    try {
        include('../bdd.php');

        $statement = $bdd->prepare("SELECT * FROM equipe WHERE id_equipe = ? AND id_utilisateur = ?");
        $statement->execute([$id_equipe, $_SESSION['id']]);
        $equipe = $statement->fetch();

        if (!$equipe){
            header('location:selectionequipe.php?message=L\'équipe sélectionnée n\'existe pas ou ne vous appartient pas');
            exit;
        }

        $statement = $bdd->prepare("SELECT count(*) AS counter FROM equipe WHERE id_utilisateur = ?");
        $statement->execute([$_SESSION['id']]);
        $equipe2 = $statement->fetch();

        if ($equipe2['counter'] == 1){
            header('location:selectionequipe.php?message=Il ne vous reste qu\'une seule équipe, vous ne pouvez pas la supprimer');
            exit;
        }

            $statement = $bdd->prepare("DELETE FROM equipe WHERE id_equipe = ?");
            $statement->execute([$id_equipe]);

            $id_utilisateur = $_SESSION['id'];
    $stmt = $bdd->prepare("SELECT id_equipe FROM EQUIPE WHERE id_utilisateur = ? ORDER BY id_equipe LIMIT 1");
    $stmt->execute([$id_utilisateur]);
    $nouvelle_equipe = $stmt->fetchColumn();
    $_SESSION['id_equipe'] = $nouvelle_equipe;

            header('location:selectionequipe.php?message=Equipe supprimée avec succes');
            exit;

    } catch (Exception $e) {
        header('location:selectionequipe.php?message=Erreur lors de la suppression de l\'équipe :' . $e->getMessage());
        exit;
    }
} else {
    echo "<p>Requête invalide.</p>";
    echo "<p><a href='selectionequipe.php'>Retour aux équipes</a></p>";
}
?>