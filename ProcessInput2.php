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
  include "AI/CombatDummy.php";
  include "AI/PlayerMacros.php";
  include "Libraries/HTTPLibraries.php";

  //We should always have a player ID as a URL parameter
  $gameName=$_GET["gameName"];
  if(!IsGameNameValid($gameName)) { echo("Invalid game name."); exit; }
  $playerID=$_GET["playerID"];
  $authKey=$_GET["authKey"];

  //We should also have some information on the type of command
  $mode = $_GET["mode"];
  $buttonInput = isset($_GET["buttonInput"]) ? $_GET["buttonInput"] : "";//The player that is the target of the command - e.g. for changing health total
  $cardID = isset($_GET["cardID"]) ? $_GET["cardID"] : "";
  $chkCount = isset($_GET["chkCount"]) ? $_GET["chkCount"] : 0;
  $chkInput = [];
  for($i=0; $i<$chkCount; ++$i)
  {
    $chk = isset($_GET[("chk" . $i)]) ? $_GET[("chk" . $i)] : "";
    if($chk != "") array_push($chkInput, $chk);
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
  if($authKey != $targetAuth) exit;

  if($playerID == 3) ExitProcessInput();
  if(!IsModeAsync($mode) && $currentPlayer != $playerID) ExitProcessInput();

  //Now we can process the command
  switch($mode) {
    case 0: //Subtract health
      $playerHealths[$player] -= 1;
      WriteLog("Player " . $playerID . " reduced player " . ($player+1) . "'s health by 1.");
      break;
    case 1: //Add health
      $playerHealths[$player] += 1;
      WriteLog("Player " . $playerID . " increased player " . ($player+1) . "'s health by 1.");
      break;
    case "HAND": //Play card from hand
      $found = HasCard($cardID);
      if($found >= 0 && IsPlayable($cardID, $turn[0], "HAND", $found)) {
        //Player actually has the card, now do the effect
        //First remove it from their hand
        $hand = &GetHand($playerID);
        unset($hand[$found]);
        $hand = array_values($hand);
        if($turn[0] == "ARS") {
          AddArsenal($cardID, $currentPlayer, "HAND", "DOWN");
          PassTurn();
        }
        else {
          PlayCard($cardID, "HAND");
        }
      }
      break;
    case "EQ": //Play equipment ability
      $index = $cardID;
      $found = -1;
      if($index != "")
      {
        $cardID = $myCharacter[$index];
        $found = HasCard($cardID);
      }
      if($index != -1 && IsPlayable($myCharacter[$found], $turn[0], "CHAR", $index))
      {
        SetClassState($playerID, $CS_CharacterIndex, $index);
        SetClassState($playerID, $CS_PlayIndex, $index);
        $character = &GetPlayerCharacter($playerID);
        if($turn[0] == "B")
        {
          if($cardID == "MON187")
          {
            $character[$index+1] = 0;
            BanishCardForPlayer($cardID, $currentPlayer, "EQUIP", "NA");
          }
          else $character[$index+6] = 1;//Else just put it on the combat chain
        }
        else
        {
          EquipPayAdditionalCosts($index, "EQUIP");
        }
        PlayCard($cardID, "EQUIP", -1, $index);
      }
      break;
    case "ARS": //Card Played from Arsenal
      $index = $cardID;
      if($index < count($myArsenal))
      {
        $cardToPlay = $myArsenal[$index];
        if(!IsPlayable($cardToPlay, $turn[0], "ARS", $index)) break;//Card not playable
        $arsenal = &GetArsenal($playerID);
        for($i=$index+ArsenalPieces()-1; $i>=$index; --$i)
        {
          unset($arsenal[$i]);
        }
        $arsenal = array_values($arsenal);
        WriteLog("Card played from arsenal.");
        PlayCard($cardToPlay, "ARS");
      }
      break;
    case "PDECK"://Pitch Deck
      if($turn[0] != "PDECK") break;
      $found = PitchHasCard($cardID);
      if($found >= 0)
      {
        PitchDeck($currentPlayer, $found);
        PassTurn();//Resume passing the turn
      }
      break;
    case 7://Number input
     if($turn[0] == "DYNPITCH")
     {
       ContinueDecisionQueue($buttonInput);
     }
     break;
    case 8: case 9://OPT, CHOOSETOP, CHOOSEBOTTOM
      if($turn[0] == "OPT" || $turn[0] == "CHOOSETOP" || $turn[0] == "CHOOSEBOTTOM")
      {
        $options = explode(",", $turn[2]);
        $found = -1;
        for($i=0; $i<count($options); ++$i)
        {
          if($options[$i] == $buttonInput) { $found = $i; break; }
        }
        if($found == -1) break;//Invalid input
        $deck = &GetDeck($playerID);
        if($mode == 8) array_unshift($deck, $buttonInput);
        else if($mode == 9) array_push($deck, $buttonInput);
        unset($options[$found]);
        $options = array_values($options);
        if(count($options) > 0) PrependDecisionQueue($turn[0], $currentPlayer, implode(",", $options));
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 10://Item ability
      $index = $cardID;//Overridden to be index instead
      if($index >= count($myItems)) break;//Item doesn't exist
      $cardID = $myItems[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Item not playable
      $items = &GetItems($playerID);
      --$items[$index+3];
      SetClassState($playerID, $CS_PlayIndex, $index);
      $set = CardSet($cardID);
      PlayCard($cardID, "PLAY", -1);
      break;
    case 11://CHOOSEDECK
      if($turn[0] == "CHOOSEDECK")
      {
        $index = $cardID;
        $cardID = $myDeck[$index];
        unset($myDeck[$index]);
        $myDeck = array_values($myDeck);
        ContinueDecisionQueue($cardID);
      }
      break;
    case 12://HANDTOP
      if($turn[0] == "HANDTOPBOTTOM")
      {
        $cardID = $myHand[$buttonInput];
        array_unshift($myDeck, $cardID);
        unset($myHand[$buttonInput]);
        $myHand = array_values($myHand);
        ContinueDecisionQueue($cardID);
      }
      break;
    case 13://HANDBOTTOM
      if($turn[0] == "HANDTOPBOTTOM")
      {
        $cardID = $myHand[$buttonInput];
        array_push($myDeck, $cardID);
        unset($myHand[$buttonInput]);
        $myHand = array_values($myHand);
        ContinueDecisionQueue($cardID);
      }
      break;
    case 14://Banish
      $index = $cardID;
      $cardID = $myBanish[$index];
      if($myBanish[$index+1] == "INST") SetClassState($currentPlayer, $CS_NextNAAInstant, 1);
      if($myBanish[$index+1] == "MON212" && CardTalent($theirCharacter[0]) == "LIGHT") AddCurrentTurnEffect("MON212", $currentPlayer);
      SetClassState($currentPlayer, $CS_PlayIndex, $index);
      PlayCard($cardID, "BANISH", -1, $index, $myBanish[$index+2]);
      break;
    case 15: case 16: case 18: //CHOOSE (15 and 18 deprecated)
      if(count($decisionQueue) > 0)//TODO: Or check all the possibilities?
      {
        $index = $cardID;
        ContinueDecisionQueue($index);
      }
      break;
    case 17://BUTTONINPUT
      if(($turn[0] == "BUTTONINPUT" || $turn[0] == "CHOOSEARCANE" || $turn[0] == "BUTTONINPUTNOPASS" || $turn[0] == "CHOOSEFIRSTPLAYER"))
      {
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 19://MULTICHOOSE X
      if(substr($turn[0], 0, 11) != "MULTICHOOSE") break;
      $params = explode("-", $turn[2]);
      $maxSelect = intval($params[0]);
      $options = explode(",", $params[1]);
      if(count($chkInput) > $maxSelect)
      {
        WriteLog("You selected " . count($chkInput) . " items, but a maximum of " . $maxSelect . " was allowed. Reverting gamestate prior to that effect.");
        RevertGamestate();
        $skipWriteGamestate = true;
        break;
      }
      for($i=0; $i<count($chkInput); ++$i)
      {
        $found = 0;
        for($j=0; $j < count($options); ++$j)
        {
          if($chkInput[$i] == $options[$j]) { $found = 1; break; }
        }
        if(!$found)
        {
          WriteLog("You selected option " . $chkInput[$i] . " but that was not one of the original options. Reverting gamestate prior to that effect.");
          RevertGamestate();
          $skipWriteGamestate = true;
          break;
        }
      }
      if(!$skipWriteGamestate)
      {
        ContinueDecisionQueue($chkInput);
      }
      break;
    case 20://YESNO
      if($turn[0] == "YESNO" && ($buttonInput == "YES" || $buttonInput == "NO")) ContinueDecisionQueue($buttonInput);
      break;
    case 21://Combat chain ability
      $index = $cardID;//Overridden to be index instead
      $cardID = $combatChain[$index];
      if(AbilityPlayableFromCombatChain($cardID) && IsPlayable($cardID, $turn[0], "PLAY", $index))
      {
        SetClassState($playerID, $CS_PlayIndex, $index);
        PlayCard($cardID, "PLAY", -1);
      }
      break;
    case 22://Aura ability
      $index = $cardID;//Overridden to be index instead
      if($index >= count($myAuras)) break;//Item doesn't exist
      $cardID = $myAuras[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Aura ability not playable
      $auras = &GetAuras($playerID);
      $auras[$index+1] = 1;//Set status to used - for now
      SetClassState($playerID, $CS_PlayIndex, $index);
      PlayCard($cardID, "PLAY", -1);
      break;
    case 23://CHOOSECARD
      if($turn[0] == "CHOOSECARD")
      {
        $options = explode(",", $turn[2]);
        $found = -1;
        for($i=0; $i<count($options); ++$i)
        {
          if($options[$i] == $buttonInput) { $found = $i; break; }
        }
        if($found == -1) break;//Invalid input
        unset($options[$found]);
        $options = array_values($options);
        ContinueDecisionQueue($buttonInput);
      }
      break;
    case 24: //Ally Ability
      $allies = &GetAllies($currentPlayer);
      $index = $cardID;//Overridden to be index instead
      if($index >= count($allies)) break;//Ally doesn't exist
      $cardID = $allies[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Ally not playable
      $allies[$index+1] = 1;
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 25: //Landmark Ability
      $index = $cardID;
      if($index >= count($landmarks)) break;//Landmark doesn't exist
      $cardID = $landmarks[$index];
      if(!IsPlayable($cardID, $turn[0], "PLAY", $index)) break;//Landmark not playable
      $myClassState[$CS_PlayIndex] = $index;
      PlayCard($cardID, "PLAY", -1);
      break;
    case 26: //Change setting
      $params = explode("-", $buttonInput);
      ChangeSetting($playerID, $params[0], $params[1]);
      break;
    case 99: //Pass
      if(CanPassPhase($turn[0]))
      {
        PassInput(false);
      }
      break;
    case 100: //Break Chain
      ResetCombatChainState();
      break;
    case 101: //Pass block and Reactions
      ChangeSetting($playerID, $SET_PassDRStep, 1);
      if(CanPassPhase($turn[0]))
      {
        PassInput(false);
      }
      break;
    case 10000://Undo
      RevertGamestate();
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their last action.");
      break;
    case 10001:
      RevertGamestate("preBlockBackup.txt");
      $skipWriteGamestate = true;
      WriteLog("Player " . $playerID . " undid their blocks.");
      break;
    case 10002:
      WriteLog("Player " . $playerID . " manually added one action point.");
      ++$actionPoints;
      break;
    case 10003://Revert to prior turn
      $params = explode("-", $buttonInput);
      RevertGamestate("p" . $params[0] . "turn" . $params[1] . "Gamestate.txt");
      WriteLog("Player " . $playerID . " reverted back to player " . $params[0] . " turn " . $params[1] . ".");
      break;
    case 10004:
      if($actionPoints > 0)
      {
        WriteLog("Player " . $playerID . " manually subtracted one action point.");
        --$actionPoints;
      }
      break;
    case 10005:
      WriteLog("Player " . $playerID . " manually subtracted one health point from themselves.");
      LoseHealth(1, $playerID);
      break;
    case 10006:
      WriteLog("Player " . $playerID . " manually added one health point to themselves.");
      $myHealth += 1;
      break;
    case 10007:
      WriteLog("Player " . $playerID . " manually added one health point to themselves.");
      LoseHealth(1, ($playerID == 1 ? 2 : 1));
      break;
    case 10008:
      WriteLog("Player " . $playerID . " manually added one health point to themselves.");
      $theirHealth += 1;
      break;
    case 10009:
      WriteLog("Player " . $playerID . " manually drew a card for themselves.");
      Draw($playerID);
      break;
    case 10010:
      WriteLog("Player " . $playerID . " manually drew a card for their opponent.");
      Draw(($playerID == 1 ? 2 : 1));
      break;
    case 10011:
      WriteLog("Player " . $playerID . " manually added a card to their hand.");
      array_push($myHand, $cardID);
      break;
    case 100000: //Quick Rematch
      $currentTime = round(microtime(true) * 1000);
      SetCachePiece($gameName, 2, $currentTime);
      SetCachePiece($gameName, 3, $currentTime);
      header("Location: " . $redirectPath . "/Start.php?gameName=$gameName&playerID=" . $playerID);
      exit;
    case 100001: //Main Menu
      header("Location: " . $redirectPath . "/MainMenu.php");
      exit;
    case 100002: //Concede
      PlayerLoseHealth($playerID, 9999);
      break;
    case 100003: //Report Bug
      $bugCount = 0;
      $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
      while($bugCount < 5 && file_exists($folderName))
      {
        ++$bugCount;
        $folderName = "./BugReports/" . $gameName . "-" . $bugCount;
      }
      if($bugCount == 5)
      {
        WriteLog("Bug report file is temporarily full for this game. Please use the discord to report further bugs.");
      }
      mkdir($folderName, 0700, true);
      copy("./Games/$gameName/gamestate.txt", $folderName . "/gamestate.txt");
      copy("./Games/$gameName/gamestateBackup.txt", $folderName . "/gamestateBackup.txt");
      copy("./Games/$gameName/gamelog.txt", $folderName . "/gamelog.txt");
      WriteLog("Thank you for reporting a bug. To describe what happened, please report it on the discord server with the game number for reference ($gameName).");
      break;
    case 100004: //Full Rematch
      $origDeck = "./Games/" . $gameName . "/p1DeckOrig.txt";
      if(file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p1Deck.txt");
      $origDeck = "./Games/" . $gameName . "/p2DeckOrig.txt";
      if(file_exists($origDeck)) copy($origDeck, "./Games/" . $gameName . "/p2Deck.txt");
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
      SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));
      exit;
    default:break;
  }

  ProcessMacros();

  if($winner != 0) { $turn[0] = "OVER"; $currentPlayer = 1; }
  CombatDummyAI();//Only does anything if applicable
  CacheCombatResult();

  //Now write out the game state
  if(!$skipWriteGamestate)
  {
    //if($mainPlayerGamestateStillBuilt) UpdateMainPlayerGamestate();
    //else UpdateGameState(1);
    DoGamestateUpdate();
    include "WriteGamestate.php";
  }

  if($makeCheckpoint) MakeGamestateBackup();
  if($makeBlockBackup) MakeGamestateBackup("preBlockBackup.txt");
  if($MakeStartTurnBackup) MakeStartTurnBackup();

  SetCachePiece($gameName, 1, strval(round(microtime(true) * 1000)));

  ExitProcessInput();

  function IsModeAsync($mode)
  {
    switch($mode)
    {
      case 26: return true;
      case 10000: return true;
      case 100001: return true;
      case 100002: return true;
      case 100003: return true;
    }
    return false;
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
      if($cardType == "C" || $cardType == "E" || $cardType == "W")
      {
        for($i=0; $i<count($myCharacter); $i+=CharacterPieces())
        {
          if($myCharacter[$i] == $cardID) {
            return $i;
          }
        }
      }
      else
      {
        for($i=0; $i<count($myHand); ++$i) {
          if($myHand[$i] == $cardID) {
            return $i;
          }
        }
      }
      return -1;
  }

  function Passed(&$turn, $playerID) {
    return $turn[1+$playerID];
  }

  function PassInput($autopass=true)
  {
    global $turn, $currentPlayer;
    if($turn[0] == "MAYCHOOSEMULTIZONE" || $turn[0] == "MAYCHOOSEHAND" || $turn[0] == "MAYCHOOSEDISCARD" || $turn[0] == "MAYCHOOSEARSENAL" || $turn[0] == "MAYCHOOSEPERMANENT" || $turn[0] == "INSTANT")
    {
      ContinueDecisionQueue("PASS");
    }
    else
    {
      if($autopass == true) WriteLog("Player " . $currentPlayer . " auto-passed.");
      else WriteLog("Player " . $currentPlayer . " passed.");
      if(Pass($turn, $currentPlayer, $currentPlayer))
      {
        if($turn[0] == "M") BeginTurnPass();
        else PassTurn();
      }
    }
  }

  function Pass(&$turn, $playerID, &$currentPlayer) {
    if($turn[0] == "M" || $turn[0] == "ARS")
    {
      return 1;
    }
    else if($turn[0] == "B")
    {
      $currentPlayer = $currentPlayer == 1 ? 2 : 1;
      $turn[0] = "A";
    }
    else if($turn[0] == "A")
    {
      if($turn[2] == "D")
      {
        return BeginChainLinkResolution();
        return 0;
      }
      else
      {
        $currentPlayer = $currentPlayer == 1 ? 2 : 1;
        $turn[0] = "D";
        $turn[2] = "A";
      }
    }
    else if($turn[0] == "D")
    {
      if($turn[2] == "A")
      {
        return BeginChainLinkResolution();
        return 0;
      }
      else
      {
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
    global $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow, $CCS_HitsWithWeapon, $CS_EffectContext, $chainLinkSummary;
    UpdateGameState($currentPlayer);
    BuildMainPlayerGameState();

    $totalAttack = 0;
    $totalDefense = 0;
    EvaluateCombatChain($totalAttack, $totalDefense);
    CombatChainResolutionEffects();

    $combatChainState[$CCS_LinkTotalAttack] = $totalAttack;

    LogCombatResolutionStats($totalAttack, $totalDefense);

    if($combatChainState[$CCS_CombatDamageReplaced] == 1) $damage = 0;
    else $damage = $totalAttack - $totalDefense;
    DamageTrigger($defPlayer, $damage, "COMBAT", $combatChain[0]);//Include prevention
    AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "-");
    ProcessDecisionQueue();
  }

  function ResolveCombatDamage($damageDone)
  {
    global $combatChain, $combatChainState, $currentPlayer, $mainPlayer, $defPlayer, $currentTurnEffects, $CCS_CombatDamageReplaced, $CCS_LinkTotalAttack;
    global $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow, $CCS_HitsWithWeapon, $CS_EffectContext, $chainLinkSummary;
    $wasHit = $damageDone > 0;
    WriteLog("Combat resolved with " . ($wasHit ? "a HIT for $damageDone damage." : "NO hit."));
    array_push($chainLinkSummary, $damageDone);
    array_push($chainLinkSummary, $combatChainState[$CCS_LinkTotalAttack]);

    if($wasHit)//Resolve hit effects
    {
      $combatChainState[$CCS_DamageDealt] = $damageDone;
      ++$combatChainState[$CCS_NumHits];
      ++$combatChainState[$CCS_HitsInRow];
      if(CardType($combatChain[0]) == "W")
      {
        ++$combatChainState[$CCS_HitsWithWeapon];
        IncrementClassState($mainPlayer, $CS_HitsWithWeapon);
      }
      for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
      {
        if($combatChain[$i] == $mainPlayer)
        {
          SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i-1]);
          ProcessHitEffect($combatChain[$i-1]);
          if($damageDone >= 4) ProcessCrushEffect($combatChain[$i-1]);
          AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
        }
      }
      for($i=count($currentTurnEffects)-CurrentTurnPieces(); $i>=0; $i-=CurrentTurnPieces())
      {
        if(IsCombatEffectActive($currentTurnEffects[$i]))
        {
          if($currentTurnEffects[$i+1] == $mainPlayer)
          {
            $shouldRemove = EffectHitEffect($currentTurnEffects[$i]);
            if($shouldRemove == 1) RemoveCurrentTurnEffect($i);
          }
        }
      }
      $currentTurnEffects = array_values($currentTurnEffects);//In case any were removed
      MainCharacterHitAbilities();
      MainCharacterHitEffects();
      ArsenalHitEffects();
      AuraHitEffects($combatChain[0]);
      AttackDamageAbilities();
    }
    else
    {
      for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
      {
        if($combatChain[$i] == $mainPlayer)
        {
          SetClassState($mainPlayer, $CS_EffectContext, $combatChain[$i-1]);
          ProcessMissEffect($combatChain[$i-1]);
          AddDecisionQueue("CLEAREFFECTCONTEXT", $mainPlayer, "-");
        }
      }
      $combatChainState[$CCS_HitsInRow] = 0;
    }
    AddDecisionQueue("FINALIZECHAINLINK", $mainPlayer, "-");
    $currentPlayer = $mainPlayer;
    ProcessDecisionQueue();//Any combat related decision queue logic should be main player gamestate
}

function FinalizeChainLink($chainClosed=false)
{
    global $turn, $actionPoints, $combatChain, $mainPlayer, $playerID, $defHealth, $currentTurnEffects, $defCharacter, $mainDiscard, $defDiscard, $currentPlayer, $defPlayer;
    global $combatChainState, $actionPoints, $CCS_LastAttack, $CCS_NumHits, $CCS_DamageDealt, $CCS_HitsInRow;
    global $mainClassState, $defClassState, $CS_AtksWWeapon, $CS_DamagePrevention, $CCS_HitsWithWeapon, $CCS_GoesWhereAfterLinkResolves;
    global $CS_LastAttack, $CCS_LinkTotalAttack, $CCS_AttackTarget, $CS_NumSwordAttacks, $chainLinks;
    UpdateGameState($currentPlayer);
    BuildMainPlayerGameState();

    if(DoesAttackHaveGoAgain() && !$chainClosed)
    {
      WriteLog("The attack has go again, gaining an action point.");
      ++$actionPoints;
      if($combatChain[0] == "DVR002" && SearchCharacterActive($mainPlayer, "DVR001")) DoriQuicksilverProdigyEffect();
    }

    //Clean up combat effects that were used and are one-time
    for($i = count($currentTurnEffects) - CurrentTurnEffectPieces(); $i >= 0; $i-=CurrentTurnEffectPieces())
    {
      if(IsCombatEffectActive($currentTurnEffects[$i]) && !IsCombatEffectLimited($i) && !IsCombatEffectPersistent($currentTurnEffects[$i]))
      {
        --$currentTurnEffects[$i+3];
        if($currentTurnEffects[$i+3] == 0) RemoveCurrentTurnEffect($i);
      }
    }

    ChainLinkResolvedEffects();

    if(CardType($combatChain[0]) == "W")
    {
      ++$mainClassState[$CS_AtksWWeapon];
      if(CardSubtype($combatChain[0]) == "Sword") ++$mainClassState[$CS_NumSwordAttacks];
    }
    $combatChainState[$CCS_LastAttack] = $combatChain[0];
    SetClassState($mainPlayer, $CS_LastAttack, $combatChain[0]);
    array_push($chainLinks, array());
    $CLIndex = count($chainLinks) - 1;
    for($i=1; $i<count($combatChain); $i+=CombatChainPieces())
    {
      $cardType = CardType($combatChain[$i-1]);
      if($cardType != "W" || $cardType != "E" || $cardType != "C")
      {
        $params = explode(",", GoesWhereAfterResolving($combatChain[$i-1], "COMBATCHAIN"));
        $goesWhere = $params[0];
        $modifier = (count($params) > 1 ? $params[1] : "NA");
        if($i == 1 && $combatChainState[$CCS_GoesWhereAfterLinkResolves] != "GY") { $goesWhere = $combatChainState[$CCS_GoesWhereAfterLinkResolves]; }
        switch($goesWhere) {
          case "BOTDECK": AddBottomMainDeck($combatChain[$i-1], "CC"); break;
          case "HAND": AddMainHand($combatChain[$i-1], "CC"); break;
          case "SOUL": AddSoul($combatChain[$i-1], $combatChain[$i], "CC"); break;
          case "GY": /*AddGraveyard($combatChain[$i-1], $combatChain[$i], "CC");*/ break;//Things that would go to the GY stay on till the end of the chain
          case "BANISH": BanishCardForPlayer($combatChain[$i-1], $mainPlayer, "CC", $modifier); break;
          default: break;
        }
      }
      array_push($chainLinks[$CLIndex], $combatChain[$i-1]);//Card ID
      array_push($chainLinks[$CLIndex], $combatChain[$i]);//Player ID
      array_push($chainLinks[$CLIndex], ($goesWhere == "GY" && $combatChain[$i+1] != "PLAY" ? "1" : "0"));//Still on chain? 1 = yes, 0 = no
    }
    CopyCurrentTurnEffectsFromCombat();
    UnsetChainLinkBanish();//For things that are banished and playable only to this chain link
    $combatChain = [];
    if($chainClosed)
    {
      ResetCombatChainState();
      $turn[0] = "M";
      FinalizeAction();
    }
    else
    {
      ResetChainLinkState();
    }
  }

  function BeginTurnPass()
  {
    global $mainPlayer, $defPlayer, $decisionQueue;
    WriteLog("Main player has passed on the turn. Beginning end of turn step.");
    if(ShouldHoldPriority($defPlayer) || count($decisionQueue) > 0)
    {
      AddLayer("ENDTURN", $mainPlayer, "-");
      ProcessDecisionQueue("");
    }
    else
    {
      FinishTurnPass();
    }
  }

  function FinishTurnPass()
  {
    global $mainPlayer;
    ResetCombatChainState();
    Heave();
    ItemEndTurnAbilities();
    AuraBeginEndStepAbilities();
    LandmarkBeginEndStepAbilities();
    BeginEndStepEffects();
    AddDecisionQueue("PASSTURN", $mainPlayer, "-");
    ProcessDecisionQueue("");
  }

  function PassTurn()
  {
    global $playerID, $currentPlayer, $turn, $mainPlayer, $mainPlayerGamestateStillBuilt;
    if(!$mainPlayerGamestateStillBuilt)
    {
      UpdateGameState($currentPlayer);
      BuildMainPlayerGameState();
    }
    $MyPitch = GetPitch($playerID);
    $TheirPitch = GetPitch(($playerID == 1 ? 2 : 1));
    $MainHand = GetHand($mainPlayer);

    if(EndTurnPitchHandling($playerID))
    {
      if(EndTurnPitchHandling(($playerID == 1 ? 2 : 1)))
      {
        if(count($MainHand) > 0 && !ArsenalFull($mainPlayer) && $turn[0] != "ARS")//Arsenal
        {
          $currentPlayer = $mainPlayer;
          $turn[0] = "ARS";
        }
        else
        {
          FinalizeTurn();
        }
      }
    }
  }

  function FinalizeTurn()
  {
    global $currentPlayer, $currentTurn, $playerID, $turn, $combatChain, $actionPoints, $mainPlayer, $defPlayer, $currentTurnEffects, $nextTurnEffects;
    global $mainHand, $defHand, $mainDeck, $mainItems, $defItems, $defDeck, $mainCharacter, $defCharacter, $mainResources, $defResources;
    global $mainAuras, $defBanish, $firstPlayer, $lastPlayed, $layerPriority;
    global $MakeStartTurnBackup;
    //Undo Intimidate
    for($i=0; $i<count($defBanish); $i+=2)
    {
      if($defBanish[$i+1] == "INT")
      {
        array_push($defHand, $defBanish[$i]);
        unset($defBanish[$i+1]);
        unset($defBanish[$i]);
        $defBanish = array_values($defBanish);
        $i -= 2;
      }
    }

    LogEndTurnStats($mainPlayer);

    //Draw Cards
    if($mainPlayer == $firstPlayer && $currentTurn == 1) //Defender draws up on turn 1
    {
      $toDraw = CharacterIntellect($defCharacter[0]) - count($defHand);
      for($i=0; $i < $toDraw; ++$i)
      {
        Draw($defPlayer, false);
      }
    }
    $toDraw = CharacterIntellect($mainCharacter[0]) - count($mainHand) + CurrentEffectIntellectModifier();
    for($i=0; $i < $toDraw; ++$i)
    {
      Draw($mainPlayer, false);
    }
    if($toDraw > 0) WriteLog("Main player drew " . $toDraw . " cards and now has " . count($mainHand) . " cards.");

    //Reset characters/equipment
    for($i=1; $i<count($mainCharacter); $i+=CharacterPieces())
    {
      if($mainCharacter[$i-1] == "CRU177" && $mainCharacter[$i+1] >= 3) $mainCharacter[$i] = 0;//Destroy Talishar if >= 3 rust counters
      if($mainCharacter[$i+6] == 1) $mainCharacter[$i] = 0;//Destroy if it was flagged for destruction
      if($mainCharacter[$i] != 0) { $mainCharacter[$i] = 2; $mainCharacter[$i + 4] = CharacterNumUsesPerTurn($mainCharacter[$i-1]); }
    }
    for($i=1; $i<count($defCharacter); $i+=CharacterPieces())
    {
      if($defCharacter[$i+6] == 1) $defCharacter[$i] = 0;//Destroy if it was flagged for destruction
      if($defCharacter[$i] == 1 || $defCharacter[$i] == 2) { $defCharacter[$i] = 2; $defCharacter[$i + 4] = CharacterNumUsesPerTurn($defCharacter[$i-1]); }
    }
    $mainAllies = &GetAllies($mainPlayer);
    for($i=0; $i<count($mainAllies); $i+=AllyPieces())
    {
      if($mainAllies[$i+1] != 0) { $mainAllies[$i+1] = 2; $mainAllies[$i+2] = AllyHealth($mainAllies[$i]) + $mainAllies[$i+7]; }
    }

    //Reset Auras
    for($i=0; $i<count($mainAuras); $i+=AuraPieces())
    {
      $mainAuras[$i+1] = 2;//If it were destroyed, it wouldn't be in the auras array
    }

    $mainResources[0] = 0;
    $mainResources[1] = 0;
    $defResources[0] = 0;
    $defResources[1] = 0;
    $lastPlayed = [];

    ArsenalEndTurn($mainPlayer);
    ArsenalEndTurn($defPlayer);
    CurrentEffectEndTurnAbilities();
    AuraEndTurnAbilities();
    AllyEndTurnAbilities();
    MainCharacterEndTurnAbilities();
    ResetMainClassState();
    ResetCharacterEffects();
    UnsetTurnBanish();
    AuraEndTurnCleanup();

    DoGamestateUpdate();

    //Update all the player neutral stuff
    if($mainPlayer == 2) { $currentTurn += 1; }
    $turn[0] = "M";
    //$turn[1] = $mainPlayer == 2 ? $turn[1] + 1 : $turn[1];
    $turn[2] = "";
    $turn[3] = "";
    $actionPoints = 1;
    $combatChain = [];//TODO: Add cards to the discard pile?...
    $currentTurnEffects = $nextTurnEffects;
    $nextTurnEffects = [];
    for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
    {
      WriteLog("Start of turn effect for " . CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " is now active.");
    }
    $defPlayer = $mainPlayer;
    $mainPlayer = ($mainPlayer == 1 ? 2 : 1);
    $currentPlayer = $mainPlayer;

    BuildMainPlayerGameState();
    ResetMainClassState();

    //Start of turn effects
    if($mainPlayer == 1) StatsStartTurn();
    StartTurnAbilities();
    $MakeStartTurnBackup = true;

    $layerPriority[0] = ShouldHoldPriority(1);
    $layerPriority[1] = ShouldHoldPriority(2);

    DoGamestateUpdate();
    ProcessDecisionQueue();
  }

  function PlayCard($cardID, $from, $dynCostResolved=-1, $index=-1, $uniqueID=-1)
  {
    global $playerID, $turn, $currentPlayer, $mainPlayer, $combatChain, $actionPoints, $CS_NumAddedToSoul, $layers;
    global $combatChainState, $CS_NumActionsPlayed, $CS_NumNonAttackCards, $CS_NextNAACardGoAgain, $CS_NumPlayedFromBanish, $CS_DynCostResolved;
    global $CS_NumAttackCards, $CS_NumBloodDebtPlayed, $layerPriority, $CS_NumWizardNonAttack, $CS_LayerTarget, $lastPlayed, $CS_PlayIndex;
    global $decisionQueue, $CS_AbilityIndex, $CS_NumRedPlayed, $CS_PlayUniqueID;
    $resources = &GetResources($currentPlayer);
    $pitch = &GetPitch($currentPlayer);
    $dynCostResolved = intval($dynCostResolved);
    $layerPriority[0] = ShouldHoldPriority(1);
    $layerPriority[1] = ShouldHoldPriority(2);
    if($dynCostResolved == -1)
    {
      //CR 5.1.2 Announce (CR 2.0)
      SetClassState($currentPlayer, $CS_PlayUniqueID, $uniqueID);
      WriteLog("Player " . $playerID . " " . PlayTerm($turn[0]) . " " . CardLink($cardID, $cardID), $turn[0] != "P" ? $currentPlayer : 0);
      LogPlayCardStats($currentPlayer, $cardID, $from);
      if($turn[0] != "P" && $turn[0] != "B") { MakeGamestateBackup(); $lastPlayed = []; $lastPlayed[0] = $cardID; $lastPlayed[1] = $currentPlayer; $lastPlayed[2] = CardType($cardID); }
      if(count($layers) > 0 && $layers[0] == "ENDTURN") $layers[0] = "RESUMETURN";//Means the defending player played something, so the end turn attempt failed
    }
    if($turn[0] != "P")
    {
        if($dynCostResolved >= 0)
        {
          SetClassState($currentPlayer, $CS_DynCostResolved, $dynCostResolved);
          $baseCost = ($from == "PLAY" || $from == "EQUIP" ? AbilityCost($cardID) : (CardCost($cardID) + SelfCostModifier($cardID)));
          if($turn[0] == "B" && CardType($cardID) != "I") $resources[1] += $dynCostResolved;
          else $resources[1] += ($dynCostResolved > 0 ? $dynCostResolved : $baseCost) + CurrentEffectCostModifiers($cardID, $from) + AuraCostModifier() + CharacterCostModifier($cardID, $from) + BanishCostModifier($from, $index);
          if($resources[1] < 0) $resources[1] = 0;
          LogResourcesUsedStats($currentPlayer, $resources[1]);
        }
        else
        {
          $dqCopy = $decisionQueue;
          $decisionQueue = [];
          //CR 5.1.3 Declare Costs Begin (CR 2.0)
          $resources[1] = 0;
          if($turn[0] == "B") $dynCost = BlockDynamicCost($cardID);
          else $dynCost = DynamicCost($cardID);//CR 5.1.3a Declare variable cost (CR 2.0)
          if($turn[0] != "B") AddPrePitchDecisionQueue($cardID, $from, $index);//CR 5.1.3b,c Declare additional/optional costs (CR 2.0)
          if($dynCost != "") AddDecisionQueue("DYNPITCH", $currentPlayer, $dynCost);
          AddPostPitchDecisionQueue($cardID, $from, $index);
          if($dynCost == "") AddDecisionQueue("PASSPARAMETER", $currentPlayer, 0);
          AddDecisionQueue("RESUMEPAYING", $currentPlayer, $cardID . "-" . $from . "-" . $index);
          $decisionQueue = array_merge($decisionQueue, $dqCopy);
          ProcessDecisionQueue();
          //MISSING CR 5.1.3d Decide if action that can be played as instant will be
          //MISSING CR 5.1.3e Decide order of costs to be paid
          return;
        }
    }
    else if($turn[0] == "P")
    {
      $pitchValue = PitchValue($cardID);
      if($pitchValue == 1)
      {
        $talismanOfRecompenseIndex = GetItemIndex("EVR191", $currentPlayer);
        if($talismanOfRecompenseIndex > -1)
        {
          WriteLog("Talisman of Recompense gained 3 instead of 1 and destroyed itself.");
          DestroyItemForPlayer($currentPlayer, $talismanOfRecompenseIndex);
          $pitchValue = 3;
        }
        if(SearchCharacterActive($currentPlayer, "UPR001") || SearchCharacterActive($currentPlayer, "UPR002"))
        {
          WriteLog("Dromai creates an Ash.");
          PutPermanentIntoPlay($currentPlayer, "UPR043");
        }
      }
      $resources[0] += $pitchValue;
      if(SearchCharacterActive($currentPlayer, "MON060") && CardTalent($cardID) == "LIGHT" && GetClassState($currentPlayer, $CS_NumAddedToSoul) > 0)
      { $resources[0] += 1; }
      array_push($pitch, $cardID);
      AddThisCardPitch($currentPlayer, $cardID);
      PitchAbility($cardID);
    }
    if($resources[0] < $resources[1])
    {
      if($turn[0] != "P")
      {
        $turn[2] = $turn[0];
        $turn[3] = $cardID;
        $turn[4] = $from;
      }
      $turn[0] = "P";
      return;//We know we need to pitch more, short circuit here
    }
    $resources[0] -= $resources[1];
    $resourcesPaid = $resources[1];
    $resources[1] = 0;
    if($turn[0] == "P")
    {
      $turn[0] = $turn[2];
      $cardID = $turn[3];
      $from = $turn[4];
    }
    $cardType = CardType($cardID);
    $abilityType = "";
    PlayerMacrosCardPlayed();
    //We've paid resources, now pay action points if applicable
    if($turn[0] != "B")// || $cardType == "I" || CanPlayAsInstant($cardID))
    {
      $goAgainPrevented = CurrentEffectPreventsGoAgain();
      //if($from == "PLAY" || $from == "EQUIP")
      if(IsStaticType($cardType, $from, $cardID))
      {
        $abilityType = GetResolvedAbilityType($cardID);
        $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from);
        $hasGoAgain = AbilityHasGoAgain($cardID);
        if($currentPlayer == $mainPlayer)
        {
          if($canPlayAsInstant) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
          else if(($abilityType == "A") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
          else if($abilityType == "AA") --$actionPoints;//Always resolve this after combat chain
        }
        if($abilityType == "A" && !$canPlayAsInstant) { ResetCombatChainState(); UnsetMyCombatChainBanish(); RemoveEffectsOnChainClose(); }
        PayAbilityAdditionalCosts($cardID);
        ActivateAbilityEffects();
      }
      else
      {
        $canPlayAsInstant = CanPlayAsInstant($cardID, $index, $from);
        $hasGoAgain = HasGoAgain($cardID);
        if(GetClassState($currentPlayer, $CS_NextNAACardGoAgain) && $cardType == "A")
        {
          $hasGoAgain = true;
          SetClassState($currentPlayer, $CS_NextNAACardGoAgain, 0);
        }
        if($cardType == "A") $hasGoAgain = CurrentEffectGrantsNonAttackActionGoAgain($cardID) || $hasGoAgain;
        if($cardType == "A" && $hasGoAgain && (SearchAuras("UPR190", 1) || SearchAuras("UPR190", 2))) $hasGoAgain = false;
        if($currentPlayer == $mainPlayer)
        {
          if($canPlayAsInstant) { if($hasGoAgain && !$goAgainPrevented) ++$actionPoints; }
          else if(($cardType == "A") && (!$hasGoAgain || $goAgainPrevented)) --$actionPoints;
          else if($cardType == "AA") --$actionPoints;//Always resolve this after combat chain
        }
        if($cardType == "A" && !$canPlayAsInstant) { ResetCombatChainState(); UnsetMyCombatChainBanish(); RemoveEffectsOnChainClose(); }
        if(SearchCurrentTurnEffects("CRU123-DMG", $playerID) && ($cardType == "A" || $cardType == "AA")) LoseHealth(1, $playerID);
        CombatChainPlayAbility($cardID);
        ItemPlayAbilities($cardID, $from);
        ResetCardPlayed($cardID);

        //Pay additional costs
        $hand = &GetHand($currentPlayer);
        if(RequiresDiscard($cardID) && count($hand) == 0)
        {
          WriteLog("This card requires a discard as an additional cost, but have no cards to discard. Reverting gamestate prior to the card declaration.");
          RevertGamestate();
        }
        if($cardID == "WTR159" && count($hand) == 0)
        {
          WriteLog("This card requires a card to put on the bottom of your deck, but you have no cards. Reverting gamestate prior to the card declaration.");
          RevertGamestate();
        }
      }
      if($cardType == "A" || $abilityType == "A" || $cardType == "AA" || $abilityType == "AA")
      {
        if($cardType == "A" && $abilityType == "")
        {
          IncrementClassState($currentPlayer, $CS_NumNonAttackCards);
          if(CardClass($cardID) == "WIZARD") { IncrementClassState($currentPlayer, $CS_NumWizardNonAttack); }
        }
        IncrementClassState($currentPlayer, $CS_NumActionsPlayed);
      }
      if($from == "BANISH") IncrementClassState($currentPlayer, $CS_NumPlayedFromBanish);
      if(HasBloodDebt($cardID)) IncrementClassState($currentPlayer, $CS_NumBloodDebtPlayed);
      if(PitchValue($cardID) == 1) IncrementClassState($currentPlayer, $CS_NumRedPlayed);
      PayAdditionalCosts($cardID, $from);
    }
    if($cardType == "AA") IncrementClassState($currentPlayer, $CS_NumAttackCards);//Played or blocked

    if($from == "BANISH")
    {
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $banish = &GetBanish($currentPlayer);
      for($i=$index+BanishPieces()-1; $i>=$index; --$i)
      {
        unset($banish[$i]);
      }
      $banish = array_values($banish);
    }

    //CR 5.1.4b Declare target of attack
    if($turn[0] == "M" && ($cardType == "AA" || $abilityType == "AA")) GetTargetOfAttack();
    AddDecisionQueue("RESUMEPLAY", $currentPlayer, $cardID . "|" . $from . "|" . $resourcesPaid . "|" . GetClassState($currentPlayer, $CS_AbilityIndex) . "|" . GetClassState($currentPlayer, $CS_PlayUniqueID));
    ProcessDecisionQueue();
  }

  function GetLayerTarget($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "CRU164":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "LAYER,TYPE-I-MAXCOST-1");
        AddDecisionQueue("MULTIZONEFORMAT", $currentPlayer, "LAYER", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
        break;
      case "MON084": case "MON085": case "MON086":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "CCAA");
        AddDecisionQueue("CHOOSECOMBATCHAIN", $currentPlayer, "<-", 1);
        AddDecisionQueue("SETLAYERTARGET", $currentPlayer, "-", 1);
        break;
      default: break;
    }
  }

  function AddPrePitchDecisionQueue($cardID, $from, $index=-1)
  {
    global $currentPlayer;
    if(IsStaticType(CardType($cardID), $from, $cardID))
    {
      $names = GetAbilityNames($cardID, $index);
      if($names != "")
      {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which ability to activate");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, $names);
        AddDecisionQueue("SETABILITYTYPE", $currentPlayer, $cardID);
      }
    }
    switch($cardID)
    {
      case "WTR081":
        if(ComboActive($cardID))
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("MULTICHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIADDDECK", $currentPlayer, "-", 1);
          AddDecisionQueue("LORDOFWIND", $currentPlayer, "-", 1);
          AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
        }
        break;
      case "ARC185": case "ARC186": case "ARC187":
        HandToTopDeck($currentPlayer);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "ARC185", 1);
        break;
      case "CRU188":
        AddDecisionQueue("COUNTITEM", $currentPlayer, "CRU197");
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "4");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_4_copper", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "CRU197-4", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);
        AddDecisionQueue("COUNTITEM", $currentPlayer, "EVR195");//TODO: Gold
        AddDecisionQueue("LESSTHANPASS", $currentPlayer, "2");
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_pay_2_silver", 1);
        AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
        AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "EVR195-2", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "CRU188", 1);
        break;
      case "MON199":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHAND");
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("SOULREAPING", $currentPlayer, "-", 1);
        break;
      case "MON257": case "MON258": case "MON259":
        HandToTopDeck($currentPlayer);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "MON257", 1);
        break;
      case "EVR161": case "EVR162": case "EVR163":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "LIFEOFPARTY");
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIZONEDESTROY", $currentPlayer, "-", 1);
        AddDecisionQueue("ADDCURRENTEFFECT", $currentPlayer, "EVR161", 1);
        break;
      default:
        break;
    }
    $auras = &GetAuras($currentPlayer);
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      switch($auras[$i])
      {
        case "ELE175":
          $type = CardType($cardID);
          if($type == "A" || $type == "AA")
          {
            AddDecisionQueue("YESNO", $currentPlayer, "Do_you_want_to_pay_1_to_give_this_action_go_again", 0, 1);
            AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
            AddDecisionQueue("PASSPARAMETER", $currentPlayer, 1, 1);
            AddDecisionQueue("PAYRESOURCES", $currentPlayer, "<-", 1);
            AddDecisionQueue("PREPITCHGIVEGOAGAIN", $currentPlayer, $type, 1);
          }
          break;
        default: break;
      }
    }
  }

  function AddPostPitchDecisionQueue($cardID, $from, $index=-1)
  {
    global $currentPlayer;
    if(RequiresDieRoll($cardID, $from)) RollDie($currentPlayer);
    switch($cardID)
    {
      case "MON089":
        AddDecisionQueue("PHANTASMALFOOTSTEPS", $currentPlayer, $index, 1);
        break;
      case "MON241": case "MON242": case "MON243": case "MON244": case "RVD006": case "RVD005":
        AddDecisionQueue("IRONHIDE", $currentPlayer, $index, 1);
        break;
      case "ELE203":
        AddDecisionQueue("RAMPARTOFTHERAMSHEAD", $currentPlayer, $index, 1);
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
    $targets = "THEIRCHAR-0";//Their hero
    $auras = &GetAuras($defPlayer);
    $arcLightIndex = -1;
    for($i=0; $i<count($auras); $i+=AuraPieces())
    {
      if(HasSpectra($auras[$i]))
      {
        $targets .= ",THEIRAURAS-" . $i;
        ++$numTargets;
        if($auras[$i] == "MON005") $arcLightIndex = $i;
      }
    }
    $allies = &GetAllies($defPlayer);
    for($i=0; $i<count($allies); $i+=AllyPieces())
    {
      $targets .= ",THEIRALLY-" . $i;
      ++$numTargets;
    }
    if($arcLightIndex > -1) $targets = "THEIRAURAS-" . $arcLightIndex;
    if($numTargets > 1)
    {
      AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, $targets);
      AddDecisionQueue("PROCESSATTACKTARGET", $mainPlayer, "-");
    }
    else
    {
      $combatChainState[$CCS_AttackTarget] = "THEIRCHAR-0";
    }
  }

  function PayAbilityAdditionalCosts($cardID)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "MON000":
        for($i=0; $i<2; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, "HANDPITCH,2");
          AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, "<-", 1);
          AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
          AddDecisionQueue("ADDDISCARD", $currentPlayer, "HAND", 1);
        }
        break;
      default: break;
    }
  }

  function PayAdditionalCosts($cardID, $from)
  {
    global $currentPlayer, $CS_AdditionalCosts;
    $cardSubtype = CardSubType($cardID);
    if($from == "PLAY" && $cardSubtype == "Item")
    {
      PayItemAbilityAdditionalCosts($cardID);
      return;
    }
    $fuseType = HasFusion($cardID);
    if($fuseType != "")
    {
      Fuse($cardID, $currentPlayer, $fuseType);
    }
    switch($cardID)
    {
      case "WTR159":
        BottomDeck();
        break;
      case "WTR179": case "WTR180": case "WTR181":
        $indices = SearchHand($currentPlayer, "", "", -1, 2);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "WTR182": case "WTR183": case "WTR184":
        $indices = SearchHand($currentPlayer, "", "", 1, 0);
        AddDecisionQueue("CHOOSEHANDCANCEL", $currentPlayer, $indices);
        AddDecisionQueue("REVEALMYCARD", $currentPlayer, "-");
        break;
      case "MON001": case "MON002":
        BanishFromSoul($currentPlayer);
        break;
      case "MON029": case "MON030":
        BanishFromSoul($currentPlayer);
        break;
      case "MON035":
        AddDecisionQueue("VOFTHEVANGUARD", $currentPlayer, "-");
        break;
      case "MON042": case "MON043": case "MON044": case "MON045": case "MON046": case "MON047":
      case "MON048": case "MON049": case "MON050": case "MON051": case "MON052": case "MON053":
      case "MON054": case "MON055": case "MON056": Charge(); break;
      case "MON062":
        BanishFromSoul($currentPlayer);
        BanishFromSoul($currentPlayer);
        BanishFromSoul($currentPlayer);
        break;
      case "MON156":
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MON156");
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $currentPlayer, "HAND,NA", 1);
        AddDecisionQueue("GIVEATTACKGOAGAIN", $currentPlayer, "-", 1);
        break;
      case "MON195": case "MON196": case "MON197":
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
        AddDecisionQueue("FINDINDICES", $currentPlayer, "MULTIHANDAA");
        AddDecisionQueue("MULTICHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REVEALHANDCARDS", $currentPlayer, "-", 1);
        AddDecisionQueue("ROUSETHEANCIENTS", $currentPlayer, "-", 1);
        break;
      case "MON281": case "MON282": case "MON283":
        if($from == "PLAY")
        {
          $hand = &GetHand($currentPlayer);
          if(count($hand) == 0)
          {
            WriteLog("This ability requires a discard as an additional cost, but you have no cards to discard. Reverting gamestate prior to the card declaration.");
            RevertGamestate();
          }
          PummelHit($currentPlayer);
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
        if($numCopper > 0)
        {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Copper to pay");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numCopper+1));
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "CRU197-");
          AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
          AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
          AddDecisionQueue("DIVIDE", $currentPlayer, "4");
          AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
        }
        $numSilver = CountItem("EVR195", $currentPlayer);
        if($numSilver > 0)
        {
          AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose how many Silver to pay");
          AddDecisionQueue("BUTTONINPUT", $currentPlayer, GetIndices($numSilver+1));
          AddDecisionQueue("PREPENDLASTRESULT", $currentPlayer, "EVR195-");
          AddDecisionQueue("FINDANDDESTROYITEM", $currentPlayer, "<-");
          AddDecisionQueue("LASTRESULTPIECE", $currentPlayer, "1", 1);
          AddDecisionQueue("DIVIDE", $currentPlayer, "2");
          AddDecisionQueue("INCDQVAR", $currentPlayer, "0");
        }
        AddDecisionQueue("KNICKKNACK", $currentPlayer, "-");
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

  function PlayCardEffect($cardID, $from, $resourcesPaid, $target="-", $additionalCosts="-", $uniqueID="-1")
  {
    global $turn, $combatChain, $currentPlayer, $combatChainState, $CCS_AttackPlayedFrom, $CS_PlayIndex;
    global $CS_CharacterIndex, $CS_NumNonAttackCards, $CS_PlayCCIndex, $CS_NumAttacks, $CCS_NumChainLinks, $CCS_LinkBaseAttack;
    global $currentTurnEffectsFromCombat, $CCS_WeaponIndex, $CS_EffectContext, $CCS_AttackFused, $CCS_AttackUniqueID, $CS_NumLess3PowPlayed;
    $character = &GetPlayerCharacter($currentPlayer);
    $definedCardType = CardType($cardID);
    //Figure out where it goes
    $openedChain = false;
    $chainClosed = false;
    $isBlock = $turn[0] == "B";//This can change over the course of the function; for example if a phantasm gets popped
    if($turn[0] != "B" && $from == "EQUIP" || $from == "PLAY") $cardType = GetResolvedAbilityType($cardID);
    else $cardType = $definedCardType;
    if(GoesOnCombatChain($turn[0], $cardID, $from))
    {
      $index = AddCombatChain($cardID, $currentPlayer, $from, $resourcesPaid);
      if($index == 0)
      {
        $currentTurnEffectsFromCombat = [];
        $combatChainState[$CCS_AttackPlayedFrom] = $from;
        $chainClosed = ProcessAttackTarget();
        if(!$chainClosed || $definedCardType == "AA")
        {
          AuraAttackAbilities($cardID);
          AllyAttackAbilities($cardID);
          ArsenalAttackAbilities();
          OnAttackEffects($cardID);
        }
        ++$combatChainState[$CCS_NumChainLinks];
        IncrementClassState($currentPlayer, $CS_NumAttacks);
        $baseAttackSet = CurrentEffectBaseAttackSet($cardID);
        $attackValue = ($baseAttackSet != -1 ? $baseAttackSet : AttackValue($cardID));
        $combatChainState[$CCS_LinkBaseAttack] = $attackValue;
        $combatChainState[$CCS_AttackUniqueID] = $uniqueID;
        if($attackValue < 3) IncrementClassState($currentPlayer, $CS_NumLess3PowPlayed);
        if($definedCardType == "AA" && SearchCharacterActive($currentPlayer, "CRU002") && $attackValue >= 6) KayoStaticAbility();
        $openedChain = true;
        if($definedCardType != "AA") $combatChainState[$CCS_WeaponIndex] = GetClassState($currentPlayer, $CS_PlayIndex);
        if($additionalCosts != "-" && HasFusion($cardID)) $combatChainState[$CCS_AttackFused] = 1;
      }
      SetClassState($currentPlayer, $CS_PlayCCIndex, $index);
    }
    else if($from != "PLAY")
    {
      $cardSubtype = CardSubType($cardID);
      if(DelimStringContains($cardSubtype, "Aura"))
      {
        PlayMyAura($cardID);
      }
      else if($cardSubtype == "Item")
      {
        PutItemIntoPlay($cardID);
      }
      else if($cardSubtype == "Landmark")
      {
        PlayLandmark($cardID, $currentPlayer);
      }
      else if($definedCardType != "C" && $definedCardType != "E" && $definedCardType != "W")
      {
        $goesWhere = GoesWhereAfterResolving($cardID, $from, $currentPlayer);
        switch($goesWhere)
        {
          case "BOTDECK": AddBottomDeck($cardID, $currentPlayer, $from); break;
          case "HAND": AddPlayerHand($cardID, $currentPlayer, $from); break;
          case "GY": AddGraveyard($cardID, $currentPlayer, $from); break;
          case "SOUL": AddSoul($cardID, $currentPlayer, $from); break;
          case "BANISH": BanishCardForPlayer($cardID, $currentPlayer, $from, "NA"); break;
          default: break;
        }
      }
    }
    //Resolve Effects
    if(!$isBlock)
    {
      if($from != "PLAY")
      {
        CurrentEffectPlayAbility($cardID);
        AuraPlayAbilities($cardID, $from);
        ArsenalPlayCardAbilities($cardID);
        if(HasBoost($cardID)) Boost();
        CharacterPlayCardAbilities($cardID, $from);
      }
      SetClassState($currentPlayer, $CS_EffectContext, $cardID);
      $playText = "";
      if(!$chainClosed) $playText = PlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts);
      AddDecisionQueue("CLEAREFFECTCONTEXT", $currentPlayer, "-");
      if($playText != "") WriteLog("Resolving play ability of " . CardLink($cardID, $cardID) . ": " . $playText);
      if($openedChain) ProcessAttackTargetAfterResolve();
    }

    if($CS_CharacterIndex != -1 && CanPlayAsInstant($cardID))
    {
      RemoveCharacterEffects($currentPlayer, GetClassState($currentPlayer, $CS_CharacterIndex), "INSTANT");
    }
    //Now determine what needs to happen next
    SetClassState($currentPlayer, $CS_PlayIndex, -1);
    SetClassState($currentPlayer, $CS_CharacterIndex, -1);
    ResetThisCardPitch($currentPlayer);
    ProcessDecisionQueue();
  }

  function ProcessAttackTarget()
  {
    global $combatChainState, $CCS_AttackTarget, $defPlayer;
    $target = explode("-", $combatChainState[$CCS_AttackTarget]);
    if($target[0] == "THEIRAURAS")
    {
      $auras = &GetAuras($defPlayer);
      if(HasSpectra($auras[$target[1]]))
      {
        DestroyAura($defPlayer, $target[1]);
        CloseCombatChain();
        return true;
      }
    }
    return false;
  }

  function ProcessAttackTargetAfterResolve()
  {
    global $combatChainState, $CCS_AttackTarget, $defPlayer, $CCS_LinkTotalAttack;
    $target = explode("-", $combatChainState[$CCS_AttackTarget]);
    if($target[0] == "THEIRALLY")
    {
      $index = $target[1];
      $totalAttack = 0;
      $totalDefense = 0;
      $chainAttackModifiers = [];
      EvaluateCombatChain($totalAttack, $totalDefense, $chainAttackModifiers);
      $allies = &GetAllies($defPlayer);
      $totalAttack = AllyDamagePrevention($defPlayer, $index, $totalAttack);
      $allies[$index+2] -= $totalAttack;
      if($totalAttack > 0) AllyDamageTakenAbilities($defPlayer, $index);
      if($allies[$index+2] <= 0) DestroyAlly($defPlayer, $index);
      //TODO: Does this need to do all of ResolveChainLink?
      $combatChainState[$CCS_LinkTotalAttack] = $totalAttack;
      AddDecisionQueue("PASSPARAMETER", $mainPlayer, $totalAttack);
      AddDecisionQueue("RESOLVECOMBATDAMAGE", $mainPlayer, "-");
      //CloseCombatChain("-");//This makes it NOT close the chain, so it does resolve go again and other chain effects
    }
  }

?>
