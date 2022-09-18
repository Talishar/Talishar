<?php
function DelimStringContains($str, $find)
{
  $arr = explode(",", $str);
  for($i=0; $i<count($arr); ++$i)
  {
    if($arr[$i] == $find) return true;
  }
  return false;
}

function GetRandom($low=-1, $high=-1)
{
  if($low == -1) return rand();
  return rand($low, $high);
}
?>
