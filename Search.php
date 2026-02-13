<?php

function SearchDeck($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $hasCrush = false)
{
  $otherPlayer = $player == 1 ? 2 : 1;
  if (SearchAurasForCard("channel_the_bleak_expanse_blue", $otherPlayer) != "" || SearchAurasForCard("channel_the_bleak_expanse_blue", $player) != "") {
    WriteLog("Deck search prevented by " . CardLink("channel_the_bleak_expanse_blue", "channel_the_bleak_expanse_blue"));
    return "";
  }
  $deck = &GetDeck($player);
  return SearchInner($deck, $player, "DECK", DeckPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, $hasCrush);
}

function SearchHand($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $arcaneDamage = -1, $hasWateryGrave = false, $hasCrush = false, $realPitch="-")
{
  $hand = &GetHand($player);
  return SearchInner($hand, $player, "HAND", HandPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, arcaneDamage: $arcaneDamage, hasWateryGrave: $hasWateryGrave, hasCrush: $hasCrush, realPitch:$realPitch);
}

function SearchCharacter($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $nameIncludes = "", $is1h = false, $faceUp = false, $faceDown = false, $nullDef = false, $hasCloaked = false)
{
  $character = &GetPlayerCharacter($player);
  return SearchInner($character, $player, "CHAR", CharacterPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, $nameIncludes, is1h: $is1h, faceUp: $faceUp, faceDown: $faceDown, nullDef: $nullDef, hasCloaked: $hasCloaked);
}

