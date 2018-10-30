<?php
  //echo "See on minu esimene PHP!";
  $firstName = "Taavi";
  $lastName = "Liivat";
  
  
  $picNum = mt_rand(1, 4);
  $picURL = "../../pics/pilt";
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
	?>, if18
  </h1>
	
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei pruugi parim välja näha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
  
  <img src="<?php echo $picFile; ?>" alt="pildike"
  </body>
</html>