<?php

function AJVPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBluePlayed, $CS_Transcended, $mainPlayer, $CS_DamagePrevention, $CS_PlayIndex;
  global $combatChain, $defPlayer, $CombatChain, $chainLinks, $combatChainState, $CCS_LinkBaseAttack, $CS_NumAttacks;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  switch ($cardID) {
    case "AJV018":
      CrumbleEquipment();
      return "";
    default:
      return "";
  }
}

function AJVCombatEffectActive($cardID, $attackID)
{
global $combatChainState, $CCS_AttackPlayedFrom, $mainPlayer;
switch($cardID) {
    case "AJV018": return true;
    default: return false;
}
}

function CrumbleEquipment()
{
  WriteLog("Crumble triggered.");
  MZMayChooseAndLowerDef($currentPlayer, "THEIRCHAR:type=E;MYCHAR:type=E", may: true, context: "Choose which equipment you want to give a -1 counter to");
  WriteLog("Crumble resolved.");
}

?>