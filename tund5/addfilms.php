<?php

$username = "Martin Kilgi";

require("../../../config.php");
require("fnc_film.php");

$inputerror = "";
$filmhtml = "";

//kas vajutati salvestusnuppu
if (isset($_POST["filmsubmit"])) {
  if(empty($_POST["titleinput"]) or empty($_POST["genreinput"]) or empty($_POST["studioinput"]) or empty($_POST["directorinput"])) {
    $inputerror .= "Osa infot on sisestamata!";
  }
  if($_POST["yearinput"] < 1895 or $_POST["yearinput"] > date("Y")) {
    $inputerror .= "Ebareaalne valmimisaasta!";
  }
  if(empty($inputerror)) {
    $storeinfo = storefilminfo($_POST["titleinput"], $_POST["yearinput"], $_POST["durationinput"], $_POST["genreinput"], $_POST["studioinput"], $_POST["directorinput"]);
      if($storeinfo == 1) {
        $filmhtml = readfilms(1);
      } else {
        $filmhtml = "<p>Kahjuks filmiinfo salvestamine seekord ebaõnnestus</p>";
      }
  }
}

//loen andmebaasist filmide info
$filmhtml = readfilms();

require("header.php");

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> progeb veebi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<?php require("../nav.php");?>
<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo $username; ?></h1>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
<h1>Siin kuvatakse filmide andmed!</h1>
<form method="POST">
  <label for="titleinput">Filmi pealkiri</label>
  <input type="text" name="titleinput" id="titleinput" placeholder="Filmi pealkiri">
  <br>
  <label for="yearinput">Filmi valmimisaasta</label>
  <input type="number" name="yearinput" id="yearinput" value="<?php echo date("Y"); ?>">
  <br>
  <label for="durationinput">Filmi kestvus minutites</label>
  <input type="number" name="durationinput" id="durationinput" value="90">
  <br>
  <label for="genreinput">Filmi žanr</label>
  <input type="text" name="genreinput" id="genreinput" placeholder="Filmi žanr">
  <br>
  <label for="studioinput">Filmi tootja</label>
  <input type="text" name="studioinput" id="studioinput" placeholder="Filmi tootja/stuudio">
  <br>
  <label for="directorinput">Filmi lavastaja</label>
  <input type="text" name="directorinput" id="directorinput" placeholder="Filmi lavastaja">
  <br>
  <input type="submit" name="filmsubmit" value="Salvesta filmi info">

</form>
<p><?php echo $inputerror; ?></p>
<hr>
<?php echo $filmhtml; ?>
<hr>
<ul>
<li><a href="home.php">Siit saab tagasi avalehele</a></li>
<li><a href="motetesisestamine.php">Siit saate minna uusi mõtted kirja panema</a></li>
</ul>
</body>
</html>