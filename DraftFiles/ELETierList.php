<?php

  function GetELEPick($cards)
  {
    $minIndex = -1;
    $minTier = 999;
    for($i=0; $i<count($cards); ++$i)
    {
      $tier = GetELETier($cards[$i]);
      if($tier < $minTier) { $minTier = $tier; $minIndex = $i; }
    }
    if($minIndex == -1) $minIndex = rand(0, count($cards)-1);
    return $cards[$minIndex];
  }

  function GetELETier($cardID)
  {
    switch($cardID)
    {
      case "ELE003": case "ELE034": case "ELE223": return 1;
      case "ELE145": case "ELE214": case "ELE234": case "ELE174": return 2;
      case "ELE235": case "ELE236": return 5;
      //Earth
      case "ELE117": return 1;
      case "ELE122": case "ELE137": return 3;
      case "ELE128": case "ELE138": return 4;
      case "ELE116": case "ELE129": case "ELE130": return 5;
      //Ice
      case "ELE151": case "ELE154": return 3;
      case "ELE148": case "ELE152": case "ELE157": return 4;
      case "ELE149": case "ELE153": case "ELE158": case "ELE160": case "ELE161": case "ELE162": return 5;
      //Lightning
      case "ELE112": return 1;
      case "ELE186": case "ELE198": case "ELE183": return 3;
      case "ELE180": case "ELE187": case "ELE188": case "ELE189": case "ELE199": return 4;
      case "ELE192": case "ELE189": case "ELE195": case "ELE200": return 5;
      //Guardian
      case "ELE004": case "ELE205": return 9;
      case "ELE005": case "ELE006": case "ELE013": case "ELE024": return 10;
      case "ELE010": case "ELE018": case "ELE019": return 11;
      case "ELE016": case "ELE011": case "ELE012": case "ELE209": case "ELE211": return 12;
      //Ranger
      case "ELE219": case "ELE036": case "ELE037": return 9;
      case "ELE220": case "ELE216": case "ELE047": case "ELE050": case "ELE059": return 10;
      case "ELE221": case "ELE053": return 11;
      //Runebade
      case "ELE225": return 5;
      case "ELE227": return 7;
      case "ELE228": return 8;
      case "ELE229": case "ELE230": case "ELE076": return 9;
      case "ELE085": case "ELE086": case "ELE231": case "ELE082": case "ELE077": return 10;
      case "ELE087": case "ELE064": case "ELE070": case "ELE083": return 11;
      default: return 999;
    }
  }

?>