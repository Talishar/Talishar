<?php


include "CardDictionary.php";
include 'Libraries/HTTPLibraries.php';
include_once "Libraries/PlayerSettings.php";
include_once "Assets/patreon-php-master/src/PatreonDictionary.php";

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo "Invalid game name.";
  exit;
}
$playerID = TryGet("playerID", 3);
$lastUpdate = TryGet("lastUpdate", 0);
$authKey = TryGet("authKey", 0);

if(!file_exists("./Games/$gameName/")) { header('HTTP/1.0 403 Forbidden'); exit; }

if($lastUpdate == "NaN") $lastUpdate = 0;
if ($lastUpdate > 10000000) $lastUpdate = 0;

include "WriteLog.php";
include "HostFiles/Redirector.php";
include "Libraries/UILibraries.php";
include "Libraries/SHMOPLibraries.php";

$currentTime = round(microtime(true) * 1000);
SetCachePiece($gameName, $playerID + 1, $currentTime);

$count = 0;
$cacheVal = GetCachePiece($gameName, 1);
if ($cacheVal > 10000000) {
  SetCachePiece($gameName, 1, 1);
  $lastUpdate = 0;
}
$kickPlayerTwo = false;
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep(100000); //100 milliseconds
  $currentTime = round(microtime(true) * 1000);
  $cacheVal = GetCachePiece($gameName, 1);
  SetCachePiece($gameName, $playerID + 1, $currentTime);
  ++$count;
  if ($count == 100) break;
  $otherP = $playerID == 1 ? 2 : 1;
  $oppLastTime = GetCachePiece($gameName, $otherP + 1);
  $oppStatus = strval(GetCachePiece($gameName, $otherP + 3));

  if ($oppStatus != "-1" && $oppLastTime != "") {
    if (($currentTime - $oppLastTime) > 8000 && $oppStatus == "0") {
      WriteLog("🔌Player $otherP has disconnected.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "-1");
      if($otherP == 2) SetCachePiece($gameName, $otherP + 6, "");
      $kickPlayerTwo = true;
    }
  }
}

include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";

$targetAuth = $playerID == 1 ? $p1Key : $p2Key;
if ($authKey != $targetAuth) {
  echo "Invalid Auth Key";
  exit;
}

if ($kickPlayerTwo) {

  $numP2Disconnects = IncrementCachePiece($gameName, 11);
  if($numP2Disconnects >= 3)
  {
    WriteLog("This lobby is now hidden due to inactivity. Type in chat to unhide the lobby.");
  }
  if (file_exists("./Games/$gameName/p2Deck.txt")) unlink("./Games/$gameName/p2Deck.txt");
  if (file_exists("./Games/$gameName/p2DeckOrig.txt")) unlink("./Games/$gameName/p2DeckOrig.txt");
  $gameStatus = $MGS_Initial;
  SetCachePiece($gameName, 14, $gameStatus);
  $p2SideboardSubmitted = "0";
  $p2Data = [];
  WriteGameFile();
}