<?php
include_once  __DIR__ . "/AHACards.php";

class sword_attack_reaction {
	public $cardID;
  	public $controller;

	function __construct($cardID, $controller) {
		$this->cardID = $cardID;
		$this->controller = $controller;
	}

	function IsPlayRestricted() {
		return TargetSwordAttack($this->controller) == "";
	}

	function PayAdditionalCosts() {
		$choices = TargetSwordAttack($this->controller);
		AddDecisionQueue("PASSPARAMETER", $this->controller, $choices);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}
}

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

	function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-', $target="-") {
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

	function SpecialBlock() {
		return 5;
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

	function SpecialBlock() {
		return 4;
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

class run_through_blue extends Card {
	function __construct($controller) {
		$this->cardID = "run_through_blue";
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
		return 1;
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

class steel_on_steel_red extends Card {
  	function __construct($controller) {
		$this->cardID = "steel_on_steel_red";
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

class steel_on_steel_yellow extends Card {
	function __construct($controller) {
		$this->cardID = "steel_on_steel_yellow";
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

class downswing_red extends Card {
	public $archetype;
	function __construct($controller) {
		$this->cardID = "downswing_red";
		$this->controller = $controller;
		$this->archetype = new sword_attack_reaction($this->cardID, $controller);
 	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if (explode("-", $target, 2)[0] == "COMBATCHAINLINK") {
			AddCurrentTurnEffect($this->cardID, $this->controller);
			AddOnWagerEffects();
		}
		else
			WriteLog("A past chain link was targeted");
		return "";
 	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return 1;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->archetype->IsPlayRestricted();
	}

	function PayAdditionalCosts($from, $index = '-') {
		return $this->archetype->PayAdditionalCosts();
	}

	function WonWager($wonWager, $amount) {
		LoseHealth(1, $wonWager);
	}

	function IsWagerEffect($index) {
		return true;
	}
}

class drawing_dead_yellow extends Card {
	public $archetype;
	function __construct($controller) {
		$this->cardID = "drawing_dead_yellow";
		$this->controller = $controller;
		$this->archetype = new sword_attack_reaction($this->cardID, $controller);
  	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if (explode("-", $target, 2)[0] == "COMBATCHAINLINK") {
			AddCurrentTurnEffect($this->cardID, $this->controller);
			AddOnWagerEffects();
		}
		else
			WriteLog("A past chain link was targeted");
		return "";
  	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return 1;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->archetype->IsPlayRestricted();
	}

	function PayAdditionalCosts($from, $index = '-') {
		return $this->archetype->PayAdditionalCosts();
	}

	function WonWager($wonWager, $amount) {
		PummelHit($wonWager);
	}

	function IsWagerEffect($index) {
		return true;
	}
}

class donkey_blue extends Card {
	public $archetype;
	function __construct($controller) {
		$this->cardID = "donkey_blue";
		$this->controller = $controller;
		$this->archetype = new sword_attack_reaction($this->cardID, $controller);
  	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		if (explode("-", $target, 2)[0] == "COMBATCHAINLINK") {
			AddCurrentTurnEffect($this->cardID, $this->controller);
			AddOnWagerEffects();
		}
		else
			WriteLog("A past chain link was targeted");
		return "";
  	}

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return 1;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->archetype->IsPlayRestricted();
	}

	function PayAdditionalCosts($from, $index = '-') {
		return $this->archetype->PayAdditionalCosts();
	}

	function WonWager($wonWager, $amount) {
		AddDecisionQueue("MULTIZONEINDICES", $wonWager, "MYARS", 1);
		AddDecisionQueue("SETDQCONTEXT", $wonWager, "Choose a card you want to destroy from your arsenal", 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $wonWager, "<-", 1);
		AddDecisionQueue("MZDESTROY", $wonWager, false, 1);
  	}

	function IsWagerEffect($index) {
		return true;
	}
}

class and_again_blue extends Card {
	function __construct($controller) {
		$this->cardID = "and_again_blue";
		$this->controller = $controller;
	}
  
  	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$targetParts = explode(",", $target, 2);
		$Sword = CleanTargetToObject($this->controller, $targetParts[0]);
		$attackTarget = $targetParts[1] ?? "NA";
		if ($Sword->Index() != -1) {
			$index = $Sword->Index();
			$uniqueID = $Sword->UniqueID();
			$cardID = $Sword->CardID();
			$parameter = "EQUIP|0|$index|$uniqueID|MYCHAR";
			AddAttackQueue($cardID, $this->controller, $attackTarget, $parameter, $uniqueID);
		}
    	return "";
  	}

	function IsAttackLayer() {
		return true;
	}

	function GetTargets() {
		global $CurrentTurnEffects, $CS_WeaponsAttackedWith;
		$targets = [];
		$Character = new PlayerCharacter($this->controller);
		for ($i = 0; $i < $Character->NumCards(); ++$i) {
			$CharacterCard = $Character->Card($i, true);
			if (!SubtypeContains($CharacterCard->CardID(), "Sword")) continue;
			$foundSharpen = $CurrentTurnEffects->FindSpecificEffect("SHARPEN", $CharacterCard->UniqueID());
			if ($foundSharpen->Index() == -1) continue;
			if (!str_contains(GetClassState($this->controller, $CS_WeaponsAttackedWith), $CharacterCard->UniqueID())) continue;
			$targets[] = "MYCHAR-" . $CharacterCard->Index();
		}
		return $targets;
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return count($this->GetTargets()) == 0;
	}

	function PayAdditionalCosts($from, $index = '-') {
		$targetSwords = $this->GetTargets();
		AddDecisionQueue("GETTARGETOFATTACK", $this->controller, "$targetSwords[0],EQUIP,1");
		Await($this->controller, "AQTargeting", "attackTarget", lastResultName:"target");
		Await($this->controller, "ChooseMultiZone", "targetSword", indices:implode(",", $targetSwords));
		Await($this->controller, $this->cardID, final:true);
	}

	function SpecificLogic() {
		global $dqVars, $Stack;
		$targetSword = CleanTarget($this->controller, $dqVars["targetSword"]);
		$attackTarget = $dqVars["attackTarget"]; // AQTargeting already cleaned this
		$target = "$targetSword,$attackTarget";
		$Layer = $Stack->TopLayer($this->cardID);
		$Layer->AddTarget($target);
	}
}

class durendal extends Card {
	function __construct($controller) {
		$this->cardID = "durendal";
		$this->controller = $controller;
	}
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		return "";
	}

	function AbilityType($index = -1, $from = '-') {
		return "AA";
	}

	function AbilityCost() {
		return 1;
	}

	function CombatChainBlockModifier($cardID, $from, $index, $sourceIndex) {
		global $CombatChain;
		if ($sourceIndex != 0) return 0;
		$AttackCard = $CombatChain->AttackCard();
		$uid = $AttackCard->OriginUniqueID();
		$Character = new PlayerCharacter($this->controller);
		$Weapon = $Character->FindCardUID($uid);
		if ($Weapon->NumPowerCounters() <= 0) return 0;
		return TypeContains($cardID, "AR") || TypeContains($cardID, "DR") ? -1 : 0;
	}
}

class raise_blades_red extends Card {
  function __construct($controller) {
    $this->cardID = "raise_blades_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		Draw($this->controller, effectSource:$this->cardID);
		Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
		Await($this->controller, "ChooseMultiZone", "MZIndex", context:"Put a card from hand back on top");
		Await($this->controller, "MZRemove", "cardID");
		Await($this->controller, "AddTopDeck", final:true);
		$hand = GetHand($this->controller);
		if (count($hand) == 0) { //handle case where the game automates putting a card back
			AddDecisionQueue("DECKCARDS", $this->controller, "0", 1);
			AddDecisionQueue("SETDQVAR", $this->controller, "1", 1);
			AddDecisionQueue("SETDQCONTEXT", $this->controller, "you drew <1> and placed it back on top", 1);
			AddDecisionQueue("OK", $this->controller, "-", 1);
			AddDecisionQueue("SETDQCONTEXT", $this->controller, "-");
		}
		AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword", $this->controller);
	}

	function EffectPowerModifier($param, $attached = false) {
		return 3;
	}
}

class overwhelming_swing_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "overwhelming_swing_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		$targetParts = explode("-", $target, 3);
		$zone = $targetParts[0];
		$linkNum = $targetParts[2] ?? -1;
		$numDefended = match($zone) {
			"COMBATCHAINLINK" => NumCardsDefended(),
			"PASTCHAINLINK" => NumCardsDefended($linkNum), //future proofing
			default => 0
		};
		$val = 2 * $numDefended + 1;
		if ($zone == "COMBATCHAINLINK")
			AddCurrentTurnEffect("$this->cardID-$val", $this->controller);
    return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		return true;
	}

	function EffectPowerModifier($param, $attached = false) {
		return $param;
	}

	private
	function GetTargets() {
		$attacks = TargetAttack($this->controller);
		$targets = [];
		foreach($attacks as $attack) {
			$Card = MZIndexToObject($this->controller, $attack);
			if (is_object($Card) && TypeContains($Card->ID(), "W"))
				$targets[] = $attack;
		}
		return implode(",", $targets);
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		return $this->GetTargets() == "";
	}

	function PayAdditionalCosts($from, $index = '-') {
		$targets = $this->GetTargets();
		Await($this->controller, "ChooseMultiZone", "index", indices:$targets, subsequent:0);
		Await($this->controller, "SetLayerTarget", layerID:$this->cardID, final:true);
	}

	function CardCost($from = '-') {
		return NumCardsDefended();
	}
}

class rake_back_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rake_back_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
		AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

	function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
		global $CombatChain;
		return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
	}

	function EffectPowerModifier($param, $attached = false) {
		return $param != "WAGER" ? 2 : 0;
	}

	function OnAttackEffect($cardID, $i) {
    global $CombatChain;
    $Effect = new CurrentEffect($i);
    if (SubtypeContains($CombatChain->AttackCard()->ID(), "Sword") && $Effect->EffectID() == $this->cardID)
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    return false;
  }

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect("$this->cardID-WAGER", $this->controller, from:"PLAY"); // contains the wager effect
    AddOnWagerEffects();
  }

	function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
		PayGoldInstead($this->controller, $this->cardID);
	}

	function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
		$Effect = new CurrentEffect($index);
		if (str_contains($Effect->EffectID(), "PAID")) {
			$remove = true;
			return true;
		}
		return false;
	}

	function WonWager($wonWager, $amount) {
    Await($wonWager, "MultiZoneIndices", "indices", search:"MYDISCARD:type=E", subsequent:0);
		Await($wonWager, "ChooseMultiZone", "MZInd", may:true, context:"Equip an equipment from your graveyard (or pass)");
		Await($wonWager, $this->cardID, final:true);
  }

	function SpecificLogic() {
		global $dqVars;
		$Card = MZIndexToObject($this->controller, $dqVars["MZInd"]);
		if ($Card != "") {
			$cardSubtype = CardSubType($Card->CardID());
			$subType = "-";
			if (str_contains($cardSubtype, "Head"))
				$subType = "Head";
			elseif (str_contains($cardSubtype, "Chest"))
				$subType = "Chest";
			elseif (str_contains($cardSubtype, "Legs"))
				$subType = "Legs";
			elseif (str_contains($cardSubtype, "Arms"))
				$subType = "Arms";
			if (!SearchCharacterAliveSubtype($this->controller, $subType)) {
				EquipEquipment($this->controller, $Card->CardID(), from:"DISCARD");
				$Card->Remove();
			}
		}
	}

  function IsWagerEffect($index) {
    $Effect = new CurrentEffect($index);
    return $Effect->EffectID() == "$this->cardID-WAGER"; // no -WAGER or -BUFF
  }
}