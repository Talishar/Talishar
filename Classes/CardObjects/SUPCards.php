<?php

class backspin_thrust_red extends Card {

  function __construct($controller) {
  $this->cardID = "backspin_thrust_red";
  $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY") {
      AddDecisionQueue("BUTTONINPUTNOPASS", $this->controller, "+1 Power,Go Again");
      AddDecisionQueue("SPECIFICCARD", $this->controller, "COGCONTROL-" . $this->cardID, 1);
    }
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    global $mainPlayer, $combatChain;
    if ($this->controller != $mainPlayer) return true;
    if ($from != "PLAY") return false;
    if (GetTapped($this->controller, "MYITEMS", "subtype=Cog") == "") return true;
    if ($from == "PLAY" && $combatChain[11] >= 1) return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $combatChain;
    if ($from == "CC") {
      $i = $index * CombatChainPieces();
      $inds = GetTapped($this->controller, "MYITEMS", "subtype=Cog");
      if($inds != "") Tap(explode(",", $inds)[0], $this->controller, 0);
      ++$combatChain[$i + 11];
    }
  }

  function AbilityPlayableFromCombatChain($index="-") {
    global $mainPlayer;
    return $this->controller == $mainPlayer;
  }

  function AbilityType($index = -1, $from = '-') {
    return $from == "PLAY" ? "I": "AA";
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
  
  function CombatEffectActive($parameter = "-", $defendingCard = '', $flicked = false) {
    return true;
  }
}


class bait extends Card {

  function __construct($controller) {
    $this->cardID = "bait";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsReactionPhase()) {
      GiveAttackGoAgain();
      CombatChainPowerModifier(0, 1);
    }
    return "";
  }

  function NumUses() {
    return 1;
  }

  function GetAbilityTypes($index = -1, $from = '-', $foundNullTime=false, $layerCount=0) {
    return "AR,AA";
  }

  function AbilityType($index = -1, $from = '-') {
    return ($from == "CC" && $index != 0) ? "AR" : "AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0) {
    global $CS_NumActionsPlayed;
    $canAttack = CanAttack($this->cardID, "PLAY", $index, "MYAURA");
    $names = ["-", "-"];
    if (IsReactionPhase()) $names[0] = "Attack Reaction";
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, $CS_NumActionsPlayed) >= 1) {
      return implode(",", $names);
    } else if ($canAttack) {
      $names[1] = "Attack";
    }
    if ($names[1] == "-") return $names[0];
    // elseif ($names[0] == "-") return $names[1];
    return implode(",", $names);
  }

  function AbilityPlayableFromCombatChain($index = '-') {
    global $mainPlayer;
    $auras = GetAuras($this->controller);
    return $index != "-" && IsReactionPhase() && $this->controller == $mainPlayer && $auras[$index + 5] > 0; //makes it so you can't activate the AR layers it puts onto the combat chain
  }

  function ResolutionStepEffectTriggers($parameter) {
    $index = SearchAurasForUniqueID($parameter, $this->controller);
    if ($index != -1) DestroyAura($this->controller, $index, skipClose:true);
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function PayAbilityAdditionalCosts($from, $index = '-', $zoneIndex = -1) {
    $auras = &GetAuras($this->controller);
    if (!IsReactionPhase()) {
      $uniqueID = $auras[$zoneIndex + 6];
      AddCurrentTurnEffect($this->cardID . "-$uniqueID", $this->controller);
    }
    else {
      --$auras[$zoneIndex + 5];
    }
  }
}


class blood_follows_blade_yellow extends Card {

  function __construct($controller) {
    $this->cardID = "blood_follows_blade_yellow";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    if (SubTypeContains($CombatChain->AttackCard()->ID(), "Sword")) {
      GiveAttackGoAgain();
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
    else WriteLog("A previous chain link was targetted for no effect");
    return "";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain, $mainPlayer;
    if (!$CombatChain->HasCurrentLink()) return true;
    if (SearchCombatChainAttacks($mainPlayer, subtype:"Sword") != "") return false;
    if (SubtypeContains($CombatChain->AttackCard()->ID(), "Sword", $mainPlayer)) return false;
    return true;
  }

  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = "-") {
    global $mainPlayer;
    AddLayer("TRIGGER", $mainPlayer, $parameter, $this->cardID, "EFFECTHITEFFECT", $source);
    return false;
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-') {
    PlayAlly("cintari_sellsword", $this->controller, isToken:true);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }


}


