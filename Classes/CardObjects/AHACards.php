<?php

class hala_bladesaint_of_the_vow extends Card {
  function __construct($controller) {
    $this->cardID = "hala_bladesaint_of_the_vow";
    $this->controller = $controller;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
		if (SearchCharacterAliveSubtype($this->controller, "Sword")) return false;
		return true;
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
		Tap("MYCHAR-$index", $this->controller);
		$search = "MYCHAR:subtype=Sword";
		AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a sword to sharpen", 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
		$CharacterCard = new CharacterCard($index, $this->controller);
		$CharacterCard->AddUse();
		$CharacterCard->SetUsed(2);
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) Sharpen("MYCHAR-$index", $this->controller);
	}

	function CurrentEffectEndTurnAbilities($i, &$remove) {
		global $currentTurnEffects;
		$remove = true;
		$uid = $currentTurnEffects[$i + 2];
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		$char = &GetPlayerCharacter($this->controller);
		$char[$index + 3] = 0;
	}
}

class zenith_blade extends Card {
	function __construct($controller) {
    $this->cardID = "zenith_blade";
    $this->controller = $controller;
	}

	function DoesAttackHaveGoAgain() {
		global $CombatChain, $CurrentTurnEffects;
		$ClassState = new ClassState($this->controller);
		$originUID = $CombatChain->AttackCard()->OriginUniqueID();
		$foundSharpen = $CurrentTurnEffects->FindSpecificEffect("hala_bladesaint_of_the_vow", $originUID);
		return $foundSharpen->Index() != -1 && $ClassState->AttacksWithWeapon() < 1;
	}

	function AbilityCost() {
		return 1;
	}

	function AbilityType($index = -1, $from = '-') {
		return "AA";
	}
}

class edict_of_steel extends BaseCard {
	function PayAdditionalCosts() {
		$search = "MYCHAR:subtype=Sword";
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a sword to sharpen");
		AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}

	function PlayAbility($target, $threshold) {
		$uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) {
			Sharpen("MYCHAR-$index", $this->controller);
			$weaponCard = new CharacterCard($index, $this->controller);
			if ($weaponCard->NumPowerCounters() >= $threshold) {
				PlayAura("flurry", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
			}
		}
	}
}

class edict_of_steel_red extends Card {
	function __construct($controller) {
    $this->cardID = "edict_of_steel_red";
    $this->controller = $controller;
		$this->baseCard = new edict_of_steel($this->cardID, $this->controller);
	}

	function PayAdditionalCosts($from, $index = '-') {
		$this->baseCard->PayAdditionalCosts();
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$this->baseCard->PlayAbility($target, 1);
		return "";
	}
}

class edict_of_steel_yellow extends Card {
	function __construct($controller) {
    $this->cardID = "edict_of_steel_yellow";
    $this->controller = $controller;
		$this->baseCard = new edict_of_steel($this->cardID, $this->controller);
	}

	function PayAdditionalCosts($from, $index = '-') {
		$this->baseCard->PayAdditionalCosts();
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$this->baseCard->PlayAbility($target, 2);
		return "";
	}
}

class edict_of_steel_blue extends Card {
	function __construct($controller) {
    $this->cardID = "edict_of_steel_blue";
    $this->controller = $controller;
		$this->baseCard = new edict_of_steel($this->cardID, $this->controller);
	}

	function PayAdditionalCosts($from, $index = '-') {
		$this->baseCard->PayAdditionalCosts();
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$this->baseCard->PlayAbility($target, 3);
		return "";
	}
}

class flurry extends Card {
	function __construct($controller) {
    $this->cardID = "flurry";
    $this->controller = $controller;
	}

