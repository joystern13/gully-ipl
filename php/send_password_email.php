<?php

	session_start();
	include("config.php");
	$db = mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

	$stmt = $GLOBALS['con']->prepare("select firstname,email,username,password from user_data where username=?");
    $stmt->bind_param("s", $_POST['username']);
    
    $stmt->bind_result($firstname, $email, $username, $password);
    $i = $stmt->fetch();

	if ($i<=0) {
		header("Location: ../forgot_pass.php");
		exit();
	}

	$username=md5($username);
	$password=md5($password);

	// password reset link
	$link = "https://gullyipl.000webhostapp.com/php/reset_password.php?key=".$username."&reset=".$password;

	// email body
	$msg = "Hello $firstname,\nGreetings from Gully IPL.\nPlease use below link to reset your password:\n\n".$link;

	// send email
	mail($email,"Gully IPL - Password Reset",$msg);

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