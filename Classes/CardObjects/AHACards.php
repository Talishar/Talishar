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