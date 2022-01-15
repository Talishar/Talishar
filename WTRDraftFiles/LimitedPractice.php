
<?php

  include '../Libraries/HTTPLibraries.php';

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  $playerID=TryGet("playerID", 3);
  $hero=TryGet("hero", "");
  $set=TryGet("set", "");

  //First we need to parse the game state from the file
  include "ZoneGetters.php";
  include "ParseGamestate.php";
  include "../HostFiles/Redirector.php";
  include "../CardDictionary.php";

  $cards = &GetZone($playerID, "ChosenCards");

  $deckCards = "";
  $sideboardCards = "";
  $headSideboard = ""; $chestSideboard = ""; $armsSideboard = ""; $legsSideboard = ""; $offhandSideboard = "";
  $unsupportedCards = "";
  $character = ""; $head = ""; $chest = ""; $arms = ""; $legs = ""; $offhand = "";
  $weapon1 = "";
  $weapon2 = "";
  $weaponSideboard = "";
  if($hero == "rhinar") { $character = "WTR002"; $weapon1 = "WTR003"; }
  else if($hero == "bravo") { $character = "WTR039"; $weapon1 = "WTR040"; }
  else if($hero == "katsu")  { $character = "WTR077"; $weapon1 = "WTR078"; $weapon2 = "WTR078"; }
  else if($hero == "dori")  { $character = "WTR114"; $weapon1 = "WTR115";}
  for($i=0; $i<count($cards); ++$i)
  {
    if(CanHeroUseCard($cards[$i], $hero))
    {
      $id = $cards[$i];
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
        if($weaponSideboard != "") $weaponSideboard .= " ";
        $weaponSideboard .= $id;
      }
      else if($cardType == "E")
      {
        $subtype = CardSubType($id);
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
      else
      {
          if($deckCards != "") $deckCards .= " ";
          $deckCards .= $id;
      }
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

  header("Location: " . $redirectPath . "/CreateGame.php?deckTestMode=deckTestMode&deck=DRAFT-" . $gameName . "&set=$set");

  function CanHeroUseCard($cardID, $hero)
  {
    $class = CardClass($cardID);
    if($class == "GENERIC") return true;
    if($hero == "rhinar")
    {
      if($class == "BRUTE") return true;
    }
    else if($hero == "bravo")
    {
      if($class == "GUARDIAN") return true;
    }
    else if($hero == "katsu")
    {
      if($class == "NINJA") return true;;
    }
    else if($hero == "dori")
    {
      if($class == "WARRIOR") return true;;
    }
    return false;
  }

?>
