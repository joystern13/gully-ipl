CREATE DEFINER=`root`@`localhost` PROCEDURE `updateMatchResult`(matchId_ INT,teamId_ INT,description varchar(500))
BEGIN
DECLARE losingCount int;
    DECLARE winningCount int;
    DECLARE winningPoints double;
    DECLARE losingPoints double;
    DECLARE code CHAR(5) DEFAULT '00000';
    DECLARE msg TEXT;
    DECLARE rows INT;
    DECLARE result TEXT;
    
    DECLARE CONTINUE HANDLER FOR SQLEXCEPTION
    BEGIN
      GET DIAGNOSTICS CONDITION 1
        code = RETURNED_SQLSTATE, msg = MESSAGE_TEXT;
    END;
    
    SET SQL_SAFE_UPDATES=0;
    
    UPDATE match_master 
    SET 
        winner_team_id = teamId_,
        match_status = 'COMPLETED',
        result_desc = description
    WHERE
        match_id = matchId_;
    
    SELECT 
        COUNT(1)
    INTO losingCount FROM
        user_vote_master
    WHERE
        matchid = matchId_ AND teamid <> teamId_;
      
    SELECT 
        COUNT(1)
    INTO winningCount FROM
        user_vote_master
    WHERE
        matchid = matchId_ AND teamid = teamId_;
    
    if winningCount = 0 OR losingCount = 0 then
        set winningPoints = 0;
        set losingPoints = 0;
    else
        set winningPoints = losingCount/winningCount;
        set losingPoints = -1;
    end if;
    
    
    UPDATE user_vote_master 
    SET 
        points = winningPoints
    WHERE
        matchid = matchId_ AND teamid = teamId_;
    
    UPDATE user_vote_master 
    SET 
        points = losingPoints
    WHERE
        matchid = matchId_ AND teamid <> teamId_;
    
    IF code = '00000' THEN
        GET DIAGNOSTICS rows = ROW_COUNT;
        SET result = CONCAT('insert succeeded, row count = ',rows);
    ELSE
        SET result = CONCAT('insert failed, error = ',code,', message = ',msg);
    END IF;
    
      -- Say what happened
    SELECT result;
END