<?php

function BanishCardForPlayer($cardID, $player, $from, $mod = "-", $banishedBy = "", $banisher = "-", $created = false)
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt, $myBanish, $theirBanish, $mainBanish, $defBanish;
  global $myClassState, $theirClassState, $mainClassState, $defClassState;
  global $myStateBuiltFor, $CS_NumCrouchingTigerCreatedThisTurn, $CS_CardsBanished;
  if (CardNameContains($cardID, "Crouching Tiger", $player) && $from == "-") IncrementClassState($player, $CS_NumCrouchingTigerCreatedThisTurn);
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) return BanishCard($mainBanish, $mainClassState, $cardID, $mod, $player, $from, $banishedBy, $banisher, $created);
    else return BanishCard($defBanish, $defClassState, $cardID, $mod, $player, $from, $banishedBy, $banisher, $created);
  } else {
    if ($player == $myStateBuiltFor) return BanishCard($myBanish, $myClassState, $cardID, $mod, $player, $from, $banishedBy, $banisher, $created);
    else return BanishCard($theirBanish, $theirClassState, $cardID, $mod, $player, $from, $banishedBy, $banisher, $created);
  }
}

function BanishCard(&$banish, &$classState, $cardID, $mod, $player = "", $from = "", $banishedBy = "", $banisher = "-", $created = false)
{
  global $CS_CardsBanished, $actionPoints, $CS_Num6PowBan, $currentPlayer, $mainPlayer, $CS_NumEarthBanished, $EffectContext;
  $rv = -1;
  if ($player == "") $player = $currentPlayer;
  $otherPlayer = 3 - $player;
  $character = &GetPlayerCharacter($player);
  $characterID = ShiyanaCharacter($character[0]);
  $amount = 1;
  $isFaceDown = isFaceDownMod($mod);
  AddEvent("BANISH", ($isFaceDown ? "CardBack" : $cardID));
  //Effects that change the modifier
  if ($characterID == "blasmophet_levia_consumed" && $character[1] < 3) {
    AddLayer("TRIGGER", $player, $characterID);
    if ($mod != "INT") $mod = "blasmophet_levia_consumed";
  }
  $Auras1 = new Auras(1);
  $Auras2 = new Auras(2);
  $names = NameOverride($cardID, $player);
  $foundThemis = "";
  foreach (explode(" // ", $names) as $name) {
    $sanitizedName = GamestateSanitize($name);
    if ($foundThemis == "") $foundThemis = $Auras1->SearchAurasForModality($sanitizedName, "blessing_of_themis_yellow");
    if ($foundThemis == "") $foundThemis = $Auras2->SearchAurasForModality($sanitizedName, "blessing_of_themis_yellow");
  }
  //Do effects that change where it goes, or banish it if not
  if (str_contains($from, "DECK") && (SearchCharacterActive($player, "data_doll_mkii") || SearchCurrentTurnEffects("data_doll_mkii-SHIYANA", $player)) && SubtypeContains($cardID, "Item", $player) && CardCost($cardID, $from) <= 2) {
    $character = &GetPlayerCharacter($player);
    AddLayer("TRIGGER", $player, $character[0], $cardID);
  }
  if (HasEphemeral($cardID)) {
    if(SearchCurrentTurnEffects("spreading_mist_blue", $player, true) || SearchCurrentTurnEffects("billowing_mist_blue", $player, true)){
      ++$amount;
    }
  } 
  if (!TypeContains($cardID, "T", $player)) { //If you banish a token, the token ceases to exist.
    if ($cardID == "fangs_a_lot_blue" && $from == "DISCARD") {
      AddPlayerHand($cardID, $player, $from);
    }
    else {
      $toBanish = str_ends_with($cardID, '_equip') ? GetCardIDBeforeTransform($cardID) : $cardID;
      $rv = count($banish);
      for ($i = 0; $i < $amount; ++$i) {
        $uid = GetUniqueId($cardID, $player);
        $banish[] = $toBanish;
        $banish[] = $mod;
        $banish[] = $uid;
        if ($foundThemis != "")
          AddLayer("TRIGGER", $foundThemis->Player(), $foundThemis->CardID(), $player, "FLIP", $uid);
      }
      if ($amount > 1) $rv += ($amount - 1) * BanishPieces();
      
    }
  }
  $classState[$CS_CardsBanished] += $amount;
  if ($created) {
      $ClassState = new ClassState($player);
      $ClassState->SetCreatedCardsThisTurn($ClassState->CreatedCardsThisTurn() + $amount);
    }
  if ($isFaceDown) return $rv;
  //Do additional effects
  if ($cardID == "slithering_shadowpede_red" && $from == "HAND" && $mod != "blasmophet_levia_consumed" && ($mod != "NOFEAR" || $player == $mainPlayer)) $banish[count($banish) - 2] = "TT";
  if (($mod == "BOOST" || $from == "DECK")
  && str_starts_with($cardID, 'back_alley_breakline_')
  && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player) || GetAbilityType($EffectContext) != "")
  && $player == $mainPlayer) {
    WriteLog("Player ". $player ." gained 1 action point from " . CardLink($cardID, $cardID).".");
    ++$actionPoints;
  }
  if (($mod == "BOOST" && $from == "DECK") && str_starts_with($cardID, 'crankshaft_')) {
    WriteLog(CardLink($cardID, $cardID) . " was banished to pay a boost cost. Put a counter on a Hyper Drive you control.");
    AddLayer("TRIGGER", $player, $cardID);
  }
  if (ModifiedPowerValue($cardID, $player, $from, source: $banishedBy) >= 6) {
    if ($classState[$CS_Num6PowBan] == 0 && $player == $mainPlayer && ($characterID == "levia_shadowborn_abomination" || $characterID == "levia") && $character[1] == 2) { // Levia
      WriteLog(CardLink($characterID, $characterID) . " banished a card with 6+ power, and won't lose life from Blood Debt this turn.");
    }
    ++$classState[$CS_Num6PowBan];
    $index = FindCharacterIndex($player, "hooves_of_the_shadowbeast");
    if ($index >= 0 && IsCharacterAbilityActive($player, $index, checkGem: true) && $player == $mainPlayer && SearchLayersForCardID("hooves_of_the_shadowbeast") == -1) {
      AddLayer("TRIGGER", $player, $character[$index]);
    }
  }
  if(TalentContains($cardID, "EARTH", $player)) {
    ++$classState[$CS_NumEarthBanished];
  }
  if (TypeContains($cardID, "E", $player) && ($from == "EQUIP" || $from == "CC")) {
    $charIndex = FindCharacterIndex($player, $cardID);
    if ($charIndex == -1) {
      DestroyCharacter($player, $charIndex, skipDestroy: true);
      CharacterBanishEffect($cardID, $player);
    } else DestroyCharacter($player, $charIndex, wasBanished: true);
  }
  if ($cardID == "nitro_mechanoidc" && $from == "CC") {
    $Items = new Items($player);
    $Item = $Items->FindCard($cardID);
    $Item->Destroy(true);
  }
  $banisher = $banisher == "-" ? $mainPlayer : $banisher;
  if ($banishedBy != "") CheckContracts($banisher, $cardID);
  $rv = BanishByEffect($cardID, $player, $banishedBy, $rv);
  return $rv;
}

