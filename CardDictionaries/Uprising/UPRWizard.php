<?php


  function UPRWizardCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR102": case "UPR103": return "C";
      case "UPR104": return "A";
      case "UPR105": return "A";
      case "UPR106": case "UPR107": case "UPR108": return "DR";
      case "UPR109": return "A";
      case "UPR110": case "UPR111": case "UPR112": return "A";
      case "UPR113": case "UPR114": case "UPR115": return "A";
      case "UPR116": case "UPR117": case "UPR118": return "A";
      case "UPR119": case "UPR120": case "UPR121": return "A";
      case "UPR122": case "UPR123": case "UPR124": return "A";
      case "UPR126": return "A";
      case "UPR127": case "UPR128": case "UPR129": return "A";
      case "UPR130": case "UPR131": case "UPR132": return "A";
      case "UPR133": case "UPR134": case "UPR135": return "A";
      case "UPR165": return "W";
      case "UPR166": return "E";
      case "UPR167": return "E";
      case "UPR168": return "A";
      case "UPR169": return "I";
      case "UPR170": case "UPR171": case "UPR172": return "A";
      case "UPR173": case "UPR174": case "UPR175": return "A";
      case "UPR176": case "UPR177": case "UPR178": return "A";
      case "UPR179": case "UPR180": case "UPR181": return "A";
      default: return "";
    }
  }

  function UPRWizardCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR126": return "Affliction,Aura";
      case "UPR165": return "Staff";
      case "UPR166": return "Chest";
      case "UPR167": return "Chest";
      case "UPR176": case "UPR177": case "UPR178": return "Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRWizardCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR102": case "UPR103": return 0;
      case "UPR104": return 0;
      case "UPR105": return 3;
      case "UPR106": case "UPR107": case "UPR108": return 1;
      case "UPR109": return 0;
      case "UPR110": case "UPR111": case "UPR112": return 3;
      case "UPR113": case "UPR114": case "UPR115": return 3;
      case "UPR116": case "UPR117": case "UPR118": return 0;
      case "UPR119": case "UPR120": case "UPR121": return 0;
      case "UPR122": case "UPR123": case "UPR124": return 2;
      case "UPR126": return 3;
      case "UPR127": case "UPR128": case "UPR129": return 1;
      case "UPR130": case "UPR131": case "UPR132": return 0;
      case "UPR133": case "UPR134": case "UPR135": return 2;
      case "UPR168": return 3;
      case "UPR169": return 1;
      case "UPR170": case "UPR171": case "UPR172": return 2;
      case "UPR173": case "UPR174": case "UPR175": return 0;
      case "UPR176": case "UPR177": case "UPR178": return 0;
      case "UPR179": case "UPR180": case "UPR181": return 1;
      default: return 0;
    }
  }

  function UPRWizardPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR104": return 1;
      case "UPR105": return 1;
      case "UPR109": return 3;
      case "UPR126": return 3;
      case "UPR106": case "UPR110": case "UPR113": case "UPR116": case "UPR119": case "UPR122": return 1;
      case "UPR107": case "UPR111": case "UPR114": case "UPR117": case "UPR120": case "UPR123": return 2;
      case "UPR108": case "UPR112": case "UPR115": case "UPR118": case "UPR121": case "UPR124": return 3;
      case "UPR127": case "UPR130": case "UPR133": return 1;
      case "UPR128": case "UPR131": case "UPR134": return 2;
      case "UPR129": case "UPR132": case "UPR135": return 3;
      case "UPR168": return 3;
      case "UPR169": return 3;
      case "UPR170": case "UPR173": case "UPR176": case "UPR179": return 1;
      case "UPR171": case "UPR174": case "UPR177": case "UPR180": return 2;
      case "UPR172": case "UPR175": case "UPR178": case "UPR181": return 3;
      default: return 0;
    }
  }

  function UPRWizardBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR102": case "UPR103": return -1;
      case "UPR105": return 3;
      case "UPR106": return 4;
      case "UPR107": return 3;
      case "UPR108": return 2;
      case "UPR168": return 2;
      case "UPR169": return -1;
      case "UPR176": case "UPR177": case "UPR178": return 2;
      default: return 3;
    }
  }

  function UPRWizardAttackValue($cardID)
  {
    switch($cardID)
    {

      default: return 0;
    }
  }

  function UPRWizardPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer, $mainPlayer;
    $rv = "";
    switch($cardID)
    {
      case "UPR104":
        DealArcane(3, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1, 1);
          AddDecisionQueue("ENCASEDAMAGE", ($currentPlayer == 1 ? 2 : 1), "-", 1);
        }
        return "Encase deals 3 arcane.";
      case "UPR105":
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          $otherPlayer = ($player == 1 ? 2 : 1);
          $damage = 5 + CountAura("ELE111", $otherPlayer) + SearchCount(SearchAura($otherPlayer, "", "Ice Affliction")) + FrozenCount($otherPlayer);
        }
        else $damage = 5;
        DealArcane($damage, 1, "PLAYCARD", $cardID, false, $currentPlayer);
        return "";
      case "UPR106": case "UPR107": case "UPR108":
        $rv = "";
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Sigil of Permafrost makes your next arcane damage give frostbites.";
        }
        return $rv;
      case "UPR109":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $numFrostBite = $resourcesPaid/2;
        for($i=0; $i<$numFrostBite; ++$i)
        {
          PlayAura("ELE111", $otherPlayer);
        }
        $amountArcane = SearchCount(SearchAurasForCard("ELE111", $otherPlayer));
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          DealArcane($amountArcane, 0, "PLAYCARD", $cardID, false, $currentPlayer);
        }
        return "Ice Eternal created $numFrostBite Frostbites and dealt $amountArcane arcane.";
      case "UPR110": case "UPR111": case "UPR112":
        if($cardID == "UPR110") $damage = 5;
        else if($cardID == "UPR111") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "";
      case "UPR113": case "UPR114": case "UPR115":
        if($cardID == "UPR113") $damage = 5;
        else if($cardID == "UPR114") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1, 1);
          PayOrDiscard(($currentPlayer == 1 ? 2 : 1), 2);
        }
        return "";
      case "UPR116": case "UPR117": case "UPR118":
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $hand = &GetHand($otherPlayer);
        $cards = "";
        for($i=0; $i<count($hand); ++$i)
        {
          if($cards != "") $cards .= ",";
          $cards .= $hand[$i];
        }
        $cardsRevealed = RevealCards($cards);
        if($cardsRevealed && DelimStringContains($additionalCosts, "ICE"))
        {
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
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1, 1);
          AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,THEIRARS", 1);
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which card you want to freeze", 1);
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $currentPlayer, "FREEZE", 1);
        }
        return "Ice Bind deals $damage arcane.";
      case "UPR122": case "UPR123": case "UPR124":
        if($cardID == "UPR122") $damage = 4;
        else if($cardID == "UPR123") $damage = 3;
        else $damage = 2;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        if(DelimStringContains($additionalCosts, "ICE"))
        {
          AddDecisionQueue("LESSTHANPASS", $currentPlayer, 1, 1);
          AddDecisionQueue("PLAYAURA", ($currentPlayer == 1 ? 2 : 1), "ELE111", 1);
        }
        return "";
      case "UPR127": case "UPR128": case "UPR129":
        if($cardID == "UPR127") $damage = 4;
        else if($cardID == "UPR128") $damage = 3;
        else $damage = 2;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "";
      case "UPR130": case "UPR131": case "UPR132":
        if($cardID == "UPR130") $damage = 3;
        else if($cardID == "UPR131") $damage = 2;
        else $damage = 1;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
      case "UPR133": case "UPR134": case "UPR135":
        if($cardID == "UPR133") $damage = 5;
        else if($cardID == "UPR134") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "Ice Bolt deals $damage arcane.";
      case "UPR165":
        if($currentPlayer != $mainPlayer) $damage = 3;
        else $damage = 2;
        DealArcane($damage, 1, "ABILITY", $cardID);
        return "Waning Moon deals arcane damage.";
      case "UPR167":
        GainResources($currentPlayer, 1);
        return "Spellfire Cloak gained 1 resource.";
      case "UPR168":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPX,2");
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,INST", 1);
        return "Tome of Duplicity lets you look at the top 2 cards of your deck.";
      case "UPR173": case "UPR174": case "UPR175":
        if($cardID == "UPR173") $damage = 3;
        else if($cardID == "UPR174") $damage = 2;
        else $damage = 1;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "";
      case "UPR179": case "UPR180": case "UPR181":
        DealArcane(1, 0, "PLAYCARD", $cardID, false, $currentPlayer);
        if($cardID == "UPR179") $maxAllies = 3;
        else if($cardID == "UPR180") $maxAllies = 2;
        else $maxAllies = 1;
        $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
        $allies = &GetAllies($otherPlayer);
        if(count($allies) < $maxAllies) $maxAllies = count($allies);
        for($i=0; $i<$maxAllies; ++$i)
        {
          DealArcane(1, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        }
        return "Singe is a partially manual card. You have to make sure you damage each target no more than once.";
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
      DealArcane(1, 0, "TRIGGER", "ELE111", false, ($player == 1 ? 2 : 1));
    }
  }


?>
