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
	    	echo getScoreSumm("0",$json);
	    	echo "<br><br>";
	    }
	    elseif(isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[0])){
	    	echo "<label style='font-size: 16px;'><b>".$json->{'matchInfo'}->{'teams'}[0]->{'team'}->{'abbreviation'}." - 0/0 (0.0 ov)</b></label><br><br>";
	    }
	    if (isset($json->{'matchInfo'}->{'teams'}) && isset($json->{'matchInfo'}->{'teams'}[1]) && isset($json->{'innings'}[1])){

	    	echo getScoreSumm("1",$json);

	    	echo getRequiredRuns($json);

	    	echo "<br>";
	    	
	    }
	    echo "<span style='padding-left: 15px;'/>".str_replace(", who"," won the toss and ","<label style='font-size: 12px;'>".$json->{'matchInfo'}->{'additionalInfo'}->{'toss.elected'}."</label>");

    }

    function getPlayerSummary($innings,$json){
    	$retval = "";
    	foreach($json->{'innings'}[$innings]->{'scorecard'}->{'battingStats'} as $bat_stat) {
    		if(isset($json->{'currentState'}->{'facingBatsman'}) && $json->{'currentState'}->{'facingBatsman'}!=-1){
			    if($bat_stat->{'playerId'} == $json->{'currentState'}->{'facingBatsman'}){
			    	$runs_1 = $bat_stat->{'r'}."(".$bat_stat->{'b'}.")";
			    }
			}
			if(isset($json->{'currentState'}->{'nonFacingBatsman'}) && $json->{'currentState'}->{'nonFacingBatsman'}!=-1){
			    if($bat_stat->{'playerId'} == $json->{'currentState'}->{'nonFacingBatsman'}){
			    	$runs_2 = $bat_stat->{'r'}."(".$bat_stat->{'b'}.")";
			    }
			}
		}

		foreach($json->{'matchInfo'}->{'teams'}[$json->{'matchInfo'}->{'battingOrder'}[$innings]]->{'players'} as $player) {
		    if(isset($json->{'currentState'}->{'facingBatsman'}) && $json->{'currentState'}->{'facingBatsman'}!=-1){
			    if($player->{'id'} == $json->{'currentState'}->{'facingBatsman'}){
			    	$retval .= "<span style='padding-left: 55px;'/><label style='font-size: 12px;'>".$player->{'shortName'}."* - $runs_1</label><br>";
			    }
			}
			if(isset($json->{'currentState'}->{'nonFacingBatsman'}) && $json->{'currentState'}->{'nonFacingBatsman'}!=-1){
			    if($player->{'id'} == $json->{'currentState'}->{'nonFacingBatsman'}){
			    	$retval .= "<span style='padding-left: 55px;'/><label style='font-size: 12px;'>".$player->{'shortName'}." - $runs_2</label><br>";
			    }
			}
		}

		return $retval;
    }

    function getScoreSumm($innings,$json){
    	$retval = "<span style='padding-left: 15px;'/><img src='images/teams/".$json->{'matchInfo'}->{'teams'}[$json->{'matchInfo'}->{'battingOrder'}[$innings]]->{'team'}->{'abbreviation'}.".png' class='imgClass' width=\"35\"> ";
    	$retval .= "<label style='font-size: 16px;'><b>".$json->{'matchInfo'}->{'teams'}[$json->{'matchInfo'}->{'battingOrder'}[$innings]]->{'team'}->{'abbreviation'}." - ".$json->{'innings'}[$innings]->{'scorecard'}->{'runs'}."/".$json->{'innings'}[$innings]->{'scorecard'}->{'wkts'}." (".$json->{'innings'}[$innings]->{'overProgress'}." ov)</b></label><br>";
		if (($innings=="0" && !isset($json->{'innings'}[1])) || $innings=="1"){
    		$retval .= getPlayerSummary($innings,$json);
    	}

    	return $retval;
    }

    function getRequiredRuns($json){
    	if (!isset($json->{'innings'}[1]->{'rodl'})){
    		$rem_balls = 120 - (intdiv(intval($json->{'innings'}[1]->{'overProgress'})*6, 1) + (fmod($json->{'innings'}[1]->{'overProgress'},1)*10));
    		$req_runs = intval($json->{'innings'}[0]->{'scorecard'}->{'runs'})-intval($json->{'innings'}[1]->{'scorecard'}->{'runs'})+1;
    		if($req_runs<=0){
    			$req_runs = 0;
    			$rrr = "0.0";
    		}
    		else{
    			$rrr = $json->{'currentState'}->{'requiredRunRate'};
    		}
    		return "<span style='padding-left: 15px;'/><b>Need $req_runs runs in $rem_balls balls at $rrr RPO</b><br>";
    	}
    	else{
    		$rem_balls = intval($json->{'innings'}[1]->{'rodl'}->{'overs'})*6 - (intdiv(intval($json->{'innings'}[1]->{'overProgress'})*6, 1) + (fmod($json->{'innings'}[1]->{'overProgress'},1)*10));
    		return "<span style='padding-left: 15px;'/><b>Need ".strval(intval($json->{'innings'}[1]->{'rodl'}->{'target'})-intval($json->{'innings'}[1]->{'scorecard'}->{'runs'})+1)." runs in $rem_balls balls</b><br>";
    	}
    }
?>