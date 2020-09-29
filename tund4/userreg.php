<?php

$username = "Martin Kilgi";

//Errorite muutujad
$inputerror = "";
$firstnameerror = "";
$lastnameerror = "";
$gendererror = "";
$emailerror = "";
$passworderror = "";

//info muutujad
$firstname = "";
$lastname = "";
$genderinput = "";
$emailinfo = "";
$passwordinput = "";
$passwordsecondaryinput = "";

//kas vajutati salvestusnuppu
if (isset($_POST["regsubmit"])) {
  $firstname = ($_POST["firstnameinput"]);
  $lastname = ($_POST["lastnameinput"]);
  $genderinput = ($_POST["genderinput"]);
  $emailinfo = ($_POST["emailinput"]);

  if (!empty($_POST["passwordinput"])) {
    $passwordinput = ($_POST["emailinput"]);
  } else {
    $passworderror = "Salasõna on sisestamata!";
  }

  if(strlen($_POST["passwordinput"]) < 8) {
    $passworderror = "Salasõna on liiga lühike.";
} if (($_POST["passwordinput"]) !== ($_POST["passwordsecondaryinput"])) {
    $passworderror = "Teine salasõna ei kattu esimesega";
}
  if(empty($_POST["firstnameinput"]) or empty($_POST["genderinput"]) or empty($_POST["lastnameinput"]) or empty($_POST["emailinput"]) or empty($_POST["passwordinput"]) or empty($_POST["passwordsecondaryinput"])) { 
    $inputerror .= "Osa infot on sisestamata!";
  }
  if (empty($inputerror) && empty($firstnameerror) && empty($lastnameerror) && empty($gendererror) && empty($emailerror) && empty($passworderror)) {

  
}


}


require("header.php");
?>

<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo $username; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
  <li><a href="home.php">Siit saab minna tagasi avalehele</a><br /></li>
<hr>
<form method="POST">

<label for="firstname">Eesnimi</label>
<input type="text" name="firstnameinput" id="firstnameinput" value="<?php echo $firstname; ?>"><span><?php echo $firstnameerror; ?></span>
<br>
<label for="lastname">Perekonnanimi</label>
<input type="text" name="lastnameinput" id="lastnameinput" value="<?php echo $lastname; ?>"><span><?php echo $lastnameerror; ?></span>
<br>
<label for="gendermale">Mees</label>
<input type="radio" name="genderinput" id="gendermaleinput" value="1" <?php if($genderinput == "1"){echo " checked";}?>><span><?php echo $gendererror; ?></span>
<br>
<label for="genderfemale">Naine</label>
<input type="radio" name="genderinput" id="genderfemaleinput" value="2" <?php if($genderinput == "2"){echo " checked";}?>><span><?php echo $gendererror; ?></span>
<br>
<label for="email">E-mail</label>
<input type="email" name="emailinput" id="emailinput" value="<?php echo $emailinfo; ?>"><span><?php echo $emailerror; ?></span>
<br>
<label for="password">Salasõna</label>
<input type="password" name="passwordinput" id="passwordinput"><span><?php echo $passworderror; ?></span>
<br>
<label for="passwordsecondary">Salasõna teist korda</label>
<input type="password" name="passwordsecondaryinput" id="passwordsecondaryinput"><span><?php echo $passworderror; ?></span>
<br>
<input type="submit" name="regsubmit" value="Registreeru">

</form>
<p><?php echo $inputerror; ?></p>
</body>
</html>






