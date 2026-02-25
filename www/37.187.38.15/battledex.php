<?php session_start(); include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" type="text/css" href="styles/stylecarte.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>Classeur du Battlepast</title>

<style>
     
.classeur {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(9.375rem, 1rem)); 
    margin: 1.25rem;   
    gap: 1.25rem;      
    padding: 1.25rem;  
}

</style>
</head>
<body>

<?php

if (!isset($_SESSION['pseudo'])) {
    header('location:index.php');
    exit;
}
include("bdd.php");
include("header.php");

$query = 'SELECT id_carte, nom_carte, image, rarete FROM CARTE';
$statement = $bdd->query($query);
$cartes = $statement->fetchAll(PDO::FETCH_ASSOC);

?>

<h1 style="text-align:center;">Classeur des Cartes</h1>

<form method='POST' action='battledex.php' class="d-flex align-items-center gap-2" role="search">
  <label class="me-2 mb-0 mx-3">Rechercher une carte dans le classeur de cartes : </label>
  <input class="form-control w-50" type="search" name="recherche">
  <input type="submit" value="Rechercher" class="btn btn-outline-light">
</form>

<br>
<form method="POST" action="battledex.php" class="d-flex align-items-center flex-wrap gap-3 mx-3">
  <label class="mb-0">Rechercher des cartes en fonction de leur rareté dans votre inventaire :</label>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="rarete[]" value="commun" id="commun">
    <label class="form-check-label" for="commun">Commun</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="rarete[]" value="rare" id="rare">
    <label class="form-check-label" for="rare">Rare</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="rarete[]" value="super rare" id="superrare">
    <label class="form-check-label" for="superrare">Super Rare</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="rarete[]" value="epique" id="epique">
    <label class="form-check-label" for="epique">Épique</label>
  </div>
  <div class="form-check form-check-inline">
    <input class="form-check-input" type="checkbox" name="rarete[]" value="legendaire" id="legendaire">
    <label class="form-check-label" for="legendaire">Légendaire</label>
  </div>
  <input type="submit" value="Rechercher" class="btn btn-outline-light ">
</form>


    <?php 
    if (isset($_POST['recherche'])){
        $recherchecarte = $bdd->prepare("
                SELECT CARTE.*
                FROM CARTE
                WHERE CARTE.nom_carte LIKE :carte
            ");
        $recherchecarte->execute([
            'carte'=>'%' . $_POST['recherche'] . '%'
        ]);
        $cartes = $recherchecarte->fetchall(PDO::FETCH_ASSOC);
        }
 
        if (isset($_POST['rarete'])) {
    
            $rarete = array_map(function($item) {
                return "'" . addslashes($item) . "'";  
            }, $_POST['rarete']);
    
            $placeholders = implode(', ', $rarete);
    
            $recherchecarte = $bdd->query("
                SELECT CARTE.*
                FROM CARTE
                WHERE CARTE.rarete IN ($placeholders)
            ");
        
            $cartes = $recherchecarte->fetchAll(PDO::FETCH_ASSOC);
        }

echo "<div class='classeur'>";

    if (count($cartes) == 0) {
        echo "<p style='text-align:center;'>Aucune carte trouvée.</p>";
    } else {
        foreach ($cartes as $carte) {
            $nomCarte = $carte['nom_carte'];
            $imagelien = "carte/" .$carte['image'];
            $rarete = $carte['rarete'];
            $classesRarete = ['commun', 'rare', 'epique', 'legendaire'];
            $choixRarete = in_array($rarete, $classesRarete) ? $rarete : 'super_rare';
            echo '<a href="carte.php?id=' . $carte["id_carte"] . '" style="text-decoration: none; color: inherit">';
            echo "<div class='carte $choixRarete'>";
            if (file_exists($imagelien)) {
                echo "<img src='$imagelien' alt='Image de $nomCarte'>";
            } else {
                echo "<p>Image non trouvée pour $nomCarte id : ".$carte['id_carte'] ."</p>";
            }
            echo "<p>$nomCarte id : ".$carte['id_carte'] ."</p><p>$rarete</p>";
            echo "</div>";
            echo "</a>";
        }
    }
echo "</div>";

include("footer.php");
?>

</body>
</html>