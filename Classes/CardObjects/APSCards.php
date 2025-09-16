<?php

// class standing_ovation_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "standing_ovation_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class moment_maker extends Card {
  function __construct($controller) {
    $this->cardID = "moment_maker";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AA";
  }

  function AbilityCost() {
    return 3;
  }

  function WeaponPowerModifier($basePower) {
    $search = SearchMultizone($this->controller, "MYAURAS:hasSuspense=1");
    return SearchCount($search) >= 3 ? $basePower + 2 : $basePower;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    $search = SearchMultizone($this->controller, "MYAURAS:hasSuspense=1");
    if (IsHeroAttackTarget() && SearchCount($search) >= 3) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    Cheer($this->controller);
  }
}

class superstar_blue extends Card {
  function __construct($controller) {
    $this->cardID = "superstar_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger($uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") DestroyAuraUniqueID($this->controller, $target);
    else Cheer($this->controller);
  }
}
?>