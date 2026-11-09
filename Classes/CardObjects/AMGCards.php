<?php
class madness_spiral_blue extends Card {
  function __construct($controller) {
    $this->cardID = "madness_spiral_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function OnBoostedEffect($owner, $cardID) {
    global $CS_NumBoosted;
		if ($owner == $this->controller) {
			DoBoost($this->controller, $cardID, boostOpp:true);
      IncrementClassState($this->controller, $CS_NumBoosted, -1);
		}
	}
}

class unstable_plasma_pistol extends Card {
  function __construct($controller) {
    $this->cardID = "unstable_plasma_pistol";
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

	function WeaponPowerModifier($basePower) {
		global $CS_OppCardsBoosted;
		return GetClassState($this->controller, $CS_OppCardsBoosted) > 0 ? $basePower+2 : $basePower;
	}

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->AddUse(); //unlimited uses
    $CharacterCard->SetUsed(2);
  }
}


class brainwave_reformer extends Card {
  // Instant - Destroy this: Flip your hero.
  // Temper (When the combat chain closes, if this defended, put a -1{d} counter on it, then if it has 0{d}, destroy it.)
  function __construct($controller) {
    $this->cardID = "brainwave_reformer";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->FlipBecome();
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function SpecialName() {
    return "Brainwave Reformer";
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "MECHANOLOGIST";
  }

  function SpecialTalent() {
    return "CHAOS";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Head";
  }

  function HasTemper() {
    return true;
  }
}

class syncopated_heart_pump extends Card {
  // Instant - {t}: Until end of turn, whenever 1 or more cards are banished from an opponent's deck, gain {r}. At the beginning of the end phase, destroy this.
  // Arcane Barrier 1 (If you would be dealt arcane damage, you may pay {r} to prevent 1 of it.)
  function __construct($controller) {
    $this->cardID = "syncopated_heart_pump";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    // This is going to have a bug because we don't track multi-event banishes well
    AddCurrentTurnEffect($this->cardID, $this->controller);
    $Character = new PlayerCharacter($this->controller);
    $Equipment = $Character->FindCardUID($additionalCosts);
    $Equipment->FlagDestroy();
    return "";
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainResources(1, $this->controller);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $CS_AdditionalCosts;
    $Equipment = new CharacterCard($index, $this->controller);
    $Equipment->TapForCost();
    SetClassState($this->controller, $CS_AdditionalCosts, $Equipment->UniqueID());
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Equipment = new CharacterCard($index, $this->controller);
    return $Equipment->Tapped();
  }

  function SpecialName() {
    return "Syncopated Heart Pump";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialClass() {
    return "MECHANOLOGIST";
  }

  function SpecialTalent() {
    return "CHAOS";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Chest";
  }

  function ArcaneBarrier($index) {
    return 1;
  }
}

class quickdigit_assemblers extends Card {
  // When this defends, you may put an item with cost 0 from your hand into the arena. If you do, this gets +1{d}.
  // Blade Break (When the combat chain closes, if this defended, destroy it.)
  function __construct($controller) {
    $this->cardID = "quickdigit_assemblers";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $LinkCard = new ChainCard($i);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $LinkCard->OriginUniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $search = "MYHAND:subtype=Item;maxCost=0";
    $context = "Put a 0 cost item from your hand into the arena";
    Await($this->controller, "MultiZoneIndices", search:$search, may:true, subsequent:0);
    Await($this->controller, "ChooseMultizone", context:$context);
    Await($this->controller, $this->cardID, uid:$target, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $ind = explode("-", $dqVars["MZIndex"] ?? "-")[1] ?? -1;
    if ($ind != -1) {
      $uid = $dqVars["uid"] ?? "-";
      $Hand = GetHand($this->controller);
      PutItemIntoPlayForPlayer($Hand[$ind], $this->controller);
      RemoveHand($this->controller, $ind);
      AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$uid);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    $uniqueID = IndexToBlockCardUID($index);
    $Effect = new CurrentEffect($effectInd);
    return $Effect->AppliestoUniqueID() == $uniqueID ? 1 : 0;
  }

  function SpecialName() {
    return "Quickdigit Assemblers";
  }

  function SpecialBlock() {
    return 1;
  }

  function SpecialClass() {
    return "MECHANOLOGIST";
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Arms";
  }
}
