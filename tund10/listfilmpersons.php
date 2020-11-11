<?php


//$username = "Martin Kilgi";

require("usesession.php");
require("../../../config.php");
require("fnc_addfilmandgenre.php");

$sortby = 0;
$sortorder = 0;

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
<h1>Siin kuvatakse filmide andmed!</h1>
<hr>
  <?php
    if(isset($_GET["sortby"]) and isset($_GET["sortorder"])) {
      if($_GET["sortby"] >= 1 and $_GET["sortorder"] <= 4) {
        $sortby = intval($_GET["sortby"]);
      }
      if($_GET["sortorder"] == 1 or $_GET["sortorder"] == 2) {
        $sortorder = intval($_GET["sortorder"]);
      }
    }
   echo readpersoninmovie($sortby, $sortorder); 
  ?>
<hr>
<ul>
<p><a href="?logout=1">Logi välja!</a></p>
</ul>
</body>
</html>