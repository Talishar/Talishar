<?php

class windup {
  public $cardID;
  public $controller;

  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return GetEasyAbilityNames($this->cardID, $index, $from);
  }

  function GoesOnCombatChain($phase, $from) {
    global $layers;
    return ($phase == "B" && count($layers) == 0) || GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return "NOT IMPLEMENTED";
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return ($from == "HAND");
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 3;
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    global $CS_NumActionsPlayed;
    $names = GetAbilityNames($this->cardID, $index, $from);
    $names = str_replace("-,", "", $names);
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, $CS_NumActionsPlayed) >= 1) {
      AddDecisionQueue("SETABILITYTYPEABILITY", $this->controller, $this->cardID);
    } elseif ($names != "" && $from == "HAND") {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose to play the ability or attack");
      AddDecisionQueue("BUTTONINPUT", $this->controller, $names);
      AddDecisionQueue("SETABILITYTYPE", $this->controller, $this->cardID);
    } else {
      AddDecisionQueue("SETABILITYTYPEATTACK", $this->controller, $this->cardID);
    }
    AddDecisionQueue("NOTEQUALPASS", $this->controller, "Ability");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("DISCARDCARD", $this->controller, "HAND-$this->cardID", 1);
    AddDecisionQueue("CONVERTLAYERTOABILITY", $this->controller, $this->cardID, 1);
  }
}

// class aether_arc_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "aether_arc_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_engagement_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_engagement_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_engagement_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_engagement_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_engagement_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_engagement_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_windup_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_windup_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_windup_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_windup_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agile_windup_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "agile_windup_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class agility extends Card {

//   function __construct($controller) {
//     $this->cardID = "agility";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ancestral_harmony_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "ancestral_harmony_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class apex_bonebreaker extends Card {

//   function __construct($controller) {
//     $this->cardID = "apex_bonebreaker";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class assault_and_battery_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "assault_and_battery_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class assault_and_battery_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "assault_and_battery_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class assault_and_battery_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "assault_and_battery_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class aurum_aegis extends Card {

//   function __construct($controller) {
//     $this->cardID = "aurum_aegis";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class balance_of_justice extends Card {

//   function __construct($controller) {
//     $this->cardID = "balance_of_justice";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ball_breaker extends Card {

//   function __construct($controller) {
//     $this->cardID = "ball_breaker";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class battered_not_broken_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "battered_not_broken_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class beast_mode_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "beast_mode_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class beast_mode_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "beast_mode_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class beckon_applause extends Card {

//   function __construct($controller) {
//     $this->cardID = "beckon_applause";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bet_big_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "bet_big_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class betsy extends Card {

//   function __construct($controller) {
//     $this->cardID = "betsy";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class betsy_skin_in_the_game extends Card {

//   function __construct($controller) {
//     $this->cardID = "betsy_skin_in_the_game";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class big_bop_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "big_bop_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class big_bop_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "big_bop_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class big_bop_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "big_bop_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bigger_than_big_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "bigger_than_big_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bigger_than_big_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "bigger_than_big_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bigger_than_big_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "bigger_than_big_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blade_flurry_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "blade_flurry_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }

class bloodied_oval extends Card {

  function __construct($controller) {
    $this->cardID = "bloodied_oval";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return PlayerHasLessHealth($this->controller) ? 1 : 0;
  }
}


// class boast_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "boast_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bonebreaker_bellow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "bonebreaker_bellow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bonebreaker_bellow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "bonebreaker_bellow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bonebreaker_bellow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "bonebreaker_bellow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cast_bones_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cast_bones_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cintari_sellsword extends Card {

//   function __construct($controller) {
//     $this->cardID = "cintari_sellsword";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_agility_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_agility_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_agility_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_agility_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_agility_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_agility_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_might_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_might_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_might_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_might_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_might_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_might_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_vigor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_vigor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_vigor_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_vigor_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clash_of_vigor_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "clash_of_vigor_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class coercive_tendency_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "coercive_tendency_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class command_respect_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "command_respect_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class command_respect_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "command_respect_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class command_respect_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "command_respect_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class commanding_performance_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "commanding_performance_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class concuss_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "concuss_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class concuss_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "concuss_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class concuss_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "concuss_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class confront_adversity extends Card {

//   function __construct($controller) {
//     $this->cardID = "confront_adversity";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cut_the_deck_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cut_the_deck_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cut_the_deck_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cut_the_deck_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cut_the_deck_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cut_the_deck_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class deathmatch_arena extends Card {

