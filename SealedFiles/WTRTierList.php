<?php

  function GetWTRPick($cards)
  {
    $minIndex = -1;
    $minTier = 999;
    for($i=0; $i<count($cards); ++$i)
    {
      $tier = GetWTRTier($cards[$i]);
      if($tier < $minTier) { $minTier = $tier; $minIndex = $i; }
    }
    if($minIndex == -1) $minIndex = rand(0, count($cards)-1);
    return $cards[$minIndex];
  }

  function GetWTRTier($cardID)
  {
    switch($cardID)
    {
      case "WTR159": return 1;
      case "WTR007": case "WTR043": case "WTR082": case "WTR118": case "WTR119": return 2;
      case "WTR152": case "WTR153": case "WTR154": case "WTR155": case "WTR156": case "WTR157": case "WTR158": return 3;
      case "WTR160": case "WTR120": case "WTR044": case "WTR121": case "WTR173": return 4;
      case "WTR092": case "WTR215": return 5;
      case "WTR017": case "WTR048": case "WTR167": case "WTR129": return 6;

      case "WTR049": case "WTR093": case "WTR018": case "WTR131": case "WTR191": case "WTR182": return 10;

      case "WTR218": case "WTR206": case "WTR209": return 12;
      case "WTR188": case "WTR189": case "WTR190": return 13;
      case "WTR132": case "WTR060": case "WTR101": case "WTR141": case "WTR183": return 15;
      case "WTR216": case "WTR212": case "WTR213": case "WTR214": return 16;
      case "WTR203": case "WTR204": case "WTR205": return 17;
      case "WTR005": case "WTR042": case "WTR080": case "WTR117": return 18;
      default: return 999;
    }
  }

?>