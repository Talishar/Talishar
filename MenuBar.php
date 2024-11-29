<?php

include_once 'Assets/patreon-php-master/src/OAuth.php';
include_once 'Assets/patreon-php-master/src/API.php';
include_once 'Assets/patreon-php-master/src/PatreonLibraries.php';
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include_once 'includes/functions.inc.php';
include_once 'includes/dbh.inc.php';
include_once 'Libraries/HTTPLibraries.php';
include_once 'HostFiles/Redirector.php';
session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$isPatron = isset($_SESSION["isPatron"]);

$isMobile = IsMobile();

?>


<body style="background-image: url('./Images/outsiders-background.jpg');">

  <div style='width: 100%'>
    <nav class='NavBarDiv'>
      <ul>
        <?php if (!$isMobile) echo '<li><a target="_blank" href="https://discord.gg/JykuRkdd5S">Discord</a></li>'; ?>

        <?php
        if (!$isMobile) echo '<li><a target="_blank" href="https://twitter.com/talishar_online">Twitter</a></li>';
        ?>
        <!--<li><a target="_blank" href="https://www.patreon.com/talishar_online">Support Us</a></li>-->
      </ul>

      <ul class='rightnav'>
        <li></li>
        <li><a href="MainMenu.php">Home Page</a></li>
        <?php if (!$isMobile) echo '<li><a href="https://fabtcg.com/events" target="_blank">Find Local Events</a></li>'; ?>
        <?php if (!$isMobile) echo '<li><a href="https://github.com/Talishar/Talishar/labels/bug" target="_blank">Known Bugs</a></li>'; ?>
        <?php if($isPatron || (isset($_SESSION["useruid"]) && $_SESSION["useruid"] == "OotTheMonk")) echo "<li><a href='Replays.php'>Replays[BETA]</a></li>";
        ?>
        <?php
        if (isset($_SESSION["useruid"])) {
          echo "<li><a href='ProfilePage.php'>Profile</a></li>";
          echo "<li><a href='./AccountFiles/LogoutUser.php'>Logout</a></li>";
        } else {
          echo "<li><a href='Signup.php'>Sign up</a></li>";
          echo "<li><a href='./LoginPage.php'>Log in</a></li>";
        }
        ?>
      </ul>
    </nav>
  </div>
