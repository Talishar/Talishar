<?php

function CardName($cardID)
{
    $arr = str_split($cardID, 3);
    if(count($arr) < 2) return "";
    $set = $arr[0];
    if($set != "ROG" && $set != "DUM")
    {
      $number = intval(substr($cardID, 3));
      if($number < 400) return GeneratedCardName($cardID);
    }
    return "";
	}
