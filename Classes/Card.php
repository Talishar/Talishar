<?php
// This is an interface with functions that each zone's card class must implement


class Card {
    // Properties
    private $cardID;
    private $controller;

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

}

?>
