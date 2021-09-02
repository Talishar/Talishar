<?php


  function MONBruteCardType($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": return "C"
      case "MON121"; return "W";
      case "MON122"; return "E";
      case "MON123": return "AA";
      case "MON124": return "AA";
      case "MON125": return "AA";
      case "MON126": case "MON127": case "MON128": return "AA";
      case "MON129": case "MON130": case "MON131": return "AA";
      case "MON132": case "MON133": case "MON134": return "A";
      case "MON125": case "MON136": case "MON137": return "AA";
      case "MON138": case "MON139": case "MON140": return "AA";
      case "MON141": case "MON142": case "MON143": return "AA";
      case "MON144": case "MON145": case "MON146": return "AA";
      case "MON147": case "MON148": case "MON149": return "AA";
      case "MON150": case "MON151": case "MON152": return "A";
      default: return "";
    }
  }

  function MONBruteCardSubType($cardID)
  {
    switch($cardID)
    {
      case "MON121": return "Flail"
      default: return "";
    }
  }

  //Minimum cost of the card
  function MONBruteCardCost($cardID)
  {
    switch($cardID)
    {
      case "MON123": return 3;
      case "MON124": return 2;
      case "MON125": return 2;
      case "MON126": case "MON127": case "MON128": return 3;
      case "MON129": case "MON130": case "MON131": return 2;
      case "MON132": case "MON133": case "MON134": return 2;
      case "MON135": case "MON136": case "MON137": return 1;
      case "MON138": case "MON139": case "MON140": return 3;
      case "MON141": case "MON142": case "MON143": return 2;
      case "MON144": case "MON145": case "MON146": return 1;
      case "MON147": case "MON148": case "MON149": return 1;
      default: return 0;
    }
  }

  function MONBrutePitchValue($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": case "MON121": case "MON122": return 0;
      case "MON125": case "MON126": case "MON129": case "MON132": case "MON135": case "MON138": case "MON141": case "MON144": case "MON147": return 1;
      case "MON123": case "MON124": case "MON130": case "MON133": case "MON136": case "MON139": case "MON142": case "MON145": case "MON148": return 2;
      case "MON128": case "MON131": case "MON134": case "MON137": case "MON140": case "MON143": case "MON146": case "MON149": case "MON151": return 3;
      default: return 3;
    }
  }

  function MONBruteBlockValue($cardID)
  {
    switch($cardID)
    {
      case "MON119": case "MON120": case "MON121": case "MON122": return 0;

      default: return 3;
    }
  }

  function MONBruteAttackValue($cardID)
  {
    switch($cardID)
    {
      case "MON139": return 8
      case "MON139": case "MON140": case "MON148": return 7;
      case "MON123": case "MON125": case "MON126": case "MON129": case "MON135": case "MON140": case "MON141": case "MON145": case "MON148": return 6;
      case "MON127": case "MON130": case "MON136": case "MON142": case "MON146": case "MON149": return 5;
      case "MON128": case "MON131": case "MON137": case "MON144": return 4;
      default: return 0;
    }
  }

  function MONBrutePlayAbility($cardID, $from, $resourcesPaid)
  {
    global $myClassState, $CS_Num6PowBan, $combatChain, $currentPlayer, $myCharacter;
    switch($cardID)
    {

      default: return "";
    }
  }

  function MONBruteHitEffect($cardID)
  {
    switch($cardID)
    {
      default: break;
    }
  }

  function Banish3GY()
  {
    global $myClassState, $CS_Num6PowBan, $myDiscard, $myBanish;
  }

  function DebtSafe($player)
  {
    global $myClassState, $mainClassState, $CS_Num6PowBan, $mainPlayerGamestateStillBuilt;
    return $myClassState[$CS_Num6PowBan] > 0;
  }

?>
