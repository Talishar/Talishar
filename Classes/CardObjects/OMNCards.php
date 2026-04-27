<?php
include_once  __DIR__ . "/HVYCards.php";
include_once  __DIR__ . "/SUPCards.php";
class twoscilio extends BaseCard {
  function PlayAbility() {
    PummelHit($this->controller, track:true);
    Await($this->controller, $this->cardID, lastResultName:"discarded", final:true);
    PlayAura("ponder", $this->controller);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $discardedUID = $dqVars["discarded"];
    $Discard = new Discard($this->controller);
    $Card = $Discard->FindCardUID($discardedUID);
    if (TypeContains($Card->ID(), "I", $this->controller)) AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$discardedUID);
  }

  function IsPlayRestricted($index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    $Auras = new Auras($this->controller);
    if ($Auras->FindCardID("lightning_flow")->Index() == -1) return true;
    return false;
  }

  function PayAdditionalCosts($index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Tap();
    $CharacterCard->AddUse(1); //unlimited uses
    $CharacterCard->SetUsed(2);
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    $Flow->Destroy();
  }
}

class bauroralegacy extends BaseCard {
  function PlayAbility() {
    PlayAura("embodiment_of_lightning", $this->controller);
    return "";
  }

  function IsPlayRestricted($index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    $Auras = new Auras($this->controller);
    if ($Auras->FindCardID("lightning_flow")->Index() == -1) return true;
    return false;
  }

  function PayAdditionalCosts($index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Tap();
    $CharacterCard->AddUse(1); //unlimited uses
    $CharacterCard->SetUsed(2);
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    $Flow->Destroy();
  }
}

