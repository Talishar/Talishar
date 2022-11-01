<?php

function DYNAbilityCost($cardID)
{
  switch ($cardID) {
    case "DYN001": return 3;
    case "DYN005": return 3;
    case "DYN068": return 3;
    case "DYN475": return 3; // TODO: Yoji cardID to be modified with set release
    case "DYN469": return 1; // TODO: Quicksilver Dagger CardID might change on set release
    case "DYN115": case "DYN116": return 2;
    case "DYN117": return 0;
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
      case "DYN469": return "AA"; // TODO: Quicksilver Dagger CardID might change on set release
      case "DYN088": return "AA";
      case "DYN475": return "I"; // TODO: Yoji cardID to be modified with set release
      case "DYN115": case "DYN116": return "AA";
      case "DYN117": return "AR";
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
    case "DYN115": case "DYN116": return true;
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
    case "DYN115":
    case "DYN116":
      return true;
    default:
      return false;
  }
}


function DYNCardTalent($cardID) // TODO
{
  $number = intval(substr($cardID, 3));
  if ($number <= 0) return "";
  else if ($number >= 1 && $number <= 2) return "ROYAL,DRACONIC";
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
    case "DYN469": return "W"; // TODO: Quicksilver Dagger CardID might change on set release
    case "DYN475": return "C"; // TODO: Yoji cardID to be modified with set release
    case "DYN088": return "W";
    case "DYN094": return "A";
    case "DYN113": return "C";
    case "DYN115": case "DYN116": return "W";
    case "DYN416": case "DYN417": case "DYN418": return "A"; // TODO: Blessing of Aether cardID to be edited
    case "DYN117": return "E";
    case "DYN118": return "E";
    case "DYN119": return "AA";
    case "DYN120": return "AA";
    case "DYN121": return "AA";
    case "DYN122": return "AA";
    case "DYN123": return "A";
    case "DYN124": case "DYN125": case "DYN126": return "AA";
    case "DYN127": case "DYN128": case "DYN129": return "AA";
    case "DYN130": case "DYN131": case "DYN132": return "AR";
    case "DYN133": case "DYN134": case "DYN135": return "AA";
    case "DYN136": case "DYN137": case "DYN138": return "AA";
    case "DYN139": case "DYN140": case "DYN141": return "AA";
    case "DYN142": case "DYN143": case "DYN144": return "AA";
    case "DYN145": case "DYN146": case "DYN147": return "AA";
    case "DYN148": case "DYN149": case "DYN150": return "AR";
    case "DYN151": return "W";
    case "DYN171": return "E";
    case "DYN174": return "A";
    case "DYN188": case "DYN189": case "DYN190": return "A";
    case "DYN192": return "W";
    case "DYN206": case "DYN207": case "DYN208": return "A";
    case "DYN230": case "DYN231": case "DYN232": return "A";
    case "DYN234": return "E";
    case "DYN242": return "A";
    case "DYN243": return "T";
    default: return "";
  }
}

function DYNCardSubtype($cardID)
{
  switch ($cardID) {
      case "DYN005": return "Rock";
      case "DYN026": return "Off-Hand";
      case "DYN045": return "Chest";
      case "DYN068": return "Axe";
      case "DYN469": return "Dagger"; // TODO: Quicksilver Dagger CardID might change on set release
      case "DYN088": return "Gun";
      case "DYN094": return "Item";
      case "DYN115": case "DYN116": return "Dagger";
      case "DYN416": case "DYN417": case "DYN418": return "Aura"; // TODO: Blessing of Aether cardID to be edited
      case "DYN117": return "Legs";
      case "DYN118": return "Head";
      case "DYN151": return "Bow";
      case "DYN171": return "Head";
      case "DYN192": return "Staff";
      case "DYN234": return "Head";
      case "DYN242": return "Item";
      case "DYN243": return "Item";

    default:
      return "";
  }
}

