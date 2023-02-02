<?php

/*
Encounter variable
encounter[0] = Encounter ID (001-099 Special Encounters | 101-199 Combat Encounters | 201-299 Event Encounters)
encounter[1] = Encounter Subphase
encounter[2] = Position in adventure
encounter[3] = Hero ID
encounter[4] = Adventure ID
encounter[5] = A string made up of encounters that have already been visited, looks like "ID-subphase,ID-subphase,ID-subphase,etc."
encounter[6] = majesticCard% (1-100, the higher it is, the more likely a majestic card is chosen) (Whole code is based off of the Slay the Spire rare card chance)
encounter[7] = background chosen
*/

function EncounterDescription()
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[0])
  {
    case 001:
      return "Welcome to Blackjack's Tavern!";
    case 002:
      return "Choose your hero";
    case 003:
      return "Choose a bounty";
    case 004:
      return "Welcome back, oh great hero of Rathe. I presume you come bearing good news? Which bounty can I take the time to cross off of this here bounty board?";
    case 005:
      return "And your reward, as promised. I hope you can use one of these to cross another bounty off my board, what do ya say?";
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
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cursed Scholar.";
      else if($encounter[1] == "AfterFight") return "You defeated the Rune Scholar.";
    case 108:
      if($encounter[1] == "BeforeFight") return "You come upon Ira. Prepare to fight.";
      else if($encounter[1] == "AfterFight") return "You defeated Ira.";
    case 113:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cloaked Figure wielding two daggers.";
      else if($encounter[1] == "AfterFight") return "You defeated the Cloaked Figure.";

    case 201:
      return "You found a battlefield. Choose what you want to do.";
    case 202:
      return "You found a library. Choose what you want to do.";
    case 203:
      return "You've stumbled on a city on the boundary between ice and lightning. You hear thunderous cracking; you can't tell which it is from. There's a tantalizing stream of energy that looks invigorating, but it's mixed with frost. You think you can time it right...";
    case 204:
      return "You stumble on a great forge, big enough for giants. The giant manning the forge comments on your flimsy armor.";
    case 205:
      return "You enter a temple. There is an altar that reads \"Offer unto yourself and receive a bountiful blessing.\"";
    case 206:
      $health = &GetZone(1, "Health");
      if($health[0] > 1) return "A witch on the side of the road approaches you. 'No! I don't wish to fight you. I only wish to play a game.'";
      else return "A witch on the side of the road approaches you. 'No! I don't wish to fight you. I only wish to play a game. But it seems you have nothing to offer me, so I must take my leave.'";

    default: return "No encounter text.";
  }
}


