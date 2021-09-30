<?php

  $gameName=$_GET["gameName"];
  $playerID=$_GET["playerID"];
  $playerCharacter =$_GET["playerCharacter"];
  $playerDeck=$_GET["playerDeck"];

  include "HostFiles/Redirector.php";
  include "MenuFiles/ParseGamefile.php";

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
    fwrite($deckFile, implode(" ", explode(",",$playerDeck)));
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