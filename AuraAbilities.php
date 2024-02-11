<?php

function PlayAura($cardID, $player, $number = 1, $isToken = false, $rogueHeronSpecial = false)
{
  global $CS_NumAuras, $EffectContext;
  $otherPlayer = ($player == 1 ? 2 : 1);
  if(TypeContains($cardID, "T", $player)) $isToken = true;
  if(DelimStringContains(CardSubType($cardID), "Affliction")) {
    $otherPlayer = $player;
    $player = ($player == 1 ? 2 : 1);
  }
  $auras = &GetAuras($player);
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("HVY209", $player) + CountCurrentTurnEffects("HVY209", $otherPlayer);
  if($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player))) $number -= $numMinusTokens;
  if($cardID == "ARC112") $number += CountCurrentTurnEffects("ARC081", $player);
  if($cardID == "MON104") {
    $index = SearchArsenalReadyCard($player, "MON404");
    if($index > -1) TheLibrarianEffect($player, $index);
  }
  $myHoldState = AuraDefaultHoldTriggerState($cardID);
  if($myHoldState == 0 && HoldPrioritySetting($player) == 1) $myHoldState = 1;
  $theirHoldState = AuraDefaultHoldTriggerState($cardID);
  if($theirHoldState == 0 && HoldPrioritySetting($otherPlayer) == 1) $theirHoldState = 1;

  for($i = 0; $i < $number; ++$i) {
    array_push($auras, $cardID);
    array_push($auras, 2); //Status
    if($rogueHeronSpecial) array_push($auras, 0); //Only happens on the damage effect of the Heron Master in the Roguelike Gamemode
    else array_push($auras, AuraPlayCounters($cardID)); //Miscellaneous Counters
    array_push($auras, 0); //Attack counters
    array_push($auras, ($isToken ? 1 : 0)); //Is token 0=No, 1=Yes
    array_push($auras, AuraNumUses($cardID));
    array_push($auras, GetUniqueId());
    array_push($auras, $myHoldState); //My Hold priority for triggers setting 2=Always hold, 1=Hold, 0=Don't hold
    array_push($auras, $theirHoldState); //Opponent Hold priority for triggers setting 2=Always hold, 1=Hold, 0=Don't hold
  }
  if(DelimStringContains(CardSubType($cardID), "Affliction")) IncrementClassState($otherPlayer, $CS_NumAuras, $number);
  else if($cardID != "ELE111") IncrementClassState($player, $CS_NumAuras, $number);
}

function AuraNumUses($cardID)
{
  switch ($cardID) {
    case "EVR140": case "EVR141": case "EVR142": case "EVR143":
    case "UPR005": return 1;
    case "DTD081": return 1;
    default: return 0;
  }
}

function TokenCopyAura($player, $index)
{
  $auras = &GetAuras($player);
  PlayAura($auras[$index], $player, 1, true);
}

function AuraDestroyed($player, $cardID, $isToken = false)
{
  $auras = &GetAuras($player);
  for($i = 0; $i < count($auras); $i += AuraPieces()) {
    switch($auras[$i]) {
      case "EVR141":
        if(!$isToken && $auras[$i+5] > 0 && ClassContains($cardID, "ILLUSIONIST", $player)) {
          --$auras[$i+5];
          PlayAura("MON104", $player);
        }
        break;
      case "EVO244":
        PhantomTidemawDestroy($player, $i);
        break;
      default: break;
    }
  }
  switch($cardID)
  {
    case "DYN072":
      $char = &GetPlayerCharacter($player);
      for($j = 0; $j < count($char); $j += CharacterPieces()) {
        if(CardSubType($char[$j]) == "Sword") $char[$j+3] = 0;
      }
      break;
    default: break;
  }
  $goesWhere = GoesWhereAfterResolving($cardID);
  $numMercifulRetribution = SearchCount(SearchAurasForCard("MON012", $player)) + ($cardID == "MON012" ? 1 : 0);
  for($i = 0; $i < $numMercifulRetribution; ++$i) {
    if(TalentContains($cardID, "LIGHT", $player)) $goesWhere = "SOUL";
    if(CardType($cardID) != "T" && $isToken) WriteLog("<span style='color:red;'>The card is not put in your soul from Merciful Retribution because it is a token copy</span>");
    DealArcane(1, 0, "STATIC", "MON012", false, $player);
  }
  if(HasWard($cardID, $player) && !$isToken) WardPoppedAbility($player, $cardID);
  if(CardType($cardID) == "T" || $isToken) return;//Don't need to add to anywhere if it's a token
  switch($goesWhere) {
    case "GY":
      if(DelimStringContains(CardSubType($cardID), "Affliction")) $player = ($player == 1 ? 2 : 1);
      AddGraveyard($cardID, $player, "PLAY", $player);
      break;
    case "SOUL":
      AddSoul($cardID, $player, "PLAY");
      break;
    case "BANISH":
      BanishCardForPlayer($cardID, $player, "PLAY", "NA");
      break;
    default: break;
  }
}

