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
    	$query = mysqli_query($GLOBALS['con'],$sql);
    	echo "Match complete";
    } elseif ($json->{'matchInfo'}->{'matchState'} == "L") {
    	//$sql = "UPDATE match_master SET match_status='IN PROGRESS' where match_id=".$json->{'matchId'}->{'id'};
    	if (isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[0]) && isset($json->{'innings'}[0])){
	    	echo $json->{'matchInfo'}->{'teams'}[0]->{'team'}->{'abbreviation'}." - ".$json->{'innings'}[0]->{'scorecard'}->{'runs'}."/".$json->{'innings'}[0]->{'scorecard'}->{'wkts'}." (".$json->{'innings'}[0]->{'overProgress'}." ov)<br><br>";
	    }
	    elseif(isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[0])){
	    	echo $json->{'matchInfo'}->{'teams'}[0]->{'team'}->{'abbreviation'}." - 0/0 (0.0 ov)<br><br>";
	    }
	    if (isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[1]) && isset($json->{'innings'}[1])){
	    	echo $json->{'matchInfo'}->{'teams'}[1]->{'team'}->{'abbreviation'}." - ".$json->{'innings'}[1]->{'scorecard'}->{'runs'}."/".$json->{'innings'}[1]->{'scorecard'}->{'wkts'}." (".$json->{'innings'}[1]->{'overProgress'}." ov)<br><br><br>";
	    }
	    echo str_replace(", who"," won the toss and ",$json->{'matchInfo'}->{'additionalInfo'}->{'toss.elected'});

    }

?>