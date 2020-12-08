<?php


//$username = "Martin Kilgi";

require("usesession.php");
require("../../../config.php");
require("fnc_viljavedu.php");

$sortby = 0;
$sortorder = 0;

//$carhtml = readCarInfo();
//$loadhtml = readLoadInfo();

if (isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
    if ($_GET["sortby"] >= 1 and $_GET["sortby"] <= 4) {
    $sortby = intval($_GET["sortby"]); 
    }
    if ($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
    $sortorder = intval($_GET["sortorder"]);
    }
}

require("header.php");

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> Filmide list</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require("../nav.php");?>
<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Tere " .$_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
<h1>Siin kuvatakse viljaveo andmed!</h1>
<hr>
<h2>Masinate info!</h2>
<?php
   echo readCarInfo($sortby, $sortorder);
?>
<h2>Koormate info!</h2>
<?php
    echo readLoadInfo();
?>
<h2>Vilja täismass</h2>
<?php
    echo readSumLoad();
?>
<hr>
<ul>
<li><a href="home.php">Siit saab tagasi avalehele</a></li>
<li><a href="motetesisestamine.php">Siit saate minna uusi mõtted kirja panema</a></li>
<p><a href="?logout=1">Logi välja!</a></p>
</ul>
</body>
</html>