<?php

include 'Libraries/HTTPLibraries.php';

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = TryGet("playerID", 3);
if(!is_numeric($playerID)) {
  echo ("Invalid player ID.");
  exit;
}

$authKey = TryGet("authKey", 3);
$lastUpdate = intval(TryGet("lastUpdate", 0));
$windowWidth = intval(TryGet("windowWidth", 0));
$windowHeight = intval(TryGet("windowHeight", 0));

if ($lastUpdate > 10000000) {
  $lastUpdate = 0;
}

include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";

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
    PlayerLoseHealth($otherP, 9999);
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
    echo ("1234REMATCH");
    exit;
  }

  $targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
  if ($playerID != 3 && $authKey != $targetAuth) {
    echo ("999999ENDTIMESTAMP");
    exit;
  }

  echo (GetCachePiece($gameName, 1) . "ENDTIMESTAMP");

  if ($currentPlayer == $playerID) {
    $icon = "ready.png";
    $readyText = "You are the player with priority.";
  } else {
    $icon = "notReady.png";
    $readyText = "The other player has priority.";
  }

  if (count($turn) == 0) {
    RevertGamestate();
    GamestateUpdated($gameName);
    exit();
  }

  if($windowWidth/16 > $windowHeight/9) $windowWidth = $windowHeight/9*16;

  $cardSize = ($windowWidth != 0 ? intval($windowWidth / 13) : 120);
  //$cardSize = ($windowWidth != 0 ? intval($windowWidth / 16) : 120);
  if (!IsDynamicScalingEnabled($playerID)) $cardSize = 120; //Temporarily disable dynamic scaling
  $rightSideWidth = (IsDynamicScalingEnabled($playerID) ? intval($windowWidth * 0.15) : 200);
  $cardSizeAura = intval($cardSize * .8); //95;
  $cardSizeEquipment = intval($cardSize * .8);
  $cardEquipmentWidth = intval($cardSizeEquipment * 0.71);
  $cardWidth = intval($cardSize * 0.72);
  $cardHeight = $cardWidth;
  $cardIconSize = intval($cardSize / 3); //40
  $cardIconLeft = intval($cardSize / 4); //30
  $cardIconTop = intval($cardSize / 4); //30
  $bigCardSize = intval($cardSize * 1.667); //200;
  $permLeft = intval(GetCharacterLeft("E", "Arms")) + $cardWidth + 20;
  $permWidth = "calc(50% - " . ($cardWidth * 2 + 30 + $permLeft) . "px)";
  $permHeight = $cardSize * 2 + 20;
  $counterHeight = IsDynamicScalingEnabled($playerID) ? intval($cardSize / 4.6) : 28;

  $darkMode = IsDarkMode($playerID);
  $manualMode = IsManualMode($playerID);

  if ($darkMode) $backgroundColor = "rgba(74, 74, 74, 0.9)";
  else $backgroundColor = "rgba(235, 235, 235, 0.9)";

  $blankZone = ($darkMode ? "blankZoneDark" : "blankZone");
  $borderColor = ($darkMode ? "#DDD" : "#1a1a1a");
  $fontColor = ($darkMode ? "#1a1a1a" : "#DDD");

  //Choose Cardback
  $MyCardBack = "CardBack";
  if (IsCardBackBlackMode($playerID)) {
    $MyCardBack = "CBBlack";
  } else if (IsCardBackCreamMode($playerID)) {
    $MyCardBack = "CBCreamWhite";
  } else if (IsCardBackGoldMode($playerID)) {
    $MyCardBack = "CBGold";
  } else if (IsCardBackGreyMode($playerID)) {
    $MyCardBack = "CBWhite";
  } else if (IsCardBackRedMode($playerID)) {
    $MyCardBack = "CBRed";
  } else if (IsCardBackTanMode($playerID)) {
    $MyCardBack = "CBParchment";
  }

  $otherPlayer = ($playerID == 1 ? 2 : 1);
  $TheirCardBack = "CardBack";
  if (IsCardBackBlackMode($otherPlayer)) {
    $TheirCardBack = "CBBlack";
  } else if (IsCardBackCreamMode($otherPlayer)) {
    $TheirCardBack = "CBCreamWhite";
  } else if (IsCardBackGoldMode($otherPlayer)) {
    $TheirCardBack = "CBGold";
  } else if (IsCardBackGreyMode($otherPlayer)) {
    $TheirCardBack = "CBWhite";
  } else if (IsCardBackRedMode($otherPlayer)) {
    $TheirCardBack = "CBRed";
  } else if (IsCardBackTanMode($otherPlayer)) {
    $TheirCardBack = "CBParchment";
  }


  if ($turn[0] == "PDECK" || $turn[0] == "ARS" || (count($layers) > 0 && $layers[0] == "ENDTURN")) {
    $passLabel = "End Turn";
    $fontSize = 30;
    $left = 65;
    $top = 20;
  } else {
    $passLabel = "Pass";
    $fontSize = 36;
    $left = 85;
    $top = 15;
  }

  echo("<BR>");
  //Display combat chain
  $combatChainContents = "";
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    //$action = $currentPlayer == $playerID && $turn[0] != "P" && $currentPlayer == $combatChain[$i + 1] && AbilityPlayableFromCombatChain($combatChain[$i]) && IsPlayable($combatChain[$i], $turn[0], "PLAY", $i) ? 21 : 0;
    //$actionDisabled = 0;
    //echo (Card($combatChain[$i], "concat", $cardSize, $action, 1, $actionDisabled, $combatChain[$i + 1] == $playerID ? 1 : 2, 0, strval($i)));
    if($combatChainContents != "") $combatChainContents .= "|";
    $combatChainContents .= ClientRenderedCard(cardNumber: $combatChain[$i], controller: $combatChain[$i+1]);
  }
  echo($combatChainContents . "<BR>");

  //Display layer
  $layerContents = "";
  for ($i = count($layers) - LayerPieces(); $i >= 0; $i -= LayerPieces()) {
    $layerName = ($layers[$i] == "LAYER" || $layers[$i] == "TRIGGER" ? $layers[$i + 2] : $layers[$i]);
    //$content .= Card($layerName, "concat", $cardSize, 0, 1, 0, $layers[$i + 1] == $playerID || $playerID == 3 ? 1 : 2, controller:$layers[$i+1]);
    if($layerContents != "") $layerContents .= "|";
    $layerContents .= ClientRenderedCard(cardNumber: $layerName, controller:$layers[$i+1]);
  }
  echo($layerContents . "<BR>");


  //Opponent Hand
  $handContents = "";
  for ($i = 0; $i < count($theirHand); ++$i) {
    if($handContents != "") $handContents .= "|";
    $handContents .= ClientRenderedCard(cardNumber: $TheirCardBack, controller: ($playerID == 1 ? 2 : 1));
  }
  echo($handContents . "<BR>");

  //Their Health
  echo($theirHealth . "<BR>");
  //Their soul count
  echo(count($theirSoul) . "<BR>");

  //Display their discard, pitch, deck, and banish
  $theirZoneContents = count($theirDiscard) . " " . (count($theirDiscard) > 0 ? $theirDiscard[0] : $blankZone);
  $theirZoneContents .= "|" . count($theirPitch) . " " . (count($theirPitch) > 0 ? $theirPitch[0] : $blankZone);
  $theirZoneContents .= "|" . count($theirDeck) . " " . $TheirCardBack;
  $theirZoneContents .= "|" . count($theirBanish) . " " . (count($theirBanish) > 0 ? ($theirBanish[1] == "INT" ? $TheirCardBack : $theirBanish[0]) : $blankZone);
  echo($theirZoneContents . "<BR>");


  //Now display their character and equipment
  $numWeapons = 0;
  $characterContents = "";
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
    if($characterContents != "") $characterContents .= "|";
    $characterContents .= ClientRenderedCard(cardNumber:$theirCharacter[$i], overlay: ($theirCharacter[$i + 1] != 2 ? 1 : 0), counters:$counters, defCounters:$theirCharacter[$i+4], atkCounters: $atkCounters, controller:$otherPlayer, type:$type, sType:$sType, isFrozen:($theirCharacter[$i + 8] == 1), onChain:($theirCharacter[$i + 6] == 1), isBroken:($theirCharacter[$i + 1] == 0));
  }
  echo($characterContents);

  echo ("<br>");


  // my hand contents
  $restriction = "";
  $actionType = $turn[0] == "ARS" ? 4 : 27;
  if (strpos($turn[0], "CHOOSEHAND") !== false && ($turn[0] != "MULTICHOOSEHAND" || $turn[0] != "MAYMULTICHOOSEHAND")) $actionType = 16;
  $handContents = "";
  for ($i = 0; $i < count($myHand); ++$i) {
    if($handContents != "") $handContents .= "|";
    if ($playerID == 3) {
      $handContents .= ClientRenderedCard(cardNumber: $MyCardBack, controller: 2);
    } else {
      if ($playerID == $currentPlayer) $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      else $playable = false;
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionTypeOut = (($currentPlayer == $playerID) && $playable == 1 ? $actionType : 0);
      if($restriction != "") $restriction = implode("_", explode(" ", $restriction));
      $actionDataOverride = (($actionType == 16 || $actionType == 27) ? strval($i) : "");
      $handContents .= ClientRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction);
    }
  }
  echo($handContents);


  if ($playerID != 3)
  {
    $banishUI = BanishUIMinimal("HAND");
    if($handContents != "" && $banishUI != "") echo("|");
    echo($banishUI);
  }
  echo ("<br>"); //End hand div

  //My Health
  echo($myHealth . "<BR>");
  //My soul count
  echo(count($mySoul) . "<BR>");

  //Display my discard, pitch, deck, and banish
  $myZoneContents = count($myDiscard) . " " . (count($myDiscard) > 0 ? $myDiscard[0] : $blankZone);
  $myZoneContents .= "|" . count($myPitch) . " " . (count($myPitch) > 0 ? $myPitch[0] : $blankZone);
  $myZoneContents .= "|" . count($myDeck) . " " . $MyCardBack;
  $myZoneContents .= "|" . count($myBanish) . " " . (count($myBanish) > 0 ? $myBanish[0] : $blankZone);
  echo($myZoneContents . "<BR>");

  //Now display my character and equipment
  $numWeapons = 0;
  $myCharData = "";
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
    if($myCharData != "") $myCharData .= "|";
    $gem = 0;
    if ($myCharacter[$i + 9] != 2 && $myCharacter[$i + 1] != 0 && $playerID != 3) {
      $gem = ($myCharacter[$i + 9] == 1 ? 1 : 2);
    }
    $restriction = implode("_", explode(" ", $restriction));
    $myCharData .= ClientRenderedCard($myCharacter[$i], $currentPlayer == $playerID && $playable ? 3 : 0, $myCharacter[$i + 1] != 2 ? 1 : 0, $border, $myCharacter[$i + 1] != 0 ? $counters : 0, strval($i), 0, $myCharacter[$i + 4], $atkCounters, $playerID, $type, $sType, $restriction, $myCharacter[$i + 1] == 0, $myCharacter[$i + 6] == 1, $myCharacter[$i + 8] == 1, $gem);

  }
  echo($myCharData);
  echo ("<br>");

  // current chain link attack
  $totalAttack = 0;
  $totalDefense = 0;
  if(count($combatChain) > 0)
  {
    $chainAttackModifiers = [];
    EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
  }
  echo($totalAttack);
  echo("<br>");

  // current chain link defence
  echo($totalDefense);
  echo("<br>");

   // what's up their arse
   $theirArse = "";
   if ($theirArsenal != "") {
    for ($i = 0; $i < count($theirArsenal); $i += ArsenalPieces()) {
      if ($theirArse != "") $theirArse .= "|";
      if ($theirArsenal[$i + 1] == "UP") {
        $theirArse .= ClientRenderedCard(cardNumber: $theirArsenal[$i], controller: ($playerID == 1 ? 2 : 1));
      } else $theirArse .= (ClientRenderedCard(cardNumber: $TheirCardBack, controller: ($playerID == 1 ? 2 : 1)));
    }
   }
   echo($theirArse);
   echo("<br>");

   // what's up my arse
    $myArse = "";
   if ($myArsenal != "") {
    for ($i = 0; $i < count($myArsenal); $i += ArsenalPieces()) {
    if ($myArse != "") $myArse .= "|";
    if ($playerID == 3 && $myArsenal[$i + 1] != "UP") {
      $myArse .= ClientRenderedCard(cardNumber: $MyCardBack, controller: 2);
    } else {
      if ($playerID == $currentPlayer) $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      else $playable = false;
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionTypeOut = (($currentPlayer == $playerID) && $playable == 1 ? $actionType : 0);
      if($restriction != "") $restriction = implode("_", explode(" ", $restriction));
      $actionDataOverride = (($actionType == 16 || $actionType == 27) ? strval($i) : "");
      $myArse .= ClientRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction);
    }
    }
   }
   echo($myArse);
   echo("<br>");

   // Chain Links, how many are there and do they do things?
   $chainLinkOutput = "";
   for ($i = 0; $i < count($chainLinks); ++$i) {
    if ($chainLinkOutput != "") $chainLinkOutput .= "|";
    $damage = $chainLinkSummary[$i * ChainLinkSummaryPieces()];
    $chainLinkOutput .= ($damage > 0 ? "hit" : "no-hit");
   }
   echo($chainLinkOutput);
   echo("<br>");

   // their allies
   $theirAlliesOutput = "";
   $theirAllies = GetAllies($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirAllies); $i+=AllyPieces()) {
    if ($theirAlliesOutput != "") $theirAlliesOutput .= "|";
    $type = CardType($theirAllies[$i]);
    $sType = CardSubType($theirAllies[$i]);
    $theirAlliesOutput .= ClientRenderedCard(cardNumber:$theirAllies[$i], overlay: ($theirAllies[$i + 1] != 2 ? 1 : 0), counters:$theirAllies[$i+6], controller:$otherPlayer, type:$type, sType:$sType, isFrozen:($myAllies[$i + 3] == 1));
   }
   echo($theirAlliesOutput . "<BR>");

   //their auras
   $theirAurasOutput = "";
   $theirAuras = GetAuras($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirAuras); $i+=AuraPieces()) {
     if ($theirAurasOutput != "") $theirAurasOutput .= "|";
     $type = CardType($theirAuras[$i]);
     $sType = CardSubType($theirAuras[$i]);
     $theirAurasOutput .= ClientRenderedCard(cardNumber:$theirAuras[$i], overlay: ($theirAuras[$i + 1] != 2 ? 1 : 0), counters:$theirAuras[$i+2], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($theirAurasOutput . "<BR>");

   //their items
   $theirItemsOutput = "";
   $theirItems = GetItems($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirItems); $i+=ItemPieces()) {
     if ($theirItemsOutput != "") $theirItemsOutput .= "|";
     $type = CardType($theirItems[$i]);
     $sType = CardSubType($theirItems[$i]);
     $theirItemsOutput .= ClientRenderedCard(cardNumber:$theirItems[$i], overlay: ($theirItems[$i + 2] != 2 ? 1 : 0), counters:$theirItems[$i+1], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($theirItemsOutput . "<BR>");

   //their permanents
   $theirPermanentsOutput = "";
   $theirPermanents = GetPermanents($playerID == 1 ? 2 : 1);
   for ($i = 0; $i < count($theirPermanents); $i+=PermanentPieces()) {
     if ($theirPermanentsOutput != "") $theirPermanentsOutput .= "|";
     $type = CardType($theirPermanents[$i]);
     $sType = CardSubType($theirPermanents[$i]);
     $theirPermanentsOutput .= ClientRenderedCard(cardNumber:$theirPermanents[$i], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($theirPermanentsOutput . "<BR>");


   //my allies
    $myAlliesOutput = "";
    $myAllies = GetAllies($playerID == 1 ? 1 : 2);
    for ($i = 0; $i < count($myAllies); $i+=AllyPieces()) {
     if ($myAlliesOutput != "") $myAlliesOutput .= "|";
     $type = CardType($myAllies[$i]);
     $sType = CardSubType($myAllies[$i]);
     $myAlliesOutput .= ClientRenderedCard(cardNumber:$myAllies[$i], overlay: ($myAllies[$i + 1] != 2 ? 1 : 0), counters:$myAllies[$i+6], controller:$otherPlayer, type:$type, sType:$sType, isFrozen:($myAllies[$i + 3] == 1));
    }
    echo($myAlliesOutput . "<BR>");

   //my auras
   $myAurasOutput = "";
   $myAuras = GetAuras($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myAuras); $i+=AuraPieces()) {
     if ($myAurasOutput != "") $myAurasOutput .= "|";
     $type = CardType($myAuras[$i]);
     $sType = CardSubType($myAuras[$i]);
     $myAurasOutput .= ClientRenderedCard(cardNumber:$myAuras[$i], overlay: ($myAuras[$i + 1] != 2 ? 1 : 0), counters:$myAuras[$i+2], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($myAurasOutput . "<BR>");

   //my items
   $myItemsOutput = "";
   $myItems = GetItems($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myItems); $i+=ItemPieces()) {
     if ($myItemsOutput != "") $myItemsOutput .= "|";
     $type = CardType($myItems[$i]);
     $sType = CardSubType($myItems[$i]);
     $myItemsOutput .= ClientRenderedCard(cardNumber:$myItems[$i], overlay: ($myItems[$i + 2] != 2 ? 1 : 0), counters:$myItems[$i+1], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($myItemsOutput . "<BR>");

   //my permanents
   $myPermanentsOutput = "";
   $myPermanents = GetPermanents($playerID == 1 ? 1 : 2);
   for ($i = 0; $i < count($myPermanents); $i+=PermanentPieces()) {
     if ($myPermanentsOutput != "") $myPermanentsOutput .= "|";
     $type = CardType($myPermanents[$i]);
     $sType = CardSubType($myPermanents[$i]);
     $myPermanentsOutput .= ClientRenderedCard(cardNumber:$myPermanents[$i], controller:$otherPlayer, type:$type, sType:$sType);
   }
   echo($myPermanentsOutput . "<BR>");

   //Now the log!
   EchoLog($gameName, $playerID);
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
      return true;
    case "ARC112":
      return true;
    case "MON186":
      return true;
    case "ELE111":
      return true;
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

  //Remove Copper as they weren't playable if shown as tiles.

  // $items = GetItems($player);
  // $copperCount = 0;
  // for ($i = 0; $i < count($items); $i += ItemPieces()) {
  //   if ($items[$i] == "CRU197") ++$copperCount;
  // }
  // if ($copperCount > 0) echo (Card("CRU197", "concat", $cardSizeAura, 0, 1, 0, 0, ($copperCount > 1 ? $copperCount : 0)) . "&nbsp");

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
