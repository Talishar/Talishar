<?php

class vyle_base extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$otherPlayer = $this->controller == 1 ? 2 : 1;
		foreach ([$this->controller, $otherPlayer] as $player) {
			if (!ArsenalFull($player)) {
				$deck = new Deck($player);
				if (!$deck->Empty()) {
					$topCard = $deck->Top(true);
					AddArsenal($topCard, $player, "DECK", "UP");
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
}

class vyle extends Card {
  function __construct($controller) {
    $this->cardID = "vyle";
    $this->controller = $controller;
		$this->baseCard = new vyle_base($this->cardID, $this->controller);
  }
}

class vyle_catalyst_of_collapse extends Card {
  function __construct($controller) {
    $this->cardID = "vyle_catalyst_of_collapse";
    $this->controller = $controller;
		$this->baseCard = new vyle_base($this->cardID, $this->controller);
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
}

class min_half_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_genius";
    $this->controller = $controller;
		$this->baseCard = new min_front($this->cardID, $this->controller);
  }
}

class min_genius extends Card {
  function __construct($controller) {
    $this->cardID = "min_genius";
    $this->controller = $controller;
		$this->baseCard = new min_front($this->cardID, $this->controller);
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
}

class min_half_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_half_mad";
    $this->controller = $controller;
		$this->baseCard = new min_back($this->cardID, $this->controller);
  }
}

class min_mad extends Card {
  function __construct($controller) {
    $this->cardID = "min_mad";
    $this->controller = $controller;
		$this->baseCard = new min_back($this->cardID, $this->controller);
  }
}

class huxley_base extends BaseCard {
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function PermanentBoostAbility($index, $banishInd, $owner) {
		if ($owner != $this->controller) return;
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
}

class huxley_racketeer extends Card {
  function __construct($controller) {
    $this->cardID = "huxley_racketeer";
    $this->controller = $controller;
		$this->baseCard = new huxley_base($this->cardID, $this->controller);
  }
}

class a_01_first_cogwarden extends Card {
	function __construct($controller) {
    $this->cardID = "a_01_first_cogwarden";
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
			if ($banishCard != "") {
				AddArsenal($banishCard->CardID(), $this->controller, "BANISH", "UP", powerCounters:2);
				$banishCard->Remove();
			}
		}
		else {
			$Layer = $Stack->TopLayer($this->cardID);
			$Layer->AddTarget($dqVars["LASTRESULT"]);
		}
	}

	function DefaultActiveState() {
		return 1;
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
}

class azalea_lone_vigilante extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_lone_vigilante";
    $this->controller = $controller;
		$this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }
}

class azalea_ace_of_spades extends Card {
  function __construct($controller) {
    $this->cardID = "azalea_ace_of_spades";
    $this->controller = $controller;
		$this->baseCard = new azalea_assassin_base($this->cardID, $this->controller);
  }
}

class paupers_knife extends Card {
  function __construct($controller) {
    $this->cardID = "paupers_knife";
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

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped();
	}
}

class cogwerx_dueling_pistol extends Card {
  function __construct($controller) {
    $this->cardID = "cogwerx_dueling_pistol";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		global $CS_PlayIndex;
		$abilityType = GetResolvedAbilityType($this->cardID);
		if($abilityType == "A")
		{
			$index = GetClassState($this->controller, $CS_PlayIndex);
			$character = new CharacterCard($index, $this->controller);
			$character->SetCounters(1);
		}
    return "";
  }

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
		$abilityType = GetResolvedAbilityType($this->cardID);
		if ($abilityType == "AA")
			$CharacterCard->AddCounters(-1);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped() || GetAbilityNames($this->cardID, $index, $from) == "-";;
	}

	function HasGoAgain($from) {
		return false;
	}

	function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-', $allNames = false) {
		if ($allNames) return "Add_a_steam_counter,Attack";
		if (!is_numeric($index) || $index == -1) return "";
		$rv = SearchLayersForPhase("RESOLUTIONSTEP") == -1 ? "Add_a_steam_counter" : "-";
		$CharacterCard = new CharacterCard($index, $this->controller);
		if ($CharacterCard->NumCounters() > 0 && !SearchCurrentTurnEffects("kabuto_of_imperial_authority", $this->controller)) $rv .= ",Attack";
		return $rv;
	}

	function GetAbilityTypes($index = -1, $from = '-') {
		return "A,AA";
	}

	function AbilityType($index = -1, $from = '-') {
		return "A";
	}

	function AbilityCost() {
		$abilityType = GetResolvedAbilityType($this->cardID);
		return ($abilityType == "A" ? 1 : 0);
	}

	function AbilityHasGoAgain($from) {
		$abilityType = GetResolvedAbilityType($this->cardID);
		return $abilityType == "A";
	}
}

