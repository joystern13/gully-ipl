<?php
   session_start();
   if (!(isset($_SESSION['loggedin']) && $_SESSION['loggedin'] == true)) {
     header ("Location: login.php");
   }
   include("php/config.php");
   $db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());
   
   
   function getLeaderBoards(){
     $i = 0;
     $result = "";
     $query = mysqli_query($GLOBALS['con'],"select a.FirstName, sum(COALESCE(b.Points,0)) Points from user_data a, user_vote_master b where a.username = b.username group by a.username order by sum(COALESCE(b.Points,0)) desc, a.Firstname asc");
     while ($row = mysqli_fetch_array($query, MYSQLI_ASSOC)){
       $i++;
                   //$result .= "<p>" .$i .". " .$row['FirstName'] ." - " .$row['Points'] ." points</p>";
       $result .= "<li class=\"mdl-list__item\">
       <span class=\"mdl-list__item-primary-content\">" .$i .". "
       .$row['FirstName'] ." (" .$row['Points'] ." points)
       </span>
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
               <span class="mdl-layout-title">Rankings</span>
            </div>
         </header>
         <div class="demo-drawer mdl-layout__drawer mdl-color--blue-grey-900 mdl-color-text--blue-grey-50">
            <header class="demo-drawer-header">
               <img src="<?=$_SESSION['profilepic'] ?>" class="demo-avatar">
               <div class="demo-avatar-dropdown">
                  <span><?=$_SESSION['username'] ?></span>
                  <div class="mdl-layout-spacer"></div>
                  <button id="accbtn" class="mdl-button mdl-js-button mdl-js-ripple-effect mdl-button--icon">
                  <i class="material-icons" role="presentation">arrow_drop_down</i>
                  <span class="visuallyhidden">Accounts</span>
                  </button>
                  <ul class="mdl-menu mdl-menu--bottom-right mdl-js-menu mdl-js-ripple-effect" for="accbtn">
                     <li class="mdl-menu__item">Update Profile Info</li>
                     <li><a class="mdl-menu__item" href="php/signout.php">Sign Out</a></li>
                     <!--li class="mdl-menu__item"><i class="material-icons">add</i>Add another account...</li-->
                  </ul>
               </div>
            </header>
            <nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
               <a class="mdl-navigation__link" href="homepage.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Home</a>
               <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">equalizer</i>Rankings</a>
               <a class="mdl-navigation__link" href="vote_now.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">touch_app</i>Vote Now</a>
               <div class="mdl-layout-spacer"></div>
               <a class="mdl-navigation__link" href=""><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">help_outline</i>Help</a>
            </nav>
         </div>
         <main class="mdl-layout__content mdl-color--grey-100">
            <div class="mdl-grid">
               <div class="demo-cards mdl-shadow--2dp mdl-color--white mdl-cell mdl-cell--12-col">
                  <?php echo getLeaderBoards(); ?>
               </div>
               
            </div>
         </main>
      </div>
      <script src="https://code.getmdl.io/1.1.3/material.min.js"></script>
   </body>
</html>