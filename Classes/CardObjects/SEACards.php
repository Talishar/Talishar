<?php

class GoFishCard extends Card {
  public $DQEffectName;
  public $targetedProperty;

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) {
        AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
        $doubleTriggers = CountCurrentTurnEffects("catch_of_the_day_blue-DOUBLETRIGGER", $this->controller);
        if ($doubleTriggers > 0) {
          for ($i = 1; $i < pow(2, $doubleTriggers); ++$i) {
            AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
          }
        }
      }
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = "-", $uniqueID = -1, $target="-") {
    global $CS_NumCannonsActivated, $defPlayer;
    if (GetClassState($this->controller, $CS_NumCannonsActivated) == 0){
      AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card from hand, $this->targetedProperty will be discarded");
      AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $defPlayer, $this->DQEffectName, 1);
    }
    else {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRHAND", 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card from their hand, $this->targetedProperty will be discarded");
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $this->controller, $this->DQEffectName, 1);
    }
  }
}

class king_kraken_harpoon_red extends GoFishCard {
  function __construct($controller) {
    $this->cardID = "king_kraken_harpoon_red";
    $this->controller = $controller;
    $this->DQEffectName = "KINGKRAKENHARPOON";
    $this->targetedProperty = "non-attack action cards";
  }
}

class king_shark_harpoon_red extends GoFishCard {
  function __construct($controller) {
    $this->cardID = "king_shark_harpoon_red";
    $this->controller = $controller;
    $this->DQEffectName = "KINGSHARKHARPOON";
    $this->targetedProperty = "attack action cards";
  }
}

class red_fin_harpoon_blue extends GoFishCard {
  function __construct($controller) {
    $this->cardID = "red_fin_harpoon_blue";
    $this->controller = $controller;
    $this->DQEffectName = "REDFINHARPOON";
    $this->targetedProperty = "red cards";
  }
}

class yellow_fin_harpoon_blue extends GoFishCard {
  function __construct($controller) {
    $this->cardID = "yellow_fin_harpoon_blue";
    $this->controller = $controller;
    $this->DQEffectName = "YELLOWFINHARPOON";
    $this->targetedProperty = "yellow cards";
  }
}

class blue_fin_harpoon_blue extends GoFishCard {
  function __construct($controller) {
    $this->cardID = "blue_fin_harpoon_blue";
    $this->controller = $controller;
    $this->DQEffectName = "BLUEFINHARPOON";
    $this->targetedProperty = "blue cards";
  }
}

// class amethyst_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "amethyst_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class angry_bones_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "angry_bones_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class angry_bones_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "angry_bones_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class angry_bones_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "angry_bones_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class anka_drag_under_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "anka_drag_under_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class arcane_compliance_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "arcane_compliance_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class avast_ye_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "avast_ye_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bam_bam_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "bam_bam_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class bandana_of_the_blue_beyond extends Card {

//   function __construct($controller) {
//     $this->cardID = "bandana_of_the_blue_beyond";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barbed_barrage_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "barbed_barrage_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class barnacle_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "barnacle_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class battalion_barque_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "battalion_barque_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class battalion_barque_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "battalion_barque_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class battalion_barque_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "battalion_barque_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class big_game_trophy_shot_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "big_game_trophy_shot_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blood_in_the_water_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "blood_in_the_water_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class blow_for_a_blow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "blow_for_a_blow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }





// class blue_sea_tricorn extends Card {

//   function __construct($controller) {
//     $this->cardID = "blue_sea_tricorn";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class board_the_ship_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "board_the_ship_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class buccaneers_bounty extends Card {

//   function __construct($controller) {
//     $this->cardID = "buccaneers_bounty";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class burly_bones_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "burly_bones_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class burly_bones_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "burly_bones_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class burly_bones_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "burly_bones_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class burn_bare extends Card {

//   function __construct($controller) {
//     $this->cardID = "burn_bare";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class call_in_the_big_guns_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "call_in_the_big_guns_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class call_in_the_big_guns_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "call_in_the_big_guns_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class call_in_the_big_guns_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "call_in_the_big_guns_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class captains_coat extends Card {

