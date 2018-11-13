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
  
   $users = users();
?>

<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
	<title>pealeht</title>
  </head>
  <body>
    <h1>KaSuTaJaTeLiSt</h1>
	<p>See leht on valminud valuliselt</p>
	<hr>
	<?php 
	echo $users;
	?>
  </body>
</html>