function SearchPitch($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $searchPitch = &GetPitch($player);
  return SearchInner($searchPitch, $player, "PITCH", PitchPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchDiscard($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $nameIncludes = "", $getDistinctCardNames = false, $hasStealth = false, $hasSuspense = false)
{
  $discard = &GetDiscard($player);
  return SearchInner($discard, $player, "DISCARD", DiscardPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, $nameIncludes, $getDistinctCardNames, hasStealth: $hasStealth, hasSuspense: $hasSuspense);
}

function SearchBanish($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $isIntimidated = false,)
{
  $banish = &GetBanish($player);
  return SearchInner($banish, $player, "BANISH", BanishPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, isIntimidated: $isIntimidated);
}

function SearchCombatChainLink($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  global $combatChain;
  return SearchInner($combatChain, $player, "CC", CombatChainPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchActiveAttack($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  global $combatChain;
  $combatChainPieces = CombatChainPieces();
  $activeAttack = array_slice($combatChain, 0, $combatChainPieces);
  return SearchInner($activeAttack, $player, "CC", $combatChainPieces, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchCombatChainAttacks($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $nameIncludes = "", $is1h = false)
{
  global $chainLinks;
  $attacks = GetCombatChainAttacks();
  return SearchInner($attacks, $player, "CC", ChainLinksPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, nameIncludes:$nameIncludes, is1h:$is1h);
}

function SearchArsenal($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false, $faceUp = false, $faceDown = false)
{
  $arsenal = &GetArsenal($player);
  return SearchInner($arsenal, $player, "ARS", ArsenalPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, faceUp: $faceUp, faceDown: $faceDown);
}

function SearchAura($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasWard = false, $hasPowerCounters = false, $nameIncludes = "", $hasSuspense = false)
{
  $auras = &GetAuras($player);
  return SearchInner($auras, $player, "AURAS", AuraPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, hasWard: $hasWard, hasPowerCounters: $hasPowerCounters, nameIncludes: $nameIncludes, hasSuspense:$hasSuspense);
}

function SearchItems($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $items = &GetItems($player);
  return SearchInner($items, $player, "ITEMS", ItemPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchAllies($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $allies = &GetAllies($player);
  return SearchInner($allies, $player, "ALLY", AllyPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchPermanents($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $permanents = &GetPermanents($player);
  return SearchInner($permanents, $player, "PERM", PermanentPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchLayer($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  global $layers;
  return SearchInner($layers, $player, "LAYER", LayerPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchLandmarks($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  global $landmarks;
  if (!isset($landmarks[0]) || !SubtypeContains($landmarks[0], "Landmark", $player)) return "";
  return SearchInner($landmarks, $player, "LANDMARK", LandmarkPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchSoul($player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $soul = &GetSoul($player);
  return SearchInner($soul, $player, "SOUL", SoulPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}

function SearchCardList($list, $player, $type = "", $subtype = "", $maxCost = -1, $minCost = -1, $class = "", $talent = "", $bloodDebtOnly = false, $phantasmOnly = false, $pitch = -1, $specOnly = false, $maxAttack = -1, $maxDef = -1, $frozenOnly = false, $hasNegCounters = false, $hasEnergyCounters = false, $comboOnly = false, $minAttack = false, $hasCrank = false, $hasSteamCounter = false)
{
  $listArr = explode(",", $list);
  return SearchInner($listArr, $player, "LIST", 1, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
}


function SearchInner(
  &$array,
  $player,
  $zone,
  $count,
  $type,
  $subtype,
  $maxCost,
  $minCost,
  $class,
  $talents,
  $bloodDebtOnly,
  $phantasmOnly,
  $pitch,
  $specOnly,
  $maxAttack,
  $maxDef,
  $frozenOnly,
  $hasNegCounters,
  $hasEnergyCounters,
  $comboOnly,
  $minAttack,
  $hasCrank = false,
  $hasSteamCounter = false,
  $nameIncludes = "",
  $getDistinctCardNames = false,
  $is1h = false,
  $hasWard = false,
  $hasPowerCounters = false,
  $faceUp = false,
  $faceDown = false,
  $isIntimidated = false,
  $arcaneDamage = -1,
  $hasStealth = false,
  $hasWateryGrave = false,
  $nullDef = false,
  $hasCrush = false,
  $realPitch = "-",
  $hasSuspense = false,
  $hasCloaked = false,
)
{
  $cardList = "";
  if (!is_array($talents)) $talents = $talents == "" ? [] : explode(",", $talents);
  $arrayCount = count($array);
  for ($i = 0; $i < $arrayCount; $i += $count) {
    if ($zone == "CHAR" && (isset($array[$i + 1]) && $array[$i + 1] == 0 || isset($array[$i + 12]) && $array[$i + 12] == "DOWN") && !$faceDown) continue;
    if ($zone == "BANISH" && isFaceDownMod($array[$i + 1]) && !$isIntimidated) continue;
    if ($zone == "DISCARD" && isFaceDownMod($array[$i + 2])) continue;
    $cardID = $array[$i];
    if (!isPriorityStep($cardID) && !isAdministrativeStep($cardID)) {
      // Check cheap conditions first: type, subtype, cost, class, talent, pitch, attack, defense, arcane damage
      if (($type == "" || DelimStringContains(CardType($cardID, $zone), $type) || $type == "C" && CardType($cardID) == "D" || $type == "W" && SubtypeContains($cardID, "Aura") && !IsWeapon($cardID, $zone))
        && ($subtype == "" || SubtypeContains($cardID, $subtype, $player))
        && ($maxCost == -1 || CardCost($cardID, $zone) <= $maxCost)
        && ($minCost == -1 || CardCost($cardID, $zone) >= $minCost)
        && ($class == "" || ClassContains($cardID, $class, $player))
        && (count($talents) == 0 || TalentContainsAny($cardID, implode(",", $talents), $player, $zone))
        && ($pitch == -1 || ColorContains($cardID, $pitch, $player))
        && ($realPitch == "-" || PitchContains($cardID, $realPitch))
        && ($maxAttack == -1 || ModifiedPowerValue($cardID, $player, $zone) <= $maxAttack)
        && ($minAttack == -1 || ModifiedPowerValue($cardID, $player, $zone) >= $minAttack)
        && ($maxDef == -1 || BlockValue($cardID) <= $maxDef)
        && ($arcaneDamage == -1 || ArcaneDamage($cardID) == $arcaneDamage)
      ) {
        // Check cheaper boolean flags first before any function calls
        if ($bloodDebtOnly && !HasBloodDebt($cardID)) continue;
        if ($phantasmOnly && !HasPhantasm($cardID, $zone)) continue;
        if ($specOnly && !HasSpecialization($cardID)) continue;
        if ($getDistinctCardNames && str_contains($cardList, GamestateSanitize(CardName($cardID)))) continue;
        if ($nullDef && BlockValue($cardID) >= 0) continue;
        if ($is1h && !Is1H($cardID)) continue;
        if ($hasStealth && !hasStealth($cardID)) continue;
        if ($hasWateryGrave && !HasWateryGrave($cardID)) continue;
        if ($hasCrush && !HasCrush($cardID)) continue;
        if ($hasSuspense && !HasSuspense($cardID)) continue;
        if ($comboOnly && !HasCombo($cardID)) continue;
        if ($hasCloaked && HasCloaked($cardID) != "DOWN") continue;
        
        // Check array-based conditions
        if ($frozenOnly && !IsFrozenMZ($array, $zone, $i, $player)) continue;
        if ($hasNegCounters && $array[$i + 4] == 0) continue;
        if ($hasEnergyCounters && !HasEnergyCounters($array, $i)) continue;
        if ($hasCrank && !HasCrank($cardID, $player)) continue;
        if ($hasSteamCounter && !HasSteamCounter($array, $i, $player)) continue;
        if ($hasWard && !HasWard($cardID, $player)) continue;
        if ($hasPowerCounters && !HasPowerCounters($zone, $array, $i)) continue;
        
        // Check zone-specific facing conditions
        if ($zone == "ARS") {
          if ($faceUp && $array[$i + 1] != "UP") continue;
          if ($faceDown && $array[$i + 1] != "DOWN") continue;
        }
        if ($zone == "CHAR") {
          if ($faceUp && $array[$i + 12] != "UP") continue;
          if ($faceDown && $array[$i + 12] != "DOWN") continue;
        }
        if ($isIntimidated) {
          if ($array[$i + 1] != "INT") continue;
        }
        
        // Check string-based conditions last (most expensive)
        if ($nameIncludes != "" && !CardNameContains($cardID, $nameIncludes, $player, partial: true)) continue;
        
        if ($cardList != "") $cardList = $cardList . ",";
        
        $cardList = $cardList . ($getDistinctCardNames ? GamestateSanitize(CardName($cardID)) : $i);
      }
    }
  }
  return $cardList;
}

function isPriorityStep($cardID)
{
  switch ($cardID) {
    case "ENDTURN":
    case "RESUMETURN":
    case "FINALIZECHAINLINK":
    case "DEFENDSTEP":
    case "ENDPHASE":
    case "ATTACKSTEP":
    case "RESOLUTIONSTEP":
    case "CLOSINGCHAIN":
    case "STARTTURN":
      return true;
    default:
      return false;
  }
}

function isAdministrativeStep($cardID)
{
  switch ($cardID) {
    case "PHANTASM":
    case "MIRAGE":
    case "BLOODDEBT":
    case "SPECTRA":
      return true;
    default:
      return false;
  }
}

function SearchHandForCard($player, $card)
{
  $hand = &GetHand($player);
  $count = count($hand);
  $pieces = HandPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($hand[$i] == $card) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchHandForCardName($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $hand = &GetHand($player);
  $countHand = count($hand);
  $handPieces = HandPieces();
  $indices = [];
  for ($i = 0; $i < $countHand; $i += $handPieces) {
    if (ShareName(CardName($hand[$i]), $name)) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchArsenalForCardName($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $arsenal = &GetArsenal($player);
  $countArsenal = count($arsenal);
  $arsenalPieces = ArsenalPieces();
  $indices = [];
  for ($i = 0; $i < $countArsenal; $i += $arsenalPieces) {
    if (ShareName(CardName($arsenal[$i]), $name)) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchArsenalForCard($player, $card, $facing = "-")
{
  $arsenal = &GetArsenal($player);
  $count = count($arsenal);
  $pieces = ArsenalPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($arsenal[$i] == $card && ($arsenal[$i + 1] == $facing || $facing == "-")) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchDeckForCard($player, $card1, $card2 = "", $card3 = "")
{
  $otherPlayer = $player == 1 ? 2 : 1;
  if (SearchAurasForCard("channel_the_bleak_expanse_blue", $otherPlayer) != "" || SearchAurasForCard("channel_the_bleak_expanse_blue", $player) != "") {
    WriteLog("Deck search prevented by " . CardLink("channel_the_bleak_expanse_blue", "channel_the_bleak_expanse_blue"));
    return "";
  }
  $deck = &GetDeck($player);
  $count = count($deck);
  $pieces = DeckPieces();
  $cardList = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    $id = $deck[$i];
    if (($id == $card1 || $id == $card2 || $id == $card3) && $id != "") {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchDeckByName($player, $name)
{
  $otherPlayer = $player == 1 ? 2 : 1;
  if (SearchAurasForCard("channel_the_bleak_expanse_blue", $otherPlayer) != "" || SearchAurasForCard("channel_the_bleak_expanse_blue", $player) != "") {
    WriteLog("Deck search prevented by " . CardLink("channel_the_bleak_expanse_blue", "channel_the_bleak_expanse_blue"));
    return "";
  }
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $deck = &GetDeck($player);
  $countDeck = count($deck);
  $deckPieces = DeckPieces();
  $cardList = [];
  for ($i = 0; $i < $countDeck; $i += $deckPieces) {
    if (ShareName(CardName($deck[$i]), $name)) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchDiscardByName($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $discard = &GetDiscard($player);
  $countDiscard = count($discard);
  $discardPieces = DiscardPieces();
  $cardList = [];
  for ($i = 0; $i < $countDiscard; $i += $discardPieces) {
    if (ShareName(CardName($discard[$i]), $name) && !isFaceDownMod($discard[$i+2])) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchDiscardNameContains($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $discard = &GetDiscard($player);
  $countDiscard = count($discard);
  $discardPieces = DiscardPieces();
  $cardList = [];
  for ($i = 0; $i < $countDiscard; $i += $discardPieces) {
    if (DelimStringContains($discard[$i], $name, true) && !isFaceDownMod($discard[$i+2])) {
      $cardList[] = $i;
    }
  }
  return $cardList;
}

function SearchItemsByName($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $items = &GetItems($player);
  $countItems = count($items);
  $itemPieces = ItemPieces();
  $cardList = [];
  for ($i = 0; $i < $countItems; $i += $itemPieces) {
    if (CardName($items[$i]) == $name) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchBanishByName($player, $name)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $banish = &GetBanish($player);
  $countBanish = count($banish);
  $banishPieces = BanishPieces();
  $cardList = [];
  for ($i = 0; $i < $countBanish; $i += $banishPieces) {
    if (CardName($banish[$i]) == $name && !isFaceDownMod($banish[$i+1])) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchDiscardForCard($player, $card1, $card2 = "", $card3 = "")
{
  $discard = &GetDiscard($player);
  $count = count($discard);
  $pieces = DiscardPieces();
  $cardList = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    $id = $discard[$i];
    if (($id == $card1 || $id == $card2 || $id == $card3) && $id != "" && !isFaceDownMod($discard[$i+2])) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchAlliesActive($player, $card1, $card2 = "", $card3 = "")
{
  $allies = &GetAllies($player);
  $countAllies = count($allies);
  $allyPieces = AllyPieces();
  for ($i = 0; $i < $countAllies; $i += $allyPieces) {
    $id = $allies[$i];
    if (($id == $card1 || $id == $card2 || $id == $card3) && $id != "") {
      return true;
    }
  }
  return false;
}

function SearchPermanentsForCard($player, $card)
{
  $permanents = &GetPermanents($player);
  $count = count($permanents);
  $pieces = PermanentPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($permanents[$i] == $card) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchCharacterAlive($player, $cardID)
{
  $index = FindCharacterIndex($player, $cardID);
  if ($index == -1) return false;
  $char = &GetPlayerCharacter($player);
  return $char[$index + 1] > 0;
}

function SearchCharacterActive($player, $cardID, $checkGem = false, $setInactive = false)
{
  $index = FindCharacterIndex($player, $cardID);
  $char = &GetPlayerCharacter($player);
  if ($index == -1) return false;
  $isActive = IsCharacterAbilityActive($player, $index, $checkGem);
  if ($isActive && $setInactive) {
    $char[$index + 1] = 1;
  }
  return $isActive;
}

function SearchCharacterForCard($player, $cardID)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (isset($character[$i]) && $character[$i] == $cardID && $character[$i + 12] != "DOWN" && $character[$i + 1] != 0) return true;
  }
  return false;
}

function SearchCharacterByName($player, $cardID) {
  $name = CardName($cardID);
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (isset($character[$i]) && NameOverride($character[$i], $player) == $name && $character[$i + 12] != "DOWN" && $character[$i + 1] != 0) return true;
  }
  return false;
}

function SearchCharacterAliveSubtype($player, $subtype, $notActiveLink = false, $excludeAuras=false)
{
  global $currentTurnEffects, $combatChain;
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  $effectPieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($character[$i + 1] != 0 && SubtypeContains($character[$i], $subtype, $player, $character[$i + 11])) {
      if (!$notActiveLink) return true;
      else if ($combatChain[8] != $character[$i + 11]) return true;
    }
    if ($character[$i] == "frostbite" && !$excludeAuras) {
      $slot = "";
      $effectCount = count($currentTurnEffects);
      for ($j = 0; $j < $effectCount; $j += $effectPieces) {
        $effect = explode(",", $currentTurnEffects[$j]);
        if ($effect[0] == "frostbite-" . $character[$i + 11]) $slot = $effect[1];
      }
      if ($subtype == $slot) return true;
    }
  }
  return false;
}

function SearchCharacterIndexSubtype($player, $subtype)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (SubtypeContains($character[$i], $subtype, $player)) return $i;
  }
  return -1;
}

function SearchCharacterAllIndexSubtype($player, $subtype)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if (SubtypeContains($character[$i], $subtype, $player)) $indices[] = $i;
  }
  return $indices;
}

function FindCharacterIndex($player, $cardID)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (isset($character[$i]) && $character[$i] == $cardID && $character[$i + 1] != 0) return $i;
  }
  return -1;
}

function FindCharacterIndexUniqueID($player, $uniqueID)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (isset($character[$i]) && $character[$i+11] == $uniqueID) {
      if ($character[$i + 1] != 0) return $i;
    }
  }
  return -1;
}

function FindCurrentTurnEffectIndex($player, $cardID)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = $count - $pieces; $i >= 0; $i -= $pieces) {
    if ($currentTurnEffects[$i + 1] == $player && $currentTurnEffects[$i] == $cardID) {
      return $i;
    }
  }
  return -1;
}

function CombineSearches($search1, $search2)
{
  if ($search2 == "") return $search1;
  else if ($search1 == "") return $search2;
  return $search1 . "," . $search2;
}

function SearchRemoveDuplicates($search)
{
  $indices = explode(",", $search);
  $countIndices = count($indices);
  for ($i = $countIndices - 1; $i >= 0; --$i) {
    for ($j = $i - 1; $j >= 0; --$j) {
      if (isset($indices[$j]) && isset($indices[$i]) && $indices[$j] == $indices[$i]) unset($indices[$i]);
    }
  }
  return implode(",", array_values($indices));
}

function SearchCount($search)
{
  if ($search == "") return 0;
  return count(explode(",", $search));
}

function SearchMultizoneFormat($search, $zone)
{
  if ($search == "") return "";
  if ($zone == "ACTIVEATTACK") $zone = "COMBATCHAINLINK";
  $searchArr = explode(",", $search);
  $countSearchArr = count($searchArr);
  for ($i = 0; $i < $countSearchArr; ++$i) {
    $searchArr[$i] = $zone . "-" . $searchArr[$i];
  }
  return implode(",", $searchArr);
}

function SearchCurrentTurnEffects($cardID, $player, $remove = false, $returnUniqueID = false, $activate = false, $stripParams = false)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (!isset($currentTurnEffects[$i + 1])) continue;
    $effectName = $stripParams ? explode(",", $currentTurnEffects[$i])[0] : $currentTurnEffects[$i];
    if ($effectName == $cardID && $currentTurnEffects[$i + 1] == $player) {
      if ($remove) RemoveCurrentTurnEffect($i);
      if ($activate) $currentTurnEffects[$i] = ExtractCardID($currentTurnEffects[$i]);
      return $returnUniqueID ? $currentTurnEffects[$i + 2] : true;
    }
  }
  return $returnUniqueID ? -1 : false;
}

function SearchDynamicCurrentTurnEffectsIndex($cardID, $player, $remove = false, $returnUniqueID = false)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    $effectID = explode(",", $currentTurnEffects[$i])[0];
    if (!isset($currentTurnEffects[$i + 1])) continue;
    if ($effectID == $cardID && $currentTurnEffects[$i + 1] == $player) {
      if ($remove) RemoveCurrentTurnEffect($i);
      return $returnUniqueID ? $currentTurnEffects[$i + 2] : $i;
    }
  }
  return -1;
}

function SearchNextTurnEffects($cardID, $player, $remove = false, $returnUniqueID = false, $activate = false)
{
  global $nextTurnEffects;
  $count = count($nextTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (!isset($nextTurnEffects[$i + 1])) continue;
    if ($nextTurnEffects[$i] == $cardID && $nextTurnEffects[$i + 1] == $player) {
      if ($remove) RemoveCurrentTurnEffect($i);
      if ($activate) $nextTurnEffects[$i] = ExtractCardID($nextTurnEffects[$i]);
      return $returnUniqueID ? $nextTurnEffects[$i + 2] : true;
    }
  }
  return $returnUniqueID ? -1 : false;
}

function SearchCurrentTurnEffectsForIndex($cardID, $player)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($currentTurnEffects[$i + 1] == $player && ExtractCardID($currentTurnEffects[$i]) == $cardID) {
      return $i;
    }
  }
  return -1;
}

function SearchCurrentTurnEffectsForCycle($card1, $card2, $card3, $player)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($currentTurnEffects[$i + 1] == $player && ($currentTurnEffects[$i] == $card1 || $currentTurnEffects[$i] == $card2 || $currentTurnEffects[$i] == $card3)) return true;
  }
  return false;
}

function CountCurrentTurnEffects($cardID, $player, $remove = false, $partial = false)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if (!$partial){
      if ($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i + 1] == $player) {
        if ($remove) RemoveCurrentTurnEffect($i);
        ++$total;
      }
    }
    else{
      if (str_contains($currentTurnEffects[$i], $cardID) && $currentTurnEffects[$i + 1] == $player) {
        if ($remove) RemoveCurrentTurnEffect($i);
        ++$total;
      }
    }
  }
  return $total;
}
function SearchPitchHighestAttack(&$pitch)
{
  global $mainPlayer;
  $highest = 0;
  $pitchCount = count($pitch);
  for ($i = 0; $i < $pitchCount; ++$i) {
    $powerValue = ModifiedPowerValue($pitch[$i], $mainPlayer, "PITCH", source: "");
    if ($powerValue > $highest) $highest = $powerValue;
  }
  return $highest;
}

function SearchPitchForColor($player, $pitchValue)
{
  $pitch = &GetPitch($player);
  $count = count($pitch);
  $pieces = PitchPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if (PitchValue($pitch[$i]) == $pitchValue && ColorContains($pitch[$i], $pitchValue, $player)) ++$total;
  }
  return $total;
}

//For e.g. Mutated Mass
function SearchPitchForNumCosts($player)
{
  $pitch = &GetPitch($player);
  $count = count($pitch);
  if ($count == 0) return 0;
  $pieces = PitchPieces();
  $total = 0;
  $countArr = [];
  
  for ($i = 0; $i < $count; $i += $pieces) {
    $cost = CardCost($pitch[$i]);
    if($cost == -1) continue;
    while (count($countArr) <= $cost) array_push($countArr, 0);
    if ($countArr[$cost] == 0) ++$total;
    ++$countArr[$cost];
  }
  return $total;
}

function SearchPitchForCard($playerID, $cardID)
{
  $pitch = GetPitch($playerID);
  $count = count($pitch);
  $pitchPieces = PitchPieces();
  for ($i = 0; $i < $count; $i += $pitchPieces) {
    if ($pitch[$i] == $cardID) return $i;
  }
  return -1;
}

function SearchBanishForCard($playerID, $cardID)
{
  $banish = GetBanish($playerID);
  $count = count($banish);
  $pieces = BanishPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($banish[$i] == $cardID && $banish[$i + 1] != "DOWN") return $i;
  }
  return -1;
}

function SearchBanishForUID($playerID, $UID)
{
  $banish = GetBanish($playerID);
  $count = count($banish);
  $pieces = BanishPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($banish[$i + 2] == $UID && $banish[$i + 1] != "DOWN") return $i;
  }
  return -1;
}

function SearchBanishForCardName($playerID, $cardID)
{
  $banish = GetBanish($playerID);
  $count = count($banish);
  $pieces = BanishPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (CardName($banish[$i]) == CardName($cardID)) return $i;
  }
  return -1;
}

function SearchBanishForCardMulti($playerID, $card1, $card2 = "", $card3 = "")
{
  $banish = GetBanish($playerID);
  $count = count($banish);
  $cardList = [];
  for ($i = 0; $i < $count; ++$i) {
    if ($banish[$i] == $card1 || $banish[$i] == $card2 || $banish[$i] == $card3) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchItemsForCardMulti($playerID, $card1, $card2 = "", $card3 = "")
{
  $items = GetItems($playerID);
  $count = count($items);
  $cardList = [];
  for ($i = 0; $i < $count; ++$i) {
    if ($items[$i] == $card1 || $items[$i] == $card2 || $items[$i] == $card3) {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchCharacterForCardMulti($playerID, $card1, $card2 = "", $card3 = "")
{
  $char = GetPlayerCharacter($playerID);
  $count = count($char);
  $cardList = [];
  for ($i = 0; $i < $count; ++$i) {
    if (($char[$i] == $card1 || $char[$i] == $card2 || $char[$i] == $card3) && $char[$i + 1] != 0 && $char[$i + 12] != "DOWN") {
      $cardList[] = $i;
    }
  }
  return implode(",", $cardList);
}

function SearchHighestAttackDefended()
{
  global $combatChain, $defPlayer;
  $count = count($combatChain);
  $pieces = CombatChainPieces();
  $highest = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($combatChain[$i + 1] == $defPlayer) {
      $powerValue = ModifiedPowerValue($combatChain[$i], $defPlayer, "CC");
      $powerValue += $combatChain[$i + 5];//Combat chain power modifier
      if ($powerValue > $highest) $highest = $powerValue;
    }
  }
  return $highest;
}

function SearchCharacterEffects($player, $index, $effect)
{
  $effects = &GetCharacterEffects($player);
  $count = count($effects);
  $pieces = CharacterEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($effects[$i] == $index && $effects[$i + 1] == $effect) return true;
  }
  return false;
}

function GetArsenalFaceDownIndices($player)
{
  $arsenal = &GetArsenal($player);
  $count = count($arsenal);
  $pieces = ArsenalPieces();
  $indices = "";
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($arsenal[$i + 1] == "DOWN") {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function GetEquipmentIndices($player, $maxBlock = -1, $minBlock = -1, $onCombatChain = false)
{
  $character = &GetPlayerCharacter($player);
  $count = count($character);
  $pieces = CharacterPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($character[$i + 1] == 0 || $character[$i + 12] == "DOWN") continue;
    if (CardType($character[$i]) != "E") continue;
    if ($onCombatChain && $character[$i + 6] == 0) continue;
    $block = BlockValue($character[$i]);
    if ($block != -1) {
      $block = $block + $character[$i + 4] + BlockModifier($character[$i], "EQUIP", "-", $i);
      $block = $block < 0 ? 0 : $block;
    }
    if ($minBlock == -1 && $maxBlock == -1 || $block <= $maxBlock && $block >= $minBlock) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchAuras($cardID, $player)
{
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i] == $cardID) return true;
  }
  return false;
}

function SearchAurasForIndex($cardID, $player)
{
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i] == $cardID) return $i;
  }
  return -1;
}

function SearchAurasForCard($cardID, $player, $selfReferential = true)
{
  if (!$selfReferential && SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i] == $cardID) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchAurasForCardName($cardName, $player, $selfReferential = true)
{
  if (!$selfReferential && SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if (NameOverride($auras[$i], $player) == $cardName) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchCharacterForCards($cardID, $player, $selfReferential = true)
{
  if (!$selfReferential && SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $char = &GetPlayerCharacter($player);
  $count = count($char);
  $pieces = CharacterPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($char[$i] == $cardID) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchZoneForUniqueID($uniqueID, $player, $zone)
{
  switch ($zone) {
    case "MYALLY":
    case "THEIRALLY":
      return SearchAlliesForUniqueID($uniqueID, $player);
    case "MYAURAS":
    case "THEIRAURAS":
      return SearchAurasForUniqueID($uniqueID, $player);
    case "MYARS":
    case "THEIRARS":
      return SearchArsenalForUniqueID($uniqueID, $player);
    default:
      return -1;
  }
}

function SearchForUniqueID($uniqueID, $player)
{
  $index = SearchAurasForUniqueID($uniqueID, $player);
  if ($index == -1) $index = SearchItemsForUniqueID($uniqueID, $player);
  if ($index == -1) $index = SearchAlliesForUniqueID($uniqueID, $player);
  if ($index == -1) $index = SearchArsenalForUniqueID($uniqueID, $player);
  if ($index == -1) $index = SearchCharacterForUniqueID($uniqueID, $player);
  if ($index == -1) $index = SearchLayersForUniqueID($uniqueID);
  return $index;
}

function SearchLayersForUniqueID($uniqueID)
{
  global $layers;
  $count = count($layers);
  $pieces = LayerPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($layers[$i + 6] == $uniqueID) return $i;
  }
  return -1;
}

function SearchLayersForTargetUniqueID($uniqueID)
{
  global $layers;
  $count = count($layers);
  $pieces = LayerPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (str_contains($layers[$i + 3], $uniqueID)) return $i;
  }
  return -1;
}

function SearchAurasForUniqueID($uniqueID, $player)
{
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i + 6] == $uniqueID) return $i;
  }
  return -1;
}

function SearchDiscardForUniqueID($uniqueID, $player)
{
  $discard = &GetDiscard($player);
  $count = count($discard);
  $pieces = DiscardPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($discard[$i + 1] == $uniqueID && !isFaceDownMod($discard[$i+2])) return $i;
  }
  return -1;
}

function SearchArsenalForUniqueID($uniqueID, $player)
{
  $arsenal = &GetArsenal($player);
  $count = count($arsenal);
  $pieces = ArsenalPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($arsenal[$i + 5] == $uniqueID) return $i;
  }
  return -1;
}

function SearchCharacterForUniqueID($uniqueID, $player)
{
  $char = &GetPlayerCharacter($player);
  $count = count($char);
  $pieces = CharacterPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($char[$i + 11] == $uniqueID && $char[$i + 1] != 0) return $i;
  }
  return -1;
}

function SearchItemsForUniqueID($uniqueID, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i + 4] == $uniqueID) return $i;
  }
  return -1;
}

