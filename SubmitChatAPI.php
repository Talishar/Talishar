<?php

include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$authKey = TryGet("authKey", "");
$playerID = $_GET["playerID"];
$chatText = htmlspecialchars($_POST["chatText"]);

if ($playerID != 1 && $playerID != 2) {
  echo 'naughty';
  exit;
}

include "ParseGamestate.php";

session_set_cookie_params(0, '/', '.talishar.net');
session_start();
$uid = "-";
if (isset($_SESSION['useruid'])) $uid = $_SESSION['useruid'];
$displayName = ($uid != "-" ? $uid : "Player " . $playerID);
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else $authKey = TryGet("authKey", "");
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);

if ($authKey != $targetAuth) {
  echo 'naughty';
  session_write_close();
  exit;
}

if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

if (isset($_SESSION["isPatron"])) $displayName = $displayName . "|P";

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "a");
$output = "{{P" . $playerID . "|" . $displayName . "}}" . $chatText;
fwrite($handler, $output . "\r\n");
fclose($handler);

GamestateUpdated($gameName);
