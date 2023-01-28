<?php

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $mainPlayer, $mainPlayerGamestateStillBuilt, $combatChain;
  $currentPlayerIsAI = ($currentPlayer == 2 && IsEncounterAI($p2CharEquip[0])) ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    $isBowActive = false;
    for($logicCount=0; $logicCount<=10 && $currentPlayerIsAI; ++$logicCount)
    {
      global $turn;
      $hand = &GetHand($currentPlayer);
      $character = &GetPlayerCharacter($currentPlayer);
      $arsenal = &GetArsenal($currentPlayer);
      $resources = &GetResources($currentPlayer);
      /*WriteLog("hand[0] = " . $hand[0]);
      WriteLog("hand[1] = " . $hand[1]);
      WriteLog("hand[2] = " . $hand[2]);
      WriteLog("character[0] = " . $character[0]);
      WriteLog("resources[0] = " . $resources[0])0;*/
      if(count($decisionQueue) > 0)
      {
        if($isBowActive)//was the last action a bow action?
        {
          $optionIndex = 0;
          $index = 0;
          $largestIndex = 0;

          for($i = 0; $i < count($hand); ++$i)//find the highest priority arrow and choose it
          {
            if(CardSubtype($hand[0]) == "Arrow")
            {
              if(FromArsenalActionPriority($hand[$largestIndex], $character[0]) <= FromArsenalActionPriority($hand[$i], $character[0]))
              {
                $largestIndex = $i;
                $optionIndex = $index;
              }
              ++$index;
            }
          }
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[$optionIndex]);
        }
        else
        {
          $options = explode(",", $turn[2]);
          ContinueDecisionQueue($options[0]);//Just pick the first option
        }
      }
      else if($turn[0] == "B")//The player is attacking the AI
      {
        if(count($hand) > 0) //are there cards in hand?
        {
          $BPV = GenerateBPV($hand, $character);
          $health = &GetHealth($currentPlayer);
          $cardToBlock = GetNextBlock($BPV); //what am I blocking with next?
          if($BPV[$cardToBlock] !=0 ) //do I have a card in hand that can block?
          {
            if(count($hand) > 0 && CachedTotalAttack() - CachedTotalBlock() >= $health) //Is it lethal?
            {
              ProcessInput($currentPlayer, 27, "", $cardToBlock, 0, "");
              CacheCombatResult();
            }
            else if(count($hand) > 0 && CachedTotalAttack() - CachedTotalBlock() >= BlockValue($hand[$cardToBlock]) && 2.1 <= $BPV[$cardToBlock] && $BPV[$cardToBlock] <= 2.9) //Is it an efficient block with a card that desires to be blocked with?
            {
              ProcessInput($currentPlayer, 27, "", $cardToBlock, 0, "");
              CacheCombatResult();
            }
          }
          else PassInput();
        }
        else PassInput();
      }
      else if($turn[0] == "M" && $mainPlayer == $currentPlayer)//AIs turn
      {
        if(count($hand) > 0) //Are there cards in hand?
        {
          $APV = GenerateAPV($hand, $character);
          $alreadyCheckedHand = 10.0; //larger than the highest possible AP
          $EPV = GenerateEPV($character);
          $alreadyCheckedEquipment = 10.0; //larger than the highest possible AP
          $arsePV = FromArsenalActionPriority($arsenal[0], $character[0]);
          /*WriteLog("APV[0] = " . $APV[0]);
          WriteLog("APV[1] = " . $APV[1]);
          WriteLog("APV[2] = " . $APV[2]);*/
          for($i = 0; $i < count($EPV); $i += CharacterPieces()) { $totalOptions += 1; } //available equipment
          for($i = 0; $i < count($hand); ++$i) { $totalOptions +=1; } //available hand
          $totalOptions +=1; //available arsenal
          for($i = 0; $i < $totalOptions; ++$i)
          {
            $nextActionIndex = GetNextAction($APV, $alreadyCheckedHand);
            $nextAbilityIndex = GetNextAbility($EPV, $alreadyCheckedEquipment);
            //WriteLog($hand[$nextActionIndex] . " " . $APV[$nextActionIndex]);
            //WriteLog($character[$nextAbilityIndex] . " " . $EPV[$nextActionIndex]);
            if($arsePV >= $EPV[$nextAbilityIndex] && $arsePV >= $APV[$nextActionIndex]) //If the arsenal has the highest priority
            {
              if(IsArsenalPlayable($hand, $arsenal, $arsePV)) //and if it's playable
              {
                ProcessInput($currentPlayer, 5, "", 0, 0, "");
                CacheCombatResult();
                $cardPlayed = true;
                break;
              }
            }
            else
            {
              if($EPV[$nextAbilityIndex] < $APV[$nextActionIndex]) //if the next highest card is higher than the next highest weapon
              {
                if(IsCardPlayable($hand, $APV, $nextActionIndex)) //Is there enough pitch in hand to play the card?
                {
                  ProcessInput($currentPlayer, 27, "", $nextActionIndex, 0, "");
                  CacheCombatResult();
                  $cardPlayed = true;
                  break;
                }
                else $alreadyCheckedHand = $APV[$nextActionIndex];
              }
              else //if the next highest equipment is higher than the next highest weapon
              {
                if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))//Is there enough pitch in hand to play the equipment?
                {
                  if(CardSubtype($character[$nextAbilityIndex]) == "Bow" ) { $isBowActive = true; }
                  ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
                  CacheCombatResult();
                }
                else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
              }
            }
          } //NOTE TO SELF: TRY REMOVING THIS NEXT SEGMENT, I DON'T ACTUALLY KNOW IF IT'S NEEDED
          $alreadyCheckedEquipment = 10.0;
          for($i = 0; $i < count($EPV); $i += CharacterPieces())
          {
            if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))
            {
              if(CardSubtype($character[$nextAbilityIndex]) == "Bow" ) { $isBowActive = true; }
              ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
              CacheCombatResult();
            }
            else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
          }
          if($cardPlayed) continue;
          PassInput();
        }
        else //no cards in hand. does the same as above without checking hand
        {
          $EPV = GenerateEPV($character);
          $alreadyCheckedEquipment = 10.0;
          $arsePV = FromArsenalActionPriority($arsenal[0], $character[0]);
          $totalOptions = 1;
          for($i = 0; $i < count($EPV); $i += CharacterPieces()) { $totalOptions += 1; }
          for($i = 0; $i < $totalOptions; ++$i)
          {
            $nextAbilityIndex = GetNextAbility($EPV, $alreadyCheckedEquipment);
            if($arsePV >= $EPV[$nextAbilityIndex])
            {
              if(IsArsenalPlayable($hand, $arsenal, $arsePV))
              {
                ProcessInput($currentPlayer, 5, "", 0, 0, "");
                CacheCombatResult();
                $cardPlayed = true;
                break;
              }
            }
            else
            {
              if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))
              {
                if(CardSubtype($character[$nextAbilityIndex]) == "Bow" ) { $isBowActive = true; }
                ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
                CacheCombatResult();
              }
              else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
            }
          }
          if($cardPlayed) continue;
          PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)//attack reaction phase
      {
        $arsePV = FromArsenalReactionPriority($arsenal[0], $character[0]);
        if(count($hand) > 0) //if there is a card in hand
        {
          $RPV = generateRPV($hand, $character);
          $alreadyChecked = 10.0;
          for($i = 0; $i < count($hand)+1; ++$i) //for each card in hand and in the arsenal, check them
          {
            $cardIndex = GetNextReaction($RPV, $alreadyChecked);
            if($arsePV >= $RPV[$cardIndex]) //if the arsenal has a higher priority
            {
              if(IsArsenalPlayable($hand, $arsenal, $arsePV)) //and it's playable
              {
                ProcessInput($currentPlayer, 5, "", 0, 0, "");
                CacheCombatResult();
              }
            }
            else //if the next card in hand has a higher priority
            {
              if(IsCardPlayable($hand, $RPV, $cardIndex)) //Is there enough pitch in hand to play the card?
              {
                ProcessInput($currentPlayer, 27, "", $cardIndex, 0, "");
                CacheCombatResult();
              }
              else $alreadyChecked = $APV[$cardIndex];
            }
          }
        }
        if(IsArsenalPlayable($hand, $arsenal, $arsePV)) //if there's no hand, it'll play the arsenal instead, if it can
        {
          ProcessInput($currentPlayer, 5, "", 0, 0, "");
          CacheCombatResult();
        }
        PassInput();
      }
      else if($turn[0] == "P" && $mainPlayer == $currentPlayer)//pitch phase
      {
        if(count($hand) > 0) //if there are cards in hand
        {
          $PPV = GeneratePPV($hand, $character);
          $cardToPitch = GetNextPitch($PPV);
          if($PPV[$cardToPitch] != 0) //choose the biggest pitch priority and pitch it if able
          {
            ProcessInput($currentPlayer, 27, "", $cardToPitch, 0, "");
            CacheCombatResult();
          }
          else PassInput();
        }
        else PassInput();
      }
      else if($turn[0] == "PDECK" && $mainPlayer == $currentPlayer)//choosing which card to bottom from pitch
      {
        $pitch = &GetPitch($currentPlayer);
        ProcessInput($currentPlayer, 6, "", $pitch[0], 0, "");
        CacheCombatResult();
      }
      else if($turn[0] == "ARS" && $mainPlayer = $currentPlayer)//choose a card to arsenal
      {
        if(count($hand) > 0)
        {
          $index = 0;
          for($i = 0; $i < count($hand); ++$i)
          {
            if(ToArsenalPriority($hand[$index], $character[0]) <= ToArsenalPriority($hand[$i], $character[0])) { $index = $i; }
          }
          if(ToArsenalPriority($hand[$index], $character[0]) != 0)
          {
            ProcessInput($currentPlayer, 4, "", $hand[$index], 0, "");
            CacheCombatResult();
          }
          else PassInput();
        }
      }
      else
      {
        PassInput();
      }
      ProcessMacros();
      $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
      if($logicCount == 10 && $currentPlayerIsAI)
      {
        for($i=0; $i<=10 && $currentPlayerIsAI; ++$i)
        {
          PassInput();
          $currentPlayerIsAI = ($currentPlayer == 2 ? true : false);
        }
      }
    }
  }
}

