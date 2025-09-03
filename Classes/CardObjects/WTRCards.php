<?php

class alpha_rampage_red extends Card {

  function __construct($controller) {
    $this->cardID = "alpha_rampage_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
    return "";
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    Intimidate();
    return "";
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    $myHand = GetHand($this->controller);
    if ($from == "HAND" && count($myHand) < 2) return true;
    else if (count($myHand) < 1) return true;
    return false;
  }

  function PayAdditionalCosts($from, $index="-") {
    global $CS_AdditionalCosts;
    $discarded = DiscardRandom($this->controller, $this->cardID);
    if ($discarded == "") {
      WriteLog("You do not have a card to discard. Reverting gamestate.", highlight: true);
      RevertGamestate();
      return;
    }
    SetClassState($this->controller, $CS_AdditionalCosts, $discarded);
  }
}


// class ancestral_empowerment_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "ancestral_empowerment_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class anothos extends Card {

//   function __construct($controller) {
//     $this->cardID = "anothos";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class awakening_bellow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "awakening_bellow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class awakening_bellow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "awakening_bellow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class awakening_bellow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "awakening_bellow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barkbone_strapping extends Card {

//   function __construct($controller) {
//     $this->cardID = "barkbone_strapping";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_beatdown_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_beatdown_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_beatdown_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_beatdown_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_beatdown_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_beatdown_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_brawnhide_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_brawnhide_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_brawnhide_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_brawnhide_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barraging_brawnhide_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "barraging_brawnhide_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class biting_blade_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "biting_blade_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class biting_blade_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "biting_blade_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class biting_blade_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "biting_blade_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blackout_kick_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "blackout_kick_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blackout_kick_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "blackout_kick_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blackout_kick_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "blackout_kick_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blessing_of_deliverance_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "blessing_of_deliverance_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blessing_of_deliverance_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "blessing_of_deliverance_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blessing_of_deliverance_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "blessing_of_deliverance_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bloodrush_bellow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "bloodrush_bellow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bone_head_barrier_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "bone_head_barrier_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class braveforge_bracers extends Card {

//   function __construct($controller) {
//     $this->cardID = "braveforge_bracers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bravo extends Card {

//   function __construct($controller) {
//     $this->cardID = "bravo";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bravo_showstopper extends Card {

//   function __construct($controller) {
//     $this->cardID = "bravo_showstopper";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class breaking_scales extends Card {

//   function __construct($controller) {
//     $this->cardID = "breaking_scales";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class breakneck_battery_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "breakneck_battery_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class breakneck_battery_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "breakneck_battery_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class breakneck_battery_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "breakneck_battery_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class buckling_blow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "buckling_blow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class buckling_blow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "buckling_blow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class buckling_blow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "buckling_blow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cartilage_crush_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cartilage_crush_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cartilage_crush_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cartilage_crush_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cartilage_crush_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cartilage_crush_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cracked_bauble_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cracked_bauble_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cranial_crush_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cranial_crush_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crazy_brew_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "crazy_brew_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crippling_crush_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "crippling_crush_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crush_confidence_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "crush_confidence_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crush_confidence_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "crush_confidence_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crush_confidence_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "crush_confidence_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dawnblade extends Card {

//   function __construct($controller) {
//     $this->cardID = "dawnblade";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class debilitate_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "debilitate_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class debilitate_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "debilitate_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class debilitate_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "debilitate_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class demolition_crew_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "demolition_crew_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class demolition_crew_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "demolition_crew_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class demolition_crew_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "demolition_crew_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class disable_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "disable_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class disable_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "disable_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class disable_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "disable_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dorinthea extends Card {

//   function __construct($controller) {
//     $this->cardID = "dorinthea";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dorinthea_ironsong extends Card {