class bully_tactics_red extends Card {

  function __construct($controller) {
    $this->cardID = "bully_tactics_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a number of resources to pay");
    AddDecisionQueue("CHOOSENUMBER", $this->controller, "0,1,2,3", 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, "<-", 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "BULLY", 1);
    return;
  }
}

class comeback_kid extends Card {
  function __construct($controller) {
    $this->cardID = "comeback_kid";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if(PlayerHasLessHealth($this->controller)) {
      Cheer($this->controller);
    }
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) ? 1 : 0;
  }
}

class comeback_kid_red extends comeback_kid {
  function __construct($controller) {
    $this->cardID = "comeback_kid_red";
    $this->controller = $controller;
  }
}


class comeback_kid_yellow extends comeback_kid {

  function __construct($controller) {
    $this->cardID = "comeback_kid_yellow";
    $this->controller = $controller;
    }
}

class comeback_kid_blue extends comeback_kid {

  function __construct($controller) {
    $this->cardID = "comeback_kid_blue";
    $this->controller = $controller;
    }
}

class crowd_goes_wild_yellow extends Card {

  function __construct($controller) {
    $this->cardID = "crowd_goes_wild_yellow";
    $this->controller = $controller;
    }

  function SelfCostModifier($from) {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) > 0 ? -3 : 0;
  }
}


class garland_of_spring extends Card {

  function __construct($controller) {
    $this->cardID = "garland_of_spring";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    GainResources($this->controller, 1);
    return "";
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}


class golden_gait extends Card {

  function __construct($controller) {
    $this->cardID = "golden_gait";
    $this->controller = $controller;
    }

  function IsGold() {
    return true;
  }
}


class golden_galea extends Card {

  function __construct($controller) {
    $this->cardID = "golden_galea";
    $this->controller = $controller;
    }

  function IsGold() {
    return true;
  }
}


class golden_gauntlets extends Card {

  function __construct($controller) {
    $this->cardID = "golden_gauntlets";
    $this->controller = $controller;
    }

  function IsGold() {
    return true;
  }
}


class golden_heart_plate extends Card {

  function __construct($controller) {
    $this->cardID = "golden_heart_plate";
    $this->controller = $controller;
    }

  function IsGold() {
    return true;
  }
}


class helm_of_hindsight extends Card {

  function __construct($controller) {
    $this->cardID = "helm_of_hindsight";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uid = explode("-", $target)[1];
    $index = SearchDiscardForUniqueID($uid, $this->controller);
    if ($index != -1) {
      $graveyard = GetDiscard($this->controller);
      AddTopDeck($graveyard[$index], $this->controller, "DISCARD");
      RemoveGraveyard($this->controller, $index);
    }
    return "";
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
    $search = "MYDISCARD:type=AA";
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an attack to put on top of your deck (or pass)", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
    DestroyCharacter($this->controller, $cardIndex);
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return 3;
  }
}


class hunter_or_hunted_blue extends Card {

