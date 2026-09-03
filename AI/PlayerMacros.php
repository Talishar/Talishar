<?php

function ProcessMacros()
{
  global $currentPlayer, $turn, $actionPoints, $mainPlayer, $defPlayer, $layers, $decisionQueue, $numPass, $CS_SkipAllRunechants;
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
      $firstLayer = $layerCount >= $layerPieces ? $layers[0] : null;
      $lastLayer = $layerCount >= $layerPieces ? $layers[$layerCount - $layerPieces] : null;

      switch ($turn[0]) {
        case "A":
          if ($currentPlayer == $mainPlayer
              && (ShouldSkipARs($mainPlayer) || AutoPassTurnSetting($mainPlayer))) {
            $somethingChanged = true;
            PassInput();
          }
          break;
        case "D":
          if ($currentPlayer == $defPlayer
              && (ShouldSkipDRs($defPlayer) || AutoPassTurnSetting($defPlayer))) {
            $somethingChanged = true;
            PassInput();
          }
          break;
        case "B":
          if (!IsHeroAttackTarget()
              || ($currentPlayer == $defPlayer && AutoPassTurnSetting($defPlayer)
                  && GetCombatChainState($CCS_RequiredEquipmentBlock) == 0)) {
            $somethingChanged = true;
            PassInput();
          }
          break;
        case "CHOOSECARDID":
        case "CHOOSECARD":
          if (SearchCount($turn[2]) == 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETOP":
        case "CHOOSEBOTTOM":
          if (SearchCount($turn[2]) == 1) {
            $somethingChanged = true;
            $mode = $turn[0] == "CHOOSETOP" ? 8 : 9;
            ProcessInput($currentPlayer, $mode, $turn[2], $turn[2], 0, "");
          }
          break;
        case "CHOOSETHEIRHAND":
          if (SearchCount($turn[2]) == 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETHEIRCHARACTER":
          if (SearchCount($turn[2]) == 1) { $somethingChanged = true; ContinueDecisionQueue($turn[2]); }
          break;
        case "CHOOSETOPOPPONENT":
          if (SearchCount($turn[2]) == 1) { $somethingChanged = true; ProcessInput($currentPlayer, 29, $turn[2], $turn[2], 0, ""); }
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
            if (AutoPassTurnSetting($currentPlayer)) {
              $somethingChanged = true;
              PassInput();
            }
            elseif ($holdPrioritySetting == 0 && !HasPlayableCard($currentPlayer, $turn[0])) {
              $somethingChanged = true;
              PassInput();
            }
            elseif ($layerCount > 0 && ($turn[0] == "INSTANT" || $firstLayer == "RESOLUTIONSTEP")) {
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
        if (GetCombatChainState($CCS_RequiredEquipmentBlock) == 0) {
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
  global $currentPlayer, $mainPlayer, $turn, $layers, $Stack;

  if ($firstLayer == "FINALIZECHAINLINK" || $firstLayer == "RESOLUTIONSTEP" || $firstLayer == "CLOSINGCHAIN") {
    $playableCard = "";
    $hasPlayable = HasPlayableCard($currentPlayer, $turn[0], $playableCard);
    if ($holdPrioritySetting != "1" && !$hasPlayable) {
      $somethingChanged = true;
      PassInput(doublePass: $firstLayer == "RESOLUTIONSTEP" && $currentPlayer == $mainPlayer);
    }
  } else if ($firstLayer == "DEFENDSTEP" && $holdPrioritySetting != "1") {
    $somethingChanged = true;
    PassInput();
  } else if ($firstLayer == "ATTACKSTEP" && $holdPrioritySetting != "1") {
    $somethingChanged = true;
    PassInput();
  } else {
    $topLayer = $Stack->TopLayer();
    $layerController = $topLayer->PlayerID();
    $uid = $topLayer->UniqueID();
    if ($uid == "-") return;

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

    // If a mandatory multi-select requires every available option, there is no choice to make we can skip the player popup.
    $minimumCount = null;
    $limitOffset = 0;
    while ($limitOffset < count($choices)) {
      $limit = explode("-", $choices[$limitOffset], 2);
      if ($limit[0] == "MINCOUNT") {
        $minimumCount = intval($limit[1] ?? 0);
        ++$limitOffset;
      }
      else if ($limit[0] == "MAXCOUNT") {
        ++$limitOffset;
      }
      else break;
    }
    if ($limitOffset > 0) {
      $selectableChoices = array_slice($choices, $limitOffset);
      if ($minimumCount !== null && $minimumCount > 0 && count($selectableChoices) == $minimumCount) {
        ContinueDecisionQueue(implode(",", $selectableChoices));
        return true;
      }
      return false;
    }

    $firstChoice = $choices[0];

    if (GetMZCard($currentPlayer, $firstChoice) == "phoenix_flame_red" &&
        ($EffectContext == "fai" || $EffectContext == "fai_rising_rebellion" || $EffectContext == "art_of_the_phoenix_war_red"))
    {
      ContinueDecisionQueue($firstChoice);
      return true;
    }
    // Auto choose mandatory selections when every option has the same card ID.
    static $autoChooseAllSameContexts = [
      "raise_an_army_yellow", "visit_the_golden_anvil_blue",
      "deadwood_dirge_red", "deadwood_dirge_yellow", "deadwood_dirge_blue",
      "gravy_bones", "gravy_bones_shipwrecked_looter",
      "puffin_hightail", "puffin", "marlynn_treasure_hunter", "marlynn", "scurv_stowaway",
      "pay_up_red",
      "mutiny_on_the_battalion_barque_blue", "mutiny_on_the_nimbus_sovereign_blue", "mutiny_on_the_swiftwater_blue",
      "sticky_fingers", "sticky_fingers_ally",
      "money_or_your_life_red", "money_or_your_life_yellow", "money_or_your_life_blue",
      "cutpurse_rapier",
      "not_so_mighty_blue", "not_so_tuff_blue",
      "dr_mortimer", "dr_mortimer_blight_of_the_pits",
      "break_stature_yellow",
      "thespian_charm_yellow", "liars_charm_yellow", "numbskull_charm_yellow", "cheaters_charm_yellow",
      "gang_robbery_yellow", "steal_victory_blue", "tempt_over_yellow",
      "destructive_fleetfoot_red", "destructive_fleetfoot_yellow", "destructive_fleetfoot_blue",
      "bash_guardian_red", "bash_brute_red",
      "clash_of_bravado_yellow",
      "condemn_to_slaughter_red", "condemn_to_slaughter_yellow", "condemn_to_slaughter_blue",
      "annexation_of_grandeur_yellow", "roiling_fissure_blue", "bloodtorn_bodice",
      "arcanic_reproach_blue", "caress_of_the_reaper_red", "dice_up_blue",
      "small_problem_yellow", "disturb_the_peace_red", "who_blinks_first_blue",
      "doomsaying_red"
    ];
    if (in_array($EffectContext, $autoChooseAllSameContexts, true))
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
      if (is_object($firstCog) && method_exists($firstCog, 'CardID')) {
        $allSameCog = true;
        $choicesCount = count($choices);
        for ($k = 1; $k < $choicesCount; ++$k) {
          $cog = MZIndexToObject($currentPlayer, $choices[$k]);
          if (!is_object($cog) || !method_exists($cog, 'CardID')
            || $cog->CardID() != $firstCog->CardID()
            || $cog->NumCounters() != $firstCog->NumCounters()
            || $cog->Modalities() != $firstCog->Modalities()) { $allSameCog = false; break; }
        }
        if ($allSameCog) { ContinueDecisionQueue($firstChoice); return true; }
      }
    }
  }
  if (str_starts_with($turn[0], "MULTICHOOSE") && !str_starts_with($turn[0], "MAYMULTICHOOSE")) {
    $params = explode("-", $turn[2]);
    $minimumCount = count($params) > 2 ? intval($params[2]) : 0;
    $choices = ($params[1] ?? "") === "" ? [] : explode(",", $params[1]);
    if ($minimumCount > 0 && count($choices) == $minimumCount) {
      ContinueDecisionQueue($choices);
      return true;
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
      ContinueDecisionQueue(explode(",", $turn[2], 2)[0]);
      return true;
    }
  }
  if ($turn[0] == "YESNO") {
    $resources = &GetResources($currentPlayer);
    $hand = &GetHand($currentPlayer);
    $handCount = count($hand);
    $handPieces = HandPieces();

    if ($EffectContext == "danse_macabre") {
      $available = intval($resources[0]);
      for ($i = 0; $i < $handCount && $available < 2; $i += $handPieces) {
        $available += PitchValue($hand[$i]);
      }
      if ($available < 2) {
        ContinueDecisionQueue("NO");
        return true;
      }
    }

    $cardsInHand = intdiv($handCount, $handPieces);
    $publicMaximumResources = intval($resources[0]) + (3 * $cardsInHand);
    if ($EffectContext == "prizeworn_pathfinders" && $publicMaximumResources < 1) {
      ContinueDecisionQueue("NO");
      return true;
    }
    if (($EffectContext == "staunch_response_red" || $EffectContext == "staunch_response_yellow" || $EffectContext == "staunch_response_blue")
      && $publicMaximumResources < 4) {
      ContinueDecisionQueue("NO");
      return true;
    }
  }
  return false;
}

function AutopassPhaseWithOneOption($phase)
{
  switch ($phase) {
    case "BUTTONINPUT":
    case "BUTTONINPUTNOPASS":
    case "CHOOSENUMBER":
    case "NUMBERINPUT":
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

  $hand = &GetHand($player);
  $handPieces = HandPieces();
  for($i=0, $count=count($hand); $i<$count; $i+=$handPieces) {
    if(IsPlayable($hand[$i], $phase, "HAND", $i, $restriction, $player)) return true;
  }

  $arsenal = &GetArsenal($player);
  $arsenalPieces = ArsenalPieces();
  for($i=0, $count=count($arsenal); $i<$count; $i+=$arsenalPieces) {
    if(IsPlayable($arsenal[$i], $phase, "ARS", $i, $restriction, $player)) return true;
  }

  $character = &GetPlayerCharacter($player);
  $characterPieces = CharacterPieces();
  for($i=0, $count=count($character); $i<$count; $i+=$characterPieces) {
    if($character[$i+1] == 2 && GetCharacterGemState($player, $character[$i]) && IsPlayable($character[$i], $phase, "CHAR", $i, $restriction, $player)) return true;
  }

  $allies = GetAllies($player);
  $allyPieces = AllyPieces();
  for($i=0, $count=count($allies); $i<$count; $i+=$allyPieces) {
    if(IsPlayable($allies[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }

  $items = &GetItems($player);
  $itemPieces = ItemPieces();
  for($i=0, $count=count($items); $i<$count; $i+=$itemPieces) {
    if (!ItemActiveStateTracked($items[$i]) || GetItemGemState($player, $items[$i], $i) != 0) {
      if(IsPlayable($items[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
    }
  }

  $auras = &GetAuras($player);
  $auraPieces = AuraPieces();
  for($i=0, $count=count($auras); $i<$count; $i+=$auraPieces) {
    if(IsPlayable($auras[$i], $phase, "PLAY", $i, $restriction, $player)) return true;
  }

  for ($i = 0, $count = $CombatChain->NumCardsActiveLink(); $i < $count; ++$i) {
    if(IsPlayable($CombatChain->Card($i, cardNumber:true)->ID(), $phase, "CC", $i, $restriction, $player)) return true;
  }

  $banish = &GetBanish($player);
  $banishPieces = BanishPieces();
  for($i=0, $count=count($banish); $i<$count; $i+=$banishPieces) {
    if(IsPlayable($banish[$i], $phase, "BANISH", $i, $restriction, $player)) return true;
  }
  $theirBanish = &GetBanish($otherPlayer);
  for($i=0, $count=count($theirBanish); $i<$count; $i+=$banishPieces) {
    if(IsPlayable($theirBanish[$i], $phase, "THEIRBANISH", $i, $restriction, $player)) return true;
  }

  $discard = GetDiscard($player);
  $discardPieces = DiscardPieces();
  for($i=0, $count=count($discard); $i<$count; $i+=$discardPieces) {
    if(IsPlayable($discard[$i], $phase, "GY", $i, $restriction, $player)) return true;
  }

  if ($character[0] == "dash_io" || $character[0] == "dash_database") {
    $deck = &GetDeck($player);
    if(count($deck) > 0 && $character[1] == 2) {
      if(IsPlayable($deck[0], $phase, "DECK", 0)) return true;
    }
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

