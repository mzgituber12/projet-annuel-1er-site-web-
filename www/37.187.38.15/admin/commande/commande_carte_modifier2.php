<?php 

$action = $bdd->prepare("UPDATE CARTE SET $v = :v WHERE id_carte = $comm[3]");
$action->execute([
    'v'=>$x
])

?>