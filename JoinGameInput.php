<?php

include "WriteLog.php";
include "Libraries/HTTPLibraries.php";
include "Libraries/SHMOPLibraries.php";
include "APIKeys/APIKeys.php";

session_start();
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = intval($_GET["playerID"]);
$deck = TryGet("deck");
$decklink = $_GET["fabdb"];
$decksToTry = TryGet("decksToTry");
$favoriteDeck = TryGet("favoriteDeck", "0");
$favoriteDeckLink = TryGet("favoriteDecks", "0");
$set = TryGet("set");
$matchup = TryGet("matchup", "");

if($matchup == "" && GetCachePiece($gameName, $playerID + 6) != "")
{
  echo("That player has already joined the game.");
  exit;
}

if ($decklink == "" && $deck == "" && $favoriteDeckLink == "0") {
  switch ($decksToTry) {
    case '1':
      $decklink = "https://fabdb.net/decks/VGkQMojg";
      break;
    case '2':
      $decklink = "https://fabdb.net/decks/eLxddlzb";
      break;
    case '3':
      $decklink = "https://fabdb.net/decks/ydeXXEzW";
      break;
    case '4':
      $decklink = "https://fabdb.net/decks/GnlPKqaO";
      break;
    case '5':
      $decklink = "https://fabdb.net/decks/omKmlPDV";
      break;
    case '6':
      $decklink = "https://fabdb.net/decks/OldYPAwm";
      break;
    case '7':
      $decklink = "https://fabdb.net/decks/WAPZxDEQ";
      break;
    case '8':
      $decklink = "https://fabdb.net/decks/nnlVMAEG";
      break;
    case '9':
      $decklink = "https://fabrary.net/decks/01G7FCP2N7N0MNHWAH6JTP0KFN";
      break;
    case '10':
      $decklink = "https://fabrary.net/decks/01G7B1T1D1M2DAM61K876VJBDK";
      break;
    case '11':
      $decklink = "https://fabrary.net/decks/01G7FD2B3YQAMR8NJ4B3M58H96";
      break;
    case '12':
      $decklink = "https://fabrary.net/decks/01G7FDVRZP35DFWBRK64AG5TKQ";
      break;
    case '13':
      $decklink = "https://fabrary.net/decks/01G7K464J7VS0K7HKW5E395TBK";
      break;
    case '14':
      $decklink = "https://fabrary.net/decks/01G7K4D304QQCZZSBT7ABCX4XC";
      break;
    case '15':
      $decklink = "https://fabrary.net/decks/01G7K3WGPVKVDXG2J013GXSXNP";
      break;
    case '16':
      $decklink = "https://fabrary.net/decks/01G76H7RG7GN5ZA10F3BJBH740";
      break;
    case '17':
      $decklink = "https://fabrary.net/decks/01G76H1R1ERRBRKS7RVCQAB8RX";
      break;
    default:
      $decklink = "https://fabdb.net/decks/VGkQMojg";
      break;
  }
}

if ($favoriteDeckLink != "0" && $decklink == "") $decklink = $favoriteDeckLink;

if ($deck == "" && !IsDeckLinkValid($decklink)) {
  echo '<b>' . "Deck URL is not valid: " . $decklink . '</b>';
  exit;
}
//TODO: Validate $deck

include "HostFiles/Redirector.php";
include "CardDictionary.php";
include "MenuFiles/ParseGamefile.php";
include "MenuFiles/WriteGamefile.php";

if ($matchup == "" && $playerID == 2 && $gameStatus >= $MGS_Player2Joined) {
  if ($gameStatus >= $MGS_GameStarted) {
    header("Location: " . $redirectPath . "/NextTurn4.php?gameName=$gameName&playerID=3");
  } else {
    header("Location: " . $redirectPath . "/MainMenu.php");
  }
  WriteGameFile();
  exit;
}

