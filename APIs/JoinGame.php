<?php

include_once "../WriteLog.php";
include_once "../Libraries/HTTPLibraries.php";
include_once "../Libraries/SHMOPLibraries.php";
include_once "../APIKeys/APIKeys.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';

if (!function_exists("DelimStringContains")) {
  function DelimStringContains($str, $find, $partial=false)
  {
    $arr = explode(",", $str);
    for($i=0; $i<count($arr); ++$i)
    {
      if($partial && str_contains($arr[$i], $find)) return true;
      else if($arr[$i] == $find) return true;
    }
    return false;
  }
}

if (!function_exists("SubtypeContains")) {
  function SubtypeContains($cardID, $subtype)
  {
    $cardSubtype = CardSubtype($cardID);
    return DelimStringContains($cardSubtype, $subtype);
  }
}

if (!function_exists("TypeContains")) {
  function TypeContains($cardID, $type)
  {
    $cardType = CardType($cardID);
    return DelimStringContains($cardType, $type);
  }
}

SetHeaders();

$response = new stdClass();

session_start();
if (!isset($gameName)) {
  $_POST = json_decode(file_get_contents('php://input'), true);
  if($_POST == NULL) {
    $response->error = "Parameters were not passed";
    echo json_encode($response);
    exit;
  }
  $gameName = $_POST["gameName"];
}
if (!IsGameNameValid($gameName)) {
  $response->error = "Invalid game name.";
  echo (json_encode($response));
  exit;
}
if (!isset($playerID)) $playerID = intval($_POST["playerID"]);
if (!isset($deck)) $deck = TryPOST("deck"); //This is for limited game modes (see JoinGameInput.php)
if (!isset($decklink)) $decklink = TryPOST("fabdb", ""); //Deck builder decklink
if (!isset($decksToTry)) $decksToTry = TryPOST("decksToTry"); //This is only used if there's no favorite deck or decklink. 1 = ira
if (!isset($favoriteDeck)) $favoriteDeck = TryPOST("favoriteDeck", false); //Set this to true to save the provided deck link to your favorites
if (!isset($favoriteDeckLink)) $favoriteDeckLink = TryPOST("favoriteDecks", "0"); //This one is kind of weird. It's the favorite deck index, then the string "<fav>" then the favorite deck link
if (!isset($matchup)) $matchup = TryPOST("matchup", ""); //The matchup link
$starterDeck = false;

if ($matchup == "" && GetCachePiece($gameName, $playerID + 6) != "") {
  $response->error = "Another player has already joined the game.";
  echo (json_encode($response));
  exit;
}
if ($decklink == "" && $deck == "" && $favoriteDeckLink == "0") {
  $starterDeck = true;
  switch ($decksToTry) {
    case '1':
      $decklink = "https://fabrary.net/decks/01GJG7Z4WGWSZ95FY74KX4M557";
      break;
    default:
      $decklink = "https://fabrary.net/decks/01GJG7Z4WGWSZ95FY74KX4M557";
      break;
  }
}

if ($favoriteDeckLink != "0" && $decklink == "") $decklink = $favoriteDeckLink;

if ($deck == "" && !IsDeckLinkValid($decklink)) {
  $response->error = "Deck URL is not valid: " . $decklink;
  echo (json_encode($response));
  exit;
}

include "../HostFiles/Redirector.php";
include "../CardDictionary.php";
include "./APIParseGamefile.php";
include "../MenuFiles/WriteGamefile.php";

$joinerName = (isset($_SESSION["useruid"]) ? $_SESSION["useruid"] : "Player 2");
if(($playerID == 3 && $joinerName == "starmorgs") || (($p1uid == "zeni" || $p1uid == "rkhalid890") && $joinerName == "starmorgs") || ($p1uid == "starmorgs" && ($joinerName == "zeni" || $joinerName == "rkhalid890"))) {
  $response->error = "Unable to join this game.";
  WriteGameFile();
  echo (json_encode($response));
  exit;
}

