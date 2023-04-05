<?php
// Deck Class to handle interactions involving the deck

class Deck {

  // Properties
  private $deck = [];
  private $playerID;

  // Constructor
  function __construct($playerID) {
    $this->deck = &GetDeck($playerID);
    $this->playerID = $playerID;
  }

  // Methods
  function Empty() {
    return count($this->deck) == 0;
  }

  function RemainingCards() {
    // Code to return the number of remaining cards in the deck
    return count($this->deck);
  }

  function Remove($index) {
    $cardID = $this->deck[$index];
    unset($this->deck[$index]);
    $this->deck = array_values($this->deck);
    return $cardID;
  }

  function Reveal($revealCount=1) {
    // Code the reveal x number of cards from the top of the deck
    if (CanRevealCards($this->playerID)) {
      if ($this->RemainingCards() > 0) {
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
  }

  function Top($remove = false, $amount = 1)
  {
    $rv = "";
    for($i=0; $i<$amount; ++$i)
    {
      if($rv != "") $rv .= ",";
      $rv .= ($remove ? array_shift($this->deck) : $this->deck[$i]);
    }
    return $rv;
  }

  function Banish() {
    // Code to banish x number of cards from the top of the deck
  }
}

?>
