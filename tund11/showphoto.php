<?php
    require("usesession.php");
    //require("fnc_photo.php");
    //check imagesize  ["mime"]
    //$imagename = PhotoIdFromDb($_GET["data-fn"]);

    header("Content-type: image/jpeg");
    readfile("../photoupload_normal/" .$_REQUEST["photo"]);
    //$photoid = $_REQUEST["photoid"];

?>