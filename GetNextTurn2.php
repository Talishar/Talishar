<?php

include 'Libraries/HTTPLibraries.php';

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("NaNENDTIMESTAMP");
  exit;
}
$playerID = TryGet("playerID", 3);
if (!is_numeric($playerID)) {
  echo ("NaNENDTIMESTAMP");
  exit;
}

if (!file_exists("./Games/" . $gameName . "/")) {
  header('HTTP/1.0 403 Forbidden');
  exit;
}

$authKey = TryGet("authKey", 3);
$lastUpdate = intval(TryGet("lastUpdate", 0));
$windowWidth = intval(TryGet("windowWidth", 0));
$windowHeight = intval(TryGet("windowHeight", 0));
$lastCurrentPlayer = intval(TryGet("lastCurrentPlayer", 0));

if (($playerID == 1 || $playerID == 2) && $authKey == "") {
  if (isset($_COOKIE["lastAuthKey"])) $authKey = $_COOKIE["lastAuthKey"];
}

include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "WriteLog.php";

SetHeaders();

if($playerID == 3 && GetCachePiece($gameName, 9) != "1") {
  echo($playerID . " " . $gameName . " " . GetCachePiece($gameName, 9));
  header('HTTP/1.0 403 Forbidden');
  exit;
}

$isGamePlayer = $playerID == 1 || $playerID == 2;
$opponentDisconnected = false;
$opponentInactive = false;

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
while ($lastUpdate != 0 && $cacheVal <= $lastUpdate) {
  usleep(100000); //100 milliseconds
  $currentTime = round(microtime(true) * 1000);
  $readCache = ReadCache($gameName);
  if($readCache == "") break;
  $cacheArr = explode(SHMOPDelimiter(), $readCache);
  $cacheVal = intval($cacheArr[0]);
  if ($isGamePlayer) {
    SetCachePiece($gameName, $playerID + 1, $currentTime);
    $otherP = ($playerID == 1 ? 2 : 1);
    $oppLastTime = intval($cacheArr[$otherP]);
    $oppStatus = $cacheArr[$otherP + 2];
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
    }
    //Handle server timeout
    $lastUpdateTime = $cacheArr[5];
    if ($currentTime - $lastUpdateTime > 90000 && $cacheArr[11] != "1")//90 seconds
    {
      SetCachePiece($gameName, 12, "1");
      $opponentInactive = true;
      $lastUpdate = 0;
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
    include_once "./APIKeys/APIKeys.php";
    PlayerLoseHealth($otherP, GetHealth($otherP));
    include "WriteGamestate.php";
  }
  else if($opponentInactive && !IsGameOver()) {
    $currentPlayerActivity = 2;
    WriteLog("The current player is inactive.");
    include "WriteGamestate.php";
    GamestateUpdated($gameName);
  }

  if ($turn[0] == "REMATCH" && intval($playerID) != 3) {
    include "MenuFiles/ParseGamefile.php";
    include "MenuFiles/WriteGamefile.php";
    if ($gameStatus == $MGS_GameStarted) {
      include "AI/CombatDummy.php";
      $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
      if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
      $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
      if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
      $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
      SetCachePiece($gameName, 14, $gameStatus);
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

  echo ($cacheVal . "ENDTIMESTAMP");

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

  echo ("<div id='iconHolder' style='display:none;'>" . $icon . "</div>");

  if ($windowWidth / 16 > $windowHeight / 9) $windowWidth = $windowHeight / 9 * 16;

  $cardSize = ($windowWidth != 0 ? intval($windowWidth / 13) : 120);
  //$cardSize = ($windowWidth != 0 ? intval($windowWidth / 16) : 120);
  if (!IsDynamicScalingEnabled($playerID)) $cardSize = 120; //Temporarily disable dynamic scaling
  $rightSideWidth = (IsDynamicScalingEnabled($playerID) ? intval($windowWidth * 0.15) : (IsStreamerMode($playerID) ? 300 : 210));
  $cardSizeAura = intval($cardSize * .8); //95;
  $cardSizeEquipment = intval($cardSize * .8);
  $cardEquipmentWidth = intval($cardSizeEquipment * 0.71);
  $cardWidth = intval($cardSize * 0.73);
  $cardHeight = $cardWidth;
  $cardIconSize = intval($cardSize / 2.7); //40
  $cardIconLeft = intval($cardSize / 4.2); //30
  $cardIconTop = intval($cardSize / 4.2); //30
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
  $bordelessFontColor = "#DDD";

  //Choose Cardback
  $MyCardBack = GetCardBack($playerID);
  $TheirCardBack = GetCardBack($playerID == 1 ? 2 : 1);
  $otherPlayer = ($playerID == 1 ? 2 : 1);

  //Display background
  if (IsDarkPlainMode($playerID))
    echo ("<div class='container;' style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/darkplain.jpg'/>
    </div>");
  else if (IsDarkMode($playerID))
    echo ("<div class='container;' style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/flicflak.jpg'/>
    </div>");
  else if (IsPlainMode($playerID))
    echo ("<div class='container;' style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;'><img style='object-fit: cover; height:100%; width:100%;' src='./Images/lightplain.jpg'/>
    </div>");
  else
    echo ("<div class='container;' style='position:absolute; z-index:-100; left:0px; top:0px; width:100%; height:100%;
    -webkit-filter: grayscale(1); -webkit-filter: grayscale(15%); -moz-filter: grayscale(15%); filter: gray;filter: grayscale(15%);
    filter: url(data:image/svg+xml;utf8,<svg version='1.1' xmlns='http://www.w3.org/2000/svg' height='0'><filter id='greyscale'><feColorMatrix type='matrix' values='0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0.3333 0.3333 0.3333 0 0 0 0 0 1 0'/></filter></svg>
    <img style='object-fit: cover; height:100%; width:100%;' src='./Images/findCenterBackground.jpg'/></div>");

  echo ("<div style='position:absolute; right:" . intval($rightSideWidth * 1.05) . "px; top:calc(50% - 100px); height:200px; z-index:100;'>
      <span style='position:absolute; text-align:center; width:100%; font-weight: 550; font-size: 140%; top:152px; user-select: none;'>$myHealth</span>"); //Master pass button div
  echo (($manualMode ? "<span style='position:absolute; top:120px; left:65px;'>" . CreateButton($playerID, "+1", 10006, 0, "20px") . CreateButton($playerID, "-1", 10005, 0, "20px") . "</span>" : ""));
  echo ("<span style='position:absolute; text-align:center; width:100%; font-size: 140%; font-weight: 550; top:31px; user-select: none;'>$theirHealth</span>");
  echo (($manualMode ? "<span style='position:absolute; top:0px; left:65px;'>" . CreateButton($playerID, "+1", 10008, 0, "20px") . CreateButton($playerID, "-1", 10007, 0, "20px") . "</span>" : ""));
  if (IsDarkMode($playerID)) echo ("<img style='height:200px;' src='./Images/DuoLifeDark.png' />");
  else echo ("<img style='height:200px;' src='./Images/DuoLife.png' />");
  echo ("<div style='position:absolute; top:37px; left:-130px; z-index:-5;'><img style='height:125px; width:150px;' src='./Images/passBG.png' /></div>");
  if ($turn[0] == "PDECK" || $turn[0] == "ARS" || (count($layers) > 0 && $layers[0] == "ENDTURN")) {
    $passLabel = "End Turn";
    $fontSize = 30;
    $left = 65;
    $top = 20;
  } else if(IsReplay())
  {
    $passLabel = "Next";
    $fontSize = 36;
    $left = 85;
    $top = 15;
  } else {
    $passLabel = "Pass";
    $fontSize = 36;
    $left = 85;
    $top = 15;
  }

  if ((CanPassPhase($turn[0]) && $currentPlayer == $playerID) || (IsReplay() && $playerID == 3)) {
    $prompt = "";
    if ($turn[0] == "ARS" && count($myHand) > 0 && !ArsenalFull($playerID) && !IsReplay()) {
      // Prompt the player if they want to skip arsenal with cards in hand.
      $prompt = "Do you want to skip arsenal?";
    }

?>

    <div title='Space is the shortcut to pass.' <?= ProcessInputLink($playerID, 99, 0, prompt: $prompt) ?> class='passButton' style='position:absolute; top:62px; left:-200px; z-index:-1; cursor:pointer; height:75px; width:225px;'>
      <span style='position:absolute; left:<?= $left ?>px; top:<?= $top ?>px; color: <?= $fontColor ?>; font-family:Helvetica; font-size:<?= $fontSize ?>px; font-weight: 550; text-shadow: 2px 0 0 <?= $borderColor ?>, 0 -2px 0 <?= $borderColor ?>, 0 2px 0 <?= $borderColor ?>, -2px 0 0 <?= $borderColor ?>; user-select: none;'>
        <?= $passLabel ?>
      </span>
      <span style='position:absolute; bottom:7px; left:57%; -ms-transform: translateX(-50%); transform: translateX(-50%); color: <?= $fontColor ?>; font-family:Helvetica; font-size:14px; text-shadow: 2px 0 0 <?= $borderColor ?>, 0 -2px 0 <?= $borderColor ?>, 0 2px 0 <?= $borderColor ?>, -2px 0 0 <?= $borderColor ?>; user-select: none;'>
        [Space]
      </span>
    </div>

  <?php
  } else {
  ?>

    <div title='Space is the shortcut to pass.' class='passInactive' style='position:absolute; top:62px; left:-200px; z-index:-1; height:75px; width:225px;'>
      <span style='position:absolute; left:<?= $left ?>px; top:<?= $top ?>px; color:gray; font-family:Helvetica; font-size:<?= $fontSize ?>px; font-weight: 550; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; user-select: none;'>
        <?= $passLabel ?>
      </span>
      <span style='position:absolute; bottom:7px; left:57%; -ms-transform: translateX(-50%); transform: translateX(-50%); color:gray; font-family:Helvetica; font-size:14px; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; user-select: none;'>
        [Space]
      </span>
    </div>

<?php
  }

  if ($mainPlayer == $playerID || ($playerID == 3 && $mainPlayer != $otherPlayer)) {
    echo ("<div style='position:absolute; top:117px; left:-153px; z-index:-4;'><img style='height:60px; width:170px;' src='./Images/p1APTracker.png' /><span style='position:absolute; margin-top:2px; left:85; top:22; z-index:10; color:#111; font-weight:600; font-size:28px; user-select: none;'>" . $actionPoints . "AP" . "</span>");
    echo (($manualMode ? "<span style='position:absolute; top:97%; right:0; display: inline-block;';>" . CreateButton($playerID, "+1", 10002, 0, "20px") . CreateButton($playerID, "-1", 10004, 0, "20px") . "</span>" : ""));
  } else {
    echo ("<div style='position:absolute; top:24px; left:-153px; z-index:-4;'><img style='height:60px; width:170px;' src='./Images/p2APTracker.png' /><span style='position:absolute; margin-top:2px; left:85; top:8; z-index:10; color:#111; font-weight:600; font-size:28px; user-select: none;'>" . $actionPoints . "AP" . "</span>");
    echo (($manualMode ? "<span style='position:absolute; top:-60%; right:0; display: inline-block;';>" . CreateButton($playerID, "+1", 10002, 0, "20px") . CreateButton($playerID, "-1", 10004, 0, "20px") . "</span>" : ""));
  }
  echo ("</div></div>");

  //Now display the screen for this turn
  echo ("<span style='position:fixed;  bottom:0px; left:14%; right:14%; z-index:10; display:inline-block; justity-content: center; font-size:30px; text-align:center;'>");


  echo (($manualMode ? "<span style='position:relative; top: 5px; z-index:10; color: " . $fontColor . "; font-family:Helvetica; font-size:18px; font-weight: 550;text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>Add to hand: </span><input style='width: 100px;' id='manualAddCardToHand' type='text' /><input type='button' style='position:relative; font-size: 14px; top:0; left:0; bottom: 5px; box-shadow: none;' value='Add' onclick='AddCardToHand()' />&nbsp;" : ""));

  //Tell the player what to pick
  if ($turn[0] != "OVER") {
    $helpText = ($currentPlayer != $playerID ? " Waiting for other player to choose " . TypeToPlay($turn[0]) . "&nbsp" : " " . GetPhaseHelptext() . "&nbsp;");

    echo ("<span style='display:inline-block; background-color: " . $backgroundColor . "; border: 2px solid " . $borderColor . "; border-radius: 5px; font-size:22px;'><img height='22px;' style='margin-left:3px; vertical-align: -5px; user-select: none;' title='" . $readyText . "' src='./Images/" . $icon . "'/><b style='user-select: none;'>" . $helpText);
    if ($currentPlayer == $playerID) {
      if ($turn[0] == "P" || $turn[0] == "CHOOSEHANDCANCEL" || $turn[0] == "CHOOSEDISCARDCANCEL") echo ("(" . ($turn[0] == "P" ? $myResources[0] . " of " . $myResources[1] . " " : "") . "or " . CreateButton($playerID, "Cancel", 10000, 0, "16px") . ")");
      if (CanPassPhase($turn[0])) {
        if ($turn[0] == "B") echo (CreateButton($playerID, "Undo Block", 10001, 0, "16px") . " " . CreateButton($playerID, "Pass", 99, 0, "16px") . " " . CreateButton($playerID, "Pass Block and Reactions", 101, 0, "16px", "", "Reactions will not be skipped if the opponent reacts"));
      }
    } else {
      if ($currentPlayerActivity == 2 && $playerID != 3) echo ("— Opponent is inactive " . CreateButton($playerID, "Claim Victory", 100007, 0, "16px"));
    }
    echo ("</b>");
    echo ("</span>");
  }
  if (IsManualMode($playerID)) echo ("&nbsp;" . CreateButton($playerID, "Turn Off Manual Mode", 26, $SET_ManualMode . "-0", "18px", "", "", true));
  echo ("</span>");

  //Deduplicate current turn effects
  $friendlyEffects = "";
  $opponentEffects = "";
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    $cardID = explode("-", $currentTurnEffects[$i])[0];
    $cardID = explode(",", $cardID)[0];
    $cardID = explode("_", $cardID)[0];
    $isFriendly = ($playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1]);
    $color = ($isFriendly ? "blue" : "red");
    $effect = "<div style='width:86px; height:66px; margin:2px; border:2px solid " . $color . ";'>";
    $effect .= Card($cardID, "crops", 65, 0, 1);
    $effect .= "</div>";
    if ($isFriendly) $friendlyEffects .= $effect;
    else $opponentEffects .= $effect;
  }

  echo ("<div style='position:fixed; height:100%; width:100px; left:0px; top:0px;'>");
  echo ("<div style='font-weight: bold; text-align:center; padding-bottom:4px; border-bottom: 4px solid " . $borderColor . "; font-size:18px; font-weight: 600; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . "; user-select: none;'>Opponent<br>Effects</div>");
  echo ($opponentEffects);
  echo ("<div style='bottom:0px; position:absolute;'>");
  echo ($friendlyEffects);
  echo ("<div style='font-weight: bolder; width:100px; text-align:center; padding-top:4px; border-top: 4px solid " . $borderColor . "; font-size:18px; font-weight: 600; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . "; user-select: none;'>Your<br>Effects</div>");
  echo ("</div>");
  echo ("</div>");


  $displayCombatChain = count($combatChain) > 0;

  if ($displayCombatChain) {
    $totalAttack = 0;
    $totalDefense = 0;
    $chainAttackModifiers = [];
    EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
    echo (CreatePopup("attackModifierPopup", [], 1, 0, "Attack Modifiers", 1, AttackModifiers($chainAttackModifiers)));
  }

  echo ("<div style='position:absolute; left:240px; top:40vh; z-index:0;'>");

  //Display the combat chain
  if ($displayCombatChain) {
    if ($totalAttack < 0) $totalAttack = 0; // CR 2.1 7.2.5b A card cannot have a negative power value {p}. If an effect would reduce a weapon’s power value {p} to less than zero, instead it reduces it to zero.
    $attackTarget = GetAttackTarget();
    if ($attackTarget != "NA" && ($attackTarget != "THEIRCHAR-0" && $attackTarget != "THEIRCHAR--1") && ($turn[0] == "A" || $turn[0] == "D")) echo ("<div style='font-size:18px; font-weight:650; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>Attack Target: " . GetMZCardLink($defPlayer, $attackTarget) . "</div>");
    echo ("<table><tr>");
    echo ("<td style='font-size:28px; font-weight:650; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>$totalAttack</td>");
    echo ("<td><img onclick='ShowPopup(\"attackModifierPopup\");' style='cursor:pointer; height:30px; width:30px; display:inline-block;' src='./Images/Attack.png' /></td>");
    echo ("<td><img style='height:30px; width:30px; display:inline-block;' src='./Images/Defense.png' /></td>");
    echo ("<td style='font-size:28px; font-weight:700; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>$totalDefense</td>");
    $damagePrevention = GetDamagePrevention($defPlayer);
    if ($damagePrevention > 0) echo ("<td style='font-size:30px; font-weight:700; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>&nbsp;<div title='$damagePrevention damage prevention' style='cursor:default; height:36px; width:36px; display:inline-block; line-height: 1.25; vertical-align: middle; background-image: url(\"./Images/damagePrevention.png\"); background-size:cover;'>" . GetDamagePrevention($defPlayer) . "</div></td>");
    if (DoesAttackHaveGoAgain()) echo ("<td><img title='This attack has go again.' style='height:30px; width:30px; display:inline-block;' src='./Images/goAgain.png' /></td>");
    if (CachedDominateActive()) echo ("<td><img style='height:40px; display:inline-block;' src='./Images/dominate.png' /></td>");
    if (CachedOverpowerActive()) echo ("<td><img style='height:40px; display:inline-block;' src='./Images/overpower.png' /></td>");
    //if($lastPlayed[3] == "FUSED") echo("<td><img title='This card was fused.' style='height:30px; width:30px; display:inline-block;' src='./Images/fuse2.png' /></td>");
  }
  echo ("<td>");
  for ($i = 0; $i < count($chainLinks); ++$i) {
    if ($i == 0) {
      $iconLeft = 10;
      $linkWidth = 60;
      $linkImage = "chainLinkLeft.png";
    }
    //else if($i==count($chainLinks)-1) { $iconLeft = 27; $linkWidth = 60; $linkImage = "chainLinkRight.png"; }
    else {
      $iconLeft = 25;
      $linkWidth = 70;
      $linkImage = "chainLink.png";
    }
    echo ("<div onclick='ShowPopup(\"chainLinkPopup-" . $i . "\")'; style='cursor:pointer; position:relative; display:inline-block;'><img title='Chain Link $i' style='height:30px; width:" . $linkWidth . "px;' src='./Images/$linkImage'>");
    $damage = $chainLinkSummary[$i * ChainLinkSummaryPieces()];
    $linkOverlay = ($damage > 0 ? "./Images/hit.png" : "./Images/Defense.png");
    $linkTitle = ($damage > 0 ? "Hit for $damage damage" : "Fully Blocked");
    echo ("<div class='chainSummary' title='$linkTitle' style='position:absolute; left:" . $iconLeft . "px; top:4px;'><img style='width:22px; height:22px;' src='$linkOverlay' /></div>");
    echo ("</img></div>");
  }
  if (count($chainLinks) > 0) {
    echo ("<div title='Break the Combat Chain' " . ProcessInputLink($playerID, 100, 0) . " class='breakChain' style='height:30px; width:60px; position:relative; display:inline-block;'></div>");
  }
  echo ("</td>");
  if ($combatChainState[$CCS_RequiredEquipmentBlock] > NumEquipBlock()) echo ("<td style='padding-left:5px; font-size:18px; font-weight:650; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . "';>Block With " . $combatChainState[$CCS_RequiredEquipmentBlock] . " Equipment Required</td>");
  echo ("</tr></table>");

  if ($displayCombatChain) {
    for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
      $action = $currentPlayer == $playerID && $turn[0] != "P" && $currentPlayer == $combatChain[$i + 1] && AbilityPlayableFromCombatChain($combatChain[$i]) && IsPlayable($combatChain[$i], $turn[0], "PLAY", $i) ? 21 : 0;
      $actionDisabled = 0;
      $aimCounters = 0;
      if($i == 0 && HasAimCounter()) $aimCounters = 1;
      echo (Card($combatChain[$i], "concat", $cardSize, $action, 1, $actionDisabled, $combatChain[$i + 1] == $playerID ? 1 : 2, 0, strval($i), atkCounters: $aimCounters, controller: $combatChain[$i + 1]));
    }
  }

  echo ("</div>"); //Combat chain div

  if (count($layers) > 0) {
    $content = "";
    $content .= "<div style='font-size:24px; margin-left:5px; margin-bottom:5px; margin-top:5px;'><b>Layers</b>&nbsp;<i style='font-size:16px; margin-right: 5px;'>(Priority settings can be adjusted in the menu)</i></div>";
    if (CardType($layers[0]) == "AA" || IsWeapon($layers[0])) {
      $attackTarget = GetAttackTarget();
      if ($attackTarget != "NA") {
        $content .= "&nbsp;Attack Target: " . GetMZCardLink($defPlayer, $attackTarget);
      }
    }
    if($dqState[8] != -1) $content .= "<div style='margin-left:5px;'><i style='font-size:16px;'>For more info about trigger ordering, see rule 1.10.2c of the <a href='http://fabjud.ge/cr' target='_blank'>comprehensive rulebook</a>.</i></div>";
    $content .= "<div style='margin-left:1px; margin-top:3px; margin-bottom:5px' display:inline;'>";
    $nbTiles = 0;
    for ($i = count($layers) - LayerPieces(); $i >= 0; $i -= LayerPieces()) {
      $content .= "<div style='display:inline; max-width:" . $cardSize . "px;'>";
      $layerName = ($layers[$i] == "LAYER" || $layers[$i] == "TRIGGER" ? $layers[$i + 2] : $layers[$i]);
      $layersColor = $layers[$i + 1] == $playerID ? 1 : 2;
      if ($playerID == 3) $layersColor = $layers[$i + 1] == $otherPlayer ? 2 : 1;
      if (IsTileable($layerName) && $nbTiles == 0) {
        for ($j = 0; $j < count($layers); $j += LayerPieces()) {
          $tilesName = ($layers[$j] == "LAYER" || $layers[$j] == "TRIGGER" ? $layers[$j + 2] : $layers[$j]);
          if ($tilesName == $layerName) ++$nbTiles;
        }
        $content .= Card($layerName, "concat", $cardSize, 0, 1, 0, $layersColor, ($nbTiles == 1 ? 0 : $nbTiles), controller: $layers[$i + 1]);
      } elseif (!IsTileable($layerName)) {
        $nbTiles = 0;
        $content .= Card($layerName, "concat", $cardSize, 0, 1, 0, $layersColor, controller: $layers[$i + 1]);
      }
      if($layers[$i] == "TRIGGER" && $dqState[8] >= $i && $playerID == $mainPlayer)
      {
        if($i < $dqState[8]) $content .= "<span style='position:relative; left:-110px; top:-20px; z-index:10000;'>" . CreateButton($playerID, "<", 31, $i, "18px", useInput:true) . "</span>";
        if($i > 0) $content .= "<span style='position:relative; left:-65px; top:-20px; z-index:10000;'>" . CreateButton($playerID, ">", 32, $i, "18px", useInput:true) . "</span>";
      }
      $content .= "</div>";
    }
    $content .= "</div>";
    echo CreatePopup("INSTANT", [], 0, 1, "", 1, $content, "./", false, true);
  }

  if ($turn[0] == "OVER") {
    if ($roguelikeGameID != "") {
      $caption = (GetHealth($playerID) > 0 ? "Continue Adventure" : "Game Over");
      if(GetHealth($playerID) > 0) $content = CreateButton($playerID, "Continue Adventure", 100011, 0, "24px", "", "", false, true);
      else $content = CreateButton($playerID, "Game Over!", 100001, 0, "24px", "", "", false, true);
    } else {
      $content = CreateButton($playerID, "Main Menu", 100001, 0, "24px", "", "", false, true);
      if ($playerID == 1 && $theirCharacter[0] != "DUMMY") $content .= "&nbsp;" . CreateButton($playerID, "Rematch", 100004, 0, "24px");
      if ($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Quick Rematch", 100000, 0, "24px");
      //if ($playerID != 3 && IsPatron($playerID)) $content .= "&nbsp;" . CreateButton($playerID, "Save Replay", 100012, 0, "24px");
      if ($playerID != 3) {
        $time = ($playerID == 1 ? $p1TotalTime : $p2TotalTime);
        $totalTime = $p1TotalTime + $p2TotalTime;
        $content .= "<BR><span class='Time-Span'>Your Play Time: " . intval($time / 60) . "m" . $time % 60 . "s - Game Time: " . intval($totalTime / 60) . "m" . $totalTime % 60 . "s</span>";
      }
    }

    $content .= "</div>";
    $content .= CardStats($playerID);
    echo CreatePopup("OVER", [], 1, 1, "Player " . $winner . " Won! ", 1, $content, "./", true);
  }

  if ($turn[0] == "DYNPITCH" && $turn[1] == $playerID) {
    $content = "";
    $content .= "<div display:inline;'>";
    $options = explode(",", $turn[2]);
    for ($i = 0; $i < count($options); ++$i) {
      $content .= CreateButton($playerID, $options[$i], 7, $options[$i], "24px");
    }
    $content .= "</div>";
    echo CreatePopup("DYNPITCH", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if (($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE" || $turn[0] == "BUTTONINPUTNOPASS") && $turn[1] == $playerID) {
    $content = "<div display:inline;'>";
    if ($turn[0] == "CHOOSEARCANE") {
      $vars = explode("-", $dqVars[0]);
      $content .= "<div>Source: " . CardLink($vars[1], $vars[1]) . " Total Damage: " . $vars[0] . "</div>";
    }
    $options = explode(",", $turn[2]);
    for ($i = 0; $i < count($options); ++$i) {
      $content .= CreateButton($playerID, str_replace("_", " ", $options[$i]), 17, strval($options[$i]), "24px");
    }
    $content .= "</div>";
    echo CreatePopup("BUTTONINPUT", [], 0, 1, GetPhaseHelptext(), 1, $content);
  }

  if ($turn[0] == "YESNO" && $turn[1] == $playerID) {
    $content = CreateButton($playerID, "Yes", 20, "YES", "20px");
    $content .= CreateButton($playerID, "No", 20, "NO", "20px");
    if (GetDQHelpText() != "-") $caption = implode(" ", explode("_", GetDQHelpText()));
    else $caption = "Choose " . TypeToPlay($turn[0]);
    echo CreatePopup("YESNO", [], 0, 1, $caption, 1, $content);
  }

  if ($turn[0] == "OK" && $turn[1] == $playerID) {
    $content = CreateButton($playerID, "Ok", 99, "OK", "20px");
    if (GetDQHelpText() != "-") $caption = implode(" ", explode("_", GetDQHelpText()));
    else $caption = "Choose " . TypeToPlay($turn[0]);
    echo CreatePopup("OK", [], 0, 1, $caption, 1, $content);
  }

  if (($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM" || $turn[0] == "CHOOSECARD") && $turn[1] == $playerID) {
    $content = "<table><tr>";
    $options = explode(",", $turn[2]);
    for ($i = 0; $i < count($options); ++$i) {
      $content .= "<td>";
      $content .= "<table><tr><td>";
      $content .= Card($options[$i], "concat", $cardSize, 0, 1);
      $content .= "</td></tr><tr><td>";
      if ($turn[0] == "CHOOSETOP" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Top", 8, $options[$i], "20px");
      if ($turn[0] == "CHOOSEBOTTOM" || $turn[0] == "OPT") $content .= CreateButton($playerID, "Bottom", 9, $options[$i], "20px");
      if ($turn[0] == "CHOOSECARD") $content .= CreateButton($playerID, "Choose", 23, $options[$i], "20px");
      $content .= "</td></tr>";
      $content .= "</table>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("OPT", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if (($turn[0] == "CHOOSETOPOPPONENT") && $turn[1] == $playerID) { //Use when you have to reorder the top of your opponent library e.g. Righteous Cleansing
    $otherPlayer = ($playerID == 1 ? 2 : 1);
    $content = "<table><tr>";
    $options = explode(",", $turn[2]);
    for ($i = 0; $i < count($options); ++$i) {
      $content .= "<td>";
      $content .= "<table><tr><td>";
      $content .= Card($options[$i], "concat", $cardSize, 0, 1);
      $content .= "</td></tr><tr><td>";
      if ($turn[0] == "CHOOSETOPOPPONENT") $content .= CreateButton($otherPlayer, "Top", 29, $options[$i], "20px");
      $content .= "</td></tr>";
      $content .= "</table>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("CHOOSETOPOPPONENT", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if ($turn[0] == "HANDTOPBOTTOM" && $turn[1] == $playerID) {
    $content = "<table><tr>";
    for ($i = 0; $i < count($myHand); ++$i) {
      $content .= "<td>";
      $content .= Card($myHand[$i], "concat", $cardSize, 0, 1);
      $content .= "</td>";
    }
    $content .= "</tr><tr>";
    for ($i = 0; $i < count($myHand); ++$i) {
      $content .= "<td><span>";
      $content .= CreateButton($playerID, "Top", 12, $i, "20px");
      $content .= "</span><span>";
      $content .= CreateButton($playerID, "Bottom", 13, $i, "20px");
      $content .= "</span>";
      $content .= "</td>";
    }
    $content .= "</tr></table>";
    echo CreatePopup("HANDTOPBOTTOM", [], 0, 1, "Choose " . TypeToPlay($turn[0]), 1, $content);
  }

  if(($turn[0] == "MAYCHOOSEMULTIZONE" || $turn[0] == "CHOOSEMULTIZONE") && $turn[1] == $playerID) {
    $content = "";
    $content .= "<div display:inline;'>";
    $options = explode(",", $turn[2]);
    $otherPlayer = $playerID == 2 ? 1 : 2;
    $theirAllies = &GetAllies($otherPlayer);
    $myAllies = &GetAllies($playerID);
    for ($i = 0; $i < count($options); ++$i) {
      $option = explode("-", $options[$i]);
      if ($option[0] == "MYAURAS") $source = $myAuras;
      else if ($option[0] == "THEIRAURAS") $source = $theirAuras;
      else if ($option[0] == "MYCHAR") $source = $myCharacter;
      else if ($option[0] == "THEIRCHAR") $source = $theirCharacter;
      else if ($option[0] == "MYITEMS") $source = $myItems;
      else if ($option[0] == "THEIRITEMS") $source = $theirItems;
      else if ($option[0] == "LAYER") $source = $layers;
      else if ($option[0] == "MYHAND") $source = $myHand;
      else if ($option[0] == "THEIRHAND") $source = $theirHand;
      else if ($option[0] == "MYDISCARD") $source = $myDiscard;
      else if ($option[0] == "THEIRDISCARD") $source = $theirDiscard;
      else if ($option[0] == "MYBANISH") $source = $myBanish;
      else if ($option[0] == "THEIRBANISH") $source = $theirBanish;
      else if ($option[0] == "MYALLY") $source = $myAllies;
      else if ($option[0] == "THEIRALLY") $source = $theirAllies;
      else if ($option[0] == "MYARS") $source = $myArsenal;
      else if ($option[0] == "THEIRARS") $source = $theirArsenal;
      else if ($option[0] == "MYPERM") $source = $myPermanents;
      else if ($option[0] == "THEIRPERM") $source = $theirPermanents;
      else if ($option[0] == "MYPITCH") $source = $myPitch;
      else if ($option[0] == "THEIRPITCH") $source = $theirPitch;
      else if ($option[0] == "MYDECK") $source = $myDeck;
      else if ($option[0] == "THEIRDECK") $source = $theirDeck;
      else if ($option[0] == "LANDMARK") $source = $landmarks;
      else if ($option[0] == "CC") $source = $combatChain;
      else if ($option[0] == "COMBATCHAINLINK") $source = $combatChain;

      $counters = 0;
      $lifeCounters = 0;
      $enduranceCounters = 0;
      $atkCounters = 0;

      if (($option[0] == "MYALLY" || $option[0] == "THEIRALLY" || $option[0] == "THEIRAURAS") && $option[1] == $combatChainState[$CCS_WeaponIndex]) {
        $counters = "Attacker";
      }

      if (count($layers) > 0) {
        if ($option[0] == "THEIRALLY" && $layers[0] != "" && $mainPlayer != $currentPlayer) {
          $index = SearchLayer($otherPlayer, subtype: "Ally");
          if ($index != "") {
            $params = explode("|", $layers[$index + 2]);
            if ($option[1] == $params[2]) $counters = "Attacker";
          }
        }
        if ($option[0] == "THEIRAURAS" && $layers[0] != "" && $mainPlayer != $currentPlayer) {
          $index = SearchLayer($otherPlayer, subtype: "Aura");
          if ($index != "") {
            $params = explode("|", $layers[$index + 2]);
            if ($option[1] == $params[2]) $counters = "Attacker";
          }
        }
      }

      //Add indication for Crown of Providence if you have the same card in hand and in the arsenal.
      if ($option[0] == "MYARS") $counters = "Arsenal";

      $index = intval($option[1]);
      $card = $source[$index];
      if ($option[0] == "LAYER" && $card == "TRIGGER") $card = $source[$index + 2];
      $playerBorderColor = 0;

      if (substr($option[0], 0, 2) == "MY") $playerBorderColor = 1;
      else if (substr($option[0], 0, 5) == "THEIR") $playerBorderColor = 2;
      else if ($option[0] == "CC") $playerBorderColor = ($combatChain[$index + 1] == $playerID ? 1 : 2);
      else if ($option[0] == "LAYER") {
        $playerBorderColor = ($layers[$index + 1] == $playerID ? 1 : 2);
      }

      if ($option[0] == "THEIRARS" && $theirArsenal[$index + 1] == "DOWN") $card = $TheirCardBack;

      //Show Life and Def counters on allies in the popups
      if ($option[0] == "THEIRALLY") {
        $lifeCounters = $theirAllies[$index + 2];
        $enduranceCounters = $theirAllies[$index + 6];
        if (SearchCurrentTurnEffectsForUniqueID($theirAllies[$index + 5]) != -1) $attackCounters = EffectAttackModifier(SearchUniqueIDForCurrentTurnEffects($theirAllies[$index + 5])) + AttackValue($theirAllies[$index]);
        else $attackCounters = 0;
      } elseif ($option[0] == "MYALLY") {
        $lifeCounters = $myAllies[$index + 2];
        $enduranceCounters = $myAllies[$index + 6];
        if (SearchCurrentTurnEffectsForUniqueID($myAllies[$index + 5]) != -1) $attackCounters = EffectAttackModifier(SearchUniqueIDForCurrentTurnEffects($myAllies[$index + 5])) + AttackValue($myAllies[$index]);
        else $attackCounters = 0;
      }

      //Show Atk counters on Auras in the popups
      if ($option[0] == "THEIRAURAS") {
        $atkCounters = $theirAuras[$index + 3];
      } elseif ($option[0] == "MYAURAS") {
        $atkCounters = $myAuras[$index + 3];
      }
      $content .= Card($card, "concat", $cardSize, 16, 1, 0, $playerBorderColor, $counters, $options[$i], "", false, $lifeCounters, $enduranceCounters, $atkCounters, controller: $playerBorderColor);
    }
    $content .= "</div>";
    echo CreatePopup("CHOOSEMULTIZONE", [], 0, 1, GetPhaseHelptext(), 1, $content);
  }

  if (($turn[0] == "MAYCHOOSEDECK" || $turn[0] == "CHOOSEDECK") && $turn[1] == $playerID) {
    ChoosePopup($myDeck, $turn[2], 11, "Choose a card from your deck");
  }

  if ($turn[0] == "CHOOSEBANISH" && $turn[1] == $playerID) {
    ChoosePopup($myBanish, $turn[2], 16, "Choose a card from your banish", BanishPieces());
  }

  if (($turn[0] == "MAYCHOOSEARSENAL" || $turn[0] == "CHOOSEARSENAL" || $turn[0] == "CHOOSEARSENALCANCEL") && $turn[1] == $playerID) {
    ChoosePopup($myArsenal, $turn[2], 16, "Choose a card from your arsenal", ArsenalPieces());
  }

  if (($turn[0] == "CHOOSEPERMANENT" || $turn[0] == "MAYCHOOSEPERMANENT") && $turn[1] == $playerID) {
    $myPermanents = &GetPermanents($playerID);
    ChoosePopup($myPermanents, $turn[2], 16, GetPhaseHelptext(), PermanentPieces());
  }

  if (($turn[0] == "CHOOSETHEIRHAND") && $turn[1] == $playerID) {
    ChoosePopup($theirHand, $turn[2], 16, "Choose a card from your opponent's hand");
  }

  if (($turn[0] == "CHOOSEMYAURA") && $turn[1] == $playerID) {
    ChoosePopup($myAuras, $turn[2], 16, "Choose one of your auras");
  }

  if (($turn[0] == "CHOOSEDISCARD" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "CHOOSEDISCARDCANCEL") && $turn[1] == $playerID) {
    $caption = "Choose a card from your discard";
    if (GetDQHelpText() != "-") $caption = implode(" ", explode("_", GetDQHelpText()));
    ChoosePopup($myDiscard, $turn[2], 16, $caption);
  }

  if (($turn[0] == "MAYCHOOSETHEIRDISCARD") && $turn[1] == $playerID) {
    ChoosePopup($theirDiscard, $turn[2], 16, "Choose a card from your opponent's graveyard");
  }

  if (($turn[0] == "CHOOSECOMBATCHAIN" || $turn[0] == "MAYCHOOSECOMBATCHAIN") && $turn[1] == $playerID) {
    ChoosePopup($combatChain, $turn[2], 16, "Choose a card from the combat chain", CombatChainPieces());
  }

  if ($turn[0] == "CHOOSECHARACTER" && $turn[1] == $playerID) {
    ChoosePopup($myCharacter, $turn[2], 16, "Choose a card from your character/equipment", CharacterPieces());
  }

  if ($turn[0] == "CHOOSETHEIRCHARACTER" && $turn[1] == $playerID) {
    ChoosePopup($theirCharacter, $turn[2], 16, "Choose a card from your opponent character/equipment", CharacterPieces());
  }

  if (($turn[0] == "CHOOSEMYSOUL" || $turn[0] == "MAYCHOOSEMYSOUL") && $turn[1] == $playerID) {
    ChoosePopup($mySoul, $turn[2], 16, "Choose a card from your soul", SoulPieces());
  }

  if ($turn[0] == "PDECK" && $currentPlayer == $playerID) {
    $content = "";
    for ($i = 0; $i < count($myPitch); $i += 1) {
      $content .= Card($myPitch[$i], "concat", $cardSize, 6, 1);
    }
    echo CreatePopup("PITCH", [], 0, 1, "Choose a card from your Pitch Zone to add to the bottom of your deck", 1, $content);
  }

  if (($turn[0] == "MULTICHOOSETHEIRDISCARD" || $turn[0] == "MULTICHOOSEDISCARD" || $turn[0] == "MULTICHOOSEHAND" || $turn[0] == "MAYMULTICHOOSEHAND" || $turn[0] == "MULTICHOOSEDECK" || $turn[0] == "MULTICHOOSETEXT" || $turn[0] == "MAYMULTICHOOSETEXT" || $turn[0] == "MULTICHOOSETHEIRDECK") && $currentPlayer == $playerID) {
    $content = "";
    echo ("<div 'display:inline; width: 100%;'>");
    $params = explode("-", $turn[2]);
    $options = explode(",", $params[1]);
    $caption = "<div>Choose up to " . $params[0] . " card" . ($params[0] > 1 ? "s." : ".") . "</div>";
    if (GetDQHelpText() != "-") $caption = "<div>" . implode(" ", explode("_", GetDQHelpText())) . "</div>";
    $content .= CreateForm($playerID, "Submit", 19, count($options));
    $content .= "<table style='border-spacing:0; border-collapse: collapse;'><tr>";
    for ($i = 0; $i < count($options); ++$i) {
      $content .= "<td>";
      $content .= CreateCheckbox($i, strval($i));
      $content .= "</td>";
    }
    $content .= "</tr><tr>";
    for ($i = 0; $i < count($options); ++$i) {
      $content .= "<td>";
      $content .= "<div class='container'>";
      if ($turn[0] == "MULTICHOOSEDISCARD") $content .= "<label class='multichoose' for=chk" . $i . ">" . Card($myDiscard[$options[$i]], "concat", $cardSize, 0, 1) . "</label>";
      else if ($turn[0] == "MULTICHOOSETHEIRDISCARD") $content .= "<label class='multichoose' for=chk" . $i . ">" . Card($theirDiscard[$options[$i]], "concat", $cardSize, 0, 1) . "</label>";
      else if ($turn[0] == "MULTICHOOSEHAND" || $turn[0] == "MAYMULTICHOOSEHAND") $content .= "<label class='multichoose' for=chk" . $i . ">" . Card($myHand[$options[$i]], "concat", $cardSize, 0, 1) . "</label>";
      else if ($turn[0] == "MULTICHOOSEDECK") $content .= "<label class='multichoose' for=chk" . $i . ">" . Card($myDeck[$options[$i]], "concat", $cardSize, 0, 1) . "</label>";
      else if ($turn[0] == "MULTICHOOSETHEIRDECK") $content .= "<label class='multichoose' for=chk" . $i . ">" . Card($theirDeck[$options[$i]], "concat", $cardSize, 0, 1) . "</label>";
      else if ($turn[0] == "MULTICHOOSETEXT" || $turn[0] == "MAYMULTICHOOSETEXT") $content .= implode(" ", explode("_", strval($options[$i])));
      $content .= "<div class='overlay'><div class='text'>Select</div></div></div>";
      $content .= "</td>";
    }
    $content .= "</tr></table></form></div>";
    echo CreatePopup("MULTICHOOSE", [], 0, 1, $caption, 1, $content);
  }

  if($turn[0] == "INPUTCARDNAME" && $turn[1] == $playerID)
  {
    $caption = "<div>Enter a card name or ID</div>";
    $content = CreateTextForm($playerID, "Submit", 30);
    echo CreatePopup("INPUTCARDNAME", [], 0, 1, $caption, 1, $content);
  }

  //Opponent hand
  $handContents = "";
  $handLeft = "calc(50% - " . ((count($theirHand) * ($cardWidth + 15)) / 2) . "px)";
  echo ("<div style='position: fixed; top: 5px; left:" . $handLeft . "; height: 50px; display:inline;'>");
  echo ("<div id='theirHand' style='display:inline;'>");
  for ($i = 0; $i < count($theirHand); ++$i) {
    if ($handContents != "") $handContents .= "|";
    if ($playerID == 3 && IsCasterMode()) $handContents .= ClientRenderedCard(cardNumber: $theirHand[$i], controller: ($playerID == 1 ? 2 : 1));
    else $handContents .= ClientRenderedCard(cardNumber: $TheirCardBack, controller: ($playerID == 1 ? 2 : 1));
  }
  echo ($handContents);
  $banishUI = TheirBanishUIMinimal("HAND");
  if ($handContents != "" && $banishUI != "") echo ("|");
  echo ($banishUI);
  echo ("</div>");

  if (count($theirSoul) > 0) echo ("<div id='theirSoulCount'>" . count($theirSoul) . "</div>");
  echo ("</div>");

  //Show deck, discard, pitch, banish
  //Display Their Discard
  if (count($theirDiscard) > 0) {
    echo ("<div title='Click to view the cards in your opponent's Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; top:" . GetZoneTop("THEIRDISCARD") . ";' onclick='ShowPopup(\"theirDiscardPopup\");'>");
    echo (Card($theirDiscard[count($theirDiscard) - 1], "concat", $cardSizeAura, 0, 0, 0, 0, count($theirDiscard), controller: $otherPlayer));
  } else {
    //Empty Discard div
    echo ("<div style='position:fixed; right:" . GetZoneRight("DISCARD") . "; top:" . GetZoneTop("THEIRDISCARD") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Discard</div>");
  }
  echo ("</div>");

  //Display Their Deck
  if (count($theirDeck) > 0) {
    echo ("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; top:" . GetZoneTop("THEIRDECK") . ";'>");
    echo (Card($TheirCardBack, "concat", $cardSizeAura, 0, 0, 0, 0, count($theirDeck)));
  } else {
    //Empty Deck div
    echo ("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; top:" . GetZoneTop("THEIRDECK") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Deck</div>");
  }
  echo (($manualMode ? "<span style='position:absolute; left:50%; -ms-transform: translateX(-50%); transform: translateX(-50%);  bottom:0px; z-index:1000;'>" . CreateButton($playerID, "Draw", 10010, 0, "20px") . "</span>" : ""));
  echo ("</div>");

  //Display Their Banish
  if (count($theirBanish) > 0) {
    echo ("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; top:" . GetZoneTop("THEIRBANISH") . ";'>");
    $mod = $theirBanish[count($theirBanish) - BanishPieces() + 1];
    $card = $mod == "INT" || $mod == "UZURI" ? $TheirCardBack : $theirBanish[count($theirBanish) - BanishPieces()];
    echo (Card($card, "concat", $cardSizeAura, 0, 0, 0, 0, controller: $otherPlayer));

    $theirBloodDeptCount = 0;
    for ($i = 0; $i < count($theirBanish); $i += BanishPieces()) {
      if (HasBloodDebt($theirBanish[$i]) && $theirBanish[$i + 1] != "INT") ++$theirBloodDeptCount;
    }

    if (TalentContains($theirCharacter[0], "SHADOW") || $theirBloodDeptCount > 0) {
      $bloodDeptImage = IsImmuneToBloodDebt(($playerID == 1 ? 2 : 1)) ? "bloodDebtImmune2.png" : "bloodDebt2.png";
      echo ("<div style='position:absolute; top:28%; left:104px; width:34px; font-size:24px; font-weight:550; color: #DDD; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; text-align:center; padding-top:15px;'>" . $theirBloodDeptCount . "<img title='Blood Debt' style='position:absolute; z-index:-5; top: 40%; left: 50%; transform: translate(-50%, -50%); width:36px;' src='./Images/" . $bloodDeptImage . "'></img></div>");
    }
    echo ("<span title='Click to see your opponent Banish Zone.' onclick='ShowPopup(\"theirBanishPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop + 2 . "px; cursor:pointer; position:absolute; display:inline-block;'>
      <img style=' opacity:0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . "; display: block; margin-left: auto; margin-right: auto;' src='./Images/banish.png'>
      <div style='text-align:center; margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 30px; height: 30px; padding: 3px; transform: translate(-50%, -50%); line-height: 1.2;
      position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . count($theirBanish) / BanishPieces());
    echo ("</img></div></span>");
  } else {
    //Empty Banish div
    echo ("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; top:" . GetZoneTop("THEIRBANISH") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Banish</div>");
  }
  echo ("</div>");

  //Display Their Pitch
  if (count($theirPitch) > 0) {
    $theirPitchCount = 0;
    $maxPitchStack = 5; // Limit before it goes out of screen
    for ($i = count($theirPitch) - PitchPieces(); $i >= 0; $i -= PitchPieces()) {
      echo ("<div style='position:fixed; z-index:-" . $theirPitchCount . "; right:" . GetZoneRight("PITCH") . "; top:" . GetZoneTop("THEIRPITCH") - $theirPitchCount * 21 . "px;'>");
      echo (Card($theirPitch[$i], "concat", $cardSizeAura, 0, 1, controller: $playerID));
      if ($theirPitchCount < $maxPitchStack) ++$theirPitchCount;
    }
    for ($j = 1; $j < count($theirPitch); ++$j) echo ("</div>");
    echo ("<span title='Click to see your opponent Pitch Zone.' onclick='ShowPopup(\"theirPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='opacity:0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . ";' src='./Images/Resource.png'>
  <div style='margin: 0; top: 51%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
  position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $theirResources[0] . "</div></img></span>");
  } else {
    //Empty Pitch div
    echo ("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; top:" . GetZoneTop("THEIRPITCH") . "px; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    if ($theirResources[0] > 0) {
      echo ("<span title='Click to see your Pitch Zone.' onclick='ShowPopup(\"theirPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block; z-index:5;'><img style='opacity: 0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . ";' src='./Images/Resource.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px; text-align: center; transform: translate(-50%, -50%); line-height: 1.2; position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $theirResources[0] . "</div></img></span>");
    } else echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Pitch</div>");
  }
  echo (($manualMode ? "<span style='position:absolute; top:2%; left:1%;'>" . CreateButton($playerID, "+1", 10013, 0, "20px") . "<br><br>" . CreateButton($playerID, "-1", 10014, 0, "20px") . "</span>" : ""));
  echo ("</div>");

  //Now display their Auras and Items
  if (count($landmarks) > 0) {
    echo ("<div style='position: fixed; top:105px; left: calc(50% + 200px); display:inline;'>");
    echo ("<h3 style='font-size:16px; font-weight: 600; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . ";'>Landmark:</h3>");
    for ($i = 0; $i < count($landmarks); $i += LandmarkPieces()) {
      $playable = $playerID == $currentPlayer && IsPlayable($landmarks[$i], $turn[0], "PLAY", $i, $restriction);
      $action = ($playable && $currentPlayer == $playerID ? 25 : 0);
      $border = CardBorderColor($landmarks[$i], "PLAY", $playable);
      $counters = 0;
      echo (Card($landmarks[$i], "concat", $cardSizeAura, $action, 1, 0, $border, $counters, strval($i), ""));
    }
    echo ("</div>");
  }

  $permTop = 7;
  $theirPermHeight = $cardSize * 2 + 60;
  $theirPermWidth = "calc(50% - " . ($cardWidth * 2 + $permLeft - 10) . "px)";
  echo ("<div style='overflow-y:auto; position: fixed; top:" . $permTop . "px; left:" . $permLeft . "px; width:" . $theirPermWidth . "; height:" . $theirPermHeight . "px;'>");
  DisplayTiles(($playerID == 1 ? 2 : 1));
  if (count($theirAuras) > 0) {
    for ($i = 0; $i < count($theirAuras); $i += AuraPieces()) {
      if (IsTileable($theirAuras[$i])) continue;
      $counters = $theirAuras[$i + 2];
      $atkCounters = $theirAuras[$i + 3];
      echo ("<div style='position:relative; display: inline-block;'>");
      echo (Card($theirAuras[$i], "concat", $cardSizeAura, 0, 1, $theirAuras[$i + 1] != 2 ? 1 : 0, 0, $counters, "", "", False, 0, 0, $atkCounters, controller: $otherPlayer) . "&nbsp");
      DisplayPriorityGem($theirAuras[$i + 8], "AURAS-" . $i, 1);
      if ($theirAuras[$i + 4] == 1 && CardType($theirAuras[$i]) != "T") echo ("<img title='Token Copy' style='position:absolute; display: inline-block; z-index:1001; top: 0px; left:" . $cardWidth / 2 - 45 . "px; width:" . $cardWidth + 15 . "px; height:30px; cursor:pointer;' src='./Images/tokenCopy.png' />");
      echo ("</div>");
    }
  }
  if (count($theirItems) > 0) {
    for ($i = 0; $i+ItemPieces()-1 < count($theirItems); $i += ItemPieces()) {
      if (IsTileable($theirItems[$i])) continue;
      echo ("<div style='position:relative; display: inline-block;'>");
      echo (Card($theirItems[$i], "concat", $cardSizeAura, 0, 1, $theirItems[$i + 2] != 2 ? 1 : 0, 0, $theirItems[$i + 1], "", "", false, 0, 0, 0, "ITEMS", controller: $otherPlayer) . "&nbsp");
      DisplayPriorityGem($theirItems[$i + 6], "ITEMS-" . $i, 1);
      echo ("</div>");
    }
  }
  if ($playerID == 3) {
    $otherPlayer = $playerID == 2 ? 2 : 1;
  } else {
    $otherPlayer = $playerID == 2 ? 1 : 2;
  }
  $theirAllies = GetAllies($otherPlayer);
  if (count($theirAllies) > 0) {
    for ($i = 0; $i+AllyPieces()-1 < count($theirAllies); $i += AllyPieces()) {
      $lifeCounters = $theirAllies[$i + 2];
      $enduranceCounters = $theirAllies[$i + 6];
      $subcard = $theirAllies[$i + 4];
      if (SearchCurrentTurnEffectsForUniqueID($theirAllies[$i + 5]) != -1) $attackCounters = EffectAttackModifier(SearchUniqueIDForCurrentTurnEffects($theirAllies[$i + 5])) + AttackValue($theirAllies[$i]);
      else $attackCounters = 0;
      echo ("<div style='position:relative; display: inline-block;'>");
      if ($subcard != "-" && $subcard != "UPR043") echo (Card($subcard, "concat", $cardSizeAura, showHover: true, from: "SUBCARD", controller: $playerID));
      echo (Card($theirAllies[$i], "concat", $cardSizeAura, 0, 1, $theirAllies[$i + 1] != 2 ? 1 : 0, 0, 0, "", "", False, $lifeCounters, $enduranceCounters, $attackCounters, ($subcard != "-" && $subcard != "UPR043") ? "HASSUBCARD" : "", controller: $otherPlayer) . "&nbsp");
      if ($theirAllies[$i + 3] == 1) echo ("<img title='Frozen' style='position:absolute; z-index:1001; top: " . ($subcard == "-" || $subcard == "UPR043" ? "6px" : "24px") . "; left: 7px; cursor:pointer; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo ("</div>");
    }
  }
  $theirPermanents = &GetPermanents($otherPlayer);
  if (count($theirPermanents) > 0) {
    for ($i = 0; $i < count($theirPermanents); $i += PermanentPieces()) {
      if (IsTileable($theirPermanents[$i])) continue;
      //$playable = ($currentPlayer == $playerID ? IsPlayable($theirPermanents[$i], $turn[0], "PLAY", $i, $restriction) : false);
      //$border = CardBorderColor($theirPermanents[$i], "PLAY", $playable);
      echo (Card($theirPermanents[$i], "concat", $cardSizeAura, 0, 1, controller: $otherPlayer) . "&nbsp");
    }
  }
  echo ("</div>");

  //Now display their character and equipment
  $numWeapons = 0;
  echo ("<div id='theirChar'>");
  $characterContents = "";
  for ($i = 0; $i < count($theirCharacter); $i += CharacterPieces()) {
    if ($i > 0 && $inGameStatus == "0") continue;
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
    if ($characterContents != "") $characterContents .= "|";
    $characterContents .= ClientRenderedCard(cardNumber: $theirCharacter[$i], overlay: ($theirCharacter[$i + 1] != 2 ? 1 : 0), counters: $counters, defCounters: $theirCharacter[$i + 4], atkCounters: $atkCounters, controller: $otherPlayer, type: $type, sType: $sType, isFrozen: ($theirCharacter[$i + 8] == 1), onChain: ($theirCharacter[$i + 6] == 1), isBroken: ($theirCharacter[$i + 1] == 0));

    /*
    echo ("<div style='z-index:5; position:fixed; left:" . GetCharacterLeft($type, $sType) . "; top:" . GetCharacterTop($type, $sType) . ";'>");
    echo (Card($theirCharacter[$i], "concat", $cardSizeEquipment, 0, 1, $theirCharacter[$i + 1] != 2 ? 1 : 0, 0, $counters, "", "", false, 0, $theirCharacter[$i + 4], $atkCounters, "CHARACTER", controller:$otherPlayer));
    if ($theirCharacter[$i + 8] == 1) echo ("<img title='Frozen' style='position:absolute; z-index:100; border-radius:5px; top:7px; left:7px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
    if ($theirCharacter[$i + 6] == 1) echo ("<img title='On Combat Chain' style='position:absolute; z-index:100; top:-25px; left:7px; width:" . $cardWidth . "' src='./Images/onChain.png' />");
    if ($theirCharacter[$i + 1] == 0) echo ("<img title='Equipment Broken' style='position:absolute; z-index:100; width:" . $cardEquipmentWidth . "; bottom: 6px; left:16px;' src='./Images/brokenEquip.png' />");
    */
  }
  echo ($characterContents);

  echo ("</div>");

  //Now display their arsenal
  if (count($theirArsenal) > 0) {
    $arsenalLeft = (count($theirArsenal) == ArsenalPieces() ? "calc(50% - " . (intval($cardWidth / 2) + 4) . "px)" : "calc(50% - " . (intval($cardWidth) + 14) . "px)");
    echo ("<div title='Your opponent's Arsenal' style='z-index:-10; position: fixed; left:" . $arsenalLeft . "; top:" . (intval(GetCharacterTop("C", "")) * 2 + 5) . "px;'>"); //arsenal div

    for ($i = 0; $i < count($theirArsenal); $i += ArsenalPieces()) {
      echo ("<div style='position:relative; display:inline;'>");
      if ($playerID == 3 && IsCasterMode()) echo (Card($theirArsenal[$i], "concat", $cardSizeAura, 0, 1, $theirArsenal[$i + 2] == 0 ? 1 : 0, 0, $theirArsenal[$i + 3], from: "ARS", controller: $otherPlayer));
      else if ($theirArsenal[$i + 1] == "UP") echo (Card($theirArsenal[$i], "concat", $cardSizeAura, 0, 1, $theirArsenal[$i + 2] == 0 ? 1 : 0, 0, $theirArsenal[$i + 3], from: "ARS", controller: $otherPlayer));
      else echo (Card($TheirCardBack, "concat", $cardSizeAura, 0, 0));
      if ($theirArsenal[$i + 4] == 1) echo ("<img title='Frozen' style='position:absolute; z-index:10; border-radius:5px; top:-80px; left:7px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo ("</div>");
    }
  } else {
    //Empty Arsenal div
    echo ("<div style='position:fixed; left: calc(50% - " . (intval($cardWidth / 2)) . "px); top:" . (intval(GetCharacterTop("C", "")) * 2 + 12) . "px; border-radius:5%; padding:" . $cardSizeAura / 2 - 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Arsenal</div>");
  }
  echo ("</div>");
  echo ("</div>");

  $restriction = "";
  $actionType = $turn[0] == "ARS" ? 4 : 27;
  if (strpos($turn[0], "CHOOSEHAND") !== false && ($turn[0] != "MULTICHOOSEHAND" || $turn[0] != "MAYMULTICHOOSEHAND")) $actionType = 16;
  $handLeft = "calc(50% - " . ((count($myHand) * ($cardWidth + 15)) / 2) . "px)";
  echo ("<div id='myHand' style='display:none; position:fixed; left:" . $handLeft . "; bottom: 5px;'>"); //Hand div
  $handContents = "";
  for ($i = 0; $i < count($myHand); ++$i) {
    if ($handContents != "") $handContents .= "|";
    if ($playerID == 3) {
      if (IsCasterMode()) $handContents .= ClientRenderedCard(cardNumber: $myHand[$i], controller: 2);
      else $handContents .= ClientRenderedCard(cardNumber: $MyCardBack, controller: 2);
    } else {
      if ($playerID == $currentPlayer) $playable = $turn[0] == "ARS" || IsPlayable($myHand[$i], $turn[0], "HAND", -1, $restriction) || ($actionType == 16 && strpos("," . $turn[2] . ",", "," . $i . ",") !== false);
      else $playable = false;
      $border = CardBorderColor($myHand[$i], "HAND", $playable);
      $actionTypeOut = (($currentPlayer == $playerID) && $playable == 1 ? $actionType : 0);
      if ($restriction != "") $restriction = implode("_", explode(" ", $restriction));
      $actionDataOverride = (($actionType == 16 || $actionType == 27) ? strval($i) : "");
      $handContents .= ClientRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction);
    }
  }
  echo ($handContents);
  $banishUI = BanishUIMinimal("HAND");
  if ($handContents != "" && $banishUI != "") echo ("|");
  echo ($banishUI);
  echo ("</div>"); //End hand div

  //Now display my arsenal
  if (count($myArsenal) > 0) {
    $arsenalLeft = (count($myArsenal) == ArsenalPieces() ? "calc(50% - " . (intval($cardWidth / 2) + 6) . "px)" : "calc(50% - " . (intval($cardWidth) + 14) . "px)");
    echo ("<div style='position:fixed; left:" . $arsenalLeft . "; bottom:" . (intval(GetCharacterBottom("C", "")) - $cardSize + 15) . "px;'>"); //arsenal div
    for ($i = 0; $i < count($myArsenal); $i += ArsenalPieces()) {
      echo ("<div style='position:relative; display:inline-block'>");
      if ($playerID == 3) {
        if ($myArsenal[$i + 1] == "UP" || IsCasterMode()) echo (Card($myArsenal[$i], "concat", $cardSizeAura, 0, 1, $myArsenal[$i + 2] == 0 ? 1 : 0, 0, $myArsenal[$i + 3]));
        else echo (Card($MyCardBack, "concat", $cardSizeAura, 0, 0, 0, 0));
      } else {
        $playable = $playerID == $currentPlayer && $turn[0] != "P" && IsPlayable($myArsenal[$i], $turn[0], "ARS", $i, $restriction);
        $border = CardBorderColor($myArsenal[$i], "ARS", $playable);
        $counters = $myArsenal[$i + 3];
        echo ("<div style='position:relative; margin:1px;>");
        echo (Card($myArsenal[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $playable ? 5 : 0, 1, $myArsenal[$i + 2] > 0 ? 0 : 1, $border, $counters, strval($i), from: "ARS", controller: $playerID));
        $iconHeight = $cardSize / 4;
        $iconLeft = $cardWidth / 2 - intval($iconHeight * .71 / 2) + 5;
        if ($myArsenal[$i + 1] == "UP") echo ("<img style='position:absolute; z-index: 5; left:" . $iconLeft . "px; bottom:3px; height:" . $iconHeight . "px; ' src='./Images/faceUp.png' title='This arsenal card is face up.'></img>");
        else echo ("<img style='position:absolute; left:" . $iconLeft . "px; bottom:3px; z-index: 5; height:" . $iconHeight . "px; ' src='./Images/faceDown.png' title='This arsenal card is face down.'></img>");
        if($restriction != "") echo ("<img style='position:absolute; left:26px; top:26px; z-index: 5;' src='./Images/restricted.png' title='$restriction'></img>");
        echo ("</div>");
      }
      if ($myArsenal[$i + 4] == 1) echo ("<img title='Frozen' style='position:absolute; z-index:100; border-radius:5px; top:7px; left:7px; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo ("</div>");
    }
  } else {
    //Empty Arsenal div
    echo ("<div style='position:fixed; left: calc(50% - " . (intval($cardWidth / 2)) . "px); bottom:" . (intval(GetCharacterBottom("C", "")) - $cardSize + 22) . "px; border-radius:5%; padding:" . $cardSizeAura / 2 - 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Arsenal</div>");
  }
  echo ("</div>"); //End arsenal div

  //Now display my Auras and items
  $permHeight = $cardSize * 2;
  $permTop = intval(GetCharacterBottom("C", "")) - ($cardSize - 14); // - 332;
  $myPermWidth = "calc(50% - 30vw)";
  echo ("<div style='overflow-y:auto; position: fixed; bottom:" . $permTop . "px; left:" . $permLeft . "px; width:" . $myPermWidth . "; max-height:" . $permHeight . "px;'>");
  DisplayTiles($playerID);
  if (count($myAuras) > 0) {
    for ($i = 0; $i < count($myAuras); $i += AuraPieces()) {
      if (IsTileable($myAuras[$i])) continue;
      $playable = ($currentPlayer == $playerID ? $myAuras[$i + 1] == 2 && IsPlayable($myAuras[$i], $turn[0], "PLAY", $i, $restriction) : false);
      $border = CardBorderColor($myAuras[$i], "PLAY", $playable);
      $counters = $myAuras[$i + 2];
      $atkCounters = $myAuras[$i + 3];
      echo ("<div style='position:relative; display: inline-block;'>");
      echo (Card($myAuras[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 22 : 0, 1, $myAuras[$i + 1] != 2 ? 1 : 0, $border, $counters, strval($i), "", False, 0, 0, $atkCounters, controller: $playerID) . "&nbsp");
      DisplayPriorityGem($myAuras[$i + 7], "AURAS-" . $i);
      if ($myAuras[$i + 4] == 1 && CardType($myAuras[$i]) != "T") echo ("<img title='Token Copy' style='position:absolute; display: inline-block; z-index:1001; top: 0px; left:" . $cardWidth / 2 - 45 . "px; width:" . $cardWidth + 15 . "px; height:30px; cursor:pointer;' src='./Images/tokenCopy.png' />");
      echo ("</div>");
    }
  }
  if (count($myItems) > 0) {
    for ($i = 0; $i < count($myItems); $i += ItemPieces()) {
      if (IsTileable($myItems[$i])) continue;
      $playable = ($currentPlayer == $playerID ? IsPlayable($myItems[$i], $turn[0], "PLAY", $i, $restriction) : false);
      $border = CardBorderColor($myItems[$i], "PLAY", $playable);
      echo ("<div style='position:relative; display: inline-block;'>");
      echo (Card($myItems[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, ItemOverlay($myItems[$i], $myItems[$i + 2], $myItems[$i + 3]), $border, $myItems[$i + 1], strval($i), "", false, 0, 0, 0, "ITEMS", controller: $playerID) . "&nbsp");
      DisplayPriorityGem($myItems[$i + 5], "ITEMS-" . $i);
      echo ("</div>");
    }
  }

  $myAllies = GetAllies($playerID);
  if (count($myAllies) > 0) {
    for ($i = 0; $i < count($myAllies); $i += AllyPieces()) {
      $lifeCounters = $myAllies[$i + 2];
      $enduranceCounters = $myAllies[$i + 6];
      $subcard = $myAllies[$i + 4];
      if (SearchCurrentTurnEffectsForUniqueID($myAllies[$i + 5]) != -1) $attackCounters = EffectAttackModifier(SearchUniqueIDForCurrentTurnEffects($myAllies[$i + 5])) + AttackValue($myAllies[$i]);
      else $attackCounters = 0;
      $playable = IsPlayable($myAllies[$i], $turn[0], "PLAY", $i, $restriction) && $myAllies[$i + 1] == 2;
      $border = CardBorderColor($myAllies[$i], "PLAY", $playable);
      echo ("<div style='position:relative; display: inline-block;'>");
      if ($subcard != "-" && $subcard != "UPR043") echo (Card($subcard, "concat", $cardSizeAura, showHover: true, from: "SUBCARD", controller: $playerID));
      echo (Card($myAllies[$i], "concat", $cardSizeAura, $currentPlayer == $playerID && $turn[0] != "P" && $playable ? 24 : 0, 1, $myAllies[$i + 1] != 2 ? 1 : 0, $border, 0, strval($i), "", False, $lifeCounters, $enduranceCounters, $attackCounters, ($subcard != "-" && $subcard != "UPR043") ? "HASSUBCARD" : "", controller: $playerID) . "&nbsp");
      if ($myAllies[$i + 3] == 1) echo ("<img title='Frozen' style='position:absolute; z-index:1001; top: " . ($subcard == "-" || $subcard == "UPR043" ? "6px" : "24px") . "; left: 6px; cursor:pointer; height:" . $cardHeight . "; width:" . $cardWidth . ";' src='./Images/frozenOverlay.png' />");
      echo ("</div>");
    }
  }
  $myPermanents = &GetPermanents($playerID);
  if (count($myPermanents) > 0) {
    for ($i = 0; $i < count($myPermanents); $i += PermanentPieces()) {
      if (IsTileable($myPermanents[$i])) continue;
      //$playable = ($currentPlayer == $playerID ? IsPlayable($myPermanents[$i], $turn[0], "PLAY", $i, $restriction) : false);
      //$border = CardBorderColor($myPermanents[$i], "PLAY", $playable);
      echo (Card($myPermanents[$i], "concat", $cardSizeAura, 0, 1, controller: $playerID) . "&nbsp");
    }
  }
  echo ("</div>");

  //Now display my character and equipment
  $numWeapons = 0;
  $myCharData = "";
  for ($i = 0; $i < count($myCharacter); $i += CharacterPieces()) {
    $restriction = "";
    $counters = 0;
    $atkCounters = 0;
    if (CardType($myCharacter[$i]) == "W") $atkCounters = $myCharacter[$i + 3];
    if ($myCharacter[$i + 2] > 0) $counters = $myCharacter[$i + 2];
    if ($turn[0] == "B") {
      $playable = $playerID == $currentPlayer && $myCharacter[$i + 1] != 0 && IsPlayable($myCharacter[$i], $turn[0], "CHAR", $i, $restriction);
    } else {
      $playable = $playerID == $currentPlayer && $myCharacter[$i + 1] == 2 && IsPlayable($myCharacter[$i], $turn[0], "CHAR", $i, $restriction);
    }
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
    if ($myCharData != "") $myCharData .= "|";
    $gem = 0;
    if ($myCharacter[$i + 9] != 2 && $myCharacter[$i + 1] != 0 && $playerID != 3) {
      $gem = ($myCharacter[$i + 9] == 1 ? 1 : 2);
    }
    $restriction = implode("_", explode(" ", $restriction));
    $myCharData .= ClientRenderedCard($myCharacter[$i], $currentPlayer == $playerID && $playable ? 3 : 0, $myCharacter[$i + 1] != 2 ? 1 : 0, $border, $myCharacter[$i + 1] != 0 ? $counters : 0, strval($i), 0, $myCharacter[$i + 4], $atkCounters, $playerID, $type, $sType, $restriction, $myCharacter[$i + 1] == 0, $myCharacter[$i + 6] == 1, $myCharacter[$i + 8] == 1, $gem);
  }
  echo ("<div id='myChar' style='display:none;'>");
  echo ($myCharData);
  echo ("</div>");
  if (count($mySoul) > 0) echo ("<div id='mySoulCount'>" . count($mySoul) . "</div>");

  echo ("</div>");

  //Show deck, discard, pitch, banish
  //Display My Discard
  if (count($myDiscard) > 0) {
    echo ("<div title='Click to view the cards in your Graveyard.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DISCARD") . "; bottom:" . GetZoneBottom("MYDISCARD") . ";' onclick='ShowPopup(\"myDiscardPopup\");'>");
    echo (Card($myDiscard[count($myDiscard) - 1], "concat", $cardSizeAura, 0, 0, 0, 0, count($myDiscard), controller: $playerID));
  } else {
    //Empty Discard div
    echo ("<div style='position:fixed; right:" . GetZoneRight("DISCARD") . "; bottom:" . GetZoneBottom("MYDISCARD") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Discard</div>");
  }
  echo ("</div>");

  //Display My Deck
  if (count($myDeck) > 0) {
    $playerDeck = new Deck($playerID);
    if ($turn[0] == "OVER") echo ("<div title='Click to view the cards in your Deck.' style='cursor:pointer; position:fixed; right:" . GetZoneRight("DECK") . "; bottom:" . GetZoneBottom("MYDECK") . "' onclick='ShowPopup(\"myDeckPopup\");'>");
    else echo ("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; bottom:" . GetZoneBottom("MYDECK") . ";'>");
    echo (Card($MyCardBack, "concat", $cardSizeAura, 0, 0, 0, 0, $playerDeck->RemainingCards()));
  } else {
    //Empty Deck div
    echo ("<div style='position:fixed; right:" . GetZoneRight("DECK") . "; bottom:" . GetZoneBottom("MYDECK") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Deck</div>");
  }
  echo (($manualMode ? "<span style='position:absolute; left:50%; -ms-transform: translateX(-50%); transform: translateX(-50%); bottom:0px; z-index:1000;'>" . CreateButton($playerID, "Draw", 10009, 0, "20px") . "</span>" : ""));
  echo ("</div>");

  //Display My Banish
  if (count($myBanish) > 0) {
    echo ("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; bottom:" . GetZoneBottom("MYBANISH") . ";'>");
    $mod = $myBanish[count($myBanish) - BanishPieces() + 1];
    if ($playerID == 3) $card = ($mod == "INT" || $mod == "UZURI" ? $MyCardBack : $myBanish[count($myBanish) - BanishPieces()]);
    else $card = $myBanish[count($myBanish) - BanishPieces()];
    echo (Card($card, "concat", $cardSizeAura, 0, 0, 0, 0, controller: $playerID));
    echo ("<span title='Click to see your Banish Zone.' onclick='ShowPopup(\"myBanishPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer;
      position:absolute; display:inline-block;'><img style='opacity:0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . ";' src='./Images/banish.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; height:" . $counterHeight . "; padding: 3px;
      text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
      position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . count($myBanish) / BanishPieces());
    echo ("</img></div></span>");
    if (TalentContains($myCharacter[0], "SHADOW") || SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true))) {
      $myBloodDeptCount = SearchCount(SearchBanish($playerID, "", "", -1, -1, "", "", true));
      $bloodDeptImage = IsImmuneToBloodDebt($playerID) ? "bloodDebtImmune2.png" : "bloodDebt2.png";
      echo ("<div style='position:absolute; top:28%; left:-40px; width:34px; font-size:24px; font-weight:550; color: #DDD; text-shadow: 2px 0 0 #1a1a1a, 0 -2px 0 #1a1a1a, 0 2px 0 #1a1a1a, -2px 0 0 #1a1a1a; text-align:center; padding-top:15px;'>" . $myBloodDeptCount . "<img title='Blood Debt' style='position:absolute; z-index:-5; top:40%; left:50%; transform: translate(-50%, -50%); width:36px;' src='./Images/" . $bloodDeptImage . "'></img></div>");
    }
  } else {
    //Empty Banish div
    echo ("<div style='position:fixed; right:" . GetZoneRight("BANISH") . "; bottom:" . GetZoneBottom("MYBANISH") . "; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Banish</div>");
  }

  echo ("</div>");

  //Display My Pitch
  if (count($myPitch) > 0) {
    $myPitchCount = 0;
    $maxPitchStack = 5; // Limit before it goes out of screen
    for ($i = count($myPitch) - PitchPieces(); $i >= 0; $i -= PitchPieces()) {
      echo ("<div style='position:fixed; z-index:-" . $myPitchCount . ";right:" . GetZoneRight("PITCH") . "; bottom:" . GetZoneBottom("MYPITCH") + $myPitchCount * 21 . "px;'>");
      echo (Card($myPitch[$i], "concat", $cardSizeAura, 0, 1, controller: $playerID));
      if ($myPitchCount < $maxPitchStack) ++$myPitchCount;
    }
    for ($j = 1; $j < count($myPitch); ++$j) echo ("</div>");
    echo (($manualMode ? "<span style='position:absolute; z-index:1; bottom:1%; left:1%;'>" . CreateButton($playerID, "+1", 10012, 0, "20px") . "<br><br>" . CreateButton($playerID, "-1", 10015, 0, "20px") . "</span>" : ""));
    echo ("<span title='Click to see your Pitch Zone.' onclick='ShowPopup(\"myPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block;'><img style='opacity: 0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . ";' src='./Images/Resource.png'>
  <div style='margin: 0; top: 51%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px;
  text-align: center; transform: translate(-50%, -50%); line-height: 1.2;
  position:absolute; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $myResources[0] . "</div></img></span>");
    echo ("</div>");
  } else {
    //Empty Pitch div
    echo ("<div style='position:fixed; right:" . GetZoneRight("PITCH") . "; bottom:" . GetZoneBottom("MYPITCH") . "px; border-radius:5%; padding:" . $cardSizeAura / 2 . "px; background-color: rgba(0, 0, 0, 0.4);'>");
    echo (($manualMode ? "<span style='position:absolute; z-index:1; bottom:1%; left:1%;'>" . CreateButton($playerID, "+1", 10012, 0, "20px") . "<br><br>" . CreateButton($playerID, "-1", 10015, 0, "20px") . "</span>" : ""));
    if ($myResources[0] > 0) {
      echo ("<span title='Click to see your Pitch Zone.' onclick='ShowPopup(\"myPitchPopup\");' style='left:" . $cardIconLeft . "px; top:" . $cardIconTop . "px; cursor:pointer; position:absolute; display:inline-block; z-index:5;'><img style='opacity: 0.9; height:" . $cardIconSize . "; width:" . $cardIconSize . ";' src='./Images/Resource.png'>
      <div style='margin: 0; top: 50%; left: 50%; margin-right: -50%; width: 28px; height: 28px; padding: 3px; text-align: center; transform: translate(-50%, -50%); line-height: 1.2; position:absolute; z-index: 5; font-size:26px; font-weight: 600; color: #EEE; text-shadow: 3px 0 0 #000, 0 -2px 0 #000, 0 2px 0 #000, -2px 0 0 #000;'>" . $myResources[0] . "</div></img></span>");
    } else echo ("<div style='position:absolute; margin: 0; position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); color: " . $bordelessFontColor . "; user-select:none;'>Pitch</div>");
  }
  echo ("</div>");
  //End play area div

  //Display the log
  echo ("<div id='sidebarWrapper' style='display:flex; flex-direction: column; position:fixed; width:" . $rightSideWidth . "px; top:5px; bottom:5px; right:5px;'>");

  echo ("<div style='flex-grow:0; flex-shrink:0; position:relative; height:50px;'><div style='position:absolute; right:50px;'><table><tr>");
  if (IsPatron($playerID)) {
    echo ("<td><div class='MenuButtons' title='Click to view stats.' style='cursor:pointer;' onclick='ShowPopup(\"myStatsPopup\");'><img style='width:45px; height:45px;' src='./Images/stats.png' /></div></td>");
    echo ("<td></td><td>");
    echo ("<div class='MenuButtons' title='Click to view the menu. (Hotkey: M)' style='cursor:pointer;' onclick='ShowPopup(\"menuPopup\");'><img style='width:45px; height:45px;' src='./Images/menu.png' /></div>");
  } else {
    echo ("<td><div class='MenuButtons' title='Click to view the menu. (Hotkey: M)' style='cursor:pointer;' onclick='ShowPopup(\"menuPopup\");'><img style='width:45px; height:45px;' src='./Images/menu.png' /></div>");
  }
  echo ("</td></tr></table></div></div>");

  echo ("<div style='flex-grow:0; flex-shrink:0; text-align:center; margin-top: 3px; width:100%; font-weight:bold; font-size:24; font-weight: 550; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . "; user-select: none;'>Turn #" . $currentTurn . "</div>");
  echo ("<div style='flex-grow:0; flex-shrink:0; text-align:center; width:100%; font-weight:bold; font-size:16; font-weight: 550; color: " . $fontColor . "; text-shadow: 2px 0 0 " . $borderColor . ", 0 -2px 0 " . $borderColor . ", 0 2px 0 " . $borderColor . ", -2px 0 0 " . $borderColor . "; user-select: none;'>Last Played</div>");
  echo ("<div style='flex-grow:0; flex-shrink:0; position:relative; left: 6px; top:2px;'>");
  if (count($lastPlayed) == 0) echo Card($MyCardBack, "CardImages", intval($rightSideWidth * 1.3));
  else {
    echo Card($lastPlayed[0], "CardImages", intval($rightSideWidth * 1.3), controller: $lastPlayed[1]);
    if (count($lastPlayed) >= 4) {
      if ($lastPlayed[3] == "FUSED") echo ("<img title='This card was fused.' style='position:absolute; z-index:100; top:125px; left:7px;' src='./Images/fuse2.png' />");
      //else if($lastPlayed[3] == "UNFUSED") echo("<img title='This card was not fused.' style='position:absolute; z-index:100; top:125px; left:7px;' src='./Images/Unfused.png' />");
    }
  }
  echo ("</div>");

  if(IsStreamerMode($playerID))
  {
    echo ("<div id='streamerPlaceholder' style='flex-grow:0; flex-shrink:0; height:200px;'></div>");
  }
  else {
    echo ("<div style='position:relative; z-index:-1;'><img style='height:(" . intval($rightSideWidth / 2) . ")px; flex-grow:0; flex-shrink:0; width:100%;' src='./Images/phaseTracker2.png' />");
    $trackerColor = ($playerID == $currentPlayer ? "blue" : "red");
    if ($turn[0] == "B" || (count($layers) > 0 && $layers[0] == "DEFENDSTEP")) $trackerLeft = intval($rightSideWidth * .43);
    else if ($turn[0] == "A" || $turn[0] == "D") $trackerLeft = intval($rightSideWidth * .61);
    else if ($turn[0] == "PDECK" || $turn[0] == "ARS" || (count($layers) > 0 && ($layers[0] == "ENDTURN" || $layers[0] == "FINALIZECHAINLINK"))) $trackerLeft = intval($rightSideWidth * .795);
    else if (count($chainLinks) > 0) $trackerLeft = $trackerLeft = intval($rightSideWidth * .245);
    else $trackerLeft = $trackerLeft = intval($rightSideWidth * .065);
    echo ("<div style='position:absolute; z-index:0; top:47px; left:" . $trackerLeft . "px;'><img style='height:29px; width:30px;' src='./Images/" . $trackerColor . "PhaseMarker.png' /></div>");
    echo ("</div>");
  }
  echo ("<div id='gamelog' style='flex-grow:1; top:3px; border: 3px solid " . $borderColor . "; border-radius: 5px; position:relative; background-color: " . $backgroundColor . "; overflow-y: scroll;'>");
  EchoLog($gameName, $playerID);
  echo ("</div>");
  if ($playerID != 3) {
    echo ("<div id='chatPlaceholder' style='flex-grow:0; flex-shrink:0; height:26px;'></div>");
    echo ("</div>");
  }

  echo ("<div style='display:none;' id='lastCurrentPlayer'>" . $currentPlayer . "</div>");
  echo ("<div style='display:none;' id='passConfirm'>" . ($turn[0] == "ARS" && count($myHand) > 0 && !ArsenalFull($playerID) ? "true" : "false") . "</div>");
}

function PlayableCardBorderColor($cardID)
{
  if (HasReprise($cardID) && RepriseActive()) return 3;
  return 0;
}

function ItemOverlay($item, $isReady, $numUses)
{
  if($item == "EVR070" && $numUses < 3) return 1;
  return ($isReady != 2 ? 1 : 0);
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
    case "W":
      return "calc(50% - " . ($cardWidth / 2 + $cardWidth + 25) . "px)";
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
      return ($cardSize * 2 - 25) . "px";
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
      return "52px";
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
      return intval($rightSideWidth * 1.05) . "px";
    case "DECK":
      return intval($rightSideWidth * 1.05) . "px";
    case "BANISH":
      return intval($rightSideWidth * 1.05) . "px";
    case "PITCH":
      return (intval($rightSideWidth * 1.14) + $cardWidth) . "px";
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
      return ($cardSize - 10);
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
      return ($cardSize - 10);
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
    case "EVR195":
    case "UPR043":
    case "DYN243":
      return true;
    default:
      return false;
  }
}

function DisplayTiles($player)
{
  global $cardSizeAura, $playerID, $turn;
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

  $permanents = GetPermanents($player);
  $ashCount = 0;
  for ($i = 0; $i < count($permanents); $i += PermanentPieces()) {
    if ($permanents[$i] == "UPR043") ++$ashCount;
  }
  if ($ashCount > 0) echo (Card("UPR043", "concat", $cardSizeAura, 0, 1, 0, 0, ($ashCount > 1 ? $ashCount : 0)) . "&nbsp");

  DisplayPlayableTiles($player, $playerID);
}

function DisplayPlayableTiles($player, $playerID)
{
  global $turn, $cardSizeAura;

  $items = GetItems($player);

  if (CountItem("CRU197", $player) > 0) {
    $copperCount = 0;
    $playable = false;
    $actionIndex = -1;
    for ($i = 0; $i < count($items); $i += ItemPieces()) {
      if ($items[$i] == "CRU197") {
        ++$copperCount;
        if ($player == $playerID && $copperCount == 1) {
          $actionIndex = $i;
          $playable = IsPlayable($items[$i], $turn[0], "PLAY", $i);
        }
      }
    }
    if ($copperCount > 0) {
      $border = CardBorderColor("CRU197", "PLAY", $playable);
      echo (Card("CRU197", "concat", $cardSizeAura, $player == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, 0, $border, ($copperCount > 1 ? $copperCount : 0), strval($actionIndex)) . "&nbsp");
    }
  }
  if (CountItem("EVR195", $player) > 0) {
    $silverCount = 0;
    $playable = false;
    $actionIndex = -1;
    for ($i = 0; $i < count($items); $i += ItemPieces()) {
      if ($items[$i] == "EVR195") {
        ++$silverCount;
        if ($player == $playerID && $silverCount == 1) {
          $actionIndex = $i;
          $playable = IsPlayable($items[$i], $turn[0], "PLAY", $i);
        }
      }
    }
    if ($silverCount > 0) {
      $border = CardBorderColor("EVR195", "PLAY", $playable);
      echo (Card("EVR195", "concat", $cardSizeAura, $player == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, 0, $border, ($silverCount > 1 ? $silverCount : 0), strval($actionIndex)) . "&nbsp");
    }
  }
  if (CountItem("DYN243", $player) > 0) {
    $goldCount = 0;
    $playable = false;
    $actionIndex = -1;
    for ($i = 0; $i < count($items); $i += ItemPieces()) {
      if ($items[$i] == "DYN243") {
        ++$goldCount;
        if ($player == $playerID && $goldCount == 1) {
          $actionIndex = $i;
          $playable = IsPlayable($items[$i], $turn[0], "PLAY", $i);
        }
      }
    }
    if ($goldCount > 0) {
      $border = CardBorderColor("DYN243", "PLAY", $playable);
      echo (Card("DYN243", "concat", $cardSizeAura, $player == $playerID && $turn[0] != "P" && $playable ? 10 : 0, 1, 0, $border, ($goldCount > 1 ? $goldCount : 0), strval($actionIndex)) . "&nbsp");
    }
  }
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
    $position = "bottom: 3px;";
  }
  if ($setting != 2 && $playerID != 3) {
    $gem = ($setting == 1 ? "hexagonRedGem.png" : "hexagonGrayGem.png");
    if ($setting == 0) echo ("<img " . ProcessInputLink($playerID, ($otherPlayer ? 104 : 103), $MZindex) . " title='Not holding priority' style='position:absolute; display: inline-block; z-index:1001; " . $position . " left:" . $cardWidth / 2 - 13 . "px; width:40px; height:40px; cursor:pointer;' src='./Images/$gem' />");
    else if ($setting == 1) echo ("<img " . ProcessInputLink($playerID, ($otherPlayer ? 104 : 103), $MZindex) . " title='Holding priority' style='position:absolute; display: inline-block; z-index:1001; " . $position . " left:" . $cardWidth / 2 - 13 . "px; width:40px; height:40px; cursor:pointer;' src='./Images/$gem' />");
  }
}
