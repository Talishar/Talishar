<?php
include_once  __DIR__ . "/HVYCards.php";
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
    WriteLog("HER: $additionalCosts");
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

  function CardCaresAboutPitch() {
    return true;
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

  function CardCaresAboutPitch() {
    return true;
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

  function CardCaresAboutPitch() {
    return true;
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

  function CardCaresAboutPitch() {
    return true;
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

class ghost_protocol_architect_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "ghost_protocol_architect_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $search = "MYDECK:subtype=Evo;maxCost=" . EvoUpgradeAmount($this->controller);
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Search your deck for an Evo with cost less than or equal to " . EvoUpgradeAmount($this->controller) ." to banish", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZBANISH", $this->controller, "-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $this->controller, "-", 1);
    AddDecisionQueue("ELSE", $this->controller, "-");
    AddDecisionQueue("SHUFFLEDECK", $this->controller, "-", 1);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return $mod == "BOOST";
  }
}

class ghost_protocol_mainframe_blue extends Card
{
  function __construct($controller)
  {
    $this->cardID = "ghost_protocol_mainframe_blue";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return EvoUpgradeAmount($this->controller);
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return $mod == "BOOST";
  }
}

class frost_spike_blue extends Card {
  function __construct($controller)
  {
    $this->cardID = "frost_spike_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    FrostBiteExposed("-", $this->controller);
  }
}

class enflame_the_firebrand_red extends Card {
  function __construct($controller)
  {
    $this->cardID = "enflame_the_firebrand_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    global $ChainLinks;
    $numDrac = NumDraconicChainLinks(); //this gets locked in immediately
    if ($numDrac > 1) GiveAttackGoAgain();
    if ($numDrac > 2) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
      for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
        $Link = $ChainLinks->GetLink($i);
        $Link->AddTalent("DRACONIC");
      }
    }
    if ($numDrac > 3) AddCurrentTurnEffect($this->cardID . "-BUFF", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return $mode == "-";
  }

  function EffectPowerModifier($param, $attached = false) {
    return $param == "BUFF" ? 2 : 0;
  }

  function RemoveEffectFromCombatChain() {
    return true;
  }
}

class aetherstorm_wellingtons extends Card {
  function __construct($controller)
  {
    $this->cardID = "aetherstorm_wellingtons";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 2;
  }
}

class double_cross_strap extends Card {
  function __construct($controller)
  {
    $this->cardID = "double_cross_strap";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 1;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharCard = new CharacterCard($index, $this->controller);
    $CharCard->Destroy();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    GainResources($this->controller, 1);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    return HitsInCombatChain() < 2 || $this->controller != $mainPlayer;
  }
}

class two_steps_forward extends Card {
  function __construct($controller)
  {
    $this->cardID = "two_steps_forward";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharCard = new CharacterCard($index, $this->controller);
    $CharCard->Destroy();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("agility", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $mainPlayer;
    return HitsInCombatChain() < 2 || $this->controller != $mainPlayer;
  }
}

class mask_of_the_swarming_claw extends Card {
  function __construct($controller)
  {
    $this->cardID = "mask_of_the_swarming_claw";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 1;
  }

  function SpellVoidAmount() {
    global $mainPlayer, $ChainLinks, $CombatChain;
    if ($this->controller != $mainPlayer) return 0;
    elseif (!$CombatChain->HasCurrentLink()) return 0;
    elseif (IsLayerStep()) return $ChainLinks->NumLinks();
    else return $ChainLinks->NumLinks() + 1;
  }

  function DefaultActiveState() {
    return 1;
  }
}

class fire_that_burns_within_red extends Card {
  function __construct($controller)
  {
    $this->cardID = "fire_that_burns_within_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYHAND:isSameName=phoenix_flame_red");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Discard a " . CardLink("phoenix_flame_red", "phoenix_flame_red") . "?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDISCARD", $this->controller, "HAND," . $this->controller, 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("DRAW", $this->controller, "-", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, $this->cardID, 1);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class teklo_trebuchet_2000 extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function CombatEffectActive() {
    global $CombatChainState;
    return $CombatChainState->IsBoosted();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class teklo_trebuchet_2000_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "teklo_trebuchet_2000_blue";
    $this->controller = $controller;
    $this->baseCard = new teklo_trebuchet_2000($this->cardID, $controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param);
  }

  function RemoveEffectFromCombatChain() {
    return true;
  }
}

class gauntlets_of_unity extends Card {
  function __construct($controller) {
    $this->cardID = "gauntlets_of_unity";
    $this->controller = $controller;
  }

  function UnityEffect() {
    AddCurrentTurnEffect("gauntlets_of_unity", $this->controller);
    return;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return CountCurrentTurnEffects($this->cardID, $this->controller);
  }
}

class helm_of_unity extends Card {
  function __construct($controller) {
    $this->cardID = "helm_of_unity";
    $this->controller = $controller;
  }

  function UnityEffect() {
    AddCurrentTurnEffect("helm_of_unity", $this->controller);
    return;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return CountCurrentTurnEffects($this->cardID, $this->controller);
  }
}

class plating_of_unity extends Card {
  function __construct($controller) {
    $this->cardID = "plating_of_unity";
    $this->controller = $controller;
  }

  function UnityEffect() {
    AddCurrentTurnEffect("plating_of_unity", $this->controller);
    return;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return CountCurrentTurnEffects($this->cardID, $this->controller);
  }
}
class pillar_of_unity extends Card {
  function __construct($controller) {
    $this->cardID = "pillar_of_unity";
    $this->controller = $controller;
  }

  function UnityEffect() {
    AddCurrentTurnEffect("pillar_of_unity", $this->controller);
    return;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return CountCurrentTurnEffects($this->cardID, $this->controller);
  }
}

class predatory_plating extends Card {
  function __construct($controller) {
    $this->cardID = "predatory_plating";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain, $ChainLinks;
    if (LayerStepPower() >= 6) return false;

    for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
      $Card = $CombatChain->Card($i, true);
      if ($Card->PlayerID() == $this->controller && TypeContains($Card->ID(), "AA") && $Card->TotalPower() >= 6) return false;
    }

    for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
      $ChainLink = $ChainLinks->GetLink($i);
      for ($j = 0; $j < $ChainLink->NumCards(); ++$j) {
        $Card = $ChainLink->GetLinkCard($j, true);
        if ($Card->PlayerID() == $this->controller && $Card->PowerModifier() >= 6) return false;
      }
    }

    $Character = new PlayerCharacter($this->controller);
    for ($i = 0; $i < $Character->NumCards(); ++$i) {
      if (PowerValue($Character->Card($i, true)->CardID(), $this->controller, "EQUIP")) return false;
    }

    $Allies = new Allies($this->controller);
    for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
      if (PowerValue($Allies->Card($i, true), $this->controller, "ALLIES")) return false;
    }
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    GainResources($this->controller, 1);
  }

  function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
    $CharCard = new CharacterCard($index, $this->controller);
    $CharCard->Destroy();
  }
}

class basalt_boots extends Card {
  function __construct($controller) {
    $this->cardID = "basalt_boots";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return SearchAuras("seismic_surge", $this->controller) ? 1 : 0;
  }
}

class aggressive_pounce extends BaseCard {
  function HasIntimidated($controller) {
    global $CS_HaveIntimidated;
    return GetClassState($controller, $CS_HaveIntimidated) > 0;
  }
}

class aggressive_pounce_red extends Card {
  function __construct($controller) {
    $this->cardID = "aggressive_pounce_red";
    $this->controller = $controller;
    $this->baseCard = new aggressive_pounce($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->HasIntimidated($this->controller);
  }
}

class aggressive_pounce_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "aggressive_pounce_yellow";
    $this->controller = $controller;
    $this->baseCard = new aggressive_pounce($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->HasIntimidated($this->controller);
  }
}

class aggressive_pounce_blue extends Card {
  function __construct($controller) {
    $this->cardID = "aggressive_pounce_blue";
    $this->controller = $controller;
    $this->baseCard = new aggressive_pounce($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->HasIntimidated($this->controller);
  }
}

class bear_hug extends BaseCard {
  function IsPlayRestricted() {
    // this is *technically* not correct, it won't interact correctly with dpot
    // leaving it as is for now because no one will play d-pot kayo
    $pitch = GetPitch($this->controller);
    for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
      if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return false;
    }
    return true;
  }
}

class bear_hug_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "bear_hug_red";
    $this->controller = $controller;
    $this->baseCard = new bear_hug($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class bear_hug_yellow extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "bear_hug_yellow";
    $this->controller = $controller;
    $this->baseCard = new bear_hug($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class bear_hug_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "bear_hug_blue";
    $this->controller = $controller;
    $this->baseCard = new bear_hug($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class enclosed_firemind extends Card {
  function __construct($controller) {
    $this->cardID = "enclosed_firemind";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 1;
  }
}

// class topsy_turvy extends Card {
//   function __construct($controller) {
//     $this->cardID = "topsy_turvy";
//     $this->controller = $controller;
//   }

//   function ArcaneBarrier() {
//     return 1;
//   }

//   function PayAbilityAdditionalCosts($index, $from = '-', $zoneIndex = -1) {
//     $CharacterCard = new CharacterCard($index, $this->controller);
//     $CharacterCard->Destroy();
//   }

//   function AbilityType($index = -1, $from = '-') {
//     return "I";
//   }

//   function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
//     AddCurrentTurnEffect($this->cardID, $this->controller);
//   }
// }

class runic_fellingsong extends BaseCard {
  function PlayAbility() {
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
  }

  function ProcessTrigger($uniqueID, $target = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:subtype=Aura");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Banish an aura to deal 1 arcane?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZBANISH", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "FELLINGSONG", 1);
  }
}

class runic_fellingsong_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "runic_fellingsong_red";
    $this->controller = $controller;
    $this->baseCard = new runic_fellingsong($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($uniqueID, $target);
  }
}

class runic_fellingsong_yellow extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "runic_fellingsong_yellow";
    $this->controller = $controller;
    $this->baseCard = new runic_fellingsong($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($uniqueID, $target);
  }
}

class runic_fellingsong_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "runic_fellingsong_blue";
    $this->controller = $controller;
    $this->baseCard = new runic_fellingsong($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    return $this->baseCard->ProcessTrigger($uniqueID, $target);
  }
}

class shroud_of_the_fate_watcher extends Card {
  function __construct($controller) {
    $this->cardID = "shroud_of_the_fate_watcher";
    $this->controller = $controller;
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    PlayAura("ponder", $this->controller);
  }
}

class robe_of_resourcefulness extends Card {
  function __construct($controller) {
    $this->cardID = "robe_of_resourcefulness";
    $this->controller = $controller;
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    GainResources($this->controller, 2);
  }
}

class gloves_of_erasure extends Card {
  function __construct($controller) {
    $this->cardID = "gloves_of_erasure";
    $this->controller = $controller;
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRAURAS:type=T&MYAURAS:type=T");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a token aura to destroy, or pass", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "-", 1);
  }
}

class silken_shroud extends Card {
  function __construct($controller) {
    $this->cardID = "silken_shroud";
    $this->controller = $controller;
  }

  function DestroyEffect(): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("ponder", $this->controller);
  }
}

class silken_shawl extends Card {
  function __construct($controller) {
    $this->cardID = "silken_shawl";
    $this->controller = $controller;
  }

  function DestroyEffect(): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }
  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("vigor", $this->controller);
  }
}

class silken_symphony extends Card {
  function __construct($controller) {
    $this->cardID = "silken_symphony";
    $this->controller = $controller;
  }

  function DestroyEffect(): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("might", $this->controller);
  }
}
class silken_slippers extends Card {
  function __construct($controller) {
    $this->cardID = "silken_slippers";
    $this->controller = $controller;
  }

  function DestroyEffect(): void {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    PlayAura("agility", $this->controller);
  }
}

class shallow_water_shark_harpoon_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "shallow_water_shark_harpoon_yellow";
    $this->controller = $controller;
  }

    function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
      global $CS_NumCannonsActivated;
    if (IsHeroAttackTarget() && GetClassState($this->controller, $CS_NumCannonsActivated) > 0) {
      if (!$check) {
        AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      }
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = "-", $uniqueID = -1, $target="-") {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRARS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card you want to destroy from their arsenal", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, false, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "gold", 1);
    AddDecisionQueue("PUTPLAY", $this->controller, "-", 1);
  }
}