function InitializeEncounter($player)
{
  $encounter = &GetZone($player, "Encounter");
  /*WriteLog("===============================");
  WriteLog("Encounter[0] = " . $encounter[0]);
  WriteLog("Encounter[1] = " . $encounter[1]);
  WriteLog("Encounter[2] = " . $encounter[2]);
  WriteLog("Encounter[3] = " . $encounter[3]);
  WriteLog("Encounter[4] = " . $encounter[4]);
  WriteLog("Encounter[5] = " . $encounter[5]);
  WriteLog("Encounter[6] = " . $encounter[6]);
  WriteLog("Encounter[7] = " . $encounter[7]);
  WriteLog("===============================");*/
  switch($encounter[0])
  {
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
      //if($encounter[3] == "Dorinthea") AddDecisionQueue("BUTTONINPUT", $player, "Cintari_Saber_Background,Dawnblade_Background");
      //if($encounter[3] == "Bravo") AddDecisionQueue("BUTTONINPUT", $player, "Anothos_Background,Titans_Fist_Background");
      AddDecisionQueue("BUTTONINPUT", $player, GetBackgrounds($encounter[3]));
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      //AddDecisionQueue("SETENCOUNTER", $player, "205-PickMode");
      break;
    case 005:
      AddDecisionQueue("CHOOSECARD", $player, GetPowers());
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
    case 204:
      AddDecisionQueue("BUTTONINPUT", $player, "Use_Forge,Ask_Legend,Leave");
      AddDecisionQueue("BLACKSMITH", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 205:
      AddDecisionQueue("BUTTONINPUT", $player, "Make_a_Small_Offering,Make_a_Sizable_Offering,Make_a_Large_Offering,Quietly_Pray,Leave");
      AddDecisionQueue("ENLIGHTENMENT", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
    case 206:
      $health = &GetZone($player, "Health");
      if($health[0] > 1) AddDecisionQueue("BUTTONINPUT", $player, "Offer_her_1_life,Leave");
      else AddDecisionQueue("BUTTONINPUT", $player, "Leave");
      AddDecisionQueue("OLDHAG", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 207:
      AddDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player));
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
    case 108:
      return "CRU046_cropped.png";
    case 113:
      return "WTR109_cropped.png";

    case 201:
      return "WTR194_cropped.png";
    case 202:
      return "UPR199_cropped.png";
    case 203:
      return "ELE112_cropped.png";
    case 204:
      return "WTR046_cropped.png";
    case 205:
      return "MON081_cropped.png";
    case 206:
      return "CRU188_cropped.png";

    default: return "CRU054_cropped.png";
  }
}

function GetBackgrounds($character)
{
  switch($character)
  {
    case "Dorinthea": $backgroundChoices = array("The_Volcai_Sellsword", "The_Lowly_Solanian", "The_Fierce_Warrior", "Spiders_Deserter"); break;
    case "Bravo": $backgroundChoices = array("The_Everfest_Showman", "The_Reclusive_Blacksmith", "The_Slumbering_Giant"); break;
  }
  $optionOne = rand(0, count($backgroundChoices)-1);
  $optionTwo = rand(0, count($backgroundChoices)-1);
  if($optionOne == $optionTwo)
  {
    if($optionOne == 0) ++$optionTwo;
    else --$optionTwo;
  }
  return $backgroundChoices[$optionOne] . "," . $backgroundChoices[$optionTwo];
}

function GetNextEncounter()
{
  $encounter = &GetZone(1, "Encounter");
  // WriteLog("hijacked GetNextEncounter");
  // WriteLog("Encounter[0]: " . $encounter[0]);
  // WriteLog("Encounter[1]: " . $encounter[1]);
  // WriteLog("Encounter[2]: " . $encounter[2]);
  ++$encounter[2];
  if($encounter[2] == 3 || $encounter[2] == 5) return GetCombat("Easy");
  else if($encounter[2] == 7 || $encounter[2] == 10) return GetCombat("Medium");
  else if($encounter[2] == 12 || $encounter[2] == 14) return GetCombat("Hard");
  else if($encounter[2] == 2) return "005-PickMode";
  else if($encounter[2] == 17) return "105-BeforeFight";
  else if($encounter[2] == 9 || $encounter[2] == 16) return "020-PickMode";
  //else return GetEvent();
  else return "20" . rand(1, 2) . "-PickMode";
}

function GetCombat($difficulty)
{
  $encounter = &GetZone(1, "Encounter");
  $alreadyPicked = explode(",", $encounter[5]);
  switch($difficulty)
  {
    case "Easy": $potentialEncounters = array("101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight", "113-BeforeFight"); break;
    case "Medium": $potentialEncounters = array("101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight"); break;
    case "Hard": $potentialEncounters = array("101-Fight", "102-BeforeFight", "103-BeforeFight", "104-BeforeFight", "106-BeforeFight", "107-BeforeFight"); break;
  }
  $generatedEncounters = [];
  for($i = 0; $i < count($potentialEncounters); ++$i)
  {
    $notFound = true;
    for($j = 0; $j < count($alreadyPicked) && $notFound; ++$j)
    {
      if($alreadyPicked[$j] == $potentialEncounters[$i]) $notFound = false;
    }
    if($notFound) array_push($generatedEncounters, $potentialEncounters[$i]);
  }
  //WriteLog("Amount of encounters to pick from: " . count($generatedEncounters));
  $randomEncounter = rand(0, count($generatedEncounters)-1);
  $encounter[5] = $encounter[5] . "," . $generatedEncounters[$randomEncounter];
  return $generatedEncounters[$randomEncounter];
}

function GetEvent()
{
  $encounter = &GetZone(1, "Encounter");
  $alreadyPicked = explode(",", $encounter[5]);
  $generateRand = rand(1, 100);
  if($generateRand >= 90) $rarity = "Rare";
  else if($generateRand >= 60) $rarity = "Uncommon";
  else $rarity = "Common";
  switch($rarity)
  {
    case "Common": $potentialEncounters = array("205-PickMode", "204-PickMode"); break;
    case "Uncommon": $potentialEncounters = array("205-PickMode"); break;
    case "Rare": $potentialEncounters = array("201-PickMode", "202-PickMode"); break;
  }
  $generatedEncounters = [];
  for($i = 0; $i < count($potentialEncounters); ++$i)
  {
    $notFound = true;
    for($j = 0; $j < count($alreadyPicked) && $notFound; ++$j)
    {
      if($alreadyPicked[$j] == $potentialEncounters[$i]) $notFound = false;
    }
    if($notFound) array_push($generatedEncounters, $potentialEncounters[$i]);
  }
  //WriteLog("Amount of encounters to pick from: " . count($generatedEncounters));
  $randomEncounter = rand(0, count($generatedEncounters)-1);
  $encounter[5] = $encounter[5] . "," . $generatedEncounters[$randomEncounter];
  return $generatedEncounters[$randomEncounter];
}

function GetPowers()
{
  $common = array("ROGUE507", "ROGUE508", "ROGUE509", "ROGUE510", "ROGUE511", "ROGUE512", "ROGUE513", "ROGUE516", "ROGUE517");
  $rare = array("ROGUE501", "ROGUE504", "ROGUE518", "ROGUE519", "ROGUE521", "ROGUE522", "ROGUE523", "ROGUE524", "ROGUE525");
  $majestic = array("ROGUE502", "ROGUE503", "ROGUE505", "ROGUE506", "ROGUE526", "ROGUE527", "ROGUE528");
  $random = rand(1, 100);
  if($random >= 90) $choiceOne = $majestic[rand(0, count($majestic)-1)];
  else if($random >= 60) $choiceOne = $rare[rand(0, count($rare)-1)];
  else $choiceOne = $common[rand(0, count($common)-1)];

  $temp = [];
  for($i = 0; $i < count($common)-1; ++$i)
  {
    if($common[$i] != $choiceOne) array_push($temp, $common[$i]);
  }
  $common = $temp; $temp = [];
  for($i = 0; $i < count($rare)-1; ++$i)
  {
    if($rare[$i] != $choiceOne) array_push($temp, $rare[$i]);
  }
  $rare = $temp; $temp = [];
  for($i = 0; $i < count($majestic)-1; ++$i)
  {
    if($majestic[$i] != $choiceOne) array_push($temp, $majestic[$i]);
  }
  $majestic = $temp;

  $random = rand(1, 100);
  if($random >= 90) $choiceTwo = $majestic[rand(0, count($majestic)-1)];
  else if($random >= 60) $choiceTwo = $rare[rand(0, count($rare)-1)];
  else $choiceTwo = $common[rand(0, count($common)-1)];

  $temp = [];
  for($i = 0; $i < count($common)-1; ++$i)
  {
    if($common[$i] != $choiceOne && $common[$i] != $choiceTwo) array_push($temp, $common[$i]);
  }
  $common = $temp; $temp = [];
  for($i = 0; $i < count($rare)-1; ++$i)
  {
    if($rare[$i] != $choiceOne && $rare[$i] != $choiceTwo) array_push($temp, $rare[$i]);
  }
  $rare = $temp; $temp = [];
  for($i = 0; $i < count($majestic)-1; ++$i)
  {
    if($majestic[$i] != $choiceOne && $majestic[$i] != $choiceTwo) array_push($temp, $majestic[$i]);
  }
  $majestic = $temp;

  $random = rand(1, 100);
  if($random >= 90) $choiceThree = $majestic[rand(0, count($majestic)-1)];
  else if($random >= 60) $choiceThree = $rare[rand(0, count($rare)-1)];
  else $choiceThree = $common[rand(0, count($common)-1)];
  return $choiceOne . "," . $choiceTwo . "," . $choiceThree;
}

function GetRandomCards($number, $special = "")
{
  //Hardcoded for 4. This is currently the only number that ever gets passed.
  $rv = "";
  if($special == "Elements") {
    RandomCard("Earth").",".RandomCard("Ice").",".RandomCard("Lightning");
  }
  if($special == "Draconic") {
    RandomCard("Draconic").",".RandomCard("Draconic").",".RandomCard("Draconic").",UPR101";
  }
  if($number == 4){
    //Current Pulls: Class/Class/Talent/Generic
    return RandomCard("Class").",".RandomCard("Class").",".RandomCard("Talent").",".RandomCard("Generic");
  }

  else{
    for($i=0; $i<$number; ++$i)
  {
    if($rv != "") $rv .= ",";
    $rv .= RandomCard("Class");
  }
  }
  return $rv;
}

function GetRandomWithRarity($number, $rarity){ //Used for Enlightenment Event
  $rv = "";
  $encounter = &GetZone(1, "Encounter");
  $classPool = GetPool("Class", $encounter[3], $rarity, $encounter[7]);
  $talentPool = GetPool("Talent", $encounter[3], $rarity, $encounter[7]);
  $genericPool = GetPool("Generic", $encounter[3], $rarity, $encounter[7]);
  for($i = 0; $i < $number; ++$i){
    if($i != 0) $rv.= ",";
    $seed = rand(0, 3);
    if($seed < 1){
      $rv.= $classPool[rand(0, count($classPool) - 1)];
    }
    elseif($seed > 2){
      $rv.= $talentPool[rand(0, count($talentPool) - 1)];
    }
    else{
      $rv.= $genericPool[rand(0, count($genericPool) - 1)];
    }
  }
  return $rv;
}

function GetRandomArmor($type)
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[3])
  {
    case "Dorinthea":
    {
      switch($type)
      {
        case "Head": $pool = array("UPR183", "WTR151", "MON241", "WTR155", "ARC155", "ELE233", "DYN236", "ARC151", "EVR053"); break;
        case "Chest": $pool = array("MON238", "DVR004", "ELE234", "WTR152", "MON242", "WTR156", "ARC156", "UPR184", "DYN237", "ARC152", "CRU081"); break;
        case "Arms": $pool = array("ARC153", "ELE235", "CRU179", "WTR153", "MON243", "WTR157", "ARC157", "UPR185", "DYN238", "MON239", "MON108"); break;
        case "Legs": $pool = array("MON244", "WTR158", "ARC154", "ARC158", "UPR186", "ELE236", "WTR154", "DYN239", "MON240", "WTR117"); break;
      }
      break;
    }
    case "Bravo":
    {
      switch($type)
      {
        case "Head": $pool = array("UPR183", "WTR151", "MON241", "WTR155", "ARC155", "ELE233", "DYN236", "ARC151", "WTR042"); break;
        case "Chest": $pool = array("MON238", "DVR004", "ELE234", "WTR152", "MON242", "WTR156", "ARC156", "UPR184", "DYN237", "ARC152", "EVR020"); break;
        case "Arms": $pool = array("ARC153", "ELE235", "CRU179", "WTR153", "MON243", "WTR157", "ARC157", "UPR185", "DYN238", "MON239", "CRU025"); break;
        case "Legs": $pool = array("MON244", "WTR158", "ARC154", "ARC158", "UPR186", "ELE236", "WTR154", "DYN239", "MON240"); break;
      }
      break;
    }
  }
  return $pool[rand(0, count($pool)-1)];
}

