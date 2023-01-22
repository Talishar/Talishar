<?php
// Deck Class to handle interactions involving the deck

class Deck {
  // Properties

  private $deck = [];
  
  // Constructor
  function __construct($playerID) {
    $this->deck = &GetDeck($playerID);
  }

  // Methods
  function RemainingCards() {
    // Code to return the number of remaining cards in the deck
    return count($this->deck);
  }
  
  function Reveal() {
    // Code the reveal x number of cards from the top of the deck
  }

  function Banish() {
    // Code to banish x number of cards from the top of the deck
  }
}

?>