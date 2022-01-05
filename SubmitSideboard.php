<?php

  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $playerCharacter =$_GET["playerCharacter"];
  $playerDeck=$_GET["playerDeck"];

  include "HostFiles/Redirector.php";
  include "MenuFiles/ParseGamefile.php";
  include "CardDictionary.php";

  if($playerID == 2)
  {
    $gameStatus = $MGS_ReadyToStart;
  }

  include "MenuFiles/WriteGamefile.php";

  if($playerCharacter != "" && $playerDeck != "")//If they submitted before loading even finished, use the deck as it existed before
  {
    $filename = "./Games/" . $gameName . "/p" . $playerID . "Deck.txt";
    $deckFile = fopen($filename, "w");
    fwrite($deckFile, implode(" ", explode(",",$playerCharacter)) . "\r\n");
    $playerDeck = explode(",",$playerDeck);
    for($i=count($playerDeck)-1; $i>=0; --$i)
    {
      $cardType = CardType($playerDeck[$i]);
      if($cardType == "" || $cardType == "C" || $cardType == "E" || $cardType == "W") unset($playerDeck[$i]);
    }
    $playerDeck = array_values($playerDeck);
    fwrite($deckFile, implode(" ", $playerDeck));
    fclose($deckFile);
  }

  if($playerID == 1)
  {
    header("Location: " . $redirectPath . "/Start.php?gameName=$gameName&playerID=$playerID");
  }
  else
  {
    header("Location: " . $redirectPath . "/GameLobby.php?gameName=$gameName&playerID=$playerID");
  }

?>