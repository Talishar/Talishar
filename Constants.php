<?php

include_once __DIR__ . '/Libraries/FormatCodes.php';

$Card_CourageBanner = "banneret_of_courage_yellow";
$Card_QuickenBanner = "banneret_of_gallantry_yellow";
$Card_SpellbaneBanner = "banneret_of_protection_yellow";
$Card_BlockBanner = "banneret_of_resilience_yellow";
$Card_LifeBanner = "banneret_of_salvation_yellow";
$Card_ResourceBanner = "banneret_of_vigor_yellow";
$CardFlurryBanner = "banneret_of_swordsmanship_yellow";

$GameStatus_Over = 2;
$GameStatus_Rematch = 3;
$GameStatus_SwapRematch = 4;

// Multi-level undo configuration - Maximum number of undo backups to maintain
define("MAX_UNDO_BACKUPS", 10);

// Game format codes
define("FORMAT_SEALED", FormatCode("sealed"));
define("FORMAT_DRAFT", FormatCode("draft"));
define("FORMAT_OPEN", FormatCode("open"));

function DeckPieces()
{
  return 1;
}

function HandPieces()
{
  return 1;
}

//0 - Card ID
//1 - Unique ID
//2 - Mods "DOWN" = Face Down
function DiscardPieces()
{
  return 3;
}

//0 - Card ID
//1 - Status (2=ready, 1=unavailable, 0=destroyed, 3=Sleeping (Sleep Dart, Crush Confidance, etc)), 4=Dishonored
//2 - Num counters
//3 - Num power counters
//4 - Num defense counters
//5 - Num uses
//6 - On chain (1 = yes, 0 = no)
//7 - Flagged for destruction (1 = yes, 0 = no)
//8 - Frozen (1 = yes, 0 = no)
//9 - Is Active (2 = always active, 1 = yes, 0 = no)
//10 - Subcards , delimited
//11 - Unique ID
//12 - Face up/down
//13 - Marked (1 = yes, 0 = no)
//14 - Tapped (1 = yes, 0 = no)
//15 - Slot
function CharacterPieces()
{
  return 16;
}

//0 - Card ID
//1 - Mods (INT == Intimidated) or "DOWN" == Face Down
//2 - Unique ID?
function BanishPieces()
{
  return 3;
}

//0 - Card ID
//1 - Player
//2 - From
//3 - Resources Paid
//4 - Reprise Active? (Or other class effects?)
//5 - Power Modifier
//6 - Defense Modifier
//7 - Combat Chain Unique ID
//8 - Origin Unique ID
//9 - Original Card ID (for if the card becomes a copy of another card)
//10 - Added static buff effects
//11 - Number of times used
function CombatChainPieces()
{
  return 12;
}

//0 - Card ID
//1 - Status (2=ready, 1=unavailable, 0=destroyed)
//2 - Num counters
//3 - Num power counters
//4 - Is Token (1 = yes, 0 = no)
//5 - Number of ability uses (triggered or activated)
//6 - Unique ID
//7 - My Hold priority for triggers (2 = always hold, 1 = hold, 0 = don't hold)
//8 - Opponent Hold priority for triggers (2 = always hold, 1 = hold, 0 = don't hold)
//9 - Where it's played from
//10 - Modalities (eg. blessing of themis)
//11 - Frozen (1 = yes, 0 = no)
//12 - Tapped (1 = yes, 0 = no)
//13 - Holo Counters
//14 - Bound to
function AuraPieces()
{
  return 15;
}

//0 - Item ID
//1 - Counters/Steam Counters
//2 - Status (2=ready, 1=unavailable, 0=destroyed)
//3 - Num Uses
//4 - Unique ID
//5 - My Hold priority for triggers (2 = always hold, 1 = hold, 0 = don't hold)
//6 - Opponent Hold priority for triggers (2 = always hold, 1 = hold, 0 = don't hold)
//7 - Frozen (1 = yes, 0 = no)
//8 - Modalities (eg. Micro-Processor)
//9 - Where it's played from
//10 - Tapped (0 = no, 1 = yes)
//11 - Subcards (, delimited)
//12 - Num defense counters
//13 - On Chain (1 = yes, 0 = no)
function ItemPieces()
{
  return 14;
}

//0 - Card ID
//1 - Unique ID
function PitchPieces()
{
  return 2;
}

//0 - Effect ID
//1 - Player ID
//2 - Applies to Unique ID
//3 - Number of uses remaining
function CurrentTurnEffectsPieces()
{
  return 4;
}

//0 - Effect ID
//1 - Player ID
//2 - Applies to Unique ID
//3 - Number of uses remaining
//4 - Number of turns before it takes effect
function NextTurnEffectsPieces()
{
  return 5;
}

//0 - Effect ID
//1 - Player ID
//2 - Applies to Unique ID
//3 - Number of uses remaining
//4 - Number of turns before it takes effect
function NextTurnPieces()
{
  return 5;
}

//0 - ?
//1 - Effect Card ID
function CharacterEffectPieces()
{
  return 2;
}

