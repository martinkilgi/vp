<?php
$database = "if20_martin_kl_2";

function storeCarInfo ($regnum, $enteringmass, $leavingmass) {
    $success = 0;
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt= $conn->prepare("INSERT INTO viljavedu (auto_registrinumber, sisenemismass, valjumismass) VALUES (?, ?, ?)");
    echo $conn->error;
    $stmt->bind_param("sii", $regnum, $enteringmass, $leavingmass);
    if($stmt->execute()) {
        $success = 1;
    }
    $stmt->close();
    $conn->close();
    return $success;

}

function readCarInfo($sortby, $sortorder) {
    $carhtml = null;
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $sqlphrase = "SELECT auto_registrinumber, sisenemismass, valjumismass FROM viljavedu ORDER BY viljavedu_id DESC";

    if ($sortby == 0) {
        $stmt = $conn->prepare($sqlphrase);
    }
    else {
        if ($sortorder == 2) {
            $stmt = $conn->prepare($sqlphrase . " ORDER BY position_name DESC");
        }
        else {
            $stmt = $conn->prepare($sqlphrase . " ORDER BY position_name");
        }
    }
    echo $conn->error;
    $stmt->bind_result($regnumfromdb, $enteringmassfromdb, $leavingmassfromdb);
    $stmt->execute();
    while($stmt->fetch()) {
        $carhtml .= "Auto registrinumber: " .$regnumfromdb ."\n \t";
        $carhtml .= "Auto sisenemismass: ". $enteringmassfromdb. ". \n \t";
        $carhtml .= "Auto väljumismass: ". $leavingmassfromdb. ". \n \t";
        $carhtml .= "<hr>";
    }
    $carhtml .= "<hr>";
    $stmt->close();
    $conn->close();
    return $carhtml;
}

function readLoadInfo() {
    $carhtml = null;
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT auto_registrinumber, (sisenemismass - valjumismass) AS koorem FROM viljavedu");
    echo $conn->error;
    $stmt->bind_result($regnumfromdb, $kooremfromdb);
    $stmt->execute();
    while($stmt->fetch()) {
        $carhtml .= "Auto registrinumber: " .$regnumfromdb ."\n \t";
        $carhtml .= "Auto koormamass: ". $kooremfromdb. ". \n \t";
        $carhtml .= "<hr>";
    }
    $stmt->close();
    $conn->close();
    return $carhtml;
}

function readSumLoad() {
    $carhtml = null;
    $conn = new mysqli($GLOBALS["serverhost"], $GLOBALS["serverusername"], $GLOBALS["serverpassword"], $GLOBALS["database"]);
    $stmt = $conn->prepare("SELECT sisenemismass - valjumismass AS koorem FROM viljavedu");
    echo $conn->error;
    $stmt->bind_result($kooremfromdb);
    $stmt->execute();
    while($stmt->fetch()) {
        $carhtml .= "Vilja täismass: " .$kooremfromdb ."\n \t";
        $carhtml .= "<hr>";
    }
    $stmt->close();
    $conn->close();
    return $carhtml;
}