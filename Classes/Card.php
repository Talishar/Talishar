<?php

// this is an abstract class that stores functions used by multiple cards
// eg. "unexpected_backhand" stores functions used by the red/yellow/blue versions of the card
class BaseCard {
  public $cardID;
  public $controller;
  public $archetype;

  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
  }
}

// This is an interface with functions that each zone's card class must implement
class Card {
  // Properties
  public $cardID;
  public $controller;
  public $baseCard;
  public $addedAbilities = [];

  // Constructor
  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
    $this->baseCard = new BaseCard($cardID, $controller);
  }

  function AddAbilities($cardIDs) {
    $addedAbilityIDs = explode(",", $cardIDs);
    foreach ($addedAbilityIDs as $ability) {
      $card = GetClass($ability, $this->controller);
      if ($card != "-") $this->addedAbilities[] = $card;
    }
  }

  function IsType($types) {
    foreach (explode(",", $types) as $type) {
      if (TypeContains($this->cardID, $type, $this->controller)) return true;
    }
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayAbility"))
      return $this->baseCard->PlayAbility($from, $resourcesPaid, $target, $additionalCosts, $uniqueID, $layerIndex);
    if (CardType($this->cardID) == "AA") return "";
    if (SubtypeContains($this->cardID, "Item")) return "";
    if (SubtypeContains($this->cardID, "Aura")) return "";
    if (SubtypeContains($this->cardID, "Ally")) return "";
    if (TypeContains($this->cardID, "W")) return "";
    else return "";
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ProcessTrigger"))
      return $this->baseCard->ProcessTrigger($uniqueID, $target, $additionalCosts, $from);
    return "";
  } 

  function CardType($from="", $additionalCosts="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardType"))
      return $this->baseCard->CardType($from, $additionalCosts);
    return GeneratedCardType($this->cardID);
  }

  function PowerValue($from="CC", $index=-1, $base=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PowerValue"))
      return $this->baseCard->PowerValue($from, $index, $base);
    return GeneratedPowerValue($this->cardID);
  }

  function CardCost($from="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardCost"))
      return $this->baseCard->CardCost($from);
    return GeneratedCardCost($this->cardID); 
  }

  function GoesOnCombatChain($phase, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GoesOnCombatChain"))
      return $this->baseCard->GoesOnCombatChain($phase, $from);
    return false;
  }

  function PayAdditionalCosts($from, $index="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PayAdditionalCosts"))
      return $this->baseCard->PayAdditionalCosts($from, $index);
    return "";
  }

  function PayAbilityAdditionalCosts($index, $from="-", $zoneIndex=-1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PayAbilityAdditionalCosts"))
      return $this->baseCard->PayAbilityAdditionalCosts($index, $from, $zoneIndex);
    return "";
  }

  function EquipPayAdditionalCosts($cardIndex="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EquipPayAdditionalCosts"))
      return $this->baseCard->EquipPayAdditionalCosts($cardIndex);
    $CharCard = new CharacterCard($cardIndex, $this->controller);
    $CharCard->AddUse(-1);
    if ($CharCard->NumUses() == 0) $CharCard->SetUsed(); //By default, if it's used, set it to used
    return;
  }

  function IsPlayRestricted(&$restriction, $from="", $index=-1, $resolutionCheck=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsPlayRestricted"))
      return $this->baseCard->IsPlayRestricted($restriction, $from, $index, $resolutionCheck);
    return false;
  }

  function AbilityPlayableFromCombatChain($index="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilityPlayableFromCombatChain"))
      return $this->baseCard->AbilityPlayableFromCombatChain($index);
    return false;
  }

  function AbilityType($index = -1, $from = "-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilityType"))
      return $this->baseCard->AbilityType($index, $from);
    return "";
  }

  function AbilityCost() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilityCost"))
      return $this->baseCard->AbilityCost();
    return 0;
  }

  function EffectPowerModifier($param, $attached=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectPowerModifier"))
      return $this->baseCard->EffectPowerModifier($param, $attached);
    return 0;
  }

  function CombatEffectActive($parameter = "-", $defendingCard = "", $flicked = false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CombatEffectActive"))
      return $this->baseCard->CombatEffectActive($parameter, $defendingCard, $flicked);
    return false;
  }

  function NumUses() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "NumUses"))
      return $this->baseCard->NumUses();
    return 1;
  }

  function GetAbilityTypes($index=-1, $from="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GetAbilityTypes"))
      return $this->baseCard->GetAbilityTypes($index, $from);
    return "";
  }

  function GetAbilityNames($index=-1, $from="-", $foundNullTime=false, $layerCount=0, $facing="-", $allNames=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GetAbilityNames"))
      return $this->baseCard->GetAbilityNames($index, $from, $foundNullTime, $layerCount, $facing, $allNames);
    return "";
  }

  function ResolutionStepEffectTriggers($parameter, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ResolutionStepEffectTriggers"))
      return $this->baseCard->ResolutionStepEffectTriggers($parameter, $index);
    return false; //return whether to remove the effect
  }

  function AddEffectHitTrigger($source="-", $fromCombat=true, $target="-", $parameter="-", $check=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddEffectHitTrigger"))
      return $this->baseCard->AddEffectHitTrigger($source, $fromCombat, $target, $parameter, $check);
    return false;
  }

  function EffectHitEffect($from, $source = "-", $effectSource  = "-", $param = "-", $mode="-", $target="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectHitEffect"))
      return $this->baseCard->EffectHitEffect($from, $source, $effectSource, $param, $mode, $target);
    return;
  }

  function ProcessAttackTrigger($target, $uniqueID) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ProcessAttackTrigger"))
      return $this->baseCard->ProcessAttackTrigger($target, $uniqueID);
    return;
  }

  function PowerModifier($from = "", $resourcesPaid = 0, $repriseActive = -1, $attackID = "-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PowerModifier"))
      return $this->baseCard->PowerModifier($from, $resourcesPaid, $repriseActive, $attackID);
    return 0;
  }

  function SelfCostModifier($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SelfCostModifier"))
      return $this->baseCard->SelfCostModifier($from);
    return 0;
  }

  function AbilityHasGoAgain($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilityHasGoAgain"))
      return $this->baseCard->AbilityHasGoAgain($from);
    return false;
  }

  function IsGold() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsGold"))
      return $this->baseCard->IsGold();
    return false;
  }

  function OnDefenseReactionResolveEffects($from, $blockedFromHand) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "OnDefenseReactionResolveEffects"))
      return $this->baseCard->OnDefenseReactionResolveEffects($from, $blockedFromHand);
    return;
  }

  function ContractType($chosenName = "") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ContractType"))
      return $this->baseCard->ContractType($chosenName);
    return "";
  }

  function ContractCompleted() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ContractCompleted"))
      return $this->baseCard->ContractCompleted();
    return;
  }

  function HasTemper() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasTemper"))
      return $this->baseCard->HasTemper();
    return GeneratedHasTemper($this->cardID) == "true";
  }

  function HasGuardwell() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasGuardwell"))
      return $this->baseCard->HasGuardwell();
    return GeneratedHasGuardwell($this->cardID);
  }

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "OnBlockResolveEffects"))
      return $this->baseCard->OnBlockResolveEffects($blockedFromHand, $i, $start);
    return;
  }

  function ProcessAbility($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ProcessAbility"))
      return $this->baseCard->ProcessAbility($uniqueID, $target, $additionalCosts, $from);
    return "";
  }

  function CanPlayAsInstant($index=-1, $from = "") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CanPlayAsInstant"))
      return $this->baseCard->CanPlayAsInstant($index, $from);
    return false;
  }

  function CanActivateAsInstant($index=-1, $from = "") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CanActivateAsInstant"))
      return $this->baseCard->CanActivateAsInstant($index, $from);
    return false;
  }

  function AddPrePitchDecisionQueue($from, $index=-1, $facing="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddPrePitchDecisionQueue"))
      return $this->baseCard->AddPrePitchDecisionQueue($from, $index, $facing);
    return;
  }

  function AddPostTargetDecisionQueue($from, $index=-1, $facing="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddPostTargetDecisionQueue"))
      return $this->baseCard->AddPostTargetDecisionQueue($from, $index, $facing);
    return;
  }

  function AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddOnHitTrigger"))
      return $this->baseCard->AddOnHitTrigger($uniqueID, $source, $targetPlayer, $check);
    return false;
  }

  function HitEffect($cardID, $from = "-", $uniqueID = -1, $target="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HitEffect"))
      return $this->baseCard->HitEffect($cardID, $from, $uniqueID, $target);
    return;
  }

  function GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GoesWhereAfterResolving"))
      return $this->baseCard->GoesWhereAfterResolving($from, $playedFrom, $stillOnCombatChain, $additionalCosts);
    return "GY";
  }

  function StartTurnAbility($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "StartTurnAbility"))
      return $this->baseCard->StartTurnAbility($index);
    return;
  }

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "LeavesPlayAbility"))
      return $this->baseCard->LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID);
    return;
  }

  function GetLayerTarget($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GetLayerTarget"))
      return $this->baseCard->GetLayerTarget($from);
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

  function AttackGetsBlockedEffect($start) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AttackGetsBlockedEffect"))
      return $this->baseCard->AttackGetsBlockedEffect($start);
    return;
  }

  // Ideally, we would pass in a "ClashResult" object with information if clashes keep getting more complex to keep the signature simple.
  function WonClashAbility($winnerID, $switched) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WonClashAbility"))
      return $this->baseCard->WonClashAbility($winnerID, $switched);
    return;
  }

  // Triggers when a clash is won with this card on top of the deck.
  function WonClashWithAbility($winnerID) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WonClashWithAbility"))
      return $this->baseCard->WonClashWithAbility($winnerID);
    return;
  }

  function AddGraveyardEffect($from, $effectController, $cardController) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddGraveyardEffect"))
      return $this->baseCard->AddGraveyardEffect($from, $effectController, $cardController);
    return false;
  }

  function HasSuspense() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasSuspense"))
      return $this->baseCard->HasSuspense();
    return GeneratedHasSuspense($this->cardID);
  }

  function HasAmbush() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasAmbush"))
      return $this->baseCard->HasAmbush();
    return GeneratedHasAmbush($this->cardID);
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardBlockModifier"))
      return $this->baseCard->CardBlockModifier($from, $resourcesPaid, $index);
    return 0;
  }

  function DoesAttackHaveGoAgain() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DoesAttackHaveGoAgain"))
      return $this->baseCard->DoesAttackHaveGoAgain();
    return false;
  }

  function EffectSetBasePower($basePower) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectSetBasePower"))
      return $this->baseCard->EffectSetBasePower($basePower);
    return $basePower;
  }

  function MultiplyBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "MultiplyBasePower"))
      return $this->baseCard->MultiplyBasePower();
    return 1;
  }

  function EffectMultiplyBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectMultiplyBasePower"))
      return $this->baseCard->EffectMultiplyBasePower();
    return 1;
  }

  function CharMultiplyBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CharMultiplyBasePower"))
      return $this->baseCard->CharMultiplyBasePower();
    return 1;
  }

  function DivideBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DivideBasePower"))
      return $this->baseCard->DivideBasePower();
    return 1;
  }

  function EffectDivideBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectDivideBasePower"))
      return $this->baseCard->EffectDivideBasePower();
    return 1;
  }

  function CharDivideBasePower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CharDivideBasePower"))
      return $this->baseCard->CharDivideBasePower();
    return 1;
  }

  function BeginEndTurnAbilities($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "BeginEndTurnAbilities"))
      return $this->baseCard->BeginEndTurnAbilities($index);
    return;
  }

  function HasCombo() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasCombo"))
      return $this->baseCard->HasCombo();
    return GeneratedHasCombo($this->cardID);
  }

  function ComboActive($lastAttackName) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ComboActive"))
      return $this->baseCard->ComboActive($lastAttackName);
    return false;
  }

  function HasTower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasTower"))
      return $this->baseCard->HasTower();
    return GeneratedHasTower($this->cardID);
  }

  function AddTowerHitTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddTowerHitTrigger"))
      return $this->baseCard->AddTowerHitTrigger();
    return;
  }

  function ProcessTowerEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ProcessTowerEffect"))
      return $this->baseCard->ProcessTowerEffect();
    return;
  }

  function HasCrush() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasCrush"))
      return $this->baseCard->HasCrush();
    return GeneratedHasCrush($this->cardID);
  }

  function AddCrushEffectTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddCrushEffectTrigger"))
      return $this->baseCard->AddCrushEffectTrigger();
    return;
  }

  function ProcessCrushEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ProcessCrushEffect"))
      return $this->baseCard->ProcessCrushEffect();
    return;
  }

  function CurrentEffectGrantsGoAgain($param) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectGrantsGoAgain"))
      return $this->baseCard->CurrentEffectGrantsGoAgain($param);
    return false;
  }

  function PitchAbility($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PitchAbility"))
      return $this->baseCard->PitchAbility($from);
    return;
  }

  function CombatChainCloseAbility($chainLink) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CombatChainCloseAbility"))
      return $this->baseCard->CombatChainCloseAbility($chainLink);
    return;
  }

  function WeaponPowerModifier($basePower) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WeaponPowerModifier"))
      return $this->baseCard->WeaponPowerModifier($basePower);
    // this function is distinct for PowerModifier, use if for weapons that buff themselves
    // (like anothos) rather than weapons that buff their attacks (like starfall)
    return $basePower;
  }

  function EntersArenaAbility($index=-1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EntersArenaAbility"))
      return $this->baseCard->EntersArenaAbility($index);
    return;
  }

  function PlayableFromGraveyard($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayableFromGraveyard"))
      return $this->baseCard->PlayableFromGraveyard($index);
    return false;
  }

  function AbilityPlayableFromGraveyard($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilityPlayableFromGraveyard"))
      return $this->baseCard->AbilityPlayableFromGraveyard($index);
    return false;
  }

  function IsGrantedBuff() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsGrantedBuff"))
      return $this->baseCard->IsGrantedBuff();
    return false;
  }

  function AddCardEffectHitTrigger($sourceID, $targetPlayer, $mode) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AddCardEffectHitTrigger"))
      return $this->baseCard->AddCardEffectHitTrigger($sourceID, $targetPlayer, $mode);
    return;
  }

  function IsCombatEffectPersistent($mode) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsCombatEffectPersistent"))
      return $this->baseCard->IsCombatEffectPersistent($mode);
    return false;
  }

  function AuraPowerModifiers($index, &$powerModifiers, $auraIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AuraPowerModifiers"))
      return $this->baseCard->AuraPowerModifiers($index, $powerModifiers, $auraIndex);
    return 0;
  }

  function PermDamagePreventionAmount($index, $type, $damage, $active, &$cancelRemove, $check) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermDamagePreventionAmount"))
      return $this->baseCard->PermDamagePreventionAmount($index, $type, $damage, $active, $cancelRemove, $check);
    return 0;
  }

  function PermCostModifier($cardID, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermCostModifier"))
      return $this->baseCard->PermCostModifier($cardID, $from);
    return 0;
  }

  function DefaultActiveState() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DefaultActiveState"))
      return $this->baseCard->DefaultActiveState();
    return 2;
  }

  function HasWateryGrave() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasWateryGrave"))
      return $this->baseCard->HasWateryGrave();
    return GeneratedHasWateryGrave($this->cardID);
  }

  function BeginningActionPhaseAbility($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "BeginningActionPhaseAbility"))
      return $this->baseCard->BeginningActionPhaseAbility($index);
    return;
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $preventable, $amount=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectDamagePrevention"))
      return $this->baseCard->CurrentEffectDamagePrevention($type, $damage, $source, $index, $remove, $preventable, $amount);
    return 0;
  }

  //"Special" functions override the generated card dictionary
  function SpecialType() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialType"))
      return $this->baseCard->SpecialType();
    return "-";
  }

  function SpecialSubType() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialSubType"))
      return $this->baseCard->SpecialSubType();
    return GeneratedCardSubtype($this->cardID);
  }

  function SpecialPower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialPower"))
      return $this->baseCard->SpecialPower();
    return -1;
  }

  function SpecialBlock() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialBlock"))
      return $this->baseCard->SpecialBlock();
    return -1;
  }

  function SpecialPitch() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialPitch"))
      return $this->baseCard->SpecialPitch();
    return -1;
  }

  function SpecialName() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialName"))
      return $this->baseCard->SpecialName();
    return "-";
  }

  function SpecialClass() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialClass"))
      return $this->baseCard->SpecialClass();
    return "-";
  }

  function SpecialTalent() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialTalent"))
      return $this->baseCard->SpecialTalent();
    return "-";
  }
  
  function SpecialCost() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialCost"))
      return $this->baseCard->SpecialCost();
    return -1;
  }

  function HasBeatChest() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasBeatChest"))
      return $this->baseCard->HasBeatChest();
    return GeneratedHasBeatChest($this->cardID);
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove, $index, $playIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectCostModifier"))
      return $this->baseCard->CurrentEffectCostModifier($cardID, $from, $remove, $index, $playIndex);
    return 0;
  }

  function WhenBeatChest($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WhenBeatChest"))
      return $this->baseCard->WhenBeatChest($index);
    return;
  }

  function CardPlayTrigger($cardID, $from, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardPlayTrigger"))
      return $this->baseCard->CardPlayTrigger($cardID, $from, $index);
    return;
  }

  function HasStealth() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasStealth"))
      return $this->baseCard->HasStealth();
    return GeneratedHasStealth($this->cardID);
  }

  function CurrentEffectEndTurnAbilities($i, &$remove) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectEndTurnAbilities"))
      return $this->baseCard->CurrentEffectEndTurnAbilities($i, $remove);
    return;
  }

  function CheerTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CheerTrigger"))
      return $this->baseCard->CheerTrigger();
    return;
  }

  function BooTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "BooTrigger"))
      return $this->baseCard->BooTrigger();
    return;
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayableFromBanish"))
      return $this->baseCard->PlayableFromBanish($mod, $nonLimitedOnly);
    return false;
  }

  function ArcaneBarrier($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ArcaneBarrier"))
      return $this->baseCard->ArcaneBarrier($index);
    return GeneratedArcaneBarrierAmount($this->cardID);
  }

  function PlayCardAbility($cardID, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayCardAbility"))
      return $this->baseCard->PlayCardAbility($cardID, $from);
    return;
  }

  function PlayCardEffectAbility($cardID, $from, &$remove, $index=-1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayCardEffectAbility"))
      return $this->baseCard->PlayCardEffectAbility($cardID, $from, $remove, $index);
    return;
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentPlayAbility"))
      return $this->baseCard->PermanentPlayAbility($cardID, $from, $i);
    return false;
  }

  function SpellVoidAmount($index=-1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpellVoidAmount"))
      return $this->baseCard->SpellVoidAmount($index);
    return GeneratedSpellVoidAmount($this->cardID);
  }

  function UnityEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "UnityEffect"))
      return $this->baseCard->UnityEffect();
    return;
  }

  function RemoveEffectFromCombatChain($effectIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "RemoveEffectFromCombatChain"))
      return $this->baseCard->RemoveEffectFromCombatChain($effectIndex);
    return false;
  }

  function DynamicCost() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DynamicCost"))
      return $this->baseCard->DynamicCost();
    return "";
  }

  function ArcaneTargeting($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ArcaneTargeting"))
      return $this->baseCard->ArcaneTargeting($from);
    return -1;
  }

  function ArcaneDamage() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ArcaneDamage"))
      return $this->baseCard->ArcaneDamage();
    return -1;
  }

  function ActionsThatDoArcaneDamage() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ActionsThatDoArcaneDamage"))
      return $this->baseCard->ActionsThatDoArcaneDamage();
    return false;
  }

  function ArcaneHitEffect($source, $target, $damage) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ArcaneHitEffect"))
      return $this->baseCard->ArcaneHitEffect($source, $target, $damage);
    return;
  }

  function CardCaresAboutPitch() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardCaresAboutPitch"))
      return $this->baseCard->CardCaresAboutPitch();
    return false;
  }

  function CurrentTurnEffectUses() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentTurnEffectUses"))
      return $this->baseCard->CurrentTurnEffectUses();
    return 1;
  }

  function DestroyEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DestroyEffect"))
      return $this->baseCard->DestroyEffect();
    return;
  }

  function ResolutionStepAttackTriggers() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ResolutionStepAttackTriggers"))
      return $this->baseCard->ResolutionStepAttackTriggers();
    return;
  }

  function GetHitTrigger($source) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GetHitTrigger"))
      return $this->baseCard->GetHitTrigger($source);
    return;
  }

  function BlockCardDestroyed() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "BlockCardDestroyed"))
      return $this->baseCard->BlockCardDestroyed();
    return;
  }

  function DoesEffectGrantDominate($effectIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DoesEffectGrantDominate"))
      return $this->baseCard->DoesEffectGrantDominate($effectIndex);
    return false;
  }

  function ResolutionStepBlockTrigger($i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ResolutionStepBlockTrigger"))
      return $this->baseCard->ResolutionStepBlockTrigger($i);
    return;
  }

  function OppStartTurnAbility($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "OppStartTurnAbility"))
      return $this->baseCard->OppStartTurnAbility($index);
    return;
  }

  function StaticPowerModifier($index, &$powerModifiers) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "StaticPowerModifier"))
      return $this->baseCard->StaticPowerModifier($index, $powerModifiers);
    return 0;
  }

  function HasGoAgain($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasGoAgain"))
      return $this->baseCard->HasGoAgain($from);
    return GeneratedGoAgain($this->cardID);
  }

  function EffectBlockModifier($index, $from, $effectInd) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectBlockModifier"))
      return $this->baseCard->EffectBlockModifier($index, $from, $effectInd);
    return 0;
  }

  function EffectAttackYouControlModifiers($cardID) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectAttackYouControlModifiers"))
      return $this->baseCard->EffectAttackYouControlModifiers($cardID);
    return 0;
  }

  function EffectDefenderPowerModifier($cardID)  {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectDefenderPowerModifier"))
      return $this->baseCard->EffectDefenderPowerModifier($cardID);
    return 0;
  }

  function StaticDefenderPowerModifier($cardID)  {
    if (isset($this->baseCard) && method_exists($this->baseCard, "StaticDefenderPowerModifier"))
      return $this->baseCard->StaticDefenderPowerModifier($cardID);
    return 0;
  }

  function DiscardStartTurnTrigger($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DiscardStartTurnTrigger"))
      return $this->baseCard->DiscardStartTurnTrigger($index);
    return;
  }

  function CurrentEffectBeginningActionPhaseAbility($i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectBeginningActionPhaseAbility"))
      return $this->baseCard->CurrentEffectBeginningActionPhaseAbility($i);
    return;
  }

  function HasHightide() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasHightide"))
      return $this->baseCard->HasHightide();
    return GeneratedHasHightide($this->cardID);
  }

  function WardAmount($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WardAmount"))
      return $this->baseCard->WardAmount($index);
    return GeneratedWardAmount($this->cardID);
  }

  function HasWard() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasWard"))
      return $this->baseCard->HasWard();
    return GeneratedHasWard($this->cardID);
  }

  function HasMirage() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasMirage"))
      return $this->baseCard->HasMirage();
    return GeneratedHasMirage($this->cardID);
  }

  function CardCareAboutChiPitch() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardCareAboutChiPitch"))
      return $this->baseCard->CardCareAboutChiPitch();
    return false;
  }

  function StaticCostModifier($cardID, $from, $cost) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "StaticCostModifier"))
      return $this->baseCard->StaticCostModifier($cardID, $from, $cost);
    return 0;
  }

  function CurrentEffectBeginEndPhaseAbility($i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectBeginEndPhaseAbility"))
      return $this->baseCard->CurrentEffectBeginEndPhaseAbility($i);
    return ;
  }

  function SpecificLogic() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecificLogic"))
      return $this->baseCard->SpecificLogic();
    // handles the end of DQ stuff
    return;
  }

  function CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, &$remove) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectGrantsNAAGoAgain"))
      return $this->baseCard->CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, $remove);
    return false;
  }

  function HasFragment() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasFragment"))
      return $this->baseCard->HasFragment();
    foreach ($this->addedAbilities as $ability) 
      if ($ability->HasFragment()) return true;
    return GeneratedHasFragment($this->cardID);
  }

  function ArcaneModifier(&$remove, $player, $index, $amount=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ArcaneModifier"))
      return $this->baseCard->ArcaneModifier($remove, $player, $index, $amount);
    return 0;
  }

  function LeavesCombatChainAbility() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "LeavesCombatChainAbility"))
      return $this->baseCard->LeavesCombatChainAbility();
    return;
  }

  function OnAttackEffect($cardID, $i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "OnAttackEffect"))
      return $this->baseCard->OnAttackEffect($cardID, $i);
    return false;
  }

  function ActiveLinkPlayTrigger($cardID, $player, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ActiveLinkPlayTrigger"))
      return $this->baseCard->ActiveLinkPlayTrigger($cardID, $player, $from);
    foreach ($this->addedAbilities as $ability) 
      $ability->ActiveLinkPlayTrigger($cardID, $player, $from);
    return;
  }

  function HasPhantasm() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasPhantasm"))
      return $this->baseCard->HasPhantasm();
    return GeneratedHasPhantasm($this->cardID);
  }

  function HasDominate() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasDominate"))
      return $this->baseCard->HasDominate();
    return GeneratedHasDominate($this->cardID);
  }

  function AbilitiesToAdd() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AbilitiesToAdd"))
      return $this->baseCard->AbilitiesToAdd();
    return "";
  }

  function AssignEffectToCard($cardID, $effectIndex, $from) { //used to apply effects to the next card "played" not resolved
    if (isset($this->baseCard) && method_exists($this->baseCard, "AssignEffectToCard"))
      return $this->baseCard->AssignEffectToCard($cardID, $effectIndex, $from);
    return;
  }

  function PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked, $check) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentHitEffect"))
      return $this->baseCard->PermanentHitEffect($index, $damageSource, $targetPlayer, $flicked, $check);
    return false;
	}

  function CurrentEffectOnBlockEffect($chainInd, $from, $start=-1, $effectIndex=-1) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectOnBlockEffect"))
      return $this->baseCard->CurrentEffectOnBlockEffect($chainInd, $from, $start, $effectIndex);
    return false;
  }

  function DamageDealtAbilities($target, $damage, $type) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DamageDealtAbilities"))
      return $this->baseCard->DamageDealtAbilities($target, $damage, $type);
    return;
  }

  function WhileBlockPlayTrigger($index, $cardID, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WhileBlockPlayTrigger"))
      return $this->baseCard->WhileBlockPlayTrigger($index, $cardID, $from);
		return;
	}

  function WonWager($wonWager, $amount) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WonWager"))
      return $this->baseCard->WonWager($wonWager, $amount);
    return;
  }

  function FragmentTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "FragmentTrigger"))
      return $this->baseCard->FragmentTrigger();
    foreach ($this->addedAbilities as $ability)
      $ability->FragmentTrigger();
    return;
  }

  function CurrentTurnEffectPaid($cardID, $from, &$remove, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentTurnEffectPaid"))
      return $this->baseCard->CurrentTurnEffectPaid($cardID, $from, $remove, $index);
    return false;
  }

  function HasFusion() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasFusion"))
      return $this->baseCard->HasFusion();
    return "";
  }

  function IsWagerEffect($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsWagerEffect"))
      return $this->baseCard->IsWagerEffect($index);
    return false;
  }

  function CurrentEffectDamageEffect($target, $source, $type, $damage, &$remove, $attached = false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectDamageEffect"))
      return $this->baseCard->CurrentEffectDamageEffect($target, $source, $type, $damage, $remove, $attached);
    return;
  }

  function PermanentDamageTakenAbility($player, $damage, $source, $playerSource) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentDamageTakenAbility"))
      return $this->baseCard->PermanentDamageTakenAbility($player, $damage, $source, $playerSource);
    return;
  }

  function PermanentAddSoulAbility() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentAddSoulAbility"))
      return $this->baseCard->PermanentAddSoulAbility();
    return;
  }

  function EffectChainClosedEffect($i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectChainClosedEffect"))
      return $this->baseCard->EffectChainClosedEffect($i);
    return;
  }

  function EffectOnBlockModifier($effectIndex, $chainInd, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectOnBlockModifier"))
      return $this->baseCard->EffectOnBlockModifier($effectIndex, $chainInd, $from);
    return false;
  }

  function CombatChainTakeDamageAbility($link, $index, $damage, $type, $source, $preventable, $amount=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CombatChainTakeDamageAbility"))
      return $this->baseCard->CombatChainTakeDamageAbility($link, $index, $damage, $type, $source, $preventable, $amount);
    //$link == -1 means current link
    return 0;
  }

  function LayerTakeDamageAbility($index, $damage, $type, $source, $preventable, $amount=false) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "LayerTakeDamageAbility"))
      return $this->baseCard->LayerTakeDamageAbility($index, $damage, $type, $source, $preventable, $amount);
    return 0;
  }

  function CardPlayedAbility($cardID, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardPlayedAbility"))
      return $this->baseCard->CardPlayedAbility($cardID, $from);
    return;
  }

  function IsAttackLayer() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsAttackLayer"))
      return $this->baseCard->IsAttackLayer();
    return false;
  }

  function hasQuickstrike() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "hasQuickstrike"))
      return $this->baseCard->hasQuickstrike();
    return GeneratedHasQuickstrike($this->cardID);
  }

  function hasUsurp() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "hasUsurp"))
      return $this->baseCard->hasUsurp();
    return GeneratedHasUsurp($this->cardID);
  }

  function HasStarfall() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasStarfall"))
      return $this->baseCard->HasStarfall();
    return GeneratedHasStarfall($this->cardID);
  }
  
  function HasDecay() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasDecay"))
      return $this->baseCard->HasDecay();
    return GeneratedHasDecay($this->cardID);
  }

  function HasIncarnate() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasIncarnate"))
      return $this->baseCard->HasIncarnate();
    return GeneratedHasIncarnate($this->cardID);
  }

  function DisplayRemainingPrevention() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DisplayRemainingPrevention"))
      return $this->baseCard->DisplayRemainingPrevention();
    return false;
  }
  
  function CurrentEffectDamageBuffs($source, $type, $index, &$remove, $player) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CurrentEffectDamageBuffs"))
      return $this->baseCard->CurrentEffectDamageBuffs($source, $type, $index, $remove, $player);
    return 0;
  }

  function EquipAbilities() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EquipAbilities"))
      return $this->baseCard->EquipAbilities();
    return;
  }

  function UsurpedEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "UsurpedEffect"))
      return $this->baseCard->UsurpedEffect();
    return;
  }

  function IsRunechant() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsRunechant"))
      return $this->baseCard->IsRunechant();
    return false;
  }

  function PermanentPitchCardAbility($pitchIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentPitchCardAbility"))
      return $this->baseCard->PermanentPitchCardAbility($pitchIndex);
    return;
  }

  function PermanentPowerModifier(&$powerModifiers) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentPowerModifier"))
      return $this->baseCard->PermanentPowerModifier($powerModifiers);
    return 0;
  }

  function DoesEffectGrantOverpower() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DoesEffectGrantOverpower"))
      return $this->baseCard->DoesEffectGrantOverpower();
    return false;
  }

  function CombatChainBlockModifier($cardID, $from, $index, $sourceIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CombatChainBlockModifier"))
      return $this->baseCard->CombatChainBlockModifier($cardID, $from, $index, $sourceIndex);
    return 0;
  }

  function PlayTrigger($from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PlayTrigger"))
      return $this->baseCard->PlayTrigger($from);
    return;
  }

  function Backside() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "Backside"))
      return $this->baseCard->Backside();
    return "-";
  }

  function Frontside() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "Frontside"))
      return $this->baseCard->Frontside();
    return $this->cardID;
  }
  
  function SpecialHealth() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "SpecialHealth"))
      return $this->baseCard->SpecialHealth();
    return GeneratedCharacterHealth($this->cardID);
  }

  function HasBloodDebt() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasBloodDebt"))
      return $this->baseCard->HasBloodDebt();
    return GeneratedHasBloodDebt($this->cardID);
  }

  function PermanentEndPhaseAbility($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentEndPhaseAbility"))
      return $this->baseCard->PermanentEndPhaseAbility($index);
    return;
  }

  function DefenderPermanentEndPhaseAbility($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "DefenderPermanentEndPhaseAbility"))
      return $this->baseCard->DefenderPermanentEndPhaseAbility($index);
    return;
  }

  function GetBanishedEffect($from, $banisher, $banishedBy) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "GetBanishedEffect"))
      return $this->baseCard->GetBanishedEffect($from, $banisher, $banishedBy);
    return;
  }

  function AwakenAbility() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AwakenAbility"))
      return $this->baseCard->AwakenAbility();
    return;
  }

  function IsUnique() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsUnique"))
      return $this->baseCard->IsUnique();
    return false;
  }

  function WinWagerTrigger() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "WinWagerTrigger"))
      return $this->baseCard->WinWagerTrigger();
    return;
  }

  function EffectIntellectModifier($i, $remove) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectIntellectModifier"))
      return $this->baseCard->EffectIntellectModifier($i, $remove);
    return 0;
  }

  function OnAttackEffectEarly($cardID, $i) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "OnAttackEffectEarly"))
      return $this->baseCard->OnAttackEffectEarly($cardID, $i);
    // used for effects that by default should trigger before other effects
    // used for convenience in default ordering
    return false;
  }

  function EffectPlayCardRestricted($cardID, $from, $playIndex, $effectIndex) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "EffectPlayCardRestricted"))
      return $this->baseCard->EffectPlayCardRestricted($cardID, $from, $playIndex, $effectIndex);
    return "";
  }

  function HasPiercing() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "HasPiercing"))
      return $this->baseCard->HasPiercing();
    return GeneratedHasPiercing($this->cardID);
  }

  function AttackPlayCardAbility($cardID, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "AttackPlayCardAbility"))
      return $this->baseCard->AttackPlayCardAbility($cardID, $from);
    return;
  }

  function CardEffectArcaneBonus() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "CardEffectArcaneBonus"))
      return $this->baseCard->CardEffectArcaneBonus();
    return 0;
  }

  function IsLayerContinuousBuff() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "IsLayerContinuousBuff"))
      return $this->baseCard->IsLayerContinuousBuff();
    return false;
  }

  function LateEffect() {
    if (isset($this->baseCard) && method_exists($this->baseCard, "LateEffect"))
      return $this->baseCard->LateEffect();
    // if true, the card will trigger "late" after other cards by default
    return false;
  }

  function PermanentDamagePrevention($damage, $type, $source, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentDamagePrevention"))
      return $this->baseCard->PermanentDamagePrevention($damage, $type, $source, $index);
    return 0;
  }

  function PermanentAllyPlayAbility($allyIndex, $charIndex, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentAllyPlayAbility"))
      return $this->baseCard->PermanentAllyPlayAbility($allyIndex, $charIndex, $from);
    return;
  }

  function ModalAbility($lastResult, $index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "ModalAbility"))
      return $this->baseCard->ModalAbility($lastResult, $index);
    return;
  }

  function PastLinkPlayTrigger($cardID, $player, $from) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PastLinkPlayTrigger"))
      return $this->baseCard->PastLinkPlayTrigger($cardID, $player, $from);
    return;
  }

  function PermanentDestroyedTrigger($cardID) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentDestroyedTrigger"))
      return $this->baseCard->PermanentDestroyedTrigger($cardID);
    return;
  }

  function PermanentAddGraveyardAbility($discardIndex, $permIndex, $from, $uniqueID="-") {
    if (isset($this->baseCard) && method_exists($this->baseCard, "PermanentAddGraveyardAbility"))
      return $this->baseCard->PermanentAddGraveyardAbility($discardIndex, $permIndex, $from, $uniqueID);
    return;
  }

  function Binding($index) {
    if (isset($this->baseCard) && method_exists($this->baseCard, "Binding"))
      return $this->baseCard->Binding($index);
    return;
  }
}
