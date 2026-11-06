<?php

class zuul_base extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		PlayAura("might", $this->controller);
	}

	function PermanentGivesAttackGoAgain($attackID) {
		if (!SubtypeContains($attackID, "Club")) return false;
		return CachedTotalPower() >= 6;
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CS_HitsWithClub;
		$CharacterCard = new CharacterCard($index, $this->controller);
		if ($CharacterCard->Tapped()) return true;
		return GetClassState($this->controller, $CS_HitsWithClub) == 0;
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "BRUTE";
	}

	function SpecialBlock() {
		return -2;
	}
}

class zuul_clubcaste extends Card {
	function __construct($controller) {
    $this->cardID = "zuul_clubcaste";
    $this->controller = $controller;
    $this->baseCard = new zuul_base($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 40;
	}

	function SpecialName() {
		return "Zuul Clubcaste";
	}
}

class zuul extends Card {
	function __construct($controller) {
    $this->cardID = "zuul";
    $this->controller = $controller;
    $this->baseCard = new zuul_base($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 20;
	}

	function SpecialName() {
		return "Zuul Clubcaste";
	}

	function SpecialSubType() {
		return "Young";
	}
}

class babaza_base extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		switch ($additionalCosts) {
			case "Might":
			case "Agility":
				PlayAura(strtolower($additionalCosts), $this->controller);
				break;
			case "Intimidate":
				$targetPlayer = str_contains($target, "MY") ? $this->controller : OtherPlayer($this->controller);
				Intimidate($targetPlayer);
				break;
			default:
				break;
		}
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
		$modalities = "Agility,Might,Intimidate";
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an Ability");
		AddDecisionQueue("BUTTONINPUT", $this->controller, $modalities);
		Await($this->controller, $this->cardID, final:true);
	}

	function SpecificLogic() {
		global $dqVars, $CS_AdditionalCosts;
		$mode = $dqVars["LASTRESULT"] ?? "-";
		WriteLog("$mode was chosen!");
		if ($mode == "Intimidate") {
			$choices = "THEIRCHAR-0";
			if (!ShouldAutotargetOpponent($this->controller))
				$choices .= ",MYCHAR-0";
			SetTargetsChoices($this->controller, $this->cardID, $choices, "Target a player to intimidate", true);
		}
		SetClassState($this->controller, $CS_AdditionalCosts, $mode);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		if ($CharacterCard->Tapped()) return true;
		return !SearchCurrentTurnEffects("BEATCHEST", $this->controller);
	}
	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "BRUTE";
	}

	function SpecialBlock() {
		return -2;
	}
}

class babaza_bloodbeat_heartfather extends Card {
	function __construct($controller) {
    $this->cardID = "babaza_bloodbeat_heartfather";
    $this->controller = $controller;
    $this->baseCard = new babaza_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Babaza Bloodbeat Heartfather";
	}

	function SpecialHealth() {
		return 40;
	}
}

class babaza extends Card {
	function __construct($controller) {
    $this->cardID = "babaza";
    $this->controller = $controller;
    $this->baseCard = new babaza_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Babaza Bloodbeat Heartfather";
	}

	function SpecialHealth() {
		return 20;
	}
}