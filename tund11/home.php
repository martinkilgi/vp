
<?php
require("usesession.php");
require("classes/Generic_class.php");
require("fnc_photo.php");

//testime klassi kasutamist
//$myfirstclass = new Generic(8);
//echo $myfirstclass->mysecret;
//echo " Oluliselt avalikum saladus on: ", $myfirstclass->yoursecret;
//$myfirstclass->showValue();
//unset($myfirstclass);

//var_dump($_POST);
require("../../../config.php");
$database = "if20_martin_kl_2";
if(isset($_POST["ideasubmit"]) and !empty($_POST["ideainput"])) {
    //loome andmebaasiga ühenduse
    $conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
    //valmistan ette SQL käsu andmete kirjutamiseks
    $stmt = $conn->prepare("INSERT INTO myideas (idea) VALUES(?)");
    echo $conn->error;
    //i - integer, d - decimal ehk murdarv, s - string
    $stmt->bind_param("s", $_POST["ideainput"]);
    $stmt->execute();
    $stmt->close();
    $conn->close();
}

//loen andmebaasist senised mõtted
$ideahtml = "";
$conn = new mysqli($serverhost, $serverusername, $serverpassword, $database);
$stmt = $conn->prepare("SELECT idea FROM myideas");
//seon tulemuse muutujaga
$stmt->bind_result($ideafromdb);
$stmt->execute();
while ($stmt->fetch()) {
    $ideahtml .= "<p>" .$ideafromdb ."</p>";
}

$stmt->close();
$conn->close();

$uusimpilt = maxIdPhoto(2);

$username = "";
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

//Tegeleme küpsistega (cookies)
//set cookie peab olema enne html algust (html on header.phps)
//määrame: nimi, väärtus, aegumine, veebikataloog (vaikimisi "/"), domeen, kas https, http only ehk ainult üle veebi
setcookie("vpvisitor", $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"], time() + (86400 * 8), "/~martkil/", "greeny.cs.tlu.ee", isset($_SERVER["HTTPS"]), true);

//kustutamiseks antakse aegumistähtaeg minevikus, näiteks time() - 3600
require("header.php");



?>


  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Tere " .$_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <?php require("../nav.php");?>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
  <p> Lehe avamise hetkel oli: <?php echo $weekdaynameset[$weekdaynow - 1] .", ". $daynow .", ".  $monthnameset[$monthnow - 1]. ", kell " . $kellpraegu; ?>.</p>
  <p id="semester"><?php echo "Parajasti on " .$partofday .".";  ?></p>
  <p id="semester"><?php echo "Semestri lõpuni on veel " .$semestrilopuni ." päeva."; ?></p>
  <p id="semester"><?php echo "Semestrist on läbitud " .$paevadprotsentides ." protsenti ehk " .$paevilainud ." päeva."; ?></p>
  <p id="semester"><?php echo $semesterstatus; ?></P>
  
  <p><a href="?logout=1">Logi välja!</a></p>
  <ul>
  </ul>
  <hr>
  <?php
   echo $pilthtml;
   echo $uusimpilt;

  ?>
  <hr>
  <?php 
    if(count($_COOKIE) > 0) {
      echo "<p>Küpsised on lubatud! Leidsin: " .count($_COOKIE) ." küpsist.</p>";
      var_dump($_COOKIE);
    } else {
      echo "<p>Küpsised pole lubatud!</p>";
    }
    if(isset($_COOKIE["vpvisitor"])) {
      echo "<p>Küpsisest selgus viimase külastaja nimi: " .$_COOKIE["vpvisitor"] .". \n";
    } else {
      echo "<p>Viimase kasutaja nime ei leitud!</p> \n";
    }
  ?>
  

</body>
</html>