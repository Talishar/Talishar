<?php

class Banish {

  // Properties
  private $banish = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->discard = &GetBanish($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->banish) == 0;
  }

  function NumCards() {
    return count($this->banish) / BanishPieces();
  }

  function Remove($index) {
    $cardID = $this->banish[$index];
    unset($this->banish[$index]);
    $this->banish = array_values($this->banish);
    return $cardID;
  }
}

?>
