<?php

//$username = "Martin Kilgi";

require("usesession.php");
require("fnc_common.php");
//var_dump($_POST);
require("../../../config.php");
$database = "if20_martin_kl_2";

$notice = "";
$userdescription = "";  //edasipidi püüate andmebaasist lugeda, kui on olemas ,kasutate seda väärtust(userid järgi, kas on userprofile)


if(isset($_POST["profilesubmit"])) {
  $description = test_input($_POST["descriptioninput"]);
  $result = storeuserprofile($description, $_POST["bgcolorinput"], $_POST["txtcolorinput"]); //sealt peaks tulemas, kas OK või mingi error
  if ($result == "ok") {
    $notice = "Kasutajaprofiil on salvestatud!";
    $_SESSION["userbgcolor"] = $_POST["bgcolorinput"];
    $_SESSION["usertxtcolor"] = $_POST["txtcolorinput"];
  } else {
      $notice = "Profiili salvestamine ebaõnnestus!";
    }
  }



require("header.php");

?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> Mõtete sisestamine</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Tere " .$_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <?php require("../nav.php");?>
  <p id="esimene">See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p id="teine">Leht on loodud veebiprogrammeerimise raames <a href="https://www.tlu.ee" target="_blank">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <p id="kolmas">Siit saad minna otse <a href="https://www.tlu.ee/infotehnoloogiaosakond" target="_blank">TLÜ infotehnoloogiaosakonda.</a></p>
<hr>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label for="descriptioninput">Minu lühitutvtustus</label>
    <br>
    <textarea name="descriptioninput" id="descriptioninput" rows="10" cols="80" placeholder="Minu tutvustus..."><?php echo $userdescription;?></textarea>
    <br>
    <label for="bgcolorinput">Minu valitud taustavärv: </label>
    <input type="color" name="bgcolorinput" id="bgcolorinput" value="<?php echo $_SESSION["userbgcolor"]; ?>">
    <br>
    <label for="txtcolorinput">Minu valitud taustavärv: </label>
    <input type="color" name="txtcolorinput" id="txtcolorinput" value="<?php echo $_SESSION["usertxtcolor"]; ?>">
    <br>
    <input type="submit" name="profilesubmit" value="Salvesta profiil!">
    <span><?php echo $notice; ?></span>


  </form>
  <hr>
  <p><a href="?logout=1">Logi välja!</a></p>
</body>
</html>