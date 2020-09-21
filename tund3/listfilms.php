<?php

$username = "Martin Kilgi";

require("../../../config.php");
require("fnc_film.php");

//loen andmebaasist filmide info
$filmhtml = readfilms();

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> progeb veebi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo $username; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
<h1>Siin kuvatakse filmide andmed!</h1>
<hr>
  <?php echo readfilms(0); ?>
<hr>
<ul>
<li><a href="home.php">Siit saab tagasi avalehele</a></li>
<li><a href="motetesisestamine.php">Siit saate minna uusi mõtted kirja panema</a></li>
</ul>
</body>
</html>