function IsEncounterAI($enemyHero)
{
  return str_contains($enemyHero, "ROGUE");
}

function IsCardPlayable($hand, $APV, $playIndex)
{
  global $currentPlayer;
  if($APV[$playIndex] != 0)
  {
    $resources = &GetResources($currentPlayer);
    $totalPitch = $resources[0];
    for($i = 0; $i < count($hand); ++$i)
    {
      if($i != $playIndex)
      {
        $totalPitch = $totalPitch + PitchValue($hand[$i]);
      }
    }
    return CardCost($hand[$playIndex]) <= $totalPitch;
  }
  else return false;
}

function isEquipmentPlayable($hand, $EPV, $playIndex, $character)
{
  global $currentPlayer;
  if($EPV[$playIndex] != 0)
  {
    $resources = &GetResources($currentPlayer);
    $totalPitch = $resources[0];
    for($i = 0; $i < count($hand); ++$i)
    {
      $totalPitch = $totalPitch + PitchValue($hand[$i]);
    }
    return AbilityCost($character[$playIndex]) <= $totalPitch;
  }
  else return false;
}

function IsArsenalPlayable($hand, $arsenal, $arsePV)
{
  global $currentPlayer;
  if($arsePV != 0)
  {
    $resources = &GetResources($currentPlayer);
    $totalPitch = $resources[0];
    for($i = 0; $i < count($hand); ++$i)
    {
      $totalPitch = $totalPitch + PitchValue($hand[$i]);
    }
    return CardCost($arsenal[0]) <= $totalPitch;
  }
  else return false;
}

