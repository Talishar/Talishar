<?php
// Discard Class to handle interactions involving the discard

class Discard {

  // Properties
  private $discard = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->discard = &GetDiscard($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->discard) == 0;
  }

  function NumCards() {
    return count($this->discard)/DiscardPieces();
  }

  function RemoveRandom($count=1) {
    $cards = "";
    for($i = 0; $i < $count && !$this->Empty(); $i++) {
      $index = (GetRandom() % $this->NumCards()) * DiscardPieces();
      if($cards != "") $cards .= ",";
      $cards .= $this->discard[$index];
      unset($this->discard[$index+1]);
      unset($this->discard[$index]);
      $this->discard = array_values($this->discard);
    }
    return $cards;
  }

  function Remove($index) {
    $cardID = $this->discard[$index];
    for ($i = DiscardPieces() - 1; $i >= 0; --$i) {
      unset($this->discard[$index+$i]);
    }
    $this->discard = array_values($this->discard);
    return $cardID;
  }

  function Add($cardID, $from="GY") {
    array_push($this->discard, $cardID);
    array_push($this->discard, GetUniqueId());
  }
}