<?php

session_start();
//kas on sisselogitud, kui pole, saadame sisselogimise lehele

if (isset($_GET["logout"])) {
  session_destroy();
  header("Location: page.php");
  exit();
}

if (!isset($_SESSION["userid"])) {
  header("Location: page.php");
  exit();
}

?>