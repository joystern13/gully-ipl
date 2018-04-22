<?php
session_start();
if (! (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header("Location: login.php");
}
include ("php/config.php");
$db = mysqli_select_db($con, DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

function getLeaderBoards()
{
    $i = 0;
    $result = "";
    $me = $_SESSION['username'];
    $query = mysqli_query($GLOBALS['con'], "select a.FirstName,a.LastName, sum(COALESCE(b.Points,0)) Points, a.username username from user_data a, user_vote_master b where a.username = b.username and b.points is not null group by a.username order by sum(COALESCE(b.Points,0)) desc, a.Firstname asc, a.LastName asc");
    $result = "<table cellspacing=6><thead><th align='left'>Rank</th><th align='left'>Player</th><th align='left'>Points</th><th align='left'>Last 10 matches</th></thead>";
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        $i ++;
        
        if($me == $row['username'])
        {
            $result .= "<tr class=\"mdl-list__item-primary-content\">
                        <td class='voterFont' align='right'><b><i><u>" . $i . ".</u></i></b></td>
                        <td class='voterFont'><b><i><u>Me</u></i></b></td>
                        <td class='voterFont' align='right'><b><i><u>" . $row['Points'] . "</u></i></b></td>
                        <td>" . getWinLoss($row['username']) . "</td>
                    </tr>";
        }
        else 
        {
            $result .= "<tr class=\"mdl-list__item-primary-content\">
                        <td class='voterFont' align='right'>" . $i . ".</td>
                        <td class='voterFont'>" . $row['FirstName'] . " " . substr($row['LastName'], 0, 1) . "</td>
                        <td class='voterFont' align='right'>" . $row['Points'] . "</td>
                        <td>" . getWinLoss($row['username']) . "</td>
                    </tr>";
        }
    }
    $result .= "</table>";
    if ($i == 0) {
        $result .= "<p>No rankings yet.</p>";
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
            limit 10) wins
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
<!--
   Material Design Lite
   Copyright 2015 Google Inc. All rights reserved.
   
   Licensed under the Apache License, Version 2.0 (the "License");
   you may not use this file except in compliance with the License.
   You may obtain a copy of the License at
   
       https://www.apache.org/licenses/LICENSE-2.0
   
   Unless required by applicable law or agreed to in writing, software
   distributed under the License is distributed on an "AS IS" BASIS,
   WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
   See the License for the specific language governing permissions and
   limitations under the License
   -->
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
<style>
#view-source {
	position: fixed;
	display: block;
	right: 0;
	bottom: 0;
	margin-right: 40px;
	margin-bottom: 40px;
	z-index: 900;
}
</style>
<style>
.voterFont {
    font-size: 14px;
    font-weight: 400;
    letter-spacing: 0
    }
.imgClass {
	width: 10px;
	height: 10px;
}

@media screen and (max-width:760px){
            .voterFont {
                font-size: 12px;
                font-weight: 400;
                letter-spacing: 0
                }
                .imgClass {
                	width: 8px;
                	height: 8px;
                }                    
            }
</style>
</head>
<body>
	<!--?php include_once("php/analyticsstart.php") ?-->
	<div
		class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header
			class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row" style="background-color: #e5e5e5">
				<span class="mdl-layout-title">Rankings</span>
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
                  <?php echo getLeaderBoards(); ?>
               </div>

		</div>
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>