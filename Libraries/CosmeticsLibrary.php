<?php

// Shared cosmetic-entitlement computation, used by GetCosmetics.php (in-game options)
// and by the per-deck cosmetic save/read endpoints, so both derive the same
// "what has this user actually unlocked" answer from a single implementation.

function GetUserCosmeticsEntitlements($userName)
{
  $response = new stdClass();
  $response->cardBacks = [];
  $response->playmats = [];

  // Define default playmat IDs (kept in sync with frontend PLAYER_PLAYMATS)
  $defaultPlaymatIds = [0, 1, 2, 3, 4, 5, 6, 8, 9, 10, 11, 12, 13, 14, 15, 30, 31, 32, 33, 34, 35, 36, 42, 44, 46];

  //Add default card back
  $cardBack = new stdClass();
  $cardBack->name = "Default";
  $cardBack->id = 0;
  array_push($response->cardBacks, $cardBack);

  //Add default playmats
  foreach ($defaultPlaymatIds as $playmatId) {
    $playmat = new stdClass();
    $playmat->id = $playmatId;
    $playmat->name = GetPlaymatName($playmatId);
    array_push($response->playmats, $playmat);
  }

  if (!empty($userName)) {
    foreach (PatreonCampaign::cases() as $campaign) {
      if (isset($_SESSION[$campaign->SessionID()]) || $campaign->IsTeamMember($userName)) {
        $cardBacks = explode(",", $campaign->CardBacks());
        $cbCount = count($cardBacks);
        $campaignName = $campaign->CampaignName();
        $showSuffix = $cbCount > 1;
        for ($i = 0; $i < $cbCount; ++$i) {
          $cardBack = new stdClass();
          $cardBack->name = $campaignName . ($showSuffix ? " " . ($i + 1) : "");
          $cardBack->id = $cardBacks[$i];
          $response->cardBacks[] = $cardBack;
        }

        $playmats = explode(",", $campaign->Playmats());
        $pmCount = count($playmats);
        for ($i = 0; $i < $pmCount; ++$i) {
          $id = trim($playmats[$i]);
          if (!empty($id)) {
            $playmat = new stdClass();
            $playmat->id = $id;
            $playmat->name = GetPlaymatName($id);
            $response->playmats[] = $playmat;
          }
        }
      }
    }

    // Add Metafy community benefits
    $conn = GetDBConnection(DBL_GET_COSMETICS);
    $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, $sql)) {
      mysqli_stmt_bind_param($stmt, 's', $userName);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      mysqli_stmt_close($stmt);

      if ($row && !empty($row['metafyCommunities'])) {
        $communities = json_decode($row['metafyCommunities'], true);
        // Deduplicate by community ID before processing
        $seenCommunityIds = [];
        $uniqueCommunities = [];
        foreach ($communities as $c) {
          $cid = $c['id'] ?? null;
          if ($cid && !isset($seenCommunityIds[$cid])) {
            $seenCommunityIds[$cid] = true;
            $uniqueCommunities[] = $c;
          }
        }
        if (is_array($uniqueCommunities)) {
          foreach ($uniqueCommunities as $community) {
            $communityId = $community['id'] ?? null;
            if ($communityId) {
              // Check if this community ID matches any Metafy community
              foreach (MetafyCommunity::cases() as $metafyCommunity) {
                if ($metafyCommunity->value === $communityId) {
                  // Add card backs
                  $cardBacks = $metafyCommunity->CardBacks();
                  if (!empty($cardBacks)) {
                    $cardBackIds = explode(",", $cardBacks);
                    $cbCount = count($cardBackIds);
                    $communityName = $metafyCommunity->CommunityName();
                    $showSuffix = $cbCount > 1;
                    for ($i = 0; $i < $cbCount; ++$i) {
                      $cardBack = new stdClass();
                      $cardBack->name = $communityName . ($showSuffix ? " " . ($i + 1) : "");
                      $cardBack->id = trim($cardBackIds[$i]);
                      $response->cardBacks[] = $cardBack;
                    }
                  }
                  // Add playmats
                  $playmats = $metafyCommunity->Playmats();
                  if (!empty($playmats)) {
                    $playmatIds = explode(",", $playmats);
                    $pmCount = count($playmatIds);
                    for ($i = 0; $i < $pmCount; ++$i) {
                      $playmat = new stdClass();
                      $playmat->id = trim($playmatIds[$i]);
                      $playmat->name = GetPlaymatName($playmat->id);
                      $response->playmats[] = $playmat;
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

  // Sort playmats: Plain (Default) first, then alphabetically
  usort($response->playmats, function ($a, $b) {
    if ($a->id === 0 || $a->id === '0') return -1;
    if ($b->id === 0 || $b->id === '0') return 1;
    return strcmp($a->name, $b->name);
  });

  // Deduplicate card backs by id (safety net for multiple sources)
  $seenCardBackIds = [];
  $response->cardBacks = array_values(array_filter($response->cardBacks, function ($cb) use (&$seenCardBackIds) {
    $id = (string)($cb->id ?? '');
    if (isset($seenCardBackIds[$id])) return false;
    $seenCardBackIds[$id] = true;
    return true;
  }));

  // Deduplicate playmats by id (safety net for multiple sources)
  $seenPlaymatIds = [];
  $response->playmats = array_values(array_filter($response->playmats, function ($pm) use (&$seenPlaymatIds) {
    $id = (string)($pm->id ?? '');
    if (isset($seenPlaymatIds[$id])) return false;
    $seenPlaymatIds[$id] = true;
    return true;
  }));

  return $response;
}

// Returns a map of cardId => [altPath, ...] for every alt art this user has unlocked,
// built from the same PatreonCampaign/MetafyCommunity entitlement sources as
// GetUserCosmeticsEntitlements(), reusing their existing AltArts() methods.
function GetUserAltArtEntitlements($userName)
{
  $altArtMap = [];

  if (empty($userName)) return $altArtMap;

  $addAltArtString = function ($altArtsString) use (&$altArtMap) {
    if (empty($altArtsString)) return;
    foreach (explode(",", $altArtsString) as $entry) {
      $entry = trim($entry);
      if ($entry === "") continue;
      $parts = explode("=", $entry, 2);
      if (count($parts) !== 2) continue;
      $cardId = trim($parts[0]);
      $altPath = trim($parts[1]);
      if ($cardId === "" || $altPath === "") continue;
      if (!isset($altArtMap[$cardId])) $altArtMap[$cardId] = [];
      if (!in_array($altPath, $altArtMap[$cardId])) $altArtMap[$cardId][] = $altPath;
    }
  };

  foreach (PatreonCampaign::cases() as $campaign) {
    if (isset($_SESSION[$campaign->SessionID()]) || $campaign->IsTeamMember($userName)) {
      // PatreonCampaign::AltArts() takes the active hero's card number to resolve
      // a couple of hero-conditional bonus alt arts (via GeneratedHasEssenceOf*(),
      // only guaranteed loaded when GeneratedCode/GeneratedCardDictionaries.php is
      // included). This is account-wide entitlement listing, outside any specific
      // game/hero context, so pass no hero and let it skip straight to the
      // campaign-wide alt art list. The try/catch stays as a safety net for any
      // other caller-context surprises inside AltArts().
      try {
        $addAltArtString($campaign->AltArts());
      } catch (\Throwable $e) {
        // Ignored: see note above.
      }
    }
  }

  $conn = GetDBConnection(DBL_GET_COSMETICS);
  $sql = "SELECT metafyCommunities FROM users WHERE usersUid=?";
  $stmt = mysqli_stmt_init($conn);
  if (mysqli_stmt_prepare($stmt, $sql)) {
    mysqli_stmt_bind_param($stmt, 's', $userName);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $row = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);

    if ($row && !empty($row['metafyCommunities'])) {
      $communities = json_decode($row['metafyCommunities'], true);
      $seenCommunityIds = [];
      foreach ((array)$communities as $c) {
        $cid = $c['id'] ?? null;
        if (!$cid || isset($seenCommunityIds[$cid])) continue;
        $seenCommunityIds[$cid] = true;
        foreach (MetafyCommunity::cases() as $metafyCommunity) {
          if ($metafyCommunity->value === $cid) {
            foreach ($metafyCommunity->AltArts() as $entry) {
              $addAltArtString($entry);
            }
            break;
          }
        }
      }
    }
  }
  mysqli_close($conn);

  return $altArtMap;
}

function GetPlaymatName($id)
{
  switch ($id) {
    case 0:
      return "Plain (Default)";
    case 1:
      return "Demonastery";
    case 2:
      return "Metrix";
    case 3:
      return "Misteria";
    case 4:
      return "The Pits";
    case 5:
      return "Savage Lands";
    case 6:
      return "Solana";
    case 7:
      return "Ironsong Determination";
    case 8:
      return "Volcor";
    case 9:
      return "Data Doll";
    case 10:
      return "Korshem";
    case 11:
      return "Dynasty";
    case 12:
      return "Everfest";
    case 13:
      return "Find Center";
    case 14:
      return "Part The Mistveil";
    case 15:
      return "Rosetta";
    case 16:
      return "Bare Fangs AHS";
    case 17:
      return "Erase Face AHS";
    case 18:
      return "Dusk Till Dawn AHS";
    case 19:
      return "Exude Confidence AHS";
    case 20:
      return "Command and Conquer AHS";
    case 21:
      return "Swarming Gloomveil AHS";
    case 22:
      return "Three Floating";
    case 23:
      return "Man Sant";
    case 24:
      return "The Table Pit";
    case 25:
      return "Steelfur";
    case 26:
      return "Flesh And Bad";
    case 27:
      return "Fabled Brazil";
    case 28:
      return "New Horizons";
    case 29:
      return "Silvaris Garden";
    case 30:
      return "Candleheim";
    case 31:
      return "Isenloft";
    case 32:
      return "Volthaven";
    case 33:
      return "High Seas";
    case 34:
      return "High Seas Necro";
    case 35:
      return "The Hunted";
    case 36:
      return "Deathmatch Arena";
    case 37:
      return "Talishar Dark";
    case 38:
      return "Talishar Red";
    case 39:
      return "Talishar Blue";
    case 40:
      return "Talishar Green";
    case 41:
      return "Talishar Purple";
    case 42:
      return "Library of Solana";
    case 43:
      return "Taddle Down";
    case 44:
      return "Omens of the Third Age";
    case 45:
      return "Talishar, The Lost Prince";
    case 46:
      return "Coax A Commotion";
    default:
      return "N/A";
  }
}
