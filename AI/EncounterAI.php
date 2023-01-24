<?php

function EncounterAI()
{
  global $currentPlayer, $p2CharEquip, $decisionQueue, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt, $combatChain;
  $currentPlayerIsAI = ($currentPlayer == 2 && IsEncounterAI($p2CharEquip[0])) ? true : false;
  if(!IsGameOver() && $currentPlayerIsAI)
  {
    for($logicCount=0; $logicCount<=10 && $currentPlayerIsAI; ++$logicCount)
    {
      $hand = &GetHand($currentPlayer);
      $character = &GetPlayerCharacter($currentPlayer);
      /*WriteLog($hand[0]);
      WriteLog($hand[1]);
      WriteLog($hand[2]);
      WriteLog($hand[3]);*/
      if(count($decisionQueue) > 0)
      {
        $options = explode(",", $turn[2]);
        ContinueDecisionQueue($options[0]);//Just pick the first option
      }
      else if($turn[0] == "B")
      {
        /*if(count($hand) > 0 && (CachedTotalAttack() - CachedTotalBlock()) > 1)
        {
          $cardToBlock = GetNextBlock($BPV);
          WriteLog($cardToBlock);
          ProcessInput($currentPlayer, 27, "", $cardToBlock, 0, "");
          CacheCombatResult();
        }
        else PassInput();*/
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
        /*$index = -1;
        for($i=0; $i<count($hand) && $index == -1; ++$i) if(CardCost($hand[0]) == 0 && CardType($hand[0]) != "I") $index = $i;
        if($index != -1)
        {
          ProcessInput($currentPlayer, 27, "", 0, $index, "");
          CacheCombatResult();
        }
        else {
          for($i=0; $i<count($character) && $index == -1; $i += CharacterPieces()) if(CardType($character[$i]) != "C") $index = $i;
          if($index != -1)
          {
            ProcessInput($currentPlayer, 3, "", CharacterPieces(), $index, "");
            CacheCombatResult();
          }
          else PassInput();
        }
        /**/
        if(count($hand) > 0) //Are there cards in hand?
        {
          $APV = GenerateAPV($hand, $character);
          $alreadyCheckedHand = 10.0; //larger than the highest possible AP
          $EPV = GenerateEPV($character);
          $alreadyCheckedEquipment = 10.0;
          /*WriteLog("APV:");
          WriteLog($hand[0]);
          WriteLog($APV[0]);
          WriteLog($hand[1]);
          WriteLog($APV[1]);
          WriteLog($hand[2]);
          WriteLog($APV[2]);
          WriteLog($EPV[9]);*/
          for($i = 0; $i < count($EPV); $i += CharacterPieces()) { $totalOptions += 1; }
          for($i = 0; $i < count($hand); ++$i) { $totalOptions +=1; }
          for($i = 0; $i < $totalOptions; ++$i)
          {
            //WriteLog($totalOptions);
            $nextActionIndex = GetNextAction($APV, $alreadyCheckedHand);
            $nextAbilityIndex = GetNextAbility($EPV, $alreadyCheckedEquipment);
            //WriteLog($hand[$nextActionIndex]);
            //WriteLog($APV[$nextActionIndex]);
            //WriteLog($character[$nextAbilityIndex]);
            //WriteLog($EPV[$nextAbilityIndex]);
            if($EPV[$nextAbilityIndex] < $APV[$nextActionIndex])
            {
              WriteLog("checking hand");
              if(IsCardPlayable($hand, $APV, $nextActionIndex)) //Is there enough pitch in hand to play the card?
              {
                //WriteLog("checking pitch, next action:");
                //WriteLog($nextActionIndex);
                //WriteLog($hand[$nextActionIndex]);
                //ProcessInput($currentPlayer, 27, "", 0, $nextActionIndex, "");
                //CacheCombatResult();
                ProcessInput($currentPlayer, 27, "", $nextActionIndex, 0, "");
                CacheCombatResult();
              }
              else $alreadyCheckedHand = $APV[$nextActionIndex];
            }
            else
            {
              if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))
              {
                ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
                CacheCombatResult();
              }
              else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
            }
          }
          $alreadyCheckedEquipment = 10.0;
          for($i = 0; $i < count($EPV); $i += CharacterPieces())
          {
            if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))
            {
              ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
              CacheCombatResult();
            }
            else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
          }
          PassInput();
        }
        else
        {
          $EPV = GenerateEPV($character);
          $alreadyCheckedEquipment = 10.0;
          for($i = 0; $i < count($EPV); $i += CharacterPieces())
          {
            $nextAbilityIndex = GetNextAbility($EPV, $alreadyCheckedEquipment);
            if(IsEquipmentPlayable($hand, $EPV, $nextAbilityIndex, $character))
            {
              ProcessInput($currentPlayer, 3, "", CharacterPieces(), $nextAbilityIndex, "");
              CacheCombatResult();
            }
            else $alreadyCheckedEquipment = $EPV[$nextAbilityIndex];
          }
          PassInput();
        }
      }
      else if($turn[0] == "A" && $mainPlayer == $currentPlayer)//attack reaction phase
      {
        /*if(count($hand) > 0 && CardCost($hand[0]) == 0 && CardType($hand[0]) == "I")
        {
          ProcessInput($currentPlayer, 27, "", 0, 0, "");
          CacheCombatResult();
        }
        else PassInput();*/
        if(count($hand) > 0)
        {
          $RPV = generateRPV($hand, $character);
          $alreadyChecked = 10.0;
          for($i = 0; $i < count($hand); ++$i)
          {
            $cardIndex = GetNextReaction($RPV, $alreadyChecked);
            if(IsCardPlayable($hand, $RPV, $cardIndex)) //Is there enough pitch in hand to play the card?
            {
              ProcessInput($currentPlayer, 27, "", 0, $cardIndex, "");
              CacheCombatResult();
            }
            else $alreadyChecked = $APV[$cardIndex];
          }
        }
        PassInput();
      }
      else if($turn[0] == "P" && $mainPlayer == $currentPlayer)//pitch phase
      {
        //WriteLog("checking the pitch step");
        if(count($hand) > 0)
        {
          $PPV = GeneratePPV($hand, $character);
          $cardToPitch = GetNextPitch($PPV);
          /*WriteLog("checking pitch, this is the hand");
          WriteLog($hand[0]);
          WriteLog($hand[1]);
          WriteLog($hand[2]);
          WriteLog($hand[3]);
          WriteLog("Index of card pitched and what that is");
          WriteLog($hand[$cardToPitch]);
          WriteLog($cardToPitch);*/
          if($PPV[$cardToPitch] != 0)
          {
            //ProcessInput($currentPlayer, 27, "", 0, $cardToPitch, "");
            //CacheCombatResult();
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
  if($APV[$playIndex] != 0)
  {
    $totalPitch = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      if($i != $playIndex)
      {
        $totalPitch = $totalPitch + PitchValue($hand[$i]);
      }
    }
    return CardCost($hand[$playIndex]) <= $totalPitch;
    //return false;
  }
  else return false;
}

function isEquipmentPlayable($hand, $EPV, $playIndex, $character)
{
  if($EPV[$playIndex] != 0)
  {
    $totalPitch = 0;
    for($i = 0; $i < count($hand); ++$i)
    {
      $totalPitch = $totalPitch + PitchValue($hand[$i]);
    }
    return AbilityCost($character[$playIndex]) <= $totalPitch;
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
  $ten = false;
  $eleven = false;
  $BPV = [];
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($BPV, BlockPriority($hand[$i], $character[0]));
    if(10.1 <= $BPV[$i] && $BPV[$i] <= 10.9) { $ten = true; }
    if(11.1 <= $BPV[$i] && $BPV[$i] <= 11.9) { $eleven = true; }
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
  $APV = [];
  for($i = 0; $i < count($hand); ++$i)
  {
    array_push($APV, ActionPriority($hand[$i], $character[0]));
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
    default: return 0;
  }
}

/*
Action Priority works out as following:
0 -> Can't play the card as an action
0.1 -> 1.9 Will be played. Higher cards played first
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
    default: return 0;
  }
}


?>
