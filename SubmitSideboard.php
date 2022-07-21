<?php

  include "Libraries/HTTPLibraries.php";
  include "Libraries/SHMOPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $playerCharacter =$_GET["playerCharacter"];
  $playerDeck=$_GET["playerDeck"];
  $authKey=$_GET["authKey"];

  include "WriteLog.php";
  include "HostFiles/Redirector.php";
  include "CardDictionary.php";

  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";

  $targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
  if($authKey != $targetAuth)
  {
    echo("Invalid Auth Key");
    exit;
  }

  if($playerCharacter != "" && $playerDeck != "")//If they submitted before loading even finished, use the deck as it existed before
  {
    $char = explode(",",$playerCharacter);
    $numHands = 0;
    for($i=0; $i<count($char); ++$i)
    {
      if(CardSubType($char[$i]) == "Off-Hand") ++$numHands;
      else if(CardType($char[$i]) == "W")
      {
        if(Is1H($char[$i])) ++$numHands;
        else $numHands += 2;
      }
    }
    if($numHands > 2)
    {
      WriteLog("Unable to submit player " . $playerID . "'s deck. $numHands of weapons currently equipped.");
      header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");
      exit;
    }
    $playerDeck = explode(",",$playerDeck);
    for($i=count($playerDeck)-1; $i>=0; --$i)
    {
      $cardType = CardType($playerDeck[$i]);
      if($cardType == "" || $cardType == "C" || $cardType == "E" || $cardType == "W") unset($playerDeck[$i]);
    }
    $playerDeck = array_values($playerDeck);
    $filename = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
    $deckFile = fopen($filename, "w");
    fwrite($deckFile, implode(" ", $char) . "\r\n");
    fwrite($deckFile, implode(" ", $playerDeck));
    fclose($deckFile);
  }

  if($playerID == 2)
  {
    $gameStatus = $MGS_ReadyToStart;
  }
  else
  {
    $gameStatus = $MGS_GameStarted;
  }
  WriteGameFile();
  GamestateUpdated($gameName);

  if($playerID == 1)
  {
    header("Location: " . $redirectPath . "/Start.php?gameName=$gameName&playerID=$playerID&authKey=$p1Key");
  }
  else
  {
    header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID&authKey=$authKey");
  }

?>