function AuraLeavesPlay($player, $index)
{
  $auras = &GetAuras($player);
  $cardID = $auras[$index];
  $uniqueID = $auras[$index + 6];
  $otherPlayer = ($player == 1 ? 2 : 1);
  switch($cardID)
  {
    case "DYN221": case "DYN222": case "DYN223":
      $banish = new Banish($otherPlayer);
      $banishCard = $banish->FirstCardWithModifier("DYN221-" . $uniqueID);
      if($banishCard == null) break;
      $banishIndex = $banishCard->Index();
      if($banishIndex > -1) PlayAura($banish->Remove($banishIndex), $otherPlayer);
      break;
    default: break;
  }
}

function AuraPlayCounters($cardID)
{
  switch ($cardID) {
    case "CRU075": return 1;
    case "EVR107": return 3;
    case "EVR108": return 2;
    case "EVR109": return 1;
    case "UPR140": return 3;
    default: return 0;
  }
}

function DestroyAuraUniqueID($player, $uniqueID)
{
  $index = SearchAurasForUniqueID($uniqueID, $player);
  if($index != -1) DestroyAura($player, $index, $uniqueID);
}

function DestroyAura($player, $index, $uniqueID="")
{
  $auras = &GetAuras($player);
  $isToken = $auras[$index + 4] == 1;
  if($uniqueID != "") $index = SearchAurasForUniqueID($uniqueID, $player);
  AuraDestroyAbility($player, $index, $isToken);
  $cardID = RemoveAura($player, $index);
  AuraDestroyed($player, $cardID, $isToken);
  return $cardID;
}

function AuraDestroyAbility($player, $index, $isToken)
{
  $auras = &GetAuras($player);
  $cardID = $auras[$index];
  switch($cardID)
  {
    case "EVR141":
      if(!$isToken && $auras[$index + 5] > 0 && ClassContains($cardID, "ILLUSIONIST", $player)) {
        --$auras[$index + 5];
        PlayAura("MON104", $player);
      }
      break;
    default: break;
  }
}

function RemoveAura($player, $index)
{
  AuraLeavesPlay($player, $index);
  $auras = &GetAuras($player);
  $cardID = $auras[$index];
  for($i = $index + AuraPieces() - 1; $i >= $index; --$i) {
    unset($auras[$i]);
  }
  $auras = array_values($auras);
  if(IsSpecificAuraAttacking($player, $index)) {
    CloseCombatChain();
  }
  //TODO: Remove temporary integrity checking code
  if(count($auras) > 0 && count($auras) % AuraPieces() != 0) ReportBug();
  return $cardID;
}

