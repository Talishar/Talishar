<?php

  include "WriteLog.php";
  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $deck=TryGet("deck");
  $decklink=$_GET["fabdb"];
  $decksToTry = TryGet("decksToTry");
  $set=TryGet("set");

  if($decklink == "" && $deck == "")
  {
    switch ($decksToTry) {
      case '1': $decklink = "https://fabdb.net/decks/PydXAQMY"; break;
      case '2': $decklink = "https://fabdb.net/decks/xPWERXWZ"; break;
      case '3': $decklink = "https://fabdb.net/decks/DxzAekMk"; break;
      case '4': $decklink = "https://fabdb.net/decks/zkVmEYOb"; break;
      default: $decklink = "https://fabdb.net/decks/pExqQzqV"; break;
    }
  }

  if($deck == "" && !IsDeckLinkValid($decklink)) {
      echo '<b>' . "Deck URL is not valid: " . $decklink . '</b>';
      exit;
  }
  //TODO: Validate $deck

  include "HostFiles/Redirector.php";
  include "CardDictionary.php";
  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";

  if($playerID == 2 && $gameStatus >= $MGS_Player2Joined)
  {
      if($gameStatus >= $MGS_GameStarted)
      {
        header("Location: " . $redirectPath . "/NextTurn3.php?gameName=$gameName&playerID=3");
      }
      else
      {
        header("Location: " . $redirectPath . "/MainMenu.php");
      }
      WriteGameFile();
      exit;
  }

  if($decklink != "")
  {
    $decklink = explode("/", $decklink);
    $slug = $decklink[count($decklink)-1];
    $apiLink = "https://api.fabdb.net/decks/" . $slug;

    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $apiLink);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
    $apiDeck = curl_exec($curl);
    curl_close($curl);

    if($apiDeck === FALSE) { echo  '<b>' . "FabDB API for this deck returns no data: " . implode("/", $decklink) . '</b>'; WriteGameFile(); exit; }
    $deckObj = json_decode($apiDeck);
    $cards = $deckObj->{'cards'};
    $deckCards = "";
    $sideboardCards = "";
    $headSideboard = ""; $chestSideboard = ""; $armsSideboard = ""; $legsSideboard = ""; $offhandSideboard = "";
    $unsupportedCards = "";
    $character = ""; $head = ""; $chest = ""; $arms = ""; $legs = ""; $offhand = "";
    $weapon1 = "";
    $weapon2 = "";
    $weaponSideboard = "";
    for($i=0; $i<count($cards); ++$i)
    {
      $count = $cards[$i]->{'total'};
      $numSideboard = $cards[$i]->{'sideboardTotal'};
      $printings = $cards[$i]->{'printings'};
      $printing = $printings[0];
      $sku = $printing->{'sku'};
      $id = $sku->{'sku'};
      $id = explode("-", $id)[0];
      $id = GetAltCardID($id);
      $cardType = CardType($id);
      if($cardType == "") //Card not supported, error
      {
          if($unsupportedCards != "") $unsupportedCards .= " ";
          $unsupportedCards .= $id;
      }
      else if($cardType == "C")
      {
        $character = $id;
      }
      else if($cardType == "W")
      {
        for($j=0; $j<($count-$numSideboard); ++$j)
        {
          if($weapon1 == "") $weapon1 = $id;
          else if($weapon2 == "") $weapon2 = $id;
          else
          {
            if($weaponSideboard != "") $weaponSideboard .= " ";
            $weaponSideboard .= $id;
          }
        }
        for($j=0; $j<$numSideboard; ++$j)
        {
            if($weaponSideboard != "") $weaponSideboard .= " ";
            $weaponSideboard .= $id;
        }
      }
      else if($cardType == "E")
      {
        $subtype = CardSubType($id);
        if($numSideboard == 0)
        {
          switch($subtype)
          {
            case "Head": if($head == "") $head = $id; else { if($headSideboard != "") $headSideboard .= " "; $headSideboard .= $id; } break;
            case "Chest": if($chest == "") $chest = $id; else { if($chestSideboard != "") $chestSideboard .= " "; $chestSideboard .= $id; } break;
            case "Arms": if($arms == "") $arms = $id; else { $armsSideboard .= " "; $armsSideboard .= $id; } break;
            case "Legs": if($legs == "") $legs = $id; else { if($legsSideboard != "") $legsSideboard .= " "; $legsSideboard .= $id; }break;
            case "Off-Hand": if($offhand == "") $offhand = $id; else { if($offhandSideboard != "") $offhandSideboard .= " "; $offhandSideboard .= $id; } break;
            default: break;
          }
        }
        else {
          switch($subtype)
          {
            case "Head": if($headSideboard != "") $headSideboard .= " "; $headSideboard .= $id; break;
            case "Chest": if($chestSideboard != "") $chestSideboard .= " "; $chestSideboard .= $id; break;
            case "Arms": if($armsSideboard != "") $armsSideboard .= " "; $armsSideboard .= $id; break;
            case "Legs": if($legsSideboard != "") $legsSideboard .= " "; $legsSideboard .= $id; break;
            case "Off-Hand": if($offhandSideboard != "") $offhandSideboard .= " "; $offhandSideboard .= $id; break;
            default: break;
          }
        }
      }
      else
      {
        for($j=0; $j<($count-$numSideboard); ++$j)
        {
          if($deckCards != "") $deckCards .= " ";
          $deckCards .= $id;
        }
        for($j=0; $j<$numSideboard; ++$j)
        {
          if($sideboardCards != "") $sideboardCards .= " ";
          $sideboardCards .= $id;
        }
      }
    }

    if($unsupportedCards != "")
    {
      echo("The following cards are not yet supported: " . $unsupportedCards);
    }

    //We have the decklist, now write to file
    $filename = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
    $deckFile = fopen($filename, "w");
    $charString = $character;
    if($weapon1 != "") $charString .= " " . $weapon1;
    if($weapon2 != "") $charString .= " " . $weapon2;
    if($offhand != "") $charString .= " " . $offhand;
    if($head != "") $charString .= " " . $head;
    if($chest != "") $charString .= " " . $chest;
    if($arms != "") $charString .= " " . $arms;
    if($legs != "") $charString .= " " . $legs;
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
    copy($filename, "./Games/" . $gameName . "/p" . $playerID ."DeckOrig.txt");
  }
  else
  {
    $deckOptions = explode("-", $deck);
    if($deckOptions[0] == "DRAFT")
    {
      if($set == "WTR") $deckFile = "./WTRDraftFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
      else $deckFile = "./DraftFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
    }
    if($deckOptions[0] == "SEALED")
    {
      $deckFile = "./SealedFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
    }
    else
    {
      switch($deck)
      {
        case "oot": $deckFile = "p1Deck.txt"; break;
        case "shawn": $deckFile = "shawnTAD.txt"; break;
        case "dori": $deckFile = "Dori.txt"; break;
        case "katsu": $deckFile = "Katsu.txt"; break;
        default: $deckFile = "p1Deck.txt"; break;
      }
    }
    copy($deckFile,"./Games/" . $gameName . "/p" . $playerID ."Deck.txt");
    copy($deckFile,"./Games/" . $gameName . "/p" . $playerID ."DeckOrig.txt");
  }

  if($playerID == 2)
  {

    $gameStatus = $MGS_Player2Joined;
    if(file_exists("./Games/" . $gameName . "/gamestate.txt")) unlink("./Games/" . $gameName . "/gamestate.txt");

    $firstPlayerChooser = 1;
    $p1roll = 0; $p2roll = 0;
    $tries = 10;
    while($p1roll == $p2roll && $tries > 0)
    {
      $p1roll = rand(1,6) + rand(1, 6);
      $p2roll = rand(1,6) + rand(1, 6);
      WriteLog("Player 1 rolled $p1roll and Player 2 rolled $p2roll.");
      --$tries;
    }
    $firstPlayerChooser = ($p1roll > $p2roll ? 1 : 2);
    WriteLog("Player $firstPlayerChooser chooses who goes first.");
    $gameStatus = $MGS_ChooseFirstPlayer;
  }

  session_start();
  if($playerID == 1 && isset($_SESSION["useruid"])) $p1uid = $_SESSION["useruid"];
  if($playerID == 2 && isset($_SESSION["useruid"])) $p2uid = $_SESSION["useruid"];
  if($playerID == 1 && isset($_SESSION["userid"])) $p1id = $_SESSION["userid"];
  if($playerID == 2 && isset($_SESSION["userid"])) $p2id = $_SESSION["userid"];

  WriteGameFile();
  SetCachePiece($gameName, $playerID+1, strval(round(microtime(true) * 1000)));
  SetCachePiece($gameName, $playerID+3, "0");
  SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));

  header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");

function GetAltCardID($cardID)
{
  switch($cardID)
  {
    case "BOL002": return "MON405";
    case "BOL006": return "MON400";
    case "CHN002": return "MON407";
    case "CHN006": return "MON401";
    case "LEV002": return "MON406";
    case "PSM002": return "MON404";
    case "PSM007": return "MON402";
    case "FAB015": return "WTR191";
    case "FAB023": return "MON135";
    case "UPR209": return "WTR191";
    case "UPR210": return "WTR192";
    case "UPR211": return "WTR193";
  }
  return $cardID;
}

?>
