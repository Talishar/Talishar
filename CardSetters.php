<?php

function BanishCardForPlayer($cardID, $player, $from, $modifier = "-", $banishedBy = "")
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor, $CS_NumCrouchingTigerCreatedThisTurn;
  if (CardNameContains($cardID, "Crouching Tiger", $player)) IncrementClassState($player, $CS_NumCrouchingTigerCreatedThisTurn);
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) return BanishCard($mainBanish, $mainClassState, $cardID, $modifier, $player, $from, $banishedBy);
    else return BanishCard($defBanish, $defClassState, $cardID, $modifier, $player, $from, $banishedBy);
  } else {
    if ($player == $myStateBuiltFor) return BanishCard($myBanish, $myClassState, $cardID, $modifier, $player, $from, $banishedBy);
    else return BanishCard($theirBanish, $theirClassState, $cardID, $modifier, $player, $from, $banishedBy);
  }
}

function BanishCard(&$banish, &$classState, $cardID, $modifier, $player = "", $from = "", $banishedBy = "")
{
  global $CS_CardsBanished, $actionPoints, $CS_Num6PowBan, $currentPlayer, $mainPlayer, $combatChain, $EffectContext;
  $rv = -1;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = $player == 1 ? 2 : 1;
  $character = &GetPlayerCharacter($player);
  $items = &GetItems($player);
  $characterID = ShiyanaCharacter($character[0]);
  AddEvent("BANISH", ($modifier == "INT" || $modifier == "NTINT" || $modifier == "UZURI" ? "CardBack" : $cardID));
  //Effects that change the modifier
  if ($characterID == "DTD564" && $character[1] < 3) {
    AddLayer("TRIGGER", $player, $characterID);
    if ($modifier != "INT") $modifier = "DTD564";
  }
  //Do effects that change where it goes, or banish it if not
  if ($from == "DECK" && (SearchCharacterActive($player, "CRU099") || SearchCurrentTurnEffects("CRU099-SHIYANA", $player)) && CardSubType($cardID) == "Item" && CardCost($cardID) <= 2) {
    $character = &GetPlayerCharacter($player);
    AddLayer("TRIGGER", $player, $character[0], $cardID);
  }
  if (CardType($cardID) != "T") { //If you banish a token, the token ceases to exist.
    $rv = count($banish);
    array_push($banish, $cardID);
    array_push($banish, $modifier);
    array_push($banish, GetUniqueId($cardID, $player));
  }
  ++$classState[$CS_CardsBanished];
  if ($modifier == "INT" || $modifier == "NTINT") return $rv;
  //Do additional effects
  if ($cardID == "DTD109" && $from == "HAND" && $modifier != "DTD564" && ($modifier != "NOFEAR" || $player == $mainPlayer)) $banish[count($banish) - 2] = "TT";
  if (($modifier == "BOOST" || $from == "DECK") && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178") && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player))) {
    WriteLog("Gained 1 action point from banishing " . CardLink($cardID, $cardID));
    ++$actionPoints;
  }
  if (($modifier == "BOOST" && $from == "DECK") && ($cardID == "DYN101" || $cardID == "DYN102" || $cardID == "DYN103")) {
    WriteLog(CardLink($cardID, $cardID) . " was banished to pay a boost cost. Put a counter on a Hyper Drive you control.");
    AddLayer("TRIGGER", $player, $cardID);
  }
  if (ModifiedAttackValue($cardID, $player, $from, source: $banishedBy) >= 6) {
    if ($classState[$CS_Num6PowBan] == 0 && $player == $mainPlayer && ($characterID == "MON119" || $characterID == "MON120") && $character[1] == 2) { // Levia
      WriteLog(CardLink($characterID, $characterID) . " banished a card with 6+ power, and won't lose life from Blood Debt this turn");
    }
    ++$classState[$CS_Num6PowBan];
    $index = FindCharacterIndex($player, "MON122");
    if ($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem: true) && $player == $mainPlayer && SearchLayersForCardID("MON122") == -1) {
      AddLayer("TRIGGER", $player, $character[$index]);
    }
  }
  if (TypeContains($cardID, "E", $player)) {
    $charIndex = FindCharacterIndex($player, $cardID);
    if ($charIndex == -1) {
      DestroyCharacter($player, $charIndex, skipDestroy: true);
      CharacterBanishEffect($cardID, $player);
    } else DestroyCharacter($player, $charIndex, wasBanished: true);
  }
  if ($banishedBy != "" && $player != $mainPlayer) CheckContracts($mainPlayer, $cardID);
  if ($banishedBy == "DTD193" && TalentContains($cardID, "LIGHT", $player)) {
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if ($banishedBy == "MST104" && (CardType($cardID) == "A" || CardType($cardID) == "AA")) {
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if ($banishedBy == "MST106" && ColorContains($cardID, 1, $currentPlayer)) {
    Draw($otherPlayer);
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if ($banishedBy == "MST107" && ColorContains($cardID, 2, $currentPlayer)) {
    Draw($otherPlayer);
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if ($banishedBy == "MST108" && ColorContains($cardID, 3, $currentPlayer)) {
    Draw($otherPlayer);
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if ($banishedBy == "MST109" || $banishedBy == "MST110" || $banishedBy == "MST111" && count($banish) / BanishPieces() >= 2) {
    $indexArray = array_keys($banish, "Source-" . $banishedBy);
    $count = count($banish) - BanishPieces();
    $pitchValues = [];
    for ($i = $count; $i >= 0; $i--) {
      if ($banish[$i + 1] == "Source-" . $banishedBy) {
        array_push($pitchValues, PitchValue($banish[$i]));
      }
    }
    if (count($pitchValues) !== count(array_unique($pitchValues))) {
      GainHealth(1, $otherPlayer);
    }
    return $rv;
  }
  if (($banishedBy == "MST115" || $banishedBy == "MST116" || $banishedBy == "MST117") && count($banish) / BanishPieces() >= 2) {
    $count = count($banish) - BanishPieces();
    $cardNames = [];
    for ($i = $count; $i >= 0; $i--) {
      if ($banish[$i + 1] == "Source-" . $banishedBy) {
        array_push($cardNames, CardName($banish[$i]));
      }
    }
    if (count($cardNames) !== count(array_unique($cardNames))) {
      GainHealth(1, $otherPlayer);
    }
    return $rv;
  }
  if (($banishedBy == "MST118" || $banishedBy == "MST119" || $banishedBy == "MST120") && TypeContains($cardID, "AA", $player)) {
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if (($banishedBy == "MST121" || $banishedBy == "MST122" || $banishedBy == "MST123") && (TypeContains($cardID, "AR", $player) || TypeContains($cardID, "DR", $player) || TypeContains($cardID, "I", $player))) {
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  if (($banishedBy == "MST124" || $banishedBy == "MST125" || $banishedBy == "MST126") && TypeContains($cardID, "A", $player)) {
    GainHealth(1, $otherPlayer);
    return $rv;
  }
  return $rv;
}

function RemoveBanish($player, $index)
{
  $banish = &GetBanish($player);
  $cardID = $banish[$index];
  for ($i = $index + BanishPieces() - 1; $i >= $index; --$i) {
    unset($banish[$i]);
  }
  $banish = array_values($banish);
  return $cardID;
}

//When it matters, make it save this off to a different zone
function TurnBanishFaceDown($player, $index)
{
  $banish = &GetBanish($player);
  $banish[$index + 1] = "FACEDOWN";
}

function AddBottomDeck($cardID, $player, $from)
{
  if(TypeContains($cardID, "T", $player)) { // 'T' type indicates the card is a token
    WriteLog(CardLink($cardID, $cardID) . " is a token. So instead of going to the bottom of the deck, it ceases to exist.");
  }
  else {
  $deck = &GetDeck($player);
  array_push($deck, $cardID);
  }
}

function AddTopDeck($cardID, $player, $from, $deckIndexModifier = 0)
{
  $deck = &GetDeck($player);
  if ($deckIndexModifier == 0) {
    array_unshift($deck, $cardID);
    return;
  }
  array_splice($deck, $deckIndexModifier, 0, $cardID);
}

function AddPlayerHand($cardID, $player, $from, $amount = 1)
{
  global $CS_NumCrouchingTigerCreatedThisTurn;
  if(TypeContains($cardID, "T", $player)) { // 'T' type indicates the card is a token
    WriteLog(CardLink($cardID, $cardID) . " is a token. So instead of going to hand, it ceases to exist.");
  }
  else {
  $hand = &GetHand($player);
  if (CardNameContains($cardID, "Crouching Tiger", $player)) IncrementClassState($player, $CS_NumCrouchingTigerCreatedThisTurn);
    for ($i = 0; $i < $amount; ++$i) {
      array_push($hand, $cardID);
    }
  }
}

function RemoveHand($player, $index)
{
  $hand = &GetHand($player);
  if (empty($hand)) return "";
  $cardID = $hand[$index];
  array_splice($hand, $index, HandPieces());
  return $cardID;
}

function RemoveDeck($player, $index)
{
  $deck = &GetDeck($player);
  if (empty($deck)) return "";
  $cardID = $deck[$index];
  array_splice($deck, $index, DeckPieces());
  return $cardID;
}

function RemoveDiscard($player, $index)
{
  $discard = &GetDiscard($player);
  if (empty($discard)) return "";
  $cardID = $discard[$index];
  array_splice($discard, $index, DiscardPieces());
  return $cardID;
}

function GainResources($player, $amount)
{
  $resources = &GetResources($player);
  $resources[0] += $amount;
}

function AddResourceCost($player, $amount)
{
  $resources = &GetResources($player);
  $resources[1] += $amount;
}

function RemovePitch($player, $index)
{
  $pitch = &GetPitch($player);
  $cardID = $pitch[$index];
  unset($pitch[$index]);
  $pitch = array_values($pitch);
  return $cardID;
}

function AddArsenal($cardID, $player, $from, $facing, $counters = 0)
{
  global $mainPlayer, $EffectContext;
  $arsenal = &GetArsenal($player);
  $character = &GetPlayerCharacter($player);
  $cardSubType = CardSubType($cardID);
  if ($facing == "UP" && $from == "DECK" && $cardSubType == "Arrow" && FindCharacterIndex($player, "DYN151") != -1) $counters = 1;
  array_push($arsenal, $cardID);
  array_push($arsenal, $facing);
  array_push($arsenal, 1); //Num uses - currently always 1
  array_push($arsenal, $counters); //Counters
  array_push($arsenal, "0"); //Is Frozen (1 = Frozen)
  array_push($arsenal, GetUniqueId($cardID, $player)); //Unique ID
  $otherPlayer = $player == 1 ? 2 : 1;
  if ($facing == "UP") {
    if ($from == "DECK" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178") && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player))) {
      WriteLog("Gained 1 action point from " . CardLink($cardID, $cardID));
      if ($player == $mainPlayer) GainActionPoints(1);
    }
    if ($from == "DECK" && CardSubType($cardID) == "Arrow" && SearchCharacterActive($player, "OUT097")) {
      AddLayer("TRIGGER", $player, "OUT097", "-", "-", -1);
    }
    switch ($cardID) {
      case "ARC057":
      case "ARC058":
      case "ARC059":
        AddCurrentTurnEffect($cardID, $player);
        break;
      case "ARC063":
      case "ARC064":
      case "ARC065":
        Opt($cardID, 1);
        break;
      case "CRU123":
        AddCurrentTurnEffect($cardID, $otherPlayer);
        break;
      case "OUT130":
      case "OUT131":
      case "OUT132":
        SpireSnipingAbility($player);
        break;
      default:
        break;
    }
  }
}

function ArsenalEndTurn($player)
{
  $arsenal = &GetArsenal($player);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    $arsenal[$i + 2] = 1;//Num uses - currently always 1
  }
}

function SetArsenalFacing($facing, $player)
{
  $arsenal = &GetArsenal($player);
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if ($facing == "UP" && $arsenal[$i + 1] == "DOWN") {
      $arsenal[$i + 1] = "UP";
      ArsenalTurnFaceUpAbility($arsenal[$i], $player);
      return $arsenal[$i];
    }
  }
  return "";
}

function ArsenalTurnFaceUpAbility($cardID, $player)
{
  switch ($cardID) {
    case "OUT130":
    case "OUT131":
    case "OUT132":
      SpireSnipingAbility($player);
      break;
    default:
      break;
  }
}

function RemoveArsenal($player, $index)
{
  $arsenal = &GetArsenal($player);
  if (count($arsenal) == 0) return "";
  $cardID = $arsenal[$index];
  for ($i = $index + ArsenalPieces() - 1; $i >= $index; --$i) {
    unset($arsenal[$i]);
  }
  $arsenal = array_values($arsenal);
  return $cardID;
}

function DestroyArsenal($player, $index = -1, $effectController = "", $allArsenal = true)
{
  $arsenal = &GetArsenal($player);
  $cardIDs = "";
  for ($i = 0; $i < count($arsenal); $i += ArsenalPieces()) {
    if ($index > -1 && $index != $i) continue;
    if ($cardIDs != "") $cardIDs .= ",";
    $cardIDs .= $arsenal[$i];
    WriteLog(CardLink($arsenal[$i], $arsenal[$i]) . " was destroyed from the arsenal");
    AddGraveyard($arsenal[$i], $player, "ARS", $effectController);
    RemoveArsenal($player, $i);
  }
  if ($allArsenal) $arsenal = [];
  else $arsenal = array_values($arsenal);

  return $cardIDs;
}

function AddSoul($cardID, $player, $from, $isMainPhase = true)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt, $combatChain;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  AddEvent("SOUL", $cardID);
  global $CS_NumAddedToSoul, $CS_NumYellowPutSoul;
  global $myStateBuiltFor;
  if ($cardID == "DYN066") {
    WriteLog("The spirit of Eirina is inside you, always.");
    PutItemIntoPlayForPlayer($cardID, $player);
  } else {
    if ($mainPlayerGamestateStillBuilt) {
      if ($player == $mainPlayer) AddSpecificSoul($cardID, $mainSoul, $from);
      else AddSpecificSoul($cardID, $defSoul, $from);
    } else {
      if ($player == $myStateBuiltFor) AddSpecificSoul($cardID, $mySoul, $from);
      else AddSpecificSoul($cardID, $theirSoul, $from);
    }
    IncrementClassState($player, $CS_NumAddedToSoul);
    if (ColorContains($cardID, 2, $player)) IncrementClassState($player, $CS_NumYellowPutSoul);
    if ($isMainPhase && str_contains(NameOverride($cardID, $player), "Herald") && (SearchCharacterActive($player, "DTD001") || SearchCharacterActive($player, "DTD002"))) {
      if ($from != "CC") {
        $char = GetPlayerCharacter($player);
        AddLayer("TRIGGER", $player, $char[0]);
      } elseif (CardNameContains($combatChain[0], "Herald", $player, true)) {
        $char = GetPlayerCharacter($player);
        AddLayer("TRIGGER", $player, $char[0]);
      }
    }
    if ($player == $mainPlayer)
      if (SearchCharacterAlive($player, "DTD004") && !SearchCurrentTurnEffects("DTD004", $player) && CardNameContains($cardID, "Herald", $player, true)) AddCurrentTurnEffect("DTD004", $player);
  }
}

function AddSpecificSoul($cardID, &$soul, $from)
{
  array_push($soul, $cardID);
}

function BanishFromSoul($player, $index = 0)
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  global $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) BanishFromSpecificSoul($mainSoul, $player, $index);
    else BanishFromSpecificSoul($defSoul, $player, $index);
  } else {
    if ($player == $myStateBuiltFor) BanishFromSpecificSoul($mySoul, $player, $index);
    else BanishFromSpecificSoul($theirSoul, $player, $index);
  }
}

