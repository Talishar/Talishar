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
}