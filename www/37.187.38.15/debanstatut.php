<?php session_start();
include("bdd.php");

if (!isset($_SESSION['pseudo'])){
    header('location:index.php');
    exit;
}

    $ban = $bdd->prepare("SELECT ban, role FROM utilisateur WHERE pseudo = :pseudo");
    $ban->execute([
        'pseudo'=>$_SESSION['pseudo']
    ]);
    $banresult = $ban->fetch();
    $_SESSION['role'] = $banresult['role'];

    if($banresult['ban'] == 0){
        header('location:index.php');
        exit;
    }

echo "<h1> Donnez nous le motif pour lequel vous avez été banni, nous vous repondrons par mail le cas écheant. </h1>";
echo "<h2> Mentir sur le motif de bannissement peut entrainer un ban permanent sans possibilité de soumettre le formulaire ci dessous. </h2>";

include('getmessage.php');

?>

<form method='post' action='debanverif.php'>
    <p><label>Indiquez l'adresse email associée au compte</label>
    <input type='email' name='email'></p>
    <p><label>Indiquez la raison de votre bannissement</label><br>
    <textarea name="raison" rows="18" style="width: 50%;" required></textarea></p>
    <input type='submit' value='Envoyer'>
</form>

<a href='deconnexion.php'>Se deconnecter</a>