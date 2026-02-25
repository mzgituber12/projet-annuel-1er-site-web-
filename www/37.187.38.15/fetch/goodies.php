<?php session_start();

include("../bdd.php");

$temporaire = "SELECT * FROM UTILISATEUR ORDER BY goodies DESC LIMIT 15";
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
    <td class='left' id='victoires' onclick='victoires()'> Classement par victoires </td>
    <td class='left' id='goodies' onclick='goodies()'> Classement par goodies ▼ </td>
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

        $temporaire2 = "SELECT * FROM UTILISATEUR ORDER BY goodies DESC";
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
        
        echo "<p style='margin-left: 50px;'>Vous êtes numéro " . $compteur4 . " du classement permanent (goodies)</p>";

?>