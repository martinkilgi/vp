
<?php

session_start();

require("../../../config.php");
require("fnc_common.php");
require("fnc_user.php");

//errorite muutujad
$emailerror = "";
$passworderror = "";
$notice = "";

$email = "";

if(isset($_POST["submituserdata"])){

  if (!empty($_POST["emailinput"])){
		$email = test_input($_POST["emailinput"]);
	  } else {
		  $emailerror = "Palun sisesta e-postiaadress!";
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
      $email = test_input($_POST["emailinput"]);
    } else {
      $emailerror = "Sisestatud tekst ei ole e-mail!";
    }
	  
	  if (empty($_POST["passwordinput"])) {
		  $passworderror = "Palun sisesta salasõna!";
	  } else {
		  if(strlen($_POST["passwordinput"]) < 8){
			  $passworderror = "Liiga lühike salasõna (sisestasite ainult " .strlen($_POST["passwordinput"]) ." märki).";
		  }
    }
    
    if(empty($firstnameerror) and empty($lastnameerror)) {
      $result = signin($email, $_POST["passwordinput"]);

      if ($result == "ok") {
        $notice = "Kasutaja on edukalt loodud!";
        $firstname= "";
        $lastname = "";
        $gender = "";
        $email = "";
      } else {
        $notice = "Kahjuks tekkis tehniline viga: ". $result;
      }
    }

}


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

//if($fromsemesterstartdays < 0) {semester pole peale hakanud)}
//mitu protsenti õppetööst on tehtud (päevi kokku 105)


//Loeme kataloogist piltide nimekirja
$allfiles = scandir("../vp_pics/");
//var_dump($allfiles);
$picfiles = array_slice($allfiles, 2);
$imghtml = "";
$pilthtml = "";
$piccount = count($picfiles);
$random = mt_rand(0, ($piccount - 1));
$pilthtml .= '<img src="../vp_pics/' .$picfiles[$random]. '" alt="Tallinna ülikool">';
//$i = $i + 1;
//$i ++;
//$i += 3;

for($i = 0; $i < $piccount; $i ++) {
  //<img src="../img/pildifail" alt="tekst"
  $imghtml .= '<img src="../vp_pics/' .$picfiles[$i] .'" alt="Tallinna Ülikool">';
}
require("header.php");



?>


  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Koduleht"; ?></h1>
  <hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="emailinput">E-mail (kasutajatunnus):</label><br>
	  <input type="email" name="emailinput" id="emailinput" value="<?php echo $email; ?>"><span><?php echo $emailerror; ?></span>
	  <br>
	  <br>
	  <label for="passwordinput">Salasõna (min 8 tähemärki):</label>
	  <br>
	  <input name="passwordinput" id="passwordinput" type="password"><span><?php echo $passworderror; ?></span>
    <br>
    <input name="submituserdata" type="submit" value="Logi sisse"><span><?php echo "&nbsp; &nbsp; &nbsp;" .$notice; ?></span>
  </form>
  <hr>
  <li><a href="userreg.php">Kasutajat tegema</a></li>
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
  <?php echo $pilthtml; ?>
  <hr>
  

</body>
</html>