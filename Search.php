<?php

function SearchDeck($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  $deck = &GetDeck($player);
  return SearchInner($deck, 1, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchHand($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  $hand = &GetHand($player);
  return SearchInner($hand, 1, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchPitch($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  $pitch = &GetPitch($player);
  return SearchInner($pitch, 1, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchDiscard($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  $discard = &GetDiscard($player);
  return SearchInner($discard, 1, $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchBanish($player, $type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  $banish = &GetBanish($player);
  return SearchInner($banish, BanishPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchCombatChain($type="", $subtype="", $maxCost=-1, $minCost=-1, $class="", $talent="", $bloodDebtOnly=false, $phantasmOnly=false)
{
  global $combatChain;
  return SearchInner($combatChain, CombatChainPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchInner(&$array, $count, $type, $subtype, $maxCost, $minCost, $class, $talents, $bloodDebtOnly, $phantasmOnly)
{
  $cardList = "";
  if(!is_array($talents)) $talents = ($talents == "" ? [] : explode(",", $talents));
  for($i=0; $i<count($array); $i += $count)
  {
    $cardID = $array[$i];
    if(count($talents) > 0)
    {
      $talentMatch = 0;
      $cardTalents = explode(",", CardTalent($cardID));
      for($j=0; $j<count($talents); ++$j)
      {
        for($k=0; $k<count($cardTalents); ++$k) { if($talents[$j] == $cardTalents[$k]) $talentMatch = 1; }
      }
    }
    if(($type == "" || CardType($cardID) == $type) && ($subtype == "" || CardSubType($cardID) == $subtype) && ($maxCost == -1 || CardCost($cardID) <= $maxCost) && ($minCost == -1 || CardCost($cardID) >= $minCost) && ($class == "" || CardClass($cardID) == $class) && (count($talents) == 0 || $talentMatch))
    {
      if($bloodDebtOnly && !HasBloodDebt($cardID)) continue;
      if($phantasmOnly && !HasPhantasm($cardID)) continue;
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

//Parses DQ subparams into search format
function SearchLayerDQ($param)
{
  global $layers;
  $type=""; $subtype=""; $maxCost=-1; $minCost=-1; $class=""; $talent=""; $bloodDebtOnly=false; $phantasmOnly=false;
  $paramArray = explode("-", $param);
  for($i=0; $i < count($paramArray); $i+=2)
  {
    if($paramArray[$i] == "TYPE") $type = $paramArray[$i+1];
    else if($paramArray[$i] == "MAXCOST") $maxCost = $paramArray[$i+1];
  }
  return SearchInner($layers, LayerPieces(), $type, $subtype, $maxCost, $minCost, $class, $talent, $bloodDebtOnly, $phantasmOnly);
}

function SearchMyDeck($type="", $subtype="", $maxCost=-1, $minCost=-1, $class="")
{
  global $myDeck;
  $cardList = "";
  for($i=0; $i<count($myDeck); ++$i)
  {
    if(($type == "" || CardType($myDeck[$i]) == $type) && ($subtype == "" || CardSubType($myDeck[$i]) == $subtype) && ($maxCost == -1 || CardCost($myDeck[$i]) <= $maxCost) && ($minCost == -1 || CardCost($myDeck[$i]) >= $minCost) && ($class == "" || CardClass($myDeck[$i]) == $class))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMyDeckForCard($card1, $card2="", $card3="")
{
  global $myDeck;
  $cardList = "";
  for($i=0; $i<count($myDeck); ++$i)
  {
    $id = $myDeck[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMainDeckForCard($card1, $card2="", $card3="")
{
  global $mainDeck;
  $cardList = "";
  for($i=0; $i<count($mainDeck); ++$i)
  {
    $id = $mainDeck[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchTheirDeck($type="", $subtype="", $maxCost=-1)
{
  global $theirDeck;
  $cardList = "";
  for($i=0; $i<count($theirDeck); ++$i)
  {
    if(($type == "" || CardType($theirDeck[$i]) == $type) && ($subtype == "" || CardSubType($theirDeck[$i]) == $subtype) && ($maxCost == -1 || CardCost($theirDeck[$i]) <= $maxCost))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMyHand($type="", $subtype="", $maxCost=-1, $minCost=-1, $maxAttack=-1)
{
  global $myHand;
  $cardList = "";
  for($i=0; $i<count($myHand); ++$i)
  {
    if(($type == "" || CardType($myHand[$i]) == $type) && ($subtype == "" || CardSubType($myHand[$i]) == $subtype) && ($maxCost == -1 || CardCost($myHand[$i]) <= $maxCost) && ($minCost == -1 || CardCost($myHand[$i]) >= $minCost) && ($maxAttack == -1 || AttackValue($myHand[$i]) <= $maxAttack))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMainHand($type="", $subtype="", $maxCost=-1, $minCost=-1, $class="")
{
  global $mainHand, $myHand, $theirHand, $mainPlayerGamestateBuilt, $mainPlayer, $playerID;
  $hand = $mainPlayerGamestateBuilt ? $mainHand : ($mainPlayer == $playerID ? $myHand : $theirHand);
  $cardList = "";
  for($i=0; $i<count($hand); ++$i)
  {
    if(($type == "" || CardType($hand[$i]) == $type) && ($subtype == "" || CardSubType($hand[$i]) == $subtype) && ($maxCost == -1 || CardCost($hand[$i]) <= $maxCost) && ($minCost == -1 || CardCost($hand[$i]) >= $minCost) && ($class == "" || CardClass($hand[$i]) == $class))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMyDiscard($type="", $subtype="", $maxCost=-1, $minCost=-1)
{
  global $myDiscard;
  $cardList = "";
  for($i=0; $i<count($myDiscard); ++$i)
  {
    $cID = $myDiscard[$i];
    if(($type == "" || CardType($cID) == $type) && ($subtype == "" || CardSubType($cID) == $subtype) && ($maxCost == -1 || CardCost($cID) <= $maxCost) && ($minCost == -1 || CardCost($cID) >= $minCost))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMainDiscard($type="", $subtype="", $maxCost=-1, $minCost=-1)
{
  global $mainDiscard;
  $cardList = "";
  for($i=0; $i<count($mainDiscard); ++$i)
  {
    $cID = $mainDiscard[$i];
    if(($type == "" || CardType($cID) == $type) && ($subtype == "" || CardSubType($cID) == $subtype) && ($maxCost == -1 || CardCost($cID) <= $maxCost) && ($minCost == -1 || CardCost($cID) >= $minCost))
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchMyDiscardForCard($card1, $card2="", $card3="")
{
  global $myDiscard;
  $cardList = "";
  for($i=0; $i<count($myDiscard); ++$i)
  {
    $id = $myDiscard[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchTheirDiscardForCard($card1, $card2="", $card3="")
{
  global $theirDiscard;
  $cardList = "";
  for($i=0; $i<count($theirDiscard); ++$i)
  {
    $id = $theirDiscard[$i];
    if($id == $card1 || $id == $card2 || $id == $card3)
    {
      if($cardList != "") $cardList = $cardList . ",";
      $cardList = $cardList . $i;
    }
  }
  return $cardList;
}

function SearchEquipNegCounter(&$character)
{
  $equipList = "";
  for($i=0; $i<count($character); $i += CharacterPieces())
  {
    if(CardType($character[$i]) == "E" && $character[$i+4] < 0)
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

function SearchCount($search)
{
  if($search == "") return 0;
  return count(explode(",", $search));
}

function SearchMultizoneFormat($search, $zone)
{
  $searchArr = explode(",", $search);
  for($i=0; $i<count($searchArr); ++$i)
  {
    $searchArr[$i] = $zone . "-" . $searchArr[$i];
  }
  return implode(",", $searchArr);
}

function SearchCurrentTurnEffects($cardID, $player)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i+1] == $player) return true;
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

function SearchMainAuras($cardID)
{
  global $mainAuras, $defAuras;
  for($i=0; $i<count($mainAuras); $i += AuraPieces())
  {
    if($mainAuras[$i] == $cardID) return true;
  }
  for($i=0; $i<count($defAuras); $i += AuraPieces())
  {
    if($defAuras[$i] == $cardID) return true;
  }
  return false;
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
    if(CardType($character[$i]) == "E" && ($maxBlock == -1 || (BlockValue($character[$i]) + $character[$i+4]) <= $maxBlock))
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

?>

