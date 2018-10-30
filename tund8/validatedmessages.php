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

  $messages = readallvalidatedmessagesbyuser();
  
?>
<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title>Anonüümsed sõnumid</title>
</head>
<body>
  <h1>Sõnumid</h1>
  <p>Siin on minu <a href="http://www.tlu.ee">TLÜ</a> õppetöö raames valminud veebilehed. Need ei oma mingit sügavat sisu ja nende kopeerimine ei oma mõtet.</p>
  <hr>
  <ul>

	<li><a href="main.php">Tagasi</a> pealehele!</li>
  </ul>
  <hr>
  <h2>Valideeritud KaSuTaJaTe KaUpPa LoL sõnumid</h2>
  <?php 
    echo $messages;
  ?>


</body>
</html>
