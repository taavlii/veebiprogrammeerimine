<?php
  //kutsume välja funktsioonide faili
  require ("functions.php");
  
   
  
  //kontrollime kas kasutaja on midagi kirjutanud
  
  
  $notice = null;
  $givecats = listcats();
  
	if (!isset($_POST["name"])){
		$notice = "viga";
	}
	else if (!isset($_POST["color"])){
		$notice = "viga";
	}
	else if (!isset($_POST["tail"])){
		$notice = "viga";
	}
	else {
		$name = test_input($_POST["name"]);
		$color = test_input($_POST["color"]);
		$tail = test_input($_POST["tail"]);
		$notice = addcat($name, $color, $tail);
	}
  
		
  
  
  
  ?>
<!DOCTYPE html>
<html>
  <head>
    <title>Add-a-Cat!
	</title>
  </head>
  <h1>Add cats bro</h1>

  <body>
  <form method="POST" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
    <label>Name of cat:</label>
    <input type="text" name="name">
    <label>Color of cat:</label>
    <input type="text" name="color">
	<label>Tail length of cat:<label>
	<input type="number" min="0" max="30" name="tail">
    <input type="submit" name="submitUserData" value="Saada andmed">
  </select>
  </form>
  
  
  
  <br>
  <?php
    echo $givecats;
  ?>
  
  
  
  
 