<?php

  echo(GenerateELEBooster());

  function GenerateELEBooster()
  {
    $booster = "";
    //1-4 Elemental common
    for($i=0; $i<4; ++$i)
    {
      $num = rand(1,4);
      switch($num)
      {
        case 1: $booster .= ELERandomElementalCommon() . " "; break;
        case 2: $booster .= ELERandomEarthCommon() . " "; break;
        case 3: $booster .= ELERandomIceCommon() . " "; break;
        case 4: $booster .= ELERandomLightningCommon() . " "; break;
      }
    }
    //5 Elemental common
    $num = rand(1,7);
    switch($num)
    {
      case 1: $booster .= ELERandomElementalCommon() . " "; break;
      case 2: $booster .= ELERandomEarthCommon() . " "; break;
      case 3: $booster .= ELERandomIceCommon() . " "; break;
      case 4: $booster .= ELERandomLightningCommon() . " "; break;
      case 5: $booster .= ELERandomGuardianCommon(true) . " "; break;
      case 6: $booster .= ELERandomRangerCommon(true) . " "; break;
      case 7: $booster .= ELERandomRunebladeCommon(true) . " "; break;
    }
    //6 Rare
    $booster .= ELERandomRare() . " ";
    //7 Rare+
    $num = rand(1,4);
    if($num == 4)
    {
      $booster .= ELERandomMajestic() . " ";
    }
    else
    {
      $booster .= ELERandomRare() . " ";
    }
    //8 RF Common+
    $num = rand(1,24);
    if($num == 24) $booster .= ELERandomMajestic() . " ";
    else if($num > 16) $booster .= ELERandomRare() . " ";
    else $booster .= ELERandomCommon() . " ";
    //9 Amulet + common equip
    $booster .= ELERandomEquipment() . " ";
    //10-15 2x each class common
    $booster .= ELERandomGuardianCommon() . " ";
    $booster .= ELERandomGuardianCommon() . " ";
    $booster .= ELERandomRangerCommon() . " ";
    $booster .= ELERandomRangerCommon() . " ";
    $booster .= ELERandomRunebladeCommon() . " ";
    $booster .= ELERandomRunebladeCommon();
    return $booster;
  }

  function ELERandomElementalCommon()
  {
    $rv = "ELE";
    $number = 94 + rand(0, 14);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function ELERandomEarthCommon()
  {
    $rv = "ELE";
    $number = 128 + rand(0, 14);
    $rv .= $number;
    return $rv;
  }

  function ELERandomIceCommon()
  {
    $rv = "ELE";
    $number = 157 + rand(0, 14);
    $rv .= $number;
    return $rv;
  }

  function ELERandomLightningCommon()
  {
    $rv = "ELE";
    $number = 186 + rand(0, 14);
    $rv .= $number;
    return $rv;
  }

  function ELERandomGuardianCommon($elementalOnly=false)
  {
    $rv = "ELE";
    $upper = $elementalOnly ? 17 : 20;
    $number = rand(0, $upper);
    $number = ($number >= 18 ? 209 + $number - 18 : $number + 13);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function ELERandomRangerCommon($elementalOnly=false)
  {
    $rv = "ELE";
    $upper = $elementalOnly ? 17 : 20;
    $number = rand(0, $upper);
    $number = ($number >= 18 ? 219 + $number - 18 : $number + 44);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function ELERandomRunebladeCommon($elementalOnly=false)
  {
    $rv = "ELE";
    $upper = $elementalOnly ? 17 : 20;
    $number = rand(0, $upper);
    $number = ($number >= 18 ? 230 + $number - 18 : $number + 73);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function ELERandomEquipment()
  {
    $number = rand(1, 13);
    switch($number)
    {
      case 1: return "ELE116";
      case 2: return "ELE145";
      case 3: return "ELE174";
      case 4: return "ELE204";
      case 5: return "ELE214";
      case 6: return "ELE225";
      case 7: return "ELE233";
      case 8: return "ELE234";
      case 9: return "ELE235";
      case 10: return "ELE236";
      case 11: return "ELE143";
      case 12: return "ELE172";
      case 13: return "ELE201";
    }
  }

  function ELERandomCommon()
  {
    $num = rand(1,7);
    switch($num)
    {
      case 1: return ELERandomElementalCommon();
      case 2: return ELERandomEarthCommon();
      case 3: return ELERandomIceCommon();
      case 4: return ELERandomLightningCommon();
      case 5: return ELERandomGuardianCommon();
      case 6: return ELERandomRangerCommon();
      case 7: return ELERandomRunebladeCommon();
    }
  }

  function ELERandomRare()
  {
    $number = rand(1, 54);
    switch($number)
    {
      case 1: return "ELE007";
      case 2: return "ELE008";
      case 3: return "ELE009";
      case 4: return "ELE010";
      case 5: return "ELE011";
      case 6: return "ELE012";
      case 7: return "ELE038";
      case 8: return "ELE039";
      case 9: return "ELE040";
      case 10: return "ELE041";
      case 11: return "ELE042";
      case 12: return "ELE043";
      case 13: return "ELE067";
      case 14: return "ELE068";
      case 15: return "ELE069";
      case 16: return "ELE070";
      case 17: return "ELE071";
      case 18: return "ELE072";
      case 19: return "ELE119";
      case 20: return "ELE120";
      case 21: return "ELE121";
      case 22: return "ELE122";
      case 23: return "ELE123";
      case 24: return "ELE124";
      case 25: return "ELE124";
      case 26: return "ELE126";
      case 27: return "ELE127";
      case 28: return "ELE148";
      case 29: return "ELE149";
      case 30: return "ELE150";
      case 31: return "ELE151";
      case 32: return "ELE152";
      case 33: return "ELE153";
      case 34: return "ELE154";
      case 35: return "ELE155";
      case 36: return "ELE156";
      case 37: return "ELE177";
      case 38: return "ELE178";
      case 39: return "ELE179";
      case 40: return "ELE180";
      case 41: return "ELE181";
      case 42: return "ELE182";
      case 43: return "ELE183";
      case 44: return "ELE184";
      case 45: return "ELE185";
      case 46: return "ELE206";
      case 47: return "ELE207";
      case 48: return "ELE208";
      case 49: return "ELE216";
      case 50: return "ELE217";
      case 51: return "ELE218";
      case 52: return "ELE227";
      case 53: return "ELE228";
      case 54: return "ELE229";
    }
  }

  function ELERandomMajestic()
  {
    $number = rand(1, 27);
    switch($number)
    {
      case 1: return "ELE003";
      case 2: return "ELE004";
      case 3: return "ELE005";
      case 4: return "ELE006";
      case 5: return "ELE034";
      case 6: return "ELE035";
      case 7: return "ELE036";
      case 8: return "ELE037";
      case 9: return "ELE064";
      case 10: return "ELE065";
      case 11: return "ELE066";
      case 12: return "ELE091";
      case 13: return "ELE092";
      case 14: return "ELE093";
      case 15: return "ELE112";
      case 16: return "ELE113";
      case 17: return "ELE114";
      case 18: return "ELE117";
      case 19: return "ELE118";
      case 20: return "ELE146";
      case 21: return "ELE147";
      case 22: return "ELE175";
      case 23: return "ELE176";
      case 24: return "ELE205";
      case 25: return "ELE215";
      case 26: return "ELE223";
      case 27: return "ELE226";
    }
  }

?>