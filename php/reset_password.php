<?php
	
	session_start();
	include("config.php");
	$db = mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

	function getUserDetails(){
		if(isset($_GET['key']) && isset($_GET['reset']))
		{
		  $username=$_GET['key'];
		  $password=$_GET['reset'];
		  $select=mysqli_query($GLOBALS['con'],"select username,firstname from user_data where md5(username)='$username' and md5(password)='$password'");
		  if(mysqli_num_rows($select)==1)
		  {
		    $row = mysqli_fetch_array($select, MYSQLI_ASSOC);
		    return $row['firstname'];
		  }
		}
		header("Location: ../forgot_pass.php");
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

<link rel="stylesheet" href="../css/normalize.css">
<link rel="stylesheet" href="../css/basic-style.css">
<link rel="stylesheet" href="../css/material.cyan-light_blue.min.css">

<!-- end CSS-->
    
<!-- JS-->
<script src="js/libs/modernizr-2.6.2.min.js"></script>
<!-- end JS-->

</head>
<!--body style="background-image: url('images/ipl-bg1.jpg'); background-size: cover;"-->
	<span style="display:block; height: 35px;"></span>
    <section id="card" class="clearfix">
	<div class="login_image">
		<img src="../images/trophy.png"/>
	</div>
	<div class="wrapper">
        <div class="row"> 
            <div class="grid_5">
				<div class="mdl-typography--body-1" style="text-align: justify;">
					Hello <?php echo getUserDetails(); ?>,<br>Enter your new password below:
				</div>
				<span style="display:block; height: 25px;"></span>
				<form method="POST" action="password_updated.php" onsubmit="validateMyForm();">
				<input id="new_password" name="new_password" placeholder="New Password" type="password" value="" spellcheck="false" onchange="remove_error();">
				<input id="key" name="key" style="visibility: hidden;" value="<?=$_GET['key']?>"></div>
				<input id="reset" name="reset" type="hidden" style="visibility: hidden;" value="<?=$_GET['reset']?>"></div>
				<span role="alert" class="error-msg" name="errormsg_0_password" id="errormsg_0_password"></span>
				<input id="ResetPass" name="ResetPass" class="button button-submit" type="submit" value="Reset Password">
				</form>
			</div>
	    </div>
	</div>
    </section>
	<script type="text/javascript">
		function validateMyForm()
		{
		  var valPassword;
		  if(this.new_password.value == "")
		  { 
			this.new_password.focus();
			this.new_password.className = this.new_password.className + ' form-error';
			this.errormsg_0_password.innerHTML = "Password cannot be left blank";
			valPassword = false;
			//return false;
		  } else {
			this.new_password.className = this.new_password.className.replace
      ( /(?:^|\s)form-error(?!\S)/g , '' );
			valPassword = true;
			//return true;
		  }
		  if (valPassword){
			return true;
		  } else {
			event.preventDefault();
                        return false;
		  }
		}
		
		function remove_error()
		{
			if(this.new_password.value != "") {
				this.new_password.className = this.new_password.className.replace
      ( /(?:^|\s)form-error(?!\S)/g , '' );
				this.errormsg_0_password.innerHTML = "";
			}
			return true;
		}
	</script>
	<script type="text/javascript">
		this.username.value = "";
	</script>
</body>
</html>