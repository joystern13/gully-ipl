<?php
        include("config.php");
        //phpinfo();
	$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysqli_error($con));
	/* $ID = $_POST['user']; $Password = $_POST['pass']; */
	function SignIn()
	{
		session_start(); //starting the session for user profile page
		if(empty($_POST['username'])){ //checking the 'user' name which is from Sign-In.html, is it empty or have some text
                        echo "Username cannot be left blank. Login Failed.";
                } elseif (empty($_POST['password'])){
                        echo "Password cannot be left blank. Login Failed.";
                }else {
            $sql="SELECT * FROM user_data where lower(USERNAME) = lower('$_POST[username]') AND PASSWORD = '$_POST[password]'";
			$query = mysqli_query($GLOBALS['con'],$sql);
			echo $sql;
			$row = mysqli_fetch_array($query,MYSQLI_BOTH);

			if(!empty($row['username']) AND !empty($row['password'])) 
			{ 
				//$_SESSION['USERNAME'] = $row['PASSWORD'];
                $_SESSION['loggedin'] = true;
                //$_SESSION['userid'] = $row['USERID'];
                $_SESSION['username'] = $_POST['username'];
                $_SESSION['invalid_user'] = false;
                //$_SESSION['profilepic'] = $row['ProfilePic'];
                if($row['gender']=='MALE')
                	$_SESSION['profilepic'] = "images/male-user.jpg";
                elseif($row['gender']=='FEMALE')
                	$_SESSION['profilepic'] = "images/female-user.jpg";
                else
                	$_SESSION['profilepic'] = "images/male-user.jpg";
                
                header("Location: ../homepage.php");
				exit();
			} else {
				$_SESSION['invalid_user'] = true;
				$_SESSION['last_page'] = "validate_entry";
				header ("Location: ../login.php");
				exit();
			} 
		}
	} 
	if(isset($_POST['signIn']))
	{
		SignIn();
	}
?>