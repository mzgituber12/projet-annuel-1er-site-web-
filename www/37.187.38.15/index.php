<?php session_start(); 
include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Milonga&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.5/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="styles/styletext.css">
    <title>BattlePast</title>
</head>

<body> 
  <?php 
    include("header.php");
    echo '<h1 class="milonga-regular" style="text-align:center; text-size:55px; margin-top:9px">Accueil</h1>';
    include('getmessage.php');
    if (!isset($_SESSION['pseudo']) && !isset($_SESSION['email'])) {
      echo '<h2 style="margin-left:13px">Connectez vous ou inscrivez vous pour jouer !</h2>';
     } else {
          echo '<div class="container d-flex justify-content-center mb-4 mt-3">
          <div id="carouselExampleAutoplaying" class="carousel slide w-75" data-bs-ride="carousel" data-bs-pause="false">
    <div class="carousel-inner rounded-4">
      <div class="carousel-item active" data-bs-interval="6000">
      <a href="Jeu-start/jeu-strat-index.php" target="_blank">
      <video class="d-block w-100" autoplay loop muted playsinline>
        <source src="video.mp4/jeu_carte.mp4.mp4" type="video/mp4" >
        </video>
            </a>
      </div>
      <div class="carousel-item" data-bs-interval="6000">
      <a href="Quiz/index.php" target="_blank">
      <video class="d-block w-100" autoplay loop muted playsinline>
        <source src="video.mp4/quiz_geo.mp4.mp4" type="video/mp4" >
            </video>
        </a>
      </div>
    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleAutoplaying" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
  </div>';
   }
     include("actualite.php");
     include("footer.php"); ?>
     
<script src="../script_theme.js"></script>

</body>
</html>