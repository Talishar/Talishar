<?php

class CombatChain {

  // Properties
  private $chain = [];

  // Constructor
  function __construct() {
    global $combatChain;
    $this->chain = $combatChain;
  }

  // Methods
  function Card($index)
  {
    return new BanishCard($this->playerID, $index);
  }
}

class ChainCard {
    // Properties
    private $chain = [];
    private $index;

    // Constructor
    function __construct($playerID, $index) {
      global $combatChain;
      $this->chain = $combatChain;
      $this->index = $index;
    }

    function Index()
    {
      return $this->index;
    }

    function ID()
    {
      return $this->chain[$this->index];
    }
}

?>
