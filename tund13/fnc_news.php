<?php

    $database = "if20_martin_kl_2";

    function storeNewsData($title, $content){
		$result = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $title, $content);
		if($stmt->execute()){
			$result = 1;
		} else {
			$result = 0;
		}
		$stmt->close();
		$conn->close();
		return $result;
    }
    
    function readNewsData(){
		$newshtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content FROM vpnews WHERE vpnews_id=(SELECT MAX(vpnews_id) FROM vpnews WHERE deleted IS NULL)");
		echo $conn->error;
		$stmt->bind_result($titlefromdb, $contentfromdb);
        $stmt->execute();
        if($stmt->fetch()) {
            $newshtml = "Uudise pealkiri: " .$titlefromdb ."\n";
            $newshtml .= " ". $contentfromdb. ". \n";
            $newshtml = htmlspecialchars_decode($newshtml);
        }
		$stmt->close();
		$conn->close();
		return $newshtml;
	}

	function addPhotoData($filename, $alttext){
		$notice = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES (?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
		if($stmt->execute()){
			$notice = 1;
		} else {
			$notice = 0;
		}
		$stmt->close();
		$conn->close();
		return $notice;

	}

	function saveNews($newsTitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
		if($stmt->execute()){
			$photoid = $conn->insert_id;
		}
		$stmt->close();
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $newsTitle, $news, $photoid, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}

	function readAllNewsData(){
		$newshtml = null;
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("SELECT title, content FROM vpnews WHERE deleted IS NULL");
		echo $conn->error;
		$stmt->bind_result($titlefromdb, $contentfromdb);
        $stmt->execute();
        while($stmt->fetch()) {
            $newshtml = "Uudise pealkiri: " .$titlefromdb ."\n";
            $newshtml .= " ". $contentfromdb. ". \n";
            $newshtml = htmlspecialchars_decode($newshtml);
        }
		$stmt->close();
		$conn->close();
		return $newshtml;
	}

	function latestNews($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		//SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid GROUP BY vpnews.vpnews_id
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE vpnews.expire >= ? AND vpnews.deleted IS NULL GROUP BY vpnews.vpnews_id ORDER By vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb, $filenamefromdb, $alttextfromdb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= '<div class="newsblock';
			if(!empty($filenamefromdb)){
				$newshtml .=" fullheightnews";
			}
			$newshtml .= '">' ."\n";
			if(!empty($filenamefromdb)){
				$newshtml .= "\t" .'<img src="' ."../photoupload_news/" .$filenamefromdb .'" ';
				if(!empty($alttextfromdb)){
					$newshtml .= 'alt="' .$alttextfromdb .'"';
				} else {
					$newshtml .= 'alt="' .$titlefromdb .'"';
				}
				$newshtml .= "> \n";
			}
			
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}

	function latestNewsToChange($limit){
		$newshtml = null;
		$today = date("Y-m-d");
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY id DESC LIMIT ?");
		//SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid GROUP BY vpnews.vpnews_id
		//$stmt = $conn->prepare("SELECT title, content, added FROM vpnews WHERE expire >=? AND deleted IS NULL ORDER BY vpnews_id DESC LIMIT ?");
		$stmt = $conn->prepare("SELECT title, content, vpnews.added, filename, alttext FROM vpnews LEFT JOIN vpnewsphotos on vpnewsphotos.vpnewsphotos_id = vpnews.photoid WHERE vpnews.expire >= ? AND vpnews.deleted IS NULL GROUP BY vpnews.vpnews_id ORDER By vpnews_id DESC LIMIT ?");
		echo $conn->error;
		$stmt->bind_param("si", $today, $limit);
		//$stmt->bind_param("i", $limit);
		$stmt->bind_result($titlefromdb, $contentfromdb, $addedFromDb, $filenamefromdb, $alttextfromdb);
		$stmt->execute();
		while ($stmt->fetch()){
			$newshtml .= '<div class="newsblock';
			if(!empty($filenamefromdb)){
				$newshtml .=" fullheightnews";
			}
			$newshtml .= '">' ."\n";
			if(!empty($filenamefromdb)){
				$newshtml .= "\t" .'<img src="' ."../photoupload_news/" .$filenamefromdb .'" ';
				if(!empty($alttextfromdb)){
					$newshtml .= 'alt="' .$alttextfromdb .'"';
				} else {
					$newshtml .= 'alt="' .$titlefromdb .'"';
				}
				$newshtml .= "> \n";
			}
			
			$newshtml .= "\t <h3>" .$titlefromdb ."</h3> \n";
			$addedtime = new DateTime($addedFromDb);
			$newshtml .= "\t <p>(Lisatud: " .$addedtime->format("d.m.Y H:i:s") .")</p> \n";
			
			$newshtml .= "\t <div>" .htmlspecialchars_decode($contentfromdb) ."</div> \n";
			$newshtml .= "</div> \n";
			$newshtml .= '<li><a href="changenews.php">Uudist toimetama</a></li>';
			$newshtml .= "<br> \n <hr> \n";
		}
		if($newshtml == null){
			$newshtml = "<p>Kahjuks uudiseid pole!</p>";
		}
		$stmt->close();
		$conn->close();
		return $newshtml;
	}

	function updateNews($newsTitle, $news, $expiredate, $filename, $alttext){
		$response = 0;
		$photoid = null;
		//kõigepealt foto!
		//echo "SALVESTATAKSE UUDIST!";
		$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
		$stmt = $conn->prepare("INSERT INTO vpnewsphotos (userid, filename, alttext) VALUES(?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("iss", $_SESSION["userid"], $filename, $alttext);
		if($stmt->execute()){
			$photoid = $conn->insert_id;
		}
		$stmt->close();
		
		//nüüd uudis ise
		$stmt = $conn->prepare("INSERT INTO vpnews (userid, title, content, photoid, expire) VALUES (?, ?, ?, ?, ?)");
		echo $conn->error;
		$stmt->bind_param("issis", $_SESSION["userid"], $newsTitle, $news, $photoid, $expiredate);
		if($stmt->execute()){
			$response = 1;
		} else {
			$response = 0;
		}
		$stmt->close();
		$conn->close();
		return $response;
	}