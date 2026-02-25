<?php if (isset($_GET['message']) && (basename($_SERVER['SCRIPT_NAME']) === 'parametres.php')){
    echo '<h2 style="margin-left:2%; margin-bottom:20px">' . htmlspecialchars($_GET['message']) . '</h2>';
} else if (isset($_GET['message'])){
    echo '<h2 style="margin-left:1.15%; margin-top:3px; margin-bottom:15px">' . htmlspecialchars($_GET['message']) . '</h2>';
}

?>