function BanishByEffect($cardID, $player, $banisher, &$rv) {
  global $mainPlayer, $CombatChain, $ChainLinks;

  $otherPlayer = 3 - $player;
  $banish = GetBanish($player);
  // $foundHorrors = SearchCurrentTurnEffects("horrors_of_the_past_yellow", $mainPlayer, returnUniqueID:true);
  // $extraText = $foundHorrors != -1 ? $foundHorrors : "-";
  $extraText = GetHorrorsBuff();
  $attackCard = IsResolutionStep() ? $ChainLinks->LastLink()->AttackCard()->ID() : $CombatChain->AttackCard()->ID();
  $banishEffects = [$banisher];
  if ($banisher == $attackCard) $banishEffects[] = $extraText;

  $banishPieces = BanishPieces();
  $banishCount = count($banish);
  foreach ($banishEffects as $banishedBy) {
    if ($banishedBy == "nasreth_the_soul_harrower" && TalentContains($cardID, "LIGHT", $player)) {
      GainHealth(1, $otherPlayer);
    }
    if ($banishedBy == "persuasive_prognosis_blue" && ($cardType = CardType($cardID)) && (DelimStringContains($cardType, "A") || $cardType == "AA")) {
      GainHealth(1, $otherPlayer);
    }
    if ($banishedBy == "art_of_desire_body_red" && ColorContains($cardID, 1, $player)) {
      Draw($otherPlayer);
      GainHealth(1, $otherPlayer);
    }
    if ($banishedBy == "art_of_desire_soul_yellow" && ColorContains($cardID, 2, $player)) {
      Draw($otherPlayer);
      GainHealth(1, $otherPlayer);
    }
    if ($banishedBy == "art_of_desire_mind_blue" && ColorContains($cardID, 3, $player)) {
      Draw($otherPlayer);
      GainHealth(1, $otherPlayer);
    }
    if ($banishedBy == "bonds_of_attraction_red" || $banishedBy == "bonds_of_attraction_yellow" || $banishedBy == "bonds_of_attraction_blue" && $banishCount / $banishPieces >= 2) {
      $count = $banishCount - $banishPieces;
      $sourceKey = "Source-" . $attackCard;
      $cardColor = ColorOverride($cardID, $player);
      $pitchSet = [];
      for ($i = $count - 1; $i >= 0; $i--) {
        if ($banish[$i + 1] == $sourceKey) {
          $pitchSet[ColorOverride($banish[$i], $player)] = true;
        }
      }
      if (isset($pitchSet[$cardColor])) {
        GainHealth(1, $otherPlayer);
      }
    }
    if (str_starts_with($banishedBy, 'bonds_of_memory_') && $banishCount / $banishPieces >= 2) {
      $count = $banishCount - $banishPieces;
      $seenNames = [];
      $hasDuplicate = false;
      $sourceKey = "Source-" . $banishedBy;
      for ($i = $count; $i >= 0; $i--) {
        if ($banish[$i + 1] == $sourceKey) {
          $name = CardName($banish[$i]);
          if (isset($seenNames[$name])) { $hasDuplicate = true; break; }
          $seenNames[$name] = true;
        }
      }
      if ($hasDuplicate) {
        GainHealth(1, $otherPlayer);
      }
    }
    if (str_starts_with($banishedBy, 'desires_of_flesh_') && TypeContains($cardID, "AA", $player)) {
      GainHealth(1, $otherPlayer);
    }
    if (str_starts_with($banishedBy, 'impulsive_desire_') && (TypeContains($cardID, "AR", $player) || TypeContains($cardID, "DR", $player) || TypeContains($cardID, "I", $player))) {
      GainHealth(1, $otherPlayer);
    }
    if (str_starts_with($banishedBy, 'minds_desire_') && TypeContains($cardID, "A", $player)) {
      GainHealth(1, $otherPlayer);
    }
  }
  return $rv;
}

