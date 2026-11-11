<?php

function HitPreviewOpen()
{
  global $combatChain, $layers;
  return count($combatChain ?? []) > 0 && !IsGameOver() && ($layers[0] ?? "") !== "FINALIZECHAINLINK" && SearchLayersForPhase("RESOLUTIONSTEP") == -1;
}

function HitPreviewResponse($gameName, $playerID, $authKey, $sessionData)
{
  global $p1Key, $p2Key;
  $viewer = intval($playerID);
  if (!in_array($viewer, [1, 2, 3], true)) return ["error" => "Invalid player ID."];
  $snap = ReadCacheArray($gameName);
  ParseGamestate(false);
  if (($snap[9] ?? "") === "1" || !HitPreviewOpen()) return ["error" => "Hit preview closed."];
  if ($viewer != 3 && $authKey !== ($viewer == 1 ? $p1Key : $p2Key)) return ["error" => "Invalid Authkey"];
  if ($viewer == 3 && ($snap[8] ?? "") != "1" && empty($sessionData["friendList"])) return ["error" => "Spectators not allowed."];
  $GLOBALS["hitPreview"] = [];
  ob_start();
  try {
    $response = HitPreviewRun($gameName, $viewer, $authKey, $sessionData, $snap);
  } catch (Throwable $e) {
    $response = null;
  }
  ob_end_clean();
  return is_object($response) ? $response : ["error" => "Hit preview failed."];
}

function HitPreviewRun($gameName, $viewer, $authKey, $sessionData, $snap)
{
  global $playerID, $currentPlayer, $turn, $layers, $decisionQueue, $combatChain, $defPlayer, $events;
  if (($turn[0] ?? "") == "B" && $viewer != $defPlayer) HitPreviewDropPendingBlocks();
  $backs = [];
  for ($p = 1; $p <= 2; ++$p) {
    HitPreviewHide($p, "MYDECK", $backs);
    if ($p == $viewer) continue;
    HitPreviewHide($p, "MYCHAR", $backs, fn($zone, $i) => $i > 0 && ($zone[$i + 12] ?? "") == "DOWN");
    if (!IsOpenHands($p)) HitPreviewHide($p, "MYHAND", $backs);
    HitPreviewHide($p, "MYARS", $backs, fn($zone, $i) => ($zone[$i + 1] ?? "") == "DOWN");
    HitPreviewHide($p, "MYBANISH", $backs, fn($zone, $i) => isFaceDownMod(explode("-", $zone[$i + 1] ?? "")[0]));
    HitPreviewHide($p, "MYDISCARD", $backs, fn($zone, $i) => isFaceDownMod($zone[$i + 2] ?? ""));
  }
  $hands = [1 => GetHand(1), 2 => GetHand(2)];
  $landed = [];
  for ($p = 1; $p <= 2; ++$p) {
    foreach (["MYHAND", "MYDECK", "MYBANISH", "MYDISCARD", "MYARS", "MYPITCH", "MYSOUL"] as $zone) $landed[] = [$p, $zone, HitPreviewCardIDs($p, $zone)];
  }

  for ($step = 0; count($combatChain) > 0 && SearchLayersForPhase("RESOLUTIONSTEP") == -1 && (($layers[0] ?? "") != "FINALIZECHAINLINK" || count($decisionQueue) > 0); ++$step) {
    if ($step == 80) return null;
    $playerID = $currentPlayer;
    BuildMyGamestate($playerID);
    $before = serialize([$turn, $currentPlayer, $layers, $decisionQueue]);
    $phase = $turn[0] ?? "";
    $options = $turn[2] ?? "";
    $picks = HitPreviewPickCount($phase, $options);
    if (CanPassPhase($phase)) ProcessInput($playerID, 99, "", "", 0, "", true);
    else if ($phase == "YESNO") ProcessInput($playerID, 20, "NO", "", 0, "", true);
    else if ($picks !== null) ProcessInput($playerID, 19, "", "", $picks, $picks > 0 ? range(0, $picks - 1) : [], true);
    else if (count($decisionQueue) > 0 && $options !== "") ProcessInput($playerID, 16, "", explode(",", $options)[0], 0, "", true);
    else return null;
    if (serialize([$turn, $currentPlayer, $layers, $decisionQueue]) === $before) return null;
  }

  BuildMyGamestate($viewer);
  $playerID = $viewer;
  $mySide = $viewer == 1 ? 1 : 2;
  $changes = [];
  for ($p = 1; $p <= 2; ++$p) {
    $hand = &GetHand($p);
    [$gained, $lost] = HitPreviewDiff($hands[$p], $hand);
    if ($p == $viewer) {
      $hand = array_merge($hands[$p], $gained);
      foreach ($lost as $cardID) {
        foreach ($landed as [$owner, $zone, $ids]) {
          $hits = array_keys(HitPreviewCardIDs($owner, $zone), $cardID, true);
          if (count($hits) <= count(array_keys($ids, $cardID, true))) continue;
          $ref = &GetMZZone($owner, $zone);
          $ref[end($hits) * GetMZZonePieces($zone)] = GetCardBack($p);
          break;
        }
      }
    }
    if ($gained || $lost) $changes[] = ["kind" => "hand", "side" => $p == $mySide ? "player" : "opponent", "gained" => count($gained), "lost" => count($lost)];
    $health = &GetHealth($p);
    if (intval($health) < 0) $health = 0;
  }
  foreach (["MYHAND", "MYDECK", "MYBANISH", "MYDISCARD", "MYARS", "MYPITCH", "MYSOUL", "MYITEMS", "MYAURAS", "MYCHAR", "MYALLY", "MYPERM", "LAYER", "CC"] as $zone) {
    for ($p = 1; $p <= 2; ++$p) {
      $ref = &GetMZZone($p, $zone);
      $ref = array_map(fn($value) => is_string($value) ? ($backs[$value] ?? $value) : (is_int($value) || is_float($value) ? (string)$value : $value), $ref);
    }
  }
  $events = [];
  DoGamestateUpdate();
  foreach (array_count_values($GLOBALS["hitPreview"]) as $key => $max) {
    [$p, $source] = explode(" ", $key, 2);
    $changes[] = ["kind" => "maybeToken", "side" => $p == $mySide ? "player" : "opponent", "cardID" => "silver", "source" => $source, "max" => $max];
  }

  $response = BuildGameStateResponse($gameName, $viewer, $authKey, $sessionData, false, false, $snap, false);
  if (is_object($response)) $response->hitPreview = $changes;
  return $response;
}

