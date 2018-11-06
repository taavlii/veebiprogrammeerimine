<?php
  require("functions.php");
  
  //kui pole sisse loginud, siis logimiselehe
  if(!isset($_SESSION["userId"])){
    header("location: index_1.php");
    exit();
  }
  
  //logime välja
  if(isset($_GET["logout"])){
    session_destroy();
    header("location: index_1.php");
    exit();
  }

  //piltide laadimine
  $target_dir = "../vp_pic_uploads/";
  
  $uploadOk = 1;
  
  // Check if image file is a actual image or fake image
  if(isset($_POST["submitImage"])) {
    if(!empty($_FILES["fileToUpload"]["name"])){

      $timeStamp = microtime(1) * 10000;
      $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
      $target_file_name = "vp_" .$timeStamp ."." .$imageFileType;
      $target_file = $target_dir .$target_file_name;

      //$imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION)); see on vana viis
      
      $check = getimagesize($_FILES["fileToUpload"]["tmp_name"]);
      if($check !== false) {
          echo "tegemist on " . $check["mime"] . " pildiga. ";
          //$uploadOk = 1; PoLe VaJaLiK
      } else {
          echo "See pole pilt!";
          $uploadOk = 0;
      }
 

  // Check if file already exists
  if (file_exists($target_file)) {
    echo "Ei, selline on olemas juba";
    $uploadOk = 0;
  }
  // Check file size
  if ($_FILES["fileToUpload"]["size"] > 2500000) {
    echo "Liiga suur pilt";
    $uploadOk = 0;
  }
  // Allow certain file formats
  if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif" ) {
    echo "Peab olema JPG, JPEG, PNG või GIF";
    $uploadOk = 0;
  }
  // Check if $uploadOk is set to 0 by an error
  if ($uploadOk == 0) {
    echo "Ei saa seda üles laadida";
  // if everything is ok, try to upload file
  } else {
    //sõltuvalt failitüübist loon sobiva pildiobjekti
    if($imageFileType == "jpg" or $imageFileType == "jpeg"){
      $myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
    }
    if($imageFileType == "png") {
      $myTempImage = imagecreatefrompng($_FILES["fileToUpload"]["tmp_name"]);
    }
    if($imageFileType == "gif") {
      $myTempImage = imagecreatefromgif($_FILES["fileToUpload"]["tmp_name"]);
   }
  }

   //pildi originaalsuurus
   $imageWidth = imagesx($myTempImage);
   $imageHeight = imagesy($myTempImage);
   //leian suuruse muutmise suhtarvu
   if($imageWidth > $imageHeight){
     $sizeRatio = $imageWidth / 600;
   }  else {
     $sizeRatio = $imageHeight / 400;
   }

   $newWidth = round($imageWidth / $sizeRatio);
   $newHeight = round($imageHeight / $sizeRatio);

   $myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);

   //vesimärgi lisamine pildina
   $waterMark = imagecreatefrompng("../vp_picfiles/vp_logo_w100_overlay.png");
   $waterMarkWidth = imagesx($waterMark);
   $waterMarkHeight = imagesy($waterMark);
   $waterMarkPosX = $newWidth - $waterMarkWidth -10; 
   $waterMarkPosY = $newHeight - $waterMarkHeight -10; 
   imagecopy($myImage, $waterMark, $waterMarkPosX, $waterMarkPosY, 0, 0, $waterMarkWidth, $waterMarkHeight);

   //tekst vesimärgina
   $textToImage ="vEeBiPrOgR4mm3eR1m!ne";
   $textColor =imagecolorallocatealpha($myImage, 255, 255, 255, 60);
    // RGB, alpha 0...127
   imagettftext($myImage, 5, 0, 5, 10, $textColor, "../vp_picfiles/ARIALBD.TTF", $textToImage);

   //faili salvestamine, sõltuvalt failitüübist
   if($imageFileType == "jpg" or $imageFileType == "jpeg"){
     if(imagejpeg($myImage, $target_file, 90)){
      echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
      addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
     } else {
      echo "Tekkis viga";
     }
   }

   if($imageFileType == "png"){
    if(imagepng($myImage, $target_file, 6)){
     echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
     addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
    } else {
     echo "Tekkis viga";
    }
  }

  if($imageFileType == "gif"){
    if(imagegif($myImage, $target_file)){
     echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
     addPhotoData($target_file_name, $_POST["altText"], $_POST["privacy"]);
    } else {
     echo "Tekkis viga";
    }
  }

  imagedestroy($myTempImage);
  imagedestroy($myImage);
  imagedestroy($waterMark);

    //if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
    //  echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
    //} else {
    //  echo "Tekkis viga";
    //}
  }
}
 
  //siin lõppeb nupuvajutuse kontroll

  function resizeImage($image, $ow, $oh, $w, $h){
    $newImage = imagecreatetruecolor($w, $h);
    imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
    return $newImage;
  }

  
  //päise laadimine
  $pageTitle="Fotode üleslaadimine";
  require("header.php");
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	
	<p>Oled sisse loginud nimega <?php echo $_SESSION["firstName"]. " " .$_SESSION["lastName"] . "."; ?></p>


	
	<ul>
    
    <li><a href="main.php">Pealehele</a></li>
	</ul>

    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali fail üleslaadimiseks(max 2.5MB)</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <label>Alt tekst:</label>
    <input type="text" name="altText"><br>
    <label>Määra pildi kasutusõigused</label><br>
    <input type="radio" name="privacy" value="1"><label>Avalik pilt</label>
    <input type="radio" name="privacy" value="2"><label>Ainult sisselogitud kasutajatele nähtav pilt </label>
    <input type="radio" name="privacy" value="3" checked><label>Salajane pilt</label>
    <input type="submit" value="Lae üles" name="submitImage"><br>
    

    </form>
	
  </body>
</html>