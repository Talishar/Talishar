<?php

function EncounterDescription($encounter, $subphase)
{
  switch($encounter)
  {
    case 1:
      if($subphase == "Fight") return "You're attacked by a Woottonhog.";
      else if($subphase == "AfterFight") return "You defeated the Woottonhog.";
    case 2:
      return "You found a campfire. Choose what you want to do.";
    case 3:
      if($subphase == "BeforeFight") return "You're attacked by a Ravenous Rabble.";
      else if($subphase == "AfterFight") return "You defeated the Ravenous Rabble.";
    case 4:
      return "You found a battlefield. Choose what you want to do.";
    case 5:
      if($subphase == "BeforeFight") return "You're attacked by a Barraging Brawnhide.";
      else if($subphase == "AfterFight") return "You defeated the Barraging Brawnhide.";
    case 6:
      return "You found a library. Choose what you want to do.";
    default: return "No encounter text.";
  }
}


function InitializeEncounter($player, $encounter, $subphase)
{
  switch($encounter)
  {
    case 2:
      AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("CAMPFIRE", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 4:
      AddDecisionQueue("BUTTONINPUT", $player, "Loot,Pay_Respects");
      AddDecisionQueue("BATTLEFIELD", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 6:
      AddDecisionQueue("BUTTONINPUT", $player, "Search,Leave");
      AddDecisionQueue("LIBRARY", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    default: break;
  }
}

function EncounterImage($encounter, $subphase)
{
  switch($encounter)
  {
    case 1:
      return "MON286_cropped.png";
    case 2:
      return "UPR221_cropped.png";
    case 3:
      return "ARC191_cropped.png";
    case 4:
      return "WTR194_cropped.png";
    case 5:
      return "WTR178_cropped.png";
    case 6:
      return "UPR199_cropped.png";
    default: return "CRU054_cropped.png";
  }
}

function GetNextEncounter($previousEncounter)
{
  switch($previousEncounter)
  {
    case 1: return "6-PickMode";
    case 2: return "7-BeforeFight";
    case 3: return "4-PickMode";
    case 4: return "3-BeforeFight";
    case 5: return "2-PickMode";
    case 6: return "5-BeforeFight";
    default: return "";
  }
}

function GetRandomCards($number)
{
  $rv = "";
  for($i=0; $i<$number; ++$i)
  {
    if($rv != "") $rv .= ",";
    $rv .= RandomWarriorCommon();
  }
  return $rv;
}

function RandomWarriorCommon()
{
  $number = rand(0,38);
  if($number <= 17)
  {
    $rv = "WTR";
    $number = 132 + $number;
    $rv .= $number;
  }
  else if($number <= 26)
  {
    $rv = "CRU";
    $number = 88 + $number - 18;
    if($number < 100) $number = "0" . $number;
    $rv .= $number;
  }
  else if($number <= 29)
  {
    $rv = "MON";
    $number = 116 + $number - 27;
    $rv .= $number;
  }
  else if($number <= 38)
  {
    $rv = "EVR";
    $number = 60 + $number - 30;
    if($number < 100) $number = "0" . $number;
    $rv .= $number;
  }
  return $rv;
}

?>
