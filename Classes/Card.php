<?php
// This is an interface with functions that each zone's card class must implement


class Card {
  // Properties
  public $cardID;
  public $controller;

  // Constructor
  function __construct($cardID, $controller) {
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

  function IsPlayable($phase, $from, $index = -1, &$restriction = null, $pitchRestriction = "") {
    return IsPlayable($this->cardID, $phase, $from, $index, $restriction, $this->controller, $pitchRestriction);
  }
}

?>
