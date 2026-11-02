<?php

  function UPRWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $mainPlayer, $CS_ArcaneDamagePrevention, $layers;
    $rv = "";
    switch($cardID)
    {
      case "encase_red":
        if (DelimStringContains($additionalCosts, "ICE")) $source = "$cardID|FUSED";
        else $source = $cardID;
        DealArcane(3, 2, "PLAYCARD", $source, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "freezing_point_red":
        if(DelimStringContains($additionalCosts, "ICE")) {
          $otherPlayer = (OtherPlayer($currentPlayer));
          $damage = 5 + CountAura("frostbite", $otherPlayer) + SearchCount(SearchAura($otherPlayer, "", "Affliction", -1, -1, "", "ICE")) + FrozenCount($otherPlayer);
        }
        else $damage = 5;
        DealArcane($damage, 0, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "sigil_of_permafrost_red": case "sigil_of_permafrost_yellow": case "sigil_of_permafrost_blue":
        if(DelimStringContains($additionalCosts, "ICE")) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "succumb_to_winter_red": case "succumb_to_winter_yellow": case "succumb_to_winter_blue":
        $damage = ByColor($cardID, 5, 4, 3);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        if(DelimStringContains($additionalCosts, "ICE")) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $target . "-" . $cardID, 1);
          AddDecisionQueue("SUCCUMBTOWINTER", $currentPlayer, "-", 1);
        }
        return "";
      case "aether_icevein_red": case "aether_icevein_yellow": case "aether_icevein_blue":
        $damage = ByColor($cardID, 5, 4, 3);
        if (DelimStringContains($additionalCosts, "ICE")) $source = "$cardID|FUSED";
        else $source = $cardID;
        DealArcane($damage, 2, "PLAYCARD", $source, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "brain_freeze_red": case "brain_freeze_yellow": case "brain_freeze_blue":
        $otherPlayer = (OtherPlayer($currentPlayer));
        $hand = &GetHand($otherPlayer);
        $cardsRevealed = RevealCards(implode(",", $hand));
        if($cardsRevealed && DelimStringContains($additionalCosts, "ICE")) {
          $maxCost = ByColor($cardID, 2, 1, 0);
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HANDACTIONMAXCOST," . $maxCost);
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("MULTIADDTOPDECK", $otherPlayer, "-", 1);
        }
        return "";
      case "icebind_red": case "icebind_yellow": case "icebind_blue":
        $damage = ByColor($cardID, 3, 2, 1);
        if (DelimStringContains($additionalCosts, "ICE")) $source = "$cardID|FUSED";
        else $source = $cardID;
        DealArcane($damage, 2, "PLAYCARD", $source, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "polar_cap_red": case "polar_cap_yellow": case "polar_cap_blue":
        $damage = ByColor($cardID, 4, 3, 2);
        if (DelimStringContains($additionalCosts, "ICE")) $source = "$cardID|FUSED";
        else $source = $cardID;
        DealArcane($damage, 2, "PLAYCARD", $source, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "conduit_of_frostburn":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "aether_hail_red": case "aether_hail_yellow": case "aether_hail_blue":
        $damage = ByColor($cardID, 4, 3, 2);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "frosting_red": case "frosting_yellow": case "frosting_blue":
        $damage = ByColor($cardID, 3, 2, 1);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "ice_bolt_red": case "ice_bolt_yellow": case "ice_bolt_blue":
        $damage = ByColor($cardID, 5, 4, 3);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "waning_moon":
        if($currentPlayer != $mainPlayer) $damage = 3;
        else $damage = 2;
        DealArcane($damage, 0, "ABILITY", $cardID, resolvedTarget: $target);
        return "";
      case "alluvion_constellas":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "spellfire_cloak":
        GainResources(1, $currentPlayer);
        return "Gain 1 resource.";
      case "rewind_blue":
        $index = intval(explode("-", $target, 2)[1]);
        $targetPlayer = $layers[$index + 1];
        if(substr($from, 0, 5) != "THEIR") NegateLayer($target, "HAND");
        else NegateLayer($target, "THEIRHAND"); 
        if($targetPlayer == $mainPlayer) GainActionPoints(1, $mainPlayer);
        return "";
      case "dampen_red": case "dampen_yellow": case "dampen_blue":
        $damage = ByColor($cardID, 4, 3, 2);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_ArcaneDamagePrevention, 1);
        return "";
      case "aether_dart_red": case "aether_dart_yellow": case "aether_dart_blue":
        $damage = ByColor($cardID, 3, 2, 1);
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "singe_red": case "singe_yellow": case "singe_blue":
        $maxAllies = ByColor($cardID, 3, 2, 1);
        $otherPlayer = (OtherPlayer($currentPlayer));
        $allies = &GetAllies($otherPlayer);
        $alliesCount = count($allies);
        if($alliesCount < $maxAllies) $maxAllies = $alliesCount;
        $damage = ArcaneDamage($cardID) + ConsumeArcaneBonus($currentPlayer);
        DealArcane($damage, 1, "PLAYCARD", $cardID, false, $currentPlayer, false, false, resolvedTarget: $target);
        for($i=1; $i<$maxAllies; ++$i) DealArcane($damage, 5, "PLAYCARD", $cardID, false, $currentPlayer, true, true);
        DealArcane($damage, 5, "PLAYCARD", $cardID, false, $currentPlayer, true, false);
        return "";
      default: return "";
    }
  }

  function UPRWizardHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

  function FrostHexEndTurnAbility($player)
  {
    $numFrostHex = SearchCount(SearchAurasForCard("frost_hex_blue", $player));
    for($i=0; $i<$numFrostHex; ++$i)
    {
      DealArcane(1, 4, "TRIGGER", "frostbite", false, $player);
    }
  }


?>
