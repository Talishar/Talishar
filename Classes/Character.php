<?php

class PlayerCharacter {

  // Properties
  private $char = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->char = &GetPlayerCharacter($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CharacterPieces();
    return new CharacterCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->char) == 0) return "";
    for ($i = 0; $i < count($this->char); $i += CharacterPieces()) {
      if ($this->char[$i + 11] == $uid) return new CharacterCard($i, $this->player);
    }
    return "";
  }

  function FindCardID($id) {
    if (count($this->char) == 0) return "";
    for ($i = 0; $i < count($this->char); $i += CharacterPieces()) {
      if ($this->char[$i] == $id) return new CharacterCard($i, $this->player);
    }
    return "";
  }

  function NumCards() {
    return intdiv(count($this->char), CharacterPieces());
  }
}

class CharacterCard {
  // Properties
  private $pieces = [];
  private $index;
  private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetPlayerCharacter($player);
    $this->index = $index;
    $this->controller = $player;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function Become($cardID) {
    global $CS_OriginalHero;
    if ($this->index == 0 && GetClassState($this->controller, $CS_OriginalHero) == "-") {
      SetClassState($this->controller, $CS_OriginalHero, $cardID);
    }
    $this->pieces[$this->index] = $cardID;
  }

  function Status() {
    // (2=ready, 1=unavailable, 0=destroyed, 3=Sleeping (Sleep Dart, Crush Confidance, etc)), 4=Dishonored
    return $this->pieces[$this->index+1] ?? 0;
  }

  function SetUsed($status=1) {
    if (isset($this->pieces[$this->index+1])) $this->pieces[$this->index+1] = $status;
  }

  function NumCounters() {
    return $this->pieces[$this->index+2] ?? 0;
  }

  function NumPowerCounters() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function AddPowerCounters($num) {
    if (isset($this->pieces[$this->index + 3]))
      $this->pieces[$this->index + 3] += $num;
  }

  function NumDefenseCounters() {
    return $this->pieces[$this->index+4] ?? 0;
  }

  function AddDefenseCounters($num) {
    if (isset($this->pieces[$this->index + 4]))
      $this->pieces[$this->index + 4] += $num;
  }

  function NumUses() {
    return $this->pieces[$this->index+5] ?? 0;
  }

  function AddUse($n=1) {
    if (isset($this->pieces[$this->index+5])) $this->pieces[$this->index+5] += $n;
  }

  function OnChain() {
    // 1 = yes, 0 = no
    return $this->pieces[$this->index+6] ?? 0;
  }

  function FlaggedForDestruction() {
    // 1  = yes, 0 = no
    return $this->pieces[$this->index+7] ?? 0;
  }

  function Frozen() {
    // 1 = yes, 0 = no
    return $this->pieces[$this->index+8] ?? 0;
  }

  function IsActive() {
    // the "gem", 2 = always active, 1 = yes, 0 = no
    return $this->pieces[$this->index+9] ?? 0;
  }

  function ToggleGem($player=0) {
    $offset = 9;
    if (isset($this->pieces[$this->index+$offset])) {
      $state = $this->pieces[$this->index+$offset]  == "1" ? "0" : "1";
      $this->pieces[$this->index+$offset] = $state;
    }
  }

  function Subcards() {
    // , delimited
    return $this->pieces[$this->index+10] ?? "-";
  }
  

  function UniqueID() {
    return $this->pieces[$this->index+11] ?? "-";
  }

  function Facing() {
    // UP or DOWN
    return $this->pieces[$this->index+12] ?? "-";
  }

  function Flip($facing) {
    if (isset($this->pieces[$this->index+12]))
      $this->pieces[$this->index+12] = $facing;
  }

  function Marked() {
    //1 = yes, 0 = no
    return $this->pieces[$this->index+13] ?? 0;
  }

  function Tapped() {
    //1 = yes, 0 = no
    return $this->pieces[$this->index+14] ?? 0;
  }

  function Destroy($skipDestroy = false, $wasBanished = false) {
    DestroyCharacter($this->controller, $this->index, $skipDestroy, $wasBanished);
  }
}