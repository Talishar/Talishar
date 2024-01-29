<?php

  function UPRNinjaPlayAbility($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "UPR044": case "UPR045":
        if(SearchCurrentTurnEffects("OUT183", $currentPlayer)) return "";
        MZMoveCard($currentPlayer, "MYDISCARD:sameName=UPR101", "MYHAND");
        return "";
      case "UPR047":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR049":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "UPR050":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CCDEFLESSX," . NumDraconicChainLinks()-1);
        AddDecisionQueue("FILTER", $currentPlayer, "CombatChain-exclude-type-E", 1);
        AddDecisionQueue("FILTER", $currentPlayer, "CombatChain-exclude-subtype-evo", 1);
        AddDecisionQueue("FILTER", $currentPlayer, "CombatChain-include-player-" . ($currentPlayer == 1 ? 2 : 1), 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish", 1);
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVECOMBATCHAIN", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", ($currentPlayer == 1 ? 2 : 1), "CC,-", 1);
        return "";
      case "UPR057": case "UPR058": case "UPR059":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        MZMoveCard($currentPlayer, "MYDISCARD:sameName=UPR101", "MYHAND", may:true);
        return "";
      case "UPR060": case "UPR061": case "UPR062":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        return "";
      case "UPR159":
        GiveAttackGoAgain();
        return "";
      default: return "";
    }
  }

  function UPRNinjaHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState;
    switch($cardID) {
      case "UPR048":
        if(IsHeroAttackTarget() && NumChainLinksWithName("Phoenix Flame") >= 3) {
          Draw($mainPlayer);
          Draw($mainPlayer);
          Draw($mainPlayer);
        }
        break;
      case "UPR051": case "UPR052": case "UPR053":
        $deck = new Deck($mainPlayer);
        if($deck->Reveal() && CardType($deck->Top()) == "AA" && CardCost($deck->Top()) < NumDraconicChainLinks()) $deck->BanishTop("TT", $mainPlayer);
        break;
      case "UPR054": case "UPR055": case "UPR056":
      case "UPR075": case "UPR076": case "UPR077":
      case "UPR081": case "UPR082": case "UPR083":
        $numDraconicLinks = NumDraconicChainLinks();
        MZMoveCard($mainPlayer, "MYHAND:type=AA;maxCost=" . ($numDraconicLinks > 0 ? $numDraconicLinks - 1 : -2), "MYBANISH,HAND,TT", may:true);
        AddDecisionQueue("PASSPARAMETER", $mainPlayer, "MYBANISH", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "LASTMZINDEX", 1);
        AddDecisionQueue("MZOP", $mainPlayer, "GETUNIQUEID", 1);
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $mainPlayer, $cardID . ",HIT", 1);
        break;
      case "UPR161":
        $deck = new Deck($mainPlayer);
        if(HitsInCombatChain() >= 2) $deck->BanishTop(CardType($deck->Top()) == "AA" ? "NT" : "-", $mainPlayer);
        break;
      default: break;
    }
  }

?>
