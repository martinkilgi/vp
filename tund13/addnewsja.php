<?php
  require("usesession.php");
  require("../../config.php");
  require("fnc_news.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");	

	$tolink = "\t" .'<script src="//cdn.tinymce.com/4/tinymce.min.js"></script>' ."\n";
	$tolink .= "\t" .'<script>tinymce.init({selector:"textarea#newsinput", plugins: "link", menubar: "edit",});</script>' ."\n";
  
  $inputerror = "";
  $photoinputerror = "";
  $notice = "";
  $news = "";
  $newstitle = "";
  $expiredate = null;
  $expire = new DateTime("now");
  $expire->add(new DateInterval("P7D"));
  $expireday = date_format($expire, "d");
  $expiremonth = date_format($expire, "m");
  $expireyear = date_format($expire, "Y");
  $monthnameset = ["jaanuar", "veebruar", "märts", "aprill", "mai", "juuni", "juuli", "august", "september", "oktoober", "november", "detsember"];
  $photomaxw = 600;
  $photomaxh = 400;
  $fileuploaddir_news = "../photoupload_news/";
  $fileuploadsizelimit = 2097152;//1048576
  $allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
  $filename = "";
  $filetype = "";
  $watermark = "../img/vp_logo_w100_overlay.png";
  $filenameprefix = "vpnews_";
  $alttext = null;

  //$savenews = storeNewsData($newstitle, $news);
  
  //kas vajutati salvestusnuppu
  if(isset($_POST["newssubmit"])){
	$alttext = test_input($_POST["altinput"]);

	if(strlen($_POST["newstitleinput"]) == 0) {
		$inputerror = "Uudise pealkiri on puudu!";
	} else {
		$newstitle = test_input($_POST["newstitleinput"]);
	}
	if(strlen($_POST["newsinput"]) == 0) {
		$inputerror .= " Uudise sisu on puudu!";
	} else {
		$news = test_input($_POST["newsinput"]);
		//htmlspecialchars teisendab html noolsulud.
		//nende tagasisaamiseks on htmlspecialchars_decode(uudis)   siis kui loed uudise andmebaasist ja lisad lehele
		//uudiste puhul originaalpilti ei salvesta, tee 600x400
	}

	if(!empty($_POST["expiredayinput"])){
		$expireday = intval($_POST["expiredayinput"]);
	  } else {
		$inputerror .= " Palun vali Aegumistähtaja päev!";
	  }
		
	  if(!empty($_POST["expiremonthinput"])){
		$expiremonth = intval($_POST["expiremonthinput"]);
	  } else {
		$inputerror .= " Palun vali Aegumistähtaja kuu!";
	  }
  
	  if(!empty($_POST["expireyearinput"])){
		$expireyear = intval($_POST["expireyearinput"]);
	  } else {
		$inputerror .= " Palun vali Aegumistähtaja aasta!";
	  }
	
	  //kontrollime kuupäeva kehtivust (valiidsust)
	  if(!empty($expireday) and !empty($expiremonth) and !empty($expireyear)){
		if(checkdate($expiremonth, $expireday, $expireyear)){
			$tempdate = new DateTime($expireyear ."-" .$expiremonth ."-" .$expireday);
			$expiredate = $tempdate->format("Y-m-d");
		} else {
			$inputerror .= " Kuupäev ei ole reaalne!";
		}
	  }


	//kas ka foto
	$alttext = test_input($_POST["altinput"]);
	if(!empty($_FILES["newsphotoinput"]["name"])){
		//echo $_FILES["photoinput"]["name"];
		$check = getimagesize($_FILES["newsphotoinput"]["tmp_name"]);
		if($check !== false){
			//var_dump($check);
			if($check["mime"] == "image/jpeg"){
				$filetype = "jpg";
			}
			if($check["mime"] == "image/png"){
				$filetype = "png";
			}
			if($check["mime"] == "image/gif"){
				$filetype = "gif";
			}
		} else {
			$photoinputerror = "Valitud fail ei ole pilt! ";
		}

		$myphoto = new Photoupload($_FILES["newsphotoinput"], $allowed_photo_types, $fileuploadsizelimit);
	
		//ega pole liiga suur fail
		$inputerror .= $myphoto->checkSize();
		//genereerime failinime
		$myphoto->generateFileName($filenameprefix);
		
		$myphoto->createImageFromFile();

		$myphoto->resizePhoto($photomaxw, $photomaxh, true);

		$myphoto->addWatermark($watermark);

		$result = $myphoto->savePhotoFile($fileuploaddir_news .$filename);
		if($result == 1){
			$notice .= " Vähendatud pildi salvestamine õnnestus!";
		} else {
			$photoinputerror .= " Vähendatud pildi salvestamisel tekkis tõrge!";
		}
		
		//eemaldan klassi
		unset($myphoto);
	}



	if(empty($inputerror) and empty($photoinputerror)) {
		//uudis salvestada
		$result = saveNews($newstitle, $news, $expiredate, $filename, $alttext);
		if($result == 1) {
			$notice .= "Uudise salvestamine õnnestus!";
			$error = "";
			$newstitle = "";
			$news = "";
			$expiredate = null;
		}
	}
}

  
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
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
    <label for="newstitleinput">Sisesta uudise pealkiri!</label>
		<input id="newstitleinput" name="newstitleinput" type="text" value="<?php echo $newstitle; ?>" required>
		<br>
		<label for="newsinput">Kirjuta uudis!</label>
		<textarea id="newsinput" name="newsinput" placeholder="Uudise sisu..."><?php echo $news; ?></textarea>
		<br>
		<label for="expiredayinput">Aegumispäev: </label>
		<?php
			echo '<select name="expiredayinput" id="expiredayinput">' ."\n";
			for ($i = 1; $i < 32; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $expireday){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "\t </select> \n";
		?>
		<label for="expiremonthinput">Aegumiskuu: </label>
		<?php
			echo "\t" .'<select name="expiremonthinput" id="expiremonthinput">' ."\n";
			for ($i = 1; $i < 13; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $expiremonth){
					echo " selected ";
				}
				echo ">" .$monthnameset[$i - 1] ."</option> \n";
			}
			echo "\t </select> \n";
		?>
		<label for="expireyearinput">Sünniaasta: </label>
		<?php
			echo "\t" .'<select name="expireyearinput" id="expireyearinput">' ."\n";
			for ($i = date("Y"); $i <= date("Y") + 10; $i ++){
				echo "\t \t" .'<option value="' .$i .'"';
				if ($i == $expireyear){
					echo " selected ";
				}
				echo ">" .$i ."</option> \n";
			}
			echo "\t </select> \n";
		?>
		<br>
		<hr>
		<br>
		<label for="newsphotoinput">Sisesta uudisele ka pilt!</label>
		<input id="newsphotoinput" name="newsphotoinput" type="file" required>
		<br>
		<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
		<input id="altinput" name="altinput" type="text" placeholder="Pildi lühikirjeldus ..." value="<?php echo $alttext; ?>">
		<br>
		<input type="submit" id="newssubmit" name="newssubmit" value="Salvesta uudis">
  </form>
  <p><span id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
  </span>
  <span id="photonotice"></span>
  </p>
  
</body>
</html>