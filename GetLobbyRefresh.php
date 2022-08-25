<?php


include 'Libraries/HTTPLibraries.php';

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = TryGet("playerID", 3);
$lastUpdate = TryGet("lastUpdate", 0);
$authKey = TryGet("authKey", 0);

if ($lastUpdate > 10000000) $lastUpdate = 0;


include "WriteLog.php";
include "CardDictionary.php";
include "HostFiles/Redirector.php";
include "Libraries/UILibraries2.php";
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
  $otherP = ($playerID == 1 ? 2 : 1);
  $oppLastTime = GetCachePiece($gameName, $otherP + 1);
  $oppStatus = strval(GetCachePiece($gameName, $otherP + 3));

  if ($oppStatus != "-1" && $oppLastTime != "") {
    if (($currentTime - $oppLastTime) > 8000 && $oppStatus == "0") {
      WriteLog("Player $otherP has disconnected.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "-1");
      $kickPlayerTwo = true;
    }
  }
}

include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";

$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
if ($authKey != $targetAuth) {
  echo ("Invalid Auth Key");
  exit;
}

if ($kickPlayerTwo) {
  if (file_exists("./Games/" . $gameName . "/p2Deck.txt")) unlink("./Games/" . $gameName . "/p2Deck.txt");
  if (file_exists("./Games/" . $gameName . "/p2DeckOrig.txt")) unlink("./Games/" . $gameName . "/p2DeckOrig.txt");
  $gameStatus = $MGS_Initial;
  $p2Data = [];
  WriteGameFile();
}

if ($lastUpdate != 0 && $cacheVal < $lastUpdate) {
  echo (GetCachePiece($gameName, 1) . "ENDTIMESTAMP");
  exit;
} else if ($gameStatus == $MGS_GameStarted) {
  echo ("1");
  exit;
} else {

  echo (GetCachePiece($gameName, 1) . "ENDTIMESTAMP");
  if ($gameStatus == $MGS_ChooseFirstPlayer) {
    if ($playerID == $firstPlayerChooser) {
      echo ("<input class='GameLobby_Button' type='button' name='action' value='Go First' onclick='SubmitFirstPlayer(1)' style='margin-left:15px; margin-right:5px; text-align:center;'>");
      echo ("<input class='GameLobby_Button' type='button' name='action' value='Go Second' onclick='SubmitFirstPlayer(2)' style='text-align:center;'><br>");
    } else {
      echo ("<div style='text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; color:#EDEDED'>Waiting for other player to choose who will go first.</div>");
    }
  }

  if ($playerID == 1 && $gameStatus < $MGS_Player2Joined) {
    echo ("<div><input class='GameLobby_Input' onclick='copyText()' style='width:40%;' type='text' id='gameLink' value='" . $redirectPath . "/JoinGame.php?gameName=$gameName&playerID=2'><button class='GameLobby_Button' style='margin-left:3px;' onclick='copyText()'>Copy Invite Link</button></div>");
  }

  // Chat Log
  echo ("<br>");
  echo ("<div id='gamelog' style='text-align:left; position:absolute; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; color: #EDEDED; background-color: rgba(20,20,20,0.8); top:115px; left:3%; width:94%; bottom:10%; font-weight:550; overflow-y: auto;'>");
  EchoLog($gameName, $playerID);
  echo ("</div>");

  echo ("<div id='playAudio' style='display:none;'>" . ($playerID == 1 && $gameStatus == $MGS_ChooseFirstPlayer ? 1 : 0) . "</div>");

  $otherHero = "CardBack";
  $otherPlayer = $playerID == 1 ? 2 : 1;
  $deckFile = "./Games/" . $gameName . "/p" . $otherPlayer . "Deck.txt";
  if (file_exists($deckFile)) {
    $handler = fopen($deckFile, "r");
    $otherCharacter = GetArray($handler);
    $otherHero = $otherCharacter[0];
    fclose($handler);
  }

  echo ("<div id='otherHero' style='display:none;'>");
  echo (Card($otherHero, "concat", 250, 0, 1));
  echo ("</div>");

  echo ("<div id='submitDisplay' style='display:none;'>" . ($playerID == 1 ? ($gameStatus == $MGS_ReadyToStart ? "block" : "none") : ($gameStatus == $MGS_P2Sideboard ? "block" : "none")) . "</div>");

  $icon = "ready.png";
  if ($gameStatus == $MGS_ChooseFirstPlayer) $icon = $playerID == $firstPlayerChooser ? "ready.png" : "notReady.png";
  else if ($playerID == 1 && $gameStatus < $MGS_ReadyToStart) $icon = "notReady.png";
  else if ($playerID == 2 && $gameStatus >= $MGS_ReadyToStart) $icon = "notReady.png";
  echo ("<div id='iconHolder' style='display:none;'>" . $icon . "</div>");
}