  function __construct($controller) {
    $this->cardID = "hunter_or_hunted_blue";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    $count = count(GetDeck($mainPlayer));
    $player = $this->controller;
    $parameter = $this->cardID;
    if (!IsAllyAttacking()) {
      //name the card
      AddDecisionQueue("INPUTCARDNAME", $player, "-");
      AddDecisionQueue("SETDQVAR", $player, "0", 1);
      AddDecisionQueue("WRITELOG", $player, "<b>📣{0}</b> is being hunted!", 1);
      //Adding the name to the card to track
      AddDecisionQueue("PREPENDLASTRESULT", $player, "NAMEDCARD|", 1);
      AddDecisionQueue("ADDSTATICBUFF", $player, $target, 1);
      //revealing the top card
      AddDecisionQueue("PASSPARAMETER", $player, "THEIRDECK-0", 1);
      AddDecisionQueue("MZREVEAL", $player, "-", 1);
      AddDecisionQueue("MZOP", $player, "GETCARDNAME", 1);
      AddDecisionQueue("SETDQVAR", $player, 1, 1);
      AddDecisionQueue("NOTEQUALNAMEPASS", $player, "{0}", 1);
      // show their hand, arsenal, and deck
      AddDecisionQueue("WRITELOG", $player, CardLink($parameter, $parameter) . " shows opponent's hand and arsenal", 1);
      AddDecisionQueue("SHOWHANDWRITELOG", $mainPlayer, "-", 1);
      AddDecisionQueue("SHOWARSENALWRITELOG", $mainPlayer, "-", 1);

      AddDecisionQueue("FINDINDICES", $mainPlayer, "DECKTOPXINDICES," . $count, 1);
      AddDecisionQueue("DECKCARDS", $mainPlayer, "<-", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, CardLink($parameter, $parameter) . " shows the your opponents deck are", 1);
      AddDecisionQueue("MULTISHOWCARDSTHEIRDECK", $player, "<-", 1);
      //MULTISHOWCARDSTHEIRDECK seems to return PASS, so we need this else and need to repeat the check
      AddDecisionQueue("ELSE", $player, "-");
      AddDecisionQueue("PASSPARAMETER", $player, "{1}", 1);
      AddDecisionQueue("NOTEQUALNAMEPASS", $player, "{0}", 1);
      AddDecisionQueue("SPECIFICCARD", $player, "HUNTERORHUNTED", 1);
    }
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    global $combatChain;
    $index = count($combatChain) - CombatChainPieces();
    AddLayer("TRIGGER", $this->controller, $this->cardID, target:$index);
  }

  function ContractType($chosenName = '') {
    return "NAMEDCARD-$chosenName";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }
}


// class in_the_palm_of_your_hand_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "in_the_palm_of_your_hand_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class light_up_the_leaves_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "light_up_the_leaves_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mocking_blow_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "mocking_blow_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mocking_blow_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "mocking_blow_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class mocking_blow_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "mocking_blow_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class offensive_behavior_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "offensive_behavior_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class old_leather_and_vim_red extends Card {

//   function __construct($controller) {
//     $this->cardID = "old_leather_and_vim_red";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class outside_interference_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "outside_interference_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class overcrowded_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "overcrowded_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class punching_gloves extends Card {

//   function __construct($controller) {
//     $this->cardID = "punching_gloves";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class spew_obscenities_yellow extends Card {

//   function __construct($controller) {
//     $this->cardID = "spew_obscenities_yellow";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


class take_the_bait_red extends Card {

  function __construct($controller) {
    $this->cardID = "take_the_bait_red";
    $this->controller = $controller;
    }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDECK");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $this->controller, "-");
    AddDecisionQueue("MULTIADDTOPDECK", $this->controller, "-", 1);
    PlayAura("bait", $otherPlayer, isToken:true, effectController:$this->controller, effectSource:$this->cardID);
    return "";
  }
}


// class toby_jugs extends Card {

//   function __construct($controller) {
//     $this->cardID = "toby_jugs";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class up_on_a_pedestal_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "up_on_a_pedestal_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }


// class uplifting_performance_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "uplifting_performance_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }

class the_old_switcheroo_blue extends card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "the_old_switcheroo_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CardCost($from = '-') {
    return 0;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0) {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanPlayAsInstant($index = -1, $from = '') {
    return $this->archetype->CanPlayAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1) {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }
}

class cheap_shot_yellow extends card {
  function __construct($controller) {
    $this->cardID = "the_old_switcheroo_blue";
    $this->controller = $controller;
  }

  function CanPlayAsInstant($index = -1, $from = '') {
    global $CS_BooedThisTurn;
    return GetClassState($this->controller, $CS_BooedThisTurn) > 0;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "MY") ? $this->controller : $otherPlayer;
    Deal2OrDiscard($targetPlayer);
  }
}

