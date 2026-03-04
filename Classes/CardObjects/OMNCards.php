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
    $Auras = new Auras($this->controller);
    $Flow = $Auras->FindCardID("lightning_flow");
    $Flow->Destroy();
    $indices = FindHoloAuras($this->controller, excludeFirstFlow:false);
    Await($this->controller, "ChooseMultizone", returnName:"MZIndex", subsequent:0, indices:$indices);
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