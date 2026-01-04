<?php

class ClassState {

  // Properties
  private $classState = [];
  private $player = 0;

  // Constructor
  function __construct($player) {
    $this->classState = &GetPlayerClassState($player);
    $this->player = $player;
  }

	function GetState($index) {
		return $this->classState[$index];
	}

	function SetState($index, $value) {
		if (isset($this->classState[$index])) $this->classState[$index] = $value;
	}

	function IncState($index, $by) {
		if (isset($this->classState[$index]) && is_numeric($this->classState[$index])) $this->classState[$index] += $by;
	}

	function Num6PowDisc() {
		global $CS_Num6PowDisc;
		return $this->classState[$CS_Num6PowDisc] ?? 0;
	}

	function NumBoosted() {
		global $CS_NumBoosted;
		return $this->classState[$CS_NumBoosted] ?? 0;
	}

	function AttacksWithWeapon() {
		global $CS_AttacksWithWeapon;
		return $this->classState[$CS_AttacksWithWeapon] ?? 0;
	}

	function HitsWDawnblade() {
		global $CS_HitsWDawnblade;
		return $this->classState[$CS_HitsWDawnblade] ?? 0;
	}

	function DamagePrevention() {
		global $CS_DamagePrevention;
		return $this->classState[$CS_DamagePrevention] ?? 0;
	}

	function CardsBanished() {
		global $CS_CardsBanished;
		return $this->classState[$CS_CardsBanished] ?? 0;
	}

	function DamageTaken() {
		global $CS_DamageTaken;
		return $this->classState[$CS_DamageTaken] ?? 0;
	}

	function NumActionsPlayed() {
		global $CS_NumActionsPlayed;
		return $this->classState[$CS_NumActionsPlayed] ?? 0;
	}

	function ArsenalFacing() {
		global $CS_ArsenalFacing;
		return $this->classState[$CS_ArsenalFacing] ?? 0;
	}

	function CharacterIndex() {
		global $CS_CharacterIndex;
		return $this->classState[$CS_CharacterIndex] ?? 0;
	}

	function PlayIndex() {
		global $CS_PlayIndex;
		return $this->classState[$CS_PlayIndex] ?? -1;
	}

	function NumNonAttackCards() {
		global $CS_NumNonAttackCards;
		return $this->classState[$CS_NumNonAttackCards] ?? 0;
	}

	function NumCardsDrawn() {
		global $CS_NumCardsDrawn;
		return $this->classState[$CS_NumCardsDrawn] ?? 0;
	}

	function NumAddedToSoul() {
		global $CS_NumAddedToSoul;
		return $this->classState[$CS_NumAddedToSoul] ?? 0;
	}

	function NextNAACardGoAgain() {
		global $CS_NextNAACardGoAgain;
		return $this->classState[$CS_NextNAACardGoAgain] ?? 0;
	}

	function NumCharged() {
		global $CS_NumCharged;
		return $this->classState[$CS_NumCharged] ?? 0;
	}

	function Num6PowBan() {
		global $CS_Num6PowBan;
		return $this->classState[$CS_Num6PowBan] ?? 0;
	}

	function ResolvingLayerUniqueID() {
		global $CS_ResolvingLayerUniqueID;
		return $this->classState[$CS_ResolvingLayerUniqueID] ?? -1;
	}

	function NextWizardNAAInstant() {
		global $CS_NextWizardNAAInstant;
		return $this->classState[$CS_NextWizardNAAInstant] ?? 0;
	}

	function ArcaneDamageTaken() {
		global $CS_ArcaneDamageTaken;
		return $this->classState[$CS_ArcaneDamageTaken] ?? 0;
	}

	function NextNAAInstant() {
		global $CS_NextNAAInstant;
		return $this->classState[$CS_NextNAAInstant] ?? 0;
	}

	function NextDamagePrevented() {
		global $CS_NextDamagePrevented;
		return $this->classState[$CS_NextDamagePrevented] ?? 0;
	}

	function LastAttack() {
		global $CS_LastAttack;
		return $this->classState[$CS_LastAttack] ?? "NA";
	}

	function NumFusedEarth() {
		global $CS_NumFusedEarth;
		return $this->classState[$CS_NumFusedEarth] ?? 0;
	}

	function NumFusedIce() {
		global $CS_NumFusedIce;
		return $this->classState[$CS_NumFusedIce] ?? 0;
	}

	function NumFusedLightning() {
		global $CS_NumFusedLightning;
		return $this->classState[$CS_NumFusedLightning] ?? 0;
	}

	function PitchedForThisCard() {
		global $CS_PitchedForThisCard;
		return $this->classState[$CS_PitchedForThisCard] ?? "-";
	}

	function PlayCCIndex() {
		global $CS_PlayCCIndex;
		return $this->classState[$CS_PlayCCIndex] ?? -1;
	}

	function NumAttackCards() {
		global $CS_NumAttackCards;
		return $this->classState[$CS_NumAttackCards] ?? 0;
	}

	function NumPlayedFromBanish() {
		global $CS_NumPlayedFromBanish;
		return $this->classState[$CS_NumPlayedFromBanish] ?? 0;
	}

	function NumAttacks() {
		global $CS_NumAttacks;
		return $this->classState[$CS_NumAttacks] ?? 0;
	}

	function DieRoll() {
		global $CS_DieRoll;
		return $this->classState[$CS_DieRoll] ?? 0;
	}

