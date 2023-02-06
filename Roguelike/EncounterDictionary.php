<?php

include "EncounterLogic.php";
include "EncounterPools.php";

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
      //AddDecisionQueue("SETENCOUNTER", $player, "207-PickMode");
      break;
    case 005:
      AddDecisionQueue("CHOOSECARD", $player, GetPowers());
      //AddDecisionQueue("SETENCOUNTER", $player, "207-PickMode");
      AddDecisionQueue("SETENCOUNTER", $player, GetNextEncounter($encounter));
      break;
    case 020:
      //AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("BUTTONINPUT", $player, "Rest,Reflect");
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
    case 204: //obsolete, doesn't currently work
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
    case 207: //unused, entirely to test out removing cards
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
?>
