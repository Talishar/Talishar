<?php
include_once  __DIR__ . "/HVYCards.php";
include_once  __DIR__ . "/SUPCards.php";

class runechant_of {
  public $cardID;
  public $controller;

  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function DestroyEffect() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROYED");
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $AuraCard = new AuraCard($i, $this->controller);
    if (!IsActivated($cardID, $from) && TypeContains($cardID, "AA", from:$from))
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function UsurpedEffect() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "USURPED");
  }

  function ProcessTrigger($uniqueID, $additionalCosts) {
    switch ($additionalCosts) {
      case "DESTROY":
        $Auras = new Auras($this->controller);
        $AuraCard = $Auras->FindCardUID($uniqueID);
        $AuraCard->Destroy();
        break;
      case "DESTROYED":
        PlayAura("runechant", $this->controller);
        break;
      default:
        break;
    }
  }

  function IsRunechant() {
    return true;
  }
}

class runechant_of_greed_yellow extends Card {
  private $archetype;
  function __construct($controller) {
    $this->cardID = "runechant_of_greed_yellow";
    $this->controller = $controller;
    $this->archetype = new runechant_of($this->cardID, $this->controller);
  }

  function DestroyEffect() {
    $this->archetype->DestroyEffect();
  }

  function BeginningActionPhaseAbility($index) {
    $this->archetype->BeginningActionPhaseAbility($index);
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    $this->archetype->PermanentPlayAbility($cardID, $from, $i);
  }

  function UsurpedEffect() {
    $this->archetype->UsurpedEffect();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "USURPED")
      Draw($this->controller);
    else
      $this->archetype->ProcessTrigger($uniqueID, $additionalCosts);
  }

  function IsRunechant() {
    return $this->archetype->IsRunechant();
  }

  function SpecialType() {
    return "I";
  }

  function SpecialSubType() {
    return "Aura";
  }
}

class baalghor_omen_of_the_end extends Card {
  function __construct($controller) {
    $this->cardID = "baalghor_omen_of_the_end";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function SpecialType() {
    return "C";
  }

  function PermanentPitchCardAbility($pitchIndex) {
    $PitchCard = new PitchCard($pitchIndex, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $PitchCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $Pitch = new PitchZone($this->controller);
    $PitchCard = $Pitch->FindCardUID($target);
    BanishCardForPlayer($PitchCard->CardID(), $this->controller, "PITCH");
    $PitchCard->Remove();
  }

  function PermanentPowerModifier(&$powerModifiers) {
    global $CombatChain;
    if ($CombatChain->AttackCard()->From() == "BANISH") {
      $powerModifiers[] = $this->cardID;
      $powerModifiers[] = 3;
      return 3;
    }
    return 0;
  }

  function SpecialTalent() {
    return "SHADOW";
  }

  function SpecialName() {
    return "Baalghor, Omen of the End";
  }
}