	function NumBloodDebtPlayed() {
		global $CS_NumBloodDebtPlayed;
		return $this->classState[$CS_NumBloodDebtPlayed] ?? 0;
	}

	function NumWizardNonAttack() {
		global $CS_NumWizardNonAttack;
		return $this->classState[$CS_NumWizardNonAttack] ?? 0;
	}

	function LayerTarget() {
		global $CS_LayerTarget;
		return $this->classState[$CS_LayerTarget] ?? "-";
	}

	function NumSwordAttacks() {
		global $CS_NumSwordAttacks;
		return $this->classState[$CS_NumSwordAttacks] ?? 0;
	}

	function HitsWithWeapon() {
		global $CS_HitsWithWeapon;
		return $this->classState[$CS_HitsWithWeapon] ?? 0;
	}

	function ArcaneDamagePrevention() {
		global $CS_ArcaneDamagePrevention;
		return $this->classState[$CS_ArcaneDamagePrevention] ?? 0;
	}

	function DynCostResolved() {
		global $CS_DynCostResolved;
		return $this->classState[$CS_DynCostResolved] ?? 0;
	}

	function CardsEnteredGY() {
		global $CS_CardsEnteredGY;
		return $this->classState[$CS_CardsEnteredGY] ?? 0;
	}

	function HighestRoll() {
		global $CS_HighestRoll;
		return $this->classState[$CS_HighestRoll] ?? 0;
	}

	function NumYellowPutSoul() {
		global $CS_NumYellowPutSoul;
		return $this->classState[$CS_NumYellowPutSoul] ?? 0;
	}

	function NumAuras() {
		global $CS_NumAuras;
		return $this->classState[$CS_NumAuras] ?? 0;
	}

	function AbilityIndex() {
		global $CS_AbilityIndex;
		return $this->classState[$CS_AbilityIndex] ?? "-";
	}

	function AdditionalCosts() {
		global $CS_AdditionalCosts;
		return $this->classState[$CS_AdditionalCosts] ?? "-";
	}

	function NumRedPlayed() {
		global $CS_NumRedPlayed;
		return $this->classState[$CS_NumRedPlayed] ?? 0;
	}

	function PlayUniqueID() {
		global $CS_PlayUniqueID;
		return $this->classState[$CS_PlayUniqueID] ?? -1;
	}

	function NumPhantasmAADestroyed() {
		global $CS_NumPhantasmAADestroyed;
		return $this->classState[$CS_NumPhantasmAADestroyed] ?? 0;
	}

	function NumLess3PowAAPlayed() {
		global $CS_NumLess3PowAAPlayed;
		return $this->classState[$CS_NumLess3PowAAPlayed] ?? 0;
	}

	function AlluvionUsed() {
		global $CS_AlluvionUsed;
		return $this->classState[$CS_AlluvionUsed] ?? 0;
	}

	function MaxQuellUsed() {
		global $CS_MaxQuellUsed;
		return $this->classState[$CS_MaxQuellUsed] ?? 0;
	}

	function DamageDealt() {
		global $CS_DamageDealt;
		return $this->classState[$CS_DamageDealt] ?? 0;
	}

	function ArcaneTargetsSelected() {
		global $CS_ArcaneTargetsSelected;
		return $this->classState[$CS_ArcaneTargetsSelected] ?? "-";
	}

	function NumDragonAttacks() {
		global $CS_NumDragonAttacks;
		return $this->classState[$CS_NumDragonAttacks] ?? 0;
	}

	function NumIllusionistAttacks() {
		global $CS_NumIllusionistAttacks;
		return $this->classState[$CS_NumIllusionistAttacks] ?? 0;
	}

	function LastDynCost() {
		global $CS_LastDynCost;
		return $this->classState[$CS_LastDynCost] ?? 0;
	}

	function NumIllusionistActionCardAttacks() {
		global $CS_NumIllusionistActionCardAttacks;
		return $this->classState[$CS_NumIllusionistActionCardAttacks] ?? 0;
	}

	function ArcaneDamageDealt() {
		global $CS_ArcaneDamageDealt;
		return $this->classState[$CS_ArcaneDamageDealt] ?? 0;
	}

	function LayerPlayIndex() {
		global $CS_LayerPlayIndex;
		return $this->classState[$CS_LayerPlayIndex] ?? -1;
	}

	function NumCardsPlayed() {
		global $CS_NumCardsPlayed;
		return $this->classState[$CS_NumCardsPlayed] ?? 0;
	}

	function NamesOfCardsPlayed() {
		global $CS_NamesOfCardsPlayed;
		return $this->classState[$CS_NamesOfCardsPlayed] ?? "-";
	}

	function NumBoostPlayed() {
		global $CS_NumBoostPlayed;
		return $this->classState[$CS_NumBoostPlayed] ?? 0;
	}

	function PlayedAsInstant() {
		global $CS_PlayedAsInstant;
		return $this->classState[$CS_PlayedAsInstant] ?? 0;
	}

	function AnotherWeaponGainedGoAgain() {
		global $CS_AnotherWeaponGainedGoAgain;
		return $this->classState[$CS_AnotherWeaponGainedGoAgain] ?? "-";
	}

	function NumContractsCompleted() {
		global $CS_NumContractsCompleted;
		return $this->classState[$CS_NumContractsCompleted] ?? 0;
	}

	function HitsWithSword() {
		global $CS_HitsWithSword;
		return $this->classState[$CS_HitsWithSword] ?? 0;
	}

