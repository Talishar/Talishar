<?php

include "../WriteLog.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";

<<<<<<< HEAD
=======
SetHeaders();

$_POST = json_decode(file_get_contents('php://input'), true);
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
$gameName = $_POST["gameName"];
$playerID = $_POST["playerID"];
if ($playerID == 1 && isset($_SESSION["p1AuthKey"])) $authKey = $_SESSION["p1AuthKey"];
else if ($playerID == 2 && isset($_SESSION["p2AuthKey"])) $authKey = $_SESSION["p2AuthKey"];
else if (isset($_POST["authKey"])) $authKey = $_POST["authKey"];
<<<<<<< HEAD
$action = $_POST["action"];//"Go First" to choose to go first, anything else will choose to go second
=======
$action = $_POST["action"]; //"Go First" to choose to go first, anything else will choose to go second
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504

if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}

include "../HostFiles/Redirector.php";
include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  echo ("Invalid Auth Key");
  exit;
}

if ($action == "Go First") {
  $firstPlayer = $playerID;
} else {
  $firstPlayer = ($playerID == 1 ? 2 : 1);
}
<<<<<<< HEAD
WriteLog("Player " . $firstPlayer . " will go first.", path:"../");
=======
WriteLog("Player " . $firstPlayer . " will go first.", path: "../");
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
$gameStatus = $MGS_P2Sideboard;
GamestateUpdated($gameName);

WriteGameFile();

$response = new stdClass();
$response->success = true;
<<<<<<< HEAD
echo(json_encode($response));

?>
=======
echo (json_encode($response));
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