class fight_fair_red extends card {
  function __construct($controller) {
    $this->cardID = "fight_fair_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    global $combatChain, $defPlayer;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && TalentContains($combatChain[$i], "REVILED", $defPlayer)) return 1;
    }
    return 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if(IsHeroAttackTarget() && TalentContains($defChar[0], "REVILED", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    if (SearchCurrentTurnEffects($this->cardID, $this->controller)) return "BOTDECK";
    else return "GY";
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class fight_dirty_red extends card {
  function __construct($controller) {
    $this->cardID = "fight_dirty_rred";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    global $combatChain, $defPlayer;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && TalentContains($combatChain[$i], "REVERED", $defPlayer)) return 1;
    }
    return 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if(IsHeroAttackTarget() && TalentContains($defChar[0], "REVERED", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    $deck->DestroyTop();
  }
}

class turn_the_crowd_hateful {
  public $cardID;
  public $controller;
  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function PlayAbility() {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVERED", $defPlayer) && IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function EffectPowerModifier() {
    return 3;
  }

  function CombatEffectActive() {
    return true;
  }

  function AddOnHitTrigger($check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if(IsHeroAttackTarget() && TalentContains($defChar[0], "REVERED", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect() {
    BOO($this->controller);
  }
}

class turn_the_crowd_hateful_red extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_hateful_red";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_hateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class turn_the_crowd_hateful_yellow extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_hateful_yellow";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_hateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class turn_the_crowd_hateful_blue extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_hateful_blue";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_hateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class turn_the_crowd_grateful {
  public $cardID;
  public $controller;
  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function PlayAbility() {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVILED", $defPlayer) && IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function EffectPowerModifier() {
    return 1;
  }

  function CombatEffectActive() {
    return true;
  }

  function AddOnHitTrigger($check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if(IsHeroAttackTarget() && TalentContains($defChar[0], "REVILED", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect() {
    BOO($this->controller);
  }
}

class turn_the_crowd_grateful_red extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_grateful_red";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_grateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class turn_the_crowd_grateful_yellow extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_grateful_yellow";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_grateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class turn_the_crowd_grateful_blue extends card {
  public $baseCard;

  function __construct($controller) {
    $this->cardID = "turn_the_crowd_grateful_blue";
    $this->controller = $controller;
    $this->baseCard = new turn_the_crowd_grateful($this->cardID,  $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class heroic_pose_red extends card {
  function __construct($controller) {
    $this->cardID = "heroic_pose_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    Cheer($this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class villainous_pose {
  public $cardID, $controller;
  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function PlayAbility() {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    BOO($this->controller);
  }

  function CombatEffectActive() {
    return true;
  }
}

class villainous_pose_red extends card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "villainous_pose_red";
    $this->controller = $controller;
    $this->baseCard = new villainous_pose($this->cardID, $controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  } 
}

class villainous_pose_yellow extends card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "villainous_pose_yellow";
    $this->controller = $controller;
    $this->baseCard = new villainous_pose($this->cardID, $controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  } 
}

class villainous_pose_blue extends card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "villainous_pose_blue";
    $this->controller = $controller;
    $this->baseCard = new villainous_pose($this->cardID, $controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  } 
}

class leave_them_hanging_red extends card {
  function __construct($controller) {
    $this->cardID = "leave_them_hanging_red";
    $this->controller = $controller;
  }

  function StartTurnAbility($index) {
    RemoveSuspense($this->controller, "MYAURAS-$index");
  }

  function LeavesPlayAbility($index, $uniqueID, $location) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"BUFF");
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "BUFF") AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    else {
      $otherPlayer = $this->controller == 1 ? 2 : 1;
      $targetPlayer = str_contains($target, "MY") ? $this->controller : $otherPlayer;
      Intimidate($targetPlayer);
    }
  }

  function EffectPowerModifier($param, $attached = false): int {
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1): void {
    $context = "Choose who to intimidate";
    if(ShouldAutotargetOpponent($this->controller)) {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "THEIRCHAR-0");
    }
    else {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "MYCHAR-0,THEIRCHAR-0");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    }
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function HasSuspense() {
    return true;
  }
}

class sadistic_scowl_red extends card {
  function __construct($controller) {
    $this->cardID = "sadistic_scowl_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "MY") ? $this->controller : $otherPlayer;
    Intimidate($targetPlayer);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 5;
  }

  function GetLayerTarget($from) {
    $context = "Choose who to intimidate";
    if(ShouldAutotargetOpponent($this->controller)) {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "THEIRCHAR-0");
    }
    else {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "MYCHAR-0,THEIRCHAR-0");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    }
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
  }
}

class fix_the_match_yellow extends card {
  function __construct($controller) {
    $this->cardID = "fix_the_match_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDECK");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $this->controller, "-");
    AddDecisionQueue("MULTIADDTOPDECK", $this->controller, "-", 1);
  }

  function AttackGetsBlockedEffect($cardID) {
    $numBlocking = $cardID == "" ? NumCardsBlocking() : 1;
    for($i = 0; $i < $numBlocking; ++$i) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Clash($this->cardID, $this->controller);
  }

  function WonClashAbility($winnerID) {
    PlayAura("might", $winnerID);
  }
}

class kick_the_hornets_nest_yellow extends card {
  function __construct($controller) {
    $this->cardID = "kick_the_hornets_nest_yellow";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("confidence", $this->controller);
    PlayAura("might", $this->controller);
    PlayAura("toughness", $this->controller);
    PlayAura("vigor", $this->controller);
    WriteLog(CardLink($this->controller, $this->cardID) . " created an " . CardLink("confidence", "confidence") . ", " . CardLink("might", "might") . ", " . CardLink("toughness", "toughness") . " and " . CardLink("vigor", "vigor") . " tokens.");
  }

