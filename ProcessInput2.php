<?php

error_reporting(E_ALL);

include "WriteLog.php";
include "WriteReplay.php";
include "GameLogic.php";
include "GameTerms.php";
include "HostFiles/Redirector.php";
include "Libraries/SHMOPLibraries.php";
include "Libraries/StatFunctions.php";
include "Libraries/UILibraries.php";
include "Libraries/PlayerSettings.php";
include "Libraries/NetworkingLibraries.php";
include "AI/CombatDummy.php";
include "AI/PlayerMacros.php";
include "Libraries/HTTPLibraries.php";
require_once("Libraries/CoreLibraries.php");
include_once "./includes/dbh.inc.php";
include_once "./includes/functions.inc.php";
include_once "APIKeys/APIKeys.php";

//We should always have a player ID as a URL parameter
$gameName = $_GET["gameName"];
if (!IsGameNameValid($gameName)) {
  echo ("Invalid game name.");
  exit;
}
$playerID = $_GET["playerID"];
$authKey = $_GET["authKey"];

//We should also have some information on the type of command
$mode = $_GET["mode"];
$buttonInput = isset($_GET["buttonInput"]) ? $_GET["buttonInput"] : ""; //The player that is the target of the command - e.g. for changing health total
$cardID = isset($_GET["cardID"]) ? $_GET["cardID"] : "";
$chkCount = isset($_GET["chkCount"]) ? $_GET["chkCount"] : 0;
$chkInput = [];
for ($i = 0; $i < $chkCount; ++$i) {
  $chk = isset($_GET[("chk" . $i)]) ? $_GET[("chk" . $i)] : "";
  if ($chk != "") array_push($chkInput, $chk);
}

//Testing only
//WriteLog('http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'] . "&gameName=$gameName&playerID=$playerID&authKey=$authKey&mode=$mode&cardID=$cardID");

//First we need to parse the game state from the file
include "ParseGamestate.php";
$otherPlayer = $currentPlayer == 1 ? 2 : 1;
$skipWriteGamestate = false;
$mainPlayerGamestateStillBuilt = 0;
$makeCheckpoint = 0;
$makeBlockBackup = 0;
$MakeStartTurnBackup = false;
$targetAuth = ($playerID == 1 ? $p1Key : $p2Key);
$conceded = false;
$randomSeeded = false;

if ($playerID != 3 && $authKey != $targetAuth) exit;
if ($playerID == 3 && !IsModeAllowedForSpectators($mode)) ExitProcessInput();
if (!IsModeAsync($mode) && $currentPlayer != $playerID)
{
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  ExitProcessInput();
}

$afterResolveEffects = [];

$animations = [];

if((IsPatron(1) || IsPatron(2)))
{
  $commandFile = fopen("./Games/" . $gameName . "/commandfile.txt", "a");
  fwrite($commandFile, $playerID . " " . $mode . " " . $buttonInput . " " . $cardID . " " . $chkCount . " " . implode("|", $chkInput) . "\r\n");
  fclose($commandFile);
}

//Now we can process the command
ProcessInput($playerID, $mode, $buttonInput, $cardID, $chkCount, $chkInput);

ProcessMacros();
if($inGameStatus == $GameStatus_Rematch)
{
  $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
  $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
  if (file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
  include "MenuFiles/ParseGamefile.php";
  include "MenuFiles/WriteGamefile.php";
  $gameStatus = (IsPlayerAI(2) ? $MGS_ReadyToStart : $MGS_ChooseFirstPlayer);
  $firstPlayer = 1;
  $firstPlayerChooser = ($winner == 1 ? 2 : 1);
  WriteLog("Player $firstPlayerChooser lost and will choose first player for the rematch.");
  WriteGameFile();
  $turn[0] = "REMATCH";
  include "WriteGamestate.php";
  $currentTime = round(microtime(true) * 1000);
  SetCachePiece($gameName, 2, $currentTime);
  SetCachePiece($gameName, 3, $currentTime);
  GamestateUpdated($gameName);
  exit;
}
else if ($winner != 0 && $turn[0] != "YESNO") {
  $inGameStatus = $GameStatus_Over;
  $turn[0] = "OVER";
  $currentPlayer = 1;
}

CombatDummyAI(); //Only does anything if applicable
CacheCombatResult();

if(!IsGameOver())
{
  if($playerID == 1) $p1TotalTime += time() - intval($lastUpdateTime);
  else if($playerID == 2) $p2TotalTime += time() - intval($lastUpdateTime);
  $lastUpdateTime = time();
}

//Now write out the game state
if (!$skipWriteGamestate) {
  //if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGamestate();
  //else UpdateGameState(1);
  DoGamestateUpdate();
  include "WriteGamestate.php";
}

if ($makeCheckpoint) MakeGamestateBackup();
if ($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
if ($MakeStartTurnBackup) MakeStartTurnBackup();

GamestateUpdated($gameName);

ExitProcessInput();

//If true, allows for the case to be doable by any player when they don't have the priority.
function IsModeAsync($mode)
{
  switch ($mode) {
    case 26:
      return true;
    case 102:
      return true;
    case 103:
      return true;
    case 104:
      return true;
    case 10000:
      return true;
    case 10003:
      return true;
    case 100000:
      return true;
    case 100001:
      return true;
    case 100002:
      return true;
    case 100003:
      return true;;
    case 100004:
      return true;
    case 100007:
      return true;
    case 100008:
      return true;
    case 100009:
      return true;
    case 100010:
      return true;
  }
  return false;
}

function IsModeAllowedForSpectators($mode)
{
  switch ($mode) {
    case 100001:
      return true;
    default:
      return false;
  }
}

function ExitProcessInput()
{
  global $playerID, $redirectPath, $gameName;
  exit;
}

function PitchHasCard($cardID)
{
  global $currentPlayer;
  return SearchPitchForCard($currentPlayer, $cardID);
}

function HasCard($cardID)
{
  global $myHand, $myCharacter;
  $cardType = CardType($cardID);
  if ($cardType == "C" || $cardType == "E" || $cardType == "W") {
    for ($i = 0; $i < count($myCharacter); $i += CharacterPieces()) {
      if ($myCharacter[$i] == $cardID) {
        return $i;
      }
    }
  } else {
    for ($i = 0; $i < count($myHand); ++$i) {
      if ($myHand[$i] == $cardID) {
        return $i;
      }
    }
  }
  return -1;
}

function Passed(&$turn, $playerID)
{
  return $turn[1 + $playerID];
}

function PassInput($autopass = true)
{
  global $turn, $currentPlayer;
  if ($turn[0] == "MAYMULTICHOOSETEXT" || $turn[0] == "MAYCHOOSECOMBATCHAIN" || $turn[0] == "MAYCHOOSEMULTIZONE" ||$turn[0] == "MAYMULTICHOOSEHAND" || $turn[0] == "MAYCHOOSEHAND" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "MAYCHOOSEARSENAL" || $turn[0] == "MAYCHOOSEPERMANENT" || $turn[0] == "MAYCHOOSEDECK" || $turn[0] == "INSTANT" || $turn[0] == "OK") {
    ContinueDecisionQueue("PASS");
  } else {
    if ($autopass == true) WriteLog("Player " . $currentPlayer . " auto-passed.");
    else WriteLog("Player " . $currentPlayer . " passed.");
    if (Pass($turn, $currentPlayer, $currentPlayer)) {
      if ($turn[0] == "M") BeginTurnPass();
      else PassTurn();
    }
  }
}

function Pass(&$turn, $playerID, &$currentPlayer)
{
  global $mainPlayer, $defPlayer;
  if ($turn[0] == "M" || $turn[0] == "ARS") {
    return 1;
  } else if ($turn[0] == "B") {
    AddLayer("DEFENDSTEP", $mainPlayer, "-");
    OnBlockResolveEffects();
    ProcessDecisionQueue();
  } else if ($turn[0] == "A") {
    if (count($turn) >= 3 && $turn[2] == "D") {
      return BeginChainLinkResolution();
    } else {
      $currentPlayer = $currentPlayer == 1 ? 2 : 1;
      $turn[0] = "D";
      $turn[2] = "A";
    }
  } else if ($turn[0] == "D") {
    if (count($turn) >= 3 && $turn[2] == "A") {
      return BeginChainLinkResolution();
    } else {
      $currentPlayer = $currentPlayer == 1 ? 2 : 1;
      $turn[0] = "A";
      $turn[2] = "D";
    }
  }
  return 0;
}

function BeginChainLinkResolution()
{
  global $mainPlayer, $turn;
  $turn[0] = "M";
  ChainLinkBeginResolutionEffects();
  AddDecisionQueue("RESOLVECHAINLINK", $mainPlayer, "-");
  ProcessDecisionQueue();
}

function ResolveChainLink()
{
  global $combatChain, $combatChainState, $currentPlayer, $mainPlayer, $defPlayer, $currentTurnEffects, $CCS_CombatDamageReplaced, $CCS_LinkTotalAttack;
  global $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow, $CCS_HitsWithWeapon, $CS_EffectContext;
  UpdateGameState($currentPlayer);
  BuildMainPlayerGameState();

  $totalAttack = 0;
  $totalDefense = 0;
  EvaluateCombatChain($totalAttack, $totalDefense);
  CombatChainResolutionEffects();

  $combatChainState[$CCS_LinkTotalAttack] = $totalAttack;

  LogCombatResolutionStats($totalAttack, $totalDefense);

  $target = explode("-", GetAttackTarget());
  if ($target[0] == "THEIRALLY") {
    $index = $target[1];
    $allies = &GetAllies($defPlayer);
    $totalAttack = AllyDamagePrevention($defPlayer, $index, $totalAttack);
    $allies[$index + 2] -= $totalAttack;
    if ($totalAttack > 0) AllyDamageTakenAbilities($defPlayer, $index);
    if ($allies[$index + 2] <= 0) DestroyAlly($defPlayer, $index);
    AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, $totalAttack);
  } else {
    if ($combatChainState[$CCS_CombatDamageReplaced] == 1) $damage = 0;
    else $damage = $totalAttack - $totalDefense;
    DamageTrigger($defPlayer, $damage, "COMBAT", $combatChain[0]); //Include prevention
    AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "-");
  }
  ProcessDecisionQueue();
}

