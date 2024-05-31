<?php

  function MONRunebladePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    $rv = "";
    switch($cardID)
    {
      case "MON153": case "MON154":
        PlayAura("MON186", $currentPlayer, 1, true);
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON158":
        AddDecisionQueue("FINDINDICES", $otherPlayer, $cardID);
        AddDecisionQueue("MULTICHOOSETHEIRDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $otherPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $otherPlayer, "DISCARD", 1);
        AddDecisionQueue("INVERTEXISTENCE", $currentPlayer, "-", 1);
        return "";
      case "MON159": case "MON160": case "MON161":
        MZMoveCard($currentPlayer, "MYDISCARD:bloodDebtOnly=true;type=A", "MYBOTDECK", may:true);
        return "";
      case "MON162": case "MON163": case "MON164":
        if($cardID == "MON162") $optAmt = 3;
        else if($cardID == "MON163") $optAmt = 2;
        else $optAmt = 1;
        Opt($cardID, $optAmt);
        AddDecisionQueue("FINDINDICES", $currentPlayer, "TOPDECK");
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SPECIFICCARD", $currentPlayer, "DIMENXXIONALGATEWAY", 1);
        return "";
      case "MON165": case "MON166": case "MON167":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON168": case "MON169": case "MON170":
        if($from == "BANISH") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON174": case "MON175": case "MON176":
        if($from == "BANISH") AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON177": case "MON178": case "MON179":
        if($from == "BANISH") DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "MON183": case "MON184": case "MON185":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON229":
        if(!IsAllyAttackTarget()) DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      case "MON230":
        GainResources($currentPlayer, 2);
        return "";
      case "MON231":
        $xVal = $resourcesPaid/2;
        $numRevealed = 3 + $xVal;
        WriteLog(CardLink($cardID, $cardID) . " reveals " . $numRevealed . " cards.");
        AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKTOPXINDICES," . $numRevealed);
        AddDecisionQueue("DECKCARDS", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("SONATAARCANIX", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTICHOOSEDECK", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDHAND", $currentPlayer, "1", 1);
        AddDecisionQueue("SONATAARCANIXSTEP2", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        return "";
      case "MON232": case "MON233": case "MON234":
        DealArcane(2, 0, "PLAYCARD", $cardID);
        return "";
      case "MON235": case "MON236": case "MON237":
        DealArcane(1, 0, "PLAYCARD", $cardID);
        return "";
      default: return "";
    }
  }

  function MONRunebladeHitEffect($cardID)
  {
    global $mainPlayer;
    switch($cardID) {
      case "MON155":
        if(IsHeroAttackTarget()) DealArcane(1, 0, "PLAYCARD", "MON155", false, $mainPlayer);
        break;
      default: break;
    }
  }

  function UpTo2FromOpposingGraveyardIndices($player)
  {
    $discard = new Discard($player);
    if($discard->Empty()) return "";
    $rv = ($discard->NumCards() == 1 ? "1" : "2") . "-";
    $rv .= GetIndices($discard->NumCards()*DiscardPieces(), 0, DiscardPieces());
    return $rv;
  }

  function DimenxxionalCrossroadsPassive($cardID, $from)
  {
    global $currentPlayer, $CS_NumAttackCards, $CS_NumNonAttackCards;
    if($from != "BANISH") return;
    $type = CardType($cardID);
    if(($type == "AA" && GetClassState($currentPlayer, $CS_NumAttackCards) == 0) || $type == "A" && GetClassState($currentPlayer, $CS_NumNonAttackCards) == 1)
    {
      DealArcane(1, 0, "PLAYCARD", "MON157");
    }
  }

  function LordSutcliffeAbility($player, $index)
  {
    global $currentPlayer;
    WriteLog(CardLink("MON407", "MON407") . " deals 1 arcane damage to each player");
    DealArcane(1, 0, "ABILITY", "MON407", false, 1);
    AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1");
    AddDecisionQueue("LORDSUTCLIFFE", $currentPlayer, $index, 1);
    DealArcane(1, 0, "ABILITY", "MON407", false, 2);
    AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1");
    AddDecisionQueue("LORDSUTCLIFFE", $currentPlayer, $index, 1);
  }

  function LordSutcliffeAfterDQ($player, $parameter)
  {
    $index = $parameter;
    $arsenal = &GetArsenal($player);
    if(!ArsenalEmpty($player)) {
      $arsenal[$index+3] += 1;
      if($arsenal[$index+3] >= 3) MentorTrigger($player, $index);
    }
  }

?>
