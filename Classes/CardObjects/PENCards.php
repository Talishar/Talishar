<?php

class synapse_sparkcap extends Card
{
  function __construct($controller)
  {
    $this->cardID = "synapse_sparkcap";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-')
  {
    return "A";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false)
  {
    if (CheckTapped("MYCHAR-$index", $this->controller) || SearchMultizone($this->controller, "MYHAND:subtype=Evo") == "")
      return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-')
  {
    Tap("MYCHAR-$index", $this->controller);
    MZChooseAndBanish($this->controller, "MYHAND:subtype=Evo", "HAND,-");
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    PlayAura("ponder", $this->controller, 1, true);
  }
}

class savage_claw extends Card
{
  function __construct($controller)
  {
    $this->cardID = "savage_claw";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-')
  {
    return "AA";
  }

  function AbilityCost()
  {
    return 2;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false)
  {
    if (CheckTapped("MYCHAR-$index", $this->controller))
      return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-')
  {
    Tap("MYCHAR-$index", $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    if (SearchCardList($additionalCosts, $this->controller, minAttack: 6) != "")
      AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false)
  {
    return true;
  }

  function EffectPowerModifier($param, $attached = false)
  {
    return 1;
  }
}

class grimoire_of_fellingsong extends Card
{
  function __construct($controller)
  {
    $this->cardID = "grimoire_of_fellingsong";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-')
  {
    return "I";
  }

  function AbilityCost()
  {
    return 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    PlayAura("runechant", $this->controller, 1, true);
  }

  function EquipPayAdditionalCosts($cardIndex = '-')
  {
    DestroyCharacter($this->controller, $cardIndex);
  }
}

class boltn_boots extends Card
{
  function __construct($controller)
  {
    $this->cardID = "boltn_boots";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-')
  {
    return "AR";
  }

  function AbilityCost()
  {
    return 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    GiveAttackGoAgain();
  }

  function EquipPayAdditionalCosts($cardIndex = '-')
  {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false)
  {
    global $CombatChain;
    return !$CombatChain->HasCurrentLink() || CachedTotalPower() <= PowerValue($CombatChain->AttackCard()->ID(), $this->controller, "CC") || !CardSubType($CombatChain->AttackCard()->ID()) == "Arrow";
  }
}

class magmatic_carapace extends Card
{

  function __construct($controller)
  {
    $this->cardID = "magmatic_carapace";
    $this->controller = $controller;
  }

  function CardPlayTrigger($cardID, $from, $index)
  {
    $char = GetPlayerCharacter($this->controller);
    if (!CheckTapped("MYCHAR-$index", $this->controller)) {
      if (SubtypeContains($cardID, "Aura", $this->controller))
        AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "-", $char[$index + 11]);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-')
  {
    $index = SearchCharacterForUniqueID($uniqueID, $this->controller);
    $char = GetPlayerCharacter($this->controller);
    if ($index == -1)
      return;
    if (CheckTapped("MYCHAR-$index", $this->controller))
      return;
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "if you want to tap " . CardLink("magmatic_carapace", "magmatic_carapace") . " and pay 1 to create a " . CardLink("seismic_surge", "seismic_surge"));
    AddDecisionQueue("YESNO", $this->controller, "-", 1);
    AddDecisionQueue("NOPASS", $this->controller, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "MYCHAR-$index", 1);
    AddDecisionQueue("MZTAP", $this->controller, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "1", 1);
    AddDecisionQueue("PAYRESOURCES", $this->controller, "<-", 1);
    AddDecisionQueue("WRITELOG", $this->controller, CardLink("magmatic_carapace", "magmatic_carapace") . " created a " . CardLink("seismic_surge", "seismic_surge"), 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "seismic_surge", 1);
  }
}

class frosthaven_sheath_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "frosthaven_sheath_red";
    $this->controller = $controller;
  }
  function SpecialType($from = '', $additionalCosts = '-')
  {
    return "DR";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    global $defPlayer, $mainPlayer;
    if (SearchCardList($additionalCosts, $this->controller, talent: "ICE") != "")
      PlayAura("frostbite", $mainPlayer, effectController: $defPlayer);
  }
}

class leaven_sheath_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "leaven_sheath_red";
    $this->controller = $controller;
  }

  function SpecialType($from = '', $additionalCosts = '-')
  {
    return "DR";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    global $defPlayer;
    if (SearchCardList($additionalCosts, $this->controller, talent: "EARTH") != "")
      PlayAura("embodiment_of_earth", $defPlayer);
  }
}

class stormwind_sheath_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "stormwind_sheath_red";
    $this->controller = $controller;
  }

  function SpecialType($from = '', $additionalCosts = '-')
  {
    return "DR";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    global $defPlayer;
    if (SearchCardList($additionalCosts, $this->controller, talent: "LIGHTNING") != "")
      PlayAura("embodiment_of_lightning", $defPlayer);
  }
}

class trench_of_watery_depths extends Card
{
  function __construct($controller)
  {
    $this->cardID = "trench_of_watery_depths";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start)
  {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-')
  {
    $search = "MYDISCARD:pitch=3";
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a blue card in your graveyard to pitch");
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("PITCHCARD", $this->controller, "DISCARD", 1);
  }
}

class elemental_strike_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "elemental_strike_red";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false)
  {
    $myHand = &GetHand(player: $this->controller);
    if ($from == "HAND" && count($myHand) < 2)
      return true;
    return false;
  }

