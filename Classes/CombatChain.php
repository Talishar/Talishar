<?php

global $CombatChain;
$CombatChain = new CombatChain();

class CombatChain {

  // Properties
  private $chain = [];

  // Constructor
  function __construct() {
    global $combatChain;
    $this->chain = &$combatChain;
  }

  // Methods
  function Card($index) {
    return new ChainCard($index);
  }

  function AttackCard() {
    return new ChainCard(0);
  }

  function Remove($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CombatChainPieces();
    if($index < 0 || $index >= count($this->chain)) return "";
    $cardID = $this->chain[$index];
    for($i = CombatChainPieces() - 1; $i >= 0; --$i) unset($this->chain[$index+$i]);
    $this->chain = array_values($this->chain);
    return $cardID;
  }

  function NumCardsActiveLink() {
    return count($this->chain) / CombatChainPieces();
  }

  function HasCurrentLink() {
    return count($this->chain) > 0;
  }

  function CurrentAttack() {
    if(!$this->HasCurrentLink()) return "";
    return $this->chain[0];
  }
}

class ChainCard {
    // Properties
    private $chain = [];
    private $index;

    // Constructor
    function __construct($index) {
      global $combatChain;
      $this->chain = &$combatChain;
      $this->index = $index;
    }

    function Index() {
      return $this->index;
    }

    function ID() {
      return $this->chain[$this->index];
    }

    function PlayerID() {
      return $this->chain[$this->index+1];
    }

    function From() {
      return $this->chain[$this->index+2];
    }

    function ModifyPower($amount) {
      $this->chain[$this->index+5] += $amount;
    }
}

?>
