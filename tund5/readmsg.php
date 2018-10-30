<?php
  require ("functions.php");
  
  $notice = listallmessages();
?>
<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <title>anon sõn lugemine</title>
</head>
<body>
  <h1>Sõnumid</h1>
	
  <p>See leht on loodud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei pruugi parim välja näha ning kindlasti ei sisalda tõsiseltvõetavat sisu!</p>
  
  <hr>
  
  
  <hr>
  
  <?php 
	echo $notice; 
  ?>
  
  </body>
</html>