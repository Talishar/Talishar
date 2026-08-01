<?php

function PayGoldInstead($player, $cardID) {
  if (CountItemByName("Gold", $player) > 0) {
    AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_" . CardLink("gold", "gold"), 1);
    AddDecisionQueue("NOPASS", $player, "-", 1);
    QueueDestroyGold($player);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "$cardID-PAID", 1);
  }
}

function TargetSwordAttack($player) {
  $attacks = TargetAttack($player);
  $choices = [];
  foreach($attacks as $attack) {
    $cardID = GetMZCard($player, $attack);
    if (SubtypeContains($cardID, "Sword", $player))
      $choices[] = $attack;
  }
  return implode(",", $choices);
}

function DrawAndPutBack($player, $cardID) {
  Draw($player, effectSource:$cardID);
  $hand = GetHand($player);
  Await($player, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
  Await($player, "ChooseMultiZone", "MZIndex", context:"Put a card from hand back on top");
  Await($player, "MZRemove", "cardID");
  Await($player, "AddTopDeck", final:true);
  if (count($hand) == 1) { //handle case where the game automates putting a card back
    AddDecisionQueue("DECKCARDS", $player, "0", 1);
    AddDecisionQueue("SETDQVAR", $player, "1", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "you drew <1> and placed it back on top", 1);
    AddDecisionQueue("OK", $player, "-", 1);
    AddDecisionQueue("SETDQCONTEXT", $player, "-");
  }
}

function AddBlockingFromHand($player, $handInd) {
  $hand = &GetHand($player);
  $cardID = $hand[$handInd];
  $dominateRestricted = IsDominateActive() && NumDefendedFromHand() >= 1;
  $overpowerRestricted = IsOverpowerActive() && NumActionsBlocking() && (TypeContains($cardID, "A") || TypeContains($cardID, "AA")) >= 1;
  if (!$dominateRestricted && !$overpowerRestricted) {
    AddCombatChain($cardID, $player, "HAND", 0, -1);
    OnBlockResolveEffects($cardID);
    array_splice($hand, $handInd, 1);
  }
  else WriteLog(CardLink($cardID, $cardID) . " could not be added as a blocking card");
}