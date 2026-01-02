<?php

class Items {

  // Properties
  private $items = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->items = &GetItems($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * ItemPieces();
    return new ItemCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->items) == 0) return "";
    for ($i = 0; $i < count($this->items); $i += ItemPieces()) {
      if ($this->items[$i + 4] == $uid) return new ItemCard($i, $this->player);
    }
    return "";
  } 

  function NumItems() {
    return intdiv(count($this->items), ItemPieces());
  }
}

class ItemCard {
  // Properties
  private $pieces = [];
  private $index;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetItems($player);
    $this->index = $index;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index];
  }

  function NumCounters() {
    return $this->pieces[$this->index+1];
  }

  function AddCounters($num) { //can add negative amounts
    $this->pieces[$this->index+1] += $num;
  }

  function Status() { //(2=ready, 1=unavailable, 0=destroyed)
    return $this->pieces[$this->index+2];
  }

  function SetStatus($status) {
    $this->pieces[$this->index+2] = $status;
  }

  function NumUses() {
    return $this->pieces[$this->index+3];
  }

  function UniqueID() {
    return $this->pieces[$this->index+4];
  }

  function MyGemStatus() {
    return $this->pieces[$this->index+5];
  }


  function TheirGemStatus() {
    return $this->pieces[$this->index+6];
  }

  function Frozen() {
    // 1  = yes, 0 = no
    return $this->pieces[$this->index+7];
  }

  function Modalities() {
    // 1 = yes, 0 = no
    return $this->pieces[$this->index+8];
  }

  function From() {
    // the "gem", 2 = always active, 1 = yes, 0 = no
    return $this->pieces[$this->index+9];
  }

  function Tapped() {
    // , delimited
    return $this->pieces[$this->index+10];
  }
}