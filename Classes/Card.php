<?php
// This is an interface with functions that each zone's card class must implement


class Card {
  // Properties
  public $cardID;
  public $controller;

  // Constructor
  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }

  function IsType($types) {
    $typesArr = explode(",", $types);
    for($i=0; $i<count($typesArr); ++$i) {
      if(TypeContains($this->cardID, $typesArr[$i], $this->controller)) return true;
    }
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    return "NOT IMPLEMENTED";
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    return "NOT IMPLEMENTED";
  } 

  function CardType($from="", $additionalCosts="-") {
    return GeneratedCardType($this->cardID);
  }

  function PowerValue($from="CC", $index=-1, $base=false) {
    return GeneratedPowerValue($this->cardID);
  }

  function PayAdditionalCosts($from, $index="-") {
    return "";
  }

  function PayAbilityAdditionalCosts($index, $from="-", $zoneIndex=-1) {
    return;
  }

  function EquipPayAdditionalCosts($cardIndex="-") {
    return;
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    return false;
  }

  function AbilityPlayableFromCombatChain($index="-") {
    return false;
  }

  function AbilityType($index = -1, $from = "-") {
    return "";
  }

  function AbilityCost() {
    return 0;
  }

  function EffectPowerModifier($attached=false) {
    return 0;
  }

  function CombatEffectActive($parameter = "-", $defendingCard = "", $flicked = false) {
    return false;
  }

  function NumUses() {
    return 0;
  }

  function GetAbilityTypes($index=-1, $from="-") {
    return "";
  }

  function GetAbilityNames($index=-1, $from="-", $foundNullTime=false, $layerCount=0) {
    return "";
  }

  function ResolutionStepEffectTriggers($parameter) {
    return false; //return whether to remove the effect
  }

  function AddEffectHitTrigger($source="-", $fromCombat=true, $target="-", $parameter="-") {
    return false;
  }

  function EffectHitEffect($from, $source = "-", $effectSource  = "-", $param = "-") {
    return;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    return;
  }

  function PowerModifier($from = "", $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    return 0;
  }

  function SelfCostModifier($from) {
    return 0;
  }

  function AbilityHasGoAgain($from) {
    return false;
  }

  function IsGold() {
    return false;
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    return;
  }

  function ContractType($chosenName = "") {
    return "";
  }

  function ContractCompleted() {
    return;
  }

  function HasTemper() {
    return GeneratedHasTemper($this->cardID) == "true";
  }

  function OnBlockResolveEffects($blockedFromHand, $blockedWithAura, $blockedWithEarth, $blockedWithIce, $i) {
    return;
  }
}

?>
