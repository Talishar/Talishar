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
    if (count($this->items) == 0) return new ItemCard(-1, $this->player);
    for ($i = 0; $i < count($this->items); $i += ItemPieces()) {
      if ($this->items[$i + 4] == $uid) return new ItemCard($i, $this->player);
    }
    return new ItemCard(-1, $this->player);
  }

  function FindCard($id) {
    if (count($this->items) == 0) return new ItemCard(-1, $this->player);
    for ($i = 0; $i < count($this->items); $i += ItemPieces()) {
      if ($this->items[$i] == $id) return new ItemCard($i, $this->player);
    }
    return new ItemCard(-1, $this->player);
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
    if ($index == -1)
      $this->pieces = [];
    else
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

  function AddUses($n=1) {
    if (isset($this->pieces[$this->index + 3]))
      $this->pieces[$this->index + 3] += $n;
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

  function RemoveAllCounters() {
    foreach ([1] as $i) {
      if (isset($this->pieces[$this->index + $i]))
        $this->pieces[$this->index + $i] = 0;
    }
  }

  function Destroy($skipDestroy=false) {
    DestroyItemForPlayer($this->controller, $this->index, $skipDestroy);
  }

  function SubCards() {
    return $this->pieces[$this->index + 11] ?? "-";
  }

  function AddSubcard($cardID) {
    if (!isset($this->pieces[$this->index + 11])) return "";
    elseif ($this->pieces[$this->index + 11] == "-")
      $this->pieces[$this->index + 11] = $cardID;
    else {
      $this->pieces[$this->index + 11] .= ",$cardID";
    }
    return $cardID;
  }

  function SetSubcards($subcards) {
    if (!isset($this->pieces[$this->index + 11])) return "";
    $this->pieces[$this->index+11] = $subcards;
  }

  function NumDefCounters() {
    return $this->pieces[$this->index + 12] ?? 0;
  }

  function AddDefCounters($n) {
    if (isset($this->pieces[$this->index + 12]))
      $this->pieces[$this->index + 12] += $n;
  }

  function OnChain() {
    return $this->pieces[$this->index + 13] ?? 0;
  }

  function ToggleOnChain($val) {
    if (isset($this->pieces[$this->index + 13]))
      $this->pieces[$this->index + 13] = $val;
  }
}