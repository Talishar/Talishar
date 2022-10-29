<?php

include 'Libraries/HTTPLibraries.php';
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";

header("Access-Control-Allow-Origin: https://talishar.net");
header('Content-Type: application/json; charset=utf-8');
$response = new stdClass();

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  $response->errorMessage = ("Invalid game name.");
  echo(json_encode($response));
  exit;
}

$playerID = TryGet("playerID", 3);
if(!is_numeric($playerID)) {
  $response->errorMessage = ("Invalid player ID.");
  echo(json_encode($response));
  exit;
}

$authKey = TryGet("authKey", 3);
$lastUpdate = intval(TryGet("lastUpdate", 0));
$windowWidth = intval(TryGet("windowWidth", 0));
$windowHeight = intval(TryGet("windowHeight", 0));

if ($lastUpdate > 10000000) {
  $lastUpdate = 0;
}

$isGamePlayer = $playerID == 1 || $playerID == 2;
$opponentDisconnected = false;

$currentTime = round(microtime(true) * 1000);
if ($isGamePlayer) {
  $playerStatus = intval(GetCachePiece($gameName, $playerID + 3));
  if ($playerStatus == "-1") WriteLog("Player $playerID has connected.");
  SetCachePiece($gameName, $playerID + 1, $currentTime);
  SetCachePiece($gameName, $playerID + 3, "0");
  if ($playerStatus > 0) {
    WriteLog("Player $playerID has reconnected.");
    SetCachePiece($gameName, $playerID + 3, "0");
  }
}
$count = 0;
$cacheVal = intval(GetCachePiece($gameName, 1));
if ($cacheVal > 10000000) {
  SetCachePiece($gameName, 1, 1);
  $lastUpdate = 0;
}
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep(50000); //50 milliseconds
  $currentTime = round(microtime(true) * 1000);
  $cacheVal = GetCachePiece($gameName, 1);
  if ($isGamePlayer) {
    SetCachePiece($gameName, $playerID + 1, $currentTime);
    $otherP = ($playerID == 1 ? 2 : 1);
    $oppLastTime = GetCachePiece($gameName, $otherP + 1);
    $oppStatus = GetCachePiece($gameName, $otherP + 3);
    if (($currentTime - $oppLastTime) > 3000 && ($oppStatus == "0")) {
      WriteLog("Opponent has disconnected. Waiting 60 seconds to reconnect.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "1");
    } else if (($currentTime - $oppLastTime) > 60000 && $oppStatus == "1") {
      WriteLog("Opponent has left the game.");
      GamestateUpdated($gameName);
      SetCachePiece($gameName, $otherP + 3, "2");
      $lastUpdate = 0;
      $opponentDisconnected = true;

      if ($otherP == 1) {
        $GLO_Player1Disconnected = -10; // Remove 10 karma to the leaver if it's player 1.
        $GLO_Player2Disconnected = 0; // No punition to the other player.
      } else {
        $GLO_Player2Disconnected = -10; // Remove 10 karma to the leaver if it's player 2.
        $GLO_Player1Disconnected = 0; // No punition to the other player.
      }
    }
  }
  ++$count;
  if ($count == 100) break;
}

if ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  echo "0";
  exit;
} else {
  //First we need to parse the game state from the file
  include "ParseGamestate.php";
  include 'GameLogic.php';
  include "GameTerms.php";
  include "Libraries/UILibraries2.php";
  include "Libraries/StatFunctions.php";
  include "Libraries/PlayerSettings.php";

  if ($opponentDisconnected && !IsGameOver()) {
    include_once "./includes/dbh.inc.php";
    include_once "./includes/functions.inc.php";
    PlayerLoseHealth($otherP, GetHealth($otherP));
    include "WriteGamestate.php";
  }

  if ($turn[0] == "REMATCH") {
    include "MenuFiles/ParseGamefile.php";
    include "MenuFiles/WriteGamefile.php";
    if ($gameStatus == $MGS_GameStarted) {
      include "AI/CombatDummy.php";
      $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
      if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
      $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
      if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
      $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
      $firstPlayer = 1;
      $firstPlayerChooser = ($winner == 1 ? 2 : 1);
      unlink("./Games/" . $gameName . "/gamestate.txt");

      $errorFileName = "./BugReports/CreateGameFailsafe.txt";
      $errorHandler = fopen($errorFileName, "a");
      date_default_timezone_set('America/Chicago');
      $errorDate = date('m/d/Y h:i:s a');
      $errorOutput = "Rematch failsafe hit for game $gameName at $errorDate";
      fwrite($errorHandler, $errorOutput . "\r\n");
      fclose($errorHandler);

      WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
    }
    WriteGameFile();
    $currentTime = round(microtime(true) * 1000);
    SetCachePiece($gameName, 2, $currentTime);
    SetCachePiece($gameName, 3, $currentTime);
    $response->errorMessage = "1234REMATCH";
    echo(json_encode($response));
    exit;
  }

  $targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
  if ($playerID != 3 && $authKey != $targetAuth) {
    $response->errorMessage = "999999ENDTIMESTAMP";
    echo(json_encode($response));
    exit;
  }

  if (count($turn) == 0) {
    RevertGamestate();
    GamestateUpdated($gameName);
    exit();
  }

  $blankZone = 'blankZone';

  //Choose Cardback
  $MyCardBack = GetCardBack($playerID);
  $TheirCardBack = GetCardBack($playerID == 1 ? 2 : 1);
  $otherPlayer = ($playerID == 1 ? 2 : 1);

  //Display combat chain
  $combatChainContents = array();
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    array_push($combatChainContents, JSONRenderedCard(cardNumber: $combatChain[$i], controller: $combatChain[$i+1]));
  }
  $response->activeChainLink = $combatChainContents;

  //Display layer
  $layerContents = array();
  for ($i = count($layers) - LayerPieces(); $i >= 0; $i -= LayerPieces()) {
    $layerName = ($layers[$i] == "LAYER" || $layers[$i] == "TRIGGER" ? $layers[$i + 2] : $layers[$i]);
    array_push($layerContents, JSONRenderedCard(cardNumber: $layerName, controller:$layers[$i+1]));
  }
  $response->layerContents = $layerContents;

  //Opponent Hand
  $handContents = array();
  for ($i = 0; $i < count($theirHand); ++$i) {
    array_push($handContents, JSONRenderedCard(cardNumber: $TheirCardBack, controller: ($playerID == 1 ? 2 : 1)));
  }
  $response->opponentHand = $handContents;

  //Their Health
  $response->opponentHealth = $theirHealth;
  //Their soul count
  $response->opponentSoulCount = count($theirSoul);

  //Display their discard, pitch, deck, and banish
  $response->opponentDiscardCount = count($theirDiscard);
  $response->opponentDiscardCard = JSONRenderedCard(count($theirDiscard) > 0 ? $theirDiscard[0] : $blankZone);

  // TODO: Might need to put pitch as an array so FE can "stack" them like in the current UI
  $response->opponentPitchCount = count($theirPitch);
  $response->opponentPitchCard = JSONRenderedCard((count($theirPitch) > 0 ? $theirPitch[0] : $blankZone));

  $response->opponentDeckCount = count($theirDeck);
  $response->opponentDeckCard = JSONRenderedCard(count($theirDeck) > 0 ? $TheirCardBack : $blankZone);

  $response->opponentBanishCount = count($theirBanish);
  $response->opponentBanishCard = JSONRenderedCard((count($theirBanish) > 0 ? ($theirBanish[1] == "INT" ? $TheirCardBack : $theirBanish[0]) : $blankZone));

  //Now display their character and equipment
  $numWeapons = 0;
  $characterContents = array();
  for ($i = 0; $i < count($theirCharacter); $i += CharacterPieces()) {
    if($i > 0 && $inGameStatus == "0") continue;
    $atkCounters = 0;
    $counters = 0;
    $type = CardType($theirCharacter[$i]); //NOTE: This is not reliable type
    $sType = CardSubType($theirCharacter[$i]);
    if ($type == "W") {
      ++$numWeapons;
      if ($numWeapons > 1) {
        $type = "E";
        $sType = "Off-Hand";
      }
    }
    if (CardType($theirCharacter[$i]) == "W") $atkCounters = $theirCharacter[$i + 3];
    if ($theirCharacter[$i + 2] > 0) $counters = $theirCharacter[$i + 2];
    $counters = $theirCharacter[$i + 1] != 0 ? $counters : 0;
    array_push($characterContents, JSONRenderedCard(cardNumber:$theirCharacter[$i], overlay: ($theirCharacter[$i + 1] != 2 ? 1 : 0), counters:$counters, defCounters:$theirCharacter[$i+4], atkCounters: $atkCounters, controller:$otherPlayer, type:$type, sType:$sType, isFrozen:($theirCharacter[$i + 8] == 1), onChain:($theirCharacter[$i + 6] == 1), isBroken:($theirCharacter[$i + 1] == 0)));
  }
  $response->opponentEquipment = $characterContents;

  // my hand contents
  $restriction = "";
  $actionType = $turn[0] == "ARS" ? 4 : 27;
  if (strpos($turn[0], "CHOOSEHAND") !== false && ($turn[0] != "MULTICHOOSEHAND" || $turn[0] != "MAYMULTICHOOSEHAND")) $actionType = 16;
  $myHandContents = array();
  for ($i = 0; $i < count($myHand); ++$i) {
    if ($playerID == 3) {
      array_push($myHandContents, JSONRenderedCard(cardNumber: $MyCardBack, controller: 2));
    } else {
      if ($playerID == $currentPlayer) $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      else $playable = false;
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionTypeOut = (($currentPlayer == $playerID) && $playable == 1 ? $actionType : 0);
      if($restriction != "") $restriction = implode("_", explode(" ", $restriction));
      $actionDataOverride = (($actionType == 16 || $actionType == 27) ? strval($i) : "");
      array_push($myHandContents, JSONRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction));
    }
  }
  $response->playerHand = $myHandContents;

  // TODO: Need to fudge for banish UI stuff (or should this be done on the FE?)
  // if ($playerID != 3)
  // {
  //   $banishUI = BanishUIMinimal("HAND");
  //   echo($banishUI);
  // }
  // echo ("<br>"); //End hand div

  //My Health
  $response->playerHealth = $myHealth;
  //My soul count
  $response->playerSoulCount = count($mySoul);

  $response->playerDiscardCount = count($myDiscard);
  $response->playerDiscardCard = JSONRenderedCard(count($myDiscard) > 0 ? $myDiscard[0] : $blankZone);

  // TODO: Might need to put pitch as an array so FE can "stack" them like in the current UI
  $response->playerPitchCount = count($myPitch);
  $response->playerPitchCard = JSONRenderedCard((count($myPitch) > 0 ? $myPitch[0] : $blankZone));

  $response->playerDeckCount = count($myDeck);
  $response->playerDeckCard = JSONRenderedCard(count($myDeck) > 0 ? $MyCardBack : $blankZone);

  $response->playerBanishCount = count($myBanish);
  $response->playerBanishCard = JSONRenderedCard((count($myBanish) > 0 ? $myBanish[0] : $blankZone));

  //Now display my character and equipment
  $numWeapons = 0;
  $myCharData = array();
  for ($i = 0; $i < count($myCharacter); $i += CharacterPieces()) {
    $restriction = "";
    $counters = 0;
    $atkCounters = 0;
    if (CardType($myCharacter[$i]) == "W") $atkCounters = $myCharacter[$i + 3];
    if ($myCharacter[$i + 2] > 0) $counters = $myCharacter[$i + 2];
    $playable = $playerID == $currentPlayer && $myCharacter[$i + 1] == 2 && IsPlayable($myCharacter[$i], $turn[0], "CHAR", $i, $restriction);
    $border = CardBorderColor($myCharacter[$i], "CHAR", $playable);
    $type = CardType($myCharacter[$i]);
    $sType = CardSubType($myCharacter[$i]);
    if ($type == "W") {
      ++$numWeapons;
      if ($numWeapons > 1) {
        $type = "E";
        $sType = "Off-Hand";
      }
    }
    $gem = 0;
    if ($myCharacter[$i + 9] != 2 && $myCharacter[$i + 1] != 0 && $playerID != 3) {
      $gem = ($myCharacter[$i + 9] == 1 ? 1 : 2);
    }
    $restriction = implode("_", explode(" ", $restriction));
    array_push($myCharData, JSONRenderedCard($myCharacter[$i], $currentPlayer == $playerID && $playable ? 3 : 0, $myCharacter[$i + 1] != 2 ? 1 : 0, $border, $myCharacter[$i + 1] != 0 ? $counters : 0, strval($i), 0, $myCharacter[$i + 4], $atkCounters, $playerID, $type, $sType, $restriction, $myCharacter[$i + 1] == 0, $myCharacter[$i + 6] == 1, $myCharacter[$i + 8] == 1, $gem));
  }
  $response->playerEquipment = $myCharData;

  // current chain link attack
  $totalAttack = 0;
  $totalDefense = 0;
  if(count($combatChain) > 0)
  {
    $chainAttackModifiers = [];
    EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
  }
  $response->totalAttack = $totalAttack;

  // current chain link defence
  $response->totalDefence = $totalDefense;

   // what's up their arse
   $theirArse = array();
   if ($theirArsenal != "") {
    for ($i = 0; $i < count($theirArsenal); $i += ArsenalPieces()) {
      if ($theirArsenal[$i + 1] == "UP") {
        array_push($theirArse, JSONRenderedCard(cardNumber: $theirArsenal[$i], controller: ($playerID == 1 ? 2 : 1)));
      } else array_push($theirArse, (JSONRenderedCard(cardNumber: $TheirCardBack, controller: ($playerID == 1 ? 2 : 1))));
    }
   }
   $response->opponentArse = $theirArse;

   // what's up my arse
    $myArse = array();
   if ($myArsenal != "") {
    for ($i = 0; $i < count($myArsenal); $i += ArsenalPieces()) {
      if ($playerID == 3 && $myArsenal[$i + 1] != "UP") {
        array_push($myArse, JSONRenderedCard(cardNumber: $MyCardBack, controller: 2));
      } else {
        if ($playerID == $currentPlayer) $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
        else $playable = false;
        $border = CardBorderColor($myHand[$i], "HAND", $playable);
        $actionTypeOut = (($currentPlayer == $playerID) && $playable == 1 ? $actionType : 0);
        if($restriction != "") $restriction = implode("_", explode(" ", $restriction));
        $actionDataOverride = (($actionType == 16 || $actionType == 27) ? strval($i) : "");
        array_push($myArse, JSONRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction));
      }
    }
   }
   $response->playerArse = $myArse;

   // Chain Links, how many are there and do they do things?
   $chainLinkOutput = array();
   for ($i = 0; $i < count($chainLinks); ++$i) {
    $damage = $chainLinkSummary[$i * ChainLinkSummaryPieces()];
    array_push($chainLinkOutput, $damage > 0 ? "hit" : "no-hit");
   }
   $response->combatChainLinks = $chainLinkOutput;

   // their allies
   $theirAlliesOutput = array();
   $theirAllies = GetAllies($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirAllies); $i+=AllyPieces()) {
    $type = CardType($theirAllies[$i]);
    $sType = CardSubType($theirAllies[$i]);
    array_push($theirAlliesOutput, JSONRenderedCard(cardNumber:$theirAllies[$i], overlay: ($theirAllies[$i + 1] != 2 ? 1 : 0), counters:$theirAllies[$i+6], lifeCounters:$myAllies[$i+2], controller:$otherPlayer, type:$type, sType:$sType, isFrozen:($$theirAllies[$i + 3] == 1)));
   }
   $response->opponentAllies = $theirAlliesOutput;

   //their auras
   $theirAurasOutput = array();
   $theirAuras = GetAuras($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirAuras); $i+=AuraPieces()) {
     $type = CardType($theirAuras[$i]);
     $sType = CardSubType($theirAuras[$i]);
     array_push($theirAurasOutput, JSONRenderedCard(cardNumber:$theirAuras[$i], overlay: ($theirAuras[$i + 1] != 2 ? 1 : 0), counters:$theirAuras[$i+2], controller:$otherPlayer, type:$type, sType:$sType));
   }
   $response->opponentAuras = $theirAurasOutput;

   //their items
   $theirItemsOutput = array();
   $theirItems = GetItems($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirItems); $i+=ItemPieces()) {
     $type = CardType($theirItems[$i]);
     $sType = CardSubType($theirItems[$i]);
     array_push($theirItemsOutput, JSONRenderedCard(cardNumber:$theirItems[$i], overlay: ($theirItems[$i + 2] != 2 ? 1 : 0), counters:$theirItems[$i+1], controller:$otherPlayer, type:$type, sType:$sType));
   }
   $response->opponentItems = $theirItemsOutput;

   //their permanents
   $theirPermanentsOutput = array();
   $theirPermanents = GetPermanents($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirPermanents); $i+=PermanentPieces()) {
     $type = CardType($theirPermanents[$i]);
     $sType = CardSubType($theirPermanents[$i]);
     array_push($theirPermanentsOutput, JSONRenderedCard(cardNumber:$theirPermanents[$i], controller:$otherPlayer, type:$type, sType:$sType));
   }
    $response->opponentPermanents = $theirPermanentsOutput;

   //my allies
    $myAlliesOutput = array();
    $myAllies = GetAllies($playerID == 1 ? 1 : 2);
    for ($i = 0; $i < count($myAllies); $i+=AllyPieces()) {
     $type = CardType($myAllies[$i]);
     $sType = CardSubType($myAllies[$i]);
     array_push($myAlliesOutput, JSONRenderedCard(cardNumber:$myAllies[$i], overlay: ($myAllies[$i + 1] != 2 ? 1 : 0), counters:$myAllies[$i+6], lifeCounters:$myAllies[$i+2], controller:$playerID, type:$type, sType:$sType, isFrozen:($myAllies[$i + 3] == 1)));
    }
    $response->playerAllies = $myAlliesOutput;

   //my auras
   $myAurasOutput = array();
   $myAuras = GetAuras($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myAuras); $i+=AuraPieces()) {
     $type = CardType($myAuras[$i]);
     $sType = CardSubType($myAuras[$i]);
     array_push($myAurasOutput, JSONRenderedCard(cardNumber:$myAuras[$i], overlay: ($myAuras[$i + 1] != 2 ? 1 : 0), counters:$myAuras[$i+2], controller:$otherPlayer, type:$type, sType:$sType));
   }
   $response->playerAuras = $myAurasOutput;

   //my items
   $myItemsOutput = array();
   $myItems = GetItems($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myItems); $i+=ItemPieces()) {
     $type = CardType($myItems[$i]);
     $sType = CardSubType($myItems[$i]);
     array_push($myItemsOutput, JSONRenderedCard(cardNumber:$myItems[$i], overlay: ($myItems[$i + 2] != 2 ? 1 : 0), counters:$myItems[$i+1], controller:$otherPlayer, type:$type, sType:$sType));
   }
   $response->playerItems = $myItemsOutput;

   //my permanents
   $myPermanentsOutput = array();
   $myPermanents = GetPermanents($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myPermanents); $i+=PermanentPieces()) {
     $type = CardType($myPermanents[$i]);
     $sType = CardSubType($myPermanents[$i]);
     array_push($myPermanentsOutput, JSONRenderedCard(cardNumber:$myPermanents[$i], controller:$otherPlayer, type:$type, sType:$sType));
   }
   $response->playerPermanents = $myPermanentsOutput;

   //Now the log!
   $response->chatLog = JSONLog($gameName, $playerID);

  // opponent name
   $response->opponentName = "Big Dum Dum";

   // my name
   $response->playerName = "Our heroic main player";

   // TODO: Array of opponent effects
   // opponent effects (array of JSONRenderedCard)
   $opponentEffects = array();
   array_push($opponentEffects, JSONRenderedCard('WTR001'));
   $response->opponentEffects = $opponentEffects;

   // TODO: Array of player effects
   // my effects (array of JSONRenderedCard)
   $playerEffects = array();
   array_push($playerEffects, JSONRenderedCard('UPR073'));
   $response->playerEffects = $playerEffects;

   // TODO: determine the turnPhase and what corresponds to what.
   // phase of the turn (for the tracker widget)
   $response->turnPhase = 1;

   // do we have priority?
   $response->havePriority = $currentPlayer == $playerID ? true : false;

   // TODO: communicate this to the player
   $activePlayerMustMakeAChoice = false;
   // the player has a window and needs to decide something.
   if ($activePlayerMustMakeAChoice) {
    $selectionWindow = new stdClass();
    $selectionWindow->text = "You played an art of war you must select a bunch of stuff";
    $selectionWindow->isMultipleSelection = true;
    // this array could contain an array of strings. or an array of JSONCardObjects I guess.
    $selectionWindow->options = array('+1{{atk}} and +1{{def}} until end of turn', 'banish a card draw 2', 'play a defending card from Arsenal (aka you\'re losing)', 'Next attack you play gains **go again**');
    $response->selectionWindow = $selectionWindow;
   }
   $response->youMustMakeAChoice = $activePlayerMustMakeAChoice;

   // TODO: opponent APs
   $response->opponentAP = 0;

   // TODO: player APs
   $response->playerAP = 0;

   // TODO: last played Card
   $response->lastPlayedCard = NULL;

   echo json_encode($response);
   exit;
}