  function AddGraveyardEffect($from, $effectController) {
    if ($effectController != $this->controller && $from != "CC") AddLayer("TRIGGER", $this->controller, $this->cardID);
  }
}

class cutting_retort_red extends card {
  function __construct($controller) {
    $this->cardID = "cutting_retort_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a number of resources to pay");
    AddDecisionQueue("CHOOSENUMBER", $this->controller, "0,1,2,3", 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, "<-", 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "CUTTING", 1);
    return;
  }
}

class two_steps_ahead_blue extends card {
  function __construct($controller) {
    $this->cardID = "two_steps_ahead_blue";
    $this->controller = $controller;
  }

  function StartTurnAbility($index) {
    PlayAura("might", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
    PlayAura("confidence", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    return true; // return true to remove
  }
}

class gang_robbery_yellow extends card {
  function __construct($controller) {
    $this->cardID = "gang_robbery_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $search = "THEIRAURAS:type=T";
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a token aura to steal", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZOP", $this->controller, "GAINCONTROL", 1);
    return;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    $auras = GetAuras($this->controller);
    if (count($auras) / AuraPieces() >= 3) return 3;
    else return 0;
  }
}

class truth_or_trickery_yellow extends card {
  function __construct($controller) {
    $this->cardID = "truth_or_trickery_yellow";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    LookAtTopCard($this->controller, $this->cardID, setPlayer:$this->controller);
    if (!IsAllyAttacking()) {
      AddDecisionQueue("PASSPARAMETER", $this->controller, "Red,Yellow,Blue");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a color", 1);
      AddDecisionQueue("BUTTONINPUT", $this->controller, "<-", 1);
      AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Do you think the top card is {0}?", 1);
      AddDecisionQueue("YESNO", $mainPlayer, "-", 1);
      AddDecisionQueue("SPECIFICCARD", $mainPlayer, "TRUTHORTRICKERY-{0}", 1);
    }
  }
}

class visit_the_boneyard_blue extends card {
  function __construct($controller) {
    $this->cardID = "visit_the_boneyard_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $search = "MYDISCARD:minAttack=6;type=AA";
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZADDZONE", $this->controller, "MYTOPDECK", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
    PlayAura("vigor", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class will_of_the_crowd_blue extends card {
  function __construct($controller) {
    $this->cardID = "will_of_the_crowd_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CS_CheeredThisTurn;
    if (GetClassState($this->controller, $CS_CheeredThisTurn)) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class wind_up_the_crowd_blue extends card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "wind_up_the_crowd_blue";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("toughness", $this->controller, 1, true, effectController: $this->controller, effectSource: $this->cardID);
    PlayAura("vigor", $this->controller, 1, true, effectController: $this->controller, effectSource: $this->cardID);
  }

  function CardCost($from = '-') {
    return $this->archetype->CardCost($from);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0) {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanPlayAsInstant($index = -1, $from = '') {
    return $this->archetype->CanPlayAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1) {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }
}

class show_of_strength_red extends card {
  function __construct($controller) {
    $this->cardID = "show_of_strength_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $combatChain, $defPlayer;
    $modifier = 0;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && PowerValue($combatChain[$i], $defPlayer, index:$i) >= 6) $modifier -= 1;
    }
    return $modifier;
  }
}

class good_natured_brutality_yellow extends card {
  function __construct($controller) {
    $this->cardID = "good_natured_brutality_yellow";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i) {
    global $combatChain;
    $uniqueID = $combatChain[$i + 7];
    AddLayer("TRIGGER", $this->controller, $this->cardID, uniqueID:$uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $combatChain, $CombatChain;
    $hand = GetHand($this->controller);
    if (count($hand) == 0) {
      for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
        if ($combatChain[$i + 7] == $uniqueID) {
          $CombatChain->Card($i)->ModifyDefense(6);
          Cheer($this->controller);
        }
      }
    }
  }
}

class no_hero_stands_alone_yellow extends card {
  function __construct($controller) {
    $this->cardID = "no_hero_stands_alone_yellow";
    $this->controller = $controller;
  }

  function HasAmbush() {
    global $CS_NumToughnessDestroyed;
    return GetClassState($this->controller, $CS_NumToughnessDestroyed) > 0 || CountAura("toughness", $this->controller) > 0;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_NumToughnessDestroyed;
    return GetClassState($this->controller, $CS_NumToughnessDestroyed) > 0 || CountAura("toughness", $this->controller) > 0 ? 3 : 0;
  }

  function OnBlockResolveEffects($blockedFromHand, $i) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Clash($this->cardID, $this->controller);
  }

  function WonClashAbility($winnerID) {
    // will need to make this able to choose past chain links and make it clear which card is a past chain link
    AddDecisionQueue("MULTIZONEINDICES", $winnerID, "COMBATCHAINLINK");
    AddDecisionQueue("SETDQCONTEXT", $winnerID, "Choose a card to give -3 power and block (or pass)", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $winnerID, "<-", 1);
    AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $winnerID, -3, 1);
    AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $winnerID, -3, 1);
  }
}

