<?php

function PlayAura($cardID, $player, $number=1, $isToken=false)
{
  global $CS_NumAuras;
  if(CardType($cardID) == "T") $isToken = true;
  if(DelimStringContains(CardSubType($cardID), "Affliction")) $player = ($player == 1 ? 2 : 1);
  $auras = &GetAuras($player);
  if($cardID == "ARC112") $number += CountCurrentTurnEffects("ARC081", $player);
  if($cardID == "MON104")
  {
    $index = SearchArsenalReadyCard($player, "MON404");
    if($index > -1) TheLibrarianEffect($player, $index);
  }
  for($i=0; $i<$number; ++$i)
  {
    array_push($auras, $cardID);
    array_push($auras, 2);//Status
    array_push($auras, AuraPlayCounters($cardID));//Miscellaneous Counters
    array_push($auras, 0);//Attack counters
    array_push($auras, ($isToken ? 1 : 0));//Is token 0=No, 1=Yes
    array_push($auras, AuraNumUses($cardID));
  }
  IncrementClassState($player, $CS_NumAuras, $number);
}

function AuraNumUses($cardID)
{
  switch($cardID)
  {
    case "EVR140": case "EVR141": case "EVR142": case "EVR143": return 1;
    case "UPR005": return 1;
    default: return 0;
  }
}

function TokenCopyAura($player, $index)
{
  $auras = &GetAuras($player);
  PlayAura($auras[$index], $player, 1, true);
}

function PlayMyAura($cardID)
{
  global $currentPlayer;
  PlayAura($cardID, $currentPlayer, 1);
}

function AuraDestroyed($player, $cardID, $isToken=false)
{
  $auras = &GetAuras($player);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    switch($auras[$i])
    {
      case "EVR141": if(!$isToken && $auras[$i+5]>0 && CardClass($cardID) == "ILLUSIONIST") { --$auras[$i+5]; PlayAura("MON104", $player); } break;
      default: break;
    }
  }
  $goesWhere = GoesWhereAfterResolving($cardID);
  for($i=0; $i<SearchCount(SearchAurasForCard("MON012", $player)); ++$i)
  {
    $goesWhere = "SOUL";
    DealArcane(1, 0, "STATIC", "MON012", true, $player);
  }
  if(CardType($cardID) == "T" || $isToken) return;//Don't need to add to anywhere if it's a token
  switch($goesWhere)
  {
    case "GY": AddGraveyard($cardID, $player, "PLAY"); break;
    case "SOUL": AddSoul($cardID, $player, "PLAY"); break;
    case "BANISH": BanishCardForPlayer($cardID, $player, "PLAY", "NA"); break;
    default: break;
  }
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
    case "EVR107": return 3;
    case "EVR108": return 2;
    case "EVR109": return 1;
    case "UPR140": return 3;
    default: return 0;
  }
}

function DestroyAura($player, $index)
{
  $auras = &GetAuras($player);
  $cardID = $auras[$index];
  $isToken = $auras[$index+4] == 1;
  AuraDestroyed($player, $cardID, $isToken);
  for($j = $index+AuraPieces()-1; $j >= $index; --$j)
  {
    unset($auras[$j]);
  }
  $auras = array_values($auras);
}

function AuraCostModifier()
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
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
      DestroyAura($currentPlayer, $i);
    }
  }

  for($i=count($theirAuras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    switch($theirAuras[$i])
    {
      case "ELE146": $modifier += 1; break;
      default: break;
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
      case "EVR107": case "EVR108": case "EVR109":
        if($auras[$i+2] == 0)
        { $dest = "Runeblood Invocation is destroyed."; }
        else { --$auras[$i+2]; PlayAura("ARC112", $mainPlayer); } break;
      case "EVR131": case "EVR132": case "EVR133": $dest = "Pyroglyphic Protection is destroyed."; break;
      case "UPR190": $dest = "Fog Down is destroyed."; break;
      case "UPR218": case "UPR219": case "UPR220": $dest = "Sigil of Protection is destroyed."; break;
      default: break;
    }
    if($dest != "") DestroyAura($mainPlayer, $i);
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
      case "UPR005":
        ++$auras[$i+2];
        $numBanished = 0;
        $discard = &GetDiscard($mainPlayer);
        for($j=count($discard)-DiscardPieces(); $j >= 0 && $numBanished < $auras[$i+2]; $j-=DiscardPieces())
        {
          if(PitchValue($discard[$j]) == 1)
          {
            BanishCardForPlayer($discard[$j], $mainPlayer, "GY", "NA");
            RemoveGraveyard($mainPlayer, $j);
            ++$numBanished;
            WriteLog("Burn Them All banished a red card.");
          }
        }
        if($numBanished < $auras[$i+2]) { $remove = 1; WriteLog("Burn Them All was unable to banish enough red cards."); }
        break;
      case "UPR176": case "UPR177": case "UPR178":
        if($auras[$i] == "UPR176") $numOpt = 3;
        else if($auras[$i] == "UPR177") $numOpt = 2;
        else $numOpt = 1;
        for($j=0; $j<$numOpt; ++$j) PlayerOpt($mainPlayer, 1);
        AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
        $remove = 1;
        break;
      case "ELE111": FrostHexEndTurnAbility($mainPlayer); $remove = 1; break;
      default: break;
    }
    if($remove == 1) DestroyAura($mainPlayer, $i);
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
      case "ELE226": $remove = 1; break;
      case "UPR139": $remove = 1; break;
      default: break;
    }
    if($remove == 1) DestroyAura($mainPlayer, $i);
  }
}

