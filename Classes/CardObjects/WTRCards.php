<?php

class nimblism_red extends Card {
  public $cardID;
  public $controller;
  function __construct($controller) {
    $this->cardID = "nimblism_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }
}

?>