function PlayableCardBorderColor($cardID)
{
  if (HasReprise($cardID) && RepriseActive()) return 3;
  return 0;
}

function ChoosePopup($zone, $options, $mode, $caption = "", $zoneSize = 1)
{
  global $cardSize;
  $content = "";
  $options = explode(",", $options);

  $content .= "<table style='border-spacing:0; border-collapse: collapse;'><tr>";
  for ($i = 0; $i < count($options); ++$i) {
    $content .= "<td style='display: inline-block;'>";
    $content .= "<div class='container'>";
    $content .= "<label class='multichoose'>" . Card($zone[$options[$i]], "concat", $cardSize, $mode, 1, 0, 0, 0, strval($options[$i])) . "</label>";
    $content .= "<div class='overlay'><div class='text'>Select</div></div></div></td>";
  }
  $content .= "</tr></table>";
  echo CreatePopup("CHOOSEZONE", [], 0, 1, $caption, 1, $content);
}

function GetCharacterLeft($cardType, $cardSubType)
{
  global $cardWidth;
  switch ($cardType) {
    case "C":
      return "calc(50% - " . ($cardWidth / 2 + 5) . "px)";
      //case "W": return "calc(50% " . ($cardSubType == "" ? "- " : "+ ") . ($cardWidth/2 + $cardWidth + 10) . "px)";//TODO: Second weapon
    case "W":
      return "calc(50% - " . ($cardWidth / 2 + $cardWidth + 25) . "px)"; //TODO: Second weapon
    default:
      break;
  }
  switch ($cardSubType) {
    case "Head":
      return "95px";
    case "Chest":
      return "95px";
    case "Arms":
      return ($cardWidth + 115) . "px";
    case "Legs":
      return "95px";
    case "Off-Hand":
      return "calc(50% + " . ($cardWidth / 2 + 15) . "px)";
  }
}

