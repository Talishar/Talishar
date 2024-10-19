<?php

function AJVPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer;
  switch ($cardID) {
    case "AJV018":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "THEIRCHAR:type=E&MYCHAR:type=E");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose an equipment to add a -1 defense counter", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MODDEFCOUNTER", $currentPlayer, "-1", 1);
      return "";
    default:
      return "";
  }
}

function AJVCombatEffectActive($cardID, $attackID)
{
switch($cardID) {
    case "AJV018": return true;
    default: return false;
}
}

?>