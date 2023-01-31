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
      case "ROGUE506": return 1;
      case "ROGUE509": return 1;
      default: return 0;
    }
}

function ROGUECombatEffectActive($cardID, $attackID)
{
    $params = explode(",", $cardID);
    $cardID = $params[0];
    switch ($cardID) {
        case "ROGUE008": return true;
        case "ROGUE506": return CardType($attackID) == "AA";
        case "ROGUE509": return $attackID == "DYN065";
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
      case "ROGUE013": return "C";

      case "ROGUE501": case "ROGUE502": case "ROGUE503": case "ROGUE504": case "ROGUE505": case "ROGUE506": case "ROGUE507": case "ROGUE508": case "ROGUE509": case "ROGUE510": return "A";
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
      case "ROGUE013": return "Ninja";

      case "ROGUE501": //Broken Hourglass
      case "ROGUE502": //Perfect Mirror
      case "ROGUE503": //Scroll of Mastery
      case "ROGUE504": //Blacksmith's Tongs
      case "ROGUE505": //Teklo's Cranium
      case "ROGUE506": //Teachings of War
      case "ROGUE507": //Merchant's Handbag
      case "ROGUE508": //Shattered Mirror
      case "ROGUE509": //Qi Scroll
      case "ROGUE510": //Survival Kit
      return "Power";
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
      case "ROGUE013": return -1;
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
      case "ROGUE013": return -1;
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

function ROGUEPowerStart()
{
  global $mainPlayer, $defPlayer;
  $permanents = &GetPermanents($mainPlayer);

  for ($i = count($permanents) - PermanentPieces(); $i >= 0; $i -= PermanentPieces()) {
    $remove = 0;
    switch ($permanents[$i]) {
      case "ROGUE508":
        $deck = &GetDeck($mainPlayer);
        $optionOne = rand(0, count($deck)-1);
        $optionTwo = rand(0, count($deck)-1);
        $optionThree = rand(0, count($deck)-1);
        if($optionOne == $optionTwo)
        {
          if($optionOne == 0) ++$optionTwo;
          else --$optionTwo;
        }
        for($i = 0; $i < 5 && ($optionThree == $optionOne || $optionThree == $optionOne); ++$i)
        {
          if($optionThree <= 4) $optionThree += 3;
          else --$optionThree;
        }
        AddDecisionQueue("CHOOSEDECK", $mainPlayer, $optionOne . "," . $optionTwo . "," . $optionThree);
        AddDecisionQueue("ROGUEMIRRORGAMESTART", $mainPlayer, "0");
        break;
      case "ROGUE510":
        GainHealth(5, $mainPlayer);
        break;
      default:
        break;
    }
  }
}
?>
