<?php

  $characterPieces = 6;

  function DeckPieces()
  {
    return 1;
  }

  function HandPieces()
  {
    return 1;
  }

  function CharacterPieces()
  {
    return 6;
  }

  function BanishPieces()
  {
    return 2;
  }

  function CombatChainPieces()
  {
    return 7;
  }

  function AuraPieces()
  {
    return 4;
  }

  function ItemPieces()
  {
    return 3;
  }

  function PitchPieces()
  {
    return 1;
  }

  function CurrentTurnPieces()
  {
    return 2;
  }

  function CharacterEffectPieces()
  {
    return 2;
  }

  function ArsenalPieces()
  {
    return 2;
  }

  function AllyPieces()
  {
    return 3;
  }

  function LayerPieces()
  {
    return 4;
  }

  //Class State (one for each player)
  $CS_Num6PowDisc = 0;
  $CS_NumBoosted = 1;
  $CS_AtksWWeapon = 2;
  $CS_HitsWDawnblade = 3;
  $CS_DamagePrevention = 4;
  $CS_CardsBanished = 5;
  $CS_DamageTaken = 6;
  $CS_NumActionsPlayed = 7;
  $CS_ArsenalFacing = 8;
  $CS_CharacterIndex = 9;
  $CS_PlayIndex = 10;
  $CS_NumNonAttackCards = 11;
  $CS_NumMoonWishPlayed = 12;
  $CS_NumAddedToSoul = 13;
  $CS_NextNAACardGoAgain = 14;
  $CS_NumCharged = 15;
  $CS_Num6PowBan = 16;
  $CS_NextArcaneBonus = 17;
  $CS_NextWizardNAAInstant = 18;
  $CS_ArcaneDamageTaken = 19;
  $CS_NextNAAInstant = 20;
  $CS_NextDamagePrevented = 21;
  $CS_LastAttack = 22;
  $CS_NumFusedEarth = 23;
  $CS_NumFusedIce = 24;
  $CS_NumFusedLightning = 25;
  $CS_PitchedForThisCard = 26;
  $CS_PlayCCIndex = 27;
  $CS_NumAttackCards = 28;//Played or blocked
  $CS_NumPlayedFromBanish = 29;
  $CS_NumAttacks = 30;
  $CS_DieRoll = 31;
  $CS_NumBloodDebtPlayed = 32;
  $CS_NumWizardNonAttack = 33;
  $CS_LayerTarget = 34;
  $CS_NumSwordAttacks = 35;
  $CS_HitsWithWeapon = 36;

  //Combat Chain State (State for the current combat chain)
  $CCS_CurrentAttackGainedGoAgain = 0;
  $CCS_WeaponIndex = 1;
  $CCS_LastAttack = 2;
  $CCS_NumHits = 3;
  $CCS_DamageDealt = 4;
  $CCS_HitsInRow = 5;
  $CCS_HitsWithWeapon = 6;
  $CCS_GoesWhereAfterLinkResolves = 7;
  $CCS_AttackPlayedFrom = 8;
  $CCS_ChainAttackBuff= 9;
  $CCS_ChainLinkHitEffectsPrevented = 10;
  $CCS_NumBoosted = 11;
  $CCS_NextBoostBuff = 12;
  $CCS_AttackFused = 13;
  $CCS_AttackTotalDamage = 14;
  $CCS_NumChainLinks = 15;
  $CCS_AttackTarget = 16;
  $CCS_LinkTotalAttack = 17;
  $CCS_LinkBaseAttack = 18;
  $CCS_BaseAttackDefenseMax = 19;
  $CCS_ResourceCostDefenseMin = 20;
  $CCS_CardTypeDefenseRequirement = 21;

  function ResetCombatChainState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves, $CCS_AttackPlayedFrom, $CCS_ChainAttackBuff, $CCS_ChainLinkHitEffectsPrevented;
    global $CCS_NumBoosted, $CCS_NextBoostBuff, $CCS_AttackFused, $CCS_AttackTotalDamage, $CCS_NumChainLinks, $CCS_AttackTarget;
    global $CCS_LinkTotalAttack, $CCS_LinkBaseAttack, $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = 0;
    $combatChainState[$CCS_LastAttack] = "NA";
    $combatChainState[$CCS_NumHits] = 0;
    $combatChainState[$CCS_DamageDealt] = 0;
    $combatChainState[$CCS_HitsInRow] = 0;
    $combatChainState[$CCS_HitsWithWeapon] = 0;
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "GY";
    $combatChainState[$CCS_AttackPlayedFrom] = "NA";
    $combatChainState[$CCS_ChainAttackBuff] = 0;
    $combatChainState[$CCS_ChainLinkHitEffectsPrevented] = 0;
    $combatChainState[$CCS_NumBoosted] = 0;
    $combatChainState[$CCS_NextBoostBuff] = 0;
    $combatChainState[$CCS_AttackFused] = 0;
    $combatChainState[$CCS_AttackTotalDamage] = 0;
    $combatChainState[$CCS_NumChainLinks] = 0;
    $combatChainState[$CCS_AttackTarget] = "NA";
    $combatChainState[$CCS_LinkTotalAttack] = 0;
    $combatChainState[$CCS_LinkBaseAttack] = 0;
    $combatChainState[$CCS_BaseAttackDefenseMax] = -1;
    $combatChainState[$CCS_ResourceCostDefenseMin] = -1;
    $combatChainState[$CCS_CardTypeDefenseRequirement] = "NA";
  }

  function ResetChainLinkState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_DamageDealt, $CCS_GoesWhereAfterLinkResolves;
    global $CCS_AttackPlayedFrom, $CCS_ChainLinkHitEffectsPrevented, $CCS_AttackFused, $CCS_AttackTotalDamage, $CCS_AttackTarget;
    global $CCS_LinkTotalAttack, $CCS_LinkBaseAttack, $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = 0;
    $combatChainState[$CCS_DamageDealt] = 0;
    $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "GY";
    $combatChainState[$CCS_AttackPlayedFrom] = "NA";
    $combatChainState[$CCS_ChainLinkHitEffectsPrevented] = 0;
    $combatChainState[$CCS_AttackFused] = 0;
    $combatChainState[$CCS_AttackTotalDamage] = 0;
    $combatChainState[$CCS_AttackTarget] = "NA";
    $combatChainState[$CCS_LinkTotalAttack] = 0;
    $combatChainState[$CCS_LinkBaseAttack] = 0;
    $combatChainState[$CCS_BaseAttackDefenseMax] = -1;
    $combatChainState[$CCS_ResourceCostDefenseMin] = -1;
    $combatChainState[$CCS_CardTypeDefenseRequirement] = "NA";
  }

  function ResetMainClassState()
  {
    global $mainClassState, $CS_Num6PowDisc, $CS_NumBoosted, $CS_AtksWWeapon, $CS_HitsWDawnblade, $CS_DamagePrevention, $CS_CardsBanished;
    global $CS_DamageTaken, $CS_NumActionsPlayed, $CS_CharacterIndex, $CS_PlayIndex, $CS_NumNonAttackCards, $CS_NumMoonWishPlayed;
    global $CS_NumAddedToSoul, $CS_NextNAACardGoAgain, $CS_NumCharged, $CS_Num6PowBan, $CS_NextArcaneBonus, $CS_NextWizardNAAInstant;
    global $CS_ArcaneDamageTaken, $CS_NextNAAInstant, $CS_NextDamagePrevented, $CS_LastAttack, $CS_PlayCCIndex;
    global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_PitchedForThisCard, $CS_NumAttackCards, $CS_NumPlayedFromBanish;
    global $CS_NumAttacks, $CS_DieRoll, $CS_NumBloodDebtPlayed, $CS_NumWizardNonAttack, $CS_LayerTarget, $CS_NumSwordAttacks;
    global $CS_HitsWithWeapon;
    $mainClassState[$CS_Num6PowDisc] = 0;
    $mainClassState[$CS_NumBoosted] = 0;
    $mainClassState[$CS_AtksWWeapon] = 0;
    $mainClassState[$CS_HitsWDawnblade] = 0;
    $mainClassState[$CS_DamagePrevention] = 0;
    $mainClassState[$CS_CardsBanished] = 0;
    $mainClassState[$CS_DamageTaken] = 0;
    $mainClassState[$CS_NumActionsPlayed] = 0;
    $mainClassState[$CS_CharacterIndex] = 0;
    $mainClassState[$CS_PlayIndex] = -1;
    $mainClassState[$CS_NumNonAttackCards] = 0;
    $mainClassState[$CS_NumMoonWishPlayed] = 0;
    $mainClassState[$CS_NumAddedToSoul] = 0;
    $mainClassState[$CS_NextNAACardGoAgain] = 0;
    $mainClassState[$CS_NumCharged] = 0;
    $mainClassState[$CS_Num6PowBan] = 0;
    $mainClassState[$CS_NextArcaneBonus] = 0;
    $mainClassState[$CS_NextWizardNAAInstant] = 0;
    $mainClassState[$CS_ArcaneDamageTaken] = 0;
    $mainClassState[$CS_NextNAAInstant] = 0;
    $mainClassState[$CS_NextDamagePrevented] = 0;
    $mainClassState[$CS_LastAttack] = "NA";
    $mainClassState[$CS_NumFusedEarth] = 0;
    $mainClassState[$CS_NumFusedIce] = 0;
    $mainClassState[$CS_NumFusedLightning] = 0;
    $mainClassState[$CS_PitchedForThisCard] = "-";
    $mainClassState[$CS_PlayCCIndex] = -1;
    $mainClassState[$CS_NumAttackCards] = 0;
    $mainClassState[$CS_NumPlayedFromBanish] = 0;
    $mainClassState[$CS_NumAttacks] = 0;
    $mainClassState[$CS_DieRoll] = 0;
    $mainClassState[$CS_NumBloodDebtPlayed] = 0;
    $mainClassState[$CS_NumWizardNonAttack] = 0;
    $mainClassState[$CS_LayerTarget] = "-";
    $mainClassState[$CS_NumSwordAttacks] = 0;
    $mainClassState[$CS_HitsWithWeapon] = 0;
  }

  function ResetCardPlayed($cardID)
  {
    global $currentPlayer, $myClassState, $CS_NextWizardNAAInstant, $CS_NextNAAInstant;
    $type = CardType($cardID);
    $class = CardClass($cardID);
    if($type == "A" && $class == "WIZARD") $myClassState[$CS_NextWizardNAAInstant] = 0;
    if($type == "A") $myClassState[$CS_NextNAAInstant] = 0;
  }

  function ResetCharacterEffects()
  {
    global $mainCharacterEffects, $defCharacterEffects;
    $mainCharacterEffects = [];
    $defCharacterEffects = [];
  }

?>
