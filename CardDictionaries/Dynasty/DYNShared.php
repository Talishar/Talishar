<?php

function DYNAbilityCost($cardID)
{
    switch ($cardID) {
        case "DYN030": return 3;
        default:
            return 0;
    }
}

function DYNAbilityType($cardID, $index = -1)
{
    global $currentPlayer, $mainPlayer, $defPlayer;
    switch ($cardID) {
        case "DYN030": return "A";
        default:
            return "";
    }
}

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

        default:
            return false;
    }
}

function DYNCardTalent($cardID)
{
    $number = intval(substr($cardID, 3));
    return "NONE";
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

function DYNCardType($cardID)
{
    switch ($cardID) {
        case "DYN030": return "C";
        default:
            return "";
    }
}

function DYNCardSubtype($cardID)
{
    switch ($cardID) {

        default:
            return "";
    }
}

function DYNCardCost($cardID)
{
    switch ($cardID) {
        case "DYN030": return 0;
        default:
            return 0;
    }
}

function DYNPitchValue($cardID)
{
    switch ($cardID) {
        case "DYN030": return 0;
        default:
            return 3;
    }
}

function DYNBlockValue($cardID)
{
    switch ($cardID) {
        case "DYN030": return -1;
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
        case "DYN030": 
            WriteLog("I'm here!");
            AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCARD,ARC159");
            AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);

            AddDecisionQueue("ATTACKWITHIT", $currentPlayer, "-", 1);

            AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
            return "";
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
