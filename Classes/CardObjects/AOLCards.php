<?php

class odds_on_favorite_blue extends Card {
  function __construct($controller) {
    $this->cardID = "odds_on_favorite_blue";
    $this->controller = $controller;
  }
  
	function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function OnAttackEffect($cardID, $i) {
		global $CombatChain;
    $Effect = new CurrentEffect($i);
    if (SubtypeContains($CombatChain->AttackCard()->ID(), "Sword"))
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    return false;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddOnWagerEffects();
  }

	function WonWager($wonWager, $amount) {
		Await($wonWager, "MultiZoneIndices", "indices", search:"MYDECK", subsequent:0);
		Await($wonWager, "ChooseMultiZone", "MZIndex", context:"Choose a card to put on top");
		Await($wonWager, "MZRemove", "cardID");
		Await($wonWager, "ShuffleDeck");
		Await($wonWager, "AddTopDeck", from:"DECK", final:true);
	}
}