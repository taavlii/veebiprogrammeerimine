<?php
  //laen andmebaasi info
  require ("../../../config.php");
  //echo $GLOBALS["serverUsername"];
  $database = "if18_taavi_li_1";
  
  //võtan kasutusele sessiooni
  session_start();

  
 
  function addUserPic($filename){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userpic) VALUES (?)");
	echo $mysqli->error;
	
	$stmt->bind_param("s", $filename);
	if($stmt->execute()){
		echo "andmebaasiga on kõik korras";
	}	else {
		echo "andmebaasiga läks midagi valesti" .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }
 
 
  function addPhotoData($filename, $altText, $privacy){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("INSERT INTO vpphotos (userid, filename, alttext, privacy) VALUES (?,?,?,?)");
	echo $mysqli->error;
	if(empty($privacy)){
		$privacy = 3;
	}
	$stmt->bind_param("issi", $_SESSION["userId"], $filename, $altText, $privacy);
	if($stmt->execute()){
		echo "andmebaasiga on kõik korras";
	}	else {
		echo "andmebaasiga läks midagi valesti" .$stmt->error;
	}
	$stmt->close();
	$mysqli->close();
  }

 
 function updateuser($userid, $description, $bgcolor, $txtcolor, $userPic) {
	$profile = readuser($userid);
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	if ($profile['exists']) {
		if ($userPic == "") {
			$userPic = $profile['userpic'];
		}
		$stmt = $mysqli->prepare("UPDATE vpuserprofiles SET description = ?, bgcolor = ?, txtcolor = ?, userpic = ? WHERE userid = ?");
		$stmt->bind_param("ssssi", $description, $bgcolor, $txtcolor, $userPic, $userid);
	} else {
		$stmt = $mysqli->prepare("INSERT INTO vpuserprofiles (userid, description, bgcolor, txtcolor, userpic) VALUES (?,?,?,?,?)");
		$stmt->bind_param("issss", $userid, $description, $bgcolor, $txtcolor, $userPic);
	}
	$stmt->execute();
	$stmt->close();
	$mysqli->close();
	


 }
 
 
 
  function readuser($userid){
	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	$stmt = $mysqli->prepare("SELECT id, userid, description, bgcolor, txtcolor, userpic FROM vpuserprofiles WHERE userid=?");
	echo $mysqli->error;
	$stmt->bind_param("i", $userid);
	$stmt->bind_result($idFromDb, $useridFromDb, $descriptionFromDb, $bgcolorFromDb, $txtcolorFromDb, $userPicFromDb);
	$stmt->execute();
	if($stmt->fetch()){
		$data = [];
		$data["description"] = $descriptionFromDb;
		$data["bgcolor"] = $bgcolorFromDb;
		$data["txtcolor"] = $txtcolorFromDb;
		$data["userpic"] = $userPicFromDb;
		$data["exists"] = true;
	}	else {
		$data["description"] = "Kes sa selline oled";
		$data["bgcolor"] = "#FFFFFF";
		$data["txtcolor"] = "#000000";
		$data["userpic"] = "";
		$data["exists"] = false;
	}
	$stmt->close();
	$mysqli->close();
	return $data;


 }
 
 
 
  //kõigi valideeritud sõnumite lugemine kasutajate kaupa 
  function readallvalidatedmessagesbyuser(){
      $totalhtml = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
	  $stmt = $mysqli->prepare("SELECT id, firstname, lastname FROM vpusers");
	  echo $mysqli->error;
	  $stmt->bind_result($idFromDb, $firstnameFromDb, $lastnameFromDb);

	  $stmt2=$mysqli->prepare("SELECT msg, accepted FROM vpamsg WHERE acceptedby=?");
	  echo $mysqli->error;
	  $stmt2->bind_param("i", $idFromDb);
	  $stmt2->bind_result($msgFromDb, $acceptedFromDb);

	  $stmt->execute();
	  //et hoida ABst leitud andmeid kauem mälus, et saaks edasi kasutada
	  $stmt->store_result();
	  while($stmt->fetch()){
		  $count = 0;	  
		  $msghtml = "<h3>" .$firstnameFromDb ." " .$lastnameFromDb ."</h3> \n";
		  $stmt2->execute();
		  while($stmt2->fetch()){
			$count++;
			$msghtml .= "<p><b>";
			if($acceptedFromDb == 1){
				$msghtml .= "Lubatud: ";
				} else {
				$msghtml .= "Keelatud: ";
				}
			$msghtml .= "</b>" .$msgFromDb ."</p> \n";	
			
		  
		  }
		  if($count > 0){
			$totalhtml .= $msghtml; 	
	  }
	  }
	  $stmt2->close();
	  $stmt->close();
  	  $mysqli->close();
	  return $totalhtml;
  }

  
  function users(){
  	$notice = "<ul>";
  	$mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
  	$stmt = $mysqli->prepare("SELECT  firstname, lastname, email FROM vpusers WHERE id !=?");
  	echo $mysqli->error;
  	$stmt->bind_param("i",$_SESSION["userId"]);
  	$stmt->bind_result($firstname, $lastname, $email);
  	$stmt->execute();
   	while($stmt->fetch()){
			  $notice .= "<li>" .$firstname . " " .$lastname . " " .$email ."</li> \n";
  	}
  	$stmt->close();
  	$mysqli->close();
  	return $notice;
  	}
  
  function validatemsg($messageId, $accepted, $userid) {
    $notice = "Tehtud";
    $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]);
    $stmt = $mysqli->prepare("UPDATE vpamsg SET acceptedby=?, accepted=?, accepttime=now() WHERE id=?");
    echo $mysqli->error;
    $stmt->bind_param("iii", $userid, $accepted, $messageId);
    $stmt->execute();
    $stmt->close();
    $mysqli->close();
    return $notice;
  }
  
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
  
  function allvalidmessages(){
	  $msgHTML = "";
	  $mysqli = new mysqli($GLOBALS["serverHost"], $GLOBALS["serverUsername"], $GLOBALS["serverPassword"], $GLOBALS["database"]); 
	  $stmt = $mysqli->prepare("SELECT msg FROM vpamsg WHERE accepted=1");
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