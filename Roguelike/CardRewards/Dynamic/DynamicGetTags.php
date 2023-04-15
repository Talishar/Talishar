<?php

function DynamicGetCardTags($cardID, $specialTag = "-")
{
  return DynamicTestGetTags($cardID, $specialTag);
  $set = CardSet($cardID);
  /*if($set == "WTR") return WTRGetTags($cardID, $specialTag);
  else if($set == "ARC") return ARCGetTags($cardID, $specialTag);
  else if($set == "CRU") return CRUGetTags($cardID, $specialTag);
  else if($set == "MON") return MONGetTags($cardID, $specialTag);
  else if($set == "ELE") return ELEGetTags($cardID, $specialTag);
  else if($set == "EVR") return EVRGetTags($cardID, $specialTag);
  else if($set == "UPR") return UPRGetTags($cardID, $specialTag);
  else if($set == "DVR") return DVRGetTags($cardID, $specialTag);
  else if($set == "RVD") return RVDGetTags($cardID, $specialTag);
  else if($set == "DYN") return DYNGetTags($cardID, $specialTag);
  else if($set == "OUT") return OUTGetTags($cardID, $specialTag);*/
}

function DynamicTestGetTags($cardID, $specialTag)
{
  switch($cardID)
  {
    case "DYN062": return array("Banish", "Wide", "CrouchingTiger");
  }
}

function CreateTag($tag, $weight, $ignore = false)
{
  $rv = new CardTag($tag, $weight, $ignore);
  return $rv;
}

 ?>