function BanishFromSpecificSoul(&$soul, $player, $index = 0)
{
  if (count($soul) == 0) return;
  $cardID = $soul[$index];
  unset($soul[$index]);
  $soul = array_values($soul);
  BanishCardForPlayer($cardID, $player, "SOUL", "SOUL");
}

function RemoveSoul($player, $index)
{
  $soul = &GetSoul($player);
  $cardID = $soul[$index];
  unset($soul[$index]);
  $soul = array_values($soul);
  return $cardID;
}

function EffectArcaneBonus($cardID)
{
  $idArr = explode("-", $cardID);
  $cardID = $idArr[0];
  $modifier = (count($idArr) > 1 ? $idArr[1] : 0);
  switch ($cardID) {
    case "ARC115":
      return 1;
    case "ARC122":
      return 1;
    case "ARC123":
    case "ARC124":
    case "ARC125":
      return 2;
    case "ARC129":
      return 3;
    case "ARC130":
      return 2;
    case "ARC131":
      return 1;
    case "ARC132":
    case "ARC133":
    case "ARC134":
      return intval($modifier);
    case "CRU161":
      return 1;
    case "CRU165":
    case "CRU166":
    case "CRU167":
      return 1;
    case "CRU171":
    case "CRU172":
    case "CRU173":
      return 1;
    case "DYN200":
      return 3;
    case "DYN201":
      return 2;
    case "DYN202":
      return 1;
    case "DYN209":
    case "DYN210":
    case "DYN211":
      return 1;
    default:
      return 0;
  }
}

