<?php

  function ARCMechanologistCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ARC003": return "Pistol";
      case "ARC004": return "Chest";
      case "ARC005": return "Legs";
      case "ARC007": case "ARC010": case "ARC017": case "ARC018": case "ARC019":
      case "ARC035": case "ARC036": case "ARC037": return "Item";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ARCMechanologistCardCost($cardID)
  {
    switch($cardID)
    {
      case "ARC005": return 0;
      case "ARC006": return 1;
      case "ARC007": return 0;
      case "ARC008": return 2;
      case "ARC009": return 0;
      case "ARC010": return 2;
      case "ARC011": case "ARC012": case "ARC013": return 2;
      case "ARC014": case "ARC015": case "ARC016": return 0;
      case "ARC017": case "ARC018": return 1;
      case "ARC019": return 0;
      case "ARC020": case "ARC021": case "ARC022": return 2;
      case "ARC023": case "ARC024": case "ARC025": return 2;
      case "ARC026": case "ARC027": case "ARC028": return 0;
      case "ARC029": case "ARC030": case "ARC031": return 1;
      case "ARC032": case "ARC033": case "ARC034": return 0;
      case "ARC035": return 2;
      case "ARC036": return 1;
      case "ARC037": return 0;
      default: return 0;
    }
  }

  function ARCMechanologistPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ARC001": case "ARC002": case "ARC003": case "ARC004": case "ARC005": return 0;
      case "ARC006": return 1;
      case "ARC007": return 3;
      case "ARC008": return 1;
      case "ARC009": return 2;
      case "ARC010": return 1;
      case "ARC011": case "ARC014": case "ARC019": case "ARC020": case "ARC023": case "ARC026": case "ARC029": case "ARC032": case "ARC036": return 1;
      case "ARC012": case "ARC015": case "ARC017": case "ARC021": case "ARC024": case "ARC027": case "ARC030": case "ARC033": case "ARC035": return 2;
      default: return 3;
    }
  }

  function ARCMechanologistBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ARC001": case "ARC002": case "ARC003": return -1;
      case "ARC004": return 2;
      case "ARC005": return 0;
      case "ARC007": case "ARC010": case "ARC017": case "ARC018": case "ARC019": return -1;
      case "ARC035": case "ARC036": case "ARC037": return -1;
      default: return 3;
    }
  }

  function ARCMechanologistAttackValue($cardID)
  {
    switch($cardID)
    {
      case "ARC003": return 2;
      case "ARC008": return 10;
      case "ARC011": return 5;
      case "ARC012": return 4;
      case "ARC013": return 3;
      case "ARC020": return 5;
      case "ARC021": return 4;
      case "ARC022": return 3;
      case "ARC023": return 6;
      case "ARC024": return 5;
      case "ARC025": return 4;
      case "ARC026": return 4;
      case "ARC027": return 3;
      case "ARC028": return 2;
      case "ARC029": return 5;
      case "ARC030": return 4;
      case "ARC031": return 3;
      default: return 0;
    }
  }

function ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid, $target = "-", $additionalCosts = "")
{
  global $currentPlayer, $CS_NumBoosted, $actionPoints, $combatChainState, $CS_PlayIndex;
  global $CCS_CurrentAttackGainedGoAgain, $combatChain, $CS_LastDynCost;
  $rv = "";
  switch ($cardID) {
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
      for ($i = 0; $i < 2; ++$i) {
        if (count($deck) < $i) {
          $rv .= "No cards in deck. Could not banish more.";
          return $rv;
        }
        $banished = $deck[$i];
        $rv .= "Banished " . CardLink($banished, $banished);
        if (ClassContains($banished, "MECHANOLOGIST", $currentPlayer)) {
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
      $actionPoints += 1;
      return "Gives you an action point.";
    case "ARC006":
      MyDrawCard();
      AddCurrentTurnEffect($cardID, $currentPlayer);
      return "Draws you a card and gives you an action point every time you boost a card this turn.";
    case "ARC009":
      AddDecisionQueue("FINDINDICES", $currentPlayer, "DECKMECHITEMCOST," . (GetClassState($currentPlayer, $CS_LastDynCost) / 2));
      AddDecisionQueue("MAYCHOOSEDECK", $currentPlayer, "<-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, "-");
      AddDecisionQueue("SHUFFLEDECK", $currentPlayer, "-");
      $boosted = GetClassState($currentPlayer, $CS_NumBoosted) > 0;
      if ($boosted) AddDecisionQueue("DRAW", $currentPlayer, "-");
      return "Let you search your deck for a Mechanologist item card with cost " . GetClassState($currentPlayer, $CS_LastDynCost) / 2 . ($boosted ? " and draw a card" : "") . ".";
    case "ARC010":
      if ($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if (count($combatChain) > 0) {
          if(ClassContains($combatChain[0], "MECHANOLOGIST", $currentPlayer)) {
            $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
            $rv = "Gives your pistol attack go again.";
          }
        } else {
          $items[$index + 1] = 1;
          $rv = "Gained a steam counter.";
        }
      }
      return $rv;
    case "ARC014":
    case "ARC015":
    case "ARC016":
      AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
      AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("PUTPLAY", $currentPlayer, (GetClassState($currentPlayer, $CS_NumBoosted) > 0 ? 1 : 0), 1);
      return "";
    case "ARC017":
      $index = GetClassState($currentPlayer, $CS_PlayIndex);
      $items = &GetItems($currentPlayer);
      if ($index != -1) {
        $items[$index + 1] = ($items[$index + 1] == 0 ? 1 : 0);
        if ($items[$index + 1] == 0) {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $items[$index + 2] = 2;
          $rv = "Gains +2 arcane barrier this turn.";
        } else {
          $rv = "Gained a steam counter.";
        }
      }
      return $rv;
    case "ARC018":
      if ($from == "PLAY") {
        $items = &GetItems($currentPlayer);
        $index = GetClassState($currentPlayer, $CS_PlayIndex);
        if (count($combatChain) > 0) {
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
      if ($index != -1) {
        AddCurrentTurnEffect($cardID, $currentPlayer);
        --$items[$index + 1];
        if ($items[$index + 1] <= 0) DestroyMyItem($index);
        $rv = "Gives your next attack this turn Dominate.";
      }
      return $rv;
    case "ARC032":
    case "ARC033":
    case "ARC034":
      AddCurrentTurnEffect($cardID, $currentPlayer);
      $boosted = GetClassState($currentPlayer, $CS_NumBoosted) > 0;
      if ($boosted) Opt($cardID, 1);
      return "Gives your next Mechanologist attack action card this turn +" . EffectAttackModifier($cardID) . ($boosted ? " and let you opt 1" : "") . ".";
    case "ARC035":
      AddCurrentTurnEffect($cardID . "-" . $additionalCosts, $currentPlayer, "PLAY");
      $rv = "Will prevent some of the next combat damage you take this turn.";
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
    default:
      return "";
  }
}

function ARCMechanologistHitEffect($cardID)
{
  global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
  switch ($cardID) {
    case "ARC011":
    case "ARC012":
    case "ARC013":
      AddCurrentTurnEffectFromCombat($cardID, $mainPlayer);
      break;
    case "ARC018":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    case "ARC020":
    case "ARC021":
    case "ARC022":
      $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
      break;
    default:
      break;
  }
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
    default:
      return false;
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
  for ($i = count($items) - ItemPieces(); $i >= 0; $i -= ItemPieces()) {
    switch ($items[$i]) {
      case "ARC036":
      case "DYN110": case "DYN111": case "DYN112":
        if ($items[$i + 2] == 2) {
          AddLayer("TRIGGER", $currentPlayer, $items[$i], $i, "-", $items[$i + 4]);
        }
        break;
      case "EVR072":
        if ($items[$i + 2] == 2) {
          WriteLog(CardLink($items[$i], $items[$i]) . " gives the attack +2.");
          --$items[$i + 1];
          $items[$i + 2] = 1;
          AddCurrentTurnEffect("EVR072", $currentPlayer, "PLAY");
          if ($items[$i + 1] <= 0) DestroyMyItem($i);
        }
        break;
      default:
        break;
    }
  }
}
