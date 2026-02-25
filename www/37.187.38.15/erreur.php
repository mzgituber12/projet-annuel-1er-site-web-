<?php
$code = isset($_SERVER["REDIRECT_STATUS"]) ? $_SERVER["REDIRECT_STATUS"] : 0;

$messages = [
    400 => ["Erreur 400", "Requête invalide."],
    401 => ["Erreur 401", "Authentification requise."],
    403 => ["Erreur 403", "Accès interdit."],
    404 => ["Erreur 404", "Page non trouvée."],
    500 => ["Erreur 500", "Erreur interne du serveur."],
    502 => ["Erreur 502", "Mauvaise passerelle."],
    503 => ["Erreur 503", "Service indisponible."]
];


$title = "Erreur inconnue";
$message = "Une erreur est survenue.";

if (isset($messages[$code])) {
    $title = $messages[$code][0];
    $message = $messages[$code][1];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        body {
            background: white;
            text-align: center;
            padding-top: 100px;
        }
        .boite-erreur {
            display: inline-block;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 5px 10px rgba(0,0,0,0.1);
        }
        h1 {
            font-size: 48px;
            color:rgb(189, 33, 33);
            margin-bottom: 0.5rem;
        }
        p {
            font-size: 20px;
            color: rgb(109, 112, 116);        
        }
        a {
            margin-top: 20px;
            display: inline-block;
            text-decoration: none;
            color:rgb(12, 121, 238);
        }
    </style>
</head>
<body>
    <div class="boite-erreur">
        <h1><?= htmlspecialchars($title) ?></h1>
        <p><?= htmlspecialchars($message) ?></p>
        <a href="/">Retour à l'accueil</a>
    </div>
</body>
</html>