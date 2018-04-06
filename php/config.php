<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
	define('DB_HOST', 'localhost');
	define('DB_NAME', 'id5201685_ipldb');
	define('DB_USER','root');
	define('DB_PASSWORD','password123');
	$con=mysqli_connect(DB_HOST,DB_USER,DB_PASSWORD) or die("Failed to connect to MySQL: " . mysqli_error($con));
?>