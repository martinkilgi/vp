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
<h1>Siin kuvatakse teie kirja pandud mõtted!</h1>
<hr>
  <?php echo $ideahtml; ?>
<hr>
<a href="home.php">Siit saab tagasi avalehele</a><br />
<a href="motetesisestamine.php">Siit saate minna uusi mõtted kirja panema</a><br />
</body>
</html>