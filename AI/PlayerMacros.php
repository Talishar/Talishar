<?php

function ProcessMacros()
{
  global $currentPlayer, $turn, $actionPoints, $mainPlayer, $layers, $decisionQueue, $numPass, $CS_SkipAllRunechants;
  global $combatChainState, $CCS_RequiredEquipmentBlock, $EffectContext, $CS_PendingNAACard;
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
      // WriteLog("$currentPlayer, $turn[0], $turn[2], $EffectContext");

      // Cache expensive function calls and counts
      $layerCount = count($layers);
      $layerPieces = LayerPieces();
      $decisionQueueCount = count($decisionQueue);
      $holdPrioritySetting = HoldPrioritySetting($currentPlayer);
      $choiceLength = strlen($turn[2] ?? "");
      $firstLayer = $layerCount >= $layerPieces ? $layers[0] : null;
      $lastLayer = $layerCount >= $layerPieces ? $layers[$layerCount - $layerPieces] : null;

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
            elseif ($turn[0] == "INSTANT" && $layerCount > 0) {// I don't think this should ever be called after the above
              ProcessInstantMacros($firstLayer, $holdPrioritySetting, $somethingChanged);
            }
          }
          break;
        default:
          if (!ProcessSpecificCardMacros()) {
            if ($decisionQueueCount == 0 || $decisionQueue[0] == "INSTANT") {
              if ($lastLayer == "ENDPHASE" && $layerCount < $layerPieces * 3) {
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
      if (!IsGameOver()) {
        $skipAllRunechants = GetClassState($currentPlayer, $CS_SkipAllRunechants);
        if ($skipAllRunechants == 1) {
          if ($turn[0] == "CHOOSEMULTIZONE" || $turn[0] == "MAYCHOOSEMULTIZONE") {
            $somethingChanged = true;
            SetClassState($currentPlayer, $CS_SkipAllRunechants, 0);
          } else if (($layers[2] ?? "-") == "runechant") {
            $somethingChanged = true;
            ContinueDecisionQueue("0");
          } else {
            SetClassState($currentPlayer, $CS_SkipAllRunechants, 0);
            $somethingChanged = true;
            ContinueDecisionQueue("0");
          }
        }
      }

      if (!IsGameOver()
          && $turn[0] == "M"
          && $currentPlayer == $mainPlayer
          && $actionPoints > 0
          && SearchLayersForPhase("RESOLUTIONSTEP") == -1) {
        $pendingCard = GetClassState($mainPlayer, $CS_PendingNAACard);
        if ($pendingCard !== "-" && $pendingCard !== "" && $pendingCard !== null) {
          SetClassState($mainPlayer, $CS_PendingNAACard, "-");

          $hand = &GetHand($mainPlayer);
          $found = -1;
          $handCount = count($hand);
          for ($j = 0; $j < $handCount; ++$j) {
            if ($hand[$j] == $pendingCard) { $found = $j; break; }
          }

          if ($found >= 0 && IsPlayable($pendingCard, $turn[0], "HAND", $found)) {
            array_splice($hand, $found, 1);
            PlayCard($pendingCard, "HAND", zone: "MYHAND", index: $found);
            $somethingChanged = true;
          }
        }
      }
    }
  }
}

function NormalizeWeaponCard($cardName)
{
  if (str_ends_with($cardName, '_r')) {
    return substr($cardName, 0, -2);
  }
  return $cardName;
}