	function HealthLost() {
		global $CS_HealthLost;
		return $this->classState[$CS_HealthLost] ?? 0;
	}

	function NumCranked() {
		global $CS_NumCranked;
		return $this->classState[$CS_NumCranked] ?? 0;
	}

	function NumItemsDestroyed() {
		global $CS_NumItemsDestroyed;
		return $this->classState[$CS_NumItemsDestroyed] ?? 0;
	}

	function NumCrouchingTigerPlayedThisTurn() {
		global $CS_NumCrouchingTigerPlayedThisTurn;
		return $this->classState[$CS_NumCrouchingTigerPlayedThisTurn] ?? 0;
	}

	function NumClashesWon() {
		global $CS_NumClashesWon;
		return $this->classState[$CS_NumClashesWon] ?? 0;
	}

	function NumVigorDestroyed() {
		global $CS_NumVigorDestroyed;
		return $this->classState[$CS_NumVigorDestroyed] ?? 0;
	}

	function NumMightDestroyed() {
		global $CS_NumMightDestroyed;
		return $this->classState[$CS_NumMightDestroyed] ?? 0;
	}

	function NumAgilityDestroyed() {
		global $CS_NumAgilityDestroyed;
		return $this->classState[$CS_NumAgilityDestroyed] ?? 0;
	}

	function HaveIntimidated() {
		global $CS_HaveIntimidated;
		return $this->classState[$CS_HaveIntimidated] ?? 0;
	}

	function ModalAbilityChoosen() {
		global $CS_ModalAbilityChoosen;
		return $this->classState[$CS_ModalAbilityChoosen] ?? "-";
	}

	function NumSpectralShieldAttacks() {
		global $CS_NumSpectralShieldAttacks;
		return $this->classState[$CS_NumSpectralShieldAttacks] ?? 0;
	}

	function NumBluePlayed() {
		global $CS_NumBluePlayed;
		return $this->classState[$CS_NumBluePlayed] ?? 0;
	}

	function Transcended() {
		global $CS_Transcended;
		return $this->classState[$CS_Transcended] ?? 0;
	}

	function NumCrouchingTigerCreatedThisTurn() {
		global $CS_NumCrouchingTigerCreatedThisTurn;
		return $this->classState[$CS_NumCrouchingTigerCreatedThisTurn] ?? 0;
	}

	function NumBlueDefended() {
		global $CS_NumBlueDefended;
		return $this->classState[$CS_NumBlueDefended] ?? 0;
	}

	function NumLightningPlayed() {
		global $CS_NumLightningPlayed;
		return $this->classState[$CS_NumLightningPlayed] ?? 0;
	}

	function NumInstantPlayed() {
		global $CS_NumInstantPlayed;
		return $this->classState[$CS_NumInstantPlayed] ?? 0;
	}

	function ActionsPlayed() {
		global $CS_ActionsPlayed;
		return $this->classState[$CS_ActionsPlayed] ?? "-";
	}

	function NumEarthBanished() {
		global $CS_NumEarthBanished;
		return $this->classState[$CS_NumEarthBanished] ?? 0;
	}

	function HealthGained() {
		global $CS_HealthGained;
		return $this->classState[$CS_HealthGained] ?? 0;
	}

	function SkipAllRunechants() {
		global $CS_SkipAllRunechants;
		return $this->classState[$CS_SkipAllRunechants] ?? 0;
	}

	function FealtyCreated() {
		global $CS_FealtyCreated;
		return $this->classState[$CS_FealtyCreated] ?? 0;
	}

	function NumDraconicPlayed() {
		global $CS_NumDraconicPlayed;
		return $this->classState[$CS_NumDraconicPlayed] ?? 0;
	}

	function NumSeismicSurgeDestroyed() {
		global $CS_NumSeismicSurgeDestroyed;
		return $this->classState[$CS_NumSeismicSurgeDestroyed] ?? 0;
	}

	function PowDamageDealt() {
		global $CS_PowDamageDealt;
		return $this->classState[$CS_PowDamageDealt] ?? 0;
	}

	function TunicTicks() {
		global $CS_TunicTicks;
		return $this->classState[$CS_TunicTicks] ?? 0;
	}

	function OriginalHero() {
		global $CS_OriginalHero;
		return $this->classState[$CS_OriginalHero] ?? 0;
	}

	function NumTimesAttacked() {
		global $CS_NumTimesAttacked;
		return $this->classState[$CS_NumTimesAttacked] ?? 0;
	}

	function DamageDealtToOpponent() {
		global $CS_DamageDealtToOpponent;
		return $this->classState[$CS_DamageDealtToOpponent] ?? 0;
	}

	function NumStealthAttacks() {
		global $CS_NumStealthAttacks;
		return $this->classState[$CS_NumStealthAttacks] ?? 0;
	}

	function NumWateryGrave() {
		global $CS_NumWateryGrave;
		return $this->classState[$CS_NumWateryGrave] ?? 0;
	}

	function NumCannonsActivated() {
		global $CS_NumCannonsActivated;
		return $this->classState[$CS_NumCannonsActivated] ?? 0;
	}

	function NumGoldCreated() {
		global $CS_NumGoldCreated;
		return $this->classState[$CS_NumGoldCreated] ?? 0;
	}

	function NumAllyPutInGraveyard() {
		global $CS_NumAllyPutInGraveyard;
		return $this->classState[$CS_NumAllyPutInGraveyard] ?? 0;
	}