function RemoveBanish($player, $index)
{
  $banish = &GetBanish($player);
  if($index == -1) return "";
  $cardID = $banish[$index];
  array_splice($banish, $index, BanishPieces());
  return $cardID;
}

//When it matters, make it save this off to a different zone
function TurnBanishFaceDown($player, $index)
{
  $banish = &GetBanish($player);
  $banish[$index + 1] = "DOWN";
}

function TurnDiscardFaceDown($player, $index)
{
  $DiscardCard = new DiscardCard(intval($index), $player);
  $DiscardCard->Flip();
}

function AddBottomDeck($cardID, $player, $from)
{
  if(TypeContains($cardID, "T", $player)) { // 'T' type indicates the card is a token
    WriteLog(CardLink($cardID, $cardID) . " is a token. So instead of going on the bottom of the deck, it ceases to exist.");
  }
  else {
  $deck = &GetDeck($player);
  $deck[] = $cardID;
  }
}

function AddTopDeck($cardID, $player, $from, $deckIndexModifier = 0)
{
  $Deck = new Deck($player);
  $Deck->AddTop($cardID, $from, $deckIndexModifier);
}

function AddPlayerHand($cardID, $player, $from, $amount = 1, $index=-1, $created=false)
{
  global $CS_NumCrouchingTigerCreatedThisTurn, $EffectContext;
  if (TypeContains($EffectContext, "C", $player) && (SearchAurasForCard("preach_modesty_red", 1) != "" || SearchAurasForCard("preach_modesty_red", 2) != "") && !str_contains($from, "DISCARD") && !str_contains($from, "BANISH")) {
    WriteLog("🙇 " . CardLink("preach_modesty_red", "preach_modesty_red") . " prevents the creation of " . CardLink($cardID, $cardID));
    return;
  }
  if(TypeContains($cardID, "T", $player)) { // 'T' type indicates the card is a token
    WriteLog(CardLink($cardID, $cardID) . " is a token. So instead of going to hand, it ceases to exist.");
  }
  else {
    $hand = &GetHand($player);
    if (HasEphemeral($cardID)) {
      if(SearchCurrentTurnEffects("spreading_mist_blue", $player, true) || SearchCurrentTurnEffects("billowing_mist_blue", $player, true)){
        ++$amount;
      }
    }     
    if (CardNameContains($cardID, "Crouching Tiger", $player)) IncrementClassState($player, $CS_NumCrouchingTigerCreatedThisTurn, $amount);
    if ($created) {
      $ClassState = new ClassState($player);
      $ClassState->SetCreatedCardsThisTurn($ClassState->CreatedCardsThisTurn() + $amount);
    }
    if ($index == -1) {
      if ($amount == 1) {
        $hand[] = $cardID;
      } else {
        for ($i = 0; $i < $amount; ++$i) {
          $hand[] = $cardID;
        }
      }
    }
    else {
      for ($i = 0; $i < $amount; ++$i) {
        array_splice($hand, $index, 0, $cardID);
      }
    }
    if ($from == "CC") {
      $card = GetClass($cardID, $player);
      if ($card != "-") $card->LeavesCombatChainAbility();
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
  if (!is_numeric($index) || $index < 0) return "";
  $cardID = $deck[$index] ?? "";
  array_splice($deck, (int)$index, DeckPieces());
  return $cardID;
}

//alias for remove graveyard
function RemoveDiscard($player, $index)
{
  return RemoveGraveyard($player, $index);
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
  array_splice($pitch, $index, PitchPieces());
  return $cardID;
}

function AddArsenal($cardID, $player, $from, $facing, $counters = 0)
{
  global $mainPlayer, $EffectContext;
  $arsenal = &GetArsenal($player);
  $character = &GetPlayerCharacter($player);
  $cardSubType = CardSubType($cardID);
  if ($facing == "UP" && $from == "DECK" && $cardSubType == "Arrow" && FindCharacterIndex($player, "sandscour_greatbow") != -1) $counters = 1;

  // cardID, facing, numUses=1, counters, isFrozen="0", uniqueID, numPowerCounters=0
  $arsenal[] = $cardID;
  $arsenal[] = $facing;
  $arsenal[] = 1;
  $arsenal[] = $counters;
  $arsenal[] = "0";
  $arsenal[] = GetUniqueId($cardID, $player);
  $arsenal[] = 0;

  $otherPlayer = 3 - $player;
  if ($facing == "UP") {
    if ($from == "DECK" && str_starts_with($cardID, 'back_alley_breakline_') && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player) || GetResolvedAbilityType($EffectContext, $from) == "A")) {
      if ($player == $mainPlayer) {
        WriteLog("Player ". $player ." gained 1 action point from " . CardLink($cardID, $cardID).".");
        GainActionPoints(1);
      }    }
    if ($from == "DECK" && $cardSubType == "Arrow" && SearchCharacterActive($player, "crows_nest")) {
      AddLayer("TRIGGER", $player, "crows_nest", "-", "-", -1);
    }

    $arsenalCount = count($arsenal);
    $arsenalPieces = ArsenalPieces();
    $uniqueID = $arsenal[$arsenalCount - $arsenalPieces + 5];
    
    switch ($cardID) {
      case "head_shot_red":
      case "head_shot_yellow":
      case "head_shot_blue":
        AddCurrentTurnEffect($cardID, $player, "", $uniqueID);
        break;
      case "ridge_rider_shot_red":
      case "ridge_rider_shot_yellow":
      case "ridge_rider_shot_blue":
        AddLayer("TRIGGER", $player, $cardID);
        break;
      case "remorseless_red":
        AddCurrentTurnEffect($cardID, $otherPlayer, "", $uniqueID);
        break;
      case "spire_sniping_red":
      case "spire_sniping_yellow":
      case "spire_sniping_blue":
        SpireSnipingAbility($player);
        break;
      case "dry_powder_shot_red":
        AddCurrentTurnEffect($cardID, $player, "", $uniqueID);
        break;
      case "entangling_shot_red":
        $search = (ShouldAutotargetOpponent($player)) ? "THEIRCHAR:type=C" : "THEIRCHAR:type=C&MYCHAR:type=C";
        AddDecisionQueue("MULTIZONEINDICES", $player, $search);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a character to tap", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("ADDTRIGGER", $player, $cardID, 1);
        break;
      case "nettling_shot_red":
        $search = (ShouldAutotargetOpponent($player)) ? "THEIRALLY" : "THEIRALLY&MYALLY";
        AddDecisionQueue("MULTIZONEINDICES", $player, $search);
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose an ally to tap", 1);
        AddDecisionQueue("MAYCHOOSEMULTIZONE", $player, "<-", 1);
        AddDecisionQueue("ADDTRIGGER", $player, $cardID, 1);
        break;
      case "scouting_shot_red":
        LookAtTopCard($player, $cardID, setPlayer:$player);
        break;
      case "swift_shot_red":
        AddCurrentTurnEffect($cardID, $player, "", $uniqueID);
        break;
      default:
        break;
    }
  }
}

