<?php

  include "HostFiles/Redirector.php";
  include "CardDictionary.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $deck=$_GET["deck"];
  $decklink=$_GET["fabdb"];

  if($decklink != "")
  {
    $decklink = explode("/", $decklink);
    $slug = $decklink[count($decklink)-1];
    $apiLink = "https://api.fabdb.net/decks/" . $slug;
    $apiDeck = file_get_contents($apiLink);
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
    $deckFile = fopen($filename, "a");
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
  }
  else
  {
    $deckOptions = explode("-", $deck);
    if($deckOptions[0] == "DRAFT")
    {
      $deckFile = "./DraftFiles/Games/" . $deckOptions[1] . "/LimitedDeck.txt";
    }
    else
    {
      switch($deck)
      {
        case "oot": $deckFile = "p1Deck.txt"; break;
        case "shane": $deckFile = "shaneDeck.txt"; break;
        case "shawn": $deckFile = "shawnTAD.txt"; break;
        case "dori": $deckFile = "Dori.txt"; break;
        case "katsu": $deckFile = "Katsu.txt"; break;
        default: $deckFile = "p1Deck.txt"; break;
      }
    }
    copy($deckFile,"./Games/" . $gameName . "/p" . $playerID ."Deck.txt");
  }

  if($playerID == 2)
  {
    $filename = "./Games/" . $gameName . "/GameFile.txt";
    $gameFile = fopen($filename, "w");

    $attemptCount = 0;
    while(!flock($gameFile, LOCK_EX) && $attemptCount < 5) {  // acquire an exclusive lock
      sleep(1);
      ++$attemptCount;
    }
    if($attemptCount >= 5)
    {
      header("Location: " . $redirectorPath . "MainMenu.php");//We never actually got the lock
    }

    //fwrite($gameFile, "\r\n");
    fwrite($gameFile, "1\r\n2\r\n4");
    flock($gameFile, LOCK_UN);    // release the lock
    fclose($gameFile);
  }

  header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");

?>