class aurora_legacy_of_tempest extends Card {
  function __construct($controller) {
    $this->cardID = "aurora_legacy_of_tempest";
    $this->controller = $controller;
    $this->baseCard = new bauroralegacy($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 2;
  }
}

class aurora_emissary_of_lightning extends Card {
  function __construct($controller) {
    $this->cardID = "aurora_emissary_of_lightning";
    $this->controller = $controller;
    $this->baseCard = new bauroralegacy($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 2;
  }
}

class oscilio_forked_continuum extends Card {
  function __construct($controller) {
    $this->cardID = "oscilio_forked_continuum";
    $this->controller = $controller;
    $this->baseCard = new twoscilio($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }
}

class oscilio_scion_of_the_third_age extends Card {
  function __construct($controller) {
    $this->cardID = "oscilio_scion_of_the_third_age";
    $this->controller = $controller;
    $this->baseCard = new twoscilio($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function SpecialType() {
    return "C";
  }
}

class lightning_flow extends Card {
  function __construct($controller) {
    $this->cardID = "lightning_flow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpellVoidAmount($index=-1) {
    global $Landmarks;
    if ($Landmarks->NumLandmarks() == 0) return 0;
    return $Landmarks->Card(0)->CardID() == "omens_of_arcana" ? 1 : 0;
  }
}

class nebula_duality extends BaseCard {
  public $archetype;
  function __construct($cardID, $controller = '-') {
    $this->cardID = $cardID;
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($damage, $target) {
    DealArcane($damage, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    return "";
  }

  function ProcessAbility($target) {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,A";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1) {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

  function CardCost() {
    global $Stack;
    // this works
    return $Stack->TopLayer()->ID() == "ABILITY" ? 1 : 0;
  }
}

class nebula_duality_red extends Card {
  function __construct($controller) {
    $this->cardID = "nebula_duality_red";
    $this->controller = $controller;
    $this->baseCard = new nebula_duality($this->cardID, $this->controller);
  }
  
  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessAbility($target);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility(3, $target);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->baseCard->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-') {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount, $facing);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }

  function ArcaneDamage() {
    return 3;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function SpecialType() {
    return "A"; //just here for testing
  }

  function CardCost($from = '-') {
    return $this->baseCard->CardCost();
  }
}

class nebula_duality_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "nebula_duality_yellow";
    $this->controller = $controller;
    $this->baseCard = new nebula_duality($this->cardID, $this->controller);
  }
  
  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessAbility($target);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility(2, $target);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->baseCard->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-') {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount, $facing);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }

  function ArcaneDamage() {
    return 2;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function SpecialType() {
    return "A"; //just here for testing
  }

  function CardCost($from = '-') {
    return $this->baseCard->CardCost();
  }
}

class nebula_duality_blue extends Card {
  function __construct($controller) {
    $this->cardID = "nebula_duality_blue";
    $this->controller = $controller;
    $this->baseCard = new nebula_duality($this->cardID, $this->controller);
  }
  
  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessAbility($target);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility(1, $target);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->baseCard->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = '-') {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount, $facing);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }

  function ArcaneDamage() {
    return 1;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function SpecialType() {
    return "A"; //just here for testing
  }

  function CardCost($from = '-') {
    return $this->baseCard->CardCost();
  }
}

class zyggy_base extends BaseCard {
  function IsPlayRestricted($index) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    $Auras = new Auras($this->controller);
    if ($Auras->FindCardID("lightning_flow")->Index() == -1) return true;
    if (FindHoloAuras($this->controller) == "") return true;
    return false;
  }

  function PayAdditionalCosts($index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Tap();
    $CharacterCard->AddUse(1); //unlimited uses
    $CharacterCard->SetUsed(2);
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    $Flow->Destroy();
    $context = "Choose a {{element|Lightning|" . GetElementColorCode("LIGHTNING") . "}} aura permanent to banish";
    $indices = FindHoloAuras($this->controller, excludeFirstFlow:false);
    Await($this->controller, "ChooseMultizone", returnName:"MZIndex", subsequent:0, indices:$indices, context:$context);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars, $Stack;
    $AuraCard = MZIndexToObject($this->controller, $dqVars["MZIndex"]);
    $banishCount = $AuraCard->Banish();
    $BanishedCard = new BanishCard($this->controller, $banishCount);
    $Layer = $Stack->TopLayer($this->cardID);
    $Layer->AddTarget($BanishedCard->UniqueID());
  }

  function PlayAbility($target) {
    $Banish = new Banish($this->controller);
    $BanishCard = $Banish->FindCardUID($target);
    if ($BanishCard != "" && $BanishCard->Index() != -1) {
      $cardID = $BanishCard->ID();
      $BanishCard->Remove();
      PlayAura($cardID, $this->controller, holoCounters:1);
    }
  }
}

class zyggy_starlight extends Card {
  function __construct($controller) {
    $this->cardID = "zyggy_starlight";
    $this->controller = $controller;
    $this->baseCard = new zyggy_base($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }
}

class zyggy extends Card {
  function __construct($controller) {
    $this->cardID = "zyggy";
    $this->controller = $controller;
    $this->baseCard = new zyggy_base($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted($index);
  }

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    return $this->baseCard->PayAdditionalCosts($index);
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }
}

class voltbound_duality_red extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "voltbound_duality_red";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

}

class voltbound_duality_yellow extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "voltbound_duality_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

}

class voltbound_duality_blue extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "voltbound_duality_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

}

class cosmic_duality_red extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "cosmic_duality_red";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

  function HasFragment() {
    return true;
  }
}

class cosmic_duality_yellow extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "cosmic_duality_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

  function HasFragment() {
    return true;
  }
}

class cosmic_duality_blue extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "cosmic_duality_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    DealArcane(1, source:$this->cardID, player:$this->controller, resolvedTarget:$target);
    PlayAura("lightning_flow", $this->controller);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 1;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = "-") {
    return DualityPrePitch($this->cardID, $index, $from, $this->controller);
  }

  function HasFragment() {
    return true;
  }
}

class astral_strike_red extends Card {
  function __construct($controller) {
    $this->cardID = "astral_strike_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_NumLightningFlowDestroyed;
    if (GetClassState($this->controller, $CS_NumLightningFlowDestroyed) > 0) {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a mode for " . CardLink($this->cardID));
      AddDecisionQueue("BUTTONINPUT", $this->controller, "Draw_a_Card,Buff_Power,Go_Again");
      AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
      Await($this->controller, $this->cardID, final:true);
    }
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    WriteLog(CardLink($this->cardID) . " mode: " . GamestateUnsanitize($dqVars["LASTRESULT"]));
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:$dqVars["LASTRESULT"]);
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    switch ($additionalCosts) {
      case "Draw_a_Card": Draw($this->controller); break;
      case "Buff_Power": AddCurrentTurnEffect($this->cardID . "-BUFF", $this->controller); break;
      case "Go_Again": AddCurrentTurnEffect($this->cardID . "-GOAGAIN", $this->controller); break;
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $parameter == "BUFF" || $parameter == "GOAGAIN";
  }

