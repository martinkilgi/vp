
<?php

$username = "Peeter Pakiraam";
$fulltimenow = date("d.m.Y H:i:s");
$hournow = date("H");
$partofday = "lihtsalt aeg";
if ($hournow < 7) {
  $partofday = "uneaeg";
}
if ($hournow >= 8 and $hournow < 18) {
	$partofday = "akadeemilise aktiivsuse aeg";
}
?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> progeb veebi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>

  <h1><?php echo $username; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
  <p> Lehe avamise hetkel oli: <?php echo $fulltimenow; ?>.</p>
  <p><?php echo "Parajasti on " .$partofday .".";  ?></p>
  <img src="pics/Mountain.jpg" alt="Mäed" id="pic1">
  <img src="pics/mäed.jfif" alt="Veel suuremad mäed" id="pic2">
  <img src="pics/Magi.jpg" alt="Suur mägi" id="pic3">
  

</body>
</html> 