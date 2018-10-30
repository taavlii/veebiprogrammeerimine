<?php
  //laen andmebaasi info
  require ("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_taavi_li_1";
  
  function signup($firstName, $lastName, $birhDate, $gender, $email, $password){
	  $notice = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $stmt = $mysqli ->prepare("INSERT INTO vpusers (firstname, lastname, birthdate, gender, email, password) VALUES (?, ?, ?, ?, ?, ?)");
	  echo $mysqli->error;
	  //valmistame parooli ette salvestamiseks - krüpteerime, teeme räsi(hash)
	  $options = [
	    "cost" => 12,
		"salt" => substr(sha1(rand()), 0, 22),];
	  $pwdhash = password_hash($password, PASSWORD_BCRYPT, $options);
	  $stmt->bind_param("sssiss", $firstName, $lastName, $birhDate, $gender, $email, $pwdhash);
	  if($stmt->execute()){
		  $notice = "Uue kasutaja lisamine õnnestus, uskumatu!";
	  } else {
		  $notice = "Kasutaja lisamisel tekkis viga :( " .$stmt->error;
	  }
	  
	  
	  $stmt->close();
	  $mysqli->close();
	  return $notice;
	  
  }
  
  //anon msg salvestamine
  function saveamsg($msg){
	  $notice = "";
	  //server ühendus (server, kasutaja, parool, andmebaas)
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  //valmistan ette SQL käsu
	  $stmt = $mysqli->prepare("INSERT INTO vpamsg (msg) VALUES (?)");
	  echo $mysqli->error;
	  //asendame SQL käsus küsimärgi päris infoga(andmetüüp, andmed ise)
	  //s-string; i-ineger; d-decimal
	  $stmt->bind_param("s", $msg);
	  if ($stmt->execute()) {
		  $notice = 'sõnum : "' .$msg .'" on salvestatud.';
	  } else {
		  $notice = "sõnumi salvestamisel tekkis tõrge: ".$stmt->error;
	  }
      $stmt->close();
	  $mysqli->close();
	  return $notice;
  }
  
  function listallmessages(){
	  $msgHTML = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  $stmt = $mysqli->prepare("SELECT msg FROM vpamsg");
	  echo $mysqli->error;
	  $stmt->bind_result($msg);
	  $stmt->execute();
	  while($stmt->fetch()){
		  $msgHTML .= "<p>" .$msg ."</p> \n";
  }
	  $stmt->close();
	  $mysqli->close();
	  return $msgHTML;
  }
  
  function addcat($name, $color, $tail){
	  $notice = "";
	  //server ühendus (server, kasutaja, parool, andmebaas)
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  //valmistan ette SQL käsu
	  $stmt = $mysqli->prepare("INSERT INTO kassid (nimi, v2rv, saba) VALUES (?,?,?)");
	  echo $mysqli->error;
	  //asendame SQL käsus küsimärgi päris infoga(andmetüüp, andmed ise)
	  //s-string; i-ineger; d-decimal
	  $stmt->bind_param("ssi", $name, $color, $tail);
	  if ($stmt->execute()) {
		  $notice = "kass : " .$name . " on salvestatud.";
	  } else {
		  $notice = "kassi salvestamisel tekkis tõrge: ".$stmt->error;
	  }
      $stmt->close();
	  $mysqli->close();
	  return $notice;
  }
  
   function listcats(){
	  $msgHTML = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  $stmt = $mysqli->prepare("SELECT nimi, v2rv, saba FROM kassid");
	  echo $mysqli->error;
	  $stmt->bind_result($readname, $readcolor, $readtail);
	  $stmt->execute();
	  while($stmt->fetch()){
		  $msgHTML .= "<p>" .$readname ." ". $readcolor . " " . $readtail ."</p> \n";
  }
	  $stmt->close();
	  $mysqli->close();
	  return $msgHTML;
  }
  
  
  //textsisestuse kontroll
  function test_input($data) {
    $data = trim($data);
    $data = stripslashes($data);
    $data = htmlspecialchars($data);
    return $data;
  }
?>