function GetCharacterBottom($cardType, $cardSubType)
{
  global $cardSize;
  switch ($cardType) {
    case "C":
      return ($cardSize * 2 - 25) . "px";
    case "W":
      return ($cardSize * 2 - 25) . "px"; //TODO: Second weapon
    default:
      break;
  }
  switch ($cardSubType) {
    case "Head":
      return ($cardSize * 2 - 25) . "px";
    case "Chest":
      return ($cardSize - 10) . "px";
    case "Arms":
      return ($cardSize - 10) . "px";
    case "Legs":
      return "5px";
    case "Off-Hand":
      return ($cardSize * 2 - 25) . "px";
  }
}

function GetCharacterTop($cardType, $cardSubType)
{
  global $cardSize;
  switch ($cardType) {
    case "C":
      return "52px";
    case "W":
      return "52px"; //TODO: Second weapon
      //case "C": return ($cardSize + 20) . "px";
      //case "W": return ($cardSize + 20) . "px";//TODO: Second weapon
    default:
      break;
  }
  switch ($cardSubType) {
    case "Head":
      return "5px";
    case "Chest":
      return ($cardSize - 10) . "px";
    case "Arms":
      return ($cardSize - 10) . "px";
    case "Legs":
      return ($cardSize * 2 - 25) . "px";
    case "Off-Hand":
      return "52px";
  }
}