function DYNCardCost($cardID)
{
  switch ($cardID) {
      case "DYN039": case "DYN040": case "DYN041": return 2;
      case "DYN416": case "DYN417": case "DYN418": return 1; // TODO: Blessing of Aether cardID to be edited
      case "DYN119": return 1;
      case "DYN121": return 0;
      case "DYN122": return 2;
      case "DYN123": return 0;
      case "DYN124": case "DYN125": case "DYN126": return 0;
      case "DYN127": case "DYN128": case "DYN129": return 1;
      case "DYN133": case "DYN134": case "DYN135": return 1;
      case "DYN139": case "DYN140": case "DYN141": return 1;
      case "DYN142": case "DYN143": case "DYN144": return 0;
      case "DYN145": case "DYN146": case "DYN147": return 0;
      case "DYN148": case "DYN149": case "DYN150": return 0;
      case "DYN174": return 3;
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
    case "DYN469": return 0; // TODO: Quicksilver Dagger CardID might change on set release
    case "DYN113": return 0;
    case "DYN115": case "DYN116": return 0;
    case "DYN416": return 1; // TODO: Blessing of Aether cardID to be edited
    case "DYN417": return 2; // TODO: Blessing of Aether cardID to be edited
    case "DYN117": return 0;
    case "DYN118": return 0;
    case "DYN119": return 2;
    case "DYN120": return 1;
    case "DYN124": case "DYN127": case "DYN130": case "DYN133": case "DYN136": case "DYN139": case "DYN142": case "DYN145": case "DYN148": return 1;
    case "DYN125": case "DYN128": case "DYN131": case "DYN134": case "DYN137": case "DYN140": case "DYN143": case "DYN146": case "DYN149": return 2;
    case "DYN188": case "DYN206": case "DYN230": return 1;
    case "DYN189": case "DYN207": case "DYN231": return 2;
    case "DYN174": return 1;
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
    case "DYN469": return -1; // TODO: Quicksilver Dagger CardID might change on set release
    case "DYN475": return -1; // TODO: Yoji cardID to be modified with set release
    case "DYN088": return -1;
    case "DYN094": return -1;
    case "DYN113": return -1;
    case "DYN115": case "DYN116": return -1;
    case "DYN117": return 1;
    case "DYN118": return 1;
    case "DYN416": case "DYN417": case "DYN418": return 2; // TODO: Blessing of Aether cardID to be edited
    case "DYN151": return -1;
    case "DYN171": return 1;
    case "DYN174": return 2;
    case "DYN188": case "DYN189": case "DYN190": return 2;
    case "DYN192": return -1;
    case "DYN230": case "DYN231": case "DYN232": return 2;
    case "DYN234": return -1;
    case "DYN242": case "DYN243": return -1;
    default: return 3;
  }
}

function DYNAttackValue($cardID)
{
  switch ($cardID) {
    case "DYN005": return 7;
    case "DYN068": return 3;
    case "DYN469": return 1; // TODO: Quicksilver Dagger CardID might change on set release
    case "DYN088": return 5;
    case "DYN115": case "DYN116": return 1;
    case "DYN120": return 4;
    case "DYN121": return 3;
    case "DYN122": return 4;
    //Assassin
    case "DYN127": case "DYN133": case "DYN139": return 5;
    case "DYN119": case "DYN124": case "DYN128": case "DYN134": case "DYN136": case "DYN140": case "DYN142": case "DYN145": return 4;
    case "DYN125": case "DYN129": case "DYN135": case "DYN137": case "DYN141": case "DYN143": case "DYN146": return 3;
    case "DYN126": case "DYN144": case "DYN147": case "DYN138": return 2;
    default: return 0;
  }
}