class laden_with_earth_red extends Card {
  function __construct($controller) {
    $this->cardID = "laden_with_earth_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    AddCurrentTurnEffect("laden_with_earth_red", $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "EARTH") != "")
      PlayAura("embodiment_of_earth", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

    function CardCaresAboutPitch() {
    return true;
  }
}

class laden_with_frost_red extends Card {
  function __construct($controller) {
    $this->cardID = "laden_with_frost_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    AddCurrentTurnEffect("laden_with_frost_red", $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "ICE") != ""){
      //Technically wrong, should be "target hero". I don't think there is currently a reason to give yourself the Frostbite.
      PlayAura("frostbite", $defPlayer);
    }
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

    function CardCaresAboutPitch() {
    return true;
  }
}

class laden_with_lightning_red extends Card {
  function __construct($controller) {
    $this->cardID = "laden_with_lightning_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    AddCurrentTurnEffect("laden_with_lightning_red", $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "LIGHTNING") != "")
      PlayAura("embodiment_of_lightning", $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

    function CardCaresAboutPitch() {
    return true;
  }
}

class oath_of_oak extends BaseCard {
  function PlayAbility($number) {
    PlayAura("embodiment_of_earth", $this->controller, $number);
  }
}

class oath_of_oak_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "oath_of_oak_red";
    $this->controller = $controller;
    $this->baseCard = new oath_of_oak($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(3);
  }
}

class oath_of_oak_yellow extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "oath_of_oak_yellow";
    $this->controller = $controller;
    $this->baseCard = new oath_of_oak($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(2);
  }
}
class oath_of_oak_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "oath_of_oak_blue";
    $this->controller = $controller;
    $this->baseCard = new oath_of_oak($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(1);
  }
}

class sprout_strength extends BaseCard {
  function Sprout($amount = 1) {
    for ($i = 0; $i < $amount; ++$i) {
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class sprout_strength_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "sprout_strength_red";
    $this->controller = $controller;
    $this->baseCard = new sprout_strength($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->Sprout(3);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class sprout_strength_yellow extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "sprout_strength_yellow";
    $this->controller = $controller;
    $this->baseCard = new sprout_strength($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->Sprout(2);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class sprout_strength_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "sprout_strength_blue";
    $this->controller = $controller;
    $this->baseCard = new sprout_strength($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->Sprout(1);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class duty_bound extends BaseCard {
  function IsPlayRestricted() {
    global $CS_NumYellowPutSoul;
    if (GetClassState($this->controller, $CS_NumYellowPutSoul) > 0) return false;
    return true;
  }
}

class duty_bound_blitz_red extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "duty_bound_blitz_red";
    $this->controller = $controller;
    $this->baseCard = new duty_bound($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class duty_bound_blitz_yellow extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "duty_bound_blitz_yellow";
    $this->controller = $controller;
    $this->baseCard = new duty_bound($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class duty_bound_blitz_blue extends Card {
  public $baseCard;
  function __construct($controller) {
    $this->cardID = "duty_bound_blitz_blue";
    $this->controller = $controller;
    $this->baseCard = new duty_bound($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return $this->baseCard->IsPlayRestricted();
  }
}

class sigil_of_silphidae_blue extends Card {
  function __construct($controller) {
    $this->cardID = "sigil_of_silphidae_blue";
    $this->controller = $controller;
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    global $CS_ArcaneTargetsSelected;
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
    AddDecisionQueue("SETCLASSSTATE", $this->controller, $CS_ArcaneTargetsSelected);
  }

  function EntersArenaAbility() {
    global $CS_ArcaneTargetsSelected;
    SetArcaneTarget($this->controller, $this->cardID, 0, 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
    AddDecisionQueue("SETCLASSSTATE", $this->controller, $CS_ArcaneTargetsSelected);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CS_ArcaneTargetsSelected;
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      if ($AuraCard != "") $AuraCard->Destroy();
    }
    else {
      $search = explode(",", SearchMultizone($this->controller, "MYDISCARD:subtype=Aura"));
      $Discard = new Discard($this->controller);
      WriteLog("HJERE: $target");
      if ($Discard->TopCard() == $this->cardID) { //it can't banish itself
        array_pop($search);
      }
      if (count($search) > 0) {
        AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $search));
        AddDecisionQueue("SETDQCONTEXT", $this->controller, "Banish an aura to deal 1 arcane?", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
        AddDecisionQueue("MZBANISH", $this->controller, "<-", 1);
        AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
        AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
        AddDecisionQueue("DEALARCANE", $this->controller, 1, 1);
        AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
        AddDecisionQueue("SETCLASSSTATE", $this->controller, parameter: $CS_ArcaneTargetsSelected);
      }
    }
  }
}

class fluid_motion_blue extends Card {
  function __construct($controller) {
    $this->cardID = "fluid_motion_blue";
    $this->controller = $controller;
  }

  function DoesAttackHaveGoAgain() {
    $ClassState = new ClassState($this->controller);
    return $ClassState->CreatedCardsThisTurn() > 0;
  }
}

class manifest_muscle_blue extends Card {
  function __construct($controller) {
    $this->cardID = "manifest_muscle_blue";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    $ClassState = new ClassState($this->controller);
    return $ClassState->CreatedCardsThisTurn() > 0 ? 1 : 0;
  }
}

class runebleed_robe extends Card {
  function __construct($controller) {
    $this->cardID = "runebleed_robe";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 1;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Auras = new Auras($this->controller);
    return $Auras->FindCardID("runechant") == "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
    $Auras = new Auras($this->controller);
    $Runechant = $Auras->FindCardID("runechant");
    if ($Runechant != "") $Runechant->Destroy();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    if ($type == "ARCANE") {
      $remove = true;
      return 1;
    }
  }
}

class beckoning_haunt extends Card {
  function __construct($controller) {
    $this->cardID = "beckoning_haunt";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $auras = SearchMultizone($this->controller, "MYDISCARD:subtype=Aura");
    return $auras == "";
  }

  function DynamicCost() {
    $costs = [];
    $discard = GetDiscard($this->controller);
    for ($i = 0; $i < count($discard); $i += DiscardPieces()) {
      $cardCost = CardCost($discard[$i]);
      $cost = 2 * $cardCost + 1;
      if (SubtypeContains($discard[$i], "Aura", $this->controller) && !in_array($cost, $costs)) {
        array_push($costs, $cost);
      }
    }
    sort($costs);
    return implode(",", $costs);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $CharacterCard = new CharacterCard($index, $this->controller);
    $CharacterCard->Destroy();
    $ClassState = new ClassState($this->controller);
    $costPaid = $ClassState->LastDynCost();
    $cost = intdiv($costPaid - 1, 2);
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:subtype=Aura;maxCost=$cost;minCost=$cost");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Target an Aura to return to hand.", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Discard = new Discard($this->controller);
    $targetCard = $Discard->FindCardUID(explode("-", $target)[1] ?? "-");
    if ($targetCard == "") return "FAILED";
    $targetIndex = $targetCard->Index();
    $cardID = RemoveGraveyard($this->controller, $targetIndex);
    AddPlayerHand($cardID, $this->controller, "DISCARD");
    return "";
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }
}

class engulfing_shadows_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "engulfing_shadows_yellow";
    $this->controller = $controller;
  }

  function AddGraveyardEffect($from, $effectController) {
    BanishCardForPlayer($this->cardID, $this->controller, $from, "NA");
    return true;
  }
}

class concoct_disorder extends BaseCard {
  function ProcessAttackTrigger() {
    $cardsArsenaled = 0;
    for ($player = 1; $player < 3; ++$player) {
      $deck = new Deck($player);
      if (!ArsenalFull($player) && $deck->RemainingCards() > 0) {
        AddArsenal($deck->Top(), $player, "DECK", "DOWN");
        RemoveDeck($player, 0);
        ++$cardsArsenaled;
      }
    }
    if ($cardsArsenaled >= 2) GiveAttackGoAgain();
  }
}

class concoct_disorder_red extends Card {
  function __construct($controller) {
    $this->cardID = "concoct_disorder_red";
    $this->controller = $controller;
    $this->baseCard = new concoct_disorder($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class concoct_disorder_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "concoct_disorder_yellow";
    $this->controller = $controller;
    $this->baseCard = new concoct_disorder($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class concoct_disorder_blue extends Card {
  function __construct($controller) {
    $this->cardID = "concoct_disorder_blue";
    $this->controller = $controller;
    $this->baseCard = new concoct_disorder($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class hyper_inflation extends BaseCard {
  function ProcessAttackTrigger() {
    AddCurrentTurnEffect($this->cardID, 1);
    AddCurrentTurnEffect($this->cardID, 2);
  }

  function CurrentEffectCostModifier($cardID, $from) {
    return IsStaticType(CardType($cardID), $from, $cardID) ? 0 : 1;
  }
}

class hyper_inflation_red extends Card {
  function __construct($controller) {
    $this->cardID = "hyper_inflation_red";
    $this->controller = $controller;
    $this->baseCard = new hyper_inflation($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove) {
    return $this->baseCard->CurrentEffectCostModifier($cardID, $from);
  }
}

class hyper_inflation_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "hyper_inflation_yellow";
    $this->controller = $controller;
    $this->baseCard = new hyper_inflation($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove) {
    return $this->baseCard->CurrentEffectCostModifier($cardID, $from);
  }
}

class hyper_inflation_blue extends Card {
  function __construct($controller) {
    $this->cardID = "hyper_inflation_blue";
    $this->controller = $controller;
    $this->baseCard = new hyper_inflation($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove) {
    return $this->baseCard->CurrentEffectCostModifier($cardID, $from);
  }
}

class strike_twice_red extends Card {
  function __construct($controller) {
    $this->cardID = "strike_twice_red";
    $this->controller = $controller;
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ArcaneDamage() {
    return 3;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(3, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }

  function CanPlayAsInstant($index = -1, $from = '') {
    global $CS_ArcaneDamageDealtToOpponent;
    return GetClassState($this->controller, $CS_ArcaneDamageDealtToOpponent) > 0;
  }
}

class glyph_power_spell_red extends Card {
  function __construct($controller) {
    $this->cardID = "glyph_power_spell_red";
    $this->controller = $controller;
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ArcaneDamage() {
    return 4;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $damage = HasAuraWithSigilInName($this->controller) ? 6 : 4;
    DealArcane($damage, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }
}

class sigil_of_fate extends Card {
  function __construct($controller) {
    $this->cardID = "sigil_of_fate";
    $this->controller = $controller;
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID = '-') {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->uniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      $AuraCard->Destroy();
    }
    else {
      PlayerOpt($this->controller, 1);
    }
  }
}

class painful_premonition extends BaseCard {
  function ArcaneHit() {
    PlayAura("sigil_of_fate", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class painful_premonition_red extends Card {
  function __construct($controller) {
    $this->cardID = "painful_premonition_red";
    $this->controller = $controller;
    $this->baseCard = new painful_premonition($this->cardID, $this->controller);
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ArcaneDamage() {
    return 3;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(3, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }

  function ArcaneHitEffect($source, $target, $damage) {
    $this->baseCard->ArcaneHit();
  }
}

class painful_premonition_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "painful_premonition_yellow";
    $this->controller = $controller;
    $this->baseCard = new painful_premonition($this->cardID, $this->controller);
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ArcaneDamage() {
    return 2;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(2, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }

  function ArcaneHitEffect($source, $target, $damage) {
    $this->baseCard->ArcaneHit();
  }
}

class painful_premonition_blue extends Card {
  function __construct($controller) {
    $this->cardID = "painful_premonition_blue";
    $this->controller = $controller;
    $this->baseCard = new painful_premonition($this->cardID, $this->controller);
  }

  function ArcaneTargeting($from) {
    return 2;
  }

  function ArcaneDamage() {
    return 1;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    DealArcane(1, 2, "PLAYCARD", $this->cardID, false, $this->controller, resolvedTarget: $target);
  }

  function ArcaneHitEffect($source, $target, $damage) {
    $this->baseCard->ArcaneHit();
  }
}

class future_sight_red extends Card {
  function __construct($controller) {
    $this->cardID = "future_sight_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("sigil_of_fate", $this->controller, 3, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class future_sight_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "future_sight_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("sigil_of_fate", $this->controller, 2, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class future_sight_blue extends Card {
  function __construct($controller) {
    $this->cardID = "future_sight_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("sigil_of_fate", $this->controller, 1, true, effectController:$this->controller, effectSource:$this->cardID);
  }
}

class voltic_veil_red extends Card {
  function __construct($controller) {
    $this->cardID = "voltic_veil_red";
    $this->controller = $controller;
  }

  function CardCaresAboutPitch() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "LIGHTNING") != "")
      DealArcane(1, 1, source:$this->cardID);
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    global $CurrentTurnEffects;
    $Effect = $CurrentTurnEffects->Effect($index);
    if ($damage >= $Effect->NumUses()) {
      $remove = true;
      return $Effect->NumUses();
    }
    else {
      if (!$amount) $Effect->AddUses(-$damage);
      return $damage;
    }
  }

  function CurrentTurnEffectUses() {
    return 4;
  }

  function ActionsThatDoArcaneDamage() {
    return true;
  }
}

class embody_greatness_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "embody_greatness_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $livingLegends;
    $choices = [];
    foreach($livingLegends as $hero) {
      array_push($choices, "CARDID-$hero");
    }
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $choices));
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "EMBODYGREATNESS", 1);
    AddNextTurnEffect($this->cardID, $this->controller);
  }
}

class heavy_metal_hardcore extends BaseCard {
  function PowerModifier() {
    global $CS_EvosBoosted;
    return GetClassState($this->controller, $CS_EvosBoosted) > 0 ? 1 : 0;
  }
}

class heavy_metal_hardcore_red extends Card {
  function __construct($controller) {
    $this->cardID = "heavy_metal_hardcore_red";
    $this->controller = $controller;
    $this->baseCard = new heavy_metal_hardcore($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class heavy_metal_hardcore_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "heavy_metal_hardcore_yellow";
    $this->controller = $controller;
    $this->baseCard = new heavy_metal_hardcore($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class heavy_metal_hardcore_blue extends Card {
  function __construct($controller) {
    $this->cardID = "heavy_metal_hardcore_blue";
    $this->controller = $controller;
    $this->baseCard = new heavy_metal_hardcore($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class emboldened_by_the_crowd_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "emboldened_by_the_crowd_yellow";
    $this->controller = $controller;
  }

  function SelfCostModifier($from) {
    global $CS_CheeredThisTurn;
    return GetClassState($this->controller, $CS_CheeredThisTurn) > 0 ? -3 : 0;
  }
}

class hulk_up extends BaseCard {
  function SelfCostModifier($from) {
    return PlayerHasLessHealth($this->controller) ? -1 : 0;
  } 
}

class hulk_up_red extends Card {  function __construct($controller) {
    $this->cardID = "hulk_up_red";
    $this->controller = $controller;
    $this->baseCard = new hulk_up($this->cardID, $this->controller);
  }

  function SelfCostModifier($from) {
    return $this->baseCard->SelfCostModifier($from);
  }
}

class hulk_up_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "hulk_up_yellow";
    $this->controller = $controller;
    $this->baseCard = new hulk_up($this->cardID, $this->controller);
  }

  function SelfCostModifier($from) {
    return $this->baseCard->SelfCostModifier($from);
  }
}

class hulk_up_blue extends Card {
  function __construct($controller) {
    $this->cardID = "hulk_up_blue";
    $this->controller = $controller;
    $this->baseCard = new hulk_up($this->cardID, $this->controller);
  }

  function SelfCostModifier($from) {
    return $this->baseCard->SelfCostModifier($from);
  }
}

class insult_to_injury extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if (IsHeroAttackTarget()) AddLayer("TRIGGER", $this->controller, $this->cardID, additionalCosts:"ATTACKTRIGGER");
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    if (PlayerHasLessHealth($otherPlayer)) GiveAttackGoAgain();
  }
}

class insult_to_injury_red extends Card {
  function __construct($controller) {
    $this->cardID = "insult_to_injury_red";
    $this->controller = $controller;
    $this->baseCard = new insult_to_injury($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }
}

class insult_to_injury_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "insult_to_injury_yellow";
    $this->controller = $controller;
    $this->baseCard = new insult_to_injury($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }
}

class insult_to_injury_blue extends Card {
  function __construct($controller) {
    $this->cardID = "insult_to_injury_blue";
    $this->controller = $controller;
    $this->baseCard = new insult_to_injury($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }
}

class song_of_larinkmorth_white_blue extends Card {
  function __construct($controller) {
    $this->cardID = "song_of_larinkmorth_white_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $defPlayer;
    PlayAura("frostbite", $defPlayer);
  }
}

class knife_through extends BaseCard {
  function DoesAttackHaveGoAgain() {
    global $combatChainState, $CCS_FlickedDamage, $chainLinks, $chainLinkSummary;
    $numDaggerHits = 0;
    $count = count($chainLinks);
    for($i=0; $i<$count; ++$i)
    {
      if(SubtypeContains($chainLinks[$i][0], "Dagger") && $chainLinkSummary[$i*ChainLinkSummaryPieces()] > 0) 
        ++$numDaggerHits;
    }
    $numDaggerHits += $combatChainState[$CCS_FlickedDamage];
    return $numDaggerHits > 0;
  }
}

class knife_through_red extends Card {
  function __construct($controller) {
    $this->cardID = "knife_through_red";
    $this->controller = $controller;
    $this->baseCard = new knife_through($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class knife_through_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "knife_through_yellow";
    $this->controller = $controller;
    $this->baseCard = new knife_through($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class knife_through_blue extends Card {
  function __construct($controller) {
    $this->cardID = "knife_through_blue";
    $this->controller = $controller;
    $this->baseCard = new knife_through($this->cardID, $this->controller);
  }

  function DoesAttackHaveGoAgain() {
    return $this->baseCard->DoesAttackHaveGoAgain();
  }
}

class seeds_of_strength_red extends Card {
  function __construct($controller) {
    $this->cardID = "seeds_of_strength_red";
    $this->controller = $controller;
  }

  function CardCaresAboutPitch() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("might", $this->controller);
    PlayAura("might", $this->controller);
    PlayAura("might", $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "EARTH") != "")
      PlayAura("might", $this->controller);
  }
}

class arc_bending_red extends Card { //untested
  function __construct($controller) {
    $this->cardID = "arc_bending_red";
    $this->controller = $controller;
  }

  function CardCaresAboutPitch() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "LIGHTNING") != "")
      GiveAttackGoAgain();
  }

  function RemoveEffectFromCombatChain() {
    return true;
  }
}

class chorus_of_rotwood_red extends Card {
  function __construct($controller) {
    $this->cardID = "chorus_of_rotwood_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Discard = new Discard($this->controller);
    $Discard->RemoveTop(); //don't let them decompose itself
    Decompose($this->controller, "CHORUSOFROTWOOD");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1); // put it back in the graveyard
    AddDecisionQueue("ADDDISCARD", $this->controller, "-", 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "runechant-3", 1);
    AddDecisionQueue("ELSE", $this->controller, "-"); //do this if you decline decompose
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1); // put it back in the graveyard
    AddDecisionQueue("ADDDISCARD", $this->controller, "-", 1);
    AddDecisionQueue("PLAYAURA", $this->controller, "runechant-3", 1);
  }
}

class limbs_of_lignum_vitae extends Card {
  function __construct($controller) {
    $this->cardID = "limbs_of_lignum_vitae";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    $results = SearchCount(SearchMultiZone($this->controller, "MYBANISH:talent=EARTH"));
    return $results >= 4 ? 1 : 0;
  }
}

class drag_down extends BaseCard
{
  function PlayAbility($value) {
    global $CombatChain;
    $CombatChain->Card(0)->ModifyPower(-$value);
  }
}

class drag_down_red extends Card {
  function __construct($controller) {
    $this->cardID = "drag_down_red";
    $this->controller = $controller;
    $this->baseCard = new drag_down($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(3);
  }
}

class drag_down_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "drag_down_yellow";
    $this->controller = $controller;
    $this->baseCard = new drag_down($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(2);
  }
}

class drag_down_blue extends Card {
  function __construct($controller) {
    $this->cardID = "drag_down_blue";
    $this->controller = $controller;
    $this->baseCard = new drag_down($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(1);
  }
}

class cloud_cover extends BaseCard
{
  public $preventionAmount = 0;

  function PlayAbility() {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }
  
  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    $remove = true;
    return $this->preventionAmount;
  }
}

class cloud_cover_red extends Card {
  function __construct($controller) {
    $this->cardID = "cloud_cover_red";
    $this->controller = $controller;
    $this->baseCard = new cloud_cover($this->cardID, $this->controller);
    $this->baseCard->preventionAmount = 3;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($type, $damage, $source, $index, $remove, $amount);
  }
}

class cloud_cover_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "cloud_cover_yellow";
    $this->controller = $controller;
    $this->baseCard = new cloud_cover($this->cardID, $this->controller);
    $this->baseCard->preventionAmount = 2;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($type, $damage, $source, $index, $remove, $amount);
  }
}

class cloud_cover_blue extends Card {
  function __construct($controller) {
    $this->cardID = "cloud_cover_blue";
    $this->controller = $controller;
    $this->baseCard = new cloud_cover($this->cardID, $this->controller);
    $this->baseCard->preventionAmount = 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    return $this->baseCard->CurrentEffectDamagePrevention($type, $damage, $source, $index, $remove, $amount);
  }
}

class stadium_security extends BaseCard {
  function hasAmbush() {
    global $CS_NumToughnessDestroyed;
    return GetClassState($this->controller, $CS_NumToughnessDestroyed) > 0 || CountAura("toughness", $this->controller) > 0;
  }
}

class stadium_security_red extends Card {
  function __construct($controller) {
    $this->cardID = "stadium_security_red";
    $this->controller = $controller;
    $this->baseCard = new stadium_security($this->cardID, $this->controller);
  }

  function HasAmbush() {
    return $this->baseCard->hasAmbush();
  }
}

class stadium_security_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "stadium_security_yellow";
    $this->controller = $controller;
    $this->baseCard = new stadium_security($this->cardID, $this->controller);
  }

  function HasAmbush() {
    return $this->baseCard->hasAmbush();
  }
}

class stadium_security_blue extends Card {
  function __construct($controller) {
    $this->cardID = "stadium_security_blue";
    $this->controller = $controller;
    $this->baseCard = new stadium_security($this->cardID, $this->controller);
  }

  function HasAmbush() {
    return $this->baseCard->hasAmbush();
  }
}

class volcanic_vice extends Card {
  function __construct($controller) {
    $this->cardID = "volcanic_vice";
    $this->controller = $controller;
  }

  function SpellVoidAmount() {
    global $CS_SeismicSurgesCreated;
    return GetClassState($this->controller, $CS_SeismicSurgesCreated) > 0 ? 3 : 0;
  }
}

// class skera_strapping extends Card {
//   function __construct($controller) {
//     $this->cardID = "skera_strapping";
//     $this->controller = $controller;
//   }

//   function SpellVoidAmount() {
//     //this has an issue where it can actually gain spellvoid in the middle of preventing arcane damage
//     $pitch = GetPitch($this->controller);
//     for ($i = 0; $i < count($pitch); $i += PitchPieces()) {
//       if (ModifiedPowerValue($pitch[$i], $this->controller, "PITCH") >= 6) return 3;
//     }
//     return 0;
//   }
// }

class mbrio_base_cortex extends Card {
  function __construct($controller) {
    $this->cardID = "mbrio_base_cortex";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return SearchItemsByName($this->controller, "Hyper Driver") != "" ? 2 : 0;
  }
}

class blast_rig_red extends Card
{
  function __construct($controller)
  {
    $this->cardID = "blast_rig_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return EvoUpgradeAmount($this->controller);
  }
}

class blackstone_greaves extends Card {
  function __construct($controller) {
    $this->cardID = "blackstone_greaves";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_ArcaneDamageDealt;
    return GetClassState($this->controller, $CS_ArcaneDamageDealt) > 0 ? 1 : 0;
  }
}

class weeping_battleground extends BaseCard {
  function PlayAbility($target) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:subtype=Aura");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Banish an aura to deal 1 arcane?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZBANISH", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
    AddDecisionQueue("THREATENARCANE", $this->controller, $this->cardID . ",$target", 1);
  }

  function PayAdditionalCosts() {
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }
}

class weeping_battleground_red extends Card {
  function __construct($controller) {
    $this->cardID = "weeping_battleground_red";
    $this->controller = $controller;
    $this->baseCard = new weeping_battleground($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }
}

class weeping_battleground_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "weeping_battleground_yellow";
    $this->controller = $controller;
    $this->baseCard = new weeping_battleground($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }
}

class weeping_battleground_blue extends Card {
  function __construct($controller) {
    $this->cardID = "weeping_battleground_blue";
    $this->controller = $controller;
    $this->baseCard = new weeping_battleground($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($target);
  }
}

class embraforged_gauntlet extends Card {
  function __construct($controller) {
    $this->cardID = "embraforged_gauntlet";
    $this->controller = $controller;
  }

  function AddGraveyardEffect($from, $effectController) {
    BanishCardForPlayer($this->cardID, $this->controller, $from, "NA");
    return true;
  }
}

class depths_of_despair extends BaseCard {
  function AddGraveyardEffect($from, $effectController) {
    global $defPlayer;
    if($from == "CC" && $this->controller == $defPlayer) {
      BanishCardForPlayer($this->cardID, $this->controller, $from, "NA");
      return true;
    }
  }
}

class depths_of_despair_red extends Card {
  function __construct($controller) {
    $this->cardID = "depths_of_despair_red";
    $this->controller = $controller;
    $this->baseCard = new depths_of_despair($this->cardID, $this->controller);
  }

  function AddGraveyardEffect($from, $effectController) {
    return $this->baseCard->AddGraveyardEffect($from, $effectController);
  }
}

class depths_of_despair_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "depths_of_despair_yellow";
    $this->controller = $controller;
    $this->baseCard = new depths_of_despair($this->cardID, $this->controller);
  }

  function AddGraveyardEffect($from, $effectController) {
    return $this->baseCard->AddGraveyardEffect($from, $effectController);
  }
}

class depths_of_despair_blue extends Card {
  function __construct($controller) {
    $this->cardID = "depths_of_despair_blue";
    $this->controller = $controller;
    $this->baseCard = new depths_of_despair($this->cardID, $this->controller);
  }

  function AddGraveyardEffect($from, $effectController) {
    return $this->baseCard->AddGraveyardEffect($from, $effectController);
  }
}

class fasting_carcass extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive() {
    global $CombatChain;
    return ColorContains($CombatChain->AttackCard()->ID(), PitchValue($this->cardID), $this->controller);
  }
}

class fasting_carcass_red extends Card {
  function __construct($controller) {
    $this->cardID = "fasting_carcass_red";
    $this->controller = $controller;
    $this->baseCard = new fasting_carcass($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class fasting_carcass_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "fasting_carcass_yellow";
    $this->controller = $controller;
    $this->baseCard = new fasting_carcass($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class fasting_carcass_blue extends Card {
  function __construct($controller) {
    $this->cardID = "fasting_carcass_blue";
    $this->controller = $controller;
    $this->baseCard = new fasting_carcass($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }
}

class submerge extends BaseCard {
  function PayAdditionalCosts($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CS_AdditionalCosts;
    MZMoveCard($this->controller, "MYHAND", "MYTOPDECK-4", DQContext: "Choose a card to put fifth from the top of your deck");
  }
}

class submerge_red extends Card {
  function __construct($controller) {
    $this->cardID = "submerge_red";
    $this->controller = $controller;
    $this->baseCard = new submerge($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-', $resourcesPaid = '-', $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PayAdditionalCosts($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }
}

class submerge_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "submerge_yellow";
    $this->controller = $controller;
    $this->baseCard = new submerge($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-', $resourcesPaid = '-', $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PayAdditionalCosts($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }
}

class submerge_blue extends Card {
  function __construct($controller) {
    $this->cardID = "submerge_blue";
    $this->controller = $controller;
    $this->baseCard = new submerge($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-', $resourcesPaid = '-', $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PayAdditionalCosts($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }
}

class rip_off_the_top_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rip_off_the_top_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Draw($this->controller);
    $card = PitchRandom($this->controller);
    if(ModifiedPowerValue($card, $this->controller, "HAND", source:$this->cardID) >= 6) AddCurrentTurnEffect($this->cardID, $this->controller);
    return "";
  }

  function SpecialCost() { //fabcube error
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class distant_rumbling extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Draw($this->controller);
    MZMoveCard($this->controller, "MYHAND", "MYTOPDECK-4");
  }

  function StartTurnAbility($index, $number=1) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, $number, "DESTROY", $AuraCard->uniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      $AuraCard->Destroy();
      PlayAura("seismic_surge", $this->controller, $target);
    }
  }
}

class distant_rumbling_red extends Card {
  function __construct($controller) {
    $this->cardID = "distant_rumbling_red";
    $this->controller = $controller;
    $this->baseCard = new distant_rumbling($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index, 3);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, 3, $additionalCosts, $from);
  }
}

class distant_rumbling_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "distant_rumbling_yellow";
    $this->controller = $controller;
    $this->baseCard = new distant_rumbling($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index, 2);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, 2, $additionalCosts, $from);
  }
}

class distant_rumbling_blue extends Card {
  function __construct($controller) {
    $this->cardID = "distant_rumbling_blue";
    $this->controller = $controller;
    $this->baseCard = new distant_rumbling($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index, 1);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, 1, $additionalCosts, $from);
  }
}

class rainbow_goo_trap_red extends Card {
  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    if (HasIncreasedAttack() && IsDominateActive() && DoesAttackHaveGoAgain())
      AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain, $mainPlayer;
    $CombatChain->Card(0)->ModifyPower(-2);
    AddCurrentTurnEffect("rainbow_goo_trap_red", $mainPlayer);
    TrapTriggered($this->cardID);
    // should probably do something with blind card here, and then remember to unblind it when it goes to graveyard
  }
}

class frail_swingline_blue extends Card {
  function __construct($controller) {
    $this->cardID = "frail_swingline_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "THEIR") ? $otherPlayer : $this->controller;
    PlayAura("frailty", $targetPlayer);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $choices = ["THEIRCHAR-0"];
    if (!ShouldAutotargetOpponent($this->controller)) array_push($choices, "MYCHAR-0");
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $choices));
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Target a hero to give a frailty", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    if (HasDecreasedAttack()) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
      TrapTriggered($this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    PummelHit($mainPlayer, effectController:$this->controller);
  }
}

class quickening_sand_blue extends Card {
  function __construct($controller) {
    $this->cardID = "quickening_sand_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "THEIR") ? $otherPlayer : $this->controller;
    PlayAura("quicken", $targetPlayer);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $choices = ["THEIRCHAR-0"];
    array_push($choices, "MYCHAR-0");
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $choices));
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Target a hero to give a quicken", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    if (DoesAttackHaveGoAgain()) {
      $context = "Choose an a target to tangle";
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYALLY&THEIRALLY&MYCHAR:type=C&THEIRCHAR:type=C");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
      //eventually will need to set this with unique ids
      AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
      TrapTriggered($this->cardID);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Tap($target, $this->controller, 1);
  }
}

class courageous_crossing_blue extends Card {
  function __construct($controller) {
    $this->cardID = "courageous_crossing_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    $targetPlayer = str_contains($target, "THEIR") ? $otherPlayer : $this->controller;
    PlayAura("courage", $targetPlayer);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $choices = ["THEIRCHAR-0"];
    array_push($choices, "MYCHAR-0");
    AddDecisionQueue("PASSPARAMETER", $this->controller, implode(",", $choices));
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Target a hero to give a courage", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    if (HasIncreasedAttack()) {
      $context = "Choose an a target to remove a counter from";
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYAURAS&THEIRAURAS&MYCHAR:type=W&THEIRCHAR:type=W");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, $context, 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
      AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    TrapTriggered($this->cardID);
    $targetPlayer = str_contains($target, "MY") ? $this->controller : ($this->controller == 1 ? 2 : 1);
    if (str_contains($target, "CHAR")) {
      $Character = new PlayerCharacter($targetPlayer);
      $CharacterCard = $Character->FindCardUID(explode("-", $target)[1]);
      if ($CharacterCard != "" && $CharacterCard->NumPowerCounters() > 0) $CharacterCard->AddPowerCounters(-1);
    }
    elseif (str_contains($target, "AURAS")) {
      $Auras = new Auras($targetPlayer);
      $AuraCard = $Auras->FindCardUID(explode("-", $target)[1]);
      if ($AuraCard != "" && $AuraCard->NumPowerCounters()) $AuraCard->AddPowerCounters(-1);
    }
  }
}

class spellbane_trap extends BaseCard {
  function PlayAbility() {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function OnBlockResolveEffects() {
    global $CS_ArcaneDamageDealt, $mainPlayer;
    if (GetClassState($mainPlayer, $CS_ArcaneDamageDealt) > 0) {
      AddLayer("TRIGGER", $this->controller, $this->cardID);
    }
  }

  function ProcessTrigger() {
    PlayAura("spellbane_aegis", $this->controller);
    TrapTriggered($this->cardID);
  }

  function CombatEffectActive() {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Arrow");
  }
}

class spellbane_trap_red extends Card {
  function __construct($controller) {
    $this->cardID = "spellbane_trap_red";
    $this->controller = $controller;
    $this->baseCard = new spellbane_trap($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class spellbane_trap_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "spellbane_trap_yellow";
    $this->controller = $controller;
    $this->baseCard = new spellbane_trap($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class spellbane_trap_blue extends Card {
  function __construct($controller) {
    $this->cardID = "spellbane_trap_blue";
    $this->controller = $controller;
    $this->baseCard = new spellbane_trap($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive();
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    $this->baseCard->OnBlockResolveEffects();
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger();
  }
}

class man_overboard extends BaseCard {
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

    function ProcessAttackTrigger($target, $uniqueID) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYHAND:subtype=Ally");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Discard an ally?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDISCARD", $this->controller, "HAND," . $this->controller, 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, $this->cardID, 1);
  }
}

class man_overboard_red extends Card {
  function __construct($controller) {
    $this->cardID = "man_overboard_red";
    $this->controller = $controller;
    $this->baseCard = new man_overboard($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }
  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class man_overboard_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "man_overboard_yellow";
    $this->controller = $controller;
    $this->baseCard = new man_overboard($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }

    function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class man_overboard_blue extends Card {
  function __construct($controller) {
    $this->cardID = "man_overboard_blue";
    $this->controller = $controller;
    $this->baseCard = new man_overboard($this->cardID, $this->controller);
  }
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }
  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
  }
  
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class skywarden_no161803_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "skywarden_no161803_yellow";
    $this->controller = $controller;
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $combatChain;
    $myItems = GetItems($this->controller);
    // check to make sure it's still there before giving it a buff
    $foundSkywarden = false;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i] == $this->cardID) $foundSkywarden = true;
    }

    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYITEMS", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an item to galvanize for " . CardLink($this->cardID, $this->cardID) . " effect", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("GOLDENSKYWARDEN", $this->controller, $target, 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
    if ($foundSkywarden) AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $this->controller, "1", 1);
  }

  function SpecialBlock() {
    return 2;
  }
}

class break_open_the_chests_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "break_open_the_chests_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    TurnArsenalFaceUp($this->controller);
    TurnArsenalFaceUp($otherPlayer);
    if(ArsenalHasColor($this->controller, 2) || ArsenalHasColor($otherPlayer, 2))
      PutItemIntoPlayForPlayer("gold", $this->controller, number: 2);
  }
} 

class roaring_beam_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "roaring_beam_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    global $CombatChain;
    PlayAura("courage", $this->controller);
    $soul = GetSoul($this->controller);
    if (count($soul) == 0) {
      AddPlayerHand($this->cardID, $this->controller, "CC");
      $CombatChain->Remove(SearchCombatChainForIndex($this->cardID, $this->controller));
      Charge(false, $this->controller);
    }
  }
}

class pound_of_flesh_blue extends Card {
  function __construct($controller) {
    $this->cardID = "pound_of_flesh_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    MZChooseAndBanish($this->controller, "MYHAND", "HAND,-");
    AddDecisionQueue("POUNDOFFLESHCHOICE", $this->controller, "<-", 1);
    MZChooseAndBanish($otherPlayer, "MYHAND", "HAND,-");
    AddDecisionQueue("POUNDOFFLESHCHOICE", $otherPlayer, "<-", 1);
  }
}

class sigil_of_voltaris_blue extends Card {
  function __construct($controller) {
    $this->cardID = "sigil_of_voltaris_blue";
    $this->controller = $controller;
  }

