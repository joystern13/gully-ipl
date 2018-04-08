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
<title>Gully IPL - Register</title>
<meta name="description" content="">
<meta name="keywords" content="">

<!-- Mobile viewport -->
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">

<link rel="shortcut icon" href="images/quiz-questions.jpg"  type="image/x-icon">

<!-- CSSK-->
<!-- Google web fonts. You can get your own bundle at http://www.google.com/fonts. Don't forget to update the CSS accordingly!-->
<link href='https://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>

<link rel="stylesheet" href="css/normalize.css">
<link rel="stylesheet" href="js/flexslider/flexslider.css">
<link rel="stylesheet" href="css/basic-style.css">

<!-- end CSS-->
    
<!-- JS-->
<script src="js/libs/modernizr-2.6.2.min.js"></script>
<!-- end JS-->

</head>
<body>
	<span style="display:block; height: 35px;"></span>
    <section id="card-wide" class="clearfix">
	<div class="wrapper">
        <!--div class="row"--> 
            <!--div class="grid_5"-->
				<h1>Register</h1>
                <?php
                session_start();
					//echo " : : : ".$_SESSION['invalid_msg'];
						if (isset($_SESSION['invalid_msg']) && $_SESSION['invalid_msg'] != "") {
							echo "<div class=\"error-msg\" id=\"errormsg_0_userpass\">".$_SESSION['invalid_msg']."</div>";
	                    }
					unset($_SESSION['invalid_msg']);
					
				?>
				<form method="POST" action="php/register_user.php">
				<strong>Name</Strong>
					<table align="center" width="100%" cellpadding="0" cellspacing="0" border="0"> 
						<tr>
							<td style="width:49%; padding-right:2%;"> <input id="firstname" maxlength="60" name="firstname" placeholder="First Name" type="text" value="" spellcheck="false"> </td>
							<td style="width:49%;"> <input id="lastname" name="lastname" maxlength="60" placeholder="Last Name" type="text" value="" spellcheck="false"> </td>
						</tr>
					</table>
				<strong>E-mail</Strong>
				<input id="email" name="email" maxlength="100" placeholder="E-mail address" type="email" value="" spellcheck="false">
				<!--input id="dob" name="dob" placeholder="Date of Birth" type="date" value=""-->
					<!--
<div>
					<strong>Birthday</strong>
					<table align="center" width="100%" cellpadding="0" cellspacing="0" border="0"> 
						<tr>
						<td style="width:42%; padding-right:2%;"><span id="BirthMonthHolder" style="width:50%;">
						  <select id="BirthMonth" name="BirthMonth">
							<option value="" disabled selected hidden>Month</option>
							<option value="01">January</option>
							<option value="02">February</option>
							<option value="03">March</option>
							<option value="04">April</option>
							<option value="05">May</option>
							<option value="06">June</option>
							<option value="07">July</option>
							<option value="08">August</option>
							<option value="09">September</option>
							<option value="10">October</option>
							<option value="11">November</option>
							<option value="12">December</option>
						  </select>
						</span></td>
						<td style="width:22%; padding-right:2%;"><input type="text" maxlength="2" name="BirthDay" id="BirthDay" value="" placeholder="Day" /></td>
						<td style="width:32%;"><input type="text" maxlength="4" value="" name="BirthYear" id="BirthYear" placeholder="Year" /></td>
						</tr> 
					</table>
				</div>
-->
				<label id="gender-label">
					<strong id="GenderLabel">Gender</strong>
					<div id="GenderHolder" >
						<select id="Gender" name="Gender" >
							<option value="" disabled selected hidden>Select Gender</option>
							<option value="FEMALE" >Female</option>
							<option value="MALE" >Male</option>
							<option value="OTHER" >Other</option>
						</select>
					</div>
				</label>
				<strong>Username</Strong>
				<input id="username" name="username" maxlength="50" placeholder="Username" type="text" value="" spellcheck="false">
				<strong>Password</Strong>
				<input id="password" name="password" maxlength="20" placeholder="Password" type="password" value="" spellcheck="false">
				<span style="display:block; height: 15px;"></span>
				<input id="register" name="register" class="button button-submit" type="submit" value="Register Now">
				</form>
			<!--/div-->
	    <!--/div-->
	</div>
    </section>
</body>
