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
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CombatChainPieces();
    return new ChainCard($index);
  }

  function AttackCard() {
    return new ChainCard(0);
  }

  function AbilityCard() {
    global $currentPlayer, $CS_PlayIndex;
    return new ChainCard(GetClassState($currentPlayer, $CS_PlayIndex));
  }

  function Remove($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CombatChainPieces();
    if($index < 0 || $index >= count($this->chain)) return "";
    $cardID = $this->chain[$index];
    RemoveEffectsFromCombatChain($cardID);
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
      if(count($this->chain) == 0) return "";
      return $this->chain[$this->index];
    }

    function PlayerID() {
      return isset($this->chain[$this->index+1]) ? $this->chain[$this->index+1] : null;
    }

    function From() {
      return isset($this->chain[$this->index+1]) ? $this->chain[$this->index+2] : null;
    }

    function ResourcesPaid() {
      return $this->chain[$this->index+3];
    }

    function RepriseActive() {
      return $this->chain[$this->index+4];
    }

    function PowerValue() {
      return $this->chain[$this->index+5];
    }

    function ModifyPower($amount) {
      $this->chain[$this->index+5] += $amount;
      CurrentEffectAfterPlayOrActivateAbility();
    }

    function ModifyDefense($amount) {
      $this->chain[$this->index+6] += $amount;
    }

    function UniqueID() {
      return $this->chain[$this->index+7];
    }
}