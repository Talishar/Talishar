<?php

function DualityPrePitch($cardID, $index, $from, $player) {
	global $CS_NumActionsPlayed;
	$names = GetAbilityNames($cardID, $index, $from);
	$attack = TypeContains($cardID, "AA", $player);
	if ($attack) {
		if (SearchCurrentTurnEffects("red_in_the_ledger_red", $player) && GetClassState($player, $CS_NumActionsPlayed) >= 1) {
      AddDecisionQueue("SETABILITYTYPEABILITY", $player, $cardID);
    } elseif ($names != "" && $from == "HAND") {
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose to play the ability or attack");
      AddDecisionQueue("BUTTONINPUT", $player, $names);
      AddDecisionQueue("SETABILITYTYPE", $player, $cardID);
    } else {
      AddDecisionQueue("SETABILITYTYPEATTACK", $player, $cardID);
    }
    AddDecisionQueue("NOTEQUALPASS", $player, "Ability");
    AddDecisionQueue("PASSPARAMETER", $player, $cardID, 1);
	}
	else {
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

		AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", 1);
		$targetType = 2;
		AddDecisionQueue("FINDINDICES", $player, "ARCANETARGET,$targetType", 1);
		AddDecisionQueue("SETDQCONTEXT", $player, "Choose a target for <0>", 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $player, "-", 1);
		AddDecisionQueue("SETLAYERTARGET", $player, $cardID, 1);

		AddDecisionQueue("ELSE", $player, "-");
		AddDecisionQueue("PASSPARAMETER", $player, $cardID, 1);
	}
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

function ProcessFragmentOnBlock($index){
	global $mainPlayer, $Stack;
	if(IsFragmentActive() && DoesBlockTriggerFragment($index))
  {
    $ChainCard = new ChainCard($index);
    $notAlreadyTriggered = $Stack->FindLayer("PRETRIGGER", $mainPlayer, target:$ChainCard->UniqueID()) == "";
    $notAlreadyTriggered = $notAlreadyTriggered && $Stack->FindLayer("TRIGGER", $mainPlayer, target:$ChainCard->UniqueID()) == "";
    if ($notAlreadyTriggered) AddLayer("TRIGGER", $mainPlayer, "FRAGMENT", $ChainCard->UniqueID());
  }
}

function IsFragmentActive() {
	global $CombatChain, $mainPlayer;
	$AttackCard = $CombatChain->AttackCard();
	$card = GetClass($AttackCard->ID(), $mainPlayer);
	if ($card != "-") return $card->HasFragment();
	return false;
}

function IsFragmentStillActive($blockingCardUID) {
	global $CombatChain;
	$BlockCard = $CombatChain->FindCardUID($blockingCardUID);
	return IsFragmentActive() && $BlockCard->TotalBlock() >= 2;
}

function DoesBlockTriggerFragment($index) {
	global $CombatChain;
	$card = $CombatChain->Card($index);
	return $card->TotalBlock() >= 2;
}

function FragmentLayer($blockingCardUID) {
	global $mainPlayer, $CombatChain, $mainPlayer;
	if (IsFragmentStillActive($blockingCardUID)) {
		AddCurrentTurnEffect("FRAGMENT", $mainPlayer);
		$attackCard = GetClass($CombatChain->AttackCard()->ID(), $mainPlayer);
		if ($attackCard != "-") $attackCard->FragmentTrigger();
	}
}

function HasFragment($cardID) {
	$card = GetClass($cardID, 0);
	if ($card != "-") return $card->HasFragment();
}

function PayLightningFlowInstead($player, $cardID) {
	if (CountAura("lightning_flow", $player) > 0) {
		AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_" . CardLink("lightning_flow"), 1);
		AddDecisionQueue("NOPASS", $player, "-", 1);
		$Auras = new Auras($player);
		$AuraCard = $Auras->FindCardID("lightning_flow");
		AddDecisionQueue("PASSPARAMETER", $player, "MYAURAS-" . $AuraCard->Index(), 1);
		AddDecisionQueue("MZDESTROY", $player, "-", 1);
		AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "$cardID-PAID", 1);
	}
}

// Use for effects that say "Prevent the next X damage"
function FloatingPrevention($index, $damage, $amount, &$remove) {
	global $CurrentTurnEffects;
	$Effect = $CurrentTurnEffects->Effect($index);
	if ($damage >= $Effect->NumUses()) {
		$remove = true;
		return $Effect->NumUses();
	}
	else {
		if (!$amount) $Effect->AddUses(-$damage);
		return $damage;
	}
}

function HoloFlicker($player, $MZIndex) {
	$Aura = MZIndexToObject($player, $MZIndex);
	$banishInd = $Aura->Banish();
	if ($banishInd != -1) {
		$BanishCard = new BanishCard($player, $banishInd);
		PlayAura($BanishCard->ID(), $player, holoCounters:1);
		$BanishCard->Remove();
	}
}

function FirstDamageTrigger($target, $cardID, $player) {
	global $CombatChain;
	if ($CombatChain->AttackCard()->ID() != $cardID) return; // for now only make this work when it's the active link
	if (is_numeric($target) && $CombatChain->AttackCard()->NumTimesUsed() == 0) {
		AddLayer("TRIGGER", $player, $cardID);
	}
	$CombatChain->AttackCard()->AddUse();
}