	function PermanentPlayAbility($cardID, $from, $i) {
		global $Stack;
		$abilityType = GetResolvedAbilityType($cardID, $from);
		$cardSubType = CardSubType($cardID);
		$auraCard = new AuraCard($i, $this->controller);
		if ((DelimStringContains($cardSubType, "Aura") && $from == "PLAY" && IsWeapon($cardID, $from)) || (TypeContains($cardID, "W", $this->controller) && $abilityType == "AA") && $abilityType != "I") {
			AddLayer("TRIGGER", $this->controller, $this->cardID, $Stack->TopLayer($cardID)->UniqueID(), "-", $auraCard->UniqueID());
		}
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $CurrentTurnEffects;
		$Char = new PlayerCharacter($this->controller);
		$targetWep = $Char->FindCardUID($target);
		// has flurry already been applied to the weapon?
		$otherFlurry = $CurrentTurnEffects->FindSpecificEffect($this->cardID, $target);
		if ($targetWep != "" && $otherFlurry->Index() == -1) {
			AddCurrentTurnEffect($this->cardID, $this->controller, "", $target);
			$targetWep->SetUsed(2);
			$targetWep->AddUse();
		}
		elseif ($otherFlurry != "") {
			WriteLog(CardLink($targetWep->CardID(), $targetWep->CardID()) . " has already been flurried!");
		}
		$Auras = new Auras($this->controller);
		$AuraCard = $Auras->FindCardUID($uniqueID);
		if ($AuraCard != "") $AuraCard->Destroy();
	}
}

class paragon_plate extends Card {
  function __construct($controller) {
    $this->cardID = "paragon_plate";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		GainResources($this->controller, 1);
    return "";
  }

	function AbilityType($index = -1, $from = '-') {
		return "AR";
	}

	function PayAdditionalCosts($from, $index = '-') {
		global $CCS_WeaponIndex, $combatChainState;
		$CharCard = new CharacterCard($index, $this->controller);
		$CharCard->Tap();
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
		$Weapon->AddPowerCounters(-1);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CCS_WeaponIndex, $combatChainState, $CombatChain;
		if (!TypeContains($CombatChain->AttackCard()->ID(), "Sword", $this->controller)) return true;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
		if ($Weapon->NumCounters() <= 0) return true;
		return false;
	}
}

class anticipating_gaze extends Card {
  function __construct($controller) {
    $this->cardID = "anticipating_gaze";
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
		if ($CharacterCard->IsActive() && TypeContains($CombatChain->AttackCard()->ID(), "Sword"))
			AddLayer("TRIGGER", $this->controller, $this->cardID, $index, "ONHITEFFECT");
	}

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		global $CCS_WeaponIndex, $combatChainState;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
		if ($Weapon->NumCounters() > 0) {
			$message = "if_you_want_to_draw";
			$context = "Choose if you want to destroy " . CardLink($this->cardID) . " and remove a counter from your sword to draw a card";
			Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
			Await($this->controller, $this->cardID, index: $target, final:true);
		}
	}

	function SpecificLogic() {
		global $CCS_WeaponIndex, $combatChainState, $dqVars;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
		$Gaze = new CharacterCard($dqVars["index"], $this->controller);
		$Weapon->AddPowerCounters(-1);
		$Gaze->Destroy();
	}
}

class reverent_rerebrace extends Card {
  function __construct($controller) {
    $this->cardID = "reverent_rerebrace";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }
}

class silverstride_dodgers extends Card {
  function __construct($controller) {
    $this->cardID = "silverstride_dodgers";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function CardBlockModifier($from, $resourcesPaid, $index) {
		return SearchAurasForCard("flurry", $this->controller, false) != "" ? 1 : 0;
	}
}

class brimming_blade_red extends Card {
  function __construct($controller) {
    $this->cardID = "brimming_blade_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) Sharpen("MYCHAR-$index", $this->controller, 2);
		return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		if (SearchCharacterAliveSubtype($this->controller, "Sword")) return false;
		return true;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$search = "MYCHAR:subtype=Sword";
		SetTargets($this->controller, $this->cardID, $search);
	}
}

class gleam_of_the_blade_red extends Card {
	public $archetype;
  function __construct($controller) {
    $this->cardID = "gleam_of_the_blade_red";
    $this->controller = $controller;
		$this->archetype = new windup($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
		if (TypeContains($CombatChain->AttackCard()->ID(), "W"))
			AddEffectToCurrentAttack($this->cardID);
		return "";
  }

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return TypeContains($CombatChain->AttackCard()->ID(), "W");
	}

