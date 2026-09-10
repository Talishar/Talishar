<?php

class restless_commander_red extends Card {
	function __construct($controller) {
		$this->cardID = "restless_commander_red";
		$this->controller = $controller;
	}
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return "";
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
		MZBanish($this->controller, "HAND", $choice);
		MZRemove($this->controller, $choice);
		$ChainCard->ModifyDefense(1);
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
		Await($this->controller, "ChooseMultiZone", "MZIndex", indices:$inds, context: "Discard a zombie", subsequent:0);
		Await($this->controller, "Discard", effectController:$this->controller, final:true);
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
}

class dig_for_souls_red extends Card {
	function __construct($controller) {
		$this->cardID = "dig_for_souls_red";
		$this->controller = $controller;
	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$Deck = new Deck($this->controller);
		if ($resourcesPaid > 0) {
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
				Await($this->controller, "ChooseMultiZone", "choice", may:1, indices:$inds, context:"Choose a zombie to put in the graveyard", subsequent:0);
				Await($this->controller, $this->cardID, inds:$allInds);
				// avoid creating a call to CHOOSEBOTTOM with no choices
				if (count(explode(",", $allInds)) > 1) AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);

				AddDecisionQueue("ELSE", $this->controller, "-");
				Await($this->controller, $this->cardID, else:true, inds:$allInds);
				AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);
			}
			else {
				if (SearchCount($allInds) == 1) {
					$cardID = explode("-", $allInds)[1];
					AddBottomDeck($cardID, $this->controller, "DECK");
					AddDecisionQueue("PASSPARAMETER", $this->controller, $cardID, 1);
					AddDecisionQueue("SETDQVAR", $this->controller, "1", 1);
					AddDecisionQueue("SETDQCONTEXT", $this->controller, "The top card was <1> and it was placed on the bottom", 1);
					AddDecisionQueue("OK", $this->controller, "-", 1);
				}
				else {
					Await($this->controller, $this->cardID, else:true, inds:$allInds);
					AddDecisionQueue("CHOOSEBOTTOM", $this->controller, "<-", 1);
				}
			}
			Await($this->controller, final:true);
		}
		AddCurrentTurnEffect($this->cardID, $this->controller);
    	return "";
  	}

	function SpecificLogic() {
		global $dqVars;
		$else = $dqVars["else"] ?? false;
		$inds = array_filter(explode(",", $dqVars["inds"] ?? ""));
		$choice = $else ? "-" : ($dqVars["choice"] ?? "-");
		if ($choice !== "-") {
			$choiceParts = explode("-", $choice, 2);
			if (isset($choiceParts[1]) && $choiceParts[1] !== "") {
				AddGraveyard($choiceParts[1], $this->controller, "DECK");
			}
		}
		$newInds = [];
		$found = false;
		foreach($inds as $ind) {
			if ($ind === $choice && !$found) {
				$found = true; // only exclude the first copy of the chosen card
				continue;
			}
			$indParts = explode("-", $ind, 2);
			if (isset($indParts[1]) && $indParts[1] !== "") $newInds[] = $indParts[1];
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
}

class drop_dead_bodice extends Card {
	function __construct($controller) {
		$this->cardID = "drop_dead_bodice";
		$this->controller = $controller;
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddCurrentTurnEffect($this->cardID, $this->controller);
		return "";
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->Destroy();
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		GainResources($this->controller, 1);
	}

	function DefaultActiveState() {
		return 1;
	}

	function SpecialName() {
		return "Drop Dead Bodice";
	}

	function SpecialType() {
		return "E";
	}

	function SpecialBlock() {
		return 0;
	}

	function SpecialTalent() {
		return "SHADOW";
	}

	function SpecialClass() {
		return "NECROMANCER";
	}

	function SpecialSubType() {
		return "Chest";
	}

	function ArcaneBarrier($index) {
		return 1;
	}
}

class clambering_corpses_blue extends Card {
	function __construct($controller) {
		$this->cardID = "clambering_corpses_blue";
		$this->controller = $controller;
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
		return "";
	}
	
	function ProcessAttackTrigger($target, $uniqueID) {
		Await($this->controller, "MultiZoneIndices", search:"MYHAND:subtype=Ally", subsequent:0);
		Await($this->controller, "ChooseMultiZone", may:true, context:"Discard an Ally to get +3 and go again?");
		Await($this->controller, "Discard");
		Await($this->controller, "AddCurrentTurnEffect", effectID:$this->cardID, final:true);
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		if ($parameter == "SWARM") return SubtypeContains($CombatChain->AttackCard()->ID(), "Zombie");
		else return true;
	}

	function IsCombatEffectPersistent($mode) {
		return $mode == "SWARM";
	}

	function EffectPowerModifier($param, $attached = false) {
		return $param != "SWARM" ? 3 : 0;
	}

	function CurrentEffectGrantsGoAgain($param) {
		// Could an AI fix this so that when the $param is "SWARM" it only gives go again on hit?
		return true;
	}

	function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
		return HeroHitTrigger($this->controller, $this->cardID, $check);
	}

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		AddCurrentTurnEffect("$this->cardID-SWARM", $this->controller);
	}

	function SpecialName() {
		return "Clambering Corpses";
	}

	function SpecialPitch() {
		return 3;
	}

	function SpecialPower() {
		return 1;
	}

	function SpecialClass() {
		return "NECROMANCER";
	}

	function SpecialTalent() {
		return "SHADOW";
	}
}

class otherworldly_ossuary_blue extends Card {
	function __construct($controller) {
		$this->cardID = "otherworldly_ossuary_blue";
		$this->controller = $controller;
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		BanishCardForPlayer("corrupted_corpse", $this->controller, "-", created:true);
		return "";
	}

	function SpecialName() {
		return "Otherworldly Ossuary";
	}

	function SpecialPitch() {
		return 3;
	}

	function SpecialCost() {
		return 1;
	}

	function SpecialType() {
		return "A";
	}

	function HasGoAgain($from) {
		return true;
	}

	function SpecialTalent() {
		return "SHADOW";
	}

	function SpecialClass() {
		return "NECROMANCER";
	}
}
