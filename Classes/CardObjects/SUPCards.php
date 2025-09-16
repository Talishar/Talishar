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

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = "-") {
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


// class uplifting_performance_blue extends Card {

//   function __construct($controller) {
//     $this->cardID = "uplifting_performance_blue";
//     $this->controller = $controller;
//     }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     return "";
//   }
// }

class the_old_switcheroo_blue extends Card {
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

class cheap_shot_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "cheap_shot_yellow";
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

class fight_fair_red extends Card {
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

class fight_dirty_red extends Card {
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

class turn_the_crowd_hateful_red extends Card {
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

class turn_the_crowd_hateful_yellow extends Card {
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

class turn_the_crowd_hateful_blue extends Card {
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

class turn_the_crowd_grateful_red extends Card {
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

class turn_the_crowd_grateful_yellow extends Card {
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

class turn_the_crowd_grateful_blue extends Card {
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

class heroic_pose extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
    Cheer($this->controller);
  }

  function CombatEffectActive() {
    return true;
  }
}

class heroic_pose_red extends Card {
  function __construct($controller) {
    $this->cardID = "heroic_pose_red";
    $this->controller = $controller;
    $this->baseCard = new heroic_pose($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class heroic_pose_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "heroic_pose_yellow";
    $this->controller = $controller;
    $this->baseCard = new heroic_pose($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class heroic_pose_blue extends Card {
  function __construct($controller) {
    $this->cardID = "heroic_pose_blue";
    $this->controller = $controller;
    $this->baseCard = new heroic_pose($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
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

class villainous_pose_red extends Card {
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

class villainous_pose_yellow extends Card {
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

class villainous_pose_blue extends Card {
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

class leave_them_hanging_red extends Card {
  function __construct($controller) {
    $this->cardID = "leave_them_hanging_red";
    $this->controller = $controller;
  }

  function StartTurnAbility($index) {
    RemoveSuspense($this->controller, "MYAURAS-$index", mainPhase: false);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"BUFF");
    else $this->ProcessTrigger("-", additionalCosts:"BUFF");
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

  function EntersArenaAbility() {
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

class sadistic_scowl_red extends Card {
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

class fix_the_match_yellow extends Card {
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

  function AttackGetsBlockedEffect($start) {
    global $combatChain;
    $numBlocking = intdiv(count($combatChain) - $start, CombatChainPieces());
    for($i = 0; $i < $numBlocking; ++$i) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Clash($this->cardID, $this->controller);
  }

  function WonClashAbility($winnerID, $switched) {
    PlayAura("might", $winnerID);
  }
}

class kick_the_hornets_nest_yellow extends Card {
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

class cutting_retort_red extends Card {
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

class two_steps_ahead_blue extends Card {
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

class gang_robbery_yellow extends Card {
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

class truth_or_trickery_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "truth_or_trickery_yellow";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
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

class visit_the_boneyard_blue extends Card {
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
    AddDecisionQueue("WRITELOGLASTRESULT", $this->controller, "-", 1);
    PlayAura("vigor", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class will_of_the_crowd_blue extends Card {
  function __construct($controller) {
    $this->cardID = "will_of_the_crowd_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
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

class wind_up_the_crowd_blue extends Card {
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

class show_of_strength_red extends Card {
  function __construct($controller) {
    $this->cardID = "show_of_strength_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $combatChain, $defPlayer;
    $modifier = 0;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && PowerValue($combatChain[$i], $defPlayer, index:$i) + $combatChain[$i + 5]  >= 6) $modifier -= 1;
    }
    return $modifier;
  }
}

class good_natured_brutality_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "good_natured_brutality_yellow";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
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

class no_hero_stands_alone_yellow extends Card {
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

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Clash($this->cardID, $this->controller);
  }

  function WonClashAbility($winnerID, $switched) {
    // will need to make this able to choose past chain links and make it clear which card is a past chain link
    AddDecisionQueue("MULTIZONEINDICES", $winnerID, "COMBATCHAINLINK");
    AddDecisionQueue("SETDQCONTEXT", $winnerID, "Choose a card to give -3 power and block (or pass)", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $winnerID, "<-", 1);
    AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $winnerID, -3, 1);
    AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $winnerID, -3, 1);
  }
}

class escalate_order_red extends Card {
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
}

class song_of_sinew_yellow extends Card {
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
      WriteLog("$this->cardID is buffing the next attack by $numSixes!");
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

class jaws_of_victory_red extends Card {
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

class gauntlets_of_tyrannical_rex extends Card {
  function __construct($controller) {
    $this->cardID = "gauntlets_of_tyrannical_rex";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
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

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityHasGoAgain($from) {
    return true;
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

class big_bully_red extends Card {
  function __construct($controller) {
    $this->cardID = "big_bully_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    if (PlayerHasLessHealth($otherPlayer)) BOO($this->controller);
  }

  function MultiplyBasePower() {
    global $CS_BooedThisTurn;
    if (GetClassState($this->controller, $CS_BooedThisTurn)) return 2;
    else return 1;
  }
}

class challenge_the_alpha_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "challenge_the_alpha_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && ClassContains($defChar[0], "BRUTE", $defPlayer)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
    }
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && ClassContains($defChar[0], "BRUTE", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    PummelHit($defPlayer, context: "Choose a card to discard, choose a 6 power card to to assert dominance and deal 2 to the opponent");
    AddDecisionQueue("SPECIFICCARD", $defPlayer, "ALPHA", 1);
  }
}

class steal_victory_blue extends Card {
  function __construct($controller) {
    $this->cardID = "steal_victory_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if (!IsAllyAttacking()) {
      $search = "THEIRAURAS:type=T";
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a token aura to steal", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZOP", $this->controller, "GAINCONTROL", 1);
    }
  }
}

class beat_the_same_drum_blue extends Card {
  function __construct($controller) {
    $this->cardID = "beat_the_same_drum_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_NumAgilityDestroyed, $CS_NumVigorDestroyed, $CS_NumMightDestroyed, $CS_NumConfidenceDestroyed, $CS_NumToughnessDestroyed;
    if (GetClassState($this->controller, $CS_NumAgilityDestroyed) > 0 || SearchAurasForCard("agility", $this->controller) != "") {
      PlayAura("agility", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
    if (GetClassState($this->controller, $CS_NumConfidenceDestroyed) > 0 || SearchAurasForCard("confidence", $this->controller) != "") {
      PlayAura("confidence", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
    if (GetClassState($this->controller, $CS_NumMightDestroyed) > 0 || SearchAurasForCard("might", $this->controller) != "") {
      PlayAura("might", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
    if (GetClassState($this->controller, $CS_NumToughnessDestroyed) > 0 || SearchAurasForCard("toughness", $this->controller) != "") {
      PlayAura("toughness", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
    if (GetClassState($this->controller, $CS_NumVigorDestroyed) > 0 || SearchAurasForCard("vigor", $this->controller) != "") {
      PlayAura("vigor", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
  }
}

class reckless_stampede_red extends Card {
  function __construct($controller) {
    $this->cardID = "reckless_stampede_red";
    $this->controller = $controller;
  }

  function AttackGetsBlockedEffect($start) {
    global $combatChain;
    $numBlocking = intdiv(count($combatChain) - $start, CombatChainPieces());
    for($i = 0; $i < $numBlocking; ++$i) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $switched = SearchCurrentTurnEffects("the_old_switcheroo_blue", 1) || SearchCurrentTurnEffects("the_old_switcheroo_blue", 2);
    Clash($this->cardID, $this->controller);

    // Prompt Controller to Sink
    $revealedCardController = $this->controller;
    if ($switched) {
      $revealedCardController = $this->controller == 1 ? 2 : 1;
    }
    AddDecisionQueue("DECKCARDS", $revealedCardController, "0", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose if you want to sink <0>", 1);
    AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_sink_the_revealed_card", 1);
    AddDecisionQueue("NOPASS", $this->controller, $this->cardID, 1);
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller sunk the revealed card", 1);
    AddDecisionQueue("FINDINDICES", $revealedCardController, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $revealedCardController, "<-", 1);
    AddDecisionQueue("ADDBOTDECK", $revealedCardController, "Skip", 1);
    AddDecisionQueue("ELSE", $this->controller, "-");
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller left the revealed card there", 1);

    // Prompt Other Player to Sink
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $revealedCardController = $otherPlayer;
    if ($switched) {
      $revealedCardController = $otherPlayer == 1 ? 2 : 1;
    }
    AddDecisionQueue("DECKCARDS", $revealedCardController, "0", 1);
    AddDecisionQueue("SETDQVAR", $otherPlayer, "0", 1);
    AddDecisionQueue("SETDQCONTEXT", $otherPlayer, "Choose if you want to sink <0>", 1);
    AddDecisionQueue("YESNO", $otherPlayer, "if_you_want_to_sink_the_revealed_card", 1);
    AddDecisionQueue("NOPASS", $otherPlayer, $this->cardID, 1);
    AddDecisionQueue("WRITELOG", $otherPlayer, "Player $otherPlayer sunk the revealed card", 1);
    AddDecisionQueue("FINDINDICES", $revealedCardController, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $revealedCardController, "<-", 1);
    AddDecisionQueue("ADDBOTDECK", $revealedCardController, "Skip", 1);
    AddDecisionQueue("ELSE", $otherPlayer, "-");
    AddDecisionQueue("WRITELOG", $otherPlayer, "Player $otherPlayer left the revealed card there", 1);
  }

  function WonClashAbility($winnerID, $switched) {
    AddDecisionQueue("PASSPARAMETER", $winnerID, "1-$this->cardID-");
    AddDecisionQueue("DEALDAMAGE", $winnerID, "THEIRCHAR-0", 1);
  }
}

class smashing_ground_blue extends Card {
  function __construct($controller) {
    $this->cardID = "smashing_ground_blue";
    $this->controller = $controller;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $totalPower = CachedTotalPower();
    if (IsHeroAttackTarget() && $totalPower >= 6) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRARS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card you want to destroy from their arsenal", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, false, 1);
  } 
}

class battered_beaten_and_broken_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "battered_beaten_and_broken_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $defPlayer;
    Intimidate($defPlayer);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    $auraCount = intdiv(count(GetAuras($this->controller)), AuraPieces());
    if (IsHeroAttackTarget() && $auraCount >= 3) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    $auraCount = intdiv(count(GetAuras($this->controller)), AuraPieces());
    return $auraCount >= 3 ? 3 : 0;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRBANISH:isIntimidated=true");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an intimidated card to put into the graveyard (The cards were intimated in left to right order)", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZADDZONE", $this->controller, "THEIRDISCARD", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "THEIRBANISH", 1);
  }
}

class revolting_gesture_red extends Card {
  function __construct($controller) {
    $this->cardID = "revolting_gesture_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    PlayAura("might", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  } 
}

class low_blow_red extends Card {
  function __construct($controller) {
    $this->cardID = "low_blow_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $CS_BooedThisTurn;
    return GetClassState($this->controller, $CS_BooedThisTurn) ? 3 : 0;
  }
}

class concealed_object_blue extends Card {
  function __construct($controller) {
    $this->cardID = "concealed_object_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY") {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
    else AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    BOO($this->controller);
  }

  function BeginEndTurnAbilities($index) {
    DestroyItemForPlayer($this->controller, $index);
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY") Tap("MYITEMS-$index", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if ($from == "PLAY") {
      if (CheckTapped("MYITEMS-$index", $this->controller)) return true;
      if (!$CombatChain->HasCurrentLink() && !IsLayerStep()) return true;
    }
    return false;
  }

  function AbilityType($index = -1, $from = '-') {
    if ($from == "PLAY") return "I";
    else return "";
  }
}

class escalate_violence_blue extends Card {
  function __construct($controller) {
    $this->cardID = "escalate_violence_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if (SearchAurasForCardName("Might", $this->controller, false) != "") {
      PlayAura("might", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
    }
  }

}

class tempest_palm_gustwave_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "tempest_palm_gustwave_yellow";
    $this->controller = $controller;
  }

  function HasCombo() {
    return true;
  }

  function ComboActive($lastAttackName) {
    return $lastAttackName == "Surging Strike";
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return ComboActive() ? 2 : 0;
  }

  function DoesAttackHaveGoAgain() {
    global $chainLinks;
    return count($chainLinks) >= 2;
  }
}

class angelic_attendant_yellow extends Card{
  function __construct($controller) {
    $this->cardID = "angelic_attendant_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $uniqueID = explode("-", $target)[1];
    $index = SearchPermanentsForUniqueID($uniqueID, $this->controller);
    if ($index != -1) {
      AddDecisionQueue("AWAKEN", $this->controller, "MYPERMS-$index", 1);
      return "";
    }
    else {
      //currently a bug where it still goes to soul even when fizzled
      return "FAILED";
    }
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return SearchPermanents($this->controller, subtype: "Figment") == "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYPERM:subtype=Figment");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Target a figment to awaken.");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "SOUL";
  }
}

class ironfist_revelation extends Card {
  function __construct($controller) {
    $this->cardID = "ironfist_revelation";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $arsenal = &GetArsenal($this->controller);
    $choices = [];
    for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
      if ($arsenal[$i + 1] == "DOWN" && HasCrush($arsenal[$i])) array_push($choices, "MYARS-$i");
    }
    if (count($choices) > 0) {
      $choices = implode(",", $choices);
      AddDecisionQueue("PASSPARAMETER", $this->controller, $choices);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Turn a card in your arsenal with crush face up?");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $this->controller, "IRONFIST", 1);
    }
  }
}

class aura_of_suspense extends BaseCard{
  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function HasSuspense() {
    return true;
  }

  function StartTurnAbility($index) {
    RemoveSuspense($this->controller, "MYAURAS-$index", false);
  }

  function LeavesPlayAbility($mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger();
  }

  function ProcessTrigger() {
    global $CombatChain;
    if (!$CombatChain->HasCurrentLink() && !IsLayerStep()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
    else AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function EffectPowerModifier(): int {
    //fill this in
    return 0;
  }

  function CombatEffectActive() {
    return true;
  }
}

class act_of_glory_red extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "act_of_glory_red";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 6;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class act_of_glory_yellow extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "act_of_glory_yellow";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 5;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class act_of_glory_blue extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "act_of_glory_blue";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class edge_of_their_seats_red extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "edge_of_their_seats_red";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 5;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class edge_of_their_seats_yellow extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "edge_of_their_seats_yellow";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class edge_of_their_seats_blue extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "edge_of_their_seats_blue";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class tension_in_the_air_red extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "tension_in_the_air_red";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class tension_in_the_air_yellow extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "tension_in_the_air_yellow";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class tension_in_the_air_blue extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "tension_in_the_air_blue";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    $this->archetype->LeavesPlayAbility($mainPhase);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->archetype->ProcessTrigger();
  }

  function EffectPowerModifier($param, $attached = false): int {
    //fill this in
    return 2;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->archetype->CombatEffectActive();
  }
}

class renounce_violence_blue extends Card {
  function __construct($controller) {
    $this->cardID = "renounce_violence_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Destroy a might to create a toughness");
    for ($i = 0; $i < 3; ++$i) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYAURAS:isSameName=might&THEIRAURAS:isSameName=might", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
      AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
      AddDecisionQueue("PLAYAURA", $this->controller, "toughness", 1);
    }
  }
}

class rip_up_their_virtues_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rip_up_their_virtues_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Destroy a toughness to create a might");
    for ($i = 0; $i < 3; ++$i) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYAURAS:isSameName=toughness&THEIRAURAS:isSameName=toughness", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
      AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
      AddDecisionQueue("PLAYAURA", $this->controller, "might", 1);
    }
  }
}

class SUPDwarfCard extends Card {
  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return HasIncreasedAttack() ? 1 : 0;
  }
}

class SUPTowerDwarfCard extends SUPDwarfCard {
  function HasTower() {
    return true;
  }

  function AddTowerHitTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "TOWEREFFECT");
  }
}

class cut_a_long_story_short_yellow extends SUPTowerDwarfCard {
  function __construct($controller) {
    $this->cardID = "cut_a_long_story_short_yellow";
    $this->controller = $controller;
  }

  function ProcessTowerEffect() {
    global $defPlayer;
    DiscardHand($defPlayer);
  }
}

class cut_off_at_the_knees_yellow extends SUPTowerDwarfCard {
  function __construct($controller) {
    $this->cardID = "cut_off_at_the_knees_yellow";
    $this->controller = $controller;
  }

  function ProcessTowerEffect() {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    for ($i = 0; $i < 3; ++$i) {
      if($deck->Empty()) break;
      else DestroyTopCard($defPlayer);
    }
  }
}

class cut_the_small_talk_yellow extends SUPTowerDwarfCard {
  function __construct($controller) {
    $this->cardID = "cut_the_small_talk_yellow";
    $this->controller = $controller;
  }

  function ProcessTowerEffect() {
    global $mainPlayer;
    MZDestroy($mainPlayer, SearchMultizone($mainPlayer, "THEIRAURAS"), $mainPlayer);
  }
}

class no_tall_tales_yellow extends SUPTowerDwarfCard {
  function __construct($controller) {
    $this->cardID = "no_tall_tales_yellow";
    $this->controller = $controller;
  }

  function ProcessTowerEffect() {
    global $mainPlayer;
    global $defPlayer;
    AddNextTurnEffect($this->cardID, $defPlayer);
    AddCurrentTurnEffect("$this->cardID-SELF", $mainPlayer);
  }
}

class SUPCrushDwarfCard extends SUPDwarfCard {
  function HasCrush() {
    return true;
  }

  function AddCrushEffectTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "CRUSHEFFECT");
  }
}

class short_shrift_yellow extends SUPCrushDwarfCard {
  function __construct($controller) {
    $this->cardID = "short_shrift_yellow";
    $this->controller = $controller;
  }

  function ProcessCrushEffect() {
    global $defPlayer;
    PummelHit($defPlayer);
  }
}

class small_problem_yellow extends SUPCrushDwarfCard {
  function __construct($controller) {
    $this->cardID = "small_problem_yellow";
    $this->controller = $controller;
  }

  function ProcessCrushEffect() {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWCHOSENCARD", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }
}

class wee_wrecking_ball_yellow extends SUPCrushDwarfCard {
  function __construct($controller) {
    $this->cardID = "wee_wrecking_ball_yellow";
    $this->controller = $controller;
  }

  function ProcessCrushEffect() {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRARS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card you want to destroy from their arsenal", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, false, 1);
  }
}

class painful_passage_red extends Card {
  function __construct($controller) {
    $this->cardID = "painful_passage_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    MZMoveCard($this->controller, "MYHAND:type=AA", "MYBANISH,HAND,", may: true);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a path through the painful passage");
    AddDecisionQueue("BUTTONINPUT", $this->controller, "buff,go_again", 1);
    AddDecisionQueue("WRITELOGLASTRESULT", $this->controller, "-", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "MYBANISH", 1);
    AddDecisionQueue("MZOP", $this->controller, "LASTMZINDEX", 1);
    AddDecisionQueue("MZOP", $this->controller, "GETUNIQUEID", 1);
    AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $this->controller, "$this->cardID-{0},PLAY", 1);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "buff" ? 3 : 0;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return $param == "go_again";
  }
}

class adaptive_alpha_mold extends Card {
  function __construct($controller) {
    $this->cardID = "adaptive_alpha_mold";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    ModularMove($this->cardID, $additionalCosts);
  }
}

class battlefield_beacon_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "battlefield_beacon_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
    return "";
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $combatChainState, $CCS_SoulBanishedThisChain;
    $count = isset($combatChainState[$CCS_SoulBanishedThisChain]) ? intval($combatChainState[$CCS_SoulBanishedThisChain]) : 0;
    if ($count <= 0) return;
    if ($count > 9) $count = 9;
    $options = [
      "Create_a_Courage_token",
      "Create_a_Courage_token",
      "Create_a_Courage_token",
      "Create_a_Toughness_token",
      "Create_a_Toughness_token",
      "Create_a_Toughness_token",
      "Create_a_Vigor_token",
      "Create_a_Vigor_token",
      "Create_a_Vigor_token",
    ];
    $modes = $count . "-" . implode(",", $options) . "-" . $count;
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose $count " . ($count == 1 ? "mode" : "modes"));
    AddDecisionQueue("MULTICHOOSETEXT", $this->controller, $modes, 1);
    AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "BFB," . $this->cardID, 1);
  }
}

class cheers_blue extends Card {
  function __construct($controller) {
    $this->cardID = "cheers_blue";
    $this->controller = $controller;
  }

  function EntersArenaAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Cheer($this->controller);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger(-1);
  }

  function StartTurnAbility($index) {
    return true;
  }
}

class booze_blue extends Card {
  function __construct($controller) {
    $this->cardID = "booze_blue";
    $this->controller = $controller;
  }

  function EntersArenaAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Boo($this->controller);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger(-1);
  }

  function StartTurnAbility($index) {
    return true;
  }
}

class hit_the_gas_blue extends Card {
  function __construct($controller) {
    $this->cardID = "hit_the_gas_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    //eventually we'll want to make this check if it should draw only after you finish flipping cards
    $search = "MYBANISH:isSameName=hyper_driver_red";
    $count = count(explode(",", SearchMultizone($this->controller, $search)));
    // AddDecisionQueue("PASSPARAMETER", $this->controller, 1);
    // AddDecisionQueue("SETDQVAR", $this->controller, 0, 1); // number of cards flipped
    for ($i = 0; $i < $count; ++$i) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a hyper driver to turn face-down",1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZOP", $this->controller, "TURNBANISHFACEDOWN", 1);
      AddDecisionQueue("GAINACTIONPOINTS", $this->controller, 1, 1);
      if ($i == 2) AddDecisionQueue("DRAW", $this->controller, 1, 1);
      // AddDecisionQueue("INCDQVAR", $this->controller, "0", 1);
    }
    // AddDecisionQueue("PASSPARAMETER", $this->controller, "PASS", 1);
    // AddDecisionQueue("ELSE", $this->controller, "-");
    // AddDecisionQueue("PASSPARAMETER", $this->controller, "{0}", 1);
    // AddDecisionQueue("LESSTHANPASS", $this->controller, 3, 1);
    // AddDecisionQueue("DRAW", $this->controller, 1, 1);
  }
}

class authority_of_ataya_blue extends Card {
  function __construct($controller) {
    $this->cardID = "authority_of_ataya_blue";
    $this->controller = $controller;
  }

  function PitchAbility($from) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    AddCurrentTurnEffect($this->cardID, $otherPlayer);
  }
}

class unexpected_backhand extends BaseCard{
  function WonClashWithAbility($winnerID) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    WriteLog(CardLink($this->cardID, $this->cardID) . " deals 1 damage");
    DealDamageAsync($otherPlayer, 1, "DAMAGE", $this->cardID);
  }
}

class unexpected_backhand_red extends Card {

  function __construct($controller) {
    $this->cardID = "unexpected_backhand_red";
    $this->controller = $controller;
    $this->baseCard = new unexpected_backhand($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class unexpected_backhand_yellow extends Card {

  function __construct($controller) {
    $this->cardID = "unexpected_backhand_yellow";
    $this->controller = $controller;
    $this->baseCard = new unexpected_backhand($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class unexpected_backhand_blue extends Card {
  function __construct($controller) {
    $this->cardID = "unexpected_backhand_blue";
    $this->controller = $controller;
    $this->baseCard = new unexpected_backhand($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class rapturous_applause extends BaseCard {
  function WonClashWithAbility($winnerID) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $winnerID);
  }

  function ProcessTrigger() {
    Cheer($this->controller);
  }
}

class rapturous_applause_red extends Card {
  function __construct($controller) {
    $this->cardID = "rapturous_applause_red";
    $this->controller = $controller;
    $this->baseCard = new rapturous_applause($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class rapturous_applause_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rapturous_applause_yellow";
    $this->controller = $controller;
    $this->baseCard = new rapturous_applause($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class rapturous_applause_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rapturous_applause_blue";
    $this->controller = $controller;
    $this->baseCard = new rapturous_applause($this->cardID, $this->controller);
  }

  function WonClashWithAbility($winnerID) {
    $this->baseCard->WonClashWithAbility($winnerID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class strongest_survive {
  public $cardID;
  public $controller;
  function __construct($cardID, $controller) {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function OnHitEffect() {
    global $CCS_DamageDealt, $combatChainState, $defPlayer;
    $minAttack = $combatChainState[$CCS_DamageDealt] + 1;
    if (CanRevealCards($defPlayer)) {
      AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND:minAttack=$minAttack");
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card with at least $minAttack power or discard a card", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $defPlayer, "<-", 1);
      AddDecisionQueue("MZREVEAL", $defPlayer, "<-", 1);
      AddDecisionQueue("ELSE", $defPlayer, "-");
      AddDecisionQueue("MULTIZONEINDICES", $defPlayer, "MYHAND", 1);
      AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose a card to discard", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $defPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $defPlayer, "<-", 1);
      AddDecisionQueue("DISCARDCARD", $defPlayer, "HAND-$this->controller", 1);
    }
    else {
      PummelHit($defPlayer);
    }
  }
}

class strongest_survive_red extends Card{
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "strongest_survive_red";
    $this->controller = $controller;
    $this->baseCard = new strongest_survive($this->cardID, $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->OnHitEffect();
  }
}

class strongest_survive_yellow extends Card{
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "strongest_survive_yellow";
    $this->controller = $controller;
    $this->baseCard = new strongest_survive($this->cardID, $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->OnHitEffect();
  }
}

class strongest_survive_blue extends Card{
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "strongest_survive_blue";
    $this->controller = $controller;
    $this->baseCard = new strongest_survive($this->cardID, $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->OnHitEffect();
  }
}

class vigorous_smashup extends BaseCard {
  function OnBlockResolveEffects() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    $switched = SearchCurrentTurnEffects("the_old_switcheroo_blue", 1) || SearchCurrentTurnEffects("the_old_switcheroo_blue", 2);
    Clash($this->cardID, $this->controller);
    // This card puts the revealed card on bottom, so it's possible we reveal an opponent's card due to Switcheroo.
    $revealedCardController = $this->controller;
    if ($switched) {
      $revealedCardController = $this->controller == 1 ? 2 : 1;
    }
    AddDecisionQueue("DECKCARDS", $revealedCardController, "0", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose if you want to sink <0>", 1);
    AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_sink_the_revealed_card", 1);
    AddDecisionQueue("NOPASS", $this->controller, $this->cardID, 1);
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller sunk the revealed card", 1);
    AddDecisionQueue("FINDINDICES", $revealedCardController, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $revealedCardController, "<-", 1);
    AddDecisionQueue("ADDBOTDECK", $revealedCardController, "Skip", 1);
    AddDecisionQueue("ELSE", $this->controller, "-");
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller left the revealed card there", 1);
  }

  function WonClashAbility($winnerID, $switched) {
    PlayAura("vigor", $winnerID);
  }
}

class vigorous_smashup_red extends Card {
  function __construct($controller) {
    $this->cardID = "vigorous_smashup_red";
    $this->controller = $controller;
    $this->baseCard = new vigorous_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class vigorous_smashup_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "vigorous_smashup_yellow";
    $this->controller = $controller;
    $this->baseCard = new vigorous_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class vigorous_smashup_blue extends Card {
  function __construct($controller) {
    $this->cardID = "vigorous_smashup_blue";
    $this->controller = $controller;
    $this->baseCard = new vigorous_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class tough_smashup extends BaseCard {
  function OnBlockResolveEffects() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    $switched = SearchCurrentTurnEffects("the_old_switcheroo_blue", 1) || SearchCurrentTurnEffects("the_old_switcheroo_blue", 2);
    Clash($this->cardID, $this->controller);
    // This card puts the revealed card on bottom, so it's possible we reveal an opponent's card due to Switcheroo.
    $revealedCardController = $this->controller;
    if ($switched) {
      $revealedCardController = $this->controller == 1 ? 2 : 1;
    }
    AddDecisionQueue("DECKCARDS", $revealedCardController, "0", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose if you want to sink <0>", 1);
    AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_sink_the_revealed_card", 1);
    AddDecisionQueue("NOPASS", $this->controller, $this->cardID, 1);
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller sunk the revealed card", 1);
    AddDecisionQueue("FINDINDICES", $revealedCardController, "TOPDECK", 1);
    AddDecisionQueue("MULTIREMOVEDECK", $revealedCardController, "<-", 1);
    AddDecisionQueue("ADDBOTDECK", $revealedCardController, "Skip", 1);
    AddDecisionQueue("ELSE", $this->controller, "-");
    AddDecisionQueue("WRITELOG", $this->controller, "Player $this->controller left the revealed card there", 1);
  }

  function WonClashAbility($winnerID, $switched) {
    PlayAura("toughness", $winnerID);
  }
}

class tough_smashup_red extends Card {
  function __construct($controller) {
    $this->cardID = "tough_smashup_red";
    $this->controller = $controller;
    $this->baseCard = new tough_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class tough_smashup_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "tough_smashup_yellow";
    $this->controller = $controller;
    $this->baseCard = new tough_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class tough_smashup_blue extends Card {
  function __construct($controller) {
    $this->cardID = "tough_smashup_blue";
    $this->controller = $controller;
    $this->baseCard = new tough_smashup($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    $this->baseCard->ProcessTrigger();
  }

  function WonClashAbility($winnerID, $switched) {
    $this->baseCard->WonClashAbility($winnerID, $switched);
  }
}

class energetic_impact_blue extends Card {
  function __construct($controller) {
    $this->cardID = "energetic_impact_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $combatChain;
    $num6Block = 0;
    for ($j = $start; $j < count($combatChain); $j += CombatChainPieces()) {
      if ($j == $i) continue;
      if (ModifiedPowerValue($combatChain[$j], $this->controller, "CC", "energetic_impact_blue") >= 6) ++$num6Block;
    }
    if ($num6Block) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    PlayAura("vigor", $this->controller);
    WriteLog(CardLink("energetic_impact_blue", "energetic_impact_blue") . " created a " . CardLink("vigor", "vigor") . " token");
  }
}

class tough_leather_boots extends Card {
  function __construct($controller) {
    $this->cardID = "tough_leather_boots";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    $foundVigor = SearchAurasForCardName("Vigor", $this->controller, false) != "";
    $foundToughness = SearchAurasForCardName("Toughness", $this->controller, false) != "";
    return $foundToughness && $foundVigor ? 2 : 0;
  }
}

class plate_of_tough_love extends Card {
  function __construct($controller) {
    $this->cardID = "plate_of_tough_love";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    $foundConf = SearchAurasForCardName("Confidence", $this->controller, false) != "";
    $foundToughness = SearchAurasForCardName("Toughness", $this->controller, false) != "";
    return $foundToughness && $foundConf ? 2 : 0;
  }
}

class laughing_knee_slappers extends Card {
  function __construct($controller) {
    $this->cardID = "laughing_knee_slappers";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    $foundMight = SearchAurasForCardName("Might", $this->controller, false) != "";
    $foundVigor = SearchAurasForCardName("Vigor", $this->controller, false) != "";
    return $foundMight && $foundVigor ? 2 : 0;
  }
}

class strong_stomach_for_adversity extends Card {
  function __construct($controller) {
    $this->cardID = "strong_stomach_for_adversity";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    $foundMight = SearchAurasForCardName("Might", $this->controller, false) != "";
    $foundConf = SearchAurasForCardName("Confidence", $this->controller, false) != "";
    return $foundMight && $foundConf ? 2 : 0;
  }
}

class cruel_ambition_red extends Card {
  function __construct($controller) {
    $this->cardID = "cruel_ambition_red";
    $this->controller = $controller;
  }
    
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("might", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class humble_entrance_blue extends Card {
  function __construct($controller) {
    $this->cardID = "humble_entrance_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("toughness", $this->controller, 3);
  }
}
    
class cries_of_encore_red extends Card {
  function __construct($controller) {
    $this->cardID = "cries_of_encore_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if (PlayerHasLessHealth($this->controller)) Cheer($this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $CS_CheeredThisTurn;
    if (IsHeroAttackTarget() && GetClassState($this->controller, $CS_CheeredThisTurn)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $choices = SearchMultizone($this->controller, "MYDISCARD:hasSuspense=1");
    // AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:hasSuspense=1");
    if ($choices != "") {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $choices);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an aura of suspense to be able to play from graveyard this turn");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZOP", $this->controller, "GETUNIQUEID", 1);
      AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, "$this->cardID!-!{0}", 1);
    }
  }
}

class GoonCard extends Card {
  function HaveGoons() {
    $auras = &GetAuras($this->controller);
    // Need to divide by pieces to get the actual aura count.
    $count = count($auras) / AuraPieces();
    return $count >= 3;
  }

  function PowerModifier($from = "", $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    return $this->HaveGoons() ? 3 : 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if($this->HaveGoons()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }
}

class goon_battery_blue extends GoonCard {
  function __construct($controller) {
    $this->cardID = "goon_battery_blue";
    $this->controller = $controller;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    Tap("THEIRCHAR-0", $this->controller);
  }
}

class goon_beatdown_blue extends GoonCard {
  function __construct($controller) {
    $this->cardID = "goon_beatdown_blue";
    $this->controller = $controller;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    Boo($this->controller);
  }
}

class goon_tactics_blue extends GoonCard {
  function __construct($controller) {
    $this->cardID = "goon_tactics_blue";
    $this->controller = $controller;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $deck = new Deck($defPlayer);
    $deck->DestroyTop();
  }
}

class a_good_clean_fight_red extends Card {
  function __construct($controller) {
    $this->cardID = "a_good_clean_fight_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    if (IsHeroAttackTarget()) BlindPlayer($defPlayer, excludeEquips: true);
  }

  function CombatChainCloseAbility($chainLink) {
    global $defPlayer;
    BlindPlayer($defPlayer, unblind:true);
  }

  function CardCost($from = '-') {
    return 3; //fabcube error
  }
}

class up_on_a_pedestal_blue extends Card {
  function __construct($controller) {
    $this->cardID = "up_on_a_pedestal_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger($uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $search = "MYDISCARD:type=AA;class=GUARDIAN&MYDISCARD:type=AA;talent=REVERED";
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an attack to put on top of your deck (or pass)", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZADDTOTOPDECK", $this->controller, "-", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    AddDecisionQueue("WRITELOG", $this->controller, "⤴️ <0> was put on the top of the deck.", 1);
  }
}

class in_the_palm_of_your_hand_red extends Card {
  function __construct($controller) {
    $this->cardID = "in_the_palm_of_your_hand_red";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else Draw($this->controller, false, effectSource:$this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Draw($this->controller, effectSource:$this->cardID);
  }
}

class buckwild extends BaseCard {
  function DoesAttackHaveGoAgain() {
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return true;
    }
    return false;
  }
}

class buckwild_red extends card {
  function __construct($controller) {
    $this->cardID = "buckwild_red";
    $this->controller = $controller;
    $this->baseCard = new buckwild($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class buckwild_yellow extends card {
  function __construct($controller) {
    $this->cardID = "buckwild_yellow";
    $this->controller = $controller;
    $this->baseCard = new buckwild($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class buckwild_blue extends card {
  function __construct($controller) {
    $this->cardID = "buckwild_blue";
    $this->controller = $controller;
    $this->baseCard = new buckwild($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class dis extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    global $CS_CheeredThisTurn;
    if (GetClassState($this->controller, $CS_CheeredThisTurn)) PlayAura("toughness", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function OnBlockResolveEffects($i) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
  }
}

class disarm_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "disarm_yellow";
    $this->controller = $controller;
    $this->baseCard = new dis($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($i);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain, $mainPlayer;
    if ($CombatChain->Card($target)->CardBlockValue() >= 6) {
      MZMoveCard($mainPlayer, "MYHAND", "MYBOTDECK", silent:true);
    }
  }
}

class disembody_red extends Card {
  function __construct($controller) {
    $this->cardID = "disembody_red";
    $this->controller = $controller;
    $this->baseCard = new dis($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($i);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain, $mainPlayer;
    if ($CombatChain->Card($target)->CardBlockValue() >= 6) {
      MZMoveCard($mainPlayer, "MYAURAS", "MYBOTDECK", silent:true);
    }
  }
}

class disperse_blue extends Card {
  function __construct($controller) {
    $this->cardID = "disperse_blue";
    $this->controller = $controller;
    $this->baseCard = new dis($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($i);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain, $mainPlayer;
    if ($CombatChain->Card($target)->CardBlockValue() >= 6) {
      MZMoveCard($mainPlayer, "MYARS", "MYBOTDECK", silent:true);
    }
  }
}

class rough_up extends BaseCard {
  function PowerModifier() {
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return 1;
    }
    return 0;
  }
}

class rough_up_red extends Card {
  function __construct($controller) {
    $this->cardID = "rough_up_red";
    $this->controller = $controller;
    $this->baseCard = new rough_up($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class rough_up_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rough_up_yellow";
    $this->controller = $controller;
    $this->baseCard = new rough_up($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class rough_up_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rough_up_blue";
    $this->controller = $controller;
    $this->baseCard = new rough_up($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class power_play extends Card {
  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return str_contains($from, "ARS") ? 5 : 0;
  }
}

class power_play_red extends power_play {
  function __construct($controller) {
    $this->cardID = "power_play_red";
    $this->controller = $controller;
  }
}

class power_play_yellow extends power_play {
  function __construct($controller) {
    $this->cardID = "power_play_yellow";
    $this->controller = $controller;
  }
}

class power_play_blue extends power_play {
  function __construct($controller) {
    $this->cardID = "power_play_blue";
    $this->controller = $controller;
  }
}

class never_give_up_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "never_give_up_yellow";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $combatChain;
    if ($from == "GY") {
      for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
        if ($combatChain[$i + 1] == $this->controller && (TypeContains($combatChain[$i], "A") || TypeContains($combatChain[$i], "AA"))) return false;
      }
      return true;
    }
    else return false;
  }

  function AbilityPlayableFromGraveyard($index) {
    global $defPlayer, $CS_CheeredThisTurn;
    if ($this->controller != $defPlayer) return false;
    if (GetClassState($this->controller, $CS_CheeredThisTurn) == 0) return false;
    if (!PlayerHasLessHealth($this->controller)) return false;
    return true;
  }

  function CardCost($from = '-') {
    if ($from == "GY") return 2;
    else return 0;
  }

  function PayAdditionalCosts($from, $index = '-') {
    TargetDefendingAction($this->controller, $this->cardID, true);
    AddDecisionQueue("CONVERTLAYERTOABILITY", $this->controller, $this->cardID, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("ADDBOTDECK", $this->controller, "-", 1);
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = explode("-", $target)[1];
    CombatChainDefenseModifier($index, 3);
  }

  function AbilityType($index = -1, $from = '-') {
    return $from == "GY" ? "I" : "";
  }
}

class turning_point_blue extends Card {
  function __construct($controller) {
    $this->cardID = "turning_point_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if (PlayerHasLessHealth($this->controller)) Cheer($this->controller);
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) ? 3 : 0;
  }
}

class heroic_grit_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "heroic_grit_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    PlayAura("toughness", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function IsGrantedBuff() {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return CountAura("toughness", $this->controller);
  }
}

class time_flies_when_youre_having_fun_red extends Card {
  function __construct($controller) {
    $this->cardID = "time_flies_when_youre_having_fun_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "ARS") AddCurrentTurnEffect($this->cardID, $this->controller);
    AddCurrentTurnEffect("$this->cardID-ONHIT", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return TypeContains($CombatChain->AttackCard()->ID(), "AA");
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = "-") {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS");
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "-", 1);
    return 1;
  }

  function IsCombatEffectPersistent($mode) {
    return $mode == "ONHIT";
  }

  function AddCardEffectHitTrigger($sourceID, $targetPlayer, $mode) {
    global $defPlayer;
    if((IsHeroAttackTarget() || $targetPlayer == $defPlayer) && $mode == "ONHIT" && TypeContains($sourceID, "AA")) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, "$this->cardID-$mode", "EFFECTHITEFFECT", $sourceID);
    }
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "-" ? 3 : 0;
  }
}

class the_suspense_is_killing_me_blue extends Card {
  function __construct($controller) {
    $this->cardID = "the_suspense_is_killing_me_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function AuraPowerModifiers($index, &$powerModifiers) {
    global $CS_NumAttacks;
    if (GetClassState($this->controller, $CS_NumAttacks) == 1) {
      array_push($powerModifiers, $this->cardID);
      array_push($powerModifiers, 1);
      return 1;
    }
    return 0;
  }
}

class to_be_continued_blue extends Card {
  function __construct($controller) {
    $this->cardID = "to_be_continued_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function NumUses() {
    return 1;
  }

  function PermDamagePreventionAmount($index, $type, $damage, $active, &$cancelRemove, $check) {
    global $CS_DamageTaken;
    $cancelRemove = true;
    $auras = &GetAuras($this->controller);
    $damageTaken = GetClassState($this->controller, $CS_DamageTaken);
    if($damageTaken == 0 && $auras[$index + 5] > 0) {
      if ($damage > 0) --$auras[$index + 5];
      return 1;
    }
    return 0;
  }
}

class what_happens_next_blue extends Card {
  function __construct($controller) {
    $this->cardID = "what_happens_next_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function PermCostModifier($cardID, $from) {
    global $CS_NumCostedCardsPlayed;
    if (CardCost($cardID, $from) > 0 && $from != "EQUIP" && $from != "PLAY" && GetResolvedAbilityName($cardID, $from) != "Ability") {
      if (GetClassState($this->controller, $CS_NumCostedCardsPlayed) == 0) return -1;
    }
    return 0;
  }
}

class look_tuff extends Card {
  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose if you want to pay 1 to avoid losing 1 damage.");
    AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_pay_1_to_losing_1_damage", 1);
    AddDecisionQueue("NOPASS", $this->controller, "-", 1, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, 1, 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, "-", 1);
    AddDecisionQueue("ELSE", $this->controller, "-");
    AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $this->controller, "-1", 1);
  }
}

class bluster_buff_red extends look_tuff {
  function __construct($controller) {
    $this->cardID = "bluster_buff_red";
    $this->controller = $controller;
  }
}

class chest_puff_red extends look_tuff {
  function __construct($controller) {
    $this->cardID = "chest_puff_red";
    $this->controller = $controller;
  }
}

class look_tuff_red extends look_tuff {
  function __construct($controller) {
    $this->cardID = "look_tuff_red";
    $this->controller = $controller;
  }
}

class punch_above_your_weight extends Card {
  protected $buffAmount;

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    ChooseToPay($this->controller, $this->cardID, "0,3");
    AddDecisionQueue("PASSPARAMETER", $this->controller, "COMBATCHAINLINK-0", 1);
    AddDecisionQueue("COMBATCHAINPOWERMODIFIER", $this->controller, "$this->buffAmount", 1);
  }
}

class punch_above_your_weight_red extends punch_above_your_weight {
  function __construct($controller) {
    $this->cardID = "punch_above_your_weight_red";
    $this->controller = $controller;
    $this->buffAmount = 5;
  }
}

class punch_above_your_weight_yellow extends punch_above_your_weight {
  function __construct($controller) {
    $this->cardID = "punch_above_your_weight_yellow";
    $this->controller = $controller;
    $this->buffAmount = 4;
  }
}

class punch_above_your_weight_blue extends punch_above_your_weight {
  function __construct($controller) {
    $this->cardID = "punch_above_your_weight_blue";
    $this->controller = $controller;
    $this->buffAmount = 3;
  }
}

class tiara_of_suspense extends Card {
  function __construct($controller) {
    $this->cardID = "tiara_of_suspense";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) == 0;
  }

  function DefaultActiveState() {
    return 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $suspAuras = GetSuspenseAuras($this->controller);
    if (count($suspAuras) > 0) {
      $suspAuras = implode(",", GetSuspenseAuras($this->controller));
      AddDecisionQueue("PASSPARAMETER", $this->controller, $suspAuras);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an aura to add a suspense counter to", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SUSPENSE", $this->controller, "ADD", 1);
    }
  }

  function PayAdditionalCosts($from, $index = '-') {
    DestroyCharacter($this->controller, $index);
  }
}

class virtuoso_bodice extends Card {
  function __construct($controller) {
    $this->cardID = "virtuoso_bodice";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $suspAuras = implode(",", GetSuspenseAuras($this->controller));
    if ($suspAuras != "") {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $suspAuras);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an aura to remove a suspense counter from or pass", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SUSPENSE", $this->controller, "REMOVE", 1);
      AddDecisionQueue("GAINRESOURCES", $this->controller, 2, 1);
    }
  }
}

class attention_grabbers extends Card {
  function __construct($controller) {
    $this->cardID = "attention_grabbers";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $suspAuras = implode(",", GetSuspenseAuras($this->controller));
    if ($suspAuras != "") {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $suspAuras);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an aura to remove a suspense counter from or pass", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SUSPENSE", $this->controller, "REMOVE", 1);
      AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
      AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $this->controller, 2, 1);
    }
  }
}

class boots_to_the_boards extends Card {
  function __construct($controller) {
    $this->cardID = "boots_to_the_boards";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a number of resources to pay");
    AddDecisionQueue("CHOOSENUMBER", $this->controller, "0,1,2,3", 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, "<-", 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "DIGIN,$this->cardID", 1);
  }
}

class beat_of_the_ironsong_blue extends Card {
  function __construct($controller) {
    $this->cardID = "beat_of_the_ironsong_blue";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    return !CardNameContains($CombatChain->AttackCard()->ID(), "Dawnblade", $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    global $combatChainState, $CCS_WeaponIndex, $CS_AdditionalCosts;
    $char = GetPlayerCharacter($this->controller);
    $ind = $combatChainState[$CCS_WeaponIndex];
    $numModes = $char[$ind + 3] + 1;
    $message = $numModes > 1 ? "Choose $numModes modes" : "Choose a mode";
    $modes = "Buff_power,Go_again,Block_gaining_defense,Can't_be_prevented";
    AddDecisionQueue("SETDQCONTEXT", $this->controller, $message);
    AddDecisionQueue("MULTICHOOSETEXT", $this->controller, "$numModes-$modes-$numModes");
    AddDecisionQueue("SETCLASSSTATE", $this->controller, $CS_AdditionalCosts, 1);
    AddDecisionQueue("SHOWMODES", $this->controller, $this->cardID, 1);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    foreach (explode(",", $additionalCosts) as $mode) {
      switch ($mode) {
        case "Buff_power":
          $CombatChain->AttackCard()->ModifyPower(1);
          break;
        case "Go_again":
          GiveAttackGoAgain();
          break;
        case "Block_gaining_defense":
          AddCurrentTurnEffect("$this->cardID-BLOCK", $this->controller);
          break;
        case "Can't_be_prevented":
          AddCurrentTurnEffect("$this->cardID-PREVENT", $this->controller);
          break;
      }
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class catch_of_the_day_blue extends Card {
  function __construct($controller) {
    $this->cardID = "catch_of_the_day_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    AddCurrentTurnEffect("$this->cardID-DOUBLETRIGGER", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Arrow", $this->controller);
  }

  function IsCombatEffectPersistent($mode) {
    return $mode == "DOUBLETRIGGER";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "-" ? 2 : 0;
  }
}

class hungry_for_more_red extends Card{
  public $archetype;
  function __construct($controller) {
    $this->cardID = "hungry_for_more_red";
    $this->controller = $controller;
    $this->archetype = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->archetype->HasSuspense();
  }

  function StartTurnAbility($index) {
    $this->archetype->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger($uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    GainHealth(3, $this->controller);
  }
}

class story_beats extends BaseCard {

  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger() {
    $suspAuras = implode(",", GetSuspenseAuras($this->controller));
    if (strlen($suspAuras) > 0) {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "add or remove a counter from an aura of suspense");
      AddDecisionQueue("BUTTONINPUT", $this->controller, "ADD,REMOVE", 1);
      AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      AddDecisionQueue("PASSPARAMETER", $this->controller, $suspAuras);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an aura to {0} a suspense counter", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SUSPENSE", $this->controller, "{0}", 1);
    }
  }
}

class story_beats_red extends Card {
  function __construct($controller) {
    $this->cardID = "story_beats_red";
    $this->controller = $controller;
    $this->baseCard = new story_beats($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class story_beats_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "story_beats_yellow";
    $this->controller = $controller;
    $this->baseCard = new story_beats($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class story_beats_blue extends Card {
  function __construct($controller) {
    $this->cardID = "story_beats_blue";
    $this->controller = $controller;
    $this->baseCard = new story_beats($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class old_favorite_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "old_favorite_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $CS_CheeredThisTurn;
    if (GetClassState($this->controller, $CS_CheeredThisTurn)) {
      PlayAura("toughness", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    if ($CombatChain->Card($target)->CardBlockValue() >= 6) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return $from == "CHAINCLOSING" && $stillOnCombatChain && SearchCurrentTurnEffects($this->cardID, $this->controller, true) ? "BOTDECK" : "GY";
  }
}

class helm_of_the_adored extends Card {
  function __construct($controller) {
    $this->cardID = "helm_of_the_adored";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Cheer($this->controller);
  }
}

class horns_of_the_despised extends Card {
  function __construct($controller) {
    $this->cardID = "horns_of_the_despised";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Boo($this->controller);
  }
}

class hold_firm extends Card {
  function __construct($controller) {
    $this->cardID = "hold_firm";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 3;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return !PlayerHasLessHealth($this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    DestroyCharacter($this->controller, $index);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("toughness", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class mightybone_knuckles extends Card {
  function __construct($controller) {
    $this->cardID = "mightybone_knuckles";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 3;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    return !PlayerHasLessHealth($otherPlayer);
  }

  function PayAdditionalCosts($from, $index = '-') {
    DestroyCharacter($this->controller, $index);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("might", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class tame_the_beastly_behavior_red extends Card {
  function __construct($controller) {
    $this->cardID = "tame_the_beastly_behavior_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVILED", $defPlayer) && IsHeroAttackTarget()) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ATTACKTRIGGER");
    }
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $CombatChain;
    $CombatChain->AttackCard()->ModifyPower(1);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVILED", $defPlayer) && IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRARS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose which card you want to put on the bottom of the deck", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZADDZONE", $this->controller, "THEIRBOTDECK", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
  }
}

class shining_courage_red extends Card {
  function __construct($controller) {
    $this->cardID = "shining_courage_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $index = explode("-", $target)[1];
    CombatChainDefenseModifier($index, 3);
    Cheer($this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    TargetDefendingAction($this->controller, $this->cardID, true);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $combatChain;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $this->controller && (TypeContains($combatChain[$i], "A") || TypeContains($combatChain[$i], "AA"))) return false;
    }
    return true;
  }
}

class empowering_ruckus_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "empowering_ruckus_yellow";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) ? 1 : 0;
  }
}

class fight_from_behind extends BaseCard {
  function ProcessTrigger() {
    if (PlayerHasLessHealth($this->controller)) Cheer($this->controller);
  }

  function AddTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }
}

class fight_from_behind_red extends Card {
  function __construct($controller) {
    $this->cardID = "fight_from_behind_red";
    $this->controller = $controller;
    $this->baseCard = new fight_from_behind($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class fight_from_behind_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "fight_from_behind_yellow";
    $this->controller = $controller;
    $this->baseCard = new fight_from_behind($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class fight_from_behind_blue extends Card {
  function __construct($controller) {
    $this->cardID = "fight_from_behind_blue";
    $this->controller = $controller;
    $this->baseCard = new fight_from_behind($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class whos_the_tough_guy extends BaseCard {
  function CombatChainCloseAbility($chainLink) {
    global $chainLinkSummary, $defPlayer, $chainLinks;
    if (SearchCurrentTurnEffects($this->cardID, $this->controller, true) && $chainLinkSummary[$chainLink * ChainLinkSummaryPieces()] == 0 && $chainLinks[$chainLink][0] == $this->cardID && $chainLinks[$chainLink][1] == $this->controller) {
        PlayAura("toughness", $defPlayer);
    }
  }

  function PlayAbility() {
    if (IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
}

class whos_the_tough_guy_red extends Card {
  function __construct($controller) {
    $this->cardID = "whos_the_tough_guy_red";
    $this->controller = $controller;
    $this->baseCard = new whos_the_tough_guy($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class whos_the_tough_guy_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "whos_the_tough_guy_yellow";
    $this->controller = $controller;
    $this->baseCard = new whos_the_tough_guy($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class whos_the_tough_guy_blue extends Card {
  function __construct($controller) {
    $this->cardID = "whos_the_tough_guy_blue";
    $this->controller = $controller;
    $this->baseCard = new whos_the_tough_guy($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class darling_of_the_crowd_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "darling_of_the_crowd_yellow";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) ? 1 : 0;
  }
}

class not_so_mighty_blue extends Card {
  function __construct($controller) {
    $this->cardID = "not_so_mighty_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $mainPlayer;
    $mainChar = GetPlayerCharacter($mainPlayer);
    if (!IsAllyAttacking() && TalentContains($mainChar[0], "REVILED", $mainPlayer)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS:isSameName=might");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "toughness", 1);
  }
}

class tear_down_the_idols_red extends Card {
  function __construct($controller) {
    $this->cardID = "tear_down_the_idols_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVERED", $defPlayer) && IsHeroAttackTarget()) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ATTACKTRIGGER");
    }
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $defPlayer;
    Intimidate($defPlayer);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (TalentContains($defChar[0], "REVERED", $defPlayer) && IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    PummelHit($defPlayer);
  }
}

class arrogant_showboating_blue extends Card {
  function __construct($controller) {
    $this->cardID = "arrogant_showboating_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $count = CountBlockingCards();
    PlayAura("might", $this->controller, $count, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class vigorous_roar_red extends Card {
  function __construct($controller) {
    $this->cardID = "vigorous_roar_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) {
        PlayAura("vigor", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
      }
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class clench_the_upper_hand extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, 1);
  }

  function ProcessTrigger() {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    if (PlayerHasLessHealth($otherPlayer)) BOO($this->controller);
  }
}

class clench_the_upper_hand_red extends Card {
  function __construct($controller) {
    $this->cardID = "clench_the_upper_hand_red";
    $this->controller = $controller;
    $this->baseCard = new clench_the_upper_hand($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-'): void {
    $this->baseCard->ProcessTrigger();
  }
}

class clench_the_upper_hand_blue extends Card {
  function __construct($controller) {
    $this->cardID = "clench_the_upper_hand_blue";
    $this->controller = $controller;
    $this->baseCard = new clench_the_upper_hand($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-'): void {
    $this->baseCard->ProcessTrigger();
  }
}

class clench_the_upper_hand_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "clench_the_upper_hand_yellow";
    $this->controller = $controller;
    $this->baseCard = new clench_the_upper_hand($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-'): void {
    $this->baseCard->ProcessTrigger();
  }
  
  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->PlayAbility();
  }
}

class instill_fear extends BaseCard {
  function PlayAbility() {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    global $defPlayer;
    Intimidate($defPlayer);
  }
}

class instill_fear_red extends Card {
  function __construct($controller) {
    $this->cardID = "instill_fear_red";
    $this->controller = $controller;
    $this->baseCard = new instill_fear($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class instill_fear_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "instill_fear_yellow";
    $this->controller = $controller;
    $this->baseCard = new instill_fear($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class instill_fear_blue extends Card {
  function __construct($controller) {
    $this->cardID = "instill_fear_blue";
    $this->controller = $controller;
    $this->baseCard = new instill_fear($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class take_that extends BaseCard {
  function CombatChainCloseAbility($chainLink) {
    global $chainLinkSummary, $defPlayer, $chainLinks;
    if (SearchCurrentTurnEffects($this->cardID, $this->controller, true) && $chainLinkSummary[$chainLink * ChainLinkSummaryPieces()] == 0 && $chainLinks[$chainLink][0] == $this->cardID && $chainLinks[$chainLink][1] == $this->controller) {
        PlayAura("might", $defPlayer);
    }
  }

  function PlayAbility() {
    if (IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
}

class take_that_red extends Card {
  function __construct($controller) {
    $this->cardID = "take_that_red";
    $this->controller = $controller;
    $this->baseCard = new take_that($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class take_that_blue extends Card {
  function __construct($controller) {
    $this->cardID = "take_that_blue";
    $this->controller = $controller;
    $this->baseCard = new take_that($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class take_that_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "take_that_yellow";
    $this->controller = $controller;
    $this->baseCard = new take_that($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }
}

class disdainful_delight_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "disdainful_delight_yellow";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_BooedThisTurn;
    return GetClassState($this->controller, $CS_BooedThisTurn) ? 1 : 0;
  }
}

class not_so_tuff_blue extends Card {
  function __construct($controller) {
    $this->cardID = "not_so_tuff_blue";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $mainPlayer;
    $mainChar = GetPlayerCharacter($mainPlayer);
    if (!IsAllyAttacking() && TalentContains($mainChar[0], "REVERED", $mainPlayer)) {
      AddLayer("REVERED", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS:isSameName=toughness");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "might", 1);
  }
}

class parched_terrain_red extends Card {
  function __construct($controller) {
    $this->cardID = "parched_terrain_red";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $auraIndex = SearchAurasForUniqueID($uniqueID, $this->controller);
    $auras = &GetAuras($this->controller);
    ++$auras[$auraIndex + 2];
    $sandCounters = $auras[$auraIndex + 2];
    $graveyard = &GetDiscard($this->controller);
    $redCardsInGraveyard = 0;
    for ($j = 0; $j < count($graveyard); $j++) {
      if (ColorContains($graveyard[$j], 1, $this->controller)) $redCardsInGraveyard++;
    }
    if ($redCardsInGraveyard < $sandCounters) {
      WriteLog("Not enough red cards in graveyard to satisfy " . CardLink("parched_terrain_red", "parched_terrain_red") . ". Aura destroyed.");
      DestroyAuraUniqueID($this->controller, $uniqueID);
    } else {
      for ($j = 0; $j < $sandCounters; $j++) {
        AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:pitch=1");
        AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a red card to banish from your graveyard");
        AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
        AddDecisionQueue("MZBANISH", $this->controller, "GY,-", 1);
        AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
      }
    }
  }
}

class shoot_your_mouth_off extends Card {
  function CombatChainCloseAbility($chainLink) {
    global $chainLinkSummary, $defPlayer, $chainLinks;
    if (SearchCurrentTurnEffects($this->cardID, $this->controller, true) && $chainLinkSummary[$chainLink * ChainLinkSummaryPieces()] == 0 && $chainLinks[$chainLink][0] == $this->cardID && $chainLinks[$chainLink][1] == $this->controller) {
        PlayAura("confidence", $defPlayer);
    }
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    if (IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
}

class shoot_your_mouth_off_red extends shoot_your_mouth_off {
  function __construct($controller) {
    $this->cardID = "shoot_your_mouth_off_red";
    $this->controller = $controller;
  }
}

class shoot_your_mouth_off_yellow extends shoot_your_mouth_off {
  function __construct($controller) {
    $this->cardID = "shoot_your_mouth_off_yellow";
    $this->controller = $controller;
  }
}

class shoot_your_mouth_off_blue extends shoot_your_mouth_off {
  function __construct($controller) {
    $this->cardID = "shoot_your_mouth_off_blue";
    $this->controller = $controller;
  }
}

class give_em_a_piece_of_your_mind extends Card {
  function CombatChainCloseAbility($chainLink) {
    global $chainLinkSummary, $defPlayer, $chainLinks;
    if (SearchCurrentTurnEffects($this->cardID, $this->controller, true) && $chainLinkSummary[$chainLink * ChainLinkSummaryPieces()] == 0 && $chainLinks[$chainLink][0] == $this->cardID && $chainLinks[$chainLink][1] == $this->controller) {
        PlayAura("vigor", $defPlayer);
    }
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    if (IsHeroAttackTarget()) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
}

class give_em_a_piece_of_your_mind_red extends give_em_a_piece_of_your_mind {
  function __construct($controller) {
    $this->cardID = "give_em_a_piece_of_your_mind_red";
    $this->controller = $controller;
  }
}

class give_em_a_piece_of_your_mind_yellow extends give_em_a_piece_of_your_mind {
  function __construct($controller) {
    $this->cardID = "give_em_a_piece_of_your_mind_yellow";
    $this->controller = $controller;
  }
}

class give_em_a_piece_of_your_mind_blue extends give_em_a_piece_of_your_mind {
  function __construct($controller) {
    $this->cardID = "give_em_a_piece_of_your_mind_blue";
    $this->controller = $controller;
  }
}

class prime_the_crowd extends Card {
  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);

    $p1Char = GetPlayerCharacter(1);
    $p2Char = GetPlayerCharacter(2);

    if (TalentContains($p1Char[0], "REVERED", 1)) {
      Cheer(1);
    }
    if (TalentContains($p2Char[0], "REVERED", 2)) {
      Cheer(2);
    }

    if (TalentContains($p1Char[0], "REVILED", 1)) {
      BOO(1);
    }
    if (TalentContains($p2Char[0], "REVILED", 2)) {
      BOO(2);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return TypeContains($CombatChain->AttackCard()->ID(), "AA");
  }
}

class prime_the_crowd_red extends prime_the_crowd {
  function __construct($controller) {
    $this->cardID = "prime_the_crowd_red";
    $this->controller = $controller;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  }
}

class prime_the_crowd_yellow extends prime_the_crowd {
  function __construct($controller) {
    $this->cardID = "prime_the_crowd_yellow";
    $this->controller = $controller;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class prime_the_crowd_blue extends prime_the_crowd {
  function __construct($controller) {
    $this->cardID = "prime_the_crowd_blue";
    $this->controller = $controller;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class gallow_end_of_the_line_yellow extends Card {
  // I'd definitely like to make a gravy ally base class in the long run
  function __construct($controller) {
    $this->cardID = "gallow_end_of_the_line_yellow";
    $this->controller = $controller;
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY") {
      $allies = &GetAllies($this->controller);
      Tap("MYALLY-$index", $this->controller);
      $ally[$index + 1] = 2;//Not once per turn effects
    }
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return ($from != "PLAY") ? "" : "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0) {
    global $CS_NumActionsPlayed;
    $names = "";
    $canAttack = CanAttack($this->cardID, "PLAY", $index, "MYALLY", type:"AA");
    if (SearchHand($this->controller, hasWateryGrave: true) != "") $names = "Instant";
    $allies = &GetAllies($this->controller);
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, piece: $CS_NumActionsPlayed) >= 1) {
      return $names;
    } else if ($canAttack) {
      $names != "" ? $names .= ",Attack" : $names = "-,Attack";
    }
    return $names;
  }

  function GoesOnCombatChain($phase, $from) {
    return GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function AbilityCost() {
    return GetResolvedAbilityType($this->cardID, "PLAY") == "AA" ? 1 : 0;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $abilityType = GetResolvedAbilityType($this->cardID, $from);
    if ($from == "PLAY" && $abilityType == "I") AddCurrentTurnEffect($this->cardID, $otherPlayer);
  }

  function HasWateryGrave() {
    return true;
  }

  function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
    $allies = GetAllies($this->controller);
    if (GetResolvedAbilityType($this->cardID, $from) == "I") {
      AddDecisionQueue("FINDINDICES", $this->controller, "HANDWATERYGRAVE,-,NOPASS");
      AddDecisionQueue("REVERTGAMESTATEIFNULL", $this->controller, "You don't have any watery grave cards in hand to discard!", 1);
      AddDecisionQueue("CHOOSEHAND", $this->controller, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $this->controller, "-", 1);
      AddDecisionQueue("DISCARDCARD", $this->controller, "HAND-" . $this->controller, 1);
      AddDecisionQueue("PASSPARAMETER", $this->controller, $allies[$zoneIndex + 5], 1);
      AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
    }
  }
}

class channel_the_tranquil_domain_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "channel_the_tranquil_domain_yellow";
    $this->controller = $controller;
  }

  function Trigger($uniqueID) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $index = SearchAurasForUniqueID($uniqueID, $this->controller);
    $myAuras = GetAuras($this->controller);
    $theirAuras = GetAuras($otherPlayer);
    if (count($myAuras) + count($theirAuras) > AuraPieces()) { //check if there are any legal targets
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS&MYAURAS", 1);
      if ($index != -1) AddDecisionQueue("DEDUPEMULTIZONEINDS", $this->controller, "MYAURAS-$index", 1);
      else AddDecisionQueue("DEDUPEMULTIZONEINDS", $this->controller, "-", 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose target aura", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
      AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    }
  }

  function BeginningActionPhaseAbility($index) {
    $auras = GetAuras($this->controller);
    $uid = $auras[$index + 6];
    $this->Trigger($uid);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $auras = GetAuras($this->controller);
    $uid = $auras[count($auras) - AuraPieces() + 6];
    $this->Trigger($uid);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "CHANNEL") {
      ChannelTalent($target, "EARTH");
    }
    else {
      $targetPlayer = explode("-", $target)[0];
      $targetUID = explode("-", $target)[1];
      $targetIndex = SearchAurasForUniqueID($targetUID, $targetPlayer);
      $targetMZIndex = $targetPlayer == $this->controller ? "MYAURAS-$targetIndex" : "THEIRAURAS-$targetIndex";
      AddDecisionQueue("PASSPARAMETER", $this->controller, $targetMZIndex);
      AddDecisionQueue("MZBOTTOM", $this->controller, "-", 1);
    }
  }

  function BeginEndTurnAbilities($index) {
    $auras = GetAuras($this->controller);
    AddLayer("TRIGGER", $this->controller, $auras[$index], $auras[$index+6], "CHANNEL");
  }
}

class mage_hunter_arrow_red extends Card {
  function __construct($controller) {
    $this->cardID = "mage_hunter_arrow_red";
    $this->controller = $controller;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && (ClassContains($defChar[0], "RUNEBLADE", $defPlayer) || ClassContains($defChar[0], "WIZARD", $defPlayer))) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, 1, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return "I,AA";
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0) {
    global $mainPlayer, $defPlayer, $layers, $combatChain, $actionPoints;
    $layerCount = count($layers);
    $foundNullTime = SearchItemForModalities(GamestateSanitize(NameOverride($this->cardID)), $mainPlayer, "null_time_zone_blue") != -1;
    $foundNullTime = $foundNullTime || SearchItemForModalities(GamestateSanitize(NameOverride($this->cardID)), $defPlayer, "null_time_zone_blue") != -1;
    $arsenal = GetArsenal($this->controller);
    if ($arsenal[$index + 1] == "DOWN") return "-,Attack";
    $names = "Ability";
    if($foundNullTime && $from == "ARS") return $names;
    if ($this->controller == $mainPlayer && count($combatChain) == 0 && $layerCount <= LayerPieces() && $actionPoints > 0){
      $warmongersPeace = SearchCurrentTurnEffects("WarmongersPeace", $this->controller);
      $underEdict = SearchCurrentTurnEffects("imperial_edict_red-" . GamestateSanitize(CardName($this->cardID)), $this->controller);
      if (!$warmongersPeace && !$underEdict && CanAttack($this->cardID, $from, $index, type:"AA")) {
        if (!SearchCurrentTurnEffects("oath_of_loyalty_red", $this->controller) || SearchCurrentTurnEffects("fealty", $this->controller)) $names .= ",Attack";
      }
    }
    return $names;
  }

  function GoesOnCombatChain($phase, $from) {
    global $layers;
    return ($phase == "B" && count($layers) == 0) || GetResolvedAbilityType($this->cardID, $from) == "AA";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CanPlayAsInstant($index = -1, $from = '') {
    return ($from == "ARS");
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "ARS") == "I" && $from == "ARS") return 0;
    return 1;
  }

  function AddPrePitchDecisionQueue($from, $index = -1) {
    global $CS_NumActionsPlayed;
    $names = GetAbilityNames($this->cardID, $index, $from);
    $names = str_replace("-,", "", $names);
    if (SearchCurrentTurnEffects("red_in_the_ledger_red", $this->controller) && GetClassState($this->controller, $CS_NumActionsPlayed) >= 1) {
      AddDecisionQueue("SETABILITYTYPEABILITY", $this->controller, $this->cardID);
    } elseif ($names != "" && $from == "ARS") {
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose to play the ability or attack");
      AddDecisionQueue("BUTTONINPUT", $this->controller, $names);
      AddDecisionQueue("SETABILITYTYPE", $this->controller, $this->cardID);
    } else {
      AddDecisionQueue("SETABILITYTYPEATTACK", $this->controller, $this->cardID);
    }
    AddDecisionQueue("NOTEQUALPASS", $this->controller, "Ability");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("DISCARDCARD", $this->controller, "ARS-$this->cardID", 1);
    AddDecisionQueue("CONVERTLAYERTOABILITY", $this->controller, $this->cardID, 1);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, &$remove) {
    if ($type == "ARCANE") {
      $remove = true;
      return 3;
    }
    return 0;
  }
}

class overbearing_presence extends Card {
  function __construct($controller) {
    $this->cardID = "overbearing_presence";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 3;
  }

  function PayAdditionalCosts($from, $index = '-') {
    DestroyCharacter($this->controller, $index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return false;
    }
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("vigor", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class stand_strong extends Card {
  function __construct($controller) {
    $this->cardID = "stand_strong";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function AbilityCost() {
    return 3;
  }

  function PayAdditionalCosts($from, $index = '-') {
    DestroyCharacter($this->controller, $index);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $pitch = GetPitch($this->controller);
    $suspAuras = GetSuspenseAuras($this->controller);
    return count($suspAuras) == 0;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("confidence", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class bark_obscenities_red extends Card {
  function __construct($controller) {
    $this->cardID = "bark_obscenities_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    return IsHeroAttackTarget() && ClassContains($defChar[0], "GUARDIAN", $defPlayer);
  }
}

class disturb_the_peace_red extends Card {
  function __construct($controller) {
    $this->cardID = "disturb_the_peace_red";
    $this->controller = $controller;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && ClassContains($defChar[0], "GUARDIAN", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWCHOSENCARD", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }
}

class asking_for_trouble_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "asking_for_trouble_yellow";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    PlayAura("vigor", $otherPlayer, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class familiar_stench_red extends Card {
  function __construct($controller) {
    $this->cardID = "familiar_stench_red";
    $this->controller = $controller;
  }

  function AttackGetsBlockedEffect($start) {
    global $combatChain, $defPlayer;
    for ($i = $start; $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i+1] == $defPlayer && ClassContains($combatChain[$i], "BRUTE", $defPlayer)) {
        AddLayer("TRIGGER", $this->controller, $this->cardID);
        return;
      }
    }
    return;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("vigor", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class familiar_story_red extends Card {
  function __construct($controller) {
    $this->cardID = "familiar_story_red";
    $this->controller = $controller;
  }

  function AttackGetsBlockedEffect($start) {
    global $combatChain, $defPlayer;
    for ($i = $start; $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i+1] == $defPlayer && ClassContains($combatChain[$i], "GUARDIAN", $defPlayer)) {
        AddLayer("TRIGGER", $this->controller, $this->cardID);
        return;
      }
    }
    return;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("confidence", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class bash_guardian_red extends Card {
  function __construct($controller) {
    $this->cardID = "bash_guardian_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $combatChain, $defPlayer;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && ClassContains($combatChain[$i], "GUARDIAN", $defPlayer)) return 1;
    }
    return 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && ClassContains($defChar[0], "GUARDIAN", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS:type=T");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWCHOSENCARD", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }
}

class high_pitched_howl extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
    return "";
  }

  function ProcessTrigger() {
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) {
        PlayAura("vigor", $this->controller, 1, true, effectSource:$this->cardID, effectController:$this->controller);
        return;
      };
    }
    return;
  }
}

class high_pitched_howl_red extends Card {
  function __construct($controller) {
    $this->cardID = "high_pitched_howl_red";
    $this->controller = $controller;
    $this->baseCard = new high_pitched_howl($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessTrigger();
  }
}

class high_pitched_howl_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "high_pitched_howl_yellow";
    $this->controller = $controller;
    $this->baseCard = new high_pitched_howl($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessTrigger();
  }
}

class high_pitched_howl_blue extends Card {
  function __construct($controller) {
    $this->cardID = "high_pitched_howl_blue";
    $this->controller = $controller;
    $this->baseCard = new high_pitched_howl($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessTrigger();
  }
}

class dramatic_pause_red extends Card {
  function __construct($controller) {
    $this->cardID = "dramatic_pause_red";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    TargetDefendingAction($this->controller, $this->cardID);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = explode("-", $target)[1];
    CombatChainDefenseModifier($index, 3);
  }
}

class dramatic_pause_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "dramatic_pause_yellow";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    TargetDefendingAction($this->controller, $this->cardID);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = explode("-", $target)[1];
    CombatChainDefenseModifier($index, 2);
  }
}

class dramatic_pause_blue extends Card {
  function __construct($controller) {
    $this->cardID = "dramatic_pause_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function EntersArenaAbility() {
    TargetDefendingAction($this->controller, $this->cardID);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $index = explode("-", $target)[1];
    CombatChainDefenseModifier($index, 1);
  }
}

class flex extends BaseCard {
  function sixPower() {
    return CachedTotalPower() >= 6;
  }
}

class flex_speed_red extends Card {
  function __construct($controller) {
    $this->cardID = "flex_speed_red";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->sixPower();
  }
}

class flex_speed_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "flex_speed_yellow";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->sixPower();
  }
}

class flex_speed_blue extends Card {
  function __construct($controller) {
    $this->cardID = "flex_speed_blue";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->sixPower();
  }
}

class flex_strength_red extends Card {
  function __construct($controller) {
    $this->cardID = "flex_strength_red";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->sixPower() ? 3 : 0;
  }
}

class flex_strength_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "flex_strength_yellow";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->sixPower() ? 3 : 0;
  }
}

class flex_strength_blue extends Card {
  function __construct($controller) {
    $this->cardID = "flex_strength_blue";
    $this->controller = $controller;
    $this->baseCard = new flex($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->sixPower() ? 3 : 0;
  }
}

class turn_heads_blue extends Card {
  function __construct($controller) {
    $this->cardID = "turn_heads_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger($uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $otherChar = GetPlayerCharacter($otherPlayer);
    if (ClassContains($otherChar[0], "BRUTE", $otherPlayer)) {
      Tap("THEIRCHAR-0", $this->controller);
      if ($otherPlayer == $mainPlayer) AddCurrentTurnEffect($this->cardID, $otherPlayer);
      else AddNextTurnEffect($this->cardID, $otherPlayer);
    }
  }
}

class who_blinks_first_blue extends Card {
  function __construct($controller) {
    $this->cardID = "who_blinks_first_blue";
    $this->controller = $controller;
    $this->baseCard = new aura_of_suspense($this->cardID, $this->controller);
  }

  function HasSuspense() {
    return $this->baseCard->HasSuspense();
  }

  function StartTurnAbility($index) {
    return $this->baseCard->StartTurnAbility($index);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase) {
    if ($mainPhase) AddLayer("TRIGGER", $this->controller, $this->cardID);
    else $this->ProcessTrigger($uniqueID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $otherChar = GetPlayerCharacter($otherPlayer);
    if (ClassContains($otherChar[0], "GUARDIAN", $otherPlayer)) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS");
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWCHOSENCARD", $this->controller, "<-", 1);
      AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
    }
  }
}

class full_of_bravado extends BaseCard {
  function ProcessTrigger() {
    $suspAuras = GetSuspenseAuras($this->controller);
    if (count($suspAuras) > 0) {
      PlayAura("confidence", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
    }
  }

  function AddTrigger() {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }
}

class full_of_bravado_red extends Card {
  function __construct($controller) {
    $this->cardID = "full_of_bravado_red";
    $this->controller = $controller;
    $this->baseCard = new full_of_bravado($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class full_of_bravado_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "full_of_bravado_yellow";
    $this->controller = $controller;
    $this->baseCard = new full_of_bravado($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class full_of_bravado_blue extends Card {
  function __construct($controller) {
    $this->cardID = "full_of_bravado_blue";
    $this->controller = $controller;
    $this->baseCard = new full_of_bravado($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->AddTrigger();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->AddTrigger();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class bash_brute_red extends Card {
  function __construct($controller) {
    $this->cardID = "bash_brute_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $combatChain, $defPlayer;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && ClassContains($combatChain[$i], "BRUTE", $defPlayer)) return 1;
    }
    return 0;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $defChar = GetPlayerCharacter($defPlayer);
    if (IsHeroAttackTarget() && ClassContains($defChar[0], "BRUTE", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS:type=T");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWCHOSENCARD", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }
}

class sit_red extends Card {
  function __construct($controller) {
    $this->cardID = "sit_red";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $CombatChain, $mainPlayer;
    if (ClassContains($CombatChain->AttackCard()->ID(), "BRUTE", $mainPlayer)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    $CombatChain->Card($target)->ModifyDefense(3);
  }
}

class unwavering_resolve_red extends Card {
  function __construct($controller) {
    $this->cardID = "unwavering_resolve_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    $deck = GetDeck($this->controller);
    return count($deck) == 0 ? 4 : 0;
  }

  function DoesAttackHaveGoAgain() {
    return NumCardsDefended() > 3;
  }
}

class right_behind_you extends BaseCard {
  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $combatChain;
    if ($combatChain[$i + 2] != "HAND") return;
    if ($blockedFromHand >= 2) AddLayer("TRIGGER", $this->controller, $this->cardID, $i);
  }

  function ProcessTrigger($target) {
    global $CombatChain;
    $CombatChain->Card($target)->ModifyDefense(1);
    PlayerOpt($this->controller, 1, false);
  }
}

class right_behind_you_red extends Card {
  function __construct($controller) {
    $this->cardID = "right_behind_you_red";
    $this->controller = $controller;
    $this->baseCard = new right_behind_you($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($blockedFromHand, $i, $start);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target);
  }
}

class right_behind_you_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "right_behind_you_yellow";
    $this->controller = $controller;
    $this->baseCard = new right_behind_you($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($blockedFromHand, $i, $start);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target);
  }
}

class right_behind_you_blue extends Card {
  function __construct($controller) {
    $this->cardID = "right_behind_you_blue";
    $this->controller = $controller;
    $this->baseCard = new right_behind_you($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects($blockedFromHand, $i, $start);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($target);
  }
}
?>