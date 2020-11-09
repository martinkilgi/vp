<?php
  require("usesession.php");
  require("../../../config.php");
  require("fnc_photo.php");
  require("fnc_common.php");
  require("classes/Photoupload_class.php");
    
  $error = "";
  $notice = "";
  $fileuploadsizelimit = 2097152;//1048576;
  $fileuploaddir_orig = "../photoupload_orig/";
  $fileuploaddir_normal = "../photoupload_normal/";
  $fileuploaddir_thumb = "../photoupload_thumb/";
  $filename = "";
  $filenameprefix = "vp_";
  $photomaxw = 600;
  $photomaxh = 400;
  $thumbsize = 100;
  $privacy = 1;
  $alttext = null;
  $watermark = "../img/vp_logo_w100_overlay.png";
  $photofiletypes = ["image/jpeg", "image/png", "image/gif"];
  
  //kas vajutati salvestusnuppu
  if(isset($_POST["photosubmit"])){
	//var_dump($_POST);
	//var_dump($_FILES);
	$privacy = intval($_POST["privinput"]);
	$alttext = test_input($_POST["altinput"]);
	if(isset($FILES_["photoinput"]["tmp_name"])) {
	
		//genereerime failinime
		$myphoto->generateFileName($filenameprefix, $filetype);
		
		//kas fail on olemas
		//if(file_exists($fileuploaddir_orig .$filename)){                           //pole hetkel vaja kasutada
			//$error .= " Sellise nimega fail on juba olemas!";
		//}
		
		if(empty($error)) {
			// Võtame kasutusele Photoupload klassi
			$myphoto = new Photoupload($_FILES["photoinput"], $filetype);
			
			//kas on üldse pilt
			$myphoto->isItImage($photofiletypes);

			//kui vigu pole, salvestame originaalpildi
			$myphoto->moveOriginalimage($fileuploaddir_orig .$filename);
				if($notice == 1) {
					$notice .= "Originaalpildi salvestamine õnnestus!";
				} else {
					$error .= "Originaalpildi salevastimsel tekkis tõrge!";
				}
				
			//muudame suurust
			//$mynewimage = resizePhoto($mytempimage, $photomaxw, $photomaxh, true);

			$myphoto->resizePhoto($photomaxw, $photomaxh, true);
			$myphoto->addWatermark($watermark);

			//ega pole liiga suur fail
			$myphoto->testFileSize($photofiletypes);

			//salvestame vähendatud pildi faili
			//$result = savePhotoFile($mynewimage, $filetype, $fileuploaddir_normal .$filename);
			$result = $myphoto->savePhotoFile($fileuploaddir_normal .$filename);
				if($result == 1){
					$notice .= "Vähendatud pildi salvestamine õnnestus!";
				} else {
					$error .= "Vähendatud pildi salvestamisel tekkis tõrge!";
				}
				
				
				//pisipildi tegemine ja salvestamine
			//$mynewimage = resizePhoto($mytempimage, $thumbsize, $thumbsize);
			$myphoto->resizePhoto($thumbsize, $thumbsize);
			//$result = savePhotoFile($mynewimage, $filetype, $fileuploaddir_thumb .$filename);
			$result = $myphoto->savePhotoFile($fileuploaddir_thumb .$filename);
				if($result == 1){
					$notice .= "Pisipildi salvestamine õnnestus!";
				} else {
					$error .= "Pisipildi salvestamisel tekkis tõrge!";
				}
				
			//kui vigu pole, salvestame info andmebaasi
			if(empty($error)){
				$result = storePhotoData($filename, $alttext, $privacy);
				if($result == 1){
					$notice .= " Pildi info lisati andmebaasi!";
					$privacy = 1;
					$alttext = null;
				} else {
					$error .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
				}
			} else {
				$error .= " Tekkinud vigade tõttu pildi andmeid ei salvestatud!";
			}
			unset($myphoto);
		
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
    <label for="photoinput">Vali pildifail!</label>
	<input id="photoinput" name="photoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text" placeholder="Pildi lühikirjeldus ..." value="<?php echo $alttext; ?>">
	<br>
	<label>Määra privaatsustase</label>
	<br>
	<input id="privinput1" name="privinput" type="radio" value="1" <?php if($privacy == 1){echo " checked";} ?>>
	<label for="privinput1">Privaatne (ise näed)</label>
	<input id="privinput2" name="privinput" type="radio" value="2" <?php if($privacy == 2){echo " checked";} ?>>
	<label for="privinput2">Sisseloginud kasutajatele</label>
	<input id="privinput3" name="privinput" type="radio" value="3" <?php if($privacy == 3){echo " checked";} ?>>
	<label for="privinput3">Avalik</label>
	
	<br>
	<input type="submit" name="photosubmit" value="Lae pilt üles">
  </form>
  <p>
  <?php
	echo $error;
	echo $notice;
  ?>
	</p>
  
  <hr>  
</body>
</html>