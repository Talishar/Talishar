<?php

function DYNAbilityCost($cardID)
{
    switch ($cardID) {
        case "DYN001": return 3;
        case "DYN243": return 2;
        default:
            return 0;
    }
}

function DYNAbilityType($cardID, $index = -1)
{
    global $currentPlayer, $mainPlayer, $defPlayer;
    switch ($cardID) {
        case "DYN001": return "A";
        case "DYN243": return "A";
        default:
            return "";
    }
}

// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function DYNHasGoAgain($cardID)
{
    switch ($cardID) {

        default:
            return false;
    }
}

function DYNAbilityHasGoAgain($cardID)
{
    switch ($cardID) {
        case "DYN243": return true;

        default:
            return false;
    }
}

function DYNEffectAttackModifier($cardID)
{
    global $combatChainState, $CCS_LinkBaseAttack;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    if (count($params) > 1) $parameter = $params[1];
    switch ($cardID) {

        default:
            return 0;
    }
}

function DYNCombatEffectActive($cardID, $attackID)
{
    global $CS_AtksWWeapon, $mainPlayer;
    $params = explode(",", $cardID);
    $cardID = $params[0];
    switch ($cardID) {

        default:
            return false;
    }
}


function DYNCardTalent($cardID) // TODO
{
  $number = intval(substr($cardID, 3));
  if($number <= 0) return "";
  else if($number >= 1 && $number <= 2) return "ROYAL,DRACONIC";
//   else if($number >= 3 && $number <= 124) return "";
//   else if($number >= 125 && $number <= 150) return "";
//   else if($number >= 406 && $number <= 417 ) return "";
//   else if($number >= 439 && $number <= 441) return "";
  else return "NONE";
}

function DYNCardType($cardID)
{
    switch ($cardID) {
        case "DYN001": return "C";
        case "DYN234": return "E";

        case "DYN243": return "T";

        default:
            return "";
    }
}

function DYNCardSubtype($cardID)
{
    switch ($cardID) {

        case "DYN234": return "Head";

        case "DYN243": return "Item";

        default:
            return "";
    }
}

function DYNCardCost($cardID)
{
    switch ($cardID) {
        case "DYN001": return 0;
        case "DYN243": return 0;

        default:
            return 0;
    }
}

function DYNPitchValue($cardID)
{
    switch ($cardID) {
        case "DYN001": return 0;
        case "DYN234": return 0;

        case "DYN243": return 0;

        default:
            return 3;
    }
}

function DYNBlockValue($cardID)
{
    switch ($cardID) {
        case "DYN001": return -1;
        case "DYN234": return -1;

        case "DYN243": return -1;

        default:
            return 3;
    }
}

function DYNAttackValue($cardID)
{
    switch ($cardID) {

        default:
            return 0;
    }
}

function DYNPlayAbility($cardID, $from, $resourcesPaid)
{
    global $currentPlayer, $combatChain, $CS_PlayIndex, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    global $CS_HighestRoll, $CS_NumNonAttackCards, $CS_NumAttackCards, $CS_NumBoosted, $mainPlayer, $CCS_NumBoosted, $CCS_RequiredEquipmentBlock;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch ($cardID) {
        case "DYN001": 
            AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCARD,ARC159");
            AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
            AddDecisionQueue("ATTACKWITHIT", $currentPlayer, "-", 1);
            AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
            return "";
        case "DYN243":
            $rv = "";
            if($from == "PLAY"){
                DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
                $rv = "Draws a card.";
                Draw($currentPlayer);
            }
            return $rv;
        default:
            return "";
    }
}

function DYNHitEffect($cardID)
{
    global $mainPlayer, $defPlayer, $CS_NumAuras, $chainLinks;
    switch ($cardID) {

        default:
            break;
    }
}