function ArsenalEndTurn($player)
{
  $arsenal = &GetArsenal($player);
  $arsenalCount = count($arsenal);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    $arsenal[$i + 2] = 1;
  }
}

function SetArsenalFacing($facing, $player)
{
  $arsenal = &GetArsenal($player);
  $arsenalCount = count($arsenal);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0; $i < $arsenalCount; $i += $arsenalPieces) {
    if ($facing == "UP" && $arsenal[$i + 1] != "UP") {
      $arsenal[$i + 1] = "UP";
      $cardID = $arsenal[$i];
      AddEvent("TURNARSENALFACEUP", $player . ":" . $cardID);
      ArsenalTurnFaceUpAbility($cardID, $player);
      return $cardID;
    }
  }
  return "";
}

function ArsenalTurnFaceUpAbility($cardID, $player)
{
  switch ($cardID) {
    case "spire_sniping_red":
    case "spire_sniping_yellow":
    case "spire_sniping_blue":
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
  array_splice($arsenal, $index, ArsenalPieces());
  return $cardID;
}

function DestroyArsenal($player, $index = -1, $effectController = "", $allArsenal = true)
{
  $arsenal = &GetArsenal($player);
  $cardIDs = "";
  $arsenalPieces = ArsenalPieces();
  for ($i = count($arsenal) - $arsenalPieces; $i >= 0; $i -= $arsenalPieces) {
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
  global $mainPlayer, $mainPlayerGamestateStillBuilt, $combatChain;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  AddEvent("SOUL", $cardID);
  global $CS_NumAddedToSoul, $CS_NumYellowPutSoul;
  global $myStateBuiltFor;
  if ($cardID == "spirit_of_eirina_yellow") {
    WriteLog("📿The spirit of Eirina is inside you, always.");
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
    if ($isMainPhase && str_contains(NameOverride($cardID, $player), "Herald") && (SearchCharacterActive($player, "prism_awakener_of_sol") || SearchCharacterActive($player, "prism_advent_of_thrones"))) {
      if ($from != "CC") {
        $char = GetPlayerCharacter($player);
        if ($char[9] != 0) AddLayer("TRIGGER", $player, $char[0]);
      } elseif (CardNameContains($combatChain[0], "Herald", $player, true)) {
        $char = GetPlayerCharacter($player);
        if ($char[9] != 0) AddLayer("TRIGGER", $player, $char[0]);
      }
    }
    if ($player == $mainPlayer)
      if (SearchCharacterAlive($player, "empyrean_rapture") && !SearchCurrentTurnEffects("empyrean_rapture", $player) && CardNameContains($cardID, "Herald", $player, true)) AddCurrentTurnEffect("empyrean_rapture", $player);
    $Auras = new Auras($player);
    for ($i = 0; $i < $Auras->NumAuras(); ++$i) {
      $card = GetClass($Auras->Card($i, true)->CardID(), $player);
      if ($card != "-") $card->PermanentAddSoulAbility();
    }
  }
  
}

function AddSpecificSoul($cardID, &$soul, $from)
{
  $soul[] = $cardID;
}

function BanishFromSoul($player, $index = 0)
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $mySoul, $theirSoul, $mainSoul, $defSoul;
  global $myStateBuiltFor;
  global $combatChainState; 
  global $CCS_SoulBanishedThisChain;
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) BanishFromSpecificSoul($mainSoul, $player, $index);
    else BanishFromSpecificSoul($defSoul, $player, $index);
  } else {
    if ($player == $myStateBuiltFor) BanishFromSpecificSoul($mySoul, $player, $index);
    else BanishFromSpecificSoul($theirSoul, $player, $index);
  }
  if ($player == $mainPlayer) {
    ++$combatChainState[$CCS_SoulBanishedThisChain];
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
  if ($index != -1) {
    $soul = &GetSoul($player);
    $cardID = $soul[$index];
    unset($soul[$index]);
    $soul = array_values($soul);
    return $cardID;
  }
  else {
    WriteLog("Something went wrong when trying to remove a card from soul, please submit a bug report.", highlight: true);
    return "";
  }
}