//0 - Card ID
//1 - Face up/down
//2 - ?
//3 - Counters
//4 - Frozen: 0 = no, 1 = yes
//5 - Unique ID
//6 - Num power counters
function ArsenalPieces()
{
  return 7;
}

//0 - Card ID
//1 - Status: 2 = ready
//2 - Life
//3 - Frozen - 0 = no, 1 = yes
//4 - Subcards , delimited
//5 - Unique ID
//6 - Endurance Counters
//7 - Life Counters
//8 - Ability/effect Uses
//9 - Power Counters
//10 - Dealt Damage to opposing hero
//11 - Tapped (0 = no, 1 = yes)
//12 - Steam Counters
//13 - Where it's played from
//14 - Modifier - e.g "Temporary" for cards that get stolen for a turn.
function AllyPieces()
{
  return 15;
}

//0 - Card ID
//1 - Where it's played from
//2 - Subcards , delimited
//3 - UniqueID
function PermanentPieces()
{
  return 4;
}

//0 - Card ID/Layer type
//1 - Player
//2 - Parameter (For play card | Delimited, piece 0 = $from)
//3 - Target
//4 - Additional Costs
//5 - Unique ID (the unique ID of the object that created the layer)
//6 - Layer Unique ID (the unique ID of the layer)
function LayerPieces()
{
  return 7;
}

//0 - Card ID/Layer type
//1 - Player
//2 - Parameter (For play card | Delimited, piece 0 = $from)
//3 - Target
//4 - Additional Costs
//5 - Unique ID (the unique ID of the object that created the layer)
//6 - Layer Unique ID (the unique ID of the layer)
//7 - queued buffs
function AttackQueuePieces()
{
  return 8;
}

//0 - Card ID
//1 - Player
//2 - Where it's played from
//3 - counters
function LandmarkPieces()
{
  return 3;
}

//0 - Card ID
function InventoryPieces()
{
  return 1;
}

//0 - Card ID
//1 - Player ID
//2 - Still on chain? 1 = yes, 0 = no
//3 - From
//4 - Power Modifier
//5 - Defense Modifier
//6 - Added On-hits (comma separated)
//7 - Original Card ID (in case of copies)
//8 - Origin Unique ID
//9 - Number of times used
function ChainLinksPieces()
{
  return 10;
}

//0 - Damage Dealt
//1 - Total Attack
//2 - Talents
//3 - Class
//4 - List of names
//5 - Hit on link
//6 - Modified Base Attack Stats - e.g. Transmogrify
//7 - Modal Play Ability - e.g. Enlightened Strike
//8 - Colors
//9 - Resolved per-card combat values (comma separated, aligned to the link's card order:
//    attackers = final attack, blockers = final block). Snapshotted at resolution because
//    recomputing after the fact drifts (attackers read their base, blockers read 0).
function ChainLinkSummaryPieces()
{
  return 10;
}

//0 - Card ID
//1 - PlayerId
//2 - ??
function DecisionQueuePieces()
{
  return 5;
}

//0 - Card ID
function SoulPieces()
{
  return 1;
}

//0 - Event type
//1 - Event Value
function EventPieces()
{
  return 2;
}

$SHMOP_CURRENTPLAYER = 9;
$SHMOP_ISREPLAY = 10;//0 = not replay, 1 = replay

