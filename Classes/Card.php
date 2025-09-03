<?php
// This is an interface with functions that each zone's card class must implement


class Card {
  // Properties
  public $cardID;
  public $controller;

  // Constructor
  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function IsType($types) {
    $typesArr = explode(",", $types);
    for($i=0; $i<count($typesArr); ++$i) {
      if(TypeContains($this->cardID, $typesArr[$i], $this->controller)) return true;
    }
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    return "NOT IMPLEMENTED";
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    return "NOT IMPLEMENTED";
  } 

  function CardType($from="", $additionalCosts="-") {
    return CardType($this->cardID, $from, $this->controller, $additionalCosts);
  }

  function PowerValue($from="CC", $index=-1, $base=false) {
    return PowerValue($this->cardID, $this->controller, $from, $index, $base);
  }

  function PayAdditionalCosts($from, $index="-") {
    return "";
  }

  function PayAbilityAdditionalCosts($index, $from="-", $zoneIndex=-1) {
    return;
  }

  function IsPlayable($phase, $from, $index = -1, &$restriction = null, $pitchRestriction = "") {
    return IsPlayable($this->cardID, $phase, $from, $index, $restriction, $this->controller, $pitchRestriction);
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    return IsPlayRestricted($this->cardID, $restriction, $from, $index, $this->controller, $resolutionCheck);
  }

  function AbilityPlayableFromCombatChain($index="-") {
    return false;
  }

  function AbilityType($index = -1, $from = "-") {
    return "";
  }

  function EffectPowerModifier($attached=false) {
    return 0;
  }

  function CombatEffectActive($parameter = "-", $defendingCard = "", $flicked = false) {
    return false;
  }

  function NumUses() {
    return 0;
  }

  function GetAbilityTypes($index=-1, $from="-") {
    return "";
  }

  function GetAbilityNames($index=-1, $from="-", $foundNullTime=false, $layerCount=0) {
    return "";
  }

  function ResolutionStepEffectTriggers($parameter) {
    return false; //return whether to remove the effect
  }
}

?>
