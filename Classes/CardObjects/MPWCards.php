<?php
include_once  __DIR__ . "/AHACards.php";

class hala extends Card {
	function __construct($controller) {
    $this->cardID = "hala_bladesaint_of_the_vow";
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

	function PayAdditionalCosts($from, $index = '-') {
		PayGoldInstead($this->controller, 2);
	}

	function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
		global $combatChainState, $CCS_WagersThisLink;
		return $combatChainState[$CCS_WagersThisLink] > 0 ? 1 : 0;
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
		if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EFFECTHITEFFECT");
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

	function EffectBlockModifier($index, $from) {
		return 2;
	}

	function WhileBlockPlayTrigger($index, $cardID, $from) {
		if (TypeContains($cardID, "AR")) AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		AddCurrentTurnEffect($this->cardID, $this->controller);
	}
}

class golden_company extends BaseCard {
	function PayAdditionalCosts() {
		PayGoldInstead($this->controller, 2);
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

	function PayAdditionalCosts($from, $index = '-') {
		return $this->baseCard->PayAdditionalCosts();
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

	function PayAdditionalCosts($from, $index = '-') {
		return $this->baseCard->PayAdditionalCosts();
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

	function PayAdditionalCosts($from, $index = '-') {
		return $this->baseCard->PayAdditionalCosts();
	}
}