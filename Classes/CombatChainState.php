<?php
global $CombatChainState;
$CombatChainState = new CombatChainState();
class CombatChainState {

  // Properties
  private $combatChainState = [];

  // Constructor
  function __construct() {
    global $combatChainState;
    $this->combatChainState = &$combatChainState;
  }

	function GetState($index) {
		return $this->combatChainState[$index];
	}

	function SetState($index, $value) {
		if (isset($this->combatChainState[$index])) $this->combatChainState[$index] = $value;
	}

	function IncState($index, $by) {
		if (isset($this->combatChainState[$index]) && is_numeric($this->combatChainState[$index])) $this->combatChainState[$index] += $by;
	}
	
		function CurrentAttackGainedGoAgain() {
		global $CCS_CurrentAttackGainedGoAgain;
		return $this->combatChainState[$CCS_CurrentAttackGainedGoAgain] ?? 0;
	}

	function SetCurrentAttackGainedGoAgain($value) {
		global $CCS_CurrentAttackGainedGoAgain;
		if (isset($this->combatChainState[$CCS_CurrentAttackGainedGoAgain])) $this->combatChainState[$CCS_CurrentAttackGainedGoAgain] = $value;
	}

	function WeaponIndex() {
		global $CCS_WeaponIndex;
		return $this->combatChainState[$CCS_WeaponIndex] ?? 0;
	}

	function SetWeaponIndex($value) {
		global $CCS_WeaponIndex;
		if (isset($this->combatChainState[$CCS_WeaponIndex])) $this->combatChainState[$CCS_WeaponIndex] = $value;
	}

	function HasAimCounter() {
		global $CCS_HasAimCounter;
		return $this->combatChainState[$CCS_HasAimCounter] ?? 0;
	}

	function SetHasAimCounter($value) {
		global $CCS_HasAimCounter;
		if (isset($this->combatChainState[$CCS_HasAimCounter])) $this->combatChainState[$CCS_HasAimCounter] = $value;
	}

	function AttackNumCharged() {
		global $CCS_AttackNumCharged;
		return $this->combatChainState[$CCS_AttackNumCharged] ?? 0;
	}

	function SetAttackNumCharged($value) {
		global $CCS_AttackNumCharged;
		if (isset($this->combatChainState[$CCS_AttackNumCharged])) $this->combatChainState[$CCS_AttackNumCharged] = $value;
	}

	function DamageDealt() {
		global $CCS_DamageDealt;
		return $this->combatChainState[$CCS_DamageDealt] ?? 0;
	}

	function SetDamageDealt($value) {
		global $CCS_DamageDealt;
		if (isset($this->combatChainState[$CCS_DamageDealt])) $this->combatChainState[$CCS_DamageDealt] = $value;
	}

	function WasRuneGate() {
		global $CCS_WasRuneGate;
		return $this->combatChainState[$CCS_WasRuneGate] ?? 0;
	}

	function SetWasRuneGate($value) {
		global $CCS_WasRuneGate;
		if (isset($this->combatChainState[$CCS_WasRuneGate])) $this->combatChainState[$CCS_WasRuneGate] = $value;
	}

	function HitsWithWeapon() {
		global $CCS_HitsWithWeapon;
		return $this->combatChainState[$CCS_HitsWithWeapon] ?? 0;
	}

	function SetHitsWithWeapon($value) {
		global $CCS_HitsWithWeapon;
		if (isset($this->combatChainState[$CCS_HitsWithWeapon])) $this->combatChainState[$CCS_HitsWithWeapon] = $value;
	}

	function GoesWhereAfterLinkResolves() {
		global $CCS_GoesWhereAfterLinkResolves;
		return $this->combatChainState[$CCS_GoesWhereAfterLinkResolves] ?? 0;
	}

	function SetGoesWhereAfterLinkResolves($value) {
		global $CCS_GoesWhereAfterLinkResolves;
		if (isset($this->combatChainState[$CCS_GoesWhereAfterLinkResolves])) $this->combatChainState[$CCS_GoesWhereAfterLinkResolves] = $value;
	}

	function AttackPlayedFrom() {
		global $CCS_AttackPlayedFrom;
		return $this->combatChainState[$CCS_AttackPlayedFrom] ?? 0;
	}

	function SetAttackPlayedFrom($value) {
		global $CCS_AttackPlayedFrom;
		if (isset($this->combatChainState[$CCS_AttackPlayedFrom])) $this->combatChainState[$CCS_AttackPlayedFrom] = $value;
	}

	function WagersThisLink() {
		global $CCS_WagersThisLink;
		return $this->combatChainState[$CCS_WagersThisLink] ?? 0;
	}