function DYNPlayAbility($cardID, $from, $resourcesPaid, $target, $additionalCosts)
{
  global $currentPlayer, $CS_PlayIndex, $CS_NumContractsCompleted;
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
    case "DYN475": // TODO: Yoji cardID to be modified with set release
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "DYN123":
      if (GetClassState($currentPlayer, $CS_NumContractsCompleted) > 0) {
        PutItemIntoPlayForPlayer("EVR195", $currentPlayer, 0, 4);
      }
      return "";
    case "DYN130":
    case "DYN131":
    case "DYN132":
      if ($cardID == "DYN130") $amount = 4;
      else if ($cardID == "DYN131") $amount = 3;
      else $amount = 2;
      $options = GetChainLinkCards(($currentPlayer == 1 ? 2 : 1), "", "C");
      AddDecisionQueue("MAYCHOOSECOMBATCHAIN", $currentPlayer, $options);
      AddDecisionQueue("COMBATCHAINDEBUFFDEFENSE", $currentPlayer, $amount, 1);
      return "Reduce the defense of target defending card by " . $amount;
    case "DYN148": case "DYN149": case "DYN150":
      $otherPlayer = ($currentPlayer == 1 ? 2 : 1);
      AddDecisionQueue("DECKCARDS", $otherPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want sink <0> with Cut to the Chase", 1);
      AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_sink_the_opponent's_card", 1);
      AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $otherPlayer, "TOPDECK", 1);
      AddDecisionQueue("MULTIREMOVEDECK", $otherPlayer, "<-", 1);
      AddDecisionQueue("ADDBOTTOMMYDECK", $otherPlayer, "-", 1);
      return "";
    case "DYN151":
      $deck = &GetDeck($currentPlayer);
      AddDecisionQueue("DECKCARDS", $currentPlayer, "0", 1);
      AddDecisionQueue("SETDQVAR", $currentPlayer, "0", 1);
      if (CardSubType($deck[0]) != "Arrow") {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Sandscour Greatbow shows you the top of your deck: <0>");
        AddDecisionQueue("OK", $currentPlayer, "whether to put an arrow in arsenal", 1);
        AddDecisionQueue("PASSPARAMETER", $currentPlayer, "NO");
      } else {
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose if you want to put <0> in your arsenal", 1);
        AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_put_the_card_in_arsenal", 1);
      }
      AddDecisionQueue("SANDSCOURGREATBOW", $currentPlayer, "-");
      return "";
    case "DYN171":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return CardLink("ARC112", "ARC112") . "s you control have spellvoid 1 this turn.";
    case "DYN174":
      $pitchArr = explode(",", $additionalCosts);
      $attackActionPitched = 0;
      $naaPitched = 0;
      for ($i = 0; $i < count($pitchArr); ++$i) {
        if (CardType($pitchArr[$i]) == "A") $naaPitched = 1;
        if (CardType($pitchArr[$i] == "AA")) $attackActionPitched = 1;
      }
      $rv = "";
      if ($attackActionPitched) {
        // Player
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,MYALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("DESTROYALLY", $currentPlayer, "-", 1);
        // Opponent
        AddDecisionQueue("FINDINDICES", $otherPlayer, "SEARCHMZ,THEIRALLY");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("DESTROYALLY", $otherPlayer, "-", 1);

        $rv .= "each hero chose and destroyed an ally they control.";
      }
      if ($naaPitched) {
        // Player
        AddDecisionQueue("FINDINDICES", $currentPlayer, "SEARCHMZ,MYAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $currentPlayer, "<-");
        AddDecisionQueue("DESTROYAURA", $currentPlayer, "-", 1);
        // Opponent
        AddDecisionQueue("FINDINDICES", $otherPlayer, "SEARCHMZ,THEIRAURAS");
        AddDecisionQueue("CHOOSEMULTIZONE", $otherPlayer, "<-");
        AddDecisionQueue("DESTROYAURA", $otherPlayer, "-", 1);

        $rv .= "each hero chose and destroyed an aura they control.";
      }
      return $rv;
    case "DYN188":
    case "DYN189":
    case "DYN190":
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
    case "DYN206":
    case "DYN207":
    case "DYN208":
      DealArcane(ArcaneDamage($cardID), 0, "PLAYCARD", $cardID, resolvedTarget: $target);
      return "";
    case "DYN230":
    case "DYN231":
    case "DYN232":
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
      if ($from == "PLAY") {
        DestroyMyItem(GetClassState($currentPlayer, $CS_PlayIndex));
        AddDecisionQueue("SETDQCONTEXT", $currentPlayer, "Choose any number of heroes");
        AddDecisionQueue("BUTTONINPUT", $currentPlayer, "Target_Opponent,Target_Both_Heroes,Target_Yourself,Target_No_Heroes");
        AddDecisionQueue("IMPERIALWARHORN", $currentPlayer, "<-", 1);
      }
      return $rv;
    case "DYN243":
      $rv = "";
      if ($from == "PLAY") {
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
  global $mainPlayer, $defPlayer, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $CCS_DamageDealt;
  switch ($cardID) {
    case "DYN115":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffectFromCombat("DYN115", $defPlayer);
      }
      break;
    case "DYN116":
      if (IsHeroAttackTarget()) {
        AddCurrentTurnEffectFromCombat("DYN115", $defPlayer);
      }
      break;
    case "DYN117":
      if (IsHeroAttackTarget()) {
        $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
        WriteLog(CardLink($cardID, $cardID) . " gives the current Assassin attack go again.");
      }
      break;
    case "DYN118":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
      }
      break;
    case "DYN119":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        for ($i = 0; $i < $combatChainState[$CCS_DamageDealt]; ++$i) {
          if (count($deck) == 0) break;
          $cardToBanish = array_shift($deck);
          BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        }
      }
      break;
    case "DYN121":
      if (IsHeroAttackTarget() && IsRoyal($defPlayer)) {
        PlayerLoseHealth($defPlayer, GetHealth($defPlayer));
      }
      break;
    case "DYN120":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRARS", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "ARS,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN122":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
        AddDecisionQueue("FINDINDICES", $mainPlayer, "SEARCHMZ,THEIRHAND", 1);
        AddDecisionQueue("SETDQCONTEXT", $mainPlayer, "Choose which card you want to banish", 1);
        AddDecisionQueue("CHOOSEMULTIZONE", $mainPlayer, "<-", 1);
        AddDecisionQueue("MZBANISH", $mainPlayer, "HAND,-," . $mainPlayer, 1);
        AddDecisionQueue("MZREMOVE", $mainPlayer, "-", 1);
      }
      break;
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN138":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      if (IsHeroAttackTarget()) {
        $deck = &GetDeck($defPlayer);
        if (count($deck) == 0) WriteLog("The opponent is already... depleted.");
        $cardToBanish = array_shift($deck);
        BanishCardForPlayer($cardToBanish, $defPlayer, "DECK", "-", $mainPlayer);
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
    case "DYN001": return true;
    default: break;
  }
  return false;
}