function ResolveCombatDamage($damageDone)
{
  global $combatChain, $combatChainState, $currentPlayer, $mainPlayer, $currentTurnEffects;
  global $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow, $CCS_HitsWithWeapon, $CS_EffectContext, $CS_HitsWithWeapon, $CS_DamageDealt, $CCS_ChainLinkHitEffectsPrevented;
  $wasHit = $damageDone > 0;

  AddLayer("FINALIZECHAINLINK", $mainPlayer, "0");

  WriteLog("Combat resolved with " . ($wasHit ? "a HIT for $damageDone damage." : "NO hit."));

  if (!DelimStringContains(CardSubtype($combatChain[0]), "Ally")) {
    SetClassState($mainPlayer, $CS_DamageDealt, GetClassState($mainPlayer, $CS_DamageDealt) + $damageDone);
  }

  if ($wasHit && !$combatChainState[$CCS_ChainLinkHitEffectsPrevented])
  {
    $combatChainState[$CCS_DamageDealt] = $damageDone;
    ++$combatChainState[$CCS_NumHits];
    ++$combatChainState[$CCS_HitsInRow];
    if (CardType($combatChain[0]) == "W") {
      ++$combatChainState[$CCS_HitsWithWeapon];
      IncrementClassState($mainPlayer, $CS_HitsWithWeapon);
    }
    for ($i = 1; $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i] == $mainPlayer) {
        SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i - 1]);
        ProcessHitEffect($combatChain[$i - 1]);
        if ($damageDone >= 4) ProcessCrushEffect($combatChain[$i - 1]);
        AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
      }
    }
    for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
      if (IsCombatEffectActive($currentTurnEffects[$i])) {
        if ($currentTurnEffects[$i + 1] == $mainPlayer) {
          $shouldRemove = EffectHitEffect($currentTurnEffects[$i]);
          if ($shouldRemove == 1) RemoveCurrentTurnEffect($i);
        }
      }
    }
    $currentTurnEffects = array_values($currentTurnEffects); //In case any were removed
    MainCharacterHitAbilities();
    MainCharacterHitEffects();
    ArsenalHitEffects();
    AuraHitEffects($combatChain[0]);
    AttackDamageAbilities($damageDone);
  } else {
    for ($i = 1; $i < count($combatChain); $i += CombatChainPieces()) {
      if ($combatChain[$i] == $mainPlayer) {
        SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i - 1]);
        ProcessMissEffect($combatChain[$i - 1]);
        AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
      }
    }
    $combatChainState[$CCS_HitsInRow] = 0;
  }
  $currentPlayer = $mainPlayer;
  ProcessDecisionQueue(); //Any combat related decision queue logic should be main player gamestate
}