  function BeginningActionPhaseAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->UniqueID());
  }

  function EntersArenaAbility() {
    global $CS_ArcaneTargetsSelected;
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
    AddDecisionQueue("SETCLASSSTATE", $this->controller, parameter: $CS_ArcaneTargetsSelected);
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-"): void {
    global $CS_ArcaneTargetsSelected;
    SetArcaneTarget($this->controller, $this->cardID, 0);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
    AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
    AddDecisionQueue("SETCLASSSTATE", $this->controller, $CS_ArcaneTargetsSelected);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CS_ArcaneTargetsSelected;
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      if ($AuraCard != "") $AuraCard->Destroy();
    }
    else {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $target, 1);
      AddDecisionQueue("DEALARCANE", $this->controller, 1, 1);
      AddDecisionQueue("PASSPARAMETER", $this->controller, "-");
      AddDecisionQueue("SETCLASSSTATE", $this->controller, $CS_ArcaneTargetsSelected);
    }
  }
}

class four_feathers_one_crown_red extends Card {
  function __construct($controller) {
    $this->cardID = "four_feathers_one_crown_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddLayer("TRIGGER", $this->controller, $this->cardID);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    $cardList = SearchDiscardNameContains($this->controller, "phoenix_bannerman");
    $CombatChain->Card(0)->ModifyPower(count($cardList));
  }
} 

