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
      $target_file = $target_dir . "vp_" .$timeStamp ."." .$imageFileType;

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
    if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
        echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
    } else {
        echo "Tekkis viga";
    }
  }
}
} 
  //siin lõppeb nupuvajutuse kontroll

  
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
    <input type="submit" value="Lae üles" name="submitImage">
    </form>
	
  </body>
</html>