function ProcessInstantMacros($firstLayer, $holdPrioritySetting, &$somethingChanged)
{
  global $currentPlayer, $turn, $layers, $Stack;
  
  // Cache whether there's a unique ID
  $topLayer = $Stack->TopLayer();
  $layerController = $topLayer->PlayerID();
  $uid = $topLayer->UniqueID();
  $hasUniqueID = $uid != "-";
  
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
    if (DelimStringContains($subtype, "Aura") && $holdPrioritySetting != "1") {
      // TODO: move this gem checking to its own function so we can do all zones checking in one spot
      $Auras = new Auras($layerController);
      $AuraCard = $Auras->FindCardUID($uid);
      $gemStatus = $currentPlayer == $layerController ? $AuraCard->MyGemStatus() : $AuraCard->TheirGemStatus();
      if ($gemStatus === "0") {
        $somethingChanged = true;
        PassInput();
      }
    } else if (DelimStringContains($subtype, "Item") && $holdPrioritySetting != "1") {
      $Items = new Items($layerController);
      $ItemCard = $Items->FindCardUID($uid);
      $gemStatus = $currentPlayer == $layerController ? $ItemCard->MyGemStatus() : $ItemCard->TheirGemStatus();
      if ($gemStatus === "0") {
        $somethingChanged = true;
        PassInput();
      }
    } else if ($layers[2] == "blasmophet_levia_consumed" && GetCharacterGemState($currentPlayer, $layers[2]) == 0 && $holdPrioritySetting != "1") {
      $somethingChanged = true;
      PassInput();
    }
  }
}

