<?php

session_start();

include "./Libraries/HTTPLibraries.php";

$gameName = $_GET["gameName"];
$playerID = $_GET["playerID"];

// For profile settings (playerID == 0), allow gameName == 0
if ($playerID == 0) {
  // Profile settings request - skip game name validation
} else {
  // Normal game request - validate game name
  if (!IsGameNameValid($gameName)) {
    echo ("Invalid game name.");
    exit;
  }
}

$authKey = TryGet("authKey", "");
$popupType = $_GET["popupType"];
$chainLinkIndex = TryGet("index", "");

ob_start();
include "./Libraries/SHMOPLibraries.php";

// For profile settings, skip ParseGamestate
if ($playerID != 0) {
  include "./ParseGamestate.php";
}

include "./GameLogic.php";
include "./Libraries/UILibraries.php";
include "./Libraries/StatFunctions.php";
include "./Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include "./GameTerms.php";
include "./HostFiles/Redirector.php";
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
ob_end_clean();

if ($playerID == 3) {
  include_once "./AccountFiles/AccountSessionAPI.php";
  
  if (!IsUserLoggedIn()) {
      loginFromCookie();
      
      if (!IsUserLoggedIn()) {
          if (!isset($response)) {
              $response = new stdClass();
          }
          $response->error = "You must be logged in to spectate games";
          echo json_encode($response);
          exit;
      }
  }
  //Now audit the spectate
  $userID = LoggedInUser();
  $conn = GetDBConnection();
  if ($conn->connect_error) {
    $response->error = "Database connection failed: " . ($conn && $conn->connect_error ? $conn->connect_error : "unknown");
    echo (json_encode($response));
    exit;
  }
  $query = "UPDATE users SET numSpectates = numSpectates + 1 WHERE usersId = ?";
  $stmt = $conn->prepare($query);
  $stmt->bind_param("i", $userID);
  $stmt->execute();
  $stmt->close();
}

// CORS etc *must* be set for all endpoints
SetHeaders();

