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
    if($cardNumber) $index *= CurrentTurnEffectPieces();
    return new CurrentEffect($index);
  }

  function FindEffect($effectID, $player="-") {
    $effects = $this->effects;
    $count = count($effects);
    if ($player === "-") {
      for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
        if ($effects[$i] == $effectID) return new CurrentEffect($i);
      }
      return new CurrentEffect(-1);
    }
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if ($player != $effects[$i + 1]) continue;
      if ($effects[$i] == $effectID) return new CurrentEffect($i);
    }
    return new CurrentEffect(-1);
  }

  function FindPartialEffect($effectID) {
    $effects = $this->effects;
    $count = count($effects);
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if (ExtractCardID($effects[$i]) == $effectID) return new CurrentEffect($i);
    }
    return new CurrentEffect(-1);
  }

  function FindEffectUID($uid) {
    $effects = $this->effects;
    $count = count($effects);
    for ($i = 2; $i < $count; $i += CurrentTurnEffectPieces()) {
      if ($effects[$i] == $uid) return new CurrentEffect($i - 2);
    }
    return new CurrentEffect(-1);
  }

  function FindSpecificEffect($cardID, $uid, $player=-1) {
    $effects = $this->effects;
    $count = count($effects);
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if ($effects[$i] != $cardID) continue;
      if ($player != -1 && ($effects[$i + 1] ?? -1) != $player) continue;
      if (isset($effects[$i + 2]) && $effects[$i + 2] == $uid) return new CurrentEffect($i);
    }
    return new CurrentEffect(-1);
  }

  function HasAnySpecificEffect($cardIDSet, $uid, $player=-1) {
    $effects = $this->effects;
    $count = count($effects);
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if (!isset($cardIDSet[$effects[$i]])) continue;
      if ($player != -1 && ($effects[$i + 1] ?? -1) != $player) continue;
      if (isset($effects[$i + 2]) && $effects[$i + 2] == $uid) return true;
    }
    return false;
  }

  function HasAnyEffectID($cardIDSet) {
    $effects = $this->effects;
    $count = count($effects);
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if (isset($cardIDSet[$effects[$i]])) return true;
    }
    return false;
  }

  function CountSpecificEffect($cardID, $uid, $player=-1) {
    $effects = $this->effects;
    $count = count($effects);
    $ret = 0;
    for ($i = 0; $i < $count; $i += CurrentTurnEffectPieces()) {
      if ($effects[$i] != $cardID) continue;
      if ($player != -1 && ($effects[$i + 1] ?? -1) != $player) continue;
      if (isset($effects[$i + 2]) && $effects[$i + 2] == $uid) ++$ret;
    }
    return $ret;
  }

  function NumEffects() {
    return intdiv(count($this->effects), CurrentTurnEffectPieces());
  }

  function RemoveEffectByID($effectID) {
    for ($i = $this->NumEffects() - 1; $i >= 0; --$i) {
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
    $this->index = $index;
    if ($index != -1)
      $this->pieces = &$currentTurnEffects;
    else
      $this->pieces = [];
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
    return $this->pieces[$this->index+2] ?? -1;
  }

  function ApplyToUniqueID($uid) {
    if (isset($this->pieces[$this->index+2]))
      $this->pieces[$this->index+2] = $uid;
  }

  function NumUses() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function AddUses($num) {//can be negative
    $this->pieces[$this->index+3] += $num;
  }

	function Remove() {
		if ($this->index < 0) return;
		array_splice($this->pieces, $this->index, CurrentTurnEffectPieces());
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
    if($cardNumber) $index *= NextTurnEffectsPieces();
    return new NextEffect($index);
  }

  function FindEffectUID($uid) {
    $count = count($this->effects);
    if ($count == 0) return "";
    $nextTurnEffectsPieces = NextTurnEffectsPieces();
    for ($i = 0; $i < $count; $i += $nextTurnEffectsPieces) {
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