function SearchAlliesForUniqueID($uniqueID, $player)
{
  $allies = &GetAllies($player);
  $count = count($allies);
  $pieces = AllyPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($allies[$i + 5] == $uniqueID) return $i;
  }
  return -1;
}

function SearchCurrentTurnEffectsForUniqueID($uniqueID)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($currentTurnEffects[$i + 2] == $uniqueID) return $i;
  }
  return -1;
}

function SearchPermanentsForUniqueID($uniqueID, $player)
{
  $perms = GetPermanents($player);
  $count = count($perms);
  $pieces = PermanentPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($perms[$i + 3] == $uniqueID) return $i;
  }
  return -1;
}

function SearchCurrentTurnEffectsForPartialId($partial)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if (strpos($currentTurnEffects[$i + 2], $partial) !== false) return true;
  }
  return false;
}

function SearchUniqueIDForCurrentTurnEffects($index)
{
  global $currentTurnEffects;
  $count = count($currentTurnEffects);
  $pieces = CurrentTurnEffectPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($currentTurnEffects[$i + 2] == $index) return $currentTurnEffects[$i];
  }
  return -1;
}

function SearchItemsForCard($cardID, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i] == $cardID) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchItemsForCardName($cardName, $player)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return "";
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  $indices = [];
  for ($i = 0; $i < $count; $i += $pieces) {
    if (CardName($items[$i]) == $cardName) {
      $indices[] = $i;
    }
  }
  return implode(",", $indices);
}