	function PlayedNimblism() {
		global $CS_PlayedNimblism;
		return $this->classState[$CS_PlayedNimblism] ?? 0;
	}

	function NumAttackCardsAttacked() {
		global $CS_NumAttackCardsAttacked;
		return $this->classState[$CS_NumAttackCardsAttacked] ?? 0;
	}

	function NumAttackCardsBlocked() {
		global $CS_NumAttackCardsBlocked;
		return $this->classState[$CS_NumAttackCardsBlocked] ?? 0;
	}

	function CheeredThisTurn() {
		global $CS_CheeredThisTurn;
		return $this->classState[$CS_CheeredThisTurn] ?? 0;
	}

	function BooedThisTurn() {
		global $CS_BooedThisTurn;
		return $this->classState[$CS_BooedThisTurn] ?? 0;
	}

	function SuspensePoppedThisTurn() {
		global $CS_SuspensePoppedThisTurn;
		return $this->classState[$CS_SuspensePoppedThisTurn] ?? 0;
	}

	function SeismicSurgesCreated() {
		global $CS_SeismicSurgesCreated;
		return $this->classState[$CS_SeismicSurgesCreated] ?? 0;
	}

	function CardsInDeckBeforeOpt() {
		global $CS_CardsInDeckBeforeOpt;
		return $this->classState[$CS_CardsInDeckBeforeOpt] ?? "-";
	}

	function NumToughnessDestroyed() {
		global $CS_NumToughnessDestroyed;
		return $this->classState[$CS_NumToughnessDestroyed] ?? 0;
	}

	function NumConfidenceDestroyed() {
		global $CS_NumConfidenceDestroyed;
		return $this->classState[$CS_NumConfidenceDestroyed] ?? 0;
	}

	function NumCostedCardsPlayed() {
		global $CS_NumCostedCardsPlayed;
		return $this->classState[$CS_NumCostedCardsPlayed] ?? 0;
	}

	function HitCounter() {
		global $CS_HitCounter;
		return $this->classState[$CS_HitCounter] ?? 0;
	}

	function SetNum6PowDisc($value) {
		global $CS_Num6PowDisc;
		if (isset($this->classState[$CS_Num6PowDisc])) $this->classState[$CS_Num6PowDisc] = $value;
	}

	function SetNumBoosted($value) {
		global $CS_NumBoosted;
		if (isset($this->classState[$CS_NumBoosted])) $this->classState[$CS_NumBoosted] = $value;
	}

	function SetAttacksWithWeapon($value) {
		global $CS_AttacksWithWeapon;
		if (isset($this->classState[$CS_AttacksWithWeapon])) $this->classState[$CS_AttacksWithWeapon] = $value;
	}

	function SetHitsWDawnblade($value) {
		global $CS_HitsWDawnblade;
		if (isset($this->classState[$CS_HitsWDawnblade])) $this->classState[$CS_HitsWDawnblade] = $value;
	}

	function SetDamagePrevention($value) {
		global $CS_DamagePrevention;
		if (isset($this->classState[$CS_DamagePrevention])) $this->classState[$CS_DamagePrevention] = $value;
	}

	function SetCardsBanished($value) {
		global $CS_CardsBanished;
		if (isset($this->classState[$CS_CardsBanished])) $this->classState[$CS_CardsBanished] = $value;
	}

	function SetDamageTaken($value) {
		global $CS_DamageTaken;
		if (isset($this->classState[$CS_DamageTaken])) $this->classState[$CS_DamageTaken] = $value;
	}

	function SetNumActionsPlayed($value) {
		global $CS_NumActionsPlayed;
		if (isset($this->classState[$CS_NumActionsPlayed])) $this->classState[$CS_NumActionsPlayed] = $value;
	}

	function SetArsenalFacing($value) {
		global $CS_ArsenalFacing;
		if (isset($this->classState[$CS_ArsenalFacing])) $this->classState[$CS_ArsenalFacing] = $value;
	}

	function SetCharacterIndex($value) {
		global $CS_CharacterIndex;
		if (isset($this->classState[$CS_CharacterIndex])) $this->classState[$CS_CharacterIndex] = $value;
	}

	function SetPlayIndex($value) {
		global $CS_PlayIndex;
		if (isset($this->classState[$CS_PlayIndex])) $this->classState[$CS_PlayIndex] = $value;
	}

	function SetNumNonAttackCards($value) {
		global $CS_NumNonAttackCards;
		if (isset($this->classState[$CS_NumNonAttackCards])) $this->classState[$CS_NumNonAttackCards] = $value;
	}

	function SetNumCardsDrawn($value) {
		global $CS_NumCardsDrawn;
		if (isset($this->classState[$CS_NumCardsDrawn])) $this->classState[$CS_NumCardsDrawn] = $value;
	}

	function SetNumAddedToSoul($value) {
		global $CS_NumAddedToSoul;
		if (isset($this->classState[$CS_NumAddedToSoul])) $this->classState[$CS_NumAddedToSoul] = $value;
	}

	function SetNextNAACardGoAgain($value) {
		global $CS_NextNAACardGoAgain;
		if (isset($this->classState[$CS_NextNAACardGoAgain])) $this->classState[$CS_NextNAACardGoAgain] = $value;
	}

	function SetNumCharged($value) {
		global $CS_NumCharged;
		if (isset($this->classState[$CS_NumCharged])) $this->classState[$CS_NumCharged] = $value;
	}

