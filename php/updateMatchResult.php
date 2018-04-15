<?php
	session_start();
	$jsonData = rawurldecode($_POST['json']);
	
    $json = json_decode($jsonData);    

    if(!(isset($json->{'matchInfo'})) || empty($json->{'matchInfo'})){
		return;
	}

	include("config.php");
	$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

    if($json->{'matchInfo'}->{'matchState'} == "C"){
    	$sql = "CALL updateMatchResult(".$json->{'matchId'}->{'id'}.", (SELECT CASE WHEN '".$json->{'matchInfo'}->{'matchStatus'}->{'outcome'}."'='A' then team1_id WHEN '".$json->{'matchInfo'}->{'matchStatus'}->{'outcome'}."'='B' then team2_id ELSE 99 END FROM match_master WHERE match_id=".$json->{'matchId'}->{'id'}."),'".$json->{'matchInfo'}->{'matchStatus'}->{'text'}."')";
    } elseif ($json->{'matchInfo'}->{'matchState'} == "L") {
    	$sql = "UPDATE match_master SET match_status='IN PROGRESS' where match_id=".$json->{'matchId'}->{'id'};
    }
    //$sql .= "where match_id=".$json->{'matchId'}->{'id'};
    if ($query = mysqli_query($GLOBALS['con'],$sql))
    	"Success";
    else
    	"Error:"+$con->error;
?>