//Class State (one for each player, tracking what has happened during the current turn)
//To add a class state, add it to the end of this list with its starting value, then use it with
//GetClassState($player, "Name") or the matching $CS_Name variable. The values are saved to the gamestate
//in this order, so only ever add to the end. Each is reset to its starting value at the start of a turn
//unless it's listed in $persistentClassStates.
$classStateDefaults = [
  "Num6PowDisc" => 0,
  "NumBoosted" => 0,
  "AttacksWithWeapon" => 0,
  "HitsWDawnblade" => 0,
  "DamagePrevention" => 0,                       // Deprecated
  "CardsBanished" => 0,
  "DamageTaken" => 0,
  "NumActionsPlayed" => 0,
  "ArsenalFacing" => "DOWN",
  "CharacterIndex" => 0,
  "PlayIndex" => -1,
  "NumNonAttackCards" => 0,
  "NumCardsDrawn" => 0,
  "NumAddedToSoul" => 0,
  "NextNAACardGoAgain" => 0,
  "NumCharged" => 0,
  "Num6PowBan" => 0,                             // used to track which player puts a 6 into banish
  "ResolvingLayerUniqueID" => -1,
  "NextWizardNAAInstant" => 0,
  "ArcaneDamageTaken" => 0,
  "NextNAAInstant" => 0,
  "NextDamagePrevented" => 0,
  "LastAttack" => "NA",
  "NumFusedEarth" => 0,
  "NumFusedIce" => 0,
  "NumFusedLightning" => 0,
  "PitchedForThisCard" => "-",
  "PlayCCIndex" => -1,
  "NumAttackCards" => 0,                         // Played or blocked
  "NumPlayedFromBanish" => 0,
  "NumAttacks" => 0,
  "DieRoll" => 0,
  "NumBloodDebtPlayed" => 0,
  "NumWizardNonAttack" => 0,
  "LayerTarget" => "-",
  "NumSwordAttacks" => 0,
  "HitsWithWeapon" => 0,
  "ArcaneDamagePrevention" => 0,
  "DynCostResolved" => 0,
  "CardsEnteredGY" => 0,
  "HighestRoll" => 0,
  "NumYellowPutSoul" => 0,
  "NumAuras" => 0,                               // Number of auras played or created this turn
  "AbilityIndex" => "-",
  "AdditionalCosts" => "-",
  "NumRedPlayed" => 0,
  "PlayUniqueID" => -1,
  "NumPhantasmAADestroyed" => 0,
  "NumLess3PowAAPlayed" => 0,
  "AlluvionUsed" => 0,
  "MaxQuellUsed" => 0,
  "DamageDealt" => 0,                            // Only includes damage dealt by the hero to Allies + Hero. CR 2.1 8.2.8f If an ally deals damage, the controlling player and their hero are not considered to have dealt damage.
  "ArcaneTargetsSelected" => "-",
  "NumDragonAttacks" => 0,
  "NumIllusionistAttacks" => 0,
  "LastDynCost" => 0,
  "NumIllusionistActionCardAttacks" => 0,
  "ArcaneDamageDealt" => 0,
  "LayerPlayIndex" => -1,
  "NumCardsPlayed" => 0,                         // Amulet of Ignition
  "NamesOfCardsPlayed" => "-",                   // Amulet of Echoes
  "NumBoostPlayed" => 0,                         // Hanabi Blaster
  "PlayedAsInstant" => 0,                        // If the card was played as an instant -- some things like banish we lose memory of as soon as it is removed from the zone
  "AnotherWeaponGainedGoAgain" => "-",
  "NumContractsCompleted" => 0,
  "HitsWithSword" => 0,
  "HealthLost" => 0,
  "NumCranked" => 0,
  "NumItemsDestroyed" => 0,
  "NumCrouchingTigerPlayedThisTurn" => 0,
  "NumClashesWon" => 0,
  "NumVigorDestroyed" => 0,
  "NumMightDestroyed" => 0,
  "NumAgilityDestroyed" => 0,
  "HaveIntimidated" => 0,
  "ModalAbilityChoosen" => "-",
  "NumSpectralShieldAttacks" => 0,
  "NumBluePlayed" => 0,
  "Transcended" => 0,
  "NumCrouchingTigerCreatedThisTurn" => 0,
  "NumBlueDefended" => 0,
  "NumLightningPlayed" => 0,
  "NumInstantPlayed" => 0,
  "ActionsPlayed" => "-",
  "NumEarthBanished" => 0,
  "HealthGained" => 0,
  "SkipAllRunechants" => 0,
  "FealtyCreated" => 0,
  "NumDraconicPlayed" => 0,
  "NumSeismicSurgeDestroyed" => 0,
  "PowDamageDealt" => 0,
  "TunicTicks" => 0,
  "OriginalHero" => "-",
  "NumTimesAttacked" => 0,                       // number of attacks that reached the attack step, distinct from $CS_NumAttacks
  "DamageDealtToOpponent" => 0,                  // Damage dealt specifically to the opposing hero (for anaphylactic shock)
  "NumStealthAttacks" => 0,                      // for slippy
  "NumWateryGrave" => 0,
  "NumCannonsActivated" => 0,
  "NumGoldCreated" => 0,                         // for gold token creation/stolen
  "NumAllyPutInGraveyard" => 0,
  "PlayedNimblism" => 0,
  "NumAttackCardsAttacked" => 0,
  "NumAttackCardsBlocked" => 0,
  "CheeredThisTurn" => 0,
  "BooedThisTurn" => 0,
  "SuspensePoppedThisTurn" => 0,
  "SeismicSurgesCreated" => 0,
  "CardsInDeckBeforeOpt" => "-",                 // to be set as a player starts opting, used to validate the result of the opt
  "NumToughnessDestroyed" => 0,
  "NumConfidenceDestroyed" => 0,
  "NumCostedCardsPlayed" => 0,                   // number of cards that cost more than 0 played
  "HitCounter" => 0,
  "CreatedCardsThisTurn" => 0,
  "ArcaneDamageDealtToOpponent" => 0,
  "EvosBoosted" => 0,
  "NumWeaponsActivated" => 0,
  "NumLightningFlowDestroyed" => 0,
  "HoloAurasEntered" => 0,
  "NumInstantsPutInGrave" => 0,
  "NumControlledAurasDestroyed" => 0,
  "NumFragmented" => 0,
  "WeaponsAttackedWith" => "-",
  "PendingNAACard" => "-",                       // Card ID of a NAA queued to auto-play after chain closes (or "-" if none)
  "HaveIntimidatedOpponent" => 0,
  "LayerResolved" => 0,
  "PreventionCache" => 0,
  "NumUndoesThisTurn" => 0,
  "NumRunechantsCreated" => 0,
  "NumBloodDebtAttacksPlayed" => 0,
  "IARGatesMadeorUsed" => 0,
  "NumBloodDebtBanished" => 0,
  "UsurpedThisTurn" => 0,
  "GuardianAACThisTurn" => 0,
  "ReveredAACThisTurn" => 0,
  "HeaveEligibleAtEndPhase" => "-",              // Heave card IDs in hand at the beginning of the end phase
  "PlayedFromGateUID" => "-",                    // Unique ID of the banished card most recently played by using a Gate to i'Arathael
  "NumTimesHeroAttacked" => 0,                   // number of attacks this turn that targeted this player's hero, distinct from $CS_NumTimesAttacked
  "NumLightningFlowsIDestroyed" => 0,            // number of Lightning Flow tokens this player destroyed this turn, whoever controlled them, distinct from $CS_NumLightningFlowDestroyed
  "DeferredLeyLineUIDs" => "-",                  // Ley Line triggers waiting for the concealed end-phase Heave choice
  "Num6PowPutIntoBanish" => 0,                   // used for Levia
  "KorshemConditionMet" => 0,                    // a hero gained resources/health from an effect, or a controlled object gained power/defense this turn
  "RunechantPreventPlan" => 0,
];
$persistentClassStates = ["ArsenalFacing", "OriginalHero"];
$classStateNames = array_keys($classStateDefaults);
foreach ($classStateDefaults as $classStateName => $classStateDefault) ${"CS_" . $classStateName} = $classStateName;
unset($classStateName, $classStateDefault);