function GetRandomDeckCard($player) //Hardcoded for 4 always
{
  $deck = &GetZone($player, "Deck");
  /*$rv = "";
  for($i = 0; $i < 4; $i++){
    $rv .= "," . $deck[rand(0, count($deck) - 1)];
  }
  return $rv;*/
  return $deck[rand(0, count($deck) - 1)].",".$deck[rand(0, count($deck) - 1)].",".$deck[rand(0, count($deck) - 1)].",".$deck[rand(0, count($deck) - 1)];

}

function RandomCard($type)
{
  $encounter = &GetZone(1, "Encounter");
  $randRarity = rand(1,100);
  //WriteLog("random pull = " . $randRarity);
  if($randRarity <= $encounter[6])
  {
    $encounter[6] = 1;
    $majesticPool = GetPool($type, $encounter[3], "Majestic", $encounter[7]);
    return $majesticPool[rand(0,count($majesticPool) - 1)];
  }
  else if($randRarity >= 75)
  {
    $encounter[6] += 3;
    $rarePool = GetPool($type, $encounter[3], "Rare", $encounter[7]);
    return $rarePool[rand(0,count($rarePool) - 1)];
  }
  else
  {
    $encounter[6] +=1;
    $commonPool = GetPool($type, $encounter[3], "Common", $encounter[7]);
    return $commonPool[rand(0,count($commonPool) - 1)];
  }
}