	function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		PlayAura("flurry", $this->controller);
	}

	function GetAbilityTypes($index = -1, $from = '-') {
		return "I,AR";
	}

	function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-') {
		global $mainPlayer, $CombatChain;
		$names = "Ability";
		$nameBlocked = NameBlocked($this->cardID, $index, $from);
		if($nameBlocked) return $names;
		if ($from != "HAND") $names = "-,Attack Reaction";
		elseif ($this->controller == $mainPlayer && $CombatChain->HasCurrentLink() && IsReactionPhase() && TypeContains($CombatChain->AttackCard()->ID(), "W")) $names .= ",Attack Reaction";
		return $names;
	}

	function CanActivateAsInstant($index = -1, $from = '') {
		return $this->archetype->CanActivateAsInstant($index, $from);
	}

	function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 1;
  }

	function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }
}

class sharp_incline extends BaseCard {
	function PayAdditionalCosts() {
		$search = "MYCHAR:subtype=Sword";
		SetTargets($this->controller, $this->cardID, $search);
	}

	function PlayAbility($target, $thresh) {
		Sharpen($target, $this->controller);
		$Weapon = MZIndexToObject($this->controller, $target);
		if ($Weapon->NumCounters() >= $thresh)
			AddCurrentTurnEffect($this->cardID, $this->controller, "-", $Weapon->UniqueID());
	}

	function CurrentEffectCostModifier($index, $playIndex, &$remove) {
		$CharCard = new CharacterCard($playIndex, $this->controller);
		$Effect = new CurrentEffect($index);
		if ($CharCard->UniqueID() == $Effect->AppliestoUniqueID()) {
			$remove = true;
			return -1;
		}
		return 0;
	}
}

class sharp_incline_red extends Card {
  function __construct($controller) {
    $this->cardID = "sharp_incline_red";
    $this->controller = $controller;
    $this->baseCard = new sharp_incline($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target, 1);
		return "";
  }

	function PayAdditionalCosts($from, $index = '-') {
		return $this->baseCard->PayAdditionalCosts();
	}

	function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
		return $this->baseCard->CurrentEffectCostModifier($index, $playIndex, $remove);
	}
}

class sharp_incline_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "sharp_incline_yellow";
    $this->controller = $controller;
    $this->baseCard = new sharp_incline($this->cardID, $this->controller);
  }
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target, 2);
		return "";
  }

	function PayAdditionalCosts($from, $index = '-') {
		return $this->baseCard->PayAdditionalCosts();
	}

	function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
		return $this->baseCard->CurrentEffectCostModifier($index, $playIndex, $remove);
	}
}

class shuck_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shuck_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("flurry", $this->controller);
		return "";
  }
}

class silverdrop_downpour_red extends Card {
  function __construct($controller) {
    $this->cardID = "silverdrop_downpour_red";
    $this->controller = $controller;
  }

	function SelfCostModifier($from) {
		return SearchCurrentTurnEffects("hala_bladesaint_of_the_vow", $this->controller) ? -1 : 0;
	}

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
		if (TypeContains($CombatChain->AttackCard()->ID(), "W"))
			AddEffectToCurrentAttack($this->cardID);
		return "";
  }

	function EffectPowerModifier($param, $attached = false) {
		return 4;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return TypeContains($CombatChain->AttackCard()->ID(), "W");
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain;
		if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) return true;
		return false;
	}
}

class backside_of_the_blade_blue extends Card {
  function __construct($controller) {
    $this->cardID = "backside_of_the_blade_blue";
    $this->controller = $controller;
  }
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain, $combatChainState, $CCS_WeaponIndex;
		if (TypeContains($CombatChain->AttackCard()->ID(), "W")) {
			// this can do some funny things with targeting previous chain links
			// for now I'm skipping it
			AddEffectToCurrentAttack($this->cardID);
			$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
			$Weapon->AddUse();
			if ($Weapon->Status() == 1) $Weapon->SetUsed(2);
		}
		return "";
  }

	function EffectPowerModifier($param, $attached = false) {
		return 1;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return TypeContains($CombatChain->AttackCard()->ID(), "W");
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain;
		if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) return true;
		return false;
	}
}

