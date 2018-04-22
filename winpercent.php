<?php
 session_start();
if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header ("Location: login.php");
}
include("php/config.php");
$db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
 
function getBarChart()
{
    $sql = " SELECT 
                username,
                FirstName,
                LastName,
                COALESCE(ROUND((winVotes / totalVotes) * 100, 2),0) winPer
            FROM
                (SELECT 
                    (SELECT 
                                COUNT(c.matchid)
                            FROM
                                user_vote_master c, match_master b
                            WHERE
                                c.matchid = b.match_id
                                    AND match_status = 'COMPLETED'
                                    AND c.username = u.username
                            GROUP BY c.username) totalVotes,
                        (SELECT 
                                COUNT(c.matchid)
                            FROM
                                user_vote_master c, match_master b
                            WHERE
                                c.matchid = b.match_id
                                    AND c.teamid = b.winner_team_id
                                    AND match_status = 'COMPLETED'
                                    AND c.username = u.username
                            GROUP BY c.username) winVotes,
                        username,
                        u.firstname FirstName,
                        u.lastname LastName
                FROM
                    user_data u
                WHERE
                    active = 1) main
            ORDER BY winPer desc ";
    
    $query = mysqli_query($GLOBALS['con'],$sql);
    $result = "";
    $totalVotes = 0;
    $correctVotes = 0;
    $winPercentage = 0;
    $name = "";
    
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
        $name = $row['FirstName'] . " " . substr($row['LastName'], 0, 1);
        $winPercentage = $row['winPer'];
        $username = $row['username'];
        
        $result .= "<div class='bodyDiv'>
                    <div class='side right'><div>".$name."&nbsp;</div></div>
            		<div class='middle'><div class='bar-container'><div style='width: ".$winPercentage."%; height: 18px; background-color: #2196F3;'></div></div></div>
            		<div class='side left'><div>&nbsp;".$winPercentage."%</div></div>
                    </div>";
        $result .= "<div class='bodyDiv'>
                    <div class='side right'><div>&nbsp;</div></div>
            		<div class='middleBottom'>".getWinLoss($username)."</div>
            		<div class='side right'><div>&nbsp;</div></div>
                    </div>";
    }
    
    return $result;
}
function getWinLoss($username)
{
    //$result = "<table width='100%'><tr>";
    $result = "";
    
    $sql = "select win_loss, matchid
            from
            (SELECT
                (CASE
                    WHEN a.teamid = b.winner_team_id THEN 'W'
                    WHEN a.teamid <> b.winner_team_id THEN 'L'
                    ELSE '-'
                END) AS win_loss,
                b.match_id matchid
            FROM
                user_vote_master a
                    RIGHT OUTER JOIN
                match_master b ON (a.matchid = b.match_id AND a.username = '".$username."')
            WHERE
                b.match_status = 'COMPLETED'
            order by b.match_id desc
            ) wins
            order by matchid";
    
    $query = mysqli_query($GLOBALS['con'], $sql);
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        if ($row['win_loss'] == "W")
            $result .= "&nbsp;<img src='images/green.png' class='imgClass'>";
            else if ($row['win_loss'] == "L")
                $result .= "&nbsp;<img src='images/red.jpg' class='imgClass'>";
                else
                    $result .= "&nbsp;<img src='images/black.png' class='imgClass'>";
    }
    //$result .= "</tr></table>";
    return $result;
}
?>

<!doctype html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta http-equiv="X-UA-Compatible" content="IE=edge">
<meta name="description"
	content="A front-end template that helps you build fast, modern mobile web apps.">
<meta name="viewport"
	content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
<title>Gully IPL - Homepage</title>
<!-- Add to homescreen for Chrome on Android -->
<meta name="mobile-web-app-capable" content="yes">
<link rel="icon" sizes="192x192" href="images/android-desktop.png">
<!-- Add to homescreen for Safari on iOS -->
<meta name="apple-mobile-web-app-capable" content="yes">
<meta name="apple-mobile-web-app-status-bar-style" content="black">
<meta name="apple-mobile-web-app-title" content="Material Design Lite">
<link rel="apple-touch-icon-precomposed" href="images/ios-desktop.png">
<!-- Tile icon for Win8 (144x144 + tile color) -->
<meta name="msapplication-TileImage"
	content="images/touch/ms-touch-icon-144x144-precomposed.png">
<meta name="msapplication-TileColor" content="#3372DF">
<link rel="shortcut icon" href="images/favicon.png">
<!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
<!--
         <link rel="canonical" href="http://www.example.com/">
         -->
<link rel="stylesheet"
	href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
<link rel="stylesheet"
	href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="css/material.cyan-light_blue.min.css">
<link rel="stylesheet" href="css/styles.css">

<link rel="stylesheet" href="css/bar.css" />


</head>
<body>
	<!--?php include_once("php/analyticsstart.php") ?-->
	<div
		class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header
			class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row" style="background-color: #e5e5e5">
				<span class="mdl-layout-title">Winning Stats</span>
			</div>
		</header>
		<div
			class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php include("menu.php"); ?>
         </div>
		<main class="mdl-layout__content mdl-color--grey-100">
		<div class="mdl-grid">
			<div
				class="demo-cards mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
                  <?php echo getBarChart(); ?>
               </div>

		</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>
