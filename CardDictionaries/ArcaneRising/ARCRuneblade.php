<?php


  function ARCRunebladeCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC075": case "ARC076": return "C";
      case "ARC077": return "W";
      case "ARC078": return "E";
      case "ARC079": return "E";
      case "ARC080": return "AA";
      case "ARC081": return "A";
      case "ARC082": return "AA";
      case "ARC083": case "ARC084": return "A";
      case "ARC085": case "ARC086": case "ARC087": return "AA";
      case "ARC088": case "ARC089": case "ARC090": return "DR";
      case "ARC091": case "ARC092": case "ARC093": return "A";
      case "ARC094": case "ARC095": case "ARC096": return "AA";
      case "ARC097": case "ARC098": case "ARC099": return "AA";
      case "ARC100": case "ARC101": case "ARC102": return "AA";
      case "ARC103": case "ARC104": case "ARC105": return "AA";
      case "ARC106": case "ARC107": case "ARC108": return "A";
      case "ARC109": case "ARC110": case "ARC111": return "A";
      case "ARC112": return "T";
      default: return "";
    }
  }

  function ARCRunebladeCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC077": return "Sword";
      case "ARC078": return "Arms";
      case "ARC079": return "Head";
      case "ARC106": case "ARC107": case "ARC108": return "Aura";
      case "ARC112": return "Aura";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCRunebladeCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC080": return 6;
      case "ARC081": return 0;
      case "ARC082": return 9;
      case "ARC083": return 0;
      case "ARC084": return 1;
      case "ARC085": case "ARC086": case "ARC087": return 2;
      case "ARC088": case "ARC089": case "ARC090": return 1;
      case "ARC091": case "ARC092": case "ARC093": return 2;
      case "ARC094": case "ARC095": case "ARC096": return 3;
      case "ARC097": case "ARC098": case "ARC099": return 2;
      case "ARC100": case "ARC101": case "ARC102": return 3;
      case "ARC103": case "ARC104": case "ARC105": return 1;
      case "ARC106": case "ARC107": case "ARC108": return 1;
      case "ARC109": case "ARC110": case "ARC111": return 0;
      default: return 0;
    }
  }

  function ARCRunebladePitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC080": return 1;
      case "ARC081": return 1;
      case "ARC082": return 2;
      case "ARC083": return 3;
      case "ARC084": return 3;
      case "ARC085": case "ARC088": case "ARC091": case "ARC094": case "ARC097": case "ARC100": case "ARC103": case "ARC106": case "ARC109": return 1;
      case "ARC086": case "ARC089": case "ARC092": case "ARC095": case "ARC098": case "ARC101": case "ARC104": case "ARC107": case "ARC110": return 2;
      case "ARC087": case "ARC090": case "ARC093": case "ARC096": case "ARC099": case "ARC102": case "ARC105": case "ARC108": case "ARC111": return 3;
      default: return 0;
    }
  }

  function ARCRunebladeBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC075": case "ARC076": return 0;
      case "ARC077": return 0;
      case "ARC078": return 2;
      case "ARC079": return 0;
      case "ARC084": return 2;
      case "ARC088": return 4;
      case "ARC089": return 3;
      case "ARC090": return 2;
      case "ARC106": case "ARC107": case "ARC108":
      case "ARC109": case "ARC110": case "ARC111": return 2;
      default: return 3;
    }
  }

  function ARCRunebladeAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ARC077": return 1;
      case "ARC080": return 5;
      case "ARC082": return 9;
      case "ARC094": return 6;
      case "ARC095": return 5;
      case "ARC085": case "ARC096": case "ARC100": case "ARC103": return 4;
      case "ARC086": case "ARC097": case "ARC101": case "ARC104": return 3;
      case "ARC087": case "ARC098": case "ARC102": case "ARC105": return 2;
      case "ARC099": return 1;
      default: return 0;
    }
  }

  function ARCRunebladePlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ARC078":
        PlayAura("ARC112", $currentPlayer);
        return "Grasp of the Arknight created a runechant.";
      case "ARC079":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARC079");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("CROWNOFDICHOTOMY", $currentPlayer, "-", 1);
        return "Crown of Dichotomy let you put cards from your discard on top of your deck.";
      case "ARC081":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Mordred Tide gives you an extra runechant whenever you create one or more.";
      case "ARC083":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDACTION");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDDISCARD", $currentPlayer, "HAND", 1);
        AddDecisionQueue("BECOMETHEARKNIGHT", $currentPlayer, "-", 1);
        return "";
      case "ARC084":
        $deck = &GetDeck($currentPlayer);
        if(count($deck) < 2) return "Not enough cards in deck.";
        RevealCards($deck[0] . "," . $deck[1]);
        $d0Type = CardType($deck[0]);
        $d1Type = CardType($deck[1]);
        if(($d0Type == "AA" && $d1Type == "A") || ($d1Type == "AA" && $d0Type == "A"))
        {
          AddPlayerHand($deck[0], $currentPlayer, $deck);
          AddPlayerHand($deck[1], $currentPlayer, $deck);
          unset($deck[1]);
          unset($deck[0]);
          $deck = array_values($deck);
        }
        return "";
      case "ARC085": case "ARC086": case "ARC087":
        PlayAura("ARC112", $currentPlayer, 2);
        return "Spellblade Assault created two runechants.";
      case "ARC088": case "ARC089": case "ARC090":
        PlayAura("ARC112", $currentPlayer);
        return "Reduce to Runechant created a runechant.";
      case "ARC091": case "ARC092": case "ARC093":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("ARC112", $currentPlayer);
        return "Oath of the Arknight gives your next Runeblade attack +" . EffectAttackModifier($cardID) . " and created a runechant.";
      case "ARC097": case "ARC098": case "ARC099": MyDrawCard(); return "Drawn to the Dark Dimension drew a card.";
      case "ARC103": case "ARC104": case "ARC105":
        PlayAura("ARC112", $currentPlayer);
        return "Spellblade Strike created a runechant.";
      case "ARC109": PlayAura("ARC112", $currentPlayer, 3); return "Read the Runes created 3 runechants.";
      case "ARC110": PlayAura("ARC112", $currentPlayer, 2); return "Read the Runes created 2 runechants.";
      case "ARC111": PlayAura("ARC112", $currentPlayer); return "Read the Runes created a runechant.";
      default: return "";
    }
  }

  function ARCRunebladeHitEffect($cardID)
  {
    global $combatChainState, $CCS_AttackTotalDamage, $mainPlayer;
    switch($cardID)
    {
      case "ARC077":
        PlayAura("ARC112", $mainPlayer);
        WriteLog("Nebula Blade created a runechant.");
        break;
      case "ARC080":
        for($i=0; $i<$combatChainState[$CCS_AttackTotalDamage]; ++$i) PlayAura("ARC112", $mainPlayer);
        break;
      default: break;
    }
  }

  function NumRunechants($player)
  {
    $auras = &GetAuras($player);
    $count = 0;
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      if($auras[$i] == "ARC112") ++$count;
    }
    return $count;
  }

  function ViseraiPlayCard($cardID)
  {
    global $currentPlayer, $CS_NumNonAttackCards;
    $target = CardType($cardID) == "A" ? 1 : 0;//Don't let a non-attack action count itself
    if(CardClass($cardID) == "RUNEBLADE" && GetClassState($currentPlayer, $CS_NumNonAttackCards) > $target)
    {
      PlayAura("ARC112", $currentPlayer);
      WriteLog("Viserai created a runechant token.");
    }
  }

?>

