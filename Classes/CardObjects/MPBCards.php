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
    global $CS_ClubDamage;
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    return GetClassState($this->controller, $CS_ClubDamage) == 0;
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

class deathbeat_bludgeon extends Card {
  // Action - {r}{r}, {t}: Attack
  // If you've discarded a card with 6 or more {p} this turn, this gets +1{p}.
  function __construct($controller) {
    $this->cardID = "deathbeat_bludgeon";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityCost() {
    return 2;
  }

	function PayAdditionalCosts($from, $index = '-') {
		$Weapon = new CharacterCard($index, $this->controller);
		$Weapon->TapForCost();
	}

	function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
		$Weapon = new CharacterCard($index, $this->controller);
		return $Weapon->Tapped();
	}

	function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
		global $CS_Num6PowDisc;
		return GetClassState($this->controller, $CS_Num6PowDisc) > 0 ? 1 : 0;
	}

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function SpecialName() {
    return "Deathbeat Bludgeon";
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialClass() {
    return "BRUTE";
  }

  function SpecialType() {
    return "W";
  }

  function SpecialSubType() {
    return "Club";
  }
}

class tenderized_yellow extends Card {
  // When this hits a hero, they lose X{h}, where X is the total damage dealt to them by clubs this turn.
  function __construct($controller) {
    $this->cardID = "tenderized_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
		return HeroHitTrigger($this->controller, $this->cardID, $check);
	}

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		global $CS_ClubDamage;
		LoseHealth(GetClassState($this->controller, $CS_ClubDamage), OtherPlayer($this->controller));
	}

  function SpecialName() {
    return "Tenderized";
  }

  function SpecialPitch() {
    return 2;
  }

  function SpecialCost() {
    return 3;
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialClass() {
    return "BRUTE";
  }

  function SpecialType() {
    return "AA";
  }
}

class beat_of_savagery_blue extends Card {
  // Beat Chest (As an additional cost to play this, you may discard a card with 6 or more {p}.)
  // If you've beaten chest this turn, gain {r}{r}{r}{r}.
  // Go again
  function __construct($controller) {
    $this->cardID = "beat_of_savagery_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (SearchCurrentTurnEffects("BEATCHEST", $this->controller))
			GainResources(4, $this->controller);
		return "";
  }

	function HasBeatChest() {
    return true;
  }

  function SpecialName() {
    return "Beat of Savagery";
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 0;
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "BRUTE";
  }

  function SpecialType() {
    return "A";
  }

  function HasGoAgain($from) {
    return true;
  }
}

class fury extends Card {
  // At the start of your turn, destroy this and you've beaten chest this turn.
  function __construct($controller) {
    $this->cardID = "fury";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function StartTurnAbility($index) {
		$AuraCard =  new AuraCard($index, $this->controller);
		AddLayer("TRIGGER", $this->controller, $this->cardID, target:$AuraCard->UniqueID());
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$Auras = new Auras($this->controller);
		$AuraCard = $Auras->FindCardUID($target);
		$AuraCard->Destroy();
		AddCurrentTurnEffect("BEATCHEST", $this->controller);
	}

  function SpecialName() {
    return "Fury";
  }

  function SpecialBlock() {
    return -2;
  }

  function SpecialClass() {
    return "BRUTE";
  }

  function SpecialType() {
    return "T";
  }

  function SpecialSubType() {
    return "Aura";
  }
}
