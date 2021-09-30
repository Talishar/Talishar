<?php


  function MONRunebladeCardType($cardID)
  {
    switch($cardID)
    {
      case "MON229": return "W";
      case "MON230": return "E";
      case "MON231": return "A";
      case "MON232": case "MON233": case "MON234": return "AA";
      case "MON235": case "MON236": case "MON237": return "AA";
      default: return "";
    }
  }

  function MONRunebladeCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON229": return "Scythe";
      case "MON230": return "Chest";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONRunebladeCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON232": case "MON233": case "MON234": return 1;
      case "MON235": case "MON236": case "MON237": return 0;
      default: return 0;
    }
  }

  function MONRunebladePitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON231": case "MON232": case "MON235": return 1;
      case "MON233": case "MON236": return 2;
      case "MON235": case "MON237": return 3;
      default: return 0;
    }
  }

  function MONRunebladeBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON229": return 0;
      case "MON230": return 1;
      case "MON231": return 2;
      default: return 3;
    }
  }

  function MONRunebladeAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON229": case "MON232": case "MON235": return 3;
      case "MON233": case "MON236": return 2;
      case "MON234": case "MON237": return 1;
      default: return 0;
    }
  }

  function MONRunebladePlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "MON229":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "Dread Scythe deals 1 arcane damage.";
      case "MON230":
        GainResources($currentPlayer, 2);
        return "Aether Ironweave gaines 2 resources.";
      case "MON231":
        $numRevealed = 3 + $resourcesPaid/2;
        AddDecisionQueue("FINDINDICES", $currentPlayer, "FIRSTXDECK," . $numRevealed);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SONATAARCANIX", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTICHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("SONATAARCANIXSTEP2", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "MON232": case "MON233": case "MON234":
        DealArcane(2, 0, "PLAYCARD", $cardID);
        return "Vexing Malice deals 2 arcane damage.";
      case "MON235": case "MON236": case "MON237":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "Arcanic Crackle deals 1 arcane damage.";
      default: return "";
    }
  }

  function MONRunebladeHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

?>

