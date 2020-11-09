<?php
    class Photoupload {
        private $photoinput;
        private $imagetype;
        private $mytempimage;
        private $mynewtempimage;
        private $uploadedphoto;
        public $fileuploadsizelimit;
        public $notice;

        function __construct($photoinput, $filetype) {
            $this->photoinput = $photoinput;
            $this->imagetype = $filetype;
            //var_dump($this->photoinput);
            $this->createImageFromFile();
        }

        function __destruct() {
            imagedestroy($this->mytempimage);
        }

        private function createImageFromFile() {
            //loome image objekti ehk pikslikogumi
            if($this->imagetype == "jpg"){
                $this->mytempimage = imagecreatefromjpeg($this->photoinput["tmp_name"]);
            }
            if($this->imagetype == "png"){
                $this->mytempimage = imagecreatefrompng($this->photoinput["tmp_name"]);
            }
            if($this->imagetype == "gif"){
                $this->mytempimage = imagecreatefromgif($this->photoinput["tmp_name"]);
            }
        }

        public function resizePhoto($w, $h, $keeporigproportion = true){
            $imagew = imagesx($this->mytempimage);
            $imageh = imagesy($this->mytempimage);
            $neww = $w;
            $newh = $h;
            $cutx = 0;
            $cuty = 0;
            $cutsizew = $imagew;
            $cutsizeh = $imageh;
            
            if($w == $h){
                if($imagew > $imageh){
                    $cutsizew = $imageh;
                    $cutx = round(($imagew - $cutsizew) / 2);
                } else {
                    $cutsizeh = $imagew;
                    $cuty = round(($imageh - $cutsizeh) / 2);
                }	
            } elseif($keeporigproportion){//kui tuleb originaaproportsioone säilitada
                if($imagew / $w > $imageh / $h){
                    $newh = round($imageh / ($imagew / $w));
                } else {
                    $neww = round($imagew / ($imageh / $h));
                }
            } else { //kui on vaja kindlasti etteantud suurust, ehk pisut ka kärpida
                if($imagew / $w < $imageh / $h){
                    $cutsizeh = round($imagew / $w * $h);
                    $cuty = round(($imageh - $cutsizeh) / 2);
                } else {
                    $cutsizew = round($imageh / $h * $w);
                    $cutx = round(($imagew - $cutsizew) / 2);
                }
            }
            
            //loome uue ajutise pildiobjekti
            $this->mynewtempimage = imagecreatetruecolor($neww, $newh);
            //kui on läbipaistvusega png pildid, siis on vaja säilitada läbipaistvusega
            imagesavealpha($this->mynewtempimage, true);
            $transcolor = imagecolorallocatealpha($this->mynewtempimage, 0, 0, 0, 127);
            imagefill($this->mynewtempimage, 0, 0, $transcolor);
            imagecopyresampled($this->mynewtempimage, $this->mytempimage, 0, 0, $cutx, $cuty, $neww, $newh, $cutsizew, $cutsizeh);
        }

        public function addWatermark ($wmf) {
            if(isset($this->mynewtempimage)) {
                $watermark = imagecreatefrompng($wmf);
                $wmw = imagesx($watermark);
                $wmh = imagesy($watermark);
                $wmx = imagesx($this->mynewtempimage) - $wmw - 10;
                $wmy = imagesy($this->mynewtempimage) - $wmh - 10;
                //kopeerin vesimärgi pildile
                imagecopy($this->mynewtempimage, $watermark, $wmx, $wmy, 0, 0, $wmw, $wmh);
                imagedestroy($watermark);
            }
        }

        public function savePhotoFile($target){
            $notice = null;
            if($this->imagetype == "jpg"){
                if(imagejpeg($this->mynewtempimage, $target, 90)){
                    $notice = 1;
                } else {
                    $notice = 0;
                }
            }
            if($this->imagetype == "png"){
                if(imagepng($this->mynewtempimage, $target, 6)){
                    $notice = 1;
                } else {
                    $notice = 0;
                }
            }
            if($this->imagetype == "gif"){
                if(imagegif($this->mynewtempimage, $target)){
                    $notice = 1;
                } else {
                    $notice = 0;
                }
            }
            imagedestroy($this->mynewtempimage);
            return $notice;
        }

        public function testFileSize($photofiletypes) {
            $fileInfo = getImagesize($this->photoinput["tmp_name"]);
            if(in_array($fileInfo["mime"], $photofiletypes)) {
                if($this->photoinput["size"] > $this->fileuploadsizelimit){
                    $this->inputerror .= " Valitud fail on liiga suur!";
                }
                return $this->inputerror;
        }
        }

        public function moveOriginalimage($target) {
            //Kui vigu pole, salvestame originaalpildi
            $notice = null;
            $error = null;
            if(move_uploaded_file($this->photoinput["tmp_name"], $target)){
                $notice .= 1;
            } else {
                $error .= 0;
            }
            return $notice;
            
        }

        public function isItImage($photofiletypes) {
            $check = getimagesize($this->photoinput["tmp_name"]);
            if(in_array($check["mime"], $photofiletypes)){
                //var_dump($check);
                if($check["mime"] == "image/jpeg"){
                    $this->filetype = "jpg";
                }
                if($check["mime"] == "image/png"){
                    $this->filetype = "png";
                }
                if($check["mime"] == "image/gif"){
                    $this->filetype = "gif";
                }
            } else {
                $this->inputerror = "Valitud fail ei ole pilt!";
            }
                }

        public function generateFileName($filenameprefix, $filetype) {
            //genereerime failinime
	        $timestamp = microtime(1) * 10000;
            $filename = $filenameprefix .$timestamp ."." .$filetype;
            return $filename;
        }


    } // klass lõppeb






