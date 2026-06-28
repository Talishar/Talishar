<?php

global $Stack;
global $AttackQueue;
$Stack = new Stack();
$AttackQueue = new AttackQueue();

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
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if ($this->layers[$i + 6] == $uid) return new Layer($i);
    }
    return "";
  }

  function FindCardSourceUID($uid) {
    if ($this->StackEmpty()) return new Layer(-1);
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if ($this->layers[$i + 5] == $uid) return new Layer($i);
    }
    return new Layer(-1);
  }

  function FindCardID($cardID) {
    if ($this->StackEmpty()) return new Layer(-1);
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if ($this->layers[$i] == $cardID) return new Layer($i);
    }
    return new Layer(-1);
  }

  function Negate($index, $cardNumber=false) {
    if($cardNumber) $index = $index * LayerPieces();
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
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if ($this->layers[$i] == $cardID) return $this->Card($i);
    }
    return new Layer(-1);
  }

  function FindTrigger($cardID) {
    if ($this->StackEmpty()) return "";
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if (($this->layers[$i] == "TRIGGER" || $this->layers[$i] == "PRETRIGGER") && $this->layers[$i+2] == $cardID) return new Layer($i);
    }
    return "";
  }

  function FindLayer($cardID, $player="-", $parameter="-", $target="-") {
    if ($this->StackEmpty()) return "";
    $rv = [];
    $count = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $count; $i += $layerPieces) {
      if ($this->layers[$i] != $cardID) continue;
      if ($player != "-" && $this->layers[$i + 1] != $player) continue;
      if ($parameter != "-" && $this->layers[$i + 2] != $parameter) continue;
      if ($target != "-" && $this->layers[$i + 3] != $target) continue;
      $rv[] = $i;
    }
    return implode(",", $rv);
  }

  function NumLayers() {
    return intdiv(count($this->layers), LayerPieces());
  }

  function CountPlayedLayers() {
    $count = 0;
    $total = count($this->layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $total; $i += $layerPieces) {
      if (!isPriorityStep($this->layers[$i]) && $this->layers[$i] != "TRIGGER") ++$count;
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
    if ($index != -1)
  		$this->layers = &$layers;
    else
      $this->layers = [];
		$this->index = $index;
	}

	function Index() {
		return $this->index;
	}

	function ID() {
		return $this->layers[$this->index] ?? "";
	}

	function PlayerID() {
		return isset($this->layers[$this->index+1]) ? $this->layers[$this->index+1] : 0;
	}

	function Parameter() {
		return isset($this->layers[$this->index+2]) ? $this->layers[$this->index+2] : "-";
	}

  function DynCost() {
    return explode("|", $this->Parameter(), 2)[1] ?? 0;
  }

	function Target() {
		return isset($this->layers[$this->index+3]) ? $this->layers[$this->index+3] : "-";
	}

  function AddTarget($target) {
    if ($this->Target() == "-") {
      $this->layers[$this->index + 3] = $target;
    }
    else {// already has a target, add another one
      $this->layers[$this->index + 3] .= ",$target";
    }
    return $this->layers[$this->index + 3];
  }

	function AdditionalCosts() {
		return isset($this->layers[$this->index+4]) ? $this->layers[$this->index+4] : "-";
	}

  function SetAdditionalCosts($costs) {
    $this->layers[$this->index+4] = $costs;
  }

	function UniqueID() { //(the unique ID of the object that created the layer)
		return $this->layers[$this->index+5] ?? "-";
	}

	function LayerUniqueID() { //(the unique ID of the layer)
		return $this->layers[$this->index+6] ?? "-";
	}

  function Negate($goesWhere="-") {
    if ($this->index != -1 && isset($this->layers[$this->index])) {
      $cardID = $this->layers[$this->index];
      $player = $this->layers[$this->index + 1];
      $otherPlayer = 3 - $player;
      $layerPieces = LayerPieces();
      for ($i = $this->index + $layerPieces - 1; $i >= $this->index; --$i) {
        unset($this->layers[$i]);
      }
      $this->layers = array_values($this->layers);
      if ($goesWhere != "-") {
        ResolveGoesWhere($goesWhere, $cardID, $player, "LAYER", $otherPlayer);
      }
    }
	}

  function IsCardLayer() {
    if (isAdministrativeStep($this->ID())) return false;
    static $nonCards = ["TRIGGER" => 1, "PRETRIGGER" => 1, "ABILITY" => 1];
    if (isset($nonCards[$this->ID()])) return false;
    $from = explode("|", $this->Parameter())[0];
    if ($from == "PLAY" || $from == "EQUIP") return false;
    return $this->ID() != "";
  }
}

class AttackQueue {
  private $attackQueue = [];

  function __construct() {
    global $attackQueue;
    $this->attackQueue = &$attackQueue;
  }

  function Card($index, $cardNumber=false) {
    if($cardNumber) $index = $index * AttackQueuePieces();
    return new AttackLayer($index);
  }

  function FindCardUID($uid) {
    if ($this->NumAttacks() == 0) return "";
    $count = count($this->attackQueue);
    $attackQueuePieces = AttackQueuePieces();
    for ($i = 0; $i < $count; $i += $attackQueuePieces) {
      if ($this->attackQueue[$i + 6] == $uid) return new AttackLayer($i);
    }
    return "";
  }

  function FindCardID($cardID) {
    if ($this->NumAttacks() == 0) return "";
    $count = count($this->attackQueue);
    $attackQueuePieces = AttackQueuePieces();
    for ($i = 0; $i < $count; $i += $attackQueuePieces) {
      if ($this->attackQueue[$i] == $cardID) return new AttackLayer($i);
    }
    return "";
  }

  function NumAttacks() {
    return intdiv(count($this->attackQueue), AttackQueuePieces());
  }
}

class AttackLayer {
	// Properties
	private $attackQueue = [];
	private $index;

	// Constructor
	function __construct($index) {
		global $attackQueue;
    if ($index != -1)
  		$this->attackQueue = &$attackQueue;
    else
      $this->attackQueue = [];
		$this->index = $index;
	}

	function Index() {
		return $this->index;
	}

	function ID() {
		if(count($this->attackQueue) == 0) return "";
		return $this->attackQueue[$this->index];
	}

	function PlayerID() {
		return isset($this->attackQueue[$this->index+1]) ? $this->attackQueue[$this->index+1] : 0;
	}

	function Parameter() {
		return isset($this->attackQueue[$this->index+2]) ? $this->attackQueue[$this->index+2] : "-";
	}

  function DynCost() {
    return explode("|", $this->Parameter(), 2)[1] ?? 0;
  }

	function Target() {
		return isset($this->layers[$this->index+3]) ? $this->attackQueue[$this->index+3] : "-";
	}

  function QueuedBuffs() {
    return $this->attackQueue[$this->index + 7] ?? "-";
  }

  function AddBuff($buff) {
    if (isset($this->attackQueue[$this->index + 7])) {
      if ($this->attackQueue[$this->index + 7] == "-")
        $this->attackQueue[$this->index + 7] = $buff;
      else
        $this->attackQueue[$this->index + 7] .= ",$buff";
    }
  }
}