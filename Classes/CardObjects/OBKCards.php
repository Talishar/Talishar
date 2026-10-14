<?php

class vyle_base extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$otherPlayer = $this->controller == 1 ? 2 : 1;
		foreach ([$this->controller, $otherPlayer] as $player) {
			if (!ArsenalFull($player)) {
				$deck = new Deck($player);
				if (!$deck->Empty()) {
					$topCard = $deck->Top(true);
					AddArsenal($topCard, $player, $from, "UP");
				}
			}
		}
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function AbilityCost() {
		return 2;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $mainPlayer;
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped() || $mainPlayer != $this->controller;
	}

	function PermanentPowerModifier(&$powerModifiers) {
		global $CombatChain, $defPlayer;
		$hasFaceUpCard = false;
		$Arsenal = new Arsenal($defPlayer);
		for ($i = 0; $i < $Arsenal->NumCards(); ++$i) {
			$ArsenalCard = $Arsenal->Card($i, true);
			if ($ArsenalCard->Facing() == "UP")
				$hasFaceUpCard = true;
		}
		if (SubTypeContains($CombatChain->AttackCard()->ID(), "Arrow") && $hasFaceUpCard) {
			$powerModifiers[] = $this->cardID;
			$powerModifiers[] = 1;
			return 1;
		}
		return 0;
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "RANGER";
	}

	function SpecialTalent() {
		return "CHAOS";
	}
}

class vyle extends Card {
  function __construct($controller) {
    $this->cardID = "vyle";
    $this->controller = $controller;
		$this->baseCard = new vyle_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Vyle";
	}

	function SpecialSubType() {
		return "Young";
	}

	function SpecialHealth() {
		return 20;
	}
}

class vyle_catalyst_of_collapse extends Card {
  function __construct($controller) {
    $this->cardID = "vyle_catalyst_of_collapse";
    $this->controller = $controller;
		$this->baseCard = new vyle_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Vyle, Catalyst of Collapse";
	}

	function SpecialHealth() {
		return 40;
	}
}

class mortimer_living_virus extends Card {
  function __construct($controller) {
    $this->cardID = "mortimer_living_virus";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", search:"MYAURAS:subtype=Disease", subsequent:0);
		Await($this->controller, "ChooseMultiZone", context:"Spread one of your diseases");
		Await($this->controller, $this->cardID, final:true);
		return "";
  }

	function SpecificLogic() {
		global $dqVars;
		$otherPlayer = $this->controller == 1 ? 2 : 1;
		$choice = MZIndexToObject($this->controller, $dqVars["MZIndex"] ?? "-");
		if ($choice != "") {
			$disease = $choice->CardID();
			PlayAura($disease, $otherPlayer, effectAgent:$this->controller, effectController:$this->controller);
		}
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function AbilityCost() {
		return 1;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $mainPlayer;
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped() || $mainPlayer != $this->controller;
	}

	function SpecialName() {
		return "Mortimer Living Virus";
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "ASSASSIN";
	}

	function SpecialTalent() {
		return "CHAOS";
	}

	function SpecialHealth() {
		return 20;
	}
}

class min_front extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function BeginEndTurnAbilities($index) {
		AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$Hero = new CharacterCard(0, $this->controller);
		$Hero->Become($this->Backside());
	}

	function Backside() {
		return match($this->cardID) {
			"min_half_genius" => "min_half_mad",
			"min_genius" => "min_mad"
		};
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "MECHANOLOGIST";
	}

	function SpecialTalent() {
		return "CHAOS";
	}
}

class min_half_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_genius";
    $this->controller = $controller;
		$this->baseCard = new min_front($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 20;
	}
}

class min_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_genius";
    $this->controller = $controller;
		$this->baseCard = new min_front($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 40;
	}
}

class min_back extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function BeginEndTurnAbilities($index) {
		AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$Hero = new CharacterCard(0, $this->controller);
		$Hero->Become($this->Backside());
	}

	function Backside() {
		return match($this->cardID) {
			"min_half_mad" => "min_half_genius",
			"min_mad" => "min_genius"
		};
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "MECHANOLOGIST";
	}

	function SpecialTalent() {
		return "CHAOS";
	}
}

class min_half_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_mad";
    $this->controller = $controller;
		$this->baseCard = new min_back($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 20;
	}
}

class min_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_mad";
    $this->controller = $controller;
		$this->baseCard = new min_back($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 40;
	}
}

class huxley_base extends BaseCard {
	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "ASSASSIN,MECHANOLOGIST";
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function PermanentBoostAbility($index, $banishInd) {
		$boostedCard = new BanishCard($this->controller, $banishInd);
		$heroCard = new CharacterCard($index, $this->controller);
		$boostedCardID = $boostedCard->CardID();
		if (!$heroCard->Tapped() && HasStealth($boostedCardID) && (TypeContains($boostedCardID, "A") || TypeContains($boostedCardID, "AA")))
			AddLayer("TRIGGER", $this->controller, $this->cardID, $boostedCard->UniqueID());
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$Banish = new Banish($this->controller);
		$BanishCard = $Banish->FindCardUID($target);
		$context = "Would you like to tap your hero to be able to play " . CardLink($BanishCard->CardID()) . " at instant speed?";
		Await($this->controller, "YesNo", context:$context, subsequent:0);
		Await($this->controller, $this->cardID, uniqueID:$target, final:true);
	}