//   function __construct($controller) {
//     $this->cardID = "deathmatch_arena";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dissolve_reality_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "dissolve_reality_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class double_down_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "double_down_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class down_but_not_out_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "down_but_not_out_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class down_but_not_out_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "down_but_not_out_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class down_but_not_out_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "down_but_not_out_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class draw_swords_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "draw_swords_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class draw_swords_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "draw_swords_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class draw_swords_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "draw_swords_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class edge_ahead_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "edge_ahead_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class edge_ahead_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "edge_ahead_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class edge_ahead_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "edge_ahead_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class embrace_adversity extends Card {

//   function __construct($controller) {
//     $this->cardID = "embrace_adversity";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class engaged_swiftblade_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "engaged_swiftblade_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class engaged_swiftblade_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "engaged_swiftblade_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class engaged_swiftblade_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "engaged_swiftblade_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class evo_magneto_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "evo_magneto_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class face_adversity extends Card {

//   function __construct($controller) {
//     $this->cardID = "face_adversity";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fatal_engagement_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "fatal_engagement_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fatal_engagement_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "fatal_engagement_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fatal_engagement_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "fatal_engagement_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flat_trackers extends Card {

//   function __construct($controller) {
//     $this->cardID = "flat_trackers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gauntlet_of_might extends Card {

//   function __construct($controller) {
//     $this->cardID = "gauntlet_of_might";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gauntlets_of_iron_will extends Card {

//   function __construct($controller) {
//     $this->cardID = "gauntlets_of_iron_will";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class glory_seeker extends Card {

//   function __construct($controller) {
//     $this->cardID = "glory_seeker";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goblet_of_bloodrun_wine_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "goblet_of_bloodrun_wine_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_glare extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_glare";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class good_time_chapeau extends Card {

//   function __construct($controller) {
//     $this->cardID = "good_time_chapeau";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class grains_of_bloodspill extends Card {

//   function __construct($controller) {
//     $this->cardID = "grains_of_bloodspill";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class grandstand_legplates extends Card {

  function __construct($controller) {
    $this->cardID = "grandstand_legplates";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return PlayerHasLessHealth($this->controller) ? 1 : 0;
  }
}


// class graven_call extends Card {

//   function __construct($controller) {
//     $this->cardID = "graven_call";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class headliner_helm extends Card {

  function __construct($controller) {
    $this->cardID = "headliner_helm";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return PlayerHasLessHealth($this->controller) ? 1 : 0;
  }
}


// class hearty_block_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "hearty_block_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class high_riser extends Card {

//   function __construct($controller) {
//     $this->cardID = "high_riser";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hold_em_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "hold_em_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hold_em_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "hold_em_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hold_em_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "hold_em_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hood_of_red_sand extends Card {

//   function __construct($controller) {
//     $this->cardID = "hood_of_red_sand";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hot_streak extends Card {

//   function __construct($controller) {
//     $this->cardID = "hot_streak";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class judge_jury_executioner_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "judge_jury_executioner_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class kassai extends Card {

//   function __construct($controller) {
//     $this->cardID = "kassai";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class kassai_of_the_golden_sand extends Card {

//   function __construct($controller) {
//     $this->cardID = "kassai_of_the_golden_sand";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class kayo extends Card {

//   function __construct($controller) {
//     $this->cardID = "kayo";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class kayo_armed_and_dangerous extends Card {

//   function __construct($controller) {
//     $this->cardID = "kayo_armed_and_dangerous";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class knucklehead extends Card {

//   function __construct($controller) {
//     $this->cardID = "knucklehead";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_heart_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_heart_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_heart_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_heart_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_heart_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_heart_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_power_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_power_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_power_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_power_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_power_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_power_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_speed_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_speed_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_speed_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_speed_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lead_with_speed_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "lead_with_speed_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class luminaris_angels_glow extends Card {

//   function __construct($controller) {
//     $this->cardID = "luminaris_angels_glow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mighty_windup_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "mighty_windup_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mighty_windup_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "mighty_windup_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mighty_windup_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "mighty_windup_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class millers_grindstone extends Card {

//   function __construct($controller) {
//     $this->cardID = "millers_grindstone";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mini_meataxe extends Card {

//   function __construct($controller) {
//     $this->cardID = "mini_meataxe";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_where_ya_mouth_is_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_where_ya_mouth_is_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_where_ya_mouth_is_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_where_ya_mouth_is_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_where_ya_mouth_is_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_where_ya_mouth_is_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class monstrous_veil extends Card {

//   function __construct($controller) {
//     $this->cardID = "monstrous_veil";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nasty_surprise_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "nasty_surprise_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class no_fear_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "no_fear_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class olympia extends Card {

//   function __construct($controller) {
//     $this->cardID = "olympia";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class olympia_prized_fighter extends Card {

