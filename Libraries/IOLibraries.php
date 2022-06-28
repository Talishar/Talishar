<?php

  function GetArray($handler)
  {
    $line = trim(fgets($handler));
    if($line=="") return [];
    return explode(" ", $line);
  }

?>
