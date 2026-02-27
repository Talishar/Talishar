<?php

// this is an abstract class that stores functions used by multiple cards
// eg. "unexpected_backhand" stores functions used by the red/yellow/blue versions of the card
class BaseCard {
  public $cardID;
  public $controller;

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

  // Constructor
  function __construct($cardID, $controller="-") {
    $this->cardID = $cardID;
    $this->controller = $controller;
    $this->baseCard = new BaseCard($cardID, $controller);
  }

  function IsType($types) {
    $typesArr = explode(",", $types);
    for($i=0; $i<count($typesArr); ++$i) {
      if(TypeContains($this->cardID, $typesArr[$i], $this->controller)) return true;
    }
    return false;
  }

  function PlayAbility($from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1) {
    if (CardType($this->cardID) == "AA") return "";
    if (SubtypeContains($this->cardID, "Item")) return "";
    if (SubtypeContains($this->cardID, "Aura")) return "";
    if (SubtypeContains($this->cardID, "Ally")) return "";
    if (TypeContains($this->cardID, "W")) return "";
    else return "";
  }

  function ProcessTrigger($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    return "";
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
    return false;
  }

  function PayAdditionalCosts($from, $index="-") {
    return "";
  }

  function PayAbilityAdditionalCosts($index, $from="-", $zoneIndex=-1) {
    return;
  }