function FinalizeChainLink($chainClosed = false)
{
  global $turn, $actionPoints, $combatChain, $mainPlayer, $playerID, $defHealth, $currentTurnEffects, $defCharacter, $mainDiscard, $defDiscard, $currentPlayer, $defPlayer;
  global $combatChainState, $actionPoints, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
  global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves;
  global $CS_LastAttack, $CCS_LinkTotalAttack, $CS_NumSwordAttacks, $chainLinks, $chainLinkSummary;
  UpdateGameState($currentPlayer);
  BuildMainPlayerGameState();

  if (DoesAttackHaveGoAgain() && !$chainClosed) {
    WriteLog("The attack has go again, gaining an action point.");
    ++$actionPoints;
    if ($combatChain[0] == "DVR002" && SearchCharacterActive($mainPlayer, "DVR001")) DoriQuicksilverProdigyEffect();
  }

  ChainLinkResolvedEffects();

  array_push($chainLinks, array());
  $CLIndex = count($chainLinks) - 1;
  for ($i = 1; $i < count($combatChain); $i += CombatChainPieces()) {
    $cardType = CardType($combatChain[$i - 1]);
    if ($cardType != "W" || $cardType != "E" || $cardType != "C") {
      $params = explode(",", GoesWhereAfterResolving($combatChain[$i - 1], "COMBATCHAIN", $combatChain[$i]));
      $goesWhere = $params[0];
      $modifier = (count($params) > 1 ? $params[1] : "NA");
      if ($i == 1 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] != "GY") {
        $goesWhere = $combatChainState[$CCS_GoesWhereAfterLinkResolves];
      }
      switch ($goesWhere) {
        case "BOTDECK":
          AddBottomMainDeck($combatChain[$i - 1], "CC");
          break;
        case "HAND":
          AddMainHand($combatChain[$i - 1], "CC");
          break;
        case "SOUL":
          AddSoul($combatChain[$i - 1], $combatChain[$i], "CC");
          break;
        case "GY": /*AddGraveyard($combatChain[$i-1], $combatChain[$i], "CC");*/
          break; //Things that would go to the GY stay on till the end of the chain
        case "BANISH":
          BanishCardForPlayer($combatChain[$i - 1], $mainPlayer, "CC", $modifier);
          break;
        default:
          break;
      }
    }
    array_push($chainLinks[$CLIndex], $combatChain[$i - 1]); //Card ID
    array_push($chainLinks[$CLIndex], $combatChain[$i]); //Player ID
    array_push($chainLinks[$CLIndex], ($goesWhere == "GY" && $combatChain[$i + 1] != "PLAY" ? "1" : "0")); //Still on chain? 1 = yes, 0 = no
  }

  array_push($chainLinkSummary, $combatChainState[$CCS_DamageDealt]);
  array_push($chainLinkSummary, $combatChainState[$CCS_LinkTotalAttack]);
  array_push($chainLinkSummary, TalentOverride($combatChain[0], $mainPlayer));
  array_push($chainLinkSummary, ClassOverride($combatChain[0], $mainPlayer));

  //Clean up combat effects that were used and are one-time
  for ($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i -= CurrentTurnEffectPieces()) {
    if (IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && !IsCombatEffectPersistent($currentTurnEffects[$i])) {
      --$currentTurnEffects[$i + 3];
      if ($currentTurnEffects[$i + 3] == 0) RemoveCurrentTurnEffect($i);
    }
  }

  CopyCurrentTurnEffectsFromCombat();

  //Don't change state until the end, in case it changes what effects are active
  if (CardType($combatChain[0]) == "W") {
    ++$mainClassState[$CS_AtksWWeapon];
    if (CardSubtype($combatChain[0]) == "Sword" || CardSubtype($combatChain[0]) == "Dagger") ++$mainClassState[$CS_NumSwordAttacks];
  }
  $combatChainState[$CCS_LastAttack] = $combatChain[0];
  SetClassState($mainPlayer, $CS_LastAttack, $combatChain[0]);

  $combatChain = [];
  if ($chainClosed) {
    ResetCombatChainState();
    $turn[0] = "M";
    FinalizeAction();
  } else {
    ResetChainLinkState();
  }
}

function BeginTurnPass()
{
  global $mainPlayer, $defPlayer, $decisionQueue;
  WriteLog("Main player has passed on the turn. Beginning end of turn step.");
  if (ShouldHoldPriority($defPlayer) || count($decisionQueue) > 0) {
    ResetCombatChainState(); // The combat chain must be closed prior to the turn ending. The close step is outlined in 7.8 - specifically: CR 2.1 - 7.8.7. Fifth and finally, the Close Step ends, and the Action Phase continues. The Action Phase will always continue after the combat chain is closed - so there is another round of priority windows
    AddLayer("ENDTURN", $mainPlayer, "-");
    ProcessDecisionQueue("");
  } else {
    FinishTurnPass();
  }
}

//CR 2.0 4.4.2. - Beginning of the end phase
function FinishTurnPass()
{
  global $mainPlayer;
  ClearLog();
  ResetCombatChainState();
  Heave();
  QuellEndPhase(1);
  QuellEndPhase(2);
  ItemEndTurnAbilities();
  AuraBeginEndPhaseAbilities();
  LandmarkBeginEndPhaseAbilities();
  BeginEndPhaseEffects();
  PermanentBeginEndPhaseEffects();
  AddDecisionQueue("PASSTURN", $mainPlayer, "-");
  ProcessDecisionQueue("");
}

function PassTurn()
{
  global $playerID, $currentPlayer, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
  if (!$mainPlayerGamestateStillBuilt) {
    UpdateGameState($currentPlayer);
    BuildMainPlayerGameState();
  }
  $MyPitch = GetPitch($playerID);
  $TheirPitch = GetPitch(($playerID == 1 ? 2 : 1));
  $MainHand = GetHand($mainPlayer);

  if (EndTurnPitchHandling($playerID)) {
    if (EndTurnPitchHandling(($playerID == 1 ? 2 : 1))) {
      if (count($MainHand) > 0 && !ArsenalFull($mainPlayer) && $turn[0] != "ARS") //Arsenal
      {
        $currentPlayer = $mainPlayer;
        $turn[0] = "ARS";
      } else {
        FinalizeTurn();
      }
    }
  }
}

