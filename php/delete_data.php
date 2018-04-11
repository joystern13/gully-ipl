<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('config.php');
$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysqli_error($con));
$username=$_SESSION['username'];
$match_id=$_POST['match_id'];

$matchQuery = "select convert_tz(now(),@@session.time_zone,'+05:30') > DATE_SUB(match_datetime, INTERVAL 1 HOUR) timecompare from match_master where match_id = $match_id";

$sql = "DELETE FROM user_vote_master WHERE username = '$username' AND matchid=$match_id";

$query = mysqli_query($GLOBALS['con'],$matchQuery);
$row = mysqli_fetch_array($query,MYSQLI_BOTH);              

if(!empty($row['timecompare']))
{
    if($row['timecompare'] > 0)
    {
        echo "Sorry! The Voting Gates are closed for this match.";
        return;
    }
}    

if (mysqli_query($GLOBALS['con'],$sql)) 
    echo "Your Vote has been deleted! You can decide to vote again anytime until the voting gates close.";
else
    echo "Error: " . $con->error;

$con->close();

 ?>
