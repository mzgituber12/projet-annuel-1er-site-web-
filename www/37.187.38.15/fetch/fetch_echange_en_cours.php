<?php session_start();
include('../bdd.php');

$statement = $bdd->prepare("
        SELECT CARTE.*, CONTIENT.nb
        FROM CONTIENT
        JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
        WHERE CONTIENT.id_inventaire = :inv
    ");
$statement->execute([
        'inv'=>$_SESSION['inventaire']
    ]);
$cartes = $statement->fetchAll(PDO::FETCH_ASSOC);

$statement2 = $bdd->prepare("
    SELECT CARTE.*, CONTIENT.nb, INVENTAIRE.id_inventaire
    FROM CONTIENT
    JOIN CARTE ON CONTIENT.id_carte = CARTE.id_carte
    JOIN INVENTAIRE ON CONTIENT.id_inventaire = INVENTAIRE.id_inventaire
    WHERE INVENTAIRE.id_utilisateur = :id
");
$statement2->execute([
    'id'=>$_GET['iduser']
]);
$cartes2 = $statement2->fetchAll(PDO::FETCH_ASSOC);

if(isset($_GET['annuler'])){

    $statement3 = $bdd->prepare("DELETE FROM ECHANGE WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $statement3->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
}

$statement4 = $bdd->prepare("SELECT * FROM ECHANGE WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
$statement4->execute([
'id1'=>$_SESSION['id'],
'id2'=>$_GET['iduser']
]);
$echange = $statement4->fetch();



if (isset($_GET['tacarte'])){

    $cartee = $bdd->prepare("SELECT id_carte FROM CARTE WHERE id_carte = ?");
    $cartee->execute([
        $_GET['tacarte']
    ]);

    $resu = $cartee->fetch();

    if (!$resu){
        exit;
    }

    $inv = $bdd->prepare("SELECT nb FROM CONTIENT WHERE id_inventaire = ? AND id_carte = ? AND nb > 0");
    $inv->execute([
        $_SESSION['inventaire'],
        $_GET['tacarte']
    ]);

    $resu2 = $inv->fetch();

    if (!$resu2){
        exit;
    }

    $you = $bdd->prepare("SELECT id_echange FROM echange WHERE id_utilisateur_1 = ?");
    $you->execute([
        $_SESSION['id']
    ]);

    if ($you->fetch()){
        $prep = $bdd->prepare("UPDATE echange SET id_carte_1 = ? WHERE id_utilisateur_1 = ?");
        $prep->execute([
            $_GET['tacarte'],
            $_SESSION['id']
    ]);
    } else {
        $prep = $bdd->prepare("UPDATE echange SET id_carte_2 = ? WHERE id_utilisateur_2 = ?");
        $prep->execute([
            $_GET['tacarte'],
            $_SESSION['id']
    ]);
    }

}


if(isset($_GET['accepter'])){

    if ($_SESSION['id'] == $echange['id_utilisateur_1']){
        if ($echange['etat'] == 'j2'){
            $x = 'effectué';
        } else {
        $x = 'j1';
        }
    } else {
        if ($echange['etat'] == 'j1'){
            $x = 'effectué';
        } else {
        $x = 'j2';
        }
    }

    $statement3 = $bdd->prepare("UPDATE ECHANGE SET etat = :x WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $statement3->execute([
    'x'=>$x,
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);

    $statement4 = $bdd->prepare("SELECT * FROM ECHANGE WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $statement4->execute([
        'id1'=>$_SESSION['id'],
        'id2'=>$_GET['iduser']
    ]);
    $echange = $statement4->fetch();

}



if(isset($_GET['unaccepter'])){

    $statement3 = $bdd->prepare("UPDATE ECHANGE SET etat = :x WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $statement3->execute([
    'x'=>'en_cours',
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
]);
}



if ($echange['etat'] == 'effectué'){

    usleep(500000);

    if (!empty($echange['id_carte_1'])){

        if ($_SESSION['id'] == $echange['id_utilisateur_1']){

        $inventaire = $bdd->prepare("SELECT id_inventaire FROM inventaire WHERE id_utilisateur = ?");
        $inventaire->execute([
            $_GET['iduser']
        ]);
        $x=$inventaire->fetch();

        $updatedinv = $bdd->prepare('SELECT nb FROM contient WHERE id_inventaire = ? AND id_carte = ?');
        $updatedinv->execute([
            $x['id_inventaire'],
            $echange['id_carte_1']
        ]);
        $nbtotal = $updatedinv->fetch();

        if ($nbtotal){

            $ajouter = $bdd->prepare("UPDATE contient SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
            $ajouter->execute([
                $nbtotal['nb'] + 1,
                $x['id_inventaire'],
                $echange['id_carte_1']
            ]);
        } else {
            $ajouter = $bdd->prepare("INSERT INTO contient (id_inventaire, id_carte, nb) VALUES (?, ?, ?)");
            $ajouter->execute([
                $x['id_inventaire'],
                $echange['id_carte_1'],
                1
            ]);
        }


        $deleter = $bdd->prepare('SELECT nb FROM contient WHERE id_inventaire = ? AND id_carte = ?');
        $deleter->execute([
            $_SESSION['inventaire'],
            $echange['id_carte_1']
        ]);
        $deletergood = $deleter->fetch();

        if ($deletergood['nb'] > 1){

            $moins = $deletergood['nb'] - 1;

            $supprr = $bdd->prepare("UPDATE contient SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
            $supprr->execute([
                $moins,
                $_SESSION['inventaire'],
                $echange['id_carte_1']
            ]);
        } else {
            $supprr = $bdd->prepare("DELETE FROM contient WHERE id_inventaire = ? AND id_carte = ?");
            $supprr->execute([
                $_SESSION['inventaire'],
                $echange['id_carte_1']
            ]);
        }
    }
    } 



    if (!empty($echange['id_carte_2'])){

        if ($_SESSION['id'] == $echange['id_utilisateur_2']){

        $inventaire = $bdd->prepare("SELECT id_inventaire FROM inventaire WHERE id_utilisateur = ?");
        $inventaire->execute([
            $_GET['iduser']
        ]);
        $x=$inventaire->fetch();

        $updatedinv = $bdd->prepare('SELECT nb FROM contient WHERE id_inventaire = ? AND id_carte = ?');
        $updatedinv->execute([
            $x['id_inventaire'],
            $echange['id_carte_2']
        ]);
        $nbtotal = $updatedinv->fetch();

        if ($nbtotal){

            $ajouter = $bdd->prepare("UPDATE contient SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
            $ajouter->execute([
                $nbtotal['nb'] + 1,
                $x['id_inventaire'],
                $echange['id_carte_2']
            ]);
        } else {
            $ajouter = $bdd->prepare("INSERT INTO contient (id_inventaire, id_carte, nb) VALUES (?, ?, ?)");
            $ajouter->execute([
                $x['id_inventaire'],
                $echange['id_carte_2'],
                1
            ]);
        }


        $deleter = $bdd->prepare('SELECT nb FROM contient WHERE id_inventaire = ? AND id_carte = ?');
        $deleter->execute([
            $_SESSION['inventaire'],
            $echange['id_carte_2']
        ]);
        $deletergood = $deleter->fetch();

        if ($deletergood['nb'] > 1){

            $moins = $deletergood['nb'] - 1;

            $supprr = $bdd->prepare("UPDATE contient SET nb = ? WHERE id_inventaire = ? AND id_carte = ?");
            $supprr->execute([
                $moins,
                $_SESSION['inventaire'],
                $echange['id_carte_2']
            ]);
        } else {
            $supprr = $bdd->prepare("DELETE FROM contient WHERE id_inventaire = ? AND id_carte = ?");
            $supprr->execute([
                $_SESSION['inventaire'],
                $echange['id_carte_2']
            ]);
        }
    }
}




    $_SESSION['effectuée_change'] = 'yes';

    $statement8 = $bdd->prepare("DELETE FROM ECHANGE WHERE (id_utilisateur_1 = :id1 AND id_utilisateur_2 = :id2) OR (id_utilisateur_1 = :id2 AND id_utilisateur_2 = :id1)");
    $statement8->execute([
    'id1'=>$_SESSION['id'],
    'id2'=>$_GET['iduser']
    ]);
}

if ($_SESSION['effectuée_change'] == 'yes'){

    $_SESSION['echange_deja_effek'] = 'effek';
    echo "<h1>Echange effectué avec succes</h1>";
    echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
    exit;
}


if ($echange && $_SESSION['idechange'] == $echange['id_echange']){

    ?>

<h1>Echange avec <?= htmlspecialchars($_GET['user']) ?></h1>

<p> Si vous fermez la page pendant un certain temps, l'échange sera annulé</p>

    <h2> Vos cartes </h2>

    <?php 

echo "<div class='inventaire'>";

        if ($cartes) { 

            $compteur = 0;

            foreach ($cartes as $carte) { 
                $rarete = $carte['rarete'];
                $classesRarete = ['commun', 'rare', 'epique', 'legendaire'];
                $choixRarete = in_array($rarete, $classesRarete) ? $rarete : 'super_rare';
                ?>
                    <div onclick="tacarte(<?= json_encode($carte['id_carte']) ?>, <?= json_encode($compteur) ?>)" class="carte <?= $choixRarete ?>">
                        <img src="/carte/<?= $carte['image'] ?>" alt="<?= $carte['nom_carte'] ?>">
                        <p><strong><?php echo $carte['nom_carte'] ?></strong></p>
                        <p>Rareté: <?php echo $carte['rarete'] ?></p>
                        <p>Nombre de fois possédée: <?php echo $carte['nb'] ?></p>
                    </div>
            <?php  $compteur += 1;
            } 
        } else {
            echo "<h4> Vous n'avez pas de cartes à échanger </h4>";
        } 

        echo "</div>";

        ?>





        <p>---------------------------------------------------</p>
        <h2>Ta carte</h2>



        <?php if ($_SESSION['id'] == $echange['id_utilisateur_1']){
                    
                    $idcarte1 = $bdd->prepare("SELECT carte.*, contient.nb FROM carte JOIN contient on contient.id_carte = carte.id_carte WHERE carte.id_carte = ? AND contient.id_inventaire = ?");
                    $idcarte1->execute([
                        $echange['id_carte_1'],
                        $_SESSION['inventaire']
                        ]);
                    $carteid1 = $idcarte1->fetch();

                    if ($carteid1){
                    $rarete2 = $carteid1['rarete'];
                    $classesRarete2 = ['commun', 'rare', 'epique', 'legendaire'];
                    $choixRarete = in_array($rarete2, $classesRarete2) ? $rarete2 : 'super_rare';
                        ?>
                            <div class="carte <?= $choixRarete ?>">
                            <img src="/carte/<?= $carteid1['image'] ?>" alt="<?= $carteid1['nom_carte'] ?>">
                            <p><strong><?php echo $carteid1['nom_carte'] ?></strong></p>
                            <p>Rareté: <?php echo $carteid1['rarete'] ?></p>
                            <p>Nombre de fois possédée: <?php echo $carteid1['nb'] ?></p>
                            </div>
                        <?php

                }} else if ($_SESSION['id'] == $echange['id_utilisateur_2']) {
                        $idcarte2 = $bdd->prepare("SELECT carte.*, contient.nb FROM carte JOIN contient on contient.id_carte = carte.id_carte WHERE carte.id_carte = ? AND contient.id_inventaire = ?");
                        $idcarte2->execute([
                            $echange['id_carte_2'],
                            $_SESSION['inventaire']
                            ]);
                        $carteid2 = $idcarte2->fetch();
                
                        if ($carteid2){
                        $rarete2 = $carteid2['rarete'];
                        $classesRarete2 = ['commun', 'rare', 'epique', 'legendaire'];
                        $choixRarete = in_array($rarete2, $classesRarete2) ? $rarete2 : 'super_rare';
                            ?>
                                <div class="carte <?= $choixRarete ?>">
                                <img src="/carte/<?= $carteid2['image'] ?>" alt="<?= $carteid2['nom_carte'] ?>">
                                <p><strong><?php echo $carteid2['nom_carte'] ?></strong></p>
                                <p>Rareté: <?php echo $carteid2['rarete'] ?></p>
                                <p>Nombre de fois possédée: <?php echo $carteid2['nb'] ?></p>
                                </div>
                        <?php
                }
            } ?>

        <?php if ($echange['etat'] == 'j1'){
            if ($_SESSION['id'] == $echange['id_utilisateur_1']){
                $e = 'yey';
            echo '<p>Vous avez accepté l\'échange, en attente de l\'autre joueur</p>';
            } else {
                $e = 'good';
                echo '<p>L\'autre joueur a accepté l\'échange</p>';

            }
        } else if ($echange['etat'] == 'j2'){
            if ($_SESSION['id'] == $echange['id_utilisateur_2']){
                $e = 'yey';
                echo '<p>Vous avez accepté l\'échange, en attente de l\'autre joueur</p>';
                } else {
                    $e = 'good';
                    echo '<p>L\'autre joueur a accepté l\'échange</p>';
                }
        } else {
            echo "<br>";
        }

        if ($e != 'yey'){
            unset($e);
            echo "<button onclick='accepter()'>Accepter l'échange</button>";
        }

        if ($e == 'yey'){
            unset($e);
            echo "<button onclick='unaccepter()'>Annuler l’acceptation</button>";
        }

        ?>
        
        <button onclick='annuler()'>Annuler l'échange</button></p>
        <h2>Carte de l'autre joueur</h2>

        <?php   $inventaire = $bdd->prepare("SELECT id_inventaire FROM inventaire WHERE id_utilisateur = ?");
                $inventaire->execute([
                $_GET['iduser']
                ]);
                $x=$inventaire->fetch();
        
        if ($_GET['iduser'] == $echange['id_utilisateur_2']){

        $idcarte2 = $bdd->prepare("SELECT carte.*, contient.nb FROM carte JOIN contient on contient.id_carte = carte.id_carte WHERE carte.id_carte = ? AND contient.id_inventaire = ?");
        $idcarte2->execute([
            $echange['id_carte_2'],
            $x['id_inventaire']
        ]);
        $carteid2 = $idcarte2->fetch();

        if ($carteid2){

        $rarete2 = $carteid2['rarete'];
        $classesRarete2 = ['commun', 'rare', 'epique', 'legendaire'];
        $choixRarete = in_array($rarete2, $classesRarete2) ? $rarete2 : 'super_rare';
            ?>
                <div class="carte <?= $choixRarete ?>">
                <img src="/carte/<?= $carteid2['image'] ?>" alt="<?= $carteid2['nom_carte'] ?>">
                <p><strong><?php echo $carteid2['nom_carte'] ?></strong></p>
                <p>Rareté: <?php echo $carteid2['rarete'] ?></p>
                <p>Nombre de fois possédée: <?php echo $carteid2['nb'] ?></p>
                </div>
            <?php
        }
    } else if ($_GET['iduser'] == $echange['id_utilisateur_1']){
                    $idcarte1 = $bdd->prepare("SELECT carte.*, contient.nb FROM carte JOIN contient on contient.id_carte = carte.id_carte WHERE carte.id_carte = ? AND contient.id_inventaire = ?");
                    $idcarte1->execute([
                        $echange['id_carte_1'],
                        $x['id_inventaire']
                    ]);
                    $carteid1 = $idcarte1->fetch();

                    if ($carteid1){
                    $rarete2 = $carteid1['rarete'];
                    $classesRarete2 = ['commun', 'rare', 'epique', 'legendaire'];
                    $choixRarete = in_array($rarete2, $classesRarete2) ? $rarete2 : 'super_rare';
                        ?>
                            <div class="carte <?= $choixRarete ?>">
                            <img src="/carte/<?= $carteid1['image'] ?>" alt="<?= $carteid1['nom_carte'] ?>">
                            <p><strong><?php echo $carteid1['nom_carte'] ?></strong></p>
                            <p>Rareté: <?php echo $carteid1['rarete'] ?></p>
                            <p>Nombre de fois possédée: <?php echo $carteid1['nb'] ?></p>
                            </div>
                        <?php
            }
            
        } ?> 

        <p>---------------------------------------------------</p>









        <h2> Cartes de <?= htmlspecialchars($_GET['user']) ?></h2>

    <?php 

echo "<div class='inventaire'>";

        if ($cartes2) { 
            foreach ($cartes2 as $carte2) { 

                if ($_SESSION['id'] == $echange['id_utilisateur_1']){
                    if ($echange['id_carte_2'] == $carte2['id_carte']){
                        $style = 'style="border: 2px solid green; outline: 2px solid blue;"';
                    } else {
                        $style = '';
                    }
                } else {
                    if ($echange['id_carte_1'] == $carte2['id_carte']){
                        $style = 'style="border: 2px solid green; outline: 2px solid blue;"';
                    } else {
                        $style = '';
                    }
                }
                
                $rarete2 = $carte2['rarete'];
                $classesRarete2 = ['commun', 'rare', 'epique', 'legendaire'];
                $choixRarete = in_array($rarete2, $classesRarete2) ? $rarete2 : 'super_rare';
                ?>
                    <div class="carte <?= $choixRarete ?>" <?= $style ?>>
                        <img src="/carte/<?= $carte2['image'] ?>" alt="<?= $carte2['nom_carte'] ?>">
                        <p><strong><?php echo $carte2['nom_carte'] ?></strong></p>
                        <p>Rareté: <?php echo $carte2['rarete'] ?></p>
                        <p>Nombre de fois possédée: <?php echo $carte2['nb'] ?></p>
                    </div>
            <?php } 
        } else {
        echo "<h4>" . htmlspecialchars($_GET['user']) . " n'a pas de cartes à échanger </h4>";
        } 

        echo "</div>";

        } else {
            $_SESSION['echange_cancel'] = 'yes';
            echo "<h1>Echange annulé</h1>";
            echo "<h3><a href='discussion.php?user=" . $_GET['user'] . "'>Retour aux messages</a></h3>";
        }?>