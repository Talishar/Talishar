<?php

class bare_destruction_red extends Card {

  function __construct($controller) {
    $this->cardID = "bare_destruction_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function HasBeatChest() {
    return true;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $hasChest = SearchCharacterAliveSubtype($this->controller, "Chest");
    if (SearchCurrentTurnEffects("BEATCHEST", $this->controller) && !$hasChest) {
      AddCurrentTurnEffectFromCombat($this->cardID, $this->controller);
      GiveAttackGoAgain();
    }
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    $attackCard = $CombatChain->AttackCard()->ID();
    return TypeContains($attackCard, "AA") && ClassContains($attackCard, "BRUTE", $this->controller);
  }
}

class bare_swing_yellow extends Card {

  function __construct($controller) {
    $this->cardID = "bare_swing_yellow";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function HasBeatChest() {
    return true;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $hasChest = SearchCharacterAliveSubtype($this->controller, "Chest");
    if (SearchCurrentTurnEffects("BEATCHEST", $this->controller) && !$hasChest) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class bare_swing_red extends Card {

  function __construct($controller) {
    $this->cardID = "bare_swing_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
    return "";
  }

  function HasBeatChest() {
    return true;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $hasChest = SearchCharacterAliveSubtype($this->controller, "Chest");
    if (SearchCurrentTurnEffects("BEATCHEST", $this->controller) && !$hasChest) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class smell_fear extends BaseCard {
  function PlayAbility() {
    if (SearchCurrentTurnEffects("BEATCHEST", $this->controller)) Intimidate();
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    return "";
  }

  function IsGrantedBuff() {
    return true;
  }

  function CombatEffectActive() {
    global $CombatChain;
    $attackCard = $CombatChain->AttackCard()->ID();
    return ClassContains($attackCard, "BRUTE", $this->controller);
  }

  function HasBeatChest() {
    return true;
  }
}

class smell_fear_yellow extends Card {

  function __construct($controller) {
    $this->cardID = "smell_fear_yellow";
    $this->controller = $controller;
    $this->baseCard = new smell_fear($this->cardID, $this->controller);
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function IsGrantedBuff() {
    return $this->baseCard->IsGrantedBuff();
  }

  function EffectPowerModifier($param, $attached = false) {
    global $CS_HaveIntimidated;
    return GetClassState($this->controller, $CS_HaveIntimidated) >= 2 ? 3 : 0;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function HasBeatChest() {
    return $this->baseCard->HasBeatChest();
  }
}

class smell_fear_blue extends Card {

  function __construct($controller) {
    $this->cardID = "smell_fear_blue";
    $this->controller = $controller;
    $this->baseCard = new smell_fear($this->cardID, $this->controller);
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
    return "";
  }

  function IsGrantedBuff() {
    return $this->baseCard->IsGrantedBuff();
  }

  function EffectPowerModifier($param, $attached = false) {
    global $CS_HaveIntimidated;
    return GetClassState($this->controller, $CS_HaveIntimidated) >= 2 ? 2 : 0;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function HasBeatChest() {
    return $this->baseCard->HasBeatChest();
  }
}

class torc_of_vim extends Card {
  function __construct($controller) {
    $this->cardID = "torc_of_vim";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = FindCharacterIndex($this->controller, $this->cardID);
    AddDecisionQueue("YESNO", $this->controller, "if you want to destroy ".Cardlink($this->cardID, $this->cardID));
    AddDecisionQueue("NOPASS", $this->controller, "-");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $index, 1);
    AddDecisionQueue("DESTROYCHARACTER", $this->controller, "-", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, $this->cardID, 1);
  }

  function DefaultActiveState() {
    return 1;
  }

  function CurrentEffectCostModifier($cardID, &$remove) {
    if (TypeContains($cardID, "AA") && ClassContains($cardID, "BRUTE", $this->controller)) {
      $remove = true;
      return -2;
    }
    return 0;
  }

  function WhenBeatChest($index) {
    if (IsCharacterActive($this->controller, $index)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-");
    }
  }
}

class trampling_trackers extends Card {
  function __construct($controller) {
    $this->cardID = "trampling_trackers";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = FindCharacterIndex($this->controller, $this->cardID);
    AddDecisionQueue("YESNO", $this->controller, "if you want to destroy ".Cardlink($this->cardID, $this->cardID));
    AddDecisionQueue("NOPASS", $this->controller, "-");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $index, 1);
    AddDecisionQueue("DESTROYCHARACTER", $this->controller, "-", 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "agility", 1);
  }

  function DefaultActiveState() {
    return 1;
  }

  function WhenBeatChest($index) {
    if (IsCharacterActive($this->controller, $index)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-");
    }
  }
}

class echo_casque extends Card {
  function __construct($controller) {
    $this->cardID = "echo_casque";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = FindCharacterIndex($this->controller, $this->cardID);
    AddDecisionQueue("YESNO", $this->controller, "if you want to pay a resource and destroy ".Cardlink($this->cardID, $this->cardID));
    AddDecisionQueue("NOPASS", $this->controller, "-");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $index, 1);
    AddDecisionQueue("DESTROYCHARACTER", $this->controller, "-", 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, 1, 1);
    AddDecisionQueue("DRAW", $this->controller, "-", 1);
  }

  function DefaultActiveState() {
    return 1;
  }

  function WhenBeatChest($index) {
    if (IsCharacterActive($this->controller, $index)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "-");
    }
  }
}