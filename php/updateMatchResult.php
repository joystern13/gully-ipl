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

    	if ($json->{'matchInfo'}->{'matchStatus'}->{'outcome'} == "A") {
    		$team_code = $json->{'matchInfo'}->{'teams'}[0]->{'team'}->{'abbreviation'};
    	}
    	elseif ($json->{'matchInfo'}->{'matchStatus'}->{'outcome'} == "B") {
    		$team_code = $json->{'matchInfo'}->{'teams'}[1]->{'team'}->{'abbreviation'};
    	}
    	else{
    		$team_code = "TBC";
    	}
    	$team_query = mysqli_query($GLOBALS['con'],"select team_id from team_master where team_code='$team_code'");
    	$row_t = mysqli_fetch_array($team_query, MYSQLI_ASSOC);

    	$sql = "CALL updateMatchResult(".$json->{'matchId'}->{'id'}.", ".$row_t['team_id'].",'".$json->{'matchInfo'}->{'matchStatus'}->{'text'}."')";
    	$query = mysqli_query($GLOBALS['con'],$sql);
    	echo "Match complete";
    } elseif ($json->{'matchInfo'}->{'matchState'} == "L") {
    	//$sql = "UPDATE match_master SET match_status='IN PROGRESS' where match_id=".$json->{'matchId'}->{'id'};
    	if (isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[0]) && isset($json->{'innings'}[0])){
	    	echo $json->{'matchInfo'}->{'teams'}[$json->{'matchInfo'}->{'battingOrder'}[0]]->{'team'}->{'abbreviation'}." - ".$json->{'innings'}[0]->{'scorecard'}->{'runs'}."/".$json->{'innings'}[0]->{'scorecard'}->{'wkts'}." (".$json->{'innings'}[0]->{'overProgress'}." ov)<br><br>";
	    }
	    elseif(isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[0])){
	    	echo $json->{'matchInfo'}->{'teams'}[0]->{'team'}->{'abbreviation'}." - 0/0 (0.0 ov)<br><br>";
	    }
	    if (isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[1]) && isset($json->{'innings'}[1])){
	    	echo $json->{'matchInfo'}->{'teams'}[$json->{'matchInfo'}->{'battingOrder'}[1]]->{'team'}->{'abbreviation'}." - ".$json->{'innings'}[1]->{'scorecard'}->{'runs'}."/".$json->{'innings'}[1]->{'scorecard'}->{'wkts'}." (".$json->{'innings'}[1]->{'overProgress'}." ov)<br><br><br>";
	    }
	    echo str_replace(", who"," won the toss and ",$json->{'matchInfo'}->{'additionalInfo'}->{'toss.elected'});

    }

?>