function GetPool($type, $hero, $rarity, $background)
{
  if($type == "Light")
  {
    switch($rarity)
    {
      case "Common": return array();
      case "Rare": return array();
      case "Majestic": return array();
    }
  }
  if($type == "Shadow")
  {
    switch($rarity)
    {
      case "Common": return array();
      case "Rare": return array();
      case "Majestic": return array();
    }
  }
  if($type == "Earth")
  {
    switch($rarity)
    {
      case "Common": return array("ELE128", "ELE129", "ELE130", "ELE131", "ELE132", "ELE133", "ELE134", "ELE135", "ELE136", "ELE137", "ELE138", "ELE139");
      case "Rare": return array("ELE119", "ELE120", "ELE121");
      case "Majestic": return array("ELE117", "ELE118");
    }
  }
  if($type == "Ice")
  {
    switch($rarity)
    {
      case "Common": return array("ELE160", "ELE161", "ELE162", "ELE166", "ELE167", "ELE168", "ELE169", "ELE170", "ELE171", "UPR147", "UPR148", "UPR149");
      case "Rare": return array("ELE148", "ELE149", "ELE150", "ELE151", "ELE152", "ELE153");
      case "Majestic": return array("ELE147", "UPR138", "UPR139");
    }
  }
  if($type == "Lightning")
  {
    switch($rarity)
    {
      case "Common": return array("ELE189", "ELE190", "ELE191", "ELE192", "ELE193", "ELE194", "ELE195", "ELE196", "ELE197", "ELE198", "ELE199", "ELE200");
      case "Rare": return array("ELE177", "ELE178", "ELE179", "ELE181", "ELE182", "ELE183");
      case "Majestic": return array("ELE175", "ELE176");
    }
  }
  if($type == "Draconic")
  {
    switch($rarity)
    {
      case "Common":
      case "Rare": return array("UPR092", "UPR093", "UPR094", "UPR095", "UPR096", "UPR097", "UPR098", "UPR099", "UPR100");
      case "Majestic": return array("UPR086", "UPR087", "UPR088", "UPR089");
    }
  }
  switch($hero)
  {
    case "Dorinthea":
      {
        switch($type)
        {
          case "Class":
            {
              switch($background)
              {
                case "Saber":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR060", "EVR061", "EVR062", "EVR063", "EVR064", "EVR065", "EVR066", "EVR067", "EVR068",
                      "DVR009",
                      "DYN079", "DYN080", "DYN081", "DYN085", "DYN086", "DYN087"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "EVR057", "EVR058", "EVR059",
                      "DVR013",
                      "DYN073", "DYN074", "DYN075", "DYN076", "DYN077", "DYN078"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU082", "CRU083", "CRU084",
                      "EVR056",
                      "DYN072"
                    );
                  }
                case "Dawnblade":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR063", "EVR064", "EVR065",
                      "DVR009",
                      "DYN079", "DYN080", "DYN081", "DYN085", "DYN086", "DYN087"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "EVR057", "EVR058", "EVR059",
                      "DVR013",
                      "DYN073", "DYN074", "DYN075", "DYN076", "DYN077", "DYN078"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU082", "CRU083", "CRU084",
                      "EVR054", "EVR056",
                      "DVR008",
                      "DYN072"
                    );
                  }
                case "Hatchet":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR060", "EVR061", "EVR062", "EVR063", "EVR064", "EVR065", "EVR066", "EVR067", "EVR068",
                      "DVR009",
                      "DYN082", "DYN083", "DYN084"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "DYN073", "DYN074", "DYN075"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU083", "CRU084",
                      "MON109",
                      "EVR056",
                      "DYN071"
                    );
                  }
                case "Battleaxe":
                  switch($rarity)
                  {
                    case "Common": return array(
                      "WTR132", "WTR133", "WTR134", "WTR135", "WTR136", "WTR137", "WTR138", "WTR139", "WTR140", "WTR141", "WTR142", "WTR143", "WTR144", "WTR145", "WTR146", "WTR147", "WTR148", "WTR149",
                      "CRU088", "CRU089", "CRU090", "CRU091", "CRU092", "CRU093", "CRU094", "CRU095", "CRU096",
                      "MON116", "MON117", "MON118",
                      "EVR063", "EVR064", "EVR065",
                      "DVR009",
                      "DYN082", "DYN083", "DYN084"
                    );
                    case "Rare": return array(
                      "WTR123", "WTR124", "WTR125", "WTR126", "WTR127", "WTR128", "WTR129", "WTR130", "WTR131",
                      "CRU085", "CRU086", "CRU087",
                      "MON110", "MON111", "MON112", "MON113", "MON114", "MON115",
                      "DYN073", "DYN074", "DYN075"
                    );
                    case "Majestic": return array(
                      "WTR118", "WTR119", "WTR120", "WTR121", "WTR122",
                      "CRU083", "CRU084",
                      "MON109",
                      "EVR054", "EVR056",
                      "DYN071"
                    );
                  }
              }
            }
          case "Talent": return GetPool("Class", $hero, $rarity, $background);
          case "Generic":
            {
              switch($rarity)
              {
                case "Common": return array(
                  "WTR203", "WTR204", "WTR205", "WTR209", "WTR210", "WTR212", "WTR213", "WTR214",
                  "ARC182", "ARC183", "ARC184", "ARC191", "ARC192", "ARC193",
                  "MON269", "MON270", "MON271",
                  "DVR014", "DVR023"
                );
                case "Rare": return array(
                  "WTR167", "WTR166", "WTR165", "WTR170", "WTR172", "WTR173", "WTR174", "WTR175",
                  "ARC164", "ARC165", "ARC163", "ARC170", "ARC171", "ARC172",
                  "CRU183", "CRU184", "CRU185"
                );
                case "Majestic": return array(
                  "WTR159", "WTR160",
                  "ARC159",
                  "CRU180", "CRU181", "CRU182",
                  "MON245", "MON246",
                  "EVR156", "EVR157", "EVR160",
                  "UPR187", "UPR188", "UPR189", "UPR190"
                );
              }
            }
        }
      }
    case "Bravo":
      switch ($type) {
        case "Class":
          switch ($background) {
            case "Anothos":
              switch($rarity){
                case "Common": return array(
                  "WTR057", "WTR058", "WTR059", "WTR063", "WTR064", "WTR065", "WTR066", "WTR067", "WTR068", "WTR069", "WTR070", "WTR071", "WTR072", "WTR073", "WTR074",
                  "CRU032", "CRU033", "CRU034", "CRU035", "CRU036", "CRU037", "CRU038", "CRU039", "CRU040", "CRU041", "CRU041", "CRU043",
                  "ELE209", "ELE210", "ELE211",
                  "EVR024", "EVR025", "EVR026", "EVR027", "EVR028", "EVR029", "EVR030", "EVR031", "EVR032", "EVR033", "EVR034", "EVR035",
                );
                case "Rare": return array(
                  "WTR045", "WTR046", "WTR048", "WTR049", "WTR050", "WTR051", "WTR054", "WTR055", "WTR056",
                  "CRU029", "CRU030", "CRU031",
                  "ELE206", "ELE207", "ELE208",
                  "DYN033", "DYN034", "DYN035"
                );
                case "Majestic": return array(
                  "WTR043", "WTR044", "WTR047",
                  "CRU026", "CRU027", "CRU028",
                  "DYN028", "DYN029",
                  "EVR000", "EVR021", "EVR022", "EVR023",
                );
              }
            case "Titan's Fist":
              //Off-hand buffers are added to this pool.
              switch($rarity){
                case "Common": return array(
                  "WTR057", "WTR058", "WTR059", "WTR063", "WTR064", "WTR065", "WTR066", "WTR067", "WTR068", "WTR069", "WTR070", "WTR071", "WTR072", "WTR073", "WTR074",
                  "CRU032", "CRU033", "CRU034", "CRU035", "CRU036", "CRU037", "CRU038", "CRU039", "CRU040", "CRU041", "CRU041", "CRU043",
                  "ELE209", "ELE210", "ELE211",
                  "EVR024", "EVR025", "EVR026", "EVR027", "EVR028", "EVR029", "EVR030", "EVR031", "EVR032", "EVR033", "EVR034", "EVR035",
                  "DYN036", "DYN037", "DYN038", "DYN039", "DYN040", "DYN041", "DYN042", "DYN043", "DYN044"
                );
                case "Rare": return array(
                  "WTR045", "WTR046", "WTR048", "WTR049", "WTR050", "WTR051", "WTR054", "WTR055", "WTR056",
                  "CRU029", "CRU030", "CRU031",
                  "ELE206", "ELE207", "ELE208",
                  "DYN030", "DYN031", "DYN032", "DYN033", "DYN034", "DYN035"
                );
                case "Majestic": return array(
                  "WTR043", "WTR044", "WTR047",
                  "CRU026", "CRU027", "CRU028",
                  "DYN028", "DYN029",
                  "EVR000", "EVR021", "EVR022", "EVR023"
                );
              }
            case "Sledge":
              //This should be the same as Anothos, at least for now
              return GetPool("Class", "Bravo", $rarity, "Anothos");
          }
        case "Talent":
          return(GetPool("Class", $hero, $rarity, $background));
        case "Generic":
            switch($rarity)
              {
                case "Common": return array(
                  "WTR203", "WTR204", "WTR205", "WTR206", "WTR207", "WTR208", "WTR212", "WTR213", "WTR214",
                  "ARC182", "ARC183", "ARC184", "ARC191", "ARC192", "ARC193",
                  "MON269", "MON270", "MON271",
                  "DVR014", "DVR023"
                );
                case "Rare": return array(
                  "WTR167", "WTR166", "WTR165", "WTR170", "WTR172", "WTR173", "WTR174", "WTR175",
                  "ARC164", "ARC165", "ARC163", "ARC170", "ARC171", "ARC172",
                  "CRU183", "CRU184", "CRU185"
                );
                case "Majestic": return array(
                  "WTR159", "WTR160",
                  "ARC159",
                  "CRU180", "CRU181", "CRU182",
                  "MON245", "MON246",
                  "EVR156", "EVR157", "EVR160",
                  "UPR187", "UPR188", "UPR189", "UPR190"
                );
              }
      }
  }
}
?>
