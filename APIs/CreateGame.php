<?php

ob_start();
include "../HostFiles/Redirector.php";
include "../Libraries/HTTPLibraries.php";
include "../Libraries/SHMOPLibraries.php";
include_once "../Libraries/PlayerSettings.php";
include_once '../Assets/patreon-php-master/src/PatreonDictionary.php';
require_once '../Assets/patreon-php-master/src/API.php';
include_once '../Assets/patreon-php-master/src/PatreonLibraries.php';
include_once "../AccountFiles/AccountDatabaseAPI.php";
include_once '../includes/functions.inc.php';
include_once '../includes/dbh.inc.php';
include_once '../Database/ConnectionManager.php';
ob_end_clean();
SetHeaders();

$response = new stdClass();

$_POST = json_decode(file_get_contents('php://input'), true);
$deck = TryPOST("deck"); //This is for limited game modes (see JoinGameInput.php)
$decklink = TryPOST("fabdb"); //Deck builder decklink (any deckbuilder, name comes from when fabdb was the only one)
$deckTestMode = TryPOST("deckTestMode", ""); //If this is populated with ANYTHING, will start a game against the combat dummy
$format = TryPOST("format"); //Format of the game -- see function FormatCode for enum of formats
$visibility = TryPOST("visibility"); //"public" = public game, "private" = private game
$decksToTry = TryPOST("decksToTry"); //This is only used if there's no favorite deck or decklink. 1 = ira
$favoriteDeck = TryPOST("favoriteDeck", false); //Set this to true to save the provided deck link to your favorites
$favoriteDeckLink = TryPOST("favoriteDecks", "0"); //This one is kind of weird. It's the favorite deck index, then the string "<fav>" then the favorite deck link
$gameDescription = htmlentities(TryPOST("gameDescription", "Game #"), ENT_QUOTES); //Just a string with the game name
$deckbuilderID = TryPOST("user", "");
$deckTestDeck = TryPOST("deckTestDeck", "");

if ($favoriteDeckLink != 0) {
  $favDeckArr = explode("<fav>", $favoriteDeckLink);
  if (count($favDeckArr) == 1) $favoriteDeckLink = $favDeckArr[0];
  else {
    $favoriteDeckIndex = $favDeckArr[0];
    $favoriteDeckLink = $favDeckArr[1];
  }
}

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    loginFromCookie();
  }
}

$isShadowBanned = false;
if(isset($_SESSION["isBanned"])) $isShadowBanned = (intval($_SESSION["isBanned"]) == 1 ? true : false);
else if(isset($_SESSION["userid"])) $isShadowBanned = IsBanned($_SESSION["userid"]);

if ($visibility == "public" && $deckTestMode != "" && !isset($_SESSION["userid"])) {
  //Must be logged in to use matchmaking
  $response->error = "You must be logged in to create a public multiplayer game.";
  echo json_encode($response);
  exit;
}

if (isset($_SESSION["userid"])) {
  //Save game creation settings
  include_once '../includes/functions.inc.php';
  include_once '../includes/dbh.inc.php';
  if (isset($favoriteDeckIndex)) {
    ChangeSetting("", $SET_FavoriteDeckIndex, $favoriteDeckIndex, $_SESSION["userid"]);
  }
  ChangeSetting("", $SET_Format, FormatCode($format), $_SESSION["userid"]);
  $visibilitySetting = ($visibility == "public" ? 1 : ($visibility == "friends-only" ? 2 : 0));
  ChangeSetting("", $SET_GameVisibility, $visibilitySetting, $_SESSION["userid"]);
  if($deckbuilderID != "")
  {
    if(str_contains($decklink, "fabrary")) storeFabraryId($_SESSION["userid"], $deckbuilderID);
    else if(str_contains($decklink, "fabdb")) storeFabDBId($_SESSION["userid"], $deckbuilderID);
  }
}

session_write_close();

$gameName = GetGameCounter("../");


if ((!file_exists("../Games/$gameName")) && (mkdir("../Games/$gameName", 0700, true))) {
} else {
  $response->error = "Game file could not be created.";
  echo (json_encode($response));
  exit;
}

if($isShadowBanned) {
  if($format == "cc" || $format == "openformatcc" || $format == "llcc" || $format == "openformatllcc") $format = "shadowcc";
  else if($format == "compcc") $format = "shadowcompcc";
  else if($format == "compllcc") $format = "shadowcompllcc";
  else if($format == "blitz" || $format == "compblitz" || $format == "commoner" || $format == "llblitz" || $format == "openformatblitz" || $format == "openformatllblitz" || $format == "sage" || $format == "compsage") $format = "shadowblitz";
}

$p1Data = [1];
$p2Data = [2];
$p1SideboardSubmitted = "0";
$p1IsAI = "0";
if ($deckTestMode != "") {
  $gameStatus = 4; //Ready to start
  if($deckTestDeck != "") $opponentDeck = "../Assets/" . $deckTestDeck . ".txt";
  else $opponentDeck = "../Assets/Dummy.txt";
  copy($opponentDeck, "../Games/" . $gameName . "/p2Deck.txt");
  $p2SideboardSubmitted = "1";
  $p2IsAI = "1";
} else {
  $gameStatus = 0; //Initial
  $p2SideboardSubmitted = "0";
  $p2IsAI = "0";
}
$firstPlayerChooser = "";
$firstPlayer = 1;
$p1Key = hash("sha256", rand() . rand());
$p2Key = hash("sha256", rand() . rand() . rand());
$p1uid = "-";
if($deckTestMode != "") $p2uid = "Practice Dummy";
else $p2uid = "-";
$p1id = "-";
$p2id = "-";
$hostIP = $_SERVER['REMOTE_ADDR'];
$gameGUID = GenerateGameGUID();

$filename = "../Games/" . $gameName . "/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
include "../MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "../Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = round(microtime(true) * 1000);
$cacheVisibility = ($visibility == "public" ? "1" : ($visibility == "friends-only" ? "2" : "0"));
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!" . $cacheVisibility . "!0!0!0!" . FormatCode($format) . "!" . $gameStatus . "!0!0"); //Initialize SHMOP cache for this game

$playerID = 1;

include './JoinGame.php';
