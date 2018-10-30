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

  $pageTitle="Pealeht";
  require("header.php");
?>


	<p>See leht on valminud <a href="http://www.tlu.ee" target="_blank">TLÜ</a> õppetöö raames ja ei oma mingisugust, mõtestatud või muul moel väärtuslikku sisu.</p>
	<hr>
	
	<p>Oled sisse loginud nimega <?php echo $_SESSION["firstName"]. " " .$_SESSION["lastName"] . "."; ?></p>
	
	<ul>
    <li><a href="?logout=1">Logi välja!</a></li>  
    <li><a href="validatemsg.php">Valideeri anon sõnumeid</a></li> 
    <li><a href="validatedmessages.php">Sõnumeid valideerijate kaupa</a></li> 
    <li><a href="userprofile.php">Muuda profiili</a></li>
    <li><a href="userlist.php">Sirvi kasutajaid</a></li>
    <li><a href="photoupload.php">Fotode üleslaadimine</a></li>
	</ul>
	
  </body>
</html>