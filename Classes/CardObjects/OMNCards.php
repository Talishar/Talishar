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
    DealArcane(2, resolvedTarget:$target, source:$this->cardID);
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
    Await($this->controller, "CardChoices", choices:"Embodiment_of_Lightning,Lightning_Flow", returnName:"cardID", subsequent:false);
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
}

class cosmic_suture extends BaseCard {
  function PlayAbility($target) {
    global $CS_NumInstantsPutInGrave;
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (GetClassState($this->controller, $CS_NumInstantsPutInGrave))
      DealArcane(1, resolvedTarget:$target, source:$this->cardID);
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
}

class auric_shards extends BaseCard {
  function PlayAbility() {
    global $CombatChain, $Stack;
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->Card($Auras->NumAuras() - 1, true); //it should always be the most recent aura
    // for now assume it's targeting the current chain link
    if (HasFragment($CombatChain->AttackCard()->ID()) || (IsLayerStep() && HasFragment($Stack->BottomLayer()->ID())))
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
    FirstDamageTrigger($target, $this->cardID, $this->controller);
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

class blink_of_an_eye extends BaseCard {
  function FragmentTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    $context = "Choose a {{element|Lightning|" . GetElementColorCode("LIGHTNING") . "}} aura permanent to flicker";
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

class blink_of_an_eye_red extends Card {
  function __construct($controller) {
    $this->cardID = "blink_of_an_eye_red";
    $this->controller = $controller;
    $this->baseCard = new blink_of_an_eye($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function FragmentTrigger()  {
    $this->baseCard->FragmentTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic();
  }
}

class flicker_reality_blue extends Card {
  function __construct($controller) {
    $this->cardID = "flicker_reality_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
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

class core_reaction extends BaseCard {

  function BeginningActionPhaseAbility($index) {
    $Aura = new AuraCard($index, $this->controller);
    $uid = $Aura->UniqueID();
    SetArcaneTarget($this->controller, $this->cardID, 2);
    AddDecisionQueue("ADDTRIGGER", $this->controller, "$this->cardID|$uid", 1);
  }

  function ProcessTrigger($target, $additionalCosts, $damage) {
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->FindCardUID($additionalCosts);
    $AuraCard->Destroy();
    DealArcane($damage, source:$this->cardID, resolvedTarget:$target);
  }
}

class core_reaction_red extends Card {
  function __construct($controller) {
    $this->cardID = "core_reaction_red";
    $this->controller = $controller;
    $this->baseCard = new core_reaction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginningActionPhaseAbility($index) {
    $this->baseCard->BeginningActionPhaseAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, $additionalCosts, 4);
  }
}

class core_reaction_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "core_reaction_yellow";
    $this->controller = $controller;
    $this->baseCard = new core_reaction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginningActionPhaseAbility($index) {
    $this->baseCard->BeginningActionPhaseAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, $additionalCosts, 3);
  }
}

class core_reaction_blue extends Card {
  function __construct($controller) {
    $this->cardID = "core_reaction_blue";
    $this->controller = $controller;
    $this->baseCard = new core_reaction($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function BeginningActionPhaseAbility($index) {
    $this->baseCard->BeginningActionPhaseAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, $additionalCosts, 2);
  }
}

class echoflash_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "echoflash_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(1, source:$this->cardID, resolvedTarget:$target);
    return "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function AddGraveyardEffect($from, $effectController) {
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $EffectContext;
    $Hero = new CharacterCard(0, $this->controller);
    $EffectContext = $Hero->CardID();
    SetDamageSourceUID($Hero->UniqueID());
    DealArcane(1, source:$Hero->CardID(), resolvedTarget:$target);
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }
}

class tempestuous_kiss_red extends Card {
  function __construct($controller) {
    $this->cardID = "tempestuous_kiss_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    if (DoesAttackHaveGoAgain() && IsHeroAttackTarget()) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER", $CombatChain->AttackCard()->UniqueID());
    }
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return DoesAttackHaveGoAgain() ? 1 : 0;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    DealArcane(1, 1, source:$this->cardID);
  }

  function DamageDealtAbilities($target, $damage, $type) {
    FirstDamageTrigger($target, $this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    PummelHit($otherPlayer);
  }
}

class mercurial_skies extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CurrentEffectDamageEffect($target, $source) {
    FirstDamageTrigger($target, $source, $this->controller, $this->cardID);
  }

  function ProcessTrigger($target, $damage) {
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    if ($Flow->Index() != -1) {
      $message = "if_they_want_to_deal_arcane";
      $context = "Choose if you want to destroy " . CardLink("lightning_flow") . " to deal $damage damage";
      Await($this->controller, "YesNo", message: $message, context: $context, subsequent:0);
      Await($this->controller, $this->cardID, target:$target, final:true);
    }
  }

  function SpecificLogic($damage) {
    global $dqVars, $CombatChain;
    $target = $dqVars["target"];
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    $Flow->Destroy();
    SetDamageSourceUID($CombatChain->AttackCard()->UniqueID());
    DealArcane($damage, 0, source:$CombatChain->AttackCard()->ID());
  }

  function CombatEffectActive() {
    global $CombatChain;
    $AttackCard = $CombatChain->AttackCard()->ID();
    return ClassContains($AttackCard, "RUNEBLADE", $this->controller) || TalentContains($AttackCard, "LIGHTNING", $this->controller);
  }
}

class mercurial_skies_red extends Card {
  function __construct($controller) {
    $this->cardID = "mercurial_skies_red";
    $this->controller = $controller;
    $this->baseCard = new mercurial_skies($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, 3);
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic(3);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function CurrentEffectDamageEffect($target, $source, $type, $damage, &$remove) {
    $this->baseCard->CurrentEffectDamageEffect($target, $source);
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class mercurial_skies_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "mercurial_skies_yellow";
    $this->controller = $controller;
    $this->baseCard = new mercurial_skies($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, 2);
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic(2);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function CurrentEffectDamageEffect($target, $source, $type, $damage, &$remove) {
    $this->baseCard->CurrentEffectDamageEffect($target, $source);
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class mercurial_skies_blue extends Card {
  function __construct($controller) {
    $this->cardID = "mercurial_skies_blue";
    $this->controller = $controller;
    $this->baseCard = new mercurial_skies($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target, 1);
  }

  function SpecificLogic() {
    return $this->baseCard->SpecificLogic(1);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function CurrentEffectDamageEffect($target, $source, $type, $damage, &$remove) {
    $this->baseCard->CurrentEffectDamageEffect($target, $source);
  }

  function CurrentEffectGrantsGoAgain($param) {
    return true;
  }
}

class ominous_aggression_red extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_aggression_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_NumControlledAurasDestroyed, $CombatChain, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    $index = explode("-", $target)[1];
    $amount = GetClassState($this->controller, $CS_NumControlledAurasDestroyed) > 0 ? 4 : 2;
    if (explode("-", $target)[0] == "COMBATCHAINLINK" && $CombatChain->HasCurrentLink() && $index != -1) {
      if ($index == 0 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] == "-") return "FAILED";
      CombatChainPowerModifier($index, $amount);
      AddCurrentTurnEffect($this->cardID."-VISUAL", $this->controller);//For Visual Effect only
    }
    elseif (explode("-", $target)[0] == "PASTCHAINLINK") {
      // targeting a past chain link, do nothing for now
    }
    //only add current turn effect if there's no target (ie. played in layer step)
    elseif (IsLayerStep()) AddCurrentTurnEffect("$this->cardID-$amount", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $targets = TargetAttackActionCard();
    return count($targets) == 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $targets = TargetAttackActionCard();
    $targets = implode(",", $targets);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $targets);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a target for $this->cardID");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, $this->cardID, 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }
}

class step_between_red extends Card {
  function __construct($controller) {
    $this->cardID = "step_between_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY")
      AddCurrentTurnEffect($this->cardID, $this->controller);
    if ($from == "PLAY" || $from == "COMBATCHAINATTACKS")
      AddCurrentTurnEffect("$this->cardID-PREVENT", $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return $mode == "PREVENT";
  }

  function RemoveEffectFromCombatChain() {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param != "PREVENT" ? 1 : 0;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    $Hero = new CharacterCard(0, $this->controller);
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY" && $from != "COMBATCHAINATTACKS") return false;
    if ($Hero->Tapped() == 1) return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY" || $from == "COMBATCHAINATTACKS") {
      $Hero = new CharacterCard(0, $this->controller);
      $Hero->Tap();
      Await($this->controller, "PayResources", amount:1, final:true);
    }
  }

  function AbilityPlayableFromCombatChain($index="-") {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "PLAY" || $from == "COMBATCHAINATTACKS") ? "I": "AA";
  }
}

class third_eye_of_the_sphinx extends Card {
  function __construct($controller) {
    $this->cardID = "third_eye_of_the_sphinx";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Draw($this->controller, effectSource:$this->cardID);
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
    $CharacterCard->Tap();
    $CharacterCard->AddUse(); // not once per turn
    $Auras = new Auras($this->controller);
    $Ponder = $Auras->FindCardID("ponder");
    $Ponder->Destroy();
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    if ($CharacterCard->Tapped() == 1) return true;
    $Auras = new Auras($this->controller);
    $Ponder = $Auras->FindCardID("ponder");
    if ($Ponder->Index() == -1) return true;
    return false;
  }

  function DefaultActiveState() {
    return 0;
  }
}

class lionclaw_maul extends SUPDwarfCard {
  function __construct($controller) {
    $this->cardID = "lionclaw_maul";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    BOO($this->controller);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AbilityCost() {
    return 2;
  }
}

class feral_instinct_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "feral_instinct_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SelfCostModifier($from) {
    global $CS_HaveIntimidated;
    return GetClassState($this->controller, $CS_HaveIntimidated) ? -3 : 0;
  }
}

class unmake_the_underlings_blue extends Card {
  function __construct($controller) {
    $this->cardID = "unmake_the_underlings_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget())
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRDISCARD:subtype=Ally", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Turn an ally into gold?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZOP", $this->controller, "FLIP", 1);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (!IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $CCS_AttackTargetUID, $combatChainState, $defPlayer;
    $uidTarget = $combatChainState[$CCS_AttackTargetUID];
    $Allies = new Allies($defPlayer);
    $AllyCard = $Allies->FindCardUID($uidTarget);
    $AllyCard->Destroy();
  }
}

class turn_to_mindfire extends BaseCard {
  function PlayAbility($target, $damage) {
    DealArcane($damage, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }

  function ArcaneHitEffect() {
    $Hero = new CharacterCard(0, $this->controller);
    if ($Hero->Tapped() == 0) {
      $message = "if_you_want_to_tap_to_ponder";
      $context = "Choose if you want to tap your hero to make a  " . CardLink("ponder");
      Await($this->controller, "YesNo", "choice", message:$message, context:$context, subsequent:0);
      Await($this->controller, $this->cardID, final:true);
    }
  }

  function SpecificLogic() {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Tap();
    PlayAura("ponder", $this->controller, effectSource:$this->cardID);
  }
}

class turn_to_mindfire_red extends Card {
  function __construct($controller) {
    $this->cardID = "turn_to_mindfire_red";
    $this->controller = $controller;
    $this->baseCard = new turn_to_mindfire($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target, $this->ArcaneDamage());
    return "";
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function ArcaneDamage() {
    return 5;
  }

  function ArcaneHitEffect($source, $target, $damage) {
    $this->baseCard->ArcaneHitEffect();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }
}

class tome_of_quandaries_blue extends Card {
  function __construct($controller) {
    $this->cardID = "tome_of_quandaries_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("ponder", $this->controller, 2);
    return "";
  }
}

class unwinding_finality_red extends Card {
  function __construct($controller) {
    $this->cardID = "unwinding_finality_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    Draw($this->controller, effectSource:$this->cardID);
  }

  function FragmentTrigger() {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYDISCARD:talent=LIGHTNING;type=I", subsequent:0);
		Await($this->controller, "ChooseMultiZone", "MZIndex", context:"Choose a card to put on top", may:true);
		Await($this->controller, "MZRemove", "cardID");
    Await($this->controller, "ShowCard");
		Await($this->controller, "AddTopDeck", from:"DECK", final:true);
  }
}

class corrosive_space_dust extends BaseCard {
  function WardAmount($index, $amount) {
    $AuraCard = new AuraCard($index, $this->controller);
    return ($AuraCard->HoloCounters() > 0) ? $amount : 1;
  }

  function LeavesPlayAbility() {
    SetArcaneTarget($this->controller, $this->cardID, "any_hero");
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function ProcessTrigger($target) {
    DealArcane(1, 0, source:$this->cardID, resolvedTarget:$target);
  }
}

class corrosive_space_dust_red extends Card {
  function __construct($controller) {
    $this->cardID = "corrosive_space_dust_red";
    $this->controller = $controller;
    $this->baseCard = new corrosive_space_dust($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function HasWard() {
    return true;
  }

  function WardAmount($index) {
    return $this->baseCard->WardAmount($index, 4);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    return $this->baseCard->LeavesPlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($target);
  }
}

class corrosive_space_dust_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "corrosive_space_dust_yellow";
    $this->controller = $controller;
    $this->baseCard = new corrosive_space_dust($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function HasWard() {
    return true;
  }

  function WardAmount($index) {
    return $this->baseCard->WardAmount($index, 3);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    return $this->baseCard->LeavesPlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($target);
  }
}

class corrosive_space_dust_blue extends Card {
  function __construct($controller) {
    $this->cardID = "corrosive_space_dust_blue";
    $this->controller = $controller;
    $this->baseCard = new corrosive_space_dust($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function HasWard() {
    return true;
  }

  function WardAmount($index) {
    return $this->baseCard->WardAmount($index, 2);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    return $this->baseCard->LeavesPlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($target);
  }
}

class prophetic_quickstep_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "prophetic_quickstep_yellow";
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
    FirstDamageTrigger($target, $this->cardID, $this->controller);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("ponder", $this->controller, effectSource:$this->cardID);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return DoesAttackHaveGoAgain() ? 1 : 0;
  }
}

class arcanic_reproach_blue extends Card {
  function __construct($controller) {
    $this->cardID = "arcanic_reproach_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PermanentDamageTakenAbility($player, $damage, $source, $playerSource) {
    global $CS_DamageDealtToOpponent;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $selfInflicted = $source == "bloodrot_pox" || $player == $playerSource;
    if(GetClassState($otherPlayer, $CS_DamageDealtToOpponent) == 0 && $damage > 0)
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-");
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "START") {
      $context = "Destroy an aura you control";
      Await($this->controller, "MultiZoneIndices", "indices", search:"MYAURAS", subsequent:0);
      Await($this->controller, "ChooseMultiZone", "MZInd", context:$context);
      Await($this->controller, "MZDestroy", final:true);
    }
    else {
			$context = "Reveal a {{element|Lightning|" . GetElementColorCode("LIGHTNING") . "}} card and deal 1 arcane (or pass)";
      Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND:talent=LIGHTNING", subsequent:0);
      Await($this->controller, "ChooseMultiZone", "choice", may:true, context:$context);
			Await($this->controller, $this->cardID, index: $target, final:true);
    }
  }

  function SpecificLogic() {
    global $dqVars;
    $choice = $dqVars["choice"];
    RevealCards($choice);
    DealArcane(1, 1, source:$this->cardID);
  }

  function BeginningActionPhaseAbility($index) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "START");
  }
}

class pile_driver extends Card {
  function __construct($controller) {
    $this->cardID = "pile_driver";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    Await($this->controller, "YesNo", "choice", subsequent:0, context:"Wager a " . CardLink("gold") . " with the opponent?");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    AddOnWagerEffects();
  }

  function WonWager($wonWager, $amount) {
    PutItemIntoPlayForPlayer("gold", $wonWager, number:$amount, effectController:$this->controller);
  }

  function IsWagerEffect($index) {
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AbilityCost() {
    return 4;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharCard = new CharacterCard($index, $this->controller);
    $CharCard->Tap();
    $CharCard->AddUse(); //not once per turn
    $CharCard->SetUsed(2);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharCard = new CharacterCard($index, $this->controller);
    return $CharCard->Tapped();
  }
}

class gear_turner_red extends Card {
  function __construct($controller) {
    $this->cardID = "gear_turner_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return AnyHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    MaySearchDeck($this->controller, "subtype=Cog", "MYITEMS", context:"Search your deck for a Cog?");
  }
}

class crash_site_salvage_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "crash_site_salvage_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $scrappedID = explode("-", $additionalCosts)[1] ?? "";
    AddLayer("TRIGGER", $this->controller, $this->cardID, $scrappedID, "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if ($target != "")
      GiveAttackGoAgain();
    if (SubtypeContains($target, "Cog"))
      PutItemIntoPlayForPlayer("gold", $this->controller);
  }
}

class astral_sanctuary {
  public $cardID;
  public $controller;

  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function IsPlayRestricted() {
    $Hero = new CharacterCard(0, $this->controller);
    return $Hero->Tapped();
  }

  function PayAdditionalCosts($index) {
    $Hero = new CharacterCard(0, $this->controller);
    $Hero->Tap();
    $CharCard = new CharacterCard($index, $this->controller);
    $CharCard->Destroy();
  }

  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }
}

class helm_of_astral_sanctuary extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "helm_of_astral_sanctuary";
    $this->controller = $controller;
    $this->archetype = new astral_sanctuary($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->archetype->PlayAbility();
    return "";
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class robe_of_astral_sanctuary extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "robe_of_astral_sanctuary";
    $this->controller = $controller;
    $this->archetype = new astral_sanctuary($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->archetype->PlayAbility();
    return "";
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class gloves_of_astral_sanctuary extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "gloves_of_astral_sanctuary";
    $this->controller = $controller;
    $this->archetype = new astral_sanctuary($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->archetype->PlayAbility();
    return "";
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class boots_of_astral_sanctuary extends Card {
  public $archetype;
  function __construct($controller) {
    $this->cardID = "boots_of_astral_sanctuary";
    $this->controller = $controller;
    $this->archetype = new astral_sanctuary($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->archetype->PlayAbility();
    return "";
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return FloatingPrevention($index, $damage, $amount, $remove);
  }

  function CurrentTurnEffectUses() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted();
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function DefaultActiveState() {
    return 1;
  }
}

class glide_through_starlight extends BaseCard {
  public $archetype;
  function __construct($cardID, $controller = '-') {
    $this->cardID = $cardID;
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function CurrentEffectDamagePrevention($amount, $preventable, &$remove) {
    $prevented = 1;
    if (!$amount) {
      if ($preventable) PlayAura("lightning_flow", $this->controller);
      $remove = true;
    }
    return $prevented;
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }
}

class glide_through_starlight_red extends Card {
  function __construct($controller) {
    $this->cardID = "glide_through_starlight_red";
    $this->controller = $controller;
    $this->baseCard = new glide_through_starlight($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardCost($from = '-') {
    return 1;
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return $this->baseCard->CurrentEffectDamagePrevention($amount, $preventable, $remove);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->baseCard->GoesOnCombatChain($phase, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }
}

class glide_through_starlight_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "glide_through_starlight_yellow";
    $this->controller = $controller;
    $this->baseCard = new glide_through_starlight($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardCost($from = '-') {
    return 1;
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return $this->baseCard->CurrentEffectDamagePrevention($amount, $preventable, $remove);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->baseCard->GoesOnCombatChain($phase, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }
}

class glide_through_starlight_blue extends Card {
  function __construct($controller) {
    $this->cardID = "glide_through_starlight_blue";
    $this->controller = $controller;
    $this->baseCard = new glide_through_starlight($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardCost($from = '-') {
    return 1;
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->baseCard->CanActivateAsInstant($index, $from);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount = false) {
    return $this->baseCard->CurrentEffectDamagePrevention($amount, $preventable, $remove);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->baseCard->GoesOnCombatChain($phase, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->baseCard->AddPrePitchDecisionQueue($from, $index);
  }
}

class red_lure_harpoon_blue extends Card {
  function __construct($controller) {
    $this->cardID = "red_lure_harpoon_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $CS_NumCannonsActivated;
    if (GetClassState($this->controller, $CS_NumCannonsActivated) > 0 && IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $context = "Banish a red action card from the opponent's graveyard";
    Await($this->controller, "MultiZoneIndices", "indices", search: "THEIRDISCARD:type=A;pitch=1&THEIRDISCARD:type=AA;pitch=1", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", may:true, context:$context);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $choice = $dqVars["choice"];
    $Card = MZIndexToObject($this->controller, $choice);
    $cardID = $Card->ID();
    $Card->Remove();
    BanishCardForPlayer($cardID, $otherPlayer, "DISCARD", "NTFromOtherPlayer", $this->cardID, $this->controller);
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 2;
  }

  function SpecialSubType() {
    return "Arrow";
  }

  function SpecialPower() {
    return 4;
  }

  function SpecialClass() {
    return "PIRATE,RANGER";
  }

  function SpecialName() {
    return "Red Lure Harpoon";
  }
}

class browbeat_blue extends Card {
  function __construct($controller) {
    $this->cardID = "browbeat_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    $Hand = new Hand($this->controller);
    return $Hand->NumCards();
  }
}

class ominous_excavation_blue extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_excavation_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $context = "Return an Aura from your graveyard to your deck (or pass)";
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYDISCARD:type=I", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", context:$context, may:true);
    Await($this->controller, $this->cardID, subsequent:0, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars, $CS_NumControlledAurasDestroyed;
    $choice = $dqVars["choice"] ?? "-";
    if ($choice != "-") {
      $obj = MZIndexToObject($this->controller, $choice);
      AddBottomDeck($obj->CardID(), $this->controller, "DISCARD");
      $obj->Remove();
      $deck = new Deck($this->controller);
      $deck->Shuffle("-");
    }
    if (GetClassState($this->controller, $CS_NumControlledAurasDestroyed) > 0)
      PlayAura("ponder", $this->controller);
  }
}

class gauntlet_of_sword_and_sorcery extends Card {
  function __construct($controller) {
    $this->cardID = "gauntlet_of_sword_and_sorcery";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Hero = new CharacterCard(0, $this->controller);
    $Gauntlet = new CharacterCard($index, $this->controller);
    return ($Hero->Tapped() || $Gauntlet->Tapped());
  }

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $Hero = new CharacterCard(0, $this->controller);
    $Gauntlet = new CharacterCard($index, $this->controller);
    $Hero->Tap();
    $Gauntlet->Tap();
    $Gauntlet->AddUse(); //unlimited uses
    $Gauntlet->SetUsed(2);
  }

  function OnAttackEffect($cardID, $i) {
    if (TypeContains($cardID, "AA")) {
      SetArcaneTarget($this->controller, $cardID, "their");
      AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID);
      return true;
    }
    return false;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    SetDamageSourceUID($CombatChain->AttackCard()->UniqueID());
    DealArcane(1, 1, source:$CombatChain->AttackCard()->ID(), resolvedTarget:$target);
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $arcaneDealt = $dqVars["ARCANEDEALT"] ?? 0;
    if ($arcaneDealt > 0) {
      AddCurrentTurnEffect("$this->cardID-BUFF", $this->controller);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return TypeContains($CombatChain->AttackCard()->ID(), "AA");
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "BUFF" ? 1 : 0;
  }

  function SpecialType() {
    return "E";
  }

  function SpecialSubType() {
    return "Arms";
  }

  function SpecialBlock() {
    return 0;
  }

  function ArcaneBarrier() {
    return 1;
  }

  function SpecialClass() {
    return "RUNEBLADE";
  }

  function SpecialName() {
    return "Gauntlet of Sword and Sorcery";
  }
}

class livewire_press extends BaseCard {

  function PlayAbility($target) {
    $zone = explode("-", $target)[0];
    switch($zone) {
      case "LAYER":
        AddCurrentTurnEffect($this->cardID, $this->controller);
        break;
      case "COMBATCHAINLINK":
        $index = intval(explode("-", $target)[1] ?? 0);
        if ($index == 0)
          AddCurrentTurnEffect($this->cardID, $this->controller);
        break;
      case "PASTCHAINLINK":
        break;
      default:
        break;
    }
    return "";
  }

  function IsPlayRestricted() {
    $targets = TargetAttackActionCard(talent:"LIGHTNING");
    return count($targets) == 0;
  }

  function PayAdditionalCosts() {
    $targets = TargetAttackActionCard(talent:"LIGHTNING");
    $targets = implode(",", $targets);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $targets);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a target for $this->cardID");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, $this->cardID, 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function AddEffectHitTrigger($check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "EFFECTHITEFFECT");
      return true;
    }
    return false;
  }

  function EffectHitEffect($damage) {
    global $CombatChain;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    SetDamageSourceUID($CombatChain->AttackCard()->UniqueID());
    DamageTrigger($otherPlayer, $damage, "DAMAGE", $CombatChain->AttackCard()->ID(), $this->controller);
  }
}

class livewire_press_red extends Card {
  function __construct($controller) {
    $this->cardID = "livewire_press_red";
    $this->controller = $controller;
    $this->baseCard = new livewire_press($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    return $this->baseCard->AddEffectHitTrigger($check);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    $this->baseCard->EffectHitEffect(4);
  }
}

class flowshard_elemental extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger() {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND:type=I", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "choice", may:true, context:"Discard an instant to make a lighting flow (or pass)");
    Await($this->controller, $this->cardID, final:true);
  }

  function SpecificLogic() {
    global $dqVars;
    $ind = explode("-", $dqVars["choice"])[1] ?? -1;
    if ($ind != -1) {
      DiscardCard($this->controller, $ind, $this->cardID, $this->controller);
      PlayAura("lightning_flow", $this->controller);
      GiveAttackGoAgain();
    }
  }
}

class flowshard_elemental_red extends Card {
  function __construct($controller) {
    $this->cardID = "flowshard_elemental_red";
    $this->controller = $controller;
    $this->baseCard = new flowshard_elemental($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function SpecificLogic() {
    $this->baseCard->SpecificLogic();
  }
}

class cosmic_flare extends BaseCard {
  function PlayAbility($num) {
    GainResources($this->controller, $num);
  }
}

class cosmic_flare_red extends Card {
  function __construct($controller) {
    $this->cardID = "cosmic_flare_red";
    $this->controller = $controller;
    $this->baseCard = new cosmic_flare($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(3);
    return "";
  }
}

class a_bit_off_the_side_red extends Card {
  function __construct($controller) {
    $this->cardID = "a_bit_off_the_side_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Axe");
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "EFFECTHITEFFECT");
      return true;
    }
    return false;
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    global $defPlayer;
    PummelHit($defPlayer);
  }

  function SpecialType() {
    return "A";
  }
}

class blessing_of_aegis_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "blessing_of_aegis_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function StartTurnAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "STARTTURN", $AuraCard->UniqueID());
  }

  function PermanentAddSoulAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "SOUL");
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "SOUL")
      GainHealth(1, $this->controller);
    else {
      AddSoul($this->cardID, $this->controller, "AURAS", false);
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      $AuraCard->Remove();
    }
  }
}

class ominous_respite_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "ominous_respite_yellow";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_NumControlledAurasDestroyed;
    if (GetClassState($this->controller, $CS_NumControlledAurasDestroyed) > 0)
      GainHealth(3, $this->controller);
    else
      GainHealth(2, $this->controller);
    return "";
  }
}

class settle_the_bill_red extends Card {
  function __construct($controller) {
    $this->cardID = "settle_the_bill_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    LoadArrow($this->controller);
    Await($this->controller, $this->cardID, final:true);
    return "";
  }

  function SpecificLogic() {
    global $dqVars;
    $loadedArrow = $dqVars["LASTRESULT"];
    $Arsenal = new Arsenal($this->controller);
    for ($i = 0; $i < $Arsenal->NumCards(); ++$i) {
      $ArsenalCard = $Arsenal->Card($i, true);
      if ($ArsenalCard->Facing() == "UP" && $ArsenalCard->CardID() == $loadedArrow) {
        AddCurrentTurnEffect($this->cardID, $this->controller, uniqueID:$ArsenalCard->UniqueID());
      }
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(),  "Arrow");
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-', $check = false) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "EFFECTHITEFFECT");
      return true;
    }
    return false;
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRARS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card you want to destroy from their arsenal", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, false, 1);
  }

  function SpecialType() {
    return "A";
  }
}

class shuriken {
  public $cardID;
  public $controller;

  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function PayAdditionalCosts($from, $index) {
    if ($from == "PLAY") {
      $Item = new ItemCard($index, $this->controller);
      $Item->Tap();
      AddCurrentTurnEffect($this->cardID, $this->controller, "", $Item->UniqueID());
    }
  }

  function GoesOnCombatChain($from) {
    return $from == "PLAY";
  }

  function EffectChainClosedEffect($i) {
    $Effect = new CurrentEffect($i);
    if ($Effect->EffectID() == $this->cardID) { // make sure it's only the basic effect
      $uid = $Effect->AppliestoUniqueID();
      $Items = new Items($this->controller);
      $ItemCard = $Items->FindCardUID($uid);
      $ItemCard->Destroy();
      $Effect->Remove();
    }
  }

  function IsPlayRestricted($index, $from) {
    if ($from == "PLAY") {
      $Item = new ItemCard($index, $this->controller);
      return $Item->Tapped();
    }
    return false;
  }
}

class razor_ring_blue extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "razor_ring_blue";
    $this->controller = $controller;
    $this->archetype = new shuriken($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($from, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($from);
  }

  function DoesAttackHaveGoAgain() {
    return true;
  }

  function EffectChainClosedEffect($i) {
    $this->archetype->EffectChainClosedEffect($i);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted($index, $from);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    AddCurrentTurnEffect("$this->cardID-SHRED", $defPlayer);
  }

  function EffectOnBlockModifier($effectIndex, $chainInd, $from) {
    $chainCard = new ChainCard($chainInd);
    if (TypeContains($chainCard->ID(), "A") || TypeContains($chainCard->ID(), "AA")) {
      $chainCard->ModifyDefense(-1);
      return true;
    }
    return false;
  }

  function RemoveEffectFromCombatChain() {
    return true;
  }
}

class stun_star_blue extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "stun_star_blue";
    $this->controller = $controller;
    $this->archetype = new shuriken($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($from, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($from);
  }

  function DoesAttackHaveGoAgain() {
    return true;
  }

  function EffectChainClosedEffect($i) {
    $this->archetype->EffectChainClosedEffect($i);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted($index, $from);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return HeroHitTrigger($this->controller, $this->cardID, $check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $defHero = new CharacterCard(0, $defPlayer);
    $defHero->Tap();
  }
}

class evasive_nageboshi_blue extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "evasive_nageboshi_blue";
    $this->controller = $controller;
    $this->archetype = new shuriken($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->archetype->PayAdditionalCosts($from, $index);
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($from);
  }

  function DoesAttackHaveGoAgain() {
    return true;
  }

  function EffectChainClosedEffect($i) {
    $this->archetype->EffectChainClosedEffect($i);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->archetype->IsPlayRestricted($index, $from);
  }
}

class draco_fire_red extends Card {
  function __construct($controller) {
    $this->cardID = "draco_fire_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    if (IsCombatChainOpen())
      AddCurrentTurnEffectFromCombat($this->cardID, $this->controller);
    else
      AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain, $mainPlayer;
    return $this->controller == $mainPlayer && TalentContains($CombatChain->AttackCard()->ID(), "DRACONIC", $mainPlayer);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    if (!TalentContains($cardID, "DRACONIC", $this->controller)) return 0;
    if (IsActivated($cardID, $from)) 
      return GetResolvedAbilityType($cardID, $from, $this->controller) == "AA" ? -1 : 0;
    else
      return TypeContains($cardID, "AA") ? -1 : 0;
  }

  function DiscardStartTurnTrigger($index) {
    global $Stack;
    if ($Stack->FindTrigger($this->cardID) != "") return; //don't trigger if there's already a trigger on the stack
    $foundFires = SearchDiscard($this->controller, nameIncludes:"Draco,Fire");
    if (SearchCount($foundFires) > 1)
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $foundFires = SearchDiscard($this->controller, nameIncludes:"Draco,Fire");
    if (SearchCount($foundFires) > 1) {
      Await($this->controller, "YesNo", context: "if_you_want_to_banish_" . CardLink($this->cardID) . "_to_gain_a_resource", subsequent:0);
      Await($this->controller, $this->cardID, final:true);
    }
  }

  function SpecificLogic() {
    $Discard = new Discard($this->controller);
    $num = 0;
    for ($i = $Discard->NumCards() - 1; $i >= 0; --$i) {
      $Card = $Discard->Card($i, true);
      if (CardName($Card->CardID()) == CardName($this->cardID)) {
        $Card->Banish();
        ++$num;
        if ($num == 2) {
          GainResources($this->controller, 1);
          return;
        }
      }
    }
  }
}

class caress_of_the_reaper_red extends Card {
  function __construct($controller) {
    $this->cardID = "caress_of_the_reaper_red";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function DamageDealtAbilities($target, $damage, $type) {
    $search = $target == $this->controller ? "MYAURAS" : "THEIRAURAS";
    $context = "Target an aura to destroy";
    Await($this->controller, "MultiZoneIndices", "indices", search:$search, subsequent:0);
    Await($this->controller, "ChooseMultiZone", "target", context:$context);
    Await($this->controller, "AddTrigger", cardID:$this->cardID, final:true);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Aura = CleanTargetToObject($this->controller,  $target);
    $Aura->Destroy();
  }
}

class arcanic_cunning extends BaseCard {


  function CombatChainTakeDamageAbility($type) {
    return $type == "ARCANE" ? 1 : 0;
  }

  function LayerTakeDamageAbility($type) {
    return $type == "ARCANE" ? 1 : 0;
  }
}

class arcanic_cunning_red extends Card {
  function __construct($controller) {
    $this->cardID = "arcanic_cunning_red";
    $this->controller = $controller;
    $this->baseCard = new arcanic_cunning($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CombatChainTakeDamageAbility($link, $index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->CombatChainTakeDamageAbility($type);
  }

  function LayerTakeDamageAbility($index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->LayerTakeDamageAbility($type);
  }
}

class arcanic_cunning_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "arcanic_cunning_yellow";
    $this->controller = $controller;
    $this->baseCard = new arcanic_cunning($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CombatChainTakeDamageAbility($link, $index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->CombatChainTakeDamageAbility($type);
  }

  function LayerTakeDamageAbility($index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->LayerTakeDamageAbility($type);
  }
}

class arcanic_cunning_blue extends Card {
  function __construct($controller) {
    $this->cardID = "arcanic_cunning_blue";
    $this->controller = $controller;
    $this->baseCard = new arcanic_cunning($this->cardID, $this->controller);
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CombatChainTakeDamageAbility($link, $index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->CombatChainTakeDamageAbility($type);
  }

  function LayerTakeDamageAbility($index, $damage, $type, $source, $preventable, $amount = false) {
    return $this->baseCard->LayerTakeDamageAbility($type);
  }
}

class fraying_lifeforce extends BaseCard {
  function FragmentTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    GainHealth(1, $this->controller);
  }
}

class fraying_lifeforce_red extends Card {
  function __construct($controller) {
    $this->cardID = "fraying_lifeforce_red";
    $this->controller = $controller;
    $this->baseCard = new fraying_lifeforce($this->cardID, $this->controller);
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
}