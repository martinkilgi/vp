
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

//vaatame semestri kulgemist
$semesterstart = new DateTime("2020-8-31");
$semesterend = new DateTime("2020-12-13");
// selgitame välja nende vahe ehk erinevuse 
$semesterduration = $semesterstart->diff($semesterend);
//leiame selle päevade arvuna
$semesterdurationdays = $semesterduration->format("%r%a");

//tänane päev 
$today = new DateTime("now");
$semestrilopuni = $today->diff($semesterend);
$semestrilopuni = $semestrilopuni->format("%r%a");

$paevikokku = 105;
$paevilainud = $semesterstart->diff($today);
$paevilainud = $paevilainud->format("%a");
$paevadprotsentides = $paevilainud / $paevikokku * 100;
$paevadprotsentides = round($paevadprotsentides, 2);


if ($paevilainud <= $paevikokku) {
  $semesterstatus = "Hetkel veel semester käib.";
} elseif ($semestrilopuni < 0) {
  $semesterstatus = "Semester pole veel hakanud";
}
else {
  $semesterstatus = "Semester on läbi.";
}

if ($paevadprotsentides <= 0) {
  $paevadprotsentides = "Semester pole veel hakanud";
} elseif ($paevadprotsentides >= 100) {
  $paevadprotsentides = "Semester on läbi";
}

//if($fromsemesterstartdays < 0) {semester pole peale hakanud)}
//mitu protsenti õppetööst on tehtud (päevi kokku 105)

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
  <p id="semester"><?php echo "Parajasti on " .$partofday .".";  ?></p>
  <p id="semester"><?php echo "Semestri lõpuni on veel " .$semestrilopuni ." päeva."; ?></p>
  <p id="semester"><?php echo "Semestrist on läbitud " .$paevadprotsentides ." protsenti ehk " .$paevilainud ." päeva."; ?></p>
  <p id="semester"><?php echo $semesterstatus; ?></P>
  <img src="pics/Mountain.jpg" alt="Mäed" id="pic1">
  <img src="pics/mäed.jfif" alt="Veel suuremad mäed" id="pic2">
  <img src="pics/Magi.jpg" alt="Suur mägi" id="pic3">
  

</body>
</html> 