<?php

class mortimer_base extends BaseCard {
	function PlayAbility($from, $target) {
		$abilityType = GetResolvedAbilityType($this->cardID, $from, $this->controller);
		if ($abilityType == "I") {
			Await($this->controller, "MultiZoneIndices", "indices", search:"THEIRAURAS:subtype=Disease", subsequent:0);
			Await($this->controller, "ChooseMultiZone", "MZInd", context:"Kindly cure your opponent's disease");
			Await($this->controller, "MZDestroy");
			Await($this->controller, "PlayItem", cardID:"silver", final:true);
		}
		elseif ($abilityType == "AR") {
			if (str_contains($target, "COMBATCHAINLINK"))
				AddCurrentTurnEffect($this->cardID, $this->controller, "PLAY");
			elseif (str_contains($target, "ATTACKQUEUE"))
				WriteLog("Targeting attack queue not yet supported for $this->cardID", highlight:true);
		}
	}

	private
	function GetTargets() {
		$targets = [];
		$attacks = TargetAttack($this->controller);
		foreach($attacks as $attack) {
			$cardID = GetMZCard($this->controller, $attack);
			if (ClassContains($cardID, "ASSASSIN", $this->controller))
				$targets[] = $attack;
		}
		return $targets;
	}

	function GetAbilityNames($from, $index) {
		$names = ["-", "-"];
		//can it instant?
		$instantRestricted = InstantRestricted($this->cardID, $from, $index);
		if (!$instantRestricted) $names[0] = "Instant";
		$silverCount = CountItemByName("Silver", $this->controller);
		if ($silverCount > 1 && count($this->GetTargets()) > 0 && IsReactionPhase())
			$names[1] = "AttackReaction";
		if ($names[1] == "-") return $names[0];
		return implode(",", $names);
	}

	function IsPlayRestricted($index, $from) {
		$names = GetAbilityNames($this->cardID, $index, $from);
		if ($names == "-") return true;
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped();
	}

	function PayAdditionalCosts($from, $index) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
		$abilityType = GetResolvedAbilityType($this->cardID, $from, $this->controller);
		if ($abilityType == "AR") {
			AddDecisionQueue("PASSPARAMETER", $this->controller, "silver-2", 1);
			AddDecisionQueue("FINDANDDESTROYITEM", $this->controller, "<-", 1);
			$targets = $this->GetTargets();
			Await($this->controller, "ChooseMultiZone", "index", indices:implode(",", $targets), subsequent:0);
			Await($this->controller, "SetLayerTarget", layerID:$this->cardID, final:true);
		}
	}
}

class dr_mortimer extends Card {
	function __construct($controller) {
		$this->cardID = "dr_mortimer";
		$this->controller = $controller;
		$this->baseCard = new mortimer_base($this->cardID, $this->controller);
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$this->baseCard->PlayAbility($from, $target);
		return "";
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function CurrentEffectGrantsGoAgain($param) {
		return true;
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function GetAbilityTypes($index = -1, $from = '-') {
		return "I,AR";
	}

	function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->baseCard->GetAbilityNames($from, $index);
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted($index, $from);
	}

	function PayAdditionalCosts($from, $index = '-') {
		$this->baseCard->PayAdditionalCosts($from, $index);
	}

	function SpecialName() {
		return "Dr. Mortimer";
	}

	function DefaultActiveState() {
		return 1;
	}
}

class dr_mortimer_blight_of_the_pits extends Card {
	function __construct($controller) {
		$this->cardID = "dr_mortimer_blight_of_the_pits";
		$this->controller = $controller;
		$this->baseCard = new mortimer_base($this->cardID, $this->controller);
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$this->baseCard->PlayAbility($from, $target);
		return "";
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function CurrentEffectGrantsGoAgain($param) {
		return true;
	}

	function AbilityType($index = -1, $from = '-') {
		return "I";
	}

	function GetAbilityTypes($index = -1, $from = '-') {
		return "I,AR";
	}

	function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-", $allNames = false) {
    return $this->baseCard->GetAbilityNames($from, $index);
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted($index, $from);
	}

	function PayAdditionalCosts($from, $index = '-') {
		$this->baseCard->PayAdditionalCosts($from, $index);
	}

