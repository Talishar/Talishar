<?php

class Arsenal {

  // Properties
  private $arsenal = [];
  private $player;

  // Constructor
  function __construct($playerID) {
    $this->arsenal = &GetArsenal($playerID);
    $this->player = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->arsenal) == 0;
  }

	function NumCards() {
		return intdiv(count($this->arsenal), ArsenalPieces());
	}

	function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * ArsenalPieces();
    return new ArsenalCard($index, $this->player);
  }

	function DestroyAll($effectController=0) {
		$cardIDs = [];
		for ($i = $this->NumCards() - 1; $i >= 0; --$i) {
			$Card = $this->Card($i, true);
			array_push($cardIDs, $Card->CardID());
			$Card->Destroy($effectController);
		}
		$this->arsenal = [];
		return implode(",", $cardIDs);
	}
}

class ArsenalCard {
	private $pieces = [];
  private $controller;
	private $index;

  // Constructor
  function __construct($index, $playerID) {
    $this->pieces = &GetArsenal($playerID);
    $this->controller = $playerID;
		$this->index = $index;
  }

	function CardID() {
		return $this->pieces[$this->index] ?? "-";
	}

	function Facing() {
		return $this->pieces[$this->index + 1] ?? "UP";
	}

	// Nothing for offset of 2 for some reason

	function Counters() {
		return $this->pieces[$this->index + 2] ?? 0;
	}

	function Frozen() {
		return $this->pieces[$this->index + 3] ?? 0;
	}

	function UniqueID() {
		return $this->pieces[$this->index + 5] ?? "-";
	}

	function NumPowerCounters() {
		return $this->pieces[$this->index + 6] ?? 0;
	}

	function Remove() {
		$cardID = $this->CardID();
		for ($i = $this->index + ArsenalPieces() - 1; $i >= $this->index; --$i) {
			unset($this->pieces[$i]);
		}
		$this->pieces = array_values($this->pieces);
		return $cardID;
	}

	function Destroy($effectController=0) {
		WriteLog(CardLink($this->CardID(), $this->CardID()) . " was destroyed from the arsenal");
		AddGraveyard($this->CardID(), $this->controller, "ARS", $effectController);
		$this->Remove();
	}
}