//   function __construct($controller) {
//     $this->cardID = "captains_coat";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chart_a_course_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "chart_a_course_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chart_a_course_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "chart_a_course_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chart_a_course_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "chart_a_course_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chart_the_high_seas_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "chart_the_high_seas_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chowder_hearty_cook_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "chowder_hearty_cook_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class chum_friendly_first_mate_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "chum_friendly_first_mate_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class clap_em_in_irons_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "clap_em_in_irons_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class claw_of_vynserakai extends Card {

//   function __construct($controller) {
//     $this->cardID = "claw_of_vynserakai";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_city_steamboat_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_city_steamboat_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_city_steamboat_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_city_steamboat_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_city_steamboat_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_city_steamboat_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_skiff_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_skiff_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_skiff_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_skiff_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cloud_skiff_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cloud_skiff_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cog_in_the_machine_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cog_in_the_machine_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_blunderbuss extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_blunderbuss";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_dovetail_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_dovetail_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_tinker_rings extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_tinker_rings";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_workshop_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_workshop_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_zeppelin_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_zeppelin_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_zeppelin_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_zeppelin_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cogwerx_zeppelin_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "cogwerx_zeppelin_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class compass_of_sunken_depths extends Card {

//   function __construct($controller) {
//     $this->cardID = "compass_of_sunken_depths";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class conqueror_of_the_high_seas_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "conqueror_of_the_high_seas_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class consign_to_cosmos___shock_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "consign_to_cosmos___shock_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class copper_cog_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "copper_cog_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crash_down_the_gates_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "crash_down_the_gates_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crash_down_the_gates_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "crash_down_the_gates_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class crash_down_the_gates_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "crash_down_the_gates_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class cutty_shark_quick_clip_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "cutty_shark_quick_clip_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dead_threads extends Card {

//   function __construct($controller) {
//     $this->cardID = "dead_threads";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class deny_redemption_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "deny_redemption_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class diamond_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "diamond_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class divvy_up_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "divvy_up_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class draw_back_the_hammer_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "draw_back_the_hammer_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class drop_the_anchor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "drop_the_anchor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class dry_powder_shot_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "dry_powder_shot_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class entangling_shot_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "entangling_shot_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class escalate_bloodshed_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "escalate_bloodshed_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class everbloom___life_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "everbloom___life_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class expedition_to_azuro_keys_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "expedition_to_azuro_keys_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class expedition_to_blackwater_strait_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "expedition_to_blackwater_strait_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class expedition_to_dreadfall_reach_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "expedition_to_dreadfall_reach_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class expedition_to_horizons_mantle_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "expedition_to_horizons_mantle_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fiddlers_green_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "fiddlers_green_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fiddlers_green_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "fiddlers_green_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fiddlers_green_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "fiddlers_green_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fire_in_the_hole_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "fire_in_the_hole_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fish_fingers extends Card {

//   function __construct($controller) {
//     $this->cardID = "fish_fingers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flying_high_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "flying_high_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flying_high_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "flying_high_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class flying_high_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "flying_high_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class fools_gold_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "fools_gold_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class give_no_quarter_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "give_no_quarter_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class glidewell_fins extends Card {

//   function __construct($controller) {
//     $this->cardID = "glidewell_fins";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_hunter_ketch_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_hunter_ketch_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_hunter_lightsail_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_hunter_lightsail_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_hunter_longboat_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_hunter_longboat_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_hunter_marauder_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_hunter_marauder_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_the_tip_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_the_tip_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gold_baited_hook extends Card {

//   function __construct($controller) {
//     $this->cardID = "gold_baited_hook";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_cog extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_cog";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_skywarden_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_skywarden_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_tipple_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_tipple_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_tipple_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_tipple_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class golden_tipple_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "golden_tipple_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goldfin_harpoon extends Card {

//   function __construct($controller) {
//     $this->cardID = "goldfin_harpoon";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goldkiss_rum extends Card {

//   function __construct($controller) {
//     $this->cardID = "goldkiss_rum";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goldwing_turbine_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "goldwing_turbine_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goldwing_turbine_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "goldwing_turbine_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class goldwing_turbine_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "goldwing_turbine_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gravy_bones extends Card {

//   function __construct($controller) {
//     $this->cardID = "gravy_bones";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class gravy_bones_shipwrecked_looter extends Card {

