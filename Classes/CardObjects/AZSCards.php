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
		if ($amount) return $Effect->NumUses();
		if ($player != $this->controller) return 0;
		$remove = true;
		return $Effect->NumUses();
	}
}