function EffectArcaneBonus($source)
{
  $idArr = explode(",", $source);
  $source = $idArr[0];
  $modifier = (count($idArr) > 1 ? $idArr[1] : 0);
  switch ($source) {
    case "crucible_of_aetherweave":
      return 1;
    case "tome_of_aetherwind_red":
      return 1;
    case "absorb_in_aether_red":
    case "absorb_in_aether_yellow":
    case "absorb_in_aether_blue":
      return 2;
    case "stir_the_aetherwinds_red":
      return 3;
    case "stir_the_aetherwinds_yellow":
      return 2;
    case "stir_the_aetherwinds_blue":
      return 1;
    case "aether_flare_red":
    case "aether_flare_yellow":
    case "aether_flare_blue":
      return intval($modifier);
    case "metacarpus_node":
      return 1;
    case "cindering_foresight_red":
    case "cindering_foresight_yellow":
    case "cindering_foresight_blue":
      return 1;
    case "rousing_aether_red":
    case "rousing_aether_yellow":
    case "rousing_aether_blue":
      return 1;
    case "surgent_aethertide":
      return intval($modifier);
    case "blessing_of_aether_red":
      return 3;
    case "blessing_of_aether_yellow":
      return 2;
    case "blessing_of_aether_blue":
      return 1;
    case "tempest_aurora_red":
    case "tempest_aurora_yellow":
    case "tempest_aurora_blue":
      return 1;
    default:
      return 0;
  }
}

