<?php

abstract class ZoneObjects {

  // Properties
  protected $cards = [];
  protected $player = 0;
  protected $pieces = 1;
  protected $uidOffset = 0;

  abstract protected function NewCard($index);

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index *= $this->pieces;
    return $this->NewCard($index);
  }

  function FindCardUID($uid) {
    return $this->NewCard(ZoneFind($this->cards, $this->pieces, $this->uidOffset, $uid));
  }

  function FindCardID($cardID) { //returns the first card with a cardID
    return $this->NewCard(ZoneFind($this->cards, $this->pieces, 0, $cardID));
  }

  function NumCards() {
    return intdiv(count($this->cards), $this->pieces);
  }
}
