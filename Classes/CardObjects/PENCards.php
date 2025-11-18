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

  function AbilityType($index = -1, $from = '-')
  {
    return "DR";
  }

  function AbilityCost()
  {
    return 1;
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

  function AbilityType($index = -1, $from = '-')
  {
    return "DR";
  }

  function AbilityCost()
  {
    return 1;
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

  function AbilityType($index = -1, $from = '-')
  {
    return "DR";
  }

  function AbilityCost()
  {
    return 1;
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