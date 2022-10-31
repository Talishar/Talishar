<?php

function DYNAbilityCost($cardID)
{
    switch ($cardID) {
        case "DYN001": return 3;
        case "DYN005": return 3;
        case "DYN068": return 3;
        case "DYN075": return 3; // TODO: Yoji cardID to be modified with set release
        case "DYN069": return 1; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN118": return 0;
        case "DYN151": return 1;
        case "DYN192": return 2;
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
        case "DYN069": return "AA"; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN088": return "AA";
        case "DYN075": return "I"; // TODO: Yoji cardID to be modified with set release
        case "DYN118": return "AR";
        case "DYN151": return "A";
        case "DYN171": return "I";
        case "DYN192": return "A";
        case "DYN242": case "DYN243": return "A";
        default: return "";
    }
}

// Natural go again or ability go again. Attacks that gain go again should be in CoreLogic (due to hypothermia)
function DYNHasGoAgain($cardID)
{
    switch ($cardID) {
        case "DYN188": case "DYN189": case "DYN190": return  true;
        case "DYN230": case "DYN231": case "DYN232": return  true;
        default: return false;
    }
}

function DYNAbilityHasGoAgain($cardID)
{
    switch ($cardID) {
        case "DYN151": return true;
        case "DYN192": return true;
        case "DYN243": return true;

        default: return false;
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
        case "DYN069": return "W"; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN075": return "C"; // TODO: Yoji cardID to be modified with set release
        case "DYN088": return "W";
        case "DYN094": return "A";
        case "DYN113": return "C";
        case "DYN116": case "DYN117": return "A"; // TODO: Blessing of Aether cardID to be edited
        case "DYN118": return "E";
        case "DYN121": return "AA";
        case "DYN122": return "AA";
        case "DYN123": return "A";
        case "DYN151": return "W";
        case "DYN171": return "E";
        case "DYN188": case "DYN189": case "DYN190": return "A";
        case "DYN192": return "W";
        case "DYN206": case "DYN207": case "DYN208": return "A";
        case "DYN230": case "DYN231": case "DYN232": return "A";
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
        case "DYN069": return "Dagger"; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN088": return "Gun";
        case "DYN094": return "Item";
        case "DYN116": case "DYN117": return "Aura"; // TODO: Blessing of Aether cardID to be edited
        case "DYN118": return "Head";
        case "DYN151": return "Bow";
        case "DYN171": return "Head";
        case "DYN192": return "Staff";
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
        case "DYN116": case "DYN117": return 1; // TODO: Blessing of Aether cardID to be edited
        case "DYN121": return 0;
        case "DYN122": return 2;
        case "DYN123": return 0;
        case "DYN242": return 2;
        default: return 0;
    }
}

