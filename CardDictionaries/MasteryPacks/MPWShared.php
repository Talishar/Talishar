<?php

function PayGoldInstead($player, $cardID) {
  if (CountItemByName("Gold", $player) > 0) {
    AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_" . CardLink("gold", "gold"), 1);
    AddDecisionQueue("NOPASS", $player, "-", 1);
    $goldIndices = GetGoldIndices($player);
    if (str_contains($goldIndices, "MYCHAR")) {
      AddDecisionQueue("PASSPARAMETER", $player, $goldIndices, 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
    } else AddDecisionQueue("FINDANDDESTROYITEM", $player, "gold-1", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "$cardID-PAID", 1);
  }
}

function TargetSwordAttack($player) {
  //eventually set this up to target past links
  global $CombatChain;
  if (!$CombatChain->HasCurrentLink()) return "";
  $choices = [];
  $pastLinkCards = GetChainLinkCards($player, subType:"Sword");
  if ($pastLinkCards != "") $choices[] = $pastLinkCards;
  if (CardSubType($CombatChain->AttackCard()->ID()) == "Sword") $choices[] = "COMBATCHAIN-0";
  return implode(",", $choices);
}