//Combat Chain State (State for the current combat chain)
$CCS_CurrentAttackGainedGoAgain = 0;
$CCS_WeaponIndex = 1;
$CCS_HasAimCounter = 2;
$CCS_AttackNumCharged = 3;
$CCS_DamageDealt = 4;
$CCS_WasRuneGate = 5;
$CCS_HitsWithWeapon = 6;
$CCS_GoesWhereAfterLinkResolves = 7;
$CCS_AttackPlayedFrom = 8;
$CCS_WagersThisLink = 9;
$CCS_ChainLinkHitEffectsPrevented = 10;
$CCS_NumBoosted = 11;
$CCS_NextBoostBuff = 12;//Deprecated -- use $CCS_IsBoosted now.
$CCS_AttackFused = 13;
$CCS_AttackTotalDamage = 14;//Deprecated -- use chain link summary instead, it has all of them
$CCS_NumChainLinks = 15;//Deprecated -- use NumChainLinks() instead
$CCS_AttackTarget = 16;
$CCS_LinkTotalPower = 17;
$CCS_LinkBasePower = 18;//Deprecated -- use LinkBasePower() instead
$CCS_BaseAttackDefenseMax = 19;
$CCS_ResourceCostDefenseMin = 20;
$CCS_CardTypeDefenseRequirement = 21;
$CCS_CachedTotalPower = 22;
$CCS_CachedTotalBlock = 23;
$CCS_CombatDamageReplaced = 24; //CR 6.5.3, CR 6.5.4 (CR 2.0)
$CCS_AttackUniqueID = 25;
$CCS_RequiredEquipmentBlock = 26;
$CCS_CachedDominateActive = 27;
$CCS_CachedNumBlockedFromHand = 28; //Deprecated by 6/22/23 Rules Bulletin
$CCS_IsBoosted = 29;
$CCS_AttackTargetUID = 30;
$CCS_CachedOverpowerActive = 31;
$CCS_CachedNumActionBlocked = 32;
$CCS_CachedNumDefendedFromHand = 33;
$CCS_HitThisLink = 34;
$CCS_WagersThisLinkDupe = 35; //somehow duplicated?
$CCS_PhantasmThisLink = 36;
$CCS_RequiredNegCounterEquipmentBlock = 37;
$CCS_NumInstantsPlayedByAttackingPlayer = 38;
$CCS_NextInstantBouncesAura = 39; //deprecated
$CCS_EclecticMag = 40;
$CCS_FlickedDamage = 41;
$CCS_NumUsedInReactions = 42;
$CCS_NumReactionPlayedActivated = 43; //Number of reactions played or activated
$CCS_NumCardsBlocking = 44; //used to track when cards "defend together"
$CCS_NumPowerCounters = 45;
$CCS_SoulBanishedThisChain = 46;
$CCS_AttackCost = 47; // the base cost of the attack (necessary for X cost attacks)
$CCS_CachedGoAgain = 48; // cached result of DoesAttackHaveGoAgain() set before EvaluateCombatChain
$CCS_AttackDamageDealtToHero = 49; // how much damage the active attack has dealt (including arcane pings)
$CCS_NumInstantsPlayedByDefendingPlayer = 50;
$CCS_CachedPreBlockValue = 51;
$CCS_DefenseReactionsPlayed = 52;
$CCS_AttackReactionsPlayed = 53;

//Deprecated
//$CCS_ChainAttackBuff -- Use persistent combat effect with RemoveEffectsFromCombatChain instead

