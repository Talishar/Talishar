<?php

class Tag {
  public $name;
  public $inWeight;
  public $outWeight;
  public $ignore; //This is set to true if this tag only increments and does not create. If false, then it will both create and increment. Only used in the channel path.
  public $unused; //This is set to true if this tag is not used in pool creation. Only used in the monitor path.

  /*function __construct($tag = "NULL", $weight = 0) {
    $this->tag = $tag;
    $this->weight = $weight;
  }*/
}

class CardReward {
  public $cardID;
  public $tags; //Array of tag classes
  public $requirement; //This is "-" if it's empty. If it's filled, it's an array with requirements
}

?>
