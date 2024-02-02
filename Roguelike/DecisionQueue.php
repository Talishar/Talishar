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
    //WriteLog($phase);
    switch($phase)
    {
      case "SETENCOUNTER":
        $params = explode("-", $parameter1);
        $encounter = &GetZone($player, "Encounter");
        $encounter->encounterID = $params[0];
        $encounter->subphase = $params[1];
        //WriteLog("Setting Encounter -> " . $encounter->encounterID . "-" . $encounter->subphase);
        InitializeEncounter($player);
        return 1;
      case "CAMPFIRE":
        switch($lastResult)
        {
          case "Rest":
            $life = &GetZone($player, "Life");
            $gain = (20 - $life[0] > 10 ? 10 : 20 - $life[0]);
            if($gain < 0) $gain = 0;
            $life[0] += $gain;
            WriteLog("You rested and gained " . $gain . " life.");
            break;
          case "Learn":
            WriteLog("You studied and learned a powerful specialization.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR119,DVR008,WTR121");
            break;
          case "Reflect":
            WriteLog("You reflected on the trials of the day, and may remove a card.");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,All"), "-", "NoReroll");
            break;
          default: break;
        }
        return 1;
      case "BATTLEFIELD":
        switch($lastResult)
        {
          case "Loot":
            WriteLog("You've found some equipment to salvage, and a gold piece in the pocket of one of the fallen.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR155", "-", "NoReroll");
            $encounter = &GetZone(1, "Encounter");
            $encounter->gold += 1;
            break;
          case "Pay_Respects":
            WriteLog("You honor the fallen. While their mortal form is gone, their stories live on. You carry their spirit with you. You gain 2 life.");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR163", "-", "NoReroll");
            $life = &GetZone(1, "Life");
            $life[0] += 2;
            break;
          default: break;
        }
        return 1;
      case "LIBRARY":
        switch($lastResult)
        {
          case "Search":
            WriteLog("You searched the library and found an interesting book about fighting techniques.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic"), "Reward,Class-Class-Talent-Generic");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic"), "Reward,Class-Class-Talent-Generic");
            break;
          case "Leave":
            break;
        }
        return 1;
      case "BLACKSMITH":
        $encounter = &GetZone($player, "Encounter");
        switch($lastResult)
        {
          case "Spend_some_time_forging_equipment_for_yourself":
            WriteLog("You used your might to craft some armor.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Common,Head"), "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Common,Chest"), "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Common,Arms"), "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Common,Legs"), "-", "NoReroll");
            break;
          case "Ask_the_blacksmith_to_make_you_a_piece_of_equipment":
            WriteLog("A giant gave you a legendary gift.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Legendary"), "-", "NoReroll");
            break;
          case "Leave":
            break;
        }
        return 1;
      case "OLDHAG":
        switch($lastResult)
        {
          case "Offer_her_1_life":
            $life = &GetZone($player, "Life");
            $life[0] += -1;
            if(rand(1,4) == 3)
            {
              WriteLog("You gave the old hag some of your blood. It was enough.");
              PrependDecisionQueue("CHOOSECARD", $player, GetPowers());
            }
            else
            {
              WriteLog("You gave the old hag some of your blood, but it wasn't enough.");
              if($life[0] > 1) {
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
        return 1;
      case "BACKGROUND":
        $deck = &GetZone($player, "Deck");
        $character = &GetZone($player, "Character");
        $encounter = &GetZone($player, "Encounter");
        GiveUniversalEquipment();
        switch($lastResult)
        {
          case "The_Volcai_Sellsword":
            $encounter->background = "Saber";
            array_push($character, "CRU079", "CRU080"); //Cintari Sabers, both
            array_push($deck, "EVR062", "EVR058", "EVR066"); //Blade Runner B, Slice and Dice Y, Outland Skirmish R
            break;
          case "The_Lowly_Solanian":
            $encounter->background = "Dawnblade";
            array_push($character, "WTR115");
            array_push($deck, "WTR125", "WTR133", "MON113"); //Overpower B, Ironsong Response Y, Plow Through R
            break;
          case "The_Fierce_Warrior":
            $encounter->background = "Hatchet";
            array_push($character, "MON105", "MON106"); //Body and Mind
            array_push($deck, "EVR062", "DYN083", "EVR066"); //Blade Runner B, Felling Swing y, Outland Skirmish R
            break;
          case "Spiders_Deserter":
            $encounter->background = "Battleaxe";
            array_push($character, "DYN068");
            array_push($deck, "WTR125", "WTR142", "DYN082"); //Overpower B, Sharpen Steel Y, Felling Swing R
            break;
          case "The_Everfest_Showman":
            $encounter->background = "Anothos";
            array_push($character, "WTR040");
            array_push($deck, "EVR024", "WTR065", "WTR066", "CRU035", "WTR206", "MON293");
            break;
          case "The_Reclusive_Blacksmith":
            $encounter->background = "TitanFist";
            array_push($character, "ELE202", "DYN026"); //Titan's Fist and Seasoned Saviour
            array_push($deck, "DYN031", "DYN038", "WTR063", "WTR064", "ARC202", "WTR212");
            break;
          case "The_Slumbering_Giant":
            $encounter->background = "Sledge";
            array_push($character, "CRU024");
            array_push($deck, "ELE208", "EVR030", "WTR070", "CRU040", "WTR190", "ARC211");
            break;
          case "The_Ancient_Ollin":
            $encounter->background = "Shiver";
            array_push($character, "ELE033");
            array_push($deck, "ELE044", "ELE045", "ELE057", "ELE058", "ELE153", "ELE154", "ELE171", "ELE168");
            break;
          case "The_Exuberant_Adventurer":
            $encounter->background = "Voltaire";
            array_push($character, "ELE034");
            array_push($deck, "ELE048", "ELE059", "ELE054", "ELE055", "ELE180", "ELE185", "ELE199", "ELE186");
            break;
          case "The_Hired_Crow":
            $encounter->background = "DeathDealer";
            array_push($character, "ARC040");
            array_push($deck, "ELE044", "ELE051", "ELE060", "ELE047", "ELE200", "ELE183", "ELE168", "ELE152");
            break;
          case "The_Roadside_Bandit":
            $encounter->background = "RedLiner";
            array_push($character, "CRU121");
            array_push($deck, "ELE216", "ELE217", "ARC069", "ARC070", "ARC054", "ARC055", "EVR100", "EVR101");
            break;
          case "The_Rebel_Organizer":
            $encounter->background = "Emberblade";
            array_push($character, "UPR046");
            array_push($deck, "UPR101", "UPR101", "UPR057", "UPR096", "UPR097");
            break;
          case "The_Travelling_Duo":
            $encounter->background = "Kodachi";
            array_push($character, "WTR078", "WTR078");
            array_push($deck, "UPR098", "UPR099", "UPR093", "UPR062", "UPR071");
            break;
          case "The_Archaeologist":
            $encounter->background = "Edge";
            array_push($character, "CRU050");
            array_push($deck, "UPR051", "UPR052", "UPR072", "UPR074", "WTR208");
            break;
          case "The_Emperor":
            $encounter->background = "Contract";
            array_push($character, "DYN115", "OUT010");
            array_push($deck, "DYN149", "DYN138", "DYN142", "DYN124", "DYN128");
            break;
          case "The_Doctor":
            $encounter->background = "Stealth";
            array_push($character, "DYN115", "OUT005");
            array_push($deck, "OUT034", "OUT024", "OUT036", "OUT039", "OUT017");
            break;
          case "The_Warrior":
            $encounter->background = "Reaction";
            array_push($character, "DYN115", "OUT008");
            array_push($deck, "OUT027", "OUT030", "OUT042", "OUT044", "OUT021");
            break;
        }
        return 1;
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
            AddDecisionQueue("SETENCOUNTER", $player, "005-PickMode");
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
          $encounter->adventure = "Ira";
          break;
        }
        return 1;
      case "VOLTHAVEN":
        switch($lastResult)
        {
          case "Enter_Stream":
            $life = &GetZone($player, "Life");
            if(rand(0,9) < 3)
            {
              $life[0] -= 3;
              if($life[0] < 0) $life[0] = 1;
              WriteLog("You mistimed your jump and got zapped by the energy.");
            }
            else {
              $life[0] += 5;
              if($life[0] > 20) $life[0] = 20;
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
          case "Quietly_Pray":
            WriteLog("Your spirit is reinvigorated and your strength is renewed. You gain 7 life.");
            $life = &GetZone($player, "Life");
            $life[0] += 7;
            break;
          case "Leave":
            break;
          case "Make_an_offering":
            PrependDecisionQueue("ENLIGHTENMENT", $player, "-");
            PrependDecisionQueue("BUTTONINPUT", $player, "Make_another_offering,Receive_a_small_blessing");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Make_another_offering":
            PrependDecisionQueue("ENLIGHTENMENT", $player, "-");
            PrependDecisionQueue("BUTTONINPUT", $player, "Make_a_final_offering,Receive_a_sizable_blessing");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Make_a_final_offering":
            PrependDecisionQueue("ENLIGHTENMENT", $player, "-");
            PrependDecisionQueue("BUTTONINPUT", $player, "Receive_an_incredible_blessing");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Receive_a_small_blessing":
            WriteLog("Your charity is recognized. May Sol shine upon you.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Common"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Common");
            break;
          case "Receive_a_sizable_blessing":
            WriteLog("Your act of selflessness is recognized and celebrated. Contemplate this treasure in your travels.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Rare"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Rare");
            break;
          case "Receive_an_incredible_blessing":
            WriteLog("Your depth of character and virtue is boundless. Please honor the church by carrying it's teaching throughout Rathe.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic");
            break;
          }
          return 1;
      case "ROCKS":
        switch($lastResult)
        {
          case "Trade_1_gold_pieces_for_the_stone":
            $encounter = &GetZone($player, "Encounter");
            $encounter->gold -= 1;
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("ResourceGems"), "-", "NoReroll");
            break;
          case "Decline_his_offer_and_move_on":
            break;
        }
        return 1;
      case "CLIFF":
        switch($lastResult)
        {
          case "Its_best_to_leave_the_gold_behind":
            $encounter = &GetZone($player, "Encounter");
            $encounter->gold = 0;
            WriteLog("You lost all your gold over the edge of a cliff.");
            break;
          case "Attempt_to_retrieve_your_coins":
            $encounter = &GetZone($player, "Encounter");
            $encounter->gold += 4;
            $life = &GetZone($player, "Life");
            $life[0] -= 4;
            WriteLog("You stumble down a cliff, losing some life but retrieving some gold. You even found some gold someone else had lost.");
            break;
        }
        return 1;
      case "PEACEFULMONK":
        switch($lastResult)
        {
          case "Tell_him_your_story":
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Listen_to_his_story":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic"), "Reward,Class-Class-Talent-Generic");
            break;
          case "Sit_peacefully":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic"), "Reward,Class-Class-Talent-Generic");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Leave":
            break;
        }
        return 1;
      case "SPARRINGKNIGHT":
        switch($lastResult)
        {
          case "Spar_for_a_short_while":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Spar_until_nightfall":
            $life = &GetZone($player, "Life");
            $life[0] -= 4;
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Politely_decline":
            break;
        }
        return 1;
      case "SHIYANASPEC":
        switch($lastResult)
        {
          case "Your_face":
            $encounter = &GetZone($player, "Encounter");
            switch($encounter->hero)
            {
              case "Dorinthea":
                PrependDecisionQueue("CHOOSECARD", $player, "WTR119", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "WTR119,WTR119", "-", "NoReroll");
                break;
              case "Bravo":
                PrependDecisionQueue("CHOOSECARD", $player, "WTR043", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "WTR043,WTR043", "-", "NoReroll");
                break;
              case "Fai":
                PrependDecisionQueue("CHOOSECARD", $player, "UPR091", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "UPR091,UPR091", "-", "NoReroll");
                break;
              case "Lexi":
                PrependDecisionQueue("CHOOSECARD", $player, "ELE036", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "ELE036,ELE036", "-", "NoReroll");
                break;
              case "Arakni":
                PrependDecisionQueue("CHOOSECARD", $player, "DYN121", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "DYN121,DYN121", "-", "NoReroll");
                break;
            }
            break;
          case "The_face_of_another":
            $encounter = &GetZone($player, "Encounter");
            switch($encounter->hero)
            {
              case "Dorinthea": $cardChoices = array("EVR070", "ARC007", "EVR055"); break;
              case "Bravo": $cardChoices = array("WTR006", "ARC080", "OUT013"); break;
              case "Fai": $cardChoices = array("ELE066", "CRU074", "EVR039"); break;
              case "Lexi": $cardChoices = array("ARC043", "UPR126", "ELE004"); break;
              case "Arakni": $cardChoices = array("WTR119", "WTR043", "MON199"); break;
            }
            $randNum = rand(0, 2);
            PrependDecisionQueue("CHOOSECARD", $player, $cardChoices[$randNum], "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, $cardChoices[$randNum].",".$cardChoices[$randNum], "-", "NoReroll");
            break;
        }
        return 1;
      case "CHEST":
        switch($lastResult)
        {
          case "Open_the_brown_chest":
            $encounter = &GetZone($player, "Encounter");
            $foundGold = rand(4, 10);
            $encounter->gold += $foundGold;
            WriteLog("You dug through the chest and found " . $foundGold . " Gold.");
            break;
          case "Open_the_white_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment"), "Equipment");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment"), "Equipment");
            break;
          case "Open_the_blue_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Class-Class"), "Reward,Class-Class-Class-Class");
            break;
          case "Open_the_red_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Talent-Talent-Talent-Talent"), "Reward,Talent-Talent-Talent-Talent");
            break;
          case "Open_the_green_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Generic-Generic-Generic-Generic"), "Reward,Generic-Generic-Generic-Generic");
            break;
          case "Open_the_purple_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Power,3,Common"), "Power,3,Common");
            break;
          case "Open_the_ornate_brown_chest":
            $encounter = &GetZone($player, "Encounter");
            $foundGold = rand(14, 20);
            $encounter->gold += $foundGold;
            WriteLog("You dug through the chest and found " . $foundGold . " Gold.");
            break;
          case "Open_the_ornate_white_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Equipment,Legendary"), "Equipment,Legendary");
            break;
          case "Open_the_ornate_blue_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Class,ForcedRarity-Majestic"), "Reward,Class-Class-Class,ForcedRarity-Majestic");
            break;
          case "Open_the_ornate_red_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Talent-Talent-Talent,ForcedRarity-Majestic"), "Reward,Talent-Talent-Talent,ForcedRarity-Majestic");
            break;
          case "Open_the_ornate_green_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Generic-Generic-Generic,ForcedRarity-Majestic"), "Reward,Generic-Generic-Generic,ForcedRarity-Majestic");
            break;
          case "Open_the_ornate_purple_chest":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Power,2,Majestic"), "Power,2,Majestic");
            break;
          case "Leave":
            break;
        }
        return 1;
      case "GAMBLER":
        switch($lastResult)
        {
          case "Partake_in_some_entertainment":
            $entertainment = rand(1, 4);
            $encounter = &GetZone($player, "Encounter");
            $encounter->gold += rand(-4, -8);
            if($encounter->gold < 0) $encounter->gold = 0;
            switch($entertainment)
            {
              case 1:
                WriteLog("You gambled and managed to win nearly every game! You should leave before they get angry.");
                $encounter->rerolls += 3;
                break;
              case 2:
                WriteLog("You gambled and did about as well as you expected.");
                $encounter->rerolls += 2;
                break;
              case 3:
                WriteLog("You gambled and barely scraped by with a win.");
                $encounter->rerolls += 1;
                break;
              case 4:
                WriteLog("You gambled and did horribly, losing every game. You should leave before you lose all your belongings");
                break;
            }
          case "Leave":
            break;
        }
        return 1;
      case "MIRROR":
        switch($lastResult)
        {
          case "Stare_into_the_mirror":
            $deck = &GetZone($player, "Deck");
            $newDeck = [];
            for($i = 0; $i < count($deck); ++$i)
            {
              if(CardSubtype($deck[$i]) == "Power")
              {
                array_push($newDeck, $deck[$i]);
              }
              else
              {
                array_push($newDeck, $deck[$i]);
                array_push($newDeck, $deck[$i]);
              }
            }
            $deck = $newDeck;
            WriteLog("You stare into the mirror. You feel your mind become cluttered with thoughts of thoughts.");
            break;
          case "Shatter_the_mirror":
            $deck = &GetZone($player, "Deck");
            $newDeck = [];
            for($i = 0; $i < count($deck); ++$i)
            {
              if(CardSubtype($deck[$i]) == "Power")
              {
                array_push($newDeck, $deck[$i]);
              }
            }
            $deck = $newDeck;
            WriteLog("You shatter the mirror. Your mind becomes empty, and you forget your journey. A short while later, it comes to you.");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic"), "Reward,Class-Class-Talent-Generic,ForcedRarity-Majestic");
            break;
          case "Leave":
            break;
        }
        return 1;
      case "WEAPONMASTER":
        switch($lastResult)
        {
          case "Take_what_you_can":
            $encounter = &GetZone($player, "Encounter");
            $character = &GetZone($player, "Character");
            switch($encounter->hero)
            {
              case "Dorinthea":
                PrependDecisionQueue("CHOOSECARD", $player, "CRU139", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "MON221", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "CRU100", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "CRU052", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "CRU052,CRU052", "-", "NoReroll");
                $encounter->background = "AllWeps";
                break;
              case "Bravo":
                PrependDecisionQueue("CHOOSECARD", $player, "MON229", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "MON221", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "EVR121", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "OUT008", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "OUT008,OUT008", "-", "NoReroll");
                break;
              case "Fai":
                PrependDecisionQueue("CHOOSECARD", $player, "OUT009", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "OUT009,OUT009", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "DYN115", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "DYN115,DYN115", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "ELE222", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "ELE003", "-", "NoReroll");
                break;
              case "Lexi":
                PrependDecisionQueue("CHOOSECARD", $player, "OUT093", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "EVR087", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "DYN151", "-", "NoReroll");
                break;
              case "Arakni":
                PrependDecisionQueue("CHOOSECARD", $player, "WTR078", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "ELE203", "-", "NoReroll");
                PrependDecisionQueue("CHOOSECARD", $player, "CRU051", "-", "NoReroll");
                break;
            }
            break;
          case "Put_the_bodies_to_rest":
            PrependDecisionQueue("CHOOSECARD", $player, "WTR173", "-", "NoReroll");
            //PrependDecisionQueue("CHOOSECARD", $player, "WTR173,WTR173", "-", "NoReroll");
            //PrependDecisionQueue("CHOOSECARD", $player, "WTR173,WTR173,WTR173", "-", "NoReroll");
            break;
        }
        return 1;
      case "TWISTEDLIBRARY":
        switch($lastResult)
        {
          case "Search_the_library":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,SpecialTag-AnyPool"), "Reward,Class-Class-Talent-Generic,SpecialTag-AnyPool");
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Reward,Class-Class-Talent-Generic,SpecialTag-AnyPool"), "Reward,Class-Class-Talent-Generic,SpecialTag-AnyPool");
            break;
          case "Donate_to_the_library":
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "leave":
            break;
        }
        return 1;
      case "COTTAGEWITCH":
        switch($lastResult)
        {
          case "Take_a_bite":
            $life = &GetZone($player, "Life");
            $life[0] -= $parameter2;
            $nextCost = $parameter2 + 1;
            PrependDecisionQueue("COTTAGEWITCH", $player, "-", $nextCost);
            if($life[0] > $nextCost) PrependDecisionQueue("BUTTONINPUT", $player, "Take_a_bite,Leave");
            else PrependDecisionQueue("BUTTONINPUT", $player, "Leave");
            PrependDecisionQueue("REMOVEDECKCARD", $player, GetRandomCards("Deck,4"), "Deck,4");
            break;
          case "Save_some_for_later":
            AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
            PrependDecisionQueue("CHOOSECARD", $player, "MON191", "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, "MON191,MON191", "-", "NoReroll");
            break;
          case "Leave":
            WriteLog("The Witch is dissapointed in your rush to leave, but understands.");
            AddDecisionQueue("SETENCOUNTER", $player, "009-PickMode");
            break;
        }
        return 1;
      case "SIGILSOLACE":
        switch($lastResult)
        {
          case "Rest_at_the_sigil":
            WriteLog("You feel more sure of yourself then you ever have been before. You can feel your destiny begin to change.");
            $encounter = &GetZone($player, "Encounter");
            $encounter->rerolls = 20;
            break;
          case "Take_the_sigil":
            PrependDecisionQueue("CHOOSECARD", $player, "WTR173", "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR173,WTR173", "-", "NoReroll");
            PrependDecisionQueue("CHOOSECARD", $player, "WTR173,WTR173,WTR173", "-", "NoReroll");
            break;
        }
        return 1;
      case "CLEARPOOL":
        switch($lastResult)
        {
          case "Cleanse_yourself_in_the_pool":
            WriteLog("As you get out of the pool, there is a weight on you that was not there before.");
            $encounter = &GetZone($player, "Encounter");
            $encounter->cleanse = true;
            break;
          case "Let_the_waters_wash_over_you":
            PrependDecisionQueue("CHOOSECARD", $player, GetRandomCards("Power,3,Rare"), "Power,3,Rare");
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
            $encounter = &GetZone(1, "Encounter");
            PrependDecisionQueue("SETENCOUNTER", $player, "008-PickMode");
            break;
          case "Search_through_the_treasures":
            PrependDecisionQueue("SETENCOUNTER", $player, "010-PickMode");
            break;
          case "Approach_your_destination":
            PrependDecisionQueue("SETENCOUNTER", $player, "108-BeforeFight");
            break;
          case "You_wander_through_a_fresh_battlefield": //battlefield
            PrependDecisionQueue("SETENCOUNTER", $player, "201-PickMode");
            break;
          case "You_find_a_great_library": //library
            PrependDecisionQueue("SETENCOUNTER", $player, "202-PickMode");
            break;
          case "You_see_a_small_temple_a_ways_from_the_path": //Enlightenment
            PrependDecisionQueue("SETENCOUNTER", $player, "205-PickMode");
            break;
          case "A_wandering_trader_approaches_you": //Rocks
            PrependDecisionQueue("SETENCOUNTER", $player, "208-PickMode");
            break;
          case "Follow_the_sound_of_metallic_ringing": //Giant Forge
            PrependDecisionQueue("SETENCOUNTER", $player, "204-PickMode");
            break;
          case "You_see_one_of_the_most_beautiful_views_in_all_of_Rathe": //Cliffside
            PrependDecisionQueue("SETENCOUNTER", $player, "210-PickMode");
            break;
          case "You_find_a_small_smithing_hut": //Armorer
            PrependDecisionQueue("SETENCOUNTER", $player, "211-PickMode");
            break;
          case "You_come_across_a_small_dojo": //DuplicateCard
            PrependDecisionQueue("SETENCOUNTER", $player, "212-PickMode");
            break;
          case "A_lavish_noble_passes_you_by": //Noble shop
            PrependDecisionQueue("SETENCOUNTER", $player, "213-PickMode");
            break;
          case "You_pass_a_strange_man_in_robes": //Monk
            PrependDecisionQueue("SETENCOUNTER", $player, "214-PickMode");
            break;
          case "A_knight_approaches_you_asking_to_spar": //Sparring Knight
            PrependDecisionQueue("SETENCOUNTER", $player, "215-PickMode");
            break;
          case "A_radiant_woman_comes_across_your_path": //Shiyana Spec
            PrependDecisionQueue("SETENCOUNTER", $player, "216-PickMode");
            break;
          case "You_find_a_small_brown_chest": //Gold Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "217-PickMode");
            break;
          case "You_find_a_small_white_chest": //Equipment Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "218-PickMode");
            break;
          case "You_find_a_small_blue_chest": //Class Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "219-PickMode");
            break;
          case "You_find_a_small_red_chest": //Talent Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "220-PickMode");
            break;
          case "You_find_a_small_green_chest": //Generic Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "221-PickMode");
            break;
          case "You_find_a_small_purple_chest": //Power Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "222-PickMode");
            break;
          case "You_find_an_ornate_brown_chest": //Gold Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "223-PickMode");
            break;
          case "You_find_an_ornate_white_chest": //Equipment Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "224-PickMode");
            break;
          case "You_find_an_ornate_blue_chest": //Class Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "225-PickMode");
            break;
          case "You_find_an_ornate_red_chest": //Talent Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "226-PickMode");
            break;
          case "You_find_an_ornate_green_chest": //Generic Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "227-PickMode");
            break;
          case "You_find_an_ornate_purple_chest": //Power Chest
            PrependDecisionQueue("SETENCOUNTER", $player, "228-PickMode");
            break;
          case "You_stumble_into_a_lively_tavern": //Gambler
            PrependDecisionQueue("SETENCOUNTER", $player, "229-PickMode");
            break;
          case "You_see_smoke_rising_in_the_distance": //shop, but it is funky
            PrependDecisionQueue("SETENCOUNTER", $player, "230-PickMode");
            break;
          case "You_find_a_large_shrine": //shrine
            PrependDecisionQueue("SETENCOUNTER", $player, "231-PickMode");
            break;
          case "You_find_a_large_mirror": //mirror
            PrependDecisionQueue("SETENCOUNTER", $player, "232-PickMode");
            break;
          case "You_visit_an_old_friend": //Weaponmaster
            PrependDecisionQueue("SETENCOUNTER", $player, "233-PickMode");
            break;
          case "You_come_across_a_strange_library": //twisted library
            PrependDecisionQueue("SETENCOUNTER", $player, "234-PickMode");
            break;
          case "You_find_an_old_cottage": //witch
            PrependDecisionQueue("SETENCOUNTER", $player, "235-PickMode");
            break;
          case "You_see_a_beautiful_sigil": //Sigil of Solace
            PrependDecisionQueue("SETENCOUNTER", $player, "236-PickMode");
            break;
          case "You_find_a_clear_pool": //clear pool
            PrependDecisionQueue("SETENCOUNTER", $player, "237-PickMode");
            break;
          case "Return_to_the_pool": //clear pool
            PrependDecisionQueue("SETENCOUNTER", $player, "238-PickMode");
            break;
          case "Take_the_scenic_route_through_the_back_streets": //Stealthy Stabber
            PrependDecisionQueue("SETENCOUNTER", $player, "114-BeforeFight");
            break;
          case "Make_your_way_up_through_Metrix": //Combustible Courier
            PrependDecisionQueue("SETENCOUNTER", $player, "120-BeforeFight");
            break;
            /* case "Catch_a_ferry_across_the_lake":
              PrependDecisionQueue("SETENCOUNTER", $player, "107-BeforeFight");
              break; */
          case "Venture_into_the_forest_and_attempt_to_sneak_past": //Ravenous Rabble
            PrependDecisionQueue("SETENCOUNTER", $player, "102-BeforeFight");
            break;
          case "Approach_the_roadblock_head_on": //SwingWithBigTree
            PrependDecisionQueue("SETENCOUNTER", $player, "121-BeforeFight");
            break;
          case "Take_precaution_and_find_a_different_way_across": //Crane Master
            PrependDecisionQueue("SETENCOUNTER", $player, "115-BeforeFight");
            break;
          case "Use_the_cover_of_darkness_to_cross": //Sparrow Master
            PrependDecisionQueue("SETENCOUNTER", $player, "117-BeforeFight");
            break;
          case "Turn_back_and_take_the_long_way_around": //Quickshot Apprentice
            PrependDecisionQueue("SETENCOUNTER", $player, "106-BeforeFight");
            break;
          case "Leave_the_town_immediately":
            PrependDecisionQueue("SETENCOUNTER", $player, "118-BeforeFight");
            break;
          case "Brave_the_bridge":
            PrependDecisionQueue("SETENCOUNTER", $player, "119-BeforeFight");
            break;
          case "Ignore_your_instincts_and_stop_for_the_night": //Tortured Soul
            PrependDecisionQueue("SETENCOUNTER", $player, "122-BeforeFight");
            break;
          case "You_notice_a_mountain_pass_you_can_move_through": //Pass Guardian
            PrependDecisionQueue("SETENCOUNTER", $player, "123-BeforeFight");
            break;
          case "Travel_through_a_thin_ravine": //Club Thug
            PrependDecisionQueue("SETENCOUNTER", $player, "130-BeforeFight");
            break;
          case "Travel_through_a_nearby_cavern": //Azvolai
            PrependDecisionQueue("SETENCOUNTER", $player, "131-BeforeFight");
            break;
          case "Attempt_to_cross_the_river_here": //Bow Fisherman
            PrependDecisionQueue("SETENCOUNTER", $player, "124-BeforeFight");
            break;
          case "Explore_the_cave": //mini-boss
            PrependDecisionQueue("SETENCOUNTER", $player, "500-PickMode");
            break;
          case "Stay_very_briefly_to_stock_up": //Shady Merchant
            PrependDecisionQueue("SETENCOUNTER", $player, "126-BeforeFight");
            break;
          case "Catch_a_ferry_across_the_lake": //Swashbuckler
            PrependDecisionQueue("SETENCOUNTER", $player, "127-BeforeFight");
            break;
          case "Travel_upstream_to_the_nearest_town": //Spectral Image
            PrependDecisionQueue("SETENCOUNTER", $player, "128-BeforeFight");
            break;
          case "Travel_downstream_to_find_a_bridge": //Mindscarred Man
            PrependDecisionQueue("SETENCOUNTER", $player, "129-BeforeFight");
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
  $life = &GetZone($player, "Life");
  array_push($life, 20); //TODO: Base on hero life
  $character = &GetZone($player, "Character");
  $character = explode(" ", $heroFileArray[0]);
  $deck = &GetZone($player, "Deck");
  $deck = explode(" ", $heroFileArray[1]);
  $encounter = &GetZone($player, "Encounter");
  $encounter->hero = $hero;
  }

?>
