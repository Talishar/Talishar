<?php

function ProcessMacros()
{
  global $currentPlayer, $turn, $actionPoints, $mainPlayer, $layers, $decisionQueue, $numPass, $CS_SkipAllRunechants;
  global $combatChainState, $CCS_RequiredEquipmentBlock, $EffectContext;
  $somethingChanged = true;
  $lastPhase = $turn[0];
  for ($i = 0; $i < $numPass; ++$i) {
    PassInput();
  }
  if (!IsGameOver()) {
    for ($i = 0; $i < 10 && $somethingChanged; ++$i) {
      if ($lastPhase != $turn[0]) $i = 0;
      $lastPhase = $turn[0];
      $somethingChanged = false;

      //Debug
      //WriteLog($turn[0] . " - " . $turn[2] . "-" . $EffectContext);

      // Cache expensive function calls and counts
      $layerCount = count($layers);
      $decisionQueueCount = count($decisionQueue);
      $holdPrioritySetting = HoldPrioritySetting($currentPlayer);
      $choiceLength = strlen($turn[2] ?? "");
      $firstLayer = $layerCount > 0 ? $layers[0] : null;
      $lastLayer = $layerCount > 0 ? $layers[$layerCount - LayerPieces()] : null;
      
      switch ($turn[0]) {
        case "A":
          if (ShouldSkipARs($currentPlayer)) { $somethingChanged = true; PassInput(); }
          break;
        case "D":
          if (ShouldSkipDRs($currentPlayer)) { $somethingChanged = true; PassInput(); }
          break;
        case "B":
          if (!IsHeroAttackTarget()) { $somethingChanged = true; PassInput(); }
          break;
        case "CHOOSECARDID":
        case "CHOOSECARD":
          if ($choiceLength <= 6) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETHEIRHAND":
          if ($choiceLength <= 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETHEIRCHARACTER":
          if ($choiceLength <= 2) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETOPOPPONENT":
          if ($choiceLength <= 6) { $somethingChanged = true; ProcessInput($currentPlayer, 29, $turn[2], $turn[2], 0, ""); }
          break;
        case "ENDPHASE":
        case "STARTTURN":
          $somethingChanged = true;
          PassInput();
          break;
        case "DYNPITCH":
          if ($turn[2] == "0") { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "INSTANT":
        case "M":
          if ($turn[0] == "INSTANT" || ($turn[0] == "M" && ($actionPoints == 0 || $currentPlayer != $mainPlayer))) {
            if ($holdPrioritySetting == 0 && !HasPlayableCard($currentPlayer, $turn[0])) {
              $somethingChanged = true;
              PassInput();
            }
            
            if ($turn[0] == "INSTANT" && $layerCount > 0) {
              ProcessInstantMacros($firstLayer, $layerCount, $holdPrioritySetting, $somethingChanged, $lastLayer);
            }
          }
          break;
        default:
          if (!ProcessSpecificCardMacros()) {
            if ($decisionQueueCount == 0 || $decisionQueue[0] == "INSTANT") {
              if ($lastLayer == "ENDPHASE" && $layerCount < LayerPieces() * 3) {
                $somethingChanged = true;
                PassInput();
              }
            }
            if (!$somethingChanged && AutopassPhaseWithOneOption($turn[0]) && SearchCount($turn[2]) == 1) {
              $somethingChanged = true;
              ContinueDecisionQueue($turn[2]);
            }
          } else {
            $somethingChanged = true;
          }
      }
      if($turn[0] == "B" || $turn[0] == "D")
      {
        $threshold = ShortcutAttackThreshold($currentPlayer);
        if ($combatChainState[$CCS_RequiredEquipmentBlock] == 0) {
          switch ($threshold) {
            case "99":
              $somethingChanged = true;
              PassInput();
              break;
            case "1":
              CacheCombatResult();
              if (CachedTotalPower() <= 1) {
                $somethingChanged = true;
                PassInput();
              }
              break;
          }
        }
      }
      if(!IsGameOver() && ($turn[0] == "CHOOSEMULTIZONE" || $turn[0] == "MAYCHOOSEMULTIZONE") && GetClassState($currentPlayer, $CS_SkipAllRunechants) == 1) { 
        $somethingChanged = true; 
        SetClassState($currentPlayer, $CS_SkipAllRunechants, 0); 
      }
      else if (!IsGameOver() && isset($layers[2]) && $layers[2] == "runechant" && GetClassState($currentPlayer, $CS_SkipAllRunechants) == 1) { 
        $somethingChanged = true; 
        ContinueDecisionQueue("0"); 
      }
      else if (!IsGameOver() && GetClassState($currentPlayer, $CS_SkipAllRunechants) == 1) { 
        SetClassState($currentPlayer, $CS_SkipAllRunechants, 0); 
        $somethingChanged = true; 
        ContinueDecisionQueue("0"); 
      }
    }
  }
}

function NormalizeWeaponCard($cardName)
{
  return preg_replace('/_r$/', '', $cardName);
}

function ProcessInstantMacros($firstLayer, $layerCount, $holdPrioritySetting, &$somethingChanged, $lastLayer)
{
  global $currentPlayer, $turn, $layers, $CS_SkipAllRunechants;
  
  // Cache whether there's a unique ID
  $hasUniqueID = isset($layers[5]) && $layers[5] != "-";
  
  if ($firstLayer == "FINALIZECHAINLINK" || $firstLayer == "RESOLUTIONSTEP" || $firstLayer == "CLOSINGCHAIN") {
    if ($holdPrioritySetting != "1" && !HasPlayableCard($currentPlayer, $turn[0])) {
      $somethingChanged = true;
      PassInput();
    }
  } else if ($firstLayer == "DEFENDSTEP" && $holdPrioritySetting != "1") {
    $somethingChanged = true;
    PassInput();
  } else if ($firstLayer == "ATTACKSTEP" && $holdPrioritySetting != "1") {
    $somethingChanged = true;
    PassInput();
  } else if ($hasUniqueID) {
    $subtype = CardSubType($layers[2]);
    if (DelimStringContains($subtype, "Aura") && GetAuraGemState($currentPlayer, $layers[2]) == 0 && $holdPrioritySetting != "1") {
      $somethingChanged = true;
      PassInput();
    } else if (DelimStringContains($subtype, "Item") && GetItemGemState($currentPlayer, $layers[2]) == 0 && $holdPrioritySetting != "1") {
      $somethingChanged = true;
      PassInput();
    } else if ($layers[2] == "blasmophet_levia_consumed" && GetCharacterGemState($currentPlayer, $layers[2]) == 0 && $holdPrioritySetting != "1") {
      $somethingChanged = true;
      PassInput();
    }
  }
}

function ProcessSpecificCardMacros()
{
  global $currentPlayer, $turn, $EffectContext;
  
  if ($turn[0] == "CHOOSEMULTIZONE" && GetMZCard($currentPlayer, explode(",", $turn[2])[0]) == "phoenix_flame_red" && 
      ($EffectContext == "fai" || $EffectContext == "fai_rising_rebellion" || $EffectContext == "art_of_the_phoenix_war_red")) 
  { 
    ContinueDecisionQueue(explode(",", $turn[2])[0]); 
    return true;
  }
  
  if ($turn[0] == "CHOOSEMULTIZONE" && $EffectContext == "blood_runs_deep_red")
  { 
    $Daggers = explode(",", $turn[2]);
    $dagger1 = NormalizeWeaponCard(GetMZCard($currentPlayer, $Daggers[0]));
    $dagger2 = NormalizeWeaponCard(GetMZCard($currentPlayer, $Daggers[1]));
    if ($dagger1 == $dagger2) 
    { 
      ContinueDecisionQueue($Daggers[0]); 
      return true;
    }
  }
  
  return false;
}

function AutopassPhaseWithOneOption($phase)
{
  switch ($phase) {
    case "BUTTONINPUT":
    case "CHOOSEMULTIZONE":
    case "CHOOSECHARACTER":
    case "CHOOSECOMBATCHAIN":
    case "CHOOSEARCANE":
    case "CHOOSEARSENAL":
    case "CHOOSEHAND":
    case "CHOOSEPERMANENT":
    case "CHOOSEMYAURA":
      return true;
    default:
      return false;
  }
}

function HasPlayableCard($player, $phase)
{
  global $CombatChain;
  $restriction = "";
  $character = &GetPlayerCharacter($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  for($i=0; $i<count($character); $i+=CharacterPieces()) {
    if($character[$i+1] == 2 && GetCharacterGemState($player, $character[$i]) && IsPlayable($character[$i], $phase, "CHAR", $i, $restriction, $player)) return true;
  }
  $hand = &GetHand($player);
  for($i=0; $i<count($hand); $i+=HandPieces()) {
    if(IsPlayable($hand[$i], $phase, "HAND", $i, $restriction, $player)) return true;
  }
  for ($i = 0; $i < $CombatChain->NumCardsActiveLink(); ++$i) {
    if(IsPlayable($CombatChain->Card($i, cardNumber:true)->ID(), $phase, "CC", $i, $restriction, $player)) return true;
  }
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces()) {
    if(IsPlayable($arsenal[$i], $phase, "ARS", $i, $restriction, $player)) return true;
  }
  $items = &GetItems($player);
  for($i=0; $i<count($items); $i+=ItemPieces()) {
    if (!ItemActiveStateTracked($items[$i]) || GetItemGemState($player, $items[$i], $i) != 0) {
      if(IsPlayable($items[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
    }
  }
  $banish = &GetBanish($player);
  for($i=0; $i<count($banish); $i+=BanishPieces()) {
    if(IsPlayable($banish[$i], $phase, "BANISH", $i, $restriction, $player)) return true;
  }
  $theirBanish = &GetBanish($otherPlayer);
  for($i=0; $i<count($theirBanish); $i+=BanishPieces()) {
    if(IsPlayable($theirBanish[$i], $phase, "THEIRBANISH", $i, $restriction, $player)) return true;
  }
  $discard = GetDiscard($player);
  for($i=0; $i<count($discard); $i+=DiscardPieces()) {
    if(IsPlayable($discard[$i], $phase, "GY", $i, $restriction, $player)) return true;
  }
  $auras = &GetAuras($player);
  for($i=0; $i<count($auras); $i+=AuraPieces()) {
    if(IsPlayable($auras[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
  $character = GetPlayerCharacter($player);
  if ($character[0] == "dash_io" || $character[0] == "dash_database") {
    $deck = &GetDeck($player);
    if(count($deck) > 0 && $character[1] == 2) {
      if(IsPlayable($deck[0], $phase, "DECK", 0)) return true;
    }
  }
  $allies = GetAllies($player);
  for($i=0; $i<count($allies); $i+=AllyPieces()) {
    if(IsPlayable($allies[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
  if(AbilityPlayableFromCombatChain($CombatChain->CurrentAttack()) && !IsPlayRestricted($CombatChain->CurrentAttack(), $restriction, "PLAY", 0, $player)) return true;
  return false;
}

function PlayerMacrosCardPlayed()
{
  global $turn, $currentPlayer, $SET_PassDRStep;
  $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
  if($turn[0] == "A")
  {
    ChangeSetting($otherPlayer, $SET_PassDRStep, 0);
  }
}

