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

  function CardCost($from="-") {
    return GeneratedCardCost($this->cardID); 
  }

  function GoesOnCombatChain($phase, $from) {
    global $layers;
    if (canBeAddedToChainDuringDR($this->cardID) && $phase == "D") return true;
    if ($phase != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetResolvedAbilityType($this->cardID, $from);
    else if ($phase == "M" && $this->cardID == "guardian_of_the_shadowrealm_red" && $from == "BANISH") $cardType = GetResolvedAbilityType($this->cardID, $from);
    else $cardType = CardType($this->cardID);
    if ($phase == "B" && count($layers) == 0) return true; //Anything you play during these combat phases would go on the chain
    if (DelimStringContains($cardType, "I")) return false; //Instants as yet never go on the combat chain
    if (($phase == "A" || $phase == "D") && DelimStringContains($cardType, "A")) return false; //Non-attacks played as instants never go on combat chain
    if ($cardType == "AR") return true; // Technically wrong, AR goes to the graveyard instead of remaining on the active chain link. CR 2.4.0 - 8.1.2b
    if ($cardType == "DR") return true;
    if (($phase == "M" || $phase == "ATTACKWITHIT") && $cardType == "AA") return true; //If it's an attack action, it goes on the chain
    return false;
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

  function OnBlockResolveEffects($blockedFromHand, $i) {
    return;
  }

  function ProcessAbility($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    return "";
  }

  function CanPlayAsInstant($index=-1, $from = "") {
    return false;
  }

  function AddPrePitchDecisionQueue($from, $index=-1) {
    return;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    return false;
  }

  function HitEffect($cardID, $from = "-", $uniqueID = -1, $target="-") {
    return;
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    return "GY";
  }

  function StartTurnAbility($index) {
    return;
  }

  function LeavesPlayAbility($index, $uniqueID, $location) {
    return;
  }

  function GetLayerTarget($from) {
    $targetType = PlayRequiresTarget($this->cardID, $from);
    if ($targetType != -1) {
      AddDecisionQueue("PASSPARAMETER", $this->controller, $this->cardID);
      AddDecisionQueue("SETDQVAR", $this->controller, "0");
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a target for <0>");
      AddDecisionQueue("FINDINDICES", $this->controller, "ARCANETARGET," . $targetType);
      AddDecisionQueue("SETDQCONTEXT", $this->controller, "Choose a target for <0>");
      AddDecisionQueue("CHOOSEMULTIZONE", $this->controller, "<-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $this->controller, "-", 1);
      AddDecisionQueue("SETLAYERTARGET", $this->controller, $this->cardID, 1);
    }
  }

  function AttackGetsBlockedEffect($cardID) {
    return;
  }

  function WonClashAbility($winnerID) {
    return;
  }

  function AddGraveyardEffect($from, $effectController) {
    return;
  }
  
  function HasSuspense() {
    return false;
  }
}

?>