if ($matchup == "" && $playerID == 2 && $gameStatus >= $MGS_Player2Joined) {
  if ($gameStatus >= $MGS_GameStarted) {
    $response->gameStarted = true;
  } else {
    $response->error = "Another player has already joined the game.";
  }
  WriteGameFile();
  echo (json_encode($response));
  exit;
}

$deckLoaded = false;

if ($decklink != "") {
  if ($playerID == 1) $p1DeckLink = $decklink;
  else if ($playerID == 2) $p2DeckLink = $decklink;
  $curl = curl_init();
  $isFaBDB = str_contains($decklink, "fabdb");
  $isFaBMeta = str_contains($decklink, "fabmeta");
  if ($isFaBDB) {
    $decklinkArr = explode("/", $decklink);
    $slug = $decklinkArr[count($decklinkArr) - 1];
    $apiLink = "https://api.fabdb.net/decks/" . $slug;
  } else if (str_contains($decklink, "fabrary")) {
    $headers = array(
      "x-api-key: " . $FaBraryKey,
      "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $decklinkArr = explode("/", $decklink);
    $decklinkArr = explode("?", $decklinkArr[count($decklinkArr) - 1]);
    $slug = $decklinkArr[0];
    $apiLink = "https://atofkpq0x8.execute-api.us-east-2.amazonaws.com/prod/v1/decks/" . $slug;
    if ($matchup != "") $apiLink .= "?matchupId=" . $matchup;
  } else {
    $decklinkArr = explode("/", $decklink);
    $slug = $decklinkArr[count($decklinkArr) - 1];
    $apiLink = "https://api.fabmeta.net/deck/" . $slug;
  }
  $response->apiLink = $apiLink;
  curl_setopt($curl, CURLOPT_URL, $apiLink);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $apiDeck = curl_exec($curl);
  $apiInfo = curl_getinfo($curl);
  curl_close($curl);

  if ($apiDeck === FALSE) {
    WriteGameFile();
    if(is_array($decklink)) $response->error = "Deckbuilder API for this deck returns no data: " . implode("/", $decklink);
    else $response->error = "Deckbuilder API for this deck returns no data: " . $decklink;
    echo (json_encode($response));
    exit;
  }
  $deckObj = json_decode($apiDeck);
  // if has message forbidden error out.
  if ($apiInfo['http_code'] == 403) {
    $response->error = "API FORBIDDEN! Invalid or missing token to access API: " . $apiLink . " The response from the deck hosting service was: " . $apiDeck;
    echo (json_encode($response));
    die();
  }
  if ($deckObj == null) {
    $response->error = 'Deck object is null. Failed to retrieve deck from API.';
    echo (json_encode($response));
    exit;
  }
  if(!isset($deckObj->{'name'}))
  {
    $response->error = 'Deck is invalid. Failed to retrieve deck from API.';
    echo (json_encode($response));
    exit;
  }
  $deckName = $deckObj->{'name'};
  if (isset($deckObj->{'matchups'})) {
    if ($playerID == 1) $p1Matchups = $deckObj->{'matchups'};
    else if ($playerID == 2) $p2Matchups = $deckObj->{'matchups'};
  }
  $deckFormat = (isset($deckObj->{'format'}) ? $deckObj->{'format'} : "");
  $cards = $deckObj->{'cards'};
  $deckCards = "";
  $sideboardCards = "";
  $headSideboard = "";
  $chestSideboard = "";
  $armsSideboard = "";
  $legsSideboard = "";
  $offhandSideboard = "";
  $quiverSideboard = "";
  $modularSideboard = "";
  $unsupportedCards = "";
  $bannedCard = "";
  $restrictedCard = "";
  $isDeckBlitzLegal = "";
  $isDeckCCLegal = "";
  $character = "";
  $head = "";
  $chest = "";
  $arms = "";
  $legs = "";
  $offhand = "";
  $quiver = "";
  $weapon1 = "";
  $weapon2 = "";
  $weaponSideboard = "";
  $totalCards = 0;
  $orderedSets = ["WTR", "ARC", "CRU", "MON", "ELE", "EVR", "UPR", "DYN", "OUT", "DTD", "TCC", "EVO", "HVY", "MST", "AKO", "ASB", "ROS", "AAZ", "TER", "AUR", "AIO", "AJV", "HNT"];

  if (is_countable($cards)) {
    for ($i = 0; $i < count($cards); ++$i) {
      $count = $cards[$i]->{'total'};
      $numSideboard = (isset($cards[$i]->{'sideboardTotal'}) ? $cards[$i]->{'sideboardTotal'} : 0);
      $id = GetCardId($cards[$i], $isFaBDB, $isFaBMeta, $orderedSets);
      if($id == "" && isset($cards[$i]->{'cardIdentifier'})) {
        $id = $cards[$i]->{'cardIdentifier'};
      }
      if ($id == "") continue;   
      ProcessCard($id, $count, $numSideboard, $isFaBDB, $totalCards, $modularSideboard, $unsupportedCards, $character, $weapon1, $weapon2, $weaponSideboard, $head, $headSideboard, $chest, $chestSideboard, $arms, $armsSideboard, $legs, $legsSideboard, $offhand, $offhandSideboard, $quiver, $quiverSideboard, $deckCards, $sideboardCards, $format, $character);

      if (IsCardBanned($id, $format, $character) && $format != "draft") {
        if ($bannedCard != "") $bannedCard .= ", ";
        $bannedCard .= PitchValue($id) > 0 ? CardName($id) . " (" . PitchValue($id) . ")" : CardName($id);
      }

      // Track the count of each card ID
      if (!isset($cardCounts[$id])) {
        $cardCounts[$id] = 0;
      }
      $cardCounts[$id] += $count;

      if(isCardRestricted($id, $format, $cardCounts[$id])) {
        if ($restrictedCard != "") $restrictedCard .= ", ";
        $restrictedCard .= PitchValue($id) > 0 ? CardName($id) . " (" . PitchValue($id) . ")" : CardName($id);
      }

      if($character != "TCC027" && $id != "TCC048") { //Exclude Brevant and Chivalry
        // Deck Check to make sure players don't run more than 2 copies of cards in Young Hero formats
        if (($format == "blitz" || $format == "compblitz" || $format == "openformatblitz" || $format == "clash") && $cardCounts[$id] > 2) {
          if ($isDeckBlitzLegal != "") $isDeckBlitzLegal .= ", ";
          $isDeckBlitzLegal .= PitchValue($id) > 0 ? CardName($id) . " (" . PitchValue($id) . ")" : CardName($id);
        }
      }
      // Deck Check to make sure players don't run more than 3 copies of cards in Classic Constructed formats
      if (($format == "cc" || $format == "compcc" || $format == "openformatcc" || $format == "llcc") && $cardCounts[$id] > 3) {
        if ($isDeckCCLegal != "") $isDeckCCLegal .= ", ";
        $isDeckCCLegal .= CardName($id);
      }
    }
    $deckLoaded = true;
  }

  if(!$deckLoaded) {
    $response->error = "⚠️ Error retrieving deck. Decklist link invalid.";
    echo(json_encode($response));
    exit;
  }

  if($isDeckBlitzLegal != "") {
    $response->error = "⚠️ The deck contains extra copies of cards and isn't legal: " . $isDeckBlitzLegal . ".";
    echo (json_encode($response));
    exit;
  }

  if($isDeckCCLegal != "") {
    $response->error = "⚠️ The deck contains extra copies of cards and isn't legal: " . $isDeckCCLegal . ".";
    echo (json_encode($response));
    exit;
  }

  if($unsupportedCards != "") {
    $response->error = "⚠️ The following cards are not yet supported: " . $unsupportedCards . ".";
    echo (json_encode($response));
    exit;
  }

  if (CharacterHealth($character) < 30 && ($format == "cc" || $format == "compcc" || $format == "openformatcc")) {
    $response->error = "⚠️ Young heroes are not legal in Classic Constructed: Young - " . CardName($character) . ".";
    echo (json_encode($response));
    exit;
  }

  if (CharacterHealth($character) >= 30 && ($format == "blitz" || $format == "compblitz" || $format == "clash" || $format == "openformatblitz")) {
    $response->error = "⚠️ Adult heroes are not legal in this format: " . CardName($character) . ".";
    echo (json_encode($response));
    exit;
  }

  if ($bannedCard != "") {
    $response->error = "⚠️ The following cards are not legal in this format: " . $bannedCard . ".";
    echo (json_encode($response));
    exit;
  }

  if ($restrictedCard != "") {
    $response->error = "⚠️ The following cards are restricted to up to 1 copy in this format: " . $restrictedCard . ".";
    echo (json_encode($response));
    exit;
  }

  if ($totalCards < 60  && ($format == "cc" || $format == "compcc")) {
    $response->error = "⚠️ The deck link you have entered has too few cards (" . $totalCards . ") and is likely for the blitz/commoner format. Please double-check your decklist link and try again.";
    echo (json_encode($response));
    exit;
  }

  if (($totalCards < 40 || $totalCards > 52) && ($format == "blitz" || $format == "compblitz" || $format == "commoner" || $format == "clash")) {
    $response->error = "⚠️ The deck link you have entered does not have 40 cards (" . $totalCards . ") and is likely for CC. Please double-check your decklist link and try again.";
    echo (json_encode($response));
    exit;
  }

  if ($totalCards > 80  && ($format == "cc" || $format == "compcc")) {
    $response->error = "⚠️ The deck link you have entered has too many cards (" . $totalCards . "). Please double-check your decklist link and try again.";
    echo (json_encode($response));
    exit;
  }

  //We have the decklist, now write to file
  $filename = "../Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $deckFile = fopen($filename, "w");
  $charString = $character;
  if ($weapon1 != "") $charString .= " " . $weapon1;
  if ($weapon2 != "") $charString .= " " . $weapon2;
  if ($offhand != "") $charString .= " " . $offhand;
  if ($quiver != "") $charString .= " " . $quiver;
  if ($head != "") $charString .= " " . $head;
  if ($chest != "") $charString .= " " . $chest;
  if ($arms != "") $charString .= " " . $arms;
  if ($legs != "") $charString .= " " . $legs;
  fwrite($deckFile, $charString . "\r\n");
  fwrite($deckFile, $deckCards . "\r\n");
  fwrite($deckFile, $headSideboard . "\r\n");
  fwrite($deckFile, $chestSideboard . "\r\n");
  fwrite($deckFile, $armsSideboard . "\r\n");
  fwrite($deckFile, $legsSideboard . "\r\n");
  fwrite($deckFile, $offhandSideboard . "\r\n");
  fwrite($deckFile, $weaponSideboard . "\r\n");
  fwrite($deckFile, $sideboardCards . "\r\n");
  fwrite($deckFile, $quiverSideboard . "\r\n");
  fwrite($deckFile, $modularSideboard);
  fclose($deckFile);
  copy($filename, "../Games/" . $gameName . "/p" . $playerID . "DeckOrig.txt");

  if (isset($_SESSION["userid"])) {
    include_once '../includes/functions.inc.php';
    include_once "../includes/dbh.inc.php";
    $deckbuilderID = GetDeckBuilderId($_SESSION["userid"], $decklink);
    if ($deckbuilderID != "") {
      if ($playerID == 1) $p1deckbuilderID = $deckbuilderID;
      else $p2deckbuilderID = $deckbuilderID;
    }
  }

  if ($favoriteDeck && isset($_SESSION["userid"])) {
    //Save deck
    include_once '../includes/functions.inc.php';
    include_once "../includes/dbh.inc.php";
    addFavoriteDeck($_SESSION["userid"], $decklink, $deckName, $character, $deckFormat);
  }
}

if (!isset($character) || $character == "") {
  $response->error = "There is no character. Something went wrong with parsing your deck.";
  echo (json_encode($response));
  exit;
}

if ($matchup == "") {
  if ($playerID == 2) {

    $gameStatus = $MGS_Player2Joined;
    if (file_exists("../Games/" . $gameName . "/gamestate.txt")) unlink("../Games/" . $gameName . "/gamestate.txt");

    $firstPlayerChooser = 1;
    $p1roll = 0;
    $p2roll = 0;
    $tries = 10;
    while ($p1roll == $p2roll && $tries > 0) {
      $p1roll = rand(1, 6) + rand(1, 6);
      $p2roll = rand(1, 6) + rand(1, 6);
      WriteLog("🎲 Player 1 rolled $p1roll and Player 2 rolled $p2roll.", path: "../");
      --$tries;
    }
    $firstPlayerChooser = ($p1roll > $p2roll ? 1 : 2);
    WriteLog("Player $firstPlayerChooser chooses who goes first.", path: "../");
    $gameStatus = $MGS_ChooseFirstPlayer;
    $joinerIP = $_SERVER['REMOTE_ADDR'];
  }

  if ($playerID == 1) {
    $p1uid = (isset($_SESSION["useruid"]) ? $_SESSION["useruid"] : "Player 1");
    $p1id = (isset($_SESSION["userid"]) ? $_SESSION["userid"] : "");
    $p1IsPatron = (isset($_SESSION["isPatron"]) || isset($_SESSION["isPvtVoidPatron"]) ? "1" : "");
    $p1ContentCreatorID = (isset($_SESSION["patreonEnum"]) ? $_SESSION["patreonEnum"] : "");
  } else if ($playerID == 2) {
    $p2uid = (isset($_SESSION["useruid"]) ? $_SESSION["useruid"] : "Player 2");
    $p2id = (isset($_SESSION["userid"]) ? $_SESSION["userid"] : "");
    $p2IsPatron = (isset($_SESSION["isPatron"]) || isset($_SESSION["isPvtVoidPatron"]) ? "1" : "");
    $p2ContentCreatorID = (isset($_SESSION["patreonEnum"]) ? $_SESSION["patreonEnum"] : "");
  }

  if ($playerID == 2) $p2Key = hash("sha256", rand() . rand() . rand());

  WriteGameFile();
  SetCachePiece($gameName, $playerID + 1, strval(round(microtime(true) * 1000)));
  SetCachePiece($gameName, $playerID + 3, "0");
  SetCachePiece($gameName, $playerID + 6, $character);
  SetCachePiece($gameName, 14, $gameStatus);
  GamestateUpdated($gameName);

  //$authKey = ($playerID == 1 ? $p1Key : $p2Key);
  //$_SESSION["authKey"] = $authKey;
  $domain = (!empty(getenv("DOMAIN")) ? getenv("DOMAIN") : "talishar.net");
  if ($playerID == 1) {
    $_SESSION["p1AuthKey"] = $p1Key;
    setcookie("lastAuthKey", $p1Key, time() + 86400, "/", $domain);
  } else if ($playerID == 2) {
    $_SESSION["p2AuthKey"] = $p2Key;
    setcookie("lastAuthKey", $p2Key, time() + 86400, "/", $domain);
  }
}

$response->message = "success";
$response->gameName = $gameName;
$response->playerID = $playerID;
$response->authKey = $playerID == 1 ? $p1Key : ($playerID == 2 ? $p2Key : '');
echo (json_encode($response));

session_write_close();

function GetAltCardID($cardID)
{
  switch ($cardID) {
    case "OXO001": return "WTR155";
    case "OXO002": return "WTR156";
    case "OXO003": return "WTR157";
    case "OXO004": return "WTR158";
    case "BOL002": return "MON405";
    case "BOL006": return "MON400";
    case "CHN002": return "MON407";
    case "CHN006": return "MON401";
    case "LEV002": return "MON406";
    case "LEV005": return "MON400";
    case "PSM002": return "MON404";
    case "PSM007": return "MON402";
    case "FAB015": return "WTR191";
    case "FAB016": return "WTR162";
    case "FAB023": return "MON135";
    case "FAB024": return "ARC200";
    case "FAB030": return "DYN030";
    case "FAB057": return "EVR063";
    case "DVR026": return "WTR182";
    case "RVD008": return "WTR006";
    case "EVR120": return "UPR103";
    case "UPR209": return "WTR191";
    case "UPR210": return "WTR192";
    case "UPR211": return "WTR193";
    case "HER075": return "DYN025";
    case "LGS112": return "DYN070";
    case "LGS116": return "DYN200";
    case "LGS117": return "DYN201";
    case "LGS118": return "DYN202";
    case "ARC218": case "UPR224": case "MON306": case "ELE237": return "WTR224";
    case "DYN238": return "MON401";
    case "LGS157": return "DTD155";
    case "LGS158": return "DTD156";
    case "LGS159": return "DTD157";
    case "HER085": return "DTD134";
    case "DTD013": return "MON007";
    case "FAB161": return "DTD048";
    case "FAB162": return "DTD049";
    case "FAB163": return "DTD050";
    case "LGS179": return "DTD054";
    case "LGS180": return "DTD055";
    case "LGS181": return "DTD056";
    case "EVO038": return "TCC007";
    case "EVO039": return "TCC008";
    case "EVO040": return "TCC009";
    case "EVO041": return "TCC010";
    case "EVO064"; return "TCC012";
    case "EVO099": return "ARC036";
    case "EVO159": return "TCC019";
    case "EVO160": return "TCC022";
    case "EVO161": return "TCC026";
    case "EVO216": return "TCC016";
    case "TCC003": return "EVO022";
    case "TCC004": return "EVO023";
    case "TCC005": return "EVO024";
    case "TCC006": return "EVO025";
    case "DRO026": return "WTR173";
    case "AUR002": return "ROS009";
    case "JDG032": return "AIO004";
    case "ARK007": return "HNT407";
  }
  return $cardID;
}

function isClashLegal($cardID, $character) {
  $set = substr($cardID, 0, 3);
  $number = intval(substr($cardID, 3, 3));
  switch ($cardID) { //Special Use Promos
    case "JDG001": case "JDG003": case "JDG006": case "JDG010": case "JDG010": case "JDG019": case "JDG024": case "JDG025": case "JDG038":
      return true;
      default:
      break;
    }
  switch (CardType($cardID)) {
    case "C": case "M": case "W":
      return true;
    default: break;
  }
  if(IsSpecialization($cardID)) return true;
  if(Rarity($cardID) == "C" || Rarity($cardID) == "T" || Rarity($cardID) == "R") return true;
  if(($character == "DYN001" || $character == "") && $cardID == "ARC159") return true; //C&C is legal for Emperor in Clash
  if(($character == "DTD002" || $character == "") && $set == "DTD" && $number >= 5 && $number <= 12) return true; //Figments are legal for Prism in Clash
  return false;
}

function IsCardBanned($cardID, $format, $character)
{
  $set = substr($cardID, 0, 3);
  if ($format == "commoner" && (Rarity($cardID) != "C" && Rarity($cardID) != "T" && Rarity($cardID) != "R") && CardType($cardID) != "C" && $cardID != "CRU187") return true;
  if ($format == "clash") return !isClashLegal($cardID, $character);

  //Ban spoiler cards in non-open-format
  if($format != "openformatcc" && $format != "openformatblitz" && $format != "openformatllcc" && isSpecialUsePromo($cardID)) return true;
  if(isBannedInFormat($cardID, $format)) return true;
  return false;
}

function isCardRestricted($cardID, $format, $count) {

  $restrictedCards = [
    "llcc" => [
      "WTR043", "ELE005", "ELE006", "UPR139", "DTD230", "OUT056", "OUT057", "OUT058", 
      "ROS195", "ROS196", "ROS197", 
    ]
  ];

  return isset($restrictedCards[$format]) && in_array($cardID, $restrictedCards[$format]) && $count > 1;
}

function isSpecialUsePromo($cardID) {
  $specialUsePromos = [
      "JDG001", "JDG002", "JDG003", "JDG004", "JDG005", "JDG006", "JDG008", "JDG010",
      "JDG019", "JDG024", "JDG025", "JDG038", "LSS001", "LSS002", "LSS003", "LSS004", "LSS005",
      "LSS006", "LSS007", "LSS008", "FAB094", "LGS099", "HER101"
  ];
  return in_array($cardID, $specialUsePromos);
}

function isBannedInFormat($cardID, $format) {
  if ($format == "compblitz") $format = "blitz";
  if ($format == "compcc") $format = "cc";

  $bannedCards = [
      "blitz" => [
          "WTR002", "WTR160", "WTR164", "WTR165", "WTR166", "ARC002", "ARC003", "ARC076", "ARC077",
          "ARC122", "ARC160", "ELE006", "ELE063", "ELE186", "ELE187", "ELE188", "ELE222", "ELE223",
          "WTR152", "CRU141", "CRU174", "CRU175", "CRU176", "CRU188", "MON065", "MON239", "EVR037",
          "UPR089", "UPR103", "EVR120", "EVR121", "ELE002", "ELE003", "MON154", "MON155", "ARC114",
          "ARC115", "CRU159", "CRU077", "CRU046", "CRU050", "DYN009", "OUT056", "OUT057", "OUT058",
          "MST080", "MON266", "MON267", "MON268"
      ],
      "cc" => [
          "WTR160", "WTR164", "WTR165", "WTR166", "ARC122", "ARC160", "ARC170", "ARC171", "ARC172",
          "CRU141", "CRU188", "MON001", "MON003", "MON065", "MON153", "MON155", "MON239", "MON266",
          "MON267", "MON268", "ELE006", "ELE186", "ELE187", "ELE188", "ELE223", "ELE115", "ELE031",
          "ELE034", "ELE062", "ELE222", "ELE001", "ELE003", "EVR017", "UPR001", "UPR003", "UPR089",
          "UPR102", "EVR121", "DYN009", "OUT056", "OUT057", "OUT058", "MST080", "ARC006", "ROS225"
      ],
      "commoner" => [
          "ELE172", "ELE186", "ELE187", "ELE188", "MON266", "MON267", "MON268", "MON230"
      ],
      "llcc" => [
          "EVR121"
      ]
  ];

  return isset($bannedCards[$format]) && in_array($cardID, $bannedCards[$format]);
}

function ReverseArt($cardID)
{
  switch ($cardID) {
    case "WTR078": return "CRU049";
    case "CRU004": return "CRU005";
    case "CRU051": return "CRU052";
    case "CRU079": return "CRU080";
    case "DYN069": return "DYN070";
    case "DYN115": return "DYN116";
    case "OUT005": return "OUT006";
    case "OUT007": return "OUT008";
    case "OUT009": return "OUT010";
    default:
      return $cardID;
  }
}

function GetCardId($card, $isFaBDB, $isFaBMeta, $orderedSets) {
  if ($isFaBDB) {
      $printings = $card->{'printings'};
      $printing = $printings[0];
      $sku = $printing->{'sku'};
      return explode("-", $sku->{'sku'})[0];
  } elseif ($isFaBMeta) {
      return $card->{'identifier'};
  } elseif (isset($card->{'setIdentifiers'})) {
      $earliest = -1;
      $id = "";
      foreach ($card->{'setIdentifiers'} as $setCard) {
          $set = substr($setCard, 0, 3);
          for ($j = 0; $j < count($orderedSets); ++$j) {
              if ($orderedSets[$j] == $set && ($earliest == -1 || $j < $earliest)) {
                  $earliest = $j;
                  $id = $setCard;
                  break;
              }
          }
      }
      return $id;
  } elseif (isset($card->{'cardIdentifier'})) {
      return $card->{'cardIdentifier'};
  }
  return "";
}

function ProcessCard($id, $count, $numSideboard, $isFaBDB, &$totalCards, &$modularSideboard, &$unsupportedCards, &$character, &$weapon1, &$weapon2, &$weaponSideboard, &$head, &$headSideboard, &$chest, &$chestSideboard, &$arms, &$armsSideboard, &$legs, &$legsSideboard, &$offhand, &$offhandSideboard, &$quiver, &$quiverSideboard, &$deckCards, &$sideboardCards) {
  $id = GetAltCardID($id);
  $cardName = CardName($id); 
  if ($cardName == "") {
      if ($unsupportedCards != "") $unsupportedCards .= " ";
      $unsupportedCards .= $id;
      return;
  }

  $numMainBoard = ($isFaBDB ? $count - $numSideboard : $count);

  if (IsModular($id)) {
      for ($j = 0; $j < $numMainBoard + $numSideboard; ++$j) {
          if ($modularSideboard != "") $modularSideboard .= " ";
          $modularSideboard .= $id;
      }
      $totalCards += $numMainBoard + $numSideboard;
  } elseif (TypeContains($id, "C")) {
      $character = $id;
  } elseif (TypeContains($id, "W")) {
      ++$totalCards;
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($j > 0) $id = ReverseArt($id);
          if ($weapon1 == "") $weapon1 = $id;
          elseif ($weapon2 == "") $weapon2 = $id;
          else {
              if ($weaponSideboard != "") $weaponSideboard .= " ";
              $weaponSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($numMainBoard > 0 || $j > 0) $id = ReverseArt($id);
          if ($weaponSideboard != "") $weaponSideboard .= " ";
          $weaponSideboard .= $id;
      }
  } elseif (TypeContains($id, "E")) {
      ++$totalCards;
      ProcessEquipment($id, $numMainBoard, $numSideboard, $head, $headSideboard, $chest, $chestSideboard, $arms, $armsSideboard, $legs, $legsSideboard, $offhand, $offhandSideboard, $quiver, $quiverSideboard);
  } else {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($deckCards != "") $deckCards .= " ";
          $deckCards .= $id;
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($sideboardCards != "") $sideboardCards .= " ";
          $sideboardCards .= $id;
      }
      $totalCards += $numMainBoard + $numSideboard;
  }
}

