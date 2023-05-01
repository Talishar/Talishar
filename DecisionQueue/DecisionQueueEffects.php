<?php

function ModalAbilities($player, $card, $lastResult)
{
  global $combatChain, $defPlayer;
  switch($card)
  {
    case "ESTRIKE":
      switch($lastResult) {
        case "Draw_a_Card": return Draw($player);
        case "Buff_Power": AddCurrentTurnEffect("WTR159", $player); return 1;
        case "Go_Again": GiveAttackGoAgain(); return 2;
      }
      return $lastResult;
    case "MICROPROCESSOR":
      $deck = new Deck($player);
      switch($lastResult) {
        case "Opt":
          WriteLog(Cardlink("EVR070","EVR070") . " let you Opt 1");
          Opt("EVR070", 1);
          break;
        case "Draw_then_top_deck":
          if(!$deck->Empty()) {
            WriteLog(Cardlink("EVR070","EVR070") . " let you draw a card then put one on top");
            Draw($player);
            HandToTopDeck($player);
          }
          break;
        case "Banish_top_deck":
          if(!$deck->Empty()) {
            $card = $deck->Top(remove:true);
            BanishCardForPlayer($card, $player, "DECK", "-");
            WriteLog(Cardlink("EVR070","EVR070") . " banished " . CardLink($card, $card));
          }
          break;
        default: break;
      }
      return "";
    case "TWINTWISTERS":
      switch($lastResult) {
        case "Hit_Effect":
          AddCurrentTurnEffect("EVR047-1", $player);
          return 1;
        case "1_Attack":
          AddCurrentTurnEffect("EVR047-2", $player);
          return 2;
      }
      return $lastResult;
    case "SHIVER":
      $arsenal = &GetArsenal($player);
      switch($lastResult) {
        case "1_Attack":
          AddCurrentTurnEffect("ELE033-1", $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
          return 1;
        case "Dominate":
          AddCurrentTurnEffect("ELE033-2", $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
          return 1;
      }
      return $lastResult;
    case "VOLTAIRE":
      $arsenal = &GetArsenal($player);
      switch ($lastResult) {
        case "1_Attack":
          AddCurrentTurnEffect("ELE034-1", $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
          return 1;
        case "Go_again":
          AddCurrentTurnEffect("ELE034-2", $player, "HAND", $arsenal[count($arsenal) - ArsenalPieces() + 5]);
          return 1;
      }
      return $lastResult;
    case "KORSHEM":
      switch($lastResult) {
          case "Gain_a_resource": GainResources($player, 1); return 1;
          case "Gain_a_life": GainHealth(1, $player); return 2;
          case "1_Attack": AddCurrentTurnEffect("ELE000-1", $player); return 3;
          case "1_Defense": AddCurrentTurnEffect("ELE000-2", $player); return 4;
          default: break;
        }
      return $lastResult;
    case "ARTOFWAR":
      $params = explode(",", $lastResult);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "Buff_your_attack_action_cards_this_turn":
            AddCurrentTurnEffect("ARC160-1", $player);
            if($player == $defPlayer) {
              for($j = CombatChainPieces(); $j < count($combatChain); $j += CombatChainPieces()) {
                if(CardType($combatChain[$j]) == "AA") CombatChainPowerModifier($j, 1);
              }
            }
            break;
          case "Your_next_attack_action_card_gains_go_again":
            if(count($combatChain) > 0) AddCurrentTurnEffectFromCombat("ARC160-3", $player);
            else AddCurrentTurnEffect("ARC160-3", $player);
            break;
          case "Defend_with_attack_action_cards_from_arsenal":
            AddCurrentTurnEffect("ARC160-2", $player);
            break;
          case "Banish_an_attack_action_card_to_draw_2_cards":
            PrependDecisionQueue("DRAW", $player, "-", 1);
            PrependDecisionQueue("DRAW", $player, "-", 1);
            PrependDecisionQueue("MULTIBANISH", $player, "HAND,-", 1);
            PrependDecisionQueue("REMOVEMYHAND", $player, "-", 1);
            PrependDecisionQueue("MAYCHOOSEHAND", $player, "<-", 1);
            PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to banish", 1);
            PrependDecisionQueue("FINDINDICES", $player, "MYHANDAA");
            break;
          default: break;
        }
      }
      return $lastResult;
    case "COAXCOMMOTION":
      if(!is_array($lastResult)) return $lastResult;
      for($i = 0; $i < count($lastResult); ++$i) {
        switch($lastResult[$i]) {
          case "Quicken_token":
            PlayAura("WTR225", 1);
            PlayAura("WTR225", 2);
            break;
          case "Draw_card":
            Draw($player);
            Draw($player == 1 ? 2 : 1);
            break;
          case "Gain_life":
            GainHealth(1, $player);
            GainHealth(1, ($player == 1 ? 2 : 1));
            break;
          default: break;
        }
      }
      return $lastResult;
    case "MON260": case "MON261": case "MON262":
      switch($lastResult) {
        case "Buff_Power": AddCurrentTurnEffect("$card-1", $player); return 1;
        case "Go_Again": AddCurrentTurnEffect("$card-2", $player); return 2;
      }
      return $lastResult;
    case "TOMEOFAETHERWIND":
      $params = explode(",", $lastResult);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "Buff_Arcane": AddCurrentTurnEffect("ARC122", $player); break;
          case "Draw_card": Draw($player); break;
          default: break;
        }
      }
      return $lastResult;
    default: return "";
  }
}

