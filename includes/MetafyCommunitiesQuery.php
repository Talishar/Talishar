<?php

if (!function_exists('FetchMetafyCommunities')) {
  function FetchMetafyCommunities($userName, $dbLabel)
  {
    $conn = GetDBConnection($dbLabel);
    if (!$conn || !($conn instanceof \mysqli)) return [];

    $communities = [];
    $stmt = mysqli_stmt_init($conn);
    if (mysqli_stmt_prepare($stmt, "SELECT metafyCommunities FROM users WHERE usersUid=?")) {
      mysqli_stmt_bind_param($stmt, 's', $userName);
      mysqli_stmt_execute($stmt);
      $result = mysqli_stmt_get_result($stmt);
      $row = mysqli_fetch_assoc($result);
      if ($result) mysqli_free_result($result);
      mysqli_stmt_close($stmt);

      if ($row && !empty($row['metafyCommunities'])) {
        $decoded = json_decode($row['metafyCommunities'], true);
        if (is_array($decoded)) $communities = $decoded;
      }
    }
    mysqli_close($conn);

    return $communities;
  }
}

if (!function_exists('GetUniqueMetafyCommunities')) {
  function GetUniqueMetafyCommunities($userName, $dbLabel)
  {
    $seenCommunityIds = [];
    $uniqueCommunities = [];
    foreach (FetchMetafyCommunities($userName, $dbLabel) as $community) {
      $communityId = $community['id'] ?? null;
      if (!$communityId || isset($seenCommunityIds[$communityId])) continue;
      $seenCommunityIds[$communityId] = true;
      $uniqueCommunities[] = $community;
    }
    return $uniqueCommunities;
  }
}
