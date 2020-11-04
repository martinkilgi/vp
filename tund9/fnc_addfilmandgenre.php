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

function readstudiotoselect($selectedstudio) {
    $notice = "<p>Kahjuks stuudioid ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT production_company_id, company_name FROM production_company");
    echo $conn->error;
    $stmt->bind_result($idfromdb, $companyfromdb);
    $stmt->execute();
    $studios = "";
    while ($stmt->fetch()){
        //<option value="1" selected>Tallinnfilm</option>
        $studios .= '<option value="' .$idfromdb .'"';
        if ($idfromdb == $selectedstudio) {
            $studios .= " selected";
        }
        $studios .= ">" .$companyfromdb ."</option> \n";
    }
    if(!empty($studios)) {
        $notice = '<select name="filmstudioinput">' ."\n";
        $notice .= '<option value="" selected disabled>Vali stuudio</option>' ."\n";
        $notice .= $studios;
        $notice .= "</select> \n";
    }

    $stmt->close();
	$conn->close();
	return $notice;
}

function storenewstudiorelation($selectedmovie, $selectedstudio) {
    $notice = "";
	$conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
	$stmt = $conn->prepare("SELECT movie_by_production_company_id FROM movie_by_production_company WHERE movie_movie_id = ? AND production_company_id = ?");
	echo $conn->error;
	$stmt->bind_param("ii", $selectedmovie, $selectedstudio);
	$stmt->bind_result($idfromdb);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = "Selline seos on juba olemas!";
	} else {
		$stmt->close();
		$stmt = $conn->prepare("INSERT INTO movie_by_production_company (movie_movie_id, production_company_id) VALUES(?,?)");
		echo $conn->error;
		$stmt->bind_param("ii", $selectedmovie, $selectedstudio);
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

function oldversionreadpersoninmovie() {
    $notice = "";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id
    ");
    echo $conn->error;
    $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
    $stmt->execute();
    while ($stmt->fetch()) {
        $notice .= "<p>" .$firstnamefromdb ." " .$lastnamefromdb;
        if(!empty($rolefromdb)) {
            $notice .= " tegelane " .$rolefromdb;  
        }
        $notice .= ' filmis "' .$titlefromdb .'"' ."\n";
    }

    $stmt->close();
	$conn->close();
	return $notice;

}

function readpersoninmovie($sortby, $sortorder) {
    $notice = "<p>Kahjuks ei leidnud filmitegelasi!</p>";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);

    $sqlphrase = "SELECT first_name, last_name, role, title FROM person JOIN person_in_movie ON person.person_id = person_in_movie.person_id JOIN movie ON movie.movie_id = person_in_movie.movie_id";

    if($sortby == 0) {
        $stmt = $conn->prepare($sqlphrase);
    }
    if ($sortby == 4) {
        if($sortorder == 2) {
            $stmt = $conn->prepare($sqlphrase ." ORDER BY title DESC");
        } else {
            $stmt = $conn->prepare($sqlphrase ." ORDER BY title");
        }
    }

    echo $conn->error;
    $stmt->bind_result($firstnamefromdb, $lastnamefromdb, $rolefromdb, $titlefromdb);
    $stmt->execute();
    $lines = "";
    while ($stmt->fetch()) {
        $lines .= "\t <tr> \n";
        $lines .= "\t \t <td>" .$firstnamefromdb ." " .$lastnamefromdb ."</td>";
        $lines .= "<td>" .$rolefromdb ."</td>";
        $lines .= "<td>" .$titlefromdb ."</td> \n";
        $lines .= "\t </tr> \n";
    }
    if(!empty($lines)) {
        $notice = "<table> \n";
        $notice .= "\t <tr> \n \t \t <th>Isik</th> \n \t \t <th>Roll</th> \n";
        $notice .= "\t \t" .'<th>Film <a href="?sortby=4&sortorder=1">&uarr;</a>&nbsp;<a href="?sortby=4&sortorder=2">&darr;</a></th>' ."\n \t </tr> \n";
        $notice .= $lines;
        $notice .= "</table> \n";
    }

    $stmt->close();
	$conn->close();
	return $notice;

}

function readperson($selected) {
    $notice = "<p>Kahjuks näitlejaid ei leitud!</p> \n";
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT person_id, first_name, last_name FROM person");
    $connerror = $conn->error;
    $stmt->bind_result($personidfromdb, $firstnamefromdb, $lastnamefromdb);
    $stmt->execute();
    $persons = "";
    while ($stmt->fetch()) {
        $persons .= '<option value="' .$personidfromdb .'"';
        if(intval($personidfromdb) == $selected){
			$persons .=" selected";
        }
        $persons .= ">" .$firstnamefromdb ." ". $lastnamefromdb ."</option> \n";
    }
    if (!empty($genres)) {
        $notice = '<select name="personinput" id="personinput">' ."\n";
		$notice .= '<option value="" selected disabled>Vali näitleja</option>' ."\n";
		$notice .= $persons;
		$notice .= "</select> \n";
    }
    $stmt->close();
    $conn->close();
    return $notice;
}












?>