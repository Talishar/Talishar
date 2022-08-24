<?php

function MZFreeze($target)
{
  global $currentPlayer;
  $pieces = explode("-", $target);
  $player = (substr($pieces[0], 0, 2) == "MY" ? $currentPlayer : ($currentPlayer == 1 ? 2 : 1));
  $zone = &GetMZZone($player, $pieces[0]);
  switch ($pieces[0]) {
    case "THEIRCHAR":
    case "MYCHAR":
      $zone[$pieces[1] + 8] = 1;
      break;
    case "THEIRALLY":
    case "MYALLY":
      $zone[$pieces[1] + 3] = 1;
      break;
    case "THEIRARS":
    case "MYARS":
      $zone[$pieces[1] + 4] = 1;
      break;
    default:
      break;
  }
}

function IsFrozenMZ(&$array, $zone, $i)
{
  $offset = FrozenOffsetMZ($zone);
  if ($offset == -1) return false;
  return $array[$i + $offset] == "1";
}

function UnfreezeMZ($player, $zone, $index)
{
  $offset = FrozenOffsetMZ($zone);
  if ($offset == -1) return false;
  $array = &GetMZZone($player, $zone);
  $array[$index + $offset] = "0";
}

function FrozenOffsetMZ($zone)
{
  switch ($zone) {
    case "ARS":
    case "MYARS":
    case "THEIRARS":
      return 4;
    case "ALLY":
    case "MYALLY":
    case "THEIRALLY":
      return 3;
    case "CHAR":
    case "MYCHAR":
    case "THEIRCHAR":
      return 8;
    default:
      return -1;
  }
}

function MZIsPlayer($MZIndex)
{
  $indexArr = explode("-", $MZIndex);
  if ($indexArr[0] == "MYCHAR" || $indexArr[0] == "THEIRCHAR") return true;
  return false;
}

function MZPlayerID($me, $MZIndex)
{
  $indexArr = explode("-", $MZIndex);
  if ($indexArr[0] == "MYCHAR") return $me;
  if ($indexArr[0] == "THEIRCHAR") return ($me == 1 ? 2 : 1);
  return -1;
}

function GetMZCard($MZIndex)
{
  global $combatChain, $layers;
  $indexArr = explode("-", $MZIndex);
  switch($indexArr[0])
  {
    case "CC": return $combatChain[$indexArr[1]];
    case "LAYER": return $combatChain[$indexArr[1]];
    default: return "";
  }
}
