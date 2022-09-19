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
?>