function FinalizeTurn()
{
  //4.4.1. Players do not get priority during the End Phase.
  global $currentPlayer, $currentTurn, $playerID, $turn, $combatChain, $actionPoints, $mainPlayer, $defPlayer, $currentTurnEffects, $nextTurnEffects;
  global $mainHand, $defHand, $mainDeck, $mainItems, $defItems, $defDeck, $mainCharacter, $defCharacter, $mainResources, $defResources;
  global $mainAuras, $firstPlayer, $lastPlayed, $layerPriority;
  global $MakeStartTurnBackup;

  //4.4.2. First, the “beginning of the end phase” event occurs and abilities that trigger at the beginning of the end phase are triggered.
  //Undo Intimidate
  $defBanish = &GetBanish($defPlayer);
  for ($i = count($defBanish) - BanishPieces(); $i >= 0; $i -= BanishPieces()) {
    if ($defBanish[$i + 1] == "INT") {
      array_push($defHand, $defBanish[$i]);
      RemoveBanish($defPlayer, $i);
    }
  }

  LogEndTurnStats($mainPlayer);
  CurrentEffectEndTurnAbilities();
  AuraEndTurnAbilities();
  AllyEndTurnAbilities();
  MainCharacterEndTurnAbilities();

  //4.4.3a All allies’ life totals are reset to their base life, modified by any counters on the object.
  AllyBeginEndTurnEffects();

  //4.4.3b The turn player may put a card from their hand face down into an empty arsenal zone they own.
  ArsenalEndTurn($mainPlayer);
  ArsenalEndTurn($defPlayer);

  //4.4.3c Each player puts all cards in their pitch zone (if any) on the bottom of their deck in any order.The order cards are put on the bottom of the deck this way is hidden information.
  //Reset characters/equipment
  for ($i = 1; $i < count($mainCharacter); $i += CharacterPieces()) {
    if ($mainCharacter[$i - 1] == "CRU177" && $mainCharacter[$i + 1] >= 3) $mainCharacter[$i] = 0; //Destroy Talishar if >= 3 rust counters
    if ($mainCharacter[$i + 6] == 1) $mainCharacter[$i] = 0; //Destroy if it was flagged for destruction
    if ($mainCharacter[$i] != 0) {
      $mainCharacter[$i] = 2;
      $mainCharacter[$i + 4] = CharacterNumUsesPerTurn($mainCharacter[$i - 1]);
    }
  }
  for ($i = 1; $i < count($defCharacter); $i += CharacterPieces()) {
    if ($defCharacter[$i + 6] == 1) $defCharacter[$i] = 0; //Destroy if it was flagged for destruction
    if ($defCharacter[$i] == 1 || $defCharacter[$i] == 2) {
      $defCharacter[$i] = 2;
      $defCharacter[$i + 4] = CharacterNumUsesPerTurn($defCharacter[$i - 1]);
    }
  }

  //Reset Auras
  for ($i = 0; $i < count($mainAuras); $i += AuraPieces()) {
    $mainAuras[$i + 1] = 2; //If it were destroyed, it wouldn't be in the auras array
  }

  //4.4.3d All players lose all action points and resources.
  $mainResources[0] = 0;
  $mainResources[1] = 0;
  $defResources[0] = 0;
  $defResources[1] = 0;
  $lastPlayed = [];

  // 4.4.3e The turn player draws cards until the number of cards in their hand is equal to their hero’s intellect.
  //Draw Cards
  if ($mainPlayer == $firstPlayer && $currentTurn == 1) //Defender draws up on turn 1
  {
    $toDraw = CharacterIntellect($defCharacter[0]) - count($defHand);
    for ($i = 0; $i < $toDraw; ++$i) {
      Draw($defPlayer, false);
    }
  }
  $toDraw = CharacterIntellect($mainCharacter[0]) - count($mainHand) + CurrentEffectIntellectModifier();
  for ($i = 0; $i < $toDraw; ++$i) {
    Draw($mainPlayer, false);
  }
  if ($toDraw > 0) WriteLog("Main player drew " . $toDraw . " cards and now has " . count($mainHand) . " cards.");

  ResetMainClassState();
  ResetCharacterEffects();
  UnsetTurnBanish();
  AuraEndTurnCleanup();

  DoGamestateUpdate();

  //Update all the player neutral stuff
  if ($mainPlayer == 2) {
    $currentTurn += 1;
  }
  $turn[0] = "M";
  //$turn[1] = $mainPlayer == 2 ? $turn[1] + 1 : $turn[1];
  $turn[2] = "";
  $turn[3] = "";
  $actionPoints = 1;
  $combatChain = []; //TODO: Add cards to the discard pile?...
  $currentTurnEffects = $nextTurnEffects;
  $nextTurnEffects = [];
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnEffectPieces()) {
    WriteLog("Start of turn effect for " . CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " is now active.");
  }
  $defPlayer = $mainPlayer;
  $mainPlayer = ($mainPlayer == 1 ? 2 : 1);
  $currentPlayer = $mainPlayer;

  BuildMainPlayerGameState();
  ResetMainClassState();

  //Start of turn effects
  if ($mainPlayer == 1) StatsStartTurn();
  StartTurnAbilities();
  $MakeStartTurnBackup = true;

  $layerPriority[0] = ShouldHoldPriority(1);
  $layerPriority[1] = ShouldHoldPriority(2);

  DoGamestateUpdate();
  ProcessDecisionQueue();
}