function HasSurge($cardID)
{
  switch ($cardID) {
    case "DYN206": case "DYN207": case "DYN208": return true;
    default: return false;
  }
}

function ContractType($cardID)
{
  switch($cardID)
  {
    case "DYN119": return "YELLOWPITCH";
    case "DYN120": return "REDPITCH";
    case "DYN122": return "BLUEPITCH";
    case "DYN124": case "DYN125": case "DYN126": return "COST1ORLESS";
    case "DYN127": case "DYN128": case "DYN129": return "COST2ORMORE";
    case "DYN133": case "DYN134": case "DYN135": return "AA";
    case "DYN136": case "DYN137": case "DYN138": return "BLOCK2ORLESS";
    case "DYN139": case "DYN140": case "DYN141": return "REACTIONS";
    case "DYN142": case "DYN143": case "DYN144": return "GOAGAIN";
    case "DYN145": case "DYN146": case "DYN147": return "NAA";
    default: return "";
  }
}

function ContractCompleted($player, $cardID)
{
  global $CS_NumContractsCompleted;
  WriteLog("Player " . $player . " completed the contract for " . CardLink($cardID, $cardID) . ".");
  IncrementClassState($player, $CS_NumContractsCompleted);
  switch($cardID)
  {
    case "DYN119": case "DYN120": case "DYN122":
    case "DYN124": case "DYN125": case "DYN126":
    case "DYN127": case "DYN128": case "DYN129":
    case "DYN133": case "DYN134": case "DYN135":
    case "DYN136": case "DYN137": case "DYN137":
    case "DYN139": case "DYN140": case "DYN141":
    case "DYN142": case "DYN143": case "DYN144":
    case "DYN145": case "DYN146": case "DYN147":
      PutItemIntoPlayForPlayer("EVR195", $player);
      break;
    default: break;
  }
}

