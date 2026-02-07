<?php

class Allies {

  // Properties
  private $allies = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->allies = &GetAllies($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * AllyPieces();
    return new AllyCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->allies) == 0) return "";
    for ($i = 0; $i < count($this->allies); $i += AllyPieces()) {
      if ($this->allies[$i + 5] == $uid) return new AllyCard($i, $this->player);
    }
    return "";
  } 

  function NumAllies() {
    return intdiv(count($this->allies), AllyPieces());
  }
}

class AllyCard {
  // Properties
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetAllies($player);
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

  function Life() {
    return $this->pieces[$this->index+2] ?? 0;
  }

  function Damage($damage, $type="DAMAGE") {
    if (isset($this->pieces[$this->index+2]))
      return DamageAlly($this->controller, $this->index, $damage, $type);
    else return 0;
  }

  function Frozen() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function Subcards() { // , delimited
    return $this->pieces[$this->index+4] ?? "-";
  }

  function UniqueID() {
    return $this->pieces[$this->index+5] ?? "-";
  }


  function EnduranceCounters() {
    return $this->pieces[$this->index+6] ?? 0;
  }

  function LifeCounters() {
    return $this->pieces[$this->index+7] ?? 0;
  }

	function NumUses() {
    return $this->pieces[$this->index+8] ?? 0;
  }

  function PowerCounters() {
    return $this->pieces[$this->index+9] ?? 0;
  }

  function AddPowerCounters($num=1) {
    if (isset($this->pieces[$this->index+9]))
      $this->pieces[$this->index+9] += $num;
  }

	function DamageDealtToOpponent() {
    return $this->pieces[$this->index+10] ?? 0;
  }

	function Tapped() { //(0 = no, 1 = yes)
    return $this->pieces[$this->index+11] ?? 0;
  }

	function SteamCounters() {
    return $this->pieces[$this->index+12] ?? 0;
  }

	function From() {
    return $this->pieces[$this->index+13] ?? "-";
  }

	function Modifier() { // e.g "Temporary" for cards that get stolen for a turn.
    return $this->pieces[$this->index+14] ?? "-";
  }

  function Destroy($skipDestroy = false, $fromCombat = false, $uniqueID = "", $toBanished = false) {
    DestroyAlly($this->controller, $this->index, $skipDestroy, $fromCombat, $uniqueID, $toBanished);
  }
}