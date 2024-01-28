<?php


function ARCGenericPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NextNAACardGoAgain, $CS_ArcaneDamagePrevention, $CombatChain;
  $rv = "";
  switch($cardID) {
    case "ARC151":
      PlayerOpt($currentPlayer, 2);
      return "";
    case "ARC153":
      $deck = new Deck($currentPlayer);
      if($deck->Reveal()) {
        $bonus = 3 - PitchValue($deck->Top());
        if($bonus > 0) AddCurrentTurnEffect($cardID . "-" . $bonus, $currentPlayer);
      }
      return $rv;
    case "ARC154":
      SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 1);
      return "";
    case "ARC160":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, $additionalCosts, 1);
      AddDecisionQueue("MODAL", $currentPlayer, "ARTOFWAR", 1);
      return "";
    case "ARC162":
      AddDecisionQueue("INPUTCARDNAME", $currentPlayer, "-");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("WRITELOG", $currentPlayer, "<b>{0}</b> was chosen");
      AddDecisionQueue("ADDCURRENTANDNEXTTURNEFFECT", ($currentPlayer == 1 ? 2 : 1), "ARC162,{0}");
      return "";
    case "ARC164": case "ARC165": case "ARC166":
      if(PlayerHasLessHealth($currentPlayer)) GiveAttackGoAgain();
      return "";
    case "ARC170": case "ARC171": case "ARC172":
      AddCurrentTurnEffect($cardID . "-1", $currentPlayer);
      if($from == "ARS") {
        AddCurrentTurnEffect($cardID . "-2", $currentPlayer);
        $rv = "Played from arsenal: Gives your next attack action card +" . EffectAttackModifier($cardID . "-2");
      }
      return $rv;
    case "ARC173": case "ARC174": case "ARC175":
      if($cardID == "ARC173") $prevent = 6;
      else if($cardID == "ARC174") $prevent = 5;
      else $prevent = 4;
      $deck = new Deck($currentPlayer);
      if($deck->Reveal(1)) {
        $prevent -= PitchValue($deck->Top());
      }
      IncrementClassState($currentPlayer, $CS_ArcaneDamagePrevention, $prevent);
      return "Eirina's Prayer reduces your next arcane damage by " . $prevent;
    case "ARC182": case "ARC183": case "ARC184":
      if($from == "ARS") GiveAttackGoAgain();
      return "";
    case "ARC191": case "ARC192": case "ARC193":
      $deck = new Deck($currentPlayer);
      if($deck->Empty()) return "Ravenous Rabble does not get negative attack because your deck is empty";
      if($deck->Reveal(1)) {
        $top = $deck->Top();
        $pitch = PitchValue($top);
        $CombatChain->AttackCard()->ModifyPower(-$pitch);
        return "Reveals " . CardLink($top, $top) . " and gets -" . $pitch . " attack";
      }
      return "Ravenous Rabble does not get negative attack because the reveal was prevented";
    case "ARC200": case "ARC201": case "ARC202":
      PlayerOpt($currentPlayer, 1);
      return "";
    case "ARC203": case "ARC204": case "ARC205":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ARC206": case "ARC207": case "ARC208":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if($from == "ARS") {
        PlayerOpt($currentPlayer, 2);
        $rv = "Was played from arsenal and lets you Opt 2";
      }
      return $rv;
    case "ARC209": case "ARC210": case "ARC211":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      if($cardID == "ARC209") $cost = 0;
      else if($cardID == "ARC210") $cost = 1;
      else $cost = 2;
      return "";
    case "ARC212": case "ARC213": case "ARC214":
      if($cardID == "ARC212") $health = 3;
      else if($cardID == "ARC213") $health = 2;
      else $health = 1;
      GainHealth($health, $currentPlayer);
      if(SearchCurrentTurnEffects("ARC185-GA", $currentPlayer)) Draw($currentPlayer);
      return "";
    case "ARC215": case "ARC216": case "ARC217":
      if($cardID == "ARC215") $opt = 4;
      else if($cardID == "ARC216") $opt = 3;
      else $opt = 2;
      PlayerOpt($currentPlayer, $opt);
      return "";
    default: return "";
  }
}

function ARCGenericHitEffect($cardID)
{
  global $mainPlayer, $CS_NextNAAInstant, $defPlayer;
  switch($cardID) {
    case "ARC159":
      if(IsHeroAttackTarget()) DestroyArsenal($defPlayer, effectController:$mainPlayer);      
      break;
    case "ARC164": case "ARC165": case "ARC166":
      GainHealth(1, $mainPlayer);
      break;
    case "ARC161":
      AddCurrentTurnEffect($cardID, $mainPlayer);
      break;
    case "ARC179": case "ARC180": case "ARC181":
      MZMoveCard($mainPlayer, "MYDISCARD:type=A", "MYTOPDECK", may:true);
      break;
    case "ARC182": case "ARC183":  case "ARC184":
      PlayerOpt($mainPlayer, 2);
      break;
    case "ARC185": case "ARC186": case "ARC187":
      MZMoveCard($mainPlayer, "MYDECK:sameName=ARC212", "MYHAND", may:true, isReveal:true);
      AddDecisionQueue("SHUFFLEDECK", $mainPlayer, "-");
      break;
    case "ARC194": case "ARC195": case "ARC196":
      SetClassState($mainPlayer, $CS_NextNAAInstant, 1);
      break;
    default: break;
  }
  return "";
}
