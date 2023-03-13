
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
  //$weapon1 = $charZone[1]; //Old code
  $deckCards = implode(" ", $cards);

  for ($i = 1; $i < count($charZone); ++$i) {
    $type = CardType($charZone[$i]);
    switch ($type) {
      case "E":
        //We don't need to process equipment here, instead we look for relevant Subtypes
      break;
      case "W":
        if ($weapon1 == "") { //If this is the first weapon read on the file,
          $weapon1 = $charZone[$i]; // then equip it
        }
        elseif(is1H($weapon1) && is1H($charZone[$i])) { //If equipped and current are both 1h,
          if($weapon2 == "" && $offhand == "") $weapon2 = $charZone[$i];
          else {
            if ($weaponSideboard != "")
            $weaponSideboard .= " ";
          $weaponSideboard .= $charZone[$i];
          }
        }
        else { //If we have extra weapons, then sideboard them
          if ($weaponSideboard != "")
            $weaponSideboard .= " ";
          $weaponSideboard .= $charZone[$i];
        }
       break;
      default:
        break;
    }
    $subtype = CardSubType($charZone[$i]);
    switch ($subtype) {
      case "Head":
        if ($head == "")
          $head = $charZone[$i];
        else {
          if ($headSideboard != "")
            $headSideboard .= " ";
          $headSideboard .= $charZone[$i];
        }
        break;
      case "Chest":
        if ($chest == "")
          $chest = $charZone[$i];
        else {
          if ($chestSideboard != "")
            $chestSideboard .= " ";
          $chestSideboard .= $charZone[$i];
        }
        break;
      case "Arms":
        if ($arms == "")
          $arms = $charZone[$i];
        else {
          if ($armsSideboard != "")
            $armsSideboard .= " ";
          $armsSideboard .= $charZone[$i];
        }
        break;
      case "Legs":
       if ($legs == "")
         $legs = $charZone[$i];
       else {
         if ($legsSideboard != "")
           $legsSideboard .= " ";
         $legsSideboard .= $charZone[$i];
       }
       break;
     case "Off-Hand":
       if ($offhand == "")
         $offhand = $charZone[$i];
         else {
         if ($offhandSideboard != "")
           $offhandSideboard .= " ";
         $offhandSideboard .= $charZone[$i];
         }
     default:
       break;
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

  $encounterName = GetEncounterName($encounter["EncounterID"]);

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
      case 108: return "Ira";
      case 113: return "Man of Momentum";
      case 114: return "StealthyStabber";
      case 115: return "CraneMaster";
      case 117: return "SparrowMaster";
      case 118: return "ExuberantEarthmage";
      case 119: return "HeronMaster";
      case 120: return "CombustibleCourier";
      case 121: return "SwingWithBigTree";
      default: return "Woottonhog";
    }
  }

?>
