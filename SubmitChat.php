<?php

  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $chatText=htmlspecialchars($_GET["chatText"]);

  session_start();
  $uid = "-";
  if(isset($_SESSION['useruid'])) $uid = $_SESSION['useruid'];
  $displayName = ($uid != "-" ? $uid : "Player " . $playerID);

  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "a");
  $output = "<span style='font-weight:bold; color:<PLAYER" . $playerID . "COLOR>;'>" . $displayName . ": </span>" . $chatText;
  fwrite($handler, $output . "\r\n");
  fclose($handler);

  SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));

?>
