<?php

/*
Encounter variable
encounter[0] = Encounter ID (001-099 Special Encounters | 101-199 Combat Encounters | 201-299 Event Encounters)
encounter[1] = Encounter Subphase
encounter[2] = Position in adventure
encounter[3] = Hero ID
encounter[4] = Adventure ID
encounter[5] = A string made up of encounters that have already been visited, looks like "ID-subphase,ID-subphase,ID-subphase,etc."
*/

function EncounterDescription()
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[0])
  {
    /*
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
    */
    case 001:
      return "Welcome to Blackjack's Tavern!";
    case 002:
      return "Choose your hero";
    case 003:
      return "Choose a bounty";
    case 004:
      return "Insert Flavor Text for Choosing a Background";
    case 005:
      return "Insert Flavor Text for Choosing a Starting Bonus";
    case 020:
      return "You found a campfire. Choose what you want to do.";

    case 101:
      if($encounter[1] == "Fight") return "You're attacked by a Woottonhog.";
      else if($encounter[1] == "AfterFight") return "You defeated the Woottonhog.";
    case 102:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Ravenous Rabble.";
      else if($encounter[1] == "AfterFight") return "You defeated the Ravenous Rabble.";
    case 103:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Barraging Brawnhide.";
      else if($encounter[1] == "AfterFight") return "You defeated the Barraging Brawnhide.";
    case 104:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Shock Striker.";
      else if($encounter[1] == "AfterFight") return "You defeated the Shock Striker.";
    case 105:
      if($encounter[1] == "BeforeFight") return "You've finished the game (so far!). If you'd like to help out with adding new encounters/classes, check out our discord! The code is open source and can be found here: https://github.com/Talishar/Talishar/tree/main/Roguelike";
      else if($encounter[1] == "AfterFight") return "You defeated the group of bandits.";
    case 106:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cloaked Ranger.";
      else if($encounter[1] == "AfterFight") return "You defeated the Quickshot Novice.";
    case 107:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cursed Scholar";
      else if($encounter[1] == "AfterFight") return "You defeated the Rune Scholar";

    case 201:
      return "You found a battlefield. Choose what you want to do.";
    case 202:
      return "You found a library. Choose what you want to do.";
    case 203:
      return "You've stumbled on a city on the boundary between ice and lightning. You hear thunderous cracking; you can't tell which it is from. There's a tantalizing stream of energy that looks invigorating, but it's mixed with frost. You think you can time it right...";

    default: return "No encounter text.";
  }
}


