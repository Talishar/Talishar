<?php

class Encounter { //There are a few things in this file that I will be using to add a tag system later down the line. Ignore them for now, it'll happen eventually

  public $encounterID; //current encounter ID (ex. 001, 211, 114)
  public $subphase; //current encounter subphase (ex. BeforeFight, PickMode)
  public $position; //Position in the encounter that increments every time you go to a new encounter
  public $hero; //Current hero (ex. Dorinthea, Bravo)
  public $adventure; //current adventure (ex. Ira)
  public $visited; //an array of visited encounters (this is only used for encounters that can only be visited once)
  public $majesticCard; //the majestic card chance. This means that majestic cards will show up *eventually*
  public $background; //current background selected. This will likely eventually be unneeded when dynamic card rewards are implemented
  public $difficulty; //current difficulty
  public $gold; //current gold total
  public $rerolls; //current amount of rerolls
  public $costToHeal; //current cost to heal
  public $costToRemove; //current cost to remove
  public $tags; //a list of tags that is currently unused but will be critical to dynamic card rewards
  public $cleanse; //used for the ClearPool event

  function __construct() {
    $this->encounterID = 1;
    $this->subphase = "PickMode";
    $this->position = 0;
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
    $this->tags = array();
    $this->cleanse = false;
  }

  /*public function AddCardTags($cardID) {
    $cardTags = GetTags($cardID);
    for($i = 0; $i < count($cardTags); ++$i)
    {
      $contains = $this->ContainsTag($cardTags[$i]->tag);
      if($contains = "-" && $cardTags[$i]->create == true) $this->AddTag($cardTags[$i]);
      else if($contains != "-") $this->IncrementTag($cardTags[$i]->weight, $i);
    }
  }

  public function ContainsTag($tagCheck) {
    for($i = 0; $i < count($this->tags); ++$i)
    {
      if($this->tags[$i] == $tagCheck) return $i;
    }
    return "-";
  }

  public function AddTag($addedTag) {
    $index = count($this->tags);
    array_push($this->tags, $addedTag);
    $deck = &GetZone($player, "Deck");
    for($i = 0; $i < count($deck); ++$i)
    {
      $specificWeight = GetTagWeight($deck[$i], $addedTag->tag);
      if($specificWeight != "-") $this->IncrementTag($specificWeight, $index);
    }
  }

  public function IncrementTag($weight, $index) {
    $tags[$index]->weight += $weight;
  }

  public function GetCardPool($type) {
      switch($type)
      {
        case "HighSynergy":
        case "MidSynergy":
        case "LowSynergy":
        case "NoSynergy":
      }
  }*/

}

/*class Tag {
  public $tag;
  public $weight;
  public $create;

  function __construct($tag = "NULL", $weight = 0) {
    $this->tag = $tag;
    $this->weight = $weight;
  }
}*/

 ?>
