<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<br>
<footer>
<nav class="navbar bg-dark p-3">
        <div class="container-fluid">
        <ul class="navbar-nav me-3 gap-3 mb-2 mb-lg-0 d-flex flex-row">
                <li class="nav-item">
                    <a class="nav-link active text-white bg-dark" aria-current="page" href="../politiqueconf.php">Politique et confidentialité</a>
                </li> 
                <li class="nav-item">
                    <a class="nav-link active text-white bg-dark" aria-current="page" href="../reglejeu.php">Regles du jeu</a>
                </li>   
                <li class="nav-item">
                    <a class="nav-link active text-white bg-dark" aria-current="page" href="../reglesite.php">Regles d'utilisation de la plateforme</a>
                </li>                 
                <?php
if (isset($_SESSION['email']) && isset($_SESSION['pseudo'])){
                echo'<li class="nav-item">
                    <a class="nav-link active text-white bg-dark" aria-current="page" href="../support.php">Contactez Nous</a>
                </li>
                </div>
                </ul>';
        }
        ?>
</footer>
