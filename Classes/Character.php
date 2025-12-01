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
}

class CharacterCard {
  // Properties
  private $pieces = [];
  private $index;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetPlayerCharacter($player);
    $this->index = $index;
  }

  function CardID() {
    return $this->pieces[$this->index];
  }

  function Status() {
    // (2=ready, 1=unavailable, 0=destroyed, 3=Sleeping (Sleep Dart, Crush Confidance, etc)), 4=Dishonored
    return $this->pieces[$this->index+1];
  }

  function SetUsed($status=1) {
    $this->pieces[$this->index+1] = $status;
  }

  function NumCounters() {
    return $this->pieces[$this->index+2];
  }

  function NumPowerCounters() {
    return $this->pieces[$this->index+3];
  }

  function NumDefenseCounters() {
    return $this->pieces[$this->index+4];
  }

  function NumUses() {
    return $this->pieces[$this->index+5];
  }

  function AddUse($n=1) {
    $this->pieces[$this->index+5] += $n;
  }

  function OnChain() {
    // 1 = yes, 0 = no
    return $this->pieces[$this->index+6];
  }

  function FlaggedForDestruction() {
    // 1  = yes, 0 = no
    return $this->pieces[$this->index+7];
  }

  function Frozen() {
    // 1 = yes, 0 = no
    return $this->pieces[$this->index+8];
  }

  function IsActive() {
    // the "gem", 2 = always active, 1 = yes, 0 = no
    return $this->pieces[$this->index+9];
  }

  function Subcards() {
    // , delimited
    return $this->pieces[$this->index+10];
  }
  

  function UniqueID() {
    return $this->pieces[$this->index+11];
  }

  function Facing() {
    // UP or DOWN
    return $this->pieces[$this->index+12];
  }

  function Marked() {
    //1 = yes, 0 = no
    return $this->pieces[$this->index+13];
  }

  function Tapped() {
    //1 = yes, 0 = no
    return $this->pieces[$this->index+14];
  }
}