//   function __construct($controller) {
//     $this->cardID = "dorinthea_ironsong";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class driving_blade_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "driving_blade_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class driving_blade_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "driving_blade_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class driving_blade_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "driving_blade_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class drone_of_brutality_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "drone_of_brutality_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class drone_of_brutality_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "drone_of_brutality_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class drone_of_brutality_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "drone_of_brutality_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class emerging_power_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "emerging_power_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class emerging_power_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "emerging_power_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class emerging_power_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "emerging_power_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class energy_potion_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "energy_potion_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class enlightened_strike_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "enlightened_strike_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flic_flak_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "flic_flak_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flic_flak_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "flic_flak_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flic_flak_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "flic_flak_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flock_of_the_feather_walkers_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "flock_of_the_feather_walkers_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flock_of_the_feather_walkers_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "flock_of_the_feather_walkers_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flock_of_the_feather_walkers_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "flock_of_the_feather_walkers_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fluster_fist_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "fluster_fist_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fluster_fist_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "fluster_fist_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fluster_fist_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "fluster_fist_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class forged_for_war_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "forged_for_war_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fyendals_spring_tunic extends Card {

//   function __construct($controller) {
//     $this->cardID = "fyendals_spring_tunic";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class glint_the_quicksilver_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "glint_the_quicksilver_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goliath_gauntlet extends Card {

//   function __construct($controller) {
//     $this->cardID = "goliath_gauntlet";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class harmonized_kodachi extends Card {

//   function __construct($controller) {
//     $this->cardID = "harmonized_kodachi";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class head_jab_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "head_jab_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class head_jab_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "head_jab_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class head_jab_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "head_jab_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class heart_of_fyendal_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "heart_of_fyendal_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class heartened_cross_strap extends Card {

//   function __construct($controller) {
//     $this->cardID = "heartened_cross_strap";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class helm_of_isens_peak extends Card {

//   function __construct($controller) {
//     $this->cardID = "helm_of_isens_peak";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hope_merchants_hood extends Card {

//   function __construct($controller) {
//     $this->cardID = "hope_merchants_hood";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hurricane_technique_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "hurricane_technique_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironrot_gauntlet extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironrot_gauntlet";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironrot_helm extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironrot_helm";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironrot_legs extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironrot_legs";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironrot_plate extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironrot_plate";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironsong_determination_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironsong_determination_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironsong_response_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironsong_response_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironsong_response_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironsong_response_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ironsong_response_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "ironsong_response_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class katsu extends Card {

//   function __construct($controller) {
//     $this->cardID = "katsu";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class katsu_the_wanderer extends Card {

//   function __construct($controller) {
//     $this->cardID = "katsu_the_wanderer";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class last_ditch_effort_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "last_ditch_effort_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class leg_tap_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "leg_tap_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class leg_tap_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "leg_tap_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class leg_tap_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "leg_tap_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lord_of_wind_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "lord_of_wind_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mask_of_momentum extends Card {

//   function __construct($controller) {
//     $this->cardID = "mask_of_momentum";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mugenshi_release_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "mugenshi_release_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class natures_path_pilgrimage_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "natures_path_pilgrimage_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class natures_path_pilgrimage_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "natures_path_pilgrimage_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class natures_path_pilgrimage_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "natures_path_pilgrimage_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimble_strike_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimble_strike_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimble_strike_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimble_strike_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimble_strike_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimble_strike_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimblism_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimblism_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     AddCurrentTurnEffect($this->cardID, $this->controller);
//     return "";
//   }
// }


// class nimblism_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimblism_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimblism_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimblism_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class open_the_center_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "open_the_center_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class open_the_center_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "open_the_center_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class open_the_center_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "open_the_center_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class overpower_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "overpower_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class overpower_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "overpower_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class overpower_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "overpower_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pack_hunt_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pack_hunt_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pack_hunt_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "pack_hunt_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pack_hunt_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pack_hunt_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class potion_of_strength_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "potion_of_strength_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pounding_gale_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pounding_gale_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class primeval_bellow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "primeval_bellow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class primeval_bellow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "primeval_bellow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class primeval_bellow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "primeval_bellow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pummel_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pummel_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pummel_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "pummel_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pummel_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pummel_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class quicken extends Card {

//   function __construct($controller) {
//     $this->cardID = "quicken";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class raging_onslaught_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "raging_onslaught_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class raging_onslaught_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "raging_onslaught_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class raging_onslaught_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "raging_onslaught_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class razor_reflex_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "razor_reflex_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class razor_reflex_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "razor_reflex_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class razor_reflex_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "razor_reflex_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class reckless_swing_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "reckless_swing_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class refraction_bolters extends Card {

