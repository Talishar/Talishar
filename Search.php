<?php

function SearchDeck($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  if(SearchAurasForCard("UPR138", $otherPlayer) != "")
  {
    WriteLog("Deck search prevented by Channel the Bleak Expanse.");
    return "";
  }
  $deck = &GetDeck($player);
  return SearchInner($deck, $player, "DECK", DeckPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchHand($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $hand = &GetHand($player);
  return SearchInner($hand, $player, "HAND", HandPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchCharacter($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $character = &GetPlayerCharacter($player);
  return SearchInner($character, $player, "CHAR", CharacterPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchPitch($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $searchPitch = &GetPitch($player);
  return SearchInner($searchPitch, $player, "PITCH", PitchPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchDiscard($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $discard = &GetDiscard($player);
  return SearchInner($discard, $player, "DISCARD", DiscardPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchBanish($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $banish = &GetBanish($player);
  return SearchInner($banish, $player, "BANISH", BanishPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchCombatChain($type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  global $combatChain;
  return SearchInner($combatChain, $player, "CC", CombatChainPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchArsenal($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $arsenal = &GetArsenal($player);
  return SearchInner($arsenal, $player, "ARS", ArsenalPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchAura($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $auras = &GetAuras($player);
  return SearchInner($auras, $player, "AURAS", AuraPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchItems($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $items = &GetItems($player);
  return SearchInner($items, $player, "ITEMS", ItemPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchAllies($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $allies = &GetAllies($player);
  return SearchInner($allies, $player, "ALLY", AllyPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchPermanents($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false, $pitch=-1, $specOnly=false, $maxAttack=-1, $maxDef=-1, $frozenOnly=false)
{
  $permanents = &GetPermanents($player);
  return SearchInner($permanents, $player, "PERM", PermanentPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchInner(&$array, $player, $zone, $count, $type, $subtype, $maxCost, $minCost, $class, $talents, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly)
{
  $cardList = "";
  if(!is_array($talents)) $talents = ($talents == "" ? [] : explode(",", $talents));
  for($i=0; $i<count($array); $i += $count)
  {
    $cardID = $array[$i];
    if(($type == "" || CardType($cardID) == $type)
    && ($subtype == "" || DelimStringContains(CardSubType($cardID), $subtype))
    && ($maxCost == -1 || CardCost($cardID) <= $maxCost)
    && ($minCost == -1 || CardCost($cardID) >= $minCost)
    && ($class == "" || ClassContains($cardID, $class, $player))
    && (count($talents) == 0 || TalentContainsAny($cardID, implode(",", $talents), $player))
    && ($pitch == -1 || PitchValue($cardID) == $pitch)
    && ($maxAttack == -1 || AttackValue($cardID) <= $maxAttack)
    && ($maxDef == -1 || BlockValue($cardID) <= $maxDef))
    {
      if($bloodDebtOnly && !HasBloodDebt($cardID)) continue;
      if($phantasmOnly && !HasPhantasm($cardID)) continue;
      if($specOnly && !IsSpecialization($cardID)) continue;
      if($frozenOnly && !IsFrozenMZ($array, $zone, $i)) continue;
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

//Parses DQ subparams into search format
function SearchLayerDQ($player, $param)
{
  global $layers;
  $type=""; $subtype=""; $maxCost=-1; $minCost=-1; $class=""; $talent=""; $bloodDebtOnly=false; $phantasmOnly=false; $pitch=-1; $specOnly=false; $maxAttack=-1; $maxDef=-1; $frozenOnly=false;
  $paramArray = explode("-", $param);
  for($i=0; $i < count($paramArray); $i+=2)
  {
    if($paramArray[$i] == "TYPE") $type = $paramArray[$i+1];
    else if($paramArray[$i] == "MAXCOST") $maxCost = $paramArray[$i+1];
  }
  return SearchInner($layers, $player, "LAYER", LayerPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly);
}

function SearchHandForCard($player, $card)
{
  $hand = &GetHand($player);
  $indices = "";
  for($i=0; $i<count($hand); $i+=HandPieces())
  {
    if($hand[$i] == $card)
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function SearchDeckForCard($player, $card1, $card2="", $card3="")
{
  $deck = &GetDeck($player);
  $cardList = "";
  for($i=0; $i<count($deck); ++$i)
  {
    $id = $deck[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchDiscardForCard($player, $card1, $card2="", $card3="")
{
  $discard = &GetDiscard($player);
  $cardList = "";
  for($i=0; $i<count($discard); ++$i)
  {
    $id = $discard[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchAlliesForCard($player, $card1, $card2="", $card3="")
{
  $allies = &GetAllies($player);
  $cardList = "";
  for($i=0; $i<count($allies); ++$i)
  {
    $id = $allies[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchAlliesActive($player, $card1, $card2="", $card3="")
{
  $allies = &GetAllies($player);
  $cardList = "";
  for($i=0; $i<count($allies); ++$i)
  {
    $id = $allies[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList != "";
}

function SearchEquipNegCounter(&$character)
{
  $equipList = "";
  for($i=0; $i<count($character); $i += CharacterPieces())
  {
    if(CardType($character[$i]) == "E" && $character[$i+4] < 0 && $character[$i+1] != 0)
    {
      if($equipList != "") $equipList = $equipList . ",";
      $equipList = $equipList . $i;
    }
  }
  return $equipList;
}

function SearchCharacterActive($player, $cardID)
{
  $index = FindCharacterIndex($player, $cardID);
  if($index == -1) return false;
  return IsCharacterAbilityActive($player, $index);
}

function SearchCharacterForCard($player, $cardID)
{
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i += CharacterPieces())
  {
    if($character[$i] == $cardID) return true;
  }
  return false;
}

function FindCharacterIndex($player, $cardID)
{
  $character = &GetPlayerCharacter($player);
  for($i=0; $i<count($character); $i += CharacterPieces())
  {
    if($character[$i] == $cardID) return $i;
  }
  return -1;
}

function CombineSearches($search1, $search2)
{
  if($search2 == "") return $search1;
  else if($search1 == "") return $search2;
  return $search1 . "," . $search2;
}

function SearchRemoveDuplicates($search)
{
  $indices = explode(",", $search);
  for($i=count($indices)-1; $i>=0; --$i)
  {
    for($j=$i-1; $j>=0; --$j)
    {
      if($indices[$j] == $indices[$i]) unset($indices[$i]);
    }
  }
  return implode(",", array_values($indices));
}

function SearchCount($search)
{
  if($search == "") return 0;
  return count(explode(",", $search));
}

function SearchMultizoneFormat($search, $zone)
{
  if($search == "") return "";
  $searchArr = explode(",", $search);
  for($i=0; $i<count($searchArr); ++$i)
  {
    $searchArr[$i] = $zone . "-" . $searchArr[$i];
  }
  return implode(",", $searchArr);
}

function SearchCurrentTurnEffects($cardID, $player, $remove=false)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i+1] == $player){
      if($remove) RemoveCurrentTurnEffect($i);
      return true;
    }
  }
  return false;
}

function SearchCurrentTurnEffectsForCycle($card1, $card2, $card3, $player)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $card1 && $currentTurnEffects[$i+1] == $player) return true;
    if($currentTurnEffects[$i] == $card2 && $currentTurnEffects[$i+1] == $player) return true;
    if($currentTurnEffects[$i] == $card3 && $currentTurnEffects[$i+1] == $player) return true;
  }
  return false;
}

function CountCurrentTurnEffects($cardID, $player, $remove=false)
{
  global $currentTurnEffects;
  $count = 0;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i+1] == $player){
      if($remove) RemoveCurrentTurnEffect($i);
      ++$count;
    }
  }
  return $count;
}

function SearchPitchHighestAttack(&$pitch)
{
  $highest = 0;
  for($i=0; $i<count($pitch); ++$i)
  {
    $av = AttackValue($pitch[$i]);
    if($av > $highest) $highest = $av;
  }
  return $highest;
}

function SearchPitchForColor($player, $color)
{
  $count = 0;
  $pitch = &GetPitch($player);
  for($i=0; $i<count($pitch); $i += PitchPieces())
  {
    if(PitchValue($pitch[$i]) == $color) ++$count;
  }
  return $count;
}

//For e.g. Mutated Mass
function SearchPitchForNumCosts($player)
{
  $count = 0;
  $countArr = [];
  $pitch = &GetPitch($player);
  for($i=0; $i<count($pitch); $i += PitchPieces())
  {
    $cost = CardCost($pitch[$i]);
    while(count($countArr) <= $cost) array_push($countArr, 0);
    if($countArr[$cost] == 0) ++$count;
    ++$countArr[$cost];
  }
  return $count;
}

function SearchPitchForCard($playerID, $cardID)
{
  $pitch = GetPitch($playerID);
  for($i=0; $i<count($pitch); ++$i)
  {
    if($pitch[$i] == $cardID) return $i;
  }
  return -1;
}

function SearchHighestAttackDefended()
{
  global $combatChain, $defPlayer;
  $highest = 0;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    if($combatChain[$i+1] == $defPlayer)
    {
      $av = AttackValue($combatChain[$i]);
      if($av > $highest) $highest = $av;
    }
  }
  return $highest;
}

function SearchCharacterEffects($player, $index, $effect)
{
  $effects = &GetCharacterEffects($player);
  for($i=0; $i<count($effects); $i+=CharacterEffectPieces())
  {
    if($effects[$i] == $index && $effects[$i+1] == $effect) return true;
  }
  return false;
}

function GetArsenalFaceDownIndices($player)
{
  $arsenal = &GetArsenal($player);
  $indices = "";
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if($arsenal[$i+1] == "DOWN")
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function GetEquipmentIndices($player, $maxBlock=-1)
{
  $character = &GetPlayerCharacter($player);
  $indices = "";
  for($i=0; $i<count($character); $i+=CharacterPieces())
  {
    if($character[$i+1] != 0 && CardType($character[$i]) == "E" && ($maxBlock == -1 || (BlockValue($character[$i]) + $character[$i+4]) <= $maxBlock))
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function SearchAuras($cardID, $player)
{
  $auras = &GetAuras($player);
  $count = 0;
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if($auras[$i] == $cardID) return true;
  }
  return false;
}

function SearchAurasForCard($cardID, $player)
{
  $auras = &GetAuras($player);
  $indices = "";
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if($auras[$i] == $cardID)
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function SearchForUniqueID($uniqueID, $player)
{
  $index = SearchAurasForUniqueID($uniqueID, $player);
  if($index == -1) $index = SearchItemsForUniqueID($uniqueID, $player);
  return $index;
}

function SearchAurasForUniqueID($uniqueID, $player)
{
  $auras = &GetAuras($player);
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if($auras[$i+6] == $uniqueID) return $i;
  }
  return -1;
}

function SearchItemsForUniqueID($uniqueID, $player)
{
  $items = &GetItems($player);
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if($items[$i+4] == $uniqueID) return $i;
  }
  return -1;
}

function SearchItemsForCard($cardID, $player)
{
  $items = &GetItems($player);
  $indices = "";
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if($items[$i] == $cardID)
    {
      if($indices != "") $indices .= ",";
      $indices .= $i;
    }
  }
  return $indices;
}

function SearchLandmarks($cardID)
{
  global $landmarks;
  for($i=0; $i<count($landmarks); $i+=LandmarkPieces())
  {
    if($landmarks[$i] == $cardID) return true;
  }
  return false;
}

function CountAura($cardID, $player)
{
  $auras = &GetAuras($player);
  $count = 0;
  for($i=0; $i<count($auras); $i+=AuraPieces())
  {
    if($auras[$i] == $cardID) ++$count;
  }
  return $count;
}


function GetItemIndex($cardID, $player)
{
  $items = &GetItems($player);
  $count = 0;
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if($items[$i] == $cardID) return $i;
  }
  return -1;
}

function CountItem($cardID, $player)
{
  $items = &GetItems($player);
  $count = 0;
  for($i=0; $i<count($items); $i+=ItemPieces())
  {
    if($items[$i] == $cardID) ++$count;
  }
  return $count;
}

function SearchArsenalReadyCard($player, $cardID)
{
  $arsenal = GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
  {
    if($arsenal[$i] != $cardID) continue;
    if($arsenal[$i+1] != "UP") continue;
    if($arsenal[$i+2] == 0) continue;
    return $i;
  }
  return -1;
}

function SearchArcaneReplacement($player, $zone)
{
  $cardList = "";
  switch($zone)
  {
    case "MYCHAR": $array = &GetPlayerCharacter($player); $count = CharacterPieces(); break;
    case "MYITEMS": $array = &GetItems($player); $count = ItemPieces(); break;
  }
  for($i=0; $i<count($array); $i += $count)
  {
    if($zone == "MYCHAR" && !IsCharacterAbilityActive($player, $i)) continue;
    $cardID = $array[$i];
    if(SpellVoidAmount($cardID) > 0 && IsCharacterActive($player, $i))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function CountCardOnChain($card1, $card2="", $card3="")
{
  global $chainLinks;
  $count = 0;
  for($i=0; $i<count($chainLinks); ++$i)
  {
    if($chainLinks[$i][2] == "1" && $chainLinks[$i][0] == $card1 || $chainLinks[$i][0] == $card2 || $chainLinks[$i][0] == $card3) ++$count;
  }
  return $count;
}

function SearchChainLinks($minPower=-1, $maxPower=-1, $cardType="")
{
  global $chainLinks;
  $links = "";
  for($i=0; $i<count($chainLinks); ++$i)
  {
    $power = AttackValue($chainLinks[$i][0]);
    $type = CardType($chainLinks[$i][0]);
    if($chainLinks[$i][2] == "1" && ($minPower == -1 || $power >= $minPower) && ($maxPower == -1 || $power <= $maxPower) && ($cardType == "" || $type == $cardType))
    {
      if($links != "") $links .= ",";
      $links .= $i;
    }
  }
  return $links;
}

function GetMZCardLink($player, $MZ)
{
  $params = explode("-", $MZ);
  $zoneDS = &GetMZZone($player, $params[0]);
  $index = $params[1];
  return CardLink($zoneDS[$index], $zoneDS[$index]);
}

function SearchMZ($player, $subparam)
{
  $rv = "";
  $otherPlayer = ($player == 1 ? 2 : 1);
  $zones = explode("|", $subparam);
  for($i=0; $i<count($zones); ++$i)
  {
    switch($zones[$i])
    {
      case "MYARS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchArsenal($player), $zones[$i])); break;
      case "THEIRARS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchArsenal($otherPlayer), $zones[$i])); break;
      case "MYALLY": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAllies($player), $zones[$i])); break;
      case "THEIRALLY": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAllies($otherPlayer), $zones[$i])); break;
      case "MYHAND": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchHand($player), $zones[$i])); break;
      case "THEIRHAND": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchHand($otherPlayer), $zones[$i])); break;
      case "MYDISCARD": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchDiscard($player), $zones[$i])); break;
      case "THEIRDISCARD": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchDiscard($otherPlayer), $zones[$i])); break;
      case "MYCHAR": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchCharacter($player), $zones[$i])); break;
      case "THEIRCHAR": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchCharacter($otherPlayer), $zones[$i])); break;
      case "MYITEMS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchItems($player), $zones[$i])); break;
      case "THEIRITEMS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchItems($otherPlayer), $zones[$i])); break;
      case "MYPERM": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchPermanents($player), $zones[$i])); break;
      case "THEIRPERM": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchPermanents($otherPlayer), $zones[$i])); break;
      case "MYAURAS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAura($player), $zones[$i])); break;
      case "THEIRAURAS": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchAura($otherPlayer), $zones[$i])); break;
      case "MYPITCH": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchPitch($player), $zones[$i])); break;
      case "THEIRPITCH": $rv = CombineSearches($rv, SearchMultiZoneFormat(SearchPitch($otherPlayer), $zones[$i])); break;
      default: break;
    }
  }
  return $rv;
}

//$searches is the following format:
//Each search is delimited by &, which means a set UNION
//Each search is the format <zone>:<condition 1>;<condition 2>,...
//Each condition is format <search parameter name>=<parameter value>
//Example: AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:maxAttack=3;type=AA");
function SearchMultizone($player, $searches)
{
  $unionSearches = explode("&", $searches);
  $rv = "";
  for($i=0; $i<count($unionSearches); ++$i)
  {
    $type=""; $subtype=""; $maxCost=-1; $minCost=-1; $class=""; $talent=""; $bloodDebtOnly=false; $phantasmOnly=false; $pitch=-1; $specOnly=false;
    $maxAttack=-1; $maxDef=-1; $frozenOnly=false;
    $searchArr = explode(":", $unionSearches[$i]);
    $zone = $searchArr[0];
    if(count($searchArr) > 1)//Means there are conditions
    {
      $conditions = explode(";", $searchArr[1]);
      for($j=0; $j<count($conditions); ++$j)
      {
        $condition = explode("=", $conditions[$j]);
        switch($condition[0])
        {
          case "type": $type = $condition[1]; break;
          case "subtype": $subtype = $condition[1]; break;
          case "maxCost": $maxCost = $condition[1]; break;
          case "minCost": $minCost = $condition[1]; break;
          case "class": $class = $condition[1]; break;
          case "talent": $talent = $condition[1]; break;
          case "bloodDebtOnly": $bloodDebtOnly = $condition[1]; break;
          case "phantasmOnly": $phantasmOnly = $condition[1]; break;
          case "pitch": $pitch = $condition[1]; break;
          case "specOnly": $specOnly = $condition[1]; break;
          case "maxAttack": $maxAttack = $condition[1]; break;
          case "maxDef": $maxDef = $condition[1]; break;
          case "frozenOnly": $frozenOnly = $condition[1]; break;
          default: break;
        }
      }
    }
    $searchPlayer = (substr($zone, 0, 2) == "MY" ? $player : ($player == 1 ? 2 : 1));
    $searchResult = "";
    switch($zone)
    {
      case "MYHAND": case "THEIRHAND": $searchResult = SearchHand($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYDISCARD": case "THEIRDISCARD": $searchResult = SearchDiscard($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYARS": case "THEIRARS": $searchResult = SearchArsenal($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYAURAS": case "THEIRAURAS": $searchResult = SearchAura($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYCHAR": case "THEIRCHAR": $searchResult = SearchCharacter($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYITEMS": case "THEIRITEMS": $searchResult = SearchItems($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYALLY": case "THEIRALLY": $searchResult = SearchAllies($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYPERM": case "THEIRPERM": $searchResult = SearchPermanents($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "MYPITCH": case "THEIRPITCH": $searchResult = SearchPitch($searchPlayer, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      case "CC": $searchResult = SearchCombatChain($type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly, $pitch, $specOnly, $maxAttack, $maxDef, $frozenOnly); break;
      default: break;
    }
    $searchResult = SearchMultiZoneFormat($searchResult, $zone);
    $rv = CombineSearches($rv, $searchResult);
  }
  return $rv;
}

function IntimidateCount($player)
{
  $otherPlayer = ($player == 1 ? 2 : 1);
  $banish = &GetBanish($otherPlayer);
  $count = 0;
  for($i=0; $i<count($banish); $i+=BanishPieces())
  {
    if($banish[$i+1] == "INT") ++$count;
  }
  return $count;
}

function FrozenCount($player)
{
  $numFrozen = 0;
  $char = &GetPlayerCharacter($player);
  for($i=0; $i<count($char); $i+=CharacterPieces())
    if($char[$i+8] == "1")
      ++$numFrozen;
  $allies = &GetAllies($player);
  for($i=0; $i<count($allies); $i+=AllyPieces())
    if($allies[$i+3] == "1")
      ++$numFrozen;
  $arsenal = &GetArsenal($player);
  for($i=0; $i<count($arsenal); $i+=ArsenalPieces())
    if($arsenal[$i+4] == "1")
      ++$numFrozen;
  return $numFrozen;
}

?>