	function SetNum6PowBan($value) {
		global $CS_Num6PowBan;
		if (isset($this->classState[$CS_Num6PowBan])) $this->classState[$CS_Num6PowBan] = $value;
	}

	function SetResolvingLayerUniqueID($value) {
		global $CS_ResolvingLayerUniqueID;
		if (isset($this->classState[$CS_ResolvingLayerUniqueID])) $this->classState[$CS_ResolvingLayerUniqueID] = $value;
	}

	function SetNextWizardNAAInstant($value) {
		global $CS_NextWizardNAAInstant;
		if (isset($this->classState[$CS_NextWizardNAAInstant])) $this->classState[$CS_NextWizardNAAInstant] = $value;
	}

	function SetArcaneDamageTaken($value) {
		global $CS_ArcaneDamageTaken;
		if (isset($this->classState[$CS_ArcaneDamageTaken])) $this->classState[$CS_ArcaneDamageTaken] = $value;
	}

	function SetNextNAAInstant($value) {
		global $CS_NextNAAInstant;
		if (isset($this->classState[$CS_NextNAAInstant])) $this->classState[$CS_NextNAAInstant] = $value;
	}

	function SetNextDamagePrevented($value) {
		global $CS_NextDamagePrevented;
		if (isset($this->classState[$CS_NextDamagePrevented])) $this->classState[$CS_NextDamagePrevented] = $value;
	}

	function SetLastAttack($value) {
		global $CS_LastAttack;
		if (isset($this->classState[$CS_LastAttack])) $this->classState[$CS_LastAttack] = $value;
	}

	function SetNumFusedEarth($value) {
		global $CS_NumFusedEarth;
		if (isset($this->classState[$CS_NumFusedEarth])) $this->classState[$CS_NumFusedEarth] = $value;
	}

	function SetNumFusedIce($value) {
		global $CS_NumFusedIce;
		if (isset($this->classState[$CS_NumFusedIce])) $this->classState[$CS_NumFusedIce] = $value;
	}

	function SetNumFusedLightning($value) {
		global $CS_NumFusedLightning;
		if (isset($this->classState[$CS_NumFusedLightning])) $this->classState[$CS_NumFusedLightning] = $value;
	}

	function SetPitchedForThisCard($value) {
		global $CS_PitchedForThisCard;
		if (isset($this->classState[$CS_PitchedForThisCard])) $this->classState[$CS_PitchedForThisCard] = $value;
	}

	function SetPlayCCIndex($value) {
		global $CS_PlayCCIndex;
		if (isset($this->classState[$CS_PlayCCIndex])) $this->classState[$CS_PlayCCIndex] = $value;
	}

	function SetNumAttackCards($value) {
		global $CS_NumAttackCards;
		if (isset($this->classState[$CS_NumAttackCards])) $this->classState[$CS_NumAttackCards] = $value;
	}

	function SetNumPlayedFromBanish($value) {
		global $CS_NumPlayedFromBanish;
		if (isset($this->classState[$CS_NumPlayedFromBanish])) $this->classState[$CS_NumPlayedFromBanish] = $value;
	}

	function SetNumAttacks($value) {
		global $CS_NumAttacks;
		if (isset($this->classState[$CS_NumAttacks])) $this->classState[$CS_NumAttacks] = $value;
	}

	function SetDieRoll($value) {
		global $CS_DieRoll;
		if (isset($this->classState[$CS_DieRoll])) $this->classState[$CS_DieRoll] = $value;
	}

	function SetNumBloodDebtPlayed($value) {
		global $CS_NumBloodDebtPlayed;
		if (isset($this->classState[$CS_NumBloodDebtPlayed])) $this->classState[$CS_NumBloodDebtPlayed] = $value;
	}

	function SetNumWizardNonAttack($value) {
		global $CS_NumWizardNonAttack;
		if (isset($this->classState[$CS_NumWizardNonAttack])) $this->classState[$CS_NumWizardNonAttack] = $value;
	}

	function SetLayerTarget($value) {
		global $CS_LayerTarget;
		if (isset($this->classState[$CS_LayerTarget])) $this->classState[$CS_LayerTarget] = $value;
	}

	function SetNumSwordAttacks($value) {
		global $CS_NumSwordAttacks;
		if (isset($this->classState[$CS_NumSwordAttacks])) $this->classState[$CS_NumSwordAttacks] = $value;
	}

	function SetHitsWithWeapon($value) {
		global $CS_HitsWithWeapon;
		if (isset($this->classState[$CS_HitsWithWeapon])) $this->classState[$CS_HitsWithWeapon] = $value;
	}

	function SetArcaneDamagePrevention($value) {
		global $CS_ArcaneDamagePrevention;
		if (isset($this->classState[$CS_ArcaneDamagePrevention])) $this->classState[$CS_ArcaneDamagePrevention] = $value;
	}

	function SetDynCostResolved($value) {
		global $CS_DynCostResolved;
		if (isset($this->classState[$CS_DynCostResolved])) $this->classState[$CS_DynCostResolved] = $value;
	}

	function SetCardsEnteredGY($value) {
		global $CS_CardsEnteredGY;
		if (isset($this->classState[$CS_CardsEnteredGY])) $this->classState[$CS_CardsEnteredGY] = $value;
	}

	function SetHighestRoll($value) {
		global $CS_HighestRoll;
		if (isset($this->classState[$CS_HighestRoll])) $this->classState[$CS_HighestRoll] = $value;
	}

