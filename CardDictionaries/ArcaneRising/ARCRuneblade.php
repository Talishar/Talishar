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
        return "";
      case "ARC081":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ARC083":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDACTION");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to discard (or pass)", 1);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "BECOMETHEARKNIGHT", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "ARC084":
        $deck = &GetDeck($currentPlayer);
        if(count($deck) < 2) return "Not enough cards in deck.";
        if(!RevealCards($deck[0] . "," . $deck[1])) return "Cannot reveal cards.";
        $type1 = CardType($deck[0]);
        $type2 = CardType($deck[1]);
        if(($type1 == "AA" && $type2 == "A") || ($type2 == "AA" && $type1 == "A"))
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
        return "";
      case "ARC088": case "ARC089": case "ARC090":
        PlayAura("ARC112", $currentPlayer);
        return "";
      case "ARC091": case "ARC092": case "ARC093":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        PlayAura("ARC112", $currentPlayer);
        return "";
      case "ARC097": case "ARC098": case "ARC099": Draw($currentPlayer); return "";
      case "ARC103": case "ARC104": case "ARC105":
        PlayAura("ARC112", $currentPlayer);
        return "";
      case "ARC109": PlayAura("ARC112", $currentPlayer, 3); return "";
      case "ARC110": PlayAura("ARC112", $currentPlayer, 2); return "";
      case "ARC111": PlayAura("ARC112", $currentPlayer); return "";
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
        break;
      case "ARC080":
        $damageDone = $combatChainState[$CCS_DamageDealt];
        PlayAura("ARC112", $mainPlayer, $damageDone);
        break;
      default: break;
    }
    return "";
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
    $target = CardType($cardID) == "A" ? 1 : 0;
    if(ClassContains($cardID, "RUNEBLADE", $currentPlayer) && GetClassState($currentPlayer, $CS_NumNonAttackCards) > $target)
    {
      PlayAura("ARC112", $currentPlayer);
    }
  }

?>
