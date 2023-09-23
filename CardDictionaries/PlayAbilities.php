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
      case "TCC051":
        Draw(1);
        Draw(2);
        return "";
      case "TCC052":
        PlayAura("TCC107", 1);
        PlayAura("TCC107", 2);
        return "";
      case "TCC053":
        PlayAura("TCC105", 1);
        PlayAura("TCC105", 2);
        return "";
      case "TCC054":
        PlayAura("WTR225", 1);
        PlayAura("WTR225", 2);
        return "";
      case "TCC057":
        $numPitch = SearchCount(SearchPitch($currentPlayer));
        AddCurrentTurnEffect($cardID . "," . ($numPitch*2), $currentPlayer);
        return "";
      case "TCC058": case "TCC062": case "TCC075":
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
      case "TCC086": case "TCC094":
        AddCurrentTurnEffectFromCombat($cardID, $currentPlayer);
        break;
      default: return "";
    }
  }


  function EVOPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
  {
    global $mainPlayer, $currentPlayer, $defPlayer;
    global $CS_NamesOfCardsPlayed, $CS_NumBoosted;
    $rv = "";
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch($cardID) {
      case "EVO007": case "EVO008":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO009":
        $evoAmt = EvoUpgradeAmount($currentPlayer);
        if($evoAmt >= 3) GiveAttackGoAgain();
        if($evoAmt >= 4) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO061": case "EVO062": case "EVO063":
        WriteLog("This is a partially manual card. Do not block with attack action cards with cost less than " . EvoUpgradeAmount($currentPlayer));
        return "";
      case "EVO101":
        $numScrap = 0;
        $costAry = explode(",", $additionalCosts);
        for($i=0; $i<count($costAry); ++$i) if($costAry[$i] == "SCRAP") ++$numScrap;
        if($numScrap > 0) GainResources($currentPlayer, $numScrap * 2);
        return "";
      case "EVO108": case "EVO109": case "EVO110":
        if($additionalCosts == "SCRAP") PlayAura("WTR225", $currentPlayer);
        return "";
      case "EVO126": case "EVO127": case "EVO128":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO129": case "EVO130": case "EVO131":
        if($additionalCosts == "SCRAP") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "EVO155": case "EVO156": case "EVO157":
        if(GetClassState($currentPlayer, $CS_NumBoosted) >= 2) AddCurrentTurnEffect($cardID, $currentPlayer);
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
      case "EVO242":
        $xVal = $resourcesPaid/2;
        PlayAura("ARC112", $currentPlayer, $xVal);
        if($xVal >= 6) {
          DiscardRandom($otherPlayer);
          DiscardRandom($otherPlayer);
          DiscardRandom($otherPlayer);
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
