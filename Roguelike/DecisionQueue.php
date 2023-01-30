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
            PrependDecisionQueue("REMOVEDECKCARD", $player, "-");
            PrependDecisionQueue("CHOOSEDECKCARD", $player, "-");
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
      case "BACKGROUND":
        $deck = & GetZone($player, "Deck");
        $character = &GetZone($player, "Character");
        $encounter = &GetZone($player, "Encounter");
        switch($lastResult)
        {
          case "Cintari_Saber_Background":
            //PrependDecisionQueue("CHOOSECARD", $player, "CRU079");
            //PrependDecisionQueue("CHOOSECARD", $player, "CRU080");
            $encounter[7] = "Saber";
            array_push($character, "CRU079"); //Saber 1
            array_push($character, "CRU080"); //Saber 2
            array_push($deck, "EVR062"); //Blade Runner (B)
            array_push($deck, "EVR058"); //Slice and Dice (Y)
            array_push($deck, "EVR066"); //Outland Skirmish (R)
            break;
          case "Dawnblade_Background":
            //PrependDecisionQueue("CHOOSECARD", $player, "WTR115");
            $encounter[7] = "Dawnblade";
            array_push($character, "WTR115");
            array_push($deck, "WTR125"); //Overpower (B)
            array_push($deck, "WTR133"); //Ironsong Response (Y)
            array_push($deck, "MON113"); //Plow Through (R)
            break;
          case "Hatchets_Background":
            $encounter[7] = "Hatchet";
            array_push($character, "MON105"); //Body
            array_push($character, "MON106"); //Mind
            array_push($deck, "EVR062"); //Blade Runner (B)
            array_push($deck, "DYN083"); //Felling Swing (Y)
            array_push($deck, "EVR066"); //Outland Skirmish (R)
            break;
          case "Battleaxe_Background":
            $encounter[7] = "Battleaxe";
            array_push($character, "DYN068");
            array_push($deck, "WTR125"); //Overpower (B)
            array_push($deck, "WTR142"); //Sharpen Steel (Y)
            array_push($deck, "DYN082"); //Felling Swing (R)
            break;
          case "Anothos_Background":
            $encounter[7] = "Anothos";
            array_push($character, "WTR040");
            array_push($deck, "EVR024", "WTR065", "WTR066", "CRU035", "WTR206", "MON293");
            break;
          case "Titans_Fist_Background":
            $encounter[7] = "Titans";
            array_push($character, "ELE202");
            array_push($character, "ELE204");
            array_push($deck, "DYN031", "DYN038", "WTR063", "WTR064", "ARC202", "WTR212");
            break;
          case "Sledge_Background":
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
          case "Begin_adventure":
            $devTest = false;
            if($devTest) AddDecisionQueue("SETENCOUNTER", $player, "204-PickMode"); //set the above line to true and the last argument of this to your encounter to test it.
            else AddDecisionQueue("SETENCOUNTER", $player, "004-PickMode");
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