function ResetChainLinkCombatChainState()
{
  global $CCS_CurrentAttackGainedGoAgain, $CCS_CachedDominateActive, $CCS_WeaponIndex, $CCS_HasAimCounter, $CCS_AttackNumCharged, $CCS_DamageDealt;
  global $CCS_WasRuneGate, $CCS_GoesWhereAfterLinkResolves, $CCS_AttackPlayedFrom, $CCS_WagersThisLink, $CCS_ChainLinkHitEffectsPrevented, $CCS_AttackFused;
  global $CCS_AttackTotalDamage, $CCS_AttackTarget, $CCS_LinkTotalPower, $CCS_LinkBasePower, $CCS_BaseAttackDefenseMax, $CCS_ResourceCostDefenseMin;
  global $CCS_CardTypeDefenseRequirement, $CCS_CachedTotalPower, $CCS_CachedTotalBlock, $CCS_CombatDamageReplaced, $CCS_AttackUniqueID, $CCS_RequiredEquipmentBlock;
  global $CCS_IsBoosted, $CCS_AttackTargetUID, $CCS_CachedOverpowerActive, $CCS_CachedNumActionBlocked, $CCS_CachedNumDefendedFromHand, $CCS_HitThisLink;
  global $CCS_PhantasmThisLink, $CCS_RequiredNegCounterEquipmentBlock, $CCS_NumInstantsPlayedByAttackingPlayer, $CCS_NumUsedInReactions, $CCS_NumReactionPlayedActivated, $CCS_NumCardsBlocking;
  global $CCS_NumPowerCounters, $CCS_AttackCost, $CCS_CachedGoAgain, $CCS_AttackDamageDealtToHero, $CCS_NumInstantsPlayedByDefendingPlayer, $CCS_CachedPreBlockValue;
  global $CCS_AttackReactionsPlayed, $CCS_DefenseReactionsPlayed;

  SetCombatChainState($CCS_CurrentAttackGainedGoAgain, 0);
  SetCombatChainState($CCS_CachedDominateActive, 0);
  SetCombatChainState($CCS_WeaponIndex, -1);
  SetCombatChainState($CCS_HasAimCounter, 0);
  SetCombatChainState($CCS_AttackNumCharged, 0);
  SetCombatChainState($CCS_DamageDealt, 0);
  SetCombatChainState($CCS_WasRuneGate, 0);
  SetCombatChainState($CCS_GoesWhereAfterLinkResolves, "GY");
  SetCombatChainState($CCS_AttackPlayedFrom, "NA");
  SetCombatChainState($CCS_WagersThisLink, 0);
  SetCombatChainState($CCS_ChainLinkHitEffectsPrevented, 0);
  SetCombatChainState($CCS_AttackFused, 0);
  SetCombatChainState($CCS_AttackTotalDamage, 0);
  SetCombatChainState($CCS_AttackTarget, "NA");
  SetCombatChainState($CCS_LinkTotalPower, 0);
  SetCombatChainState($CCS_LinkBasePower, 0);
  SetCombatChainState($CCS_BaseAttackDefenseMax, -1);
  SetCombatChainState($CCS_ResourceCostDefenseMin, -1);
  SetCombatChainState($CCS_CardTypeDefenseRequirement, "NA");
  SetCombatChainState($CCS_CachedTotalPower, 0);
  SetCombatChainState($CCS_CachedTotalBlock, 0);
  SetCombatChainState($CCS_CombatDamageReplaced, 0);
  SetCombatChainState($CCS_AttackUniqueID, -1);
  SetCombatChainState($CCS_RequiredEquipmentBlock, 0);
  SetCombatChainState($CCS_IsBoosted, 0);
  SetCombatChainState($CCS_AttackTargetUID, "-");
  SetCombatChainState($CCS_CachedOverpowerActive, 0);
  SetCombatChainState($CCS_CachedNumActionBlocked, 0);
  SetCombatChainState($CCS_CachedNumDefendedFromHand, 0);
  SetCombatChainState($CCS_HitThisLink, 0);
  SetCombatChainState($CCS_PhantasmThisLink, 0);
  SetCombatChainState($CCS_RequiredNegCounterEquipmentBlock, 0);
  SetCombatChainState($CCS_NumInstantsPlayedByAttackingPlayer, 0);
  SetCombatChainState($CCS_NumUsedInReactions, 0);
  SetCombatChainState($CCS_NumReactionPlayedActivated, 0);
  SetCombatChainState($CCS_NumCardsBlocking, 0);
  SetCombatChainState($CCS_NumPowerCounters, 0);
  SetCombatChainState($CCS_AttackCost, -1);
  SetCombatChainState($CCS_CachedGoAgain, 0);
  SetCombatChainState($CCS_AttackDamageDealtToHero, 0);
  SetCombatChainState($CCS_NumInstantsPlayedByDefendingPlayer, 0);
  SetCombatChainState($CCS_CachedPreBlockValue, 0);
  SetCombatChainState($CCS_AttackReactionsPlayed, 0);
  SetCombatChainState($CCS_DefenseReactionsPlayed, 0);
}

