<?php

include "EncounterDictionary.php";

function ClearPhase($player)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
  array_shift($decisionQueue);
}

function AddDecisionQueue($phase, $player, $parameter1="-", $parameter2="-", $parameter3="-", $subsequent=0, $makeCheckpoint=0)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_push($decisionQueue, $phase);
  array_push($decisionQueue, $parameter1);
  array_push($decisionQueue, $parameter2);
  array_push($decisionQueue, $parameter3);
  array_push($decisionQueue, $subsequent);
  array_push($decisionQueue, $makeCheckpoint);
}

function PrependDecisionQueue($phase, $player, $parameter1="-", $parameter2="-", $parameter3="-", $subsequent=0, $makeCheckpoint=0)
{
  $decisionQueue = &GetZone($player, "DecisionQueue");
  array_unshift($decisionQueue, $makeCheckpoint);
  array_unshift($decisionQueue, $subsequent);
  array_unshift($decisionQueue, $parameter3);
  array_unshift($decisionQueue, $parameter2);
  array_unshift($decisionQueue, $parameter1);
  array_unshift($decisionQueue, $phase);
}

  function ProcessDecisionQueue($player)
  {
    ContinueDecisionQueue($player);
  }

  //Must be called with the my/their context
  function ContinueDecisionQueue($player, $lastResult="")
  {
    global $makeCheckpoint;
    $decisionQueue = &GetZone($player, "DecisionQueue");
    if(count($decisionQueue) == 0)
    {
      return;
    }
    $phase = $decisionQueue[0];
    $parameter1 = $decisionQueue[1];
    $parameter2 = $decisionQueue[2];
    $parameter3 = $decisionQueue[3];
    $subsequent = $decisionQueue[4];
    $makeCheckpoint = $decisionQueue[5];
    $return = "PASS";
    ClearPhase($player);
    if($subsequent != 1 || is_array($lastResult) || strval($lastResult) != "PASS") $return = DecisionQueueStaticEffect($phase, $player, ($parameter1 == "<-" ? $lastResult : $parameter1), $parameter2, $parameter3, $lastResult);
    //if(strval($return) != "NOTSTATIC") ClearPhase($player);
    if(strval($return) == "NOTSTATIC") PrependDecisionQueue($phase, $player, $parameter1, $parameter2, $parameter3, $subsequent, $makeCheckpoint);
    if($parameter1 == "<-" && !is_array($lastResult) && $lastResult == "-1") $return = "PASS";//Collapse the rest of the queue if this decision point has invalid parameters
    if(strval($return) != "NOTSTATIC")
    {
      ContinueDecisionQueue($player, $return);
    }
  }

  function DecisionQueueStaticEffect($phase, $player, $parameter1, $parameter2, $parameter3, $lastResult)
  {
    global $numPlayers;
    switch($phase)
    {
      case "SETENCOUNTER":
        $params = explode("-", $parameter1);
        $encounter = &GetZone($player, "Encounter");
        $encounter[0] = $params[0];
        $encounter[1] = $params[1];
        //WriteLog("Setting Encounter -> " . $encounter[0] . "-" . $encounter[1]);
        InitializeEncounter($player);
        return 1;
      case "CAMPFIRE":
        switch($lastResult)
        {
          case "Rest":
            $health = &GetZone($player, "Health");
            $gain = (20 - $health[0] > 10 ? 10 : 20 - $health[0]);
            if($gain < 0) $gain = 0;
            $health[0] += $gain;
            WriteLog("You rested and gained " . $gain . " life.");
            break;
          case "Learn":
            WriteLog("You studied and learned a powerful specialization.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR119,DVR008,WTR121");
            break;
          case "Reflect":
            WriteLog("You reflected on the trials of the day, and may remove a card.");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player));
            break;
          default: break;
        }
      case "BATTLEFIELD":
        switch($lastResult)
        {
          case "Loot":
            WriteLog("You've found some equipment to salvage.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR155");
            break;
          case "Pay_Respects":
            WriteLog("You've found a new sense of peace and reflection.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR163");
            break;
          default: break;
        }
      case "LIBRARY":
        switch($lastResult)
        {
          case "Search":
            WriteLog("You searched the library and found an interesting book about fighting techniques.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4));
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4));
            break;
          case "Leave":
            break;
        }
      case "BLACKSMITH":
        $encounter = &GetZone($player, "Encounter");
        switch($lastResult)
        {
          case "Use_Forge":
            WriteLog("You used your might to craft some armor.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomArmor("Head"));
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomArmor("Chest"));
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomArmor("Arms"));
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomArmor("Legs"));
            break;
          case "Ask_Legend":
            WriteLog("A giant gave you a legendary gift.");
            if($encounter[3] == "Dorinthea") PrependDecisionQueue("CHOOSECARD", $player, "WTR116");
            if($encounter[3] == "Bravo") PrependDecisionQueue("CHOOSECARD", $player, "WTR041");
            break;
          case "Leave":
            break;
        }
      case "OLDHAG":
        switch($lastResult)
        {
          case "Offer_her_1_life":
            $health = &GetZone($player, "Health");
            $health[0] += -1;
            if(rand(1,4) == 3)
            {
              WriteLog("You gave the old hag some of your blood. It was enough.");
              PrependDecisionQueue("CHOOSECARD", $player, GetPowers());
            }
            else
            {
              WriteLog("You gave the old hag some of your blood, but it wasn't enough.");
              if($health[0] > 1) {
                PrependDecisionQueue("OLDHAG", $player, "-");
                PrependDecisionQueue("BUTTONINPUT", $player, "Offer_her_1_life,Leave");
              }
              else  {
                PrependDecisionQueue("OLDHAG", $player, "-");
                PrependDecisionQueue("BUTTONINPUT", $player, "Leave");
              }
            }
          case "Leave":
            break;
        }
      case "BACKGROUND":
        $deck = &GetZone($player, "Deck");
        $character = &GetZone($player, "Character");
        $encounter = &GetZone($player, "Encounter");
        switch($lastResult)
        {
          case "The_Volcai_Sellsword":
            $encounter[7] = "Saber";
            array_push($character, "CRU079", "CRU080"); //Cintari Sabers, both
            array_push($deck, "EVR062", "EVR058", "EVR066"); //Blade Runner B, Slice and Dice Y, Outland Skirmish R
            break;
          case "The_Lowly_Solanian":
            $encounter[7] = "Dawnblade";
            array_push($character, "WTR115");
            array_push($deck, "WTR125", "WTR133", "MON113"); //Overpower B, Ironsong Response Y, Plow Through R
            break;
          case "The_Fierce_Warrior":
            $encounter[7] = "Hatchet";
            array_push($character, "MON105", "MON106"); //Body and Mind
            array_push($deck, "EVR062", "DYN083", "EVR066"); //Blade Runner B, Felling Swing y, Outland Skirmish R
            break;
          case "Spiders_Deserter":
            $encounter[7] = "Battleaxe";
            array_push($character, "DYN068");
            array_push($deck, "WTR125", "WTR142", "DYN082"); //Overpower B, Sharpen Steel Y, Felling Swing R
            break;
          case "The_Everfest_Showman":
            $encounter[7] = "Anothos";
            array_push($character, "WTR040");
            array_push($deck, "EVR024", "WTR065", "WTR066", "CRU035", "WTR206", "MON293");
            break;
          case "The_Reclusive_Blacksmith":
            $encounter[7] = "Titan's Fist";
            array_push($character, "ELE202", "DYN026"); //Titan's Fist and Seasoned Saviour
            array_push($deck, "DYN031", "DYN038", "WTR063", "WTR064", "ARC202", "WTR212");
            break;
          case "The_Slumbering_Giant":
            $encounter[7] = "Sledge";
            array_push($character, "CRU024");
            array_push($deck, "ELE208", "EVR030", "WTR070", "CRU040", "WTR190", "ARC211");
            break;
        }
      case "STARTADVENTURE":
        switch($lastResult)
        {
          case "Change_your_hero":
            AddDecisionQueue("SETENCOUNTER", $player, "002-PickMode");
            break;
          case "Change_your_bounty":
            AddDecisionQueue("SETENCOUNTER", $player, "003-PickMode");
            break;
          case "Change_your_difficulty":
            AddDecisionQueue("SETENCOUNTER", $player, "004-PickMode");
            break;
          case "Begin_adventure":
            $devTest = false;
            if($devTest) AddDecisionQueue("SETENCOUNTER", $player, "102-PickMode"); //set the above line to true and the last argument of this to your encounter to test it.
            else AddDecisionQueue("SETENCOUNTER", $player, "005-PickMode");
            break;
          /*case "Shop_Test":
            AddDecisionQueue("SHOP", $player, "EVR060,DYN073,DYN071,MON107,WTR156,ROGUE501");
            break;*/
        }
        return 1;
      case "CHOOSEDIFFICULTY":
        switch($lastResult)
        {
          case "Easy":
          case "Normal":
          case "hard":
            break;
        }
        return 1;
      case "CHOOSEHERO": //Logic for hero selection moved to ResetHero function
          ResetHero($player, $lastResult);
        return 1;
      case "CHOOSEADVENTURE":
        switch($lastResult)
        {
          case "Ira":
          $encounter = &GetZone($player, "Encounter");
          $encounter[4] = "Ira";
          break;
        }
        return 1;
      case "VOLTHAVEN":
        switch($lastResult)
        {
          case "Enter_Stream":
            $health = &GetZone($player, "Health");
            if(rand(0,9) < 3)
            {
              $health[0] -= 3;
              if($health[0] < 0) $health[0] = 1;
              WriteLog("You mistimed your jump and got zapped by the energy.");
            }
            else {
              $health[0] += 5;
              if($health[0] > 20) $health[0] = 20;
              WriteLog("You timed your jump perfectly and feel reinvigorated by the stream of energy.");
            }
            break;
          case "Leave":
            break;
        }
        return 1;
      case "ENLIGHTENMENT":
        switch($lastResult)
        {
          case "Make_a_Small_Offering":
            $deck = &GetZone($player, "Deck");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4, "ForcedRarity", "Common"));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            break;
          case "Make_a_Sizable_Offering":
            $deck = &GetZone($player, "Deck");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4, "ForcedRarity", "Rare"));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            break;
          case "Make_a_Large_Offering":
            $deck = &GetZone($player, "Deck");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards(4, "ForcedRarity", "Majestic"));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomDeckCard($player, 4));
            break;
          case "Quietly_Pray":
            WriteLog("Your spirit is reinvigorated and your strength is renewed. You gain 7 health.");
            $health = &GetZone($player, "Health");
            $health[0] += 7;
            break;
          case "Leave":
              break;
        }
        return 1;
      case "CROSSROADS":
        switch($lastResult)
        {
          case "Go_towards_the_smoke_rising_in_the_distance": //campfire
            PrependDecisionQueue("SETENCOUNTER", $player, "007-PickMode");
            break;
          case "Follow_the_sounds_of_laughter": //shop
            PrependDecisionQueue("SETENCOUNTER", $player, "008-PickMode");
            break;
          case "Explore_some_nearby_ruins": //battlefield
            PrependDecisionQueue("SETENCOUNTER", $player, "201-PickMode");
            break;
          case "Visit_a_local_library": //library
            PrependDecisionQueue("SETENCOUNTER", $player, "202-PickMode");
            break;
          case "Enter_a_nearby_Temple": //Enlightenment
            PrependDecisionQueue("SETENCOUNTER", $player, "205-PickMode");
            break;
          case "Take_the_scenic_route_through_the_back_streets":
            PrependDecisionQueue("SETENCOUNTER", $player, "114-BeforeFight"); //Stealthy Stabber
            break;
          default: //
            PrependDecisionQueue("SETENCOUNTER", $player, "101-BeforeFight");
            break;
        }
        return 1;
      default:
        return "NOTSTATIC";
    }
  }
  function ResetHero($player, $hero="Dorinthea")
  {
  $heroFileArray = file("Heroes/" . $hero . ".txt", FILE_IGNORE_NEW_LINES);
  $health = &GetZone($player, "Health");
  array_push($health, 20); //TODO: Base on hero health
  $character = &GetZone($player, "Character");
  $character = explode(" ", $heroFileArray[0]); //TODO: Support multiple heroes
  $deck = &GetZone($player, "Deck");
  $deck = explode(" ", $heroFileArray[1]); //TODO: Support multiple heroes
  $encounter = &GetZone($player, "Encounter");
  $encounter[3] = $hero;
  }

?>
