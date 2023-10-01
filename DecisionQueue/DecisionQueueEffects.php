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
            PrependDecisionQueue("MZREMOVE", $player, "-", 1);
            PrependDecisionQueue("MZADDZONE", $player, "MYBANISH,HAND,-", 1);
            PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
            PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to banish", 1);
            PrependDecisionQueue("MULTIZONEINDICES", $player, "MYHAND:type=AA");
            break;
          default: break;
        }
      }
      return $lastResult;
    case "FABRICATE":
      $params = explode(",", $lastResult);
      for($i = 0; $i < count($params); ++$i) {
        switch($params[$i]) {
          case "Equip_a_base_equipment_with_Proto_in_its_name_from_your_inventory":
            $protos = "EVO022,EVO023,EVO024,EVO025";
            AddDecisionQueue("SETDQCONTEXT", $player, "Choose a proto to equip (make sure you choose one in your inventory)");
            AddDecisionQueue("CHOOSECARD", $player, $protos);
            AddDecisionQueue("EQUIPCARD", $player, "<-");
            break;
          case "Evo_permanents_you_control_get_+1_block_this_turn":
            AddCurrentTurnEffect("EVO146", $player);
            break;
          case "Put_this_under_an_Evo_permanent_you_control":
            AddDecisionQueue("MULTIZONEINDICES", $player, "MYCHAR:subtype=Evo");
            AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
            AddDecisionQueue("MZOP", $player, "ADDSUBCARD,EVO146", 1);
            break;
          case "Banish_an_Evo_from_your_hand_and_draw_a_card":
            MZChooseAndBanish($player, "MYHAND:subtype=Evo", "HAND,-", may:true);
            AddDecisionQueue("DRAW", $player, "-", 1);
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
    case "JINGLEWOOD":
      switch($lastResult[0])
      {
        case "Might": PlayAura("TCC105", $defPlayer); break;
        case "Vigor": PlayAura("TCC107", $defPlayer); break;
        case "Quicken": PlayAura("WTR225", $defPlayer); break;
        default: break;
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
    case "DTD178": case "DTD179": case "DTD180":
      if($card == "DTD178") $pitchTarget = 1;
      else if($card == "DTD179") $pitchTarget = 2;
      else $pitchTarget = 3;
      $deck = new Deck($target);
      $banished = $deck->BanishTop();
      if(PitchValue($banished) == $pitchTarget) LoseHealth(1, $target);
      return "";
    case "BURDENSOFTHEPAST":
      if(SearchCount(SearchDiscard($target, "DR")) >= 10) Draw($player);
      AddCurrentTurnEffect("OUT187", $target);
      return "";
    default: return $lastResult;
  }
}

function SpecificCardLogic($player, $card, $lastResult, $initiator)
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
      if(intval($lastResult) > 0) {
        for ($i = 0; $i < $lastResult; ++$i) Draw($player);
      }
      return $lastResult;
    case "SCEPTEROFPAIN":
      global $dqVars;
      if(intval($dqVars[0]) > 0) {
        PlayAura("ARC112", $player, number:intval($dqVars[0]));
      }
      return $lastResult;
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
      $deck = new Deck($player);
      $discard = new Discard($player);
      sort($lastResult);
      for($i = count($lastResult)-1; $i >= 0; --$i) {
        $cardID = $discard->Remove($lastResult[$i]);
        $deck->AddBottom($cardID, "GY");
        if($cards != "") $cards .= ", ";
        if($i == 0) $cards .= "and ";
        $cards .= CardLink($cardID, $cardID);
      }
      WriteLog("Remembrance shuffled " . $cards);
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
        $deck = new Deck($player);
        $cardID = $deck->Top(remove:true);
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
      $deck = new Deck($player);
      if($deck->Empty()) {
        LoseHealth(9999, $player);
        WriteLog("Your deck has no cards, so " . CardLink("CRU007", "CRU007") . " continues damaging you until you die");
        return 1;
      }
      $card = $deck->BanishTop("-", $player);
      LoseHealth(1, $player);
      if(AttackValue($card) >= 6) {
        $banish = new Banish($player);
        RemoveBanish($player, ($banish->NumCards()-1)*BanishPieces());
        AddPlayerHand($card, $player, "BANISH");
      } else PrependDecisionQueue("SPECIFICCARD", $player, "BEASTWITHIN");
      return 1;
    case "CROWNOFDICHOTOMY":
      $lastType = CardType($lastResult);
      $newType = ($lastType == "A" ? "AA" : "A");
      MZMoveCard($player, "MYDISCARD:type=" . $newType, "MYTOPDECK");
      return 1;
    case "PICKACARD":
      $index = explode("-", $dqVars[0])[1];
      $hand = &GetHand(($player == 1 ? 2 : 1));
      $chosenName = CardName($hand[$index]);
      $rand = GetRandom(0, count($hand) - 1);
      if(RevealCards($hand[$rand], $player) && $chosenName == CardName($hand[$rand])) {
        WriteLog("Bingo! Your opponent tossed you a silver.");
        PutItemIntoPlayForPlayer("EVR195", $player);
      }
      return $lastResult;
    case "GENESIS":
      AddSoul($lastResult, $player, "HAND", false);
      if(TalentContains($lastResult, "LIGHT", $player)) Draw($player, false);
      if(ClassContains($lastResult, "ILLUSIONIST", $player)) PlayAura("MON104", $player);
      return 1;
    case "SPOILEDSKULL":
      $rand = GetRandom(0, count($lastResult) - 1);
      $banish = new Banish($player);
      $card = $banish->Card($lastResult[$rand]);
      $card->SetModifier("TT");
      WriteLog("You may play " . CardLink($card->ID(), $card->ID()) . " this turn");
      return $lastResult;
    case "ALLURINGINDUCEMENT":
      global $combatChain, $combatChainState, $CCS_LinkBaseAttack;
      $combatChain[0] = $lastResult;
      $combatChainState[$CCS_LinkBaseAttack] = AttackValue($combatChain[0]);
      return $lastResult;
    case "CONSTRUCTNITROMECHANOID":
      sort($lastResult);
      for($i = count($lastResult)-1; $i >= 0; --$i) {
        RemoveItemAndAddAsSubcardToCharacter($player, $lastResult[$i], $initiator);
      }
      return $lastResult;
    case "SYSTEMRESET":
      for($i=count($lastResult)-1; $i>=0; --$i) {
        $cardID = DestroyItemForPlayer($player, $lastResult[$i], skipDestroy:true);
        $banishIndex = BanishCardForPlayer($cardID, $player, "PLAY", "-", banishedBy:$player);
        $cardID = RemoveBanish($player, $banishIndex);
        PutItemIntoPlayForPlayer($cardID, $player);
      }
      return $lastResult;
    case "TICKTOCKCLOCK":
      DamageTrigger($player, $dqVars[0], "DAMAGE", "EVO074");
      return $lastResult;
    case "EVOBREAKER":
      if($lastResult == "PASS") {
        if($dqVars[0] != "-") {
          global $CS_CharacterIndex;
          $index = $dqVars[1];
          $hyperdriverArr = explode(",", $dqVars[0]);
          for($i=0; $i<count($hyperdriverArr); ++$i) CharacterAddSubcard($player, $index, $hyperdriverArr[$i]);
        }
        return $lastResult;
      }
      else if($lastResult != "-") {
        if($dqVars[0] == "-") $dqVars[0] = $lastResult;
        else $dqVars[0] .= "," . $lastResult;
      }
      PrependDecisionQueue("SPECIFICCARD", $player, "EVOBREAKER");
      PrependDecisionQueue("MZREMOVE", $player, "-", 1);
      PrependDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a Hyper Driver to transform (or pass)", 1);
      PrependDecisionQueue("MULTIZONEINDICES", $player, "MYITEMS:sameName=ARC036", 1);
      return $lastResult;
    default: return "";
  }
}

function PitchCard($player, $search="MYHAND", $skipGain=false)
{
  if(!$skipGain) PrependDecisionQueue("GAINPITCHVALUE", $player, "-", 1);
  PrependDecisionQueue("PITCHABILITY", $player, "-", 1);
  PrependDecisionQueue("ADDMYPITCH", $player, "-", 1);
  PrependDecisionQueue("REMOVEMYHAND", $player, "-", 1);
  PrependDecisionQueue("CHOOSEHANDCANCEL", $player, "<-", 1);
  PrependDecisionQueue("SETDQCONTEXT", $player, "Choose a card to pitch", 1);
  PrependDecisionQueue("MZOP", $player, "GETCARDINDICES", 1);
  PrependDecisionQueue("MULTIZONEINDICES", $player, $search, 1);
}

?>
