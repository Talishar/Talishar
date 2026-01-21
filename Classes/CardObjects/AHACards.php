<?php

class hala_bladesaint_of_the_vow extends Card {
  function __construct($controller) {
    $this->cardID = "hala_bladesaint_of_the_vow";
    $this->controller = $controller;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
		if (SearchCharacterAliveSubtype($this->controller, "Sword")) return false;
		return true;
	}

	function AbilityCost() {
		return 3;
	}

	function AbilityType($index = -1, $from = '-') {
		return "A";
	}

	function AbilityHasGoAgain($from) {
		return true;
	}

	function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
		Tap("MYCHAR-$index", $this->controller);
		$search = "MYCHAR:subtype=Sword";
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a sword to sharpen");
		AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) Sharpen("MYCHAR-$index", $this->controller);
	}

	function CurrentEffectEndTurnAbilities($i, &$remove) {
		global $currentTurnEffects;
		$remove = true;
		$uid = $currentTurnEffects[$i + 2];
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		$char = &GetPlayerCharacter($this->controller);
		$char[$index + 3] = 0;
	}
}

class zenith_blade extends Card {
	function __construct($controller) {
    $this->cardID = "zenith_blade";
    $this->controller = $controller;
	}

	function DoesAttackHaveGoAgain() {
		global $CombatChain, $CurrentTurnEffects;
		$originUID = $CombatChain->AttackCard()->OriginUniqueID();
		$foundSharpen = $CurrentTurnEffects->FindSpecificEffect("hala_bladesaint_of_the_vow", $originUID);
		return $foundSharpen != "";
	}
}

class edict_of_steel_red extends Card {
	function __construct($controller) {
    $this->cardID = "edict_of_steel_red";
    $this->controller = $controller;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$search = "MYCHAR:subtype=Sword";
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a sword to sharpen");
		AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) {
			Sharpen("MYCHAR-$index", $this->controller);
			$weaponCard = new CharacterCard($index, $this->controller);
			if ($weaponCard->NumPowerCounters() > 0) {
				PlayAura("flurry", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
			}
		}
	}
}

class flurry extends Card {
	function __construct($controller) {
    $this->cardID = "flurry";
    $this->controller = $controller;
	}

	function PermanentPlayAbility($cardID, $from) {
		global $Stack;
		$abilityType = GetResolvedAbilityType($cardID, $from);
		$cardSubType = CardSubType($cardID);
		if ((DelimStringContains($cardSubType, "Aura") && $from == "PLAY" && IsWeapon($cardID, $from)) || (TypeContains($cardID, "W", $this->controller) && $abilityType == "AA") && $abilityType != "I") {
			AddLayer("TRIGGER", $this->controller, $this->cardID, $Stack->TopLayer($cardID)->UniqueID());
		}
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $CurrentTurnEffects;
		$Char = new PlayerCharacter($this->controller);
		$targetWep = $Char->FindCardUID($target);
		// has flurry already been applied to the weapon?
		$otherFlurry = $CurrentTurnEffects->FindSpecificEffect($this->cardID, $target);
		if ($targetWep != "" && $otherFlurry == "") {
			AddCurrentTurnEffect($this->cardID, $this->controller, "", $target);
			$targetWep->SetUsed(2);
			$targetWep->AddUse();
		}
		elseif ($otherFlurry != "") {
			WriteLog(CardLink($targetWep->CardID(), $targetWep->CardID()) . " has already been flurried!");
		}
	}
}