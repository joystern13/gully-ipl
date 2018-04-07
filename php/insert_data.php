<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
session_start();
include('config.php');
$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysqli_error($con));
$username=$_SESSION['username'];
$match_id=$_POST['match_id'];

if (!isset($_POST['teamid']) || empty($_POST['teamid'])) {
    echo "Please select the team you would like to vote first!";
    exit();
}

$vote_team_id=$_POST['teamid'];

$matchQuery = "select convert_tz(now(),@@session.time_zone,'+05:30') > DATE_SUB(match_datetime, INTERVAL 1 HOUR) timecompare from match_master where match_id = $match_id";

$insertQuery = "INSERT INTO user_vote_master(username,matchid,teamid)
        VALUES('$username', $match_id, $vote_team_id)";
        
$updateQuery = "UPDATE user_vote_master SET teamid = $vote_team_id 
                where username = '$username' and matchid = $match_id";

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
                
$sql = "";

$query = mysqli_query($GLOBALS['con'],"SELECT * FROM user_vote_master where lower(username) = lower('$username') AND matchid = $match_id");
$row = mysqli_fetch_array($query,MYSQLI_BOTH);

if(!empty($row['username']))
    $sql = $updateQuery;
else
    $sql = $insertQuery;
    

if (mysqli_query($GLOBALS['con'],$sql)) 
    echo "Your Vote has been Registered. Best Of Luck!";
else
    echo "Error: " . $sql . "<br>" . $con->error;

$con->close();

 ?>
