<?php 

$action = $bdd->prepare("UPDATE ATTAQUE_CARTE SET $v = :v WHERE id_attaque = $comm[3]");
$action->execute([
    'v'=>$x
])

?>