function AssignArcaneBonus($playerID)
{
  global $currentTurnEffects, $layers;
  $layerIndex = 0;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    if ($currentTurnEffects[$i + 1] == $playerID && EffectArcaneBonus($currentTurnEffects[$i]) > 0) {
      $skip = intval($currentTurnEffects[$i + 2]) != -1;
      switch ($currentTurnEffects[$i]) {
        case "DYN209":
          if (CardCost($layers[$layerIndex]) > 2) $skip = true;
          break;
        case "DYN210":
          if (CardCost($layers[$layerIndex]) > 1) $skip = true;
          break;
        case "DYN211":
          if (CardCost($layers[$layerIndex]) > 0) $skip = true;
          break;
        default:
          break;
      }
      if (!$skip) {
        WriteLog("Arcane bonus from " . CardLink($currentTurnEffects[$i], $currentTurnEffects[$i]) . " associated with " . CardLink($layers[$layerIndex], $layers[$layerIndex]));
        $uniqueID = $layers[$layerIndex + 6];
        $currentTurnEffects[$i + 2] = $uniqueID;
      }
    }
  }
}

function ClearNextCardArcaneBuffs($player, $playedCard = "", $from = "")
{
  global $currentTurnEffects;
  $layerIndex = 0;
  for ($i = 0; $i < count($currentTurnEffects); $i += CurrentTurnPieces()) {
    $remove = 0;
    if ($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "DYN200":
        case "DYN201":
        case "DYN202":
          if (!IsStaticType(CardType($playedCard), $from, $playedCard)) $remove = 1;
          break;
        default:
          break;
      }
    }
    if ($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function ConsumeArcaneBonus($player)
{
  global $currentTurnEffects, $CS_ResolvingLayerUniqueID;
  $uniqueID = GetClassState($player, $CS_ResolvingLayerUniqueID);
  $totalBonus = 0;
  for ($i = count($currentTurnEffects) - CurrentTurnPieces(); $i >= 0; $i -= CurrentTurnPieces()) {
    $remove = 0;
    if ($currentTurnEffects[$i + 1] == $player && $currentTurnEffects[$i + 2] == $uniqueID) {
      $bonus = EffectArcaneBonus($currentTurnEffects[$i]);
      if ($bonus > 0) {
        $totalBonus += $bonus;
        $remove = 1;
      }
    }
    if ($remove == 1) RemoveCurrentTurnEffect($i);
  }
  return $totalBonus;
}

function ConsumeDamagePrevention($player)
{
  global $CS_NextDamagePrevented;
  $prevention = GetClassState($player, $CS_NextDamagePrevented);
  SetClassState($player, $CS_NextDamagePrevented, 0);
  return $prevention;
}

function IncrementClassState($player, $piece, $amount = 1)
{
  SetClassState($player, $piece, (GetClassState($player, $piece) + $amount));
}

function AppendClassState($player, $piece, $value)
{
  $currentState = GetClassState($player, $piece);
  if ($currentState == "-") $currentState = "";
  if ($currentState != "") $currentState .= ",";
  $currentState .= $value;
  SetClassState($player, $piece, $currentState);
}

function SetClassState($player, $piece, $value)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) $mainClassState[$piece] = $value;
    else $defClassState[$piece] = $value;
  } else {
    if ($player == $myStateBuiltFor) $myClassState[$piece] = $value;
    else $theirClassState[$piece] = $value;
  }
}

