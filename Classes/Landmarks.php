<?php
global $Landmarks;
$Landmarks = new Landmarks();

class Landmarks {

  // Properties
  private $landmarks = [];

  // Constructor
  function __construct() {
    global $landmarks;
    $this->landmarks = &$landmarks;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * LandmarkPieces();
    return new LandmarkCard($index);
  }

  function NumLandmarks() {
    return intdiv(count($this->landmarks), LandmarkPieces());
  }
}

class LandmarkCard {
  // Properties
  private $pieces = [];
  private $index;

  // Constructor
  function __construct($index) {
    global $landmarks;
		$this->pieces = $landmarks;
    $this->index = $index;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function Player() {
    return $this->pieces[$this->index + 1] ?? 0;
  }

  function From() {
    return $this->pieces[$this->index + 2] ?? "-";
  }

  function Counters() {
    return $this->pieces[$this->index + 3] ?? 0;
  }
}