function ProcessSpecificCardMacros()
{
  global $currentPlayer, $turn, $EffectContext;

  if ($turn[0] == "CHOOSEMULTIZONE") {
    $choices = explode(",", $turn[2]);
    $firstChoice = $choices[0];

    if (GetMZCard($currentPlayer, $firstChoice) == "phoenix_flame_red" &&
        ($EffectContext == "fai" || $EffectContext == "fai_rising_rebellion" || $EffectContext == "art_of_the_phoenix_war_red"))
    {
      ContinueDecisionQueue($firstChoice);
      return true;
    }
    if ($EffectContext == "raise_an_army_yellow" || $EffectContext == "golden_anvil_blue" || $EffectContext == "gravy_bones" || $EffectContext == "gravy_bones_shipwrecked_looter"
      || $EffectContext == "puffin_hightail" || $EffectContext == "puffin" || $EffectContext == "marlynn_treasure_hunter" || $EffectContext == "marlynn" || $EffectContext == "scurv_stowaway")
    {
      $firstCard = GetMZCard($currentPlayer, $firstChoice);
      $choiceCount = count($choices);
      $allSame = true;
      for ($k = 1; $k < $choiceCount; ++$k) {
        if (GetMZCard($currentPlayer, $choices[$k]) != $firstCard) {
          $allSame = false;
          break;
        }
      }
      if ($allSame) {
        ContinueDecisionQueue($firstChoice);
        return true;
      }
    }
    if ($EffectContext == "blood_runs_deep_red")
    {
      $dagger1 = NormalizeWeaponCard(GetMZCard($currentPlayer, $firstChoice));
      $dagger2 = NormalizeWeaponCard(GetMZCard($currentPlayer, $choices[1] ?? "-"));
      if ($dagger1 == $dagger2)
      {
        ContinueDecisionQueue($firstChoice);
        return true;
      }
    }
    // Auto-resolve a cog tap/untap choice when every option is the same cog (card + steam counters + mod)
    // identical cogs are interchangeable so don't make the player pick. Gated on the choice being a cog.
    // Still prompts when the cogs differ (steam counters, a copper cog, a turn-stolen cog, etc.).
    if (SubtypeContains(GetMZCard($currentPlayer, $firstChoice), "Cog", $currentPlayer)) {
      $firstCog = MZIndexToObject($currentPlayer, $firstChoice);
      if (is_object($firstCog)) {
        $allSameCog = true;
        for ($k = 1; $k < count($choices); ++$k) {
          $cog = MZIndexToObject($currentPlayer, $choices[$k]);
          if (!is_object($cog)
            || $cog->CardID() != $firstCog->CardID()
            || $cog->NumCounters() != $firstCog->NumCounters()
            || $cog->Modalities() != $firstCog->Modalities()) { $allSameCog = false; break; }
        }
        if ($allSameCog) { ContinueDecisionQueue($firstChoice); return true; }
      }
    }
  }
  if ($turn[0] == "MAYCHOOSECARD" && ($EffectContext == "cindra_dracai_of_retribution" || $EffectContext == "cindra"))
  {
    $daggers = explode(",", $turn[2]);
    $dagger1 = NormalizeWeaponCard(GetMZCard($currentPlayer, $daggers[0] ?? "-"));
    $dagger2 = NormalizeWeaponCard(GetMZCard($currentPlayer, $daggers[1] ?? "-"));
    if ($dagger1 == $dagger2)
    {
      ContinueDecisionQueue($daggers[0]);
      return true;
    }
  }
  if ($turn[0] == "BUTTONINPUT" && $EffectContext == "jarl_vetreidi")
  {
    if(GetCharacterGemState($currentPlayer, $EffectContext) != 0) {
      ContinueDecisionQueue(explode(",", $turn[2])[0]);
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
  $otherPlayer = 3 - $player;
  
  // Cache piece sizes
  $characterPieces = CharacterPieces();
  $handPieces = HandPieces();
  $arsenalPieces = ArsenalPieces();
  $itemPieces = ItemPieces();
  $banishPieces = BanishPieces();
  $auraPieces = AuraPieces();
  $allyPieces = AllyPieces();
  $discardPieces = DiscardPieces();
  
  // Get all zones once
  $character = &GetPlayerCharacter($player);
  $hand = &GetHand($player);
  $arsenal = &GetArsenal($player);
  $items = &GetItems($player);
  $banish = &GetBanish($player);
  $theirBanish = &GetBanish($otherPlayer);
  $discard = GetDiscard($player);
  $auras = &GetAuras($player);
  $allies = GetAllies($player);
  $deck = &GetDeck($player);
  $ccNumCards = $CombatChain->NumCardsActiveLink();
  
  // Cache counts
  $characterCount = count($character);
  $handCount = count($hand);
  $arsenalCount = count($arsenal);
  $itemCount = count($items);
  $banishCount = count($banish);
  $theirBanishCount = count($theirBanish);
  $discardCount = count($discard);
  $auraCount = count($auras);
  $allyCount = count($allies);
  $deckCount = count($deck);
  
  for($i=0; $i<$characterCount; $i+=$characterPieces) {
    if($character[$i+1] == 2 && GetCharacterGemState($player, $character[$i]) && IsPlayable($character[$i], $phase, "CHAR", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$handCount; $i+=$handPieces) {
    if(IsPlayable($hand[$i], $phase, "HAND", $i, $restriction, $player)) return true;
  }
  for ($i = 0; $i < $ccNumCards; ++$i) {
    if(IsPlayable($CombatChain->Card($i, cardNumber:true)->ID(), $phase, "CC", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$arsenalCount; $i+=$arsenalPieces) {
    if(IsPlayable($arsenal[$i], $phase, "ARS", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$itemCount; $i+=$itemPieces) {
    if (!ItemActiveStateTracked($items[$i]) || GetItemGemState($player, $items[$i], $i) != 0) {
      if(IsPlayable($items[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
    }
  }
  for($i=0; $i<$banishCount; $i+=$banishPieces) {
    if(IsPlayable($banish[$i], $phase, "BANISH", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$theirBanishCount; $i+=$banishPieces) {
    if(IsPlayable($theirBanish[$i], $phase, "THEIRBANISH", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$discardCount; $i+=$discardPieces) {
    if(IsPlayable($discard[$i], $phase, "GY", $i, $restriction, $player)) return true;
  }
  for($i=0; $i<$auraCount; $i+=$auraPieces) {
    if(IsPlayable($auras[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
  if ($character[0] == "dash_io" || $character[0] == "dash_database") {
    if($deckCount > 0 && $character[1] == 2) {
      if(IsPlayable($deck[0], $phase, "DECK", 0)) return true;
    }
  }
  for($i=0; $i<$allyCount; $i+=$allyPieces) {
    if(IsPlayable($allies[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }
  
  $currentAttack = $CombatChain->CurrentAttack();
  if(AbilityPlayableFromCombatChain($currentAttack) && !IsPlayRestricted($currentAttack, $restriction, "PLAY", 0, $player)) return true;
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

