<?php
include_once  __DIR__ . "/AHACards.php";

class hala extends Card {
	function __construct($controller) {
    $this->cardID = "hala";
    $this->controller = $controller;
		$this->baseCard = new hala_base($this->cardID, $this->controller);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted($index);
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
		return $this->baseCard->PayAdditionalCosts($index);
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return $this->baseCard->PlayAbility($target);
	}
}

class golden_grail extends Card {
  function __construct($controller) {
    $this->cardID = "golden_grail";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return "AA";
	}

	function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
		PayGoldInstead($this->controller, $this->cardID);
	}

	function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
		global $combatChainState, $CCS_WagersThisLink;
		return $combatChainState[$CCS_WagersThisLink] > 0 ? 1 : 0;
	}

	function AbilityCost() {
		return 2;
	}

	function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
		$remove = true;
		return true;
	}
}

class sharpening_sparks_red extends Card {
  function __construct($controller) {
    $this->cardID = "sharpening_sparks_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddEffectToCurrentAttack($this->cardID);
		return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain;
		if (!SubTypeContains($CombatChain->AttackCard()->ID(), "Sword")) return true;
		return false;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return 2;
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "EFFECTHITEFFECT");
		return true;
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
		global $combatChainState, $CCS_WeaponIndex;
		Sharpen("MYCHAR-$combatChainState[$CCS_WeaponIndex]", $this->controller);
	}
}

class stand_tall_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "stand_tall_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }


	function WhileBlockPlayTrigger($index, $cardID, $from) {
		$BlockCard = new ChainCard($index);
		if (TypeContains($cardID, "AR")) AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$BlockCard->UniqueID());
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $CombatChain;
		$BlockCard = $CombatChain->FindCardUID($uniqueID);
		$BlockCard->ModifyDefense(2);
	}
}

class golden_company extends BaseCard {
	function PrePitchDecsions() {
		PayGoldInstead($this->controller, $this->cardID);
	}

	function CurrentTurnEffectPaid(&$remove) {
		$remove = true;
		return true;
	}
}

class golden_company_red extends Card {
  function __construct($controller) {
    $this->cardID = "golden_company_red";
    $this->controller = $controller;
    $this->baseCard = new golden_company($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
		return $this->baseCard->PrePitchDecsions();
	}

	function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
		return $this->baseCard->CurrentTurnEffectPaid($remove);
	}
}

class golden_company_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "golden_company_yellow";
    $this->controller = $controller;
    $this->baseCard = new golden_company($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
		return $this->baseCard->PrePitchDecsions();
	}

	function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
		return $this->baseCard->CurrentTurnEffectPaid($remove);
	}
}

class golden_company_blue extends Card {
  function __construct($controller) {
    $this->cardID = "golden_company_blue";
    $this->controller = $controller;
    $this->baseCard = new golden_company($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
		return $this->baseCard->PrePitchDecsions();
	}

	function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
		return $this->baseCard->CurrentTurnEffectPaid($remove);
	}
}