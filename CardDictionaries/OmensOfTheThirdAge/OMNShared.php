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
	$card = GetClass($AttackCard->ID(), $mainPlayer, "CC", $AttackCard->UniqueID());
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
	global $mainPlayer, $CombatChain, $mainPlayer, $CS_NumFragmented;
	if (IsFragmentStillActive($blockingCardUID)) {
		AddCurrentTurnEffect("FRAGMENT", $mainPlayer);
		$attackCard = GetClass($CombatChain->AttackCard()->ID(), $mainPlayer, "CC", $CombatChain->AttackCard()->UniqueID());
		if ($attackCard != "-") $attackCard->FragmentTrigger();
		IncrementClassState($mainPlayer, $CS_NumFragmented);
	}
}

function HasFragment($cardID) {
	$card = GetClass($cardID, 0);
	if ($card != "-") return $card->HasFragment();
}

function PayLightningFlowInstead($player, $cardID) {
	if (CountAura("lightning_flow", $player) > 0) {
		AddDecisionQueue("YESNO", $player, "if_you_want_to_pay_a_" . "{{element|Lightning Flow|" . GetElementColorCode("LIGHTNING") . "|lightning_flow}}", 1);
		AddDecisionQueue("NOPASS", $player, "-", 1);
		$Auras = new Auras($player);
		$AuraCard = $Auras->FindCardID("lightning_flow");
		AddDecisionQueue("PASSPARAMETER", $player, "MYAURAS-" . $AuraCard->Index(), 1);
		AddDecisionQueue("MZDESTROY", $player, "-", 1);
		AddDecisionQueue("ADDCURRENTTURNEFFECT", $player, "$cardID-PAID", 1);
	}
}

// Use for effects that say "Prevent the next X damage"
function FloatingPrevention($index, $damage, $amount, &$remove, $preventable=true) {
	global $CurrentTurnEffects;
	$Effect = $CurrentTurnEffects->Effect($index);
	if ($damage >= $Effect->NumUses() && $preventable) {
		$remove = true;
		return $Effect->NumUses();
	}
	elseif($preventable) {
		if (!$amount) $Effect->AddUses(-$damage);
		return $damage;
	}
	return 0;
}

function HoloFlicker($player, $MZIndex) {
	$Aura = MZIndexToObject($player, $MZIndex);
	if ($Aura == "") return;
	$banishInd = $Aura->Banish();
	if ($banishInd != -1) {
		$BanishCard = new BanishCard($player, $banishInd);
		PlayAura($BanishCard->ID(), $player, holoCounters:1);
		$BanishCard->Remove();
	}
}

function FirstDamageTrigger($target, $cardID, $player, $effectID="-") {
	global $CombatChain, $combatChainState, $CCS_AttackDamageDealtToHero;
	$triggeringCard = $effectID == "-" ? $cardID : $effectID;
	if ($CombatChain->AttackCard()->ID() != $cardID) return; // for now only make this work when it's the active link
	if (is_numeric($target) && $combatChainState[$CCS_AttackDamageDealtToHero] == 0) {
		AddLayer("TRIGGER", $player, $triggeringCard, $target);
	}
}

// returns a list of all attack action cards that could be targeted
function TargetAttackActionCard($player="", $talent="", $maxCost=-1) {
	global $Stack, $CombatChain, $ChainLinks, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
	$targets = [];
	if (IsLayerStep()) {
		$botLayer = $Stack->BottomLayer();
		if (!TypeContains($botLayer->ID(), "AA")) {}
		elseif ($player != "" && $botLayer->PlayerID() != $player) {}
		elseif ($talent != "" && !TalentContains($botLayer->ID(), "LIGHTNING", $botLayer->PlayerID())) {}
		elseif ($maxCost != -1 && CardCost($botLayer->ID(), "LAYER", $botLayer->Index()) > $maxCost) {}
		else $targets[] = "LAYER-" . $botLayer->Index();
	}
	for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
		$ChainCard = $CombatChain->Card($i, true);
		if ($i == 0 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] == "-") continue;
		if (!TypeContains($ChainCard->ID(), "AA")) continue;
		if ($player != "" && $ChainCard->PlayerID() != $player) continue;
		if ($talent != "" && !TalentContains($ChainCard->ID(), "LIGHTNING", $ChainCard->PlayerID())) continue;
		if ($maxCost != -1 && CardCost($ChainCard->ID(), "CC", $ChainCard->Index()) > $maxCost) continue;
		$targets[] = "COMBATCHAINLINK-" . $ChainCard->Index();
	}
	for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
		$Link = $ChainLinks->GetLink($i);
		for ($j = 0; $j < $Link->NumCards(); ++$j) {
			$ChainCard = $Link->GetLinkCard($j, true);
			if (!$ChainCard->StillOnChain()) continue;
			if (!TypeContains($ChainCard->ID(), "AA")) continue;
			if ($player != "" && $ChainCard->PlayerID() != $player) continue;
			if ($talent != "" && !TalentContains($ChainCard->ID(), "LIGHTNING", $ChainCard->PlayerID())) continue;
			if ($maxCost != -1 && CardCost($ChainCard->ID(), "CC", $ChainCard->Index()) > $maxCost) continue;
			$targets[] = "PASTCHAINLINK-" . $ChainCard->Index() . "-$i";
		}
	}
	return $targets;
}

// returns a list of any attack that can be targeted
function TargetAttack($player) {
	global $Stack, $CombatChain, $ChainLinks, $combatChainState, $CCS_GoesWhereAfterLinkResolves, $AttackQueue;
	$targets = [];
	if (IsLayerStep()) {
		$botLayer = $Stack->BottomLayer();
		$targets[] = "LAYER-" . $botLayer->Index();
	}

	$i = 0;
	if ($CombatChain->HasCurrentLink()) {
		$ChainCard = $CombatChain->Card($i, true);
		if ($combatChainState[$CCS_GoesWhereAfterLinkResolves] != "-")
			$targets[] = "COMBATCHAINLINK-" . $ChainCard->Index();
	}

	for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
		$Link = $ChainLinks->GetLink($i);
		$j = 0;
		$ChainCard = $Link->GetLinkCard($j, true);
		if ($ChainCard->StillOnChain())
			$targets[] = "PASTCHAINLINK-" . $ChainCard->Index() . "-$i";
	}

	for ($i = 0; $i < $AttackQueue->NumAttacks(); ++$i) {
		$Card = $AttackQueue->Card($i, true);
		$targets[] = "ATTACKQUEUE-" . $Card->Index();
	}
	return $targets;
}

function SetDamageSourceUID($uid) {
	global $CS_ResolvingLayerUniqueID;
	SetClassState(1, $CS_ResolvingLayerUniqueID, $uid);
	SetClassState(2, $CS_ResolvingLayerUniqueID, $uid);
}