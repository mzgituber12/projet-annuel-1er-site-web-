<?php session_start();
include_once("theme.php");
include('../bdd.php');
?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="../styles/styletext.css">
    <title >Changer le Theme</title>
</head>
<body>
    <?php

if(!isset($_SESSION['pseudo'])){
    header('location:../index.php');
    exit;
}

if ($_GET['message'] === 'theme') {

  if (empty($_POST['theme'])){
    header("location:parametres.php?message=Theme inchangé");
    exit;
  }

        $themevalue = $_POST['theme'];
        $id = $_SESSION['id'];
        $insertheme = "UPDATE utilisateur SET theme = :themevalue WHERE id_utilisateur= :id";
        $statement = $bdd->prepare($insertheme);
        $statement->execute([
                'themevalue' => $themevalue,
                'id' => $id,
              ]);
              header("location:parametres.php?message=Theme changé avec succes");
              exit;
}

$theme = $bdd->prepare("SELECT theme FROM utilisateur WHERE id_utilisateur = ?");
$theme->execute([
  $_SESSION['id']
]);
$themeresult = $theme->fetch();
$themeactu = $themeresult['theme'];

if ($themeactu == 'light') {
  $btn = "btn btn-outline-dark text-dark";
  $btd = "black";
} else {
  $btn = "btn btn-outline-light text-dark";
  $btd = "white";
}

include('header_parametres.php');

?>

<h1 class='text-center m-4 milonga-regular'>Thème clair/sombre</h1>

    <div class="container mt-5 ms-4">
    <form method="post" action="parametres_couleur.php?message=theme" class="d-flex align-items-center gap-3">
  <select class="form-select w-50" name="theme">
    <option value="" disabled selected>Choisir un thème</option>
    <option value="light">⚪ Blanc</option>
    <option value="dark">⚫ Noir</option>
  </select>
  <button type="submit" class=<?= $btn ?> style="border-color: <?= $btd ?>"><i class="bi bi-brightness-high"></i>/  <i class="bi bi-moon"></i></button>
</form>
</div>
<?php include("footer_parametres.php"); ?>
</body>
</html>