function ResetCombatChainState()
{
  global $chainLinks, $mainPlayer;
  global $CCS_HitsWithWeapon, $CCS_NumBoosted, $CCS_NextInstantBouncesAura, $CCS_EclecticMag, $CCS_FlickedDamage, $CCS_SoulBanishedThisChain;

  if(count($chainLinks) > 0) WriteLog("The combat chain was closed.");
  ResetChainLinkCombatChainState();
  SetCombatChainState($CCS_HitsWithWeapon, 0);
  SetCombatChainState($CCS_NumBoosted, 0);
  SetCombatChainState($CCS_NextInstantBouncesAura, 0);
  SetCombatChainState($CCS_EclecticMag, 0);
  SetCombatChainState($CCS_FlickedDamage, 0);
  SetCombatChainState($CCS_SoulBanishedThisChain, 0);

  $aGoodCleanFight = false;
  $numChainLinks = count($chainLinks);
  $chainLinkPieces = ChainLinksPieces();
  for($i = 0; $i < $numChainLinks; ++$i) {
    if (!isset($chainLinks[$i])) {
      WriteLog("Something odd happened while closing the chain, please submit a bug report", highlight:true);
      continue;
    }
    if (!is_array($chainLinks[$i])) continue;
    $innerCount = count($chainLinks[$i]);
    for($j = 0; $j < $innerCount; $j += $chainLinkPieces) {
      if($chainLinks[$i][$j + 2] != "1") continue;
      CombatChainCloseAbilities($chainLinks[$i][$j + 1], $chainLinks[$i][$j], $i);
      if ($chainLinks[$i][$j] == "a_good_clean_fight_red" && $chainLinks[$i][$j+1] == $mainPlayer) $aGoodCleanFight = true;
    }
  }
  CombatChainClosedTriggers();
  Await($mainPlayer, "ClearCombatChain", subsequent:false);
  UnsetCombatChainBanish();
  CombatChainClosedCharacterEffects(); //eventually all these effects should move above the combat chain being cleared
  CombatChainClosedItemEffects();
  CombatChainClosedMainCharacterEffects();
  RemoveEffectsFromCombatChain();
  RemoveThisLinkEffects();
  Await($mainPlayer, "CloseCombatChain", subsequent:false, final:true);
}

function AttackReplaced($cardID, $player, $from)
{
  global $combatChainState, $currentTurnEffects, $mainPlayer;
  global $CCS_CurrentAttackGainedGoAgain, $CCS_CachedDominateActive, $CCS_GoesWhereAfterLinkResolves, $CCS_AttackPlayedFrom, $CCS_LinkBasePower, $combatChain;
  global $CS_NumStealthAttacks, $CCS_AttackCost, $CCS_CachedGoAgain, $CCS_AttackDamageDealtToHero;
  SetCombatChainState($CCS_CurrentAttackGainedGoAgain, 0);
  SetCombatChainState($CCS_CachedDominateActive, 0);
  SetCombatChainState($CCS_GoesWhereAfterLinkResolves, "GY");
  SetCombatChainState($CCS_AttackPlayedFrom, "BANISH");//Right now only Uzuri can do this
  SetCombatChainState($CCS_LinkBasePower, 0);
  SetCombatChainState($CCS_AttackCost, -1);
  SetCombatChainState($CCS_CachedGoAgain, 0);
  SetCombatChainState($CCS_AttackDamageDealtToHero, 0);

  if (HasStealth($cardID)) IncrementClassState($player, $CS_NumStealthAttacks);
  $combatChain[0] = $cardID;
  $combatChain[2] = $from;
  $combatChain[5] = 0;//Reset Power Modifiers
  $combatChain[6] = 0;//Reset Defense modifiers
  $combatChain[7] = GetUniqueId($cardID, $player); //new unique id
  $combatChain[9] = $cardID; //new original id
  $combatChain[10] = "-"; // get rid of any layer continuous buffs
  //1.8.10 in the CR
  $currentTurnEffectPieces = CurrentTurnEffectPieces();
  for ($i = count($currentTurnEffects) - $currentTurnEffectPieces; $i >= 0; $i -= $currentTurnEffectPieces) {
    if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && IsLayerContinuousBuff($currentTurnEffects[$i]) && $currentTurnEffects[$i + 1] == $mainPlayer) {
      if ($combatChain[10] == "-") $combatChain[10] = ConvertToSetID($currentTurnEffects[$i]); //saving them as set ids saves space
      else $combatChain[10] .= "," . ConvertToSetID($currentTurnEffects[$i]);
      RemoveCurrentTurnEffect($i);
    }
  }
  CleanUpCombatEffects(true);
}

function ResetChainLinkState()
{
  WriteLog("The chain link was resolved.");
  ResetChainLinkCombatChainState();
  RemoveThisLinkEffects();
}

function ResetMainClassState()
{
  global $mainClassState, $classStateDefaults, $persistentClassStates;
  $mainClassState ??= [];
  $kept = [];
  foreach ($persistentClassStates as $name) {
    if (array_key_exists($name, $mainClassState)) $kept[$name] = $mainClassState[$name];
  }
  $mainClassState = array_replace($mainClassState, $classStateDefaults);
  foreach ($persistentClassStates as $name) {
    if (array_key_exists($name, $kept)) $mainClassState[$name] = $kept[$name];
    else unset($mainClassState[$name]);
  }
}

