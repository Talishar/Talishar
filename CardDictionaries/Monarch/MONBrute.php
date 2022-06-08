<?php

  function MONBruteCardType($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": return "C";
      case "MON121"; return "W";
      case "MON122"; return "E";
      case "MON123": return "AA";
      case "MON124": return "AA";
      case "MON125": return "AA";
      case "MON126": case "MON127": case "MON128": return "AA";
      case "MON129": case "MON130": case "MON131": return "AA";
      case "MON132": case "MON133": case "MON134": return "A";
      case "MON135": case "MON136": case "MON137": return "AA";
      case "MON138": case "MON139": case "MON140": return "AA";
      case "MON141": case "MON142": case "MON143": return "AA";
      case "MON144": case "MON145": case "MON146": return "AA";
      case "MON147": case "MON148": case "MON149": return "AA";
      case "MON150": case "MON151": case "MON152": return "A";
      case "MON221": return "W";
      case "MON222": return "A";
      case "MON223": case "MON224": case "MON225": return "AA";
      case "MON226": case "MON227": case "MON228": return "AA";
      case "MON406": return "M";
      default: return "";
    }
  }

  function MONBruteCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON121": return "Flail";
      case "MON122": return "Legs";
      case "MON221": return "Axe";
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONBruteCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON123": return 3;
      case "MON124": return 2;
      case "MON125": return 2;
      case "MON126": case "MON127": case "MON128": return 3;
      case "MON129": case "MON130": case "MON131": return 2;
      case "MON132": case "MON133": case "MON134": return 2;
      case "MON135": case "MON136": case "MON137": return 1;
      case "MON138": case "MON139": case "MON140": return 3;
      case "MON141": case "MON142": case "MON143": return 2;
      case "MON144": case "MON145": case "MON146": return 1;
      case "MON147": case "MON148": case "MON149": return 2;
      case "MON150": case "MON151": case "MON152": return 1;
      case "MON221": return 0;
      case "MON222": return 2;
      case "MON223": case "MON224": case "MON225": return 2;
      case "MON226": case "MON227": case "MON228": return 2;
      default: return 0;
    }
  }

  function MONBrutePitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": case "MON121": case "MON122": return 0;
      case "MON123": case "MON124": return 2;
      case "MON125": return 1;
      case "MON126": case "MON129": case "MON132": case "MON135": case "MON138": case "MON141": case "MON144": case "MON147": case "MON150": return 1;
      case "MON127": case "MON130": case "MON133": case "MON136": case "MON139": case "MON142": case "MON145": case "MON148": case "MON151": return 2;
      case "MON128": case "MON131": case "MON134": case "MON137": case "MON140": case "MON143": case "MON146": case "MON149": case "MON152": return 3;
      case "MON221": return 0;
      case "MON222": return 3;
      case "MON223": case "MON226": return 1;
      case "MON224": case "MON227": return 2;
      case "MON225": case "MON228": return 3;
      case "MON406": return 0;
      default: return 3;
    }
  }

  function MONBruteBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": return 0;
      case "MON122": return 1;
      case "MON123": return 0;
      case "MON125": return 0;
      case "MON138": case "MON139": case "MON140": return 0;
      case "MON221": return 0;
      case "MON222": return 0;
      case "MON223": case "MON224": case "MON225": return 0;
      case "MON226": case "MON227": case "MON228": return 0;
      default: return 3;
    }
  }

  function MONBruteAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON121": return 6;
      case "MON124": return 6;
      case "MON138": return 8;
      case "MON139": case "MON144": case "MON147": return 7;
      case "MON123": case "MON125": case "MON126": case "MON129": case "MON135": case "MON140": case "MON141": case "MON145": case "MON148": return 6;
      case "MON127": case "MON130": case "MON136": case "MON142": case "MON146": case "MON149": return 5;
      case "MON128": case "MON131": case "MON137": case "MON143" :return 4;
      case "MON221": return 3;
      case "MON226": return 7;
      case "MON223": case "MON227": return 6;
      case "MON224": case "MON228": return 5;
      case "MON225": return 4;
      default: return 0;
    }
  }

  function MONBrutePlayAbility($cardID, $from, $resourcesPaid)
  {
    global $CS_Num6PowBan, $combatChain, $currentPlayer;
    $rv = "";
    switch($cardID)
    {
      case "MON121":
        $numBD = SearchCount(SearchBanish($currentPlayer, "", "", -1, -1, "", "", true));
        $damage = 6 - $numBD;
        DamageTrigger($currentPlayer, $damage, "PLAYCARD", $cardID);
        return "Hexagore, the Death Hydra did $damage damage to you.";
      case "MON125":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Shadow of Blasmophet discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("CHOOSEDECK", $currentPlayer, "<-", 1);
          AddDecisionQueue("REVEALCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $currentPlayer, "DECK,NA", 1);
          $rv .= "and banished a card with Blood Debt your Deck.";
        }
        return $rv;
      case "MON126": case "MON127": case "MON128":
        if(RandomBanish3GY())
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Endless Maw gained 3 power from banishing a card with 6 or more power.";
        }
        return $rv;
      case "MON129": case "MON130": case "MON131":
        if(RandomBanish3GY())
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Writhing Beast Hulk gained Dominate from banishing a card with 6 or more power.";
        }
        return $rv;
      case "MON132": case "MON133": case "MON134":
        if(RandomBanish3GY())
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Convulsions gives your next attack action card +" . EffectAttackModifier($cardID) . " and Dominate from banishing a card with 6 or more power.";
        }
        return $rv;
      case "MON135": case "MON136": case "MON137":
        RandomBanish3GY();
        return "";
      case "MON138": case "MON139": case "MON140":
        MyDrawCard();
        $card = DiscardRandom();
        if(AttackValue($card) >= 6)
        {
          $rv = "Deadwood Rumbler let you banish a card.";
          AddDecisionQueue("FINDINDICES", $currentPlayer, "GY");
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to banish with Deadwood Rumbler");
          AddDecisionQueue("CHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIBANISH", $currentPlayer, "DISCARD,NA", 1);
        }
        return $rv;
      case "MON141": case "MON142": case "MON143":
        if(RandomBanish3GY())
        {
          GiveAttackGoAgain();
          $rv = "Dread Screamer gained Go Again from banishing a card with 6 or more power.";
        }
        return $rv;
      case "MON147": case "MON148": case "MON149":
        RandomBanish3GY();
        return "";
      case "MON150": case "MON151": case "MON152":
        RandomBanish3GY();
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Unworldly Bellow gives your next Brute or Shadow attack action card +" . EffectAttackModifier($cardID) . ".";
      case "MON221":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Ravenous Meataxe discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv .= " and got +2 from discarding a card with 6 or more power";
        }
        $rv .= ".";
        return $rv;
      case "MON222":
        MyDrawCard();
        $card = DiscardRandom();
        $rv = "Tear Limb from Limb discarded " . CardLink($card, $card);
        if(AttackValue($card) >= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv .= " and doubled the base attack of your next Brute attack action card";
        }
        $rv .= ".";
        return $rv;
      case "MON223": case "MON224": case "MON225":
        MyDrawCard();
        $card = DiscardRandom();
        if(AttackValue($card) >= 6)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Pulping got Dominate from discarding " . CardLink($card, $card) . " with 6 or more power.";
        } else {
          $rv = "Pulping did not gain dominate from discarding " . CardLink($card, $card);
        }
        return $rv;
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
    global $CS_Num6PowBan, $currentPlayer;
    $hand = &GetHand($currentPlayer);
    $discard = &GetDiscard($currentPlayer);
    if(count($discard) < 3) return;
    $BanishedIncludes6 = false;
    for($i = 0; $i < 3; $i++)
    {
      $index = rand() % count($discard);
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
    $log = "Lady Barthimont banished " . CardLink($topDeck, $topDeck);
    if(AttackValue($topDeck) >= 6)
    {
      $arsenal = &GetArsenal($player);
      ++$arsenal[$index+3];
      AddCurrentTurnEffect("MON406", $player);
      if($arsenal[$index+3] == 2)
      {
        $log .= ", gave Dominate, and searched for a specialization card";
        RemoveArsenal($player, $index);
        BanishCardForPlayer("MON406", $player, "ARS", "-");
        AddDecisionQueue("FINDINDICES", $player, "DECKSPEC");
        AddDecisionQueue("CHOOSEDECK", $player, "<-", 1);
        AddDecisionQueue("ADDARSENALFACEUP", $player, "DECK", 1);
      }
      else $log .= " and gave Dominate";
      WriteLog($log . ".");
    }
  }

?>