class phoenix_bannerman extends BaseCard {

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if(CanRevealCards($this->controller)) {
      MZMoveCard($this->controller, "MYDECK:isSameName=phoenix_flame_red", "MYHAND", may:true);
      AddDecisionQueue("SHUFFLEDECK", $this->controller, "-", 1);
    }
  }
}
class phoenix_bannerman_head_red extends Card {
  function __construct($controller) {
    $this->cardID = "phoenix_bannerman_head_red";
    $this->controller = $controller;
    $this->baseCard = new phoenix_bannerman($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
    PlayAura("ponder", $this->controller);
  }
}

class phoenix_bannerman_chest_red extends Card {
  function __construct($controller) {
    $this->cardID = "phoenix_bannerman_chest_red";
    $this->controller = $controller;
    $this->baseCard = new phoenix_bannerman($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
    PlayAura("vigor", $this->controller);
  }
}

class phoenix_bannerman_arms_red extends Card {
  function __construct($controller) {
    $this->cardID = "phoenix_bannerman_arms_red";
    $this->controller = $controller;
    $this->baseCard = new phoenix_bannerman($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
    PlayAura("might", $this->controller);
  }
}

class phoenix_bannerman_legs_red extends Card {
  function __construct($controller) {
    $this->cardID = "phoenix_bannerman_legs_red";
    $this->controller = $controller;
    $this->baseCard = new phoenix_bannerman($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
    PlayAura("agility", $this->controller);
  }
}

class buzzard_helm extends Card {
  function __construct($controller) {
    $this->cardID = "buzzard_helm";
    $this->controller = $controller;
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    global $CombatChain;
    $uid = $CombatChain->Card($i)->UniqueID();
    AddLayer("TRIGGER", $this->controller, $this->cardID, $uid);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $CombatChain;
    Draw($this->controller);
    $card = DiscardRandom($this->controller, $this->cardID, $this->controller);
    if(ModifiedPowerValue($card, $this->controller, "HAND", source:$this->cardID) >= 6){
      $defCard = $CombatChain->FindCardUID($target);
      if ($defCard != "") {
        AddDecisionQueue("PASSPARAMETER", $this->controller, $defCard->Index(), 1);
        AddDecisionQueue("COMBATCHAINDEFENSEMODIFIER", $this->controller, 1, 1);
      }
    }
  }
}

class rites_of_earthlore extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    PlayAura("seismic_surge", $this->controller);
  }

