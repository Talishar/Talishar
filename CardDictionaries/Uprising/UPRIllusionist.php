<?php

  function UPRIllusionistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "UPR006": case "UPR007": case "UPR008": case "UPR009": case "UPR010": case "UPR011": case "UPR012": case "UPR013": case "UPR014": case "UPR015": case "UPR016": case "UPR017": return "Invocation";
      case "UPR003": return "Scepter";
      case "UPR004": return "Arms";
      case "UPR005": return "Aura";
      case "UPR042": return "Dragon,Ally";
      case "UPR043": return "Ash";
      case "UPR151": return "Arms";
      case "UPR152": return "Legs";
      case "UPR406": return "Dragon,Ally";
      case "UPR407": return "Dragon,Ally";
      case "UPR408": return "Dragon,Ally";
      case "UPR409": return "Dragon,Ally";
      case "UPR410": return "Dragon,Ally";
      case "UPR411": return "Dragon,Ally";
      case "UPR412": return "Dragon,Ally";
      case "UPR413": return "Dragon,Ally";
      case "UPR414": return "Dragon,Ally";
      case "UPR415": return "Dragon,Ally";
      case "UPR416": return "Dragon,Ally";
      case "UPR417": return "Dragon,Ally";
      case "UPR439": case "UPR440": case "UPR441": return "Ash";
      case "UPR551": return "Ally";
      default: return "";
    }
  }

  //Minimum cost of the card
  function UPRIllusionistCardCost($cardID)
  {
    switch($cardID)
    {
      case "UPR001": case "UPR002": return -1;
      case "UPR004": return -1;
      case "UPR005": return 0;
      case "UPR006": return 6;
      case "UPR007": return 5;
      case "UPR008": return 4;
      case "UPR009": return 0;
      case "UPR010": return 0;
      case "UPR011": return 1;
      case "UPR012": return 1;
      case "UPR013": return 3;
      case "UPR014": return 2;
      case "UPR015": return 2;
      case "UPR016": return 3;
      case "UPR017": return 1;
      case "UPR018": case "UPR019": case "UPR020": return 1;
      case "UPR021": case "UPR022": case "UPR023": return 1;
      case "UPR024": case "UPR025": case "UPR026": return 0;
      case "UPR027": case "UPR028": case "UPR029": return 2;
      case "UPR030": case "UPR031": case "UPR032": return 1;
      case "UPR033": case "UPR034": case "UPR035": return 1;
      case "UPR036": case "UPR037": case "UPR038": return 0;
      case "UPR039": case "UPR040": case "UPR041": return 0;
      case "UPR042": case "UPR043": return -1;
      case "UPR153": return 3;
      case "UPR154": return 3;
      case "UPR155": case "UPR156": case "UPR157": return 1;
      default: return 0;
    }
  }

  function UPRIllusionistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "UPR005": return 1;
      case "UPR006": return 1;
      case "UPR007": return 1;
      case "UPR008": return 1;
      case "UPR009": return 1;
      case "UPR010": return 1;
      case "UPR011": return 1;
      case "UPR012": return 1;
      case "UPR013": return 1;
      case "UPR014": return 1;
      case "UPR015": return 1;
      case "UPR016": return 1;
      case "UPR017": return 1;
      case "UPR018": case "UPR021": case "UPR024": case "UPR027": case "UPR030": case "UPR033": case "UPR036": case "UPR039": return 1;
      case "UPR019": case "UPR022": case "UPR025": case "UPR028": case "UPR031": case "UPR034": case "UPR037": case "UPR040": return 2;
      case "UPR020": case "UPR023": case "UPR026": case "UPR029": case "UPR032": case "UPR035": case "UPR038": case "UPR041": return 3;
      case "UPR153": return 1;
      case "UPR154": return 3;
      case "UPR155": return 1;
      case "UPR156": return 2;
      case "UPR157": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "UPR004": return 0;
      case "UPR005": return 3;
      case "UPR006": return 3;
      case "UPR007": return 3;
      case "UPR008": return 3;
      case "UPR009": return 3;
      case "UPR010": return 3;
      case "UPR011": return 3;
      case "UPR012": return 3;
      case "UPR013": return 3;
      case "UPR014": return 3;
      case "UPR015": return 3;
      case "UPR016": return 3;
      case "UPR017": return 3;
      case "UPR018": case "UPR019": case "UPR020": return 3;
      case "UPR021": case "UPR022": case "UPR023": return 3;
      case "UPR024": case "UPR025": case "UPR026": return 3;
      case "UPR027": case "UPR028": case "UPR029": return 3;
      case "UPR030": case "UPR031": case "UPR032": return 3;
      case "UPR033": case "UPR034": case "UPR035": return 2;
      case "UPR036": case "UPR037": case "UPR038": return 2;
      case "UPR151": return 0;
      case "UPR152": return 0;
      case "UPR155": case "UPR156": case "UPR157": return 2;
      default: return -1;
    }
  }

  function UPRIllusionistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "UPR018": return 3;
      case "UPR019": return 2;
      case "UPR020": return 1;
      case "UPR021": return 5;
      case "UPR022": return 4;
      case "UPR023": return 3;
      case "UPR024": return 4;
      case "UPR025": return 3;
      case "UPR026": return 2;
      case "UPR027": return 8;
      case "UPR028": return 7;
      case "UPR029": return 6;
      case "UPR030": return 3;
      case "UPR031": return 2;
      case "UPR032": return 1;
      case "UPR042": return 1;
      case "UPR153": return 13;
      case "UPR406": return 6;
      case "UPR407": return 5;
      case "UPR408": return 4;
      case "UPR409": return 2;
      case "UPR410": return 3;
      case "UPR411": return 4;
      case "UPR412": return 2;
      case "UPR413": return 4;
      case "UPR414": return 1;
      case "UPR415": return 3;
      case "UPR416": return 6;
      case "UPR417": return 3;
      default: return 0;
    }
  }

  function UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "UPR004":
        Transform($currentPlayer, "Ash", "UPR042");
        return "";
      case "UPR006":
        Transform($currentPlayer, "Ash", "UPR406");
        return "";
      case "UPR007":
        Transform($currentPlayer, "Ash", "UPR407");
        return "";
      case "UPR008":
        Transform($currentPlayer, "Ash", "UPR408");
        return "";
      case "UPR009":
        Transform($currentPlayer, "Ash", "UPR409");
        return "";
      case "UPR010":
        Transform($currentPlayer, "Ash", "UPR410");
        return "";
      case "UPR011":
        Transform($currentPlayer, "Ash", "UPR411");
        return "";
      case "UPR012":
        Transform($currentPlayer, "Ash", "UPR412");
        return "";
      case "UPR013":
        Transform($currentPlayer, "Ash", "UPR413");
        return "";
      case "UPR014":
        Transform($currentPlayer, "Ash", "UPR414");
        return "";
      case "UPR015":
        Transform($currentPlayer, "Ash", "UPR415");
        return "";
      case "UPR016":
        Transform($currentPlayer, "Ash", "UPR416");
        return "";
      case "UPR017":
        Transform($currentPlayer, "Ash", "UPR417");
        return "";
      case "UPR018": case "UPR019": case "UPR020":
        Transform($currentPlayer, "Ash", "UPR042", true);
        return "";
      case "UPR030": case "UPR031": case "UPR032":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        return "Sweeping Blow created an Ash token.";
      case "UPR033": case "UPR034": case "UPR035":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        if($cardID == "UPR033") $maxTransform = 3;
        else if($cardID == "UPR034") $maxTransform = 2;
        else $maxTransform = 1;
        for($i=0; $i<$maxTransform; ++$i)
        {
          Transform($currentPlayer, "Ash", "UPR042", true, ($i == 0 ? false : true));
        }
        return "";
      case "UPR039":
          TransformPermanent($currentPlayer, "Ash", "UPR439");
        return "";
      case "UPR040":
          TransformPermanent($currentPlayer, "Ash", "UPR440");
        return "";
      case "UPR041":
          TransformPermanent($currentPlayer, "Ash", "UPR441");
        return "";
      case "UPR036": case "UPR037": case "UPR038":
        Transform($currentPlayer, "Ash", "UPR042");
        AddDecisionQueue("MZGETUNIQUEID", $currentPlayer, "-");
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $currentPlayer, $cardID . "," . "HAND");
        return "";
      case "UPR151":
        $gtIndex = FindCharacterIndex($currentPlayer, "UPR151");
        $char = &GetPlayerCharacter($currentPlayer);
        $index = PlayAlly("UPR551", $currentPlayer);
        $allies = &GetAllies($currentPlayer);
        $allies[$index+2] = $char[$gtIndex+2];
        AddCurrentTurnEffect($cardID . "-" . $char[$gtIndex+2], $currentPlayer);
        return "Animates itself into an Ally.";
      case "UPR154":
        AddCurrentTurnEffect("UPR154", $currentPlayer);
        return "Makes target illusionist attack lose Phantasm.";
      case "UPR155": case "UPR156": case "UPR157":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Makes your next attack action card Illusionist, modify its base attack and gain Phantasm.";
      default: return "";
    }
  }

  function UPRIllusionistHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState, $CCS_WeaponIndex, $defPlayer;
    switch($cardID)
    {
      case "UPR024": case "UPR025": case "UPR026":
        PutPermanentIntoPlay($mainPlayer, "UPR043");
        Transform($mainPlayer, "Ash", "UPR042", true);
        break;
      case "UPR411":
        if(IsHeroAttackTarget())
        {
          $items = &GetItems($defPlayer);
          if(count($items) == 0)
          {
            Draw($mainPlayer);
            WriteLog(CardLink($cardID,$cardID) . " draw a card.");
          }
          else
          {
            AddDecisionQueue("FINDINDICES", $defPlayer, "ITEMS");
            AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to take control of");
            AddDecisionQueue("CHOOSETHEIRITEM", $mainPlayer, "<-", 1);
            AddDecisionQueue("ITEMGAINCONTROL", $mainPlayer, "-", 1);
          }
        }
        break;
      case "UPR413":
        $index = $combatChainState[$CCS_WeaponIndex];
        $allies = &GetAllies($mainPlayer);
        $allies[$index+2] -= 1;
        $allies[$index+7] -= 1;
        PutPermanentIntoPlay($mainPlayer, "UPR043");
        WriteLog(CardLink($cardID,$cardID) . " got a -1 health counter and created an ash token.");
        break;
      case "UPR416": if(IsHeroAttackTarget()) { DealArcane(3, 0, "ABILITY", $cardID, true, $mainPlayer); } break;
      default: break;
    }
  }