class one_click_pinpointer extends Card {
  function __construct($controller) {
    $this->cardID = "one_click_pinpointer";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$Arsenal = new Arsenal($this->controller);
		for ($i = $Arsenal->NumCards() - 1; $i >= 0; --$i) {
			$ArsenalCard = $Arsenal->Card($i, true);
			AddBottomDeck($ArsenalCard->CardID(), $this->controller, "ARS");
			$ArsenalCard->Remove();
		}
		LoadArrow($this->controller);
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return "A";
	}

	function AbilityHasGoAgain($from) {
		return true;
	}

	function AbilityCost() {
		$Character = new PlayerCharacter($this->controller);
		for ($i = 0; $i < $Character->NumCards(); ++$i) {
			$CharacterCard = $Character->Card($i, true);
			if (SubtypeContains($CharacterCard->CardID(), "Bow") && $CharacterCard->Tapped())
				return 1;
		}
		return 0;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$CharacterCard = new CharacterCard($index, $this->controller);
		return $CharacterCard->Tapped();
	}
}

class extinction_level_event_red extends Card {
	function __construct($controller) {
		$this->cardID = "extinction_level_event_red";
		$this->controller = $controller;
	}
	
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return "";
	}

	function EntersArenaAbility($index = -1) {
		$AuraCard = new AuraCard($index, $this->controller);
		AddLayer("TRIGGER", $this->controller, $this->cardID, $AuraCard->UniqueID(), "ENTERS");
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $AllDiseases;
		switch($additionalCosts) {
			case "ENTERS":
				$Auras = new Auras($this->controller);
				$AuraCard = $Auras->FindCardUID($target);
				if ($AuraCard->Index() != -1) {
					$choices = [];
					foreach ($AllDiseases as $disease) {
						$choices[] = "CARDID-$disease";
					}
					$choices = implode(",", $choices);
					Await($this->controller, "ChooseMultiZone", indices:$choices, context:"Choose a disease to propagate", subsequent:0);
					Await($this->controller, $this->cardID, uniqueID:$target);
				}
				break;
			case "EXTINCT":
				$count = 0;
				foreach ([1, 2] as $player) {
					$Auras = new Auras($player);
					for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
						$AuraCard = $Auras->Card($i, true);
						if (SubtypeContains($AuraCard->CardID(), "Disease"))
							++$count;
					}
				}
				if ($count >= 33) {
					WriteLog("And all was still", highlight:true);
					$otherPlayer = $this->controller == 1 ? 2 : 1;
					LoseHealth(GetHealth($otherPlayer), $otherPlayer);
				}
				else {
					WriteLog(33 - $count . " diseases left until extinction");
				}
				break;
			default:
				break;
		}
	}

	function SpecificLogic() {
		global $dqVars;
		$uniqueID = $dqVars["uniqueID"] ?? "-";
		$choice = explode("-", $dqVars["MZIndex"] ?? "-")[1];
		$Auras = new Auras($this->controller);
		$AuraCard = $Auras->FindCardUID($uniqueID);
		if ($AuraCard->Index() != -1 && $choice != "") {
			WriteLog(CardLink($choice) . " will spread!", highlight:true, highlightColor:"g");
			$AuraCard->AddModality(GamestateSanitize(CardName($choice)));
		}
	}

	function StartTurnAbility($index) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EXTINCT");
	}
}

class bootlegger_bust_up extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function SelfBoostEffect($boostedCardID, $owner) {
		if (!ClassContains($boostedCardID, "MECHANOLOGIST", $owner))
			AddCurrentTurnEffect($this->cardID, $this->controller);
	}

	function EffectPowerModifier($param, $attached = false) {
		return 2;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}
}

class bootlegger_bust_up_red extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_red";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}

class bootlegger_bust_up_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_yellow";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}

class bootlegger_bust_up_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bootlegger_bust_up_blue";
    $this->controller = $controller;
    $this->baseCard = new bootlegger_bust_up($this->cardID, $this->controller);
  }
}