function GetNextBlock($BPV)
{
  $index = 0;
  for($i = 0; $i < count($BPV); ++$i)
  {
    if($BPV[$index] <= $BPV[$i]) { $index = $i; }
  }
  return $index;
}

//prerequisite: count($hand) > 0
function GenerateBPV($hand, $character)
{
  global $currentTurn;
  $ten = false;
  $eleven = false;
  $BPV = [];
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($BPV, BlockPriority($hand[$i], $character[0]));
    if(10.1 <= $BPV[$i] && $BPV[$i] <= 10.9) { $ten = true; }
    if(11.1 <= $BPV[$i] && $BPV[$i] <= 11.9) { $eleven = true; }
    if($BPV[$i] != 0 && $currentTurn == 1) { $BPV[$i] = 2.9; }
  }

  if($ten)
  {
    $index = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      if($BPV[$index] <= $BPV[$i] && 10.1 <= $BPV[$i] && $BPV[$i] <= 10.9) { $index = $i; }
    }
    $BPV[$index] = $BPV[$index]-10;
    for($i = 0; $i < count($hand); ++$i)
    {
      if(10.1 <= $BPV[$i] && $BPV[$i] <= 10.9) { $BPV[$i] = $BPV[$i]-8; }
    }
  }

  if($eleven)
  {
    $index = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      if($BPV[$index] <= $BPV[$i] && 11.1 <= $BPV[$i] && $BPV[$i] <= 11.9) { $index = $i; }
    }
    $BPV[$index] = $BPV[$index]-11;
    for($i = 0; $i < count($hand); ++$i)
    {
      if(11.1 <= $BPV[$i] && $BPV[$i] <= 11.9) { $BPV[$i] = $BPV[$i]-9; }
    }
  }
  return $BPV;
}