function UPRIllusionistDealDamageEffect($cardID)
{
  global $mainPlayer, $combatChainState, $CCS_WeaponIndex;
  switch ($cardID) {
    case "UPR413":
      $index = $combatChainState[$CCS_WeaponIndex];
      $allies = &GetAllies($mainPlayer);
      $allies[$index + 2] -= 1;
      $allies[$index + 7] -= 1;
      PutPermanentIntoPlay($mainPlayer, "UPR043");
      WriteLog(CardLink($cardID, $cardID) . " got a -1 health counter and created an ash token.");
      break;
    default:
      break;
  }
}

  function Transform($player, $materialType, $into, $optional=false, $subsequent=false)
  {
    if ($materialType == "Ash") {
      AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType, ($subsequent ? 1 : 0));
      if ($optional) {
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform into " . CardLink($into, $into) . " or skip with the Pass button", 1);
        AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
      }
      else {
        AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform into " . CardLink($into, $into), 1);
        AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
      }
      AddDecisionQueue("TRANSFORM", $player, $into, 1);
    }
    else {
      $subType = CardSubType($materialType);
      AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $subType, ($subsequent ? 1 : 0));
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a " . CardLink($materialType, $materialType) . " to transform into " . CardLink($into, $into), 1);
      AddDecisionQueue("CHOOSEMY" . strtoupper($subType), $player, "<-", 1);
      AddDecisionQueue("TRANSFORM" . strtoupper($subType), $player, $into, 1);
    }
  }

  function ResolveTransform($player, $materialIndex, $into)
  {
    $materialType = RemovePermanent($player, $materialIndex);
    return PlayAlly($into, $player, $materialType);//Right now transform only happens into allies
  }

  function TransformPermanent($player, $materialType, $into, $optional=false)
  {
    AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType);
    AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform", 1);
    if($optional) AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
    else AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
    AddDecisionQueue("TRANSFORMPERMANENT", $player, $into, 1);
  }

  function ResolveTransformPermanent($player, $materialIndex, $into)
  {
    RemovePermanent($player, $materialIndex);
    return PutPermanentIntoPlay($player, $into);
  }

  function ResolveTransformAura($player, $materialIndex, $into)
  {
    $materialType = DestroyAura($player, $materialIndex);
    return PlayAlly($into, $player, $materialType);//Right now transform only happens into allies
  }

  function GhostlyTouchPhantasmDestroy()
  {
    global $mainPlayer;
    $ghostlyTouchIndex = FindCharacterIndex($mainPlayer, "UPR151");
    if($ghostlyTouchIndex > -1)
    {
      $char = &GetPlayerCharacter($mainPlayer);
      ++$char[$ghostlyTouchIndex+2];
    }
  }

?>
