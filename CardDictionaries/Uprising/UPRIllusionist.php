<?php

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
