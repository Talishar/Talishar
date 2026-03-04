<?php

function DualityPrePitch($cardID, $index, $from, $player, $actionTargets=true) {
	global $CS_NumActionsPlayed;
	$names = GetAbilityNames($cardID, $index, $from);
	if (SearchCurrentTurnEffects("red_in_the_ledger_red", $player) && GetClassState($player, $CS_NumActionsPlayed) >= 1) {
		AddDecisionQueue("SETABILITYTYPEABILITY", $player, $cardID);
	} elseif ($names != "" && $from == "HAND" && $names != "-,Action"){
		AddDecisionQueue("SETDQCONTEXT", $player, "Choose to play the ability or the action");
		AddDecisionQueue("BUTTONINPUT", $player, $names);
		AddDecisionQueue("SETABILITYTYPE", $player, $cardID);
	} else{
		AddDecisionQueue("SETABILITYTYPEACTION", $player, $cardID);
	}
	AddDecisionQueue("NOTEQUALPASS", $player, "Action", 1);
	AddDecisionQueue("PASSPARAMETER", $player, $cardID, 1);
	AddDecisionQueue("SETDQVAR", $player, "0", 1);
	if ($actionTargets) {
		AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", 1);
		$targetType = 2;
		AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET,$targetType", 1);
		AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $player, "-", 1);
		AddDecisionQueue("SETLAYERTARGET", $player, $cardID, 1);
	}
	AddDecisionQueue("ELSE", $player, "-");
	AddDecisionQueue("PASSPARAMETER", $player, $cardID, 1);
	AddDecisionQueue("DISCARDCARD", $player, "HAND-$cardID", 1);
	AddDecisionQueue("CONVERTLAYERTOABILITY", $player, $cardID, 1);
	AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET,0", 1);
	AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", 1);
	AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
	AddDecisionQueue("SHOWSELECTEDTARGET", $player, "-", 1);
	AddDecisionQueue("SETLAYERTARGET", $player, "ABILITY", 1);
}

function FindHoloAuras($player, $subtype="LIGHTNING", $holoState=0, $excludeFirstFlow=true) {
	$Auras = new Auras($player);
	$ret = [];
	for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
		$AuraCard = $Auras->Card($i, true);
		if ($excludeFirstFlow && $AuraCard->CardID() == "lightning_flow") {
			$excludeFirstFlow = false;
			continue;
		}
		if ($AuraCard->HoloCounters() != $holoState) continue;
		if (!TalentContains($AuraCard->CardID(), $subtype, $player)) continue;
		$ret[] = "MYAURAS-" . $AuraCard->Index();
	}
	return implode(",", $ret);
}