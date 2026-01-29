<?php

class Auras {

  // Properties
  private $auras = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->auras = &GetAuras($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * AuraPieces();
    return new AuraCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->auras) == 0) return "";
    for ($i = 0; $i < count($this->auras); $i += AuraPieces()) {
      if ($this->auras[$i + 6] == $uid) return new AuraCard($i, $this->player);
    }
    return "";
  } 

  function NumAuras() {
    return intdiv(count($this->auras), AuraPieces());
  }

  function FindCardID($cardID) { //returns first AuraCard with a cardID
    if (count($this->auras) == 0) return "";
    for ($i = 0; $i < count($this->auras); $i += AuraPieces()) {
      if ($this->auras[$i] == $cardID) return new AuraCard($i, $this->player);
    }
    return "";
  }
}

class AuraCard {
  // Properties
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetAuras($player);
    $this->index = $index;
		$this->controller = $player;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function Status() {
    return $this->pieces[$this->index+1] ?? 0;
  }

  function SetStatus($status) {
    if (isset($this->pieces[$this->index+1])) $this->pieces[$this->index+1] = $status;
  }

  function NumCounters() {
    return $this->pieces[$this->index+2] ?? 0;
  }

	function AddCounters($n=1) {
		if (isset($this->pieces[$this->index+2])) $this->pieces[$this->index+2] += $n;
		return $this->NumCounters();
	}

  function NumPowerCounters() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function IsToken() {
    return $this->pieces[$this->index+4] ?? 0;
  }

  function NumAbilityUses() {
    return $this->pieces[$this->index+5] ?? 0;
  }


  function UniqueID() {
    return $this->pieces[$this->index+6] ?? "-";
  }

  function MyGemStatus() { //(2 = always hold, 1 = hold, 0 = don't hold)
    return $this->pieces[$this->index+7] ?? 0;
  }

	function TheirGemStatus() {
    return $this->pieces[$this->index+8] ?? 0;
  }

  function ToggleGem($player=0) {
    $offset = ($player == $this->controller || $player == 0) ? 7 : 8;
    if (isset($this->pieces[$this->index+$offset])) {
      $state = $this->pieces[$this->index+$offset]  == "1" ? "0" : "1";
      $this->pieces[$this->index+$offset] = $state;
    }
  }

  function From() {
    return $this->pieces[$this->index+9] ?? "-";
  }

	function Remove($skipTrigger = false, $skipClose = false, $mainPhase = true, $destinationUID = "-") { //don't call this for removing auras in the equipment
		return RemoveAura($this->controller, $this->index, $this->UniqueID(), "AURAS", $skipTrigger, $skipClose, $mainPhase, $destinationUID);
	}

	function Destroy($skipTrigger = false, $skipClose = false, $mainPhase = true) { //don't call this for removing auras in the equipment
    return DestroyAura($this->controller, $this->index, $this->UniqueID(), "AURAS", $skipTrigger, $skipClose, $mainPhase);
	}
}