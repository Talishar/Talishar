<?php


  function ARCRunebladePlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ARC078":
        PlayAura("ARC112", $currentPlayer);
        return "Creates a runechant.";
      case "ARC079":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARC079");
        AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("CROWNOFDICHOTOMY", $currentPlayer, "-", 1);
        return "Lets you put cards from your discard on top of your deck.";
      case "ARC081":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Gives you an extra runechant whenever you create 1 or more.";
      case "ARC083":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDACTION");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to discard (or pass)", 1);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND", 1);
        AddDecisionQueue("BECOMETHEARKNIGHT", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "ARC084":
        $deck = &GetDeck($currentPlayer);
        if(count($deck) < 2) return "Not enough cards in deck.";
        if(!RevealCards($deck[0] . "," . $deck[1])) return "Cannot reveal cards.";
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
        return "Creates 2 runechants.";
      case "ARC088": case "ARC089": case "ARC090":
        PlayAura("ARC112", $currentPlayer);
        return "Creates a runechant.";
      case "ARC091": case "ARC092": case "ARC093":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("ARC112", $currentPlayer);
        return "Gives your next Runeblade attack +" . EffectAttackModifier($cardID) . " and created a runechant.";
      case "ARC097": case "ARC098": case "ARC099": MyDrawCard(); return "Draws a card.";
      case "ARC103": case "ARC104": case "ARC105":
        PlayAura("ARC112", $currentPlayer);
        return "Creates a runechant.";
      case "ARC109": PlayAura("ARC112", $currentPlayer, 3); return "Creates 3 runechants.";
      case "ARC110": PlayAura("ARC112", $currentPlayer, 2); return "Creates 2 runechants.";
      case "ARC111": PlayAura("ARC112", $currentPlayer); return "Creates a runechant.";
      default: return "";
    }
  }

  function ARCRunebladeHitEffect($cardID)
  {
    global $combatChainState, $mainPlayer, $CCS_DamageDealt;
    switch($cardID)
    {
      case "ARC077":
        PlayAura("ARC112", $mainPlayer);
        WriteLog(CardLink($cardID, $cardID) . " creates a runechant.");
        break;
      case "ARC080":
        $damageDone = $combatChainState[$CCS_DamageDealt];
        PlayAura("ARC112", $mainPlayer, $damageDone);
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
    if(ClassContains($cardID, "RUNEBLADE", $currentPlayer) && GetClassState($currentPlayer, $CS_NumNonAttackCards) > $target)
    {
      PlayAura("ARC112", $currentPlayer);
      WriteLog(CardLink("ARC075", "ARC075") . " created a runechant.");
    }
  }

?>
