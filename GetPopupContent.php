<?php

include "./Libraries/HTTPLibraries.php";

$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$authKey = TryGet("authKey", "");
$popupType = $_GET["popupType"];
$chainLinkIndex = TryGet("chainLinkIndex", "");

ob_start();
include "./ParseGamestate.php";
include "./GameLogic.php";
include "./Libraries/SHMOPLibraries.php";
include "./Libraries/UILibraries2.php";
include "./Libraries/StatFunctions.php";
include "./Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include "./GameTerms.php";
include "./HostFiles/Redirector.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
ob_end_clean();

session_start();

$cardSize = 120;
$params = explode("-", $popupType);
$popupType = $params[0];
switch ($popupType) {
  case "myPitchPopup":
    echo (CreatePopup("myPitchPopup", $myPitch, 1, 0, "Your Pitch"));
    break;
  case "myDiscardPopup":
    echo (CreatePopup("myDiscardPopup", $myDiscard, 1, 0, "Your Discard"));
    break;
  case "myBanishPopup":
    if ($playerID == 3) {
      $myBanishDisplay = GetMyBanishForDisplay($playerID);
      echo (CreatePopup("myBanishPopup", $myBanishDisplay, 1, 0, "Banish Zone"));
    }
    else echo (CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI()));
    break;
  case "myDeckPopup":
    echo (CreatePopup("myDeckPopup", $myDeck, 1, 0, "Your Deck (In order from top to bottom"));
    break;
  case "myStatsPopup":
    echo (CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID), "./", true));
    break;
  case "menuPopup":
    if (IsGameOver()) {
      if($roguelikeGameID != "")
      {
        $content = CreateButton($playerID, "Continue Adventure", 100011, 0, "24px", "", "", false, true);
      }
      else
      {
        $content = CreateButton($playerID, "Main Menu", 100001, 0, "24px", "", "", false, true);
        if ($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Rematch", 100004, 0, "24px");
        if ($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Quick Rematch", 100000, 0, "24px");
        $content .= CreateButton($playerID, "Report Bug", 100003, 0, "24px") . "<BR>";
      }
      $content .= "</div>";
      $time = ($playerID == 1 ? $p1TotalTime : $p2TotalTime);
      $totalTime = $p1TotalTime + $p2TotalTime;
      $content .= "<BR><span class='Time-Span'>Your Play Time: " . intval($time / 60) . "m" . $time % 60 . "s - Game Time: " . intval($totalTime / 60) . "m" . $totalTime % 60 . "s</span>";
      $content .= CardStats($playerID);
      echo CreatePopup("OVER", [], 1, 1, "Player " . $winner . " Won! ", 1, $content, "./", true);
    } else {
      echo (CreatePopup("menuPopup", [], 1, 0, "Main Menu", 1, MainMenuUI(), "./", true));
    }
    break;
  case "mySoulPopup":
    echo (CreatePopup("mySoulPopup", $mySoul, 1, 0, "My Soul"));
    break;
  case "theirBanishPopup":
    $theirBanishDisplay = GetTheirBanishForDisplay($playerID);
    echo (CreatePopup("theirBanishPopup", $theirBanishDisplay, 1, 0, "Opponent's Banish Zone"));
    break;
  case "theirPitchPopup":
    echo (CreatePopup("theirPitchPopup", $theirPitch, 1, 0, "Opponent's Pitch Zone"));
    break;
  case "theirDiscardPopup":
    echo (CreatePopup("theirDiscardPopup", $theirDiscard, 1, 0, "Opponent's Discard Zone"));
    break;
  case "theirSoulPopup":
    echo (CreatePopup("theirSoulPopup", $theirSoul, 1, 0, "Opponent's Soul"));
    break;
  case "chainLinkPopup":
    $popupIndex = intval(($chainLinkIndex != "" ? $chainLinkIndex : $params[1]));
    echo (CreatePopup("chainLinkPopup-" . $popupIndex, [], 1, 0, "Summary Chain Link " . $popupIndex + 1, 1, ChainLinkPopup($popupIndex), "./", false, false, "Total Damage Dealt: " . $chainLinkSummary[$popupIndex * ChainLinkSummaryPieces()]));
    break;
  default:
    break;
}

function ChainLinkPopup($link)
{
  global $chainLinks, $cardSize, $playerID, $mainPlayer, $defPlayer;
  $rv = "";
  for ($i = 0; $i < count($chainLinks[$link]); $i += ChainLinksPieces()) {
    if ($chainLinks[$link][$i + 1] == $mainPlayer && CardType($chainLinks[$link][$i]) != "AR")
    {
      $attackValue = AttackValue($chainLinks[$link][$i]) + $chainLinks[$link][$i + 4];
    }
    elseif ($chainLinks[$link][$i + 1] == $mainPlayer && (CardType($chainLinks[$link][$i]) == "AR" || CardType($chainLinks[$link][$i]) == "I"))
    {
      $attackValue = AttackModifier($chainLinks[$link][$i]);
    }
    else $attackValue = 0;

    if ($chainLinks[$link][$i + 1] == $defPlayer) $blockValue = BlockValue($chainLinks[$link][$i]) + $chainLinks[$link][$i + 5];
    else $blockValue = 0;

    $rv .= Card($chainLinks[$link][$i], "concat", $cardSize, 0, 1, 0, ($chainLinks[$link][$i + 1] == $playerID ? 1 : 2), 0, "", "", false, 0, $blockValue, $attackValue);
    //$rv .= $chainLinks[$link][$i] . " ";

  }
  return $rv;
}