function GetZoneRight($zone)
{
  global $cardWidth, $rightSideWidth;
  switch ($zone) {
    case "DISCARD":
      return intval($rightSideWidth * 1.08) . "px";
    case "DECK":
      return intval($rightSideWidth * 1.08) . "px";
    case "BANISH":
      return intval($rightSideWidth * 1.08) . "px";
    case "PITCH":
      return (intval($rightSideWidth * 1.18) + $cardWidth) . "px";
  }
}

function GetZoneBottom($zone)
{
  global $cardSize;
  switch ($zone) {
    case "MYDISCARD":
      return ($cardSize * 2 - 25) . "px";
    case "MYDECK":
      return ($cardSize - 10) . "px";
    case "MYBANISH":
      return (5) . "px";
    case "MYPITCH":
      return ($cardSize - 10) . "px";
  }
}

function GetZoneTop($zone)
{
  global $cardSize;
  switch ($zone) {
    case "THEIRDISCARD":
      return ($cardSize * 2 - 25) . "px";
    case "THEIRDECK":
      return ($cardSize - 10) . "px";
    case "THEIRBANISH":
      return (5) . "px";
    case "THEIRPITCH":
      return ($cardSize - 10) . "px";
  }
}

function IsTileable($cardID)
{
  switch ($cardID) {
    case "WTR075":
    case "ARC112":
    case "CRU197":
    case "MON186":
    case "ELE111":
    case "UPR043":
      return true;
    default:
      return false;
  }
}

