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

  if(isset($_GET["id"])){
	  $msgid = $_GET["id"];
      $msg = readmsgforvalidation($_GET["id"]);

  }
  
  if(isset($_POST["submitValidation"]) and isset($_POST["id"])){
	  $msgid = $_POST["id"];
	  $msg = readmsgforvalidation($_POST["id"]);
	  $notice = validatemsg($_POST["id"],$_POST["validation"], $_SESSION["userId"]);
  }


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
	<li><a href="?logout=1">Logi välja</a>!</li>
	<li><a href="validatemsg.php">Tagasi</a> sõnumite lehele!</li>
  </ul>
  <hr>
  <h2>Valideeri see sõnum:</h2>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
    <input name="id" type="hidden" value="<?php echo $msgid; ?>">
    <p><?php echo $msg; ?></p>
    <input type="radio" name="validation" value="0" checked><label>Keela näitamine</label><br>
    <input type="radio" name="validation" value="1"><label>Luba näitamine</label><br>
    <input type="submit" value="Kinnita" name="submitValidation">
  </form>
  <hr>
  <?php
   if(isset($notice)){
	   echo $notice;
   }
  ?>

</body>
</html>

