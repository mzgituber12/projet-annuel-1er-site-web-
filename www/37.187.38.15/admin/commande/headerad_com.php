<?php
include('../../statut.php');
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

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<header>
    <nav class="navbar bg-body-secondary">
        <div class="container-fluid">
        <a class="navbar-brand">
                <img src="../../image/logo_noir.png.PNG" height="50px" width="220px">
            </a>
            <a class="navbar-brand" href="../../index.php">
                <img src="https://cdn-icons-png.flaticon.com/512/861/861435.png" height="40px" width="40px">
            </a>
            <ul class="navbar-nav me-3 gap-3 mb-lg-0 d-flex flex-row">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../indexad.php">Accueil Admin</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../lexcommande.php">Lexique des commandes</a>
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
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../liste_ban.php">Liste des bannissements</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../liste_utilisateur.php">Liste des utilisateurs</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../liste_admin.php">Liste des admins</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="../liste_id_cartes.php">Liste des identifiants des cartes</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../liste_id_attaques.php">Liste des identifiants des attaques</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../liste_id_actualités.php">Liste des identifiants des actualités</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../../deconnexion.php">Deconnexion</a>
                </li>
              </ul>
            <form class="d-flex mt-3" role="search" action='../../profil.php'>
                <input class="form-control me-2" type="search" name="user" placeholder="Search" aria-label="Search">
                <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
        </div>   
    </div>
  </header>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>