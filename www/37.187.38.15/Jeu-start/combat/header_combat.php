<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php

include("../../statut.php");
if (isset($_SESSION['id'])){
    ?> <script>
    
    function envoyerTemps() {
      navigator.sendBeacon('../update_temps.php');
    }
    setInterval(envoyerTemps, 1000);

    window.addEventListener('beforeunload', function () {
      envoyerTemps();
    })
    </script>
    <?php
  }
?>

<header>
    <nav class="navbar bg-body-secondary">
        <div class="container-fluid">
        <a class="navbar-brand">
                <img src="../../image/logo_noir.png.PNG" height="50px" width="220px">
            </a>
            <a class="navbar-brand" href="../../index.php">
                <img src="https://cdn-icons-png.flaticon.com/512/861/861435.png" height="40px" width="40px">
            </a>
            <?php if (!isset($_SESSION['email']) && !isset($_SESSION['pseudo'])){
                header("location:../../index.php");
                exit;
                }  else { ?>

            <ul class="navbar-nav me-3 gap-3 mb-lg-0 d-flex flex-row">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../boutique.php">
                        <img src="https://cdn-icons-png.flaticon.com/512/2697/2697432.png" height="40px" width="40px">
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../inventaire.php">
                    <img src="https://cdn-icons-png.flaticon.com/512/3313/3313637.png" height="40px" width="40px">
                    </a>
                </li>
            </ul>
       
            <button class="navbar-toggler ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <span class="navbar-toggler-icon"></span>
            </button>
        </div>
    </nav>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <?php 
                        if($_SESSION['statut'] == "0" ){
                    ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../activation_du_compte.php">Activer son compte</a>
                </li>
                <?php }  ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../listeamis.php">Liste d'amis</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../../battledex.php">BattleDex</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../classement.php">Classement</a>
                </li>
                <?php
                        if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] =='admin'){
                        echo'<a class="nav-link active" aria-current="page" href="../../admin/indexad.php"> Zone administrateur </a>';
                        }  
                    ?> 
                <li class="nav-item dropdown">
    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
        Autre
    </a>
    <ul class="dropdown-menu">
        <li><a class="dropdown-item" href="../../profil.php?user=<?= $_SESSION['pseudo'] ?>">Mon Profil</a></li>
        <li><a class="dropdown-item" href="../../parametres/parametres.php">Parametres</a></li>
        <li><hr class="dropdown-divider"></li>
        <li><a class="dropdown-item" href="../../deconnexion.php">Se deconnecter</a></li>
    </ul>
</li>
        </ul>
            <form class="d-flex mt-3" role="search" action='../../profil.php'>
                <input class="form-control me-2" type="search" required name="user" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>   
    </div>
    <?php } ?>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>