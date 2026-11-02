<?php



class Permanents extends ZoneObjects {

  // Constructor
  function __construct($player) {
    $this->cards = &GetPermanents($player);
    $this->player = $player;
    $this->pieces = PermanentPieces();
    $this->uidOffset = 3;
  }

  protected function NewCard($index) {
    return new PermanentCard($index, $this->player);
  }

  // Methods
  function NumPermanents() {
    return $this->NumCards();
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

  function Destroy() {
    DestroyPermanent($this->controller, $this->index);
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