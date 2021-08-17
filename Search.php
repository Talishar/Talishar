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

function SearchMyHand($type="", $subtype="", $maxCost=-1, $minCost=-1)
{
  global $myHand;
  $cardList = "";
  for($i=0; $i<count($myHand); ++$i)
  {
    if(($type == "" || CardType($myHand[$i]) == $type) && ($subtype == "" || CardSubType($myHand[$i]) == $subtype) && ($maxCost == -1 || CardCost($myHand[$i]) <= $maxCost) && ($minCost == -1 || CardCost($myHand[$i]) >= $minCost))
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
    if(($type == "" || CardType($myDiscard[$i]) == $type) && ($subtype == "" || CardSubType($myDiscard[$i]) == $subtype) && ($maxCost == -1 || CardCost($myDiscard[$i]) <= $maxCost) && ($minCost == -1 || CardCost($myDiscard[$i]) >= $minCost))
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

?>

