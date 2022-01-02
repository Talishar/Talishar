<?php
  
  include "HostFiles/Redirector.php";
  include "CardDictionary.php";
  include "Libraries/HTTPLibraries.php";

  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }

  //Setup the random number generator
  srand(make_seed());

  //First initialize the initial state of the game
  $filename = "./Games/" . $gameName . "/gamestate.txt";
  $handler = fopen($filename, "w");
  fwrite($handler, "20 20\r\n");//Player health totals //TODO: Change based on character

  //Player 1
  $p1DeckHandler = fopen("./Games/" . $gameName . "/p1Deck.txt", "r");
  initializePlayerState($handler, $p1DeckHandler);
  fclose($p1DeckHandler);

  //Player 2
  $p2DeckHandler = fopen("./Games/" . $gameName . "/p2Deck.txt", "r");
  initializePlayerState($handler, $p2DeckHandler);
  fclose($p2DeckHandler);

  fwrite($handler, "\r\n");//Landmarks
  fwrite($handler, "0\r\n");//Game winner (0=none, else player ID)
  fwrite($handler, "1\r\n");//First Player
  fwrite($handler, "1\r\n");//Current Player
  fwrite($handler, "1\r\n");//Current Turn
  fwrite($handler, "M 1\r\n");//What phase/player is active
  fwrite($handler, "1\r\n");//Action points
  fwrite($handler, "\r\n");//Combat Chain
  fwrite($handler, "0 0 NA 0 0 0 0 GY NA 0 0 0 0 0 0 0 NA 0 0 -1 -1 NA 0 0\r\n");//Combat Chain State
  fwrite($handler, "\r\n");//Current Turn Effects
  fwrite($handler, "\r\n");//Current Turn Effects From Combat
  fwrite($handler, "\r\n");//Next Turn Effects
  fwrite($handler, "\r\n");//Decision Queue
  fwrite($handler, "0\r\n");//Decision Queue Variables
  fwrite($handler, "\r\n");//Layers
  fwrite($handler, "\r\n");//Layer Priority
  fwrite($handler, "1\r\n");//What player's turn it is
  fwrite($handler, "\r\n");//Last Played Card
  fclose($handler);

  //Set up log file
  $filename = "./Games/" . $gameName . "/gamelog.txt";
  $handler = fopen($filename, "w");
  fclose($handler);

  //Update the game file to show that the game has started and other players can join

  $filename = "./Games/" . $gameName . "/GameFile.txt";
  $gameFile = fopen($filename, "w");
  fwrite($gameFile, "1\r\n2\r\n6");
  fclose($gameFile);

  header("Location: " . $redirectPath . "/StartEffects.php?gameName=$gameName&playerID=1");

  exit;


  function make_seed()
  {
    list($usec, $sec) = explode(' ', microtime());
    return $sec + $usec * 1000000;
  }

  function initializePlayerState($handler, $deckHandler)
  {
    $charEquip = GetArray($deckHandler);
    $deckCards = GetArray($deckHandler);
    $deckSize = count($deckCards);
    $cards = array_fill(0, $deckSize, 0);
    $deck = array();
    //Randomize the deck
    for($i=0; $i<$deckSize; ++$i)
    {
      $num = rand(1, $deckSize-$i);
      $found = 0;
      for($j=0; $j<$deckSize && $found!=$num; ++$j)
      {
        if($cards[$j] == 0) ++$found;
        if($found==$num)
        {
          $deck[count($deck)] = $deckCards[$j];
          $cards[$j] = 1;
        }
      }
    }
    //Output player 1's hand
    $int = 4;//TODO: Base on hero
    $hand = "";
    for($i=0; $i<$int; ++$i)
    {
      if($i >= count($deck)) break;
      $hand .= $deck[$i];
      if($i != $int-1) $hand .= " ";
    }
    fwrite($handler, $hand . "\r\n");
    //Output the rest of the deck
    $deckStr = "";
    for($i=$int; $i<$deckSize; ++$i)
    {
      $deckStr .= $deck[$i];
      if($i != $deckSize-1) $deckStr .= " ";
    }
    $deckStr .= "\r\n";
    fwrite($handler, $deckStr);

    for($i=0; $i<count($charEquip); ++$i)
    {
      fwrite($handler, $charEquip[$i] . " 2 0 0 0 " . CharacterNumUsesPerTurn($charEquip[$i]) . " 0 0" . ($i < count($charEquip)-1 ? " " : "\r\n"));
    }
               //Character and equipment. First is ID. Four numbers each. First is status (0=Destroy/unavailable, 1=Used, 2=Unused, 3=Disabled). Second is num counters
               //Third is attack modifier, fourth is block modifier
               //Order: Character, weapon 1, weapon 2, head, chest, arms, legs
    fwrite($handler, "0 0\r\n");//Resources float/needed
    fwrite($handler, "\r\n");//Arsenal
    fwrite($handler, "\r\n");//Item
    fwrite($handler, "\r\n");//Aura
    fwrite($handler, "\r\n");//Discard
    fwrite($handler, "\r\n");//Pitch
    fwrite($handler, "\r\n");//Banish
    fwrite($handler, "0 0 0 0 0 0 0 0 DOWN 0 -1 0 0 0 0 0 0 0 0 0 0 0 NA 0 0 0 - -1 0 0 0 0 0 0 - 0 0 0\r\n");//Class State
    fwrite($handler, "\r\n");//Character effects
    fwrite($handler, "\r\n");//Soul
    fwrite($handler, "\r\n");//Card Stats
    fwrite($handler, "\r\n");//Turn Stats
    fwrite($handler, "\r\n");//Allies
    //$holdPriority = ($charEquip[0] == "ARC113" || $charEquip[0] == "ARC114" ? "1" : "0");
    $holdPriority = "1";
    fwrite($handler, $holdPriority . " 1\r\n");//Settings
  }

  function GetArray($handler)
  {
    $line = trim(fgets($handler));
    if($line=="") return [];
    return explode(" ", $line);
  }

?>

Something is wrong with the XAMPP installation :-(
