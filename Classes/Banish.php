<?php

class Banish {

  // Properties
  private $banish = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->banish = &GetBanish($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->banish) == 0;
  }

  function NumCards() {
    return count($this->banish) / BanishPieces();
  }

  function FirstCardWithModifier($modifier)
  {
    $index = -1;
    for($i=0; $i<count($this->banish); $i+=BanishPieces()) {
      if($this->banish[$i+1] == $modifier) $index = $i;
    }
    return new BanishCard($this->banish, $index);
  }

  function Remove($index) {
    $cardID = $this->banish[$index];
    for($i=0; $i<BanishPieces(); ++$i) unset($this->banish[$index+$i]);
    $this->banish = array_values($this->banish);
    return $cardID;
  }
}

class BanishCard {
    // Properties
    private $banish = [];
    private $index;

    // Constructor
    function __construct(&$banish, $index) {
      $this->banish = $banish;
      $this->index = $index;
    }

    function Index()
    {
      return $this->index;
    }
}

?>
