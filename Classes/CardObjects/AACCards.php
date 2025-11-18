<?php

class meet_madness_red extends Card {
  function __construct($controller) {
    $this->cardID = "meet_madness_red";
    $this->controller = $controller;
	}

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
		global $mainPlayer;
    if(IsHeroAttackTarget()) {
			if (!$check) {
				$roll = GetRandom(1,3);
				switch ($roll) {
					case 1:
						WriteLog("<b>The madness says \"Banish a card from hand!\"</b>");
						break;
					case 2:
						WriteLog("<b>The madness says \"banish a card from arsenal!\"</b>");
						break;
					case 3:
						WriteLog("<b>The madness says \"banish a card from the top of your deck!\"</b>");
						break;
					default:
						break;
				}
				AddLayer("TRIGGER", $mainPlayer, $this->cardID, $roll, "ONHITEFFECT");
			}
			return true;
		}
  }

	function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
		global $defPlayer;
		$roll = $target;
		switch ($roll) {
			case 1:
				AddDecisionQueue("FINDINDICES", $defPlayer, "HAND");
				AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to banish", 1);
				AddDecisionQueue("CHOOSEHAND", $defPlayer, "<-", 1);
				AddDecisionQueue("MULTIREMOVEHAND", $defPlayer, "-", 1);
				//including $cardID as the third param makes it count for contracts
				AddDecisionQueue("BANISHCARD", $defPlayer, "THEIRHAND,-,$cardID", 1);
				break;
			case 2:
				//including $cardID as the third param makes it count for contracts
				MZMoveCard($defPlayer, "MYARS", "MYBANISH,ARS,$cardID," . $defPlayer, false);
				break;
			case 3:
				$deck = new Deck($defPlayer);
				if($deck->Empty()) { WriteLog("The opponent deck is already... depleted."); break; }
				$deck->BanishTop(banishedBy:$cardID);
				break;
		}
	}

	function HasStealth() {
		return true;
	}
}

class rage_baiters extends Card {
	function __construct($controller) {
    $this->cardID = "rage_baiters";
    $this->controller = $controller;
	}

	
}