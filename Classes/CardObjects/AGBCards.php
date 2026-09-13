<?php

// class breakwater_undertow extends Card {

//   function __construct($controller) {
//     $this->cardID = "breakwater_undertow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class graven_justaucorpse extends Card {

//   function __construct($controller) {
//     $this->cardID = "graven_justaucorpse";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class loot_the_arsenal_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "loot_the_arsenal_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class loot_the_hold_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "loot_the_hold_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class tricorn_of_saltwater_death extends Card {

  function __construct($controller) {
    $this->cardID = "tricorn_of_saltwater_death";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

	function OnBlockResolveEffects($blockedFromHand, $i, $start) {
		AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
	}

	function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
		$inds = SearchHand($this->controller, hasWateryGrave: true);
		if ($inds != "") {
			$indices = [];
			foreach (explode(",", $inds) as $ind)
				$indices[] = "MYHAND-$ind";
			$indices = implode(",",  $indices);
			Await($this->controller, "ChooseMultiZone", "choice", indices: $indices, may:true, context: "Discard a card with watery grave (or pass)");
			Await($this->controller, $this->cardID, final:true);
		}
	}

	function SpecificLogic() {
		global $dqVars;
		$choice = $dqVars["choice"] ?? "";
		$ind = explode("-", $choice)[1] ?? -1;
		if ($ind != -1) {
			DiscardCard($this->controller, $ind);
			Draw($this->controller);
		}
	}
}


?>