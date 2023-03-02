<?php

include "EncounterLogic.php";
include "EncounterPools.php";
include "AdventurePools.php";

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
encounter[8] = adventure difficulty (to be used later)
encounter[9] = current gold
encounter[10] = rerolls remaining //TODO: Add in a reroll system
*/

function EncounterDescription()
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[0])
  {
    case 001:
      return "Blackjack's Tavern is a lively space. You have been here many times before, and you come today with a purpose. What will you do?";
    case 002:
      return "A woman at a nearby table calls you over and asks you your name. You are in high spirits today, and give it freely.";
    case 003:
      return "You walk over to the bounty board. You see the bounty you are about to turn in, but you also see a few new ones. Which one catches your eye?";
    case 004:
      return "The bartender owes you a favor. Perhaps you should cash it in for some refreshments. The strength of the drink may make what's to come more difficult, though, so you choose carefully.";
    case 005:
      return "You approach the man in the corner. He greets you pleasantly. You have just finished a contract, which bounty are you removing from the board?";
    case 006:
      return "The man smiles and hands you your reward. You take it before making your way back out in search of your next bounty.";
    case 007:
      return "You found a campfire. Choose what you want to do.";
    case 8:
      return "You come across a small village. A merchant waves you down.";
    case 9:
      return GetCrossroadsDescription();

    case 101:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Woottonhog.";
      else if($encounter[1] == "AfterFight") return "You defeated the Woottonhog.";
    case 102:
      if($encounter[1] == "BeforeFight") return "While wandering through the woods, you're attacked by a mysterious and deadly looking creature.";
      else if($encounter[1] == "AfterFight") return "You defeated the Ravenous Rabble. There don't seem to be any other threats in these woods... at least not right now. You continue your trek.";
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
      if($encounter[1] == "BeforeFight") return "You turn to find another way, only to see someone has been following. They knock an arrow, ready to fight.";
      else if($encounter[1] == "AfterFight") return "You defeated the Quickshot Apprentice. His bow lies in pieces on the ground. ";
    case 107:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cursed Scholar.";
      else if($encounter[1] == "AfterFight") return "You defeated the Rune Scholar.";
    case 108:
      if($encounter[1] == "BeforeFight") return "You come upon Ira. Prepare to fight.";
      else if($encounter[1] == "AfterFight") return "You defeated Ira.";
    case 113:
      if($encounter[1] == "BeforeFight") return "You're attacked by a Cloaked Figure wielding two daggers.";
      else if($encounter[1] == "AfterFight") return "You defeated the Cloaked Figure.";
    case 114:
      if($encounter[1] == "BeforeFight") return "You barely notice as a stranger raises a dagger behind your back.";
      else if($encounter[1] == "AfterFight") return "You defended yourself, and defeated the assassin.";
    case 115:
      if($encounter[1] == "BeforeFight") return "A graceful figure stands atop a spire some distance away. He remains there, as if asking you to make the first move.";
      else if($encounter[1] == "AfterFight") return "You defeated the Master of the Arts.";
    case 117:
      if($encounter[1] == "BeforeFight") return "As night falls around you, you realize you aren't alone. A figure approaches, but does not draw their blade. Yet.";
      else if($encounter[1] == "AfterFight") return "You defeated the Master of the Arts.";
    case 118:
      if($encounter[1] == "BeforeFight") return "As you travel, you encounter a boisterous traveler wearing leather armor with a green glimmer. \"Hail, bounty hunter. Would you like to spar?\"";
      else if($encounter[1] == "AfterFight") return "\"Wow stranger, that was an impressive match. Thank you for the lessons learned!\"";
    case 119:
      if($encounter[1] == "BeforeFight") return "As you cross the bridge, thunder cracks. A storm begins, and the winds nearly knock you into the chasm below. A figure runs towards you, as though dancing on the rain itself.";
      else if($encounter[1] == "AfterFight") return "You defeated the Master of the Arts.";
    case 120:
      if($encounter[1] == "BeforeFight") return "You emerge in the noticeably cleaner streets of Metrix. Just as you're emerging, a mailman sees you and panics, attempting to attack you.";
      else if($encounter[1] == "AfterFight") return "You killed a poor mailman. You heartless monster! Oh well, no use dwelling on the past, now that you're out of the city it's time to move towards your objective.";

    case 201: return "You found a battlefield. Choose what you want to do.";
    case 202: return "You found a library. Choose what you want to do.";
    case 203: return "You've stumbled on a city on the boundary between ice and lightning. You hear thunderous cracking; you can't tell which it is from. There's a tantalizing stream of energy that looks invigorating, but it's mixed with frost. You think you can time it right...";
    case 204: return "You stumble on a great forge, big enough for giants. The giant manning the forge comments on your flimsy armor.";
    case 205: return "You enter a temple. There is an altar that reads \"Offer of yourself and receive a bountiful blessing.\"";
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
      AddDecisionQueue("BUTTONINPUT", $player, "Change_your_hero,Change_your_bounty,Change_your_difficulty,Begin_adventure");
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
      AddDecisionQueue("BUTTONINPUT", $player, "Normal");
      AddDecisionQueue("CHOOSEDIFFICULTY", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "001-PickMode");
      break;
    case 005:
      //if($encounter[3] == "Dorinthea") AddDecisionQueue("BUTTONINPUT", $player, "Cintari_Saber_Background,Dawnblade_Background");
      //if($encounter[3] == "Bravo") AddDecisionQueue("BUTTONINPUT", $player, "Anothos_Background,Titans_Fist_Background");
      AddDecisionQueue("BUTTONINPUT", $player, GetBackgrounds($encounter[3]));
      AddDecisionQueue("BACKGROUND", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "006-PickMode");
      //AddDecisionQueue("SETENCOUNTER", $player, "106-BeforeFight");
      break;
    case 006:
      AddDecisionQueue("CHOOSECARD", $player, GetPowers());
      //AddDecisionQueue("SETENCOUNTER", $player, "101-BeforeFight");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 007:
      //AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("BUTTONINPUT", $player, "Rest,Reflect");
      AddDecisionQueue("CAMPFIRE", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 8:
      //AddDecisionQueue("BUTTONINPUT", $player, "Rest,Learn,Reflect");
      AddDecisionQueue("SHOP", $player, GetShop());
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 9:
      AddDecisionQueue("BUTTONINPUT", $player, GetNextEncounter());
      AddDecisionQueue("CROSSROADS", $player, "-");
      break;

    case 201:
      AddDecisionQueue("BUTTONINPUT", $player, "Loot,Pay_Respects");
      AddDecisionQueue("BATTLEFIELD", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 202:
      AddDecisionQueue("BUTTONINPUT", $player, "Search,Leave");
      AddDecisionQueue("LIBRARY", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 203:
      AddDecisionQueue("BUTTONINPUT", $player, "Enter_Stream,Leave");
      AddDecisionQueue("VOLTHAVEN", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 204: //obsolete, doesn't currently work
      AddDecisionQueue("BUTTONINPUT", $player, "Use_Forge,Ask_Legend,Leave");
      AddDecisionQueue("BLACKSMITH", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 205:
      AddDecisionQueue("BUTTONINPUT", $player, "Make_an_offering,Quietly_Pray,Leave");
      AddDecisionQueue("ENLIGHTENMENT", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 206:
      $health = &GetZone($player, "Health");
      if($health[0] > 1) AddDecisionQueue("BUTTONINPUT", $player, "Offer_her_1_life,Leave");
      else AddDecisionQueue("BUTTONINPUT", $player, "Leave");
      AddDecisionQueue("OLDHAG", $player, "-");
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 207: //unused, entirely to test out removing cards
      AddDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player));
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    case 209:
      AddDecisionQueue("SHOP", $player, GetShop());
      AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
      break;
    default: /*WriteLog("We Shouldn't Be Here");*/ break;
  }
}

function EncounterImage()
{
  $encounter = &GetZone(1, "Encounter");
  switch($encounter[0])
  {
    case 001: case 002: case 003: case 004: case 005: case 006:
      return "ROGUELORE001_cropped.png";
    case 007:
      return "UPR221_cropped.png";
    case 8:
      return "WTR151_cropped.png";
    case 9:
      return GetCrossroadsImage();

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
      return "ARC072_cropped.png";
    case 107:
      return "ARC103_cropped.png";
    case 108:
      return "CRU046_cropped.png";
    case 113:
      return "WTR109_cropped.png";
    case 114:
      return "DYN147_cropped.png";
    case 115:
      return "CRU057_cropped.png";
    case 117:
      return "EVR038_cropped.png";
    case 118:
      return "ELE082_cropped.png";
    case 119:
      return "CRU055_cropped.png";
    case 120:
      return "CRU110_cropped.png";

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
