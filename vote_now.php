<?php
   session_start();
   if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
     header ("Location: login.php");
   }
   include("php/config.php");
   $db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
   
   function getFirstName(){
           $sql = "SELECT FirstName FROM user_data where username='".$_SESSION['username']."'";
           $query = mysqli_query($GLOBALS['con'],$sql);
           $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
           return $row['FirstName'];
   }
   function getWinLoss($teamId)
   {
        $result = "";
        $sql = "SELECT (CASE WHEN winner_team_id = ".$teamId." then 'W' ELSE 'L' END) as win_loss "
                . "FROM match_master WHERE (team1_id = ".$teamId." or team2_id = ".$teamId.") and match_status = 'COMPLETED' order by match_id";
        $query = mysqli_query($GLOBALS['con'],$sql);
        while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
            if ($row['win_loss'] == "W")
                $result .= "<img src='images/green.png' class='imgClass'>";
                else if ($row['win_loss'] == "L")
                    $result .= "<img src='images/red.jpg' class='imgClass'>";
                else
                    $result .= "<img src='images/black.png' class='imgClass'>";
        }
        $result .= "";
        return $result;
   }
   function getMatchInfo(){
     $sql = "select a.match_id,t1.team_id t1_id,t1.team_name t1_name,t1.logo_path t1_logo_path,t2.team_id t2_id,t2.team_name t2_name,t2.logo_path t2_logo_path,v.teamid voted_team, CASE WHEN convert_tz(now(),@@session.time_zone,'+05:30') > DATE_SUB(match_datetime, INTERVAL 1 HOUR) THEN 0 ELSE 1 END voting_allowed,
                TIMESTAMPDIFF(SECOND, convert_tz(now(),@@session.time_zone,'+05:30'), DATE_SUB(match_datetime, INTERVAL 1 HOUR)) secsToGo
              from match_master a left join user_vote_master v
              on a.match_id = v.matchid and v.username = '".$_SESSION['username']."', (select * from team_master b) t1, (select * from team_master b) t2
              where a.team1_id = t1.team_id
              and a.team2_id = t2.team_id
              and a.match_status <> 'COMPLETED'
              order by a.match_id asc";
     $query = mysqli_query($GLOBALS['con'],$sql);
     $i = 0;
     $result = "";
     $isClockAdded=false;
     while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
       $i++;
       $player_query1 = mysqli_query($GLOBALS['con'],"select b.player_name from team_master a, player_master b where a.team_id=b.team_id and a.team_id=".$row['t1_id']);
       $player_query2 = mysqli_query($GLOBALS['con'],"select b.player_name from team_master a, player_master b where a.team_id=b.team_id and a.team_id=".$row['t2_id']);
       $result .= "<div class=\"demo-cards mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col\">";
       if(!$isClockAdded && $row['voting_allowed']=="1")
       {
            $result .= "<script type='text/javascript'>
		                  var clock;
		                  $(document).ready(function() {
		                      var clock;

                    			clock = $('.clock').FlipClock({
                    		        clockFace: 'HourlyCounter',
                    		        autoStart: false,
                    		        callbacks: {
                    		        	stop: function() {
                    		        		$('#clockDiv').html('<b>Voting Gates Closed!</b>');
                    		        	}
                    		        }
                    		    });
                    				    
                    		    clock.setTime(". $row['secsToGo'].");
                    		    clock.setCountdown(true);
                    		    clock.start();
                    
                    		});
                	</script>
            <div id='clockDiv'><table width='100%' border='0'><tr><td><div class='clock'></div><div class='message'></div></td></tr></table></div>";
            $isClockAdded=true;
       }
       else if($row['voting_allowed']=="0"){
        $result .= "<div style='text-align: center;'><b>Voting Gates Closed!</b></div>";
       }
       $result .= "<div class=\"cc-selector\" style=\"text-align: center; margin: auto;\">
                     <div>
                     <table style=\"text-align: center; margin: auto;\">
                        <td align='center'><input id=\"". $row['match_id']."_".$row['t1_id'] ."\" type=\"radio\" name=\"". $row['match_id'] ."\" value=\"". $row['t1_id'] ."\"";
                        if($row['t1_id']==$row['voted_team']) 
                            $result .= " checked=\"checked\"";
                        else if($row['t1_id']=="99" || $row['t2_id']=="99" || $row['voting_allowed']=="0")
                            $result .= " disabled=\"disabled\"";
                        $result .= "/>
                        <label class=\"drinkcard-cc\" for=\"". $row['match_id']."_".$row['t1_id'] ."\" style=\"background-position: center; background-image: url('" .$row['t1_logo_path'] ."');\"></label></br>".getWinLoss($row['t1_id'])."</td><td width=\"50%\">
                        <label style=\"text-align: center; position: relative;\">" .getMatchDetails($row['match_id']) ."</label></td>
                        <td align='center'>
                        <input id=\"". $row['match_id']."_". $row['t2_id'] ."\" type=\"radio\" name=\"". $row['match_id'] ."\" value=\"". $row['t2_id'] ."\"";
                        if($row['t2_id']==$row['voted_team']) $result .= " checked=\"checked\"";
                        else if($row['t1_id']=="99" || $row['t2_id']=="99" || $row['voting_allowed']=="0") $result .= " disabled=\"disabled\"";
                        $result .= "/>
                        <label class=\"drinkcard-cc\" for=\"". $row['match_id']."_".$row['t2_id'] ."\" style=\"background-position: center; background-image: url('" .$row['t2_logo_path'] ."');\"></label></br>".getWinLoss($row['t2_id'])."</td>
                      </table>
                     </div>
                  </div>";
                  if($row['t1_id']!="99" && $row['t2_id']!="99" && $row['voting_allowed']=="1"){
                  $result .= "<div class=\"mdl-layout-spacer\">
                  </div>
                  <div class=\"mdl-card__actions mdl-card--border\" style=\"text-align: center;\">
                     <a id=\"btn_".$row['match_id']."\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" onclick=\"vote(".$row['match_id'].");\">";
                     if (is_null($row['voted_team'])) $result .= "Cast Vote"; else $result .= "Update Vote";
                     $result .= "</a><a id=\"del_".$row['match_id']."\" class=\"mdl-button mdl-button--colored mdl-js-button mdl-js-ripple-effect\" onclick=\"del_vote(".$row['match_id'].");\"";
                     if (is_null($row['voted_team'])) $result .= " style=\"display: none;\"";
                     else $result .= "style=\"display:true\"";
                      $result .= ">Delete Vote</a>";
                  $result .= "</div>";}
                  $result .= "<div class=\"mdl-layout-spacer\">
                  </div>
                    <div class=\"mdl-card__actions mdl-card--border\" style=\"text-align: center;\">
                    <div class=\"expansion-panel list-group-item\">
                      <a aria-controls=\"cp_".$row['match_id']."\" aria-expanded=\"false\" class=\"expansion-panel-toggler collapsed\" data-toggle=\"collapse\" href=\"#cp_".$row['match_id']."\" id=\"ep_".$row['match_id']."\" role=\"tab\">
                        <div class=\"expansion-panel-icon\">
                          <div class=\"collapsed-show\">View Players</div>
                          <div class=\"collapsed-hide\">Hide Players</div>
                        </div>
                        <div class=\"expansion-panel-icon ml-md text-black-secondary\">
                          <i class=\"collapsed-show material-icons\">keyboard_arrow_down</i>
                          <i class=\"collapsed-hide material-icons\">keyboard_arrow_up</i>
                        </div>
                      </a>
                      <div aria-labelledby=\"ep_".$row['match_id']."\" class=\"collapse\" data-parent=\"#accordionOne\" id=\"cp_".$row['match_id']."\" role=\"tabpanel\">
                        <div class=\"expansion-panel-body mdl-typography--body-1\" style=\"align: centre;\">
                          <table align='center' cellpadding=\"0\" cellspacing=\"0\" style=\"width: 100%;\">
                          <tr>
                          <td width=\"35%\" align='center' style=\"vertical-align:top;\">";
                          while ($row1 = mysqli_fetch_array($player_query1, MYSQLI_ASSOC)){
                              $result .= $row1['player_name'] ."<br>";
                          }
                          $result .= "</td>
                          <td width=\"30%\"></td>
                          <td width=\"35%\" align='center' style=\"vertical-align:top;\">";
                          while ($row2 = mysqli_fetch_array($player_query2, MYSQLI_ASSOC)){
                              $result .= $row2['player_name'] ."<br>";
                          }
                          $result .= "</td>
                          </tr>
                          </table>
                        </div>
                      </div>
                    </div>
                    </div>
                    </div>";
     } 
     if ($i == 0){
       $result .= "<p>No rankings yet.</p>";
     }
     return $result;
   }

   function getMatchDetails($match_id){
    $sql = "SELECT match_description,
                DATE_FORMAT(match_datetime, '%d %b %Y %h:%i %p') match_time,
                b.venue_name_long, b.venue_city
            FROM match_master a, venue_master b
            WHERE a.match_id = " .$match_id ."
            AND a.venue_id = b.venue_id";
    $query = mysqli_query($GLOBALS['con'],$sql);
    $row = mysqli_fetch_array($query,MYSQLI_ASSOC);
    return "<b>".$row['match_description'] ."</b></br>" .$row['match_time'] ." IST</br>" .$row['venue_name_long'] .",</br>".$row['venue_city'];
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
      <title>Gully IPL - Vote Now</title>
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
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
      <link rel="stylesheet" href="css/material.min2.css"/>
      <link rel="stylesheet" href="css/bootstrap.min.css">
      <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.cyan-light_blue.min.css"/>
      <link rel="stylesheet" href="css/styles.css"/>
      <link rel="stylesheet" href="css/radio_css.css"/>
      <link rel="stylesheet" href="clock/flipclock.css"/>
      <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
		<script src="clock/flipclock.js"></script>
      <style>
         .imgClass {
        	width: 10px;
        	height: 10px;
        }
        @media screen and (max-width:760px){
                .imgClass {
                	width: 8px;
                	height: 8px;
                }                    
            }
      </style>
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
      <!-- <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script> -->
      <script lang="javascript">
         function vote(rdbName)
         {
          //alert("rdbName : " + rdbName);
             var teamid=$("input[name='"+rdbName+"']:checked").val();
             var snackbarContainer = document.querySelector('#demo-toast-example');
             
             $.ajax({
             type: "POST",
             url: "php/insert_data.php",
             data: {match_id:rdbName,teamid:teamid},
             dataType: "text",
             success: function(data) {
              //$("#message").html(data);
              'use strict';
              var msg = {message: data};
              if(String(data).indexOf("Please select the team")==-1){
                $("#btn_"+rdbName).html("UPDATE VOTE");
                document.getElementById("del_"+rdbName).style.display = "inline";
              }
              snackbarContainer.MaterialSnackbar.showSnackbar(msg);
             },
             error: function(err) {
             //alert("error : "+err);
             'use strict';
              var msg = {message: err};
              snackbarContainer.MaterialSnackbar.showSnackbar(msg);
             }
         });
         }
         function del_vote(rdbName)
         {
             var snackbarContainer = document.querySelector('#demo-toast-example');
             
             $.ajax({
             type: "POST",
             url: "php/delete_data.php",
             data: {match_id:rdbName},
             dataType: "text",
             success: function(data) {
              //$("#message").html(data);
              $("input[name='"+rdbName+"']").prop("checked", false);
              $("#btn_"+rdbName).html("CAST VOTE");
              document.getElementById("del_"+rdbName).style.display = "none";
              'use strict';
              var msg = {message: data};
              snackbarContainer.MaterialSnackbar.showSnackbar(msg);
             },
             error: function(err) {
             //alert("error : "+err);
             'use strict';
              var msg = {message: err};
              snackbarContainer.MaterialSnackbar.showSnackbar(msg);
             }
         });
        }
      </script>
      
   </head>
   <body>
      <!--?php include_once("php/analyticsstart.php") ?-->
      <div class="demo-layout mdl-layout mdl-js-layout mdl-layout--fixed-drawer mdl-layout--fixed-header">
         <header class="demo-header mdl-layout__header mdl-color--grey-100 mdl-color-text--grey-600">
            <div class="mdl-layout__header-row">
               <span class="mdl-layout-title">Vote Now</span>
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
                  <div class="mdl-card__supporting-text" id="message">Start voting now!</br>Click on the team that you bet to win the match and click the 'CAST VOTE' button.</br>Your vote can be updated any number of times until 1 hour before the match starts using 'UPDATE VOTE' button.</div>
                  
               </div>
               <?php echo getMatchInfo(); ?>
               <div id="demo-toast-example" class="mdl-js-snackbar mdl-snackbar">
                 <div class="mdl-snackbar__text"></div>
                 <button class="mdl-snackbar__action" type="button"></button>
               </div>
            </div>
         </main>
      </div>
      <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
   </body>
</html>