function SearchItemForIndex($cardID, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i] == $cardID) {
      return $i;
    }
  }
  return -1;
}

function SearchItemForLastIndex($cardID, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  for ($i = $count - $pieces; $i >= 0; $i -= $pieces) {
    if ($items[$i] == $cardID) {
      return $i;
    }
  }
  return -1;
}

function SearchItemForModalities($modality, $player, $cardID)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i] == $cardID && DelimStringContains($items[$i + 8], $modality)) {
      return $i;
    }
  }
  return -1;
}

function SearchAuraForModalities($modality, $player, $cardID)
{
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i] == $cardID && DelimStringContains($auras[$i + 10], $modality)) {
      return $i;
    }
  }
  return -1;
}

function SearchInventoryForCard($player, $cardID)
{
  $inventory = &GetInventory($player);
  $count = count($inventory);
  $pieces = InventoryPieces();
  $indices = "";
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($inventory[$i] == $cardID) {
      if ($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function SearchLandmark($cardID)
{
  global $landmarks;
  $count = count($landmarks);
  $pieces = LandmarkPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($landmarks[$i] == $cardID) return true;
  }
  return false;
}

function CountAura($cardID, $player)
{
  if (SearchCurrentTurnEffects("amnesia_red", $player)) return 0;
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if (CardName($auras[$i]) == CardName($cardID)) ++$total;
  }
  return $total;
}

function CountAuraPowerCounters($player)
{
  $auras = &GetAuras($player);
  $count = count($auras);
  $pieces = AuraPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($auras[$i + 3] > 0) $total += $auras[$i + 3];
  }
  return $total;
}

