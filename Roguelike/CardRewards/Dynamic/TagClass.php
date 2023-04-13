<?php

class Tag {
  public $tag;
  public $Weight;
  public $removed;
  public $unusedPool;
}

class CardReward {
  public $cardID;
  public $tags;
  public $requirement; //This is "-" if it's empty. If it's filled, it's an array with requirements

  function __construct($cardID, $tags, $requirement = "-") {
    $this->cardID = $cardID;
    $this->tags = $tags;
    $this->requirement = $requirement;
  }
}


class InnerTag {
  public $tag;
  public $weight;
  public $ignore;

  function __construct($tag, $weight, $ignore) {
    $this->tag = $tag;
    $this->weight = $weight;
    $this->ignore = $ignore;
  }
}

?>
