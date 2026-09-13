<?php

if (!function_exists("GetArray")) {
  function GetArray($handler)
  {
    if (!$handler) return false;
    $line = trim(fgets($handler));
    if ($line == "") return [];
    return explode(" ", $line);
  }
}

if (!function_exists("UnlockGamefile")) {
  function UnlockGamefile()
  {
    global $gameFileHandler;
    fclose($gameFileHandler);
  }
}