function GetItemIndex($cardID, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i] == $cardID) return $i;
  }
  return -1;
}

function GetCombatChainIndex($cardID, $player)
{
  global $combatChain;
  $count = count($combatChain);
  $pieces = CombatChainPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($combatChain[$i] == $cardID && $combatChain[$i + 1] == $player) return $i;
  }
  return -1;
}

function CountItem($cardID, $player, $NotTokens = true)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($items[$i] == $cardID) ++$total;
  }
  if ($cardID == "gold" && $NotTokens) {
    $char = GetPlayerCharacter($player);
    $charCount = count($char);
    $charPieces = CharacterPieces();
    for ($i = 0; $i < $charCount; $i += $charPieces) {
      if (IsGold($char[$i]) && $char[$i+1] > 1) ++$total;
    }
  }
  return $total;
}

function CountItemByName($cardName, $player)
{
  $items = &GetItems($player);
  $count = count($items);
  $pieces = ItemPieces();
  $total = 0;
  for ($i = 0; $i < $count; $i += $pieces) {
    if (CardNameContains($items[$i], $cardName, $player)) ++$total;
  }
  if ($cardName == "Gold") {
    $char = GetPlayerCharacter($player);
    $charCount = count($char);
    $charPieces = CharacterPieces();
    for ($i = 0; $i < $charCount; $i += $charPieces) {
      if (IsGold($char[$i]) && $char[$i + 1] > 1) ++$total;
    }
  }
  return $total;
}

function SearchArsenalReadyCard($player, $cardID)
{
  $arsenal = GetArsenal($player);
  $count = count($arsenal);
  $pieces = ArsenalPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($arsenal[$i] != $cardID) continue;
    if ($arsenal[$i + 1] != "UP") continue;
    if ($arsenal[$i + 2] == 0) continue;
    return $i;
  }
  return -1;
}

function SearchArcaneReplacement($player, $zone, $damage)
{
  $cardList = [];
  $count = 0;
  $pieces = 0;
  switch ($zone) {
    case "MYCHAR":
      $array = &GetPlayerCharacter($player);
      $count = count($array);
      $pieces = CharacterPieces();
      break;
    case "MYITEMS":
      $array = &GetItems($player);
      $count = count($array);
      $pieces = ItemPieces();
      break;
    case "MYAURAS":
      $array = &GetAuras($player);
      $count = count($array);
      $pieces = AuraPieces();
      break;
    case "MYALLY":
      $array = &GetAllies($player);
      $count = count($array);
      $pieces = AllyPieces();
      break;
    default:
      return "";
  }
  $addedRunechants = 0; //tracks how many runechants are displayed, only display up to the amount of damage
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($zone == "MYCHAR" && !IsCharacterAbilityActive($player, $i)) continue;
    $cardID = $array[$i];
    if ($zone == "MYAURAS" && $array[$i + 7] == 0) continue;
    if (SpellVoidAmount($cardID, $player) > 0) {
      if ($zone == "MYCHAR" && !IsCharacterActive($player, $i)) continue;
      if ($cardID == "runechant" && $zone != "MYCHAR") {
        if ($addedRunechants < $damage) {
          $cardList[] = $i;
          ++$addedRunechants;
        }
      } else {
        $cardList[] = $i;
      }
    }
  }
  return implode(",", $cardList);
}

function SearchChainLinks($minPower = -1, $maxPower = -1, $cardType = "")
{
  global $chainLinks, $mainPlayer;
  $links = [];
  $count = count($chainLinks);
  for ($i = 0; $i < $count; ++$i) {
    if ($chainLinks[$i][2] != "1") continue;
    $power = PowerValue($chainLinks[$i][0], $mainPlayer, "CC");
    if ($minPower != -1 && $power < $minPower) continue;
    if ($maxPower != -1 && $power > $maxPower) continue;
    if ($cardType != "") {
      $type = CardType($chainLinks[$i][0]);
      if ($type != $cardType) continue;
    }
    $links[] = $i;
  }
  return implode(",", $links);
}

function GetRelativeMZCardLink($player, $MZ)
{
  $params = explode("-", $MZ);
  if (count($params) < 2 || $params[0] == "" || $params[1] == "") return "";
  $zoneDS = &GetRelativeMZZone($player, $params[0]);
  $index = $params[1];
  if ($index == "" || !isset($zoneDS[$index])) return "";
  return CardLink($zoneDS[$index], $zoneDS[$index]);
}

