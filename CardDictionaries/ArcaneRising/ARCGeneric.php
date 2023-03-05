<?php


function ARCGenericPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CS_NumMoonWishPlayed;
  global $CS_NextNAACardGoAgain, $CS_ArcaneDamagePrevention;
  $rv = "";
  switch ($cardID) {
    case "ARC151":
      Opt($cardID, 2);
      return "Lets you opt 2.";
    case "ARC153":
      $pitchValue = 0;
      $deck = GetDeck($currentPlayer);
      if (count($deck) > 0) {
        $pitchValue = PitchValue($deck[0]);
        $rv = "Revealed " . CardLink($deck[0], $deck[0]) . " and gives the next attack action card +" . (3 - $pitchValue) . ".";
      } else {
        $rv = "There are no cards in deck for Bracers of Belief to reveal, so the next attack gets +3.";
      }
      $bonus = 3 - $pitchValue;
      if ($bonus > 0) AddCurrentTurnEffect($cardID . "-" . $bonus, $currentPlayer);
      return $rv;
    case "ARC154":
      SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
      return "Gives your next non-attack action card this turn go again.";
    case "ARC160":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("ARTOFWAR", $currentPlayer, "-", 1);
      return "";
    case "ARC162":
      return "Is currently a manual resolve card. Name the card in chat, and enforce not playing it manually.";
    case "ARC164":
    case "ARC165":
    case "ARC166":
      if (IHaveLessHealth()) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $rv = "Gained go again.";
      }
      return $rv;
    case "ARC170":
    case "ARC171":
    case "ARC172":
      $rv = "Makes your next attack action that hits draw a card";
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      if ($from == "ARS") {
        AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        $rv .= " and gives your next attack action card +" . EffectAttackModifier($cardID . "-2") . ".";
      } else {
        $rv .= ".";
      }
      return $rv;
    case "ARC173":
    case "ARC174":
    case "ARC175":
      if ($cardID == "ARC173") $prevent = 6;
      else if ($cardID == "ARC174") $prevent = 5;
      else $prevent = 4;
      $deck = GetDeck($currentPlayer);
      if (count($deck) > 0) {
        $revealed = $deck[0];
        $prevent -= PitchValue($revealed);
      }
      IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
      return "Reveals " . CardLink($revealed, $revealed) . " and prevents the next " . $prevent . " arcane damage.";
    case "ARC182":
    case "ARC183":
    case "ARC184":
      if ($from == "ARS") {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        $ret = "Gained go again.";
      }
      return $ret;
    case "ARC191":
    case "ARC192":
    case "ARC193":
      if (CanRevealCards($currentPlayer)) {
        $deck = GetDeck($currentPlayer);
        if (count($deck) == 0) return "Your deck is empty. Ravenous Rabble does not get negative attack.";
        $pitchVal = PitchValue($deck[0]);
        SetCCAttackModifier(0, -$pitchVal);
        return "Reveals " . CardLink($deck[0], $deck[0]) . " and gets -" . $pitchVal . " attack.";
      }
      return "Reveal has been prevented.";
    case "ARC200":
    case "ARC201":
    case "ARC202":
      Opt($cardID, 1);
      return "Lets you to Opt 1.";
    case "ARC203":
    case "ARC204":
    case "ARC205":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Gives your next attack action card +" . EffectAttackModifier($cardID) . ".";
    case "ARC206":
    case "ARC207":
    case "ARC208":
      $rv = "Gives your next attack action card +" . EffectAttackModifier($cardID);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if ($from == "ARS") {
        Opt($cardID, 2);
        $rv .= " and lets you opt 2.";
      } else {
        $rv .= ".";
      }
      return $rv;
    case "ARC209":
    case "ARC210":
    case "ARC211":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if ($cardID == "ARC209") $cost = 0;
      else if ($cardID == "ARC210") $cost = 1;
      else $cost = 2;
      return "Makes you gain an action point the next time you play an action card with cost $cost or greater.";
    case "ARC212":
    case "ARC213":
    case "ARC214":
      if ($cardID == "ARC212") $health = 3;
      else if ($cardID == "ARC213") $health = 2;
      else $health = 1;
      GainHealth($health, $currentPlayer);
      if (GetClassState($currentPlayer, $CS_NumMoonWishPlayed) > 0) {
        MyDrawCard();
      }
      return "";
    case "ARC215":
    case "ARC216":
    case "ARC217":
      if ($cardID == "ARC215") $opt = 4;
      else if ($cardID == "ARC216") $opt = 3;
      else $opt = 2;
      Opt($cardID, $opt);
      return "Lets you opt " . $opt . ".";
    default:
      return "";
  }
}

function ARCGenericHitEffect($cardID)
{
  global $mainPlayer, $CS_NextNAAInstant, $defPlayer;
  switch ($cardID) {
    case "ARC159":
      if (IsHeroAttackTarget()) {
        DestroyArsenal($defPlayer);
      }
      break;
    case "ARC164": case "ARC165": case "ARC166":
      GainHealth(1, $mainPlayer);
      break;
    case "ARC161":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      break;
    case "ARC179": case "ARC180": case "ARC181":
      AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "MYDISCARD:type=A");
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a non-attack action card to put on top of your deck");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $mainPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $mainPlayer, "-", 1);
      break;
    case "ARC182": case "ARC183":  case "ARC184":
      OptMain(2);
      break;
    case "ARC185": case "ARC186": case "ARC187":
      AddLayer("TRIGGER", $mainPlayer, $cardID);
      break;
    case "ARC194": case "ARC195": case "ARC196":
      SetClassState($mainPlayer, $CS_NextNAAInstant, 1);
      break;
    default:
      break;
  }
}