function AddCharacterEffect($player, $index, $effect)
{
  global $currentPlayer, $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCharacterEffects, $theirCharacterEffects, $mainCharacterEffects, $defCharacterEffects;
  global $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) {
      array_push($mainCharacterEffects, $index);
      array_push($mainCharacterEffects, $effect);
    } else {
      array_push($defCharacterEffects, $index);
      array_push($defCharacterEffects, $effect);
    }
  } else {
    if ($player == $myStateBuiltFor) {
      array_push($myCharacterEffects, $index);
      array_push($myCharacterEffects, $effect);
    } else {
      array_push($theirCharacterEffects, $index);
      array_push($theirCharacterEffects, $effect);
    }
  }
}

function AddGraveyard($cardID, $player, $from, $effectController = "")
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  global $myStateBuiltFor, $CS_CardsEnteredGY, $EffectContext;
  if ($from == "DECK" && ($cardID == "ARC176" || $cardID == "ARC177" || $cardID == "ARC178") && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player))) {
    WriteLog("Gained 1 action point from " . CardLink($cardID, $cardID));
    if ($player == $mainPlayer) GainActionPoints(1);
  }
  //Code for EVO400+ going to GY, then Scrapped and it makes them unplayable.
  if ($from == "CHAR") {
    $set = substr($cardID, 0, 3);
    $number = intval(substr($cardID, 3, 3));
    if ($number >= 400 && !IsAltCard($cardID)) {
      $number -= 400;
      if ($number < 0) return;
      $id = $number;
      if ($number < 100) $id = "0" . $id;
      if ($number < 10) $id = "0" . $id;
      $cardID = $set . $id;
    }
  }
  if (HasEphemeral($cardID)) return;
  switch ($cardID) {
    case "MON124":
      BanishCardForPlayer($cardID, $player, $from, "NA");
      return;
    case "CRU007":
      if ($from != "CC") AddLayer("TRIGGER", $player, $cardID);
      break;
    case "WTR164":
    case "WTR165":
    case "WTR166":
      AddBottomDeck($cardID, $player, $from);
      return;
    case "HVY207":
      if ($effectController != $player && $from != "CC") AddLayer("TRIGGER", $player, $cardID);
      break;
    default:
      break;
  }
  IncrementClassState($player, $CS_CardsEnteredGY);
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) AddSpecificGraveyard($cardID, $mainDiscard, $from);
    else AddSpecificGraveyard($cardID, $defDiscard, $from);
  } else {
    if ($player == $myStateBuiltFor) AddSpecificGraveyard($cardID, $myDiscard, $from);
    else AddSpecificGraveyard($cardID, $theirDiscard, $from);
  }
}