function DisplayTiles($player)
{
  global $cardSizeAura, $playerID;
  $auras = GetAuras($player);

  $count = 0;
  $first = -1;
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if ($auras[$i] == "WTR075") {
      if ($count == 0) $first = $i;
      ++$count;
    }
  }
  if ($count > 0) {
    echo ("<div style='position:relative; display: inline-block;'>");
    echo (Card("WTR075", "concat", $cardSizeAura, 0, 1, 0, 0, ($count > 1 ? $count : 0)) . "&nbsp");
    DisplayPriorityGem(($player == $playerID ? $auras[$first + 7] : $auras[$first + 8]), "AURAS-" . $first, ($player != $playerID ? 1 : 0));
    echo ("</div>");
  }

  $count = 0;
  $first = -1;
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if ($auras[$i] == "ARC112") {
      if ($count == 0) $first = $i;
      ++$count;
    }
  }
  if ($count > 0) {
    echo ("<div style='position:relative; display: inline-block;'>");
    echo (Card("ARC112", "concat", $cardSizeAura, 0, 1, 0, 0, ($count > 1 ? $count : 0)) . "&nbsp");
    DisplayPriorityGem(($player == $playerID ? $auras[$first + 7] : $auras[$first + 8]), "AURAS-" . $first, ($player != $playerID ? 1 : 0));
    echo ("</div>");
  }


  $soulShackleCount = 0;
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if ($auras[$i] == "MON186") ++$soulShackleCount;
  }
  if ($soulShackleCount > 0) echo (Card("MON186", "concat", $cardSizeAura, 0, 1, 0, 0, ($soulShackleCount > 1 ? $soulShackleCount : 0)) . "&nbsp");

  $frostbiteCount = 0;
  $first = -1;
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if ($auras[$i] == "ELE111") {
      if ($count == 0) $first = $i;
      ++$frostbiteCount;
    }
  }
  if ($frostbiteCount > 0) {
    echo ("<div style='position:relative; display: inline-block;'>");
    echo (Card("ELE111", "concat", $cardSizeAura, 0, 1, 0, 0, ($frostbiteCount > 1 ? $frostbiteCount : 0)) . "&nbsp");
    DisplayPriorityGem(($player == $playerID ? $auras[$first + 7] : $auras[$first + 8]), "AURAS-" . $first, ($player != $playerID ? 1 : 0));
    echo ("</div>");
  }

  $items = GetItems($player);
  $copperCount = -1;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    if ($items[$i] == "CRU197") ++$copperCount;
  }
  if ($copperCount > 0) echo (Card("CRU197", "concat", $cardSizeAura, 0, 1, 0, 0, ($copperCount > 1 ? $copperCount : 0)) . "&nbsp");

  $permanents = GetPermanents($player);
  $ashCount = 0;
  for ($i = 0; $i < count($permanents); $i += PermanentPieces()) {
    if ($permanents[$i] == "UPR043") ++$ashCount;
  }
  if ($ashCount > 0) echo (Card("UPR043", "concat", $cardSizeAura, 0, 1, 0, 0, ($ashCount > 1 ? $ashCount : 0)) . "&nbsp");
}