function GetMZCardLink($player, $MZ)
{
  if ($MZ == "") return "";
  $params = explode("-", $MZ);
  $zoneDS = &GetMZZone($player, $params[0]);
  $index = $params[1];
  if ($index == "") return "";
  if (is_numeric($index)) {
    if ($params[0] == "PASTCHAINLINK") {
      $cardID = $zoneDS[$params[2]][$index];
    }
    else {
      if (isset($zoneDS[$index]) && is_string($zoneDS[$index])) {
        if ($zoneDS[$index] == "TRIGGER" || $zoneDS[$index] == "MELD" || $zoneDS[$index] == "ABILITY") $index += 2;
        $cardID = $zoneDS[$index] == "runechant_batch" ? "runechant" : $zoneDS[$index];
      }
    }
    return CardLink($cardID, $cardID);
  }
  else { //the index was a UID
    $pieces = GetMZZonePieces($params[0]);
    $offset = GetMZZoneUIDIndex($params[0]);
    if ($pieces > 0 && $offset > 0) {
      $count = count($zoneDS);
      for ($i = 0; $i < $count; $i += $pieces) {
        $cardID = $zoneDS[$i];
        if ($index == $zoneDS[$i + $offset]) return CardLink($cardID, $cardID);
      }
    }
  }
  return "";
}

