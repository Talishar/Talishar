<?php

  function TCCPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID) {
      case "TCC035":
        AddCurrentTurnEffect($cardID, $defPlayer);
        return "";
      case "TCC057":
        $numPitch = SearchCount(SearchPitch($currentPlayer));
        AddCurrentTurnEffect($cardID . "," . ($numPitch*2), $currentPlayer);
        return "";
      case "TCC058":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "TCC061":
        MZMoveCard($currentPlayer, "MYDISCARD:class=BARD;type=AA", "MYHAND", may:false, isSubsequent:false);
        return "";
      case "TCC064":
        PlayAura("WTR225", $otherPlayer);
        return "";
      case "TCC065":
        GainHealth(1, $otherPlayer);
        return "";
      case "TCC066": case "TCC067"://TODO: Add right Aura
        PlayAura("DTD232", $otherPlayer);
        return "";
      case "TCC068":
        Draw($otherPlayer);
        return "";
      case "TCC069":
        MZMoveCard($otherPlayer, "MYDISCARD:type=AA", "MYBOTDECK", may:true);
        return "";
      case "TCC079":
        Draw($currentPlayer);
        return "";
      case "TCC082":
        BanishCardForPlayer("DYN065", $currentPlayer, "-", "TT", $currentPlayer);
        return "";
      case "TCC083":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        break;
      case "TCC086":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        break;
      default: return "";
    }
  }


  function EVOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer;
    global $CS_NamesOfCardsPlayed;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID) {
      case "EVO007": case "EVO008":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO235":
        $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "AA");
        if($options != "") {
          AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, $options);
          AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $currentPlayer, -1, 1);
        }
        return "";
      case "EVO239":
        $cardsPlayed = explode(",", GetClassState($currentPlayer, $CS_NamesOfCardsPlayed));
        for($i=0; $i<count($cardsPlayed); ++$i) {
          if(CardName($cardsPlayed[$i]) == "Wax On") {
            PlayAura("CRU075", $currentPlayer);
            break;
          }
        }
        return "";
      case "EVO245":
        Draw($currentPlayer);
        if(IsRoyal($currentPlayer)) Draw($currentPlayer);
        PrependDecisionQueue("OP", $currentPlayer, "BANISHHAND", 1);
        if(SearchCount(SearchHand($currentPlayer, pitch:1)) >= 2) {
          PrependDecisionQueue("ELSE", $currentPlayer, "-");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PitchCard($currentPlayer, "MYHAND:pitch=1");
          PrependDecisionQueue("NOPASS", $currentPlayer, "-");
          PrependDecisionQueue("YESNO", $currentPlayer, "if you want to pitch 2 red cards");
        }
        return "";
      case "EVO247":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      default: return "";
    }
  }

?>
