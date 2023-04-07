<?php
  //Last redirect to the game page
  if (!empty($_SERVER['HTTPS']) && ('on' == $_SERVER['HTTPS'])) {
    $uri = 'https://';

  } else {

    $uri = 'http://';

  }

  $uri .= $_SERVER['HTTP_HOST'];
  $redirectPath = $uri . "/game";
  $autoDeleteGames = false;

  $roguelikePath = $redirectPath . "/Roguelike/CreateGame.php";
  $gameUIPath = $redirectPath . "/NextTurn4.php";

?>
