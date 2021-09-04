<?php


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

function SearchMyDeck($filterFunction)
{
  return array_values(array_filter(global $myDeck, $filterFunction));
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

function CombineSearches($search1, $search2)
{
  if($search2 == "") return $search1;
  else if($search1 == "") return $search2;
  return $search1 . "," . $search2;
}

function SearchCount($search)
{
  return count(explode(",", $search));
}

function SearchCurrentTurnEffects($cardID, $player)
{
  global $currentTurnEffects;
  for($i=0; $i<count($currentTurnEffects); $i+=CurrentTurnEffectPieces())
  {
    if($currentTurnEffects[$i] == $cardID && $currentTurnEffects[$i+1] == $player) return true;
  }
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

?>
