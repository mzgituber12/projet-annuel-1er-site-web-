<?php include_once("parametres/theme.php");?>
<!DOCTYPE html>
<html lang="en" data-bs-theme="<?= htmlspecialchars($theme) ?>">
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Classement</title>
    <style>

        .introclassement{
            padding : 15px;
            text-align : center;
            font-size : 37px;
            height : 60px;
            font-family:'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif
        }

.table-permanent {
  width: 90%;
  margin: 30px auto;
  border-collapse: collapse;
  font-family:'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif;
  border-radius: 8px;
  overflow: hidden;
}

.table-permanent th, .table-permanent td {
  border: 1px solid #ddd;
  padding: 10px 14px;
  text-align: center;
  user-select: none;
}

.table-permanent th {
  background: #f4f4f4;
  font-weight: bold;
  font-size : 35px;
}

.table-permanent .numb {
  font-weight: bold;
  width: 5%;
}

.table-permanent .left {
  text-align: left;
  cursor: pointer;
  color: #0066cc;
  text-decoration: underline;
}

.table-permanent .left:hover {
  color: #004a99;
}



   .table-classement {
    width: 82%;
    border-collapse: collapse;
    margin: 20px auto;
    font-family:'Gill Sans', 'Gill Sans MT', 'Calibri', 'Trebuchet MS', sans-serif
}

.table-classement th,
.table-classement td {
    border: 1px solid #ddd;
    padding: 10px;
    text-align: center;
}

.table-classement th {
    background-color: #f4f4f4;
    font-size: 24px;
    font-weight: bold;
    text-align: center;
    color: #333;
}

.table-classement tr:hover {
    background-color: #e9f0ff;
    transition: background-color 0.3s ease;
}

.table-classement .numb {
    width: 5%;
    font-weight: bold;
}
    </style>    
</head>
<body>
    <?php session_start();
    if (!isset($_SESSION['pseudo'])) {
        header('location:index.php');
        exit;
    }

    include("header.php");
    echo '<h1 style="padding:1.5px 0px 3px 6px; font-size:45px;">Classement</h1>';
    
    date_default_timezone_set('Europe/Paris');
    
    echo "<h2 id='time' style='text-align:center; font-size:34px;'>" . 23-date("H") . "h " . 59-date("i") . "min " . 59 - date("s") . "sec </h2>";
    ?>


    <script>
    function updateTime() {
        const a = document.getElementById('time');
        const currentTime = new Date();
        let hourLeft = 23 - currentTime.getHours();
        const minutesLeft = 59 - currentTime.getMinutes();
        const secondsLeft = 59 - currentTime.getSeconds();

        if (hourLeft == 24){
            hourLeft = 0
        }
        
        a.innerHTML = hourLeft + "h " + minutesLeft + "min " + secondsLeft + "sec ";
    }

    setInterval(updateTime, 1000);
    </script>

    <?php

    include("bdd.php");

    $temporaire = "SELECT * FROM UTILISATEUR ORDER BY nb_victoire_temp DESC LIMIT 15";
    $statement = $bdd->prepare($temporaire);
    $statement->execute();
    $results = $statement->fetchAll(PDO::FETCH_ASSOC); 
    $compteur = 0;
    
    echo "<table>";
    echo "<table class='table-classement'>";
    echo "<tr>";
    echo "<th colspan='3' style='padding-top: 20px; padding-bottom: 20px; font-size: 35px;'>Classement quotidien</th>";
    echo "</tr>";
    foreach ($results as $result) {
        $compteur += 1;
        echo "<tr>";
        echo "<td class='numb'>" . $compteur . "</td>";
        echo "<td>" . htmlspecialchars($result['pseudo']) . "</td>";
        echo "<td>" . htmlspecialchars($result['nb_victoire_temp']) . " Victoires </td>";
        echo "</tr>";
    }
    echo "</table>";

        $temporaire2 = "SELECT * FROM UTILISATEUR ORDER BY nb_victoire_temp DESC";
        $statement = $bdd->prepare($temporaire2);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC); 
        $compteur2 = 1;

        foreach ($results as $result) {
            if ($_SESSION['pseudo'] !== $result['pseudo']){
             $compteur2 += 1;
        } else {
            break;
        } 
    }
        
        echo "<p style='margin-left: 70px;'> Vous êtes numéro " . $compteur2 . " du classement quotidien </p>";
    
    echo "<br>"

    ?>

    <script> 
    
    async function goodies(){
        const goodiesfetch = await fetch('fetch/goodies.php')
        const data = await goodiesfetch.text()
        document.getElementById('perm').innerHTML = data
    }

    async function victoires(){
        const victoiresfetch = await fetch('fetch/victoires.php')
        const dat = await victoiresfetch.text()
        document.getElementById('perm').innerHTML = dat
    }

    victoires()

    </script>

    <div id='perm'>

    <?php

    $temporaire = "SELECT * FROM UTILISATEUR ORDER BY nb_victoire DESC LIMIT 15";
$statement = $bdd->prepare($temporaire);
$statement->execute();
$results = $statement->fetchAll(PDO::FETCH_ASSOC); 
$compteur3 = 0;

?>
    
<table class="table-permanent">
<tr>
    <th colspan='4' style='padding-top: 20px; padding-bottom: 20px; font-size: 35px;'> Classement permanent </th>
</tr>
<tr>
    <td colspan='2'> </td>
    <td class='left' id='victoires' onclick='victoires()'> Classement par victoires ▼ </td>
    <td class='left' id='goodies' onclick='goodies()'> Classement par goodies </td>
</tr>
<tbody id="classement-table">
<?php 
foreach ($results as $result) {
    $compteur3 += 1;
    echo "<tr>";
    echo "<td class='numb'>" . $compteur3 . "</td>";
    echo "<td>" . htmlspecialchars($result['pseudo']) . "</td>";
    echo "<td>" . htmlspecialchars($result['nb_victoire']) . " Victoires </td>";
    echo "<td>" . htmlspecialchars($result['goodies']) . " Goodies </td>";
    echo "</tr>";
}
?>
</tbody>
</table>


    <?php

        $temporaire2 = "SELECT * FROM UTILISATEUR ORDER BY nb_victoire DESC";
        $statement = $bdd->prepare($temporaire2);
        $statement->execute();
        $results = $statement->fetchAll(PDO::FETCH_ASSOC); 
        $compteur4 = 1;

        foreach ($results as $result) {
            if ($_SESSION['pseudo'] !== $result['pseudo']){
             $compteur4 += 1;
        } else {
            break;
        } 
    }
        
        echo "<p style='margin-left: 50px;'>Vous êtes numéro " . $compteur4 . " du classement permanent (victoires)</p>";

?>

    </div>

    <?php

    include("footer.php");

    ?>

</body>
</html>