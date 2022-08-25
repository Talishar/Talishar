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

ob_start();
include "./ParseGamestate.php";
include "./GameLogic.php";
include "./Libraries/UILibraries2.php";
include "./Libraries/StatFunctions.php";
include "./Libraries/PlayerSettings.php";
include "./GameTerms.php";
ob_end_clean();

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
    echo (CreatePopup("myBanishPopup", [], 1, 0, "Your Banish", 1, BanishUI()));
    break;
  case "myStatsPopup":
    echo (CreatePopup("myStatsPopup", [], 1, 0, "Your Game Stats", 1, CardStats($playerID), "./", true));
    break;
  case "menuPopup":
    if (IsGameOver()) {
      $content = CreateButton($playerID, "Main Menu", 100001, 0, "24px", "", "", false, true);
      if ($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Rematch", 100004, 0, "24px");
      if ($playerID == 1) $content .= "&nbsp;" . CreateButton($playerID, "Quick Rematch", 100000, 0, "24px");
      $content .= "</div>" . CardStats($playerID);
      echo CreatePopup("OVER", [], 1, 1, "Player " . $winner . " Won! ", 1, $content, "./", true);
    } else {
      echo (CreatePopup("menuPopup", [], 1, 0, "Main Menu", 1, MainMenuUI(), "./", true));
    }
    break;
  case "mySoulPopup":
    echo (CreatePopup("mySoulPopup", $mySoul, 1, 0, "My Soul"));
    break;
  case "theirBanishPopup":
    $theirBanishDisplay = GetTheirBanishForDisplay();
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
    echo (CreatePopup("chainLinkPopup-" . $params[1], [], 1, 0, "Summary Chain Link " . $params[1] + 1, 1, ChainLinkPopup($params[1]), "./", false, false, "Total Damage Dealt: " . $chainLinkSummary[$params[1] * ChainLinkSummaryPieces()]));
    break;
  default:
    break;
}

function ChainLinkPopup($link)
{
  global $chainLinks, $cardSize, $playerID, $mainPlayer, $defPlayer;
  $rv = "";
  for ($i = 0; $i < count($chainLinks[$link]); $i += ChainLinksPieces()) {
    if ($chainLinks[$link][$i + 1] == $mainPlayer && CardType($chainLinks[$link][$i]) != "AR") {
      $attackValue = AttackValue($chainLinks[$link][$i]);
    } elseif ($chainLinks[$link][$i + 1] == $mainPlayer && (CardType($chainLinks[$link][$i]) == "AR" || CardType($chainLinks[$link][$i]) == "I")) {
      $attackValue = AttackModifier($chainLinks[$link][$i]);
    } else $attackValue = 0;

    if ($chainLinks[$link][$i + 1] == $defPlayer) $blockValue = BlockValue($chainLinks[$link][$i]);
    else $blockValue = 0;

    $rv .= Card($chainLinks[$link][$i], "concat", $cardSize, 0, 1, 0, ($chainLinks[$link][$i + 1] == $playerID ? 1 : 2), 0, "", "", false, 0, $blockValue, $attackValue);
    //$rv .= $chainLinks[$link][$i] . " ";

  }
  return $rv;
}
