<?php

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include "GameLogic.php";
include "GameTerms.php";
include "Libraries/StatFunctions.php";
include "Libraries/PlayerSettings.php";
include "Libraries/UILibraries2.php";
include "AI/CombatDummy.php";
include "WriteReplay.php";
ob_end_clean();

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];

if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) exit;

ob_start();
include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";
ob_end_clean();

$authKey = $_GET["authKey"];
if ($authKey != $p1Key) exit;

//Setup the random number generator
srand(make_seed());

//First initialize the initial state of the game
$filename = "./Games/" . $gameName . "/gamestate.txt";
$handler = fopen($filename, "w");
fwrite($handler, "20 20\r\n"); //Player health totals //TODO: Change based on character

//Player 1
$p1DeckHandler = fopen("./Games/" . $gameName . "/p1Deck.txt", "r");
initializePlayerState($handler, $p1DeckHandler, 1);
fclose($p1DeckHandler);

//Player 2
$p2DeckHandler = fopen("./Games/" . $gameName . "/p2Deck.txt", "r");
initializePlayerState($handler, $p2DeckHandler, 2);
fclose($p2DeckHandler);

fwrite($handler, "\r\n"); //Landmarks
fwrite($handler, "0\r\n"); //Game winner (0=none, else player ID)
fwrite($handler, "$firstPlayer\r\n"); //First Player
fwrite($handler, "1\r\n"); //Current Player
fwrite($handler, "1\r\n"); //Current Turn
fwrite($handler, "M 1\r\n"); //What phase/player is active
fwrite($handler, "1\r\n"); //Action points
fwrite($handler, "\r\n"); //Combat Chain
fwrite($handler, "0 0 NA 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0 0 -1 0 0 0\r\n"); //Combat Chain State
fwrite($handler, "\r\n"); //Current Turn Effects
fwrite($handler, "\r\n"); //Current Turn Effects From Combat
fwrite($handler, "\r\n"); //Next Turn Effects
fwrite($handler, "\r\n"); //Decision Queue
fwrite($handler, "0\r\n"); //Decision Queue Variables
fwrite($handler, "0 - - -\r\n"); //Decision Queue State
fwrite($handler, "\r\n"); //Layers
fwrite($handler, "\r\n"); //Layer Priority
fwrite($handler, "1\r\n"); //What player's turn it is
fwrite($handler, "\r\n"); //Last Played Card
fwrite($handler, "0\r\n"); //Number of prior chain links this turn
fwrite($handler, "\r\n"); //Chain Link Summaries
fwrite($handler, $p1Key . "\r\n"); //Player 1 auth key
fwrite($handler, $p2Key . "\r\n"); //Player 2 auth key
fwrite($handler, 0 . "\r\n"); //Permanent unique ID counter
fwrite($handler, "0\r\n"); //Game status -- 0 = START, 1 = PLAY, 2 = OVER
fwrite($handler, "\r\n"); //Animations
fwrite($handler, "0\r\n"); //Current Player activity status -- 0 = active, 2 = inactive
fwrite($handler, "0\r\n"); //Player1 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
fwrite($handler, "0\r\n"); //Player2 Rating - 0 = not rated, 1 = green (positive), 2 = red (negative)
fclose($handler);

//Set up log file
$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = strval(round(microtime(true) * 1000));
$currentUpdate = GetCachePiece($gameName, 1);
$p1Hero = GetCachePiece($gameName, 7);
$p2Hero = GetCachePiece($gameName, 8);
WriteCache($gameName, ($currentUpdate + 1) . "!" . $currentTime . "!" . $currentTime . "!-1!-1!" . $currentTime . "!"  . $p1Hero . "!" . $p2Hero); //Initialize SHMOP cache for this game

ob_start();
include "StartEffects.php";
ob_end_clean();

$gameStateTries = 0;
while (!file_exists($filename) && $gameStateTries < 10) {
  usleep(100000); //100ms
  ++$gameStateTries;
}

//Update the game file to show that the game has started and other players can join to spectate
$gameStatus = $MGS_GameStarted;
WriteGameFile();

