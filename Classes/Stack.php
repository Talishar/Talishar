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
      return isset($this->layers[$this->index+1]) ? $this->layers[$this->index+2] : "-";
    }

    function Target() {
      return isset($this->layers[$this->index+3]) ? $this->layers[$this->index+3] : "";
    }

    function AdditionalCosts() {
      return isset($this->layers[$this->index+4]) ? $this->layers[$this->index+4] : "-";
    }

    function UniqueID() { //(the unique ID of the object that created the layer)
      return isset($this->chalayersin[$this->index+5]) ? $this->layers[$this->index+5] : "-";
    }

    function LayerUniqueID() { //(the unique ID of the layer)
      return isset($this->chalayersin[$this->index+6]) ? $this->layers[$this->index+7] : "-";
    }

    function NegateMe() {
      NegateLayer("LAYERS-" . $this->index);
    }
}