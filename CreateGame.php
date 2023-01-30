<?php

ob_start();
include "HostFiles/Redirector.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include_once "Libraries/PlayerSettings.php";
include_once 'Assets/patreon-php-master/src/PatreonDictionary.php';
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

if($favoriteDeckLink != 0)
{
  $favDeckArr = explode("<fav>", $favoriteDeckLink);
  if(count($favDeckArr) == 1) $favoriteDeckLink = $favDeckArr[0];
  else {
    $favoriteDeckIndex = $favDeckArr[0];
    $favoriteDeckLink = $favDeckArr[1];
  }
}

$isisShadowBanned = false;

session_start();

if (!isset($_SESSION["userid"])) {
  if (isset($_COOKIE["rememberMeToken"])) {
    include_once './includes/functions.inc.php';
    include_once './includes/dbh.inc.php';
    include_once './Assets/patreon-php-master/src/PatreonLibraries.php';
    include_once './Assets/patreon-php-master/src/API.php';
    include_once './Assets/patreon-php-master/src/PatreonDictionary.php';
    loginFromCookie();
  }
}

if(isset($_SESSION["userid"]))
{
  //Save game creation settings
  include_once 'includes/functions.inc.php';
  include_once 'includes/dbh.inc.php';
  if(isset($favoriteDeckIndex))
  {
    ChangeSetting("", $SET_FavoriteDeckIndex, $favoriteDeckIndex, $_SESSION["userid"]);
  }
  ChangeSetting("", $SET_Format, FormatCode($format), $_SESSION["userid"]);
  ChangeSetting("", $SET_GameVisibility, ($visibility == "public" ? 1 : 0), $_SESSION["userid"]);
  if($deckbuilderID != "")
  {
    if(str_contains($decklink, "fabrary")) storeFabraryId($_SESSION["userid"], $deckbuilderID);
    else if(str_contains($decklink, "fabdb")) storeFabDBId($_SESSION["userid"], $deckbuilderID);
  }
}

session_write_close();

$bannedIPHandler = fopen("./HostFiles/bannedIPs.txt", "r");
while (!feof($bannedIPHandler)) {
  $bannedIP = trim(fgets($bannedIPHandler), "\r\n");
  // echo ($_SERVER['REMOTE_ADDR'] . " " . $bannedIP . "<BR>");
  if ($_SERVER['REMOTE_ADDR'] == $bannedIP) {
    $isisShadowBanned = true;
  }
}
fclose($bannedIPHandler);

if ($isisShadowBanned) {
  if ($format == "cc" || $format == "livinglegendscc") $format = "shadowcc";
  else if ($format == "compcc") $format = "shadowcompcc";
  else if ($format == "blitz" || $format == "compblitz") $format = "shadowblitz";
  else if ($format == "commoner") $format = "shadowcommoner";
}

$gameName = GetGameCounter();

if ( (!file_exists("Games/$gameName")) && (mkdir("Games/$gameName", 0700, true)) ){
} else {
  print_r("Encountered a problem creating a game. Please return to the main menu and try again");
}

$p1Data = [1];
$p2Data = [2];
if ($deckTestMode != "") {
  $gameStatus = 4; //ReadyToStart
  $opponentDeck = "./Assets/Dummy.txt";
  switch($deckTestMode)
  {
    case "Woottonhog": $opponentDeck = "./Roguelike/Encounters/Woottonhog.txt"; break;
    case "RavenousRabble": $opponentDeck = "./Roguelike/Encounters/RavenousRabble.txt"; break;
    case "BarragingBrawnhide": $opponentDeck = "./Roguelike/Encounters/BarragingBrawnhide.txt"; break;
    case "ShockStriker": $opponentDeck = "./Roguelike/Encounters/ShockStriker.txt"; break;
    case "QuickshotNovice": $opponentDeck = "./Roguelike/Encounters/QuickshotNovice.txt"; break;
    case "RuneScholar": $opponentDeck = "./Roguelike/Encounters/RuneScholar.txt"; break;
    case "Ira": $opponentDeck = "./Roguelike/Encounters/Ira.txt"; break;
    default: break;
  }
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

$filename = "./Games/" . $gameName . "/GameFile.txt";
$gameFileHandler = fopen($filename, "w");
include "MenuFiles/WriteGamefile.php";
WriteGameFile();

$filename = "./Games/" . $gameName . "/gamelog.txt";
$handler = fopen($filename, "w");
fclose($handler);

$currentTime = round(microtime(true) * 1000);
WriteCache($gameName, 1 . "!" . $currentTime . "!" . $currentTime . "!0!-1!" . $currentTime . "!!!0!0!"); //Initialize SHMOP cache for this game
header("Location: JoinGameInput.php?gameName=$gameName&playerID=1&deck=$deck&fabdb=$decklink&format=$format&set=$set&decksToTry=$decksToTry&favoriteDeck=$favoriteDeck&favoriteDecks=$favoriteDeckLink");
