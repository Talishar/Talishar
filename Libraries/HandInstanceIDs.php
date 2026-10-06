<?php

function DecodeHandInstanceIDs($line)
{
  $decoded = json_decode(trim($line ?? ""), true);
  if (!is_array($decoded)) $decoded = [];
  $decoded["n"] = intval($decoded["n"] ?? 1);
  foreach ([1, 2] as $player) {
    $entry = $decoded[$player] ?? null;
    $decoded[$player] = [
      "h" => is_array($entry["h"] ?? null) ? array_values($entry["h"]) : [],
      "i" => is_array($entry["i"] ?? null) ? array_values($entry["i"]) : []
    ];
  }
  return $decoded;
}

function ReconcileHandInstanceIDs($prevHand, $prevIDs, $hand, &$counter, $removedIndex = null, $removedCardID = null)
{
  if (count($prevHand) != count($prevIDs)) {
    $prevHand = [];
    $prevIDs = [];
  }
  if ($removedIndex !== null && $removedIndex >= 0 && $removedIndex < count($prevHand)
    && ($removedCardID === null || $prevHand[$removedIndex] === $removedCardID)) {
    array_splice($prevHand, $removedIndex, 1);
    array_splice($prevIDs, $removedIndex, 1);
  }
  $prevCount = count($prevHand);
  $count = count($hand);
  $lcs = array_fill(0, $prevCount + 1, array_fill(0, $count + 1, 0));
  for ($i = $prevCount - 1; $i >= 0; --$i) {
    for ($j = $count - 1; $j >= 0; --$j) {
      $lcs[$i][$j] = $prevHand[$i] === $hand[$j]
        ? $lcs[$i + 1][$j + 1] + 1
        : max($lcs[$i + 1][$j], $lcs[$i][$j + 1]);
    }
  }
  $ids = array_fill(0, $count, null);
  $used = [];
  $i = 0;
  $j = 0;
  while ($i < $prevCount && $j < $count) {
    if ($prevHand[$i] === $hand[$j]) {
      $ids[$j] = $prevIDs[$i];
      $used[$i] = true;
      ++$i;
      ++$j;
    } else if ($lcs[$i + 1][$j] >= $lcs[$i][$j + 1]) {
      ++$i;
    } else {
      ++$j;
    }
  }
  for ($j = 0; $j < $count; ++$j) {
    if ($ids[$j] !== null) continue;
    for ($i = 0; $i < $prevCount; ++$i) {
      if (!isset($used[$i]) && $prevHand[$i] === $hand[$j]) {
        $ids[$j] = $prevIDs[$i];
        $used[$i] = true;
        break;
      }
    }
    if ($ids[$j] === null) $ids[$j] = "h" . $counter++;
  }
  return $ids;
}

function UpdateHandInstanceIDs()
{
  $state = $GLOBALS["handInstanceIDs"] ?? DecodeHandInstanceIDs(null);
  $removed = $GLOBALS["handInstanceRemovedIndex"] ?? [];
  $counter = $state["n"];
  foreach ([1, 2] as $player) {
    $hand = $GLOBALS["p" . $player . "Hand"] ?? [];
    $hint = $removed[$player] ?? null;
    $state[$player] = [
      "h" => $hand,
      "i" => ReconcileHandInstanceIDs($state[$player]["h"], $state[$player]["i"], $hand, $counter, $hint["index"] ?? null, $hint["card"] ?? null)
    ];
  }
  $state["n"] = $counter;
  $GLOBALS["handInstanceIDs"] = $state;
  $GLOBALS["handInstanceRemovedIndex"] = [];
  return $state;
}

function NoteHandCardRemoved($player, $index, $cardID = null)
{
  $GLOBALS["handInstanceRemovedIndex"][$player] ??= ["index" => intval($index), "card" => $cardID];
}

function ClearHandCardRemovedNote($player, $index, $cardID)
{
  if (($GLOBALS["handInstanceRemovedIndex"][$player] ?? null) === ["index" => intval($index), "card" => $cardID]) {
    unset($GLOBALS["handInstanceRemovedIndex"][$player]);
  }
}

function HandInstanceIDsFor($player)
{
  $entry = $GLOBALS["handInstanceIDs"][$player] ?? null;
  return $entry !== null && $entry["h"] === ($GLOBALS["p" . $player . "Hand"] ?? []) ? $entry["i"] : [];
}
