<?php

class Tag {
  public $tag;
  public $weight;
  public $removed;
  public $unusedPool;

  function __construct($tag, $unused = false) {
    $this->tag = $tag;
    $this->weight = 0;
    $this->removed = [];
    $this->unused = $unused;
  }
}

class CardTag {
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
