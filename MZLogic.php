<?php

function MZDestroy($player, $lastResult)
{
  $lastResultArr = explode(",", $lastResult);
  $otherPlayer = ($player == 1 ? 2 : 1);
  for ($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    switch ($mzIndex[0]) {
      case "MYHAND": $lastResult = DiscardCard($player, $mzIndex[1]); break;
      case "THEIRHAND": $lastResult = DiscardCard($otherPlayer, $mzIndex[1]); break;
      case "MYCHAR": $lastResult = DestroyCharacter($player, $mzIndex[1]); break;
      case "THEIRCHAR": $lastResult = DestroyCharacter($otherPlayer, $mzIndex[1]); break;
      case "MYALLY": $lastResult = DestroyAlly($player, $mzIndex[1]); break;
      case "THEIRALLY": $lastResult = DestroyAlly($otherPlayer, $mzIndex[1]); break;
      case "MYAURAS": $lastResult = DestroyAura($player, $mzIndex[1]); break;
      case "THEIRAURAS": $lastResult = DestroyAura($otherPlayer, $mzIndex[1]); break;
      case "MYITEMS": $lastResult = DestroyItemForPlayer($player, $mzIndex[1]); break;
      case "THEIRITEMS": $lastResult = DestroyItemForPlayer($otherPlayer, $mzIndex[1]); break;
      case "MYARS": $lastResult = DestroyArsenal($player, $mzIndex[1]); break;
      case "THEIRARS": $lastResult = DestroyArsenal($otherPlayer, $mzIndex[1]); break;
      case "LANDMARK": $lastResult = DestroyLandmark($mzIndex[1]); break;
      default: break;
    }
  }
  return $lastResult;
}

function MZRemove($player, $lastResult)
{
  //TODO: Make each removal function return the card ID that was removed, so you know what it was
  $lastResultArr = explode(",", $lastResult);
  $otherPlayer = ($player == 1 ? 2 : 1);
  for($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    switch($mzIndex[0]) {
      case "MYDISCARD": $lastResult = RemoveGraveyard($player, $mzIndex[1]); break;
      case "THEIRDISCARD": $lastResult = RemoveGraveyard($otherPlayer, $mzIndex[1]); break;
      case "MYBANISH": RemoveBanish($player, $mzIndex[1]); break;
      case "THEIRBANISH": RemoveBanish($otherPlayer, $mzIndex[1]); break;
      case "MYARS": $lastResult = RemoveArsenal($player, $mzIndex[1]); break;
      case "THEIRARS": $lastResult = RemoveArsenal($otherPlayer, $mzIndex[1]); break;
      case "MYPITCH": RemovePitch($player, $mzIndex[1]); break;
      case "THEIRPITCH": RemovePitch($otherPlayer, $mzIndex[1]); break;
      case "MYHAND": $lastResult = RemoveHand($player, $mzIndex[1]); break;
      case "THEIRHAND": $lastResult = RemoveHand($otherPlayer, $mzIndex[1]); break;
      case "THEIRAURAS": RemoveAura($otherPlayer, $mzIndex[1]); break;
      case "MYDECK":
        $deck = new Deck($player);
        return $deck->Remove($mzIndex[1]);
        break;
      default: break;
    }
  }
  return $lastResult;
}

function MZDiscard($player, $parameter, $lastResult)
{
  $lastResultArr = explode(",", $lastResult);
  $otherPlayer = ($player == 1 ? 2 : 1);
  $params = explode(",", $parameter);
  $cardIDs = [];
  for($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    $cardOwner = (substr($mzIndex[0], 0, 2) == "MY" ? $player : $otherPlayer);
    $zone = &GetMZZone($cardOwner, $mzIndex[0]);
    $cardID = $zone[$mzIndex[1]];
    AddGraveyard($cardID, $cardOwner, $params[0]);
    WriteLog(CardLink($cardID, $cardID) . " was discarded");
  }
  return $lastResult;
}

