<?php
include_once "Libraries/PlayerSettings.php";
if (!function_exists('IsHideHandFromFriends')) {
    function IsHideHandFromFriends($player) { return false; }
}
function BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData = [], $includeInitialLoad = true, $inactive = false) {
  global $myHand, $myPitch, $myDeck, $myDiscard, $myBanish, $myArsenal, $myCharacter;
  global $myAuras, $myItems, $mySoul, $myAllies, $myPermanents, $myResources;
  global $theirHand, $theirPitch, $theirDeck, $theirDiscard, $theirBanish, $theirArsenal, $theirCharacter;
  global $theirAuras, $theirItems, $theirSoul, $theirAllies, $theirPermanents, $theirResources;
  global $combatChain, $combatChainState, $layers, $chainLinks, $chainLinkSummary, $landmarks;
  global $turn, $currentPlayer, $mainPlayer, $defPlayer, $firstPlayer, $currentTurn, $actionPoints;
  global $currentTurnEffects, $nextTurnEffects, $dqVars, $lastPlayed, $events;
  global $p1Key, $p2Key, $myHealth, $theirHealth, $winner;
  global $CombatChain, $CCS_AttackTargetUID, $CCS_WeaponIndex, $CCS_RequiredEquipmentBlock, $CCS_RequiredNegCounterEquipmentBlock, $CCS_CachedPreBlockValue;
  global $AIHasInfiniteHP, $EffectContext;
  global $p1IsPatron, $p2IsPatron, $p1MetafyTiers, $p2MetafyTiers, $p1IsAI, $p2IsAI;
  global $roguelikeGameID, $gameGUID, $p1uid, $p2uid;
  global $p1MetafyCommunities, $p2MetafyCommunities;
  global $p1TotalTime, $p2TotalTime, $ChainLinks;

  // Variables that will be set locally and need to be accessible to BuildPlayerInputPopup
  global $MyCardBack, $TheirCardBack, $otherPlayer, $isReactFE, $isGameOver, $isCasterMode, $isReplay, $isHideHandFromFriends, $viewerIsFriendOfOpponent;

  if (!IsGameNameValid($gameName)) {
    return "Invalid game name.";
  }

  if (!is_numeric($playerID)) {
    return "Invalid player ID.";
  }

  static $gameFileSeenAt = [];
  $nowTs = microtime(true);
  if (!isset($gameFileSeenAt[$gameName]) || $nowTs - $gameFileSeenAt[$gameName] > 5) {
    if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
      unset($gameFileSeenAt[$gameName]);
      return "Game no longer exists on the server.";
    }
    $gameFileSeenAt[$gameName] = $nowTs;
  }

  $buildCacheArr = ReadCacheArray($gameName) ?? [];
  $spectatorsPubliclyAllowed = ($buildCacheArr[8] ?? "") == "1";

  // Extract session data with defaults
  $sessionUserLoggedIn = $sessionData['userLoggedIn'] ?? false;
  $sessionUserName = $sessionData['userName'] ?? null;
  $sessionIsPvtVoidPatron = $sessionData['isPvtVoidPatron'] ?? false;
  $sessionPatreonCampaigns = $sessionData['patreonCampaigns'] ?? [];

  $friendListFromSession = [];

  $response = new stdClass();
  $response->playerInventory = [];

  $isGamePlayer = $playerID == 1 || $playerID == 2;
  $otherPlayer = $playerID == 1 ? 2 : 1;
  $cacheVal = intval($buildCacheArr[0] ?? 0);

  include_once "ParseGamestate.php";
  ParseGamestate();

  if (empty($p1uid) || empty($p2uid)) {
    $gameFileLines = @file("./Games/" . $gameName . "/GameFile.txt", FILE_IGNORE_NEW_LINES);
    if ($gameFileLines !== false && count($gameFileLines) >= 11) {
      if (empty($p1uid)) $p1uid = trim($gameFileLines[9]);
      if (empty($p2uid)) $p2uid = trim($gameFileLines[10]);
    }
  }

  // Auth validation
  $targetAuth = $playerID == 1 ? $p1Key : $p2Key;
  if ($playerID != 3 && $authKey !== $targetAuth) {
    return "Invalid Authkey";
  }

  $turnCount = count($turn);
  if ($turnCount == 0) {
    RevertGamestate();
    GamestateUpdated($gameName);
    return "Game state reverted.";
  }

  $isReactFE = true;
  $isGameOver = function_exists("IsGameOver") ? IsGameOver() : false;
  $isCasterMode = function_exists('IsCasterMode') ? IsCasterMode() : false;
  $isReplay = IsReplay();

  // Determine friend-based hand visibility using pre-loaded friend list from sessionData
  $isHideHandFromFriends = IsHideHandFromFriends($otherPlayer);
  $viewerIsFriendOfOpponent = false;
  $spectatorIsFriendOfP1 = false;
  $spectatorIsFriendOfP2 = false;
  
  $friendList = $sessionData['friendList'] ?? [];
  $friendSet = !empty($friendList) ? array_flip($friendList) : [];
  if ($playerID == 1 || $playerID == 2) {
    $opponentUID = $playerID == 1 ? $p2uid : $p1uid;
    $viewerIsFriendOfOpponent = isset($friendSet[$opponentUID]);
  } else if ($playerID == 3) {
    $spectatorIsFriendOfP1 = isset($friendSet[$p1uid]);
    $spectatorIsFriendOfP2 = isset($friendSet[$p2uid]);
  }

  // Check spectator permission: allowed if the host opened the game to public
  // spectators, or if the viewer is a friend of either player.
  if ($playerID == 3 && !$spectatorsPubliclyAllowed && !$spectatorIsFriendOfP1 && !$spectatorIsFriendOfP2) {
    return "Spectators not allowed.";
  }

  $response->lastUpdate = $cacheVal;

  // Spectator count and names
  $spectatorData = function_exists('GetActiveSpectators') ? GetActiveSpectators($gameName) : ['count' => 0, 'names' => []];
  $response->spectatorCount = $spectatorData['count'] ?? 0;
  $response->spectatorNames = $spectatorData['names'] ?? [];

  // send initial on-load information if requested
  if ($includeInitialLoad) {
    include "MenuFiles/ParseGamefile.php";
    $initialLoad = new stdClass();
    $initialLoad->gameGUID = $gameGUID;
    $initialLoad->playerName = $playerID == 1 ? $p1uid : $p2uid;
    $initialLoad->opponentName = $playerID == 1 ? $p2uid : $p1uid;
    static $contributors = ["sugitime" => true, "OotTheMonk" => true, "Launch" => true, "LaustinSpayce" => true, "Star_Seraph" => true, "Tower" => true, "Etasus" => true, "scary987" => true, "Celenar" => true, "DKGaming" => true, "Aegisworn" => true, "PvtVoid" => true, "Bluffkin" => true];

    $initialLoad->playerIsContributor = isset($contributors[$initialLoad->playerName]);
    $initialLoad->playerIsPatron = ($playerID == 1 ? $p1IsPatron : $p2IsPatron) ?: "";

    // Use cached Metafy tiers from game file (populated at JoinGame time)
    $initialLoad->playerMetafyTiers = ($playerID == 1 ? $p1MetafyTiers : $p2MetafyTiers) ?: [];

    $initialLoad->opponentIsContributor = isset($contributors[$initialLoad->opponentName]);
    $initialLoad->opponentIsPatron = ($playerID == 1 ? $p2IsPatron : $p1IsPatron) ?: "";
    $initialLoad->opponentMetafyTiers = ($playerID == 1 ? $p2MetafyTiers : $p1MetafyTiers) ?: [];

    $initialLoad->roguelikeGameID = $roguelikeGameID;
    $initialLoad->playerIsPvtVoidPatron = $initialLoad->playerName == "PvtVoid" || $playerID == 1 && $sessionIsPvtVoidPatron;
    $initialLoad->opponentIsPvtVoidPatron = $initialLoad->opponentName == "PvtVoid" || $playerID == 2 && $sessionIsPvtVoidPatron;
    $initialLoad->isOpponentAI = $playerID == 1 ? ($p2IsAI == "1") : ($p1IsAI == "1");
    $initialLoad->gameFormat = $format;

    $initialLoad->altArts = [];
    $initialLoad->opponentAltArts = [];

    // For spectators (playerID==3), resolve alt arts from the game file usernames directly.
    // p1uid maps to the "player" (altArts) slot; p2uid maps to the "opponent" (opponentAltArts) slot.
    $altArtsPlayerName  = $playerID == 3 ? $p1uid : $initialLoad->playerName;
    $altArtsOpponentName = $playerID == 3 ? $p2uid : $initialLoad->opponentName;
    $altArtsPlayerID    = $playerID == 3 ? 1 : $playerID;
    $altArtsOpponentID  = $playerID == 3 ? 2 : $otherPlayer;

    $patreonCampaigns = PatreonCampaign::cases();
    $metafyCommunityMap = [];
    foreach (MetafyCommunity::cases() as $case) {
      $metafyCommunityMap[$case->value] = $case;
    }

    if($playerID == 3 || !AltArtsDisabled($playerID))
    {
      foreach($patreonCampaigns as $campaign) {
        $sessionID = $campaign->SessionID();
        $isPatronOfCampaign = $playerID != 3 && ($sessionPatreonCampaigns[$sessionID] ?? false);

        if ($playerID != 3 && $sessionID == "isPvtVoidPatron") {
          $isPatronOfCampaign = $sessionUserName == "PvtVoid" || ($sessionPatreonCampaigns[$sessionID] ?? false);
        }

        if($isPatronOfCampaign || $campaign->IsTeamMember($sessionUserName ?? '') || $campaign->IsTeamMember($altArtsPlayerName)) {
          $altArts = $campaign->AltArts($altArtsPlayerID);
          if($altArts == "") continue;
          $altArts = explode(",", $altArts);
          $altArtsCount = count($altArts);
          $campaignName = $campaign->CampaignName();
          for($i = 0; $i < $altArtsCount; ++$i) {
            $arr = explode("=", $altArts[$i]);
            $altArt = new stdClass();
            $altArt->name = $campaignName . ($altArtsCount > 1 ? " " . $i + 1 : "");
            $altArt->cardId = $arr[0];
            $altArt->altPath = $arr[1];
            $initialLoad->altArts[] = $altArt;
          }
        }
      }

      // Add Metafy community alt arts from cached game file data
      $playerCommunities = $playerID == 3 ? ($p1MetafyCommunities ?? []) : ($playerID == 1 ? ($p1MetafyCommunities ?? []) : ($p2MetafyCommunities ?? []));
      if (is_array($playerCommunities)) {
        foreach ($playerCommunities as $community) {
          $communityId = $community['id'] ?? null;
          $metafyCommunity = $communityId ? ($metafyCommunityMap[$communityId] ?? null) : null;
          if ($metafyCommunity !== null) {
            $metafyAltArts = $metafyCommunity->AltArts();
            if (!empty($metafyAltArts)) {
              $metafyAltArtsCount = count($metafyAltArts);
              $communityName = $metafyCommunity->CommunityName();
              for($i = 0; $i < $metafyAltArtsCount; ++$i) {
                $arr = explode("=", $metafyAltArts[$i]);
                if (count($arr) === 2) {
                  $altArt = new stdClass();
                  $altArt->name = $communityName . ($metafyAltArtsCount > 1 ? " " . $i + 1 : "");
                  $altArt->cardId = trim($arr[0]);
                  $altArt->altPath = trim($arr[1]);
                  $initialLoad->altArts[] = $altArt;
                }
              }
            }
          }
        }
      }
    }

    // Get opponent's alt arts
    if($playerID == 3 || !AltArtsDisabled($playerID))
    {
      foreach($patreonCampaigns as $campaign) {
        $isOpponentSupporterOfCampaign = $campaign->IsTeamMember($altArtsOpponentName);

        if ($campaign->SessionID() == "isPvtVoidPatron") {
          $isOpponentSupporterOfCampaign = $altArtsOpponentName == "PvtVoid" || $campaign->IsTeamMember($altArtsOpponentName);
        }

        if($isOpponentSupporterOfCampaign) {
          $opponentAltArts = $campaign->AltArts($altArtsOpponentID);
          if($opponentAltArts == "") continue;
          $opponentAltArts = explode(",", $opponentAltArts);
          $opponentAltArtsCount = count($opponentAltArts);
          $campaignName = $campaign->CampaignName();
          for($i = 0; $i < $opponentAltArtsCount; ++$i) {
            $arr = explode("=", $opponentAltArts[$i]);
            $opponentAltArt = new stdClass();
            $opponentAltArt->name = $campaignName . ($opponentAltArtsCount > 1 ? " " . $i + 1 : "");
            $opponentAltArt->cardId = $arr[0];
            $opponentAltArt->altPath = $arr[1];
            $initialLoad->opponentAltArts[] = $opponentAltArt;
          }
        }
      }

      // Add opponent's Metafy community alt arts from cached game file data
      $opponentCommunities = $playerID == 3 ? ($p2MetafyCommunities ?? []) : ($playerID == 1 ? ($p2MetafyCommunities ?? []) : ($p1MetafyCommunities ?? []));
      if (is_array($opponentCommunities)) {
        foreach ($opponentCommunities as $community) {
          $communityId = $community['id'] ?? null;
          $metafyCommunity = $communityId ? ($metafyCommunityMap[$communityId] ?? null) : null;
          if ($metafyCommunity !== null) {
            $opponentMetafyAltArts = $metafyCommunity->AltArts();
            if (!empty($opponentMetafyAltArts)) {
              $opponentMetafyAltArtsCount = count($opponentMetafyAltArts);
              $communityName = $metafyCommunity->CommunityName();
              for($i = 0; $i < $opponentMetafyAltArtsCount; ++$i) {
                $arr = explode("=", $opponentMetafyAltArts[$i]);
                if (count($arr) === 2) {
                  $opponentAltArt = new stdClass();
                  $opponentAltArt->name = $communityName . ($opponentMetafyAltArtsCount > 1 ? " " . $i + 1 : "");
                  $opponentAltArt->cardId = trim($arr[0]);
                  $opponentAltArt->altPath = trim($arr[1]);
                  $initialLoad->opponentAltArts[] = $opponentAltArt;
                }
              }
            }
          }
        }
      }
    }
    $response->initialLoad = $initialLoad;
  }

  $blankZone = 'blankZone';

  //Choose Cardback
  $MyCardBack = GetCardBack($playerID);
  $TheirCardBack = GetCardBack($otherPlayer);
  $borderColor = 0;

  $response->MyPlaymat = IsColorblindMode($playerID) ? 0 : GetPlaymat($playerID);
  if(isset($initialLoad) && $initialLoad->isOpponentAI) $response->TheirPlaymat = IsColorblindMode($playerID) ? 0 : 2;
  else $response->TheirPlaymat = IsColorblindMode($playerID) ? 0 : GetPlaymat($otherPlayer);
  if ($response->MyPlaymat == 0) $response->TheirPlaymat = 0;

  //Display active chain link
  $activeChainLink = new stdClass();
  $combatChainReactions = [];
  $combatChainCount = count($combatChain);
  $combatChainPieceCount = CombatChainPieces();
  $turnPhase = $turn[0];
  for ($i = 0; $i < $combatChainCount; $i += $combatChainPieceCount) {
    $action = $currentPlayer == $playerID && $turnPhase != "P" &&
      $currentPlayer == $combatChain[$i + 1] &&
      AbilityPlayableFromCombatChain($combatChain[$i]) &&
      IsPlayable($combatChain[$i], $turnPhase, "PLAY", $i) ? 21 : 0;

    $borderColor = $action == 21 ? 6 : ($combatChain[$i + 1] == $playerID ? 1 : 2);
    if($playerID == 3) $borderColor = $combatChain[$i + 1] == $otherPlayer ? 2 : 1;

    $countersMap = new stdClass();
    if ($i == 0 && HasAimCounter()) $countersMap->aim = 1;

    if ($i == 0) {
      $activeChainLink->attackingCard = JSONRenderedCard(
        cardNumber: $combatChain[$i],
        controller: $combatChain[$i + 1],
        action: $action,
        actionDataOverride: '0',
        borderColor: $borderColor,
        countersMap: $countersMap,
      );
      continue;
    }
    $cardID = $turnPhase == "B" && ($playerID == $mainPlayer || $playerID == 3) ? $TheirCardBack : $combatChain[$i];
    $combatChainReactions[] = JSONRenderedCard(
      cardNumber: $cardID,
      controller: $combatChain[$i + 1] ?? NULL,
      action: $action,
      actionDataOverride: strval($i),
      borderColor: $borderColor,
      countersMap: $countersMap,
    );
  }

  $totalPower = 0;
  $totalDefense = 0;
  if ($combatChainCount > 0) {
    $chainPowerModifiers = [];
    EvaluateCombatChain($totalPower, $totalDefense, $chainPowerModifiers);
  }
  $blockVal = $turn[0] == "B" && ($playerID == $mainPlayer || $playerID == 3) ? 0 : $totalDefense;
  $powVal = $turn[0] == "B" && ($playerID == $mainPlayer || $playerID == 3) ? $combatChainState[$CCS_CachedPreBlockValue] : $totalPower;
  $activeChainLink->totalPower = $powVal;

  $activeChainLink->totalDefense = $blockVal;
  $activeChainLink->reactions = $combatChainReactions;
  $activeChainLink->attackTarget = GetAttackTargetNames($mainPlayer);
  $activeChainLink->damagePrevention = ($combatChainCount > 0 && CanDamageBePrevented($mainPlayer, 0, "COMBAT", $combatChain[0])) ? GetDamagePrevention($defPlayer, $totalPower) : 0;
  $activeChainLink->goAgain = CachedAttackHasGoAgain();
  $activeChainLink->dominate = CachedDominateActive();
  $activeChainLink->overpower = CachedOverpowerActive();
  $activeChainLink->confidence = SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence");
  $activeChainLink->activeOnHits = ActiveOnHits();
  if ($combatChainState[$CCS_RequiredEquipmentBlock] > NumEquipBlock("EQUIP")) $activeChainLink->numRequiredEquipBlock = $combatChainState[$CCS_RequiredEquipmentBlock];
  elseif ($combatChainState[$CCS_RequiredNegCounterEquipmentBlock] > NumNegCounterEquipBlock()) $activeChainLink->numRequiredEquipBlock = $combatChainState[$CCS_RequiredNegCounterEquipmentBlock];
  $activeChainLink->wager = CachedWagerActive();
  $activeChainLink->phantasm = CachedPhantasmActive();
  $activeChainLink->fusion = CachedFusionActive();
  $hasCurrentLink = $CombatChain->HasCurrentLink();
  $staticBuffsArr = [];
  if ($hasCurrentLink) {
    $rawBuffs = $CombatChain->AttackCard()->StaticBuffs();
    if ($rawBuffs !== "-") $staticBuffsArr = explode(",", $rawBuffs);
  }
  if ($hasCurrentLink) $activeChainLink->tower = IsTowerActive();
  if ($hasCurrentLink) $activeChainLink->piercing = IsPiercingActive($combatChain[0]);
  if ($hasCurrentLink) $activeChainLink->combo = ComboActive();
  if ($hasCurrentLink) $activeChainLink->highTide = IsHighTideActive();

  $activeChainLink->fused = false;

  $response->activeChainLink = $activeChainLink;

  $layersCount = count($layers);
  $chainLinksCount = count($chainLinks);

  // Tracker State — deprecated, no longer used
  // $tracker = new stdClass();
  // $tracker->color = $playerID == $currentPlayer ? "blue" : "red";
  // if ($turnPhase == "B" || $layersCount > 0 && $layers[0] == "DEFENDSTEP") $tracker->position = "Defense";
  // else if ($turnPhase == "A" || $turnPhase == "D") $tracker->position = "Reactions";
  // else if ($turnPhase == "PDECK" || $turnPhase == "ARS" || $layersCount > 0 && ($layers[0] == "ENDTURN" || $layers[0] == "FINALIZECHAINLINK")) $tracker->position = "EndTurn";
  // else $tracker->position = ($chainLinksCount > 0 || $layersCount > 0 && $layers[0] == "ATTACKSTEP") ? "Combat" : "Main";
  // $response->tracker = $tracker;

  //Display Layer
  $layerObject = new stdClass;
  $layerContents = [];
  $layerPieces = LayerPieces();
  static $specialLayersSet = ["LAYER" => true, "TRIGGER" => true, "MELD" => true, "PRETRIGGER" => true, "ABILITY" => true, "ATTACK" => true];
  $reorderableLayers = [];
  $numReorderable = 0;
  for ($i = $layersCount - $layerPieces; $i >= 0; $i -= $layerPieces) {
    $layerName = isset($specialLayersSet[$layers[$i]]) ? $layers[$i+2] : $layers[$i];
    $layerContents[] = JSONRenderedCard(cardNumber: $layerName, controller: $layers[$i + 1]);

    $layer = new stdClass();
    $borderColor = null;
    if (str_contains($layers[$i+2], "sigil") && $layers[$i+4] == "DESTROY") $borderColor = 9;
    $layer->card = JSONRenderedCard(cardNumber: $layerName, controller: $layers[$i + 1], lightningPlayed:"SKIP", borderColor:$borderColor);
    $layer->layerID = $i;
    $layer->isReorderable = false;
    $reorderableLayers[] = $layer;
  }
  $layerObject->target = GetAttackTargetNames($mainPlayer);
  $layerObject->layerContents = $layerContents;
  $layerObject->reorderableLayers = $reorderableLayers;
  $response->layerDisplay = $layerObject;

  //Their Hand
  $theirHandContents = [];
  $theirBanishCount = count($theirBanish);
  $myBanishCount = count($myBanish);
  $theirDiscardCount = count($theirDiscard);
  $banishPieces = BanishPieces();
  $discardPieces = DiscardPieces();

  for ($i=0; $i < $theirBanishCount; $i += $banishPieces) {
    if (PlayableFromBanish($theirBanish[$i], $theirBanish[$i+1], player:$otherPlayer) && $theirBanish[$i+1] != "TRAPDOOR") {
      $theirHandContents[] = JSONRenderedCard($theirBanish[$i], borderColor:7);
    }
  }

  for ($i=0; $i < $myBanishCount; $i += $banishPieces) {
    if(PlayableFromOtherPlayerBanish($myBanish[$i], $myBanish[$i+1], $otherPlayer)) {
      $theirHandContents[] = JSONRenderedCard($myBanish[$i], borderColor:7);
    }
  }

  for ($i=0; $i < $theirDiscardCount; $i += $discardPieces) {
    if (isset($theirDiscard[$i+2]) && PlayableFromGraveyard($theirDiscard[$i], $theirDiscard[$i+2], $otherPlayer, $i)) {
      $theirHandContents[] = JSONRenderedCard($theirDiscard[$i], borderColor:7);
    }
  }

  $theirHandCount = count($theirHand);
  $spectatorCanSeeP1Hand = $playerID == 3 && ($isCasterMode || ($spectatorIsFriendOfP1 && !IsHideHandFromFriends(1)));
  $showTheirHand = $isGameOver || $isReplay || ($playerID == 3 && $spectatorIsFriendOfP1 && !IsHideHandFromFriends(1));
  
  for ($i = 0; $i < $theirHandCount; ++$i) {
    $theirHandContents[] = JSONRenderedCard($showTheirHand ? $theirHand[$i] : $TheirCardBack);
  }

  $response->opponentHand = $theirHandContents;

  //Their Life
  $response->opponentHealth = $theirHealth;
  //Their Soul Count
  $theirSoulCount = count($theirSoul);
  $response->opponentSoulCount = $theirSoulCount;

  $opponentSoulArr = [];
  $soulPieces = SoulPieces();
  for ($i = 0; $i < $theirSoulCount; $i += $soulPieces) {
    $opponentSoulArr[] = JSONRenderedCard($theirSoul[$i]);
  }
  $response->opponentSoul = $opponentSoulArr;

  //Display their discard, pitch, deck, and banish
  $opponentDiscardArray = [];
  for ($i = 0; $i < $theirDiscardCount; $i += $discardPieces) {
    if (isset($theirDiscard[$i + 2])) {
      $mod = $theirDiscard[$i + 2];
      $cardID = isFaceDownMod($mod) ? $TheirCardBack : $theirDiscard[$i];
      $opponentDiscardArray[] = JSONRenderedCard($cardID);
    }
  }
  $response->opponentDiscard = $opponentDiscardArray;

  $response->opponentPitchCount = $theirResources[0];
  $opponentPitchArr = [];
  $pitchPieces = PitchPieces();
  $showOppPitch = $turnPhase != "PDECK";
  $theirPitchCount = count($theirPitch);
  for ($i = $theirPitchCount - $pitchPieces; $i >= 0; $i -= $pitchPieces) {
    $opponentPitchArr[] = JSONRenderedCard($showOppPitch ? $theirPitch[$i] : $TheirCardBack);
  }
  $response->opponentPitch = $opponentPitchArr;

  $theirDeckCount = count($theirDeck);
  $response->opponentDeckCount = $theirDeckCount;
  $response->opponentDeckCard = JSONRenderedCard($theirDeckCount > 0 ? $TheirCardBack : $blankZone);
  $opponentDeckArr = [];
  $deckPieces = DeckPieces();
  if ($isGameOver || $isReplay) {
    for($i=0; $i<$theirDeckCount; $i+=$deckPieces) {
      $opponentDeckArr[] = JSONRenderedCard($theirDeck[$i]);
    }
  }
  $theirBlessingsCount = SearchCount(SearchDiscardForCard($otherPlayer, "count_your_blessings_red", "count_your_blessings_yellow", "count_your_blessings_blue"));
  if ($theirBlessingsCount > 0) {
    $response->opponentBlessingsCount = $theirBlessingsCount;
  }
  $response->opponentDeck = $opponentDeckArr;

  $response->opponentCardBack = JSONRenderedCard($TheirCardBack);

  //Their Banish
  $opponentBanishArr = [];
  for ($i = 0; $i < $theirBanishCount; $i += $banishPieces) {
    $overlay = 0;
    $border = 0;
    $cardID = $theirBanish[$i];
    $mod = strstr($theirBanish[$i + 1], '-', true) ?: $theirBanish[$i + 1];
    $action = $currentPlayer == $playerID && IsPlayable($theirBanish[$i], $turnPhase, "THEIRBANISH", $i) ? 15 : 0;
    $label = "";
    if (isFaceDownMod($mod) && !$isGameOver) {
      $cardID = $TheirCardBack;
    }
    else if ($mod == "INT") {
        $overlay = 1;
        $label = "Intimidated";
    }
    else $border = CardBorderColor($theirBanish[$i], "BANISH", $action > 0, $playerID, $mod);

    $opponentBanishArr[] = JSONRenderedCard($cardID, $action, $overlay, borderColor: $border, actionDataOverride: strval($i), label: $label);
  }
  $response->opponentBanish = $opponentBanishArr;

  $theirCountBloodDebt = SearchCount(SearchBanish($otherPlayer, "", "", -1, -1, "", "", true));
  if ($theirCountBloodDebt > 0) {
    $response->opponentBloodDebtCount = $theirCountBloodDebt;
    $response->isOpponentBloodDebtImmune = IsImmuneToBloodDebt($otherPlayer);
  }
  if (GeneratedHasEssenceOfEarth($theirCharacter[0])) {
    $response->opponentEarthCount = SearchCount(SearchBanish($otherPlayer, talent:"EARTH"));
  }

  //Now display their character and equipment
  $numWeapons = 0;
  $characterContents = [];
  $theirCharacterCount = count($theirCharacter);
  $characterPieces = CharacterPieces();
  for ($i = 0; $i < $theirCharacterCount; $i += $characterPieces) {
    $label = "";
    $border = 0;
    $theirChar = $theirCharacter[$i];
    if ($theirCharacter[$i + 1] == 4) $theirChar = "DUMMYDISHONORED";
    $powerCounters = 0;
    $counters = 0;
    $type = CardType($theirChar);
    if (TypeContains($theirChar, "D")) $type = "C";
    $sTypeArr = explode(",", CardSubType($theirChar, $theirCharacter[$i+11]));
    $sType = $sTypeArr[0];
    static $equipSlots = ["Head" => true, "Chest" => true, "Arms" => true, "Legs" => true];
    foreach ($sTypeArr as $st) {
      if (isset($equipSlots[$st])) { $sType = $st; break; }
    }
    $border = CardBorderColor($theirChar, "THEIRCHAR", true, $otherPlayer);
    if (TypeContains($theirCharacter[$i], "W", $playerID)) {
      ++$numWeapons;
      if ($numWeapons > 1) {
        $type = "E";
        $sType = "Off-Hand";
      }
      $label = WeaponHasGoAgainLabel($i, $otherPlayer) ? "Go Again" : "";
      $weaponPowerModifiers = [];
      $powerCounters = $theirCharacter[$i + 3];
      if(MainCharacterPowerModifiers($weaponPowerModifiers, $i, true, $otherPlayer) > 0 ||
        SearchCurrentTurnEffectsForPartialId($theirCharacter[$i + 11] ?? "-")) $border = 5;
    }
    if ($theirCharacter[$i + 2] > 0) $counters = $theirCharacter[$i + 2];
    $counters = $theirCharacter[$i + 1] != 0 ? $counters : 0;
    // hide opponent's equipment while deciding on adaptive stuff
    $facing = ($i != 0 && SearchCurrentTurnEffects("HIDEOPEQUIP", $playerID)) ? "DOWN" : $theirCharacter[$i + 12];
    if($isGameOver) $theirCharacter[$i + 12] = "UP";
    if ($theirCharacter[$i + 12] == "UP" || $playerID == 3 && $isCasterMode || $isGameOver) {
      if($theirCharacter[$i + 1] > 0) {
      $characterContents[] = JSONRenderedCard(
        $theirChar,
        borderColor: $border,
        overlay: $theirCharacter[$i + 1] != 2 && $theirChar != "DUMMYDISHONORED" ? 1 : 0,
        counters: $counters,
        defCounters: $theirCharacter[$i + 4],
        powerCounters: $powerCounters,
        controller: $otherPlayer,
        type: $type,
        sType: $sType,
        isFrozen: IsFrozenMZ($theirCharacter, "CHAR", $i, $otherPlayer),
        onChain: $turnPhase == "B" && ($playerID == $mainPlayer || $playerID == 3) && SearchCombatChainForIndex($theirCharacter[$i], $otherPlayer) != -1 ? 0 : $theirCharacter[$i + 6] == 1,
        isBroken: $theirCharacter[$i + 1] == 0,
        label: $label,
        facing: $facing,
        numUses: $theirCharacter[$i + 5],
        subcard: isSubcardEmpty($theirCharacter, $i) ? NULL : $theirCharacter[$i+10],
        marked: $theirCharacter[$i + 13] == 1,
        tapped: $theirCharacter[$i + 14] == 1
        );
      }
    } else {
      $characterContents[] = JSONRenderedCard(
          $TheirCardBack,
          overlay: $theirCharacter[$i + 1] != 2 ? 1 : 0,
          counters: $counters,
          defCounters: $theirCharacter[$i + 4],
          powerCounters: $powerCounters,
          controller: $otherPlayer,
          type: $type,
          sType: $sType,
          label: $label,
          facing: $theirCharacter[$i + 12],
          subcard: isSubcardEmpty($theirCharacter, $i) ? NULL : $theirCharacter[$i+10],
          marked: $theirCharacter[$i + 13] == 1,
          tapped: $theirCharacter[$i + 14] == 1
          );
    }
  }

  $response->opponentEquipment = $characterContents;

  //My Hand
  $restriction = "";
  $actionType = $turnPhase == "ARS" ? 4 : 27;
  $resourceRestrictedCard = "";
  if(isset($turn[3])) $resourceRestrictedCard = $turn[3];
  if (strpos($turnPhase, "CHOOSEHAND") !== false && ($turnPhase != "MULTICHOOSEHAND" || $turnPhase != "MAYMULTICHOOSEHAND")) $actionType = 16;
  $myHandContents = [];
  $myHandCount = count($myHand);
  $handPieces = HandPieces();
  for ($i = 0; $i < $myHandCount; $i += $handPieces) {
    if ($playerID == 3) {
      $spectatorCanSeeP2Hand = $isCasterMode || $isGameOver || ($spectatorIsFriendOfP2 && !IsHideHandFromFriends(2));
      if($spectatorCanSeeP2Hand) $myHandContents[] = JSONRenderedCard(cardNumber: $myHand[$i], controller: 2);
      else $myHandContents[] = JSONRenderedCard(cardNumber: $MyCardBack, controller: 2);
    } else {
      $playable = ($playerID == $currentPlayer) ? $turnPhase == "ARS" || IsPlayable($myHand[$i], $turnPhase, "HAND", -1, $restriction, pitchRestriction:$resourceRestrictedCard) || $actionType == 16 && $turnPhase != "MULTICHOOSEHAND" && strpos("," . $turn[2] . ",", "," . $i . ",") !== false && $restriction == "" : false;
      if ($restriction == "" && str_contains(GetAbilityTypes($myHand[$i], -1, "HAND"), "I") && InstantRestricted($myHand[$i], "HAND", -1) && !$playable) {
        $restriction = "Instant cannot be played.";
      }
      $border = CardBorderColor($myHand[$i], "HAND", $playable, $playerID);
      $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? $actionType : 0;
      if ($restriction !== "" && str_contains($restriction, ' ')) $restriction = str_replace(' ', '_', $restriction);
      $actionDataOverride = ($actionType == 16 || $actionType == 27) ? strval($i) : $myHand[$i];
      
      if (isset($myHand[$i + $handPieces - 1])) {
        $label = GetCardEffectLabel($myHand[$i + $handPieces - 1], $currentTurnEffects);
      }
      
      $myHandContents[] = JSONRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction, label: $label);
    }
  }
  $response->playerHand = $myHandContents;

  //My Life
  $response->playerHealth = $myHealth;
  //My Soul Count
  $mySoulCount = count($mySoul);
  $response->playerSoulCount = $mySoulCount;

  $playerSoulArr = [];
  for ($i = 0; $i < $mySoulCount; $i += $soulPieces) {
    $playerSoulArr[] = JSONRenderedCard($mySoul[$i]);
  }
  $response->playerSoul = $playerSoulArr;

  //My Discard
  $playerDiscardArr = [];
  $myDiscardCount = count($myDiscard);
  for($i = 0; $i < $myDiscardCount; $i += $discardPieces) {
    if (isset($myDiscard[$i+2])) {
      $overlay = 0;
      $action = $currentPlayer == $playerID && (PlayableFromGraveyard($myDiscard[$i], $myDiscard[$i+2], $playerID, $i) || AbilityPlayableFromGraveyard($myDiscard[$i], $i)) && IsPlayable($myDiscard[$i], $turnPhase, "GY", $i) ? 36 : 0;
      $mod = strstr($myDiscard[$i + 2], '-', true) ?: $myDiscard[$i + 2];
      $border = CardBorderColor($myDiscard[$i], "GY", $action == 36, $playerID, $mod, $i);
      $cardID = $myDiscard[$i];
      if($mod == "DOWN") {
        $overlay = 1;
        $border = 0;
      }
      elseif (isFaceDownMod($mod) && $playerID == 3) $cardID = $MyCardBack;
      $playerDiscardArr[] = JSONRenderedCard($cardID, action: $action, overlay: $overlay, borderColor: $border, actionDataOverride: strval($i));
    }
  }
  $myBlessingsCount = SearchCount(SearchDiscardForCard($playerID, "count_your_blessings_red", "count_your_blessings_yellow", "count_your_blessings_blue"));
  if ($myBlessingsCount > 0) {
    $response->myBlessingsCount = $myBlessingsCount;
  }
  $response->playerDiscard = $playerDiscardArr;

  //My Pitch
  $response->playerPitchCount = $myResources[0];
  $playerPitchArr = [];
  $myPitchCount = count($myPitch);
  for($i = $myPitchCount - $pitchPieces; $i >= 0; $i -= $pitchPieces) {
    $playerPitchArr[] = JSONRenderedCard($myPitch[$i]);
  }
  $response->playerPitch = $playerPitchArr;

  //My Deck
  $myDeckCount = count($myDeck);
  $response->playerDeckCount = $myDeckCount;
  $playerHero = ShiyanaCharacter($myCharacter[0], $playerID);
  static $blockDIOCards = ["spark_of_genius_yellow" => true, "teklovossens_workshop_red" => true, "teklovossens_workshop_yellow" => true, "teklovossens_workshop_blue" => true, "viziertronic_model_i" => true];
  static $blockDIOEvents = ["DOCRANK" => true, "CHOOSEMULTIZONE" => true];
  $blockDIO = isset($blockDIOEvents[$turnPhase]) && isset($EffectContext) && isset($blockDIOCards[$EffectContext]);
  if($playerID < 3 && $myDeckCount > 0 && $myCharacter[1] < 3 && ($playerHero == "dash_database" || $playerHero == "dash_io") && $turnPhase != "OPT" && $turnPhase != "P" && $turnPhase != "CHOOSETOPOPPONENT" && !$blockDIO) {
    $playable = $playerID == $currentPlayer && IsPlayable($myDeck[0], $turnPhase, "DECK", 0);
    $response->playerDeckCard = JSONRenderedCard($myDeck[0], action:$playable ? 35 : 0, actionDataOverride:strval(0), borderColor: $playable ? 6 : 0, controller:$playerID);
  }
  else $response->playerDeckCard = JSONRenderedCard($myDeckCount > 0 ? $MyCardBack : $blankZone);
  $playerDeckArr = [];
  $response->playerDeckPopup = false;
  if ($playerID == $currentPlayer || $isGameOver || $isReplay) {
    if(($turnPhase == "CHOOSEMULTIZONE" || $turnPhase == "MAYCHOOSEMULTIZONE") && isset($turn[2]) && substr($turn[2], 0, 6) === "MYDECK" && $turn[2] != "MYDECK-0"
    || $turnPhase == "MAYCHOOSEDECK"
    || $turnPhase == "CHOOSEDECK"
    || $turnPhase == "MULTICHOOSEDECK"
    || $isGameOver || $isReplay) {
      for($i=0; $i<$myDeckCount; $i+=$deckPieces) {
        $playerDeckArr[] = JSONRenderedCard($myDeck[$i]);
      }
    }
  }
  $response->playerDeck = $playerDeckArr;

  //My Card Back
  $response->playerCardBack = JSONRenderedCard($MyCardBack);

  $bottomPlayer = $otherPlayer == 1 ? 2 : 1;
  //My Banish
  $playerBanishArr = [];
  for ($i = 0; $i < $myBanishCount; $i += $banishPieces) {
    $label = "";
    $overlay = 0;
    $action = $currentPlayer == $playerID && IsPlayable($myBanish[$i], $turnPhase, "BANISH", $i) ? 14 : 0;
    $mod = strstr($myBanish[$i + 1], '-', true) ?: $myBanish[$i + 1];
    $border = CardBorderColor($myBanish[$i], "BANISH", $action > 0, $playerID, $mod);
    $cardID = $myBanish[$i];
    if($mod == "DOWN") {
      $overlay = 1;
      $border = 0;
    }
    elseif (isFaceDownMod($mod) && $playerID == 3 && !$isGameOver) $cardID = $MyCardBack;
    if ($mod == "INT") {
      $overlay = 1;
      $label = "Intimidated";
    }
    if (isset($myBanish[$i + 2])) {
      $label = GetCardEffectLabel($myBanish[$i + 2], $currentTurnEffects);
    }
    $playerBanishArr[] = JSONRenderedCard($cardID, $action, $overlay, borderColor: $border, actionDataOverride: strval($i), label: $label);
  }
  $response->playerBanish = $playerBanishArr;

  $myBloodDebtCount = SearchCount(SearchBanish($playerID == 3 ? $bottomPlayer : $playerID, "", "", -1, -1, "", "", true));
  if ($myBloodDebtCount > 0) {
    $response->myBloodDebtCount = $myBloodDebtCount;
    $response->amIBloodDebtImmune = IsImmuneToBloodDebt($playerID);
  }
  if (GeneratedHasEssenceOfEarth($myCharacter[0])) {
    $response->myEarthCount = SearchCount(SearchBanish($playerID, talent:"EARTH"));
  }

  //Now display my character and equipment
  $numWeapons = 0;
  $myCharData = [];
  $myCharacterCount = count($myCharacter);
  for ($i = 0; $i < $myCharacterCount; $i += $characterPieces) {
    $restriction = "";
    $counters = 0;
    $powerCounters = 0;
    $gem = 0;
    $label = "";
    $border = 0;
    $myChar = $myCharacter[$i] ?? "-";
    if (($myCharacter[$i + 1] ?? 0) == 4) $myChar = "DUMMYDISHONORED";
    if (($myCharacter[$i + 2] ?? 0) > 0) $counters = $myCharacter[$i + 2];
    $playable = $playerID == $currentPlayer && ($myCharacter[$i + 1] ?? 0) > 0 && IsPlayable($myChar, $turnPhase, "CHAR", $i, $restriction);
    $border = CardBorderColor($myChar, "CHAR", $playable, $playerID);
    $type = CardType($myChar);
    if (TypeContains($myChar, "D")) $type = "C";
    $sTypeArr = explode(",", CardSubType($myChar, $myCharacter[$i+11] ?? ""));
    $sType = $sTypeArr[0];
    foreach ($sTypeArr as $st) {
      if (isset($equipSlots[$st])) { $sType = $st; break; }
    }
    if (TypeContains($myChar, "W", $playerID)) {
      ++$numWeapons;
      if ($numWeapons > 1) {
        $type = "E";
        $sType = "Off-Hand";
      }
      $label = WeaponHasGoAgainLabel($i, $playerID) ? "Go Again" : "";
      $weaponPowerModifiers = [];
    if (!$playable) {
        if (MainCharacterPowerModifiers($weaponPowerModifiers, $i, true, $playerID) > 0 ||
            SearchCurrentTurnEffectsForPartialId($myCharacter[$i + 11] ?? "-")) {
            $border = 5;
        }
    }
      $powerCounters = $myCharacter[$i + 3] ?? 0;
    }
    if (($myCharacter[$i + 9] ?? 0) != 2 && ($myCharacter[$i + 1] ?? 0) != 0 && $playerID != 3) {
      $gem = ($myCharacter[$i + 9] ?? 0) == 1 ? 1 : 2;
    }
    if ($restriction !== "" && str_contains($restriction, ' ')) $restriction = str_replace(' ', '_', $restriction);
    if($isGameOver) ($myCharacter[$i + 12] ?? "-") == "UP";
    if($playerID == 3 &&( $myCharacter[$i + 12] ?? "-") == "DOWN" && !$isGameOver) {
      $myCharData[] = JSONRenderedCard(
        $MyCardBack);
    }
    else{
      if(($myCharacter[$i + 1] ?? 0) > 0) {
        $myCharData[] = JSONRenderedCard(
          $myChar,
          $currentPlayer == $playerID && $playable ? 3 : 0,
          ($myCharacter[$i + 1] ?? 0) != 2 && $myChar != "DUMMYDISHONORED"? 1 : 0,
          $border,
          ($myCharacter[$i + 1] ?? 0) != 0 ? $counters : 0,
          strval($i),
          0,
          $myCharacter[$i + 4] ?? "",
          $powerCounters,
          $playerID,
          $type,
          $sType,
          $restriction,
          ($myCharacter[$i + 1] ?? 0) == 0,
          ($myCharacter[$i + 6] ?? 0) == 1,
          ($myCharacter[$i + 8] ?? 0) == 1,
          $gem,
          label: $label,
          facing: $myCharacter[$i + 12] ?? "-",
          numUses: $myCharacter[$i + 5] ?? 0,
          subcard: isSubcardEmpty($myCharacter, $i) ? NULL : ($myCharacter[$i+10] ?? null),
          marked: ($myCharacter[$i + 13] ?? 0) == 1,
          tapped: ($myCharacter[$i + 14] ?? 0) == 1);
      }
    }
  }

  $response->playerEquipment = $myCharData;

  //Now display any previous chain links that can be activated
  $playablePastLinks = [];
  $attacks = GetCombatChainAttacks();
  $attacksCount = count($attacks);
  $chainLinksPieces = ChainLinksPieces();
  for ($i = 0; $i < $attacksCount; $i += $chainLinksPieces) {
    $linkNum = intdiv($i, $chainLinksPieces);
    $label = "Chain Link " . $linkNum + 1;
    $overlay = 0;
    $action = $currentPlayer == $playerID && IsPlayable($attacks[$i], $turnPhase, "COMBATCHAINATTACKS", $linkNum) ? 38 : 0;
    $border = CardBorderColor($attacks[$i], "BANISH", $action > 0, $playerID);
    $cardID = $attacks[$i];
    if ($action != 0) $playablePastLinks[] = JSONRenderedCard($cardID, $action, borderColor: $border, actionDataOverride: strval($i), label: $label);
  }
  if (!empty($playablePastLinks)) {
    $response->playerBanish = [...$response->playerBanish, ...$playablePastLinks];
  }

  //Their Arsenal
  $theirArse = [];
  $theirArsenalCount = 0;
  $arsenalPieces = ArsenalPieces();
  if ($theirArsenal != "") {
    $theirArsenalCount = count($theirArsenal);
    for ($i = 0; $i < $theirArsenalCount; $i += $arsenalPieces) {
      if ($isGameOver) $theirArsenal[$i + 1] = "UP";
      if ($theirArsenal[$i + 1] == "UP" || $playerID == 3 && $isCasterMode || $isGameOver || ($playerID == 3 && $spectatorIsFriendOfP1 && !IsHideHandFromFriends(1))) {
        $overlay = 0;
        $border = 0;
        $cardID = $theirArsenal[$i];
        $action = $currentPlayer == $playerID && IsPlayable($cardID, $turnPhase, "THEIRARS", $i) ? 37 : 0;
        $border = CardBorderColor($cardID, "THEIRARS", $action > 0, $playerID);
        $theirArse[] = JSONRenderedCard(
          cardNumber: $theirArsenal[$i],
          action: $action,
          overlay: $overlay,
          borderColor: $border,
          controller: $playerID == 1 ? 2 : 1,
          facing: $theirArsenal[$i + 1],
          countersMap: (object) ["counters" => $theirArsenal[$i + 3]],
          isFrozen: IsFrozenMZ($theirArsenal, "ARS", $i, $otherPlayer),
          actionDataOverride: strval($i),
          powerCounters: $theirArsenal[$i + 6] ?? 0,
          uniqueID: $theirArsenal[$i + 5]
        );
      } else $theirArse[] = JSONRenderedCard(
        cardNumber: $TheirCardBack,
        controller: $playerID == 1 ? 2 : 1,
        facing: $theirArsenal[$i + 1],
        countersMap: (object) ["counters" => $theirArsenal[$i + 3]],
        isFrozen: IsFrozenMZ($theirArsenal, "ARS", $i, $otherPlayer),
        uniqueID: $theirArsenal[$i + 5]
      );
    }
  }
  $response->opponentArse = $theirArse;

  //My Arsenal
  $myArse = [];
  $myArsenalCount = 0;
  if ($myArsenal != "") {
    $myArsenalCount = count($myArsenal);
    for ($i = 0; $i < $myArsenalCount; $i += $arsenalPieces) {
      if ($isGameOver) $myArsenal[$i + 1] = "UP";
      if ($playerID == 3 && !$isCasterMode && $myArsenal[$i + 1] != "UP" && !$isGameOver && !($spectatorIsFriendOfP2 && !IsHideHandFromFriends(2))) {
        $myArse[] = JSONRenderedCard(
          cardNumber: $MyCardBack,
          controller: 2,
          facing: $myArsenal[$i + 1],
          countersMap: (object) ["counters" => $myArsenal[$i + 3]],
          isFrozen: IsFrozenMZ($myArsenal, "ARS", $i, $playerID),
          uniqueID: $myArsenal[$i + 5]
        );
      } else {
        $playable = $playerID == $currentPlayer && $turnPhase != "P" && IsPlayable($myArsenal[$i], $turnPhase, "ARS", $i, $restriction);
        $border = CardBorderColor($myArsenal[$i], "ARS", $playable, $playerID);
        $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? 5 : 0;
        if ($restriction !== "" && str_contains($restriction, ' ')) $restriction = str_replace(' ', '_', $restriction);
        $actionDataOverride = ($actionType == 16 || $actionType == 27) ? strval($i) : "";
        $myArse[] = JSONRenderedCard(
          cardNumber: $myArsenal[$i],
          action: $actionTypeOut,
          borderColor: $border,
          actionDataOverride: $actionDataOverride,
          controller: $playerID,
          restriction: $restriction,
          facing: $myArsenal[$i + 1],
          countersMap: (object) ["counters" => $myArsenal[$i + 3]],
          isFrozen: IsFrozenMZ($myArsenal, "ARS", $i, $playerID),
          powerCounters: $myArsenal[$i + 6] ?? 0,
          uniqueID: $myArsenal[$i + 5]
        );
      }
    }
  }
  $response->playerArse = $myArse;

  //Chain Links
  $chainLinkOutput = [];
  $chainLinkSummaryCount = count($chainLinkSummary);
  $chainLinkSummaryPieces = ChainLinkSummaryPieces();
  for ($i = 0; $i < $chainLinksCount; ++$i) {
    $Link = $ChainLinks->GetLink($i);
    $damage = $Link->DamageDealt();
    $hasHit = $damage > 0 || $Link->HitOnLink() == 1;
    $isDraconic = DelimStringContains($Link->Talents(), "DRACONIC", $playerID);
    $chainLinkOutput[] = [
      'result' => $hasHit ? "hit" : "no-hit",
      'isDraconic' => $isDraconic
    ];
  }
  $response->combatChainLinks = $chainLinkOutput;

  //Their Allies
  $theirAlliesOutput = [];
  $theirAllies = GetAllies($playerID == 1 ? 2 : 1);
  $theirAlliesCount = count($theirAllies);
  $allyPieces = AllyPieces();

  for ($i = 0; $i + $allyPieces - 1 < $theirAlliesCount; $i += $allyPieces) {
    $label = "";
    $type = CardType($theirAllies[$i]);
    $sType = CardSubType($theirAllies[$i]);
    $uniqueID = $theirAllies[$i+5];
    if($combatChainState[$CCS_AttackTargetUID] == $uniqueID) $label = "Targeted";
    elseif(SearchLayersForTargetUniqueID($uniqueID) != -1 && SearchCurrentTurnEffectsForUniqueID($uniqueID) != -1) $label = "Targeted/Effect Active";
    elseif(SearchCurrentTurnEffectsForUniqueID($uniqueID) != -1) $label = "Effect Active";
    elseif(SearchLayersForTargetUniqueID($uniqueID) != -1) $label = "Targeted";
    $theirAlliesOutput[] = JSONRenderedCard(
        cardNumber: $theirAllies[$i],
        overlay: $theirAllies[$i + 1] != 2 ? 1 : 0,
        counters: $theirAllies[$i + 6],
        lifeCounters: $theirAllies[$i + 2],
        controller: $otherPlayer,
        type: $type,
        sType: $sType,
        isFrozen: IsFrozenMZ($theirAllies, "ALLY", $i, $otherPlayer),
        subcard: $theirAllies[$i+4] != "-" ? $theirAllies[$i+4] : NULL,
        powerCounters:$theirAllies[$i+9],
        label: $label,
        tapped: $theirAllies[$i+11] == 1,
        steamCounters: $theirAllies[$i + 12]);
  }
  $response->opponentAllies = $theirAlliesOutput;

  //Their Auras
  $theirAurasOutput = [];
  $theirAurasCount = count($theirAuras);
  $auraPieces = AuraPieces();
  static $labeledAurasSet = ["blessing_of_themis_yellow" => true, "leave_em_speechless_blue" => true];
  for ($i = 0; $i + $auraPieces - 1 < $theirAurasCount; $i += $auraPieces) {
    $type = CardType($theirAuras[$i]);
    $sType = CardSubType($theirAuras[$i]);
    $gem = $theirAuras[$i + 8] != 2 ? $theirAuras[$i + 8] : NULL;
    $holoCounters = $theirAuras[$i + 13];
    if(isset($labeledAurasSet[$theirAuras[$i]])) {
      $label = GamestateUnsanitize($theirAuras[$i + 10]);
    }
    elseif (!TypeContains($theirAuras[$i], "T") && $theirAuras[$i + 4] == 1) {
      $label = "Token Copy";
    }
    else $label = "";
    $theirAurasOutput[] = JSONRenderedCard(cardNumber: $theirAuras[$i],
      actionDataOverride: strval($i),
      overlay: $theirAuras[$i + 1] != 2 ? 1 : 0,
      counters: $theirAuras[$i + 2],
      powerCounters: $theirAuras[$i + 3],
      controller: $otherPlayer,
      type: $type,
      sType: $sType,
      gem: $gem,
      label: $label,
      isFrozen: IsFrozenMZ($theirAuras, "AURAS", $i, $otherPlayer),
      tapped: $theirAuras[$i+12] == "1",
      holoCounters: $holoCounters > 0
      );
  }
  $response->opponentAuras = $theirAurasOutput;

  //Their Items
  $theirItemsOutput = [];
  $theirItemsCount = count($theirItems);
  $itemPieces = ItemPieces();
  for ($i = 0; $i + $itemPieces - 1 < $theirItemsCount; $i += $itemPieces) {
    $type = CardType($theirItems[$i]);
    $sType = CardSubType($theirItems[$i]);
    $gem = $theirItems[$i + 6] != 2 ? $theirItems[$i + 6] : NULL;
    $label = "";
    if($theirItems[$i] == "null_time_zone_blue") {
      $label = GamestateUnsanitize($theirItems[$i + 8]);
    }
    $theirItemsOutput[] = JSONRenderedCard(
      cardNumber: $theirItems[$i],
      actionDataOverride: strval($i),
      overlay: $theirItems[$i + 2] != 2 ? 1 : 0,
      counters: $theirItems[$i + 1],
      controller: $otherPlayer,
      type: $type,
      sType: $sType,
      isFrozen: IsFrozenMZ($theirItems, "ITEMS", $i, $otherPlayer),
      gem: $gem,
      label: $label,
      tapped: $theirItems[$i + 10] == 1,
      subcard: $theirItems[$i+11] != "-" ? $theirItems[$i+11] : NULL,
      defCounters: $theirItems[$i + 12],
      onChain: $theirItems[$i + 13] == 1);
  }
  $response->opponentItems = $theirItemsOutput;

  //Their Permanents
  $theirPermanentsOutput = [];
  $theirPermanents = GetPermanents($playerID == 1 ? 2 : 1);
  $theirPermanentsCount = count($theirPermanents);
  $permanentPieces = PermanentPieces();
  for ($i = 0; $i + $permanentPieces - 1 < $theirPermanentsCount; $i += $permanentPieces) {
    if($theirPermanents[$i] == "levia_redeemed") continue;
    $type = CardType($theirPermanents[$i]);
    $sType = CardSubType($theirPermanents[$i]);
    $theirPermanentsOutput[] = JSONRenderedCard(cardNumber: $theirPermanents[$i], controller: $otherPlayer, type: $type, sType: $sType);
  }
  $response->opponentPermanents = $theirPermanentsOutput;

  //My Allies
  $myAlliesOutput = [];
  $myAllies = GetAllies($playerID == 1 ? 1 : 2);
  $myAlliesCount = count($myAllies);
  for ($i = 0; $i + $allyPieces - 1 < $myAlliesCount; $i += $allyPieces) {
    $label = "";
    $type = CardType($myAllies[$i]);
    $sType = CardSubType($myAllies[$i]);
    $playable = $currentPlayer == $playerID ? IsPlayable($myAllies[$i], $turn[0], "PLAY", $i, $restriction) && ($myAllies[$i + 1] == 2 || !CheckTapped("MYALLY-".$i, $currentPlayer)) : false;
    $actionType = ($currentPlayer == $playerID && $turn[0] != "P" && $playable) ? 24 : 0;
    $border = CardBorderColor($myAllies[$i], "PLAY", $playable, $playerID);
    $actionDataOverride = $actionType == 24 ? strval($i) : "";
    $uniqueID = $myAllies[$i+5];
    if($combatChainState[$CCS_AttackTargetUID] == $uniqueID) $label = "Targeted";
    elseif(SearchLayersForTargetUniqueID($uniqueID) != -1) $label = "Targeted";
    elseif(SearchCurrentTurnEffectsForUniqueID($uniqueID) != -1) $label = "Effect Active";
    $myAlliesOutput[] = JSONRenderedCard(
      cardNumber: $myAllies[$i],
      action: $actionType,
      overlay: $myAllies[$i+1] != 2 ? 1 : 0,
      counters: $myAllies[$i+6],
      borderColor: $border,
      actionDataOverride: $actionDataOverride,
      lifeCounters: $myAllies[$i+2],
      controller: $playerID,
      type: $type,
      sType: $sType,
      isFrozen:IsFrozenMZ($myAllies, "ALLY", $i, $playerID),
      subcard: $myAllies[$i+4] != "-" ? $myAllies[$i+4] : NULL,
      powerCounters: $myAllies[$i+9],
      label: $label,
      tapped: $myAllies[$i + 11] == 1,
      steamCounters: $myAllies[$i + 12]
    );
  }
  $response->playerAllies = $myAlliesOutput;

  //My Auras
  $myAurasOutput = [];
  $myAurasCount = count($myAuras);
  for ($i = 0; $i + $auraPieces - 1 < $myAurasCount; $i += $auraPieces) {
    $playable = $currentPlayer == $playerID ? $myAuras[$i + 1] == 2 && IsPlayable($myAuras[$i], $turnPhase, "PLAY", $i, $restriction) : false;
    if($myAuras[$i] == "restless_coalescence_yellow" && $currentPlayer == $playerID && IsPlayable($myAuras[$i], $turnPhase, "PLAY", $i, $restriction)) $playable = true;
    $border = CardBorderColor($myAuras[$i], "PLAY", $playable, $playerID);
    $counters = $myAuras[$i + 2];
    $powerCounters = $myAuras[$i + 3];
    $action = $currentPlayer == $playerID && $turnPhase != "P" && $playable ? 22 : 0;
    $type = CardType($myAuras[$i]);
    $sType = CardSubType($myAuras[$i]);
    $gem = $myAuras[$i + 7] != 2 ? $myAuras[$i + 7] : NULL;
    $holoCounters = $myAuras[$i + 13];
    if(isset($labeledAurasSet[$myAuras[$i]])) {
      $label = GamestateUnsanitize($myAuras[$i + 10]);
    }
    elseif (!TypeContains($myAuras[$i], "T") && $myAuras[$i + 4] == 1) {
      $label = "Token Copy";
    }
    else $label = "";
    $myAurasOutput[] = JSONRenderedCard(
      cardNumber: $myAuras[$i],
      overlay: $myAuras[$i + 1] != 2 ? 1 : 0,
      counters: $counters,
      powerCounters: $powerCounters,
      action: $action,
      controller: $playerID,
      borderColor: $border,
      type: $type,
      actionDataOverride: strval($i),
      sType: $sType,
      gem: $gem,
      label: $label,
      isFrozen: IsFrozenMZ($myAuras, "AURAS", $i, $playerID),
      tapped: $myAuras[$i + 12] == 1,
      holoCounters: $holoCounters > 0
    );
  }
  $response->playerAuras = $myAurasOutput;

  //My Items
  $myItemsOutput = [];
  $myItemsCount = count($myItems);
  for ($i = 0; $i + $itemPieces - 1 < $myItemsCount; $i += $itemPieces) {
    $type = CardType($myItems[$i]);
    $sType = CardSubType($myItems[$i]);
    $playable = $currentPlayer == $playerID ? IsPlayable($myItems[$i], $turn[0], "PLAY", $i, $restriction) : false;
    $border = CardBorderColor($myItems[$i], "PLAY", $playable, $playerID);
    $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? 10 : 0;
    $label = "";
    if ($restriction !== "" && str_contains($restriction, ' ')) $restriction = str_replace(' ', '_', $restriction);
    $actionDataOverride = strval($i);
    $gem = $myItems[$i + 5] != 2 ? $myItems[$i + 5] : NULL;
    $rustCounters = null;
    $verseCounters = null;
    $flowCounters = null;
    if ($myItems[$i] == "micro_processor_blue") {
      if (DelimStringContains($myItems[$i + 8], "Opt", true)) $verseCounters = 1;
      if (DelimStringContains($myItems[$i + 8], "Draw_then_top_deck", true)) $rustCounters = 1;
      if (DelimStringContains($myItems[$i + 8], "Banish_top_deck", true)) $flowCounters = 1;
    }
    if ($myItems[$i] == "null_time_zone_blue") {
      $label = GamestateUnsanitize($myItems[$i + 8]);
    }
    $myItemsOutput[] = JSONRenderedCard(
      cardNumber: $myItems[$i],
      action: $actionTypeOut,
      borderColor: $border,
      actionDataOverride: $actionDataOverride,
      overlay: ItemOverlay($myItems[$i], $myItems[$i + 2], $myItems[$i + 3]),
      counters: $myItems[$i + 1],
      controller: $playerID,
      type: $type,
      sType: $sType,
      isFrozen: IsFrozenMZ($myItems, "ITEMS", $i, $playerID),
      gem: $gem,
      restriction: $restriction,
      label: $label,
      rustCounters: $rustCounters,
      verseCounters: $verseCounters,
      flowCounters: $flowCounters,
      tapped: $myItems[$i + 10] == 1,
      subcard: $myItems[$i+11] != "-" ? $myItems[$i+11] : NULL,
      defCounters: $myItems[$i + 12],
      onChain: $myItems[$i + 13] == 1);
  }
  $response->playerItems = $myItemsOutput;

  //My Permanents
  $myPermanentsOutput = [];
  $myPermanents = GetPermanents($playerID == 1 ? 1 : 2);
  $myPermanentsCount = count($myPermanents);
  for ($i = 0; $i + $permanentPieces - 1 < $myPermanentsCount; $i += $permanentPieces) {
    $type = CardType($myPermanents[$i]);
    $sType = CardSubType($myPermanents[$i]);
    $playable = $currentPlayer == $playerID ? IsPlayable($myPermanents[$i], $turnPhase, "PLAY", $i, $restriction) : false;
    $border = CardBorderColor($myPermanents[$i], "PLAY", $playable, $playerID);
    $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? 34 : 0;
    if ($restriction !== "" && str_contains($restriction, ' ')) $restriction = str_replace(' ', '_', $restriction);
    $actionDataOverride = strval($i);
    $myPermanentsOutput[] = JSONRenderedCard(cardNumber: $myPermanents[$i], controller: $playerID, type: $type, sType: $sType, action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, restriction: $restriction);
  }
  $response->playerPermanents = $myPermanentsOutput;

  //My Inventory
  $myInventoryOutput = [];
  $myInventory = &GetInventory($playerID == 1 ? 1 : 2);
  $myInventoryCount = count($myInventory);
  for ($i = 0; $i < $myInventoryCount; ++$i) {
    $myInventoryOutput[] = JSONRenderedCard(cardNumber: $myInventory[$i], controller: $playerID);
  }
  $response->playerInventory = $myInventoryOutput;

  //Landmarks
  $landmarksOutput = [];
  $landmarksCount = count($landmarks);
  $landmarkPieces = LandmarkPieces();
  for ($i = 0; $i + $landmarkPieces - 1 < $landmarksCount; $i += $landmarkPieces) {
    $playable = $currentPlayer == $playerID ? IsPlayable($landmarks[$i], $turn[0], "PLAY", $i, $restriction) : false;
    $action = $playable && $currentPlayer == $playerID ? 25 : 0;
    $border = CardBorderColor($landmarks[$i], "PLAY", $playable, $playerID);
    $counters = $landmarks[$i + 3];
    $type = CardType($landmarks[$i]);
    $sType = CardSubType($landmarks[$i]);
    $landmarksOutput[] = JSONRenderedCard(
      cardNumber: $landmarks[$i],
      type: $type,
      sType: $sType,
      actionDataOverride: strval($i),
      action: $action,
      borderColor: $border,
      counters: $counters
    );
  }
  $response->landmarks = $landmarksOutput;
  // if ($inactive) WriteLog("The current player may be inactive", highlight:true);
  $response->chatLog = JSONLog($gameName, $playerID);

  // Current turn effects
  $playerEffects = [];
  $opponentEffects = [];
  $friendlyEffects = "";
  $BorderColor = NULL;
  $counters = NULL;
  $friendlyCounts = [];
  $opponentCounts = [];
  $friendlyRenderedEffects = [];
  $opponentRenderedEffects = [];
  $currentTurnEffectsCount = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  $effectCardIds = [];
  $adminEffectIdx = [];
  $fancyCountersCache = [];

  for ($i = 0; $i + $currentTurnEffectsPieces - 1 < $currentTurnEffectsCount; $i += $currentTurnEffectsPieces) {
      $raw = $currentTurnEffects[$i];
      $tmp = strstr($raw, '-', true);
      $firstPart = $tmp !== false ? $tmp : $raw;
      $tmp2 = strstr($firstPart, ',', true);
      $cardID = $tmp2 !== false ? $tmp2 : $firstPart;
      $effectCardIds[$i] = $cardID;
      $isAdmin = AdministrativeEffect($cardID) || $cardID === "luminaris_angels_glow-1" || $cardID === "luminaris_angels_glow-2";
      $adminEffectIdx[$i] = $isAdmin;
      if ($isAdmin) continue;
      if (!isset($fancyCountersCache[$cardID])) {
          $fancyCountersCache[$cardID] = HasFancyCounters($cardID);
      }
      if ($fancyCountersCache[$cardID]) continue;
      $isFriendly = $playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1];

      if ($isFriendly) {
          if (!isset($friendlyCounts[$cardID])) $friendlyCounts[$cardID] = 0;
          $friendlyCounts[$cardID]++;
      } else {
          if (!isset($opponentCounts[$cardID])) $opponentCounts[$cardID] = 0;
          $opponentCounts[$cardID]++;
      }
  }
  if (!empty($staticBuffsArr)) {
    foreach ($staticBuffsArr as $effectSetID) {
      $cardID = ExtractCardID(ConvertToCardID($effectSetID));
      if ($cardID != "") {
        $isFriendly = $playerID == $mainPlayer;
        if ($isFriendly) {
          if (!isset($friendlyCounts[$cardID])) $friendlyCounts[$cardID] = 0;
          $friendlyCounts[$cardID]++;
        } else {
          if (!isset($opponentCounts[$cardID])) $opponentCounts[$cardID] = 0;
          $opponentCounts[$cardID]++;
        }
      }
    }
  }

  $skipStackCache = [];
  for ($i = 0; $i + $currentTurnEffectsPieces - 1 < $currentTurnEffectsCount; $i += $currentTurnEffectsPieces) {
      $cardID = $effectCardIds[$i];
      if ($adminEffectIdx[$i]) continue;
      $isFriendly = $playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1];
      $BorderColor = $isFriendly ? "blue" : "red";
      $counters = $isFriendly ? $friendlyCounts[$cardID] ?? 0 : $opponentCounts[$cardID] ?? 0;

      if (!isset($skipStackCache[$cardID])) {
          $skipStackCache[$cardID] = skipEffectUIStacking($cardID);
      }
      $doesStack = !$skipStackCache[$cardID];

      if ($isFriendly) {
          if (!isset($friendlyRenderedEffects[$cardID]) || $doesStack) {
              $card = GetClass($cardID, 0);
              if ($cardID == "shelter_from_the_storm_red" || $cardID == "calming_breeze_red" || ($card != "-" && $card->DisplayRemainingPrevention())) {
                  $counters = $currentTurnEffects[$i + 3];
              }
              if ($cardID == "haunting_rendition_red" || $cardID == "mental_block_blue") {
                  $parts = explode("-", $currentTurnEffects[$i]);
                  $counters = intval(end($parts));
              }
              $friendlyRenderedEffects[$cardID] = true;
              $playerEffects[] = JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP", showAmpAmount:"Effect-".$i);
          }
      } elseif (!isset($opponentRenderedEffects[$cardID]) && $otherPlayer == $currentTurnEffects[$i + 1] || $doesStack) {
          $card = GetClass($cardID, 0);
          if ($cardID == "shelter_from_the_storm_red" || $cardID == "calming_breeze_red" || ($card != "-" && $card->DisplayRemainingPrevention())) {
              $counters = $currentTurnEffects[$i + 3];
          }
          if ($cardID == "haunting_rendition_red" || $cardID == "mental_block_blue") {
              $parts = explode("-", $currentTurnEffects[$i]);
              $counters = intval(end($parts));
          }
          $opponentRenderedEffects[$cardID] = true;
          $opponentEffects[] = JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP", showAmpAmount:"Effect-".$i);
      }
  }
  if (!empty($staticBuffsArr)) {
    foreach ($staticBuffsArr as $effectSetID) {
      $cardID = ExtractCardID(ConvertToCardID($effectSetID));
      if ($cardID != "") {
        $isFriendly = $playerID == $mainPlayer;
        $BorderColor = $isFriendly ? "blue" : "red";

        $counters = $isFriendly ? $friendlyCounts[$cardID] : $opponentCounts[$cardID];
        if (!isset($skipStackCache[$cardID])) {
          $skipStackCache[$cardID] = skipEffectUIStacking($cardID);
        }
        $buffDoesStack = !$skipStackCache[$cardID];
        if ($isFriendly || $playerID == 3 && !$isFriendly) {
          if (!isset($friendlyRenderedEffects[$cardID]) || $buffDoesStack) {
            $friendlyRenderedEffects[$cardID] = true;
            $playerEffects[] = JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP");
          }
        }
        elseif (!isset($opponentRenderedEffects[$cardID]) && !$isFriendly || $buffDoesStack) {
          $opponentRenderedEffects[$cardID] = true;
          $opponentEffects[] = JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP");
        }
      }
    }
  }
  $response->opponentEffects = $opponentEffects;
  $response->playerEffects = $playerEffects;

  //Events
  $newEvents = new stdClass();
  $newEvents->eventArray = [];
  if (!$isGameOver) {
    $eventsCount = count($events);
    $eventPieces = EventPieces();
    for ($i = 0; $i < $eventsCount; $i += $eventPieces) {
      $thisEvent = new stdClass();
      $thisEvent->eventType = $events[$i];
      $thisEvent->eventValue = $events[$i + 1] ?? null;
      // CLASHDASH: Dash's own deck reveal skip animation for Dash IO since they already see the top of their decks.
      if ($thisEvent->eventType == "CLASHDASH") {
        $clashParts = explode(":", $thisEvent->eventValue ?? "");
        if (intval($clashParts[0]) == intval($playerID)) continue;
        $thisEvent->eventType = "CLASH";
      }
      $newEvents->eventArray[] = $thisEvent;
    }
  }
  $response->newEvents = $newEvents;

  // Phase of the turn
  $turnPhaseObj = new stdClass();
  $turnPhaseObj->turnPhase = $turnPhase;
  if ($layersCount > 0) {
    $turnPhaseObj->layer = $layers[0];
  }
  $isItMeOrThem = $currentPlayer == $playerID ? "Choose " : "Your opponent is choosing ";
  $turnPhaseObj->caption = $isItMeOrThem . TypeToPlay($turnPhase);
  $response->turnPhase = $turnPhaseObj;

  // Do we have priority?
  $response->havePriority = ($currentPlayer == $playerID);

  // For spectators, simulate havePriority as if they were player 1
  if ($playerID == 3) {
    $response->havePriority = ($currentPlayer == 1);
  }

  // opponent and player Action Points
  if ($mainPlayer == $playerID || $playerID == 3 && $mainPlayer != $otherPlayer) {
    $response->opponentAP = 0;
    $response->playerAP = $actionPoints;
  } else {
    $response->opponentAP = $actionPoints;
    $response->playerAP = 0;
  }

  // Last played Card
  $lastPlayedCount = count($lastPlayed);
  $response->lastPlayedCard = ($lastPlayedCount == 0) ?
    JSONRenderedCard($MyCardBack) :
    JSONRenderedCard($lastPlayed[0], controller: $lastPlayed[1]);
  // is the player the active player (is it their turn?)
  $response->amIActivePlayer = ($turn[1] == $playerID) ? true : false;

  $response->turnPlayer = $mainPlayer;
  $response->otherPlayer = $playerID == 1 ? 2 : 1;
  $response->firstPlayer = $firstPlayer;
  $response->turnNo = $currentTurn;
  $response->clock = $p1TotalTime + $p2TotalTime;

  $playerPrompt = new StdClass();
  $promptButtons = [];
  $helpText = "";
  // Reminder text box highlight thing
  if ($turnPhase != "OVER") {
    $helpText .= $currentPlayer != $playerID ? WaitingMessage($turnPhase) : GetPhaseHelptext();
    if($currentPlayer == $playerID) { 
      if ($turnPhase == "P" || $turnPhase == "CHOOSEHANDCANCEL" || $turnPhase == "CHOOSEDISCARDCANCEL") {
        $helpText .= $turnPhase == "P" ? " (" . $myResources[0] . " of " . $myResources[1] . ")" : "";
        $promptButtons[] = CreateButtonAPI($playerID, "Cancel", 10000, 0, "16px");
      }
      if (CanPassPhase($turnPhase)) {
        if ($turnPhase == "B") {
          $promptButtons[] = CreateButtonAPI($playerID, "Undo Block", 10001, 0, "16px");
          $promptButtons[] = CreateButtonAPI($playerID, "Pass", 99, 0, "16px");
          $promptButtons[] = CreateButtonAPI($playerID, "Pass Block and Reactions", 101, 0, "16px", "", "Reactions will not be skipped if the opponent reacts");
        }
      }
    }
  }

  $playerPrompt->helpText = $helpText;
  $playerPrompt->buttons = $promptButtons;
  $response->playerPrompt = $playerPrompt;

  $response->fullRematchAccepted = $turnPhase == "REMATCH";

  // Build player input popup
  $response->playerInputPopUp = BuildPlayerInputPopup($playerID, $turnPhase, $turn, $gameName);

  $canPassPhaseForPlayer = (CanPassPhase($turn[0]) && $currentPlayer == $playerID) || ($isReplay && $playerID == 3);
  $response->canPassPhase = $canPassPhaseForPlayer;

  $response->preventPassPrompt = "";
  if ($canPassPhaseForPlayer) {
    if ($turn[0] == "ARS" && count($myHand) > 0 && !ArsenalFull($playerID) && !$isReplay) {
      $response->preventPassPrompt = "Are you sure you want to skip arsenal?";
    }
  }

  if ($canPassPhaseForPlayer) {
    if ($turn[0] == "M" && SearchLayersForPhase("RESOLUTIONSTEP") != -1 && $actionPoints > 0 && !$isReplay) {
      global $p1Settings, $p2Settings;
      $pSettings = ($playerID == 1 ? $p1Settings : $p2Settings);
      if (intval($pSettings[0] ?? 0) === 1) {
        $response->preventPassPrompt = "Are you sure you want to close the combat chain?";
      }
    }
  }

  $response->chatEnabled = intval($buildCacheArr[14] ?? 0) == 1 && intval($buildCacheArr[15] ?? 0) == 1;
  $response->isPrivate = (($buildCacheArr[8] ?? "") !== "1");
  $response->isReplay = (($buildCacheArr[9] ?? "") === "1");

  $response->aiHasInfiniteHP = $AIHasInfiniteHP;

  // Opponent typing indicator
  if ($playerID >= 1 && $playerID <= 2) {
    $opponentID = ($playerID == 1) ? 2 : 1;
    $typingCacheKey = "typing_" . md5($gameName) . "_player_" . $opponentID;

    $isOpponentTyping = false;
    if (extension_loaded('apcu') && ini_get('apc.enabled')) {
      if (function_exists('apcu_fetch')) {
        $isOpponentTyping = @apcu_fetch($typingCacheKey) !== false;
      }
    }
    $response->opponentIsTyping = $isOpponentTyping;
  }

  $response->inactive = $inactive;
  return $response;
}

