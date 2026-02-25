<?php
session_start();

include('pasadmin.php');
include('../bdd.php');
?>


<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <title>Index admin</title>
  </head>
<body>
<?php 
include("headerad.php");
?>
<main class="container my-5">

<h1>Admin</h1>

<script>
function resetAll() {
        const zones = ['email', 'admin', 'ban', 'edit', 'supp', 'carte', 'attaque', 'actu', 'inv', 'alerter'];

        zones.forEach(id => {
            const label = document.getElementById(id + '1');
            if (label) label.innerHTML = '';
            const all = document.querySelectorAll('.' + id + '2');
            all.forEach(el => {
            el.style.display = "none";
            });
            const input = document.getElementById(id + 'change')
            input.value=''
            const here = document.getElementById(id + 'here')
            here.style.display = "none"
        });

        document.getElementById('invchange2').style.display="none"
        document.getElementById('invchange2').value=""

        const zones2 = ['email', 'admin', 'ban', 'carte', 'attaque', 'actu', 'inv']

        zones2.forEach(id => {
            const label2 = document.getElementById(id + '1_1');
            const label3 = document.getElementById(id + '1_2');
            const label4 = document.getElementById(id + '1_3');
            const label5 = document.getElementById(id + '1_4');
            if (label2) label2.innerHTML = '';
            if (label3) label3.innerHTML = '';
            if (label4) label4.innerHTML = '';
            if (label5) label5.innerHTML = '';
        });

    }


    function email() {
        resetAll();
        document.getElementById('email1').innerHTML = 'Choisissez l\'utilisateur'
        document.getElementById('email1_1').innerHTML = 'Verifier l\'email'
        document.getElementById('email1_2').innerHTML = 'Supprimer la verification d\'email'
        const all_email = document.querySelectorAll('.email2');
        all_email.forEach((el, i) => {
        el.style.display = "";
        el.checked = (i === 0)
        });
        document.getElementById('commande').value = 'commande/verif'
    }
    function email2(){
        document.getElementById('commande').value = 'commande/verif'
        const input = document.getElementById('emailchange')
        input.value=''
        document.getElementById('emailhere').style.display='none'
    }
    function email3(){
        document.getElementById('commande').value = 'commande/suppverif'
        const input = document.getElementById('emailchange')
        input.value=''
        document.getElementById('emailhere').style.display='none'
    }
    let pseudoem = ''
    function email4(){
        let commande = document.getElementById('commande').value;
    if (pseudoem) {
        commande = commande.replace("/" + pseudoem, '');
    }
    pseudoem = document.getElementById('emailchange').value;
    if (pseudoem) {
        commande += "/" + pseudoem;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('emailhere').style.display=''
    }


    function admin() {
        resetAll();
        document.getElementById('admin1').innerHTML = 'Choisissez l\'utilisateur';
        document.getElementById('admin1_1').innerHTML = 'Mettre admin'
        document.getElementById('admin1_2').innerHTML = 'Supprimer d\'admin'
        const all = document.querySelectorAll('.admin2');
        all .forEach((el, i) => {
        el.style.display = "";
        el.checked = (i === 0)
        });
        document.getElementById('commande').value = 'commande/admin'
    }
    function admin2(){
        document.getElementById('commande').value = 'commande/admin'
        const input = document.getElementById('adminchange')
        input.value=''
        document.getElementById('adminhere').style.display='none'
    }
    function admin3(){
        document.getElementById('commande').value = 'commande/suppadmin'
        const input = document.getElementById('adminchange')
        input.value=''
        document.getElementById('adminhere').style.display='none'
    }
    let pseudoad = ''
    function admin4(){
        let commande = document.getElementById('commande').value;
    if (pseudoad) {
        commande = commande.replace("/" + pseudoad, '');
    }
    pseudoad = document.getElementById('adminchange').value;
    if (pseudoad) {
        commande += "/" + pseudoad;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('adminhere').style.display=''
    }


    function ban() {
        resetAll();
        document.getElementById('ban1').innerHTML = 'Choisissez l\'utilisateur';
        document.getElementById('ban1_1').innerHTML = 'Bannir'
        document.getElementById('ban1_2').innerHTML = 'Debannir'
        const all = document.querySelectorAll('.ban2');
        all.forEach((el, i) => {
        el.style.display = "";
        el.checked = (i === 0)
        });
        document.getElementById('commande').value = 'commande/ban'
    }
    function ban2(){
        document.getElementById('commande').value = 'commande/ban'
        const input = document.getElementById('banchange')
        input.value=''
        document.getElementById('banhere').style.display='none'
    }
    function ban3(){
        document.getElementById('commande').value = 'commande/deban'
        const input = document.getElementById('banchange')
        input.value=''
        document.getElementById('banhere').style.display='none'
    }
    let pseudoban = ''
    function ban4(){
        let commande = document.getElementById('commande').value;
    if (pseudoban) {
        commande = commande.replace("/" + pseudoban, '');
    }
    pseudoban = document.getElementById('banchange').value;
    if (pseudoban) {
        commande += "/" + pseudoban;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('banhere').style.display=''
    }



    function alerter() {
        resetAll();
        document.getElementById('alerter1').innerHTML = 'Choisissez l\'utilisateur';
        const all = document.querySelectorAll('.alerter2');
        all.forEach(el => {
        el.style.display = "";
        });
        document.getElementById('commande').value = 'commande/avertir'
    }
    let pseudoalerter = ''
    function alerter2(){
        let commande = document.getElementById('commande').value;
    if (pseudoalerter) {
        commande = commande.replace("/" + pseudoalerter, '');
    }
    pseudoalerter = document.getElementById('alerterchange').value;
    if (pseudoalerter) {
        commande += "/" + pseudoalerter;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('alerterhere').style.display=''
    }




    function edit() {
        resetAll();
        document.getElementById('edit1').innerHTML = 'Choisissez l\'utilisateur';
        const all = document.querySelectorAll('.edit2');
        all.forEach(el => {
        el.style.display = "";
        });
        document.getElementById('commande').value = 'commande/modifier'
    }
    let editsupp = ''
    function edit2(){
        let commande = document.getElementById('commande').value;
    if (editsupp) {
        commande = commande.replace("/" + editsupp, '');
    }
    editsupp = document.getElementById('editchange').value;
    if (editsupp) {
        commande += "/" + editsupp;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('edithere').style.display=''
    }



    function supp() {
        resetAll();
        document.getElementById('supp1').innerHTML = 'Choisissez l\'utilisateur';
        const all = document.querySelectorAll('.supp2');
        all.forEach(el => {
        el.style.display = "";
        });
        document.getElementById('commande').value = 'commande/supprimer'
    }
    let pseudosupp = ''
    function supp2(){
        let commande = document.getElementById('commande').value;
    if (pseudosupp) {
        commande = commande.replace("/" + pseudosupp, '');
    }
    pseudosupp = document.getElementById('suppchange').value;
    if (pseudosupp) {
        commande += "/" + pseudosupp;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('supphere').style.display=''
    }
    

    function carte() {
        resetAll();
        document.getElementById('carte1_1').innerHTML = 'Creer'
        document.getElementById('carte1_2').innerHTML = 'Modifier'
        document.getElementById('carte1_3').innerHTML = 'Supprimer'
        const all = document.querySelectorAll('.carte2');
        all .forEach(el => {
        el.style.display = "";
        el.checked = false
        });
        document.getElementById('commande').value = 'commande/carte'
        document.getElementById('cartechange').style.display='none'
    }
    function carte2(){
        document.getElementById('commande').value = 'commande/carte/creer'
        document.getElementById('carte1').innerHTML = ''
        const all = document.querySelectorAll('.carte2')
        all[3].style.display="None";
        const input = document.getElementById('cartechange')
        input.value=''
        document.getElementById('cartehere').style.display=''
    }
    function carte3(){
        document.getElementById('commande').value = 'commande/carte/modifier'
        document.getElementById('carte1').innerHTML = 'Selectionnez une carte'
        const all = document.querySelectorAll('.carte2')
        all[3].style.display="";
        const input = document.getElementById('cartechange')
        input.value=''
        document.getElementById('cartehere').style.display='none'
    }
    function carte4(){
        document.getElementById('commande').value = 'commande/carte/supprimer'
        document.getElementById('carte1').innerHTML = 'Selectionnez une carte'
        const all = document.querySelectorAll('.carte2')
        all[3].style.display="";
        const input = document.getElementById('cartechange')
        input.value=''
        document.getElementById('cartehere').style.display='none'
    }
    let nomcarte = ''
    function carte5(){
        let commande = document.getElementById('commande').value;
    if (nomcarte) {
        commande = commande.replace("/" + nomcarte, '');
    }
    nomcarte = document.getElementById('cartechange').value;
    if (nomcarte) {
        commande += "/" + nomcarte;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('cartehere').style.display=''
    }


    function attaque() {
        resetAll();
        document.getElementById('attaque1_1').innerHTML = 'Creer'
        document.getElementById('attaque1_2').innerHTML = 'Modifier'
        document.getElementById('attaque1_3').innerHTML = 'Supprimer'
        const all = document.querySelectorAll('.attaque2');
        all .forEach(el => {
        el.style.display = "";
        el.checked = false
        });
        document.getElementById('commande').value = 'commande/attaque'
        document.getElementById('attaquechange').style.display='none'
    }
    function attaque2(){
        document.getElementById('commande').value = 'commande/attaque/creer'
        document.getElementById('attaque1').innerHTML = ''
        const all = document.querySelectorAll('.attaque2')
        all[3].style.display="None";
        const input = document.getElementById('attaquechange')
        input.value=''
        document.getElementById('attaquehere').style.display=''
    }
    function attaque3(){
        document.getElementById('commande').value = 'commande/attaque/modifier'
        document.getElementById('attaque1').innerHTML = 'Selectionnez une attaque'
        const all = document.querySelectorAll('.attaque2')
        all[3].style.display="";
        const input = document.getElementById('attaquechange')
        input.value=''
        document.getElementById('attaquehere').style.display='none'
    }
    function attaque4(){
        document.getElementById('commande').value = 'commande/attaque/supprimer'
        document.getElementById('attaque1').innerHTML = 'Selectionnez une attaque'
        const all = document.querySelectorAll('.attaque2')
        all[3].style.display="";
        const input = document.getElementById('attaquechange')
        input.value=''
        document.getElementById('attaquehere').style.display='none'
    }
    let nomattaque = ''
    function attaque5(){
        let commande = document.getElementById('commande').value;
    if (nomattaque) {
        commande = commande.replace("/" + nomattaque, '');
    }
    nomattaque = document.getElementById('attaquechange').value;
    if (nomattaque) {
        commande += "/" + nomattaque;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('attaquehere').style.display=''
    }

    function actu() {
        resetAll();
        document.getElementById('actu1_1').innerHTML = 'Creer'
        document.getElementById('actu1_2').innerHTML = 'Modifier'
        document.getElementById('actu1_3').innerHTML = 'Supprimer'
        const all = document.querySelectorAll('.actu2');
        all .forEach(el => {
        el.style.display = "";
        el.checked = false
        });
        document.getElementById('commande').value = 'commande/actu'
        document.getElementById('actuchange').style.display='none'
    }
    function actu2(){
        document.getElementById('commande').value = 'commande/actu/creer'
        document.getElementById('actu1').innerHTML = ''
        const all = document.querySelectorAll('.actu2')
        all[3].style.display="None";
        const input = document.getElementById('actuchange')
        input.value=''
        document.getElementById('actuhere').style.display=''
    }
    function actu3(){
        document.getElementById('commande').value = 'commande/actu/modifier'
        document.getElementById('actu1').innerHTML = 'Selectionnez une actualité'
        const all = document.querySelectorAll('.actu2')
        all[3].style.display="";
        const input = document.getElementById('actuchange')
        input.value=''
        document.getElementById('actuhere').style.display='none'
    }
    function actu4(){
        document.getElementById('commande').value = 'commande/actu/supprimer'
        document.getElementById('actu1').innerHTML = 'Selectionnez une actualité'
        const all = document.querySelectorAll('.actu2')
        all[3].style.display="";
        const input = document.getElementById('actuchange')
        input.value=''
        document.getElementById('actuhere').style.display='none'
    }
    let nomactu = ''
    function actu5(){
        let commande = document.getElementById('commande').value;
    if (nomactu) {
        commande = commande.replace("/" + nomactu, '');
    }
    nomactu = document.getElementById('actuchange').value;
    if (nomactu) {
        commande += "/" + nomactu;
    }
    document.getElementById('commande').value = commande;

    document.getElementById('actuhere').style.display=''
    }

function inv() {
    resetAll();
    document.getElementById('inv1_1').innerHTML = 'Ajouter';
    document.getElementById('inv1_2').innerHTML = 'Retirer';
    const all = document.querySelectorAll('.inv2');
    all.forEach((el, i) => {
        el.style.display = '';
        el.checked = false
    });
    document.getElementById('commande').value = 'commande/inventaire';
    document.getElementById('invchange').style.display = 'none';
}
function inv2() {
    document.getElementById('commande').value = 'commande/inventaire/ajouter';
    document.getElementById('inv1_3').innerHTML = 'Selectionnez une carte';
    document.getElementById('invchange').style.display = '';
    document.getElementById('invchange').value = '';
    document.getElementById('invchange2').style.display = 'none';
    document.getElementById('invhere').style.display = 'none';
    document.getElementById('inv1_4').innerHTML = '';
}
function inv3() {
    document.getElementById('commande').value = 'commande/inventaire/retirer';
    document.getElementById('inv1_3').innerHTML = 'Selectionnez une carte';
    document.getElementById('invchange').style.display = '';
    document.getElementById('invchange').value = '';
    document.getElementById('invchange2').style.display = 'none';
    document.getElementById('invhere').style.display = 'none';
    document.getElementById('inv1_4').innerHTML = '';
}
let nominv = '';
let nominv2 = '';
function inv4() {
    document.getElementById('inv1_4').innerHTML = 'Selectionnez un utilisateur';
    document.getElementById('invchange2').style.display = '';
    document.getElementById('invchange2').value = '';
    document.getElementById('invhere').style.display = 'none';
    let commande = document.getElementById('commande').value;
    if (nominv2) {
        commande = commande.replace("/" + nominv2, '');
    }
    if (nominv) {
        commande = commande.replace("/" + nominv, '');
    }
    nominv = document.getElementById('invchange').value;
    if (nominv) {
        commande += "/" + nominv;
    }
    document.getElementById('commande').value = commande;
}
function inv5() {
    let commande = document.getElementById('commande').value;
    if (nominv2) {
        commande = commande.replace("/" + nominv2, '');
    }
    nominv2 = document.getElementById('invchange2').value;
    if (nominv2) {
        commande += "/" + nominv2;
    }
    document.getElementById('commande').value = commande;
    const utilisateur = document.getElementById('invchange2').value;
    if (utilisateur) {
        document.getElementById('invhere').style.display = '';
    }
}

</script>

<?php
include('../getmessage.php');
?>
<p>
<form id='form_commande' method="post" action="traitement_commande.php">
        <label>panneau de commande admin </label>
        <input type="text" id="commande" name="commande">
        <input type="submit" value="Envoyer">
    </form>
</p>

<p><label>Verifier ou supprimer la verification d'email</label>
<input type='radio' id='verif' onclick = 'email()' name='1'><br>
<label id='email1_1'></label>
<input type='radio' class='email2' onclick = 'email2()' name='2' style='display:none'><br>
<label id='email1_2'></label>
<input type='radio' class='email2' onclick = 'email3()' name='2' style='display:none'><br>
<label id='email1'></label>
<select class="email2" id="emailchange" style='display:none' onchange="email4()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='emailhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Mettre admin ou supprimer d'admin</label>
<input type='radio' id='admin' onclick = 'admin()' name='1'><br>
<label id='admin1_1'></label>
<input type='radio' class='admin2' onclick = 'admin2()' name='2' style='display:none'><br>
<label id='admin1_2'></label>
<input type='radio' class='admin2' onclick = 'admin3()' name='2' style='display:none'><br>
<label id='admin1'></label>
<select class="admin2" id="adminchange" style='display:none' onchange="admin4()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='adminhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Bannir ou debannir</label>
<input type='radio' id='ban' onclick = 'ban()' name='1'><br>
<label id='ban1_1'></label>
<input type='radio' class='ban2' onclick = 'ban2()' name='2' style='display:none'><br>
<label id='ban1_2'></label>
<input type='radio' class='ban2' onclick = 'ban3()' name='2' style='display:none'><br>
<label id='ban1'></label>
<select class="ban2" id="banchange" style='display:none' onchange="ban4()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='banhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Avertir un utilisateur</label>
<input type='radio' id='alerter' onclick = 'alerter()' name='1'><br>
<label id='alerter1'></label>
<select class="alerter2" id="alerterchange" style='display:none' onchange="alerter2()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='alerterhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Modifier les données d'un utilisateur</label>
<input type='radio' id='edit' onclick = 'edit()' name='1'><br>
<label id='edit1'></label>
<select class="edit2" id="editchange" style='display:none' onchange="edit2()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='edithere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Supprimer un utilisateur</label>
<input type='radio' id='supp' onclick = 'supp()' name='1'><br>
<label id='supp1'></label>
<select class="supp2" id="suppchange" style='display:none' onchange="supp2()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='supphere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Creer, Modifier ou Supprimer une carte</label>
<input type='radio' id='carte' onclick = 'carte()' name='1'><br>
<label id='carte1_1'></label>
<input type='radio' class='carte2' onclick = 'carte2()' name='2' style='display:none'><br>
<label id='carte1_2'></label>
<input type='radio' class='carte2' onclick = 'carte3()' name='2' style='display:none'><br>
<label id='carte1_3'></label>
<input type='radio' class='carte2' onclick = 'carte4()' name='2' style='display:none'><br>
<label id='carte1'></label>
<select class="carte2" id="cartechange" style='display:none' onchange="carte5()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT nom_carte, id_carte FROM CARTE");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['id_carte']) . '">' . htmlspecialchars($row['id_carte']) . ' -> ' . htmlspecialchars($row['nom_carte']) . '</option>';
        }
        ?>
</select>
<button id='cartehere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Creer, Modifier ou Supprimer une attaque</label>
<input type='radio' id='attaque' onclick = 'attaque()' name='1'><br>
<label id='attaque1_1'></label>
<input type='radio' class='attaque2' onclick = 'attaque2()' name='2' style='display:none'><br>
<label id='attaque1_2'></label>
<input type='radio' class='attaque2' onclick = 'attaque3()' name='2' style='display:none'><br>
<label id='attaque1_3'></label>
<input type='radio' class='attaque2' onclick = 'attaque4()' name='2' style='display:none'><br>
<label id='attaque1'></label>
<select class="attaque2" id="attaquechange" style='display:none' onchange="attaque5()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT nom, id_attaque FROM ATTAQUE_CARTE");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['id_attaque']) . '">' . htmlspecialchars($row['id_attaque']) . ' -> ' . htmlspecialchars($row['nom']) . '</option>';
        }
        ?>
</select>
<button id='attaquehere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Creer, Modifier ou Supprimer une actualité</label>
<input type='radio' id='actu' onclick = 'actu()' name='1'><br>
<label id='actu1_1'></label>
<input type='radio' class='actu2' onclick = 'actu2()' name='2' style='display:none'><br>
<label id='actu1_2'></label>
<input type='radio' class='actu2' onclick = 'actu3()' name='2' style='display:none'><br>
<label id='actu1_3'></label>
<input type='radio' class='actu2' onclick = 'actu4()' name='2' style='display:none'><br>
<label id='actu1'></label>
<select class="actu2" id="actuchange" style='display:none' onchange="actu5()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT titre, id_actualite FROM ACTUALITE");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['id_actualite']) . '">' . htmlspecialchars($row['id_actualite']) . ' -> ' . htmlspecialchars($row['titre']) . '</option>';
        }
        ?>