function PlayCard($cardID, $from, $dynCostResolved = -1, $index = -1, $uniqueID = -1)
{
  global $playerID, $turn, $currentPlayer, $mainPlayer, $combatChain, $actionPoints, $CS_NumAddedToSoul, $layers;
  global $combatChainState, $CS_NumActionsPlayed, $CS_NumNonAttackCards, $CS_NextNAACardGoAgain, $CS_NumPlayedFromBanish, $CS_DynCostResolved;
  global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layerPriority, $CS_NumWizardNonAttack, $CS_LayerTarget, $lastPlayed, $CS_PlayIndex;
  global $decisionQueue, $CS_AbilityIndex, $CS_NumRedPlayed, $CS_PlayUniqueID, $CS_LayerPlayIndex, $CS_LastDynCost;
  $resources = &GetResources($currentPlayer);
  $pitch = &GetPitch($currentPlayer);
  $dynCostResolved = intval($dynCostResolved);
  $layerPriority[0] = ShouldHoldPriority(1);
  $layerPriority[1] = ShouldHoldPriority(2);
  $playingCard = $turn[0] != "P" && ($turn[0] != "B" || count($layers) > 0);
  if ($dynCostResolved == -1) {
    //CR 5.1.1 Play a Card (CR 2.0) - Layer Created
    if($playingCard)
    {
      SetClassState($currentPlayer, $CS_AbilityIndex, $index);
      $layerIndex = AddLayer($cardID, $currentPlayer, $from, "-", "-");
      SetClassState($currentPlayer, $CS_LayerPlayIndex, $layerIndex);
    }
    //CR 5.1.2 Announce (CR 2.0)
    if ($from == "ARS") WriteLog("Player " . $playerID . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID) . " from arsenal", $turn[0] != "P" ? $currentPlayer : 0);
    else WriteLog("Player " . $playerID . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID), $turn[0] != "P" ? $currentPlayer : 0);

    LogPlayCardStats($currentPlayer, $cardID, $from);
    if ($playingCard) {
      ClearAdditionalCosts($currentPlayer);
      MakeGamestateBackup();
      $lastPlayed = [];
      $lastPlayed[0] = $cardID;
      $lastPlayed[1] = $currentPlayer;
      $lastPlayed[2] = CardType($cardID);
      $lastPlayed[3] = "-";
      SetClassState($currentPlayer, $CS_PlayUniqueID, $uniqueID);
    }
    if (count($layers) > 0 && $layers[count($layers)-LayerPieces()] == "ENDTURN") $layers[count($layers)-LayerPieces()] = "RESUMETURN"; //Means the defending player played something, so the end turn attempt failed
  }
  if ($turn[0] != "P") {
    if ($dynCostResolved >= 0) {
      SetClassState($currentPlayer, $CS_DynCostResolved, $dynCostResolved);
      $baseCost = ($from == "PLAY" || $from == "EQUIP" ? AbilityCost($cardID) : (CardCost($cardID) + SelfCostModifier($cardID)));
      if (!$playingCard) $resources[1] += $dynCostResolved;
      else $resources[1] += ($dynCostResolved > 0 ? $dynCostResolved : $baseCost) + CurrentEffectCostModifiers($cardID, $from) + AuraCostModifier() + CharacterCostModifier($cardID, $from) + BanishCostModifier($from, $index);
      if ($resources[1] < 0) $resources[1] = 0;
      LogResourcesUsedStats($currentPlayer, $resources[1]);
    } else {
      $dqCopy = $decisionQueue;
      $decisionQueue = [];
      //CR 5.1.3 Declare Costs Begin (CR 2.0)
      $resources[1] = 0;
      if ($playingCard) $dynCost = DynamicCost($cardID); //CR 5.1.3a Declare variable cost (CR 2.0)
      else $dynCost = "";
      if ($playingCard) AddPrePitchDecisionQueue($cardID, $from, $index); //CR 5.1.3b,c Declare additional/optional costs (CR 2.0)
      if ($dynCost != "") {
        AddDecisionQueue("DYNPITCH", $currentPlayer, $dynCost);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_LastDynCost);
      }
      if ($dynCost == "") AddDecisionQueue("PASSPARAMETER", $currentPlayer, 0);
      AddDecisionQueue("RESUMEPAYING", $currentPlayer, $cardID . "-" . $from . "-" . $index);
      $decisionQueue = array_merge($decisionQueue, $dqCopy);
      ProcessDecisionQueue();
      //MISSING CR 5.1.3d Decide if action that can be played as instant will be
      //MISSING CR 5.1.3e Decide order of costs to be paid
      return;
    }
  } else if ($turn[0] == "P") {
    $pitchValue = PitchValue($cardID);
    $resources[0] += $pitchValue;
    array_push($pitch, $cardID);
    if (CardCaresAboutPitch($turn[3])) AddAdditionalCost($currentPlayer, $cardID);
    PitchAbility($cardID);
  }
  if ($resources[0] < $resources[1]) {
    if ($turn[0] != "P") {
      $turn[2] = $turn[0];
      $turn[3] = $cardID;
      $turn[4] = $from;
    }
    $turn[0] = "P";
    return; //We know we need to pitch more, short circuit here
  }
  $resources[0] -= $resources[1];
  $resourcesPaid = $resources[1];
  $resources[1] = 0;
  if ($turn[0] == "P") {
    $turn[0] = $turn[2];
    $cardID = $turn[3];
    $from = $turn[4];
    $playingCard = $turn[0] != "P" && ($turn[0] != "B" || count($layers) > 0);
  }
  $cardType = CardType($cardID);
  $abilityType = "";
  $playType = $cardType;
  PlayerMacrosCardPlayed();
  //We've paid resources, now pay action points if applicable
  if ($playingCard) {
    $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from);
    //if($from == "PLAY" || $from == "EQUIP")
    if (IsStaticType($cardType, $from, $cardID)) {
      $playType = GetResolvedAbilityType($cardID);
      $abilityType = $playType;
      if ($abilityType == "A" && !$canPlayAsInstant) {
        ResetCombatChainState();
      }
      PayAbilityAdditionalCosts($cardID);
      ActivateAbilityEffects();
    } else {
      if ($cardType == "A" && !$canPlayAsInstant) {
        ResetCombatChainState();
      }
      if (SearchCurrentTurnEffects("CRU123-DMG", $playerID) && ($cardType == "A" || $cardType == "AA")) LoseHealth(1, $playerID);
      CombatChainPlayAbility($cardID);
      ItemPlayAbilities($cardID, $from);
      ResetCardPlayed($cardID);
    }
    if ($playType == "A" || $playType == "AA") {
      if (!$canPlayAsInstant) --$actionPoints;
      if ($cardType == "A" && $abilityType == "") {
        IncrementClassState($currentPlayer, $CS_NumNonAttackCards);
        if (ClassContains($cardID, "WIZARD", $currentPlayer)) {
          IncrementClassState($currentPlayer, $CS_NumWizardNonAttack);
        }
      }
      IncrementClassState($currentPlayer, $CS_NumActionsPlayed);
    }
    if ($from == "BANISH") IncrementClassState($currentPlayer, $CS_NumPlayedFromBanish);
    if (HasBloodDebt($cardID)) IncrementClassState($currentPlayer, $CS_NumBloodDebtPlayed);
    if (PitchValue($cardID) == 1) IncrementClassState($currentPlayer, $CS_NumRedPlayed);
    PayAdditionalCosts($cardID, $from);
  }
  if ($cardType == "AA") IncrementClassState($currentPlayer, $CS_NumAttackCards); //Played or blocked

  if ($from == "BANISH") {
    $index = GetClassState($currentPlayer, $CS_PlayIndex);
    $banish = &GetBanish($currentPlayer);
    for ($i = $index + BanishPieces() - 1; $i >= $index; --$i) {
      unset($banish[$i]);
    }
    $banish = array_values($banish);
  }

  GetLayerTarget($cardID); //Layer target
  //CR 5.1.4b Declare target of attack
  if ($turn[0] == "M" && ($cardType == "AA" || $abilityType == "AA")) GetTargetOfAttack();
  if ($turn[0] != "B" || $layers[0] != "") {
  if (HasBoost($cardID)) Boost();
  MainCharacterPlayCardAbilities($cardID, $from);
  AuraPlayAbilities($cardID);
  }
  AddDecisionQueue("RESUMEPLAY", $currentPlayer, $cardID . "|" . $from . "|" . $resourcesPaid . "|" . GetClassState($currentPlayer, $CS_AbilityIndex) . "|" . GetClassState($currentPlayer, $CS_PlayUniqueID));
  ProcessDecisionQueue();
}

function GetLayerTarget($cardID)
{
  global $currentPlayer;
  switch ($cardID) {
    case "CRU164":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "LAYER,TYPE-I-MAXCOST-1");
      AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "LAYER", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      break;
    case "MON084":
    case "MON085":
    case "MON086":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "CCAA");
      AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      break;
    case "ELE183":
    case "ELE184":
    case "ELE185":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "CC:maxCost=1;type=AA"); // &LAYER:maxCost=1;type=AA
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      break;
    case "EVR033": case "EVR034": case "EVR035":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DMGPREVENTION");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a damage source for Steadfast");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      break;
    case "UPR169":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "LAYER,TYPE-A");
      AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "LAYER", 1);
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      break;
    case "UPR221": case "UPR222": case "UPR223":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DMGPREVENTION");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a damage source for Oasis Respite");
      AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
      AddDecisionQueue("SHOWSELECTEDTARGET", $currentPlayer, "-", 1);
      break;
    default:
      break;
  }
}

