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

  function FindCardUID($uid) {
    if (!$this->HasCurrentLink()) return "";
    for ($i = 0; $i < count($this->chain); $i += CombatChainPieces()) {
      if ($this->chain[$i + 7] == $uid) return new ChainCard($i);
    }
    return "";
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

    function Become($cardID) {
      if (isset($this->chain[$this->index]))
        $this->chain[$this->index] = $cardID;
    }

    function PlayerID() {
      return isset($this->chain[$this->index+1]) ? $this->chain[$this->index+1] : null;
    }

    function From() {
      return isset($this->chain[$this->index+1]) ? $this->chain[$this->index+2] : null;
    }

    function ResourcesPaid() {
      return isset($this->chain[$this->index+3]) ? $this->chain[$this->index+3] : 0;
    }

    function RepriseActive() {
      return isset($this->chain[$this->index+4]) ? $this->chain[$this->index+4] : 0;
    }

    function PowerValue() {
      return isset($this->chain[$this->index+5]) ? $this->chain[$this->index+5] : 0;
    }

    function TotalPower() {
      $powerModifiers = [];
      $player = $this->PlayerID();
      if(PowerCantBeModified($this->ID())) return PowerValue($this->ID(), $player, "CC");
      $powerValue = ModifiedPowerValue($this->ID(), $player, "CC", $this->ID(), $this->index);
      $powerValue += AuraPowerModifiers($this->index, $powerModifiers, onBlock: true);
      $powerValue += ItemsPowerModifiers($this->ID(), $player, "CC");
      $powerValue += $this->PowerValue();//Combat chain power modifier
      return $powerValue;
    }

    function ModifyPower($amount) {
      $this->chain[$this->index+5] += $amount;
      CurrentEffectAfterPlayOrActivateAbility();
    }

    function ModifyDefense($amount) {
      global $CombatChain;
      if (!isset($this->chain[$this->index+6]) || !CanGainBlock($this->chain[$this->index+6])) $amount = 0;
      if (isset($this->chain[$this->index+6])) $this->chain[$this->index+6] += $amount;
    }

    function UniqueID() {
      return isset($this->chain[$this->index+7]) ? $this->chain[$this->index+7] : null;
    }

    function OriginUniqueID() {
      return isset($this->chain[$this->index+8]) ? $this->chain[$this->index+8] : null;
    }

    function StaticBuffs() {
      return isset($this->chain[$this->index+10]) ? $this->chain[$this->index+10] : "";
    }

    function CardBlockValue() {
      if (CanGainBlock($this->ID())) {
        return BlockValue($this->ID()) + $this->chain[$this->index + 6];
      }
      else return BlockValue($this->ID());
    }

    function OriginalID() {
      if(count($this->chain) == 0) return "";
      return $this->chain[$this->index + 9];
    }

    function Remove() {
      global $CombatChain;
      return $CombatChain->Remove($this->Index());
    }
}