</select>
<button id='actuhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

<p><label>Ajouter ou retirer une carte à un inventaire</label>
<input type='radio' id='inv' onclick = 'inv()' name='1'><br>
<label id='inv1_1'></label>
<input type='radio' class='inv2' onclick = 'inv2()' name='2' style='display:none'><br>
<label id='inv1_2'></label>
<input type='radio' class='inv2' onclick = 'inv3()' name='2' style='display:none'><br>
<label id='inv1_3'></label>
<select class="inv2" id="invchange" style='display:none' onchange="inv4()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT nom_carte, id_carte FROM CARTE");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['id_carte']) . '">' . htmlspecialchars($row['id_carte']) . ' -> ' . htmlspecialchars($row['nom_carte']) . '</option>';
        }
        ?>
</select>
<label id='inv1_4'></label>
<select class="inv2_2" id="invchange2" style='display:none' onchange="inv5()">
<option value = ""></option>
        <?php
        $stmt = $bdd->query("SELECT pseudo FROM UTILISATEUR");
        while ($row = $stmt->fetch()) {
            echo '<option value="' . htmlspecialchars($row['pseudo']) . '">' . htmlspecialchars($row['pseudo']) . '</option>';
        }
        ?>
</select>
<button id='invhere' type="submit" form="form_commande" style='display:none'>Envoyer</button></p>

    </main>
    <?php 
include("footerad.php");
?>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>