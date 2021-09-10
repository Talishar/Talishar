<?php


  function ELERangerCardType($cardID)
  {
    switch($cardID)
    {
      case "ELE031": case "ELE032": return "C";
      case "ELE033": case "ELE034": return "W";
      case "ELE035": return "AA";
      case "ELE036": return "AA";
      case "ELE037": return "A";
      case "ELE041": case "ELE042": case "ELE043": return "AA";
      case "ELE047": case "ELE048": case "ELE049": return "AA";
      case "ELE050": case "ELE051": case "ELE052": return "AA";
      case "ELE059": case "ELE060": case "ELE061": return "AA";
      case "ELE213": return "E";
      case "ELE215": return "A";
      case "ELE216": case "ELE217": case "ELE218": return "AA";
      default: return "";
    }
  }

  function ELERangerCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ELE033": case "ELE034": return "Bow";
      case "ELE035": case "ELE036": return "Arrow";
      case "ELE041": case "ELE042": case "ELE043":
      case "ELE047": case "ELE048": case "ELE049":
      case "ELE050": case "ELE051": case "ELE052":
      case "ELE059": case "ELE060": case "ELE061": return "Arrow";
      case "ELE213": return "Head";
      case "ELE216": case "ELE217": case "ELE218": return "Arrow";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ELERangerCardCost($cardID)
  {
    switch($cardID)
    {
      case "ELE035": return 1;
      case "ELE036": return 1;
      case "ELE037": return 0;
      case "ELE041": case "ELE042": case "ELE043": return 0;
      case "ELE047": case "ELE048": case "ELE049": return 1;
      case "ELE050": case "ELE051": case "ELE052": return 1;
      case "ELE059": case "ELE060": case "ELE061": return 1;
      //Normal Ranger
      case "ELE215": return 0;
      case "ELE216": case "ELE217": case "ELE218": return 0;
      default: return 0;
    }
  }

  function ELERangerPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ELE035": return 3;
      case "ELE036": return 2;
      case "ELE037": return 1;
      case "ELE041": case "ELE047": case "ELE050": case "ELE059": return 1;
      case "ELE042": case "ELE048": case "ELE051": case "ELE060": return 2;
      case "ELE043": case "ELE049": case "ELE052": case "ELE061": return 3;
      //Normal Ranger
      case "ELE215": return 1;
      case "ELE216": return 1;
      case "ELE217": return 2;
      case "ELE218": return 3;
      default: return 0;
    }
  }

  function ELERangerBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ELE031": case "ELE032": case "ELE033": case "ELE034": return 0;
      case "ELE037": return 2;
      case "ELE213": return 2;
      case "ELE215": return 2;
      default: return 3;
    }
  }

  function ELERangerAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ELE035": return 3;
      case "ELE036": return 4;
      case "ELE047": case "ELE050": case "ELE059": return 5;
      case "ELE041": case "ELE048": case "ELE051": case "ELE060": return 4;
      case "ELE042": case "ELE049": case "ELE052": case "ELE061": return 3;
      case "ELE043": return 2;
      //Normal Ranger
      case "ELE216": return 4;
      case "ELE217": return 3;
      case "ELE218": return 2;
      default: return 0;
    }
  }

  function ELERangerPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $otherPlayer;
    switch($cardID)
    {
      case "ELE031": case "ELE032":
        if(ArsenalHasFaceDownCard($currentPlayer))
        {
          $cardFlipped = SetMyArsenalFacing("UP");
          $rv = "Lexi turned " . $cardFlipped . " face up.";
          if(TalentContains($cardFlipped, "LIGHTNING")) AddCurrentTurnEffect("ELE031-1", $currentPlayer);
          else if(TalentContains($cardFlipped, "ICE")) PlayAura("ELE111", $otherPlayer);
        }
        return $rv;
      case "ELE033":
        if(ArsenalFull($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "1_Attack,Dominate");
        AddDecisionQueue("SHIVER", $currentPlayer, "-", 1);
        return "";
      case "ELE034":
        if(ArsenalFull($currentPlayer)) return "There is already a card in your arsenal, so you cannot put an arrow in your arsenal.";
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHANDARROW");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $currentPlayer, "HAND", 1);
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "1_Attack,Go_again");
        AddDecisionQueue("VOLTAIRE", $currentPlayer, "-", 1);
        return "";
      case "ELE035":
        Fuse($cardID, $currentPlayer, "ICE");
        AddCurrentTurnEffect($cardID . "-1", $otherPlayer);
        return "Frost Lock makes cards and activating abilities by the opponent cost 1 more this turn.";
      case "ELE036":
        Fuse($cardID, $currentPlayer, "LIGHTNING");
        return "";
      case "ELE037":
        Fuse($cardID, $currentPlayer, "ICE,LIGHTNING");
        AddCurrentTurnEffect("ELE037-1", $currentPlayer);
        return "";
      case "ELE041": case "ELE042": case "ELE043":
        Fuse($cardID, $currentPlayer, "LIGHTNING");
        return "";
      case "ELE047": case "ELE048": case "ELE049":
        Fuse($cardID, $currentPlayer, "LIGHTNING");
        return "";
      case "ELE050": case "ELE051": case "ELE052":
        Fuse($cardID, $currentPlayer, "ICE");
        return "";
      case "ELE059": case "ELE060": case "ELE061":
        Fuse($cardID, $currentPlayer, "LIGHTNING");
        return "";
      default: return "";
    }
  }

  function ELERangerHitEffect($cardID)
  {
    global $defPlayer, $combatChainState, $CCS_AttackFused, $mainPlayer;
    switch($cardID)
    {
      case "ELE036":
        if($combatChainState[$CCS_AttackFused]) DealDamage($defPlayer, NumEquipment($defPlayer), "ATTACKHIT");
        break;
      case "ELE216": case "ELE217": case "ELE218": if(HasIncreasedAttack()) Reload($mainPlayer); break;
      default: break;
    }
  }

  function Fuse($cardID, $player, $elements)
  {
    $elementArray = explode(",", $elements);
    $elementText = "";
    for($i=0; $i<count($elementArray); ++$i)
    {
      $element = $elementArray[$i];
      AddDecisionQueue("FINDINDICES", $player, "HAND" . $element, $i > 0 ? 1 : 0);
      AddDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
      AddDecisionQueue("REVEALMYCARD", $player, "<-", 1);
      if($i > 0) $elementText .= " and ";
      $elementText .= $element;
    }
    AddDecisionQueue("AFTERFUSE", $player, $cardID . "-" . $elements, 1);
    WriteLog("To get the effect of card " . $cardID . ", you may fuse " . $elementText . ".");
  }

  function FuseAbility($cardID, $player, $element)
  {
    $otherPlayer = ($player == 2 ? 1 : 2);
    switch($cardID)
    {
      case "ELE004": AddCurrentTurnEffect($cardID, $otherPlayer); break;
      case "ELE005": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE016": case "ELE017": case "ELE018": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE019": case "ELE020": case "ELE021": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE025": case "ELE026": case "ELE027": PlayAura("ELE111", $otherPlayer); break;
      case "ELE028": case "ELE029": case "ELE030": PlayAura("WTR075", $player); break;
      case "ELE035": AddCurrentTurnEffect($cardID . "-2", $player); break;
      case "ELE037": AddCurrentTurnEffect($cardID . "-2", $player); break;
      case "ELE041": case "ELE042": case "ELE043":
        SearchCharacterAddUses($player, 1, "W", "Bow");
        SearchCharacterAddEffect($player, "INSTANT", "W", "Bow");
        break;
      case "ELE047": case "ELE048": case "ELE049": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE050": case "ELE051": case "ELE052": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE059": case "ELE060": case "ELE061": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE097": case "ELE098": case "ELE099": AddCurrentTurnEffect($cardID, $player); break;
      case "ELE100": case "ELE101": case "ELE102": GiveAttackGoAgain(); break;
      default: break;
    }
  }

  function PayOrDiscard($player, $amount)
  {
    AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_" . $amount . "_to_avoid_discarding_a_card", 1, 1);
    AddDecisionQueue("FINDRESOURCECOST", $player, $amount, 1);
    AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
    AddDecisionQueue("FINDINDICES", $player, "HANDIFZERO", 1);
    AddDecisionQueue("CHOOSEHAND", $player, "<-", 1);
    AddDecisionQueue("DISCARDMYHAND", $player, "-", 1);
  }

?>

