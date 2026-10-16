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
    Await($this->controller, "PlayAura", lastResultName: "number", cardID:"frostbite");
  }

  function SpecialType() {
    return "C";
  }

  function SpecificLogic() {
    global $dqVars;
    $lastResult = GamestateUnsanitize($dqVars["LASTRESULT"]);
    $a = $dqVars["a"];
    $b = $dqVars["b"];
    WriteLog("DEBUG: $lastResult - $a - $b");
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

class shapeshifter extends Card {
  function __construct($controller) {
    $this->cardID = "fangs_a_lot_blue";
    $this->controller = $controller;
  }

  function SpecialType() {
    return "T";
  }
}

class DISCARDACARD extends Card {
  function __construct($controller) {
    $this->cardID = "DISCARD";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYHAND", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "MZIndex", may:true, context:"Delete a card from your hand");
    Await($this->controller, "MZRemove", final:true);
    return "";
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "-";
  }

  function SpecialType() {
    return "I";
  }

  function SpecialName() {
    return "Manual Mode Discard";
  }
}

class DELPERM extends Card {
  function __construct($controller) {
    $this->cardID = "DISCARD";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Await($this->controller, "MultiZoneIndices", "indices", search:"MYAURAS&MYITEMS&MYCHAR&MYALLY&MYPERMANENTS", subsequent:0);
    Await($this->controller, "ChooseMultiZone", "MZIndex", may:true, context:"Delete a card from the arena");
    Await($this->controller, "MZRemove", final:true);
    return "";
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "-";
  }

  function SpecialType() {
    return "I";
  }

  function SpecialName() {
    return "Manual Mode Delete";
  }
}

class MILL extends Card {
  function __construct($controller) {
    $this->cardID = "DISCARD";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Deck = new Deck($this->controller);
    $inds = implode(",", range(0, $resourcesPaid-1));
    $cardIDs = $Deck->Remove($inds);
    foreach (explode(",", $cardIDs) as $cardID)
      AddGraveyard($cardID, $this->controller, "DECK");
    return "";
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "-";
  }

  function SpecialType() {
    return "I";
  }

  function SpecialName() {
    return "Manual Mode Mill";
  }
}

class flow_baited_hook extends Card {
  function __construct($controller) {
    $this->cardID = "flow_baited_hook";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(1, resolvedTarget:$target, source:$this->cardID);
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $CharacterCard = new CharacterCard($index, $this->controller);
    return $CharacterCard->Tapped();
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->TapForCost();
    SetArcaneTarget($this->controller, $this->cardID, "any");
  }

  function ArcaneHitEffect($source, $target, $damage) {
    PlayAura("lightning_flow", $this->controller);
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
  }

  function AbilityCost() {
    return 3;
  }

  function SpecialType() {
    return "E";
  }

  function SpecialTalent() {
    return "LIGHTNING";
  }

  function SpecialSubType() {
    return "Arms";
  }

  function SpecialBlock() {
    return 0;
  }

  function SpecialName() {
    return "Flow Baited Hook";
  }
}