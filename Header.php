<?php
  include_once 'includes/functions.inc.php';
  include_once 'includes/dbh.inc.php';
  session_start();
?>

<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Flesh and Blood Online</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="css/reset.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="shortcut icon" type="image/png" href="./Images/favicon3.png"/>
  </head>
  <body>

    <nav>
      <div class="wrapper">
        <a href="MainMenu.php"></a>
        <ul>
          <li><a href="MainMenu.php">Main Menu</a></li>
          <li><a href="Draft.php">Limited</a></li>
          <li><a target="_blank" href="https://discord.gg/JykuRkdd5S">Discord</a></li>
          <li><a target="_blank" href="https://www.patreon.com/bePatron?u=36985868">Support Us</a></li>
          <?php
            if (isset($_SESSION["useruid"])) {
              echo "<li><a href='Profile.php'>Profile Page</a></li>";
              echo "<li><a href='Logout.php'>Logout</a></li>";
            }
            else {
              echo "<li><a href='Signup.php'>Sign up</a></li>";
              echo "<li><a href='Login.php'>Log in</a></li>";
            }
          ?>
        </ul>
      </div>
    </nav>