function AddPrePitchDecisionQueue($cardID, $from, $index = -1)
{
  global $currentPlayer;
  if (IsStaticType(CardType($cardID), $from, $cardID)) {
    $names = GetAbilityNames($cardID, $index);
    if ($names != "") {
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which ability to activate");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
      AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
    }
  }
  switch ($cardID) {
    case "WTR081":
      if (ComboActive($cardID)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
        AddDecisionQueue("LORDOFWIND", $currentPlayer, "-", 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      }
      break;
    case "ARC185":
    case "ARC186":
    case "ARC187":
      HandToTopDeck($currentPlayer);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC185", 1);
      break;
    case "CRU188":
      AddDecisionQueue("COUNTITEM", $currentPlayer, "CRU197"); //Copper
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "4");
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_4_coppers", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "CRU197-4", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);

      AddDecisionQueue("COUNTITEM", $currentPlayer, "EVR195"); //Silver
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "2");
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_2_silvers", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "EVR195-2", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);

      AddDecisionQueue("COUNTITEM", $currentPlayer, "DYN243"); //Gold
      AddDecisionQueue("LESSTHANPASS", $currentPlayer, "2");
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_1_gold", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "DYN243", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);
      break;
    case "MON199":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHAND");
      AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
      AddDecisionQueue("SOULREAPING", $currentPlayer, "-", 1);
      break;
    case "MON257":
    case "MON258":
    case "MON259":
      HandToTopDeck($currentPlayer);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "MON257", 1);
      break;
    case "EVR161":
    case "EVR162":
    case "EVR163":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "LIFEOFPARTY");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "EVR161", 1);
      break;
    default:
      break;
  }
}

function GetTargetOfAttack()
{
  global $mainPlayer, $combatChainState, $CCS_AttackTarget;
  $defPlayer = $mainPlayer == 1 ? 2 : 1;
  $numTargets = 1;
  $targets = "THEIRCHAR-0"; //Their hero
  $auras = &GetAuras($defPlayer);
  $arcLightIndex = -1;
  for ($i = 0; $i < count($auras); $i += AuraPieces()) {
    if (HasSpectra($auras[$i])) {
      $targets .= ",THEIRAURAS-" . $i;
      ++$numTargets;
      if ($auras[$i] == "MON005") $arcLightIndex = $i;
    }
  }
  $allies = &GetAllies($defPlayer);
  for ($i = 0; $i < count($allies); $i += AllyPieces()) {
    $targets .= ",THEIRALLY-" . $i;
    ++$numTargets;
  }
  if ($arcLightIndex > -1) $targets = "THEIRAURAS-" . $arcLightIndex;
  if ($numTargets > 1) {
    AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose a target for the attack");
    AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $targets);
    AddDecisionQueue("PROCESSATTACKTARGET", $mainPlayer, "-");
  } else {
    $combatChainState[$CCS_AttackTarget] = "THEIRCHAR-0"; //Their Hero
  }
}

function PayAbilityAdditionalCosts($cardID)
{
  global $currentPlayer;
  switch ($cardID) {
    case "MON000":
      for ($i = 0; $i < 2; ++$i) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDPITCH,2");
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDDISCARD", $currentPlayer, "HAND", 1);
      }
      break;
    default:
      break;
  }
}

