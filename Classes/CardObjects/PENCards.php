<?php

class synapse_sparkcap extends Card {
  function __construct($controller) {
    $this->cardID = "synapse_sparkcap";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Tap("MYCHAR-$index", $this->controller);
    MZChooseAndBanish($currentPlayer, "MYHAND:subtype=Evo", "HAND,-");
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("ponder", $currentPlayer);
  }
}
?>