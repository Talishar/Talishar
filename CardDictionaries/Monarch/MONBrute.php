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
        return "Does $damage damage to yourself";
      case "MON125":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(AttackValue($card) >= 6) {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("REVEALCARDS", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
          AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,NA", 1);
        }
        return "";
      case "MON138": case "MON139": case "MON140":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(AttackValue($card) >= 6)
        {
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
        if(AttackValue($card) >= 6) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "MON222":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(AttackValue($card) >= 6) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
        return "";
      case "MON223": case "MON224": case "MON225":
        Draw($currentPlayer);
        $card = DiscardRandom();
        if(AttackValue($card) >= 6) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
        }
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

  function RandomBanish3GY()
  {
    global $currentPlayer;
    $hand = &GetHand($currentPlayer);
    $discard = &GetDiscard($currentPlayer);
    if(count($discard) < 3) return;
    $BanishedIncludes6 = false;
    for($i = 0; $i < 3; $i++)
    {
      $index = GetRandom() % count($discard);
      if(AttackValue($discard[$index]) >= 6) $BanishedIncludes6 = true;
      BanishCardForPlayer($discard[$index], $currentPlayer, "DISCARD", "NA");
      unset($discard[$index]);
      $discard = array_values($discard);
    }
    return $BanishedIncludes6;
  }

  function LadyBarthimontAbility($player, $index)
  {
    $deck = &GetDeck($player);
    if(count($deck) == 0) return;
    $topDeck = array_shift($deck);
    BanishCardForPlayer($topDeck, $player, "DECK", "-");
    $log = CardLink("MON406", "MON406") . " banished " . CardLink($topDeck, $topDeck);
    if(AttackValue($topDeck) >= 6)
    {
      $arsenal = &GetArsenal($player);
      ++$arsenal[$index+3];
      AddCurrentTurnEffect("MON406", $player);
      if($arsenal[$index+3] == 2)
      {
        $log .= ", gave Dominate, and searched for a specialization card";
        MentorTrigger($player, $index);
      }
      else $log .= " and gave Dominate";
      WriteLog($log . ".");
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
