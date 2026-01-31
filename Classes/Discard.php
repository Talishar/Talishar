<?php
// Discard Class to handle interactions involving the discard

class Discard {

  // Properties
  private $discard = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->discard = &GetDiscard($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->discard) == 0;
  }

  function NumCards() {
    $count = 0;
    for ($i=0; $i < count($this->discard); $i += DiscardPieces()) {
      if($this->discard[$i+2] != "DOWN") $count++;
    }
    return $count;
  }

  function RemoveRandom($count=1) {
    $cards = "";
    for($i = 0; $i < $count && !$this->Empty(); $i++) {
      $index = (GetRandom() % $this->NumCards()) * DiscardPieces();
      if($cards != "") $cards .= ",";
      $cards .= $this->discard[$index];
      for ($j = DiscardPieces() - 1; $j >= 0; --$j) {
        unset($this->discard[$index + $j]);
      }
      $this->discard = array_values($this->discard);
    }
    return $cards;
  }

  function Remove($index) {
    if (isset($this->discard[$index])) {
      $cardID = $this->discard[$index];
      for ($i = DiscardPieces() - 1; $i >= 0; --$i) {
        unset($this->discard[$index+$i]);
      }
      $this->discard = array_values($this->discard);
      return $cardID;
    }
    else {
      WriteLog("Something went wrong with removing a card from the graveyard, please submit a bug report");
      return "";
    }
  }

  function Add($cardID, $from="GY", $mods="-") {
    array_push($this->discard, $cardID);
    array_push($this->discard, GetUniqueId());
    array_push($this->discard, "-");
  }

  function TopCard() {
    return count($this->discard) > 0 ? $this->discard[count($this->discard) - DiscardPieces()] : "";
  }

  function FindCardUID($uid) {
    if (count($this->discard) == 0) return "";
    for ($i = 0; $i < count($this->discard); $i += DiscardPieces()) {
      if ($this->discard[$i + 1] == $uid) return new DiscardCard($i, $this->playerID);
    }
    return "";
  }
}

class DiscardCard {
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetDiscard($player);
    $this->index = $index;
		$this->controller = $player;
  }

  function ID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function UniqueID() {
    return $this->pieces[$this->index+1] ?? "-";
  }

  function Index() {
    return $this->index;
  }
}