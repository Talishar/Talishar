<?php

function PlayAura($cardID, $player, $number=1)
{
  $auras = &GetAuras($player);
  if($cardID == "ARC112" && SearchCurrentTurnEffects("ARC081", $player)) ++$number;
  if($cardID == "MON104")
  {
    $index = SearchArsenalReadyCard($player, "MON404");
    if($index > -1) TheLibrarianEffect($player, $index);
  }
  for($i=0; $i<$number; ++$i)
  {
    array_push($auras, $cardID);
    array_push($auras, 2);
    array_push($auras, AuraPlayCounters($cardID));
    array_push($auras, 0);
  }
}

function PlayMyAura($cardID)
{
  global $myAuras;
  array_push($myAuras, $cardID);
  array_push($myAuras, 2);
  array_push($myAuras, AuraPlayCounters($cardID));
  array_push($myAuras, 0);
}

function PlayTheirAura($cardID)
{
  global $theirAuras;
  array_push($theirAuras, $cardID);
  array_push($theirAuras, 2);
  array_push($theirAuras, AuraPlayCounters($cardID));
  array_push($theirAuras, 0);
}

function AuraPlayCounters($cardID)
{
  switch($cardID)
  {
    case "CRU075": return 1;
    default: return 0;
  }
}

function DestroyAura($player, $index)
{
  $auras = &GetAuras($player);
  for($j = $index+AuraPieces()-1; $j >= $index; --$j)
  {
    unset($auras[$j]);
  }
  $auras = array_values($auras);
}

function AuraCostModifier()
{
  global $currentPlayer, $otherPlayer;
  $myAuras = &GetAuras($currentPlayer);
  $theirAuras = &GetAuras($otherPlayer);
  $modifier = 0;
  for($i=count($myAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($myAuras[$i])
    {
      case "ELE111": $modifier += 1; $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      AuraDestroyed($currentPlayer, $myAuras[$i]);
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($myAuras[$j]);
      }
      $myAuras = array_values($myAuras);
    }
  }

  for($i=count($theirAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($theirAuras[$i])
    {
      case "ELE146": $modifier += 1; break;
      default: break;
    }
    if($remove == 1)
    {
      AuraDestroyed($otherPlayer, $theirAuras[$i]);
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($theirAuras[$j]);
      }
      $theirAuras = array_values($theirAuras);
    }
  }
  return $modifier;
}

//NOTE: This happens at start of turn, so can't use the my/their directly
function AuraDestroyAbility($cardID)
{
  global $mainPlayer;
  switch($cardID)
  {
    case "WTR046": return "Forged for War was destroyed at the beginning of your action phase.";
    case "WTR047": MainDrawCard(); return "Show Time! drew a card.";
    case "WTR054": return BlessingOfDeliveranceDestroy(3);
    case "WTR055": return BlessingOfDeliveranceDestroy(2);
    case "WTR056": return BlessingOfDeliveranceDestroy(1);
    case "WTR069": case "WTR070": case "WTR071": return EmergingPowerDestroy($cardID);
    case "WTR072": case "WTR073": case "WTR074": return "Stonewall Confidence was destroyed at the beginning of your action phase.";
    case "WTR075": AddCurrentTurnEffect($cardID, $mainPlayer); return "Seismic Surge reduces the cost of the next Guardian attack action card you play this turn by 1.";
    case "ARC162": return "Chains of Eminence was destroyed at the beginning of your action phase.";
    case "CRU028": return "Stamp Authority is destroyed at the beginning of your action phase.";
    case "CRU029": case "CRU030": case "CRU031": AddCurrentTurnEffect($cardID, $mainPlayer); return "Towering Titan gives your next Guardian Attack Action +" . EffectAttackModifier($cardID) . ".";
    case "CRU038": case "CRU039": case "CRU040": AddCurrentTurnEffect($cardID, $mainPlayer); return "Emerging Dominance gives your next Guardian Attack Action +" . EffectAttackModifier($cardID) . " and dominate.";
    case "CRU144": return "Runeblood Barrier is destroyed at the beginning of your action phase.";
    case "ELE025": case "ELE026": case "ELE027": AddCurrentTurnEffect($cardID, $mainPlayer); return "Emerging Avalanche gives your next Attack Action +" . EffectAttackModifier($cardID) . ".";
    case "ELE028": case "ELE029": case "ELE030": AddCurrentTurnEffect($cardID, $mainPlayer); return "Strength of Sequoia gives your next Attack Action +" . EffectAttackModifier($cardID) . ".";
    case "ELE109": return "Embodiment of Earth is destroyed at the beginning of your action phase.";
    case "ELE206": case "ELE207": case "ELE208": AddCurrentTurnEffect($cardID, $mainPlayer); return "Embolden gives your next Guardian Attack Action card +" . EffectAttackModifier($cardID) . ".";
    default: return "";
  }
}

