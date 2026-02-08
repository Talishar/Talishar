<?php
function PENAbilityType($cardID, $index = -1, $from = "-"): string
{
  switch ($cardID) {
    default:
      return "";
  }
}

function PENAbilityCost($cardID): int
{
  global $currentPlayer;
  switch ($cardID) {
    default:
      return 0;
  }
}

function PENAbilityHasGoAgain($cardID): bool
{
  switch ($cardID) {
    default:
      return false;
  }
}

function PENEffectPowerModifier($cardID): int
{
  switch ($cardID) {
    default:
      return  0;
  }
}

function PENCombatEffectActive($cardID, $attackID): bool
{
  global $mainPlayer;
  switch ($cardID) {
    default:
      return false;
  }
  ;
}

function PENPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $mainPlayer, $combatChainState, $combatChain, $chainLinkSummary, $chainLinks, $defPlayer;
  global $CombatChain;
  $otherPlayer = $currentPlayer == 1 ? 2 : 1;
  switch ($cardID) {
    default:
      return "";
  }
}

function PENHitEffect($cardID): void
{
  global $mainPlayer;
  switch ($cardID) {
    default:
      break;
  }
}

function DoSolrayPlating($targetPlayer, $damage)
{
  if ($damage > 0) {
    PrependDecisionQueue("ADDTOLASTRESULT", $targetPlayer, "{0}", 1);
    PrependDecisionQueue("PASSPARAMETER", $targetPlayer, 1, 1); //prevent 1 damage
    if (!SearchCurrentTurnEffects("solray_plating", $targetPlayer))
      PrependDecisionQueue("CHARFLAGDESTROY", $targetPlayer, FindCharacterIndex($targetPlayer, "solray_plating"), 1);
    PrependDecisionQueue("MULTIBANISHSOUL", $targetPlayer, "-", 1);
    PrependDecisionQueue("MAYCHOOSEMYSOUL", $targetPlayer, "<-", 1);
    PrependDecisionQueue("SETDQCONTEXT", $targetPlayer, "Banish a card from soul to " . CardLink("solray_plating"), 1);
    PrependDecisionQueue("FINDINDICES", $targetPlayer, "SOULINDICES0", 1);
    PrependDecisionQueue("SETDQVAR", $targetPlayer, "0", 1); // current damage prevention
    LogDamagePreventedStats($targetPlayer, 1);
  }
}

function SuperFrozen($player, $MZIndex) {
  global $CurrentTurnEffects;
  for ($i = 0; $i < $CurrentTurnEffects->NumEffects(); ++$i) {
    $Effect = $CurrentTurnEffects->Effect($i, true);
    if ($Effect->PlayerID() != $player) continue;
    switch ($Effect->EffectID()) {
      case "channel_galcias_cradle_blue":
        $mzUID = CleanTarget($player, $MZIndex);
        if ($mzUID == (explode(",", $Effect->AppliestoUniqueID())[0] ?? "-")) return true;
        break;
      case "crown_of_frozen_thoughts":
        if ($MZIndex == "MYCHAR-0")
          return true;
      default:
        break;
    }
  }
  return false;
}

function IcelochActive($player) {
  $otherPlayer = $player == 1 ? 2 : 1;
  if (SearchAurasForCard("channel_iceloch_glaze_blue", $otherPlayer) == "") return false;
  if (SearchAurasForCard("frostbite", $player) != "") return true;
  if (SearchCharacterForCard($player, "frostbite")) return true;
  if (SearchCharacter($player, frozenOnly:true) != "") return true;
  if (SearchItems($player, frozenOnly:true) != "") return true;
  if (SearchAura($player, frozenOnly:true) != "") return true;
  return false;
}

function Smoldering($player, $cardID, $zone="AURAS", $number=1, $effectSource="", $effectController="", $slot="") {
  switch ($cardID) {
    case "smoldering_scales":
      $Character = new PlayerCharacter($player);
      $Scales = $Character->FindCardID($cardID);
      $scaleIndex = $Scales != "" ? $Scales->Index() : -1;
      if (!SearchCharacterActive($player, $cardID) || SearchCurrentTurnEffects($cardID, $player))
        return false;
      break;
    case "smoldering_steel_red":
      $steelIndex = SearchDiscardForCard($player, $cardID);
      $index = explode(",", $steelIndex)[0];
      if ($steelIndex == "" || SearchCurrentTurnEffects("smoldering_steel_red", $player))
        return false;
      break;
    default:
      return false;
  }
  $message = "The heat of your " . CardLink($cardID) . " melts the frostbites!";
  AddDecisionQueue("YESNO", $player, "if_you_want_to_destroy_" . CardLink($cardID) . "_to_melt_the_frostbites");
  AddDecisionQueue("NOPASS", $player, "-", 1);
  switch ($cardID) {
    case "smoldering_scales":
      AddDecisionQueue("PASSPARAMETER", $player, "MYCHAR-$scaleIndex", 1);
      AddDecisionQueue("MZDESTROY", $player, "-", 1);
      break;
    case "smoldering_steel_red":
      AddDecisionQueue("PASSPARAMETER", $player, "MYDISCARD-$index", 1);
      AddDecisionQueue("MZBANISH", $player, "-", 1);
      AddDecisionQueue("MZREMOVE", $player, "-", 1);
      //need to remove this if its there, always call smoldering steel replacement last
      //add more REMOVECURRENTTURNEFFECT if we get more smoldering effects
      AddDecisionQueue("REMOVECURRENTTURNEFFECT", $player, "smoldering_scales", 1);
      break;
    default:
      break;
  }
  AddDecisionQueue("WRITELOG", $player, $message, 1);
  AddDecisionQueue("ELSE", $player, "-");
  // track whether to skip this check next time
  AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, $cardID, 1);
  if ($zone == "AURAS")
    AddDecisionQueue("PLAYAURA", $player, "frostbite-$number-$effectSource-$effectController", 1);
  elseif ($zone == "EQUIP")
    AddDecisionQueue("EQUIPCARD", $player, "frostbite-$slot", 1);
  return true;
}