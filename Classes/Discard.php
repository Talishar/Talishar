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
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * DiscardPieces();
    return new DiscardCard($index, $this->playerID);
  }

  function Empty() {
    return count($this->discard) == 0;
  }

  function NumCards() {
    $count = 0;
    $total = count($this->discard);
    $discardPieces = DiscardPieces();
    for ($i = 0; $i < $total; $i += $discardPieces) {
      if($this->discard[$i+2] != "DOWN") $count++;
    }
    return $count;
  }

  function NumTotalCards() {
    return intdiv(count($this->discard), DiscardPieces());
  }

  function TotalCards() { //includes facedown cards in the count
    return count($this->discard);
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
    return RemoveGraveyard($this->playerID, $index);
  }

  function RemoveTop() {
    $this->Remove(count($this->discard) - DiscardPieces());
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
    $count = count($this->discard);
    if ($count == 0) return new DiscardCard(-1, $this->playerID);
    $discardPieces = DiscardPieces();
    for ($i = 0; $i < $count; $i += $discardPieces) {
      if ($this->discard[$i + 1] == $uid) return new DiscardCard($i, $this->playerID);
    }
    return new DiscardCard(-1, $this->playerID);
  }
}

class DiscardCard {
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->index = $index;
		$this->controller = $player;
    if ($index != -1)
      $this->pieces = &GetDiscard($player);
    else
      $this->pieces = [];
  }

  function Owner() {
    return $this->controller;
  }

  //alias for ID
  function CardID() {
    return $this->ID();
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

  function Remove() {
    RemoveDiscard($this->controller, $this->Index());
  }

  function Flip($facing="DOWN") {
    if (isset($this->pieces[$this->index + 2]))
      $this->pieces[$this->index + 2] = $facing;
  }

  function Facing() {
    return $this->pieces[$this->index + 2] ?? "UP";
  }

  function Banish($mod="-", $banishedBy="", $banisher="-") {
    BanishCardForPlayer($this->ID(), $this->controller, "DISCARD", $mod, $banishedBy, $banisher);
    RemoveDiscard($this->controller, $this->index);
  }
}