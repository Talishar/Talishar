<?php
//File for debug cards
class time_walk extends Card {
  function __construct($controller) {
    $this->cardID = "time_walk";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect("standing_ovation_blue", $this->controller);
  }

  function SpecialType() {
    return "A";
  }
}

class dummy extends Card {
  function __construct($controller) {
    $this->cardID = "dummy";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("PASSPARAMETER", $this->controller, "this is the last result");
    Await($this->controller, $this->cardID, a:"hello", b:"world");
    AddDecisionQueue("PASSPARAMETER", $this->controller, 5);
    Await($this->controller, "PlayAura", "number", cardID:"frostbite");
  }

  function SpecialType() {
    return "A";
  }

  function SpecificLogic() {
    global $dqVars;
    $lastResult = GamestateUnsanitize($dqVars["LASTRESULT"]);
    $a = $dqVars["a"];
    $b = $dqVars["b"];
    WriteLog("HERE: $lastResult - $a - $b");
  }
}

class fangs_a_lot_blue extends Card {
  function __construct($controller) {
    $this->cardID = "fangs_a_lot_blue";
    $this->controller = $controller;
  }

  function SpecialType() {
    return "AA";
  }

  function SpecialPower() {
    return 6;
  }

  function SpecialBlock() {
    return 3;
  }

  function SpecialPitch() {
    return 3;
  }

  function SpecialCost() {
    return 4;
  }

  function SpecialName() {
    return "Fangs A Lot";
  }

  function SpecialClass() {
    return "GENERIC";
  }
}