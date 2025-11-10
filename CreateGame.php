<?php

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include_once "Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
include_once "./AccountFiles/AccountDatabaseAPI.php";
include_once './includes/functions.inc.php';
include_once './includes/dbh.inc.php';
include_once './Database/ConnectionManager.php';
ob_end_clean();

$deck = TryGET("deck");
$decklink = TryGET("fabdb");
$deckTestMode = TryGET("deckTestMode", "");
$format = TryGET("format");
$visibility = TryGET("visibility");
$set = TryGET("set");
$decksToTry = TryGet("decksToTry");
$favoriteDeck = TryGet("favoriteDeck", "0");
$favoriteDeckLink = TryGet("favoriteDecks", "0");
$gameDescription = htmlentities(TryGet("gameDescription", "Game #"), ENT_QUOTES);
$deckbuilderID = TryGet("user", "");
$roguelikeGameID = TryGet("roguelikeGameID", "");
$startingHealth = TryGet("startingHealth", "");

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
    include_once './Assets/patreon-php-master/src/PatreonLibraries.php';
    include_once './Assets/patreon-php-master/src/API.php';
    include_once './Assets/patreon-php-master/src/PatreonDictionary.php';
    loginFromCookie();
  }
}

$isShadowBanned = false;
if (isset($_SESSION["isBanned"])) $isShadowBanned = (intval($_SESSION["isBanned"]) == 1 ? true : false);
else if (isset($_SESSION["userid"])) $isShadowBanned = IsBanned($_SESSION["userid"]);

if ($visibility == "public" && $deckTestMode != "" && !isset($_SESSION["userid"])) {
  //Must be logged in to use matchmaking
  header("Location: MainMenu.php");
  exit;
}

if (isset($_SESSION["userid"])) {
  //Save game creation settings
  if (isset($favoriteDeckIndex)) {
    ChangeSetting("", $SET_FavoriteDeckIndex, $favoriteDeckIndex, $_SESSION["userid"]);
  }
  ChangeSetting("", $SET_Format, FormatCode($format), $_SESSION["userid"]);
  $visibilitySetting = ($visibility == "public" ? 1 : ($visibility == "friends-only" ? 2 : 0));
  ChangeSetting("", $SET_GameVisibility, $visibilitySetting, $_SESSION["userid"]);
  if ($deckbuilderID != "") {
    if (str_contains($decklink, "fabrary")) storeFabraryId($_SESSION["userid"], $deckbuilderID);
    else if (str_contains($decklink, "fabdb")) storeFabDBId($_SESSION["userid"], $deckbuilderID);
  }
}

session_write_close();
if ($isShadowBanned) {
  if ($format == "cc" || $format == "openformatcc" || $format == "llcc" || $format == "openformatcc") $format = "shadowcc";
  else if ($format == "compcc") $format = "shadowcompcc";
  else if ($format == "blitz" || $format == "compblitz" || $format == "commoner" || $format == "llblitz" || $format == "openformatblitz"  || $format == "openformatllblitz") $format = "shadowblitz";
}

$gameName = GetGameCounter();

if ((!file_exists("Games/$gameName")) && (mkdir("Games/$gameName", 0700, true))) {
} else {
  print_r("Encountered a problem creating a game. Please return to the main menu and try again");
}

$p1Data = [1];
$p2Data = [2];
$p1SideboardSubmitted = "0";
$p2SideboardSubmitted = "0";
if ($deckTestMode != "") {
  $gameStatus = 4; //ReadyToStart
  $p2SideboardSubmitted = "1";
  $opponentDeck = "./Assets/Dummy.txt";
  $fileName = "./Roguelike/Encounters/" . $deckTestMode . ".txt";
  if (file_exists($fileName)) $opponentDeck = $fileName;
  copy($opponentDeck, "./Games/" . $gameName . "/p2Deck.txt");
} else {
  $gameStatus = 0; //Initial
}
$firstPlayerChooser = "";
$firstPlayer = 1;
$p1Key = hash("sha256", rand() . rand());
$p2Key = hash("sha256", rand() . rand() . rand());
$p1uid = "-";
$p2uid = "-";
$p1id = "-";
$p2id = "-";
$hostIP = $_SERVER['REMOTE_ADDR'];
$p1StartingHealth = $startingHealth;
$gameGUID = GenerateGameGUID();

$filename = "./Games/" . $gameName . "/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
include "MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = round(microtime(true) * 1000);
$cacheVisibility = ($visibility == "public" ? "1" : ($visibility == "friends-only" ? "2" : "0"));
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!" . $cacheVisibility . "!0!0!0!" . $format . "!" . $gameStatus . "!0!0!0!0"); //Initialize SHMOP cache for this game (piece 17 & 18 = undo decline counts for p1 & p2)
header("Location: JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink&format=$format&set=$set&decksToTry=$decksToTry&favoriteDeck=$favoriteDeck&favoriteDecks=$favoriteDeckLink");