function AuraCostModifier($cardID="")
{
  global $currentPlayer;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  $myAuras = &GetAuras($currentPlayer);
  $theirAuras = &GetAuras($otherPlayer);
  $modifier = 0;
  for($i = count($myAuras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    switch($myAuras[$i]) {
      case "ELE111":
        $modifier += 1;
        AddLayer("TRIGGER", $currentPlayer, "ELE111", "-", "-", $myAuras[$i + 6]);
        break;
      default: break;
    }
  }

  for($i = count($theirAuras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    switch($theirAuras[$i]) {
      case "ELE146":
        $modifier += 1;
        break;
      default:
        break;
    }
  }
  return CostCantBeModified($cardID) ? 0 : $modifier;
}

// CR 2.1 - 4.2.1. Players do not get priority during the Start Phase.
// CR 2.1 - 4.3.1. The “beginning of the action phase” event occurs and abilities that trigger at the beginning of the action phase are triggered.
function AuraStartTurnAbilities()
{
  global $mainPlayer, $EffectContext, $defPlayer, $CS_NumVigorDestroyed, $CS_NumMightDestroyed, $CS_NumAgilityDestroyed;
  $auras = &GetAuras($mainPlayer);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $EffectContext = $auras[$i];
    switch ($auras[$i]) {
      //These are all "beginning of the action phase" events with priority holding
      case "WTR046": case "WTR047": case "WTR054": case "WTR055": case "WTR056": case "WTR069": case "WTR070": case "WTR071":
      case "WTR072": case "WTR073": case "WTR074": case "WTR075":
      case "ARC162":
      case "CRU028": case "CRU029": case "CRU030": case "CRU031": case "CRU038": case "CRU039": case "CRU040": case "CRU075": case "CRU144":
      case "MON186":
      case "ELE025": case "ELE026": case "ELE027": case "ELE028": case "ELE029": case "ELE030": case "ELE109": case "ELE206": case "ELE207": case "ELE208":
      case "EVR107": case "EVR108": case "EVR109": case "EVR131": case "EVR132": case "EVR133":
      case "UPR190": case "UPR218": case "UPR219": case "UPR220":
      case "DYN217":
        AddLayer("TRIGGER", $mainPlayer, $auras[$i], "-", "-", $auras[$i + 6]);
        break;
      //These are all start of turn events without priority
      case "MON006":
        AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYHAND");
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a card to put in your hero's soul for Genesis");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "GENESIS", 1);
        break;
      case "DYN013": case "DYN014": case "DYN015":
        if($auras[$i] == "DYN013") $amount = 3;
        else if($auras[$i] == "DYN014") $amount = 2;
        else $amount = 1;
        AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i + 6]);
        break;
      case "DYN029":
        DestroyAuraUniqueID($mainPlayer, $auras[$i + 6]);
        $hand = &GetHand($mainPlayer);
        if(count($hand) == 0) {
          Draw($mainPlayer, false);
        }
        if(PlayerHasLessHealth($mainPlayer)) {
          GainHealth(2, $mainPlayer);
        }
        if(SearchCount(SearchCharacter($mainPlayer, type:"E")) < SearchCount(SearchCharacter($defPlayer, type:"E"))) {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYCHAR:type=E;hasNegCounters=true");
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an equipment to remove a -1 defense counter", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $mainPlayer, "GETCARDINDEX", 1);
          AddDecisionQueue("MODDEFCOUNTER", $mainPlayer, "1", 1);
        }
        break;
      case "DYN033": case "DYN034": case "DYN035":
        if($auras[$i] == "DYN033") $amount = 3;
        else if($auras[$i] == "DYN034") $amount = 2;
        else $amount = 1;
        GainHealth($amount, $mainPlayer);
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN048":
        AddPlayerHand("DYN065", $mainPlayer, "-");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN053": case "DYN054": case "DYN055":
        if($auras[$i] == "DYN053") $amount = 3;
        else if($auras[$i] == "DYN054") $amount = 2;
        else $amount = 1;
        $index = BanishCardForPlayer("DYN065", $mainPlayer, "-", "TT", $mainPlayer);
        $banish = new Banish($mainPlayer);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, $banish->Card($index)->UniqueID());
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $auras[$i] . ",BANISH");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN073": case "DYN074": case "DYN075":
        if($auras[$i] == "DYN073") $amount = 3;
        else if($auras[$i] == "DYN074") $amount = 2;
        else $amount = 1;
        AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN098": case "DYN099": case "DYN100":
        if($auras[$i] == "DYN098") $amount = 3;
        else if($auras[$i] == "DYN099") $amount = 2;
        else $amount = 1;
        DestroyAuraUniqueID($mainPlayer, $auras[$i + 6]);
        $searchHyper = CombineSearches(SearchDiscardForCard($mainPlayer, "ARC036", "DYN111", "DYN112"), SearchBanishForCardMulti($mainPlayer, "ARC036", "DYN111", "DYN112"));
        $countHyper = count(explode(",", $searchHyper));
        if($amount > $countHyper) $amount = $countHyper;
        for($i = 0; $i < $amount; ++$i) {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYDISCARD:cardID=ARC036;cardID=DYN111;cardID=DYN112&MYBANISH:cardID=ARC036;cardID=DYN111;cardID=DYN112");
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to put into play");
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("SETDQVAR", $mainPlayer, "0", 1);
          AddDecisionQueue("MZOP", $mainPlayer, "GETCARDID", 1);
          AddDecisionQueue("PUTPLAY", $mainPlayer, "False", 1);
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, "{0}", 1);
          AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
        }
        break;
      case "DYN159": case "DYN160": case "DYN161":
        if($auras[$i] == "DYN159") $amount = 3;
        else if($auras[$i] == "DYN160") $amount = 2;
        else $amount = 1;
        DestroyAuraUniqueID($mainPlayer, $auras[$i + 6]);
        PlayerOpt($mainPlayer, $amount);
        AddDecisionQueue("SPECIFICCARD", $mainPlayer, "BLESSINGOFFOCUS", 1);
        break;
		  case "DYN179": case "DYN180": case "DYN181":
        if($auras[$i] == "DYN179") $amount = 3;
        else if($auras[$i] == "DYN180") $amount = 2;
        else $amount = 1;
        PlayAura("ARC112", $mainPlayer, $amount, true);
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN200": case "DYN201": case "DYN202":
        AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DYN218": case "DYN219": case "DYN220":
        if($auras[$i] == "DYN218") $amount = 3;
        else if($auras[$i] == "DYN219") $amount = 2;
        else $amount = 1;
        PlayAura("MON104", $mainPlayer, $amount);
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "DTD170":
        if($auras[$i+2] > 0) {
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you want to remove a Doom Counter from " . CardLink($auras[$i], $auras[$i]) . "?");
          AddDecisionQueue("YESNO", $mainPlayer, "if_you_want_to_remove_a_Doom_Counter_and_keep_" . CardLink($auras[$i], $auras[$i]));
          AddDecisionQueue("REMOVECOUNTERAURAORDESTROY", $mainPlayer, $auras[$i+6]);
        }
        else {
          DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
          WriteLog(CardLink($auras[$i], $auras[$i]) . " was destroyed");
        }
        break;
      case "TCC037": case "TCC038": case "TCC042": case "TCC043":
        AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "TCC105": case "HVY241": //Might
        AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        IncrementClassState($mainPlayer, $CS_NumMightDestroyed, 1);
        break;
      case "TCC107": case "HVY242": //Vigor
        GainResources($mainPlayer, 1);
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        IncrementClassState($mainPlayer, $CS_NumVigorDestroyed, 1);
        break;
      case "EVO243":
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "HVY068": case "HVY069": case "HVY070":
        WriteLog("Resolving ". CardLink($auras[$i], $auras[$i]). " ability");
        DestroyAuraUniqueID($mainPlayer, $auras[$i + 6]);
        Draw($mainPlayer);
        MZMoveCard($mainPlayer, "MYHAND", "MYTOPDECK", silent:true);
        break;
      case "HVY083": case "HVY084": case "HVY085":
        AddCurrentTurnEffect($auras[$i] . "-BUFF", $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "HVY086": case "HVY087": case "HVY088":
        AddCurrentTurnEffect($auras[$i] . "-BUFF", $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        break;
      case "HVY240": //Agility
        if(!SearchCurrentTurnEffects($auras[$i], $mainPlayer)) AddCurrentTurnEffect($auras[$i], $mainPlayer, "PLAY");
        DestroyAuraUniqueID($mainPlayer, $auras[$i+6]);
        IncrementClassState($mainPlayer, $CS_NumAgilityDestroyed, 1);
        break;
      default: break;
    }
  }
}


