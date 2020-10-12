<?php 

$database = "if20_martin_kl_2";

function readmovie($selected) {
    $notice = "<p>Kahjuks filme ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT movie_id, title FROM movie");
    $connerror = $conn->error;
    $stmt->bind_result($movieidfromdb, $movietitlefromdb);
    $stmt->execute();
    $movies = "";
    while ($stmt->fetch()) {
        $movies .= '<option value="' .$movieidfromdb .'"';
        if(intval($movieidfromdb) == $selected){
			$movies .=" selected";
        }
        $movies .= ">" .$movietitlefromdb ."</option> \n";
    }
    if (!empty($movies)) {
        $notice = '<select name="movieinput" id="movieinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali film</option>' ."\n";
		$notice .= $movies;
		$notice .= "</select> \n";
    }
    $stmt->close();
    $conn->close();
    return $notice;
}

function readgenre($selected) {
    $notice = "<p>Kahjuks žanre ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT genre_id, genre_name FROM genre");
    $connerror = $conn->error;
    $stmt->bind_result($genreidfromdb, $genrenamefromdb);
    $stmt->execute();
    $genres = "";
    while ($stmt->fetch()) {
        $genres .= '<option value="' .$genreidfromdb .'"';
        if(intval($genreidfromdb) == $selected){
			$genres .=" selected";
        }
        $genres .= ">" .$genrenamefromdb ."</option> \n";
    }
    if (!empty($genres)) {
        $notice = '<select name="moviegenreinput" id="moviegenreinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali žanr</option>' ."\n";
		$notice .= $genres;
		$notice .= "</select> \n";
    }
    $stmt->close();
    $conn->close();
    return $notice;
}

function storenewgenrerelation($selectedmovie, $selectedgenre){
	$notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_genre_id FROM movie_genre WHERE movie_id = ? AND genre_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $selectedmovie, $selectedgenre);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = "Selline seos on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO movie_genre (movie_id, genre_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedmovie, $selectedgenre);
		if($stmt->execute()){
			$notice = "Uus seos edukalt salvestatud!";
		} else {
			$notice = "Seose salvestamisel tekkis tehniline tõrge: " .$stmt->error;
		}
	}
	
	$stmt->close();
	$conn->close();
	return $notice;
}













?>