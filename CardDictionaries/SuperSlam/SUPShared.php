<?php

function SUPAbilityType($cardID): string
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
    default => 0
  };
}

function SUPAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    default => 0,
  };
}

function SUPCombatEffectActive($cardID, $attackID): bool
{
  return match ($cardID) {
    default => false,
  };
}

function SUPPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $CCS_LinkBasePower, $combatChain, $chainLinkSummary, $chainLinks;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    case "lyath_goldmane":
    case "lyath_goldmane_vile_savant":
      BOO($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "kayo_underhanded_cheat":
    case "kayo_strong_arm":
      if ($currentPlayer == $mainPlayer) {
        //check to make sure they targeted the current chain link
        $combatChainState[$CCS_LinkBasePower] = 6;
      }
      else {
        $targetIndex = intval(explode("-", $target)[1]);
        CombatChainPowerModifier($targetIndex, 6 - $combatChain[$targetIndex + 5]);
      }
      break;
    case "tuffnut":
    case "tuffnut_bumbling_hulkster":
      $deck = new Deck($currentPlayer);
      $top = $deck->Top(true);
      Pitch($top, $currentPlayer);
      if (ModifiedPowerValue($top, $currentPlayer, "DECK") >= 6) {
        Cheer($currentPlayer);
      }
      break;
    case "pleiades":
    case "pleiades_superstar":
      //put a suspense counter on an aura of suspense you control
      break;
    case "comeback_kid_red": //I'm going to try be default to be consistent in coding attack triggers as triggers
    case "comeback_kid_yellow":
    case "comeback_kid_blue":
    case "mocking_blow_red":
    case "mocking_blow_yellow":
    case "mocking_blow_blue":
    case "bully_tactics_red":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      break;
    case "bask_in_your_own_greatness_red":
    case "bask_in_your_own_greatness_yellow":
    case "bask_in_your_own_greatness_blue":
      AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      break;
    case "thespian_charm_yellow":
      $params = explode(",", $additionalCosts);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "destroy_a_might_or_vigor":
            $search = "THEIRAURAS:cardID=might;cardID=vigor";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to destroy", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "-", 1);
            AddDecisionQueue("MZDESTROY", $currentPlayer, "<-", 1);
            break;
          case "cheer":
            Cheer($currentPlayer);
            break;
          case "bounce_an_aura":
            $search = "MYAURAS";
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, $search);
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an aura to return to hand", 1);
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "-", 1);
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
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "-", 1);
            AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
            break;
          case "boo":
            Boo($currentPlayer);
            break;
          case "remove_hero_abilities":
            $targetPlayer = str_contains($target, "MY") ? $currentPlayer : $otherPlayer;
            AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose if you want to discard or lose your hero ability.");
            AddDecisionQueue("YESNO", $targetPlayer, "if_you_want_to_discard_or_lose_hero_ability", 1);
            AddDecisionQueue("NOPASS", $targetPlayer, "-", 1);
            AddDecisionQueue("FINDINDICES", $targetPlayer, "HAND", 1);
            AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose a card to discard", 1);
            AddDecisionQueue("CHOOSEHAND", $targetPlayer, "<-", 1);
            AddDecisionQueue("MULTIREMOVEHAND", $targetPlayer, "-", 1);
            AddDecisionQueue("DISCARDCARD", $targetPlayer, "HAND", 1);
            AddDecisionQueue("ELSE", $targetPlayer, "-");
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
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "-", 1);
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
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "-", 1);
            AddDecisionQueue("MZOP", $currentPlayer, "GAINCONTROL", 1);
            break;
          case "boo":
            Boo($currentPlayer);
            break;
          case "deal_2_damage":
            $targetPlayer = str_contains($target, "MY") ? $currentPlayer : $otherPlayer;
            $condition = false;
            for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
              if ($combatChain[$i + 1] == $currentPlayer && ModifiedPowerValue($combatChain[$i], $currentPlayer, "CC") + $combatChain[$i+5] >= 6) $condition = true;
              foreach ($chainLinks as $link) {
                for ($i = 0; $i < count($link); $i += ChainLinksPieces()) {
                  if ($link[$i+1] == $currentPlayer && ModifiedPowerValue($link[$i], $currentPlayer, "CC") + $link[$i+4] > 6) $condition = true;
                }
              }
            }
            if ($condition) {
              AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose if you want to discard or take 2 damage.");
              AddDecisionQueue("YESNO", $targetPlayer, "if_you_want_to_discard_or_lose_hero_ability", 1);
              AddDecisionQueue("NOPASS", $targetPlayer, "-", 1);
              AddDecisionQueue("FINDINDICES", $targetPlayer, "HAND", 1);
              AddDecisionQueue("SETDQCONTEXT", $targetPlayer, "Choose a card to discard", 1);
              AddDecisionQueue("CHOOSEHAND", $targetPlayer, "<-", 1);
              AddDecisionQueue("MULTIREMOVEHAND", $targetPlayer, "-", 1);
              AddDecisionQueue("DISCARDCARD", $targetPlayer, "HAND", 1);
              AddDecisionQueue("ELSE", $targetPlayer, "-");
              AddDecisionQueue("TAKEDAMAGE", $targetPlayer, 2, 1);
            }
            break;
          default: break;
        }
      }
      break;
    default:
      break;
  }
  return "";
}

function SUPHitEffect($cardID): void
{
  switch ($cardID) {
    default:
      break;
  }
}

function BOO($player)
{
  global $CS_BooedThisTurn;
  SetClassState($player, $CS_BooedThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("BOOOOO! The crowd jeers at " . CardLink($char[0], $char[0]) . "!");
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

function Cheer($player)
{
  global $CS_CheeredThisTurn;
  SetClassState($player, $CS_CheeredThisTurn, 1);
  $char = GetPlayerCharacter($player);
  WriteLog("Let's go! The crowd cheers for " . CardLink($char[0], $char[0]) . "!");
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

function GetSuspenseAuras()
{
  return [];
}

function RemoveSuspense()
{

}

function AddSuspense()
{

}