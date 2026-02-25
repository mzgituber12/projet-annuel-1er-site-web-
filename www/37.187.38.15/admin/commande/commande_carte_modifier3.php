<?php 

$action = $bdd->prepare("UPDATE STATS_CARTE SET $v = :v WHERE id_carte = $comm[3]");
$action->execute([
    'v'=>$x
])

?>