function AuraBeginEndPhaseTriggers()
{
  global $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    switch($auras[$i]) {
      case "UPR176": case "UPR177": case "UPR178":
      case "DYN244":
      case "OUT234": case "OUT235": case "OUT236":
        AddLayer("TRIGGER", $mainPlayer, $auras[$i], "-", "-", $auras[$i+6]);
        break;
      default: break;
    }
  }
  $auras = array_values($auras);
}

function AuraBeginEndPhaseAbilities()
{
  global $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "ELE117":
        ChannelTalent($i, "EARTH");
        break;
      case "ELE146":
        ChannelTalent($i, "ICE");
        break;
      case "ELE175":
        ChannelTalent($i, "LIGHTNING");
        break;
      case "UPR005":
        $toBanish = ++$auras[$i + 2];
        $discardReds = SearchCount(SearchDiscard($mainPlayer, pitch:1));
        if($toBanish <= $discardReds) {
          for($j = $toBanish; $j > 0; --$j) {
            MZMoveCard($mainPlayer, "MYDISCARD:pitch=1", "MYBANISH,GY,-", may:true, isSubsequent:$j < $toBanish);
          }
          AddDecisionQueue("ELSE", $mainPlayer, "-");
          AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYAURAS-" . $i, 1);
          AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
        } else {
          DestroyAura($mainPlayer, $i);
        }
        break;
      case "UPR138":
        ChannelTalent($i, "ICE");
        break;
      case "ELE111":
        FrostHexEndTurnAbility($mainPlayer);
        $remove = 1;
        break;
      case "DYN175":
        if ($auras[$i + 2] == 0) $remove = 1;
        else {
          --$auras[$i + 2];
          DealArcane(2, 2, "PLAYCARD", "DYN175", false, $mainPlayer);
        }
        break;
      default: break;
    }
    if($remove == 1) DestroyAura($mainPlayer, $i);
  }
  $auras = array_values($auras);
}