function ProcessEquipment($id, $numMainBoard, $numSideboard, &$head, &$headSideboard, &$chest, &$chestSideboard, &$arms, &$armsSideboard, &$legs, &$legsSideboard, &$offhand, &$offhandSideboard, &$quiver, &$quiverSideboard) {
  if (SubtypeContains($id, "Head")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($head == "") $head = $id;
          else {
              if ($headSideboard != "") $headSideboard .= " ";
              $headSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($headSideboard != "") $headSideboard .= " ";
          $headSideboard .= $id;
      }
  } elseif (SubtypeContains($id, "Chest")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($chest == "") $chest = $id;
          else {
              if ($chestSideboard != "") $chestSideboard .= " ";
              $chestSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($chestSideboard != "") $chestSideboard .= " ";
          $chestSideboard .= $id;
      }
  } elseif (SubtypeContains($id, "Arms")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($arms == "") $arms = $id;
          else {
              if ($armsSideboard != "") $armsSideboard .= " ";
              $armsSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($armsSideboard != "") $armsSideboard .= " ";
          $armsSideboard .= $id;
      }
  } elseif (SubtypeContains($id, "Legs")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($legs == "") $legs = $id;
          else {
              if ($legsSideboard != "") $legsSideboard .= " ";
              $legsSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($legsSideboard != "") $legsSideboard .= " ";
          $legsSideboard .= $id;
      }
  } elseif (SubtypeContains($id, "Off-Hand")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($offhand == "") $offhand = $id;
          else {
              if ($offhandSideboard != "") $offhandSideboard .= " ";
              $offhandSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($offhandSideboard != "") $offhandSideboard .= " ";
          $offhandSideboard .= $id;
      }
  } elseif (SubtypeContains($id, "Quiver")) {
      for ($j = 0; $j < $numMainBoard; ++$j) {
          if ($quiver == "") $quiver = $id;
          else {
              if ($quiverSideboard != "") $quiverSideboard .= " ";
              $quiverSideboard .= $id;
          }
      }
      for ($j = 0; $j < $numSideboard; ++$j) {
          if ($quiverSideboard != "") $quiverSideboard .= " ";
          $quiverSideboard .= $id;
      }
  }
}