<?php

class Encounter {

  public $encounterID;
  public $subphase;
  public $position;
  public $hero;
  public $adventure;
  public $visited;
  public $majesticCard;
  public $background;
  public $difficulty;
  public $gold;
  public $rerolls;
  public $costToHeal;
  public $costToRemove;

  function __construct() {
    $this->encounterID = 1;
    $this->subphase = "PickMode";
    $this->postion = 0;
    $this->hero = "Dorinthea";
    $this->adventure = "Ira";
    $this->visited = array();
    $this->majesticCard = 1;
    $this->background = "none";
    $this->difficulty = "Normal";
    $this->gold = 0;
    $this->rerolls = 2;
    $this->costToHeal = 1;
    $this->costToRemove = 1;
  }

  public function set_encounterID($value) {
    $this->encounterID = $value;
  }
  public function get_encounterID() {
    return $this->encounterID;
  }

  /*function WriteArray() {
    $returnArray = [];
    array_push($returnArray, "encounterID->".$this->encounterID);
    array_push($returnArray, "subphase->".$this->subphase);
    array_push($returnArray, "position->".$this->position);
    array_push($returnArray, "hero->".$this->hero);
    array_push($returnArray, "visited->".implode(",", $this->visited));
    array_push($returnArray, "majesticCard->".$this->majesticCard);
    array_push($returnArray, "background->".$this->background);
    array_push($returnArray, "difficulty->".$this->difficulty);
    array_push($returnArray, "gold->".$this->gold);
    array_push($returnArray, "rerolls->".$this->rerolls);
    array_push($returnArray, "costToHeal->".$this->costToHeal);
    array_push($returnArray, "costToRemove->".$this->costToRemove);
  }*/
}

 ?>
