<?php 

$action = $bdd->prepare("UPDATE ACTUALITE SET $v = :v WHERE id_actualite = :t");
$action->execute([
    'v'=>$x,
    't'=>$comm[3]
])

?>