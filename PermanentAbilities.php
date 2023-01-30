<?php

function PutPermanentIntoPlay($player, $cardID)
{
  $permanents = &GetPermanents($player);
  array_push($permanents, $cardID);
  return count($permanents) - PermanentPieces();
}

function RemovePermanent($player, $index)
{
  $index = intval($index);
  $permanents = &GetPermanents($player);
  $cardID = $permanents[$index];
  for ($j = $index + PermanentPieces() - 1; $j >= $index; --$j) {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
  return $cardID;
}

function DestroyPermanent($player, $index)
{
  $index = intval($index);
  $permanents = &GetPermanents($player);
  $cardID = $permanents[$index];
  $isToken = $permanents[$index + 4] == 1;
  PermanentDestroyed($player, $cardID, $isToken);
  for ($j = $index + PermanentPieces() - 1; $j >= $index; --$j) {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
}

function PermanentDestroyed($player, $cardID, $isToken = false)
{
  $permanents = &GetPermanents($player);
  for ($i = 0; $i < count($permanents); $i += PermanentPieces()) {
    switch ($permanents[$i]) {
      default:
        break;
    }
  }
  $goesWhere = GoesWhereAfterResolving($cardID);
  if (CardType($cardID) == "T" || $isToken) return; //Don't need to add to anywhere if it's a token
  switch ($goesWhere) {
    case "GY":
      AddGraveyard($cardID, $player, "PLAY");
      break;
    case "SOUL":
      AddSoul($cardID, $player, "PLAY");
      break;
    case "BANISH":
      BanishCardForPlayer($cardID, $player, "PLAY", "NA");
      break;
    default:
      break;
  }
}

function PermanentBeginEndPhaseEffects()
{
  global $mainPlayer, $defPlayer;

  $permanents = &GetPermanents($mainPlayer);
  /*WriteLog("size of zone = " . count($permanents));
  WriteLog("zone[0] = " . $permanents[0]);*/
  for ($i = count($permanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439": case "UPR440": case "UPR441":
        PutPermanentIntoPlay($mainPlayer, "UPR043");
        $remove = 1;
        break;
      case "ROGUE501":
        $deck = &GetDeck($mainPlayer);
        $discard = &GetDiscard($mainPlayer);
        $banish = &GetBanish($mainPlayer);
        /*WriteLog("size of discard = " . count($discard));
        WriteLog("discard[0] = " . $discard[0]);
        WriteLog("discard[1] = " . $discard[1]);*/
        for($i = count($discard)-1; $i >= 0; --$i)
        {
          if(rand(0, 1) == 0) array_push($deck, $discard[$i]);
          else
          {
            array_push($banish, $discard[$i]);
            array_push($banish, "");
            array_push($banish, GetUniqueId());
          }
          /*WriteLog("banish[0] = " . $banish[0]);
          WriteLog("banish[1] = " . $banish[1]);
          WriteLog("banish[2] = " . $banish[2]);*/

          unset($discard[$i]);
        }
        $destArr = [];
        while (count($deck) > 0) {
          $index = GetRandom(0, count($deck) - 1);
          array_push($destArr, $deck[$index]);
          unset($deck[$index]);
          $deck = array_values($deck);
        }
        $deck = $destArr;
        break;
      default:
        break;
    }
    if ($remove == 1) DestroyPermanent($mainPlayer, $i);
  }

  $permanents = &GetPermanents($defPlayer);
  for ($i = count($permanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439": case "UPR440": case "UPR441":
        PutPermanentIntoPlay($defPlayer, "UPR043");
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove == 1) DestroyPermanent($defPlayer, $i);
  }
}

function PermanentTakeDamageAbilities($player, $damage, $type)
{
  $permanents = &GetPermanents($player);
  $otherPlayer = $player == 1 ? 1 : 2;
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $preventable = CanDamageBePrevented($otherPlayer, $damage, $type);
  for ($i = count($permanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439":
        if ($damage > 0) {
          if ($preventable) $damage -= 4;
          $remove = 1;
        }
        break;
      case "UPR440":
        if ($damage > 0) {
          if ($preventable) $damage -= 3;
          $remove = 1;
        }
        break;
      case "UPR441":
        if ($damage > 0) {
          if ($preventable) $damage -= 2;
          $remove = 1;
        }
        break;
      default:
        break;
    }
    if ($remove == 1) {
      if (HasWard($permanents[$i]) && SearchCharacterActive($player, "DYN213") && CardType($permanents[$i]) != "T") {
        $index = FindCharacterIndex($player, "DYN213");
        $char[$index + 1] = 1;
        GainResources($player, 1);
      }
      DestroyPermanent($player, $i);
    }
  }
  if ($damage <= 0) $damage = 0;
  return $damage;
}

function PermanentStartTurnAbilities()
{
  global $mainPlayer, $defPlayer;

  $permanents = &GetPermanents($mainPlayer);
  $defPermanents = &GetPermanents($defPlayer);
  $character = &GetPlayerCharacter($mainPlayer);
  $hand = &GetHand($mainPlayer);
  /*WriteLog("size of hand = " . count($hand));
  WriteLog("hand[0] = " . $hand[0]);*/
  for ($i = count($permanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "ROGUE502":
        array_push($hand, $hand[rand(0, count($hand)-1)]);
        break;
      case "ROGUE503":
        $choices = array("WTR098", "WTR099", "WTR100");
        array_push($hand, $choices[rand(0, count($choices)-1)]);
        break;
      case "ROGUE504":
        for($j = 0; $j < count($character)-1; ++$j)
        {
          //if(CardType($character[$j]) == "W") WriteLog("Found " . $character[$j]);
          if(CardType($character[$j]) == "W") $character[$j + 3] += 1;
          //WriteLog("character[" . $j . "] = " . $character[$j]);
        }
        break;
      case "ROGUE505":
        AddCurrentTurnEffect($permanents[$i], $mainPlayer);
        break;
      case "ROGUE506":
        AddCurrentTurnEffect($permanents[$i], $mainPlayer);
        break;
      default:
        break;
    }
  }
  for ($i = count($defPermanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($defPermanents[$i]) {
      case "ROGUE506":
        AddCurrentTurnEffect($defPermanents[$i], $defPlayer);
        break;
      default:
        break;
    }
  }
}
/*
function DestroyAlly($player, $index)
{
  $allies = &GetAllies($player);
  for($j = $index+AllyPieces()-1; $j >= $index; --$j)
  {
    unset($allies[$j]);
  }
  $allies = array_values($allies);
}
*/
