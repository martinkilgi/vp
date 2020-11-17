<?php
    require("usesession.php");
    //check imagesize
    header("Content-type: image/jpeg");
    readfile("../photoupload_normal/" .$_REQUEST["photo"]);


?>