function ChannelTalent($index, $talent)
{
  global $mainPlayer;
  $auras = &GetAuras($mainPlayer);
  $toBottom = ++$auras[$index + 2];
  $numTalent = SearchCount(SearchPitch($mainPlayer, talent:$talent));
  if($toBottom <= $numTalent) {
    for($j = $toBottom; $j > 0; --$j) {
      MZMoveCard($mainPlayer, "MYPITCH:talent=" . $talent, "MYBOTDECK", may:true, isSubsequent:$j < $toBottom);
    }
    AddDecisionQueue("ELSE", $mainPlayer, "-");
    AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYAURAS-" . $index, 1);
    AddDecisionQueue("MZDESTROY", $mainPlayer, "-", 1);
  } else {
    DestroyAura($mainPlayer, $index);
  }
}

function AuraEndTurnAbilities()
{
  global $CS_NumNonAttackCards, $mainPlayer, $CS_HitsWithSword;
  $auras = &GetAuras($mainPlayer);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = false;
    switch($auras[$i]) {
      case "ARC167": case "ARC168": case "ARC169":
        if(GetClassState($mainPlayer, $CS_NumNonAttackCards) == 0) $remove = true;
        break;
      case "ELE226": $remove = true; break;
      case "UPR139": $remove = true; break;
      case "DYN072":
        if(GetClassState($mainPlayer, $CS_HitsWithSword) <= 0) $remove = true;
        break;
      default: break;
    }
    if($remove) DestroyAura($mainPlayer, $i);
  }
}

