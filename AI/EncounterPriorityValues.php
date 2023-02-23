<?php

function GetPriority($cardID, $heroID, $type)
{
  switch($heroID)
  {
    /*case "heroID":
      switch($cardID)
      {
        case "cardID": $priority = array(Block, Action, ArsenalAction, Reaction, ArsenalReaction, Pitch, ToArsenal); return $priority[$type]; //NOTE: Check documentation
      }*/
      case "ROGUE001":
        switch($cardID)
        {
          case "ROGUE002": $priority = array(0, 0.2, 0, 0, 0, 0, 0); return $priority[$type];
          case "MON284": $priority = array(2.3, 1.8, 1.8, 0, 0, 0.5, 1.8); return $priority[$type];
          case "MON285": $priority = array(2.5, 1.6, 1.6, 0, 0, 1.5, 1.6); return $priority[$type];
          case "MON286": $priority = array(2.7, 1.4, 1.4, 0, 0, 2.5, 1.4); return $priority[$type];
          case "UPR092": $priority = array(2.7, 1.4, 1.4, 0, 0, 0.5, 1.4); return $priority[$type];
          default: return 0;
        }
  }
}
?>
