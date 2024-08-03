<?php

function TerraEndFaseAbility($characterID, $player)
{
  $resources = &GetResources($player);
  $hand = &GetHand($player);
  $earthTalent = SearchCount(SearchPitch($player, talent: "EARTH"));
  if (($earthTalent >= 1) && (Count($hand) > 0 || $resources[0] > 0)) {
    AddDecisionQueue("YESNO", $player, "if you want to pay 1 to create a " . CardLink("HVY241", "HVY241"), 0, 1);
    AddDecisionQueue("NOPASS", $player, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
    AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
    AddDecisionQueue("WRITELOG", $player, CardLink($characterID, $characterID) . " created a " . CardLink("HVY241", "HVY241") . " token ", 1);
    AddDecisionQueue("PASSPARAMETER", $player, "HVY241", 1);
    AddDecisionQueue("PUTPLAY", $player, "-", 1);
  }
}

function TEREffectAttackModifier($cardID)
{
  return match ($cardID) {
    "TER002" => 1,
    default => 0,
  };
}

function TERCombatEffectActive($cardID)
{
  return match ($cardID) {
    "TER002" => true,
    default => false,
  };
}