	function SetWagersThisLink($value) {
		global $CCS_WagersThisLink;
		if (isset($this->combatChainState[$CCS_WagersThisLink])) $this->combatChainState[$CCS_WagersThisLink] = $value;
	}

	function ChainLinkHitEffectsPrevented() {
		global $CCS_ChainLinkHitEffectsPrevented;
		return $this->combatChainState[$CCS_ChainLinkHitEffectsPrevented] ?? 0;
	}

	function SetChainLinkHitEffectsPrevented($value) {
		global $CCS_ChainLinkHitEffectsPrevented;
		if (isset($this->combatChainState[$CCS_ChainLinkHitEffectsPrevented])) $this->combatChainState[$CCS_ChainLinkHitEffectsPrevented] = $value;
	}

	function NumBoosted() {
		global $CCS_NumBoosted;
		return $this->combatChainState[$CCS_NumBoosted] ?? 0;
	}

	function SetNumBoosted($value) {
		global $CCS_NumBoosted;
		if (isset($this->combatChainState[$CCS_NumBoosted])) $this->combatChainState[$CCS_NumBoosted] = $value;
	}

	function AttackFused() {
		global $CCS_AttackFused;
		return $this->combatChainState[$CCS_AttackFused] ?? 0;
	}

	function SetAttackFused($value) {
		global $CCS_AttackFused;
		if (isset($this->combatChainState[$CCS_AttackFused])) $this->combatChainState[$CCS_AttackFused] = $value;
	}

	function AttackTarget() {
		global $CCS_AttackTarget;
		return $this->combatChainState[$CCS_AttackTarget] ?? 0;
	}

	function SetAttackTarget($value) {
		global $CCS_AttackTarget;
		if (isset($this->combatChainState[$CCS_AttackTarget])) $this->combatChainState[$CCS_AttackTarget] = $value;
	}

	function LinkTotalPower() {
		global $CCS_LinkTotalPower;
		return $this->combatChainState[$CCS_LinkTotalPower] ?? 0;
	}

	function SetLinkTotalPower($value) {
		global $CCS_LinkTotalPower;
		if (isset($this->combatChainState[$CCS_LinkTotalPower])) $this->combatChainState[$CCS_LinkTotalPower] = $value;
	}

	function BaseAttackDefenseMax() {
		global $CCS_BaseAttackDefenseMax;
		return $this->combatChainState[$CCS_BaseAttackDefenseMax] ?? 0;
	}

	function SetBaseAttackDefenseMax($value) {
		global $CCS_BaseAttackDefenseMax;
		if (isset($this->combatChainState[$CCS_BaseAttackDefenseMax])) $this->combatChainState[$CCS_BaseAttackDefenseMax] = $value;
	}

	function ResourceCostDefenseMin() {
		global $CCS_ResourceCostDefenseMin;
		return $this->combatChainState[$CCS_ResourceCostDefenseMin] ?? 0;
	}

	function SetResourceCostDefenseMin($value) {
		global $CCS_ResourceCostDefenseMin;
		if (isset($this->combatChainState[$CCS_ResourceCostDefenseMin])) $this->combatChainState[$CCS_ResourceCostDefenseMin] = $value;
	}

	function CardTypeDefenseRequirement() {
		global $CCS_CardTypeDefenseRequirement;
		return $this->combatChainState[$CCS_CardTypeDefenseRequirement] ?? 0;
	}

	function SetCardTypeDefenseRequirement($value) {
		global $CCS_CardTypeDefenseRequirement;
		if (isset($this->combatChainState[$CCS_CardTypeDefenseRequirement])) $this->combatChainState[$CCS_CardTypeDefenseRequirement] = $value;
	}

	function CachedTotalPower() {
		global $CCS_CachedTotalPower;
		return $this->combatChainState[$CCS_CachedTotalPower] ?? 0;
	}

	function SetCachedTotalPower($value) {
		global $CCS_CachedTotalPower;
		if (isset($this->combatChainState[$CCS_CachedTotalPower])) $this->combatChainState[$CCS_CachedTotalPower] = $value;
	}

	function CachedTotalBlock() {
		global $CCS_CachedTotalBlock;
		return $this->combatChainState[$CCS_CachedTotalBlock] ?? 0;
	}

	function SetCachedTotalBlock($value) {
		global $CCS_CachedTotalBlock;
		if (isset($this->combatChainState[$CCS_CachedTotalBlock])) $this->combatChainState[$CCS_CachedTotalBlock] = $value;
	}

	function CombatDamageReplaced() {
		global $CCS_CombatDamageReplaced;
		return $this->combatChainState[$CCS_CombatDamageReplaced] ?? 0;
	}

