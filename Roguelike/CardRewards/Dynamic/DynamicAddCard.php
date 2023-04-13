<?php

function AddCard($cardID)
{
  $cardTags = DynamicGetCardTags($cardID);
  $encounter = &GetZone(1, "Encounter");

}

function DynamicGetEncounterIndex($tag, $array)
{
  for($i = 0; $i < count($array); $i++)
  {
    if($array[$i]->tag == $tag) return $i;
  }
  return -1;
}
 ?>
