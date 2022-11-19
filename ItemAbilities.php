<?php

function PayItemAbilityAdditionalCosts($cardID, $from)
{
  global $currentPlayer, $CS_PlayIndex, $combatChain;
  $paidSteamCounter = "NOTPAID";
  switch ($cardID) {
    case "WTR162":
    case "WTR170": case "WTR171": case "WTR172":
    case "ELE143": case "ELE172": case "ELE201":
    case "EVR176": case "EVR177": case "EVR178":
    case "EVR179": case "EVR180": case "EVR181":
    case "EVR182": case "EVR183": case "EVR184":
    case "EVR185": case "EVR186": case "EVR187":
      DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
      break;
    case "ARC035":
      $items = &GetItems($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $paidSteamCounter = $items[$index + 1];
      DestroyMyItem($index);
      break;
    case "ARC010": case "ARC018":
      $items = &GetItems($currentPlayer);
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      if ($from == "PLAY" && $items[$index + 1] > 0 && count($combatChain) > 0) {
        $items[$index + 1] -= 1;
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
  if (CardType($items[$index]) != "T" && GoesWhereAfterResolving($items[$index], "PLAY", $player) == "GY") {
    AddGraveyard($items[$index], $player, "PLAY");
  }
  for ($i = $index + ItemPieces() - 1; $i >= $index; --$i) {

    //Mechanoid Check
    if ($items[$i] == "DYN492c") {
      $indexWeapon = FindCharacterIndex($player, "DYN492a"); // Weapon
      DestroyCharacter($player, $indexWeapon);
      $indexEquipment = FindCharacterIndex($player, "DYN492b"); // Equipment
      DestroyCharacter($player, $indexEquipment);
    }

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

function ItemHitEffects($attackID)
{
  global $mainPlayer;
  $attackSubType = CardSubType($attackID);
  $items = &GetItems($mainPlayer);
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    switch ($items[$i]) {
      case "DYN094":
        if ($attackSubType == "Gun" && ClassContains($attackID, "MECHANOLOGIST", $mainPlayer)) {
          AddLayer("TRIGGER", $mainPlayer, $items[$i], "-", "-", $items[$i + 4]);
        }
        break;
      default:
        break;
    }
  }
}

function ItemTakeDamageAbilities($player, $damage, $type)
{
  $items = &GetItems($player);
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  for($i=count($items) - ItemPieces(); $i >= 0 && $damage > 0; $i -= ItemPieces())
  {
    switch($items[$i])
    {
      case "CRU104":
        if($damage > $items[$i+1]) { if($preventable) $damage -= $items[$i+1]; $items[$i+1] = 0; }
        else { $items[$i+1] -= $damage; if($preventable) $damage = 0; }
        if($items[$i+1] <= 0) DestroyItemForPlayer($player, $i);
    }
  }
  return $damage;
}
