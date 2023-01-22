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

<head>
  <title>Talishar</title>
  <link rel="shortcut icon" type="image/png" href="Images/TeenyCoin.png" />
  <link rel="stylesheet" href="https://unpkg.com/bamboo.css/dist/dark.min.css">
  <style>
    body {
      background-image: url('Images/background_DYN.jpg');
      background-position: top center;
      background-repeat: no-repeat;
      background-size: cover;
      overflow: hidden;
      height: 100vh;
      height: 100dvh;
    }

    li {
      display: inline;
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
      padding: 0 1em;
      background-color: rgba(40, 40, 40, .7);
      font-family: helvetica;
      color: white;
      position: absolute;
    }

    .NavBarDiv {
      font-size: 1.5rem;
      position: fixed;
      left: 0px;
      top: 0px;
      height: 45px;
      width: 100%;
      z-index: 100;
      background-color: rgba(30, 30, 30, .8);
    }

    td>img {
      height: max-content;
      width: 100%;
    }

    .rightnav {
      position: absolute;
      right: 0px;
    }

    h1,
    h2,
    h3,
    h4,
    h5 {
      text-align: center;
    }

    td {
      color: white;
    }

    span {
      color: white;
    }
  </style>
</head>

<body style="background-image: url('./Images/background.jpg');">

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
          echo "<li><a href='./AccountFiles/LoginPage.php'>Log in</a></li>";
        }
        ?>
      </ul>
    </nav>
  </div>