	function SpecificLogic() {
		global $dqVars;
		$target = $dqVars["uniqueID"] ?? "-";
		if ($target != "-") {
			AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$target);
			$Hero = new CharacterCard(0, $this->controller);
			$Hero->Tap();
		}
	}
}

class huxley extends Card {
  function __construct($controller) {
    $this->cardID = "huxley";
    $this->controller = $controller;
		$this->baseCard = new huxley_base($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 19;
	}

	function SpecialName() {
		return "Huxley";
	}
}

class huxley_racketeer extends Card {
  function __construct($controller) {
    $this->cardID = "huxley_racketeer";
    $this->controller = $controller;
		$this->baseCard = new huxley_base($this->cardID, $this->controller);
  }

	function SpecialHealth() {
		return 38;
	}

	function SpecialName() {
		return "Huxley Racketeer";
	}
}

class alpha_01_first_cogwarden extends Card {
	function __construct($controller) {
    $this->cardID = "alpha_01_first_cogwarden";
    $this->controller = $controller;
  }

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		switch ($target) {
			case "Load_Boosted_Arrow":
				$boostedArrows = [];
				$Banish = new Banish($this->controller);
				for ($i = 0; $i < $Banish->NumCards(); ++$i) {
					$BanishCard = $Banish->Card($i, true);
					if ($BanishCard->Modifier() == "BOOST" && SubtypeContains($BanishCard->CardID(), "Arrow"))
						$boostedArrows[] = "MYBANISH-" . $BanishCard->Index();
				}
				$choices = implode(",", $boostedArrows);
				Await($this->controller, "ChooseMultiZone", indices:$choices, context:"Load a boosted arrow", subsequent:0);
				Await($this->controller, $this->cardID, mode:"load", final:true);
				break;
			case "Give_Go_Again":
				AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
				break;
			default:
				break;
		}
	}

	function CurrentEffectGrantsGoAgain($param) {
		return true;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain, $combatChainState, $CCS_NumPowerCounters;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Arrow") && $combatChainState[$CCS_NumPowerCounters] > 0;
	}

	function AbilityCost() {
		return 1;
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped();
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an ability for " . CardLink($this->cardID));
		AddDecisionQueue("BUTTONINPUT", $this->controller, "Load_Boosted_Arrow,Give_Go_Again");
		AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
		Await($this->controller, $this->cardID, final:true);
	}

	function SpecificLogic() {
		global $Stack, $dqVars;
		$mode = $dqVars["mode"] ?? "-";
		if ($mode == "load") {
			$choice = $dqVars["MZIndex"] ?? "-";
			$banishCard = MZIndexToObject($this->controller, $choice);
			if ($banishCard != "")
				AddArsenal($banishCard->CardID(), $this->controller, "BANISH", "UP", powerCounters:2);
		}
		else {
			$Layer = $Stack->TopLayer($this->cardID);
			$Layer->AddTarget($dqVars["LASTRESULT"]);
		}
	}

	function DefaultActiveState() {
		return 1;
	}

	function SpecialName() {
		return "α-01, First Cogwarden";
	}

	function SpecialClass() {
		return "MECHANOLOGIST,RANGER";
	}

	function SpecialHealth() {
		return 19;
	}

	function SpecialType() {
		return "C";
	}
}

class azalea_assassin_base extends baseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$Deck = new Deck($this->controller);
		if (ArsenalFull($this->controller)) {
			WriteLog("🏹 Your arsenal is full, you cannot load");
      return "🏹 Your arsenal is full, you cannot load";
		}
		$topCard = $Deck->Top(true);
		$uniqueID = AddArsenal($topCard, $this->controller, "DECK", "UP");
		if (HasStealth($topCard))
			AddCurrentTurnEffect("$this->cardID-STEALTH", $this->controller, uniqueID:$uniqueID);
		if (SubtypeContains($topCard, "Arrow"))
			AddCurrentTurnEffect("$this->cardID-ARROW", $this->controller, uniqueID:$uniqueID);
		return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function CurrentEffectGrantsGoAgain($param) {
		return $param == "STEALTH";
	}

	function EffectPowerModifier($param, $attached = false) {
		return $param == "STEALTH" ? 1 : 0;
	}

	function DoesEffectGrantVeil($effectIndex) {
		$effect = new CurrentEffect($effectIndex);
		return str_contains($effect->EffectID(), "ARROW");
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
		if(ArsenalEmpty($this->controller)) {
			WriteLog("There's no card in your arsenal! Reverting Gamestate!", highlight:true);
			RevertGamestate();
			return;
		}
		AddDecisionQueue("FINDINDICES", $this->controller, "ARSENAL");
		AddDecisionQueue("CHOOSEARSENAL", $this->controller, "<-", 1);
		AddDecisionQueue("REMOVEARSENAL", $this->controller, "-", 1);
		AddDecisionQueue("ADDBOTDECK", $this->controller, "-", 1);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard  = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped() || ArsenalEmpty($this->controller);
	}

	function DefaultActiveState() {
		return 1;
	}

	function SpecialType() {
		return "C";
	}

	function SpecialClass() {
		return "ASSASSIN,RANGER";
	}
}

class azalea_lone_vigilante extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_lone_vigilante";
    $this->controller = $controller;
		$this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Azalea Lone Vigilante";
	}

	function SpecialHealth() {
		return 20;
	}
}

class azalea_ace_of_spades extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_ace_of_spades";
    $this->controller = $controller;
		$this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }

	function SpecialName() {
		return "Azalea Ace of Spades";
	}

	function SpecialHealth() {
		return 40;
	}
}