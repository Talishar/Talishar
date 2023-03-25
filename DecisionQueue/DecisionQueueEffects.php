<?php

function ModalAbilities($player, $card, $lastResult)
{
  switch($card)
  {
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
      $numBanished = SearchCount(",", $lastResult);//Parameter is the banished cards
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
    default: return "";
  }
}

?>
