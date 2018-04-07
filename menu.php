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
         <li><a class="mdl-menu__item" href="php/signout.php">Sign Out</a></li>
      </ul>
   </div>
</header>
<nav class="demo-navigation mdl-navigation mdl-color--blue-grey-800">
   <a class="mdl-navigation__link" href="homepage.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">home</i>Home</a>
   <a class="mdl-navigation__link" href="vote_now.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">touch_app</i>Vote Now</a>
   <a class="mdl-navigation__link" href="rankings.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">star_border</i>Rankings</a>
   <a class="mdl-navigation__link" href="results_page.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">star_border</i>Match Results</a>
   <a class="mdl-navigation__link" href="vote_stats.php"><i class="mdl-color-text--blue-grey-400 material-icons" role="presentation">star_border</i>Voting Stats</a>
   <div class="mdl-layout-spacer"></div>
</nav>