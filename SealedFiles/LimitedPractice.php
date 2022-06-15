
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
  if($hero == "dromai") { $character = "UPR002"; $weapon1 = "UPR003"; }
  else if($hero == "fai") { $character = "UPR045"; $weapon1 = "UPR046"; }
  else if($hero == "iyslander")  { $character = "UPR103"; $weapon1 = "UPR165"; }
  if($hero == "dromai" || $hero == "fai") $sideboardCards = "UPR101 UPR101 UPR101";
  $head = "UPR183";
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

  header("Location: " . $redirectPath . "/CreateGame.php?deckTestMode=deckTestMode&deck=SEALED-" . $gameName . "&set=$set");

  function CanHeroUseCard($cardID, $hero)
  {
    $class = CardClass($cardID);
    $talent = CardTalent($cardID);
    if($class == "GENERIC") return true;
    if($hero == "dromai")
    {
      if($class == "ILLUSIONIST") return true;
      if($talent == "DRACONIC" && $class != "NINJA") return true;
    }
    else if($hero == "fai")
    {
      if($class == "NINJA") return true;
      if($talent == "DRACONIC" && $class != "ILLUSIONIST") return true;
    }
    else if($hero == "iyslander")
    {
      if($class == "WIZARD") return true;
      if($talent == "ICE" || $talent == "ELEMENTAL") return true;
    }
    return false;
  }

?>
