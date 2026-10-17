<?php

// class cap_of_quick_thinking extends Card {

//   function __construct($controller) {
//     $this->cardID = "cap_of_quick_thinking";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class photon_rush_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "photon_rush_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class shock_frock extends Card {

//   function __construct($controller) {
//     $this->cardID = "shock_frock";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sizzle_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sizzle_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class skyward_serenade_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "skyward_serenade_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class skyzyk_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "skyzyk_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class spark_spray_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "spark_spray_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class starlight_striders extends Card {

//   function __construct($controller) {
//     $this->cardID = "starlight_striders";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class static_shock_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "static_shock_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class written_in_the_stars_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "written_in_the_stars_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class zap_clappers extends Card {

    function __construct($controller) {
        $this->cardID = "zap_clappers";
        $this->controller = $controller;
    }

    function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
        return "";
    }

    function OnBlockResolveEffects($blockedFromHand, $i, $start) {
        AddLayer("TRIGGER", $this->controller, $this->cardID);
    }

    function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
        if (CanRevealCards($this->controller)) {
          QueueRevealInstant($this->controller);
          Await($this->controller, $this->cardID, final:true);
        }
    }

    function SpecificLogic() {
        global $mainPlayer;
        $Character = new PlayerCharacter($this->controller);
        $CharacterCard = $Character->FindCardID($this->cardID);
        SetDamageSourceUID($CharacterCard->UniqueID());
        DealArcane(1, 1, source:$this->cardID);
    }
}


?>