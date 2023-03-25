<?php

function ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBoosted, $actionPoints, $combatChainState, $CS_PlayIndex;
  global $CCS_CurrentAttackGainedGoAgain, $combatChain, $CS_LastDynCost;
  $rv = "";
  switch($cardID) {
    case "ARC003":
      $abilityType = GetResolvedAbilityType($cardID);
      if($abilityType == "A")
      {
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        $character = new Character($currentPlayer, $index);
        $character->numCounters = 1;
        $character->Finished();
      }
      return "";
    case "ARC004":
      $deck = &GetDeck($currentPlayer);
      for($i = 0; $i < 2; ++$i) {
        if(count($deck) < $i) {
          $rv .= "No cards in deck. Could not banish more cards.";
          return $rv;
        }
        $banished = $deck[$i];
        $rv .= "Banished " . CardLink($banished, $banished);
        if(ClassContains($banished, "MECHANOLOGIST", $currentPlayer)) {
          GainResources($currentPlayer, 1);
          $rv .= " and gained 1 resource. ";
        } else {
          $rv .= ". ";
        }
        BanishCardForPlayer($banished, $currentPlayer, "DECK");
        unset($deck[$i]);
      }
      $deck = array_values($deck);
      return $rv;
    case "ARC005":
      GainActionPoints(1, $currentPlayer);
      return "";
    case "ARC006":
      Draw($currentPlayer);
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "";
    case "ARC009":
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYDECK:subtype=Item;class=MECHANOLOGIST;maxCost=" . (GetClassState($currentPlayer, $CS_LastDynCost) / 2));
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, 0, 1);
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      if(GetClassState($currentPlayer, $CS_NumBoosted) > 0) AddDecisionQueue("DRAW", $currentPlayer, "-");
      return "";
    case "ARC010":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if(count($combatChain) > 0) {
          if(ClassContains($combatChain[0], "MECHANOLOGIST", $currentPlayer)) {
            GiveAttackGoAgain();
            $rv = "Gives your pistol attack go again.";
          }
        } else {
          $items[$index + 1] = 1;
          $rv = "Gained a steam counter.";
        }
      }
      return $rv;
    case "ARC014": case "ARC015": case "ARC016":
      if($cardID == "ARC014") $maxCost = 2;
      else if($cardID == "ARC015") $maxCost = 1;
      if($cardID == "ARC016") $maxCost = 0;
      AddDecisionQueue("MULTIZONEINDICES", $currentPlayer, "MYHAND:subtype=Item;maxCost=$maxCost;class=MECHANOLOGIST");
      AddDecisionQueue("MAYCHOOSEMULTIZONE", $currentPlayer, "<-", 1);
      AddDecisionQueue("MZREMOVE", $currentPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, (GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? 1 : 0), 1);
      return "";
    case "ARC017":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if($index != -1) {
        $items[$index + 1] = ($items[$index + 1] == 0 ? 1 : 0);
        if($items[$index + 1] == 0) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $items[$index + 2] = 2;
          $rv = "Gains +2 arcane barrier this turn.";
        } else {
          $rv = "Gained a steam counter.";
        }
      }
      return $rv;
    case "ARC018":
      if($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if(count($combatChain) > 0) {
          $rv = "Makes your attack go on the bottom of your deck if it hits.";
        } else {
          $items[$index + 1] = 1;
          $rv = "Gained a steam counter.";
        }
      }
      return $rv;
    case "ARC019": //Convection Amplifier
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if($index != -1) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        --$items[$index + 1];
        if ($items[$index + 1] <= 0) DestroyMyItem($index);
        $rv = "Gives your next attack this turn Dominate.";
      }
      return $rv;
    case "ARC032": case "ARC033": case "ARC034":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $boosted = GetClassState($currentPlayer, $CS_NumBoosted) > 0;
      if ($boosted) Opt($cardID, 1);
      return "";
    case "ARC035":
      AddCurrentTurnEffect($cardID . "-" . $additionalCosts, $currentPlayer, "PLAY");
      $rv = "";
      return $rv;
    case "ARC037": //Optekal Monocle
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if ($index != -1) {
        Opt($cardID, 1);
        --$items[$index + 1];
        if ($items[$index + 1] <= 0) DestroyMyItem($index);
        $rv = "Lets you Opt 1.";
      }
      return $rv;
    default: return "";
  }
}

function ARCMechanologistHitEffect($cardID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  switch ($cardID) {
    case "ARC011": case "ARC012": case "ARC013":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "ARC018":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    case "ARC020": case "ARC021": case "ARC022":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    default: break;
  }
  return "";
}

function HasBoost($cardID)
{
  switch ($cardID) {
    case "ARC011": case "ARC012": case "ARC013":
    case "ARC020": case "ARC021": case "ARC022":
    case "ARC023": case "ARC024": case "ARC025":
    case "ARC026": case "ARC027": case "ARC028":
    case "ARC029": case "ARC030": case "ARC031":
    case "CRU106": case "CRU107": case "CRU108":
    case "CRU109": case "CRU110": case "CRU111":
    case "EVR073": case "EVR074": case "EVR075":
    case "EVR079": case "EVR080": case "EVR081":
    case "DYN090":
    case "DYN095": case "DYN096": case "DYN097":
		case "DYN101": case "DYN102": case "DYN103":
		case "DYN104": case "DYN105": case "DYN106":
      return true;
    default: return false;
  }
}

function Boost()
{
  global $currentPlayer;
  AddDecisionQueue("YESNO", $currentPlayer, "if_you_want_to_boost");
  AddDecisionQueue("NOPASS", $currentPlayer, "-", 1);
  AddDecisionQueue("BOOST", $currentPlayer, "-", 1);
  if (SearchCurrentTurnEffects("CRU102", $currentPlayer)) {
    AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
    AddDecisionQueue("FINDINDICES", $currentPlayer, "HAND");
    AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
    AddDecisionQueue("MULTIREMOVEHAND", $currentPlayer, "-", 1);
    AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
  }
}

function ItemBoostEffects()
{
  global $currentPlayer;
  $items = &GetItems($currentPlayer);
  for($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    switch($items[$i]) {
      case "ARC036":
      case "DYN110": case "DYN111": case "DYN112":
        if($items[$i + 2] == 2) {
          AddLayer("TRIGGER", $currentPlayer, $items[$i], $i, "-", $items[$i + 4]);
        }
        break;
      case "EVR072":
        if($items[$i + 2] == 2) {
          WriteLog(CardLink($items[$i], $items[$i]) . " gives the attack +2.");
          --$items[$i + 1];
          $items[$i + 2] = 1;
          AddCurrentTurnEffect("EVR072", $currentPlayer, "PLAY");
          if($items[$i + 1] <= 0) DestroyMyItem($i);
        }
        break;
      default:
        break;
    }
  }
}
