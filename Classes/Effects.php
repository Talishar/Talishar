<?php

global $CurrentTurnEffects, $NextTurnEffects;
$CurrentTurnEffects = new CurrentTurnEffects();
$NextTurnEffects = new NextTurnEffects();

class CurrentTurnEffects {

  // Properties
  private $effects = [];

  // Constructor
  function __construct() {
    global $currentTurnEffects;
    $this->effects = &$currentTurnEffects;
  }

  // Methods
  function Effect($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CurrentTurnEffectPieces();
    return new CurrentEffect($index);
  }

  function FindEffectUID($uid) {
    if (count($this->effects) == 0) return "";
    for ($i = 0; $i < count($this->effects); $i += CurrentTurnEffectPieces()) {
      if ($this->effects[$i + 2] == $uid) return new CurrentEffect($i);
    }
    return "";
  }

  function FindSpecificEffect($cardID, $uid) {
    if (count($this->effects) == 0) return "";
    for ($i = 0; $i < count($this->effects); $i += CurrentTurnEffectPieces()) {
      if ($this->effects[$i] == $cardID && $this->effects[$i + 2] == $uid) return new CurrentEffect($i);
    }
    return "";
  }

  function NumEffects() {
    return intdiv(count($this->effects), CurrentTurnEffectPieces());
  }

	function RemoveEffectByID($effectID) {
		for ($i = $this->NumEffects() - 1; $i >= 0; $i -= 1) {
			$Effect = $this->Effect($i, true);
			if ($Effect->EffectID() == $effectID) $Effect->Remove();
		}
	}
}

class CurrentEffect {
  // Properties
  private $pieces = [];
	private $index;

  // Constructor
  function __construct($index) {
		global $currentTurnEffects;
    $this->pieces = &$currentTurnEffects;
    $this->index = $index;
  }

  function Index() {
    return $this->index;
  }

  function EffectID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function Replace($effectID) {
    $this->pieces[$this->index] = $effectID;
  }

  function PlayerID() {
    return $this->pieces[$this->index+1] ?? 0;
  }

  function AppliestoUniqueID() {
    return $this->pieces[$this->index+2] ?? "-";
  }

  function NumUses() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function AddUses($num) {//can be negative
    $this->pieces[$this->index+3] += $num;
  }

	function Remove() {
		for ($i = CurrentTurnEffectPieces() - 1; $i >= 0; --$i) {
			unset($this->pieces[$this->index + $i]);
		}
		$this->pieces = array_values($this->pieces);
	}
}

class NextTurnEffects {

  // Properties
  private $effects = [];

  // Constructor
  function __construct() {
    global $nextTurnEffects;
    $this->effects = &$nextTurnEffects;
  }

  // Methods
  function Effect($index, $cardNumber=false) {
    if($cardNumber) $index = $index * NextTurnEffectsPieces();
    return new NextEffect($index);
  }

  function FindEffectUID($uid) {
    if (count($this->effects) == 0) return "";
    for ($i = 0; $i < count($this->effects); $i += NextTurnEffectsPieces()) {
      if ($this->effects[$i + 2] == $uid) return new NextEffect($i);
    }
    return "";
  } 

  function NumEffects() {
    return intdiv(count($this->effects), NextTurnEffectsPieces());
  }
}

class NextEffect {
  // Properties
  private $pieces = [];
	private $index;

  // Constructor
  function __construct($index) {
		global $nextTurnEffects;
    $this->pieces = &$nextTurnEffects;
    $this->index = $index;
  }

  function Index() {
    return $this->index;
  }

  function EffectID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function PlayerID() {
    return $this->pieces[$this->index+1] ?? 0;
  }

  function AppliestoUniqueID() {
    return $this->pieces[$this->index+2] ?? "-";
  }

  function NumUses() {
    return $this->pieces[$this->index+3] ?? 0;
  }

	function NumTurnsBeforeEffect() {
		return $this->pieces[$this->index+4] ?? 0;
	}
}