  function EffectPowerModifier($param, $attached = false) {
    if ($param == "BUFF") return 2;
    return 0;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return $param == "GOAGAIN";
  }
}

class FRAGMENT extends Card {
  function __construct($controller) {
    $this->cardID = "FRAGMENT";
    $this->controller = $controller;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return -2;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    FragmentLayer($target);
  }
}

class lightning_jab extends Card {
  //base card for flowing stormstrike, meteoric rise, and voltic impact
  function __construct($controller) {
    $this->cardID = "lightning_jab";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY") {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
    return "";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    global $mainPlayer, $CombatChain, $ChainLinks;
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
    if ($from == "PLAY" && $CombatChain->AttackCard()->NumTimesUsed() >= 2) return true;
    if ($from == "COMBATCHAINATTACKS") {
      $Link = $ChainLinks->GetLink($index);
      return $Link->AttackCard()->NumTimesUsed() >= 2;
    }
    return false;
  }

  function AbilityPlayableFromCombatChain($index="-") {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "I": "AA";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $CombatChain, $ChainLinks;
    if (is_numeric($index)) {
      if ($from == "CC") {
        $i = 0;
      }
      else {
        $i = intdiv($index, ChainLinksPieces());
      }
      if ($from == "CC" || $from == "COMBATCHAINATTACKS") {
        if ($from == "COMBATCHAINATTACKS") $ChainLinks->GetLink($i)->AttackCard()->AddUse(1);
        else $CombatChain->AttackCard()->AddUse(1);
      }
    }
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
    return true;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    PlayAura("lightning_flow", $this->controller);
  }
}

class flowing_stormstrike_red extends lightning_jab {
  function __construct($controller) {
    $this->cardID = "flowing_stormstrike_red";
    $this->controller = $controller;
  }
}

class meteoric_rise_red extends lightning_jab {
  function __construct($controller) {
    $this->cardID = "meteoric_rise_red";
    $this->controller = $controller;
  }
}

class voltic_impact_red extends lightning_jab {
  function __construct($controller) {
    $this->cardID = "voltic_impact_red";
    $this->controller = $controller;
  }
}

class aphrodias extends Card {
  function __construct($controller) {
    $this->cardID = "aphrodias";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(2, resolvedTarget:$target);
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_HoloAurasEntered;
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped()) return true;
    if (GetClassState($this->controller, $CS_HoloAurasEntered) == 0) return true;
    return false;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Tap();
    $CharacterCard->AddUse(1); //unlimited uses
    $CharacterCard->SetUsed(2);
    SetArcaneTarget($this->controller, $this->cardID);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }
}

class omens_of_arcana extends Card {
  function __construct($controller) {
    $this->cardID = "omens_of_arcana";
    $this->controller = $controller;
  }
}

class flowstate_embodiment_red extends Card {
  function __construct($controller) {
    $this->cardID = "flowstate_embodiment_red";
    $this->controller = $controller;
  }
  
  function ActiveLinkPlayTrigger($cardID, $player, $from) {
    if (TypeContains($cardID, "I", $player, from:$from) && !IsActivated($cardID, $from) && $player == $this->controller)
      AddLayer("TRIGGER", $this->controller, $this->cardID); 
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Await($this->controller, "CardChoices", choices:"embodiment_of_lightning,lightning_flow", returnName:"cardID", subsequent:false);
    Await($this->controller, "PlayAura", final:true);
  }
}

class volzar_meteor_storm extends Card {
  function __construct($controller) {
    $this->cardID = "volzar_meteor_storm";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_NumInstantsPutInGrave;
    $Weapon = new CharacterCard($index, $this->controller);
    if ($Weapon->Tapped()) return true;
    if (GetClassState($this->controller, $CS_NumInstantsPutInGrave) == 0) return true;
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $Weapon = new CharacterCard($index, $this->controller);
    $Weapon->Tap();
    $Weapon->SetUsed(2); //unlimited uses
    $Weapon->AddUse();
  }

  function ArcaneModifier(&$remove, $player, $index, $amount = false) {
    return Amp(1, $remove, $player, $this->controller, $amount);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function SpecialType() {
    return "W";
  }
}

class cosmic_suture extends BaseCard {
  function PlayAbility($target) {
    global $CS_NumInstantsPutInGrave;
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (GetClassState($this->controller, $CS_NumInstantsPutInGrave))
      DealArcane(1, resolvedTarget:$target);
  }

