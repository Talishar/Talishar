<?php

  function &GetZone($player, $zoneName)
  {
    $varName = "p" . $player . $zoneName;
    global $$varName;
    return $$varName;
  }

  function GetZoneCount($player, $zoneName, $key)
  {
    $count = 0;
    $zone = &GetZone($player, $zoneName);
    for($i=0; $i<count($zone); ++$i)
    {
      if($zone[$i] == $key) ++$count;
    }
    return $count;
  }

?>