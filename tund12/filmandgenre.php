
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
$genrenotice = "";
$studionotice = "";
$personnotice = "";
$selectedmovie = "";
$selectedgenre = "";
$selectedstudio = "";
$selectedperson = "";
$movieselecthtml = "";
$moviegenreselecthtml = "";

if(isset($_POST["filmstudiorelationsubmit"])) {
  if(!empty($_POST["movieinput"])){
		$selectedmovie = intval($_POST["movieinput"]);
	} else {
      $studionotice = " Vali film!";
  }

  if(!empty($_POST["filmstudioinput"])){
		$selectedstudio = intval($_POST["filmstudioinput"]);
	} else {
    $studionotice = " Vali stuudio!";
  }

  if (!empty($selectedmovie) and !empty($selectedstudio)) {
    $studionotice = storenewstudiorelation($selectedmovie, $selectedstudio);
  }
}

if(isset($_POST["filmgenrerelationsubmit"])){
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

  if(isset($_POST["personrelationsubmit"])) {
    if(!empty($_POST["movieinput"])){
      $selectedmovie = intval($_POST["movieinput"]);
    } else {
        $persononotice = " Vali film!";
    }
  
    if(!empty($_POST["filmstudioinput"])){
      $selectedperson = intval($_POST["filmstudioinput"]);     ///POOOOLEELII
    } else {
      $personnotice = " Vali stuudio!";
    }
  
    if (!empty($selectedmovie) and !empty($selectedstudio)) {
      $studionotice = storenewstudiorelation($selectedmovie, $selectedstudio);
    }
  }

$movieselecthtml = readmovie($selectedmovie);
$moviegenreselecthtml = readgenre($selectedgenre);
$moviestudioselecthtml = readstudiotoselect($selectedstudio);
$personselecthtml = readperson($selectedperson);


require("header.php");

?>


  <img src="../img/vp_banner.png" alt="Veebiprogrammeerimise kursuse logo">
  <h1><?php echo "Siin saad luua filmide seoseid!"; ?></h1>
  <?php require("../nav.php");?>
  <hr>
  <p><?php echo $connerror;?>
  <h2>Määrame filmistuudio</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
      echo $movieselecthtml;
      echo $moviestudioselecthtml;
    ?>
  <input type="submit" name="filmstudiorelationsubmit" value="Salvesta"><span><?php echo $studionotice; ?></span>
  </form>
  <hr>
  <h2>Määrame filmile žanri</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		  <?php
        echo $movieselecthtml;
		  ?>
      <?php 
        echo $moviegenreselecthtml;
      ?>
      <input type="submit" name="filmgenrerelationsubmit" value="Salvesta"><span><?php echo $notice; ?></span>
  </form>
  <h2>Määrame näitleja</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <?php
      echo $movieselecthtml;
      echo $personselecthtml;
    ?>
  <input type="submit" name="personrelationsubmit" value="Salvesta"><span><?php echo $personnotice; ?></span>
  <ul>
  </ul>
  <hr>
  <p><a href="?logout=1">Logi välja!</a></p>
  

</body>
</html>