function AuraEndTurnCleanup()
{
  $auras = &GetAuras(1);
  for($i = 0; $i < count($auras); $i += AuraPieces()) $auras[$i + 5] = AuraNumUses($auras[$i]);
  $auras = &GetAuras(2);
  for($i = 0; $i < count($auras); $i += AuraPieces()) $auras[$i + 5] = AuraNumUses($auras[$i]);
}

function AuraDamagePreventionAmount($player, $index, $damage=0, $active=false, &$cancelRemove=false)
{
  $auras = &GetAuras($player);
  switch($auras[$index])
  {
    case "ARC112": return (CountAura("CRU144", $player) > 0 ? 1 : 0);
    case "ARC167": return 4;
    case "ARC168": return 3;
    case "ARC169": return 2;
    case "MON104": return 1;
    case "UPR218": return 4;
    case "UPR219": return 3;
    case "UPR220": return 2;
    case "DYN217": return 1;
    case "DYN218": case "DYN219": case "DYN220": return 1;
    case "DYN221": case "DYN222": case "DYN223": return 1;
    case "DTD081":
      $auras = &GetAuras($player);
      if($active)
      {
        $soul = &GetSoul($player);
        if(count($soul) > 0)
        {
          $cancelRemove = count($soul) > 1 ? true : false;
          MZMoveCard($player, "MYSOUL", "MYBANISH,SOUL,-");
          if($damage > 1) $auras[$index+5] = 0;
          return 1;
        }
      }
      else if($auras[$index+5] == 1) {
        return 1;
      } else {
        $auras[$index+5] = 1;
        return 0;
      }
      break;
    case "EVO244": return 1;
    default: break;
  }
}

//This function is for effects that prevent damage and DO destroy themselves
function AuraTakeDamageAbility($player, $index, $damage, $preventable)
{
  $cancelRemove = false;
  if($preventable) $damage -= AuraDamagePreventionAmount($player, $index, $damage, true, $cancelRemove);
  if(!$cancelRemove) DestroyAura($player, $index);
  return $damage;
}

//This function is for effects that prevent damage and do NOT destroy themselves
//These are applied first and not prompted (which would be annoying because of course you want to do this before consuming something)
function AuraTakeDamageAbilities($player, $damage, $type)
{
  $auras = &GetAuras($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    if($damage <= 0) {
      $damage = 0;
      break;
    }
    switch($auras[$i]) {
      case "CRU075":
        if($preventable) $damage -= 1;
        break;
      case "EVR131":
        if($type == "ARCANE" && $preventable) $damage -= 3;
        break;
      case "EVR132":
        if($type == "ARCANE" && $preventable) $damage -= 2;
        break;
      case "EVR133":
        if($type == "ARCANE" && $preventable) $damage -= 1;
        break;
      default: break;
    }
  }
  return $damage;
}


function AuraDamageTakenAbilities($player, $damage)
{
  $auras = &GetAuras($player);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "ARC106": case "ARC107": case "ARC108": $remove = 1; break;
      case "EVR023": $remove = 1; break;
      default: break;
    }
    if($remove) DestroyAura($player, $i);
  }
  return $damage;
}

function AuraLoseHealthAbilities($player, $amount)
{
  global $mainPlayer;
  $auras = &GetAuras($player);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "MON157":
        if($player == $mainPlayer) $remove = 1;
        break;
      default: break;
    }
    if($remove == 1) DestroyAura($player, $i);
  }
  return $amount;
}

