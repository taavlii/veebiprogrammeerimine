<?php
    class Photoupload
    {
        private $tempName;
        private $imageFileType;
        private $myTempImage;
        private $myImage;
        private $textOfDate;

        function __construct($name, $type)
        {
            $this->tempName = $name;
            $this->imageFileType = $type;
            $this->createImageFromFile();
        }

        function __destruct()
        {
            imagedestroy($this->myTempImage);
            imagedestroy($this->myImage);
        }

        private function createImageFromFile()
        {
                if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg")
                {
                    $this->myTempImage = imagecreatefromjpeg($this->tempName);
                }
                if($this->imageFileType == "png") 
                {
                    $this->myTempImage = imagecreatefrompng($this->tempName);
                }
                if($this->imageFileType == "gif") 
                {
                    $this->myTempImagee = imagecreatefromgif($this->tempName);
                }
        }

        public function readExif(){
            @$exif=exif_read_data($this->tempName, "ANY_TAG", 0,true);
            //var_dump($exif);
            echo $exif["DateTimeOriginal"];
            
        }

        public function changePhotoSize($width, $height)
        {
            //pildi originaalsuurus
            $imageWidth = imagesx($this->myTempImage);
            $imageHeight = imagesy($this->myTempImage);
            //leian suuruse muutmise suhtarvu
            if($imageWidth > $imageHeight)
            {
                 $sizeRatio = $imageWidth / $width;
            }  else 
            {
                 $sizeRatio = $imageHeight / $height;
            }

            $newWidth = round($imageWidth / $sizeRatio);
            $newHeight = round($imageHeight / $sizeRatio);

            $this->myImage = $this->resizeImage($this->myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);
   

        }

        private function resizeImage($image, $ow, $oh, $w, $h)
        {
            $newImage = imagecreatetruecolor($w, $h);
            imagesavealpha($newImage, true);
            $transColor =imagecolorallocatealpha($newImage, 0, 0, 0, 127);
            imagefill($newImage, 0, 0, $transColor);
            imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
            return $newImage;
        }

        public function addWatermark()
        {
            $waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
            $waterMarkWidth = imagesx($waterMark);
            $waterMarkHeight = imagesy($waterMark);
            $waterMarkPosX = imagesx($this->myImage) - $waterMarkWidth -10; 
            $waterMarkPosY = imagesy($this->myImage) - $waterMarkHeight -10; 
            imagecopy($this->myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);
        }

        public function addTextToImage()
        {
            if(!empty($exif["DateTimeOriginal"])){
                $textToImage ="PoLeTeAdAMiLlALpIlDiSTaTuD";
            } else {
                $textToImage=$exif["DateTimeOriginal"];
            }
            $textColor =imagecolorallocatealpha($this->myImage, 255, 255, 255, 60);
             // RGB, alpha 0...127
            imagettftext($this->myImage, 5, 0, 5, 10, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);
        }

        public function createThumbNail($directory, $size){
            $imageWidth = imagesx($this->myTempImage);
            $imageHeight = imagesy($this->myTempImage);
            if($imageWidth > $imageHeight){
                $cutSize = $imageHeight;
                $cutX = round(($imageWidth - $cutSize) / 2);
                $cutY = 0;
            } else {
                $cutSize = $imageWidth;
                $cutX = 0;
                $cutY = round(($imageHeight - $cutSize) / 2);
            }
            $myThumbnail = imagecreatetruecolor($size, $size);
            imagecopyresampled($myThumbnail, $this->myTempImage, 0, 0, $cutX, $cutY, $size, $size, $cutSize, $cutSize);
            
            if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg"){
                imagejpeg($myThumbnail, $target_file, 90);
            }
            if($this->imageFileType == "png") {
                imagepng($myThumbnail, $target_file, 6);
            }
            if($this->imageFileType == "gif") {
                imagegif($myThumbnail, $target_file);
             }
            }
            
        
    

        public function savePhoto($target_file)
        {
            $notice=null;
            if($this->imageFileType == "jpg" or $this->imageFileType == "jpeg")
            {
                if(imagejpeg($this->myImage, $target_file, 90))
                {
                    $notice=1;            
                } else 
                {
                    $notice=0;
                }
              }
           
              if($this->imageFileType == "png")
              {
               if(imagepng($this->myImage, $target_file, 6))
               {
                    $notice=1;
               } else 
               {
                    $notice=0;
               }
             }
           
             if($this->imageFileType == "gif")
             {
               if(imagegif($this->myImage, $target_file))
               {
                    $notice=1;
               } else 
               {
                    $notice=0;
               }
             }
             return $notice;
        }


        
    }//class lõppeb
?>