  function StartTurnAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "DESTROY", $AuraCard->uniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    if ($additionalCosts == "DESTROY") {
      $Auras = new Auras($this->controller);
      $AuraCard = $Auras->FindCardUID($uniqueID);
      $AuraCard->Destroy();
      AddCurrentTurnEffect($this->cardID, $this->controller);
    }
  }

  function CombatEffectActive() {
    global $CombatChain;
    return ClassContains($CombatChain->AttackCard()->ID(), "GUARDIAN", $this->controller) && CardType($CombatChain->AttackCard()->ID()) == "AA";
  }
}

class rites_of_earthlore_red extends Card {
  function __construct($controller) {
    $this->cardID = "rites_of_earthlore_red";
    $this->controller = $controller;
    $this->baseCard = new rites_of_earthlore($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, $target, $additionalCosts, $from);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class rites_of_earthlore_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "rites_of_earthlore_yellow";
    $this->controller = $controller;
    $this->baseCard = new rites_of_earthlore($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, $target, $additionalCosts, $from);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class rites_of_earthlore_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rites_of_earthlore_blue";
    $this->controller = $controller;
    $this->baseCard = new rites_of_earthlore($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function StartTurnAbility($index) {
    $this->baseCard->StartTurnAbility($index);
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    $this->baseCard->ProcessTrigger($uniqueID, $target, $additionalCosts, $from);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class assembly_module_blue extends Card {
  function __construct($controller) {
    $this->cardID = "assembly_module_blue";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false)
  {
    if ($from == "PLAY" && CheckTapped("MYITEMS-$index", $this->controller))
      return true;
    return false;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "PLAY") {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDECK:isSameName=hyper_driver_red");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a " . CardLink("hyper_driver", "hyper_driver") . "?", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
      AddDecisionQueue("PUTPLAY", $this->controller, "-", 1);
      AddDecisionQueue("SHUFFLEDECK", $this->controller, "-");
    }
  }

  function PayAdditionalCosts($from, $index = '-') {
    if ($from == "PLAY") Tap("MYITEMS-$index", $this->controller);
  }
}

class line_crossers extends Card {
  function __construct($controller) {
    $this->cardID = "line_crossers";
    $this->controller = $controller;
  }
}

class doubling_season_red extends Card {
  function __construct($controller) {
    $this->cardID = "doubling_season_red";
    $this->controller = $controller;
  }
}


class evo_beta_base_head_blue extends Card {
  function __construct($controller) {
    $this->cardID = "evo_beta_base_head_blue";
    $this->controller = $controller;
  }
}

class evo_beta_base_chest_blue extends Card {
  function __construct($controller) {
    $this->cardID = "evo_beta_base_chest_blue";
    $this->controller = $controller;
  }
}

class evo_beta_base_arms_blue extends Card {
  function __construct($controller) {
    $this->cardID = "evo_beta_base_arms_blue";
    $this->controller = $controller;
  }
}

class evo_beta_base_legs_blue extends Card {
  function __construct($controller) {
    $this->cardID = "evo_beta_base_legs_blue";
    $this->controller = $controller;
  }
}
    
class speed_demon_red extends Card {
  function __construct($controller) {
    $this->cardID = "speed_demon_red";
    $this->controller = $controller;
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return SearchItemsForCardName("Hyper Driver", $this->controller) != "" ? 1 : 0;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1){
    if(DelimStringContains($additionalCosts, "hyper_driver", true)) {
      PutItemIntoPlayForPlayer("hyper_driver", $this->controller, 2);
    }

  }
}

class dyed_silk_sleeves extends Card {
  function __construct($controller) {
    $this->cardID = "dyed_silk_sleeves";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "AR";
  }

  function AbilityCost() {
    return 1;
  }

  function PayAdditionalCosts($from, $index = '-') {
    Tap("MYCHAR-$index", $this->controller);
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYCHAR:subtype=Dagger&COMBATCHAINATTACKS:subtype=Dagger;type=AA", 1);
    AddDecisionQueue("REMOVEINDICESIFACTIVECHAINLINK", $this->controller, "<-", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
    if (!$CombatChain->HasCurrentLink()) return true;
    $attackCard = $CombatChain->AttackCard()->ID();
    if (!TypeContains($attackCard, "AA") || !ClassContains($attackCard, "NINJA", $this->controller)) return true;
    if (!SearchCharacterAliveSubtype($this->controller, "Dagger", true) && SearchCombatChainAttacks($this->controller, subtype:"Dagger", type:"AA") == "") {
      $restriction = "No dagger to poke with";
      return true;
    }
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function ResolutionStepEffectTriggers($parameter) {
    global $combatChainState, $CCS_DamageDealt;
    if ($combatChainState[$CCS_DamageDealt] == 0) {
      $Character = new PlayerCharacter($this->controller);
      $CharacterCard = $Character->FindCardID($this->cardID);
      $CharacterCard->Destroy();
    }
  }
}

class feign_vengeance_blue extends Card {
  function __construct($controller) {
    $this->cardID = "feign_vengeance_blue";
    $this->controller = $controller;
  }

  function ResolutionStepAttackTriggers() {
    global $CombatChain, $defPlayer;
    for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
      if ($CombatChain->Card($i, true)->PlayerID() == $defPlayer) {
        AddLayer("TRIGGER", $this->controller, $this->cardID);
        return;
      }
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    Draw($this->controller, effectSource:$this->cardID);
  }
}

class become_the_bottle extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    global $ChainLinks;
    $choices = [];
    for ($i = 0; $i < $ChainLinks->NumLinks(); ++$i) {
      $Link = $ChainLinks->GetLink($i);
      for ($j = 0; $j < $Link->NumCards(); ++$j) {
        $LinkCard = $Link->GetLinkCard($j, true);
        $ind = $LinkCard->Index();
        if (!TypeContains($LinkCard->ID(), "AR")) array_push($choices, "PASTCHAINLINK-$ind-$i");
      }
    }
    $choices = implode(",", $choices);
    if($ChainLinks->NumLinks() > 0) {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $choices);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card for the bottle to become", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SPECIFICCARD", $this->controller, "BOTTLE", 1);
    }
  }
}

class become_the_bottle_red extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_bottle_red";
    $this->controller = $controller;
    $this->baseCard = new become_the_bottle($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class become_the_bottle_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_bottle_yellow";
    $this->controller = $controller;
    $this->baseCard = new become_the_bottle($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class become_the_bottle_blue extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_bottle_blue";
    $this->controller = $controller;
    $this->baseCard = new become_the_bottle($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class become_the_cup extends BaseCard {
  function PayAdditionalCosts() {
    AddDecisionQueue("BUTTONINPUT", $this->controller, "Red,Yellow,Blue", 1);
    AddDecisionQueue("SETDQVAR", $this->controller, "0", subsequent: 1);
    AddDecisionQueue("WRITELOG", $this->controller, CardLink($this->cardID) . " gains {0} color", 1);
    AddDecisionQueue("SPECIFICCARD", $this->controller, "BECOMETHECUP", 1);
    AddDecisionQueue("PREPENDLASTRESULT", $this->controller, $this->cardID . "-", 1);
    AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, "<-", 1);
  }
}

class become_the_cup_red extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_cup_red";
    $this->controller = $controller;
    $this->baseCard = new become_the_cup($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class become_the_cup_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_cup_yellow";
    $this->controller = $controller;
    $this->baseCard = new become_the_cup($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class become_the_cup_blue extends Card {
  function __construct($controller) {
    $this->cardID = "become_the_cup_blue";
    $this->controller = $controller;
    $this->baseCard = new become_the_cup($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->PayAdditionalCosts();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class heart_wrencher_red extends Card {
  function __construct($controller) {
    $this->cardID = "heart_wrencher_red";
    $this->controller = $controller;
  }
}

class heart_wrencher_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "heart_wrencher_yellow";
    $this->controller = $controller;
  }
}

class heart_wrencher_blue extends Card {
  function __construct($controller) {
    $this->cardID = "heart_wrencher_blue";
    $this->controller = $controller;
  }
}

class concealed_pathogen extends Card {
  function __construct($controller) {
    $this->cardID = "concealed_pathogen";
    $this->controller = $controller;
  }

  function DefaultActiveState() {
    return 0;
  }

  function GetHitTrigger($source) {
    if ($source == "CURRENTATTACK") {
      $Character = new PlayerCharacter($this->controller);
      $CharCard = $Character->FindCardID($this->cardID);
      if ($CharCard != "" && $CharCard->IsActive() && NumAttackReactionsPlayed() > 0 && $CharCard->Facing() == "DOWN") {
        AddDecisionQueue("YESNO", $this->controller, "Do you want to release the pathogen?");
        AddDecisionQueue("NOPASS", $this->controller, "-", 1);
        AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
      }
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    TrapTriggered($this->cardID);
    $Character = new PlayerCharacter($this->controller);
    $CharCard = $Character->FindCardID($this->cardID);
    $CharCard->Destroy();
    PlayAura("bloodrot_pox", $mainPlayer);
  }
}

class concealed_sedative extends Card {
  function __construct($controller) {
    $this->cardID = "concealed_sedative";
    $this->controller = $controller;
  }

  function DefaultActiveState() {
    return 0;
  }

  function GetHitTrigger($source) {
    if ($source == "CURRENTATTACK") {
      $Character = new PlayerCharacter($this->controller);
      $CharCard = $Character->FindCardID($this->cardID);
      if ($CharCard != "" && $CharCard->IsActive() && HasIncreasedAttack() && $CharCard->Facing() == "DOWN") {
        AddDecisionQueue("YESNO", $this->controller, "Do you want to release the sedative?");
        AddDecisionQueue("NOPASS", $this->controller, "-", 1);
        AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
      }
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    TrapTriggered($this->cardID);
    $Character = new PlayerCharacter($this->controller);
    $CharCard = $Character->FindCardID($this->cardID);
    $CharCard->Destroy();
    PlayAura("inertia", $mainPlayer);
  }
}

class concealed_nerve_gas extends Card {
  function __construct($controller) {
    $this->cardID = "concealed_nerve_gas";
    $this->controller = $controller;
  }

  function DefaultActiveState() {
    return 0;
  }

  function GetHitTrigger($source) {
    if ($source == "CURRENTATTACK") {
      $Character = new PlayerCharacter($this->controller);
      $CharCard = $Character->FindCardID($this->cardID);
      if ($CharCard != "" && $CharCard->IsActive() && DoesAttackHaveGoAgain() && $CharCard->Facing() == "DOWN") {
        AddDecisionQueue("YESNO", $this->controller, "Do you want to release the nerve gas?");
        AddDecisionQueue("NOPASS", $this->controller, "-", 1);
        AddDecisionQueue("ADDTRIGGER", $this->controller, $this->cardID, 1);
      }
    }
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    global $mainPlayer;
    TrapTriggered($this->cardID);
    $Character = new PlayerCharacter($this->controller);
    $CharCard = $Character->FindCardID($this->cardID);
    $CharCard->Destroy();
    PlayAura("frailty", $mainPlayer);
  }
}

class verdant_tide_red extends Card {
  function __construct($controller) {
    $this->cardID = "verdant_tide_red";
    $this->controller = $controller;
  }

  function CardCaresAboutPitch() {
    return true;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1)
  {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    if (SearchCardList($additionalCosts, $this->controller, talent: "EARTH") != "") {
      PlayAura("embodiment_of_earth", $this->controller);
    }
  }
}

class shimmering_specter extends BaseCard {
  function CombatChainCloseAbility($chainLink) {
    PlayAura("spectral_shield", $this->controller);
  }

  function BlockCardDestroyed() {
    PlayAura("spectral_shield", $this->controller);
  }
}

class shimmering_specter_red extends Card {
  function __construct($controller) {
    $this->cardID = "shimmering_specter_red";
    $this->controller = $controller;
    $this->baseCard = new shimmering_specter($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function BlockCardDestroyed() {
    $this->baseCard->BlockCardDestroyed();
  }

  function SpecialBlock() {
    return 2;
  }
}

class shimmering_specter_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "shimmering_specter_yellow";
    $this->controller = $controller;
    $this->baseCard = new shimmering_specter($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }

  function BlockCardDestroyed() {
    $this->baseCard->BlockCardDestroyed();
  }

  function SpecialBlock() {
    return 2;
  }
}

class shimmering_specter_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shimmering_specter_blue";
    $this->controller = $controller;
    $this->baseCard = new shimmering_specter($this->cardID, $this->controller);
  }

  function CombatChainCloseAbility($chainLink) {
    $this->baseCard->CombatChainCloseAbility($chainLink);
  }
  
  function BlockCardDestroyed() {
    $this->baseCard->BlockCardDestroyed();
  }

  function SpecialBlock() {
    return 2;
  }
}

class rend_flesh_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rend_flesh_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return SubtypeContains($CombatChain->AttackCard()->ID(), "Sword");
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    global $combatChainState, $CCS_WeaponIndex, $defPlayer;
    $CharCard = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
    if ($CharCard->NumPowerCounters() > 0) {
      AddDecisionQueue("YESNO", $this->controller, "if_you_want_to_remove_a_counter_from_" . CardLink($CharCard->CardID()) . "?");
      AddDecisionQueue("NOPASS", $this->controller, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $this->controller, "MYCHAR-" . $CharCard->Index(), 1);
      AddDecisionQueue("MZOP", $this->controller, "REMOVEPOWERCOUNTER", 1);
      AddDecisionQueue("PASSPARAMETER", $defPlayer, 2, 1);
      AddDecisionQueue("OP", $defPlayer, "LOSEHEALTH", 1);
    }
  }
  
  function AddEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-') {
    global $CombatChain;
    if (IsHeroAttackTarget() && SubtypeContains($CombatChain->AttackCard()->ID(), "Sword"))
      AddLayer("TRIGGER", $this->controller, $parameter, $this->cardID, "EFFECTHITEFFECT");
    return false;
  }
}

class deep_recesses_of_existence_blue extends Card {
  function __construct($controller) {
    $this->cardID = "deep_recesses_of_existence_blue";
    $this->controller = $controller;
  }
}

class power_of_make_believe extends BaseCard {
  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    global $combatChain, $defPlayer, $CombatChain;
    $modifier = 0;
    for ($i = CombatChainPieces(); $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i + 1] == $defPlayer && $CombatChain->Card($i)->TotalPower() >= 6) $modifier += 1;
    }
    return $modifier;
  }
}

class power_of_make_believe_red extends Card {
  function __construct($controller) {
    $this->cardID = "power_of_make_believe_red";
    $this->controller = $controller;
    $this->baseCard = new power_of_make_believe($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class power_of_make_believe_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "power_of_make_believe_yellow";
    $this->controller = $controller;
    $this->baseCard = new power_of_make_believe($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class power_of_make_believe_blue extends Card {
  function __construct($controller) {
    $this->cardID = "power_of_make_believe_blue";
    $this->controller = $controller;
    $this->baseCard = new power_of_make_believe($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier();
  }
}

class shimmering_mirage_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shimmering_mirage_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ResolutionStepBlockTrigger($i) {
    global $CombatChain;
    $ChainCard = $CombatChain->Card($i, true);
    $ChainCard->Remove();
    BanishCardForPlayer($this->cardID, $this->controller, "CC", "TCC");
  }
}

class display_of_craftsmanship extends BaseCard {
  function GetLayerTarget() {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "COMBATCHAINATTACKS:type=W&ACTIVEATTACK:type=W");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a weapon attack");
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);  
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function PlayAbility($target) {
    global $combatChainState, $CCS_WeaponIndex, $CombatChain;
    global $CombatChain, $CurrentTurnEffects;
    if (TypeContains($CombatChain->AttackCard()->ID(), "W", $this->controller)) {
    // if ($target == "COMBATCHAINLINK-0") { //TODO make it possible to target past links reasonably
      AddCurrentTurnEffect($this->cardID, $this->controller);
      $ClassState = new ClassState($this->controller);
      $originUID = $CombatChain->AttackCard()->OriginUniqueID();
      $foundSharpen = $CurrentTurnEffects->FindSpecificEffect("hala_bladesaint_of_the_vow", $originUID);
      $WeaponCard = new CharacterCard($combatChainState[$CCS_WeaponIndex], $this->controller);
      if ($foundSharpen != "") $WeaponCard->AddPowerCounters(1);
    }
  }
}

class display_of_craftsmanship_red extends Card {
  function __construct($controller) {
    $this->cardID = "display_of_craftsmanship_red";
    $this->controller = $controller;
    $this->baseCard = new display_of_craftsmanship($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if (!$CombatChain->HasCurrentLink()) return true;
    if (SearchCombatChainAttacks($this->controller, type:"W") != "") return false;
    if (TypeContains($CombatChain->AttackCard()->ID(), "W", $this->controller)) return false;
    return true;
}

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 4;
  }
}

class display_of_craftsmanship_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "display_of_craftsmanship_yellow";
    $this->controller = $controller;
    $this->baseCard = new display_of_craftsmanship($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if (!$CombatChain->HasCurrentLink()) return true;
    if (SearchCombatChainAttacks($this->controller, type:"W") != "") return false;
    if (TypeContains($CombatChain->AttackCard()->ID(), "W", $this->controller)) return false;
    return true;
}

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class display_of_craftsmanship_blue extends Card {
  function __construct($controller) {
    $this->cardID = "display_of_craftsmanship_blue";
    $this->controller = $controller;
    $this->baseCard = new display_of_craftsmanship($this->cardID, $this->controller);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if (!$CombatChain->HasCurrentLink()) return true;
    if (SearchCombatChainAttacks($this->controller, type:"W") != "") return false;
    if (TypeContains($CombatChain->AttackCard()->ID(), "W", $this->controller)) return false;
    return true;
}

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 2;
  }
}

class cut_n_carve extends BaseCard {
  function GetLayerTarget() {
		$search = "MYCHAR:subtype=Sword";
		AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a sword to sharpen");
		AddDecisionQueue("MULTIZONEINDICES", $this->controller, $search, 1);
		AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
		AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
		AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
	}

  function PlayAbility($target) {
    $uid = explode("-", $target)[1] ?? -1;
		$index = SearchCharacterForUniqueID($uid, $this->controller);
		if ($index != -1) {
			Sharpen("MYCHAR-$index", $this->controller);
			$weaponCard = new CharacterCard($index, $this->controller);
      $threshold = match($this->cardID) {
        "cut_n_carve_red" => 0,
        "cut_n_carve_yellow" => 1,
        "cut_n_carve_blue" => 2,
      };
			if ($weaponCard->NumPowerCounters() > $threshold) {
				AddCurrentTurnEffect($this->cardID, $this->controller, "-", $weaponCard->UniqueID());
			}
		}
  }
}

class cut_n_carve_red extends Card {
  function __construct($controller) {
    $this->cardID = "cut_n_carve_red";
    $this->controller = $controller;
    $this->baseCard = new cut_n_carve($this->cardID, $this->controller);
  }

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function DoesEffectGrantDominate() {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }
}

class cut_n_carve_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "cut_n_carve_yellow";
    $this->controller = $controller;
    $this->baseCard = new cut_n_carve($this->cardID, $this->controller);
  }

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function DoesEffectGrantDominate() {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }
}

class cut_n_carve_blue extends Card {
  function __construct($controller) {
    $this->cardID = "cut_n_carve_blue";
    $this->controller = $controller;
    $this->baseCard = new cut_n_carve($this->cardID, $this->controller);
  }

