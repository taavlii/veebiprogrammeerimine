<?php
    require ("functions.php");
    
  
    if(isset($_POST["txtcolor"]) and isset($_POST["description"]) and isset($_POST["bgcolor"])){
        $mydescription = $_POST["description"];
        $mybgcolor = $_POST["bgcolor"];
        $mytxtcolor = $_POST["txtcolor"];
        updateuser($_SESSION["userId"], $mydescription, $mybgcolor, $mytxtcolor);
     } else {
         $mydescription = "lisa bio";
         $mybgcolor = "#FFFFFF";
         $mytxtcolor = "#000000";
     }
     $pageTitle="Muuda profiili";

     if(isset($_POST["userpic"])){
         $userpic=$_POST["userpic"];
     }  else {
         $userpic="../vp_picfiles/vp_user_generic.png";
     }

     $target_dir = "../vp_userpics/";


     $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
     if($imageFileType == "jpg" or $imageFileType == "jpeg"){
        $myTempImage = imagecreatefromjpeg($_FILES["fileToUpload"]["tmp_name"]);
     }

     $newWidth=300;
     $newHeight=300;
     $imageWidth = imagesx($myTempImage);
     $imageHeight = imagesy($myTempImage);

     function resizeImage($image, $ow, $oh, $w, $h){
        $newImage = imagecreatetruecolor($w, $h);
        imagecopyresampled($newImage, $image, 0, 0, 0, 0, $w, $h, $ow, $oh);
        return $newImage;
      }

     $myImage = resizeImage($myTempImage, $imageWidth, $imageHeight, $newWidth, $newHeight);

     $imageFileType = "jpg";
     if(isset($_POST["submitImage"])) {
        if(!empty($_FILES["fileToUpload"])){
    
         
          $imageFileType = strtolower(pathinfo(basename($_FILES["fileToUpload"]["name"]),PATHINFO_EXTENSION));
          $target_file_name = "vp_" .$_SESSION["userId"] ." " .$imageFileType;
          $target_file = $target_dir .$target_file_name;
        }
    }
     
     
     
     if($imageFileType == "jpg" or $imageFileType == "jpeg"){
        if(imagejpeg($myImage, $target_file, 90)){
         echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
         addUserPic($target_file_name);
        } else {
         echo "Tekkis viga";
        }
      }
    
     require("header.php");
?>







    

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>Kirjuta kes sa oled selline</label><br>
        <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
        <label>Minu valitud taustavärv: </label><br>
        <input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
        <label>Minu valitud tekstivärv: </label><br>
        <input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
        <input type="submit" name="submitProfile" value="Salvesta profiil"><br>
    </form>

     <br>
     <h2>Teie kasutajapilt</h2><br>
     <img src=<?php echo $userpic; ?> alt="kasutaja pilt"><br>
     
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <label>Vali uus kasutajapilt(max 2.5MB, peab olema jpeg formaadis)</label>
    <input type="file" name="fileToUpload" id="fileToUpload"><br>
    <input type="submit" value="Lae üles" name="submitImage"><br>
    

    </form>
     


    <ul>
     
    <li><a href="main.php">Pealehele</a></li> 
   
	</ul>











</body>
</html>