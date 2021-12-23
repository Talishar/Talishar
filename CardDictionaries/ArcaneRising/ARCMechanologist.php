<?php


  function ARCMechanologistCardType($cardID)
  {
    switch($cardID)
    {
      case "ARC001": case "ARC002": return "C";
      case "ARC003": return "W";
      case "ARC004": case "ARC005": return "E";
      case "ARC006": case "ARC007": return "A";
      case "ARC008": return "AA";
      case "ARC009": case "ARC010": return "A";
      case "ARC011": case "ARC012": case "ARC013": return "AA";
      case "ARC014": case "ARC015": case "ARC016": return "A";
      case "ARC017": case "ARC018": case "ARC019": return "A";
      case "ARC020": case "ARC021": case "ARC022": return "AA";
      case "ARC023": case "ARC024": case "ARC025": return "AA";
      case "ARC026": case "ARC027": case "ARC028": return "AA";
      case "ARC029": case "ARC030": case "ARC031": return "AA";
      case "ARC032": case "ARC033": case "ARC034": return "A";
      case "ARC035": case "ARC036": case "ARC037": return "A";
      default: return "";
    }
  }

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
      case "ARC001": case "ARC002": case "ARC003": return 0;
      case "ARC004": return 2;
      case "ARC005": return 0;
      case "ARC007": case "ARC010": case "ARC017": case "ARC018": case "ARC019": return 0;
      case "ARC035": case "ARC036": case "ARC037": return 0;
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

  function ARCMechanologistPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $myClassState, $CS_NumBoosted, $CS_CharacterIndex, $myCharacter, $actionPoints, $combatChainState, $CS_PlayIndex, $myItems;
    global $CCS_CurrentAttackGainedGoAgain, $combatChain, $myDeck, $myResources, $myBanish;
    $rv = "";
    switch($cardID)
    {
      case "ARC003":
        $myCharacter[$myClassState[$CS_CharacterIndex] + 2] = ($myCharacter[$myClassState[$CS_CharacterIndex] + 2] == 0 ? 1 : 0);
        return "";
      case "ARC004":
        for($i=0; $i<2; ++$i)
        {
          if(count($myDeck) == $i) { $rv .= "No cards in deck. Could not banish more."; return $rv; }
          $banished = $myDeck[$i];
          $rv .= "Banished $banished";
          if(CardClass($banished) == "MECHANOLOGIST") { $myResources[0] += 1; $rv .= " and gained 1 resource. "; }
          else { $rv .= ". "; }
          BanishCardForPlayer($banished, $currentPlayer, "DECK");
          unset($myDeck[$i]);
        }
        $myDeck = array_values($myDeck);
        return $rv;
      case "ARC005":
        $actionPoints += 1;
        return "Achilles Accelerator gave you an action point.";
      case "ARC006":
        MyDrawCard();
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "High Octane draws you a card and gives you an action point every time you boost a card this turn.";
      case "ARC009":
        $items = SearchMyDeck("", "Item", $resourcesPaid/2);
        AddDecisionQueue("CHOOSEDECK", $currentPlayer, $items);
        AddDecisionQueue("PUTPLAY", $currentPlayer, "-");
        $boosted = $myClassState[$CS_NumBoosted] > 0;
        if($boosted) AddDecisionQueue("DRAW", $currentPlayer, "-");
        return "Spark of Genius let you search your deck for a Mechanologist item card with cost " . $resourcesPaid/2 . " or less" . ($boosted ? " and draw a card" : "") . ".";
      case "ARC010":
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          $myItems[$index + 1] = ($myItems[$index + 1] == 0 ? 1 : 0);
          if($myItems[$index + 1] == 0)
          {
            $combatChainState[$CCS_CurrentAttackGainedGoAgain] = 1;
            $myItems[$index + 2] = 1;
            $rv = "Induction Chamber gives your pistol attack go again.";
          }
          else
          {
            $rv = "Induction Chamber gained a steam counter.";
          }
        }
        return $rv;
      case "ARC014": case "ARC015": case "ARC016":
        AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
        AddDecisionQueue("MAYCHOOSEHAND", $currentPlayer, "<-", 1);
        AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
        AddDecisionQueue("PUTPLAY", $currentPlayer, ($myClassState[$CS_NumBoosted] > 0 ? 1 : 0), 1);
        return "";
      case "ARC017":
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          $myItems[$index + 1] = ($myItems[$index + 1] == 0 ? 1 : 0);
          if($myItems[$index + 1] == 0)
          {
            AddCurrentTurnEffect($cardID, $currentPlayer);
            $rv = "Aether Sink gains +2 arcane barrier this turn.";
          }
          else
          {
            $rv = "Aether Sink gained a steam counter.";
          }
        }
        return $rv;
      case "ARC018":
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          $myItems[$index + 1] = ($myItems[$index + 1] == 0 ? 1 : 0);
          if($myItems[$index + 1] == 0)
          {
            $myItems[$index + 2] = 1;
            $rv = "Cognition Nodes makes your attack go on the bottom of your deck if it hits.";
          }
          else
          {
            $rv = "Cognition Nodes gained a steam counter.";
          }
        }
        return $rv;
      case "ARC019"://Convection Amplifier
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          --$myItems[$index+1];
          if($myItems[$index+1] <= 0) DestroyMyItem($index);
          $rv = "Convection Amplifier gives your next attack this turn Dominate.";
        }
        return $rv;
      case "ARC032": case "ARC033": case "ARC034":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        $boosted = $myClassState[$CS_NumBoosted] > 0;
        if($boosted) Opt($cardID, 1);
        return "Locked and Loaded gives your next Mechanologist attack action card this turn +" . EffectAttackModifier($cardID) . ($boosted ? " and let you opt 1" : "") . ".";
      case "ARC035":
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          AddCurrentTurnEffect("PREVENT-" . $myItems[$index+1], $currentPlayer);
          $rv = "Dissipation Shield will prevent some of the next combat damage you take this turn.";
          DestroyMyItem($index);
        }
        return $rv;
      case "ARC037"://Optekal Monocle
        $index = $myClassState[$CS_PlayIndex];
        if($index != -1)
        {
          Opt($cardID, 1);
          --$myItems[$index+1];
          if($myItems[$index+1] <= 0) DestroyMyItem($index);
          $rv = "Optekal Monocle lets you Opt 1.";
        }
        return $rv;
      default: return "";
    }
  }

  function ARCMechanologistHitEffect($cardID)
  {
    global $mainPlayer, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "ARC011": case "ARC012": case "ARC013": AddCurrentTurnEffectFromCombat($cardID, $mainPlayer); break;
      case "ARC018": $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK"; break;
      case "ARC020": case "ARC021": case "ARC022":
        $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
        break;
      default: break;
    }
  }

  function HasBoost($cardID)
  {
    switch($cardID)
    {
      case "ARC011": case "ARC012": case "ARC013":
      case "ARC020": case "ARC021": case "ARC022":
      case "ARC023": case "ARC024": case "ARC025":
      case "ARC026": case "ARC027": case "ARC028":
      case "ARC029": case "ARC030": case "ARC031":
      case "CRU106": case "CRU107": case "CRU108":
      case "CRU109": case "CRU110": case "CRU111":
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
    if(SearchCurrentTurnEffects("CRU102", $currentPlayer))
    {
      AddDecisionQueue("DRAW", $currentPlayer, "-", 1);
      AddDecisionQueue("FINDINDICES", $currentPlayer, "MYHAND", 1);
      AddDecisionQueue("CHOOSEHAND", $currentPlayer, "<-", 1);
      AddDecisionQueue("REMOVEMYHAND", $currentPlayer, "-", 1);
      AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
    }
    AddDecisionQueue("BOOST", $currentPlayer, "-", 1);
  }

  function DoBoost()
  {
    global $playerID, $myDeck, $myBanish, $myClassState, $combatChainState, $CCS_CurrentAttackGainedGoAgain, $myClassState, $CS_NumBoosted, $currentPlayer, $actionPoints, $CCS_NumBoosted, $combatChain, $CCS_NextBoostBuff;
    if(count($myDeck) == 0) { WriteLog("Could not boost. No cards left in deck."); return; }
    ItemBoostEffects();
    if(SearchCurrentTurnEffects("ARC006", $currentPlayer)) ++$actionPoints;//High Octane
    $cardID = $myDeck[0];
    BanishCardForPlayer($cardID, $currentPlayer, "DECK", "BOOST");
    unset($myDeck[0]);
    $myDeck = array_values($myDeck);
    $grantsGA = CardClass($cardID) == "MECHANOLOGIST";
    WriteLog("Boost banished $cardID and " . ($grantsGA ? "DID" : "did NOT") . " grant Go Again.");
    if($grantsGA) { GiveAttackGoAgain(); }
    ++$myClassState[$CS_NumBoosted];
    ++$combatChainState[$CCS_NumBoosted];
    $combatChain[5] += $combatChainState[$CCS_NextBoostBuff];
    $combatChainState[$CCS_NextBoostBuff] = 0;
  }

  function ItemBoostEffects()
  {
    global $myItems, $myResources;
    for($i=count($myItems) - ItemPieces(); $i >= 0; $i -= ItemPieces())
    {
      switch($myItems[$i])
      {
        case "ARC036":
          if($myItems[$i+2] == 2)
          {
            --$myItems[$i+1];
            if($myItems[$i+1] <= 0) DestroyMyItem($i);
            $myItems[$i+2] = 1;
            $myResources[0] += 1;
          }
          break;
        default: break;
      }
    }
  }

?>

