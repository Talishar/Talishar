<?php

function PayItemAbilityAdditionalCosts($cardID, $from)
{
  global $currentPlayer, $CS_PlayIndex, $combatChain;
  $paidSteamCounter = "NOTPAID"; 
  switch ($cardID) {
    case "WTR162":
    case "WTR170":
    case "WTR171":
    case "WTR172":
    case "ELE143":
    case "ELE172":
    case "ELE201":
    case "EVR176":
    case "EVR177":
    case "EVR178":
    case "EVR179":
    case "EVR180":
    case "EVR181":
    case "EVR182":
    case "EVR183":
    case "EVR184":
    case "EVR185":
    case "EVR186":
    case "EVR187":
      DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      break;
    case "ARC010": case "ARC018":
      $items = &GetItems($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($from == "PLAY" && $items[$index + 1] > 0 && count($combatChain) > 0) {
        $items[$index + 1] = 0;
        $items[$index + 2] = 1;
        $paidSteamCounter = "PAID";
      }
      break;
    case "CRU105":
      $items = &GetItems($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($from == "PLAY" && $items[$index + 1] > 0) {
        $items[$index + 1] = 0;
        $paidSteamCounter = "PAID";
      }
      break;
    default:
      break;
  }
  return $paidSteamCounter;
}

function ItemPlayAbilities($cardID, $from)
{
  global $currentPlayer;
  $items = &GetItems($currentPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    $remove = false;
    switch ($items[$i]) {
      case "EVR189":
        if ($from == "BANISH") {
          TalismanOfCremationBanishPlay();
          $remove = true;
        }
        break;
      default:
        break;
    }
    if ($remove) DestroyItemForPlayer($currentPlayer, $i);
  }
}

function DestroyMainItem($index)
{
  global $mainPlayer;
  DestroyItemForPlayer($mainPlayer, $index);
}

function DestroyMyItem($index)
{
  global $currentPlayer;
  DestroyItemForPlayer($currentPlayer, $index);
}

function DestroyItemForPlayer($player, $index)
{
  $items = &GetItems($player);
  if (CardType($items[$index]) != "T") {
    AddGraveyard($items[$index], $player, "PLAY");
  }
  for ($i = $index + ItemPieces() - 1; $i >= $index; --$i) {
    unset($items[$i]);
  }
  $items = array_values($items);
}

function StealItem($srcPlayer, $index, $destPlayer)
{
  $srcItems = &GetItems($srcPlayer);
  $destItems = &GetItems($destPlayer);
  for ($i = 0; $i < ItemPieces(); ++$i) {
    array_push($destItems, $srcItems[$index + $i]);
    unset($srcItems[$index + $i]);
  }
  $srcItems = array_values($srcItems);
}

function GetItemGemState($player, $cardID)
{
  global $currentPlayer;
  $items = &GetItems($player);
  $offset = ($currentPlayer == $player ? 5 : 6);
  $state = 0;
  for ($i = 0; $i < count($items); $i += ItemPieces()) {
    if ($items[$i] == $cardID && $items[$i + $offset] > $state) $state = $items[$i + $offset];
  }
  return $state;
}