//$searches is the following format:
//Each search is delimited by &, which means a set UNION
//Each search is the format <zone>:<condition 1>;<condition 2>,...
//Each condition is format <search parameter name>=<parameter value>
//Example: AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:maxAttack=3;type=AA");
function SearchMultizone($player, $searches)
{
  global $combatChain;
  $otherPlayer = $player == 1 ? 2 : 1;
  $unionSearches = explode("&", $searches);
  $rv = "";
  for ($i = 0; $i < count($unionSearches); ++$i) {
    $type              = "";
    $subtype           = "";
    $maxCost           = -1;
    $minCost           = -1;
    $class             = "";
    $talent            = "";
    $nameIncludes      = "";
    $bloodDebtOnly     = false;
    $phantasmOnly      = false;
    $pitch             = -1;
    $specOnly          = false;
    $maxAttack         = -1;
    $minAttack         = -1;
    $maxDef            = -1;
    $frozenOnly        = false;
    $hasNegCounters    = false;
    $hasEnergyCounters = false;
    $comboOnly         = false;
    $hasCrank          = false;
    $hasSteamCounter   = false;
    $searchArr         = explode(":", $unionSearches[$i]);
    $zone              = $searchArr[0];
    $isCardID          = false;
    $isSameName        = false;
    $is1h              = false;
    $hasWard           = false;
    $faceUp            = false;
    $faceDown          = false;
    $hasPowerCounters  = false;
    $isIntimidated     = false;
    $arcaneDamage      = -1;
    $hasStealth        = false;
    $nullDef           = false;
    $hasCrush          = false;
    $realPitch         = "-";
    $hasSuspense       = false;
    $hasCloaked        = false;
    if (count($searchArr) > 1) //Means there are conditions
    {
      $conditions = explode(";", $searchArr[1]);
      for ($j = 0; $j < count($conditions); ++$j) {
        $condition = explode("=", $conditions[$j]);
        switch ($condition[0]) {
          case "type":
            $type = $condition[1];
            break;
          case "subtype":
            $subtype = $condition[1];
            break;
          case "maxCost":
            $maxCost = $condition[1];
            break;
          case "minCost":
            $minCost = $condition[1];
            break;
          case "class":
            $class = $condition[1];
            break;
          case "talent":
            $talent = $condition[1];
            break;
          case "nameIncludes":
            $nameIncludes = $condition[1];
            break;
          case "bloodDebtOnly":
            $bloodDebtOnly = $condition[1];
            break;
          case "phantasmOnly":
            $phantasmOnly = $condition[1];
            break;
          case "pitch":
            $pitch = $condition[1];
            break;
          case "specOnly":
            $specOnly = $condition[1];
            break;
          case "maxAttack":
            $maxAttack = $condition[1];
            break;
          case "minAttack":
            $minAttack = $condition[1];
            break;
          case "maxDef":
            $maxDef = $condition[1];
            break;
          case "frozenOnly":
            $frozenOnly = $condition[1];
            break;
          case "hasNegCounters":
            $hasNegCounters = $condition[1];
            break;
          case "hasEnergyCounters":
            $hasEnergyCounters = $condition[1];
            break;
          case "comboOnly":
            $comboOnly = $condition[1];
            break;
          case "hasCrank":
            $hasCrank = $condition[1];
            break;
          case "hasSteamCounter":
            $hasSteamCounter = $condition[1];
            break;
          case "isIntimidated":
            $isIntimidated = $condition[1];
            break;
          case "hasCloaked":
            $hasCloaked = $condition[1];
            break;
          case "cardID":
            $cards = explode(",", $condition[1]);
            switch ($zone) {
              case "MYDECK":
                if (count($cards) == 1) $searchResult = SearchDeckForCard($player, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchDeckForCard($player, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchDeckForCard($player, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Deck multizone search only supports 3 cards -- report bug.");
                break;
              case "MYDISCARD":
                if (count($cards) == 1) $searchResult = SearchDiscardForCard($player, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchDiscardForCard($player, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchDiscardForCard($player, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Discard multizone search only supports 3 cards -- report bug.");
                break;
              case "THEIRDISCARD":
                if (count($cards) == 1) $searchResult = SearchDiscardForCard($otherPlayer, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchDiscardForCard($otherPlayer, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchDiscardForCard($otherPlayer, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Discard multizone search only supports 3 cards -- report bug.");
                break;
              case "MYBANISH":
                if (count($cards) == 1) $searchResult = SearchBanishForCardMulti($player, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchBanishForCardMulti($player, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchBanishForCardMulti($player, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Banish multizone search only supports 3 cards -- report bug.");
                break;
              case "THEIRBANISH":
                if (count($cards) == 1) $searchResult = SearchBanishForCardMulti($otherPlayer, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchBanishForCardMulti($otherPlayer, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchBanishForCardMulti($otherPlayer, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Banish multizone search only supports 3 cards -- report bug.");
                break;
              case "MYITEMS":
                if (count($cards) == 1) $searchResult = SearchItemsForCardMulti($player, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchItemsForCardMulti($player, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchItemsForCardMulti($player, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Items multizone search only supports 3 cards -- report bug.");
                break;
              case "THEIRITEMS":
                if (count($cards) == 1) $searchResult = SearchItemsForCardMulti($otherPlayer, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchItemsForCardMulti($otherPlayer, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchItemsForCardMulti($otherPlayer, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Items multizone search only supports 3 cards -- report bug.");
                break;
              case "MYAURAS":
                if (count($cards) == 1) $searchResult = SearchAurasForCard($cards[0], $player);
                else WriteLog("aura multizone search only supports 1 card -- report bug.");
                break;
              case "THEIRAURAS":
                if (count($cards) == 1) $searchResult = SearchAurasForCard($cards[0], $otherPlayer);
                else WriteLog("aura multizone search only supports 1 card -- report bug.");
                break;
              case "MYCHAR":
                if (count($cards) == 1) $searchResult = SearchCharacterForCardMulti($player, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchCharacterForCardMulti($player, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchCharacterForCardMulti($player, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Character multizone search only supports 3 cards -- report bug.");
                break;
              case "THEIRCHAR":
                if (count($cards) == 1) $searchResult = SearchCharacterForCardMulti($otherPlayer, $cards[0]);
                else if (count($cards) == 2) $searchResult = SearchCharacterForCardMulti($otherPlayer, $cards[0], $cards[1]);
                else if (count($cards) == 3) $searchResult = SearchCharacterForCardMulti($otherPlayer, $cards[0], $cards[1], $cards[2]);
                else WriteLog("Character multizone search only supports 3 cards -- report bug.");
                break;
              default:
                break;
            }
            $searchResult = SearchMultiZoneFormat($searchResult, $zone);
            $rv = CombineSearches($rv, $searchResult);
            $isCardID = true;
            break;
          case "isSameName":
          case "shareNames":
            if ($condition[0] == "isSameName") $name = CardName($condition[1]);
            else $name = GamestateUnsanitize(str_replace("|", ":", $condition[1]));
            switch ($zone) {
              case "MYDECK":
                $searchResult = SearchDeckByName($player, $name);
                break;
              case "MYDISCARD":
                $searchResult = SearchDiscardByName($player, $name);
                break;
              case "MYITEMS":
                $searchResult = SearchItemsByName($player, $name);
                break;
              case "MYBANISH":
                $searchResult = SearchBanishByName($player, $name);
                break;
              case "MYHAND":
                $searchResult = SearchHandForCardName($player, $name);
                break;
              case "THEIRHAND":
                $searchResult = SearchHandForCardName($otherPlayer, $name);
                break;
              case "THEIRDECK":
                $searchResult = SearchDeckByName($otherPlayer, $name);
                break;
              case "THEIRDISCARD":
                $searchResult = SearchDiscardByName($otherPlayer, $name);
                break;
              case "THEIRITEMS":
                $searchResult = SearchItemsByName($otherPlayer, $name);
                break;
              case "THEIRBANISH":
                $searchResult = SearchBanishByName($otherPlayer, $name);
                break;
              case "THEIRARSENAL":
                $searchResult = SearchArsenalForCardName($otherPlayer, $name);
                break;
              case "MYAURAS":
                $searchResult = SearchAurasForCardName($name, $player);
                break;
              case "THEIRAURAS":
                $searchResult = SearchAurasForCardName($name, $otherPlayer);
                break;
              default:
                break;
            }
            if ($rv != "") $rv = $rv . ",";
            $rv = $rv . SearchMultiZoneFormat($searchResult, $zone);
            if (substr($rv, -1) == ",") $rv = substr($rv, 0, -1);
            $isSameName = true;
            break;
          case "is1h":
            $is1h = $condition[1];
            break;
          case "hasWard":
            $hasWard = $condition[1];
            break;
          case "faceUp":
            $faceUp = $condition[1];
            break;
          case "faceDown":
            $faceDown = $condition[1];
            break;
          case "hasPowerCounters":
            $hasPowerCounters = $condition[1];
            break;
          case "arcaneDamage":
            $arcaneDamage = $condition[1];
            break;
          case "hasStealth":
            $hasStealth = $condition[1];
            break;
          case "nullDef":
            $nullDef = $condition[1];
            break;
          case "hasCrush":
            $hasCrush = $condition[1];
            break;
          case "realPitch":
            $realPitch = $condition[1];
            break;
          case "hasSuspense":
            $hasSuspense = $condition[1];
            break;
          default:
            break;
        }
      }
    }
    $searchPlayer = substr($zone, 0, 2) == "MY" ? $player : ($player == 1 ? 2 : 1);
    $searchResult = "";
    if (!$isCardID && !$isSameName) {
      switch ($zone) {
        case "MYDECK":
        case "THEIRDECK":
          $searchResult = SearchDeck($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "MYHAND":
        case "THEIRHAND":
          $searchResult = SearchHand($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, arcaneDamage: $arcaneDamage, hasCrush: $hasCrush, realPitch:$realPitch);
          break;
        case "MYDISCARD":
        case "THEIRDISCARD":
          $searchResult = SearchDiscard($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, nameIncludes: $nameIncludes, hasStealth:$hasStealth, hasSuspense:$hasSuspense);
          break;
        case "MYARS":
        case "THEIRARS":
          $searchResult = SearchArsenal($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, $faceUp, $faceDown);
          break;
        case "MYAURAS":
        case "THEIRAURAS":
          $searchResult = SearchAura($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasWard, $hasPowerCounters, hasSuspense: $hasSuspense);
          break;
        case "MYCHAR":
        case "THEIRCHAR":
          $searchResult = SearchCharacter($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter, $nameIncludes, $is1h, $faceUp, $faceDown, $nullDef, $hasCloaked);
          break;
        case "MYITEMS":
        case "THEIRITEMS":
          $searchResult = SearchItems($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, $hasSteamCounter);
          break;
        case "MYALLY":
        case "THEIRALLY":
          $searchResult = SearchAllies($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "MYPERM":
        case "THEIRPERM":
          $searchResult = SearchPermanents($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "MYBANISH":
        case "THEIRBANISH":
          $searchResult = SearchBanish($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank, isIntimidated: $isIntimidated);
          break;
        case "MYPITCH":
        case "THEIRPITCH":
          $searchResult = SearchPitch($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "MYSOUL":
        case "THEIRSOUL":
          $searchResult = SearchSoul($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "COMBATCHAINLINK":
          $searchResult = SearchCombatChainLink($player, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "ACTIVEATTACK":
          $searchResult = SearchActiveAttack($player, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "LAYER":
          $searchResult = SearchLayer($player, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "LANDMARK":
          $searchResult = SearchLandmarks($player, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        case "COMBATCHAINATTACKS":
          $searchResult = SearchCombatChainAttacks($player, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly, $hasNegCounters, $hasEnergyCounters, $comboOnly, $minAttack, $hasCrank);
          break;
        default:
          break;
      }
    }
    $searchResult = SearchMultiZoneFormat($searchResult, $zone);
    $rv = CombineSearches($rv, $searchResult);
  }
  return $rv;
}

function MZToIndices($mzSearch)
{
  $output = "";
  $mzSearchArr = explode(",", $mzSearch);
  for ($i = 0; $i < count($mzSearchArr); ++$i) {
    $mzArr = explode("-", $mzSearchArr[$i]);
    if ($output != "") $output .= ",";
    $output .= $mzArr[1];
  }
  return $output;
}

function FrozenCount($player)
{
  $numFrozen = 0;
  $char = &GetPlayerCharacter($player);
  $countChar = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $countChar; $i += $charPieces)
    if ($char[$i + 8] == "1" && $char[$i + 1] != "0")
      ++$numFrozen;
  $allies = &GetAllies($player);
  $countAllies = count($allies);
  $allyPieces = AllyPieces();
  for ($i = 0; $i < $countAllies; $i += $allyPieces)
    if ($allies[$i + 3] == "1")
      ++$numFrozen;
  $arsenal = &GetArsenal($player);
  $countArsenal = count($arsenal);
  $arsenalPieces = ArsenalPieces();
  for ($i = 0; $i < $countArsenal; $i += $arsenalPieces)
    if ($arsenal[$i + 4] == "1")
      ++$numFrozen;
  return $numFrozen;
}

function SearchSpellvoidIndices($player, $damage)
{
  $search = SearchArcaneReplacement($player, "MYCHAR", $damage);
  $charIndices = SearchMultizoneFormat($search, "MYCHAR");
  $search = SearchArcaneReplacement($player, "MYITEMS", $damage);
  $itemsIndices = SearchMultizoneFormat($search, "MYITEMS");
  $indices = CombineSearches($charIndices, $itemsIndices);
  $search = SearchArcaneReplacement($player, "MYAURAS", $damage);
  $auraIndices = SearchMultizoneFormat($search, "MYAURAS");
  $indices = CombineSearches($indices, $auraIndices);
  $search = SearchArcaneReplacement($player, "MYALLY", $damage);
  $allyIndices = SearchMultizoneFormat($search, "MYALLY");
  $indices = CombineSearches($indices, $allyIndices);
  return $indices;
}

function SearchGetFirst($search)
{
  if ($search == "") return "";
  $arr = explode(",", $search);
  return $arr[0];
}

function SearchGetLast($search)
{
  if ($search == "") return "";
  $arr = explode(",", $search);
  return $arr[count($arr) - 1];
}

function SearchGetFirstIndex($search)
{
  $firstMZ = SearchGetFirst($search);
  if ($search == "") return "";
  $arr = explode("-", $firstMZ);
  return $arr[1];
}

function SearchGetLastIndex($search)
{
  $lastMZ = SearchGetLast($search);
  if ($search == "") return "";
  $arr = explode("-", $lastMZ);
  return $arr[1];
}

function SearchLayersForCardID($cardID)
{
  global $layers;
  $count = count($layers);
  $pieces = LayerPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($layers[$i + 2] == $cardID) return $i;
  }
  return -1;
}

function SearchLayersForPhase($phase)
{
  global $layers;
  $count = count($layers);
  $pieces = LayerPieces();
  for ($i = 0; $i < $count; $i += $pieces) {
    if ($layers[$i] == $phase) return $i;
  }
  return -1;
}

function GetPlayerNumEquipment($player, $face = "UP")
{
  $characters = &GetPlayerCharacter($player);
  $count = 0;
  $countChar = count($characters);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $countChar; $i += $charPieces) {
    if (TypeContains($characters[$i], "E", $player) && $characters[$i + 1] != 0 && $characters[$i + 12] == $face) ++$count;
  }
  return $count;
}

function GetPlayerNumTokens($player)
{
  $auras = &GetAuras($player);
  $items = &GetItems($player);
  $ally = &GetAllies($player);
  $permanents = &GetPermanents($player);
  $char = &GetPlayerCharacter($player);
  $count = 0;
  $countAuras = count($auras);
  $auraPieces = AuraPieces();
  for ($i = 0; $i < $countAuras; $i += $auraPieces) {
    if (TypeContains($auras[$i], "T", $player)) ++$count;
  }
  $countItems = count($items);
  $itemPieces = ItemPieces();
  for ($i = 0; $i < $countItems; $i += $itemPieces) {
    if (TypeContains($items[$i], "T", $player)) ++$count;
  }
  $countAllies = count($ally);
  $allyPieces = AllyPieces();
  for ($i = 0; $i < $countAllies; $i += $allyPieces) {
    if (TypeContains($ally[$i], "T", $player)) ++$count;
    else if (TypeContains($ally[$i + 4], "T", $player)) ++$count; //Check for Allies Subcards
  }
  $countPermanents = count($permanents);
  $permanentsPieces = PermanentPieces();
  for ($i = 0; $i < $countPermanents; $i += $permanentsPieces) {
    if (TypeContains($permanents[$i], "T", $player)) ++$count;
  }
  $countChar = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $countChar; $i += $charPieces) {
    if (TypeContains($char[$i], "T", $player)) ++$count;
  }
  return $count;
}

function RemoveCardSameNames($player, $stringCardsIndex, $zone)
{
  if ($stringCardsIndex == "") return "";
  $indexToCheck = explode(',', $stringCardsIndex);
  $uniqueNameIndex = [];
  $seen = [];
  for ($i = 0; $i < count($indexToCheck); $i++) {
    $name = CardName($zone[$indexToCheck[$i]]);
    if (!isset($seen[$name])) {
      $seen[$name] = true;
      $uniqueNameIndex[] = $indexToCheck[$i];
    }
  }
  return implode(",", $uniqueNameIndex);
}

function RemoveDuplicateCards($player, $stringCardsIndex, $zone)
{
  if ($stringCardsIndex == "") return "";
  $indexToCheck = explode(',', $stringCardsIndex);
  $uniqueNameIndex = [];
  $seen = [];
  for ($i = 0; $i < count($indexToCheck); $i++) {
    $cardID = $zone[$indexToCheck[$i]];
    if (!isset($seen[$cardID])) {
      $seen[$cardID] = true;
      $uniqueNameIndex[] = $indexToCheck[$i];
    }
  }
  return implode(",", $uniqueNameIndex);
}

function SearchSoulForIndex($cardID, $player)
{
  $souls = &GetSoul($player);
  $countSouls = count($souls);
  $soulPieces = SoulPieces();
  for ($i = 0; $i < $countSouls; $i += $soulPieces) {
    if ($souls[$i] == $cardID) return $i;
  }
  return -1;
}

function SearchCombatChainDefendingCards($player, $cardType = "-")
{
  global $chainLinks;
  if ($cardType == "-") $cardType = "";
  $otherPlayer = $player == 1 ? 2 : 1;
  $cardIDList = [];
  $baseList = GetChainLinkCardIDs($otherPlayer, $cardType, exclCardTypes: "C");
  if ($baseList != "") {
    $cardIDList = explode(",", $baseList);
  }
  $countChainLinks = count($chainLinks);
  $chainLinksPieces = ChainLinksPieces();
  for ($i = 0; $i < $countChainLinks; ++$i) {
    $innerCount = count($chainLinks[$i]);
    for ($j = 0; $j < $innerCount; $j += $chainLinksPieces) {
      if ($chainLinks[$i][$j + 1] != $otherPlayer || $chainLinks[$i][$j + 2] != "1") continue;
      if ($cardType != "" && !TypeContains($chainLinks[$i][$j], $cardType, $player)) continue;
      $cardIDList[] = $chainLinks[$i][$j];
    }
  }
  return implode(",", $cardIDList);
}

function SearchCombatChainForIndex($cardID, $player)
{
  global $combatChain;
  $countCombatChain = count($combatChain);
  $combatChainPieces = CombatChainPieces();
  for ($i = $countCombatChain - $combatChainPieces; $i >= 0; $i -= $combatChainPieces) {
    if ($combatChain[$i] == $cardID && $combatChain[$i + 1] == $player) return $i;
  }
  return -1;
}

// needed to catch meld card types
function SearchLayersCardType($type, $type2="-")
{
  global $layers;
  $found = [];
  $countLayers = count($layers);
  $layerPieces = LayerPieces();
  for ($i = 0; $i < $countLayers; $i += $layerPieces) {
    $cardType = CardType($layers[$i], "STACK", $layers[$i+1], $layers[$i+4]);
    if (DelimStringContains($cardType, $type) || $type2 != "-" && DelimStringContains($cardType, $type2)) {
      array_push($found, $i);
    }
  }
  if (count($found) == 0) return "";
  else return implode(",", $found);
}

function SearchLayersForNAA($maxCost=-1) {
    global $layers;
    $found = [];
    $countLayers = count($layers);
    $layerPieces = LayerPieces();
    for ($i = 0; $i < $countLayers; $i += $layerPieces) {
      $playerID = $layers[$i+1];
      $from = explode("|",$layers[$i+2])[0];
      $cardType = CardType($layers[$i], "LAYER", $playerID, $i);
      if ($maxCost != -1 && CardCost($layers[$i], "LAYER") > $maxCost) continue;
      if (TypeContains($layers[$i], "A", from: "LAYER", index:$i) && ($from != "PLAY" || GetResolvedAbilityType($layers[$i], $from, $playerID) == "A")) {
        array_push($found, "LAYER-" . $i);
      }
    }
    $rv = (count($found) == 0) ? "" : implode(",", $found);
    return $rv;
}

function GetGoldIndices($player) {
  $indices = [];
  if (!SearchCurrentTurnEffects("amnesia_red", $player)) {
    $items = GetItems($player);
    $countItems = count($items);
    $itemPieces = ItemPieces();
    for ($i = 0; $i < $countItems; $i += $itemPieces) {
      if ($items[$i] == "gold") array_push($indices, "MYITEMS-$i");
    }
  }
  $char = GetPlayerCharacter($player);
  $countChar = count($char);
  $charPieces = CharacterPieces();
  for ($i = 0; $i < $countChar; $i += $charPieces) {
    if (IsGold($char[$i]) && $char[$i+1] > 1) array_push($indices, "MYCHAR-$i");
  }
  return implode(",", $indices);
}

function GetAllyCounterIndices($player) {
  $choices = [];
  $Allies = new Allies($player);
  for ($i = 0; $i < $Allies->NumAllies(); ++$i) {
    $Ally = $Allies->Card($i, true);
    if ($Ally->PowerCounters() > 0) {
      array_push($choices, "MYALLY-" . $Ally->Index());
    }
  }
  return implode(",", $choices);
}