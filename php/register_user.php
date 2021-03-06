<?php
	include("config.php");
	$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
	/* $ID = $_POST['user']; $Password = $_POST['pass']; */
	function SignIn()
	{
		session_start(); //starting the session for user profile page
		if(empty($_POST['firstname'])){ //checking the 'user' name which is from Sign-In.html, is it empty or have some text
                echo "Firstname cannot be left blank.";
        } elseif (empty($_POST['lastname'])){
                echo "Lastname cannot be left blank.";
        } elseif (empty($_POST['email'])){
                echo "Email cannot be left blank.";
        } elseif (empty($_POST['Gender'])){
                echo "Gender cannot be left blank.";
        } elseif (empty($_POST['username'])){
                echo "Username cannot be left blank.";
        } elseif (empty($_POST['password'])){
                echo "Password cannot be left blank.";
        } else {
 
			$query = mysqli_query($GLOBALS['con'],"SELECT * FROM user_data where lower(USERNAME) = lower('$_POST[username]')");
			//$row = mysqli_fetch_array($query,MYSQLI_BOTH);
            if(mysqli_num_rows($query) > 0) 
			{
				//echo "Username already exists. Please select a different username."; 
                $_SESSION['invalid_msg'] = "Username already exists. Please select a different username."; 
                header ("Location: ../signup.php");
				    				exit();
			} else {
			 
                $fName = ucwords(strtolower($_POST['firstname']));
                $lName = ucwords(strtolower($_POST['lastname']));
                
                $username = $_POST['username'];
                $password = $_POST['password'];
                $email = $_POST['email'];
                $gender = $_POST['Gender'];
                
                $stmt = $GLOBALS['con']->prepare("insert into user_data (username,password,firstname,lastname,email,gender) VALUES (?, ?, ?, ?, ?, ?)");
                $stmt->bind_param("ssssss", $username,$password,$fName,$lName,$email,$gender);
                $i = $stmt->execute();
				if ($i > 0) {
				    $_SESSION['msg'] = "You have successfully registered.</br>Please login to continue.";
                                    header ("Location: ../login.php");
				    				exit();
                                } else {
                                    echo "Error: <br>" . $GLOBALS['con']->error;
                                } 
			} 
		}
	} 
	if(isset($_POST['register']))
	{
		SignIn();
	}
?>