function GetNextAction($APV, $alreadyChecked)
{
  $index = 0;
  for($i = 0; $i < count($APV); ++$i)
  {
    if($APV[$index] <= $APV[$i] && $APV[$i] < $alreadyChecked) { $index = $i; }
  }
  return $index;
}

function GenerateAPV($hand, $character)
{
  $ten = false;
  $APV = [];
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($APV, ActionPriority($hand[$i], $character[0]));
    if(10.1 <= $APV[$i] && $APV[$i] <= 10.9) { $ten = true; }
  }
  if($ten)
  {
    $index = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      if($APV[$index] <= $APV[$i] && 10.1 <= $APV[$i] && $APV[$i] <= 10.9) { $index = $i; }
    }
    $APV[$index] = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      if(10.1 <= $APV[$i] && $APV[$i] <= 10.9) { $APV[$i] = $APV[$i]-9; }
    }
  }
  return $APV;
}

function GetNextAbility($EPV, $alreadyChecked)
{
  $index = 0;
  for($i = 0; $i < count($EPV); $i += CharacterPieces())
  {
    if($EPV[$index] <= $EPV[$i] && $EPV[$i] < $alreadyChecked) { $index = $i; }
  }
  return $index;
}

function GenerateEPV($character)
{
  $EPV = [];
  for($i = 0; $i < count($character); ++$i)
  {
    array_push($EPV, ActionPriority($character[$i], $character[0]));
    if($character[$i+1] != 2) { $EPV[$i] = 0; }
  }
  return $EPV;
}

function GetNextReaction($RPV, $alreadyChecked)
{
  $index = 0;
  for($i = 0; $i < count($RPV); $i += CharacterPieces())
  {
    if($RPV[$index] <= $RPV[$i] && $RPV[$i] < $alreadyChecked) { $index = $i; }
  }
  return $index;
}

function GenerateRPV($hand, $character)
{
  $RPV = [];
  $type = CardType($combatChain[0]);
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($RPV, ReactionPriority($hand[$i], $character[0]));
    if($type == "W" && 2.1 <= $RPV[$i] && $RPV[$i] <= 2.9) { $RPV[$i] = 0; }
    if($type == "A" && 1.1 <= $RPV[$i] && $RPV[$i] <= 1.9) { $RPV[$i] = 0; }
    if(10.1 <= $RPV[$i] && $RPV[$i] <= 10.9) { $RPV[$i] = $RPV[$i] - 10; }
  }
  return $RPV;
}

function GetNextPitch($PPV)
{
  $index = 0;
  for($i = 0; $i < count($PPV); ++$i)
  {

    if($PPV[$index] <= $PPV[$i]) { $index = $i; }
  }
  return $index;
}

function GeneratePPV($hand, $character)
{
  $PPV = [];
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($PPV, PitchPriority($hand[$i], $character[0]));
  }
  return $PPV;
}

/*
Block Priority works out as following:
0 -> Can't block with, even if lethal
0.1-0.9 -> don't block, unless lethal
1.1-1.9 -> don't block, can be thrown in front of an on hit effect (not implemented)
2.1-2.9 -> willing to be blocked with
10.1-10.9 -> One of these won't be blocked with per hand. The rest will be blocked with.
11.1-11.9 -> One of these won't be blocked with per hand. The rest will be blocked with.
*/

function BlockPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 2.3;
        case "MON285": return 2.5;
        case "MON286": return 2.7;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 10.5;
        case "WTR178": return 11.5;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0.1;
        case "ARC192": return 0.2;
        case "ARC193": return 0.3;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0.2;
          case "ELE183": return 0;
          case "ELE184": return 0;
          case "ELE185": return 0;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 10.3;
          case "WTR100": return 10.5;
          case "EVR043": return 10.4;
          case "WTR103": return 10.6;
          case "CRU063": return 11.2;
          case "CRU073": return 11.1;
          case "WTR082": return 0.1;
          case "CRU074": return 0.1;
          case "WTR209": return 0.3;
          case "WTR098": return 0.4;
          case "WTR099": return 0.8;
          case "EVR041": return 0.4;
          case "EVR042": return 0.8;
          case "WTR101": return 0.6;
          case "WTR102": return 0.9;
          case "CRU072": return 0.4;
          default: return 0;
        }
      }
    case "ROGUE009":
      {
        switch($cardId)
        {
          case "ARC045": return 0.1;
          case "ARC069": return 0.2;
          case "ARC054": return 0.5;
          case "EVR091": return 0.3;
          case "EVR100": return 0.4;
          case "WTR218": return 0.6;
          default: return 0;
        }
      }
    case "ROGUE010":
      {
        switch($cardId)
        {
          case "ARC106": return 0.4;
          case "ARC107": return 0.6;
          case "ARC108": return 0.5;
          case "EVR107": return 0.45;
          case "EVR108": return 0.65;
          case "EVR109": return 0.55;
          case "EVR113": return 0.1;
          case "EVR114": return 0.3;
          case "EVR115": return 0.2;
          case "ARC085": return 0.15;
          case "ARC086": return 0.35;
          case "ARC087": return 0.25;
        }
      }
    default: return 0;
  }
}

/*
Action Priority works out as following:
0 -> Can't play the card as an action
0.1 -> 1.9 Will be played. Higher cards played first
10.1 -> 10.9 One of these will not be played. The remainder will attempt to be played. This allows for fusion and guarenteed arsenal
*/

function ActionPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 1.8;
        case "MON285": return 1.6;
        case "MON286": return 1.4;
        case "ROGUE002": return 0.1;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 1.5;
        case "WTR178": return 0.8;
        case "ROGUE005": return 0.6;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0.3;
        case "ARC192": return 0.2;
        case "ARC193": return 0.1;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0.5;
          case "ELE183": return 0;
          case "ELE184": return 0;
          case "ELE185": return 0;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 0.4;
          case "WTR100": return 1.1;
          case "EVR043": return 0.7;
          case "WTR103": return 1.4;
          case "CRU063": return 0.5;
          case "CRU073": return 0.6;
          case "WTR082": return 0;
          case "CRU074": return 1.5;
          case "WTR209": return 0;
          case "WTR098": return 1.3;
          case "WTR099": return 1.2;
          case "EVR041": return 0.9;
          case "EVR042": return 0.8;
          case "WTR101": return 1.8;
          case "WTR102": return 1.7;
          case "CRU072": return 1.6;
          case "CRU050": return 1.9;
          default: return 0;
        }
      }
    case "ROGUE009":
      {
        switch($cardId)
        {
          case "ARC045": return 0;
          case "ARC069": return 0;
          case "ARC054": return 1.9;
          case "EVR091": return 1.8;
          case "EVR100": return 1.8;
          case "WTR218": return 1.7;
          case "CRU121": return 1.4;
          default: return 0;
        }
      }
    case "ROGUE010":
      {
        switch($cardId)
        {
          case "ARC106": return 1.9;
          case "ARC107": return 1.7;
          case "ARC108": return 0.6;
          case "EVR107": return 1.8;
          case "EVR108": return 1.6;
          case "EVR109": return 0.5;
          case "EVR113": return 1.5;
          case "EVR114": return 1.3;
          case "EVR115": return 0.8;
          case "ARC085": return 1.4;
          case "ARC086": return 1.3;
          case "ARC087": return 0.7;
        }
      }
    default: return 0;
  }
}

