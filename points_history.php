<?php
session_start();
if (! (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
    header("Location: login.php");
}
include ("php/config.php");
$db = mysqli_select_db($con, DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

function getCompletedMatches(){
	$sql = "select match_description from match_master where match_status='COMPLETED' order by match_id";
	$query = mysqli_query($GLOBALS['con'],$sql);
	$out = "";
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$out .= "<td>".$row['match_description']."</td>";
	}
	return $out;
}

function getUserPoints(){
	$sql = "select firstname,lastname,username from user_data where active=1 order by firstname,lastname";
	$query = mysqli_query($GLOBALS['con'],$sql);
	$out = "<tr>";
	
	while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
		$out .= "<td class=\"mdl-data-table__cell--non-numeric\">".$row['firstname']." ".$row['lastname']."</td>";
		$user = $row['username'];
		$points_sql = "select coalesce(a.points,0) points from user_vote_master a right outer join match_master b on a.matchid=b.match_id and username='$user' where b.match_status='COMPLETED' order by match_id";
		$points_query = mysqli_query($GLOBALS['con'],$points_sql);
		while ($points_row = mysqli_fetch_array($points_query, MYSQLI_ASSOC)) {
			$out .= "<td>".$points_row['points']."</td>";
		}
		$out .= "</tr>";
	}
	return $out;
}

function getPointsHistory(){
    $result = "<script>
        var config = {
            type: 'line',
            data: {
                labels: [";
    $matches_sql = "select match_description from match_master where match_status='COMPLETED' order by match_id";
    $query = mysqli_query($GLOBALS['con'], $matches_sql);
    $i = 0;
    while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)) {
        if($i==0)
            $result .= "'".$row['match_description']."'";
        else
            $result .= ", '".$row['match_description']."'";
        $i++;
    }
    $result .= "],
                datasets: [";
	
	$user_sql = "select firstname,lastname,username,colour from user_data where active=1 order by firstname,lastname";
	$user_query = mysqli_query($GLOBALS['con'],$user_sql);
	$j=0;
	while ($user_row = mysqli_fetch_array($user_query, MYSQLI_ASSOC)) {
		if($j>0)
			$result .= ",";
		$result .= "{label: '".$user_row['firstname']." ".$user_row['lastname']."',
					backgroundColor: window.chartColors.".$user_row['colour'].",
                    borderColor: window.chartColors.".$user_row['colour'].",
                    data: [";
		$user = $user_row['username'];
		$points_sql = "select coalesce(a.points,0) points from user_vote_master a right outer join match_master b on a.matchid=b.match_id and username='$user' where b.match_status='COMPLETED' order by match_id";
		$points_query = mysqli_query($GLOBALS['con'],$points_sql);
		$points = 0;
		$i = 0;
		while ($points_row = mysqli_fetch_array($points_query, MYSQLI_ASSOC)) {
			$points += (float)$points_row['points'];
			if($i==0)
				$result .= $points;
			else
				$result .= ",".$points;
			$i++;
		}
		$result .= "],
                    fill: false,
				}";
		$j++;
	}

        $result .= "]
            },
            options: {
                responsive: true,
                title: {
                    display: true,
                    text: 'Points History'
                },
                tooltips: {
                    mode: 'point',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Match'
                        }
                    }],
                    yAxes: [{
                        display: true,
                        scaleLabel: {
                            display: true,
                            labelString: 'Points'
                        }
                    }]
                }
            }
        };
        window.onload = function() {
            var ctx = document.getElementById('myChart').getContext('2d');
            window.myLine = new Chart(ctx, config);
        };
        var colorNames = Object.keys(window.chartColors);
    </script>";
    echo $result;
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

<link rel="stylesheet"
	href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
<link rel="stylesheet"
	href="https://fonts.googleapis.com/icon?family=Material+Icons">
<link rel="stylesheet" href="css/material.cyan-light_blue.min.css">
<link rel="stylesheet" href="css/styles.css">
</head>
<body>
	<!--?php include_once("php/analyticsstart.php") ?-->
    <script src="scripts/Chart.js"></script>
    <script src="scripts/utils.js"></script>
	<div
		class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
		<header
			class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
			<div class="mdl-layout__header-row" style="background-color: #e5e5e5">
				<span class="mdl-layout-title">Points History</span>
			</div>
		</header>
		<div
			class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php include("menu.php"); ?>
         </div>
		<main class="mdl-layout__content mdl-color--grey-100">
		<table class="mdl-data-table mdl-js-data-table mdl-shadow--2dp">
  <thead>
    <tr>
      <th class="mdl-data-table__cell--non-numeric">User</th>
      <?php echo getCompletedMatches(); ?>
    </tr>
  </thead>
  <tbody>
    <?php echo getUserPoints(); ?>
  </tbody>
</table>
        <!--canvas id="myChart" width="400" height="200" class="chartjs-render-monitor"></canvas>
        <!?php echo getPointsHistory(); ?-->
		</main>
	</div>
	<script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
</body>
</html>