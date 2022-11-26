<?php

include_once 'Assets/patreon-php-master/src/OAuth.php';
include_once 'Assets/patreon-php-master/src/API.php';
include_once 'Assets/patreon-php-master/src/PatreonLibraries.php';
include_once 'includes/functions.inc.php';
include_once 'includes/dbh.inc.php';
session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

if (isset($_SESSION["useruid"])) {
  $useruid = $_SESSION["useruid"];
  $banfileHandler = fopen("./HostFiles/bannedPlayers.txt", "r");
  while (!feof($banfileHandler)) {
    $bannedPlayer = trim(fgets($banfileHandler), "\r\n");
    if ($useruid == $bannedPlayer) {
      fclose($banfileHandler);
      exit;
    }
  }
  fclose($banfileHandler);
}

if (isset($_SESSION["isPatron"])) $isPatron = $_SESSION["isPatron"];
else $isPatron = false;

?>

<!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
  <meta charset="utf-8" name="viewport" content="width=device-width, initial-scale=1">
  <title>Talishar</title>

  <meta property="og:title" content="Talishar">
  <meta name="twitter:title" content="Talishar">

  <meta name="description" content="Talishar is a fan-made FABTCG project not associated with Legend Story Studios. Talishar is a browser-based platform to play Flesh and Blood. Please report any feedback or bugs you have on our discord.">
  <meta property="og:description" content="Talishar is a fan-made FABTCG project not associated with Legend Story Studios. Talishar is a browser-based platform to play Flesh and Blood. Please report any feedback or bugs you have on our discord.">
  <meta name="twitter:description" content="Talishar is a fan-made FABTCG project not associated with Legend Story Studios. Talishar is a browser-based platform to play Flesh and Blood. Please report any feedback or bugs you have on our discord.">

  <meta property="og:image" content="http://talishar.net/game/Images/TalisharLogo2.webp">
  <meta name="twitter:image" content="http://talishar.net/game/Images/TalisharLogo2.webp">

  <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="css/reset.css">
  <link rel="stylesheet" href="css/style4.css">
  <link rel="shortcut icon" type="image/png" href="./Images/TeenyCoin.png" />
  <script async src="https://pagead2.googlesyndication.com/pagead/js/adsbygoogle.js?client=ca-pub-8442966023291783" crossorigin="anonymous"></script>
</head>

<body>


  <nav>
    <div class="wrapper">
      <ul>
        <!--<li><a href="Blog.php">Blog</a></li>-->
        <li><a target="_blank" href="https://discord.gg/JykuRkdd5S">Discord</a></li>
        <li><a target="_blank" href="https://twitter.com/talishar_online">Twitter</a></li>
        <li><a target="_blank" href="https://www.patreon.com/talishar_online">Support Us</a></li>
      </ul>

      <ul>
        <li><a href="MainMenu.php">Home Page</a></li>
        <li><a href="https://fabtcg.com/events">Find Local Events</a></li>
        <?php if($isPatron) echo "<li><a href='Replays.php'>Replays[BETA]</a></li>"; ?>
        <li><a href="Draft.php">Limited</a></li>
        <?php
        if (isset($_SESSION["useruid"])) {
          echo "<li><a href='Profile.php'>Profile</a></li>";
          echo "<li><a href='Logout.php'>Logout</a></li>";
        } else {
          echo "<li><a href='Signup.php'>Sign up</a></li>";
          echo "<li><a href='Login.php'>Log in</a></li>";
        }
        ?>
      </ul>
    </div>
  </nav>