	function SetNumYellowPutSoul($value) {
		global $CS_NumYellowPutSoul;
		if (isset($this->classState[$CS_NumYellowPutSoul])) $this->classState[$CS_NumYellowPutSoul] = $value;
	}

	function SetNumAuras($value) {
		global $CS_NumAuras;
		if (isset($this->classState[$CS_NumAuras])) $this->classState[$CS_NumAuras] = $value;
	}

	function SetAbilityIndex($value) {
		global $CS_AbilityIndex;
		if (isset($this->classState[$CS_AbilityIndex])) $this->classState[$CS_AbilityIndex] = $value;
	}

	function SetAdditionalCosts($value) {
		global $CS_AdditionalCosts;
		if (isset($this->classState[$CS_AdditionalCosts])) $this->classState[$CS_AdditionalCosts] = $value;
	}

	function SetNumRedPlayed($value) {
		global $CS_NumRedPlayed;
		if (isset($this->classState[$CS_NumRedPlayed])) $this->classState[$CS_NumRedPlayed] = $value;
	}

	function SetPlayUniqueID($value) {
		global $CS_PlayUniqueID;
		if (isset($this->classState[$CS_PlayUniqueID])) $this->classState[$CS_PlayUniqueID] = $value;
	}

	function SetNumPhantasmAADestroyed($value) {
		global $CS_NumPhantasmAADestroyed;
		if (isset($this->classState[$CS_NumPhantasmAADestroyed])) $this->classState[$CS_NumPhantasmAADestroyed] = $value;
	}

	function SetNumLess3PowAAPlayed($value) {
		global $CS_NumLess3PowAAPlayed;
		if (isset($this->classState[$CS_NumLess3PowAAPlayed])) $this->classState[$CS_NumLess3PowAAPlayed] = $value;
	}

	function SetAlluvionUsed($value) {
		global $CS_AlluvionUsed;
		if (isset($this->classState[$CS_AlluvionUsed])) $this->classState[$CS_AlluvionUsed] = $value;
	}

	function SetMaxQuellUsed($value) {
		global $CS_MaxQuellUsed;
		if (isset($this->classState[$CS_MaxQuellUsed])) $this->classState[$CS_MaxQuellUsed] = $value;
	}

	function SetDamageDealt($value) {
		global $CS_DamageDealt;
		if (isset($this->classState[$CS_DamageDealt])) $this->classState[$CS_DamageDealt] = $value;
	}

	function SetArcaneTargetsSelected($value) {
		global $CS_ArcaneTargetsSelected;
		if (isset($this->classState[$CS_ArcaneTargetsSelected])) $this->classState[$CS_ArcaneTargetsSelected] = $value;
	}

	function SetNumDragonAttacks($value) {
		global $CS_NumDragonAttacks;
		if (isset($this->classState[$CS_NumDragonAttacks])) $this->classState[$CS_NumDragonAttacks] = $value;
	}

	function SetNumIllusionistAttacks($value) {
		global $CS_NumIllusionistAttacks;
		if (isset($this->classState[$CS_NumIllusionistAttacks])) $this->classState[$CS_NumIllusionistAttacks] = $value;
	}

	function SetLastDynCost($value) {
		global $CS_LastDynCost;
		if (isset($this->classState[$CS_LastDynCost])) $this->classState[$CS_LastDynCost] = $value;
	}

	function SetNumIllusionistActionCardAttacks($value) {
		global $CS_NumIllusionistActionCardAttacks;
		if (isset($this->classState[$CS_NumIllusionistActionCardAttacks])) $this->classState[$CS_NumIllusionistActionCardAttacks] = $value;
	}

	function SetArcaneDamageDealt($value) {
		global $CS_ArcaneDamageDealt;
		if (isset($this->classState[$CS_ArcaneDamageDealt])) $this->classState[$CS_ArcaneDamageDealt] = $value;
	}

	function SetLayerPlayIndex($value) {
		global $CS_LayerPlayIndex;
		if (isset($this->classState[$CS_LayerPlayIndex])) $this->classState[$CS_LayerPlayIndex] = $value;
	}

	function SetNumCardsPlayed($value) {
		global $CS_NumCardsPlayed;
		if (isset($this->classState[$CS_NumCardsPlayed])) $this->classState[$CS_NumCardsPlayed] = $value;
	}

	function SetNamesOfCardsPlayed($value) {
		global $CS_NamesOfCardsPlayed;
		if (isset($this->classState[$CS_NamesOfCardsPlayed])) $this->classState[$CS_NamesOfCardsPlayed] = $value;
	}

	function SetNumBoostPlayed($value) {
		global $CS_NumBoostPlayed;
		if (isset($this->classState[$CS_NumBoostPlayed])) $this->classState[$CS_NumBoostPlayed] = $value;
	}

	function SetPlayedAsInstant($value) {
		global $CS_PlayedAsInstant;
		if (isset($this->classState[$CS_PlayedAsInstant])) $this->classState[$CS_PlayedAsInstant] = $value;
	}

	function SetAnotherWeaponGainedGoAgain($value) {
		global $CS_AnotherWeaponGainedGoAgain;
		if (isset($this->classState[$CS_AnotherWeaponGainedGoAgain])) $this->classState[$CS_AnotherWeaponGainedGoAgain] = $value;
	}