//   function __construct($controller) {
//     $this->cardID = "gravy_bones_shipwrecked_looter";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hms_barracuda_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "hms_barracuda_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hms_kraken_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "hms_kraken_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hms_marlin_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "hms_marlin_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hammerhead_harpoon_cannon extends Card {

//   function __construct($controller) {
//     $this->cardID = "hammerhead_harpoon_cannon";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class head_stone extends Card {

//   function __construct($controller) {
//     $this->cardID = "head_stone";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class heave_ho_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "heave_ho_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class helmsmans_peak extends Card {

//   function __construct($controller) {
//     $this->cardID = "helmsmans_peak";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class herald_of_sekem_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "herald_of_sekem_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hoist_em_up_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "hoist_em_up_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class hook_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "hook_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jack_be_nimble_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "jack_be_nimble_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jack_be_quick_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "jack_be_quick_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jittery_bones_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "jittery_bones_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jittery_bones_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "jittery_bones_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jittery_bones_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "jittery_bones_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class jolly_bludger_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "jolly_bludger_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class kelpie_tangled_mess_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "kelpie_tangled_mess_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class light_fingers extends Card {

//   function __construct($controller) {
//     $this->cardID = "light_fingers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class limpit_hop_a_long_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "limpit_hop_a_long_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class line_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "line_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class loan_shark_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "loan_shark_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lost_in_transit_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "lost_in_transit_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class lubricate_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "lubricate_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class marlynn extends Card {

//   function __construct($controller) {
//     $this->cardID = "marlynn";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class marlynn_treasure_hunter extends Card {

//   function __construct($controller) {
//     $this->cardID = "marlynn_treasure_hunter";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class midas_touch_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "midas_touch_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_or_your_life_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_or_your_life_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_or_your_life_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_or_your_life_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class money_or_your_life_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "money_or_your_life_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class monkey_powder_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "monkey_powder_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class moray_le_fay_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "moray_le_fay_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class murderous_rabble_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "murderous_rabble_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mutiny_on_the_battalion_barque_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "mutiny_on_the_battalion_barque_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mutiny_on_the_nimbus_sovereign_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "mutiny_on_the_nimbus_sovereign_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mutiny_on_the_swiftwater_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "mutiny_on_the_swiftwater_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nettling_shot_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "nettling_shot_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimby_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimby_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimby_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimby_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class nimby_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "nimby_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class not_so_fast_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "not_so_fast_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class old_knocker extends Card {

//   function __construct($controller) {
//     $this->cardID = "old_knocker";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class on_the_horizon_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "on_the_horizon_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class on_the_horizon_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "on_the_horizon_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class on_the_horizon_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "on_the_horizon_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class onyx_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "onyx_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class opal_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "opal_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class oysten_heart_of_gold_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "oysten_heart_of_gold_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class paddle_faster_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "paddle_faster_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class palantir_aeronought_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "palantir_aeronought_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class patch_the_hole extends Card {

//   function __construct($controller) {
//     $this->cardID = "patch_the_hole";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pearl_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pearl_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class peg_leg extends Card {

//   function __construct($controller) {
//     $this->cardID = "peg_leg";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class perk_up_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "perk_up_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pilfer_the_wreck_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "pilfer_the_wreck_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pilfer_the_wreck_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "pilfer_the_wreck_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pilfer_the_wreck_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pilfer_the_wreck_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pinion_sentry_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pinion_sentry_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class platinum_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "platinum_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class polly_cranka extends Card {

//   function __construct($controller) {
//     $this->cardID = "polly_cranka";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class portside_exchange_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "portside_exchange_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class pounamu_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "pounamu_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class preach_modesty_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "preach_modesty_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class puffin extends Card {

//   function __construct($controller) {
//     $this->cardID = "puffin";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class puffin_hightail extends Card {

//   function __construct($controller) {
//     $this->cardID = "puffin_hightail";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class quartermasters_boots extends Card {

//   function __construct($controller) {
//     $this->cardID = "quartermasters_boots";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class quick_clicks extends Card {

//   function __construct($controller) {
//     $this->cardID = "quick_clicks";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rally_the_coast_guard_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "rally_the_coast_guard_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rally_the_coast_guard_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "rally_the_coast_guard_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rally_the_coast_guard_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rally_the_coast_guard_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class redspine_manta extends Card {

