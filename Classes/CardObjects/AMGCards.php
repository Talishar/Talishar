<?php
class madness_spiral_blue extends Card {
  function __construct($controller) {
    $this->cardID = "madness_spiral_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function OnBoostedEffect($owner, $cardID) {
    global $CS_NumBoosted;
		if ($owner == $this->controller) {
			DoBoost($this->controller, $cardID, boostOpp:true);
      IncrementClassState($this->controller, $CS_NumBoosted, -1);
		}
	}
}

class unstable_plasma_pistol extends Card {
  function __construct($controller) {
    $this->cardID = "unstable_plasma_pistol";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return "AA";
	}

	function AbilityCost() {
		return 2;
	}

	function WeaponPowerModifier($basePower) {
		global $CS_OppCardsBoosted;
		return GetClassState($this->controller, $CS_OppCardsBoosted) > 0 ? $basePower+2 : $basePower;
	}

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->AddUse(); //unlimited uses
    $CharacterCard->SetUsed(2);
  }
}