function HitPreviewDropPendingBlocks()
{
  global $combatChain, $defPlayer;
  $links = array_chunk($combatChain, CombatChainPieces());
  foreach ($links as $k => [0 => $cardID, 1 => $owner, 2 => $from, 7 => $uniqueID, 8 => $originUniqueID]) {
    if ($k == 0 || $owner != $defPlayer) continue;
    unset($links[$k]);
    if ($from == "HAND") {
      $hand = &GetHand($defPlayer);
      $hand[] = $cardID;
    } else if ($from == "ARS") {
      $arsenal = &GetArsenal($defPlayer);
      array_push($arsenal, $cardID, "DOWN", "1", "0", "0", $uniqueID, "0");
    } else if ($from == "PLAY") {
      $items = &GetItems($defPlayer);
      if (($i = ZoneFind($items, ItemPieces(), 4, $originUniqueID)) >= 0) $items[$i + 13] = 0;
    } else if ($from == "CHAR" || $from == "EQUIP") {
      $character = &GetPlayerCharacter($defPlayer);
      if (($i = ZoneFind($character, CharacterPieces(), 11, $originUniqueID)) >= 0) $character[$i + 6] = 0;
    }
  }
  $combatChain = array_merge(...$links);
}

function HitPreviewHide($player, $zone, &$backs, $hidden = null)
{
  $ref = &GetMZZone($player, $zone);
  for ($i = 0, $pieces = GetMZZonePieces($zone); $i < count($ref); $i += $pieces) {
    if ($hidden && !$hidden($ref, $i)) continue;
    $ref[$i] = "hpvhidden" . count($backs);
    $backs[$ref[$i]] = GetCardBack($player);
  }
}

function HitPreviewCardIDs($player, $zone)
{
  return array_column(array_chunk(GetMZZone($player, $zone), GetMZZonePieces($zone)), 0);
}

function HitPreviewDiff($before, $after)
{
  $lost = [];
  foreach ($before as $cardID) {
    $i = array_search($cardID, $after, true);
    if ($i === false) $lost[] = $cardID;
    else unset($after[$i]);
  }
  return [array_values($after), $lost];
}

function HitPreviewPickCount($phase, $options)
{
  if (preg_match('/^(MAY)?MULTICHOOSE/', $phase)) {
    $params = explode("-", $options);
    return min(max(count($params) > 2 ? intval($params[2]) : -1, 1), intval($params[0]), ($params[1] ?? "") === "" ? 0 : count(explode(",", $params[1])));
  }
  if (($phase != "CHOOSEMULTIZONE" && $phase != "MAYCHOOSEMULTIZONE") || !preg_match('/^M(AX|IN)COUNT-/', $options)) return null;
  $parts = explode(",", $options);
  $limits = ["MAXCOUNT" => 0, "MINCOUNT" => 0];
  $offset = 0;
  foreach (array_slice($parts, 0, 2) as $part) {
    $limit = explode("-", $part, 2);
    if (!isset($limits[$limit[0]])) continue;
    $limits[$limit[0]] = intval($limit[1] ?? 0);
    ++$offset;
  }
  return min(max($limits["MINCOUNT"], 1), $limits["MAXCOUNT"], count($parts) - $offset);
}
