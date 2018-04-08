<?php
	session_start();
	if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true) {
		header ("Location: homepage.php");
	}
?>
<!doctype html>
<!--[if IEMobile 7 ]>    <html class="no-js iem7" lang="en-US"> <![endif]-->
<!--[if lt IE 7]> <html class="no-js lt-ie9 lt-ie8 lt-ie7" lang="en-US"> <![endif]-->
<!--[if IE 7]>    <html class="no-js lt-ie9 lt-ie8" lang="en-US"> <![endif]-->
<!--[if IE 8]>    <html class="no-js lt-ie9" lang="en-US"> <![endif]-->
<!--[if gt IE 8|(gt IEMobile 7)|!(IEMobile)]><!--> <html class="no-js" lang="en-US"> <!--<![endif]-->
<head>
  <meta charset="UTF-8" />
  <meta http-equiv="cleartype" content="on">
  <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
<title>Gully IPL - Forgot Password</title>
<meta name="description" content="">
<meta name="keywords" content="">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">



<!-- CSS-->
<!-- Google web fonts. You can get your own bundle at http://www.google.com/fonts. Don't forget to update the CSS accordingly!-->
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="css/basic-style.css">
<link rel="stylesheet" href="css/material.cyan-light_blue.min.css">

<!-- end CSS-->
    
<!-- JS-->
<script src="js/libs/modernizr-2.6.2.min.js"></script>
<!-- end JS-->

</head>
<!--body style="background-image: url('images/ipl-bg1.jpg'); background-size: cover;"-->
	<span style="display:block; height: 35px;"></span>
    <section id="card" class="clearfix">
	<div class="login_image">
		<img src="images/trophy.png"/>
	</div>
	<div class="wrapper">
        <div class="row"> 
            <div class="grid_5">
				<div class="mdl-typography--body-1" style="text-align: justify;">
					Please enter your username and click Forgot Password. You will receive an email on your registered email address with instructions to reset your password.
				</div>
				<span style="display:block; height: 25px;"></span>
				<form method="POST" action="php/send_password_email.php" onsubmit="validateMyForm();">
				<input id="username" name="username" placeholder="Username" type="text" value="" spellcheck="false" onchange="remove_error();">
				<span role="alert" class="error-msg" name="errormsg_0_username" id="errormsg_0_username"></span>
				<input id="ForgotPass" name="forgotPass" class="button button-submit" type="submit" value="Forgot Password">
				</form>
			</div>
	    </div>
	</div>
    </section>
	<script type="text/javascript">
		function validateMyForm()
		{
		  var valUsername;
		  if(this.username.value == "")
		  { 
			this.username.focus();
			this.username.className = this.username.className + ' form-error';
			this.errormsg_0_username.innerHTML = "Username cannot be left blank";
			valUsername = false;
			//return false;
		  } else {
			this.username.className = this.username.className.replace
      ( /(?:^|\s)form-error(?!\S)/g , '' );
			valUsername = true;
			//return true;
		  }
		  if (valUsername){
			return true;
		  } else {
			event.preventDefault();
                        return false;
		  }
		}
		
		function remove_error()
		{
			if(this.username.value != "") {
				this.username.className = this.username.className.replace
      ( /(?:^|\s)form-error(?!\S)/g , '' );
				this.errormsg_0_username.innerHTML = "";
				this.errormsg_0_userpass.innerHTML = "";
			}
			return true;
		}
	</script>
</body>
</html>