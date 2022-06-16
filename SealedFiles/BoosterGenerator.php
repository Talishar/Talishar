<?php

  function GenerateUPRBooster()
  {
    $booster = "";
    //1-4 Generic common
    for($i=0; $i<3; ++$i)
    {
      $booster .= UPRRandomNonClassCommon() . " ";
    }
    //5 Common equip
    $booster .= UPRRandomEquipment() . " ";
    //6 Rare
    $booster .= UPRRandomRare() . " ";
    //7 Rare+
    $num = rand(1,4);
    if($num == 4)
    {
      $booster .= UPRRandomMajestic() . " ";
    }
    else
    {
      $booster .= UPRRandomRare() . " ";
    }
    //8 RF Common+
    $num = rand(1,24);
    if($num == 24) $booster .= UPRRandomMajestic() . " ";
    else if($num > 16) $booster .= UPRRandomRare() . " ";
    else $booster .= UPRRandomCommon() . " ";
    //7x class common
    for($i=0; $i<7; ++$i)
    {
      $booster .= UPRRandomClassCommon();
      if($i != 6) $booster .= " ";
    }
    return $booster;
  }

  function UPRRandomCommon()
  {
    $number = rand(1,2);
    switch($number)
    {
      case 1: return UPRRandomNonClassCommon();
      case 2: return UPRRandomClassCommon();
    }
  }

  function UPRRandomGenericCommon()
  {
    $rv = "UPR";
    $number = 203 + rand(0, 20);
    $rv .= $number;
    if($rv == "UPR209") return "WTR191";
    else if($rv == "UPR210") return "WTR192";
    else if($rv == "UPR211") return "WTR193";
    return $rv;
  }

  function UPRRandomIllusionistCommon()
  {
    $rv = "UPR";
    $number = 18 + rand(0, 23);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function UPRRandomNinjaCommon()
  {
    $rv = "UPR";
    $number = 60 + rand(0, 23);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function UPRRandomWizardCommon()
  {
    $rv = "UPR";
    $rand = rand(0, 20);
    if($rand <= 11) $number = 113 + $rand;
    else $number = 173 + $rand - 12;
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function UPRRandomDraconicCommon()
  {
    $rv = "UPR";
    $number = 92 + rand(0, 8);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }

  function UPRRandomIceCommon()
  {
    $rv = "UPR";
    $number = 144 + rand(0, 5);
    if($number < 100) $rv .= "0";
    $rv .= $number;
    return $rv;
  }


  function UPRRandomEquipment()
  {
    $number = rand(1, 11);
    switch($number)
    {
      case 1: return "UPR004";
      case 2: return "UPR047";
      case 3: return "UPR085";
      case 4: return "UPR125";
      case 5: return "UPR137";
      case 6: return "UPR152";
      case 7: return "UPR159";
      case 8: return "UPR167";
      case 9: return "UPR184";
      case 10: return "UPR185";
      case 11: return "UPR186";
    }
  }

  function UPRRandomNonClassCommon()
  {
    $num = rand(1,34);
    if($num < 21) return UPRRandomGenericCommon();
    else if($num < 29) return UPRRandomDraconicCommon();
    else return UPRRandomIceCommon();
  }

  function UPRRandomClassCommon()
  {
    $num = rand(1,3);
    switch($num)
    {
      case 1: return UPRRandomNinjaCommon();
      case 2: return UPRRandomWizardCommon();
      case 3: return UPRRandomIllusionistCommon();
    }
  }

  function UPRRandomRare()
  {
    $number = rand(1, 51);
    switch($number)
    {
      case 1: return "UPR009";
      case 2: return "UPR010";
      case 3: return "UPR011";
      case 4: return "UPR012";
      case 5: return "UPR013";
      case 6: return "UPR014";
      case 7: return "UPR015";
      case 8: return "UPR016";
      case 9: return "UPR017";
      case 10: return "UPR051";
      case 11: return "UPR052";
      case 12: return "UPR053";
      case 13: return "UPR054";
      case 14: return "UPR055";
      case 15: return "UPR056";
      case 16: return "UPR057";
      case 17: return "UPR058";
      case 18: return "UPR059";
      case 19: return "UPR090";
      case 20: return "UPR091";
      case 21: return "UPR106";
      case 22: return "UPR107";
      case 23: return "UPR108";
      case 24: return "UPR109";
      case 25: return "UPR110";
      case 26: return "UPR111";
      case 27: return "UPR112";
      case 28: return "UPR141";
      case 29: return "UPR142";
      case 30: return "UPR143";
      case 31: return "UPR155";
      case 32: return "UPR156";
      case 33: return "UPR157";
      case 34: return "UPR162";
      case 35: return "UPR163";
      case 36: return "UPR164";
      case 37: return "UPR170";
      case 38: return "UPR171";
      case 39: return "UPR172";
      case 40: return "UPR191";
      case 41: return "UPR192";
      case 42: return "UPR193";
      case 43: return "UPR194";
      case 44: return "UPR195";
      case 45: return "UPR196";
      case 46: return "UPR197";
      case 47: return "UPR198";
      case 48: return "UPR199";
      case 49: return "UPR200";
      case 50: return "UPR201";
      case 51: return "UPR202";
    }
  }

  function UPRRandomMajestic()
  {
    $number = rand(1, 25);
    switch($number)
    {
      case 1: return "UPR005";
      case 2: return "UPR006";
      case 3: return "UPR007";
      case 4: return "UPR008";
      case 5: return "UPR048";
      case 6: return "UPR049";
      case 7: return "UPR050";
      case 8: return "UPR086";
      case 9: return "UPR087";
      case 10: return "UPR088";
      case 11: return "UPR089";
      case 12: return "UPR104";
      case 13: return "UPR105";
      case 14: return "UPR126";
      case 15: return "UPR138";
      case 16: return "UPR139";
      case 17: return "UPR140";
      case 18: return "UPR153";
      case 19: return "UPR154";
      case 20: return "UPR160";
      case 21: return "UPR161";
      case 22: return "UPR168";
      case 23: return "UPR169";
      case 24: return "UPR187";
      case 25: return "UPR188";
      case 26: return "UPR189";
      case 27: return "UPR190";
    }
  }

?>