function AuraPlayAbilities($attackID, $from="")
{
  global $currentPlayer, $CS_NumIllusionistActionCardAttacks;
  $auras = &GetAuras($currentPlayer);
  $cardType = CardType($attackID);
  $cardSubType = CardSubType($attackID);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "WTR225":
        if(($cardType == "AA" && GetResolvedAbilityType($attackID) == "") || ($cardSubType == "Aura" && $from == "PLAY") || ($cardType == "W" && GetResolvedAbilityType($attackID) == "AA")) {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " gives the attack go again");
          GiveAttackGoAgain();
          $remove = 1;
        }
        break;
      case "ARC112":
        if(($cardType == "AA" || ($cardSubType == "Aura" && $from == "PLAY") || ($cardType == "W" && GetResolvedAbilityType($attackID) == "AA")) && GetResolvedAbilityType($attackID) != "I") {
          $numRunechants = CountAura("ARC112", $currentPlayer);
          AddLayer("TRIGGER", $currentPlayer, $auras[$i], "-", "-", $auras[$i + 6]);
        }
        break;
      case "MON157":
        DimenxxionalCrossroadsPassive($attackID, $from);
        break;
      case "EVR143":
        if($auras[$i + 5] > 0 && CardType($attackID) == "AA" && ClassContains($attackID, "ILLUSIONIST", $currentPlayer) && GetClassState($currentPlayer, $CS_NumIllusionistActionCardAttacks) <= 1) {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " gives the attack +2");
          --$auras[$i + 5];
          AddCurrentTurnEffect("EVR143", $currentPlayer, true);
        }
        break;
      case "ELE175":
        if($cardType == "A" || $cardType == "AA") {
          AddLayer("TRIGGER", $currentPlayer, $auras[$i], $cardType, "-", $auras[$i + 6]);
        }
        break;
      case "DTD232":
        if(($cardType == "AA" || ($cardSubType == "Aura" && $from == "PLAY") || $cardType == "W") && GetResolvedAbilityType($attackID) != "I") {
          AddCurrentTurnEffect("DTD232", $currentPlayer);
          $remove = 1;
        }
        break;
      case "DTD233":
        if($cardType == "A" && $from != "PLAY") {
          global $CS_NextNAACardGoAgain;
          WriteLog(CardLink($auras[$i], $auras[$i]) . " gives the card go again");
          SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
          AddLayer("TRIGGER", $currentPlayer, $auras[$i], $cardType, "-", $auras[$i + 6]);
        }
        break;
      default: break;
    }
    if($remove == 1) DestroyAura($currentPlayer, $i);
  }
}

function AuraAttackAbilities($attackID)
{
  global $CombatChain, $mainPlayer, $CS_PlayIndex, $CS_NumIllusionistAttacks;
  $auras = &GetAuras($mainPlayer);
  $attackType = CardType($attackID);
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "ELE110":
        if($attackType == "AA") {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " grants go again");
          GiveAttackGoAgain();
          $remove = 1;
        }
        break;
      case "ELE226":
        if($attackType == "AA") DealArcane(1, 0, "PLAYCARD", $CombatChain->CurrentAttack());
        break;
      case "EVR140":
        if($auras[$i + 5] > 0 && DelimStringContains(CardSubtype($attackID), "Aura") && ClassContains($attackID, "ILLUSIONIST", $mainPlayer)) {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " puts a +1 counter");
          --$auras[$i + 5];
          ++$auras[GetClassState($mainPlayer, $CS_PlayIndex) + 3];
        }
        break;
      case "EVR142":
        if($auras[$i + 5] > 0 && ClassContains($attackID, "ILLUSIONIST", $mainPlayer) && GetClassState($mainPlayer, $CS_NumIllusionistAttacks) <= 1) {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " makes your first illusionist attack each turn lose Phantasm");
          --$auras[$i + 5];
          AddCurrentTurnEffect("EVR142", $mainPlayer, true);
        }
        break;
      case "UPR005":
        if($auras[$i + 5] > 0 && DelimStringContains(CardSubType($attackID), "Dragon")) {
          --$auras[$i + 5];
          DealArcane(1, 1, "STATIC", $attackID, false, $mainPlayer);
        }
        break;
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
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    $remove = 0;
    switch($auras[$i]) {
      case "ARC106": case "ARC107": case "ARC108":
        if($auras[$i] == "ARC106") $amount = 3;
        else if($auras[$i] == "ARC107") $amount = 2;
        else $amount = 1;
        if($attackType == "AA") {
          WriteLog(CardLink($auras[$i], $auras[$i]) . " created $amount runechants");
          PlayAura("ARC112", $mainPlayer, $amount);
          $remove = 1;
        }
        break;
      default: break;
    }
    if($remove == 1) DestroyAura($mainPlayer, $i);
  }
}