function DYNPitchValue($cardID)
{
    switch ($cardID) {
        case "DYN005": return 0;
        case "DYN039": return 1;
        case "DYN040": return 2;
        case "DYN069": return 0; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN113": return 0;
        case "DYN116": return 1; // TODO: Blessing of Aether cardID to be edited
        case "DYN117": return 2; // TODO: Blessing of Aether cardID to be edited
        case "DYN118": return 0;
        case "DYN188": case "DYN206": case "DYN230": return 1;
        case "DYN189": case "DYN207": case "DYN231": return 2;
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
        case "DYN069": return -1; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN075": return -1; // TODO: Yoji cardID to be modified with set release
        case "DYN088": return -1;
        case "DYN094": return -1;
        case "DYN113": return 0;
        case "DYN118": return 1;
        case "DYN116": case "DYN117": case "DYN118": return 2; // TODO: Blessing of Aether cardID to be edited
        case "DYN151": return -1;
        case "DYN171": return 1;
        case "DYN188": case "DYN189": case "DYN190": return 2;
        case "DYN192": return -1;
        case "DYN230": case "DYN231": case "DYN232": return 2;
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
        case "DYN069": return 1; // TODO: Quicksilver Dagger CardID might change on set release
        case "DYN088": return 5;
        case "DYN121": return 3;
        case "DYN122": return 4;
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
            $deck = &GetDeck($currentPlayer);
            AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
            AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
            if (CardSubType($deck[0]) != "Arrow")
            {
                AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
                AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
                AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
            }
            else
            {
                AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <0> in your arsenal", 1);
                AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_put_the_card_in_arsenal", 1);
            }
            AddDecisionQueue("SANDSCOURGREATBOW", $currentPlayer, "-");
            return "";
        case "DYN171":
            AddCurrentTurnEffect($cardID, $currentPlayer);
            return CardLink("ARC112", "ARC112") . "s you control have spellvoid 1 this turn.";
        case "DYN188": case "DYN189": case "DYN190":
            if (CanRevealCards($currentPlayer)) {
                $deck = GetDeck($currentPlayer);
                if (count($deck) == 0) return "Your deck is empty. Nothing was revealed.";
                if (PitchValue($deck[0]) == PitchValue($cardID)) {
                    PlayAura("ARC112", $currentPlayer, 1, true);
                    return "Reveals " . CardLink($deck[0], $deck[0]) . " and creates a " . CardLink("ARC112", "ARC112");
                } else {
                    return "Reveals " . CardLink($deck[0], $deck[0]);
                }
            }
            return "Reveal has been prevented.";
        case "DYN192":
            DealArcane(1, 1, "ABILITY", $cardID, resolvedTarget: $target);
            AddDecisionQueue("SURGENTAETHERTIDE", $currentPlayer, "-");
            return "";
        case "DYN206": case "DYN207": case "DYN208":
            DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
            return "";
        case "DYN230": case "DYN231": case "DYN232":
            if (CanRevealCards($currentPlayer)) {
                $deck = GetDeck($currentPlayer);
                if (count($deck) == 0) return "Your deck is empty. Nothing was revealed.";
                if (PitchValue($deck[0]) == PitchValue($cardID)) {
                    PlayAura("MON104", $currentPlayer, 1, true);
                    return "Reveals " . CardLink($deck[0], $deck[0]) . " and creates a " . CardLink("MON104", "MON104");
                } else {
                    return "Reveals " . CardLink($deck[0], $deck[0]);
                }
            }
            return "Reveal has been prevented.";
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
  global $mainPlayer, $defPlayer;
  switch ($cardID) {
    case "DYN118":
      if (IsHeroAttackTarget()) {
      $deck = &GetDeck($defPlayer);
      if(count($deck) == 0) WriteLog("The opponent is already... depleted.");
      $cardToBanish = array_shift($deck);
      BanishCardForPlayer($cardToBanish, $otherPlayer, "DECK", "-", $mainPlayer);
      }
      break;
    case "DYN122":
    if (IsHeroAttackTarget()) {
        $otherPlayer = ($mainPlayer == 1 ? 2 : 1);
        $deck = &GetDeck($otherPlayer);
        if(count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $otherPlayer, "DECK", "-", $mainPlayer);
        AddDecisionQueue("FINDINDICES", $otherPlayer, "HAND");
        AddDecisionQueue("CHOOSETHEIRHAND", $mainPlayer, "<-", 1);
        AddDecisionQueue("MULTIREMOVEHAND", $otherPlayer, "-", 1);
        AddDecisionQueue("MULTIBANISH", $otherPlayer, "HAND,NA," . $mainPlayer, 1);
      }
      break;
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

function ContractType($cardID)
{
  switch($cardID)
  {
    case "DYN122": return "BLUEPITCH";
    default: return "";
  }
}

function ContractCompleted($player, $cardID)
{
  WriteLog("Player " . $banishedBy . " completed the contract for " . CardLink($cardID, $cardID) . ".");
  switch($cardID)
  {
    case "DYN122":
      PutItemIntoPlayForPlayer("EVR195", $player);
      break;
    default: break;
  }
}

function CheckContracts($banishedBy, $cardBanished)
{
  global $combatChain;
  for($i=0; $i<count($combatChain); $i+=CombatChainPieces())
  {
    if($combatChain[$i+1] != $banishedBy) continue;
    $contractType = ContractType($combatChain[$i]);
    $contractCompleted = false;
    switch($contractType)
    {
      case "BLUEPITCH":
        if(PitchValue($cardBanished) == 3) $contractCompleted = true;
        break;
      default: break;
    }
    if($contractCompleted) ContractCompleted($banishedBy, $combatChain[$i]);
  }
}
