<?php

class Inventory {

  // Properties
  private $inventory = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->inventory = &GetInventory($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->inventory) == 0;
  }

  function RemainingCards() {
    // Code to return the number of remaining cards in the deck
    return count($this->inventory);
  }

  function Remove($indices) {
    if ($indices == "") return "";
    if ($indices === 0 || $indices === "0") {
      if (isset($this->inventory[0])) return array_shift($this->inventory);
      WriteLog("Something went wrong with removing a card from deck, please submit a bug report");
      return "";
    }
    $indexArr = explode(",", $indices);
    $cardIDs = [];
    for($i=count($indexArr)-1; $i>= 0; --$i) {
      if (isset($this->inventory[$indexArr[$i]])) {
        $cardIDs[] = $this->inventory[$indexArr[$i]];
        unset($this->inventory[$indexArr[$i]]);
      }
      else WriteLog("Something went wrong with removing a card from inventory, please submit a bug report");
    }
    $this->inventory = array_values($this->inventory);
    return implode(",", $cardIDs);
  }


  function GetCard($index) {
    return $this->inventory[$index];
  }
}
