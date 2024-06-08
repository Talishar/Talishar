<?php

  function UPRIllusionistPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
  {
    global $currentPlayer;
    switch($cardID)
    {
      case "UPR004": Transform($currentPlayer, "Ash", "UPR042", target:$target); return "";
      case "UPR006": Transform($currentPlayer, "Ash", "UPR406", target:$target); return "";
      case "UPR007": Transform($currentPlayer, "Ash", "UPR407", target:$target); return "";
      case "UPR008": Transform($currentPlayer, "Ash", "UPR408", target:$target); return "";
      case "UPR009": Transform($currentPlayer, "Ash", "UPR409", target:$target); return "";
      case "UPR010": Transform($currentPlayer, "Ash", "UPR410", target:$target); return "";
      case "UPR011": Transform($currentPlayer, "Ash", "UPR411", target:$target); return "";
      case "UPR012": Transform($currentPlayer, "Ash", "UPR412", target:$target); return "";
      case "UPR013": Transform($currentPlayer, "Ash", "UPR413", target:$target); return "";
      case "UPR014": Transform($currentPlayer, "Ash", "UPR414", target:$target); return "";
      case "UPR015": Transform($currentPlayer, "Ash", "UPR415", target:$target); return "";
      case "UPR016": Transform($currentPlayer, "Ash", "UPR416", target:$target); return "";
      case "UPR017": Transform($currentPlayer, "Ash", "UPR417", target:$target); return "";
      case "UPR018": case "UPR019": case "UPR020": Transform($currentPlayer, "Ash", "UPR042", true); return "";
      case "UPR030": case "UPR031": case "UPR032":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        return "";
      case "UPR033": case "UPR034": case "UPR035":
        PutPermanentIntoPlay($currentPlayer, "UPR043");
        if($cardID == "UPR033") $maxTransform = 3;
        else if($cardID == "UPR034") $maxTransform = 2;
        else $maxTransform = 1;
        for($i=0; $i<$maxTransform; ++$i) Transform($currentPlayer, "Ash", "UPR042", true, ($i == 0 ? false : true), ($i == 0 ? false : true));
        return "";
      case "UPR039": TransformPermanent($currentPlayer, "Ash", "UPR439"); return "";
      case "UPR040": TransformPermanent($currentPlayer, "Ash", "UPR440"); return "";
      case "UPR041": TransformPermanent($currentPlayer, "Ash", "UPR441"); return "";
      case "UPR036": case "UPR037": case "UPR038":
        Transform($currentPlayer, "Ash", "UPR042", target:$target);
        AddDecisionQueue("MZOP", $currentPlayer, "GETUNIQUEID");
        AddDecisionQueue("ADDLIMITEDCURRENTEFFECT", $currentPlayer, $cardID . ",HAND");
        return "";
      case "UPR151":
        $gtIndex = FindCharacterIndex($currentPlayer, "UPR151");
        $char = &GetPlayerCharacter($currentPlayer);
        $index = PlayAlly("UPR551", $currentPlayer);
        $allies = &GetAllies($currentPlayer);
        $allies[$index+2] = $char[$gtIndex+2];
        AddCurrentTurnEffect($cardID . "-" . $char[$gtIndex+2], $currentPlayer);
        return "";
      case "UPR154":
        AddCurrentTurnEffect("UPR154", $currentPlayer);
        return "";
      case "UPR155": case "UPR156": case "UPR157":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "";
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
        if(IsHeroAttackTarget()) {
          AddDecisionQueue("MULTIZONEINDICES", $mainPlayer, "THEIRITEMS");
          AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose an item to take");
          AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
          AddDecisionQueue("MZOP", $mainPlayer, "GAINCONTROL", 1);
          AddDecisionQueue("ELSE", $mainPlayer, "-");
          AddDecisionQueue("DRAW", $mainPlayer, "-", 1);
        }
        break;
      case "UPR413":
        $index = $combatChainState[$CCS_WeaponIndex];
        $allies = &GetAllies($mainPlayer);
        $allies[$index+2] -= 1;
        $allies[$index+7] -= 1;
        PutPermanentIntoPlay($mainPlayer, "UPR043");
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
      break;
    default:
      break;
  }
}

  function Transform($player, $materialType, $into, $optional=false, $subsequent=false, $firstTransform=true, $target="")
  {
    if($target != ""){
      $index = explode("-", $target);
      AddDecisionQueue("PASSPARAMETER", $player, $index[1], 1);
      AddDecisionQueue("TRANSFORM", $player, $into.",".$firstTransform, 1);
    } else if($materialType == "Ash") {
      AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $materialType, ($subsequent ? 1 : 0));
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a material to transform into " . CardLink($into, $into), 1);
      if($optional) AddDecisionQueue("MAYCHOOSEPERMANENT", $player, "<-", 1);
      else AddDecisionQueue("CHOOSEPERMANENT", $player, "<-", 1);
      AddDecisionQueue("TRANSFORM", $player, $into.",".$firstTransform, 1);
    } else if($materialType == "MON104") {
      $subType = CardSubType($materialType);
      AddDecisionQueue("FINDINDICES", $player, "MON104",($subsequent ? 1 : 0));
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a " . CardLink($materialType, $materialType) . " to transform into " . CardLink($into, $into), 1);
      AddDecisionQueue("CHOOSEMY" . strtoupper($subType), $player, "<-", 1);
      AddDecisionQueue("TRANSFORM" . strtoupper($subType), $player, $into, 1);
    } else {
      $subType = CardSubType($materialType);
      AddDecisionQueue("FINDINDICES", $player, "PERMSUBTYPE," . $subType, ($subsequent ? 1 : 0));
      AddDecisionQueue("SETDQCONTEXT", $player, "Choose a " . CardLink($materialType, $materialType) . " to transform into " . CardLink($into, $into), 1);
      AddDecisionQueue("CHOOSEMY" . strtoupper($subType), $player, "<-", 1);
      AddDecisionQueue("TRANSFORM" . strtoupper($subType), $player, $into, 1);
    }
  }

  function ResolveTransform($player, $materialIndex, $into, $firstTransform=true)
  {
    $materialType = RemovePermanent($player, $materialIndex);
    return PlayAlly($into, $player, $materialType, firstTransform: $firstTransform);
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
    $materialType = RemoveAura($player, $materialIndex, -1);
    return PlayAlly($into, $player, $materialType);
  }

  function GhostlyTouchPhantasmDestroy()
  {
    global $mainPlayer;
    $ghostlyTouchIndex = FindCharacterIndex($mainPlayer, "UPR151");
    if($ghostlyTouchIndex > -1) {
      $char = &GetPlayerCharacter($mainPlayer);
      ++$char[$ghostlyTouchIndex+2];
    }
  }

?>