function MZAddZone($player, $parameter, $lastResult)
{
  //TODO: Add "from", add more zones
  $lastResultArr = explode(",", $lastResult);
  $otherPlayer = ($player == 1 ? 2 : 1);
  $params = explode(",", $parameter);
  $cardIDs = [];
  for($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    $cardOwner = (substr($mzIndex[0], 0, 2) == "MY" ? $player : $otherPlayer);
    $zone = &GetMZZone($cardOwner, $mzIndex[0]);
    array_push($cardIDs, $zone[$mzIndex[1]]);
  }
  for($i=0; $i<count($cardIDs); ++$i)
  {
    switch($params[0])
    {
      case "MYBANISH": BanishCardForPlayer($cardIDs[$i], $player, $params[1], $params[2]); break;
      case "MYHAND": AddPlayerHand($cardIDs[$i], $player, "-"); break;
      case "MYTOPDECK": AddTopDeck($cardIDs[$i], $player, "-"); break;
      case "MYBOTDECK": AddBottomDeck($cardIDs[$i], $player, "-"); break;
      case "THEIRBOTDECK": AddBottomDeck($cardIDs[$i], $otherPlayer, "-"); break;
      default: break;
    }
  }
  return $lastResult;
}

function MZUndestroy($player, $parameter, $lastResult)
{
  $lastResultArr = explode(",", $lastResult);
  $params = explode(",", $parameter);
  $otherPlayer = ($player == 1 ? 2 : 1);
  for($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    switch ($mzIndex[0]) {
      case "MYCHAR":
        UndestroyCharacter($player, $mzIndex[1]);
        break;
      default: break;
    }
  }
  return $lastResult;
}

function MZBanish($player, $parameter, $lastResult)
{
  $lastResultArr = explode(",", $lastResult);
  $params = explode(",", $parameter);
  $otherPlayer = ($player == 1 ? 2 : 1);
  for($i = 0; $i < count($lastResultArr); ++$i) {
    $mzIndex = explode("-", $lastResultArr[$i]);
    $cardOwner = (substr($mzIndex[0], 0, 2) == "MY" ? $player : $otherPlayer);
    $zone = &GetMZZone($cardOwner, $mzIndex[0]);
    BanishCardForPlayer($zone[$mzIndex[1]], $cardOwner, $params[0], $params[1], $params[2]);
  }
  if(count($params) <= 3) WriteLog(CardLink($zone[$mzIndex[1]], $zone[$mzIndex[1]]) . " was banished");
  return $lastResult;
}

function MZGainControl($player, $target)
{
  $targetArr = explode("-", $target);
  switch($targetArr[0])
  {
    case "MYITEMS": case "THEIRITEMS": StealItem(($player == 1 ? 2 : 1), $targetArr[1], $player); break;
    default: break;
  }
}

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
    case "ARS": case "MYARS": case "THEIRARS": return 4;
    case "ALLY": case "MYALLY": case "THEIRALLY": return 3;
    case "CHAR": case "MYCHAR": case "THEIRCHAR": return 8;
    default: return -1;
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

function GetMZCard($player, $MZIndex)
{
  $params = explode("-", $MZIndex);
  if(count($params) < 2) return "";
  if(substr($params[0], 0, 5) == "THEIR") $player = ($player == 1 ? 2 : 1);
  $zoneDS = &GetMZZone($player, $params[0]);
  $index = $params[1];
  if($index == "") return "";
  return $zoneDS[$index];
}

function MZStartTurnAbility($player, $MZIndex)
{
  $cardID = GetMZCard($player, $MZIndex);
  switch($cardID)
  {
    case "UPR086":
      AddDecisionQueue("PASSPARAMETER", $player, $MZIndex);
      AddDecisionQueue("MZREMOVE", $player, "-", 1);
      AddDecisionQueue("MULTIBANISH", $player, "GY,-", 1);
      AddDecisionQueue("FINDINDICES", $player, "UPR086");
      AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
      AddDecisionQueue("AFTERTHAW", $player, "<-", 1);
      break;
    default: break;
  }
}
