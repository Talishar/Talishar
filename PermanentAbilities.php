<?php

function PutPermanentIntoPlay($player, $cardID, $number=1, $isToken=false, $from="-", $subCards="-", $uniqueID="-")
{
  global $EffectContext;
  $permanents = &GetPermanents($player);
  $otherPlayer = $player == 1 ? 2 : 1;
  if (TypeContains($EffectContext, "C", $player) && (SearchAurasForCard("preach_modesty_red", 1) != "" || SearchAurasForCard("preach_modesty_red", 2) != "")) {
    WriteLog("🙇 " . CardLink("preach_modesty_red", "preach_modesty_red") . " prevents the creation of " . CardLink($cardID, $cardID));
    return;
  }
  if(TypeContains($cardID, "T", $player)) $isToken = true;
  $numMinusTokens = 0;
  $numMinusTokens = CountCurrentTurnEffects("ripple_away_blue", $player) + CountCurrentTurnEffects("ripple_away_blue", $otherPlayer);
  if($numMinusTokens > 0 && $isToken && (TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "A", $player))) $number -= $numMinusTokens;
  for($i = 0; $i < $number; ++$i) {
    array_push($permanents, $cardID);
    array_push($permanents, $from);
    array_push($permanents, $subCards);
    array_push($permanents, $uniqueID == "-" ? GetUniqueId($cardID, $player) : $uniqueID);
  }
  return count($permanents) - PermanentPieces();
}

function RemovePermanent($player, $index)
{
  $index = intval($index);
  $permanents = &GetPermanents($player);
  $PermanentCard = new PermanentCard($index, $player);
  $cardID = $permanents[$index];
  if ($cardID == "UPR439" || $cardID == "UPR440" || $cardID == "UPR441") {
    $cardID = explode(",", $PermanentCard->Subcards())[0];
  }
  $permanentPieces = PermanentPieces();
  for($j = $index + $permanentPieces - 1; $j >= $index; --$j) {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
  return $cardID;
}

function DestroyPermanent($player, $index)
{
  if($index == -1) return;
  $index = intval($index);
  $permanents = &GetPermanents($player);
  $cardID = $permanents[$index];
  $isToken = isset($permanents[$index + 4]) ? ($permanents[$index + 4] == 1) : false;
  PermanentDestroyed($player, $cardID, $isToken);
  $permanentPieces = PermanentPieces();
  for ($j = $index + $permanentPieces - 1; $j >= $index; --$j) {
    unset($permanents[$j]);
  }
  $permanents = array_values($permanents);
}

function PermanentDestroyed($player, $cardID, $isToken = false)
{
  $permanents = &GetPermanents($player);
  $countPermanents = count($permanents);
  $permanentPieces = PermanentPieces();
  for ($i = 0; $i < $countPermanents; $i += $permanentPieces) {
    switch ($permanents[$i]) {
      default:
        break;
    }
  }
  $goesWhere = GoesWhereAfterResolving($cardID);
  if (CardType($cardID) == "T" || $isToken || CardType($cardID) == "Macro") return; //Don't need to add to anywhere if it's a token
  ResolveGoesWhere($goesWhere, $cardID, $player, "PLAY");
}

function PermanentBeginEndPhaseEffects()
{
  global $mainPlayer, $defPlayer;
  $permanents = &GetPermanents($mainPlayer);
  $countPermanents = count($permanents);
  $permanentPieces = PermanentPieces();
  for ($i = $countPermanents - $permanentPieces; $i >= 0; $i -= $permanentPieces) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439": case "UPR440": case "UPR441":
        $origMaterial = explode(",", $permanents[$i+2])[0];
        if ($origMaterial != "-") PutPermanentIntoPlay($mainPlayer, $origMaterial);
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove == 1) DestroyPermanent($mainPlayer, $i);
  }

  $permanents = &GetPermanents($defPlayer);
  $countPermanents = count($permanents);
  for ($i = $countPermanents - $permanentPieces; $i >= 0; $i -= $permanentPieces) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439": case "UPR440": case "UPR441":
        $origMaterial = explode(",", $permanents[$i+2])[0];
        if ($origMaterial != "-") PutPermanentIntoPlay($defPlayer, $origMaterial);
        $remove = 1;
        break;
      default:
        break;
    }
    if ($remove == 1) DestroyPermanent($defPlayer, $i);
  }
}

function PermanentDamagePreventionAmount($player, $index, $damage)
{
  $permanents = &GetPermanents($player);
  $preventedDamage = 0;
  switch ($permanents[$index]) {
    case "UPR439":
      $preventedDamage += 4;
      break;
    case "UPR440":
      $preventedDamage += 3;
      break;
    case "UPR441":
      $preventedDamage += 2;
      break;
    default:
      break;
  }
  return $preventedDamage;
}

function PermanentTakeDamageAbilities($player, $index, $damage, $preventable, $type)
{
  $char = &GetPlayerCharacter($player);
  $permanents = &GetPermanents($player);
  //CR 2.1 6.4.10f If an effect states that a prevention effect can not prevent the damage of an event, the prevention effect still applies to the event but its prevention amount is not reduced. Any additional modifications to the event by the prevention effect still occur.
  $preventedDamage = 0;
  $countPermanents = count($permanents);
  $permanentPieces = PermanentPieces();
  for ($i = $countPermanents - $permanentPieces; $i >= 0; $i -= $permanentPieces) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "UPR439":
        if ($damage > 0) {
          if ($preventable) $preventedDamage += 4;
          $remove = 1;
        }
        break;
      case "UPR440":
        if ($damage > 0) {
          if ($preventable) $preventedDamage += 3;
          $remove = 1;
        }
        break;
      case "UPR441":
        if ($damage > 0) {
          if ($preventable) $preventedDamage += 2;
          $remove = 1;
        }
        break;
      default:
        break;
    }
    if ($remove == 1) {
      if (HasWard($permanents[$i], $player) && SearchCharacterActive($player, "celestial_kimono") && CardType($permanents[$i]) != "T") {
        $index = FindCharacterIndex($player, "celestial_kimono");
        $char[$index + 1] = 1;
        GainResources($player, 1);
        WriteLog("Player " . $player . " gained 1 resource from " . CardLink("celestial_kimono", "celestial_kimono"));
      }
      DestroyPermanent($player, $i);
    }
  }
  if (SearchCurrentTurnEffects("vambrace_of_determination", $player) != "" && $preventedDamage > 0) {//vambrace
    $preventedDamage -= 1;
    SearchCurrentTurnEffects("vambrace_of_determination", $player, remove:true);
  }
  $damage -= $preventedDamage;
  if ($damage <= 0) $damage = 0;
  return $damage;
}