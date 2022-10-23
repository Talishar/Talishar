<?php

  function EncounterDescription($encounter, $subphase)
  {
    switch($encounter)
    {
      case 1:
        if($subphase == "Fight") return "You're attacked by a Woottonhog.";
        else if($subphase == "AfterFight") return "You defeated the Woottonhog.";
      case 2:
        return "You found a campfire. Choose what you want to do.";
      case 3:
        if($subphase == "BeforeFight") return "You're attacked by a Ravenous Rabble.";
        else if($subphase == "AfterFight") return "You defeated the Ravenous Rabble.";
      case 4:
        return "You found a battlefield. Choose what you want to do.";
      case 5:
        if($subphase == "BeforeFight") return "You're attacked by a Barraging Brawnhide.";
        else if($subphase == "AfterFight") return "You defeated the Barraging Brawnhide.";
      default: return "No encounter text.";
    }
  }
  
?>
