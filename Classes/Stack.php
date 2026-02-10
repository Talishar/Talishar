<?php

global $Stack;
$Stack = new Stack();

class Stack {

  // Properties
  private $layers = [];

  // Constructor
  function __construct() {
    global $layers;
    $this->layers = &$layers;
  }

  // Methods
  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * LayerPieces();
    return new Layer($index);
  }

  function FindCardUID($uid) {
    if ($this->StackEmpty()) return "";
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if ($this->layers[$i + 6] == $uid) return new Layer($i);
    }
    return "";
  }

  function FindCardID($cardID) {
    if ($this->StackEmpty()) return "";
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if ($this->layers[$i] == $cardID) return new Layer($i);
    }
    return "";
  }

  function Negate($index, $cardNumber=false) {
    if($cardNumber) $index = $index * CombatChainPieces();
    if($index < 0 || $index >= count($this->layers)) return "";
    $cardID = $this->layers[$index];
    NegateLayer("LAYERS-$index");
    return $cardID;
  }

  function StackEmpty() {
    return count($this->layers) == 0;
  }

  function BottomLayer($i=0) {
    $index = count($this->layers) - LayerPieces() * ($i + 1);
    return $this->Card($index);
  }

  function TopLayer($cardID="-") {
    if ($cardID == "-") return $this->Card(0);
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if ($this->layers[$i] == $cardID) return $this->Card($i);
    }
    return "";
  }

  function FindTrigger($cardID) {
    if ($this->StackEmpty()) return "";
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if ($this->layers[$i] == "TRIGGER" && $this->layers[$i+2] == $cardID) return new Layer($i);
    }
    return "";
  }

  function FindLayer($cardID, $player="-", $parameter="-", $target="-") {
    if ($this->StackEmpty()) return "";
    $rv = [];
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if ($this->layers[$i] != $cardID) continue;
      if ($player != "-" && $this->layers[$i + 1] != $player) continue;
      if ($parameter != "-" && $this->layers[$i + 2] != $parameter) continue;
      if ($target != "-" && $this->layers[$i + 3] != $target) continue;
      array_push($rv, $i);
    }
    return implode(",", $rv);
  }

  function NumLayers() {
    return intdiv(count($this->layers), LayerPieces());
  }

  function CountTrueLayers() {
    $count = 0;
    for ($i = 0; $i < count($this->layers); $i += LayerPieces()) {
      if (!isPriorityStep($this->layers[$i])) ++$count;
    }
    return $count;
  }
}

class Layer {
	// Properties
	private $layers = [];
	private $index;

	// Constructor
	function __construct($index) {
		global $layers;
		$this->layers = &$layers;
		$this->index = $index;
	}

	function Index() {
		return $this->index;
	}

	function ID() {
		if(count($this->layers) == 0) return "";
		return $this->layers[$this->index];
	}

	function PlayerID() {
		return isset($this->layers[$this->index+1]) ? $this->layers[$this->index+1] : 0;
	}

	function Parameter() {
		return isset($this->layers[$this->index+2]) ? $this->layers[$this->index+2] : "-";
	}

	function Target() {
		return isset($this->layers[$this->index+3]) ? $this->layers[$this->index+3] : "";
	}

	function AdditionalCosts() {
		return isset($this->layers[$this->index+4]) ? $this->layers[$this->index+4] : "-";
	}

	function UniqueID() { //(the unique ID of the object that created the layer)
		return $this->layers[$this->index+5] ?? "-";
	}

	function LayerUniqueID() { //(the unique ID of the layer)
		return isset($this->chalayersin[$this->index+6]) ? $this->layers[$this->index+7] : "-";
	}

  function Negate($goesWhere="-") {
		$cardID = $this->layers[$this->index];
		$player = $this->layers[$this->index + 1];
		$otherPlayer = $player == 1 ? 2 : 1;
		for ($i = $this->index + LayerPieces() - 1; $i >= $this->index; --$i) {
			unset($this->layers[$i]);
		}
		$this->layers = array_values($this->layers);
		if ($goesWhere != "-") {
			ResolveGoesWhere($goesWhere, $cardID, $player, "LAYER", $otherPlayer);
		}
	}
}