	function SetNumContractsCompleted($value) {
		global $CS_NumContractsCompleted;
		if (isset($this->classState[$CS_NumContractsCompleted])) $this->classState[$CS_NumContractsCompleted] = $value;
	}

	function SetHitsWithSword($value) {
		global $CS_HitsWithSword;
		if (isset($this->classState[$CS_HitsWithSword])) $this->classState[$CS_HitsWithSword] = $value;
	}

	function SetHealthLost($value) {
		global $CS_HealthLost;
		if (isset($this->classState[$CS_HealthLost])) $this->classState[$CS_HealthLost] = $value;
	}

	function SetNumCranked($value) {
		global $CS_NumCranked;
		if (isset($this->classState[$CS_NumCranked])) $this->classState[$CS_NumCranked] = $value;
	}

	function SetNumItemsDestroyed($value) {
		global $CS_NumItemsDestroyed;
		if (isset($this->classState[$CS_NumItemsDestroyed])) $this->classState[$CS_NumItemsDestroyed] = $value;
	}

	function SetNumCrouchingTigerPlayedThisTurn($value) {
		global $CS_NumCrouchingTigerPlayedThisTurn;
		if (isset($this->classState[$CS_NumCrouchingTigerPlayedThisTurn])) $this->classState[$CS_NumCrouchingTigerPlayedThisTurn] = $value;
	}

	function SetNumClashesWon($value) {
		global $CS_NumClashesWon;
		if (isset($this->classState[$CS_NumClashesWon])) $this->classState[$CS_NumClashesWon] = $value;
	}

	function SetNumVigorDestroyed($value) {
		global $CS_NumVigorDestroyed;
		if (isset($this->classState[$CS_NumVigorDestroyed])) $this->classState[$CS_NumVigorDestroyed] = $value;
	}

	function SetNumMightDestroyed($value) {
		global $CS_NumMightDestroyed;
		if (isset($this->classState[$CS_NumMightDestroyed])) $this->classState[$CS_NumMightDestroyed] = $value;
	}

	function SetNumAgilityDestroyed($value) {
		global $CS_NumAgilityDestroyed;
		if (isset($this->classState[$CS_NumAgilityDestroyed])) $this->classState[$CS_NumAgilityDestroyed] = $value;
	}

	function SetHaveIntimidated($value) {
		global $CS_HaveIntimidated;
		if (isset($this->classState[$CS_HaveIntimidated])) $this->classState[$CS_HaveIntimidated] = $value;
	}

	function SetModalAbilityChoosen($value) {
		global $CS_ModalAbilityChoosen;
		if (isset($this->classState[$CS_ModalAbilityChoosen])) $this->classState[$CS_ModalAbilityChoosen] = $value;
	}

	function SetNumSpectralShieldAttacks($value) {
		global $CS_NumSpectralShieldAttacks;
		if (isset($this->classState[$CS_NumSpectralShieldAttacks])) $this->classState[$CS_NumSpectralShieldAttacks] = $value;
	}

	function SetNumBluePlayed($value) {
		global $CS_NumBluePlayed;
		if (isset($this->classState[$CS_NumBluePlayed])) $this->classState[$CS_NumBluePlayed] = $value;
	}

	function SetTranscended($value) {
		global $CS_Transcended;
		if (isset($this->classState[$CS_Transcended])) $this->classState[$CS_Transcended] = $value;
	}

	function SetNumCrouchingTigerCreatedThisTurn($value) {
		global $CS_NumCrouchingTigerCreatedThisTurn;
		if (isset($this->classState[$CS_NumCrouchingTigerCreatedThisTurn])) $this->classState[$CS_NumCrouchingTigerCreatedThisTurn] = $value;
	}

	function SetNumBlueDefended($value) {
		global $CS_NumBlueDefended;
		if (isset($this->classState[$CS_NumBlueDefended])) $this->classState[$CS_NumBlueDefended] = $value;
	}

	function SetNumLightningPlayed($value) {
		global $CS_NumLightningPlayed;
		if (isset($this->classState[$CS_NumLightningPlayed])) $this->classState[$CS_NumLightningPlayed] = $value;
	}

	function SetNumInstantPlayed($value) {
		global $CS_NumInstantPlayed;
		if (isset($this->classState[$CS_NumInstantPlayed])) $this->classState[$CS_NumInstantPlayed] = $value;
	}

	function SetActionsPlayed($value) {
		global $CS_ActionsPlayed;
		if (isset($this->classState[$CS_ActionsPlayed])) $this->classState[$CS_ActionsPlayed] = $value;
	}

	function SetNumEarthBanished($value) {
		global $CS_NumEarthBanished;
		if (isset($this->classState[$CS_NumEarthBanished])) $this->classState[$CS_NumEarthBanished] = $value;
	}

	function SetHealthGained($value) {
		global $CS_HealthGained;
		if (isset($this->classState[$CS_HealthGained])) $this->classState[$CS_HealthGained] = $value;
	}

	function SetSkipAllRunechants($value) {
		global $CS_SkipAllRunechants;
		if (isset($this->classState[$CS_SkipAllRunechants])) $this->classState[$CS_SkipAllRunechants] = $value;
	}

	function SetFealtyCreated($value) {
		global $CS_FealtyCreated;
		if (isset($this->classState[$CS_FealtyCreated])) $this->classState[$CS_FealtyCreated] = $value;
	}

	function SetNumDraconicPlayed($value) {
		global $CS_NumDraconicPlayed;
		if (isset($this->classState[$CS_NumDraconicPlayed])) $this->classState[$CS_NumDraconicPlayed] = $value;
	}