function PlayerTargetedAbility($player, $card, $lastResult)
{
  global $dqVars;
  $target = ($lastResult == "Target_Opponent" ? ($player == 1 ? 2 : 1) : $player);
  switch($card)
  {
    case "CORONETPEAK":
      AddDecisionQueue("DQPAYORDISCARD", $target, "1");
      return "";
    case "IMPERIALWARHORN":
      if($lastResult == "Target_Opponent" || $lastResult == "Target_Both_Heroes")
      {
        if(IsRoyal($player)) ImperialWarHorn($player, "THEIR");
        else ImperialWarHorn(($player == 1 ? 2 : 1), "MY");
      }
      if($lastResult == "Target_Yourself" || $lastResult == "Target_Both_Heroes") ImperialWarHorn($player, "MY");
      return "";
    case "PRY":
      $zone = $target == $player ? "HAND" : "THEIRHAND";
      AddDecisionQueue("FINDINDICES", $target, "HAND");
      AddDecisionQueue("PREPENDLASTRESULT", $target, $dqVars[0] . "-", 1);
      AddDecisionQueue("APPENDLASTRESULT", $target, "-" . $dqVars[0], 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose " . $dqVars[0] . " card" . ($dqVars[0] > 1 ? "s" : ""), 1);
      AddDecisionQueue("MULTICHOOSEHAND", $target, "<-", 1);
      AddDecisionQueue("IMPLODELASTRESULT", $target, ",", 1);
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a card", 1);
      AddDecisionQueue("CHOOSE" . $zone, $player, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $target, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $target, "-", 1);
      AddDecisionQueue("DRAW", $target, "-", 1);
      return "";
    case "AMULETOFECHOES":
      PummelHit($target);
      PummelHit($target);
      return "";
    default: return $lastResult;
  }
}

function SpecificCardLogic($player, $card, $lastResult)
{
  global $dqVars, $CS_DamageDealt;
  switch($card)
  {
    case "BLOODONHERHANDS":
      BloodOnHerHandsResolvePlay($lastResult);
      return $lastResult;
    case "RIGHTEOUSCLEANSING":
      $numBanished = SearchCount($lastResult);
      $numLeft = 5 - $numBanished;
      $deck = new Deck($player == 1 ? 2 : 1);
      $reorderCards = "";
      for($i = 0; $i < $numLeft; ++$i) {
        if($deck->RemainingCards() > 0) {
          if($reorderCards != "") $reorderCards .= ",";
          $reorderCards .= $deck->Top(remove:true);
        }
      }
      if($reorderCards != "") {
        PrependDecisionQueue("CHOOSETOPOPPONENT", $player, $reorderCards);
        PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to put on top of their deck");
      }
      return "";
    case "PULSEWAVEHARPOONFILTER":
      $indices = (is_array($lastResult) ? $lastResult : explode(",", $lastResult));
      $hand = &GetHand($player);
      $filteredIndices = "";
      for($i = 0; $i < count($indices); ++$i) {
        $block = BlockValue($hand[$indices[$i]]);
        if($block > -1 && $block <= $dqVars[0]) {
          $type = CardType($hand[$indices[$i]]);
          if($type == "A" || $type == "AA") {
            if ($filteredIndices != "") $filteredIndices .= ",";
            $filteredIndices .= $indices[$i];
          }
        }
      }
      return ($filteredIndices != "" ? $filteredIndices : "PASS");
    case "SIFT":
      $numCards = SearchCount($lastResult);
      for ($i = 0; $i < $numCards; ++$i) {
        Draw($player);
      }
      return "1";
    case "ENCASEDAMAGE":
      $character = &GetPlayerCharacter($player);
      $character[8] = 1;//Freeze their character
      for ($i = CharacterPieces(); $i < count($character); $i += CharacterPieces()) {
        if (CardType($character[$i]) == "E" && $character[$i + 1] != 0) $character[$i + 8] = 1;//Freeze their equipment
      }
      return $lastResult;
    case "BLESSINGOFFOCUS":
      $deck = new Deck($player);
      if($deck->Reveal() && CardSubType($deck->Top()) == "Arrow") {
        if(!ArsenalFull($player)) { AddArsenal($deck->Top(true), $player, "DECK", "UP", 1); }
        else WriteLog("Your arsenal is full");
      }
      return $lastResult;
    case "EVENBIGGERTHANTHAT":
      $deck = new Deck($player);
      if($deck->Reveal() && AttackValue($deck->Top()) > GetClassState(($player == 1 ? 1 : 2), $CS_DamageDealt)) {
        WriteLog("Even Bigger Than That! drew a card and created a Quicken token");
        Draw($player);
        PlayAura("WTR225", $player);
      }
      return $lastResult;
    case "KRAKENAETHERVEIN":
      if($lastResult > 0) {
        for ($i = 0; $i < $lastResult; ++$i) Draw($player);
      }
      return $lastResult;
    case "TALISMANOFCREMATION":
      $discard = &GetDiscard($player);
      $cardName = CardName($discard[$lastResult]);
      $count = 0;
      for($i = count($discard) - DiscardPieces(); $i >= 0; $i -= DiscardPieces()) {
        if(CardName($discard[$i]) == $cardName) {
          BanishCardForPlayer($discard[$i], $player, "GY");
          RemoveGraveyard($player, $i);
          ++$count;
        }
      }
      WriteLog("Talisman of Cremation banished " . $count . " cards named " . $cardName);
      return "";
    case "KNICKKNACK":
      for($i = 0; $i < ($dqVars[0] + 1); ++$i) {
        PrependDecisionQueue("PUTPLAY", $player, "-", 1);
        PrependDecisionQueue("MAYCHOOSEDECK", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "KNICKKNACK");
      }
      return "";
    case "BECOMETHEARKNIGHT":
      $type = (CardType($lastResult) == "A" ? "AA" : "A");
      PrependDecisionQueue("MULTIADDHAND", $player, "-", 1);
      PrependDecisionQueue("REVEALCARDS", $player, "-", 1);
      PrependDecisionQueue("MZREMOVE", $player, "-", 1);
      PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      PrependDecisionQueue("MULTIZONEINDICES", $player, "MYDECK:type=$type;class=RUNEBLADE");
      return 1;
    case "HOPEMERCHANTHOOD":
      $count = SearchCount($lastResult);
      for($i = 0; $i < $count; ++$i) Draw($player);
      WriteLog(CardLink("WTR151", "WTR151") . " shuffled and drew " . $count . " cards");
      return "1";
    case "CASHOUTCONTINUE":
      PrependDecisionQueue("SPECIFICCARD", $player, "CASHOUTCONTINUE", 1);
      PrependDecisionQueue("PUTPLAY", $player, "-", 1);
      PrependDecisionQueue("PASSPARAMETER", $player, "EVR195", 1);
      PrependDecisionQueue("MZDESTROY", $player, "-", 1);
      PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      PrependDecisionQueue("FINDINDICES", $player, "CASHOUT");
      return "";
    case "SANDSKETCH":
      if(AttackValue(DiscardRandom($player, "WTR009")) >= 6) GainActionPoints(2, $player);
      return "1";
    case "REMEMBRANCE":
      $cards = "";
      $deck = &GetDeck($player);
      $discard = &GetDiscard($player);
      for($i = 0; $i < count($lastResult); ++$i) {
        array_push($deck, $discard[$lastResult[$i]]);
        if($cards != "") $cards .= ", ";
        if($i == count($lastResult) - 1) $cards .= "and ";
        $cards .= CardLink($discard[$lastResult[$i]], $discard[$lastResult[$i]]);
        unset($discard[$lastResult[$i]]);
      }
      WriteLog("Remembrance shuffled " . $cards);
      $discard = array_values($discard);
      return "1";
    case "PLASMAMAINLINE":
      $items = &GetItems($player);
      $lastResultArr = explode(",", $lastResult);
      $PMIndex = SearchItemsForUniqueID($lastResultArr[0], $player);
      $targetIndex = SearchItemsForUniqueID($lastResultArr[1], $player);
      ++$items[$targetIndex + 1];
      if(--$items[$PMIndex + 1] == 0) DestroyItemForPlayer($player, $PMIndex);
      return $lastResult;
    case "TOMEOFDUPLICITY":
      $cards = explode(",", $lastResult);
      $mzIndices = "";
      $mod = (CardType($cards[0]) == "A" ? "INST" : "-");
      for($i = 0; $i < count($cards); ++$i) {
        $index = BanishCardForPlayer($cards[$i], $player, "DECK", $mod);
        WriteLog(CardLink($cards[$i], $cards[$i]) . " was banished");
        if($mzIndices != "") $mzIndices .= ",";
        $mzIndices .= "BANISH-" . $index;
      }
      $dqState[5] = $mzIndices;
      return $lastResult;
    case "SANDSCOURGREATBOW":
      if($lastResult == "NO") LoadArrow($player);
      else {
        $deck = &GetDeck($player);
        $cardID = array_shift($deck);
        AddArsenal($cardID, $player, "DECK", "UP");
      }
      return $lastResult;
    case "SOULREAPING":
      $cards = $lastResult != "" ? explode(",", $lastResult) : [];
      if(count($cards) > 0) AddCurrentTurnEffect("MON199", $player);
      $numBD = 0;
      for($i = 0; $i < count($cards); ++$i) if (HasBloodDebt($cards[$i])) {
        ++$numBD;
      }
      GainResources($player, $numBD);
      return 1;
    case "DIMENXXIONALGATEWAY":
      if(ClassContains($lastResult, "RUNEBLADE", $player)) DealArcane(1, 0, "PLAYCARD", "MON161", true);
      if(TalentContains($lastResult, "SHADOW", $player)) {
        PrependDecisionQueue("MULTIBANISH", $player, "DECK,-", 1);
        PrependDecisionQueue("MULTIREMOVEDECK", $player, "<-", 1);
        PrependDecisionQueue("FINDINDICES", $player, "TOPDECK", 1);
        PrependDecisionQueue("NOPASS", $player, "-", 1);
        PrependDecisionQueue("YESNO", $player, "if_you_want_to_banish_the_card", 1);
      }
      return $lastResult;
    case "BEASTWITHIN":
      $deck = &GetDeck($player);
      if(count($deck) == 0) {
        LoseHealth(9999, $player);
        WriteLog("Your deck has no cards, so " . CardLink("CRU007", "CRU007") . " continues damaging you until you die");
        return 1;
      }
      $card = array_shift($deck);
      LoseHealth(1, $player);
      WriteLog(CardLink("CRU007", "CRU007") . " banished " . CardLink($card, $card) . " and lost 1 health");
      if(AttackValue($card) >= 6) {
        BanishCardForPlayer($card, $player, "DECK", "-");
        $banish = &GetBanish($player);
        RemoveBanish($player, count($banish) - BanishPieces());
        AddPlayerHand($card, $player, "BANISH");
      } else {
        BanishCardForPlayer($card, $player, "DECK", "-");
        PrependDecisionQueue("SPECIFICCARD", $player, "BEASTWITHIN");
      }
      return 1;
    default: return "";
  }
}

?>
