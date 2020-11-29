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
  $news = "";
  $newstitle = "";
  $expire = "";
  $photomaxw = 600;
  $photomaxh = 400;
  $fileuploaddir_news = "../photoupload_news/";
  $fileuploadsizelimit = 2097152;//1048576
  $allowed_photo_types = ["image/jpeg", "image/png", "image/gif"];
  $filename = "";
  $filenameprefix = "vp_";
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

	$myphoto = new Photoupload($_FILES["newsphotoinput"], $allowed_photo_types, $fileuploadsizelimit);

	//kas on üldse pilt
	$inputerror .= $myphoto->setImageType();
	
	//ega pole liiga suur fail
	$inputerror .= $myphoto->checkSize();
	
	//genereerime failinime
	$myphoto->generateFileName($filenameprefix);
	
	//kas fail on olemas
	$inputerror .= $myphoto->exists($fileuploaddir_news);

	if(empty($inputerror)) {
		//uudis salvestada
		$result = storeNewsData($newstitle, $news);
		
		if($result == 1) {
			$notice .= "Uudise salvestamine õnnestus!";
		} else {
			$inputerror .= "Uudise salvestamine ebaõnnestus!";
		}

		$myphoto->createImageFromFile();
		$myphoto->resizePhoto($photomaxw, $photomaxh, true);

		$result = $myphoto->savePhotoFile($fileuploaddir_news); 

		if($result == 1){
			$notice .= "Uudisepildi salvestamine õnnestus!";
		} else {
			$inputerror .= "Uudisepildi salvestamisel tekkis tõrge!";
		}

		$result = $myphoto->addPhotoData($filename, $alttext);
			if($result == 1){
				$notice .= " Pildi info lisati andmebaasi!";
				$privacy = 1;
				$alttext = null;
			} else {
				$inputerror .= " Pildi info andmebaasi salvestamisel tekkis tõrge!";
			}
		} else {
			$inputerror .= " Tekkinud vigade tõttu pildi andmeid ei salvestatud!";
		}

	}

	//Pildi suuruse tegemiseks on olemas klass.
	////kas vajutati salvestusnuppu
//   if(isset($_POST["photosubmit"])){
// 	//var_dump($_POST);
// 	//var_dump($_FILES);
// 	$privacy = intval($_POST["privinput"]);
// 	$alttext = test_input($_POST["altinput"]);
	
// 	$myphoto = new Photoupload($_FILES["photoinput"], $allowed_photo_types, $fileuploadsizelimit);

// 	//kas on üldse pilt
// 	$inputerror .= $myphoto->setImageType();
	
// 	//ega pole liiga suur fail
// 	$inputerror .= $myphoto->checkSize();
	
// 	//genereerime failinime
// 	$myphoto->generateFileName($filenameprefix);
	
// 	//kas fail on olemas
// 	$inputerror .= $myphoto->exists($fileuploaddir_orig);
	
// 	if(empty($inputerror)){

// 		$myphoto->createImageFromFile();


// 		//teen väiksemaks
// 		//loome image objekti ehk pikslikogumi
// 		//muudame suurust
// 		//$mynewimage = resizePhoto($mytempimage, $photomaxw, $photomaxh, true);
// 		$myphoto->resizePhoto($photomaxw, $photomaxh, true);
// 		$myphoto->addWatermark($watermark);
// 		//salvestame vähendatud pildi faili
// 		$result = $myphoto->savePhotoFile($fileuploaddir_normal); 

// 		if($result == 1){
// 			$notice .= "Vähendatud pildi salvestamine õnnestus!";
// 		} else {
// 			$inputerror .= "Vähendatud pildi salvestamisel tekkis tõrge!";
// 		}
//    
//   SORTEERI JA VAATA, MIS ON VAJA ALLES JÄTTA JA TEE PILDI SALVESTAMISE FUNKTSIOON.
  

  
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
	<input type="submit" id="newssubmit" name="newssubmit" value="Salvesta uudis">
	<hr>
	<br>
	<label for="newsphotoinput">Sisesta uudisele ka pilt!</label>
	<input id="newsphotoinput" name="newsphotoinput" type="file" required>
	<br>
	<label for="altinput">Lisa pildi lühikirjeldus (alternatiivtekst)</label>
	<input id="altinput" name="altinput" type="text" placeholder="Pildi lühikirjeldus ..." value="<?php echo $alttext; ?>">
  </form>
  <br>
  <p id="notice">
  <?php
	echo $inputerror;
	echo $notice;
  ?>
	</p>
  
  <hr>  
</body>
</html>