//The gamestate stores a class state as its values in $classStateDefaults order, separated by spaces
function ClassStateFromString($line)
{
  global $classStateDefaults, $classStateNames;
  $line = trim($line);
  $values = $line == "" ? [] : explode(" ", $line);
  $count = count($values);
  $nameCount = count($classStateNames);
  if ($count == $nameCount) return array_combine($classStateNames, $values);
  if ($count > $nameCount) return array_combine($classStateNames, array_slice($values, 0, $nameCount));
  return array_combine(array_slice($classStateNames, 0, $count), $values) + $classStateDefaults; //Games started before a class state was added won't have it yet
}

function ClassStateToString($classState)
{
  global $classStateDefaults, $classStateNames;
  if (array_keys($classState) === $classStateNames && !in_array(null, $classState, true)) return implode(" ", $classState);
  $values = [];
  foreach ($classStateDefaults as $name => $default) $values[] = $classState[$name] ?? $default;
  return implode(" ", $values);
}

function ResetCardPlayed($cardID, $from="-")
{
  global $currentPlayer, $mainPlayer, $CS_NextWizardNAAInstant, $CS_NextNAAInstant, $combatChainState, $CCS_EclecticMag;
  $type = CardType($cardID);
  $effectRemoved = false;
  if(DelimStringContains($type, "A") && ClassContains($cardID, "WIZARD", $currentPlayer) && (GetResolvedAbilityType($cardID) == "A" || GetResolvedAbilityType($cardID) == "") && GetClassState($currentPlayer, $CS_NextWizardNAAInstant) == 1) {
    SetClassState($currentPlayer, $CS_NextWizardNAAInstant, 0);
    //Section below helps with visualization and removing used effects
    SearchCurrentTurnEffects("storm_striders", $currentPlayer, true);
    SearchCurrentTurnEffects("chain_lightning_yellow", $currentPlayer, true);
    $effectRemoved = true;
  }
  if (SubtypeContains($cardID, "Evo") && GetResolvedAbilityType($cardID, $from, $currentPlayer) != "AA") {
    //Section below helps with visualization and removing used effects
    SearchCurrentTurnEffects("scrap_compactor_red", $currentPlayer, true);
    SearchCurrentTurnEffects("scrap_compactor_yellow", $currentPlayer, true);
    SearchCurrentTurnEffects("scrap_compactor_blue", $currentPlayer, true);
  }
  if(DelimStringContains($type, "A") && (GetResolvedAbilityType($cardID) == "A" || GetResolvedAbilityType($cardID) == "") && GetClassState($currentPlayer, $CS_NextNAAInstant) == 1) {
    SetClassState($currentPlayer, $CS_NextNAAInstant, 0);
    SearchCurrentTurnEffects("tempest_dancers", $currentPlayer, true);
    $effectRemoved = true;
  }
  //You may use this effect on any one non-attack action card this chain link, not just the next non-attack action card you play this chain link.
  $abilityType = GetResolvedAbilityType($cardID, $from);
  if(!$effectRemoved && DelimStringContains($type, "A") && ($abilityType == "A" || $abilityType == "")) {
    if ($mainPlayer == $currentPlayer) {
      SearchCurrentTurnEffects("eclectic_magnetism_red", $mainPlayer, true);
      SetCombatChainState($CCS_EclecticMag, 0);
    }
  }
}

function ResetCharacterEffects()
{
  global $mainCharacterEffects, $defCharacterEffects;
  $mainCharacterEffects = [];
  $defCharacterEffects = [];
}


function GetAttackTarget()
{
  global $combatChainState, $CCS_AttackTarget, $CCS_AttackTargetUID, $defPlayer;
  $uid = GetCombatChainState($CCS_AttackTargetUID);
  $MZTarget = GetCombatChainState($CCS_AttackTarget);
  if ($MZTarget == "NA") return "";
  if (!str_contains($uid, ",")) {
    if($uid == "-") return $MZTarget;
    $mzArr = explode("-", $MZTarget);
    $index = SearchZoneForUniqueID($uid, $defPlayer, $mzArr[0]);
    return $mzArr[0] . "-" . $index . "-" . $uid;
  }
  else {//multiple attack targets
    $uidArr = explode(",", $uid);
    $targetArr = explode(",", $MZTarget);
    $ret = [];
    $numUids = count($uidArr);
    for ($i = 0; $i < $numUids; ++$i) {
      if ($uidArr[$i] == "-") $ret[] = $targetArr[$i];
      else {
        $mzArr = explode("-", $targetArr[$i]);
        $index = SearchZoneForUniqueID($uidArr[$i], $defPlayer, $mzArr[0]);
        $ret[] = "{$mzArr[0]}-{$index}-{$uidArr[$i]}";
      }
    }
    return implode(",", $ret);
  }
}

function GetAttackTargetNames($player)
{
  $targets = GetAttackTarget();
  $ret = [];
  foreach(explode(",", $targets) as $target) {
    $ret[] = CardName(GetMZCard($player, $target));
  }
  return implode("|", $ret);
}

