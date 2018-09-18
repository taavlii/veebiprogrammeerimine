<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Taavi";
  $lastName = "Liivat";
  $dateToday = date("d.m.Y");
  $weekdayNow = date("N");
  $weekdayNamesET = ["esmaspäev", "teisipäev", "kolmapäev", "neljapäev", "reede", "laupäev", "pühapäev"];
  //echo $weekdayNamesET[1];
  //var_dump($weekdayNamesET);
  $hourNow = date("G");
  $partOfDay = "";
  if ($hourNow < 8) {
	  $partOfDay = "varane hommik";
  } 
  if ($hourNow >= 8 and $hourNow < 16) {
	  $partOfDay = "koolipäev";}
  if ($hourNow >= 16) {
	  $partOfDay = "vaba aeg";  
  } 
  $picNum = mt_rand(2, 43);
  //echo $picNum;
  $picURL = "http://www.cs.tlu.ee/~rinde/media/fotod/TLU_600x400/tlu_";
  $picEXT = ".jpg";
  $picFile = $picURL .$picNum .$picEXT;
  ?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>
    <?php
	  echo $firstName;
	  echo " ";
	  echo $lastName;
	?>, õppetöö</title>
</head>
<body>
  <h1>
    <?php
	  echo $firstName ." " .$lastName;
	?>, if18</h1>
	
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei pruugi parim välja näha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
  <p>Tundides tehtu: <a href="photo.php">photo.php</a><p>
  <?php
    //echo "<p>Tänane kuuupäev on " .$dateToday .".</p> \n";
	echo "<p> Täna on " .$weekdayNamesET[$weekdayNow - 1] .", " .$dateToday .".</p> \n";
	echo "<p>Lehe avamise hetkel oli kell " .date("H:i:s") .". Käes oli " .$partOfDay .".</p> \n";
	echo "<p>Ühest kümneni on php suht " . $picNum . " raske<p>\n";
  ?>
  <h2>THIS IS BANANAZ</h2>  
   <!--<img src="../../../~rinde/veebiprogrammeerimine2018s/tlu_terra_600x400_1.jpg" alt="koolimaja">-->
  <img src="<?php echo $picFile; ?>" alt="TLU maja"
  <p>Mul on ka sõber, kes teeb oma <a href="../../../~karlkar/">veebi.</a></p>
  </body>
</html>