<?php

class Hand {

  // Properties
  private $hand = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->hand = &GetHand($playerID);
    $this->playerID = $playerID;
  }

  function NumCards() {
    return intdiv(count($this->hand), HandPieces());
  }

  function Remove($index) {
    return RemoveHand($this->playerID, $index);
  }
}

class PitchZone {
  private $pitch = [];
  private $player;

  // Constructor
  function __construct($playerID) {
    $this->pitch = &GetPitch($playerID);
    $this->player = $playerID;
  }

  function NumCards() {
    return intdiv(count($this->pitch), PitchPieces());
  }

  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * PitchPieces();
    return new PitchCard($index, $this->player);
  }

  function FindCardUID($uid) {
    $count = count($this->pitch);
    if ($count == 0) return new PitchCard(-1, $this->player);
    $pitchPieces = PitchPieces();
    for ($i = 0; $i < $count; $i += $pitchPieces) {
      if ($this->pitch[$i + 1] == $uid) return new PitchCard($i, $this->player);
    }
    return new PitchCard(-1, $this->player);
  }
}

class PitchCard {
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->index = $index;
		$this->controller = $player;
    if ($index != -1)
      $this->pieces = &GetPitch($player);
    else
      $this->pieces = [];
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function UniqueID() {
    return $this->pieces[$this->index+1] ?? "-";
  }

  function Remove() {
    if ($this->index == -1) return;
    $cardID = $this->CardID();
    for ($i = $this->index + PitchPieces() - 1; $i >= $this->index; --$i)
      unset($this->pieces[$i]);
    $this->pieces = array_values($this->pieces);
    return $cardID;
  }
}