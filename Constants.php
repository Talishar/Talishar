<?php

  $characterPieces = 5;

  function CharacterPieces()
  {
    return 5;
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
    return 2;
  }

  //Class State (one for each player)
  $CS_Num6PowDisc = 0;
  $CS_NumBoosted = 1;
  $CS_AtksWWeapon = 2;
  $CS_HitsWDawnblade = 3;
  $CS_DamagePrevention = 4;

  //Combat Chain State (State for the current combat chain)
  $CCS_CurrentAttackGainedGoAgain = 0;
  $CCS_WeaponIndex = 1;
  $CCS_LastAttack = 2;
  $CCS_NumHits = 3;
  $CCS_DamageDealt = 4;
  $CCS_HitsInRow = 5;
  $CCS_HitsWithWeapon = 6;

  function ResetCombatChainState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = 0;
    $combatChainState[$CCS_LastAttack] = "NA";
    $combatChainState[$CCS_NumHits] = 0;
    $combatChainState[$CCS_DamageDealt] = 0;
    $combatChainState[$CCS_HitsInRow] = 0;
  }

  function ResetChainLinkState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_DamageDealt;
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = 0;
    $combatChainState[$CCS_DamageDealt] = 0;
  }

  function ResetMainClassState()
  {
    global $mainClassState, $CS_Num6PowDisc, $CS_NumBoosted, $CS_AtksWWeapon, $CS_HitsWDawnblade, $CCS_HitsWithWeapon;
    $mainClassState[$CS_Num6PowDisc] = 0;
    $mainClassState[$CS_NumBoosted] = 0;
    $mainClassState[$CS_AtksWWeapon] = 0;
    $mainClassState[$CS_HitsWDawnblade] = 0;
    $combatChainState[$CCS_HitsWithWeapon] = 0;
  }

  function ResetCharacterEffects()
  {
    global $mainCharacterEffects, $defCharacterEffects;
    $mainCharacterEffects = [];
    $defCharacterEffects = [];
  }

?>

