<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<?php 
include("../parametres/theme.php");
include("../parametres/avatar_img.php");
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
                    <i class="bi bi-backpack fs-1" height="100px" width="100px"></i>
                    </a>
                </li>
            </ul>

            <?php if($_SESSION['role'] == 'admin'){
                echo "<p>Administrateur</p>";
            }?>
       
            <button class="navbar-toggler border-0 ms-auto" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar">
                <img id="avatar-header" src="<?= htmlspecialchars($avatar_url) ?>" alt="Mon avatar" width="60">
            </button>
        </div>
    </nav>
    <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar" aria-labelledby="offcanvasNavbarLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="offcanvasNavbarLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <ul class="navbar-nav justify-content-end flex-grow-1 pe-3">
                <li class="nav-item">
                    <a class="nav-link active" aria-current="page" href="../listeamis.php">Liste d'amis</a>
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
                        <li><a class="dropdown-item" href="../parametres/parametres.php"><i class="bi bi-gear"></i>   Parametres</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item" href="../deconnexion.php"><i class="bi bi-box-arrow-left"></i>   Se deconnecter</a></li>
                    </ul>
                </li>
        </ul>
            <form class="d-flex mt-3" role="search" action='profil.php'>
                <input class="form-control me-2" type="search" name="user" placeholder="Rechercher" aria-label="Search">
                <button class="btn btn-outline-success" type="submit"> <i class="bi bi-search"></i></button>
            </form>
        </div>   
    </div>
</header>

            