//   function __construct($controller) {
//     $this->cardID = "redspine_manta";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class regain_composure_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "regain_composure_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class restless_bones_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "restless_bones_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class restless_bones_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "restless_bones_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class restless_bones_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "restless_bones_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class return_fire_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "return_fire_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class riches_of_tropal_dhani_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "riches_of_tropal_dhani_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class riddle_with_regret_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "riddle_with_regret_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class riggermortis_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "riggermortis_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class ruby_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "ruby_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rust_belt extends Card {

//   function __construct($controller) {
//     $this->cardID = "rust_belt";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class rusty_harpoon_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "rusty_harpoon_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class saltwater_swell_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "saltwater_swell_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class saltwater_swell_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "saltwater_swell_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class saltwater_swell_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "saltwater_swell_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sapphire_amulet_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sapphire_amulet_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sawbones_dock_hand_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sawbones_dock_hand_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scooba_salty_sea_dog_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "scooba_salty_sea_dog_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scouting_shot_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "scouting_shot_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scrub_the_deck_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "scrub_the_deck_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class scurv_stowaway extends Card {

//   function __construct($controller) {
//     $this->cardID = "scurv_stowaway";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sea_floor_salvage_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sea_floor_salvage_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sea_legs_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sea_legs_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sealace_sarong extends Card {

//   function __construct($controller) {
//     $this->cardID = "sealace_sarong";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class shelly_hardened_traveler_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "shelly_hardened_traveler_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class shifting_tides_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "shifting_tides_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sinker_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sinker_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sirens_of_safe_harbor_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sirens_of_safe_harbor_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sirens_of_safe_harbor_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sirens_of_safe_harbor_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sirens_of_safe_harbor_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sirens_of_safe_harbor_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sky_skimmer_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "sky_skimmer_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sky_skimmer_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "sky_skimmer_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sky_skimmer_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sky_skimmer_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class spitfire extends Card {

//   function __construct($controller) {
//     $this->cardID = "spitfire";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sticky_fingers extends Card {

//   function __construct($controller) {
//     $this->cardID = "sticky_fingers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class strike_gold_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "strike_gold_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class strike_gold_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "strike_gold_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class strike_gold_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "strike_gold_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class sunken_treasure_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "sunken_treasure_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class surface_shaking_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "surface_shaking_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swabbie_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "swabbie_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swift_shot_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "swift_shot_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swiftstrike_bracers extends Card {

//   function __construct($controller) {
//     $this->cardID = "swiftstrike_bracers";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swiftwater_sloop_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "swiftwater_sloop_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swiftwater_sloop_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "swiftwater_sloop_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swiftwater_sloop_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "swiftwater_sloop_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swindlers_grift_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "swindlers_grift_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swindlers_grift_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "swindlers_grift_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class swindlers_grift_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "swindlers_grift_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class teeth_of_the_cog_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "teeth_of_the_cog_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class teeth_of_the_cog_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "teeth_of_the_cog_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class teeth_of_the_cog_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "teeth_of_the_cog_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class thievn_varmints_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "thievn_varmints_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class throw_caution_to_the_wind_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "throw_caution_to_the_wind_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tighten_the_screws_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "tighten_the_screws_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tip_the_barkeep_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "tip_the_barkeep_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tit_for_tat_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "tit_for_tat_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tough_old_wrench_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "tough_old_wrench_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tough_old_wrench_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "tough_old_wrench_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class tough_old_wrench_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "tough_old_wrench_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class treasure_island extends Card {

//   function __construct($controller) {
//     $this->cardID = "treasure_island";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class undercover_acquisition_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "undercover_acquisition_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class unicycle extends Card {

//   function __construct($controller) {
//     $this->cardID = "unicycle";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class wailer_humperdinck_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "wailer_humperdinck_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class walk_the_plank_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "walk_the_plank_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class walk_the_plank_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "walk_the_plank_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class walk_the_plank_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "walk_the_plank_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class washed_up_wave extends Card {

//   function __construct($controller) {
//     $this->cardID = "washed_up_wave";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class yo_ho_ho_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "yo_ho_ho_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


?>