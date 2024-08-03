<?php

function TerraEndTurnAbility($characterID, $player)
{
    $resources = &GetResources($player);
    $hand = &GetHand($player);
    $earthTalent = SearchCount(SearchPitch($player, talent:"EARTH"));
    if (($earthTalent >= 1) && (Count($hand) > 0 || $resources[0] > 0)) {
        AddDecisionQueue("YESNO", $player, "if you want to pay 1 to create a " . CardLink("HVY241", "HVY241"), 0, 1);
        AddDecisionQueue("NOPASS", $player, "-", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "1", 1);
        AddDecisionQueue("PAYRESOURCES", $player, "<-", 1);
        AddDecisionQueue("WRITELOG", $player, CardLink($characterID, $characterID) . " created a " . CardLink("HVY241", "HVY241") . " token ", 1);
        AddDecisionQueue("PASSPARAMETER", $player, "HVY241", 1);
        AddDecisionQueue("PUTPLAY", $player, "-", 1);
    }
}

function FSEffectAttackModifier($cardID)
{
    switch($cardID)
    {
        case "AUR014": return 3;
        case "AUR021": return 2;
        default: return 0;
    }
}

function FSCombatEffectActive($cardID, $attackID)
{
    global $mainPlayer;
    switch($cardID)
    {
        case "AUR014": case "AUR021":
            return TalentContainsAny($attackID, "LIGHTNING,ELEMENTAL",$mainPlayer);
        default: return "";
    }
}