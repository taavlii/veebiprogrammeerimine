<?php

require_once ("functions.php");

$profile = readuser($_SESSION["userId"]);

$mydescription = $profile["description"];
$mybgcolor = $profile["bgcolor"];
$mytxtcolor = $profile["txtcolor"];
if ($profile["userpic"] == "") {
  $userpic = "../vp_picfiles/vp_user_generic.png";
} else {
  $userpic = "../vp_userpics/" . $profile["userpic"];
}

?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <style>
	  <?php
        echo "body{background-color: " .$mybgcolor ."; \n";
		    echo "color: " .$mytxtcolor ."} \n";
	  ?>
	</style>
	<title><?php echo $pageTitle; ?></title>
  </head>
  <body>

    <div>
      <a href="main.php">
       <img src="../vp_picfiles/vp_logo_w135_h90.png" alt="logo">
      </a>
        <img src="../vp_picfiles/vp_banner.png" alt="banner">
    </div>

    <h1><?php echo $pageTitle; ?></h1>