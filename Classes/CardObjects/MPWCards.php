<?php

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
		if (CountItemByName("Gold", $this->controller) > 0) {
			AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_pay_a_" . CardLink("gold", "gold"), 1);
      AddDecisionQueue("NOPASS", $this->controller, "-", 1);
      $goldIndices = GetGoldIndices($this->controller);
      if (str_contains($goldIndices, "MYCHAR")) {
        AddDecisionQueue("PASSPARAMETER", $this->controller, $goldIndices, 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
        AddDecisionQueue("MZDESTROY", $this->controller, "-", 1);
      } else AddDecisionQueue("FINDANDDESTROYITEM", $this->controller, "gold-1", 1);
      AddDecisionQueue("ELSE", $this->controller, "-");
			AddDecisionQueue("PASSPARAMETER", $this->controller, 2, 1);
			AddDecisionQueue("PAYRESOURCES", $this->controller, 2, 1);
		}
		else {
			AddDecisionQueue("PASSPARAMETER", $this->controller, 2);
			AddDecisionQueue("PAYRESOURCES", $this->controller, 2, 1);
		}
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