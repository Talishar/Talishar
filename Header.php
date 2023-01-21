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

if (isset($_SESSION["isPatron"])) $isPatron = $_SESSION["isPatron"];
else $isPatron = false;

$isMobile = IsMobile();

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <title>Talishar</title>
  <link rel="shortcut icon" type="image/png" href="Images/TeenyCoin.png" />
  <!--<link rel="stylesheet" href="css/style4.css">-->
  <!--<script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8442966023291783" crossorigin="anonymous"></script>-->

  <style>
    li {
      display: inline;
      font-size: 24px;
      padding: 8px;
    }

    ul {
      display: inline;
      padding: 0px;
      margin: 0px;
    }

    a {
      color: white;
      background-color: transparent;
      text-decoration: none;
      font-family: helvetica;
    }

    .ContentWindow {
      background-color: rgba(40, 40, 40, .7);
      font-size: 1rem;
      font-family: helvetica;
      color: white;
      position: absolute;
    }

    h1,
    h2,
    h3,
    h4,
    h5 {
      text-align: center;
    }
  </style>
</head>

<body style="background-image: url('./Images/background.jpg');">

  <div style='position:fixed; left:0px; top:0px; height:30px; width:100%; z-index:100; background-color:rgba(30, 30, 30, .8);'>
    <nav>
      <ul>
        <?php if (!$isMobile) echo '<li><a target="_blank" href="https://discord.gg/JykuRkdd5S">Discord</a></li>'; ?>

        <?php
        if (!$isMobile) echo '<li><a target="_blank" href="https://twitter.com/talishar_online">Twitter</a></li>';
        ?>
        <!--<li><a target="_blank" href="https://www.patreon.com/talishar_online">Support Us</a></li>-->
      </ul>

      <ul style='float:right;'>
        <li><a href="MainMenu.php">Home Page</a></li>
        <?php if (!$isMobile) echo '<li><a href="https://fabtcg.com/events" target="_blank">Find Local Events</a></li>'; ?>
        <?php if (!$isMobile) echo '<li><a href="https://github.com/Talishar/Talishar/labels/bug" target="_blank">Known Bugs</a></li>'; ?>
        <?php //if($isPatron) echo "<li><a href='Replays.php'>Replays[BETA]</a></li>"; 
        ?>
        <li><a href="Draft.php">Limited</a></li>
        <?php if (!$isMobile) echo '<li><a href="' . $roguelikePath . '">Roguelike</a></li>'; ?>
        <?php
        if (isset($_SESSION["useruid"])) {
          echo "<li><a href='ProfilePage.php'>Profile</a></li>";
          echo "<li><a href='Logout.php'>Logout</a></li>";
        } else {
          echo "<li><a href='Signup.php'>Sign up</a></li>";
          echo "<li><a href='Login.php'>Log in</a></li>";
        }
        ?>
      </ul>
    </nav>
  </div>