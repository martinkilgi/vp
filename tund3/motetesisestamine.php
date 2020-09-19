<?php

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


?>

<!DOCTYPE html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <title><?php echo $username; ?> progeb veebi</title>
  <link rel="stylesheet" href="style.css">
</head>
<body>
<h1>Siin saad kirja panna enda mõtted!</h1>
<hr>
  <form method="POST">
    <label>Kirjutage oma esimene pähe tulev mõte!</label>
    <input type="text" name="ideainput" placeholder="mõttekoht">
    <input type="submit" name="ideasubmit" value="Saada mõte teele!">
  </form>
  <hr>
  <a href="home.php">Siit pääsed tagasi avalehele</a><br />
  <a href="motetelist.php">Siit saad oma kirja pandud mõtteid vaatama minna</a>
</body>
</html>