function RemoveGraveyard($player, $index)
{
  $discard = &GetDiscard($player);
  $cardID = "";
  if (isset($discard[$index])) {
    $cardID = $discard[$index];
    unset($discard[$index + 1]);
    unset($discard[$index]);
    $discard = array_values($discard);
  }
  return $cardID;
}

function SearchCharacterAddUses($player, $uses, $type = "", $subtype = "")
{
  $character = &GetPlayerCharacter($player);
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if ($character[$i + 1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i]))) {
      $character[$i + 1] = 2;
      $character[$i + 5] += $uses;
    }
  }
}

function SearchCharacterAddEffect($player, $effect, $type = "", $subtype = "")
{
  $character = &GetPlayerCharacter($player);
  for ($i = 0; $i < count($character); $i += CharacterPieces()) {
    if ($character[$i + 1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i]))) {
      AddCharacterEffect($player, $i, $effect);
    }
  }
}

function RemoveCharacterEffects($player, $index, $effect)
{
  $effects = &GetCharacterEffects($player);
  for ($i = count($effects) - CharacterEffectPieces(); $i >= 0; $i -= CharacterEffectPieces()) {
    if ($effects[$i] == $index && $effects[$i + 1] == $effect) {
      unset($effects[$i + 1]);
      unset($effects[$i]);
    }
  }
  $effects = array_values($effects);
  return false;
}