//   function __construct($controller) {
//     $this->cardID = "olympia_prized_fighter";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class over_the_top_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "over_the_top_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class over_the_top_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "over_the_top_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class over_the_top_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "over_the_top_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class overcome_adversity extends Card {

//   function __construct($controller) {
//     $this->cardID = "overcome_adversity";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pack_call_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pack_call_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pack_call_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pack_call_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class parry_blade extends Card {

//   function __construct($controller) {
//     $this->cardID = "parry_blade";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pay_up_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pay_up_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class performance_bonus_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "performance_bonus_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class performance_bonus_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "performance_bonus_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class performance_bonus_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "performance_bonus_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pint_of_strong_and_stout_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pint_of_strong_and_stout_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pound_town_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pound_town_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pound_town_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "pound_town_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pound_town_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pound_town_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class primed_to_fight_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "primed_to_fight_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class prized_galea extends Card {

//   function __construct($controller) {
//     $this->cardID = "prized_galea";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class raise_an_army_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "raise_an_army_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class raw_meat extends Card {

//   function __construct($controller) {
//     $this->cardID = "raw_meat";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rawhide_rumble_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rawhide_rumble_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rawhide_rumble_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rawhide_rumble_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rawhide_rumble_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rawhide_rumble_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class reckless_charge_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "reckless_charge_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class reel_in_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "reel_in_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ripple_away_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "ripple_away_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_energy_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_energy_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_energy_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_energy_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_energy_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_energy_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_power_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_power_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_power_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_power_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_power_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_power_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_speed_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_speed_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_speed_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_speed_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rising_speed_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rising_speed_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class run_into_trouble_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "run_into_trouble_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class runner_runner_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "runner_runner_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class seduce_secrets_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "seduce_secrets_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class send_packing_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "send_packing_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sheltered_cove extends Card {

//   function __construct($controller) {
//     $this->cardID = "sheltered_cove";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class shift_the_tide_of_battle_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "shift_the_tide_of_battle_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class show_no_mercy_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "show_no_mercy_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class slap_happy_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "slap_happy_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class smashback_alehorn_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "smashback_alehorn_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sonata_galaxia_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sonata_galaxia_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stacked_in_your_favor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "stacked_in_your_favor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stacked_in_your_favor_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "stacked_in_your_favor_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stacked_in_your_favor_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "stacked_in_your_favor_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class stadium_centerpiece extends Card {

  function __construct($controller) {
    $this->cardID = "stadium_centerpiece";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return PlayerHasLessHealth($this->controller) ? 1 : 0;
  }
}


// class stand_ground extends Card {

//   function __construct($controller) {
//     $this->cardID = "stand_ground";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class standing_order_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "standing_order_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class starting_stake_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "starting_stake_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class stonewall_impasse extends Card {

//   function __construct($controller) {
//     $this->cardID = "stonewall_impasse";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class take_it_on_the_chin_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "take_it_on_the_chin_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class take_the_upper_hand_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "take_the_upper_hand_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class take_the_upper_hand_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "take_the_upper_hand_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class take_the_upper_hand_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "take_the_upper_hand_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class talk_a_big_game_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "talk_a_big_game_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tenacity_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "tenacity_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class test_of_agility_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "test_of_agility_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class test_of_might_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "test_of_might_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class test_of_strength_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "test_of_strength_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class test_of_vigor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "test_of_vigor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class the_golden_son_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "the_golden_son_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class thunk_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "thunk_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class thunk_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "thunk_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class thunk_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "thunk_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class ticket_puncher extends Card {

  function __construct($controller) {
    $this->cardID = "ticket_puncher";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return PlayerHasLessHealth($this->controller) ? 1 : 0;
  }
}


// class trounce_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "trounce_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class up_the_ante_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "up_the_ante_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class victor_goldmane extends Card {

//   function __construct($controller) {
//     $this->cardID = "victor_goldmane";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class victor_goldmane_high_and_mighty extends Card {

//   function __construct($controller) {
//     $this->cardID = "victor_goldmane_high_and_mighty";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigor_girth extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigor_girth";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_engagement_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_engagement_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_engagement_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_engagement_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_engagement_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_engagement_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_windup_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_windup_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_windup_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_windup_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class vigorous_windup_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "vigorous_windup_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_agility_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_agility_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_agility_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_agility_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_agility_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_agility_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_gold_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_gold_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_gold_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_gold_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_gold_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_gold_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_might_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_might_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_might_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_might_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_might_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_might_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_vigor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_vigor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_vigor_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_vigor_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wage_vigor_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wage_vigor_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wall_of_meat_and_muscle_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wall_of_meat_and_muscle_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wallop_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "wallop_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wallop_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wallop_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wallop_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "wallop_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


?>