function AuraStartTurnAbilities()
{
  global $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $dest = AuraDestroyAbility($auras[$i]);
    switch($auras[$i])
    {
      case "MON186": SoulShackleStartTurn($mainPlayer); break;
      case "MON006": GenesisStartTurnAbility(); break;
      case "CRU075": if($auras[$i+2] == 0) { $dest = "Zen State is destroyed."; } else { --$auras[$i+2]; } break;
      default: break;
    }
    if($dest != "")
    {
      WriteLog($dest);
      AuraDestroyed($mainPlayer, $auras[$i]);
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($auras[$j]);
      }
      $auras = array_values($auras);
    }
  }
}


function AuraBeginEndStepAbilities()
{
  global $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($auras[$i])
    {
      case "ELE117":
        $pitchCount = SearchCount(SearchPitch($mainPlayer, "", "", -1, -1, "", "EARTH"));
        ++$auras[$i+2];
        WriteLog("Channel Mount Heroic has " . $auras[$i+2] . " flow counters, and you have " . $pitchCount . " earth cards in your pitch zone.");
        if($pitchCount < $auras[$i+2]) $remove = 1;
        break;
      case "ELE146": ++$auras[$i+2]; if(SearchCount(SearchPitch($mainPlayer, "", "", -1, -1, "", "ICE")) < $auras[$i+2]) $remove = 1; break;
      case "ELE175": ++$auras[$i+2]; if(SearchCount(SearchPitch($mainPlayer, "", "", -1, -1, "", "LIGHTNING")) < $auras[$i+2]) $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      AuraDestroyed($mainPlayer, $auras[$i]);
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($auras[$j]);
      }
    }
  }
  $auras = array_values($auras);
}

function AuraEndTurnAbilities()
{
  global $mainClassState, $CS_NumNonAttackCards, $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($auras[$i])
    {
      case "ARC167": case "ARC168": case "ARC169": if(GetClassState($mainPlayer, $CS_NumNonAttackCards) == 0) { $remove = 1; } break;
      case "ELE111": $remove = 1; break;
      case "ELE226": $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      AuraDestroyed($mainPlayer, $auras[$i]);
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($auras[$j]);
      }
      $auras = array_values($auras);
    }
  }
}

function AuraTakeDamageAbilities($player, $damage)
{
  $Auras = &GetAuras($player);
  $hasRunebloodBarrier = CountAura("CRU144", $player) > 0;
  for($i=count($Auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    if($damage <= 0) { $damage = 0; break; }
    switch($Auras[$i])
    {
      case "ARC112": if($hasRunebloodBarrier) { $damage -= 1; $remove = 1; } break;
      case "ARC167": $damage -= 4; $remove = 1; break;
      case "ARC168": $damage -= 3; $remove = 1; break;
      case "ARC169": $damage -= 2; $remove = 1; break;
      case "CRU075": $damage -= 1; break;
      case "MON104": $damage -= 1; $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($Auras[$j]);
      }
      $Auras = array_values($Auras);
    }
  }
  return $damage;
}