function AddSpecificGraveyard($cardID, &$graveyard, $from)
{
  array_push($graveyard, $cardID);
  array_push($graveyard, GetUniqueId());
}

function NegateLayer($MZIndex, $goesWhere = "GY")
{
  global $layers;
  $params = explode("-", $MZIndex);
  $index = $params[1];
  $cardID = $layers[$index];
  $player = $layers[$index + 1];
  $otherPlayer = $player == 1 ? 2 : 1;
  for ($i = $index + LayerPieces() - 1; $i >= $index; --$i) {
    unset($layers[$i]);
  }
  $layers = array_values($layers);
  switch ($goesWhere) {
    case "GY":
      AddGraveyard($cardID, $player, "LAYER", $otherPlayer);
      break;
    case "HAND":
      AddPlayerHand($cardID, $player, "LAYER");
      break;
    case "THEIRHAND":
      AddPlayerHand($cardID, $otherPlayer, "LAYER");
      break;
    default:
      break;
  }
}

function AddAdditionalCost($player, $value)
{
  global $CS_AdditionalCosts;
  AppendClassState($player, $CS_AdditionalCosts, $value);
}

function ClearAdditionalCosts($player)
{
  global $CS_AdditionalCosts;
  SetClassState($player, $CS_AdditionalCosts, "-");
}

function FaceDownArsenalBotDeck($player)
{
  if (ArsenalHasFaceDownCard($player)) {
    AddDecisionQueue("FINDINDICES", $player, "ARSENALDOWN");
    AddDecisionQueue("CHOOSEARSENAL", $player, "<-", 1);
    AddDecisionQueue("REMOVEARSENAL", $player, "-", 1);
    AddDecisionQueue("ADDBOTDECK", $player, "-", 1);
  }
}

function RemoveInventory($player, $index)
{
  $index = intval($index);
  $inventory = &GetInventory($player);
  $cardID = $inventory[$index];
  for ($j = $index + InventoryPieces() - 1; $j >= $index; --$j) {
    unset($inventory[$j]);
  }
  $inventory = array_values($inventory);
  return $cardID;
}

function IsAltCard($cardID)
{
  switch ($cardID) {
    case "MON400":
    case "MON401":
    case "MON402":
    case "MON404":
    case "MON405":
    case "MON406":
    case "MON407":
      return true;
  }
  return false;
}
