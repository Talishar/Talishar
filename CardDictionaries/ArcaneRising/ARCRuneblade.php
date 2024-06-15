<?php


  function ARCRunebladePlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "ARC078":
        PlayAura("ARC112", $currentPlayer);
        return "";
      case "ARC079":
        MZMoveCard($currentPlayer, "MYDISCARD:type=A&MYDISCARD:type=AA", "MYTOPDECK");
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "CROWNOFDICHOTOMY", 1);
        return "";
      case "ARC081":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "ARC083":
        AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:type=A&MYHAND:type=AA");
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to discard", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        AddDecisionQueue("DISCARDCARD", $currentPlayer, "HAND-".$currentPlayer, 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "BECOMETHEARKNIGHT", 1);
        return "";
      case "ARC084":
        $deck = new Deck($currentPlayer);
        $deck->Reveal(2);
        $cards = explode(",", $deck->Top(amount:2));
        $type1 = CardType($cards[0]);
        $type2 = CardType($cards[1]);
        if(($type1 == "AA" && $type2 == "A") || ($type2 == "AA" && $type1 == "A")) {
          $deck->Top(remove:true, amount:2);
          AddPlayerHand($cards[0], $currentPlayer, "HAND");
          AddPlayerHand($cards[1], $currentPlayer, "HAND");
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
      case "ARC103": case "ARC104": case "ARC105": PlayAura("ARC112", $currentPlayer); return "";
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
      if(CardNameContains($auras[$i], "Runechant", $player)) ++$count;
    }
    return $count;
  }

  function ViseraiPlayCard($cardID)
  {
    global $currentPlayer, $CS_NumNonAttackCards;
    $target = CardType($cardID) == "A" ? 1 : 0;
    if(ClassContains($cardID, "RUNEBLADE", $currentPlayer) && GetClassState($currentPlayer, $CS_NumNonAttackCards) > $target) {
      PlayAura("ARC112", $currentPlayer);
    }
  }

?>