function AuraAttackModifiers($index, &$attackModifiers)
{
  global $CombatChain, $combatChainState, $CCS_AttackPlayedFrom;
  global $CID_Frailty;
  $chainCard = $CombatChain->Card($index);
  $modifier = 0;
  $player = $chainCard->PlayerID();
  $myAuras = &GetAuras($player);
  for($i = 0; $i < count($myAuras); $i += AuraPieces()) {
    switch($myAuras[$i]) {
      case "ELE117":
        if(CardType($chainCard->ID()) == "AA") {
          $modifier += 3;
          array_push($attackModifiers, $myAuras[$i]);
          array_push($attackModifiers, 3);
        }
        break;
      case $CID_Frailty:
        if($index == 0 && (IsWeaponAttack() || $combatChainState[$CCS_AttackPlayedFrom] == "ARS")) 
        {
          $modifier -= 1;
          array_push($attackModifiers, $myAuras[$i]);
          array_push($attackModifiers, -1);
        }
        break;
      default: break;
    }
  }
  $theirAuras = &GetAuras($player == 1 ? 2 : 1);
  for($i = 0; $i < count($theirAuras); $i += AuraPieces()) {
    switch($theirAuras[$i]) {
      case "MON011":
        if(CardType($CombatChain->CurrentAttack()) == "AA") 
        {
          $modifier -= 1;
          array_push($attackModifiers, $theirAuras[$i]);
          array_push($attackModifiers, -1);
        }
        break;
      default: break;
    }
  }
  return $modifier;
}

function NumNonTokenAura($player)
{
  $count = 0;
  $auras = &GetAuras($player);
  for($i = 0; $i < count($auras); $i += AuraPieces()) {
    if(CardType($auras[$i]) != "T") ++$count;
  }
  return $count;
}

function DestroyAllThisAura($player, $cardID)
{
  $auras = &GetAuras($player);
  $count = 0;
  for($i = count($auras) - AuraPieces(); $i >= 0; $i -= AuraPieces()) {
    if($auras[$i] == $cardID) {
      DestroyAura($player, $i);
      ++$count;
    }
  }
  return $count;
}

function GetAuraGemState($player, $cardID)
{
  global $currentPlayer;
  $auras = &GetAuras($player);
  $offset = ($currentPlayer == $player ? 7 : 8);
  $state = 0;
  for($i = 0; $i < count($auras); $i += AuraPieces()) {
    if($auras[$i] == $cardID && $auras[$i + $offset] > $state) $state = $auras[$i + $offset];
  }
  return $state;
}

function AuraIntellectModifier() {
  global $mainPlayer;
  $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
  $intellectModifier = 0;
  $auras = &GetAuras($mainPlayer);
  for($i=0; $i<count($auras); $i+=AuraPieces()) {
    switch($auras[$i]) {
      case "EVO243": $intellectModifier -= 1; break;
      default: break;
    }
  }
  $auras = &GetAuras($otherPlayer);
  for($i=0; $i<count($auras); $i+=AuraPieces()) {
    switch($auras[$i]) {
      case "EVO243": $intellectModifier -= 1; break;
      default: break;
    }
  }
  return $intellectModifier;
}

function PayAuraAbilityAdditionalCosts($cardID, $from)
{
  global $currentPlayer, $CS_PlayIndex;
  $index = GetClassState($currentPlayer, $CS_PlayIndex);
  switch($cardID) {
    case "DTD060": case "DTD061": case "DTD062":
      $hand = &GetHand($currentPlayer);
      if(count($hand) == 0) {
        WriteLog("You do not have a card to charge. Reverting gamestate.");
        RevertGamestate();
        return;
      }
      DestroyAura($currentPlayer, $index);
      Charge(may:false);
      break;
    default: break;
  }
}
