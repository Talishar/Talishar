<?php

  echo(GenerateWTRBooster());

  function GenerateWTRBooster()
  {
    $booster = "";
    //1-4 Generic common
    for($i=0; $i<4; ++$i)
    {
      $booster .= WTRRandomGenericCommon() . " ";
    }
    //5 Common equip
    $booster .= WTRRandomEquipment() . " ";
    //6 Rare
    $booster .= WTRRandomRare() . " ";
    //7 Rare+
    $num = rand(1,4);
    if($num == 4)
    {
      $booster .= WTRRandomMajestic() . " ";
    }
    else
    {
      $booster .= WTRRandomRare() . " ";
    }
    //8 RF Common+
    $num = rand(1,24);
    if($num == 24) $booster .= WTRRandomMajestic() . " ";
    else if($num > 16) $booster .= WTRRandomRare() . " ";
    else $booster .= WTRRandomCommon() . " ";
    //9-15 2x each class common
    $minor1 = (rand(1,2) == 1 ? 1 : 0);
    if($minor1 == 0)
    {
      $booster .= WTRRandomGuardianCommon() . " ";
      $booster .= WTRRandomBruteCommon() . " ";
      $booster .= WTRRandomGuardianCommon() . " ";
    }
    else
    {
      $booster .= WTRRandomBruteCommon() . " ";
      $booster .= WTRRandomGuardianCommon() . " ";
      $booster .= WTRRandomBruteCommon() . " ";
    }
    $minor2 = (rand(1,2) == 1 ? 3 : 2);
    $mid = (rand(1,2) == 1 ? $minor1 : $minor2);
    switch($mid)
    {
      case 0: $booster .= WTRRandomBruteCommon() . " "; break;
      case 1: $booster .= WTRRandomGuardianCommon() . " "; break;
      case 2: $booster .= WTRRandomNinjaCommon() . " "; break;
      case 3: $booster .= WTRRandomWarriorCommon() . " "; break;
    }
    if($minor1 == 2)
    {
      $booster .= WTRRandomWarriorCommon() . " ";
      $booster .= WTRRandomNinjaCommon() . " ";
      $booster .= WTRRandomWarriorCommon();
    }
    else
    {
      $booster .= WTRRandomNinjaCommon() . " ";
      $booster .= WTRRandomWarriorCommon() . " ";
      $booster .= WTRRandomNinjaCommon();
    }
    return $booster;
  }

  function WTRRandomGenericCommon()
  {
    $rv = "WTR";
    $number = 176 + rand(0, 47);
    $rv .= $number;
    return $rv;
  }

  function WTRRandomBruteCommon()
  {
    $rv = "WTR";
    $number = 20 + rand(0, 16);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function WTRRandomGuardianCommon()
  {
    $rv = "WTR";
    $number = 57 + rand(0, 17);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function WTRRandomNinjaCommon()
  {
    $rv = "WTR";
    $number = 95 + rand(0, 17);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function WTRRandomWarriorCommon()
  {
    $rv = "WTR";
    $number = 132 + rand(0, 17);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function WTRRandomEquipment()
  {
    $number = rand(1, 12);
    switch($number)
    {
      case 1: return "WTR005";
      case 2: return "WTR042";
      case 3: return "WTR080";
      case 4: return "WTR117";
      case 5: return "WTR151";
      case 6: return "WTR152";
      case 7: return "WTR153";
      case 8: return "WTR154";
      case 9: return "WTR155";
      case 10: return "WTR156";
      case 11: return "WTR157";
      case 12: return "WTR158";
    }
  }

  function WTRRandomCommon()
  {
    $num = rand(1,5);
    switch($num)
    {
      case 1: return WTRRandomGenericCommon();
      case 2: return WTRRandomBruteCommon();
      case 3: return WTRRandomGuardianCommon();
      case 4: return WTRRandomNinjaCommon();
      case 5: return WTRRandomWarriorCommon();
    }
  }

  function WTRRandomRare()
  {
    $number = rand(1, 45);
    switch($number)
    {
      case 1: return "WTR011";
      case 2: return "WTR012";
      case 3: return "WTR013";
      case 4: return "WTR014";
      case 5: return "WTR015";
      case 6: return "WTR016";
      case 7: return "WTR017";
      case 8: return "WTR018";
      case 9: return "WTR019";
      case 10: return "WTR048";
      case 11: return "WTR049";
      case 12: return "WTR050";
      case 13: return "WTR051";
      case 14: return "WTR052";
      case 15: return "WTR053";
      case 16: return "WTR054";
      case 17: return "WTR055";
      case 18: return "WTR056";
      case 19: return "WTR086";
      case 20: return "WTR087";
      case 21: return "WTR088";
      case 22: return "WTR089";
      case 23: return "WTR090";
      case 24: return "WTR091";
      case 25: return "WTR092";
      case 26: return "WTR093";
      case 27: return "WTR094";
      case 28: return "WTR123";
      case 29: return "WTR124";
      case 30: return "WTR125";
      case 31: return "WTR126";
      case 32: return "WTR127";
      case 33: return "WTR128";
      case 34: return "WTR129";
      case 35: return "WTR130";
      case 36: return "WTR131";
      case 37: return "WTR167";
      case 38: return "WTR168";
      case 39: return "WTR169";
      case 40: return "WTR170";
      case 41: return "WTR171";
      case 42: return "WTR172";
      case 43: return "WTR173";
      case 44: return "WTR174";
      case 45: return "WTR175";
    }
  }

  function WTRRandomMajestic()
  {
    $number = rand(1, 25);
    switch($number)
    {
      case 1: return "WTR006";
      case 2: return "WTR007";
      case 3: return "WTR008";
      case 4: return "WTR009";
      case 5: return "WTR010";
      case 6: return "WTR043";
      case 7: return "WTR044";
      case 8: return "WTR045";
      case 9: return "WTR046";
      case 10: return "WTR047";
      case 11: return "WTR081";
      case 12: return "WTR082";
      case 13: return "WTR083";
      case 14: return "WTR084";
      case 15: return "WTR085";
      case 16: return "WTR118";
      case 17: return "WTR119";
      case 18: return "WTR120";
      case 19: return "WTR121";
      case 20: return "WTR122";
      case 21: return "WTR159";
      case 22: return "WTR160";
      case 23: return "WTR161";
      case 24: return "WTR162";
      case 25: return "WTR163";
    }
  }

?>
