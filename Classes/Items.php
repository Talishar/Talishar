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
  private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->controller = $player;
    $this->pieces = &GetItems($player);
    $this->index = $index;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function NumCounters() {
    return $this->pieces[$this->index+1] ?? 0;
  }

  function AddCounters($num) { //can add negative amounts
    if (isset($this->pieces[$this->index+1])) $this->pieces[$this->index+1] += $num;
  }

  function Status() { //(2=ready, 1=unavailable, 0=destroyed)
    return $this->pieces[$this->index+2] ?? 0;
  }

  function SetStatus($status) {
    if (isset($this->pieces[$this->index+2])) $this->pieces[$this->index+2] = $status;
  }

  function NumUses() {
    return $this->pieces[$this->index+3] ?? 0;
  }

  function UniqueID() {
    return $this->pieces[$this->index+4] ?? "-";
  }

  function MyGemStatus() {
    return $this->pieces[$this->index+5] ?? 0;
  }

  function TheirGemStatus() {
    return $this->pieces[$this->index+6] ?? 0;
  }

  function ToggleGem($player=0) {
    $offset = ($player == $this->controller || $player == 0) ? 5 : 6;
    if (isset($this->pieces[$this->index+$offset])) {
      $state = $this->pieces[$this->index+$offset]  == "1" ? "0" : "1";
      $this->pieces[$this->index+$offset] = $state;
    }
  }

  function Frozen() {
    // 1  = yes, 0 = no
    return $this->pieces[$this->index+7] ?? 0;
  }

  function Modalities() {
    return $this->pieces[$this->index+8] ?? "-";
  }

  function From() {
    return $this->pieces[$this->index+9] ?? "-";
  }

  function Tapped() {
    return $this->pieces[$this->index+10] ?? 0;
  }
}