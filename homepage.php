<?php
   session_start();
   if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
     header ("Location: login.php");
   }
   include("php/config.php");
   $db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
   
   function getFirstName(){
           $sql = "SELECT FirstName FROM user_data where username='"
           .$_SESSION['username']
           ."'";
           $query = mysqli_query($GLOBALS['con'],$sql);
           $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
           return $row['FirstName'];
   }
   function getLeaderBoards(){
     $i = 0;
     $result = "";
     $query = mysqli_query($GLOBALS['con'],"select lower(a.FirstName) fname,lower(a.LastName) lname, sum(COALESCE(b.Points,0)) Points from user_data a, user_vote_master b where a.username = b.username group by a.username order by sum(COALESCE(b.Points,0)) desc, a.Firstname asc limit 3");
     while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
       $i++;
       $fName = $row['fname'];
       $lName = $row['lname'];
       if($fName != null)
            $fName = ucwords($fName);
       if($lName != null)
            $lName = ucwords($lName);
       //$result .= "<p>" .$i .". " .$row['FirstName'] ." - " .$row['Points'] ." points</p>";
       $result .= "<li class=\"mdl-list__item\">
       <span class=\"mdl-list__item-primary-content\">" .$i .". "
       .$fName." " .$lName." (" .$row['Points'] ." points)
       </span>
       </li>";
     } 
     if ($i == 0){
       $result .= "<p>No rankings yet.</p>";
     }
     return $result;
   }
   function getPrevWinners(){
     $i = 0;
     $result = "";
     $query = mysqli_query($GLOBALS['con'],"SELECT 
                                                 c.firstname, c.lastname
                                              FROM
                                                  match_master a,
                                                  user_vote_master b,
                                                  user_data c
                                              WHERE
                                                  a.match_status = 'COMPLETED'
                                                      AND a.match_id = b.matchid
                                                      AND b.username = c.username
                                                      AND a.winner_team_id = b.teamid
                                                      AND b.matchid = (SELECT 
                                                          MAX(match_id)
                                                      FROM
                                                          match_master
                                                      WHERE
                                                          match_status = 'COMPLETED'
                                                              AND a.winner_team_id IS NOT NULL)
                                                      LIMIT 3;");
     while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
       $i++;
                   //$result .= "<p>" .$i .". " .$row['firstname'] ." - " .$row['lastname'] ." points</p>";
       $result .= "<li class=\"mdl-list__item\">
       <span class=\"mdl-list__item-primary-content\">
       <i class=\"material-icons mdl-list__item-icon\">euro_symbol</i>"
       .$row['firstname'] ." " .$row['lastname']
       ."</span>
       </li>";
     } 
     if ($i == 0){
       $result .= "<p>No rankings yet.</p>";
     }
     return $result;
   }
   function getRecentResults(){
     $i = 0;
     $result = "";
     $query = mysqli_query($GLOBALS['con'],"SELECT 
                                                (SELECT 
                                                        team_name
                                                    FROM
                                                        team_master
                                                    WHERE
                                                        team_id = team1_id) team1,
                                                (SELECT 
                                                        team_name
                                                    FROM
                                                        team_master
                                                    WHERE
                                                        team_id = team2_id) team2,
                                                result_desc
                                            FROM
                                                match_master
                                            WHERE
                                                match_status = 'COMPLETED'
                                                    AND winner_team_id IS NOT NULL
                                            ORDER BY match_id DESC
                                            LIMIT 3;");
     while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
       $i++;
                   //$result .= "<p>" .$i .". " .$row['firstname'] ." - " .$row['lastname'] ." points</p>";
       $result .= "<li class=\"mdl-list__item\">
       <span class=\"mdl-list__item-primary-content\">
       <i class=\"material-icons mdl-list__item-icon\">grade</i>"
       .$row['team1'] ." vs " .$row['team2']
       ."</br>".$row['result_desc'] ."</span>
       </li>";
     } 
     if ($i == 0){
       $result .= "<p>No rankings yet.</p>";
     }
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
      <meta name="description" content="A front-end template that helps you build fast, modern mobile web apps.">
      <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0">
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
      <meta name="msapplication-TileImage" content="images/touch/ms-touch-icon-144x144-precomposed.png">
      <meta name="msapplication-TileColor" content="#3372DF">
      <link rel="shortcut icon" href="images/favicon.png">
      <!-- SEO: If your mobile URL is different from the desktop URL, add a canonical link to the desktop page https://developers.google.com/webmasters/smartphone-sites/feature-phones -->
      <!--
         <link rel="canonical" href="http://www.example.com/">
         -->
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons">
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
   </head>
   <body>
      <!--?php include_once("php/analyticsstart.php") ?-->
      <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
         <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
            <div class="mdl-layout__header-row" style="background-color: #e5e5e5">
               <span class="mdl-layout-title">Home</span>
            </div>
         </header>
         <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <?php include("menu.php"); ?>
         </div>
         <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-grid">
               <div class="demo-cards mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
                  <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-800">
                     <h2 class="mdl-card__title-text" style=""><b>Hi <?php echo getFirstName(); ?>! Welcome to Gully IPL</b></h2>
                  </div>
                  <div class="mdl-card__supporting-text">
                     This site offers you to predict the winner of the IPL matches and defeat your opponents.
                     Have you got what it takes to be the best of the best? Start voting now!
                     <div>
                        <h3><b>Rules:</b></h3>
                        1. Each correct guess will earn you 1 point.
                        <br>2. Each incorrect guess will cost you 1 point.
                     </div>
                  </div>
                  <div class="mdl-layout-spacer"></div>
                  <div class="mdl-card__actions mdl-card--border">
                     <a class="mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect" href="vote_now.php">
                     Vote Now
                     </a>
                  </div>
               </div>
               <div class="mdl-grid">
               <div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
                  <div class="demo-updates1 mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                     <div class="mdl-card__title mdl-color--green-900">
                        <h2 class="mdl-card__title-text">Leaderboards</h2>
                     </div>
                     <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-600">
                        <ul class="demo-list-icon mdl-list">
                           <?php echo getLeaderBoards(); ?>
                        </ul>
                     </div>
                     <div class="mdl-card__actions mdl-card--border">
                        <a href="rankings.php" class="mdl-button mdl-js-button mdl-js-ripple-effect">See Full List</a>
                     </div>
                  </div>
               </div>
               <div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
                  <div class="demo-updates2 mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                     <div class="mdl-card__title mdl-color--yellow-800">
                        <h2 class="mdl-card__title-text">Previous Match Winners</h2>
                     </div>
                     <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-600">
                        <ul class="demo-list-icon mdl-list">
                           <?php echo getPrevWinners(); ?>
                        </ul>
                     </div>
                     <div class="mdl-card__actions mdl-card--border">
                        <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect">See Full List</a>
                     </div>
                  </div>
               </div>
               <div class="mdl-cell mdl-cell--4-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
                  <div class="demo-updates3 mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
                     <div class="mdl-card__title mdl-color--blue-700">
                        <h2 class="mdl-card__title-text">Recent Results</h2>
                     </div>
                     <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-600">
                        <ul class="demo-list-icon mdl-list">
                           <?php echo getRecentResults(); ?>
                        </ul>
                     </div>
                     <div class="mdl-card__actions mdl-card--border">
                        <a href="#" class="mdl-button mdl-js-button mdl-js-ripple-effect">See Full List</a>
                     </div>
                  </div>
               </div>
             </div>
            </div>
         </main>
      </div>
      <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
   </body>
</html>