	function SetCombatDamageReplaced($value) {
		global $CCS_CombatDamageReplaced;
		if (isset($this->combatChainState[$CCS_CombatDamageReplaced])) $this->combatChainState[$CCS_CombatDamageReplaced] = $value;
	}

	function AttackUniqueID() {
		global $CCS_AttackUniqueID;
		return $this->combatChainState[$CCS_AttackUniqueID] ?? 0;
	}

	function SetAttackUniqueID($value) {
		global $CCS_AttackUniqueID;
		if (isset($this->combatChainState[$CCS_AttackUniqueID])) $this->combatChainState[$CCS_AttackUniqueID] = $value;
	}

	function RequiredEquipmentBlock() {
		global $CCS_RequiredEquipmentBlock;
		return $this->combatChainState[$CCS_RequiredEquipmentBlock] ?? 0;
	}

	function SetRequiredEquipmentBlock($value) {
		global $CCS_RequiredEquipmentBlock;
		if (isset($this->combatChainState[$CCS_RequiredEquipmentBlock])) $this->combatChainState[$CCS_RequiredEquipmentBlock] = $value;
	}

	function CachedDominateActive() {
		global $CCS_CachedDominateActive;
		return $this->combatChainState[$CCS_CachedDominateActive] ?? 0;
	}

	function SetCachedDominateActive($value) {
		global $CCS_CachedDominateActive;
		if (isset($this->combatChainState[$CCS_CachedDominateActive])) $this->combatChainState[$CCS_CachedDominateActive] = $value;
	}

	function IsBoosted() {
		global $CCS_IsBoosted;
		return $this->combatChainState[$CCS_IsBoosted] ?? 0;
	}

	function SetIsBoosted($value) {
		global $CCS_IsBoosted;
		if (isset($this->combatChainState[$CCS_IsBoosted])) $this->combatChainState[$CCS_IsBoosted] = $value;
	}

	function AttackTargetUID() {
		global $CCS_AttackTargetUID;
		return $this->combatChainState[$CCS_AttackTargetUID] ?? 0;
	}

	function SetAttackTargetUID($value) {
		global $CCS_AttackTargetUID;
		if (isset($this->combatChainState[$CCS_AttackTargetUID])) $this->combatChainState[$CCS_AttackTargetUID] = $value;
	}

	function CachedOverpowerActive() {
		global $CCS_CachedOverpowerActive;
		return $this->combatChainState[$CCS_CachedOverpowerActive] ?? 0;
	}

	function SetCachedOverpowerActive($value) {
		global $CCS_CachedOverpowerActive;
		if (isset($this->combatChainState[$CCS_CachedOverpowerActive])) $this->combatChainState[$CCS_CachedOverpowerActive] = $value;
	}

	function CachedNumActionBlocked() {
		global $CCS_CachedNumActionBlocked;
		return $this->combatChainState[$CCS_CachedNumActionBlocked] ?? 0;
	}

	function SetCachedNumActionBlocked($value) {
		global $CCS_CachedNumActionBlocked;
		if (isset($this->combatChainState[$CCS_CachedNumActionBlocked])) $this->combatChainState[$CCS_CachedNumActionBlocked] = $value;
	}

	function CachedNumDefendedFromHand() {
		global $CCS_CachedNumDefendedFromHand;
		return $this->combatChainState[$CCS_CachedNumDefendedFromHand] ?? 0;
	}

	function SetCachedNumDefendedFromHand($value) {
		global $CCS_CachedNumDefendedFromHand;
		if (isset($this->combatChainState[$CCS_CachedNumDefendedFromHand])) $this->combatChainState[$CCS_CachedNumDefendedFromHand] = $value;
	}

	function HitThisLink() {
		global $CCS_HitThisLink;
		return $this->combatChainState[$CCS_HitThisLink] ?? 0;
	}

	function SetHitThisLink($value) {
		global $CCS_HitThisLink;
		if (isset($this->combatChainState[$CCS_HitThisLink])) $this->combatChainState[$CCS_HitThisLink] = $value;
	}

	function PhantasmThisLink() {
		global $CCS_PhantasmThisLink;
		return $this->combatChainState[$CCS_PhantasmThisLink] ?? 0;
	}

	function SetPhantasmThisLink($value) {
		global $CCS_PhantasmThisLink;
		if (isset($this->combatChainState[$CCS_PhantasmThisLink])) $this->combatChainState[$CCS_PhantasmThisLink] = $value;
	}