/*
Reaction Priority works out as following:
0 -> Can't play the card as a reaction
1.1 -> 1.9 Will play to a weapon chain link (do not put an AR in this number if it can't be played to the weapon the hero has)
2.1 -> 2.9 Will play to an attack action chain link (do not put an AR in this number if it can't be played to at least one attack in the deck (ie: don't put razor here if it there is even a single 2+ cost attack))
10.1 -> 10.9 Will play to either a weapon or an attack chain link (If there is a single card in the deck that can't be targeted, don't include the AR in this section) (It will prioritize more restrictive ARs if possible)
*/

function ReactionPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 0;
        case "MON285": return 0;
        case "MON286": return 0;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 0;
        case "WTR178": return 0;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0;
        case "ARC192": return 0;
        case "ARC193": return 0;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0;
          case "ELE183": return 2.5;
          case "ELE184": return 2.3;
          case "ELE185": return 2.1;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 0;
          case "WTR100": return 0;
          case "EVR043": return 0;
          case "WTR103": return 0;
          case "CRU063": return 0;
          case "CRU073": return 0;
          case "WTR082": return 2.9;
          case "CRU074": return 0;
          case "WTR209": return 1.9;
          case "WTR098": return 0;
          case "WTR099": return 0;
          case "EVR041": return 0;
          case "EVR042": return 0;
          case "WTR101": return 0;
          case "WTR102": return 0;
          case "CRU072": return 0;
          default: return 0;
        }
      }
    default: return 0;
  }
}

/*
Pitch Priority works out as following:
0 -> Can't pitch
0.1 -> 0.9 Red cards, will pitch last
1.1 -> 1.9 Yellow cards, will pitch second
2.1 -> 2.9 Blue cards, will pitch third
NOTE: there is no mechanical differences between 0.x, 1.x, and 2.x. It is entirely seperated for organizational reasons
*/

function PitchPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 0.5;
        case "MON285": return 1.5;
        case "MON286": return 2.5;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 0.5;
        case "WTR178": return 2.5;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0.1;
        case "ARC192": return 0.2;
        case "ARC193": return 0.3;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 2.6;
          case "ELE183": return 0.5;
          case "ELE184": return 1.5;
          case "ELE185": return 2.5;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 2.9;
          case "WTR100": return 2.8;
          case "EVR043": return 2.7;
          case "WTR103": return 2.6;
          case "CRU063": return 0.9;
          case "CRU073": return 1.2;
          case "WTR082": return 0.4;
          case "CRU074": return 1.1;
          case "WTR209": return 0.8;
          case "WTR098": return 0.7;
          case "WTR099": return 1.7;
          case "EVR041": return 0.6;
          case "EVR042": return 1.6;
          case "WTR101": return 0.5;
          case "WTR102": return 1.5;
          case "CRU072": return 1.4;
          default: return 0;
        }
      }
    case "ROGUE010":
      {
        switch($cardId)
        {
          case "ARC106": return 0.2;
          case "ARC107": return 1.2;
          case "ARC108": return 2.2;
          case "EVR107": return 0.3;
          case "EVR108": return 1.3;
          case "EVR109": return 2.3;
          case "EVR113": return 0.4;
          case "EVR114": return 1.4;
          case "EVR115": return 2.4;
          case "ARC085": return 0.5;
          case "ARC086": return 1.5;
          case "ARC087": return 2.5;
        }
      }
    default: return 0;
  }
}

/*
To Arsenal Priority works out as following:
0 -> Will never arsenal this card (stops things like resources from being put in Arsenal)
0.1 -> 1.9 Will arsenal this card. Higher values take precedent
*/

function ToArsenalPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 1.8;
        case "MON285": return 1.6;
        case "MON286": return 1.4;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 1.5;
        case "WTR178": return 0.8;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0.3;
        case "ARC192": return 0.2;
        case "ARC193": return 0.1;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0.5;
          case "ELE183": return 0.4;
          case "ELE184": return 0.3;
          case "ELE185": return 0.2;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 0.4;
          case "WTR100": return 1.1;
          case "EVR043": return 0.7;
          case "WTR103": return 1.4;
          case "CRU063": return 0.5;
          case "CRU073": return 0.6;
          case "WTR082": return 0.1;
          case "CRU074": return 1.5;
          case "WTR209": return 0.2;
          case "WTR098": return 1.3;
          case "WTR099": return 1.2;
          case "EVR041": return 0.9;
          case "EVR042": return 0.8;
          case "WTR101": return 1.8;
          case "WTR102": return 1.7;
          case "CRU072": return 1.6;
          default: return 0;
        }
      }
    case "ROGUE009":
      {
        switch($cardId)
        {
          case "ARC045": return 1.5;
          case "ARC069": return 1.4;
          case "ARC054": return 1.9;
          case "EVR091": return 1.8;
          case "EVR100": return 1.7;
          case "WTR218": return 1.6;
          default: return 0;
        }
      }
    default: return 0;
  }
}