function CheckContracts($banishedBy, $cardBanished)
{
  global $combatChain, $chainLinks;
  //Current Chainlink
  for ($i = 0; $i < count($combatChain); $i += CombatChainPieces()) {
    if ($combatChain[$i + 1] != $banishedBy) continue;
    $contractType = ContractType($combatChain[$i]);
    $contractCompleted = false;
    switch ($contractType) {
      case "REDPITCH":
        if (PitchValue($cardBanished) == 1) $contractCompleted = true;
        break;
      case "YELLOWPITCH":
        if (PitchValue($cardBanished) == 2) $contractCompleted = true;
        break;
      case "BLUEPITCH":
        if (PitchValue($cardBanished) == 3) $contractCompleted = true;
        break;
      case "COST1ORLESS":
        if (CardCost($cardBanished) <= 1) $contractCompleted = true;
        break;
      case "COST2ORMORE":
        if (CardCost($cardBanished) >= 2) $contractCompleted = true;
        break;
      case "AA":
        if (CardType($cardBanished) == "AA") $contractCompleted = true;
        break;
      case "GOAGAIN":
        if (HasGoAgain($cardBanished)) $contractCompleted = true;
        break;
      case "NAA":
        if (CardType($cardBanished) == "A") $contractCompleted = true;
        break;
      case "BLOCK2ORLESS":
        if (BlockValue($cardBanished) <= 2) $contractCompleted = true;
        break;
      case "REACTIONS":
        if (CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR") $contractCompleted = true;
        break;
      default:
        break;
    }
    if ($contractCompleted) ContractCompleted($banishedBy, $combatChain[$i]);
  }
  //Chain Links
  for ($i = 0; $i < count($chainLinks); ++$i) {
    for ($j = 0; $j < count($chainLinks[$i]); $j += ChainLinksPieces()) {
      if ($chainLinks[$i][$j + 1] != $banishedBy) continue;
      if ($chainLinks[$i][$j + 2] == 0) continue; //Skip if the card isn't on the chain anymore
      $contractType = ContractType($chainLinks[$i][$j]);
      $contractCompleted = false;
      switch ($contractType) {
        case "REDPITCH":
          if (PitchValue($cardBanished) == 1) $contractCompleted = true;
          break;
        case "BLUEPITCH":
          if (PitchValue($cardBanished) == 3) $contractCompleted = true;
          break;
        case "COST1ORLESS":
          if (CardCost($cardBanished) <= 1) $contractCompleted = true;
          break;
        case "COST2ORMORE":
          if (CardCost($cardBanished) >= 2) $contractCompleted = true;
          break;
        case "AA":
          if (CardType($cardBanished) == "AA") $contractCompleted = true;
          break;
        case "GOAGAIN":
          if (HasGoAgain($cardBanished)) $contractCompleted = true;
          break;
        case "NAA":
          if (CardType($cardBanished) == "A") $contractCompleted = true;
          break;
        case "BLOCK2ORLESS":
          if (BlockValue($cardBanished) <= 2) $contractCompleted = true;
          break;
        case "REACTIONS":
          if (CardType($cardBanished) == "AR" || CardType($cardBanished) == "DR") $contractCompleted = true;
          break;
        default:
          break;
        }
      if ($contractCompleted) ContractCompleted($banishedBy, $chainLinks[$i][$j]);
      }
    }
}