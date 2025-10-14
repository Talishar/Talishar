<?php

function SUPAbilityType($cardID, $index=-1, $from="-"): string
{
  return match ($cardID) {
    "lyath_goldmane" => "I",
    "lyath_goldmane_vile_savant" => "I",
    "kayo_underhanded_cheat" => "I",
    "kayo_strong_arm" => "I",
    "tuffnut" => "I",
    "tuffnut_bumbling_hulkster" => "I",
    "pleiades_superstar" => "I",
    "pleiades" => "I",
    "punching_gloves" => "A",
    default => ""
  };
}

function SUPAbilityCost($cardID): int
{
  global $currentPlayer;
  return match ($cardID) {
    "lyath_goldmane" => 2,
    "lyath_goldmane_vile_savant" => 2,
    "kayo_underhanded_cheat" => 4,
    "kayo_strong_arm" => 4,
    "punching_gloves" => 2,
    default => 0
  };
}

function SUPAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "punching_gloves" => true,
    default => false,
  };
}

function SUPEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "punching_gloves" => 2,
    default => 0,
  };
}

function SUPCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  return match ($cardID) {
    "confidence" => TypeContains($attackID, "AA", $mainPlayer),
    "punching_gloves" => TypeContains($attackID, "AA", $mainPlayer),
    "kayo_underhanded_cheat", "kayo_strong_arm" => true,
    default => false,
  };
}

function SUPPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $combatChain, $chainLinkSummary, $chainLinks, $defPlayer;
  global $CombatChain;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    case "punching_gloves":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
      BOO($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
      if ($currentPlayer == $mainPlayer) {
        //check to make sure they targeted the current chain link
        $uid = $CombatChain->AttackCard()->UniqueID();
        AddCurrentTurnEffect($cardID, $currentPlayer, $uid);
      }
      else {
        $targetIndex = intval(explode("-", $target)[1]);
        $uid = $CombatChain->Card($targetIndex)->UniqueID();
        AddCurrentTurnEffect($cardID, $currentPlayer, "", $uid);
        ReEvalCombatChain();
      }
      break;
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      $top = PitchTopCard($currentPlayer);
      if (ModifiedPowerValue($top, $currentPlayer, "DECK") >= 6) {
        Cheer($currentPlayer);
      }
      break;
    case "pleiades":
    case "pleiades_superstar":
      $suspAuras = GetSuspenseAuras($currentPlayer);
      if (count($suspAuras) > 0) {
        $suspAuras = implode(",", GetSuspenseAuras($currentPlayer));
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, $suspAuras);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to add a suspense counter to (or pass)", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SUSPENSE", $currentPlayer, "ADD", 1);
      }
      break;
    case "mocking_blow_red":
    case "mocking_blow_yellow":
    case "mocking_blow_blue":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      break;
    case "bask_in_your_own_greatness_red":
    case "bask_in_your_own_greatness_yellow":
    case "bask_in_your_own_greatness_blue":
    case "overcrowded_blue":
      AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      break;
    case "thespian_charm_yellow":
      $params = explode(",", $additionalCosts);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "destroy_a_might_or_vigor":
            $search = "THEIRAURAS:cardID=might;cardID=vigor";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search, 1);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to destroy", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZDESTROY", $currentPlayer, "<-", 1);
            break;
          case "cheer":
            Cheer($currentPlayer);
            break;
          case "bounce_an_aura":
            $search = "MYAURAS";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to return to hand", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZBOUNCE", $currentPlayer, "<-", 1);
            break;
          default: break;
        }
      }
      break;
    case "liars_charm_yellow":
      $params = explode(",", $additionalCosts);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "steal_a_toughness_or_vigor":
            $search = "THEIRAURAS:cardID=vigor;cardID=toughness";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to steal", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
            break;
          case "boo":
            Boo($currentPlayer);
            break;
          case "remove_hero_abilities":
            $targetPlayer = str_contains($target, "MY") ? $currentPlayer : $otherPlayer;
            $hand = GetHand($targetPlayer);
            if (count($hand) > 0) {
              AddDecisionQueue("FINDINDICES", $targetPlayer, "HAND");
              AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Discard a card or else lose your hero ability", 1);
              AddDecisionQueue("MAYCHOOSEHAND", $targetPlayer, "<-", 1);
              AddDecisionQueue("MULTIREMOVEHAND", $targetPlayer, "-", 1);
              AddDecisionQueue("DISCARDCARD", $targetPlayer, "HAND", 1);
              AddDecisionQueue("ELSE", $targetPlayer, "-");
            }
            AddDecisionQueue("SPECIFICCARD", $targetPlayer, "LIAR", 1);
            break;
          default: break;
        }
      }
      break;
    case "numbskull_charm_yellow":
      $params = explode(",", $additionalCosts);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "destroy_a_confidence_or_might":
            $search = "THEIRAURAS:cardID=confidence;cardID=might";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to destroy", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZDESTROY", $currentPlayer, "<-", 1);
            break;
          case "cheer":
            Cheer($currentPlayer);
            break;
          case "pitch_top_card":
            $deck = new Deck($currentPlayer);
            $top = $deck->Top(true);
            Pitch($top, $currentPlayer);
            if (ModifiedPowerValue($top, $currentPlayer, "DECK") >= 6) {
              PlayAura("vigor", $currentPlayer, isToken:true, effectController:$currentPlayer, effectSource:$cardID);
            }
            break;
          default: break;
        }
      }
      break;
    case "cheaters_charm_yellow":
      $params = explode(",", $additionalCosts);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "steal_a_confidence_or_toughness":
            $search = "THEIRAURAS:cardID=confidence;cardID=toughness";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to steal", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
            break;
          case "boo":
            Boo($currentPlayer);
            CacheCombatResult();
            break;
          case "deal_2_damage":
            $targetPlayer = str_contains($target, "MY") ? $currentPlayer : $otherPlayer;
            $condition = false;
            if ($currentPlayer == $mainPlayer) {
              if (CachedTotalPower() >= 6) $condition = true;
              for ($j = 0; $j < count($chainLinkSummary); $j += ChainLinkSummaryPieces()) {
                if ($chainLinkSummary[$j + 1] >= 6) $condition = true;
              }
              if ($condition) {
                Deal2OrDiscard($targetPlayer);
              }
            }
            break;
          default: break;
        }
      }
      break;
    //expansion slot cards
    case "light_up_the_leaves_red":
      DealArcane(ArcaneDamage($cardID), 2, "PLAYCARD", $cardID, resolvedTarget: $target);
      break;
    default:
      break;
  }
  return "";
}

