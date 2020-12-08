<?php

//$username = "Martin Kilgi";

require("usesession.php");
require("../../../config.php");
require("fnc_viljavedu.php");

$inputerror = "";

//kas vajutati salvestusnuppu
if (isset($_POST["carsubmit"])) {
  if(empty($_POST["registernumber"]) or empty($_POST["enteringmass"]) or empty($_POST["leavingmass"])) {
    $inputerror .= "Osa infot on sisestamata!";
  }
  if(empty($inputerror)) {
    $storeinfo = storeCarInfo($_POST["registernumber"], $_POST["enteringmass"], $_POST["leavingmass"]);
  }
}



require("header.php");

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> Filmide lisamine</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require("../nav.php");?>
<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Tere " .$_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
<h1>Autode viljavedu</h1>
<form method="POST">
  <label for="registernumber">Auto registrinumber</label>
  <input type="text" name="registernumber" id="registernumber" placeholder="Registrinumber">
  <br>
  <label for="enteringmass">Auto kaal sisenemisel(kg)</label>
  <input type="text" name="enteringmass" id="enteringmass" placeholder="Sisenemiskaal">
  <br>
  <label for="leavingmass">Auto kaal väljumisel(kg)</label>
  <input type="text" name="leavingmass" id="leavingmass" placeholder="Väljumiskaal">
  <br>
  <input type="submit" name="carsubmit" value="Salvesta info!">

</form>
<p><?php echo $inputerror; ?></p>
<hr>
<ul>
<li><a href="home.php">Siit saab tagasi avalehele</a></li>
<p><a href="?logout=1">Logi välja!</a></p>
</ul>
</body>
</html>