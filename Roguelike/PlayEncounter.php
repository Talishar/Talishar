
<?php

  include '../Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);
  $hero=TryGet("hero", "");

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "../CardDictionary.php";

  $charZone = &GetZone($playerID, "Character");
  $cards = &GetZone($playerID, "Deck");
  $encounter = &GetZone($playerID, "Encounter");
  $health = &GetZone($playerID, "Health");

  $deckCards = "";
  $sideboardCards = "";
  $headSideboard = ""; $chestSideboard = ""; $armsSideboard = ""; $legsSideboard = ""; $offhandSideboard = "";
  $unsupportedCards = "";
  $character = ""; $head = ""; $chest = ""; $arms = ""; $legs = ""; $offhand = "";
  $weapon1 = "";
  $weapon2 = "";
  $weaponSideboard = "";
  $character = $charZone[0];
  $weapon1 = $charZone[1];
  $deckCards = implode(" ", $cards);

  for($i=1; $i<count($charZone); ++$i)
  {
    $subtype = CardSubType($charZone[$i]);
    switch($subtype)
    {
      case "Head":
        if($head == "") $head = $charZone[$i];
        else
        {
          if($headSideboard != "") $headSideboard .= " ";
          $headSideboard .= $charZone[$i];
        }
        break;
      case "Chest":
        if($chest == "") $chest = $charZone[$i];
        else
        {
          if($chestSideboard != "") $chestSideboard .= " ";
          $chestSideboard .= $charZone[$i];
        }
        break;
      default: break;
    }
  }

    $filename = "./Games/" . $gameName . "/LimitedDeck.txt";
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

  $encounterName = GetEncounterName($encounter[0]);

  header("Location: " . $redirectPath . "/CreateGame.php?deckTestMode=" . $encounterName . "&roguelikeGameID=" . $gameName . "&deck=ROGUELIKE-" . $gameName. "&startingHealth=" . $health[0]);

  function GetEncounterName($encounterId)
  {
    switch($encounterId)
    {
      case 101: return "Woottonhog";
      case 102: return "RavenousRabble";
      case 103: return "BarragingBrawnhide";
      case 104: return "ShockStriker";
      case 106: return "QuickshotNovice";
      case 107: return "RuneScholar";
      default: return "Woottonhog";
    }
  }

?>
