<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css" rel="stylesheet">
<?php if(!isset($_SESSION['id'])){
    header('location:../index.php');
    exit;
}

include("../statutfolder.php");
include("theme.php");
include("avatar_img.php");

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

    include_once('../bdd.php');
    $notif = $bdd->prepare("SELECT messagenotif, echangenotif, aminotif FROM utilisateur WHERE id_utilisateur = ?");
    $notif->execute([
        $_SESSION['id']
    ]);
    $results = $notif->fetch();

    $trade = $bdd->prepare("SELECT id_echange FROM echange WHERE id_utilisateur_2 = ?");
    $trade->execute([
        $_SESSION['id']
    ]);
    $echange = $trade->fetch();

    $ami = $bdd->prepare("SELECT etat FROM amitier WHERE id_amis_recoit = ? AND etat = 'en attente'");
    $ami->execute([
        $_SESSION['id']
    ]);
    $ami2 = $ami->fetch();

    $message = $bdd->prepare("SELECT id_message FROM message WHERE id_utilisateur_destinataire = ? AND id2vu = 0");
    $message->execute([
        $_SESSION['id']
    ]);
    $message2 = $message->fetch();
  
?>

<header>
    <nav class="navbar bg-body-secondary">
        <div class="container-fluid">
        <a class="navbar-brand">
<img id="logosite" src="<?= ($theme === 'dark') ? '../image/logo_blanc.png.PNG' : '../image/logo_noir.png.PNG' ?>" height="50px" width="220px">
            </a>

                <a class="navbar-brand" href="../index.php">
                <i class="bi bi-house fs-1"></i>
                </a>

                <ul class="navbar-nav me-3 gap-3 mb-lg-0 d-flex flex-row">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../boutique.php">
                    <i class="bi bi-shop fs-1"></i>
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="../inventaire.php">
                        <i class="bi bi-backpack fs-1" style="height: 100px; width: 100px;"></i>
                    </a>
                </li>

                <li class="nav-item d-flex align-items-center">
                    <?php if($_SESSION['role'] == 'admin'): ?>
                        <p class="mb-0">Administrateur</p>
                    <?php endif; ?>
                    </li>
            </ul>
       
            <button class="navbar-toggler border-0 ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <img id="avatar-header" src="<?= htmlspecialchars($avatar_url) ?>" alt="Mon avatar" width="60">
            </button>
        </div>
    </nav>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title mt-1" id="offcanvasNavbarLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <?php 
                        if($_SESSION['statut'] == "0" ){
                    ?>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../activation_du_compte.php">Activer son compte</a>
                </li>
                <?php }  ?>
                <li class="nav-item">
            <a class="nav-link active" aria-current="page" href="../listeamis.php" style="display: inline-flex; align-items: center;">
                Liste d'amis
                <?php if ($results['echangenotif'] == 1 && !empty($echange)) { 
                    echo '<span style="
                        display: inline-block;
                        margin-left: 4px;
                        width: 12px;
                        height: 12px;
                        background-color: orange;
                        border-radius: 50%;
                        transform: translate(4px, 1.5px);
                    "></span>';
                 } else if ((!empty($ami2) && $results['aminotif'] == 1) || ($results['messagenotif'] == 1) && !empty($message2)) { 
                    echo '<span style="
                        display: inline-block;
                        margin-left: 4px;
                        width: 12px;
                        height: 12px;
                        background-color: green;
                        border-radius: 50%;
                        transform: translate(4px, 1.5px);
                    "></span>';
                 }
                    ?>
            </a>
        </li>
                <li class="nav-item">
                    <a class="nav-link" href="../battledex.php"><i class="bi bi-book"></i>   BattleDex</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../classement.php"><i class="bi bi-bar-chart"></i>    Classement</a>
                </li>
                <?php
                        if (isset($_SESSION['email']) && isset($_SESSION['role']) && $_SESSION['role'] =='admin'){
                        echo'<a class="nav-link active" aria-current="page" href="../admin/indexad.php"> Zone administrateur </a>';
                        }  
                    ?> 
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        Autre
                    </a>
                            
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="../profil.php?user=<?= $_SESSION['pseudo'] ?>"><i class="bi bi-person"></i>   Mon Profil</a></li>
                        <li><a class="dropdown-item" href="parametres.php"><i class="bi bi-gear"></i>   Parametres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../deconnexion.php"><i class="bi bi-box-arrow-left"></i>   Se deconnecter</a></li>
                    </ul>
                </li>
        </ul>
            <form class="d-flex mt-3" role="search" action='../profil.php'>
                <input class="form-control me-2" type="search" name="user" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"> <i class="bi bi-search"></i></button>
            </form>
        </div>   
    </div>
</header>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
