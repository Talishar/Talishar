<?php

  include "HostFiles/Redirector.php";
  include "CardDictionary.php";

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $deck=$_GET["deck"];
  $decklink=$_GET["fabdb"];
  if($playerID == 1)
  {
    $filename = "./Games/" . $gameName . "/GameFile.txt";
    $gameFile = fopen($filename, "a");
    fwrite($gameFile, $playerID);
    fclose($gameFile);
  }
  else
  {
    $filename = "./Games/" . $gameName . "/GameFile.txt";
    $gameFile = fopen($filename, "a");

    $attemptCount = 0;
    while(!flock($gameFile, LOCK_EX) && $attemptCount < 5) {  // acquire an exclusive lock
      sleep(1);
      ++$attemptCount;
    }
    if($attemptCount >= 5)
    {
      header("Location: " . $redirectorPath . "MainMenu.php");//We never actually got the lock
    }

    fwrite($gameFile, "\r\n");
    fwrite($gameFile, $playerID);
    flock($gameFile, LOCK_UN);    // release the lock
    fclose($gameFile);
  }

  if($decklink != "")
  {
    $decklink = explode("/", $decklink);
    $slug = $decklink[count($decklink)-1];
    $apiLink = "https://api.fabdb.net/decks/" . $slug;
    $apiDeck = file_get_contents($apiLink);
    $deckObj = json_decode($apiDeck);
    $cards = $deckObj->{'cards'};
    $deckCards = "";
    $unsupportedCards = "";
    $weapon1 = "";
    $weapon2 = "";
    for($i=0; $i<count($cards); ++$i)
    {
      $count = $cards[$i]->{'total'};
      $printings = $cards[$i]->{'printings'};
      $printing = $printings[0];
      $sku = $printing->{'sku'};
      $id = $sku->{'number'};
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
        if($weapon1 == "") $weapon1 = $id;
        else $weapon2 = $id;
      }
      else if($cardType == "E")
      {
        $subtype = CardSubType($id);
        switch($subtype)
        {
          case "Head": $head = $id; break;
          case "Chest": $chest = $id; break;
          case "Arms": $arms = $id; break;
          case "Legs": $legs = $id; break;
          case "Off-Hand": $offhand = $id; break;
          default: break;
        }
      }
      else
      {
        for($j=0; $j<$count; ++$j)
        {
          if($deckCards != "") $deckCards .= " ";
          $deckCards .= $id;
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
    fwrite($deckFile, $character . " " . $weapon1 . " " . ($weapon2 != "" ? $weapon2 . " " : "") . ($offhand != "" ? $offhand . " " : "") . $head . " " . $chest . " " . $arms . " " . $legs . "\r\n");
    fwrite($deckFile, $deckCards);
    fclose($deckFile);
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
    copy($deckFile,"./Games/" . $gameName . "/p" . $playerID ."Deck.txt");
  }

  header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");

?>

