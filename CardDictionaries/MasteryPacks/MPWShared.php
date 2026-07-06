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