/*
From Arsenal Action Priority works out as following:
0 -> Will not or cannot play this card from the arsenal (reactions get 0, wrong phase to play them)
0.1 -> 1.9 Will play this card from arsenal during the main phase (It will prioritize cards from arsenal with the same priority value as cards from hand or equipment)
*/

function FromArsenalActionPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 1.8;
        case "MON285": return 1.6;
        case "MON286": return 1.4;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 1.5;
        case "WTR178": return 0.8;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0.3;
        case "ARC192": return 0.2;
        case "ARC193": return 0.1;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0.5;
          case "ELE183": return 0;
          case "ELE184": return 0;
          case "ELE185": return 0;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 0.4;
          case "WTR100": return 1.1;
          case "EVR043": return 0.7;
          case "WTR103": return 1.4;
          case "CRU063": return 0.5;
          case "CRU073": return 0.6;
          case "WTR082": return 0;
          case "CRU074": return 1.5;
          case "WTR209": return 0;
          case "WTR098": return 1.3;
          case "WTR099": return 1.2;
          case "EVR041": return 0.9;
          case "EVR042": return 0.8;
          case "WTR101": return 1.8;
          case "WTR102": return 1.7;
          case "CRU072": return 1.6;
          default: return 0;
        }
      }
    case "ROGUE009":
      {
        switch($cardId)
        {
          case "ARC045": return 1.5;
          case "ARC069": return 1.4;
          case "ARC054": return 1.9;
          case "EVR091": return 1.8;
          case "EVR100": return 1.7;
          case "WTR218": return 1.6;
          default: return 0;
        }
      }
    default: return 0;
  }
}

/*
From Arsenal Reaction Priority works out as following: (It's exactly the same as from hand, but it will prioritize arsenal over hand if they have the same values)
0 -> Can't play the card as a reaction
1.1 -> 1.9 Will play to a weapon chain link (do not put an AR in this number if it can't be played to the weapon the hero has)
2.1 -> 2.9 Will play to an attack action chain link (do not put an AR in this number if it can't be played to at least one attack in the deck (ie: don't put razor here if it there is even a single 2+ cost attack))
10.1 -> 10.9 Will play to either a weapon or an attack chain link (If there is a single card in the deck that can't be targeted, don't include the AR in this section) (It will prioritize more restrictive ARs if possible)
*/

function FromArsenalReactionPriority($cardId, $heroId)
{
  switch($heroId)
  {
    case "ROGUE001":
      switch($cardId)
      {
        case "MON284": return 0;
        case "MON285": return 0;
        case "MON286": return 0;
        default: return 0;
      }
    case "ROGUE004":
      switch($cardId)
      {
        case "WTR176": return 0;
        case "WTR178": return 0;
        default: return 0;
      }
    case "ROGUE003":
      switch($cardId)
      {
        case "ARC191": return 0;
        case "ARC192": return 0;
        case "ARC193": return 0;
        default: return 0;
      }
    case "ROGUE006":
      {
        switch($cardId)
        {
          case "ELE197": return 0;
          case "ELE183": return 2.5;
          case "ELE184": return 2.3;
          case "ELE185": return 2.1;
          default: return 0;
        }
      }
    case "ROGUE008":
      {
        switch($cardId)
        {
          case "CRU065": return 0;
          case "WTR100": return 0;
          case "EVR043": return 0;
          case "WTR103": return 0;
          case "CRU063": return 0;
          case "CRU073": return 0;
          case "WTR082": return 2.9;
          case "CRU074": return 0;
          case "WTR209": return 1.9;
          case "WTR098": return 0;
          case "WTR099": return 0;
          case "EVR041": return 0;
          case "EVR042": return 0;
          case "WTR101": return 0;
          case "WTR102": return 0;
          case "CRU072": return 0;
          default: return 0;
        }
      }
    default: return 0;
  }
}
?>
