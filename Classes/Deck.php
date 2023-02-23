<?php
// Deck Class to handle interactions involving the deck

class Deck {
<<<<<<< HEAD
  
  // Properties
  private $deck = [];
  private $playerID;
  
=======

  // Properties
  private $deck = [];
  private $playerID;

>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
  // Constructor
  function __construct($playerID) {
    $this->deck = &GetDeck($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function RemainingCards() {
    // Code to return the number of remaining cards in the deck
    return count($this->deck);
  }
<<<<<<< HEAD
  
  function Reveal($revealCount) {
    // Code the reveal x number of cards from the top of the deck
    if (CanRevealCards($this->playerID)) {
      if (RemainingCards() > 0) {
=======

  function Reveal($revealCount=1) {
    // Code the reveal x number of cards from the top of the deck
    if (CanRevealCards($this->playerID)) {
      if ($this->RemainingCards() > 0) {
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
        for ($revealedCards = 0; $revealedCards < $revealCount ; $revealedCards++) {
          WriteLog("Reveals " . CardLink($this->deck[$revealedCards], $this->deck[$revealedCards]));
          AddEvent("REVEAL", $this->deck[$revealedCards]);
        }
        if(SearchLandmark("ELE000")) KorshemRevealAbility($this->playerID);
        return true;
      } else {
        WriteLog("Your deck is empty. Nothing was revealed.");
        return false;
      }
    }
<<<<<<< HEAD
=======
  }

  function Top($remove = false)
  {
    if(count($this->deck) == 0) return "";
    return ($remove ? array_shift($this->deck) : $this->deck[0]);
>>>>>>> 1ef0ba3a750457c881a809d2569d3200f0cb5504
  }

  function Banish() {
    // Code to banish x number of cards from the top of the deck
  }
}

?>