function InitializeEncounter($player)
{
  $encounter = &GetZone($player, "Encounter");
  WriteLog("Encounter[0] = " . $encounter[0]);
  WriteLog("Encounter[1] = " . $encounter[1]);
  WriteLog("Encounter[2] = " . $encounter[2]);
  WriteLog("Encounter[3] = " . $encounter[3]);
  WriteLog("Encounter[4] = " . $encounter[4]);
  WriteLog("Encounter[5] = " . $encounter[5]);
  switch($encounter[0])
  {
    /*
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
      break;
    */
    case 001:
      AddDecisionQueue("BUTTONINPUT", $player, "Change_your_hero,Change_your_bounty,Begin_adventure");
      AddDecisionQueue("STARTADVENTURE", $player, "-");
      break;
    case 002:
      AddDecisionQueue("BUTTONINPUT", $player, "Dorinthea,Bravo");
      AddDecisionQueue("CHOOSEHERO", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "001-PickMode");
      break;
    case 003:
      AddDecisionQueue("BUTTONINPUT", $player, "Ira");
      AddDecisionQueue("CHOOSEADVENTURE", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "001-PickMode");
      break;
    case 004:
      if($encounter[3] == "Dorinthea") AddDecisionQueue("BUTTONINPUT", $player, "Cintari_Saber_Background,Dawnblade_Background");
      if($encounter[3] == "Bravo") AddDecisionQueue("BUTTONINPUT", $player, "Anothos_Background,Titans_Fist_Background");
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 005:
      AddDecisionQueue("BUTTONINPUT", $player, "Choice_1_to_be_implemented,Choice_2_to_be_implemented");
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 020:
      //AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn");
      AddDecisionQueue("CAMPFIRE", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;

    case 201:
      AddDecisionQueue("BUTTONINPUT", $player, "Loot,Pay_Respects");
      AddDecisionQueue("BATTLEFIELD", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 202:
      AddDecisionQueue("BUTTONINPUT", $player, "Search,Leave");
      AddDecisionQueue("LIBRARY", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 203:
      AddDecisionQueue("BUTTONINPUT", $player, "Enter_Stream,Leave");
      AddDecisionQueue("VOLTHAVEN", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    default: break;
  }
}

function EncounterImage()
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[0])
  {
    /*
    case 101:
      return "MON286_cropped.png";
    case 020:
      return "UPR221_cropped.png";
    case 102:
      return "ARC191_cropped.png";
    case 201:
      return "WTR194_cropped.png";
    case 103:
      return "WTR178_cropped.png";
    case 202:
      return "UPR199_cropped.png";
    case 104:
      return "ELE197_cropped.png";
    case 203:
      return "ELE112_cropped.png";
    case 105:
      return "ELE117_cropped.png";
    case 001: case 002: case 003: case 004: case 005:
      return "ROGUELORE001_cropped.png";*/
    case 001: case 002: case 003: case 004: case 005:
      return "ROGUELORE001_cropped.png";
    case 020:
      return "UPR221_cropped.png";

    case 101:
      return "MON286_cropped.png";
    case 102:
      return "ARC191_cropped.png";
    case 103:
      return "WTR178_cropped.png";
    case 104:
      return "ELE197_cropped.png";
    case 105:
      return "ELE117_cropped.png";
    case 106:
      return "ELE214_cropped.png";
    case 107:
      return "ARC103_cropped.png";

    case 201:
      return "WTR194_cropped.png";
    case 202:
      return "UPR199_cropped.png";
    case 203:
      return "ELE112_cropped.png";
    default: return "CRU054_cropped.png";
  }
}

function GetNextEncounter()
{
  if(true) //set to true to enable new random encounter generation
  {
    $encounter = &GetZone(1, "Encounter");
    WriteLog("hijacked GetNextEncounter");
    WriteLog("Encounter[0]: " . $encounter[0]);
    WriteLog("Encounter[1]: " . $encounter[1]);
    WriteLog("Encounter[2]: " . $encounter[2]);
    ++$encounter[2];
    if($encounter[2] == 3 || $encounter[2] == 5) return GetEasyCombat();
    else if($encounter[2] == 7 || $encounter[2] == 10) return GetMediumCombat();
    else if($encounter[2] == 12 || $encounter[2] == 14) return GetHardCombat();
    else if($encounter[2] == 2) return "005-PickMode";
    else if($encounter[2] == 17) return "105-BeforeFight";
    else if($encounter[2] == 9 || $encounter[2] == 16) return "020-PickMode";
    else return GetEvent($encounter);
  }
  else
  {
    switch($encounter[0])
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

function GetEasyCombat()
{
  $encounter = &GetZone(1, "Encounter");
  $alreadyPicked = explode(" ", $encounter[5]);
  $easyEncounters = array(
    "101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight"
  );
  $generatedEncounters = [];
  for($i = 0; $i < count($easyEncounters); ++$i)
  {
    $notFound = true;
    for($j = 0; $j < count($alreadyPicked) && $notFound; ++$j)
    {
      if($alreadyPicked[$j] == $easyEncounters[$i]) $notFound = false;
    }
    if($notFound) array_push($generatedEncounters, $easyEncounters[$i]);
  }
  WriteLog(count($generatedEncounters));
  $randomEncounter = rand(0, count($generatedEncounters)-1); //going to implement  no duplicate encounters later
  $encounter[5] = $encounter[5] . "," . $generatedEncounters[$randomEncounter];
  return $generatedEncounters[$randomEncounter];
}

function GetMediumCombat()
{
  $encounter = &GetZone(1, "Encounter");
  $alreadyPicked = explode(" ", $encounter[5]);
  $mediumEncounters = array(
    "101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight"
  );
  $generatedEncounters = [];
  for($i = 0; $i < count($mediumEncounters); ++$i)
  {
    $notFound = true;
    for($j = 0; $j < count($alreadyPicked) && $notFound; ++$j)
    {
      if($alreadyPicked[$j] == $mediumEncounters[$i]) $notFound = false;
    }
    if($notFound) array_push($generatedEncounters, $mediumEncounters[$i]);
  }
  $randomEncounter = rand(0, count($generatedEncounters)-1); //going to implement  no duplicate encounters later
  $encounter[5] = $encounter[5] . $generatedEncounters[$randomEncounter];
  return $generatedEncounters[$randomEncounter];
}

function GetHardCombat()
{
  $encounter = &GetZone(1, "Encounter");
  $alreadyPicked = explode(" ", $encounter[5]);
  $hardEncounters = array(
    "101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight"
  );
  $generatedEncounters = [];
  for($i = 0; $i < count($hardEncounters); ++$i)
  {
    $notFound = true;
    for($j = 0; $j < count($alreadyPicked) && $notFound; ++$j)
    {
      if($alreadyPicked[$j] == $easyEncounters[$i]) $notFound = false;
    }
    if($notFound) array_push($generatedEncounters, $hardEncounters[$i]);
  }
  $randomEncounter = rand(0, count($generatedEncounters)-1); //going to implement  no duplicate encounters later
  $encounter[5] = $encounter[5] . $generatedEncounters[$randomEncounter];
  return $generatedEncounters[$randomEncounter];
}

function GetEvent()
{
  $eventEncounters = array(
    "201-PickMode", "202-PickMode"
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
