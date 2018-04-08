<?php

	session_start();
	include("config.php");
	$db = mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

	
	$sql = "select firstname,email,username,password from user_data where username='".$_POST['username']."'";
	$query = mysqli_query($GLOBALS['con'],$sql);
	$row = mysqli_fetch_array($query, MYSQLI_ASSOC);

	if (!isset($row) || empty($row)) {
		header("Location: ../forgot_pass.php");
		exit();
	}

	$username=md5($row['username']);
	$password=md5($row['password']);

	if(!mysqli_num_rows($query)==1){
		header("Location: ../forgot_pass.php");
		exit();
	}

	$link = "https://gullyipl.000webhostapp.com/php/reset_password.php?key=".$username."&reset=".$password;

	$msg = "Hello " .$row['firstname'] .",\nGreetings from Gully IPL.\nPlease use below link to reset your password:\n\n".$link;

	// use wordwrap() if lines are longer than 70 characters
	$msg = wordwrap($msg,70);
	// send email
	mail($row['email'],"Gully IPL - Password Reset",$msg);
	//header("Location: ../login.php");
	//exit();

?>
<!doctype html><html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Gully IPL - Homepage</title>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
      <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.cyan-light_blue.min.css"/>
      <link rel="stylesheet" href="css/styles.css"/>
    </head>
    <body>
      <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
          <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
               <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-800">
                  We have sent an email to your registered email with instructions to reset your password.<br><br>Please be patient if you do not receive the email immediately. Also check your spam folder.
               </div>
               <div class="mdl-card__actions mdl-card--border">
                  <a href="../login.php" class="mdl-button mdl-js-button mdl-js-ripple-effect">Login Again</a>
               </div>
            </div>
         </div>
       </div>
</body>
</html>