  function GetLayerTarget($from) {
    $this->baseCard->GetLayerTarget();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($target);
  }

  function DoesEffectGrantDominate() {
    return true;
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }
}
class graven_gaslight extends Card {
  function __construct($controller) {
    $this->cardID = "graven_gaslight";
    $this->controller = $controller;
  }

  function PlayableFromGraveyard($index) {
    return true;
  }

  function AbilityType($index = -1, $from = '-') {
    if ($from == "GY") return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if ($from == "GY") return CountItem("silver", $this->controller) < 2; 
    else return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    if ($from == "GY") {
      $discardIndex = SearchDiscardForUniqueID($target, $this->controller);
      if ($discardIndex != -1) {
        RemoveDiscard($this->controller, $discardIndex);
        $character = &GetPlayerCharacter($this->controller);
        EquipWeapon($this->controller, "graven_gaslight");
      }
      else {
        WriteLog("Graven Gaslight failed to be equipped");
      }
    }
    return "";
  }
}

class graven_cowl extends Card {
  function __construct($controller) {
    $this->cardID = "graven_cowl";
    $this->controller = $controller;
  }
}
class graven_vestment extends Card {
  function __construct($controller) {
    $this->cardID = "graven_vestment";
    $this->controller = $controller;
  }
}
class graven_gloves extends Card {
  function __construct($controller) {
    $this->cardID = "graven_gloves";
    $this->controller = $controller;
  }
}
class graven_walkers extends Card {
  function __construct($controller) {
    $this->cardID = "graven_walkers";
    $this->controller = $controller;
  }
}

class shapeless_form_blue extends Card {
  function __construct($controller) {
    $this->cardID = "shapeless_form_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }
}

class look_within_blue extends Card {
  function __construct($controller) {
    $this->cardID = "look_within_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDECK:subtype=Chi");
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
    AddDecisionQueue("SHUFFLEDECK", $this->controller, "-");
    AddDecisionQueue("REVEALCARDS", $this->controller, "-", 1);
    AddDecisionQueue("MULTIADDTOPDECK", $this->controller, "-", 1);
  }
}

class spreading_mist_blue extends Card {
  function __construct($controller) {
    $this->cardID = "spreading_mist_blue";
    $this->controller = $controller;
  }
    function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID . "-GOAGAIN", $this->controller);
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    if ($parameter == "GOAGAIN") {
      return true;
    }
  }
}

class billowing_mist_blue extends Card {
  function __construct($controller) {
    $this->cardID = "billowing_mist_blue";
    $this->controller = $controller;
  }
    function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID . "-BUFF", $this->controller);
    AddCurrentTurnEffect($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    if ($parameter == "BUFF") {
      return true;
    }
  }

  function EffectPowerModifier($param, $attached = false)
  {
    if ($param == "BUFF") {
      return 1;
    }
  }
}

class descend_into_madness_blue extends Card {
  function __construct($controller) {
    $this->cardID = "descend_into_madness_blue";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    BanishRandom($otherPlayer, $this->cardID);
    Draw($otherPlayer, effectSource:$this->cardID);
  }
}

class tough_as_a_rok_blue extends Card {
  function __construct($controller) {
    $this->cardID = "tough_as_a_rok_blue";
    $this->controller = $controller;
  }
}

class rockyard_rodeo_blue extends Card {
  function __construct($controller) {
    $this->cardID = "rockyard_rodeo_blue";
    $this->controller = $controller;
  }
}

class bad_breath extends BaseCard {
  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    Intimidate();
  }

  function AddCardEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-') {
    AddLayer("TRIGGER", $this->controller, $parameter, $this->cardID, "EFFECTHITEFFECT");
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

}

class bad_breath_red extends Card {
  function __construct($controller) {
    $this->cardID = "bad_breath_red";
    $this->controller = $controller;
    $this->baseCard = new bad_breath($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    PlayAura("might", $this->controller, 3);
    return 1;
  }
  
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function AddCardEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-') {
    $this->baseCard->AddCardEffectHitTrigger($source, $fromCombat, $target, $parameter);
  }
}

