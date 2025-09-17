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