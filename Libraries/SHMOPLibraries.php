<?php

function WriteCache($name, $data)
{
  //DeleteCache($name);
  $serData = serialize($data);
  $id = shmop_open($name, "c", 0644, 128);
  $rv = shmop_write($id, $serData, 0);
}

function ReadCache($name)
{
  $id = shmop_open($name, "a", 0, 0);
  $data = shmop_read($id, 0, shmop_size($id));
  $data = preg_replace_callback( '!s:(\d+):"(.*?)";!', function($match) {
    return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
}, $data );
  return unserialize($data);
}

function DeleteCache($name)
{
    $id=shmop_open($name, "w", 0644, 128);
    if($id)
    {
      shmop_delete($id);
      shmop_close($id);
    }
}

function SetCachePiece($name, $piece, $value)
{
  $piece -= 1;
  $cacheVal = ReadCache($name);
  $cacheArray = explode("!", $cacheVal);
  $cacheArray[$piece] = $value;
  WriteCache($name, implode("!", $cacheArray));
}

function GetCachePiece($name, $piece)
{
  $piece -= 1;
  $cacheVal = ReadCache($name);
  $cacheArray = explode("!", $cacheVal);
  return $cacheArray[$piece];
}

?>