function AssignEffectToCard($cardID, $player, $from) {
  global $CurrentTurnEffects;
  for ($i = 0; $i < $CurrentTurnEffects->NumEffects(); ++$i) {
    $Effect = $CurrentTurnEffects->Effect($i, true);
    if ($Effect->PlayerID() != $player) continue;
    if ($Effect->AppliestoUniqueID() != -1) continue;
    $card = GetClass($Effect->EffectID(), $player);
    if ($card != "-") $card->AssignEffectToCard($cardID, $Effect->Index(), $from);
  }
}

function AssignArcaneBonus($playerID)
{
  global $currentTurnEffects, $layers;
  $layerIndex = 0;
  //not a damage bonus, but needs to be associated with the first card played
  $ind = SearchCurrentTurnEffectsForIndex("conduit_of_frostburn", $playerID);
  if ($ind != -1) $currentTurnEffects[$ind + 2] = $layers[$layerIndex + 6];
  $currentTurnEffectsCount = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = 0; $i < $currentTurnEffectsCount; $i += $currentTurnEffectsPieces) {
    if ($currentTurnEffects[$i + 1] == $playerID && EffectArcaneBonus($currentTurnEffects[$i]) > 0) {
      $skip = intval($currentTurnEffects[$i + 2]) != -1;
      switch ($currentTurnEffects[$i]) {
        case "tempest_aurora_red":
          if (CardCost($layers[$layerIndex]) > 2) $skip = true;
          break;
        case "tempest_aurora_yellow":
          if (CardCost($layers[$layerIndex]) > 1) $skip = true;
          break;
        case "tempest_aurora_blue":
          if (CardCost($layers[$layerIndex]) > 0) $skip = true;
          break;
        default:
          break;
      }
      if (!$skip) {
        while ($layers[$layerIndex] == "TRIGGER" || $layers[$layerIndex] == "PRETRIGGER") {
          $layerIndex += LayerPieces();
        }
        $effectID = ExtractCardID($currentTurnEffects[$i]);
        WriteLog("Arcane bonus from " . CardLink($effectID, $effectID) . " associated with " . CardLink($layers[$layerIndex], $layers[$layerIndex]));
        $uniqueID = $layers[$layerIndex + 6];
        $currentTurnEffects[$i + 2] = $uniqueID;
      }
    }
  }
}

function ClearNextCardArcaneBuffs($player, $playedCard = "", $from = "")
{
  global $currentTurnEffects;
  $currentTurnEffectsCount = count($currentTurnEffects);
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  for ($i = $currentTurnEffectsCount - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    $remove = 0;
    if ($currentTurnEffects[$i + 1] == $player) {
      switch ($currentTurnEffects[$i]) {
        case "blessing_of_aether_red":
        case "blessing_of_aether_yellow":
        case "blessing_of_aether_blue":
          if ($currentTurnEffects[$i + 2] == -1) {
            if (!IsStaticType(CardType($playedCard), $from, $playedCard) && GetResolvedAbilityType($playedCard, $from) != "I") $remove = 1;
          }
          break;
        default:
          break;
      }
    }
    if ($remove == 1) RemoveCurrentTurnEffect($i);
  }
}

