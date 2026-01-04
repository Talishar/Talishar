<?php



class Permanents {

  // Properties
  private $permanents = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->permanents = &GetPermanents($player);
    $this->player = $player;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * PermanentPieces();
    return new PermanentCard($index, $this->player);
  }

  function FindCardUID($uid) {
    if (count($this->permanents) == 0) return "";
    for ($i = 0; $i < count($this->permanents); $i += PermanentPieces()) {
      if ($this->permanents[$i + 5] == $uid) return new PermanentCard($i, $this->player);
    }
    return "";
  } 

  function NumPermanents() {
    return intdiv(count($this->permanents), PermanentPieces());
  }
}

class PermanentCard {
  // Properties
  private $pieces = [];
  private $index;
	private $controller;

  // Constructor
  function __construct($index, $player) {
    $this->pieces = &GetPermanents($player);
    $this->index = $index;
		$this->controller = $player;
  }

  function Index() {
    return $this->index;
  }

  function CardID() {
    return $this->pieces[$this->index] ?? "-";
  }

  function From() {
    return $this->pieces[$this->index + 1] ?? "-";
  }

  function Subcards() { // , delimited
    return $this->pieces[$this->index + 2] ?? "-";
  }

  function UniqueID() {
    return $this->pieces[$this->index + 3] ?? "-";
  }
}

function GetPermanent($zone, $index, $player) {
	if (str_contains($zone, "THEIR")) {
		$player = 2 - $player;
		$zone = substr($zone, 5);
	}
  switch ($zone) {
		case "AURAS":
			return new AuraCard($index, $player);
		case "ITEMS":
			return new ItemCard($index, $player);
		case "HERO":
		case "CHAR":
			return new CharacterCard($index, $player);
		case "ALLIES":
			return new AllyCard($index, $player);
		default:
			return new PermanentCard($index, $player);
	}
}