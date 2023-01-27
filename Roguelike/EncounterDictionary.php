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
    case 7:
      if($subphase == "BeforeFight") return "You're attacked by a Shock Striker.";
      else if($subphase == "AfterFight") return "You defeated the Shock Striker.";
    case 8:
      return "You've stumbled on a city on the boundary between ice and lightning. You hear thunderous cracking; you can't tell which it is from. There's a tantalizing stream of energy that looks invigorating, but it's mixed with frost. You think you can time it right...";
    case 9:
      if($subphase == "BeforeFight") return "You've finished the game (so far!). If you'd like to help out with adding new encounters/classes, check out our discord! The code is open source and can be found here: https://github.com/Talishar/Talishar/tree/main/Roguelike";
      else if($subphase == "AfterFight") return "You defeated the group of bandits.";
    case 10:
      return "Insert Flavor Text for Choosing a Backgroud";
    case 11:
      return "Insert Flavor Text for Choosing a Starting Bonus";
    default: return "No encounter text.";
  }
}


function InitializeEncounter($player, $encounter, $subphase)
{
  switch($encounter)
  {
    case 2:
      //AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn");
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
    case 8:
      AddDecisionQueue("BUTTONINPUT", $player, "Enter_Stream,Leave");
      AddDecisionQueue("VOLTHAVEN", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 10:
      AddDecisionQueue("BUTTONINPUT", $player, "Cintari_Saber_Background,Dawnblade_Background");
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 11:
      AddDecisionQueue("BUTTONINPUT", $player, "Choice_1_to_be_implemented,Choice_2_to_be_implemented");
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
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
    case 7:
      return "ELE197_cropped.png";
    case 8:
      return "ELE112_cropped.png";
    case 9:
      return "ELE117_cropped.png";
    case 10: case 11:
      return "ROGUELORE001_cropped.png";
    default: return "CRU054_cropped.png";
  }
}

function GetNextEncounter($previousEncounter)
{
  if(false)
  {
    WriteLog("hijacked GetNextEncounter");
    $encounter = &GetZone(1, "Encounter");
    WriteLog("Encounter[0]: " . $encounter[0]);
    WriteLog("Encounter[1]: " . $encounter[1]);
    WriteLog("Encounter[2]: " . $encounter[2]);
    ++$encounter[2];
    if($encounter[2] == 3 || $encounter[2] == 5) return GetEasyCombat($previousEncounter, $encounter);
    else if($encounter[2] == 7 || $encounter[2] == 10) return GetMediumCombat($previousEncounter, $encounter);
    else if($encounter[2] == 12 || $encounter[2] == 14) return GetHardCombat($previousEncounter, $encounter);
    else if($encounter[2] == 2) return "11-PickMode";
    else if($encounter[2] == 17) return "9-BeforeFight";
    else if($encounter[2] == 9 || $encounter[2] == 16) return "2-PickMode";
    else return GetEvent($previousEncounter, $encounter);
  }
  else
  {
    switch($previousEncounter)
    {
      case 1: return "6-PickMode";
      case 2: return "7-BeforeFight";
      case 3: return "2-PickMode";
      case 4: return "3-BeforeFight";
      case 5: return "4-PickMode";
      case 6: return "5-BeforeFight";
      case 7: return "8-PickMode";
      case 8: return "9-BeforeFight";
      case 10: return "11-PickMode";
      case 11: return "1-Fight";
      default: return "";
    }
  }
}

function GetEasyCombat($previousEncounter, $encounter)
{
  //$alreadyPicked = explode(" ", $encounter[3]);
  $easyEncounters = array(
    "1-Fight", "3-BeforeFight", "5-BeforeFight", "7-BeforeFight"
  );
  $randomEncounter = rand(0, count($easyEncounters)-1); //going to implement  no duplicate encounters later
  /*$encounterFound = false;
  for($i = 0; i < count($easyEncounters) && !$encounterFound; ++$i)
  {
    if($randomEncounter == count($easyEncounters)) { $randomEncounter = 0; }
    for($index = 0; $index < count($alreadyPicked); $index++)
    {
      if($easyEncounters[$randomEncounter] == $alreadyPicked[$index]) { ++$randomEncounter; }
    }
  }*/
  return $easyEncounters[$randomEncounter];
}

function GetMediumCombat($previousEncounter, $encounter)
{
  $mediumEncounters = array(
    "1-Fight", "3-BeforeFight", "5-BeforeFight", "7-BeforeFight"
  );
  $randomEncounter = rand(0, count($mediumEncounters)-1);
  return $mediumEncounters[$randomEncounter];
}

function GetHardCombat($previousEncounter, $encounter)
{
  $hardEncounters = array(
    "1-Fight", "3-BeforeFight", "5-BeforeFight", "7-BeforeFight"
  );
  $randomEncounter = rand(0, count($hardEncounters)-1);
  return $hardEncounters[$randomEncounter];
}

function GetEvent($previousEncounter, $encounter)
{
  $eventEncounters = array(
    "4-PickMode", "6-PickMode"
  );
  $randomEncounter = rand(0, count($eventEncounters)-1);
  return $eventEncounters[$randomEncounter];
}

function GetRandomCards($number)
{
  //Hardcoded for 4. This is currently the only number that ever gets passed.
  $rv = "";
  if($number == 4){ 
    //Current Pulls: Warrior/Warrior/Warrior/Generic
    return RandomDoriCommon().",".RandomDoriCommon().",".RandomDoriCommon().",".RandomGenericCommon();
  }

  else{
    for($i=0; $i<$number; ++$i)
  {
    if($rv != "") $rv .= ",";
    $rv .= RandomDoriCommon();
  }
  }
  return $rv;
}

function RandomDoriCommon()
{
  //Card pool is all warrior commons up to Everfest, except Outland Skirmish
  $DoriPoolRandomCommon = array(
    "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
    "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
    "MON116", "MON117", "MON118",
    "EVR060", "EVR061", "EVR062", "EVR063", "EVR064", "EVR065"
  );
  $poolCount = count($DoriPoolRandomCommon);
  $number = rand(0,$poolCount - 1);
  return $DoriPoolRandomCommon[$number];
}

function RandomGenericCommon()
{
  //Wounding Blow RYB, Brandish RYB, Ravenous Rabble RYB
  $GenericPoolCommon = array(
    "WTR203", "WTR204", "WTR205", "WTR212", "WTR213", "WTR214", 
    "ARC182", "ARC183", "ARC184", "ARC191", "ARC192", "ARC193",
    "MON269", "MON270", "MON271",
    "DVR014", "DVR023"
  );
  $poolCount = count($GenericPoolCommon);
  $number = rand(0, $poolCount - 1);
  return $GenericPoolCommon[$number];
}
?>