function ConsumeArcaneBonus($player, $noRemove = false)
{
  global $currentTurnEffects, $CS_ResolvingLayerUniqueID;
  $uniqueID = GetClassState($player, $CS_ResolvingLayerUniqueID);
  $totalBonus = 0;
  $activeArcaneCompliance = -1;
  $currentTurnEffectsPieces = CurrentTurnEffectsPieces();
  $currentTurnEffectsCount = count($currentTurnEffects);

  for ($i = $currentTurnEffectsCount - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    if ($currentTurnEffects[$i] == "arcane_compliance_blue" && $currentTurnEffects[$i+2] == $uniqueID) $activeArcaneCompliance = $i;
  }

  for ($i = $currentTurnEffectsCount - $currentTurnEffectsPieces; $i >= 0; $i -= $currentTurnEffectsPieces) {
    $remove = 0;
    if ($currentTurnEffects[$i + 1] == $player && ($currentTurnEffects[$i + 2] == $uniqueID || DelimStringContains($uniqueID, "MELD", true))) {
      $bonus = EffectArcaneBonus($currentTurnEffects[$i]);
      if ($bonus > 0) {
        if ($activeArcaneCompliance == -1) $totalBonus += $bonus;
        if (!$noRemove) $remove = 1;
      }
    }
    if (!$noRemove && $currentTurnEffects[$i] == "arcane_compliance_blue" && $currentTurnEffects[$i+2] == $uniqueID) $remove = 1;
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
  SetClassState($player, $piece, (intval(GetClassState($player, $piece)) + intval($amount)));
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
  global $mainPlayer, $mainPlayerGamestateStillBuilt;
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
  global $mainPlayer, $mainPlayerGamestateStillBuilt;
  global $myCharacterEffects, $theirCharacterEffects, $mainCharacterEffects, $defCharacterEffects;
  global $myStateBuiltFor;
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) {
      $mainCharacterEffects[] = $index;
      $mainCharacterEffects[] = $effect;
    } else {
      $defCharacterEffects[] = $index;
      $defCharacterEffects[] = $effect;
    }
  } else {
    if ($player == $myStateBuiltFor) {
      $myCharacterEffects[] = $index;
      $myCharacterEffects[] = $effect;
    } else {
      $theirCharacterEffects[] = $index;
      $theirCharacterEffects[] = $effect;
    }
  }
}

function AddGraveyard($cardID, $player, $from, $effectController = "")
{
  global $mainPlayer, $mainPlayerGamestateStillBuilt, $CS_NumAllyPutInGraveyard;
  global $myDiscard, $theirDiscard, $mainDiscard, $defDiscard;
  global $myStateBuiltFor, $CS_CardsEnteredGY, $EffectContext, $CS_NumInstantsPutInGrave;
  if (str_contains($from, "DECK") && str_starts_with($cardID, 'back_alley_breakline_') && (TypeContains($EffectContext, "A", $player) || TypeContains($EffectContext, "AA", $player) || TypeContains($EffectContext, "E", $player))) {
    if ($player == $mainPlayer) {
      WriteLog("Player ". $player ." gained 1 action point from " . CardLink($cardID, $cardID).".");
      GainActionPoints(1);
    }
  }
  if (SubtypeContains($cardID, "Ally", $player)) {
    IncrementClassState($player, $CS_NumAllyPutInGraveyard);
  }
  $char = GetPlayerCharacter($player);
  $hero = $char[0];
  if (!SearchCurrentTurnEffects($hero, $player) && ColorContains($cardID, 3, $player) && ($hero == "gravy_bones_shipwrecked_looter" || $hero == "gravy_bones")) {
    AddCurrentTurnEffect($hero, $player);
  }
  if (TypeContains($cardID, "I", $player, from:"DISCARD"))
    IncrementClassState($player, $CS_NumInstantsPutInGrave);
  // Code for equipped evos+ going to GY, then Scrapped and it makes them unplayable.
  // this may not be required anymore
  if ($from == "CHAR" && str_ends_with($cardID, '_equip')) {
    $cardID = GetCardIDBeforeTransform($cardID);
  }
  if (HasEphemeral($cardID) || TypeContains($cardID, "T", $player) || $cardID == "goldfin_harpoon_yellow") return;
  $card = GetClass($cardID, $player);
  $ret = false;
  if ($card != "-") $ret = $card->AddGraveyardEffect($from, $effectController);
  if ($ret) return;
  switch ($cardID) {
    case "mark_of_the_beast_yellow":
      BanishCardForPlayer($cardID, $player, $from, "NA");
      return;
    case "beast_within_yellow":
      if ($from != "CC" && $from != "COMBATCHAINLINK") AddLayer("TRIGGER", $player, $cardID);
      break;
    case "drone_of_brutality_red":
    case "drone_of_brutality_yellow":
    case "drone_of_brutality_blue":
      AddBottomDeck($cardID, $player, $from);
      return;
    case "nasty_surprise_blue":
      if ($effectController != $player && $from != "CC") AddLayer("TRIGGER", $player, $cardID);
      break;
    case "fiddlers_green_red": case "fiddlers_green_yellow": case "fiddlers_green_blue":
    case "sirens_of_safe_harbor_red": case "sirens_of_safe_harbor_yellow": case "sirens_of_safe_harbor_blue":
      AddLayer("TRIGGER", $player, $cardID);
      break;
    case "sea_legs_yellow": case "fools_gold_yellow":
      if(str_contains($from, "HAND")) {
        AddLayer("TRIGGER", $player, $cardID);
      }
    default:
      break;
  }
  // $mods = (HasWateryGrave($cardID) && $from == "PLAY") ? "DOWN" : "-";
  $mods = "-";
  IncrementClassState($player, $CS_CardsEnteredGY);
  if ($mainPlayerGamestateStillBuilt) {
    if ($player == $mainPlayer) AddSpecificGraveyard($cardID, $mainDiscard, $from, $mods);
    else AddSpecificGraveyard($cardID, $defDiscard, $from, $mods);
  } else {
    if ($player == $myStateBuiltFor) AddSpecificGraveyard($cardID, $myDiscard, $from, $mods);
    else AddSpecificGraveyard($cardID, $theirDiscard, $from, $mods);
  }
  $grave = GetDiscard($player);
  $graveLastIndex = count($grave) - DiscardPieces() + 1;
  if ((HasWateryGrave($cardID) && $from == "PLAY") || ($cardID == "beneath_the_surface_yellow" && $from == "CC")) {
    AddLayer("TRIGGER", $player, "WATERYGRAVE", target:$grave[$graveLastIndex]);
  }
  return $grave[$graveLastIndex];
}

