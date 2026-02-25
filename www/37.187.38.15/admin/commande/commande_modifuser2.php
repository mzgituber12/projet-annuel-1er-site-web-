<?php 

$action = $bdd->prepare("UPDATE UTILISATEUR SET $v = :v WHERE pseudo = :pseudo");
$action->execute([
    'v'=>$x,
    'pseudo'=>$comm[2]
]);

?>