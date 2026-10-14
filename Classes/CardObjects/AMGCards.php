<?php
class madness_spiral_blue extends Card {
  function __construct($controller) {
    $this->cardID = "madness_spiral_blue";
    $this->controller = $controller;
  }
  
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function OnBoostedEffect($owner, $cardID) {
		if ($owner == $this->controller) {
			DoBoost($this->controller, $cardID, boostOpp:true);
		}
	}
}