function GetPhaseHelptext()
{
  global $turn;
  $defaultText = "Choose " . TypeToPlay($turn[0]);
  return (GetDQHelpText() != "-" ? implode(" ", explode("_", GetDQHelpText())) : $defaultText);
}

function DisplayPriorityGem($setting, $MZindex, $otherPlayer = 0)
{
  global $cardWidth, $playerID;
  if ($otherPlayer != 0) {
    $position = "top: 60px;";
  } else {
    $position = "bottom: 5px;";
  }
  if ($setting != 2 && $playerID != 3) {
    $gem = ($setting == 1 ? "hexagonRedGem.png" : "hexagonGrayGem.png");
    if ($setting == 0) echo ("<img " . ProcessInputLink($playerID, ($otherPlayer ? 104 : 103), $MZindex) . " title='Not holding priority' style='position:absolute; display: inline-block; z-index:1001; " . $position . " left:" . $cardWidth / 2 - 12 . "px; width:40px; height:40px; cursor:pointer;' src='./Images/$gem' />");
    else if ($setting == 1) echo ("<img " . ProcessInputLink($playerID, ($otherPlayer ? 104 : 103), $MZindex) . " title='Holding priority' style='position:absolute; display: inline-block; z-index:1001; " . $position . " left:" . $cardWidth / 2 - 12 . "px; width:40px; height:40px; cursor:pointer;' src='./Images/$gem' />");
  }
}
