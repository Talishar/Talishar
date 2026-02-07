<?php

class meet_madness_red extends Card {
  function __construct($controller) {
    $this->cardID = "meet_madness_red";
    $this->controller = $controller;
	}

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
		global $mainPlayer;
    if(IsHeroAttackTarget()) {
			if (!$check) {
				$roll = GetRandom(1,3);
				switch ($roll) {
					case 1:
						WriteLog("🌪️ The madness says <b>\"Banish a card from hand!\"</b>");
						break;
					case 2:
						WriteLog("🌪️ The madness says <b>\"banish a card from arsenal!\"</b>");
						break;
					case 3:
						WriteLog("🌪️ The madness says <b>\"banish a card from the top of your deck!\"</b>");
						break;
					default:
						break;
				}
				AddLayer("TRIGGER", $mainPlayer, $this->cardID, $roll, "ONHITEFFECT");
			}
			return true;
		}
		return false;
  }

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		global $defPlayer;
		$roll = $target;
		switch ($roll) {
			case 1:
				AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
				AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
				AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
				AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
				//including $cardID as the third param makes it count for contracts
				AddDecisionQueue("BANISHCARD", $defPlayer, "THEIRHAND,-,$cardID", 1);
				break;
			case 2:
				//including $cardID as the third param makes it count for contracts
				MZMoveCard($defPlayer, "MYARS", "MYBANISH,ARS,$cardID," . $defPlayer, false);
				break;
			case 3:
				$deck = new Deck($defPlayer);
				if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
				$deck->BanishTop(banishedBy:$cardID);
				break;
		}
	}

	function HasStealth() {
		return true;
	}
}

class rage_baiters extends Card {
	function __construct($controller) {
    $this->cardID = "rage_baiters";
    $this->controller = $controller;
	}

	function AbilityType($index = -1, $from = '-') {
		return "AR";
	}

	function AbilityCost() {
		return 1;
	}

	function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
		global $CombatChain, $chainLinks;
		Tap("MYCHAR-$index", $this->controller);
		$options = [];
		if (HasStealth($CombatChain->AttackCard()->ID())) array_push($options, "COMBATCHAINLINK-0");
		for ($i = 0; $i < count($chainLinks); ++$i) {
			if (HasStealth($chainLinks[$i][0])) array_push($options, "PASTCHAINLINK-0-$i");
		}
		$options = implode(",", $options);
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card with stealth to give on-hit mark", 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, $options, 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain, $chainLinks;
		if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
		if (HasStealth($CombatChain->AttackCard()->ID())) return false;
		foreach ($chainLinks as $link) {
			if (HasStealth($link[0])) return false;
		}
		return true;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if ($target == "COMBATCHAINLINK-0") AddEffectToCurrentAttack($this->cardID);
		else {
			$index = intval(explode("-", $target)[1]);
			AddEffectToPastAttack($index, $this->cardID);
		}
	}

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = "-", $check = false) {
    if (IsHeroAttackTarget()) {
			if (!$check) AddLayer("TRIGGER", $this->controller, $parameter, $this->cardID, "EFFECTHITEFFECT", $source);
      		return true;
		}
		return false;
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
		global $defPlayer;
		MarkHero($defPlayer);
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}
}

class creep_red extends Card {
	function __construct($controller) {
    $this->cardID = "creep_red";
    $this->controller = $controller;
	}

	function HasStealth() {
		return true;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
	}

	function ProcessAttackTrigger($target, $uniqueID) {
		AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
	}

	function CurrentEffectGrantsGoAgain($param) {
		return true;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		$attackID = $CombatChain->AttackCard()->ID();
		return HasStealth($attackID) && TypeContains($attackID, "AA", $this->controller);
	}

	function RemoveEffectFromCombatChain() {
		return true;
	}
}

class horrors_of_the_past_yellow extends Card {
	function __construct($controller) {
    $this->cardID = "horrors_of_the_past_yellow";
    $this->controller = $controller;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
	}

	function ProcessAttackTrigger($target, $uniqueID) {
		global $chainLinks, $combatChain;
		for ($i = count($chainLinks) - 1; $i >=0; --$i) {
			if (HasStealth($chainLinks[$i][0])) {
				AddEffectToCurrentAttack("$this->cardID-" . $chainLinks[$i][0]);
				return;
			}
		}
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function HasStealth() {
		return true;
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		$copiedText = explode("-", $parameter)[1] ?? "-";
		if ($copiedText != "-") {
			if (!$check) AddOnHitTrigger($copiedText, $this->cardID, $target);
			return true;
		}
		return false;
	}

	function EffectPowerModifier($param, $attached = false) {
		$copiedText = SearchCurrentTurnEffects($this->cardID, $this->controller, returnUniqueID:true);
		return PowerModifier($copiedText, "CC");
	}
}

class nights_embrace_blue extends Card {
	function __construct($controller) {
    $this->cardID = "nights_embrace_blue";
    $this->controller = $controller;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddCurrentTurnEffect($this->cardID, $this->controller);
	}

	function IsCombatEffectPersistent($mode) {
		return true;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return HasStealth($CombatChain->AttackCard()->ID());
	}

	function EffectPowerModifier($param, $attached = false) {
		return 1;
	}

	function SpecialBlock() {
		return 3;
	}
}

class stalkers_steps extends Card {
	function __construct($controller) {
		$this->cardID = "stalkers_steps";
		$this->controller = $controller;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain, $chainLinks;
		if (HasStealth($CombatChain->AttackCard()->ID())) return false;
		foreach ($chainLinks as $link) {
			if (HasStealth($link[0])) return false;
		}
		return true;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		global $CombatChain;
		if (HasStealth($CombatChain->AttackCard()->ID())) {
			GiveAttackGoAgain();
		}
	}

	function ArcaneBarrier() {
		return 1;
	}

	function PayAdditionalCosts($from, $index = '-') {
		DestroyCharacter($this->controller, $index);
	}

	function AbilityType($index = -1, $from = '-') {
		return "AR";
	}
}

class prey_spotters extends Card {
	function __construct($controller) {
		$this->cardID = "prey_spotters";
		$this->controller = $controller;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$otherPlayer = $this->controller == 1 ? 2 : 1;
		MarkHero($otherPlayer);
	}

	function PayAdditionalCosts($from, $index = '-') {
		DestroyCharacter($this->controller, $index);
	}

	function AbilityType($index = -1, $from = '-') {
		return "AR";
	}
}

class inverters_nightcowl extends Card {
	function __construct($controller) {
		$this->cardID = "inverters_nightcowl";
		$this->controller = $controller;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddCurrentTurnEffect($this->cardID, $this->controller);
	}

	function PayAdditionalCosts($from, $index = '-') {
		DestroyCharacter($this->controller, $index);
	}

	function AbilityType($index = -1, $from = '-') {
		return "A";
	}

	function AbilityHasGoAgain($from) {
		return true;
	}

	function PlayCardEffectAbility($cardID, $from, &$remove) {
		if (HasStealth($cardID)) AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		GainResources($this->controller, 1);
	}
}