function BuildPlayerInputPopup($playerID, $turnPhase, $turn, $gameName) {
  global $myHand, $myPitch, $myDeck, $theirDeck, $myDiscard, $theirDiscard;
  global $myBanish, $theirBanish, $myArsenal, $theirArsenal;
  global $myCharacter, $theirCharacter, $myAuras, $theirAuras;
  global $myItems, $theirItems, $mySoul, $theirSoul;
  global $combatChain, $layers, $dqVars, $currentPlayer;
  global $TheirCardBack, $otherPlayer;

  $playerInputPopup = new stdClass();
  $playerInputButtons = [];
  $playerInputPopup->active = false;

  // This is a placeholder - the full implementation would require copying
  // the entire switch statement from GetNextTurn.php
  // For now, we include the necessary helper function and delegate to it
  include_once "BuildPlayerInputPopup.php";

  if (function_exists('BuildPlayerInputPopupFull')) {
    return BuildPlayerInputPopupFull($playerID, $turnPhase, $turn, $gameName);
  }

  // Fallback minimal implementation
  $playerInputPopup->buttons = $playerInputButtons;
  return $playerInputPopup;
}

function ItemOverlay($item, $isReady, $numUses)
{
  return $item == "micro_processor_blue" && $numUses < 3 || $isReady != 2 ? 1 : 0;
}