$cardSize = 120;
$params = explode("-", $popupType);
$popupType = $params[0];
$response = new stdClass();
switch ($popupType) {
  case "attackSummary":
    $totalPower = 0;
    $totalDefense = 0;
    $powerModifiers = [];
    $response->Cards = array();
    EvaluateCombatChain($totalPower, $totalDefense, $powerModifiers);
    for ($i = 0; $i < count($powerModifiers); $i += 2) {
      $thisModifier = new stdClass();
      $idArr = explode("-", $powerModifiers[$i]);
      $cardID = ExtractCardID($idArr[0]);
      $bonus = $powerModifiers[$i + 1];
      if ($bonus == 0) continue;
      $effectName = CardName($cardID);
      if($effectName == "")
      {
        $effectName = $cardID;
        $cardID = "";
      }
      $thisModifier->Player = $mainPlayer;
      $thisModifier->Name = $effectName;
      $thisModifier->cardID = $cardID;
      $thisModifier->modifier = $bonus;
      array_push($response->Cards, $thisModifier);
    }
    break;
  case "myPitchPopup":
    JSONPopup($response, $myPitch, PitchPieces());
    break;
  case "myDiscardPopup":
    JSONPopup($response, $myDiscard, DiscardPieces());
    break;
  case "myBanishPopup":
    JSONPopup($response, $myBanish, BanishPieces());
    break;
  case "myDeckPopup":
    JSONPopup($response, $myDeck, DeckPieces());
    break;
  case "myStatsPopup":
    if($turn[0] == "OVER") SetCachePiece($gameName, 14, 99);//$MGS_GameOver
    // Get opponent's hero for export display
    $opponentPlayerID = ($playerID == 1 ? 2 : 1);
    $opponentDeckFile = "./Games/" . $gameName . "/p" . $opponentPlayerID . "Deck.txt";
    $opponentHero = "";
    if(file_exists($opponentDeckFile)) {
      $opponentDeckContent = file_get_contents($opponentDeckFile);
      $opponentDeckLines = explode("\r\n", $opponentDeckContent);
      if(count($opponentDeckLines) > 0) {
        $opponentHeroLine = explode(" ", trim($opponentDeckLines[0]));
        if(count($opponentHeroLine) > 0) {
          $opponentHero = $opponentHeroLine[0];
        }
      }
    }
    echo(SerializeGameResult($playerID, "", file_get_contents("./Games/" . $gameName . "/p" . $playerID . "Deck.txt"), $gameName, $opponentHero, "", "", includeFullLog:true));
    exit;
  case "mySoulPopup":
    JSONPopup($response, $mySoul, SoulPieces());
    break;
  case "theirBanishPopup":
    JSONPopup($response, $theirBanish, BanishPieces());
    break;
  case "theirPitchPopup":
    JSONPopup($response, $theirPitch, PitchPieces());
    break;
  case "theirDiscardPopup":
    JSONPopup($response, $theirDiscard, DiscardPieces());
    break;
  case "theirSoulPopup":
    JSONPopup($response, $theirSoul, SoulPieces());
    break;
  case "chainLinkPopup":
    $popupIndex = intval(($chainLinkIndex ?? $params[1]));
    $response = ChainLinkObject($popupIndex);
    $index = $popupIndex * ChainLinkSummaryPieces();
    if (isset($chainLinkSummary[$index])) {
        $response->TotalDamageDealt = $chainLinkSummary[$index];
    } else {
        $response->TotalDamageDealt = 0;
    }
    break;
  case "mySettings":
    global $SET_AlwaysHoldPriority, $SET_TryUI2, $SET_DarkMode, $SET_ManualMode, $SET_SkipARs, $SET_SkipDRs;
    global $SET_PassDRStep, $SET_AutotargetArcane, $SET_ColorblindMode, $SET_ShortcutAttackThreshold, $SET_EnableDynamicScaling;
    global $SET_Mute, $SET_Cardback, $SET_IsPatron, $SET_MuteChat, $SET_DisableStats, $SET_CasterMode, $SET_StreamerMode, $SET_AlwaysShowCounters;
    global $SET_Playmat, $SET_AlwaysAllowUndo, $SET_DisableAltArts, $SET_ManualTunic, $SET_DisableFabInsights, $SET_DisableHeroIntro, $SET_MirroredBoardLayout, $SET_MirroredPlayerBoardLayout;
    
    $response->Settings = array();
    
    // For profile settings (playerID == 0), load from database
    if ($playerID == 0) {
      include_once "./includes/functions.inc.php";
      $userID = $_SESSION["userid"] ?? "";
      $dbSettingsFlat = LoadSavedSettings($userID);
      
      // Convert flat array to associative array: [settingID => settingValue]
      $dbSettings = [];
      for ($i = 0; $i < count($dbSettingsFlat); $i += 2) {
        $settingNumber = $dbSettingsFlat[$i];
        $settingValue = $dbSettingsFlat[$i + 1];
        $dbSettings[$settingNumber] = $settingValue;
      }
      
      // Using numeric IDs from ParseSettingsStringValueToIdInt
      AddSettingFromDB($response->Settings, "HoldPrioritySetting", 0, $dbSettings);
      AddSettingFromDB($response->Settings, "TryReactUI", 1, $dbSettings);
      AddSettingFromDB($response->Settings, "DarkMode", 2, $dbSettings);
      AddSettingFromDB($response->Settings, "ManualMode", 3, $dbSettings);
      AddSettingFromDB($response->Settings, "SkipARWindow", 4, $dbSettings);
      AddSettingFromDB($response->Settings, "SkipDRWindow", 5, $dbSettings);
      AddSettingFromDB($response->Settings, "AutoTargetOpponent", 7, $dbSettings);
      AddSettingFromDB($response->Settings, "ColorblindMode", 8, $dbSettings);
      AddSettingFromDB($response->Settings, "ShortcutAttackThreshold", 9, $dbSettings);
      AddSettingFromDB($response->Settings, "MuteSound", 11, $dbSettings);
      AddSettingFromDB($response->Settings, "CardBack", 12, $dbSettings);
      AddSettingFromDB($response->Settings, "IsPatron", 13, $dbSettings);
      AddSettingFromDB($response->Settings, "MuteChat", 14, $dbSettings);
      AddSettingFromDB($response->Settings, "DisableStats", 15, $dbSettings);
      AddSettingFromDB($response->Settings, "DisableAltArts", 26, $dbSettings);
      AddSettingFromDB($response->Settings, "IsCasterMode", 16, $dbSettings);
      AddSettingFromDB($response->Settings, "IsStreamerMode", 23, $dbSettings);
      AddSettingFromDB($response->Settings, "Playmat", 24, $dbSettings);
      AddSettingFromDB($response->Settings, "AlwaysAllowUndo", 25, $dbSettings);
      AddSettingFromDB($response->Settings, "ManualTunic", 27, $dbSettings);
      AddSettingFromDB($response->Settings, "DisableFabInsights", 28, $dbSettings);
      AddSettingFromDB($response->Settings, "DisableHeroIntro", 29, $dbSettings);
      AddSettingFromDB($response->Settings, "MirroredBoardLayout", 30, $dbSettings);
      AddSettingFromDB($response->Settings, "MirroredPlayerBoardLayout", 31, $dbSettings);
      AddSettingFromDB($response->Settings, "AlwaysShowCounters", 32, $dbSettings);
    } else {
      // Normal game settings
      AddSetting($response->Settings, "HoldPrioritySetting", $SET_AlwaysHoldPriority);
      AddSetting($response->Settings, "TryReactUI", $SET_TryUI2);
      AddSetting($response->Settings, "DarkMode", $SET_DarkMode);
      AddSetting($response->Settings, "ManualMode", $SET_ManualMode);
      AddSetting($response->Settings, "SkipARWindow", $SET_SkipARs);
      AddSetting($response->Settings, "SkipDRWindow", $SET_SkipDRs);
      AddSetting($response->Settings, "AutoTargetOpponent", $SET_AutotargetArcane);
      AddSetting($response->Settings, "ColorblindMode", $SET_ColorblindMode);
      AddSetting($response->Settings, "ShortcutAttackThreshold", $SET_ShortcutAttackThreshold);
      AddSetting($response->Settings, "MuteSound", $SET_Mute);
      AddSetting($response->Settings, "CardBack", $SET_Cardback);
      AddSetting($response->Settings, "IsPatron", $SET_IsPatron);
      AddSetting($response->Settings, "MuteChat", $SET_MuteChat);
      AddSetting($response->Settings, "DisableStats", $SET_DisableStats);
      AddSetting($response->Settings, "DisableAltArts", $SET_DisableAltArts);
      AddSetting($response->Settings, "IsCasterMode", $SET_CasterMode);
      AddSetting($response->Settings, "IsStreamerMode", $SET_StreamerMode);
      AddSetting($response->Settings, "Playmat", $SET_Playmat);
      AddSetting($response->Settings, "AlwaysAllowUndo", $SET_AlwaysAllowUndo);
      AddSetting($response->Settings, "ManualTunic", $SET_ManualTunic);
      AddSetting($response->Settings, "DisableFabInsights", $SET_DisableFabInsights);
      AddSetting($response->Settings, "DisableHeroIntro", $SET_DisableHeroIntro);
      AddSetting($response->Settings, "MirroredBoardLayout", $SET_MirroredBoardLayout);
      AddSetting($response->Settings, "MirroredPlayerBoardLayout", $SET_MirroredPlayerBoardLayout);
      AddSetting($response->Settings, "AlwaysShowCounters", $SET_AlwaysShowCounters);
      $response->isSpectatingEnabled = GetCachePiece($gameName, 9) == "1";
    }
    break;
  default:
    break;
}

