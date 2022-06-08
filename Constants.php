<?php

  $characterPieces = 8;

  function DeckPieces()
  {
    return 1;
  }

  function HandPieces()
  {
    return 1;
  }

  function DiscardPieces()
  {
    return 1;
  }

  //0 = ID
  //1 = Status (2=ready, 1=unavailable, 0=destroyed)
  //2 = Num counters
  //3 = Num attack counters
  //4 = Num defense counters
  //5 = Num uses
  //6 = On chain (1 = yes, 0 = no)
  //7 = Flagged for destruction (1 = yes, 0 = no)
  //8 = Frozen (1 = yes, 0 = no)
  function CharacterPieces()
  {
    return 9;
  }

  function BanishPieces()
  {
    return 2;
  }

  //Card ID
  //Player
  //From
  //Resources Paid
  //Reprise Active? (Or other class effects?)
  //Attack Modifier
  //Defense Modifier
  function CombatChainPieces()
  {
    return 7;
  }

  function AuraPieces()
  {
    return 6;
  }

  //Item ID
  //Counters/Steam Counters
  //Status
  //Num Uses
  function ItemPieces()
  {
    return 4;
  }

  function PitchPieces()
  {
    return 1;
  }

  //Effect ID
  //Player ID
  //Applies to Unique ID
  function CurrentTurnPieces()
  {
    return 4;
  }

  function CharacterEffectPieces()
  {
    return 2;
  }

  function ArsenalPieces()
  {
    return 5;
  }

  //Card ID
  //Status - 2 = ready
  //Health
  //Frozen - 0 = no, 1 = yes
  //Subcards , delimited
  //Unique ID
  function AllyPieces()
  {
    return 6;
  }

  //Card ID
  function PermanentPieces()
  {
    return 1;
  }

  function LayerPieces()
  {
    return 5;
  }

  function LandmarkPieces()
  {
    return 2;
  }

  //Card ID
  //Player ID
  //Still on chain? 1 = yes, 0 = no
  function ChainLinksPieces()
  {
    return 3;
  }

  //Damage Dealt
  //Total Attack
  function ChainLinkSummaryPieces()
  {
    return 2;
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
  $CS_ArcaneDamagePrevention = 37;
  $CS_DynCostResolved = 38;
  $CS_CardsEnteredGY = 39;
  $CS_HighestRoll = 40;
  $CS_EffectContext = 41;
  $CS_NumAuras = 42;
  $CS_AbilityIndex = 43;
  $CS_AdditionalCosts = 44;
  $CS_NumRedPlayed = 45;

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
  $CCS_CachedTotalAttack = 22;
  $CCS_CachedTotalBlock = 23;
  $CCS_CombatDamageReplaced = 24;//CR 6.5.3, CR 6.5.4 (CR 2.0)

  function ResetCombatChainState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves, $CCS_AttackPlayedFrom, $CCS_ChainAttackBuff, $CCS_ChainLinkHitEffectsPrevented;
    global $CCS_NumBoosted, $CCS_NextBoostBuff, $CCS_AttackFused, $CCS_AttackTotalDamage, $CCS_NumChainLinks, $CCS_AttackTarget;
    global $CCS_LinkTotalAttack, $CCS_LinkBaseAttack, $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
    global $CCS_CachedTotalAttack, $CCS_CachedTotalBlock;
    global $defPlayer;
    global $chainLinks, $chainLinkSummary;
    WriteLog("The combat chain was closed.");
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = -1;
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
    $combatChainState[$CCS_CachedTotalAttack] = 0;
    $combatChainState[$CCS_CachedTotalBlock] = 0;
    $defCharacter = &GetPlayerCharacter($defPlayer);
    for($i=0; $i<count($defCharacter); $i+=CharacterPieces())
    {
      $defCharacter[$i+6] = 0;
    }
    for($i=0; $i<count($chainLinks); ++$i)
    {
      for($j=0; $j<count($chainLinks[$i]); $j += ChainLinksPieces())
      {
        if($chainLinks[$i][$j+2] != "1") continue;
        CombatChainCloseAbilities($chainLinks[$i][$j+1], $chainLinks[$i][$j], $i);
        $cardType = CardType($chainLinks[$i][$j]);
        if($cardType != "AA" && $cardType != "DR" && $cardType != "AR" && $cardType != "A") continue;
        if(GoesWhereAfterResolving($chainLinks[$i][$j], "CHAINCLOSING", $chainLinks[$i][$j+1]) == "GY") AddGraveyard($chainLinks[$i][$j], $chainLinks[$i][$j+1], "CC");
      }
    }
    CombatChainClosedCharacterEffects();
    CurrentEffectChainClosedEffects();
    $chainLinks = [];
    $chainLinkSummary = [];
  }

  function ResetChainLinkState()
  {
    global $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_WeaponIndex, $CCS_DamageDealt, $CCS_GoesWhereAfterLinkResolves;
    global $CCS_AttackPlayedFrom, $CCS_ChainLinkHitEffectsPrevented, $CCS_AttackFused, $CCS_AttackTotalDamage, $CCS_AttackTarget;
    global $CCS_LinkTotalAttack, $CCS_LinkBaseAttack, $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin, $CCS_CardTypeDefenseRequirement;
    global $CCS_CachedTotalAttack, $CCS_CachedTotalBlock, $CCS_CombatDamageReplaced;
    WriteLog("The chain link was closed.");
    $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 0;
    $combatChainState[$CCS_WeaponIndex] = -1;
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
    $combatChainState[$CCS_CachedTotalAttack] = 0;
    $combatChainState[$CCS_CachedTotalBlock] = 0;
    $combatChainState[$CCS_CombatDamageReplaced] = 0;
  }

  function ResetMainClassState()
  {
    global $mainClassState, $CS_Num6PowDisc, $CS_NumBoosted, $CS_AtksWWeapon, $CS_HitsWDawnblade, $CS_DamagePrevention, $CS_CardsBanished;
    global $CS_DamageTaken, $CS_NumActionsPlayed, $CS_CharacterIndex, $CS_PlayIndex, $CS_NumNonAttackCards, $CS_NumMoonWishPlayed;
    global $CS_NumAddedToSoul, $CS_NextNAACardGoAgain, $CS_NumCharged, $CS_Num6PowBan, $CS_NextArcaneBonus, $CS_NextWizardNAAInstant;
    global $CS_ArcaneDamageTaken, $CS_NextNAAInstant, $CS_NextDamagePrevented, $CS_LastAttack, $CS_PlayCCIndex;
    global $CS_NumFusedEarth, $CS_NumFusedIce, $CS_NumFusedLightning, $CS_PitchedForThisCard, $CS_NumAttackCards, $CS_NumPlayedFromBanish;
    global $CS_NumAttacks, $CS_DieRoll, $CS_NumBloodDebtPlayed, $CS_NumWizardNonAttack, $CS_LayerTarget, $CS_NumSwordAttacks;
    global $CS_HitsWithWeapon, $CS_ArcaneDamagePrevention, $CS_DynCostResolved, $CS_CardsEnteredGY;
    global $CS_HighestRoll, $CS_EffectContext, $CS_NumAuras, $CS_AbilityIndex, $CS_AdditionalCosts, $CS_NumRedPlayed;
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
    $mainClassState[$CS_ArcaneDamagePrevention] = 0;
    $mainClassState[$CS_DynCostResolved] = 0;
    $mainClassState[$CS_CardsEnteredGY] = 0;
    $mainClassState[$CS_HighestRoll] = 0;
    $mainClassState[$CS_EffectContext] = "-";
    $mainClassState[$CS_NumAuras] = 0;
    $mainClassState[$CS_AbilityIndex] = "-";
    $mainClassState[$CS_AdditionalCosts] = "-";
    $mainClassState[$CS_NumRedPlayed] = 0;
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


  function GetAttackTarget()
  {
    global $combatChainState, $CCS_AttackTarget;
    return $combatChainState[$CCS_AttackTarget];
  }

  function GetDamagePrevention($player)
  {
    global $CS_DamagePrevention;
    return GetClassState($player, $CS_DamagePrevention);
  }

?>
