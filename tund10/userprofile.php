<?php
    require ("functions.php");
    
    if (isset($_POST['submitProfile'])) {
        
        if(isset($_POST["txtcolor"]) and isset($_POST["description"]) and isset($_POST["bgcolor"])){
            $mydescription = $_POST["description"];
            $mybgcolor = $_POST["bgcolor"];
            $mytxtcolor = $_POST["txtcolor"];
        }

        $target_dir = "../vp_userpics/";

        if(isset($_FILES["fileToUpload"]) and !empty($_FILES["fileToUpload"]["name"])){
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
            $target_file_name = "vp_" .$_SESSION["userId"] ."." .$imageFileType;
            $target_file = $target_dir .$target_file_name;
            
            if(imagejpeg($myImage, $target_file, 90)){
                echo "Fail ". basename( $_FILES["fileToUpload"]["name"]). " laeti üles.";
                updateuser($_SESSION["userId"], $mydescription, $mybgcolor, $mytxtcolor, $target_file_name);
            } else {
                echo "Tekkis viga";
            }
        } else {
            updateuser($_SESSION["userId"], $mydescription, $mybgcolor, $mytxtcolor, "");
        }
    }
    
    $pageTitle="Muuda profiili";
    require("header.php");
?>







    

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" enctype="multipart/form-data">
        <label>Kirjuta kes sa oled selline</label><br>
        <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
        <label>Minu valitud taustavärv: </label><br>
        <input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
        <label>Minu valitud tekstivärv: </label><br>
        <input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
        <label>Vali uus kasutajapilt(max 2.5MB, peab olema jpeg formaadis)</label><br>
        <input type="file" name="fileToUpload" id="fileToUpload"><br>
        <input type="submit" value="Salvesta profiil" name="submitProfile"><br>
   
   
    </form>

     <br>
     <h2>Teie kasutajapilt</h2><br>
     <img src=<?php echo $userpic; ?> alt="kasutaja pilt"><br>
     
    <!-- <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data"> -->
    <!-- <label>Vali uus kasutajapilt(max 2.5MB, peab olema jpeg formaadis)</label> -->
    <!-- <input type="file" name="fileToUpload" id="fileToUpload"><br> -->
    <!-- <input type="submit" value="Lae üles" name="submitImage"><br> -->
    

    </form>
     


    <ul>
     
    <li><a href="main.php">Pealehele</a></li> 
   
	</ul>











</body>
</html>