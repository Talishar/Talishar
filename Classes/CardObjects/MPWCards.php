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
		global $currentPlayer;
		$BlockCard = new ChainCard($index);
		if (TypeContains($cardID, "AR") || (IsStaticType(CardType($cardID, $from, $currentPlayer)) && GetResolvedAbilityType($cardID, $from, $currentPlayer) == "AR"))
			AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$BlockCard->UniqueID());
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

class run_through extends BaseCard {
	function IsPlayRestricted() {
		return TargetSwordAttack($this->controller) == "";
	}

	function PlayAbility() {
		GiveAttackGoAgain();
		AddCurrentTurnEffectFromCombat($this->cardID, $this->controller);
		return "";
	}

	function CombatEffectActive() {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
	}
}

class run_through_red extends Card {
	function __construct($controller) {
    $this->cardID = "run_through_red";
    $this->controller = $controller;
    $this->baseCard = new run_through($this->cardID, $this->controller);
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted();
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return $this->baseCard->PlayAbility();
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return $this->baseCard->CombatEffectActive();
	}
}

class run_through_yellow extends Card {
	function __construct($controller) {
    $this->cardID = "run_through_yellow";
    $this->controller = $controller;
    $this->baseCard = new run_through($this->cardID, $this->controller);
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted();
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return $this->baseCard->PlayAbility();
	}

	function EffectPowerModifier($param, $attached = false) {
		return 2;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return $this->baseCard->CombatEffectActive();
	}
}

class shove_off_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shove_off_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		global $defPlayer;
		$options = GetChainLinkCards($defPlayer, "", "E,C");
		if($options != "") {
			AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $this->controller, $options);
			AddDecisionQueue("ADDHANDOWNER", $defPlayer, "-", 1);
			AddDecisionQueue("REMOVECOMBATCHAIN", $this->controller, "-", 1);
		}
    return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return TargetSwordAttack($this->controller) == "";
	}
}

class squires_bracers extends Card {
  function __construct($controller) {
    $this->cardID = "squires_bracers";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function DefaultActiveState() {
		return 1;
	}

	function PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked) {
		global $CombatChain;
		$CharacterCard = new CharacterCard($index, $this->controller);
		if ($CharacterCard->IsActive() && SubtypeContains($CombatChain->AttackCard()->ID(), "Sword"))
			AddLayer("TRIGGER", $this->controller, $this->cardID, $index);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$message = "if_you_want_to_buff_next_sword";
		$context = "Choose if you want to destroy " . CardLink($this->cardID) . " to buff your next sword attack";
		Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
		Await($this->controller, $this->cardID, index: $target, final:true);
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
	}

	function EffectPowerModifier($param, $attached = false) {
		return 2;
	}

	function SpecificLogic() {
		global $dqVars;
		$index = $dqVars["index"];
		AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->Destroy();
	}
}

class cutting_couriers extends Card {
  function __construct($controller) {
    $this->cardID = "cutting_couriers";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function DefaultActiveState() {
		return 1;
	}

	function PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked) {
		global $CombatChain;
		$CharacterCard = new CharacterCard($index, $this->controller);
		if ($CharacterCard->IsActive() && SubtypeContains($CombatChain->AttackCard()->ID(), "Sword"))
			AddLayer("TRIGGER", $this->controller, $this->cardID, $index);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$message = "if_you_want_to_buff_next_sword";
		$context = "Choose if you want to destroy " . CardLink($this->cardID) . " to give your attack go again";
		Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
		Await($this->controller, $this->cardID, index: $target, final:true);
	}

	function SpecificLogic() {
		global $dqVars;
		$index = $dqVars["index"];
		AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->Destroy();
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
	}

	function CurrentEffectGrantsGoAgain($param) {
		return true;
	}
}

class back_for_seconds_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "back_for_seconds_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_NumAttacks;
		if (GetClassState($this->controller, $CS_NumAttacks) == 2)
			AddCurrentTurnEffect("$this->cardID-3", $this->controller);
		else
			AddCurrentTurnEffect("$this->cardID-2", $this->controller);
		return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return TargetSwordAttack($this->controller) == "";
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return intval($param);
	}
}

class blade_rush_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "blade_rush_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect("$this->cardID-GOAGAIN", $this->controller);
		AddCurrentTurnEffect("$this->cardID-1", $this->controller);
		return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain, $CS_NumSwordAttacks;
		$attackCard = $CombatChain->AttackCard()->ID();
		if (!SubtypeContains($attackCard, "Sword")) return false;
		switch ($parameter) {
			case "GOAGAIN":
				return GetClassState($this->controller, $CS_NumSwordAttacks) == 0;
			default:
				return GetClassState($this->controller, $CS_NumSwordAttacks) == 1;
		}
	}

	function CurrentEffectGrantsGoAgain($param) {
		return $param == "GOAGAIN";
	}

	function EffectPowerModifier($param, $attached = false) {
		return $param != "GOAGAIN" ? 1 : 0;
	}

	function SpecialType() {
		return "A";
	}
}

class steel_on_steel_blue extends Card {
  function __construct($controller) {
    $this->cardID = "steel_on_steel_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function CardBlockModifier($from, $resourcesPaid, $index) {
		global $CombatChain;
		return TypeContains($CombatChain->AttackCard()->ID(), "W") ? 1 : 0;
	}
}