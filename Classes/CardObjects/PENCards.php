<?php

class boltn_boots extends Card
{
  function __construct($controller)
  {
    $this->cardID = "boltn_boots";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-')
  {
    return "AR";
  }

  function AbilityCost()
  {
    return 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    GiveAttackGoAgain();
  }
}
?>