function SUPHitEffect($cardID): void
{
  global $mainPlayer;
  switch ($cardID) {
    case "old_leather_and_vim_red":
      PlayAura("toughness", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      PlayAura("vigor", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      break;
    case "uplifting_performance_blue":
      PlayAura("confidence", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      PlayAura("toughness", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      break;
    case "offensive_behavior_blue":
      PlayAura("might", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      PlayAura("vigor", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      break;
    case "spew_obscenities_yellow":
      PlayAura("confidence", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      PlayAura("might", $mainPlayer, isToken:true, effectController:$mainPlayer, effectSource:$cardID);
      break;
    default:
      break;
  }
}

function Deal2OrDiscard($targetPlayer)
{
  $hand = GetHand($targetPlayer);
  if (count($hand) > 0) {
    // AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose if you want to discard or take 2 damage.");
    // AddDecisionQueue("BUTTONINPUT", $targetPlayer, "Take_2_Damage,Discard", 1);
    // AddDecisionQueue("EQUALPASS", $targetPlayer, "Take_2_Damage", 1);
    // AddDecisionQueue("FINDINDICES", $targetPlayer, "HAND", 1);
    // AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose a card to discard", 1);
    // AddDecisionQueue("CHOOSEHAND", $targetPlayer, "<-", 1);
    // AddDecisionQueue("MULTIREMOVEHAND", $targetPlayer, "-", 1);
    // AddDecisionQueue("DISCARDCARD", $targetPlayer, "HAND", 1);
    // AddDecisionQueue("ELSE", $targetPlayer, "-");
    // is this more intuitive?
    AddDecisionQueue("FINDINDICES", $targetPlayer, "HAND");
    AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Discard a card or else take 2 damage", 1);
    AddDecisionQueue("MAYCHOOSEHAND", $targetPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $targetPlayer, "-", 1);
    AddDecisionQueue("DISCARDCARD", $targetPlayer, "HAND", 1);
    AddDecisionQueue("ELSE", $targetPlayer, "-");
  }
  AddDecisionQueue("TAKEDAMAGE", $targetPlayer, 2, 1);
}

function BOO($player)
{
  global $CS_BooedThisTurn;
  SetClassState($player, $CS_BooedThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("BOOOOO! The crowd jeers at " . CardLink($char[0], $char[0]) . "!");
  if ($char[1] < 3) {
    switch($char[0]) {
      case "lyath_goldmane":
      case "lyath_goldmane_vile_savant":
      case "kayo_underhanded_cheat":
      case "kayo_strong_arm":
        AddLayer("TRIGGER", $player, $char[0]);
        break;
      default:
        break;
    }
  }
}

function Cheer($player)
{
  global $CS_CheeredThisTurn;
  SetClassState($player, $CS_CheeredThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("Let's go! The crowd cheers for " . CardLink($char[0], $char[0]) . "!");
  if ($char[1] < 3) {
    switch($char[0]) {
      case "pleiades":
      case "pleiades_superstar":
      case "tuffnut":
      case "tuffnut_bumbling_hulkster":
        AddLayer("TRIGGER", $player, $char[0]);
        break;
      default:
        break;
    }
  }
}

function HasSuspense($cardID)
{
  $card = GetClass($cardID, 0);
  if ($card != "-") return $card->HasSuspense();
}

function GetSuspenseAuras($player, $hasCounter = false)
{
  $auras = GetAuras($player);
  $susp = [];
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if (HasSuspense($auras[$i]) && (!$hasCounter || $auras[$i + 2])) array_push($susp, "MYAURAS-$i");
  }
  return $susp;
}

function RemoveSuspense($player, $MZIndex, $mainPhase = true)
{
  $otherPlayer = $player == 1 ? 2 : 1;
  $targetPlayer = str_contains($MZIndex, "MY") ? $player : $otherPlayer;
  $auras = &GetAuras($targetPlayer);
  $ind = explode("-", $MZIndex)[1];
  --$auras[$ind + 2];
  if ($auras[$ind + 2] <= 0) {
    AddLayer("TRIGGER", $targetPlayer, "$auras[$ind]", $auras[$ind + 6], "DESTROY");
  }
}

function AddSuspense($player, $MZIndex)
{
  $otherPlayer = $player == 1 ? 2 : 1;
  $targetPlayer = str_contains($MZIndex, "MY") ? $player : $otherPlayer;
  $auras = &GetAuras($targetPlayer);
  $ind = explode("-", $MZIndex)[1];
  ++$auras[$ind + 2];
}

function TargetDefendingAction($player, $cardID, $setTarget=false) {
  global $CombatChain;
  if (!$CombatChain->HasCurrentLink()) return;
  $AOptions = GetChainLinkCards($player, "A", "C");
  $AAOptions = GetChainLinkCards($player, "AA", "C");
  if ($AOptions == "") $numOptions = $AAOptions;
  elseif ($AAOptions == "") $numOptions = $AOptions;
  else $numOptions = "$AAOptions,$AOptions";
  if ($numOptions != "") {
    $numOptions = explode(",", $numOptions);
    $options = [];
    foreach ($numOptions as $num) array_push($options, "COMBATCHAINLINK-$num");
    $options = implode(",", $options);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a defending action card to buff");
    AddDecisionQueue("CHOOSEMULTIZONE", $player, $options, 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $player, "-", 1);
    if ($setTarget) AddDecisionQueue("SETLAYERTARGET", $player, $cardID, 1);
  }
  else {
    WriteLog(CardLink($cardID, $cardID) . " is targeting a prior chain link (this  won't have any effect for now)");
  }
}