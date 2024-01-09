<?php

  function MONBrutePlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    $rv = "";
    switch($cardID)
    {
      case "MON121":
        $numBD = SearchCount(SearchBanish($currentPlayer, "", "", -1, -1, "", "", true));
        $damage = 6 - $numBD;
        DamageTrigger($currentPlayer, $damage, "PLAYCARD", $cardID);
        return "";
      case "MON125":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:$cardID) >= 6) {
          MZMoveCard($currentPlayer, "MYDECK:bloodDebtOnly=true", "MYBANISH,DECK,-", may:true);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
        }
        return "";
      case "MON138": case "MON139": case "MON140":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:$cardID) >= 6) {
          AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDISCARD&THEIRDISCARD");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish with Deadwood Rumbler");
          AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
          AddDecisionQueue("MZBANISH", $currentPlayer, "GY,-," . $currentPlayer, 1);
          AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
        }
        return "";
      case "MON150": case "MON151": case "MON152":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON221":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:$cardID) >= 6) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON222":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:$cardID) >= 6) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      case "MON223": case "MON224": case "MON225":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(ModifiedAttackValue($card, $currentPlayer, "HAND", source:$cardID) >= 6) AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
      default: return "";
    }
  }

  function MONBruteHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

  function RandomBanish3GY($cardID)
  {
    global $currentPlayer;
    $hand = &GetHand($currentPlayer);
    $discard = &GetDiscard($currentPlayer);
    if(count($discard) < 3) return;
    $BanishedIncludes6 = false;
    for($i = 0; $i < 3; $i++) {
      $index = GetRandom() % count($discard);
      if(ModifiedAttackValue($discard[$index], $currentPlayer, "GY", source:$cardID) >= 6) $BanishedIncludes6 = true;
      BanishCardForPlayer($discard[$index], $currentPlayer, "DISCARD", "NA");
      unset($discard[$index]);
      $discard = array_values($discard);
    }
    return $BanishedIncludes6;
  }

  function LadyBarthimontAbility($player, $index)
  {
    $deck = new Deck($player);
    if($deck->Empty()) return;
    $topDeck = $deck->BanishTop("-", $player);
    if(ModifiedAttackValue($topDeck, $player, "DECK", source:"MON406") >= 6) {
      $arsenal = &GetArsenal($player);
      ++$arsenal[$index+3];
      AddCurrentTurnEffect("MON406", $player);
      if($arsenal[$index+3] == 2) MentorTrigger($player, $index);
    }
  }

  function MentorTrigger($player, $index, $specificCard="")
  {
    $cardID = RemoveArsenal($player, $index);
    BanishCardForPlayer($cardID, $player, "ARS", "-");
    if($specificCard != "") AddDecisionQueue("MULTIZONEINDICES", $player, "MYDECK:cardID=$specificCard");
    else AddDecisionQueue("MULTIZONEINDICES", $player, "MYDECK:specOnly=true");
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
    AddDecisionQueue("MZREMOVE", $player, "-", 1);
    AddDecisionQueue("ADDARSENAL", $player, "DECK-DOWN", 1);
    AddDecisionQueue("SHUFFLEDECK", $player, "-");
  }

?>