class bad_breath_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "bad_breath_yellow";
    $this->controller = $controller;
    $this->baseCard = new bad_breath($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    PlayAura("might", $this->controller, 2);
    return 1;
  }
  
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function AddCardEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-') {
    $this->baseCard->AddCardEffectHitTrigger($source, $fromCombat, $target, $parameter);
  }
}

class bad_breath_blue extends Card {
  function __construct($controller) {
    $this->cardID = "bad_breath_blue";
    $this->controller = $controller;
    $this->baseCard = new bad_breath($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
  }

  function EffectHitEffect($from, $source = '-', $effectSource = '-', $param = '-', $mode = '-') {
    PlayAura("might", $this->controller, 1);
    return 1;
  }
  
  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function AddCardEffectHitTrigger($source = '-', $fromCombat = true, $target = '-', $parameter = '-') {
    $this->baseCard->AddCardEffectHitTrigger($source, $fromCombat, $target, $parameter);
  }
}

class mist_hunter_red extends Card {
  function __construct($controller) {
    $this->cardID = "mist_hunter_red";
    $this->controller = $controller;
  }

  function ContractType($chosenName = '') {
    return "BLUEPITCH";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    global $defPlayer;
    $DefHero = new CharacterCard(0, $defPlayer);
    if (IsHeroAttackTarget() && TalentContains($DefHero->CardID(), "MYSTIC", $defPlayer)) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    global $defPlayer;
    $search = SearchDeckByName($defPlayer, "Inner Chi");
    $defDeck = new Deck($defPlayer);
    for ($i = 0; $i < SearchCount($search); ++$i) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "THEIRDECK:isSameName=MST000_inner_chi_blue", 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Hunt the mists", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZBANISH", $this->controller, "-,Source-" . $this->cardID . "," . $this->cardID, 1);
      AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    }
    AddDecisionQueue("FINDINDICES", $defPlayer, "DECKTOPXINDICES," . $defDeck->RemainingCards());
    AddDecisionQueue("DECKCARDS", $defPlayer, "<-", 1);
    AddDecisionQueue("LOOKTOPDECK", $defPlayer, "-", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, CardLink($this->cardID) . " shows your opponent's deck", 1);
    AddDecisionQueue("MULTISHOWCARDSTHEIRDECK", $this->controller, "<-", 1);
    AddDecisionQueue("SHUFFLEDECK", $defPlayer, "-");
  }
}

class excessive_bloodloss extends BaseCard {
  function AddOnHitTrigger($check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect() {
    global $defPlayer;
    $defDeck = new Deck($defPlayer);
    $numBanishes = 1;
    if (ColorContains($defDeck->Top(), 1, $defPlayer)) ++$numBanishes;
    for ($i = 0; $i < $numBanishes; ++$i) {
      $defDeck->BanishTop();
    }
  }
}

class excessive_bloodloss_red extends Card {
  function __construct($controller) {
    $this->cardID = "excessive_bloodloss_red";
    $this->controller = $controller;
    $this->baseCard = new excessive_bloodloss($this->cardID, $this->controller);
  }

  function ContractType($chosenName = '') {
    return "REDPITCH";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->HitEffect();
  }
}

class excessive_bloodloss_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "excessive_bloodloss_yellow";
    $this->controller = $controller;
    $this->baseCard = new excessive_bloodloss($this->cardID, $this->controller);
  }

  function ContractType($chosenName = '') {
    return "REDPITCH";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->HitEffect();
  }
}

class excessive_bloodloss_blue extends Card {
  function __construct($controller) {
    $this->cardID = "excessive_bloodloss_blue";
    $this->controller = $controller;
    $this->baseCard = new excessive_bloodloss($this->cardID, $this->controller);
  }

  function ContractType($chosenName = '') {
    return "REDPITCH";
  }

  function ContractCompleted() {
    PutItemIntoPlayForPlayer("silver", $this->controller);
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->AddOnHitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    return $this->baseCard->HitEffect();
  }
}

class burnished_bunkerplate extends Card {
  function __construct($controller) {
    $this->cardID = "burnished_bunkerplate";
    $this->controller = $controller;
  }

  function GoesOnCombatChain($phase, $from) {
    return $phase == "B";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if (!$CombatChain->HasCurrentLink()) return true;
    return SearchArsenal($this->controller, type:"A") == "" && SearchArsenal($this->controller, type:"AA");
  }

  function AbilityType($index = -1, $from = '-') {
    return "DR";
  }

  function EquipPayAdditionalCosts($cardIndex = "-") {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $canBlock = CanBlock("fry_red", "ARS"); //a little hacky for now, just checking, "can you block with an AA from arsenal"
    if ($canBlock) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYARS:type=A&MYARS:type=AA");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a card to add as a defending card", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZREMOVE", $this->controller, "-", 1);
      AddDecisionQueue("ADDCARDTOCHAINASDEFENDINGCARD", $this->controller, "ARS", 1);
    }
    else WriteLog("You cannot add your arsenal as a defending card");
  }
}

class carrion_crown extends Card {
  function __construct($controller) {
    $this->cardID = "carrion_crown";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    return SearchHand($this->controller, subtype:"Ally") == "";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $ChacterCard = new CharacterCard($index, $this->controller);
    $ChacterCard->Destroy();
    AddDecisionQueue("FINDINDICES", $this->controller, "HANDSUBTYPE,Ally,NOPASS");
    AddDecisionQueue("REVERTGAMESTATEIFNULL", $this->controller, "You don't have any allies in hand to discard!", 1);
    AddDecisionQueue("CHOOSEHAND", $this->controller, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $this->controller, "-", 1);
    AddDecisionQueue("DISCARDCARD", $this->controller, "HAND-" . $this->controller, 1);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    Draw($this->controller);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class mournful_casket extends Card {
  function __construct($controller) {
    $this->cardID = "mournful_casket";
    $this->controller = $controller;
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    global $CS_NumAllyPutInGraveyard;
    return GetClassState($this->controller, $CS_NumAllyPutInGraveyard) > 0 ? 1 : 0;
  }
}

class reach_beyond_the_grave extends Card {
  function __construct($controller) {
    $this->cardID = "reach_beyond_the_grave";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "A";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $ChacterCard = new CharacterCard($index, $this->controller);
    $ChacterCard->Destroy();
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:subtype=Ally");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an ally to return to hand", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("ADDHAND", $this->controller, "<-", 1);
    PummelHit($this->controller);
  }

  function AbilityHasGoAgain($from) {
    return true;
  }
}

class scuttle_toes extends Card {
  function __construct($controller) {
    $this->cardID = "scuttle_toes";
    $this->controller = $controller;
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    $Allies = new Allies($this->controller);
    return $Allies->NumAllies() == 0;
  }

  function AbilityCost() {
    return 2;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PayAdditionalCosts($from, $index = '-') {
    $ChacterCard = new CharacterCard($index, $this->controller);
    $ChacterCard->Destroy();
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYALLY");
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose an ally to untap an ally", 1);
    AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "<-", 1);
    AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Allies = new Allies($this->controller);
    $TargetAlly = $Allies->FindCardUID(explode("-", $target)[1]);
    Tap("MYALLY-" . $TargetAlly->Index(), $this->controller, 0);
    AddCurrentTurnEffect($this->cardID, $this->controller, "-", $TargetAlly->UniqueID());
  }

  function CurrentEffectEndTurnAbilities($i, &$remove) {
    $Effect = new CurrentEffect($i);
    $Allies = new Allies($this->controller);
    $TargetAlly = $Allies->FindCardUID($Effect->AppliestoUniqueID());
    $TargetAlly->Destroy();
    $remove = true;
  }

  function ArcaneBarrier() {
    return 1;
  }
}

class beneath_the_surface_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "beneath_the_surface_yellow";
    $this->controller = $controller;
  }
}

class tentacular_toll extends BaseCard {
  function PlayAbility($n) {
    for ($i = 0; $i < $n; ++$i) {
      AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYDISCARD:subtype=Ally", 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Turn an ally into gold?", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("MZOP", $this->controller, "FLIP", 1);
      AddDecisionQueue("PLAYITEM", $this->controller, "gold", 1);
    }
  }
}

class tentacular_toll_red extends Card {
  function __construct($controller) {
    $this->cardID = "tentacular_toll_red";
    $this->controller = $controller;
    $this->baseCard = new tentacular_toll($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(3);
  }
}

class tentacular_toll_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "tentacular_toll_red";
    $this->controller = $controller;
    $this->baseCard = new tentacular_toll($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(2);
  }
}

class tentacular_toll_blue extends Card {
  function __construct($controller) {
    $this->cardID = "tentacular_toll_red";
    $this->controller = $controller;
    $this->baseCard = new tentacular_toll($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility(1);
  }
}

class lighten_the_load extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYITEMS&MYHAND", 1);
    AddDecisionQueue("SETDQCONTEXT", $this->controller, "Destroy an item or discard a card?", 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZREMOVE", $this->controller, "<-", 1);
    AddDecisionQueue("ADDDISCARD", $this->controller, "<-", 1);
    AddDecisionQueue("OP", $this->controller, "GIVEATTACKGOAGAIN", 1);
  }
}

