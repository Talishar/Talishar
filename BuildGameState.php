<?php
function BuildGameStateResponse($gameName, $playerID, $authKey, $sessionData = [], $includeInitialLoad = true) {
  global $myHand, $myPitch, $myDeck, $myDiscard, $myBanish, $myArsenal, $myCharacter;
  global $myAuras, $myItems, $mySoul, $myAllies, $myPermanents, $myResources;
  global $theirHand, $theirPitch, $theirDeck, $theirDiscard, $theirBanish, $theirArsenal, $theirCharacter;
  global $theirAuras, $theirItems, $theirSoul, $theirAllies, $theirPermanents, $theirResources;
  global $combatChain, $combatChainState, $layers, $chainLinks, $chainLinkSummary, $landmarks;
  global $turn, $currentPlayer, $mainPlayer, $defPlayer, $firstPlayer, $currentTurn, $actionPoints;
  global $currentTurnEffects, $nextTurnEffects, $dqVars, $lastPlayed, $events;
  global $p1Key, $p2Key, $myHealth, $theirHealth, $winner;
  global $CombatChain, $CCS_AttackTargetUID, $CCS_WeaponIndex, $CCS_RequiredEquipmentBlock, $CCS_RequiredNegCounterEquipmentBlock;
  global $AIHasInfiniteHP, $EffectContext;
  global $p1IsPatron, $p2IsPatron, $p1MetafyTiers, $p2MetafyTiers, $p1IsAI, $p2IsAI;
  global $roguelikeGameID, $gameGUID, $p1uid, $p2uid;
  global $p1TotalTime, $p2TotalTime;

  // Variables that will be set locally and need to be accessible to BuildPlayerInputPopup
  global $MyCardBack, $TheirCardBack, $otherPlayer, $isReactFE, $isGameOver, $isCasterMode, $isReplay;

  if (!IsGameNameValid($gameName)) {
    return "Invalid game name.";
  }

  if (!is_numeric($playerID)) {
    return "Invalid player ID.";
  }

  if (!file_exists("./Games/" . $gameName . "/GameFile.txt")) {
    return "Game no longer exists on the server.";
  }

  // Check spectator permission
  if ($playerID == 3 && GetCachePiece($gameName, 9) != "1") {
    return "Spectators not allowed.";
  }

  // Extract session data with defaults
  $sessionUserLoggedIn = $sessionData['userLoggedIn'] ?? false;
  $sessionUserName = $sessionData['userName'] ?? null;
  $sessionIsPvtVoidPatron = $sessionData['isPvtVoidPatron'] ?? false;
  $sessionPatreonCampaigns = $sessionData['patreonCampaigns'] ?? [];

  $response = new stdClass();
  $response->playerInventory = [];

  $isGamePlayer = $playerID == 1 || $playerID == 2;
  $otherPlayer = $playerID == 1 ? 2 : 1;
  $cacheVal = intval(GetCachePiece($gameName, 1));

  include_once "ParseGamestate.php";
  ParseGamestate();

  // Auth validation
  $targetAuth = $playerID == 1 ? $p1Key : $p2Key;
  if ($playerID != 3 && $authKey != $targetAuth) {
    return "Invalid Authkey";
  }

  $turnCount = count($turn);
  if ($turnCount == 0) {
    RevertGamestate();
    GamestateUpdated($gameName);
    return "Game state reverted.";
  }

  $isReactFE = true;
  $isGameOver = IsGameOver();
  $isCasterMode = IsCasterMode();
  $isReplay = IsReplay();

  $response->lastUpdate = $cacheVal;

  // send initial on-load information if requested
  if ($includeInitialLoad) {
    include "MenuFiles/ParseGamefile.php";
    $initialLoad = new stdClass();
    $initialLoad->gameGUID = $gameGUID;
    $initialLoad->playerName = $playerID == 1 ? $p1uid : $p2uid;
    $initialLoad->opponentName = $playerID == 1 ? $p2uid : $p1uid;
    $contributors = ["sugitime", "OotTheMonk", "Launch", "LaustinSpayce", "Star_Seraph", "Tower", "Etasus", "scary987", "Celenar", "DKGaming", "Aegisworn", "PvtVoid"];

    $initialLoad->playerIsContributor = in_array($initialLoad->playerName, $contributors);
    $initialLoad->playerIsPatron = ($playerID == 1 ? $p1IsPatron : $p2IsPatron) ?: "";

    // Fetch tiers live from DB so they reflect the current state (not stale game file values)
    $livePlayerTiers = GetMetafyTiersFromDatabase($initialLoad->playerName);
    $liveOpponentTiers = GetMetafyTiersFromDatabase($initialLoad->opponentName);
    $initialLoad->playerMetafyTiers = !empty($livePlayerTiers) ? $livePlayerTiers : (($playerID == 1 ? $p1MetafyTiers : $p2MetafyTiers) ?: []);

    $initialLoad->opponentIsContributor = in_array($initialLoad->opponentName, $contributors);
    $initialLoad->opponentIsPatron = ($playerID == 1 ? $p2IsPatron : $p1IsPatron) ?: "";
    $initialLoad->opponentMetafyTiers = !empty($liveOpponentTiers) ? $liveOpponentTiers : (($playerID == 1 ? $p2MetafyTiers : $p1MetafyTiers) ?: []);

    $initialLoad->roguelikeGameID = $roguelikeGameID;
    $initialLoad->playerIsPvtVoidPatron = $initialLoad->playerName == "PvtVoid" || $playerID == 1 && $sessionIsPvtVoidPatron;
    $initialLoad->opponentIsPvtVoidPatron = $initialLoad->opponentName == "PvtVoid" || $playerID == 2 && $sessionIsPvtVoidPatron;
    $initialLoad->isOpponentAI = $playerID == 1 ? ($p2IsAI == "1") : ($p1IsAI == "1");

    $initialLoad->altArts = [];
    $initialLoad->opponentAltArts = [];

    // For spectators (playerID==3), resolve alt arts from the game file usernames directly.
    // p1uid maps to the "player" (altArts) slot; p2uid maps to the "opponent" (opponentAltArts) slot.
    $altArtsPlayerName  = $playerID == 3 ? $p1uid : $initialLoad->playerName;
    $altArtsOpponentName = $playerID == 3 ? $p2uid : $initialLoad->opponentName;
    $altArtsPlayerID    = $playerID == 3 ? 1 : $playerID;
    $altArtsOpponentID  = $playerID == 3 ? 2 : $otherPlayer;

    if($playerID == 3 || !AltArtsDisabled($playerID))
    {
      foreach(PatreonCampaign::cases() as $campaign) {
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
          for($i = 0; $i < $altArtsCount; ++$i) {
            $arr = explode("=", $altArts[$i]);
            $altArt = new stdClass();
            $altArt->name = $campaign->CampaignName() . ($altArtsCount > 1 ? " " . $i + 1 : "");
            $altArt->cardId = $arr[0];
            $altArt->altPath = $arr[1];
            array_push($initialLoad->altArts, $altArt);
          }
        }
      }

      // Add Metafy community alt arts
      // We look up by player username since session might not have login data (e.g., in SSE connections)
      $playerUsername = $altArtsPlayerName;
      if (!empty($playerUsername) && !IsDevEnvironment()) {
        $conn = GetDBConnection();
        $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
          mysqli_stmt_bind_param($stmt, 's', $playerUsername);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          mysqli_stmt_close($stmt);

          if ($row && !empty($row['metafyCommunities'])) {
            $communities = json_decode($row['metafyCommunities'], true);
            if (is_array($communities)) {
              foreach ($communities as $community) {
                $communityId = $community['id'] ?? null;
                if ($communityId) {
                  foreach(MetafyCommunity::cases() as $metafyCommunity) {
                    if ($metafyCommunity->value === $communityId) {
                      $metafyAltArts = $metafyCommunity->AltArts();
                      if (!empty($metafyAltArts)) {
                        $metafyAltArtsCount = count($metafyAltArts);
                        for($i = 0; $i < $metafyAltArtsCount; ++$i) {
                          $arr = explode("=", $metafyAltArts[$i]);
                          if (count($arr) === 2) {
                            $altArt = new stdClass();
                            $altArt->name = $metafyCommunity->CommunityName() . ($metafyAltArtsCount > 1 ? " " . $i + 1 : "");
                            $altArt->cardId = trim($arr[0]);
                            $altArt->altPath = trim($arr[1]);
                            array_push($initialLoad->altArts, $altArt);
                          }
                        }
                      }
                      break;
                    }
                  }
                }
              }
            }
          }
        }
        mysqli_close($conn);
      }
    }

    // Get opponent's alt arts
    if($playerID == 3 || !AltArtsDisabled($playerID))
    {
      foreach(PatreonCampaign::cases() as $campaign) {
        $isOpponentSupporterOfCampaign = $campaign->IsTeamMember($altArtsOpponentName);

        if ($campaign->SessionID() == "isPvtVoidPatron") {
          $isOpponentSupporterOfCampaign = $altArtsOpponentName == "PvtVoid" || $campaign->IsTeamMember($altArtsOpponentName);
        }

        if($isOpponentSupporterOfCampaign) {
          $opponentAltArts = $campaign->AltArts($altArtsOpponentID);
          if($opponentAltArts == "") continue;
          $opponentAltArts = explode(",", $opponentAltArts);
          $opponentAltArtsCount = count($opponentAltArts);
          for($i = 0; $i < $opponentAltArtsCount; ++$i) {
            $arr = explode("=", $opponentAltArts[$i]);
            $opponentAltArt = new stdClass();
            $opponentAltArt->name = $campaign->CampaignName() . ($opponentAltArtsCount > 1 ? " " . $i + 1 : "");
            $opponentAltArt->cardId = $arr[0];
            $opponentAltArt->altPath = $arr[1];
            array_push($initialLoad->opponentAltArts, $opponentAltArt);
          }
        }
      }

      // Add opponent's Metafy community alt arts
      if (!IsDevEnvironment()) {
        $conn = GetDBConnection();
        $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
        $stmt = mysqli_stmt_init($conn);
        if (mysqli_stmt_prepare($stmt, $sql)) {
          mysqli_stmt_bind_param($stmt, 's', $altArtsOpponentName);
          mysqli_stmt_execute($stmt);
          $result = mysqli_stmt_get_result($stmt);
          $row = mysqli_fetch_assoc($result);
          mysqli_stmt_close($stmt);

          if ($row && !empty($row['metafyCommunities'])) {
            $communities = json_decode($row['metafyCommunities'], true);
            if (is_array($communities)) {
              foreach ($communities as $community) {
                $communityId = $community['id'] ?? null;
                if ($communityId) {
                  foreach(MetafyCommunity::cases() as $metafyCommunity) {
                    if ($metafyCommunity->value === $communityId) {
                      $opponentMetafyAltArts = $metafyCommunity->AltArts();
                      if (!empty($opponentMetafyAltArts)) {
                        $opponentMetafyAltArtsCount = count($opponentMetafyAltArts);
                        for($i = 0; $i < $opponentMetafyAltArtsCount; ++$i) {
                          $arr = explode("=", $opponentMetafyAltArts[$i]);
                          if (count($arr) === 2) {
                            $opponentAltArt = new stdClass();
                            $opponentAltArt->name = $metafyCommunity->CommunityName() . ($opponentMetafyAltArtsCount > 1 ? " " . $i + 1 : "");
                            $opponentAltArt->cardId = trim($arr[0]);
                            $opponentAltArt->altPath = trim($arr[1]);
                            array_push($initialLoad->opponentAltArts, $opponentAltArt);
                          }
                        }
                      }
                      break;
                    }
                  }
                }
              }
            }
          }
        }
        mysqli_close($conn);
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
    if (HasAimCounter() && $i == 0) $countersMap->aim = 1;

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
    array_push($combatChainReactions, JSONRenderedCard(
      cardNumber: $cardID,
      controller: $combatChain[$i + 1] ?? NULL,
      action: $action,
      actionDataOverride: strval($i),
      borderColor: $borderColor,
      countersMap: $countersMap,
    ));
  }

  $totalPower = 0;
  $totalDefense = 0;
  if ($combatChainCount > 0) {
    $chainPowerModifiers = [];
    EvaluateCombatChain($totalPower, $totalDefense, $chainPowerModifiers);
  }
  $blockVal = $turn[0] == "B" && ($playerID == $mainPlayer || $playerID == 3) ? 0 : $totalDefense;
  $activeChainLink->totalPower = $totalPower;

  $activeChainLink->totalDefense = $blockVal;
  $activeChainLink->reactions = $combatChainReactions;
  $activeChainLink->attackTarget = GetAttackTargetNames($mainPlayer);
  $activeChainLink->damagePrevention = ($combatChainCount > 0) ? GetDamagePrevention($defPlayer, $totalPower) : 0;
  $activeChainLink->goAgain = DoesAttackHaveGoAgain();
  $activeChainLink->dominate = CachedDominateActive();
  $activeChainLink->overpower = CachedOverpowerActive();
  $activeChainLink->confidence = SearchCurrentTurnEffects("confidence", $mainPlayer) && IsCombatEffectActive("confidence");
  $activeChainLink->activeOnHits = ActiveOnHits();
  if ($combatChainState[$CCS_RequiredEquipmentBlock] > NumEquipBlock("EQUIP")) $activeChainLink->numRequiredEquipBlock = $combatChainState[$CCS_RequiredEquipmentBlock];
  elseif ($combatChainState[$CCS_RequiredNegCounterEquipmentBlock] > NumNegCounterEquipBlock()) $activeChainLink->numRequiredEquipBlock = $combatChainState[$CCS_RequiredNegCounterEquipmentBlock];
  $activeChainLink->wager = CachedWagerActive();
  $activeChainLink->phantasm = CachedPhantasmActive();
  $activeChainLink->fusion = CachedFusionActive();
  if ($CombatChain->HasCurrentLink()) $activeChainLink->tower = IsTowerActive();
  if ($CombatChain->HasCurrentLink()) $activeChainLink->piercing = IsPiercingActive($combatChain[0]);
  if ($CombatChain->HasCurrentLink()) $activeChainLink->combo = ComboActive();
  if ($CombatChain->HasCurrentLink()) $activeChainLink->highTide = IsHighTideActive();

  $activeChainLink->fused = false;

  $response->activeChainLink = $activeChainLink;

  //Tracker State
  $tracker = new stdClass();
  $tracker->color = $playerID == $currentPlayer ? "blue" : "red";
  $layersCount = count($layers);
  $chainLinksCount = count($chainLinks);
  if ($turnPhase == "B" || $layersCount > 0 && $layers[0] == "DEFENDSTEP") $tracker->position = "Defense";
  else if ($turnPhase == "A" || $turnPhase == "D") $tracker->position = "Reactions";
  else if ($turnPhase == "PDECK" || $turnPhase == "ARS" || $layersCount > 0 && ($layers[0] == "ENDTURN" || $layers[0] == "FINALIZECHAINLINK")) $tracker->position = "EndTurn";
  else $tracker->position = ($chainLinksCount > 0 || $layersCount > 0 && $layers[0] == "ATTACKSTEP") ? "Combat" : "Main";
  $response->tracker = $tracker;

  //Display Layer
  $layerObject = new stdClass;
  $layerContents = [];
  $layerPieces = LayerPieces();
  for ($i = $layersCount - $layerPieces; $i >= 0; $i -= $layerPieces) {
    $layerName = $layers[$i] == "LAYER" || $layers[$i] == "TRIGGER" || $layers[$i] == "MELD" || $layers[$i] == "PRETRIGGER" || $layers[$i] == "ABILITY" ? $layers[$i + 2] : $layers[$i];
    array_push($layerContents, JSONRenderedCard(cardNumber: $layerName, controller: $layers[$i + 1]));
  }
  $reorderableLayers = [];
  $numReorderable = 0;
  for ($i = $layersCount - $layerPieces; $i >= 0; $i -= $layerPieces) {
    $layer = new stdClass();
    $layerName = $layers[$i] == "LAYER" || $layers[$i] == "TRIGGER" || $layers[$i] == "MELD" || $layers[$i] == "PRETRIGGER" || $layers[$i] == "ABILITY" ? $layers[$i + 2] : $layers[$i];
    $borderColor = null;
    if (str_contains($layers[$i+2], "sigil") && $layers[$i+4] == "DESTROY") $borderColor = 2;
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
  $showTheirHand = $playerID == 3 && $isCasterMode || $isGameOver || $isReplay;
  for ($i = 0; $i < $theirHandCount; ++$i) {
    $theirHandContents[] = JSONRenderedCard($showTheirHand ? $theirHand[$i] : $TheirCardBack);
  }

  $response->opponentHand = $theirHandContents;

  //Their Life
  $response->opponentHealth = $theirHealth;
  //Their Soul Count
  $response->opponentSoulCount = count($theirSoul);

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
  if($isGameOver) {
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
    $mod = explode("-", $theirBanish[$i + 1])[0];
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
    $sTypeArrCount = count($sTypeArr);
    for($j=0; $j<$sTypeArrCount; ++$j) {
      if($sTypeArr[$j] == "Head" || $sTypeArr[$j] == "Chest" || $sTypeArr[$j] == "Arms" || $sTypeArr[$j] == "Legs") {
        $sType = $sTypeArr[$j];
        break;
      }
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
    if($isGameOver) $theirCharacter[$i + 12] = "UP";
    if ($theirCharacter[$i + 12] == "UP" || $playerID == 3 && $isCasterMode || $isGameOver) {
      if($theirCharacter[$i + 1] > 0) {
      array_push($characterContents, JSONRenderedCard(
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
        facing: $theirCharacter[$i + 12],
        numUses: $theirCharacter[$i + 5],
        subcard: isSubcardEmpty($theirCharacter, $i) ? NULL : $theirCharacter[$i+10],
        marked: $theirCharacter[$i + 13] == 1,
        tapped: $theirCharacter[$i + 14] == 1
        ));
      }
    } else {
      array_push($characterContents, JSONRenderedCard(
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
          ));
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
  for ($i = 0; $i < $myHandCount; ++$i) {
    if ($playerID == 3) {
      if($isCasterMode || $isGameOver) array_push($myHandContents, JSONRenderedCard(cardNumber: $myHand[$i], controller: 2));
      else array_push($myHandContents, JSONRenderedCard(cardNumber: $MyCardBack, controller: 2));
    } else {
      $playable = ($playerID == $currentPlayer) ? $turnPhase == "ARS" || IsPlayable($myHand[$i], $turnPhase, "HAND", -1, $restriction, pitchRestriction:$resourceRestrictedCard) || $actionType == 16 && $turnPhase != "MULTICHOOSEHAND" && strpos("," . $turn[2] . ",", "," . $i . ",") !== false && $restriction == "" : false;
      $border = CardBorderColor($myHand[$i], "HAND", $playable, $playerID);
      $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? $actionType : 0;
      if ($restriction != "") $restriction = implode("_", explode(" ", $restriction));
      $actionDataOverride = ($actionType == 16 || $actionType == 27) ? strval($i) : $myHand[$i];
      array_push($myHandContents, JSONRenderedCard(cardNumber: $myHand[$i], action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, controller: $playerID, restriction: $restriction));
    }
  }
  $response->playerHand = $myHandContents;

  //My Life
  $response->playerHealth = $myHealth;
  //My Soul Count
  $response->playerSoulCount = count($mySoul);

  //My Discard
  $playerDiscardArr = [];
  $myDiscardCount = count($myDiscard);
  for($i = 0; $i < $myDiscardCount; $i += $discardPieces) {
    if (isset($myDiscard[$i+2])) {
      $overlay = 0;
      $action = $currentPlayer == $playerID && (PlayableFromGraveyard($myDiscard[$i], $myDiscard[$i+2], $playerID, $i) || AbilityPlayableFromGraveyard($myDiscard[$i], $i)) && IsPlayable($myDiscard[$i], $turnPhase, "GY", $i) ? 36 : 0;
      $mod = explode("-", $myDiscard[$i + 2])[0];
      $border = CardBorderColor($myDiscard[$i], "GY", $action == 36, $playerID, $mod);
      $cardID = $myDiscard[$i];
      if($mod == "DOWN") {
        $overlay = 1;
        $border = 0;
      }
      elseif (isFaceDownMod($mod) && $playerID == 3) $cardID = $MyCardBack;
      array_push($playerDiscardArr, JSONRenderedCard($cardID, action: $action, overlay: $overlay, borderColor: $border, actionDataOverride: strval($i)));
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
  static $blockDIOCards = ["spark_of_genius_yellow", "teklovossens_workshop_red", "teklovossens_workshop_yellow", "teklovossens_workshop_blue", "viziertronic_model_i"];
  static $blockDIOEvents = ["DOCRANK", "CHOOSEMULTIZONE"];
  $blockDIO = in_array($turnPhase, $blockDIOEvents) && isset($EffectContext) && in_array($EffectContext, $blockDIOCards);
  if($playerID < 3 && $myDeckCount > 0 && $myCharacter[1] < 3 && ($playerHero == "dash_database" || $playerHero == "dash_io") && $turnPhase != "OPT" && $turnPhase != "P" && $turnPhase != "CHOOSETOPOPPONENT" && !$blockDIO) {
    $playable = $playerID == $currentPlayer && IsPlayable($myDeck[0], $turnPhase, "DECK", 0);
    $response->playerDeckCard = JSONRenderedCard($myDeck[0], action:$playable ? 35 : 0, actionDataOverride:strval(0), borderColor: $playable ? 6 : 0, controller:$playerID);
  }
  else $response->playerDeckCard = JSONRenderedCard($myDeckCount > 0 ? $MyCardBack : $blankZone);
  $playerDeckArr = [];
  $response->playerDeckPopup = false;
  if ($playerID == $currentPlayer || $isGameOver || IsReplay()) {
    if($isGameOver || IsReplay() || ($turnPhase == "CHOOSEMULTIZONE" || $turnPhase == "MAYCHOOSEMULTIZONE") && isset($turn[2]) && substr($turn[2], 0, 6) === "MYDECK" && $turn[2] != "MYDECK-0" || $turnPhase == "MAYCHOOSEDECK" || $turnPhase == "CHOOSEDECK" || $turnPhase == "MULTICHOOSEDECK") {
      for($i=0; $i<$myDeckCount; $i+=$deckPieces) {
        array_push($playerDeckArr, JSONRenderedCard($myDeck[$i]));
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
    $mod = explode("-", $myBanish[$i + 1])[0];
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
    $sTypeArrCount = count($sTypeArr);
    for($j=0; $j<$sTypeArrCount; ++$j) {
      if($sTypeArr[$j] == "Head" || $sTypeArr[$j] == "Chest" || $sTypeArr[$j] == "Arms" || $sTypeArr[$j] == "Legs") {
        $sType = $sTypeArr[$j];
        break;
      }
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
      $powerCounters = $myCharacter[$i + 3];
    }
    if (($myCharacter[$i + 9] ?? 0) != 2 && ($myCharacter[$i + 1] ?? 0) != 0 && $playerID != 3) {
      $gem = $myCharacter[$i + 9] == 1 ? 1 : 2;
    }
    $restriction = implode("_", explode(" ", $restriction));
    if($isGameOver) ($myCharacter[$i + 12] ?? "-") == "UP";
    if($playerID == 3 &&( $myCharacter[$i + 12] ?? "-") == "DOWN" && !$isGameOver) {
      array_push($myCharData, JSONRenderedCard(
        $MyCardBack));
    }
    else{
      if(($myCharacter[$i + 1] ?? 0) > 0) {
        array_push($myCharData, JSONRenderedCard(
          $myChar,
          $currentPlayer == $playerID && $playable ? 3 : 0,
          $myCharacter[$i + 1] != 2 && $myChar != "DUMMYDISHONORED"? 1 : 0,
          $border,
          $myCharacter[$i + 1] != 0 ? $counters : 0,
          strval($i),
          0,
          $myCharacter[$i + 4],
          $powerCounters,
          $playerID,
          $type,
          $sType,
          $restriction,
          $myCharacter[$i + 1] == 0,
          $myCharacter[$i + 6] == 1,
          $myCharacter[$i + 8] == 1,
          $gem,
          label: $label,
          facing: $myCharacter[$i + 12],
          numUses: $myCharacter[$i + 5],
          subcard: isSubcardEmpty($myCharacter, $i) ? NULL : $myCharacter[$i+10],
          marked: $myCharacter[$i + 13] == 1,
          tapped: $myCharacter[$i + 14] == 1));
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
    if ($action != 0) array_push($playablePastLinks, JSONRenderedCard($cardID, $action, borderColor: $border, actionDataOverride: strval($i), label: $label));
  }
  $response->playerBanish = array_merge($response->playerBanish, $playablePastLinks);

  //Their Arsenal
  $theirArse = [];
  $theirArsenalCount = 0;
  $arsenalPieces = ArsenalPieces();
  if ($theirArsenal != "") {
    $theirArsenalCount = count($theirArsenal);
    for ($i = 0; $i < $theirArsenalCount; $i += $arsenalPieces) {
      if ($isGameOver) $theirArsenal[$i + 1] = "UP";
      if ($theirArsenal[$i + 1] == "UP" || $playerID == 3 && $isCasterMode || $isGameOver) {
        $overlay = 0;
        $border = 0;
        $cardID = $theirArsenal[$i];
        $action = $currentPlayer == $playerID && IsPlayable($cardID, $turnPhase, "THEIRARS", $i) ? 37 : 0;
        $border = CardBorderColor($cardID, "THEIRARS", $action > 0, $playerID);
        array_push($theirArse, JSONRenderedCard(
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
        ));
      } else array_push($theirArse, JSONRenderedCard(
        cardNumber: $TheirCardBack,
        controller: $playerID == 1 ? 2 : 1,
        facing: $theirArsenal[$i + 1],
        countersMap: (object) ["counters" => $theirArsenal[$i + 3]],
        isFrozen: IsFrozenMZ($theirArsenal, "ARS", $i, $otherPlayer),
        uniqueID: $theirArsenal[$i + 5]
      ));
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
      if ($playerID == 3 && !$isCasterMode && $myArsenal[$i + 1] != "UP" && !$isGameOver) {
        array_push($myArse, JSONRenderedCard(
          cardNumber: $MyCardBack,
          controller: 2,
          facing: $myArsenal[$i + 1],
          countersMap: (object) ["counters" => $myArsenal[$i + 3]],
          isFrozen: IsFrozenMZ($myArsenal, "ARS", $i, $playerID),
          uniqueID: $myArsenal[$i + 5]
        ));
      } else {
        $playable = $playerID == $currentPlayer && $turnPhase != "P" && IsPlayable($myArsenal[$i], $turnPhase, "ARS", $i, $restriction);
        $border = CardBorderColor($myArsenal[$i], "ARS", $playable, $playerID);
        $actionTypeOut = $currentPlayer == $playerID && $playable == 1 ? 5 : 0;
        if ($restriction != "") $restriction = implode("_", explode(" ", $restriction));
        $actionDataOverride = ($actionType == 16 || $actionType == 27) ? strval($i) : "";
        array_push($myArse, JSONRenderedCard(
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
        ));
      }
    }
  }
  $response->playerArse = $myArse;

  //Chain Links
  $chainLinkOutput = [];
  $chainLinkSummaryCount = count($chainLinkSummary);
  $chainLinkSummaryPieces = ChainLinkSummaryPieces();
  for ($i = 0; $i < $chainLinksCount; ++$i) {
    $damage = $chainLinkSummary[$i * $chainLinkSummaryPieces];
    $hasHit = $damage > 0 || $chainLinkSummary[$i * $chainLinkSummaryPieces + 5] == 1;
    $isDraconic = DelimStringContains($chainLinkSummary[$i * $chainLinkSummaryPieces + 2], "DRACONIC", $playerID);
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
    array_push($theirAlliesOutput,
      JSONRenderedCard(
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
        steamCounters: $theirAllies[$i + 12]));
  }
  $response->opponentAllies = $theirAlliesOutput;

  //Their Auras
  $theirAurasOutput = [];
  $theirAurasCount = count($theirAuras);
  $auraPieces = AuraPieces();
  $labeledAuras = ["blessing_of_themis_yellow", "leave_em_speechless_blue"];
  for ($i = 0; $i + $auraPieces - 1 < $theirAurasCount; $i += $auraPieces) {
    $type = CardType($theirAuras[$i]);
    $sType = CardSubType($theirAuras[$i]);
    $gem = $theirAuras[$i + 8] != 2 ? $theirAuras[$i + 8] : NULL;
    if(in_array($theirAuras[$i], $labeledAuras)) {
      $label = GamestateUnsanitize($theirAuras[$i + 10]);
    }
    elseif (!TypeContains($theirAuras[$i], "T") && $theirAuras[$i + 4] == 1) {
      $label = "Token Copy";
    }
    else $label = "";
    array_push($theirAurasOutput,
      JSONRenderedCard(cardNumber: $theirAuras[$i],
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
      tapped: $theirAuras[$i+12] == "1"));
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
    array_push($theirItemsOutput,
    JSONRenderedCard(
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
      tapped: $theirItems[$i + 10] == 1));
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
    array_push($theirPermanentsOutput, JSONRenderedCard(cardNumber: $theirPermanents[$i], controller: $otherPlayer, type: $type, sType: $sType));
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
    array_push($myAlliesOutput, JSONRenderedCard(
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
    ));
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
    if(in_array($myAuras[$i], $labeledAuras)) {
      $label = GamestateUnsanitize($myAuras[$i + 10]);
    }
    elseif (!TypeContains($myAuras[$i], "T") && $myAuras[$i + 4] == 1) {
      $label = "Token Copy";
    }
    else $label = "";
    array_push($myAurasOutput, JSONRenderedCard(
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
    ));
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
    if ($restriction != "") $restriction = implode("_", explode(" ", $restriction));
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
    array_push($myItemsOutput,
    JSONRenderedCard(
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
      tapped: $myItems[$i + 10] == 1));
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
    if ($restriction != "") $restriction = implode("_", explode(" ", $restriction));
    $actionDataOverride = strval($i);
    array_push($myPermanentsOutput, JSONRenderedCard(cardNumber: $myPermanents[$i], controller: $playerID, type: $type, sType: $sType, action: $actionTypeOut, borderColor: $border, actionDataOverride: $actionDataOverride, restriction: $restriction));
  }
  $response->playerPermanents = $myPermanentsOutput;

  //My Inventory
  $myInventoryOutput = [];
  $myInventory = &GetInventory($playerID == 1 ? 1 : 2);
  $myInventoryCount = count($myInventory);
  for ($i = 0; $i < $myInventoryCount; ++$i) {
    array_push($myInventoryOutput, JSONRenderedCard(cardNumber: $myInventory[$i], controller: $playerID));
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
    array_push($landmarksOutput, JSONRenderedCard(
      cardNumber: $landmarks[$i],
      type: $type,
      sType: $sType,
      actionDataOverride: strval($i),
      action: $action,
      borderColor: $border,
      counters: $counters
    ));
  }
  $response->landmarks = $landmarksOutput;

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

  for ($i = 0; $i + $currentTurnEffectsPieces - 1 < $currentTurnEffectsCount; $i += $currentTurnEffectsPieces) {
      $cardID = explode("-", $currentTurnEffects[$i])[0];
      $cardID = explode(",", $cardID)[0];
      if(AdministrativeEffect($cardID) || $cardID == "luminaris_angels_glow-1" || $cardID == "luminaris_angels_glow-2" || HasFancyCounters($cardID)) continue;
      $isFriendly = $playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1];

      if ($isFriendly) {
          if (!isset($friendlyCounts[$cardID])) $friendlyCounts[$cardID] = 0;
          $friendlyCounts[$cardID]++;
      } else {
          if (!isset($opponentCounts[$cardID])) $opponentCounts[$cardID] = 0;
          $opponentCounts[$cardID]++;
      }
  }
  if ($CombatChain->HasCurrentLink()) {
    if ($CombatChain->AttackCard()->StaticBuffs() != "-") {
      $activeEffects = explode(",", $CombatChain->AttackCard()->StaticBuffs());
      foreach ($activeEffects as $effectSetID) {
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
  }

  for ($i = 0; $i + $currentTurnEffectsPieces - 1 < $currentTurnEffectsCount; $i += $currentTurnEffectsPieces) {
      $cardID = explode("-", $currentTurnEffects[$i])[0];
      $cardID = explode(",", $cardID)[0];
      if(AdministrativeEffect($cardID) || $cardID == "luminaris_angels_glow-1" || $cardID == "luminaris_angels_glow-2") continue;
      $isFriendly = $playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1];
      $BorderColor = $isFriendly ? "blue" : "red";

      $counters = $isFriendly ? $friendlyCounts[$cardID] ?? 0 : $opponentCounts[$cardID] ?? 0;

      if($cardID == "shelter_from_the_storm_red" || $cardID == "calming_breeze_red") {
        $counters = $currentTurnEffects[$i + 3];
      }

      if ($playerID == $currentTurnEffects[$i + 1] || $playerID == 3 && $otherPlayer != $currentTurnEffects[$i + 1]) {
          if(array_search($cardID, $friendlyRenderedEffects) === false || !skipEffectUIStacking($cardID)) {
              array_push($friendlyRenderedEffects, $cardID);
              array_push($playerEffects, JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP", showAmpAmount:"Effect-".$i));
          }
      }
      elseif(array_search($cardID, $opponentRenderedEffects) === false && $otherPlayer == $currentTurnEffects[$i + 1] || !skipEffectUIStacking($cardID)) {
          array_push($opponentRenderedEffects, $cardID);
          array_push($opponentEffects, JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP", showAmpAmount:"Effect-".$i));
      }
  }
  if ($CombatChain->HasCurrentLink()) {
    if ($CombatChain->AttackCard()->StaticBuffs() != "-") {
      $activeEffects = explode(",", $CombatChain->AttackCard()->StaticBuffs());
      foreach ($activeEffects as $effectSetID) {
        $cardID = ExtractCardID(ConvertToCardID($effectSetID));
        if ($cardID != "") {
          $isFriendly = $playerID == $mainPlayer;
          $BorderColor = $isFriendly ? "blue" : "red";

          $counters = $isFriendly ? $friendlyCounts[$cardID] : $opponentCounts[$cardID];
          if ($isFriendly || $playerID == 3 && !$isFriendly) {
            if(array_search($cardID, $friendlyRenderedEffects) === false || !skipEffectUIStacking($cardID)) {
              array_push($friendlyRenderedEffects, $cardID);
              array_push($playerEffects, JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP"));
            }
          }
          elseif(array_search($cardID, $opponentRenderedEffects) === false && !$isFriendly || !skipEffectUIStacking($cardID)) {
            array_push($opponentRenderedEffects, $cardID);
            array_push($opponentEffects, JSONRenderedCard($cardID, borderColor:$BorderColor, counters:$counters > 1 ? $counters : NULL, lightningPlayed:"SKIP"));
          }
        }
      }
    }
  }
  $response->opponentEffects = $opponentEffects;
  $response->playerEffects = $playerEffects;

  //Events
  $newEvents = new stdClass();
  $newEvents->eventArray = [];
  $eventsCount = count($events);
  for ($i = 0; $i < $eventsCount; $i += EventPieces()) {
    $thisEvent = new stdClass();
    $thisEvent->eventType = $events[$i];
    $thisEvent->eventValue = $events[$i + 1] ?? null;
    array_push($newEvents->eventArray, $thisEvent);
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
  $response->havePriority = $currentPlayer == $playerID ? true : false;

  // For spectators, simulate havePriority as if they were player 1
  if ($playerID == 3) {
    $response->havePriority = $currentPlayer == 1 ? true : false;
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
    $helpText .= $currentPlayer != $playerID ? "Waiting for other player to choose " . TypeToPlay($turnPhase) : GetPhaseHelptext();
    switch ($currentPlayer) {
      case $playerID:
        if ($turnPhase == "P" || $turnPhase == "CHOOSEHANDCANCEL" || $turnPhase == "CHOOSEDISCARDCANCEL") {
          $helpText .= $turnPhase == "P" ? " (" . $myResources[0] . " of " . $myResources[1] . ")" : "";
          array_push($promptButtons, CreateButtonAPI($playerID, "Cancel", 10000, 0, "16px"));
        }
        if (CanPassPhase($turnPhase)) {
          if ($turnPhase == "B") {
            array_push($promptButtons, CreateButtonAPI($playerID, "Undo Block", 10001, 0, "16px"));
            array_push($promptButtons, CreateButtonAPI($playerID, "Pass", 99, 0, "16px"));
            array_push($promptButtons, CreateButtonAPI($playerID, "Pass Block and Reactions", 101, 0, "16px", "", "Reactions will not be skipped if the opponent reacts"));
          }
        }
        break;
      default:
        $currentPlayerActivity = intval(GetCachePiece($gameName, 12));
        if ($currentPlayerActivity == 1 && $playerID != 3) {
          $helpText .= " — Opponent is inactive";
          array_push($promptButtons, CreateButtonAPI($playerID, "Leave Game", 100007, 0, "16px"));
        }
        break;
    }
  }

  $playerPrompt->helpText = $helpText;
  $playerPrompt->buttons = $promptButtons;
  $response->playerPrompt = $playerPrompt;

  $response->fullRematchAccepted = $turnPhase == "REMATCH";

  $response->opponentActivity = intval(GetCachePiece($gameName, 12));

  // Build player input popup
  $response->playerInputPopUp = BuildPlayerInputPopup($playerID, $turnPhase, $turn, $gameName);

  $response->canPassPhase = CanPassPhase($turn[0]) && $currentPlayer == $playerID || $isReplay && $playerID == 3;

  $response->preventPassPrompt = "";
  if (CanPassPhase($turn[0]) && $currentPlayer == $playerID || $isReplay && $playerID == 3) {
    if ($turn[0] == "ARS" && count($myHand) > 0 && !ArsenalFull($playerID) && !$isReplay) {
      $response->preventPassPrompt = "Are you sure you want to skip arsenal?";
    }
  }

  if (CanPassPhase($turn[0]) && $currentPlayer == $playerID || $isReplay && $playerID == 3) {
    if ($turn[0] == "M" && SearchLayersForPhase("RESOLUTIONSTEP") != -1 && $actionPoints > 0 && !$isReplay) {
      $response->preventPassPrompt = "Are you sure you want to close the combat chain?";
    }
  }

  // Chat enabled check
  $chatPiece15 = intval(GetCachePiece($gameName, 15));
  $chatPiece16 = intval(GetCachePiece($gameName, 16));
  $response->chatEnabled = $chatPiece15 == 1 && $chatPiece16 == 1 ? true : false;

  $spectatorCount = 0;
  $currentTime = round(microtime(true) * 1000);
  $spectatorTimeout = 30000;
  $spectatorFile = "./Games/" . $gameName . "/spectators.txt";

  if (file_exists($spectatorFile)) {
    $content = file_get_contents($spectatorFile);
    if (!empty($content)) {
      $spectatorData = json_decode($content, true);
      if (is_array($spectatorData)) {
        $spectatorCount = 0;
        foreach ($spectatorData as $sessionKey => $spectatorInfo) {
          $timestamp = is_array($spectatorInfo) ? $spectatorInfo['timestamp'] : $spectatorInfo;

          $timeDiff = $currentTime - intval($timestamp);
          if ($timeDiff < $spectatorTimeout) {
            $spectatorCount++;
          }
        }
      }
    }
  }

  $response->spectatorCount = $spectatorCount;
  $response->spectatorNames = [];

  $cacheVisibility = GetCachePiece($gameName, 9);
  $response->isPrivate = ($cacheVisibility !== "1");

  $isReplayFlag = GetCachePiece($gameName, 10);
  $response->isReplay = ($isReplayFlag === "1");

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
    } else {
      $typingFile = "./Games/" . $gameName . "/typing_p" . $opponentID . ".txt";
      if (file_exists($typingFile)) {
        $expiryTime = intval(file_get_contents($typingFile));
        $isOpponentTyping = $expiryTime > time();
      }
    }
    $response->opponentIsTyping = $isOpponentTyping;
  }

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

function skipEffectUIStacking($cardID) {
  return !HasFancyCounters($cardID) && $cardID != "shelter_from_the_storm_red" && $cardID != "calming_breeze_red";
}


if (!function_exists('IsDevEnvironment')) {
  function IsDevEnvironment() {
    $domain = getenv("DOMAIN");
    if ($domain === "localhost") return true;
    if ($_SERVER['SERVER_NAME'] === 'localhost' || $_SERVER['SERVER_NAME'] === '127.0.0.1') return true;
    return false;
  }
}