  function PayAdditionalCosts($from, $index = '-')
  {
    MZChooseAndBanish($this->controller, "MYHAND", "HAND,-");
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    $isEarth = SearchCardList($additionalCosts, $this->controller, talent: "EARTH");
    $isLightning = SearchCardList($additionalCosts, $this->controller, talent: "LIGHTNING");
    $isIce = SearchCardList($additionalCosts, $this->controller, talent: "ICE");

    if ($isEarth != '') {
      AddDecisionQueue("POWERMODIFIER", $this->controller, "2", 1);
    }
    if ($isLightning != '') {
      GiveAttackGoAgain();
    }
    if ($isIce != '') {
      GiveAttackDominate();
    }
  }
}

class colors_of_aria_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "colors_of_aria_red";
    $this->controller = $controller;
  }

  function SpecialTalent($from = "", $index = "")
  {
    return "ELEMENTAL,ICE,EARTH,LIGHTNING";
  }

  function PitchAbility($from = "", $index = "")
  {
    TalentOverride("colors_of_aria_red", $this->controller);
  }
}

class unyielding_grip extends Card
{
  function __construct($controller)
  {
    $this->cardID = "unyielding_grip";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index)
  {
    $myHand = &GetHand($this->controller);
    if (count($myHand) === 0) {
      return 3;
    }
    return 0;
  }
}

class comeback_kicks extends Card
{
  function __construct($controller)
  {
    $this->cardID = "comeback_kicks";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    if (PlayerHasLessHealth($this->controller)) {
      $message = "if you want to destroy " . Cardlink($this->cardID, $this->cardID);
      if ($this->controller != $mainPlayer) $message .= " (you won't gain an action point on your opponent's turn";
      $index = FindCharacterIndex($this->controller, $this->cardID);
      AddDecisionQueue("YESNO", $this->controller, $message);
      AddDecisionQueue("NOPASS", $this->controller, "-");
      AddDecisionQueue("PASSPARAMETER", $this->controller, $index, 1);
      AddDecisionQueue("DESTROYCHARACTER", $this->controller, "-", 1);
      AddDecisionQueue("GAINACTIONPOINTS", $this->controller, 1, 1);
    }
  }

  function CheerTrigger() {
    $index = FindCharacterIndex($this->controller, $this->cardID);
    if (IsCharacterActive($this->controller, $index)) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function DefaultActiveState() {
    return 1;
  }
}

// class ghost_protocol_architect_red extends Card
// {
//   function __construct($controller)
//   {
//     $this->cardID = "ghost_protocol_architect_red";
//     $this->controller = $controller;
//   }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
//   }

//   function ProcessAttackTrigger($target, $uniqueID) {
//     $search = "MYDECK:subtype=Evo;maxCost=" . EvoUpgradeAmount($this->controller);
//     AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
//     AddDecisionQueue("SETDQCONTEXT", $this->controller, "Search your deck for an Evo with cost less than or equal to " . EvoUpgradeAmount($this->controller) ." to banish", 1);
//     AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
//     AddDecisionQueue("MZBANISH", $this->controller, "-", 1);
//     AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
//     AddDecisionQueue("SHUFFLEDECK", $this->controller, "-", 1);
//     AddDecisionQueue("ELSE", $this->controller, "-");
//     AddDecisionQueue("SHUFFLEDECK", $this->controller, "-", 1);
//   }

//   function PlayableFromBanish($mod, $nonLimitedOnly) {
//     return $mod == "BOOST";
//   }
// }

// class ghost_protocol_mainframe_blue extends Card
// {
//   function __construct($controller)
//   {
//     $this->cardID = "ghost_protocol_mainframe_blue";
//     $this->controller = $controller;
//   }

//   function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
//     return EvoUpgradeAmount($this->controller);
//   }

//   function PlayableFromBanish($mod, $nonLimitedOnly) {
//     return $mod == "BOOST";
//   }
// }