function AuraDamageTakenAbilities(&$Auras, $damage)
{
  for($i=count($Auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($Auras[$i])
    {
      case "ARC106": case "ARC107": case "ARC108": $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($Auras[$j]);
      }
      $Auras = array_values($Auras);
    }
  }
  return $damage;
}

function AuraLoseHealthAbilities($player, $amount)
{
  global $mainPlayer;
  $auras = &GetAuras($player);
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($auras[$i])
    {
      case "MON157": if($player == $mainPlayer) { $remove = 1; } break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($auras[$j]);
      }
      $auras = array_values($auras);
    }
  }
  return $amount;
}

function AuraPlayAbilities($cardID, $from)
{
  global $currentPlayer;
  $auras = &GetAuras($currentPlayer);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    switch($auras[$i])
    {
      case "MON157": DimenxxionalCrossroadsPassive($cardID, $from); break;
      default: break;
    }
  }
}

function AuraAttackAbilities($attackID)
{
  global $myAuras, $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain;
  $attackType = CardType($attackID);
  for($i=count($myAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($myAuras[$i])
    {
      case "WTR225": if($attackType == "AA" || $attackType == "W") { WriteLog("Quicken grants Go Again."); GiveAttackGoAgain(); $remove = 1; } break;
      case "ARC112": DealArcane(1, 0, "RUNECHANT", "ARC112"); $remove = 1; break;
      case "ELE110": if($attackType == "AA") { WriteLog("Embodiment of Lightning grants Go Again."); GiveAttackGoAgain(); $remove = 1; } break;
      case "ELE226": if($attackType == "AA") DealArcane(1, 0, "PLAYCARD", $combatChain[0]); break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($myAuras[$j]);
      }
      $myAuras = array_values($myAuras);
    }
  }
}

function AuraHitEffects($attackID)
{
  global $mainPlayer;
  $attackType = CardType($attackID);
  $auras = &GetAuras($mainPlayer);
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($auras[$i])
    {
      case "ARC106": if($attackType == "AA") { WriteLog("Bloodspill Invocation created 3 runechants."); PlayAura("ARC112", $mainPlayer, 3); $remove = 1; } break;
      case "ARC107": if($attackType == "AA") { WriteLog("Bloodspill Invocation created 2 runechants."); PlayAura("ARC112", $mainPlayer, 2); $remove = 1; } break;
      case "ARC108": if($attackType == "AA") { WriteLog("Bloodspill Invocation created 1 runechants."); PlayAura("ARC112", $mainPlayer, 1); $remove = 1; } break;
      default: break;
    }
    if($remove == 1)
    {
      for($j = $i+AuraPieces()-1; $j >= $i; --$j)
      {
        unset($auras[$j]);
      }
      $auras = array_values($auras);
    }
  }
}

  function AuraAttackModifiers($index)
  {
    global $combatChain;
    $modifier = 0;
    $player = $combatChain[$index+1];
    $otherPlayer = ($player == 1 ? 2 : 1);
    $controlAuras = &GetAuras($player);
    for($i=0; $i < count($controlAuras); $i += AuraPieces())
    {
      switch($controlAuras[$i])
      {
        case "ELE117": if(CardType($combatChain[$index]) == "AA") { $modifier += 3; } break;
        default: break;
      }
    }
    $otherAuras = &GetAuras($otherPlayer);
    for($i=0; $i < count($otherAuras); $i += AuraPieces())
    {
      switch($otherAuras[$i])
      {
        case "MON011": if(CardType($combatChain[$index]) == "AA") { $modifier -= 1; } break;
        default: break;
      }
    }
    return $modifier;
  }

  function NumNonTokenAura($player)
  {
    $count = 0;
    $auras = &GetAuras($player);
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      if(CardType($auras[$i]) != "T") ++$count;
    }
    return $count;
  }


?>