header("Location: " . $redirectPath . "/NextTurn4.php?gameName=$gameName&playerID=1&authKey=$p1Key");

exit;

function make_seed()
{
  list($usec, $sec) = explode(' ', microtime());
  return $sec + $usec * 1000000;
}

function initializePlayerState($handler, $deckHandler, $player)
{
  global $p1IsPatron, $p2IsPatron, $p1IsChallengeActive, $p2IsChallengeActive;
  $charEquip = GetArray($deckHandler);
  $deckCards = GetArray($deckHandler);
  $deckSize = count($deckCards);
  fwrite($handler, "\r\n"); //Hand

  if($player == 1) $p1IsChallengeActive = "0";
  else if($player == 2) $p2IsChallengeActive = "0";

  //Equipment challenge
  if($charEquip[0] != "ARC001" && $charEquip[0] != "ARC002" && $charEquip[1] == "CRU177")
  {
    if($player == 1) $p1IsChallengeActive = "1";
    else if($player == 2) $p2IsChallengeActive = "1";
  }

  /*
  $challengeThreshold = (CharacterHealth($charEquip[0]) > 25 ? 3 : 2);
  $challengeThreshold = 2;
  $numChallengeCard = 0;
  for($i=0; $i<count($deckCards); ++$i)
  {
    if($deckCards[$i] == "WTR175") ++$numChallengeCard;
  }
  if($player == 1 && $numChallengeCard >= $challengeThreshold) $p1IsChallengeActive = "1";
  else if($player == 2 && $numChallengeCard >= $challengeThreshold) $p2IsChallengeActive = "1";
  */

  fwrite($handler, implode(" ", $deckCards) . "\r\n");

  for ($i = 0; $i < count($charEquip); ++$i) {
    fwrite($handler, $charEquip[$i] . " 2 0 0 0 " . CharacterNumUsesPerTurn($charEquip[$i]) . " 0 0 0 " . CharacterDefaultActiveState($charEquip[$i]) . ($i < count($charEquip) - 1 ? " " : "\r\n"));
  }
  //Character and equipment. First is ID. Four numbers each. First is status (0=Destroy/unavailable, 1=Used, 2=Unused, 3=Disabled). Second is num counters
  //Third is attack modifier, fourth is block modifier
  //Order: Character, weapon 1, weapon 2, head, chest, arms, legs
  fwrite($handler, "0 0\r\n"); //Resources float/needed
  fwrite($handler, "\r\n"); //Arsenal
  fwrite($handler, "\r\n"); //Item
  fwrite($handler, "\r\n"); //Aura
  fwrite($handler, "\r\n"); //Discard
  fwrite($handler, "\r\n"); //Pitch
  fwrite($handler, "\r\n"); //Banish
  fwrite($handler, "0 0 0 0 0 0 0 0 DOWN 0 -1 0 0 0 0 0 0 0 0 0 0 0 NA 0 0 0 - -1 0 0 0 0 0 0 - 0 0 0 0 0 0 - 0 - - 0 -1 0 0 0 0 0 - 0 0 0 0 0\r\n"); //Class State
  fwrite($handler, "\r\n"); //Character effects
  fwrite($handler, "\r\n"); //Soul
  fwrite($handler, "\r\n"); //Card Stats
  fwrite($handler, "\r\n"); //Turn Stats
  fwrite($handler, "\r\n"); //Allies
  fwrite($handler, "\r\n"); //Permanents
  //$holdPriority = ($charEquip[0] == "ARC113" || $charEquip[0] == "ARC114" ? "1" : "0");
  $holdPriority = "0"; //Auto-pass layers
  $isPatron = ($player == 1 ? $p1IsPatron : $p2IsPatron);
  if($isPatron == "") $isPatron = "0";
  $mute = 0;
  fwrite($handler, $holdPriority . " 1 0 0 0 0 0 1 0 0 0 " . $mute . " 0 " . $isPatron . "\r\n"); //Settings
}

function GetArray($handler)
{
  $line = trim(fgets($handler));
  if ($line == "") return [];
  return explode(" ", $line);
}

?>

Something is wrong with the XAMPP installation :-(
