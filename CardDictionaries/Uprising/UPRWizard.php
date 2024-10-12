<?php

  function UPRWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $mainPlayer, $CS_ArcaneDamagePrevention, $CS_LastDynCost;
    $rv = "";
    switch($cardID)
    {
      case "UPR104":
        DealArcane(3, 2, "PLAYCARD", $cardID, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "UPR105":
        if(DelimStringContains($additionalCosts, "ICE")) {
          $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
          $damage = 5 + CountAura("ELE111", $otherPlayer) + SearchCount(SearchAura($otherPlayer, "", "Affliction", -1, -1, "", "ICE")) + FrozenCount($otherPlayer);
        }
        else $damage = 5;
        DealArcane($damage, 0, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR106": case "UPR107": case "UPR108":
        if(DelimStringContains($additionalCosts, "ICE")) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR109":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $numFrostBite = GetClassState($currentPlayer, $CS_LastDynCost)/2;
        PlayAura("ELE111", $otherPlayer, $numFrostBite, effectController: $currentPlayer);
        $amountArcane = SearchCount(SearchAurasForCard("ELE111", $otherPlayer));
        if(DelimStringContains($additionalCosts, "ICE")) DealArcane($amountArcane, 0, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR110": case "UPR111": case "UPR112":
        if($cardID == "UPR110") $damage = 5;
        else if($cardID == "UPR111") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        if(DelimStringContains($additionalCosts, "ICE")) {
          AddDecisionQueue("PASSPARAMETER", $currentPlayer, $target . "-" . $cardID, 1);
          AddDecisionQueue("SUCCUMBTOWINTER", $currentPlayer, "-", 1);
        }
        return "";
      case "UPR113": case "UPR114": case "UPR115":
        if($cardID == "UPR113") $damage = 5;
        else if($cardID == "UPR114") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "UPR116": case "UPR117": case "UPR118":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $hand = &GetHand($otherPlayer);
        $cards = "";
        for($i=0; $i<count($hand); ++$i) {
          if($cards != "") $cards .= ",";
          $cards .= $hand[$i];
        }
        $cardsRevealed = RevealCards($cards);
        if($cardsRevealed && DelimStringContains($additionalCosts, "ICE")) {
          if($cardID == "UPR116") $maxCost = 2;
          else if($cardID == "UPR117") $maxCost = 1;
          else $maxCost = 0;
          AddDecisionQueue("FINDINDICES", $otherPlayer, "HANDACTIONMAXCOST," . $maxCost);
          AddDecisionQueue("CHOOSETHEIRHAND", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
          AddDecisionQueue("MULTIADDTOPDECK", $otherPlayer, "-", 1);
        }
        return "";
      case "UPR119": case "UPR120": case "UPR121":
        if($cardID == "UPR119") $damage = 3;
        else if($cardID == "UPR120") $damage = 2;
        else $damage = 1;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "UPR122": case "UPR123": case "UPR124":
        if($cardID == "UPR122") $damage = 4;
        else if($cardID == "UPR123") $damage = 3;
        else $damage = 2;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, false, false, !DelimStringContains($additionalCosts, "ICE"), resolvedTarget: $target);
        return "";
      case "UPR125":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR127": case "UPR128": case "UPR129":
        if($cardID == "UPR127") $damage = 4;
        else if($cardID == "UPR128") $damage = 3;
        else $damage = 2;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR130": case "UPR131": case "UPR132":
        if($cardID == "UPR130") $damage = 3;
        else if($cardID == "UPR131") $damage = 2;
        else $damage = 1;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR133": case "UPR134": case "UPR135":
        if($cardID == "UPR133") $damage = 5;
        else if($cardID == "UPR134") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR165":
        if($currentPlayer != $mainPlayer) $damage = 3;
        else $damage = 2;
        DealArcane($damage, 0, "ABILITY", $cardID, resolvedTarget: $target);
        return "";
      case "UPR166":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR167":
        GainResources($currentPlayer, 1);
        return "Gain 1 resource.";
      case "UPR168":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXINDICES,2");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "TOMEOFDUPLICITY", 1);
        return "";
      case "UPR169":
        if(substr($from, 0, 5) != "THEIR") NegateLayer($target, "HAND");
        else NegateLayer($target, "THEIRHAND"); 
        if($currentPlayer != $mainPlayer) GainActionPoints(1, $mainPlayer);
        return "";
      case "UPR170": case "UPR171": case "UPR172":
        if($cardID == "UPR170") $damage = 4;
        else if($cardID == "UPR171") $damage = 3;
        else $damage = 2;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_ArcaneDamagePrevention, 1);
        return "";
      case "UPR173": case "UPR174": case "UPR175":
        if($cardID == "UPR173") $damage = 3;
        else if($cardID == "UPR174") $damage = 2;
        else $damage = 1;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer, resolvedTarget: $target);
        return "";
      case "UPR179": case "UPR180": case "UPR181":
        if($cardID == "UPR179") $maxAllies = 3;
        else if($cardID == "UPR180") $maxAllies = 2;
        else $maxAllies = 1;
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $allies = &GetAllies($otherPlayer);
        if(count($allies) < $maxAllies) $maxAllies = count($allies);
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
    $numFrostHex = SearchCount(SearchAurasForCard("UPR126", $player));
    for($i=0; $i<$numFrostHex; ++$i)
    {
      DealArcane(1, 4, "TRIGGER", "ELE111", false, $player);
    }
  }


?>