function GetDamagePrevention($player, $damage) 
{
  global $currentTurnEffects, $combatChain, $CombatChain, $ChainLinks, $Stack;
  $preventionLeft = 0;

  $countEffects = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectPieces();  
  for($i = 0; $i < $countEffects; $i += $currentTurnEffectsPieces) {
    if($currentTurnEffects[$i + 1] == $player) {
      $preventionLeft += CurrentTurnEffectDamagePreventionAmount($player, $i, $damage, "COMBAT", $combatChain[0]);
    }
  }

  $auras = &GetAuras($player);
  $countAuras = count($auras);
  $auraPieces = AuraPieces();
  for ($i = 0; $i < $countAuras; $i += $auraPieces) {
    $preventionLeft += AuraDamagePreventionAmount($player, $i, "COMBAT", $damage, check: true);
  }

  $permanents = &GetPermanents($player);
  $countPermanents = count($permanents);
  $permanentPieces = PermanentPieces();
  for ($i = 0; $i < $countPermanents; $i += $permanentPieces) {
    $preventionLeft += PermanentDamagePreventionAmount($player, $i, $damage);
  }

  $allies = &GetAllies($player);
  $countAllies = count($allies);
  $allyPieces = AllyPieces();
  for ($i = 0; $i < $countAllies; $i += $allyPieces) {
    $preventionLeft += WardAmount($allies[$i], $player, $i);
  }

  $character = &GetPlayerCharacter($player);
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if (($character[$i + 12] ?? "DOWN") == "UP") {
      $preventionLeft += WardAmount($character[$i],$player);
      $preventionLeft += CharacterDamagePreventionAmount($player, $i, $damage, true, true);
    }
  }

  $activeLinkCardCount = $CombatChain->NumCardsActiveLink();
  for ($i = 0; $i < $activeLinkCardCount; ++$i) {
    $ChainCard = $CombatChain->Card($i, true);
    if ($player != $ChainCard->PlayerID()) continue;
    $card = GetClass($ChainCard->ID(), $player, "CC", $ChainCard->UniqueID());
    if ($card != "-") $preventionLeft += $card->CombatChainTakeDamageAbility(-1, $ChainCard->Index(), $damage, "COMBAT", true, true);
  }

  $chainLinkCount = $ChainLinks->NumLinks();
  for ($i = 0; $i < $chainLinkCount; ++$i) {
    $Link = $ChainLinks->GetLink($i);
    $linkCardCount = $Link->NumCards();
    for ($j = 0; $j < $linkCardCount; ++$j) {
      $ChainCard = $Link->GetLinkCard($j, true);
      $card = GetClass($ChainCard->ID(), $player, "CC");
      if ($card != "-") $preventionLeft += $card->CombatChainTakeDamageAbility($i, $ChainCard->Index(), $damage, "COMBAT", true, true);
    }
  }

  return $preventionLeft;
}

function AttackPlayedFrom()
{
  global $CCS_AttackPlayedFrom, $combatChainState;
  return GetCombatChainState($CCS_AttackPlayedFrom);
}

function HasAimCounter()
{
  global $combatChainState, $CCS_HasAimCounter;
  return GetCombatChainState($CCS_HasAimCounter);
}

function CombatChainOffset($piece)
{
  switch($piece)
  {
    case "player": return 1;
    default: return 0;
  }
}

$livingLegends = ["chane_bound_by_shadow", "bravo_star_of_the_show", "aurora_shooting_star", "azalea_ace_in_the_hole", "briar_warden_of_thorns", "dash_inventor_extraordinaire", "dromai_ash_artist", "enigma_ledger_of_ancestry",
                  "florian_rotwood_harbinger", "iyslander_stormbind", "kano_dracai_of_aether", "lexi_livewire", "nuu_alluring_desire", "oldhim_grandfather_of_eternity", "prism_sculptor_of_arc_light",
                  "viserai_rune_blood", "zen_tamer_of_purpose", "kayo_armed_and_dangerous", "verdance_thorn_of_the_rose", "prism_awakener_of_sol", "victor_goldmane_high_and_mighty"];

$benched = ["briar", "oldhim", "oscilio", "chane"];

// Cards that can be discarded or used as attack
const WINDUP_STYLE_CARDS = [
  "mighty_windup_red", "mighty_windup_yellow", "mighty_windup_blue",
  "agile_windup_red", "agile_windup_yellow", "agile_windup_blue",
  "vigorous_windup_red", "vigorous_windup_yellow", "vigorous_windup_blue",
  "fruits_of_the_forest_red", "fruits_of_the_forest_yellow", "fruits_of_the_forest_blue",
  "trip_the_light_fantastic_red", "trip_the_light_fantastic_yellow", "trip_the_light_fantastic_blue",
  "under_the_trap_door_blue",
  "reapers_call_red", "reapers_call_yellow", "reapers_call_blue",
  "tip_off_red", "tip_off_yellow", "tip_off_blue",
  "deny_redemption_red", "bam_bam_yellow", "outside_interference_blue",
  "fearless_confrontation_blue",
];

// Cards that can be discarded or used as an action
const ARCANE_ABILITY_ACTION_CARDS = [
  "chorus_of_the_amphitheater_red", "chorus_of_the_amphitheater_yellow", "chorus_of_the_amphitheater_blue",
  "arcane_twining_red", "arcane_twining_yellow", "arcane_twining_blue",
  "photon_splicing_red", "photon_splicing_yellow", "photon_splicing_blue",
  "burn_bare",
];
