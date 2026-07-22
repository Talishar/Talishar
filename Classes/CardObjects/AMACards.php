<?php

class restless_commander_red extends Card {
  function __construct($controller) {
    $this->cardID = "restless_commander_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function HasDecay() {
    return true;
  }

  function SpecialSubType() {
    return "Zombie,Ally";
  }

  function SpecialPower() {
    return 3;
  }

  function SpecialHealth() {
    return 3;
  }

  function SpecialType() {
    return "A";
  }

  function SpecialName() {
    return "Restless Commander";
  }

  function HasBloodDebt() {
    return true;
  }

  function SpecialClass() {
    return "NECROMANCER";
  }

  function SpecialTalent() {
    return "SHADOW";
  }

	function PermanentPowerModifier(&$powerModifiers) {
		global $CombatChain;
		if (SubTypeContains($CombatChain->AttackCard()->ID(), "Zombie")) {
      $powerModifiers[] = $this->cardID;
      $powerModifiers[] = 1;
      return 1;
    }
    return 0;
	}
}

class corrupted_crown extends Card {
  function __construct($controller) {
    $this->cardID = "corrupted_crown";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function OnBlockResolveEffects($blockedFromHand, $i, $start) {
		$BlockCard = new ChainCard($i);
		AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$BlockCard->UniqueID());
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
		Await($this->controller, "ChooseMultiZone", "choice", may:true, context:"Banish a card from hand (or pass)");
		Await($this->controller, $this->cardID, uniqueID:$uniqueID, final:true);
	}

	function SpecificLogic() {
		global $dqVars, $CombatChain;
		$choice = $dqVars["choice"];
		$uniqueID = $dqVars["uniqueID"];
		$ChainCard = $CombatChain->FindCardUID($uniqueID);
		MZBanish($this->controller, "", $choice);
		MZRemove($this->controller, $choice);
		$ChainCard->ModifyDefense(1);
	}

	function SpecialName() {
		return "Corrupted Crown";
	}

	function SpecialType() {
		return "E";
	}

	function SpecialTalent() {
		return "SHADOW";
	}

	function SpecialSubType() {
		return "Head";
	}

	function SpecialBlock() {
		return 1;
	}
}

class undead_grasp extends Card {
  function __construct($controller) {
    $this->cardID = "undead_grasp";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

	function AbilityCost() {
		return 1;
	}

	function AbilityType($index = -1, $from = '-') {
		return "A";
	}

	function AbilityHasGoAgain($from) {
		return true;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->Destroy();
		$inds = SearchMultizone($this->controller, "MYHAND:subtype=Zombie");
		if ($inds == "") {
			WriteLog("No zombie to discard, reverting gamestate", highlight:true);
			RevertGamestate();
		}
		else {
			Await($this->controller, "ChooseMultiZone", "MZIndex", indices:$inds, context: "Discard a Zombie", subsequent:0);
			Await($this->controller, "Discard", effectController:$this->controller, final:true);
		}
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$inds = SearchMultizone($this->controller, "MYHAND:subtype=Zombie");
		return $inds == "";
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Zombie");
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		return AnyHitTrigger($this->controller, $this->cardID, $check, true);
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
		global $CombatChain;
		$Allies = new Allies($this->controller);
		$Ally = $Allies->FindCardUID($CombatChain->AttackCard()->OriginUniqueID());
		$Ally->Destroy();
	}

	function SpecialName() {
		return "Undead Grasp";
	}

	function SpecialType() {
		return "E";
	}

	function SpecialTalent() {
		return "SHADOW";
	}

	function SpecialClass() {
		return "NECROMANCER";
	}

	function SpecialSubType() {
		return "Arms";
	}

	function SpecialBlock() {
		return 1;
	}
}

class dig_for_souls_red extends Card {
	function __construct($controller) {
		$this->cardID = "dig_for_souls_red";
		$this->controller = $controller;
	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$Deck = new Deck($this->controller);
		$cards = $Deck->Top(true, $resourcesPaid);
		$inds = [];
		$allInds = [];
		foreach (explode(",", $cards) as $card) {
			if (SubtypeContains($card, "Zombie")) $inds[] = "CARDID-$card";
			$allInds[] = "CARDID-$card";
		}
		$inds = implode(",", $inds);
		$allInds = implode(",", $allInds);
		if ($inds != "") {
			Await($this->controller, "ChooseMultiZone", "choice", may:1, indices:$inds, context:"Choose a Zombie to put in the graveyard", subsequent:0);
			Await($this->controller, $this->cardID, inds:$allInds);
			AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);
			Await($this->controller, final:true);

			AddDecisionQueue("ELSE", $this->controller, "-");
			Await($this->controller, $this->cardID, else:true, inds:$allInds);
			AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);
			Await($this->controller, final:true);
		}
		else {
			Await($this->controller, $this->cardID, else:true, inds:$allInds);
			AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);
			Await($this->controller, final:true);
		}

		AddCurrentTurnEffect($this->cardID, $this->controller);
    	return "";
  	}

	function SpecificLogic() {
		global $dqVars;
		$else = $dqVars["else"] ?? false;
		$inds = explode(",", $dqVars["inds"]);
		if ($else)
			$choice = "-";
		else {
			$choice = $dqVars["choice"];
			$chosenCard = explode("-", $choice)[1];
			AddGraveyard($chosenCard, $this->controller, "DECK");
		}
		$newInds = [];
		foreach($inds as $ind) {
			if ($ind == $choice)
				$choice = "-";
			else
				$newInds[] = explode("-", $ind)[1];
		}
		return implode(",", $newInds);
	}

	function DynamicCost() {
    return implode(",", range(0, 20, 1));
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Zombie");
	}

	function EffectPowerModifier($param, $attached = false) {
		return 4;
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		return AnyHitTrigger($this->controller, $this->cardID, $check, true);
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target = '-') {
		global $CombatChain;
		$Allies = new Allies($this->controller);
		$Ally = $Allies->FindCardUID($CombatChain->AttackCard()->OriginUniqueID());
		$Ally->Destroy();
	}

	function SpecialName() {
		return "Dig for Souls";
	}

	function SpecialBlock() {
		return 3;
	}

	function SpecialClass() {
		return "NECROMANCER";
	}

	function SpecialTalent() {
		return "SHADOW";
	}

	function SpecialType() {
		return "A";
	}

	function HasGoAgain($from) {
		return true;
	}
}