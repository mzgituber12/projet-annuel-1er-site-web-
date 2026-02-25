<style>
  .wrap {
    height: 100%;
    display: flex;
    justify-content: end;
    padding: 20px;
  }
     
.button1{
  width: 140px;
  height: 45px;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 500;
  color: #000;
  background-color: #fff;
  border: none;
  border-radius: 10px;
  box-shadow: 0px 8px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: none;
}

.button1:hover{
  background-color: #2EE29D;
  box-shadow: 0px 15px 20px rgba(46,229,157,0.4);
  color: #fff;
  transform: translate(-7px);
}

.button0{
  width: 140px;
  height: 45px;
  font-size: 11px;
  text-transform: uppercase;
  letter-spacing: 2.5px;
  font-weight: 500;
  color: #000;
  background-color: #fff;
  border: none;
  border-radius: 10px;
  box-shadow: 0px 8px 15px rgba(0,0,0,0.1);
  transition: all 0.3s ease 0s;
  cursor: pointer;
  outline: none;
}

.button0:hover{
  background-color:rgb(252, 0, 0);
  box-shadow: 0px 15px 20px rgba(210, 19, 13, 0.77);
  color: #fff;
  transform: translate(-7px);
}
</style>


<?php

if(isset($_SESSION['id'])){
$id = $_SESSION['id'];
}
include('bdd.php');

$statement = $bdd->query("SELECT * FROM ACTUALITE ORDER BY date_creation DESC LIMIT 4");
$actualites = $statement->fetchAll(PDO::FETCH_ASSOC);
?>

<section>
  <h1 class="milonga-regular" style="text-align:center">Actualité</h1>
  <?php
  $abo = $_SESSION['abonne'] ?? 0;

  if ($abo == 0 && isset($_SESSION['pseudo'])): ?>
    <form method="post">
    <div class="wrap">
    <button type="submit" name="abo1" class="button1" onclick="alert('Merci pour votre abonnement')">S'abonner</button>
    
  </div>
    </form>
  <?php elseif(isset($_SESSION['pseudo'])): ?>
    <form method="post">
    <div class="wrap">
    <button type="submit" name="abo0" class="button0">Se désabonner</button>
    </div>
  </form>
<?php endif;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['abo1'])) {
      $modif = "UPDATE UTILISATEUR SET abonne = 1 WHERE id_utilisateur = ? ;";
      $statement = $bdd->prepare($modif);
      $statement->execute([$id]);
      $_SESSION['abonne'] = 1;
  } elseif (isset($_POST['abo0'])) {
      $modif = "UPDATE UTILISATEUR SET abonne = 0 WHERE id_utilisateur = ? ;";
      $statement = $bdd->prepare($modif);
      $statement->execute([$id]);
      $_SESSION['abonne'] = 0;
  }
  header("Location: ".$_SERVER['PHP_SELF']);
}
  ?>

<div class="container">
  <div class="row flex-nowrap overflow-auto">
    <?php foreach ($actualites as $actu):
      if ($actu['image']){
        $image = '<img src="imageactu/' . htmlspecialchars($actu['image']) . '"class="card-img-top" alt="Image de l\'actualité">';
      } else {
        $image = '';
      }?>
      <div class="col-md-4">
        <div class="card mb-4">
          <?= $image ?>
          <div class="card-body">
            <h5 class="milonga-regular" class="card-title"><?= htmlspecialchars($actu['titre']) ?></h5>
            <p class="card-text"><?= nl2br(htmlspecialchars($actu['contenu'])) ?></p>
          </div>
          <div class="card-footer text-muted">
            Publié le <?= date("d/m/Y H:i", strtotime($actu['date_creation'])) ?>
          </div>
        </div>
      </div>    
    <?php 
  endforeach; ?>
  </div>
  
</div>