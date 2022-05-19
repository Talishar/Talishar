<?php


  function UPRWizardCardType($cardID)
  {
    switch($cardID)
    {
      case "UPR102": case "UPR103": return "C";
      case "UPR104": return "A";
      case "UPR109": return "A";
      case "UPR119": case "UPR120": case "UPR121": return "A";
      case "UPR126": return "A";
      case "UPR133": case "UPR134": case "UPR135": return "A";
      default: return "";
    }
  }

  function UPRWizardCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR126": return "Affliction,Aura";
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
      case "UPR109": return 0;
      case "UPR119": case "UPR120": case "UPR121": return 0;
      case "UPR126": return 3;
      case "UPR133": case "UPR134": case "UPR135": return 2;
      default: return 0;
    }
  }

  function UPRWizardPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR104": return 1;
      case "UPR109": return 3;
      case "UPR126": return 3;
      case "UPR119": case "UPR133": return 1;
      case "UPR120": case "UPR134": return 2;
      case "UPR121": case "UPR135": return 3;
      default: return 0;
    }
  }

  function UPRWizardBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR102": case "UPR103": return -1;
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
    global $currentPlayer;
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
      case "UPR133": case "UPR134": case "UPR135":
        if($cardID == "UPR133") $damage = 5;
        else if($cardID == "UPR134") $damage = 4;
        else $damage = 3;
        DealArcane($damage, 2, "PLAYCARD", $cardID, false, $currentPlayer);
        return "Ice Bolt deals $damage arcane.";
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