	function SetNumSeismicSurgeDestroyed($value) {
		global $CS_NumSeismicSurgeDestroyed;
		if (isset($this->classState[$CS_NumSeismicSurgeDestroyed])) $this->classState[$CS_NumSeismicSurgeDestroyed] = $value;
	}

	function SetPowDamageDealt($value) {
		global $CS_PowDamageDealt;
		if (isset($this->classState[$CS_PowDamageDealt])) $this->classState[$CS_PowDamageDealt] = $value;
	}

	function SetTunicTicks($value) {
		global $CS_TunicTicks;
		if (isset($this->classState[$CS_TunicTicks])) $this->classState[$CS_TunicTicks] = $value;
	}

	function SetOriginalHero($value) {
		global $CS_OriginalHero;
		if (isset($this->classState[$CS_OriginalHero])) $this->classState[$CS_OriginalHero] = $value;
	}

	function SetNumTimesAttacked($value) {
		global $CS_NumTimesAttacked;
		if (isset($this->classState[$CS_NumTimesAttacked])) $this->classState[$CS_NumTimesAttacked] = $value;
	}

	function SetDamageDealtToOpponent($value) {
		global $CS_DamageDealtToOpponent;
		if (isset($this->classState[$CS_DamageDealtToOpponent])) $this->classState[$CS_DamageDealtToOpponent] = $value;
	}

	function SetNumStealthAttacks($value) {
		global $CS_NumStealthAttacks;
		if (isset($this->classState[$CS_NumStealthAttacks])) $this->classState[$CS_NumStealthAttacks] = $value;
	}

	function SetNumWateryGrave($value) {
		global $CS_NumWateryGrave;
		if (isset($this->classState[$CS_NumWateryGrave])) $this->classState[$CS_NumWateryGrave] = $value;
	}

	function SetNumCannonsActivated($value) {
		global $CS_NumCannonsActivated;
		if (isset($this->classState[$CS_NumCannonsActivated])) $this->classState[$CS_NumCannonsActivated] = $value;
	}

	function SetNumGoldCreated($value) {
		global $CS_NumGoldCreated;
		if (isset($this->classState[$CS_NumGoldCreated])) $this->classState[$CS_NumGoldCreated] = $value;
	}

	function SetNumAllyPutInGraveyard($value) {
		global $CS_NumAllyPutInGraveyard;
		if (isset($this->classState[$CS_NumAllyPutInGraveyard])) $this->classState[$CS_NumAllyPutInGraveyard] = $value;
	}

	function SetPlayedNimblism($value) {
		global $CS_PlayedNimblism;
		if (isset($this->classState[$CS_PlayedNimblism])) $this->classState[$CS_PlayedNimblism] = $value;
	}

	function SetNumAttackCardsAttacked($value) {
		global $CS_NumAttackCardsAttacked;
		if (isset($this->classState[$CS_NumAttackCardsAttacked])) $this->classState[$CS_NumAttackCardsAttacked] = $value;
	}

	function SetNumAttackCardsBlocked($value) {
		global $CS_NumAttackCardsBlocked;
		if (isset($this->classState[$CS_NumAttackCardsBlocked])) $this->classState[$CS_NumAttackCardsBlocked] = $value;
	}

	function SetCheeredThisTurn($value) {
		global $CS_CheeredThisTurn;
		if (isset($this->classState[$CS_CheeredThisTurn])) $this->classState[$CS_CheeredThisTurn] = $value;
	}

	function SetBooedThisTurn($value) {
		global $CS_BooedThisTurn;
		if (isset($this->classState[$CS_BooedThisTurn])) $this->classState[$CS_BooedThisTurn] = $value;
	}

	function SetSuspensePoppedThisTurn($value) {
		global $CS_SuspensePoppedThisTurn;
		if (isset($this->classState[$CS_SuspensePoppedThisTurn])) $this->classState[$CS_SuspensePoppedThisTurn] = $value;
	}

	function SetSeismicSurgesCreated($value) {
		global $CS_SeismicSurgesCreated;
		if (isset($this->classState[$CS_SeismicSurgesCreated])) $this->classState[$CS_SeismicSurgesCreated] = $value;
	}

	function SetCardsInDeckBeforeOpt($value) {
		global $CS_CardsInDeckBeforeOpt;
		if (isset($this->classState[$CS_CardsInDeckBeforeOpt])) $this->classState[$CS_CardsInDeckBeforeOpt] = $value;
	}

	function SetNumToughnessDestroyed($value) {
		global $CS_NumToughnessDestroyed;
		if (isset($this->classState[$CS_NumToughnessDestroyed])) $this->classState[$CS_NumToughnessDestroyed] = $value;
	}

	function SetNumConfidenceDestroyed($value) {
		global $CS_NumConfidenceDestroyed;
		if (isset($this->classState[$CS_NumConfidenceDestroyed])) $this->classState[$CS_NumConfidenceDestroyed] = $value;
	}

	function SetNumCostedCardsPlayed($value) {
		global $CS_NumCostedCardsPlayed;
		if (isset($this->classState[$CS_NumCostedCardsPlayed])) $this->classState[$CS_NumCostedCardsPlayed] = $value;
	}

	function SetHitCounter($value) {
		global $CS_HitCounter;
		if (isset($this->classState[$CS_HitCounter])) $this->classState[$CS_HitCounter] = $value;
	}
}