<?php
  require("usesession.php");
  require("../../config.php");
  require("fnc_news.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");	

	$tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
	$tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";
  
  $inputerror = "";
  $notice = "";

  $uudised = latestNewsToChange(5);

  require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>Leht on loodud veebiprogrammeerimise kursuse raames <a href="http://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
   <li><a href="home.php">Avalehele</a></li>
   <li><a href="?logout=1">Logi välja</a>!</li>
  </ul>
  <hr>
  <br>
  <p id="notice">
  <?php
	echo $uudised;
	echo $inputerror;
	echo $notice;
  ?>
	</p>
  
  <hr>  
</body>
</html>