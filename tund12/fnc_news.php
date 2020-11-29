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