class lighten_the_load_red extends Card {
  function __construct($controller) {
    $this->cardID = "lighten_the_load_red";
    $this->controller = $controller;
    $this->baseCard = new lighten_the_load($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class lighten_the_load_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "lighten_the_load_yellow";
    $this->controller = $controller;
    $this->baseCard = new lighten_the_load($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class lighten_the_load_blue extends Card {
  function __construct($controller) {
    $this->cardID = "lighten_the_load_blue";
    $this->controller = $controller;
    $this->baseCard = new lighten_the_load($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }
}

class herald_of_victoria_yellow extends Card {
  public $archetype;

  function __construct($controller) {
    $this->cardID = "herald_of_victoria_yellow";
    $this->controller = $controller;
    $this->archetype = new windup($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function ProcessAbility($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    AddCurrentTurnEffect($this->cardID, $this->controller == 1 ? 2 : 1);
  }

  function CardCost($from = '-') {
    if (GetResolvedAbilityType($this->cardID, "HAND") == "I" && $from == "HAND") return 0;
    return 2;
  }

  function GetAbilityTypes($index = -1, $from = '-') {
    return $this->archetype->GetAbilityTypes($index, $from);
  }

  function GetAbilityNames($index = -1, $from = '-', $foundNullTime = false, $layerCount = 0, $facing = "-") {
    return $this->archetype->GetAbilityNames($index, $from, $foundNullTime, $layerCount);
  }

  function GoesOnCombatChain($phase, $from) {
    return $this->archetype->GoesOnCombatChain($phase, $from);
  }

  function CanActivateAsInstant($index = -1, $from = '') {
    return $this->archetype->CanActivateAsInstant($index, $from);
  }

  function AddPrePitchDecisionQueue($from, $index = -1, $facing="-") {
    return $this->archetype->AddPrePitchDecisionQueue($from, $index);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return -1;
  }
}

class soul_bond_belief extends BaseCard {
  function PlayAbility() {
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "ATTACKTRIGGER");
  }

  function ProcessAttackTrigger() {
    if (CanRevealCards($this->controller)) {
      $Deck = new Deck($this->controller);
      $Deck->Reveal();
      if (ColorContains($Deck->Top(), 2, $this->controller)) {
        AddSoul($Deck->Top(true), $this->controller, "DECK");
        AddCurrentTurnEffect($this->cardID, $this->controller);
      }
    }
  }
}

class soul_bond_belief_red extends Card {
  function __construct($controller) {
    $this->cardID = "soul_bond_belief_red";
    $this->controller = $controller;
    $this->baseCard = new soul_bond_belief($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class soul_bond_belief_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "soul_bond_belief_yellow";
    $this->controller = $controller;
    $this->baseCard = new soul_bond_belief($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class soul_bond_belief_blue extends Card {
  function __construct($controller) {
    $this->cardID = "soul_bond_belief_blue";
    $this->controller = $controller;
    $this->baseCard = new soul_bond_belief($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility();
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    $this->baseCard->ProcessAttackTrigger();
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class sowing_thorns_red extends Card {
  function __construct($controller) {
    $this->cardID = "sowing_thorns_red";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $Discard = new Discard($this->controller);
    $Discard->RemoveTop(); //don't let them decompose itself
    Decompose($this->controller, "SOWINGTHORNS");
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1); // put it back in the graveyard
    AddDecisionQueue("ADDDISCARD", $this->controller, "-", 1);
    AddDecisionQueue("GAINLIFE", $this->controller, "1", 1);
    AddDecisionQueue("ELSE", $this->controller, "-"); //do this if you decline decompose
    AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID, 1); // put it back in the graveyard
    AddDecisionQueue("ADDDISCARD", $this->controller, "-", 1);
    AddDecisionQueue("GAINLIFE", $this->controller, "1", 1);
  }
}

class chain_of_brutality_red extends Card {
  function __construct($controller) {
    $this->cardID = "chain_of_brutality_red";
    $this->controller = $controller;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (CachedTotalPower() >= 6 && IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    AddCurrentTurnEffectNextAttack($this->cardID, $this->controller);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    return TypeContains($CombatChain->AttackCard()->ID(), "AA");
  }

  function DoesAttackHaveGoAgain() {
    return CachedTotalPower() >= 6;
  }

  function EffectSetBasePower($basePower) {
    return 6;
  }
}

class mbrio_base_digits extends Card {
  function __construct($controller) {
    $this->cardID = "mbrio_base_digits";
    $this->controller = $controller;
  }

  function GoesOnCombatChain($phase, $from) {
    return $phase == "B";
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    if (CheckTapped("MYCHAR-$index", $this->controller)) return true;
    if (GetUntapped($this->controller, "MYITEMS", "subtype=Cog") == "") return true;
    return false;
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
      Tap("MYCHAR-$cardIndex", $this->controller);
      $inds = GetUntapped($this->controller, "MYITEMS", "subtype=Cog");
      if($inds != "") Tap(explode(",", $inds)[0], $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $index = SearchCombatChainForIndex($this->cardID, $this->controller);
    if ($index != "") {
      AddDecisionQueue("ADDCURRENTTURNEFFECT", $this->controller, $this->cardID, 1);
    }
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    if (SearchCurrentTurnEffects($this->cardID, $this->controller)) return CountCurrentTurnEffects($this->cardID, $this->controller);
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }
}

class mbrio_base_vizier extends Card {
  function __construct($controller) {
    $this->cardID = "mbrio_base_vizier";
    $this->controller = $controller;
  }
}

class ion_charged_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "ion_charged_yellow";
    $this->controller = $controller;
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $otherPlayer = $this->controller == 1 ? 2 : 1;
    AddCurrentTurnEffect($this->cardID, $this->controller);
    AddCurrentTurnEffect($this->cardID, $otherPlayer);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    global $CombatChain;
    static $recursionGuard = false;
    if ($recursionGuard) return false;
    $recursionGuard = true;
    $hasGoAgain = DoesAttackHaveGoAgain();
    $recursionGuard = false;
    return $hasGoAgain && (TalentContains($CombatChain->AttackCard()->ID(), "LIGHTNING", $this->controller) || TalentContains($CombatChain->AttackCard()->ID(), "ELEMENTAL", $this->controller));
  }

  function IsCombatEffectPersistent($mode) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 1;
  }
}

class overcharge extends BaseCard {
  function PowerModifier($param, $attached = false) {
    global $combatChainState, $CCS_NumInstantsPlayedByAttackingPlayer;
    return $combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer] > 0 ? $param : 0;
  }
}

class overcharge_red extends Card {
  function __construct($controller) {
    $this->cardID = "overcharge_red";
    $this->controller = $controller;
    $this->baseCard = new overcharge($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier(3);
  }
}

class overcharge_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "overcharge_yellow";
    $this->controller = $controller;
    $this->baseCard = new overcharge($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier(2);
  }
}

class overcharge_blue extends Card {
  function __construct($controller) {
    $this->cardID = "overcharge_blue";
    $this->controller = $controller;
    $this->baseCard = new overcharge($this->cardID, $this->controller);
  }

  function PowerModifier($from = '', $resourcesPaid = 0, $repriseActive = -1, $attackID = '-') {
    return $this->baseCard->PowerModifier(1);
  }
}
 
class voltic_vanguard extends Card {
  function __construct($controller) {
    $this->cardID = "voltic_vanguard";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID."-2", $this->controller);
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CS_NumInstantPlayed;
    return GetClassState($this->controller, $CS_NumInstantPlayed) == 0;
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    global $currentTurnEffects;
    $effects = explode("-", $currentTurnEffects[$index]);
    $prevented = min($damage, $effects[1]);
    $effects[1] -= $prevented;
    if ($effects[1] <= 0) $remove = true;
    return $prevented;
  }
}

class solray_plating extends Card {
  function __construct($controller) {
    $this->cardID = "solray_plating";
    $this->controller = $controller;
  }

  function DefaultActiveState() {
    return 0;
  }  
}

class blessing_of_themis_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "blessing_of_themis_yellow";
    $this->controller = $controller;
  }

  function EntersArenaAbility() {
    $Auras = new Auras($this->controller);
    $AuraCard = $Auras->Card($Auras->NumAuras() - 1, true);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "-", $AuraCard->UniqueID());
  }

  function StartTurnAbility($index) {
    $AuraCard = new AuraCard($index, $this->controller);
    AddLayer("TRIGGER", $this->controller, $this->cardID, "-", "STARTTURN", $AuraCard->UniqueID());
  }

  function ProcessTrigger($uniqueID, $target = '-', $additionalCosts = '-', $from = '-') {
    switch ($additionalCosts) {
      case "FLIP":
        $Banish = new Banish($target);
        $BanishCard = $Banish->FindCardUID($uniqueID);
        if ($BanishCard != "")
          $BanishCard->Modify("DOWN");
        break;
      case "STARTTURN":
        $Auras = new Auras($this->controller);
        $AuraCard = $Auras->FindCardUID($uniqueID);
        $AuraCard->Remove();
        AddSoul($this->cardID, $this->controller, "AURAS", false);
        break;
      default:
        $Auras = new Auras($this->controller);
        $AuraCard = $Auras->FindCardUID($uniqueID);
        AddDecisionQueue("INPUTCARDNAME", $this->controller, "-");
        AddDecisionQueue("SETDQVAR", $this->controller, "0");
        AddDecisionQueue("WRITELOG", $this->controller, "📣<b>{0}</b> was chosen");
        AddDecisionQueue("BLESSINGOFTHEMIS", $this->controller, $AuraCard->Index().",{0}");
        break;
    }
  }
}

class unflinching_foothold extends Card {
  function __construct($controller) {
    $this->cardID = "unflinching_foothold";
    $this->controller = $controller;
  }

  function AbilityType($index = -1, $from = '-') {
    return "I";
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function IsPlayRestricted(&$restriction, $from = '', $index = -1, $resolutionCheck = false) {
    global $CombatChain;
    if ($CombatChain->HasCurrentLink()) return false;//If there's an attack, there's a valid target
    return !IsLayerStep();
  }

  function GoesOnCombatChain($phase, $from) {
    return $phase == "B";
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    AddCurrentTurnEffect($this->cardID, $this->controller == 1 ? 2 : 1);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }
}

class myrkhellir_helm extends Card {
  function __construct($controller) {
    $this->cardID = "myrkhellir_helm";
    $this->controller = $controller;
  }

  function AbilityCost() {
    return 2;
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

  function GoesOnCombatChain($phase, $from) {
    return $phase == "B";
  }

  function EquipPayAdditionalCosts($cardIndex = '-') {
    DestroyCharacter($this->controller, $cardIndex);
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return CountItemByName("Gold", $this->controller) > 0 ? 1 : 0;
  }
}

class conquer_the_icy_terrain extends BaseCard {
  function HitTrigger($check) {
    if (IsHeroAttackTarget()) {
      if (!$check) AddLayer("TRIGGER", $this->controller, $this->cardID, $this->cardID, "ONHITEFFECT");
      return true;
    }
    return false;
  }

  function HitEffect() {
    global $defPlayer;
    AddDecisionQueue("SETDQCONTEXT", $defPlayer, "Choose if you want to pay 2 to avoid a frozen card being destroyed");
    AddDecisionQueue("YESNO", $defPlayer, "if_you_want_to_pay_2_to_avoid_discarding", 1);
    AddDecisionQueue("NOPASS", $defPlayer, "-", 1);
    AddDecisionQueue("PASSPARAMETER", $defPlayer, 2, 1);
    AddDecisionQueue("PAYRESOURCES", $defPlayer, "-", 1);
    AddDecisionQueue("ELSE", $defPlayer, "-");
    //don't do auras for now
    $choices = SearchMultizone($this->controller, "THEIRCHAR:frozenOnly=1&THEIRALLY:frozenOnly=1&THEIRITEMS:frozenOnly=1&THEIRARS:frozenOnly=1");
    function NotHero($mzind) {
      return $mzind != "THEIRCHAR-0";
    }
    $choices = implode(",", array_filter(explode(",", $choices), "NotHero"));
    AddDecisionQueue("PASSPARAMETER", $this->controller, $choices, 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "<-", 1);
  }
}

class conquer_the_icy_terrain_red extends Card {
  function __construct($controller) {
    $this->cardID = "conquer_the_icy_terrain_red";
    $this->controller = $controller;
    $this->baseCard = new conquer_the_icy_terrain($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->HitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class conquer_the_icy_terrain_yellow extends Card {
  function __construct($controller) {
    $this->cardID = "conquer_the_icy_terrain_yellow";
    $this->controller = $controller;
    $this->baseCard = new conquer_the_icy_terrain($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->HitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class conquer_the_icy_terrain_blue extends Card {
  function __construct($controller) {
    $this->cardID = "conquer_the_icy_terrain_blue";
    $this->controller = $controller;
    $this->baseCard = new conquer_the_icy_terrain($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    return "";
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return $this->baseCard->HitTrigger($check);
  }

  function HitEffect($cardID, $from = '-', $uniqueID = -1, $target = '-') {
    $this->baseCard->HitEffect();
  }
}

class elixir extends BaseCard {
  function PlayAbility($auraName) {
    AddCurrentTurnEffect($this->cardID, $this->controller);
    AddDecisionQueue("MULTIZONEINDICES", $this->controller, "MYAURAS:isSameName=".$auraName, 1);
    AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
    AddDecisionQueue("MZDESTROY", $this->controller, "-", 1);
    AddDecisionQueue("GAINLIFE", $this->controller, "1", 1);
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return true;
  }

  function EffectPowerModifier($param, $attached = false) {
    return 3;
  }
}

class clearwater_elixir_red extends Card {
  function __construct($controller) {
    $this->cardID = "clearwater_elixir_red";
    $this->controller = $controller;
    $this->baseCard = new elixir($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility("bloodrot_pox");  
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class restvine_elixir_red extends Card {
  function __construct($controller) {
    $this->cardID = "restvine_elixir_red";
    $this->controller = $controller;
    $this->baseCard = new elixir($this->cardID, $this->controller);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility("inertia");  
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class sapwood_elixir_red extends Card {
  function __construct($controller) {
    $this->cardID = "sapwood_elixir_red";
    $this->controller = $controller;
    $this->baseCard = new elixir($this->cardID, $this->controller);
  }

    function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility("frailty");  
  }

  function CombatEffectActive($parameter = '-', $defendingCard = '', $flicked = false) {
    return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
  }

  function EffectPowerModifier($param, $attached = false) {
    return $this->baseCard->EffectPowerModifier($param, $attached);
  }
}

class shamanic_shinbones extends Card {
  function __construct($controller) {
    $this->cardID = "shamanic_shinbones";
    $this->controller = $controller;
  }

  function ArcaneBarrier() {
    return 1;
  }
}

class put_on_ice extends BaseCard {
  function PlayAbility($from, $target) {
    if ($from == "ARS") Draw($this->controller);
    //this card's targeting is a mess right now that I don't want to deal with anymore, this works and I'm happy
    $targetArr = explode("~", $target);
    $targerArr = explode(",", $targetArr[count($targetArr) - 1]);
    for ($i = 1; $i < count($targetArr); ++$i) {
      MZFreeze($targerArr[$i]);
    }
  }

  function SetTargets($N) {
    AddDecisionQueue("PASSPARAMETER", $this->controller, "~");
    AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
    for($i=0; $i<$N; ++$i) {
      AddDecisionQueue("ALLYINDICES", $this->controller, "{0}", 1);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose " . $N - 1 . " allies(s) to freeze", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
      AddDecisionQueue("PREPENDLASTRESULT", $this->controller, "{0},", 1);
      AddDecisionQueue("SETDQVAR", $this->controller, "0", 1);
      AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
    }
  }
}

class put_on_ice_red extends Card {
  function __construct($controller) {
    $this->cardID = "put_on_ice_red";
    $this->controller = $controller;
    $this->baseCard = new put_on_ice($this->cardID, $this->controller);
  }

  function PayAdditionalCosts($from, $index = '-') {
    $this->baseCard->SetTargets(3);
  }

  function PlayAbility($from, $resourcesPaid, $target = '-', $additionalCosts = '-', $uniqueID = '-1', $layerIndex = -1) {
    $this->baseCard->PlayAbility($from, $target);
  }
}