function RemoveGraveyard($player, $index, $resetGraveyard=true)
{
  $discard = &GetDiscard($player);
  $cardID = "";
  if (isset($discard[$index])) {
    $cardID = $discard[$index];
    $count = CountAura("sigil_of_gravespawning_blue", $player);
    if($count > 0) {
      for($i = 0; $i < $count; ++$i) {
        SetArcaneTarget($player, "sigil_of_gravespawning_blue", 0);
        AddDecisionQueue("SHOWSELECTEDTARGET", $player, "<-", 1);
        AddDecisionQueue("ADDTRIGGER", $player, "sigil_of_gravespawning_blue", 1);
      }
    }
    if ($resetGraveyard) {
      array_splice($discard, $index, DiscardPieces());
    } else {
      $discardPieces = DiscardPieces();
      for ($i = $discardPieces - 1; $i >= 0; --$i) {
        unset($discard[$index + $i]);
      }
    }
  }
  return $cardID;
}

function SearchCharacterAddUses($player, $uses, $type = "", $subtype = "")
{
  $character = &GetPlayerCharacter($player);
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if ($character[$i + 1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i]))) {
      $character[$i + 1] = 2;
      $character[$i + 5] += $uses;
    }
  }
}

function SearchCharacterAddEffect($player, $effect, $type = "", $subtype = "")
{
  $character = &GetPlayerCharacter($player);
  $countCharacter = count($character);
  $characterPieces = CharacterPieces();
  for ($i = 0; $i < $countCharacter; $i += $characterPieces) {
    if ($character[$i + 1] != 0 && ($type == "" || CardType($character[$i]) == $type) && ($subtype == "" || $subtype == CardSubtype($character[$i]))) {
      AddCharacterEffect($player, $i, $effect);
    }
  }
}

function RemoveCharacterEffects($player, $index, $effect)
{
  $effects = &GetCharacterEffects($player);
  $characterEffectPieces = CharacterEffectPieces();
  for ($i = count($effects) - $characterEffectPieces; $i >= 0; $i -= $characterEffectPieces) {
    if ($effects[$i] == $index && $effects[$i + 1] == $effect) {
      array_splice($effects, $i, $characterEffectPieces);
    }
  }
  return false;
}

function AddSpecificGraveyard($cardID, &$graveyard, $from, $mods="-")
{
  $graveyard[] = $cardID;
  $graveyard[] = GetUniqueId();
  $graveyard[] = $mods;
}

function NegateLayer($MZIndex, $goesWhere = "GY")
{
  global $layers;
  $params = explode("-", $MZIndex, 2);
  $index = $params[1];
  if (!is_numeric($index)) return;
  $cardID = $layers[$index];
  $player = $layers[$index + 1];
  $otherPlayer = 3 - $player;
  array_splice($layers, $index, LayerPieces());
  if ($goesWhere != "-") {
    ResolveGoesWhere($goesWhere, $cardID, $player, "LAYER", $otherPlayer);
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
  array_splice($inventory, $index, InventoryPieces());
  return $cardID;
}

function IsAltCard($cardID)
{
  switch ($cardID) {
    case "the_librarian":
    case "minerva_themis":
    case "lady_barthimont":
    case "lord_sutcliffe":
    case "the_hand_that_pulls_the_strings":
      return true;
  }
  return false;
}

function MaySearchDeck($player, $search, $dest, $isReveal=1, $mod="-", $context="") {
  if ($isReveal & !CanRevealCards($player)) return false;
  if ($context != "") AddDecisionQueue("SETDQCONTEXT", $player, $context);
  AddDecisionQueue("BUTTONINPUT", $player, "Search,No_search");
  AddDecisionQueue("MAYSEARCHDECK", $player, "$search,$dest,$isReveal,$mod", 1);
  return true;
}