  function EquipPayAdditionalCosts($cardIndex="-") {
    $CharCard = new CharacterCard($cardIndex, $this->controller);
    $CharCard->AddUse(-1);
    if ($CharCard->NumUses() == 0) $CharCard->SetUsed(); //By default, if it's used, set it to used
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

  function EffectPowerModifier($param, $attached=false) {
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

  function GetAbilityNames($index=-1, $from="-", $foundNullTime=false, $layerCount=0, $facing="-") {
    return "";
  }

  function ResolutionStepEffectTriggers($parameter) {
    return false; //return whether to remove the effect
  }

  function AddEffectHitTrigger($source="-", $fromCombat=true, $target="-", $parameter="-", $check=false) {
    return false;
  }

  function EffectHitEffect($from, $source = "-", $effectSource  = "-", $param = "-", $mode="-") {
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

  function OnBlockResolveEffects($blockedFromHand, $i, $start) {
    return;
  }

  function ProcessAbility($uniqueID, $target = "-", $additionalCosts = "-", $from = "-") {
    return "";
  }

  function CanPlayAsInstant($index=-1, $from = "") {
    return false;
  }

  function CanActivateAsInstant($index=-1, $from = "") {
    return false;
  }

  function AddPrePitchDecisionQueue($from, $index=-1, $facing="-") {
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

  function LeavesPlayAbility($index, $uniqueID, $location, $mainPhase, $destinationUID="-") {
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

  function AttackGetsBlockedEffect($start) {
    return;
  }

  // Ideally, we would pass in a "ClashResult" object with information if clashes keep getting more complex to keep the signature simple.
  function WonClashAbility($winnerID, $switched) {
    return;
  }

  // Triggers when a clash is won with this card on top of the deck.
  function WonClashWithAbility($winnerID) {
    return;
  }

  function AddGraveyardEffect($from, $effectController) {
    return;
  }

  function HasSuspense() {
    return GeneratedHasSuspense($this->cardID);
  }

  function HasAmbush() {
    return GeneratedHasAmbush($this->cardID);
  }

  function CardBlockModifier($from, $resourcesPaid, $index) {
    return 0;
  }

  function DoesAttackHaveGoAgain() {
    return false;
  }

  function EffectSetBasePower($basePower) {
    return $basePower;
  }

  function MultiplyBasePower() {
    return 1;
  }

  function EffectMultiplyBasePower() {
    return 1;
  }

  function CharMultiplyBasePower() {
    return 1;
  }

  function DivideBasePower() {
    return 1;
  }

  function EffectDivideBasePower() {
    return 1;
  }

  function CharDivideBasePower() {
    return 1;
  }

  function BeginEndTurnAbilities($index) {
    return;
  }

  function HasCombo() {
    return GeneratedHasCombo($this->cardID);
  }

  function ComboActive($lastAttackName) {
    return false;
  }

  function HasTower() {
    return GeneratedHasTower($this->cardID);
  }

  function AddTowerHitTrigger() {
    return;
  }

  function ProcessTowerEffect() {
    return;
  }

  function HasCrush() {
    return GeneratedHasCrush($this->cardID);
  }

  function AddCrushEffectTrigger() {
    return;
  }

  function ProcessCrushEffect() {
    return;
  }

  function CurrentEffectGrantsGoAgain($param) {
    return false;
  }

  function PitchAbility($from) {
    return;
  }

  function CombatChainCloseAbility($chainLink) {
    return;
  }

  function WeaponPowerModifier($basePower) {
    // this function is distinct for PowerModifier, use if for weapons that buff themselves
    // (like anothos) rather than weapons that buff their attacks (like starfall)
    return $basePower;
  }

  function EntersArenaAbility() {
    return;
  }

  function PlayableFromGraveyard($index) {
    return false;
  }

  function AbilityPlayableFromGraveyard($index) {
    return false;
  }

  function IsGrantedBuff() {
    return false;
  }

  function AddCardEffectHitTrigger($sourceID, $targetPlayer, $mode) {
    return;
  }

  function IsCombatEffectPersistent($mode) {
    return false;
  }

  function AuraPowerModifiers($index, &$powerModifiers) {
    return 0;
  }

  function PermDamagePreventionAmount($index, $type, $damage, $active, &$cancelRemove, $check) {
    return 0;
  }

  function PermCostModifier($cardID, $from) {
    return 0;
  }

  function DefaultActiveState() {
    return 2;
  }

  function HasWateryGrave() {
    return GeneratedHasWateryGrave($this->cardID);
  }

  function BeginningActionPhaseAbility($index) {
    return;
  }

  function CurrentEffectDamagePrevention($type, $damage, $source, $index, &$remove, $amount=false) {
    return 0;
  }

  //"Special" functions override the generated card dictionary
  function SpecialType() {
    return "-";
  }

  function SpecialPower() {
    return -1;
  }

  function SpecialBlock() {
    return -1;
  }

  function SpecialPitch() {
    return -1;
  }

  function SpecialName() {
    return "-";
  }

  function SpecialClass() {
    return "-";
  }

  function SpecialTalent() {
    return "-";
  }
  
  function SpecialCost() {
    return -1;
  }

  function HasBeatChest() {
    return GeneratedHasBeatChest($this->cardID);
  }

  function CurrentEffectCostModifier($cardID, $from, &$remove) {
    return 0;
  }

  function WhenBeatChest($index) {
    return;
  }

  function CardPlayTrigger($cardID, $from, $index) {
    return;
  }

  function HasStealth() {
    return GeneratedHasStealth($this->cardID);
  }

  function CurrentEffectEndTurnAbilities($i, &$remove) {
    return;
  }

  function CheerTrigger() {
    return;
  }

  function PlayableFromBanish($mod, $nonLimitedOnly) {
    return false;
  }

  function ArcaneBarrier() {
    return GeneratedArcaneBarrierAmount($this->cardID);
  }

  function PlayCardAbility($cardID, $from) {
    return;
  }

  function PlayCardEffectAbility($cardID, $from, &$remove) {
    return;
  }

  function PermanentPlayAbility($cardID, $from, $i) {
    return false;
  }

  function SpellVoidAmount() {
    return GeneratedSpellVoidAmount($this->cardID);
  }

  function UnityEffect() {
    return;
  }

  function RemoveEffectFromCombatChain() {
    return false;
  }

  function DynamicCost() {
    return "";
  }

  function ArcaneTargeting($from) {
    return -1;
  }

  function ArcaneDamage() {
    return -1;
  }

  function ActionsThatDoArcaneDamage() {
    return false;
  }

  function ArcaneHitEffect($source, $target, $damage) {
    return;
  }

  function CardCaresAboutPitch() {
    return false;
  }

  function CurrentTurnEffectUses() {
    return 1;
  }

  function DestroyEffect() {
    return;
  }

  function ResolutionStepAttackTriggers() {
    return;
  }

  function GetHitTrigger($source) {
    return;
  }

  function BlockCardDestroyed() {
    return;
  }

  function DoesEffectGrantDominate() {
    return false;
  }

  function ResolutionStepBlockTrigger($i) {
    return;
  }

  function OppStartTurnAbility($index) {
    return;
  }

  function StaticPowerModifier($index, &$powerModifiers) {
    return 0;
  }

  function HasGoAgain($from) {
    return GeneratedGoAgain($this->cardID);
  }

  function EffectBlockModifier($index, $from) {
    return 0;
  }

  function EffectAttackYouControlModifiers($cardID) {
    return 0;
  }

  function EffectDefenderPowerModifier($cardID)  {
    return 0;
  }

  function StaticDefenderPowerModifier($cardID)  {
    return 0;
  }

  function DiscardStartTurnTrigger($index) {
    return;
  }

  function CurrentEffectBeginningActionPhaseAbility($i) {
    return;
  }

  function HasHightide() {
    return GeneratedHasHightide($this->cardID);
  }

  function WardAmount($index) {
    return GeneratedWardAmount($this->cardID);
  }

  function HasWard() {
    return GeneratedHasWard($this->cardID);
  }

  function HasMirage() {
    return GeneratedHasMirage($this->cardID);
  }

  function CardCareAboutChiPitch() {
    return false;
  }

  function StaticCostModifier($cardID, $from, $cost) {
    return 0;
  }

  function CurrentEffectBeginEndPhaseAbility($i) {
    return ;
  }

  function SpecificLogic() {
    // handles the end of DQ stuff
    return;
  }

  function CurrentEffectGrantsNAAGoAgain($cardID, $from, $uniqueID, $parameter, &$remove) {
    return false;
  }
}
