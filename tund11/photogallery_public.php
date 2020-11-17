<?php
  require("usesession.php");
  require("../../config.php");
  require("fnc_photo.php");	
  
  $tolink = '<link rel="stylesheet" type="text/css" href="style/gallery.css">' ."\n";
  $tolink .= '<link rel="stylesheet" type="text/css" href="style/modal.css">' ."\n";
  $tolink .= '<script src="javascript/modal.js" defer></script>' ."\n";


  $notice = "";
  $fileuploaddir_orig = "../photoupload_orig/";
  $fileuploaddir_normal = "../photoupload_normal/";
  $fileuploaddir_thumb = "../photoupload_thumb/";

  $gallerypagelimit = 3;
  $page = 1;
  $photocount = countPublicPhotos(2);

  if(!isset($_GET["page"]) or $_GET["page"] < 1) {
	$page = 1;
  } elseif ((intval($_GET["page"]) - 1) * $gallerypagelimit >= $photocount) {
	$page = ceil($photocount / $gallerypagelimit);
  } else {
	  $page = intval($_GET["page"]);
  }

  //$publicphotothumbshtml = readAllPublicPhotoThumbs(2);
  $publicphotothumbshtml = readAllPublicPhotoThumbsPage(2, $gallerypagelimit, $page);
  

  require("header.php");
?>
  <h1><?php echo $_SESSION["userfirstname"] ." " .$_SESSION["userlastname"]; ?></h1>
  <p>See veebileht on loodud õppetöö käigus ning ei sisalda mingit tõsiseltvõetavat sisu!</p>
  <p>Leht on loodud veebiprogrammeerimise kursuse raames <a href="http://www.tlu.ee">Tallinna Ülikooli</a> Digitehnoloogiate instituudis.</p>
  <ul>
   <li><a href="home.php">Avalehele</a></li>
   <li><a href="?logout=1">Logi välja</a>!</li>
  </ul>

  <!--Modaalaken fotogalerii jaoks-->
  <div id="modalarea" class="modalarea">
    <!--Sulgemisnupp-->
    <span id="modalclose" class="modalclose">&times;</span>
    <!--Pildikoht-->
    <div class="modalhorizontal">
      <div class="modalvertical">
        <p id="modalcaption"></p>
        <img id="modalimg" src="../img/empty.png" alt="galeriipilt">
        <br>
        <div id="rating" class="modalRating">
          <label><input id="rate1" name="rating" type="radio" value="1">1</label>
          <label><input id="rate2" name="rating" type="radio" value="2">2</label>
          <label><input id="rate3" name="rating" type="radio" value="3">3</label>
          <label><input id="rate4" name="rating" type="radio" value="4">4</label>
          <label><input id="rate5" name="rating" type="radio" value="5">5</label>
          <button id="storeRating">Salvesta hinnang!</button>
          <br>
          <p id="avgRating"></p>
        </div>
      </div>
    </div>
  </div>

  <hr>
  <h2>Fotogalerii</h2>
  <p>
	  <?php
	  	if($page > 1) {
			echo '<span><a href="?page=' .($page - 1) .'">Eelmine leht</a></span> | ' ."\n";
		} else {
			echo '<span>Eelmine leht</span> | ' ."\n";
		}

		if($page * $gallerypagelimit < $photocount) {
			echo '<span><a href="?page=' .($page + 1) .'">Järgmine leht</a></span> | ' ."\n";
		} else {
			echo '<span>Järgmine leht</span> | ' ."\n";
		}
	  ?>
  </p>
  <?php
	echo $publicphotothumbshtml;
	?>

  <hr>  
</body>
</html>