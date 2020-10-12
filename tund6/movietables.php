
<?php

session_start();

if(!isset($_SESSION["userid"])){
  //jõugu sisselogimise lehele
  header("Location: page.php");
}

if(isset($_GET["logout"])){
  session_destroy();
   header("Location: page.php");
   exit();
}

require("../../../config.php");
require("fnc_common.php");
require("fnc_user.php");
require("fnc_addfilmandgenre.php");

$film = null;
$connerror = "";
$notice = "";
$selectedmovie = "";
$selectedgenre = "";
$movieselecthtml = "";
$moviegenreselecthtml = "";

if(isset($_POST["movierelationsubmit"])){
	if(!empty($_POST["movieinput"])){
		$selectedmovie = intval($_POST["movieinput"]);
	} else {
		$notice = " Vali film!";
	}
	if(!empty($_POST["moviegenreinput"])){
		$selectedgenre = intval($_POST["moviegenreinput"]);
	} else {
		$notice .= " Vali žanr!";
	}
	if(!empty($selectedmovie) and !empty($selectedgenre)){
		$notice = storenewgenrerelation($selectedmovie, $selectedgenre);
	}
  }

$movieselecthtml = readmovie($selectedmovie);
$moviegenreselecthtml = readgenre($selectedgenre);

$username = "Koduleht";
$fulltimenow = date("d.m.Y H:i:s");
$hournow = date("H");
$kellpraegu = date("H:i:s");
$partofday = "lihtsalt aeg";

$weekdaynameset = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
$monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
//echo $weekdaynameset[1];
$weekdaynow = date("N");
$monthnow = date("m");
$daynow = date("d");


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

require("header.php");



?>


  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Koduleht"; ?></h1>
  <hr>
  <p><?php echo $connerror;?>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
  <label for="movieinput">Film: </label>
		  <?php
        echo $movieselecthtml;
		  ?>
  <label for="moviegenreinput">Žanr: </label>
      <?php 
        echo $moviegenreselecthtml;
      ?>
      <input type="submit" name="movierelationsubmit" value="Salvesta"><span><?php echo $notice; ?></span>
  </form>
  <hr>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
  <p> Lehe avamise hetkel oli: <?php echo $weekdaynameset[$weekdaynow - 1] .", ". $daynow .", ".  $monthnameset[$monthnow - 1]. ", kell " . $kellpraegu; ?>.</p>
  <p id="semester"><?php echo "Parajasti on " .$partofday .".";  ?></p>
  <p id="semester"><?php echo "Semestri lõpuni on veel " .$semestrilopuni ." päeva."; ?></p>
  <p id="semester"><?php echo "Semestrist on läbitud " .$paevadprotsentides ." protsenti ehk " .$paevilainud ." päeva."; ?></p>
  <p id="semester"><?php echo $semesterstatus; ?></P>
  <ul>
  </ul>
  <hr>
  

</body>
</html>