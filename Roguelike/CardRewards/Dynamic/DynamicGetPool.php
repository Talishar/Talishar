<?php

function DynamicGetPool($tag)
{
  switch($tag)
  {
    case "Deck":
      $deck = &GetZone(1, "Deck");
      return $deck;
    case "PhoenixFlame": return array("UPR096", "UPR097", "UPR101", "UPR057", "UPR058", "UPR059", "UPR048");
    case "Wide": return array("WTR098", "WTR099", "WTR100", "EVR041", "EVR042", "EVR043", "UPR160");
    case "CrouchingTiger": return array("DYN062", "DYN063", "DYN064", "DYN050", "DYN051", "DYN052", "DYN049");
    case "Sword": return array("DYN079", "DYN080", "DYN081", "DYN076", "DYN077", "DYN078", "CRU082");
    case "Nimble": return array("WTR218", "WTR219", "WTR220", "ELE183", "ELE184", "ELE185", "WTR159");
    case "Sloggish": return array("WTR221", "WTR222", "WTR223", "CRU010", "CRU011", "CRU012", "EVR002");
    case "Banish": return array("MON168", "MON169", "MON170", "UPR054", "UPR055", "UPR056", "MON124");
    case "Stealth": return array("OUT024", "OUT025", "OUT026", "OUT015", "OUT016", "OUT017", "OUT012");
  }
}

 ?>