class visit_the_dawnsmith_blue extends Card {
  function __construct($controller) {
    $this->cardID = "visit_the_dawnsmith_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function StartTurnAbility($index) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, $index);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$Aura = new AuraCard($target, $this->controller);
		$Aura->Destroy();
		$Character = new PlayerCharacter($this->controller);
		for ($i = 0; $i < $Character->NumCards(); ++$i) {
			$CharacterCard = $Character->Card($i, true);
			if (SubtypeContains($CharacterCard->CardID(), "Sword"))
				Sharpen("MYCHAR-" . $CharacterCard->Index(), $this->controller);
		}
	}
}

class toe_the_line_red extends Card {
  function __construct($controller) {
    $this->cardID = "toe_the_line_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
		return "";
  }

	function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    $prevented = 2;
    if (!$amount) {
			if ($preventable) PlayAura("flurry", $this->controller);
      $remove = true;
    }
    return $prevented;
	}
}

class indefensibly_honed_blue extends Card {
  function __construct($controller) {
    $this->cardID = "indefensibly_honed_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) {
			Sharpen("MYCHAR-$index", $this->controller, 2);
			$CharacterCard = new CharacterCard($index, $this->controller);
			if ($CharacterCard->NumCounters() >= 3)
				AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID: $CharacterCard->UniqueID());
		}
		return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		if (SearchCharacterAliveSubtype($this->controller, "Sword")) return false;
		return true;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$search = "MYCHAR:subtype=Sword";
		SetTargets($this->controller, $this->cardID, $search);
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function CurrentEffectOnBlockEffect($cardID, $from, $start=-1) {
		AddLayer("TRIGGER", $this->controller, $this->cardID);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$otherPlayer = $this->controller == 1 ? 2 : 1;
		DamageTrigger($otherPlayer, 1, "DAMAGE", $this->cardID, $this->controller);
	}
}

class deadly_display extends BaseCard {
	function PlayAbility() {
		global $CombatChain, $CurrentTurnEffects;
		if (TypeContains($CombatChain->AttackCard()->ID(), "W")) {
			AddEffectToCurrentAttack($this->cardID);
			$originUID = $CombatChain->AttackCard()->OriginUniqueID();
			$foundSharpen = $CurrentTurnEffects->FindSpecificEffect("hala_bladesaint_of_the_vow", $originUID);
			if ($foundSharpen)
				AddEffectToCurrentAttack("$this->cardID-ONHIT");
		}
		return "";
	}

	function IsPlayRestricted() {
		global $CombatChain;
		if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) return true;
		return false;
	}
}

class deadly_display_red extends Card {
  function __construct($controller) {
    $this->cardID = "deadly_display_red";
    $this->controller = $controller;
		$this->baseCard = new deadly_display($this->cardID, $this->controller);
  }

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

	function EffectPowerModifier($param, $attached = false) {
		return $param == "ONHIT" ? 0 : 3;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted();
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		if ($parameter == "ONHIT")
			AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EFFECTHITEFFECT");
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
		PlayAura("flurry", $this->controller);
	}
}

class deadly_display_blue extends Card {
  function __construct($controller) {
    $this->cardID = "deadly_display_blue";
    $this->controller = $controller;
		$this->baseCard = new deadly_display($this->cardID, $this->controller);
  }

	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

	function EffectPowerModifier($param, $attached = false) {
		return $param == "ONHIT" ? 0 : 1;
	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->baseCard->IsPlayRestricted();
	}

	function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
		if ($parameter == "ONHIT")
			AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "EFFECTHITEFFECT");
	}

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
		PlayAura("flurry", $this->controller);
	}
}

class swordmasters_path extends BaseCard {
	function PlayAbility() {
		AddCurrentTurnEffect($this->cardID, $this->controller);
		AddCurrentTurnEffect("$this->cardID-SHARP", $this->controller);
	}

