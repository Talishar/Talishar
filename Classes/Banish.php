<?php

class Banish {

  // Properties
  private $banish = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->banish = &GetBanish($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->banish) == 0;
  }

  function NumCards() {
    return count($this->banish) / BanishPieces();
  }

  function Card($index)
  {
    return new BanishCard($this->playerID, $index);
  }

  function FirstCardWithModifier($modifier)
  {
    $index = -1;
    for($i=0; $i<count($this->banish); $i+=BanishPieces()) {
      if($this->banish[$i+1] == $modifier) $index = $i;
    }
    if($index == -1) return null;
    return new BanishCard($this->playerID, $index);
  }

  function Remove($index) {
    $cardID = $this->banish[$index];
    for($i=0; $i<BanishPieces(); ++$i) unset($this->banish[$index+$i]);
    $this->banish = array_values($this->banish);
    return $cardID;
  }

  function UnsetBanishModifier($modifier, $newMod="-") {
    for($i=0; $i<count($this->banish); $i+=BanishPieces()) {
      $modArr = explode("-", $this->banish[$i+1]);
      $cardModifier = $modArr[0];
      if($cardModifier == $modifier) $this->banish[$i+1] = $newMod;
      if($cardModifier == "Source" && $modifier == "TCL") $this->banish[$i+1] = $newMod;
    }
  }
}

class BanishCard {
    // Properties
    private $banish = [];
    private $index;

    // Constructor
    function __construct($playerID, $index) {
      $this->banish = &GetBanish($playerID);
      $this->index = $index;
    }

    function Index()
    {
      return $this->index;
    }

    function ID()
    {
      return $this->banish[$this->index];
    }

    function Modifier()
    {
      return $this->banish[$this->index+1];
    }

    function UniqueID()
    {
      return $this->banish[$this->index+2];
    }

    function SetModifier($newModifier)
    {
      $this->banish[$this->index+1] = $newModifier;
    }

    function ClearModifier()
    {
      $this->banish[$this->index+1] = "-";
    }

    function Remove()
    {
      $cardID = $this->banish[$this->index];
      for($i=0; $i<BanishPieces(); ++$i) unset($this->banish[$this->index+$i]);
      $this->banish = array_values($this->banish);
      return $cardID;
    }
}

?>