function AuraEndTurnCleanup()
{
  $auras = &GetAuras(1);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    $auras[$i+5] = AuraNumUses($auras[$i]);
  }
    $auras = &GetAuras(2);
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      $auras[$i+5] = AuraNumUses($auras[$i]);
    }
}

function AuraTakeDamageAbilities($player, $damage, $type)
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
      case "EVR131": if($type == "ARCANE") $damage -= 3; break;
      case "EVR132": if($type == "ARCANE") $damage -= 2; break;
      case "EVR133": if($type == "ARCANE") $damage -= 1; break;
      case "UPR218": $damage -= 4; $remove = 1; break;
      case "UPR219": $damage -= 3; $remove = 1; break;
      case "UPR220": $damage -= 2; $remove = 1; break;
      default: break;
    }
    if($remove == 1)
    {
      DestroyAura($player, $i);
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
      case "EVR023": $remove = 1; break;
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
    if($remove == 1) DestroyAura($player, $i);
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
      case "EVR143": if($auras[$i+5]>0 && CardType($cardID) == "AA" && CardClass($cardID) == "ILLUSIONIST") { WriteLog("Pierce Reality gives the attack +2."); --$auras[$i+5]; AddCurrentTurnEffect("EVR143", $currentPlayer, true); } break;
      default: break;
    }
  }
}

function AuraAttackAbilities($attackID)
{
  global $combatChain, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $mainPlayer, $CS_PlayIndex;
  $auras = &GetAuras($mainPlayer);
  $attackType = CardType($attackID);
  $numRunechants = CountAura("ARC112", $mainPlayer);
  if($numRunechants > 0) WriteLog($numRunechants . " total Runechant tokens trigger incoming arcane damage.");
  for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
  {
    $remove = 0;
    switch($auras[$i])
    {
      case "WTR225": if($attackType == "AA" || $attackType == "W") { WriteLog("Quicken grants Go Again."); GiveAttackGoAgain(); $remove = 1; } break;
      case "ARC112": DealArcane(1, 0, "RUNECHANT", "ARC112"); $remove = 1; break;
      case "ELE110": if($attackType == "AA") { WriteLog("Embodiment of Lightning grants Go Again."); GiveAttackGoAgain(); $remove = 1; } break;
      case "ELE226": if($attackType == "AA") DealArcane(1, 0, "PLAYCARD", $combatChain[0]); break;
      case "EVR140": if($auras[$i+5]>0 && DelimStringContains(CardSubtype($attackID), "Aura") && CardClass($attackID) == "ILLUSIONIST") { WriteLog("Shimmers of Silver puts a +1 counter."); --$auras[$i+5]; ++$auras[GetClassState($mainPlayer, $CS_PlayIndex)+3]; } break;
      case "EVR142": if($auras[$i+5]>0 && CardClass($attackID) == "ILLUSIONIST") { WriteLog("Passing Mirage makes your next attack lose Phantasm."); --$auras[$i+5]; AddCurrentTurnEffect("EVR142", $mainPlayer, true); } break;
      case "UPR005": if($auras[$i+5]>0 && DelimStringContains(CardSubType($attackID), "Dragon")) { WriteLog("Burn Them All deals 1 arcane damage."); --$auras[$i+5]; DealArcane(1, 0, "STATIC", $attackID, false, $mainPlayer); } break;
      default: break;
    }
    if($remove == 1) DestroyAura($mainPlayer, $i);
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

  function DestroyAllThisAura($player, $cardID)
  {
    $auras = &GetAuras($player);
    for($i=count($auras)-AuraPieces(); $i>=0; $i-=AuraPieces())
    {
      if($auras[$i] == $cardID) DestroyAura($player, $i);
    }
  }


?>