	function DefaultActiveState() {
		return 1;
	}
}

class remember_the_mists_blue extends Card {
	function __construct($controller) {
		$this->cardID = "remember_the_mists_blue";
		$this->controller = $controller;
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return "";
	}

	function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
		return !str_contains($from, "HAND") && !str_contains($from, "ARS") ? 2 : 0;
	}

	function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
		return HeroHitTrigger($this->controller, $this->cardID, $check);
	}

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		global $mainPlayer;
		MZMoveCard($mainPlayer, "THEIRHAND", "THEIRBANISH,HAND,NT,$cardID,$mainPlayer");
	}
}

class prey_on_insecurity_red extends Card {
  function __construct($controller) {
    $this->cardID = "prey_on_insecurity_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if ($from == "COMBATCHAINATTACKS") {
			if (str_contains($target, "COMBATCHAINLINK"))
				AddCurrentTurnEffect($this->cardID, $this->controller, "PLAY");
			elseif (str_contains($target, "ATTACKQUEUE"))
				WriteLog("Targeting attack queue not yet supported for $this->cardID", highlight:true);
		}
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "AR": "AA";
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	private
	function GetTargets($index) {
		$targets = [];
		$attacks = TargetAttack($this->controller);
		foreach($attacks as $attack) {
			$cardID = GetMZCard($this->controller, $attack);
			if (str_contains($attack, "PASTCHAINLINK")) {
				$linkNum = explode("-", $attack)[2] ?? -1;
				if ($linkNum == $index) continue; // it can't target itself
			}
			if (HasStealth($cardID))
				$targets[] = $attack;
		}
		return $targets;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $mainPlayer;
		if ($this->controller != $mainPlayer) return true;
		if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
		if ($from == "PLAY") return true; // can't use it if its the active chain link
		$ChainLink = new ChainLink($index);
		$LinkCard = $ChainLink->AttackCard();
		if ($from == "COMBATCHAINATTACKS") {
			if (!IsReactionPhase()) return true;
			$hand = GetHand($this->controller);
			if (count($hand) == 0) return true;
			if (!$LinkCard->StillOnChain() || count($this->GetTargets($index)) == 0) return true;
		}
		return false;
	}

	function AbilityPlayableFromCombatChain($index = '-') {
		global $mainPlayer;
		return $this->controller == $mainPlayer;
	}

	function PayAdditionalCosts($from, $index = '-') {
		if ($from == "COMBATCHAINATTACKS") {
			$i = intdiv($index, ChainLinksPieces());
			$Link = new ChainLink($i);
			$LinkCard = $Link->AttackCard();
			$LinkCard->Destroy();

			$targets = $this->GetTargets($index);
			$hand = &GetHand($this->controller);
			if (count($hand) == 0) {
				WriteLog("You do not have a card to sink. Reverting gamestate.", highlight: true);
				RevertGamestate();
				return;
			}
			BottomDeck();
			Await($this->controller, "ChooseMultiZone", "index", indices:implode(",", $targets), subsequent:0);
			Await($this->controller, "SetLayerTarget", layerID:$this->cardID, final:true);
		}
	}
}

class mutually_assured_destruction_red extends Card {
	function __construct($controller) {
		$this->cardID = "mutually_assured_destruction_red";
		$this->controller = $controller;
	}
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return "";
	}

	function ContractType($chosenName = '') {
		return "BANISHINFECTED";
	}

	function ContractCompleted() {
		PutItemIntoPlayForPlayer("silver", $this->controller);
	}

	function AttackPlayCardAbility($cardID, $from) {
		global $currentPlayer, $mainPlayer, $combatChainState, $CCS_AttackReactionsPlayed, $CCS_DefenseReactionsPlayed;
		$piece = $currentPlayer == $mainPlayer ? $CCS_AttackReactionsPlayed : $CCS_DefenseReactionsPlayed;
		if ($combatChainState[$piece] == 1 && !IsActivated($cardID, $from) && (TypeContains($cardID, "DR") || TypeContains($cardID, "AR")))
			AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $mainPlayer, $defPlayer;
		$players = [$mainPlayer, $defPlayer];
		foreach ($players as $player) 
			PlayAura("bloodrot_pox", $player);
		foreach($players as $player) {
			$deck = new Deck($player);
			if($deck->Empty()) 
				WriteLog("The deck is already depleted.");
			else $deck->BanishTop(banishedBy:$this->cardID, banisher:$this->controller);
		}
	}
}