<?php
  session_start();
  include("config.php");
  $db=mysqli_select_db($con,DB_NAME) or die("Failed to connect to MySQL: " . mysql_error());

  function updatePassword(){
    if(isset($_POST['new_password'])){
      $sql = "update user_data SET password='".$_POST['new_password']."' where md5(username)='".$_POST['key']."' and md5(password)='".$_POST['reset']."'";
      $update = mysqli_query($GLOBALS['con'],$sql);
      if(isset($update) && $update)
        return "Your password has been reset. Please login with your new password.";
      else return "There was a problem while resetting your password. Please try again or contact website administrators";
    }
    else return "There was a problem while resetting your password. Please try again or contact website administrators";
  }
?>
<!doctype html><html lang="en">
   <head>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <title>Gully IPL - Password Updated</title>
      <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Roboto:regular,bold,italic,thin,light,bolditalic,black,medium&amp;lang=en">
      <link rel="stylesheet" href="https://fonts.googleapis.com/icon?family=Material+Icons"/>
      <link rel="stylesheet" href="https://code.getmdl.io/1.1.3/material.cyan-light_blue.min.css"/>
      <link rel="stylesheet" href="css/styles.css"/>
    </head>
    <body>
      <div class="mdl-grid">
        <div class="mdl-cell mdl-cell--12-col mdl-cell--8-col-tablet mdl-grid mdl-grid--no-spacing">
          <div class="mdl-card mdl-shadow--2dp mdl-cell mdl-cell--4-col mdl-cell--4-col-tablet mdl-cell--12-col-desktop">
               <div class="mdl-card__supporting-text mdl-card--expand mdl-color-text--grey-800">
                  <?php echo updatePassword();?>
               </div>
               <div class="mdl-card__actions mdl-card--border">
                  <a href="../login.php" class="mdl-button mdl-js-button mdl-js-ripple-effect">Login Again</a>
               </div>
            </div>
         </div>
       </div>
</body>
</html>