  function CurrentEffectDamagePrevention($index, $damage, $amount, &$remove) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function PayAdditionalCosts() {
    SetTargetsArcane($this->controller, $this->cardID);
  }
}

class cosmic_suture_red extends Card {
  function __construct($controller) {
    $this->cardID = "cosmic_suture_red";
    $this->controller = $controller;
    $this->baseCard = new cosmic_suture($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 4;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function SpecialType() {
    return "I";
  }
}

class cosmic_suture_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "cosmic_suture_yellow";
    $this->controller = $controller;
    $this->baseCard = new cosmic_suture($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 3;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function SpecialType() {
    return "I";
  }
}

class cosmic_suture_blue extends Card {
  function __construct($controller) {
    $this->cardID = "cosmic_suture_blue";
    $this->controller = $controller;
    $this->baseCard = new cosmic_suture($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 2;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function SpecialType() {
    return "I";
  }
}

class scorpio_comet_tail extends Card {
  function __construct($controller) {
    $this->cardID = "scorpio_comet_tail";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $ChainLinks;
    $Weapon = new CharacterCard($index, $this->controller);
    if ($Weapon->Tapped()) return true;
    for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
      $AttackCard = $ChainLinks->GetLink($i)->AttackCard();
      if ($AttackCard->StillOnChain() && TalentContains($AttackCard->ID(), "LIGHTNING", $this->controller))
        return false;
    }
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $Weapon = new CharacterCard($index, $this->controller);
    $Weapon->Tap();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    DealArcane(1, 1);
  }
}

class pulsing_cardia extends BaseCard {
  function FragmentTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    GainResources($this->controller, 1);
  }
}

class pulsing_cardia_red extends Card {
  function __construct($controller) {
    $this->cardID = "pulsing_cardia_red";
    $this->controller = $controller;
    $this->baseCard = new pulsing_cardia($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function FragmentTrigger() {
    $this->baseCard->FragmentTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }

  function HasFragment() {
    return true;
  }
}

class pulsing_cardia_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "pulsing_cardia_yellow";
    $this->controller = $controller;
    $this->baseCard = new pulsing_cardia($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function FragmentTrigger() {
    $this->baseCard->FragmentTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }

  function HasFragment() {
    return true;
  }

  function SpecialPower() {
    return 4;
  }
}

class pulsing_cardia_blue extends Card {
  function __construct($controller) {
    $this->cardID = "pulsing_cardia_blue";
    $this->controller = $controller;
    $this->baseCard = new pulsing_cardia($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function FragmentTrigger() {
    $this->baseCard->FragmentTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }

  function HasFragment() {
    return true;
  }

  function SpecialPower() {
    return 3;
  }
}

class auric_shards extends BaseCard {
  function PlayAbility() {
    global $CombatChain;
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->Card($Auras->NumAuras() - 1, true); //it should always be the most recent aura
    // for now assume it's targeting the current chain link
    if (HasFragment($CombatChain->AttackCard()->ID()))
      AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$AuraCard->UniqueID());
  }

  function ProcessTrigger($val, $uniqueID) {
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->FindCardUID($uniqueID);
    $pow = $AuraCard->HoloCounters() > 0 ? $val : 1;
    AddCurrentTurnEffect("$this->cardID-$pow", $this->controller);
  }

  function CombatEffectActive() {
    global $CombatChain;
    return HasFragment($CombatChain->AttackCard()->ID());
  }
}

class auric_shards_red extends Card {
  function __construct($controller) {
    $this->cardID = "auric_shards_red";
    $this->controller = $controller;
    $this->baseCard = new auric_shards($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function EntersArenaAbility() {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger(4, $uniqueID);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }
}

class auric_shards_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "auric_shards_yellow";
    $this->controller = $controller;
    $this->baseCard = new auric_shards($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function EntersArenaAbility() {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger(3, $uniqueID);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }
}

class auric_shards_blue extends Card {
  function __construct($controller) {
    $this->cardID = "auric_shards_blue";
    $this->controller = $controller;
    $this->baseCard = new auric_shards($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function EntersArenaAbility() {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger(2, $uniqueID);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }
}

class dashing_flashfoot_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "dashing_flashfoot_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (DoesAttackHaveGoAgain() && IsHeroAttackTarget())
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    DealArcane(1, 1, source:$this->cardID);
  }

  function DamageDealtAbilities($target, $damage, $type) {
    global $CombatChain;
    if ($CombatChain->AttackCard()->ID() != $this->cardID) return; // for now only make this work when it's the active link
    if (is_numeric($target) && $CombatChain->AttackCard()->NumTimesUsed() == 0) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
    $CombatChain->AttackCard()->AddUse();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("embodiment_of_lightning", $this->controller, effectSource:$this->cardID);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return DoesAttackHaveGoAgain() ? 1 : 0;
  }
}

class rift_breaker extends BaseCard {
  function AddOnHitTrigger($check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect() {
    global $defPlayer;
    $Auras = new Auras($defPlayer);
    $AuraCard = $Auras->FindCardID("lightning_flow");
    $AuraCard->Destroy();
  }
}

class rift_breaker_red extends Card {
  function __construct($controller) {
    $this->cardID = "rift_breaker_red";
    $this->controller = $controller;
    $this->baseCard = new rift_breaker($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class rift_breaker_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rift_breaker_yellow";
    $this->controller = $controller;
    $this->baseCard = new rift_breaker($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class rift_breaker_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rift_breaker_blue";
    $this->controller = $controller;
    $this->baseCard = new rift_breaker($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class arc_ramp extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (SearchAuras("lightning_flow", $this->controller)) {
      Await($this->controller, "YesNo", "choice", subsequent:0, context:"Destroy a " . CardLink("lightning_flow") . " to get go again?");
      Await($this->controller, $this->cardID, final:true);
    }
  }

  function SpecificLogic() {
    global $dqVars;
    if ($dqVars["choice"] ?? "NO" == "YES") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardID("lightning_flow");
      $AuraCard->Destroy();
      AddCurrentTurnEffect("$this->cardID-GOAGAIN", $this->controller);
    }
  }

  function ArcaneModifier($val, &$remove, $player, $index, $amount) {
    $Effect = new CurrentEffect($index);
    if (str_contains($Effect->EffectID(), "GOAGAIN")) return;
    return Amp($val, $remove, $player, $this->controller, $amount);
  }

  function CurrentEffectGrantsNAAGoAgain(&$remove, $parameter) {
    if ($parameter == "GOAGAIN") {
      $remove = true;
      return true;
    }
    return false;
  }
}

class arc_ramp_red extends Card {
  function __construct($controller) {
    $this->cardID = "arc_ramp_red";
    $this->controller = $controller;
    $this->baseCard = new arc_ramp($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }

  function ArcaneModifier(&$remove, $player, $index, $amount = false) {
    return $this->baseCard->ArcaneModifier(3, $remove, $player, $index, $amount);
  }

  function CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, &$remove) {
    return $this->baseCard->CurrentEffectGrantsNAAGoAgain($remove, $parameter);
  }

  function SpecialType() { //not in the database yet
    return "A";
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "WIZARD";
  }

  function SpecialTalent() {
    return "LIGHTNING";
  }

  function SpecialName() {
    return "Arc Ramp";
  }
}

class arc_ramp_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "arc_ramp_yellow";
    $this->controller = $controller;
    $this->baseCard = new arc_ramp($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }

  function ArcaneModifier(&$remove, $player, $index, $amount = false) {
    return $this->baseCard->ArcaneModifier(2, $remove, $player, $index, $amount);
  }

  function CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, &$remove) {
    return $this->baseCard->CurrentEffectGrantsNAAGoAgain($remove, $parameter);
  }

  function SpecialType() { //not in the database yet
    return "A";
  }

  function SpecialBlock() {
    return 2;
  }

  function SpecialClass() {
    return "WIZARD";
  }

  function SpecialTalent() {
    return "LIGHTNING";
  }

  function SpecialName() {
    return "Arc Ramp";
  }
}

class arc_ramp_blue extends Card {
  function __construct($controller) {
    $this->cardID = "arc_ramp_blue";
    $this->controller = $controller;
    $this->baseCard = new arc_ramp($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }

  function ArcaneModifier(&$remove, $player, $index, $amount = false) {
    return $this->baseCard->ArcaneModifier(1, $remove, $player, $index, $amount);
  }

  function CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, &$remove) {
    return $this->baseCard->CurrentEffectGrantsNAAGoAgain($remove, $parameter);
  }
}

class circular_flowtide_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "circular_flowtide_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("lightning_flow", $this->controller, effectSource:$this->cardID);
  }
}

class stormshard extends BaseCard {
  function PlayAbility($target, $amount) {
    global $CombatChain, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    $index = explode("-", $target)[1];
    if (explode("-", $target)[0] == "COMBATCHAINLINK" && $CombatChain->HasCurrentLink() && $index != -1) {
      if ($index == 0 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] == "-") return "FAILED";
      CombatChainPowerModifier($index, $amount);
      AddCurrentTurnEffect($this->cardID."-VISUAL", $this->controller);//For Visual Effect only
    }
    elseif (explode("-", $target)[0] == "COMBATCHAINATTACKS") {
      // targeting a past chain link, do nothing for now
    }
    //only add current turn effect if there's no target (ie. played in layer step)
    elseif (IsLayerStep()) AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function IsPlayRestricted() {
    global $CombatChain, $Stack, $ChainLinks;
    if ($Stack->NumLayers() == 0 && !$CombatChain->HasCurrentLink() && !IsResolutionStep()) return true;
    if (SearchCount(SearchCombatChainLink($this->controller, type: "AA", talent: "LIGHTNING")) > 0) return false;
    if (SearchCount(SearchCombatChainAttacks($this->controller, type: "AA", talent: "LIGHTNING")) > 0) return false;
    if ($ChainLinks->SearchChainLinks(type:"AA", talent:"LIGHTNING") != "") return false;
    $countLayers = $Stack->NumLayers();
    for ($i = 0; $i < $countLayers; ++$i) {
      $layer = $Stack->Card($i, true);
      if (TypeContains($layer->ID(), "AA", $layer->PlayerID(), from:"LAYERS", index:$i) && TalentContains($layer->ID(), "LIGHTNING", $layer->PlayerID()) <= 1) return false;
    }
    return true;
  }

  function PayAdditionalCosts() {
    if (IsLayerStep()) {
      // targetting attack layer
      AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
    }
    elseif (!ShouldHoldPriority($this->controller) && ShouldAutotargetOpponent($this->controller)) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "COMBATCHAINLINK:talent=LIGHTNING;type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
    }
    else {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "COMBATCHAINATTACKS:talent=LIGHNTING;type=AA&COMBATCHAINLINK:talent=LIGHTNING;type=AA");
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
    }
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function PrePitchDecsions() {
    PayLightningFlowInstead($this->controller, $this->cardID);
  }

  function CurrentTurnEffectPaid(&$remove, $index) {
    $Effect = new CurrentEffect($index);
    $param = explode("-", $Effect->EffectID())[1] ?? "-";
    if ($param == "PAID") {
      $remove = true;
      return true;
    }
    return false;
  }
}

class stormshard_red extends Card {
  function __construct($controller) {
    $this->cardID = "stormshard_red";
    $this->controller = $controller;
    $this->baseCard = new stormshard($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target, 3);
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing = '-') {
    $this->baseCard->PrePitchDecsions();
  }

  function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
    return $this->baseCard->CurrentTurnEffectPaid($remove, $index);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "VISUAL" ? 0 : 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class boots_of_omnis_ward extends Card {
  function __construct($controller) {
    $this->cardID = "boots_of_omnis_ward";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_ArcaneDamageTaken;
    return GetClassState($this->controller, $CS_ArcaneDamageTaken) > 0 ? 1 : 0;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Hero = new CharacterCard(0, $this->controller);
    return $Hero->Tapped();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $Hero = new CharacterCard(0, $this->controller);
    $Equip = new CharacterCard($index, $this->controller);
    $Hero->Tap();
    $Equip->Destroy();
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 1;
  }
}

class static_shelter_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "static_shelter_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $message = "if_you_want_to_make_a_lightning_flow";
    $context = "Choose if you want to pay a resource and create a " . CardLink("lightning_flow");
    Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
    Await($this->controller, "PayResourcesEffect", amount:1);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    PlayAura("lightning_flow", $this->controller);
  }
}

class beckoning_brilliance extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $playIndex) {
    if (TypeContains($cardID, "I", $this->controller, from:$from, index:$playIndex)) {
      $remove = true;
      return -1;
    }
    return 0;
  }
}

class beckoning_brilliance_red extends Card {
  function __construct($controller) {
    $this->cardID = "beckoning_brilliance_red";
    $this->controller = $controller;
    $this->baseCard = new beckoning_brilliance($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    return $this->baseCard->CurrentEffectCostModifier($cardID, $from, $remove, $playIndex);
  }
}