	function RequiredNegCounterEquipmentBlock() {
		global $CCS_RequiredNegCounterEquipmentBlock;
		return $this->combatChainState[$CCS_RequiredNegCounterEquipmentBlock] ?? 0;
	}

	function SetRequiredNegCounterEquipmentBlock($value) {
		global $CCS_RequiredNegCounterEquipmentBlock;
		if (isset($this->combatChainState[$CCS_RequiredNegCounterEquipmentBlock])) $this->combatChainState[$CCS_RequiredNegCounterEquipmentBlock] = $value;
	}

	function NumInstantsPlayedByAttackingPlayer() {
		global $CCS_NumInstantsPlayedByAttackingPlayer;
		return $this->combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer] ?? 0;
	}

	function SetNumInstantsPlayedByAttackingPlayer($value) {
		global $CCS_NumInstantsPlayedByAttackingPlayer;
		if (isset($this->combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer])) $this->combatChainState[$CCS_NumInstantsPlayedByAttackingPlayer] = $value;
	}

	function NextInstantBouncesAura() {
		global $CCS_NextInstantBouncesAura;
		return $this->combatChainState[$CCS_NextInstantBouncesAura] ?? 0;
	}

	function SetNextInstantBouncesAura($value) {
		global $CCS_NextInstantBouncesAura;
		if (isset($this->combatChainState[$CCS_NextInstantBouncesAura])) $this->combatChainState[$CCS_NextInstantBouncesAura] = $value;
	}

	function EclecticMag() {
		global $CCS_EclecticMag;
		return $this->combatChainState[$CCS_EclecticMag] ?? 0;
	}

	function SetEclecticMag($value) {
		global $CCS_EclecticMag;
		if (isset($this->combatChainState[$CCS_EclecticMag])) $this->combatChainState[$CCS_EclecticMag] = $value;
	}

	function FlickedDamage() {
		global $CCS_FlickedDamage;
		return $this->combatChainState[$CCS_FlickedDamage] ?? 0;
	}

	function SetFlickedDamage($value) {
		global $CCS_FlickedDamage;
		if (isset($this->combatChainState[$CCS_FlickedDamage])) $this->combatChainState[$CCS_FlickedDamage] = $value;
	}

	function NumUsedInReactions() {
		global $CCS_NumUsedInReactions;
		return $this->combatChainState[$CCS_NumUsedInReactions] ?? 0;
	}

	function SetNumUsedInReactions($value) {
		global $CCS_NumUsedInReactions;
		if (isset($this->combatChainState[$CCS_NumUsedInReactions])) $this->combatChainState[$CCS_NumUsedInReactions] = $value;
	}

	function NumReactionPlayedActivated() {
		global $CCS_NumReactionPlayedActivated;
		return $this->combatChainState[$CCS_NumReactionPlayedActivated] ?? 0;
	}

	function SetNumReactionPlayedActivated($value) {
		global $CCS_NumReactionPlayedActivated;
		if (isset($this->combatChainState[$CCS_NumReactionPlayedActivated])) $this->combatChainState[$CCS_NumReactionPlayedActivated] = $value;
	}

	function NumCardsBlocking() {
		global $CCS_NumCardsBlocking;
		return $this->combatChainState[$CCS_NumCardsBlocking] ?? 0;
	}

	function SetNumCardsBlocking($value) {
		global $CCS_NumCardsBlocking;
		if (isset($this->combatChainState[$CCS_NumCardsBlocking])) $this->combatChainState[$CCS_NumCardsBlocking] = $value;
	}

	function NumPowerCounters() {
		global $CCS_NumPowerCounters;
		return $this->combatChainState[$CCS_NumPowerCounters] ?? 0;
	}

	function SetNumPowerCounters($value) {
		global $CCS_NumPowerCounters;
		if (isset($this->combatChainState[$CCS_NumPowerCounters])) $this->combatChainState[$CCS_NumPowerCounters] = $value;
	}

	function SoulBanishedThisChain() {
		global $CCS_SoulBanishedThisChain;
		return $this->combatChainState[$CCS_SoulBanishedThisChain] ?? 0;
	}

	function SetSoulBanishedThisChain($value) {
		global $CCS_SoulBanishedThisChain;
		if (isset($this->combatChainState[$CCS_SoulBanishedThisChain])) $this->combatChainState[$CCS_SoulBanishedThisChain] = $value;
	}

	function AttackCost() {
		global $CCS_AttackCost;
		return $this->combatChainState[$CCS_AttackCost] ?? 0;
	}

	function SetAttackCost($value) {
		global $CCS_AttackCost;
		if (isset($this->combatChainState[$CCS_AttackCost])) $this->combatChainState[$CCS_AttackCost] = $value;
	}
}