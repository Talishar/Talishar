<?php

class stardust_spike_red extends Card {
  function __construct($controller) {
    $this->cardID = "stardust_spike_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		global $CurrentTurnEffects;
    GainResources($this->controller, 1);
		$Effect = $CurrentTurnEffects->FindEffect($this->cardID);
    if ($Effect->Index() == -1) AddCurrentTurnEffect($this->cardID, $this->controller);
		else $Effect->AddUses(1);
	}

	function ArcaneModifier(&$remove, $player, $index, $amount=false) {
    $Effect = new CurrentEffect($index);
		return Amp($Effect->NumUses(), $remove, $player, $this->controller, $amount);
	}
}

class blitz_kicks extends Card {
  function __construct($controller) {
    $this->cardID = "blitz_kicks";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("embodiment_of_lightning", $this->controller);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_NumInstantPlayed;
    return GetClassState($this->controller, $CS_NumInstantPlayed) == 0;
  }

  function DefaultActiveState() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }
}

class starfield_touch extends Card {
  function __construct($controller) {
    $this->cardID = "starfield_touch";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Character = new PlayerCharacter($this->controller);
    $CharacterCard = $Character->FindCardID("aphrodias");
    $CharacterCard->Tap(0);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function DefaultActiveState() {
    return 1;
  }

  function SpecialBlock() {
    return 1; //fabcube error
  }
}

class starfield_veil extends Card {
  function __construct($controller) {
    $this->cardID = "starfield_veil";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
  }

  function DefaultActiveState() {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_NumFragmented;
    return GetClassState($this->controller, $CS_NumFragmented) == 0;
  }

  function PlayCardEffectAbility($cardID, $from, &$remove, $index=-1) {
    global $Stack;
    $Effect = new CurrentEffect($index);
    if (SubtypeContains($cardID, "Aura") && $Effect->AppliestoUniqueID() == -1) {
      $uid = $Stack->TopLayer($cardID)->LayerUniqueID();
      $Effect->ApplyToUniqueID($uid);
    }
  }

  function SpecialBlock() {
    return 2; //fabcube error
  }
}

class starfield_carapace extends Card {
  function __construct($controller) {
    $this->cardID = "starfield_carapace";
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

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    return $cardID == "aphrodias" ? -1 : 0;
  }

  function SpecificLogic() {
    global $dqVars;
    $arcaneDealt = $dqVars["ARCANEDEALT"] ?? 0;
    if ($arcaneDealt > 0)
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("lightning_flow", $this->controller, effectSource:"aphrodias");
  }

  function DefaultActiveState() {
    return 0;
  }

  function SpecialBlock() {
    return 1; //fabcube mistake
  }
}

class shattering_stardust_red extends Card {
  function __construct($controller) {
    $this->cardID = "shattering_stardust_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function FragmentTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function ArcaneModifier(&$remove, $player, $index, $amount = false) {
    return Amp(1, $remove, $player, $this->controller, $amount);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $context = "Choose a {{element|Lightning|" . GetElementColorCode("LIGHTNING") . "}} aura permanent to flicker (or pass)";
    $indices = FindHoloAuras($this->controller);
    Await($this->controller, "ChooseMultizone", returnName:"MZIndex", subsequent:0, indices:$indices, context:$context, may:true);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $MZIndex = $dqVars["MZIndex"];
    HoloFlicker($this->controller, $MZIndex);
  }
}

class blur_reality_blue extends Card {
  function __construct($controller) {
    $this->cardID = "blur_reality_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $context = "Choose a {{element|Lightning|" . GetElementColorCode("LIGHTNING") . "}} aura permanent to flicker";
    $indices = FindHoloAuras($this->controller);
    Await($this->controller, "ChooseMultizone", returnName:"MZIndex", subsequent:0, indices:$indices, context:$context);
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $MZIndex = $dqVars["MZIndex"];
    HoloFlicker($this->controller, $MZIndex);
  }
}