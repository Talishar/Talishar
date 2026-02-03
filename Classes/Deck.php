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

  function Remove($indices) {
    $indexArr = explode(",", $indices);
    $cardIDs = "";
    for($i=count($indexArr)-1; $i>= 0; --$i) {
      if (isset($this->deck[$indexArr[$i]])) {
        if($cardIDs != "") $cardIDs .= ",";
        $cardIDs .= $this->deck[$indexArr[$i]];
        unset($this->deck[$indexArr[$i]]);
        $this->deck = array_values($this->deck);
      }
      else WriteLog("Something went wrong with removing a card from deck, please submit a bug report");
    }
    return $cardIDs;
  }

  function Reveal($revealCount=1, $switched=false) {
    // Code the reveal x number of cards from the top of the deck
    if(CanRevealCards($this->playerID)) {
      if($this->RemainingCards() > 0) {
        $otherPlayer = $this->playerID == 1 ? 2 : 1;
        for($revealedCards = 0; $revealedCards < $revealCount && count($this->deck) > $revealedCards; $revealedCards++) {
          if (!$switched) WriteLog("👁️‍🗨️Player " . $this->playerID . " reveals " . CardLink($this->deck[$revealedCards], $this->deck[$revealedCards]));
          else WriteLog("👁️‍🗨️Player " . $otherPlayer . " reveals " . CardLink($this->deck[$revealedCards] , $this->deck[$revealedCards]) . " from their opponent's deck!");
          AddEvent("REVEAL", $this->deck[$revealedCards]);
        }
        if(SearchLandmark("korshem_crossroad_of_elements")) KorshemRevealAbility($this->playerID);
        return true;
      } else {
        WriteLog("Your deck is empty. Nothing was revealed.");
        return false;
      }
    }
    return false;
  }

  function Top($remove = false, $amount = 1)
  {
    $rv = "";
    for($i=0; $i<$amount && count($this->deck) > ($remove ? 0 : $i); ++$i)
    {
      if($rv != "") $rv .= ",";
      $rv .= ($remove ? array_shift($this->deck) : $this->deck[$i]);
    }
    return $rv;
  }

  function BanishTop($modifier = "-", $banishedBy = "", $amount=1) {
    global $currentPlayer;
    if($this->Empty()) return "";
    if($banishedBy == "") $banishedBy = $currentPlayer;
    for($i=0; $i<$amount; ++$i)
    {
      $cardID = $this->Remove(0);
      $cardType = CardType($cardID);
      if($modifier == "TCC" && $cardType != "AR" && $cardType != "I" && $cardType != "AA" && !CanPlayAsInstant($cardID)) $modifier = "-";
      WriteLog(CardLink($cardID, $cardID). " was banished.");
      BanishCardForPlayer($cardID, $this->playerID, "DECK", $modifier, $banishedBy);
    }
    return $cardID;
  }

  function DestroyTop($amount=1) {
    if($this->Empty()) return "";
    for($i=0; $i<$amount; ++$i)
    {
      $cardID = $this->Remove(0);
      DestroyTopCard($this->playerID);
    }
    return $cardID;
  }

  function AddTop($cardID, $from="GY", $deckIndexModifier=0)
  {
    if ($deckIndexModifier > 0) {
      array_splice($this->deck, $deckIndexModifier, 0, $cardID);
    }
    else {
      if (SearchCurrentTurnEffects("topsy_turvy", 1) || SearchCurrentTurnEffects("topsy_turvy", 2)) {
        WriteLog("Things are " . CardLink("topsy_turvy") . " so the card goes to the bottom instead!");
        $this->AddBottom($cardID);
      }
      else array_unshift($this->deck, $cardID);
    }
    return $cardID;
  }

  function AddBottom($cardID, $from="GY")
  {
    array_push($this->deck, $cardID);
    return $cardID;
  }

  function Opt($topCardID, $bottomCardID, $from="GY")
  {
    $validation = array_merge($topCardID, array_merge($this->deck, $bottomCardID));
    $valCounts = array_count_values($validation);
    $valid = true;
    foreach($valCounts as $key => $value) {
      if ($value >= 4 && $key != "copper_cog_blue") {
        WriteLog("A card may have been duplicated! Please report a bug, then post the id on the Talishar discord so I can know if opting was the cause", highlight: true);
        $valid = false;
      }
    }
    for($i = count($topCardID)-1; $i >= 0; --$i) $this->AddTop($topCardID[$i]);
    foreach($bottomCardID as $cardID) $this->AddBottom($cardID);
    return true;
  }

  function GetCard($index) {
    return $this->deck[$index];
  }
}