	function CombatEffectActive() {
		global $CombatChain;
		return SubTypeContains($CombatChain->AttackCard()->ID(), "Sword");
	}
}

class swordmasters_path_red extends Card {
  function __construct($controller) {
    $this->cardID = "swordmasters_path_red";
    $this->controller = $controller;
		$this->baseCard = new swordmasters_path($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
		return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return $this->baseCard->CombatEffectActive();
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	function SpecialType() {
		return "A";
	}
}

class swordmasters_path_blue extends Card {
  function __construct($controller) {
    $this->cardID = "swordmasters_path_blue";
    $this->controller = $controller;
		$this->baseCard = new swordmasters_path($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
		return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return $this->baseCard->CombatEffectActive();
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}

	function SpecialType() {
		return "A";
	}
}

class flurry_foot_dance_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "flurry_foot_dance_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function CardBlockModifier($from, $resourcesPaid, $index) {
		return SearchAurasForCard("flurry", $this->controller, false) ? 2 : 0;
	}
}

class ole_blue extends Card {
  function __construct($controller) {
    $this->cardID = "ole_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $combatChainState, $CCS_WeaponIndex;
		if (!str_contains($target, "COMBATCHAINATTACKS")) {
			$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
			if ($Weapon->NumPowerCounters() > 0) {
				$Weapon->AddPowerCounters(-1);
				Draw($this->controller);
				PlayAura("flurry", $this->controller);
			}
		}
		return "";
  }

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain;
		if (TypeContains($CombatChain->AttackCard()->ID(), "W")) return false;
		if (SearchCombatChainAttacks($this->controller, type:"W") != "") return false;
		return true;
	}

	function PayAdditionalCosts($from, $index = '-') {
		SetTargets($this->controller, $this->cardID, "COMBATCHAIN:type=W&COMBATCHAINATTACKS:type=W");
	}
}

class polished_blade_red extends Card {
  function __construct($controller) {
    $this->cardID = "polished_blade_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $combatChainState, $CCS_WeaponIndex;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
		$modes = explode(",", $additionalCosts);
		foreach ($modes as $mode) {
			switch($mode) {
				case "Go_again":
					GiveAttackGoAgain();
					break;
				case "Additional_attack":
					$Weapon->AddUse();
					if ($Weapon->Status() == 1) $Weapon->SetUsed(2);
					break;
				case "Discount_attack":
					AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$Weapon->UniqueID());
					break;
				default:
					break;
			}
		}
		return "";
  }

	function PayAdditionalCosts($from, $index = '-') {
		global $combatChainState, $CCS_WeaponIndex;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex],  $this->controller);
		$modalities = "Go_again,Additional_attack,Discount_attack";
		$range = implode(",", range(1, $Weapon->NumPowerCounters()));
		Await($this->controller, "ChooseText", "numChoices", choices:$range, subsequent:0, context:"Choose a number of counters to remove");
		Await($this->controller, "ChooseText", "modes", choices:$modalities, modal:$this->cardID, context: "Choose modes");
		Await($this->controller, $this->cardID, final:true);
	}

	function SpecificLogic() {
		global $dqVars, $Stack;
		$Character = new PlayerCharacter($this->controller);
		$Weapon = $Character->FindCardID($this->cardID);
		$Weapon->AddPowerCounters(-1 * intval($dqVars["numChoices"]));
		$Layer = $Stack->TopLayer($this->cardID);
		$Layer->SetAdditionalCosts($dqVars["modes"]);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		global $CombatChain, $combatChainState, $CCS_WeaponIndex;
		if (!TypeContains($CombatChain->AttackCard()->ID(), "W")) return true;
		$Weapon = new CharacterCard($combatChainState[$CCS_WeaponIndex],  $this->controller);
		if ($Weapon->NumPowerCounters() == 0) return true;
		return false;
	}

	function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
		$CharCard = new CharacterCard($playIndex, $this->controller);
		$Effect = new CurrentEffect($index);
		if ($CharCard->UniqueID() == $Effect->AppliestoUniqueID()) {
			$remove = true;
			return -1;
		}
		return 0;
	}
}