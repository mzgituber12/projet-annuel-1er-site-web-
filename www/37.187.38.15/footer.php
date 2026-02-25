<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
<br>
<footer class="bd-footer py-4 py-md-5 mt-5 bg-body-tertiary">
<nav class="navbar p-3">
        <div class="container-fluid">
        <ul class="navbar-nav me-3 gap-3 mb-2 mb-lg-0 d-flex flex-row">
            <a class="link-opacity-50-hover p-2 d-inline-block link-offset-2 link-underline link-underline-opacity-0" href="politiqueconf.php" style="color: inherit">Politique et confidentialité</a>
            <a class="link-opacity-50-hover p-2 d-inline-block link-offset-2 link-underline link-underline-opacity-0" href="reglejeu.php" style="color: inherit">Regles du jeu</a>
            <a class="link-opacity-50-hover p-2 d-inline-block link-offset-2 link-underline link-underline-opacity-0" href="reglesite.php" style="color: inherit">Règles d'utilisation de la plateforme</a>
            <?php
if (isset($_SESSION['email'])){
                echo'<li class="nav-item">
                            <a class="link-opacity-50-hover p-2 d-inline-block link-offset-2 link-underline link-underline-opacity-0" href="support.php" style="color: inherit">Contactez Nous</a>

                </li>
                </div>
                </ul>';
        }
        ?>
</footer>
