<?php

function MPGAbilityType($cardID, $index = -1, $from = "-"): string
{
  global $currentPlayer;
  return match ($cardID) {
    "richter_scale" => "A",
    "gauntlet_of_boulderhold" => "A",
    "craterhoof" => "A",
    default => ""
  };
}

function MPGAbilityHasGoAgain($cardID): bool
{
  return match ($cardID) {
    "gauntlet_of_boulderhold" => true,
    "craterhoof" => true,
    default => false
  };
}

function MPGEffectPowerModifier($cardID): int
{
  return match ($cardID) {
    "draw_a_crowd_blue" => 3,
    "fearless_confrontation_blue" => -1,
    "gauntlet_of_boulderhold" => 2,
    "overswing_red" => 3,
    "overswing_yellow" => 2,
    "overswing_blue" => 1,
    default => 0
  };
}

function MPGCombatEffectActive($cardID, $attackID, $from="-"): bool
{
  global $mainPlayer;
  return match($cardID) {
    "valda_seismic_impact" => HasCrush($attackID),
    "draw_a_crowd_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA"),
    "leave_a_dent_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer),
    "craterhoof" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA") && AttackPlayedFrom() == "ARS",
    "gauntlet_of_boulderhold" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA"),
    "fearless_confrontation_blue" => true,
    "overswing_red", "overswing_yellow", "overswing_blue" => ClassContains($attackID, "GUARDIAN", $mainPlayer) && TypeContains($attackID, "AA"),
    default => false
  };
}

function MPGAbilityCost($cardID): int
{
  return match($cardID) {
    "gauntlet_of_boulderhold" => 3,
    "craterhoof" => 3,
    default => 0
  };
}

function MPGPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = ""): string
{
  global $currentPlayer;
  $otherPlayer = 3 - $currentPlayer;
  switch ($cardID) {
    case "seismic_eruption_yellow":
      PlayAura("seismic_surge", $currentPlayer, 3, true);
      return "";
    case "draw_a_crowd_blue":
      Draw($currentPlayer, effectSource:$cardID);
      Draw($otherPlayer, effectSource:$cardID);
      return "";
    case "visit_anvilheim_blue":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;hasNegCounters=true;class=GUARDIAN");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZOP", $currentPlayer, "GETCARDINDEX", 1);
      AddDecisionQueue("REMOVEDEFCOUNTER", $currentPlayer, $resourcesPaid, 1);
      return "";
    case "leave_a_dent_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "hostile_encroachment_red":
      if (IsHeroAttackTarget()) AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      return "";
    case "ley_line_of_the_old_ones_blue":
      AddLayer("TRIGGER", $currentPlayer, $cardID);
      return "";
    case "richter_scale":
      PlayAura("seismic_surge", $currentPlayer, 2, true, effectController:$currentPlayer, effectSource:$cardID);
      return "";
    case "craterhoof":
    case "gauntlet_of_boulderhold":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "aftershock_red":
    case "aftershock_yellow":
    case "aftershock_blue":
      AddLayer("TRIGGER", $currentPlayer, $cardID, additionalCosts:"ATTACKTRIGGER");
      return "";
    case "tectonic_instability_blue":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, 0);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if (count(GetArsenal($currentPlayer)) > 0) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL", 1);
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("DRAW", $currentPlayer, $cardID, 1);
        AddDecisionQueue("NOTEQUALPASS", $currentPlayer, 1, 1);
        AddDecisionQueue("INCDQVARIFNOTPASS", $currentPlayer, "0", 1);
      }
      if (count(GetArsenal($otherPlayer)) > 0) {
        AddDecisionQueue("FINDINDICES", $otherPlayer, "ARSENAL", 1);
        AddDecisionQueue("CHOOSEARSENAL", $otherPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $otherPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $otherPlayer, "-", 1);
        AddDecisionQueue("DRAW", $otherPlayer, $cardID, 1);
        AddDecisionQueue("NOTEQUALPASS", $currentPlayer, 1, 1);
        AddDecisionQueue("INCDQVARIFNOTPASS", $currentPlayer, "0", 1);
      }
      AddDecisionQueue("PLAYAURA", $currentPlayer, "seismic_surge-{0}-$cardID", 1);
      return "";
    case "overswing_red":
    case "overswing_yellow":
    case "overswing_blue":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    default:
      return "";
  }
}

function BlindPlayer($player, $unblind=false, $excludeEquips=false)
{
  global $chainLinks;
  $deck = &GetDeck($player);
  $deckCount = count($deck);
  $deckPieces = DeckPieces();
  for ($i = 0; $i < $deckCount; $i += $deckPieces) {
    $deck[$i] = BlindCard($deck[$i], $unblind, $excludeEquips);
  }

  $discard = &GetDiscard($player);
  $discardCount = count($discard);
  $discardPieces = DiscardPieces();
  for ($i = 0; $i < $discardCount; $i += $discardPieces) {
    $discard[$i] = BlindCard($discard[$i], $unblind, $excludeEquips);
  }

  $banish = &GetBanish($player);
  $banishCount = count($banish);
  $banishPieces = BanishPieces();
  for ($i = 0; $i < $banishCount; $i += $banishPieces) {
    $banish[$i] = BlindCard($banish[$i], $unblind, $excludeEquips);
  }

  $pitch = &GetPitch($player);
  $pitchCount = count($pitch);
  $pitchPieces = PitchPieces();
  for ($i = 0; $i < $pitchCount; $i += $pitchPieces) {
    $pitch[$i] = BlindCard($pitch[$i], $unblind, $excludeEquips);
  }

  $hand = &GetHand($player);
  $handCount = count($hand);
  $handPieces = HandPieces();
  for ($i = 0; $i < $handCount; $i += $handPieces) {
    $hand[$i] = BlindCard($hand[$i], $unblind, $excludeEquips);
  }

  $arsenal = &GetArsenal($player);
  $arsenalCount = count($arsenal);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    $arsenal[$i] = BlindCard($arsenal[$i], $unblind, $excludeEquips);
  }

  $char = &GetPlayerCharacter($player);
  $charCount = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $charCount; $i += $charPieces) {
    $char[$i] = BlindCard($char[$i], $unblind, $excludeEquips);
  }

  $items = &GetItems($player);
  $itemsCount = count($items);
  $itemsPieces = ItemPieces();
  for ($i = 0; $i < $itemsCount; $i += $itemsPieces) {
    $items[$i] = BlindCard($items[$i], $unblind, $excludeEquips);
  }

  $auras = &GetAuras($player);
  $aurasCount = count($auras);
  $aurasPieces = AuraPieces();
  for ($i = 0; $i < $aurasCount; $i += $aurasPieces) {
    $auras[$i] = BlindCard($auras[$i], $unblind, $excludeEquips);
  }

  $allies = &GetAllies($player);
  $alliesCount = count($allies);
  $alliesPieces = AllyPieces();
  for ($i = 0; $i < $alliesCount; $i += $alliesPieces) {
    $allies[$i] = BlindCard($allies[$i], $unblind, $excludeEquips);
  }

  $chainLinksPieces = ChainLinksPieces();
  foreach ($chainLinks as &$link) {
    $linkCount = count($link);
    for ($i = 0; $i < $linkCount; $i += $chainLinksPieces) {
      if ($link[$i + 1] == $player) {
        $link[$i] = BlindCard($link[$i], $unblind, $excludeEquips);
      }
    }
    unset($link);
  }
}