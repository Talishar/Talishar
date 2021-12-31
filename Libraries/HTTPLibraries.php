<?php

function TryGET($key, $default="")
{
  return isset($_GET[$key]) ? $_GET[$key] : $default;
}

function IsGameNameValid($gameName)
{
  return is_numeric($gameName);
}

?>

