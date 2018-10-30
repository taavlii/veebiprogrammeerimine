<?php
    require ("functions.php");
    $profile = readuser($_SESSION["userId"]);
  
    if(isset($_POST["txtcolor"]) and isset($_POST["description"]) and isset($_POST["bgcolor"])){
        $mydescription = $_POST["description"];
        $mybgcolor = $_POST["bgcolor"];
        $mytxtcolor = $_POST["txtcolor"];
     } else {
         $mydescription = "lisa bio";
         $mybgcolor = "#FFFFFF";
         $mytxtcolor = "#000000";
     }
    
?>

<!DOCTYPE html>
<html>


<head>
    <meta charset="utf-8">
    <title>profiili muutmine</title>
</head>

<body>
    <h1>MuUdA Pr0Fi1Li</h1>

    <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <label>Kirjuta kes sa oled selline</label><br>
        <textarea rows="10" cols="80" name="description"><?php echo $mydescription; ?></textarea><br>
        <label>Minu valitud taustavärv: </label><br>
        <input name="bgcolor" type="color" value="<?php echo $mybgcolor; ?>"><br>
        <label>Minu valitud tekstivärv: </label><br>
        <input name="txtcolor" type="color" value="<?php echo $mytxtcolor; ?>"><br>
        <input type="submit" name="submitProfile" value="Salvesta profiil"><br>
    </form>











</body>
</html>