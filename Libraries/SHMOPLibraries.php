<?php

function WriteCache($name, $data)
{
  //DeleteCache($name);
  $serData = serialize($data);
  $id = shmop_open($name, "c", 0644, 255);
  if($id === false) $id = shmop_open($name, "w", 0644, 255);
  $rv = shmop_write($id, $serData, 0);
}

function ReadCache($name)
{
  $id = shmop_open($name, "a", 0, 0);
  if(!$id) return "";
  $data = shmop_read($id, 0, shmop_size($id));
  $data = preg_replace_callback( '!s:(\d+):"(.*?)";!', function($match) {
    return ($match[1] == strlen($match[2])) ? $match[0] : 's:' . strlen($match[2]) . ':"' . $match[2] . '";';
}, $data );
  return unserialize($data);
}

function DeleteCache($name)
{
    $id=shmop_open($name, "w", 0777, 1);
    if($id)
    {
      shmop_delete($id);
    }
}

?>