if ($decklink != "") {
  if($playerID == 1) $p1DeckLink = $decklink;
  else if($playerID == 2) $p2DeckLink = $decklink;
  $curl = curl_init();
  $isFaBDB = str_contains($decklink, "fabdb");
  if ($isFaBDB) {
    $decklinkArr = explode("/", $decklink);
    $slug = $decklinkArr[count($decklinkArr) - 1];
    $apiLink = "https://api.fabdb.net/decks/" . $slug;
  } else if(str_contains($decklink, "fabrary")) {
    $headers = array(
      "x-api-key: " . $FaBraryKey,
      "Content-Type: application/json",
    );
    curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
    $decklinkArr = explode("/", $decklink);
    $slug = $decklinkArr[count($decklinkArr) - 1];
    $apiLink = "https://5zvy977nw7.execute-api.us-east-2.amazonaws.com/prod/decks/" . $slug;
    if($matchup != "") $apiLink .= "?matchupId=" . $matchup;
  }
  else {
    $decklinkArr = explode("/", $decklink);
    $slug = $decklinkArr[count($decklinkArr) - 1];
    $apiLink = "https://api.fabmeta.net/deck/" . $slug;
  }

  curl_setopt($curl, CURLOPT_URL, $apiLink);
  curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
  $apiDeck = curl_exec($curl);
  curl_close($curl);

  if ($apiDeck === FALSE) {
    echo  '<b>' . "FabDB API for this deck returns no data: " . implode("/", $decklink) . '</b>';
    WriteGameFile();
    exit;
  }
  $deckObj = json_decode($apiDeck);
  $deckName = $deckObj->{'name'};
  if(isset($deckObj->{'matchups'}))
  {
    if($playerID == 1) $p1Matchups = $deckObj->{'matchups'};
    else if($playerID == 2) $p2Matchups = $deckObj->{'matchups'};
  }
  $cards = $deckObj->{'cards'};
  $deckCards = "";
  $sideboardCards = "";
  $headSideboard = "";
  $chestSideboard = "";
  $armsSideboard = "";
  $legsSideboard = "";
  $offhandSideboard = "";
  $unsupportedCards = "";
  $bannedCard = "";
  $character = "";
  $head = "";
  $chest = "";
  $arms = "";
  $legs = "";
  $offhand = "";
  $weapon1 = "";
  $weapon2 = "";
  $weaponSideboard = "";
  $totalCards = 0;

  if (is_countable($cards)) {
    for ($i = 0; $i < count($cards); ++$i) {
      $count = $cards[$i]->{'total'};
      $numSideboard = $cards[$i]->{'sideboardTotal'};
      if ($isFaBDB) {
        $printings = $cards[$i]->{'printings'};
        $printing = $printings[0];
        $sku = $printing->{'sku'};
        $id = $sku->{'sku'};
        $id = explode("-", $id)[0];
      } else {
        $id = $cards[$i]->{'cardIdentifier'};
      }
      $id = GetAltCardID($id);
      $cardType = CardType($id);

      if (IsBanned($id, $format)) {
        if ($bannedCard != "") $bannedCard .= ", ";
        $bannedCard .= CardName($id);
      }

      if ($cardType == "") //Card not supported, error
      {
        if ($unsupportedCards != "") $unsupportedCards .= " ";
        $unsupportedCards .= $id;
      } else if ($cardType == "C") {
        $character = $id;
      } else if ($cardType == "W") {
        for ($j = 0; $j < ($count - $numSideboard); ++$j) {
          if ($weapon1 == "") $weapon1 = $id;
          else if ($weapon2 == "") $weapon2 = $id;
          else {
            if ($weaponSideboard != "") $weaponSideboard .= " ";
            $weaponSideboard .= $id;
          }
        }
        for ($j = 0; $j < $numSideboard; ++$j) {
          if ($weaponSideboard != "") $weaponSideboard .= " ";
          $weaponSideboard .= $id;
        }
      } else if ($cardType == "E") {
        $subtype = CardSubType($id);
        if ($numSideboard == 0) {
          switch ($subtype) {
            case "Head":
              if ($head == "") $head = $id;
              else {
                if ($headSideboard != "") $headSideboard .= " ";
                $headSideboard .= $id;
              }
              break;
            case "Chest":
              if ($chest == "") $chest = $id;
              else {
                if ($chestSideboard != "") $chestSideboard .= " ";
                $chestSideboard .= $id;
              }
              break;
            case "Arms":
              if ($arms == "") $arms = $id;
              else {
                $armsSideboard .= " ";
                $armsSideboard .= $id;
              }
              break;
            case "Legs":
              if ($legs == "") $legs = $id;
              else {
                if ($legsSideboard != "") $legsSideboard .= " ";
                $legsSideboard .= $id;
              }
              break;
            case "Off-Hand":
              if ($offhand == "") $offhand = $id;
              else {
                if ($offhandSideboard != "") $offhandSideboard .= " ";
                $offhandSideboard .= $id;
              }
              break;
            default:
              break;
          }
        } else {
          switch ($subtype) {
            case "Head":
              if ($headSideboard != "") $headSideboard .= " ";
              $headSideboard .= $id;
              break;
            case "Chest":
              if ($chestSideboard != "") $chestSideboard .= " ";
              $chestSideboard .= $id;

              break;
            case "Arms":
              if ($armsSideboard != "") $armsSideboard .= " ";
              $armsSideboard .= $id;
              break;
            case "Legs":
              if ($legsSideboard != "") $legsSideboard .= " ";
              $legsSideboard .= $id;
              break;
            case "Off-Hand":
              if ($offhandSideboard != "") $offhandSideboard .= " ";
              $offhandSideboard .= $id;
              break;
            default:
              break;
          }
        }
      } else {
        $numMainBoard = ($isFaBDB ? $count - $numSideboard : $count);
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
  } else {
    $_SESSION['error'] = 'Error: The decklist link you have entered might be invalid or contain invalid cards (e.g Tokens).\n\nPlease double-check your decklist link and try again.';
    header("Location: MainMenu.php");
    die();
  }

  if ($unsupportedCards != "") {
    echo ("The following cards are not yet supported: " . $unsupportedCards);
  }

  if ($bannedCard != "") {
    if ($format == "blitz") {
      $_SESSION['error'] = 'Error: The following cards are not legal in the Blitz format: ' . $bannedCard;
    } elseif ($format == "cc" || $format == "compcc") {
      $_SESSION['error'] = 'Error: The following cards are not legal in the Classic Constructed format: ' . $bannedCard;
    } elseif ($format == "commoner") {
      $_SESSION['error'] = 'Error: The following cards are not legal the Commoner format: ' . $bannedCard;
    }
    header("Location: MainMenu.php");
    die();
  }

  if($totalCards < 60  && ($format == "cc" || $format == "compcc"))
  {
    $_SESSION['error'] = 'Error: The deck link you have entered has too few cards (' . $totalCards . ') and is likely for blitz.\n\nPlease double-check your decklist link and try again.';
    header("Location: MainMenu.php");
    die();
  }

  if(($totalCards < 40 || $totalCards > 52) && ($format == "blitz" || $format == "commoner"))
  {
    $_SESSION['error'] = 'Error: The deck link you have entered does not have 40 cards (' . $totalCards . ') and is likely for CC.\n\nPlease double-check your decklist link and try again.';
    header("Location: MainMenu.php");
    die();
  }


  //We have the decklist, now write to file
  $filename = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
  $deckFile = fopen($filename, "w");
  $charString = $character;
  if ($weapon1 != "") $charString .= " " . $weapon1;
  if ($weapon2 != "") $charString .= " " . $weapon2;
  if ($offhand != "") $charString .= " " . $offhand;
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
  fwrite($deckFile, $sideboardCards);
  fclose($deckFile);
  copy($filename, "./Games/" . $gameName . "/p" . $playerID . "DeckOrig.txt");

  if ($favoriteDeck == "on" && isset($_SESSION["userid"])) {
    //Save deck
    require_once './includes/functions.inc.php';
    include_once "./includes/dbh.inc.php";
    addFavoriteDeck($_SESSION["userid"], $decklink, $deckName, $character);
  }
} else {
  $character = "";
  $deckOptions = explode("-", $deck);
  if ($deckOptions[0] == "DRAFT") {
    if ($set == "WTR") $deckFile = "./WTRDraftFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
    else $deckFile = "./DraftFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
  }
  if ($deckOptions[0] == "SEALED") {
    $deckFile = "./SealedFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
  } else {
    switch ($deck) {
      case "oot":
        $deckFile = "p1Deck.txt";
        break;
      case "shawn":
        $deckFile = "shawnTAD.txt";
        break;
      case "dori":
        $deckFile = "Dori.txt";
        break;
      case "katsu":
        $deckFile = "Katsu.txt";
        break;
      default:
        $deckFile = "p1Deck.txt";
        break;
    }
  }
  copy($deckFile, "./Games/" . $gameName . "/p" . $playerID . "Deck.txt");
  copy($deckFile, "./Games/" . $gameName . "/p" . $playerID . "DeckOrig.txt");
}

if($matchup == "")
{
  if ($playerID == 2) {

    $gameStatus = $MGS_Player2Joined;
    if (file_exists("./Games/" . $gameName . "/gamestate.txt")) unlink("./Games/" . $gameName . "/gamestate.txt");

    $firstPlayerChooser = 1;
    $p1roll = 0;
    $p2roll = 0;
    $tries = 10;
    while ($p1roll == $p2roll && $tries > 0) {
      $p1roll = rand(1, 6) + rand(1, 6);
      $p2roll = rand(1, 6) + rand(1, 6);
      WriteLog("Player 1 rolled $p1roll and Player 2 rolled $p2roll.");
      --$tries;
    }
    $firstPlayerChooser = ($p1roll > $p2roll ? 1 : 2);
    WriteLog("Player $firstPlayerChooser chooses who goes first.");
    $gameStatus = $MGS_ChooseFirstPlayer;
    $joinerIP = $_SERVER['REMOTE_ADDR'];
  }

  if($playerID == 1)
  {
    $p1uid = (isset($_SESSION["useruid"]) ? $_SESSION["useruid"] : "Player 1");
    $p1id = (isset($_SESSION["userid"]) ? $_SESSION["userid"] : "");
    $p1IsPatron = (isset($_SESSION["isPatron"]) ? "1" : "");
  }
  else if($playerID == 2)
  {
    $p2uid = (isset($_SESSION["useruid"]) ? $_SESSION["useruid"] : "Player 2");
    $p2id = (isset($_SESSION["userid"]) ? $_SESSION["userid"] : "");
    $p2IsPatron = (isset($_SESSION["isPatron"]) ? "1" : "");
  }

  if($playerID == 2) $p2Key = hash("sha256", rand() . rand() . rand());

  WriteGameFile();
  SetCachePiece($gameName, $playerID + 1, strval(round(microtime(true) * 1000)));
  SetCachePiece($gameName, $playerID + 3, "0");
  SetCachePiece($gameName, $playerID + 6, $character);
  GamestateUpdated($gameName);

  //$authKey = ($playerID == 1 ? $p1Key : $p2Key);
  //$_SESSION["authKey"] = $authKey;
  if($playerID == 1) $_SESSION["p1AuthKey"] = $p1Key;
  if($playerID == 2) $_SESSION["p2AuthKey"] = $p2Key;
}

session_write_close();
//header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID&authKey=$authKey");
header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");

function GetAltCardID($cardID)
{
  switch ($cardID) {
    case "BOL002":
      return "MON405";
    case "BOL006":
      return "MON400";
    case "CHN002":
      return "MON407";
    case "CHN006":
      return "MON401";
    case "LEV002":
      return "MON406";
    case "LEV005":
      return "MON400";
    case "PSM002":
      return "MON404";
    case "PSM007":
      return "MON402";
    case "FAB015":
      return "WTR191";
    case "FAB016":
      return "WTR162";
    case "FAB023":
      return "MON135";
    case "FAB030":
      return "DYN030";
    case "DVR026":
      return "WTR182";
    case "RVD008":
      return "WTR006";
    case "UPR209":
      return "WTR191";
    case "UPR210":
      return "WTR192";
    case "UPR211":
      return "WTR193";
  }
  return $cardID;
}

function IsBanned($cardID, $format)
{
  switch ($format) {

      //    The following cards are banned in Blitz:
      //    ARC076: Viserai
      //    ARC077: Nebula Blade
      //    ELE186: ELE187: ELE188: Awakening
      //    ELE186: ELE187: ELE188: Ball Lightning
      //    WTR164: WTR165: WTR166: Drone of Brutality
      //    ELE223: Duskblade
      //    WTR152: Heartened Cross Strap
      //    CRU174: CRU175: CRU176: Snapback
      //    ARC129: ARC130: ARC131: Stir the Aetherwind
      //    MON239: Stubby Hammerers
      //    ELE115: Crown of Seeds (Until Oldhim becomes Living Legend)
      //    MON183: MON184: MON185: Seeds of Agony (Until Chane becomes Living Legend)
      //    MON231: Sonata Arcanix (Until the next banned and suspended announcement)
    case "blitz":
      switch ($cardID) {
        case "ARC076":
        case "ARC077":
        case "ELE006":
        case "ELE186":
        case "ELE187":
        case "ELE188":
        case "WTR164":
        case "WTR165":
        case "WTR166":
        case "ELE223":
        case "WTR152":
        case "CRU174":
        case "CRU175":
        case "CRU176":
        case "ARC129":
        case "ARC130":
        case "ARC131":
        case "MON239":
        case "ELE115":
        case "MON183":
        case "MON184":
        case "MON185":
        case "MON231":
          return true;
        default:
          return false;
      }
      break;

      //    MON001: Prism, Sculptor of Arc Light (as of August 30, 2022)
      //    MON003: Luminaris (as of August 30, 2022)
      //    EVR017: Bravo, Star of the Show
      //    MON153: Chane, Bound by Shadow
      //    MON155: Galaxxi Black

      //    The following cards are banned in Classic Constructed:
      //    ELE006: Awakening
      //    ELE186: ELE187: ELE188: Ball Lightning
      //    WTR164: WTR165: WTR166: Drone of Brutality
      //    ELE223: Duskblade
      //    ARC170: ARC171: ARC172: Plunder Run
      //    MON239: Stubby Hammerers
      //    CRU141: Bloodsheath Skeleta (until Viserai, Rune Blood becomes Living Legend)
    case "cc":
    case "compcc":
      switch ($cardID) {
        case "MON001":
        case "MON003":
        case "EVR017":
        case "MON153":
        case "MON155":
        case "ELE006":
        case "ELE186":
        case "ELE187":
        case "ELE188":
        case "WTR164":
        case "WTR165":
        case "WTR166":
        case "ELE223":
        case "ARC170":
        case "ARC171":
        case "ARC172":
        case "MON239":
        case "CRU141":
          return true;
        default:
          return false;
      }
      break;

      //   The following cards are banned in Commoner:
      //   ELE186: ELE187: ELE188: Ball Lightning
      //   MON266: MON267: MON268: Belittle
    case "commoner":
      switch ($cardID) {
        case "ELE186":
        case "ELE187":
        case "ELE188":
        case "MON266":
        case "MON267":
        case "MON268":
          return true;
        default:
          return false;
      }
      break;
    default:
      return false;
  }
}