function PayAdditionalCosts($cardID, $from)
{
  global $currentPlayer, $CS_AdditionalCosts, $CS_CharacterIndex, $CS_PlayIndex;
  $cardSubtype = CardSubType($cardID);

  if ($from == "PLAY" && $cardSubtype == "Item") {
    $paidSteamCounter = PayItemAbilityAdditionalCosts($cardID, $from);
    SetClassState($currentPlayer, $CS_AdditionalCosts, $paidSteamCounter);
    return;
  }
  $fuseType = HasFusion($cardID);
  if ($fuseType != "") {
    Fuse($cardID, $currentPlayer, $fuseType);
  }
  if (RequiresDiscard($cardID)) {
    $discarded = DiscardRandom($currentPlayer, $cardID);
    if ($discarded == "") {
      WriteLog("You do not have a card to discard. Reverting gamestate.");
      RevertGamestate();
      return;
    }
    SetClassState($currentPlayer, $CS_AdditionalCosts, $discarded);
  }
  switch ($cardID) {
    case "WTR159":
      BottomDeck();
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Draw_a_Card,Buff_Power,Go_Again");
      AddDecisionQueue("SHOWSELECTEDMODE", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "WTR179":
    case "WTR180":
    case "WTR181":
      $indices = SearchHand($currentPlayer, "", "", -1, 2);
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
      AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-");
      break;
    case "WTR182":
    case "WTR183":
    case "WTR184":
      $indices = SearchHand($currentPlayer, "", "", 1, 0);
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
      AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-");
      break;
    case "WTR185":
    case "WTR186":
    case "WTR187":
      $indices = SearchDiscardForCard($currentPlayer, "WTR218", "WTR219", "WTR220");
      if ($indices == "") {
        return "No Nimblism to banish.";
      }
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, $indices);
      AddDecisionQueue("REMOVEMYDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "DISCARD", 1);
      AddDecisionQueue("NIMBLESTRIKE", $currentPlayer, "-", 1);
      break;
    case "WTR197":
    case "WTR198":
    case "WTR199":
      $indices = SearchDiscardForCard($currentPlayer, "WTR221", "WTR222", "WTR223");
      if ($indices == "") {
        return "No Sloggism to banish.";
      }
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, $indices);
      AddDecisionQueue("REMOVEMYDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("BANISH", $currentPlayer, "DISCARD", 1);
      AddDecisionQueue("SLOGGISM", $currentPlayer, "-", 1);
      break;
    case "ARC003":
      $abilityType = GetResolvedAbilityType($cardID);
      if($abilityType == "AA")
      {
        $character = &GetPlayerCharacter($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_CharacterIndex);
        $character[$index + 2] = 0;
      }
      break;
    case "ARC041":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        SetArsenalFacing("UP", $currentPlayer);
      }
      break;
    case "ARC122":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2 modes");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_Arcane,Buff_Arcane,Draw_card,Draw_card");
      AddDecisionQueue("SETCLASSSTATEMULTICHOOSETEXT", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWSELECTEDMODES", $currentPlayer, $cardID, 1);
      break;
    case "ARC160":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose 2 modes");
      AddDecisionQueue("MULTICHOOSETEXT", $currentPlayer, "2-Buff_your_attack_action_cards_this_turn,Your_next_attack_action_card_gains_go_again,Defend_with_attack_action_cards_from_arsenal,Banish_an_attack_action_card_to_draw_2_cards");
      AddDecisionQueue("SETCLASSSTATEMULTICHOOSETEXT", $currentPlayer, $CS_AdditionalCosts, 1);
      AddDecisionQueue("SHOWSELECTEDMODES", $currentPlayer, $cardID, 1);
      break;
    case "CRU097":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      $otherCharacter = &GetPlayerCharacter($otherPlayer);
      if (SearchCurrentTurnEffects($otherCharacter[0] . "-SHIYANA", $currentPlayer)) {
        PayAdditionalCosts($otherCharacter[0], $from);
      }
      break;
    case "MON001":
    case "MON002":
      BanishFromSoul($currentPlayer);
      break;
    case "MON029":
    case "MON030":
      BanishFromSoul($currentPlayer);
      break;
    case "MON033":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MON033-1");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many cards to banish from your soul");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEMYSOUL", $currentPlayer, "-", 1);
      AddDecisionQueue("BEACONOFVICTORY-1", $currentPlayer, "-", 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "MON035":
      AddDecisionQueue("VOFTHEVANGUARD", $currentPlayer, "-");
      break;
    case "MON042":
    case "MON043":
    case "MON044":
    case "MON045":
    case "MON046":
    case "MON047":
    case "MON048":
    case "MON049":
    case "MON050":
    case "MON051":
    case "MON052":
    case "MON053":
    case "MON054":
    case "MON055":
    case "MON056":
      Charge();
      break;
    case "MON062":
      BanishFromSoul($currentPlayer);
      BanishFromSoul($currentPlayer);
      BanishFromSoul($currentPlayer);
      break;
    case "MON126":
    case "MON127":
    case "MON128":
    case "MON129":
    case "MON130":
    case "MON131":
    case "MON132":
    case "MON133":
    case "MON134":
    case "MON141":
    case "MON142":
    case "MON143":
      if (RandomBanish3GY()) AddCurrentTurnEffect($cardID, $currentPlayer);
      break;
    case "MON135":
    case "MON136":
    case "MON137":
    case "MON147":
    case "MON148":
    case "MON149":
    case "MON150":
    case "MON151":
    case "MON152":
      RandomBanish3GY();
      break;
    case "MON156":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MON156");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
      AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
      break;
    case "MON195":
    case "MON196":
    case "MON197":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
      AddDecisionQueue("ALLCARDTALENTORPASS", $currentPlayer, "SHADOW", 1);
      AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, $cardID, 1);
      break;
    case "MON198":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "GY");
      AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "6-", 1);
      AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1, 1);
      AddDecisionQueue("VALIDATECOUNT", $currentPlayer, "6", 1);
      AddDecisionQueue("SOULHARVEST", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,-", 1);
      break;
    case "MON247":
      if (CanRevealCards($currentPlayer)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHANDAA");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "1", 1);
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to reveal", 1);
        AddDecisionQueue("MAYMULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ROUSETHEANCIENTS", $currentPlayer, "-", 1);
      }
      break;
    case "MON251": case "MON252": case "MON253":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND");
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which cards to put on top of your deck (or pass)", 1);
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZADDTOPDECK", $currentPlayer, "-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "MON260": case "MON261": case "MON262":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Buff_Power,Go_Again");
      AddDecisionQueue("SHOWSELECTEDMODE", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "MON266": case "MON267": case "MON268":
      if (CanRevealCards($currentPlayer)) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a card to reveal for Belittle");
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON266-1");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "BELITTLE", 1);
        AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
        AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      }
      break;
    case "MON281": case "MON282": case "MON283":
      if ($from == "PLAY") {
        $hand = &GetHand($currentPlayer);
        if (count($hand) == 0) {
          WriteLog("This ability requires a discard as an additional cost, but you have no cards to discard. Reverting gamestate prior to the card declaration.");
          RevertGamestate();
        }
        PummelHit($currentPlayer);
      }
      break;
    case "ELE031":
    case "ELE032":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        $cardFlipped = SetArsenalFacing("UP", $currentPlayer);
        AddAdditionalCost($currentPlayer, TalentOverride($cardFlipped, $currentPlayer));
        WriteLog("Lexi turns " . CardLink($cardFlipped, $cardFlipped) . " face up.");
      }
      break;
    case "ELE115":
      if (ArsenalHasFaceDownCard($currentPlayer)) {
        AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENALDOWN");
        AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
        WriteLog(CardLink($cardID, $cardID) . " put your arsenal at the bottom of your deck");
      }
      break;
    case "ELE118":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "ARSENAL");
      AddDecisionQueue("CHOOSEARSENAL", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEARSENAL", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
      break;
    case "ELE234":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
      AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("ADDBOTDECK", $currentPlayer, "-", 1);
      break;
    case "EVR047": case "EVR048": case "EVR049":
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose a mode");
      AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Hit_Effect,1_Attack");
      AddDecisionQueue("SHOWSELECTEDMODE", $currentPlayer, $cardID, 1);
      AddDecisionQueue("SETCLASSSTATE", $currentPlayer, $CS_AdditionalCosts, 1);
      break;
    case "EVR158":
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      AddDecisionQueue("FINDINDICES", $currentPlayer, "CASHOUT");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
      AddDecisionQueue("PASSPARAMETER", $currentPlayer, "EVR195", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-", 1);
      AddDecisionQueue("CASHOUTCONTINUE", $currentPlayer, "-", 1);
      break;
    case "EVR159":
      $numCopper = CountItem("CRU197", $currentPlayer);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "0");
        AddDecisionQueue("SETDQVAR", $currentPlayer, "0");
      if ($numCopper > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Copper to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numCopper + 1));
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "CRU197-");
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("DIVIDE", $currentPlayer, "4");
        AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
      }
      $numSilver = CountItem("EVR195", $currentPlayer);
      if ($numSilver > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Silver to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numSilver + 1));
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "EVR195-");
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("DIVIDE", $currentPlayer, "2");
        AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
      }
      $numGold = CountItem("DYN243", $currentPlayer);
      if ($numGold > 0) {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Gold to pay");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numGold + 1));
        AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "DYN243-");
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
        AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
        AddDecisionQueue("DIVIDE", $currentPlayer, "1"); //Useless line?
        AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
      }
      AddDecisionQueue("KNICKKNACK", $currentPlayer, "-");
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
      break;
    case "UPR094":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "GYCARD,UPR101");
      AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIBANISH", $currentPlayer, "GY,-", 1);
      AddDecisionQueue("APPENDCLASSSTATE", $currentPlayer, $CS_AdditionalCosts . "-PHOENIXBANISH", 1);
      break;
    default:
      break;
  }
}