class escalate_order_red extends card {
  function __construct($controller) {
    $this->cardID = "escalate_order_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if (SearchAurasForCardName("Toughness", $this->controller, false) != "") {
      PlayAura("toughness", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
    }
  }

  function CardCost($from = '-') {
    return 2; //fabcube error
  }
}

class song_of_sinew_yellow extends card {
  function __construct($controller) {
    $this->cardID = "song_of_sinew_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $deck = new Deck($this->controller);
    if ($deck->Reveal(4)) {
      $cards = explode(",", $deck->Top(true, amount: 4));
      $numSixes = 0;
      for ($i = 0; $i < count($cards); ++$i) {
        if (ModifiedPowerValue($cards[$i], $this->controller, "DECK") >= 6) ++$numSixes;
      }
      AddCurrentTurnEffect("$this->cardID-$numSixes", $this->controller);
      $cardList = implode(",", $cards);
      AddDecisionQueue("PASSPARAMETER", $this->controller, $cardList);
      AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      for ($i = 0; $i < count($cards); ++$i) {
        AddDecisionQueue("CHOOSECARDID", $this->controller, "<-", 1);
        AddDecisionQueue("SETDQCONTEXT", $this->controller, "Put a card on top of your deck, the last card chosen will be the top card at the end", 1);
        AddDecisionQueue("ADDTOPDECK", $this->controller, "<-", 1);
        AddDecisionQueue("REMOVEFROMCHOICES", $this->controller, "{0}", 1);
        AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      }
    }
  }

  function EffectPowerModifier($param, $attached = false) {
    return intval($param);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class jaws_of_victory_red extends card {
  function __construct($controller) {
    $this->cardID = "jaws_of_victory_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if (PlayerHasLessHealth($this->controller)) Cheer($this->controller);
  }

  function DoesAttackHaveGoAgain() {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) > 0;
  }
}

class gauntlets_of_the_tyrannical_rex extends card {
  function __construct($controller) {
    $this->cardID = "gauntlets_of_the_tyrannical_rex";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return false;
    }
    return true;
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Tap("MYCHAR-$index", $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}
?>