function GetPhaseHelptext()
{
  global $turn;
  $defaultText = "Choose " . TypeToPlay($turn[0]);
  $DQText = GetDQHelpText();
  return $DQText != "-" ? GamestateUnsanitize($DQText) : $defaultText;
}

if (!function_exists('GetCardEffectLabel')) {
  function GetCardEffectLabel($uniqueID, $currentTurnEffects) {
    if ($uniqueID == "" || $uniqueID == "-") return "";
    
    global $CurrentTurnEffects;
    $Effect = $CurrentTurnEffects->FindEffectUID($uniqueID);
    if ($Effect->Index() == -1) return "";
    
    $effectName = $Effect->EffectID();
    switch ($effectName) {
      case "beseech_the_demigon_red":
      case "beseech_the_demigon_yellow":
      case "beseech_the_demigon_blue":
      case "painful_passage_red-buff":
        return "Power +" . EffectPowerModifier($effectName);
      case "tear_through_the_portal_red":
      case "tear_through_the_portal_yellow":
      case "tear_through_the_portal_blue":
      case "painful_passage_red-go_again":
        return "Go Again";
      default:
        return "";
    }
  }
}

function skipEffectUIStacking($cardID) {
  if (HasFancyCounters($cardID) || $cardID == "shelter_from_the_storm_red" || $cardID == "calming_breeze_red") return false;
  $card = GetClass($cardID, 0);
  if ($card != "-" && $card->DisplayRemainingPrevention()) return false;
  return true;
}


if (!function_exists('IsDevEnvironment')) {
  function IsDevEnvironment() {
    $domain = getenv("DOMAIN");
    if ($domain === "localhost") return true;
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') return true;
    return false;
  }
}
