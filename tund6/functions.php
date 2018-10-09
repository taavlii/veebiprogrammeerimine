<?php
  //laen andmebaasi info
  require ("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_taavi_li_1";
  
  //võtan kasutusele sessiooni
  session_start();


  //loen sõnumi valideerimiseks
  function readmsgforvalidation($editId){
	$notice = "";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT msg FROM vpamsg WHERE id = ?");
	$stmt->bind_param("i", $editId);
	$stmt->bind_result($msg);
	$stmt->execute();
	if($stmt->fetch()){
		$notice = $msg;
	}
	$stmt->close();
	$mysqli->close();
	return $notice;
  }

   //valideerimata sõnumite lugemine
  function readallunvalidatedmessages(){
	$notice = "<ul> \n";
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, msg FROM vpamsg WHERE accepted IS NULL ORDER BY id DESC");
	echo $mysqli->error;
	$stmt->bind_result($id, $msg);
	$stmt->execute();
	
	while($stmt->fetch()){
		$notice .= "<li>" .$msg .'<br><a href="validatemessage.php?id=' .$id .'">Valideeri</a>' ."</li> \n";
	}
	$notice .= "</ul> \n";
	$stmt->close();
	$mysqli->close();
	return $notice;
  }
  
  //sisselogimine
  function signin($email, $password){
	  $notice = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $stmt = $mysqli->prepare("SELECT id, firstname, lastname, password FROM vpusers WHERE email=?");
	  $mysqli->error;
	  $stmt->bind_param("s", $email);
	  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb, $passwordFromDb);
      if($stmt->execute()){
		  //kui õnnestus andmebaasist lugemine
		  if($stmt->fetch()){
			  //leiti selline kasutaja
			  if(password_verify($password, $passwordFromDb)){
				  //parool õige
				  $notice = "Logisite Sisseee";
				  $_SESSION["userId"] = $idFromDb;
				  $_SESSION["firstName"] = $firstnameFromDb;
				  $_SESSION["lastName"] = $lastnameFromDb;
				  $stmt->close();
				  $mysqli->close();
			      header("Location: main.php");
				  exit();
				  
			} 
			  else {
				  $notice = "sisestasite vale salasõna";
			  }
		  } else {
			  $notice = "sellist kasutajat (" .$email .") ei leitud";
		  }
	  } else {
		  $notice = "sisselogimisel tekkis kala" .$stmt->error;
	  }
  
	
	  
	  $stmt->close();
	  $mysqli->close();
	  return $notice;
  }
  
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