function PlayCardEffect($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "-", $uniqueID = "-1", $layerIndex = -1)
{
  global $turn, $combatChain, $currentPlayer, $combatChainState, $CCS_AttackPlayedFrom, $CS_PlayIndex;
  global $CS_CharacterIndex, $CS_NumNonAttackCards, $CS_PlayCCIndex, $CS_NumAttacks, $CCS_NumChainLinks, $CCS_LinkBaseAttack;
  global $currentTurnEffectsFromCombat, $CCS_WeaponIndex, $CS_EffectContext, $CCS_AttackFused, $CCS_AttackUniqueID, $CS_NumLess3PowAAPlayed, $layers;
  global $CS_NumDragonAttacks, $CS_NumIllusionistAttacks, $CS_NumIllusionistActionCardAttacks, $CCS_IsBoosted;

  if($layerIndex > -1) SetClassState($currentPlayer, $CS_PlayIndex, $layerIndex);
  $index = SearchForUniqueID($uniqueID, $currentPlayer);
  if($cardID == "ARC003") $index = FindCharacterIndex($currentPlayer, "ARC003");//TODO: Fix this. This is an issue with the entire "multiple abilities" framework
  if ($index > -1) SetClassState($currentPlayer, $CS_PlayIndex, $index);

  $character = &GetPlayerCharacter($currentPlayer);
  $definedCardType = CardType($cardID);
  //Figure out where it goes
  $openedChain = false;
  $chainClosed = false;
  $isBlock = ($turn[0] == "B" && count($layers) == 0); //This can change over the course of the function; for example if a phantasm gets popped
  if (!$isBlock && ($from == "EQUIP" || $from == "PLAY")) $cardType = GetResolvedAbilityType($cardID);
  else $cardType = $definedCardType;
  if (GoesOnCombatChain($turn[0], $cardID, $from)) {
    if($from == "PLAY" && $uniqueID != "-1" && $index == -1) { WriteLog(CardLink($cardID, $cardID) . " is no longer in play and so the effect does not resolve."); return; }
    $index = AddCombatChain($cardID, $currentPlayer, $from, $resourcesPaid);
    if ($index == 0) {
      $currentTurnEffectsFromCombat = [];
      $combatChainState[$CCS_AttackPlayedFrom] = $from;
      $chainClosed = ProcessAttackTarget();
      ++$combatChainState[$CCS_NumChainLinks];
      $baseAttackSet = CurrentEffectBaseAttackSet($cardID);
      $attackValue = ($baseAttackSet != -1 ? $baseAttackSet : AttackValue($cardID));
      $combatChainState[$CCS_LinkBaseAttack] = $attackValue;
      $combatChainState[$CCS_AttackUniqueID] = $uniqueID;
      if ($definedCardType == "AA" && $attackValue < 3) IncrementClassState($currentPlayer, $CS_NumLess3PowAAPlayed);
      if ($definedCardType == "AA" && (SearchCharacterActive($currentPlayer, "CRU002") || (SearchCharacterActive($currentPlayer, "CRU097") && SearchCurrentTurnEffects("CRU002-SHIYANA", $currentPlayer))) && $attackValue >= 6) KayoStaticAbility();
      $openedChain = true;
      if ($definedCardType != "AA") $combatChainState[$CCS_WeaponIndex] = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($additionalCosts != "-" && HasFusion($cardID)) $combatChainState[$CCS_AttackFused] = 1;
      // If you didn't attack an aura with Spectra
      if (!$chainClosed && $definedCardType == "AA") {
        ArsenalAttackAbilities();
        OnAttackEffects($cardID);
      }
      if (!$chainClosed || $definedCardType == "AA") {
        IncrementClassState($currentPlayer, $CS_NumAttacks);
        AuraAttackAbilities($cardID);
        if (DelimStringContains(CardSubType($cardID), "Dragon")) IncrementClassState($currentPlayer, $CS_NumDragonAttacks);
        if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer)) IncrementClassState($currentPlayer, $CS_NumIllusionistAttacks);
        if (ClassContains($cardID, "ILLUSIONIST", $currentPlayer) && $definedCardType == "AA") IncrementClassState($currentPlayer, $CS_NumIllusionistActionCardAttacks);
        if ($from == "PLAY" && DelimStringContains(CardSubType($cardID), "Ally")) AllyAttackAbilities($cardID);
        if ($from == "PLAY" && DelimStringContains(CardSubType($cardID), "Ally")) SpecificAllyAttackAbilities($cardID);
      }
    }
    SetClassState($currentPlayer, $CS_PlayCCIndex, $index);
  } else if ($from != "PLAY") {
    $cardSubtype = CardSubType($cardID);
    if (DelimStringContains($cardSubtype, "Aura")) {
      PlayMyAura($cardID);
    } else if ($cardSubtype == "Item") {
      PutItemIntoPlay($cardID);
    } else if ($cardSubtype == "Landmark") {
      PlayLandmark($cardID, $currentPlayer);
    } else if ($definedCardType != "C" && $definedCardType != "E" && $definedCardType != "W") {
      $goesWhere = GoesWhereAfterResolving($cardID, $from, $currentPlayer);
      switch ($goesWhere) {
        case "BOTDECK":
          AddBottomDeck($cardID, $currentPlayer, $from);
          break;
        case "HAND":
          AddPlayerHand($cardID, $currentPlayer, $from);
          break;
        case "GY":
          AddGraveyard($cardID, $currentPlayer, $from);
          break;
        case "SOUL":
          AddSoul($cardID, $currentPlayer, $from);
          break;
        case "BANISH":
          BanishCardForPlayer($cardID, $currentPlayer, $from, "NA");
          break;
        default:
          break;
      }
    }
  }
  //Resolve Effects
  if (!$isBlock) {
    if ($from != "PLAY") {
      CurrentEffectPlayAbility($cardID);
      ArsenalPlayCardAbilities($cardID);
      CharacterPlayCardAbilities($cardID, $from);
    }
    SetClassState($currentPlayer, $CS_EffectContext, $cardID);
    $playText = "";
    if (!$chainClosed) $playText = PlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
    AddDecisionQueue("CLEAREFFECTCONTEXT", $currentPlayer, "-");
    if ($playText != "") WriteLog("Resolving play ability of " . CardLink($cardID, $cardID) . ": " . $playText);
    if (!$openedChain) ResolveGoAgain($cardID, $currentPlayer, $from);
    CopyCurrentTurnEffectsFromAfterResolveEffects();
  }

  if ($CS_CharacterIndex != -1 && CanPlayAsInstant($cardID)) {
    RemoveCharacterEffects($currentPlayer, GetClassState($currentPlayer, $CS_CharacterIndex), "INSTANT");
  }
  //Now determine what needs to happen next
  SetClassState($currentPlayer, $CS_PlayIndex, -1);
  SetClassState($currentPlayer, $CS_CharacterIndex, -1);
  ProcessDecisionQueue();
}

function ProcessAttackTarget()
{
  global $defPlayer;
  $target = explode("-", GetAttackTarget());
  if ($target[0] == "THEIRAURAS") {
    $auras = &GetAuras($defPlayer);
    if (HasSpectra($auras[$target[1]])) {
      DestroyAura($defPlayer, $target[1]);
      CloseCombatChain();
      return true;
    }
  }
  return false;
}
