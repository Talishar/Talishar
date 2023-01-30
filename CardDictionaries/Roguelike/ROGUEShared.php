<?php

function ROGUEAbilityCost($cardID)
{
    switch ($cardID) {
      case "ROGUE002": return 0;
      case "ROGUE005": return 0;
      case "ROGUE007": return 0;
      default: return 0;
    }
}

function ROGUEAbilityType($cardID, $index = -1)
{
    switch ($cardID) {
      case "ROGUE002": return "AA";
      case "ROGUE005": return "AA";
      case "ROGUE007": return "A";
      default: return "";
    }
}

// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function ROGUEHasGoAgain($cardID)
{
    switch ($cardID) {
      default: return false;
    }
}

function ROGUEAbilityHasGoAgain($cardID)
{
    switch ($cardID) {
      case "ROGUE007": return true;
      default: return false;
    }
}

function ROGUEEffectAttackModifier($cardID)
{
    global $combatChainState, $CCS_LinkBaseAttack;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if (count($params) > 1) $parameter = $params[1];
    switch ($cardID) {
      case "ROGUE008": return 1;
      default: return 0;
    }
}

function ROGUECombatEffectActive($cardID, $attackID)
{
    $params = explode(",", $cardID);
    $cardID = $params[0];
    switch ($cardID) {
        case "ROGUE008": return true;
        default:
            return false;
    }
}


function ROGUECardTalent($cardID) // TODO
{
  $number = intval(substr($cardID, 3));
  if($number <= 0) return "";
//   else if($number >= 3 && $number <= 124) return "";
//   else if($number >= 125 && $number <= 150) return "";
//   else if($number >= 406 && $number <= 417 ) return "";
//   else if($number >= 439 && $number <= 441) return "";
  else return "NONE";
}

function ROGUECardType($cardID)
{
    switch ($cardID) {
      case "ROGUE001": return "C";
      case "ROGUE002": return "W";
      case "ROGUE003": return "C";
      case "ROGUE004": return "C";
      case "ROGUE005": return "W";
      case "ROGUE006": return "C";
      case "ROGUE007": return "E";
      case "ROGUE008": return "C";
      case "ROGUE009": return "C";
      case "ROGUE010": return "C";

      case "ROGUE501": return "A";
      default:
        return "";
    }
}

function ROGUECardSubtype($cardID)
{
    switch ($cardID) {
      case "ROGUE001": return "Hog";
      case "ROGUE002": return "Natural";
      case "ROGUE003": return "Monster";
      case "ROGUE004": return "Bear";
      case "ROGUE005": return "Natural";
      case "ROGUE006": return "Elemental";
      case "ROGUE007": return "Chest";
      case "ROGUE008": return "Ninja";
      case "ROGUE009": return "Ranger";
      case "ROGUE009": return "Guardian";

      case "ROGUE501": return "Power";
      default: return "";
    }
}

function ROGUECardCost($cardID)
{
    switch ($cardID) {
      case "ROGUE501": return 0;
      default: return 0;
    }
}

function ROGUEPitchValue($cardID)
{
    switch ($cardID) {
      case "ROGUE001": return -1;
      case "ROGUE002": return -1;
      case "ROGUE003": return -1;
      case "ROGUE004": return -1;
      case "ROGUE005": return -1;
      case "ROGUE006": return -1;
      case "ROGUE007": return -1;
      case "ROGUE008": return -1;
      case "ROGUE009": return -1;
      case "ROGUE010": return -1;
      case "ROGUE501": return 3;
      default: return 3;
    }
}

function ROGUEBlockValue($cardID)
{
    switch ($cardID) {
      case "ROGUE001": return -1;
      case "ROGUE002": return -1;
      case "ROGUE003": return -1;
      case "ROGUE004": return -1;
      case "ROGUE005": return -1;
      case "ROGUE006": return -1;
      case "ROGUE007": return -1;
      case "ROGUE008": return -1;
      case "ROGUE009": return -1;
      case "ROGUE010": return -1;
      case "ROGUE501": return -1;
      default:
        return 3;
    }
}

function ROGUEAttackValue($cardID)
{
    switch ($cardID) {
      case "ROGUE002": return 2;
      case "ROGUE005": return 4;
      case "ROGUE007": return 2;
      default:
        return 0;
    }
}

function ROGUEPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
    global $currentPlayer, $CS_PlayIndex;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch ($cardID) {
      case "ROGUE007":
        $hand = &GetHand($currentPlayer);
        array_unshift($hand, "ELE191");
        return "";
      case "ROGUE501":
        PutPermanentIntoPlay($currentPlayer, $cardID);
        return "";
      default:
        return "";
    }
}

function ROGUEHitEffect($cardID)
{
    switch ($cardID) {

        default: break;
    }
}
?>
