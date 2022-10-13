<?php

function DYNAbilityCost($cardID)
{
    switch ($cardID) {
        case "DYN001": return 3;
        case "DYN005": return 3;
        case "DYN068": return 3;
        case "DYN075": return 3; // TODO: Yoji cardID to be modified with set release
        case "DYN151": return 1;
        case "DYN242": return 1;
        case "DYN243": return 2;

        default: return 0;
    }
}

function DYNAbilityType($cardID, $index = -1)
{
    switch ($cardID) {
        case "DYN001": return "A";
        case "DYN005": return "AA";
        case "DYN068": return "AA";
        case "DYN088": return "AA";
        case "DYN075": return "I"; // TODO: Yoji cardID to be modified with set release
        case "DYN151": return "A";
        case "DYN171": return "I";
        case "DYN242": case "DYN243": return "A";
        default: return "";
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
        case "DYN151": return true;
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
        case "DYN005": return "W";
        case "DYN026": return "E";
        case "DYN039": case "DYN040": case "DYN041": return "A";
        case "DYN045": return "E";
        case "DYN068": return "W";
        case "DYN075": return "C"; // TODO: Yoji cardID to be modified with set release
        case "DYN088": return "W";
        case "DYN094": return "A";
        case "DYN116": case "DYN117": case "DYN118": return "A"; // TODO: Blessing of Aether cardID to be edited
        case "DYN151": return "W";
        case "DYN171": return "E";
        case "DYN206": case "DYN207": case "DYN208": return "A";
        case "DYN234": return "E";
        case "DYN242": return "A";
        case "DYN243": return "T";

        default:
            return "";
    }
}

function DYNCardSubtype($cardID)
{
    switch ($cardID) {
        case "DYN005": return "Rock";
        case "DYN026": return "Off-Hand";
        case "DYN045": return "Chest";
        case "DYN068": return "Axe";
        case "DYN088": return "Gun";
        case "DYN094": return "Item";
        case "DYN116": case "DYN117": case "DYN118": return "Aura"; // TODO: Blessing of Aether cardID to be edited
        case "DYN151": return "Bow";
        case "DYN171": return "Head";
        case "DYN234": return "Head";
        case "DYN242": return "Item";
        case "DYN243": return "Item";

        default: return "";
    }
}

function DYNCardCost($cardID)
{
    switch ($cardID) {
        case "DYN039": case "DYN040": case "DYN041": return 2;
        case "DYN116": case "DYN117": case "DYN118": return 1; // TODO: Blessing of Aether cardID to be edited
        case "DYN242": return 2;
        case "DYN243": return 0;
        default: return 0;
    }
}

function DYNPitchValue($cardID)
{
    switch ($cardID) {
        case "DYN005": return 0;
        case "DYN039": return 1;
        case "DYN040": return 2;
        case "DYN116": return 1; // TODO: Blessing of Aether cardID to be edited
        case "DYN117": return 2; // TODO: Blessing of Aether cardID to be edited
        case "DYN206": return 1;
        case "DYN207": return 2;
        case "DYN234": return 0;
        case "DYN242": return 1;
        case "DYN243": return 0;
        default: return 3;
    }
}

function DYNBlockValue($cardID)
{
    switch ($cardID) {
        case "DYN001": return -1;
        case "DYN005": return -1;
        case "DYN026": return 3;
        case "DYN045": return 1;
        case "DYN068": return -1;
        case "DYN075": return -1; // TODO: Yoji cardID to be modified with set release
        case "DYN088": return -1;
        case "DYN094": return -1;
        case "DYN116": case "DYN117": case "DYN118": return 2; // TODO: Blessing of Aether cardID to be edited
        case "DYN151": return -1;
        case "DYN171": return 1;
        case "DYN234": return -1;
        case "DYN242": case "DYN243": return -1;
        default:
            return 3;
    }
}

function DYNAttackValue($cardID)
{
    switch ($cardID) {
        case "DYN005": return 7;
        case "DYN068": return 3;
        case "DYN088": return 5;
        default:
            return 0;
    }
}

function DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
    global $currentPlayer, $CS_PlayIndex;
    $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
    switch ($cardID) {
        case "DYN001":
            AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKCARD,ARC159");
            AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
            AddDecisionQueue("ATTACKWITHIT", $currentPlayer, "-", 1);
            AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-", 1);
            return "";
        case "DYN039":
        case "DYN040":
        case "DYN041":
            if ($cardID == "DYN039") $maxDef = 3;
            else if ($cardID == "DYN040") $maxDef = 2;
            else $maxDef = 1;
            AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYCHAR:type=E;subtype=Off-Hand;hasNegCounters=true;maxDef=" . $maxDef . ";class=GUARDIAN");
            AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose which Guardian Off-Hand to remove a -1 defense counter");
            AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-", 1);
            AddDecisionQueue("MZGETCARDINDEX", $currentPlayer, "-", 1);
            AddDecisionQueue("REMOVENEGDEFCOUNTER", $currentPlayer, "-", 1);
            return "Remove a -1 counter from a Guardian Off-hand with " . $maxDef . " or less base defense.";
        case "DYN075": // TODO: Yoji cardID to be modified with set release
            AddCurrentTurnEffect($cardID, $currentPlayer);
            return "";
        case "DYN151":
            $deck = GetDeck($currentPlayer);
            AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
            AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
            AddDecisionQueue("SETDQVAR", $currentPlayer, "1", 1);
            AddDecisionQueue("ALLCARDSUBTYPEORPASS", $currentPlayer, "Arrow", 1);
            if (CardSubType($deck[0]) != "Arrow") 
            {
                AddDecisionQueue("PASSPARAMETER", $currentPlayer, "{1}", 1);
                AddDecisionQueue("NULLPASS", $currentPlayer, "-", 1);
                AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows the top of your deck: <1>", 1);
                AddDecisionQueue("OK", $currentPlayer, "-", 1);
                AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO", 1);
            }
            else 
            {
                AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <1> in your arsenal", 1);
                AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_put_the_card_in_arsenal", 1);
            }
            AddDecisionQueue("SANDSCOURGREATBOW", $currentPlayer, "-");
            return "";
        case "DYN171":
            AddCurrentTurnEffect($cardID, $currentPlayer);
            return CardLink("ARC112", "ARC112") . "s you control have spellvoid 1 this turn.";
        case "DYN206": case "DYN207": case "DYN208":
            DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
            return "Deals " . ArcaneDamage($cardID) . " arcane damage.";
        case "DYN242":
            $rv = "";
            if($from == "PLAY"){
                DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
                AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of heroes");
                AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Both_Heroes,Target_Yourself,Target_No_Heroes");
                AddDecisionQueue("IMPERIALWARHORN", $currentPlayer, "<-", 1);
            }
        return $rv;
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

        default: break;
    }
}

function IsRoyal($player)
{
    $mainCharacter = &GetPlayerCharacter($player);

    if (SearchCharacterForCard($player, "DYN234")) return true;

    switch ($mainCharacter[0]) {
        case "DYN001":
            return true;
        default: break;
    }
    return false;
}

function HasSurge($cardID)
{
    switch ($cardID) 
    {
        case "DYN206": case "DYN207": case "DYN208": return true;
        default: return false;
    }
}