echo json_encode($response);

function AddSettingFromDB(&$response, $name, $settingID, $dbSettings)
{
  $thisSetting = new stdClass();
  $thisSetting->name = $name;
  $thisSetting->value = isset($dbSettings[$settingID]) ? $dbSettings[$settingID] : null;
  array_push($response, $thisSetting);
}

function AddSetting(&$response, $name, $setting)
{
  global $playerID;
  $mySettings = &GetSettings($playerID);
  $thisSetting = new stdClass();
  $thisSetting->name = $name;
  // Use isset to handle new settings that may not exist for existing players
  $thisSetting->value = isset($mySettings[$setting]) ? $mySettings[$setting] : "0";
  array_push($response, $thisSetting);
}

function JSONPopup($response, $zone, $zonePieces)
{
  $response->cards = array();
  for($i=0; $i<count($zone); $i+=$zonePieces)
  {
    array_push($response->cards, JSONRenderedCard($zone[$i]));
  }
}

function ChainLinkObject($link)
{
  global $chainLinks, $mainPlayer, $defPlayer;
  $chainLink = new stdClass();
  $chainLink->Cards = array();
  if (!is_array($chainLinks) || empty($chainLinks[$link])) {
    return $chainLink;
  }
  for ($i = 0; $i < count($chainLinks[$link]); $i += ChainLinksPieces()) {
    $card = new stdClass();
    $card->Player = $chainLinks[$link][$i+1];
    if ($chainLinks[$link][$i + 1] == $mainPlayer && CardType($chainLinks[$link][$i]) != "AR")
    {
      $powerValue = PowerValue($chainLinks[$link][$i], $mainPlayer, "CC") + $chainLinks[$link][$i + 4];
    }
    elseif ($chainLinks[$link][$i + 1] == $mainPlayer && (CardType($chainLinks[$link][$i]) == "AR" || DelimStringContains(CardType($chainLinks[$link][$i]), "I")))
    {
      $powerValue = PowerModifier($chainLinks[$link][$i]);
    }
    else $powerValue = 0;
    $uid = $chainLinks[$link][$i + 8];
    if ($chainLinks[$link][$i + 1] == $defPlayer) $blockValue = ModifiedBlockValue($chainLinks[$link][$i], $defPlayer, "CC", "", $uid) + $chainLinks[$link][$i + 5];
    else $blockValue = 0;

    if($card->Player == $mainPlayer) $card->modifier = $powerValue;
    else $card->modifier = $blockValue;
    $card->cardID = $chainLinks[$link][$i];
    $card->Name = CardName($chainLinks[$link][$i]);

    array_push($chainLink->Cards, $card);
  }
  return $chainLink;
}