//   function __construct($controller) {
//     $this->cardID = "refraction_bolters";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class regurgitating_slog_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "regurgitating_slog_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class regurgitating_slog_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "regurgitating_slog_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class regurgitating_slog_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "regurgitating_slog_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class remembrance_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "remembrance_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rhinar extends Card {

//   function __construct($controller) {
//     $this->cardID = "rhinar";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rhinar_reckless_rampage extends Card {

//   function __construct($controller) {
//     $this->cardID = "rhinar_reckless_rampage";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_knee_thrust_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_knee_thrust_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_knee_thrust_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_knee_thrust_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_knee_thrust_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_knee_thrust_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class romping_club extends Card {

//   function __construct($controller) {
//     $this->cardID = "romping_club";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rout_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rout_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sand_sketched_plan_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sand_sketched_plan_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_feast_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_feast_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_feast_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_feast_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_feast_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_feast_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_swing_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_swing_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_swing_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_swing_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class savage_swing_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "savage_swing_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scabskin_leathers extends Card {

//   function __construct($controller) {
//     $this->cardID = "scabskin_leathers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scar_for_a_scar_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "scar_for_a_scar_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scar_for_a_scar_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "scar_for_a_scar_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scar_for_a_scar_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "scar_for_a_scar_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scour_the_battlescape_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "scour_the_battlescape_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scour_the_battlescape_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "scour_the_battlescape_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scour_the_battlescape_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "scour_the_battlescape_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class seismic_surge extends Card {

//   function __construct($controller) {
//     $this->cardID = "seismic_surge";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sharpen_steel_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sharpen_steel_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sharpen_steel_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sharpen_steel_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sharpen_steel_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sharpen_steel_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class show_time_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "show_time_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sigil_of_solace_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sigil_of_solace_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sigil_of_solace_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sigil_of_solace_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sigil_of_solace_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sigil_of_solace_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class singing_steelblade_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "singing_steelblade_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sink_below_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sink_below_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sink_below_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sink_below_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sink_below_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sink_below_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sloggism_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sloggism_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sloggism_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sloggism_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sloggism_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sloggism_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class smash_instinct_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "smash_instinct_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class smash_instinct_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "smash_instinct_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class smash_instinct_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "smash_instinct_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class snapdragon_scalers extends Card {

//   function __construct($controller) {
//     $this->cardID = "snapdragon_scalers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class snatch_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "snatch_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class snatch_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "snatch_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class snatch_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "snatch_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class spinal_crush_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "spinal_crush_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class staunch_response_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "staunch_response_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class staunch_response_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "staunch_response_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class staunch_response_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "staunch_response_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class steelblade_shunt_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "steelblade_shunt_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class steelblade_shunt_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "steelblade_shunt_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class steelblade_shunt_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "steelblade_shunt_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class steelblade_supremacy_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "steelblade_supremacy_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stonewall_confidence_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "stonewall_confidence_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stonewall_confidence_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "stonewall_confidence_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stonewall_confidence_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "stonewall_confidence_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stroke_of_foresight_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "stroke_of_foresight_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stroke_of_foresight_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "stroke_of_foresight_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stroke_of_foresight_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "stroke_of_foresight_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class surging_strike_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "surging_strike_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class surging_strike_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "surging_strike_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class surging_strike_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "surging_strike_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tectonic_plating extends Card {

//   function __construct($controller) {
//     $this->cardID = "tectonic_plating";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class timesnap_potion_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "timesnap_potion_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tome_of_fyendal_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "tome_of_fyendal_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class unmovable_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "unmovable_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class unmovable_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "unmovable_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class unmovable_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "unmovable_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class warriors_valor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "warriors_valor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class warriors_valor_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "warriors_valor_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class warriors_valor_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "warriors_valor_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class whelming_gustwave_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "whelming_gustwave_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class whelming_gustwave_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "whelming_gustwave_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class whelming_gustwave_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "whelming_gustwave_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounded_bull_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounded_bull_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounded_bull_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounded_bull_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounded_bull_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounded_bull_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounding_blow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounding_blow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounding_blow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounding_blow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wounding_blow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wounding_blow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wrecker_romp_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wrecker_romp_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wrecker_romp_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wrecker_romp_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wrecker_romp_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wrecker_romp_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// ?>