<?php


  function ELETalentCardType($cardID)
  {
    switch($cardID)
    {
      case "ELE092": return "A";
      case "ELE097": case "ELE098": case "ELE099": return "AA";
      case "ELE100": case "ELE101": case "ELE102": return "AA";
      case "ELE103": case "ELE104": case "ELE105": return "A";
      case "ELE111": return "T";
      case "ELE112": return "I";
      case "ELE113": return "A";
      case "ELE114": return "DR";
      case "ELE115": return "E";
      case "ELE117": return "A";
      case "ELE118": return "A";
      case "ELE119": case "ELE120": case "ELE121": return "AA";
      case "ELE122": case "ELE123": case "ELE124": return "A";
      case "ELE128": case "ELE129": case "ELE130": return "AA";
      case "ELE143": return "A";
      case "ELE146": return "A";
      case "ELE148": case "ELE149": case "ELE150": return "AA";
      case "ELE154": case "ELE155": case "ELE156": return "A";
      case "ELE160": case "ELE161": case "ELE162": return "AA";
      case "ELE163": case "ELE164": case "ELE165": return "A";
      case "ELE169": case "ELE170": case "ELE171": return "A";
      case "ELE172": return "A";
      case "ELE173": return "E";
      case "ELE175": return "A";
      case "ELE176": return "I";
      case "ELE180": case "ELE181": case "ELE182": return "A";
      case "ELE195": case "ELE196": case "ELE197": return "AA";
      case "ELE198": case "ELE199": case "ELE200": return "A";
      case "ELE201": return "A";
      default: return "";
    }
  }

  function ELETalentCardSubType($cardID)
  {
    switch($cardID)
    {
      case "ELE111": return "Aura";
      case "ELE115": return "Head";
      case "ELE117": return "Aura";
      case "ELE143": return "Item";
      case "ELE146": return "Aura";
      case "ELE172": return "Item";
      case "ELE173": return "Arms";
      case "ELE175": return "Aura";
      case "ELE201": return "Item";
      default: return "";
    }
  }

  //Minimum cost of the card
  function ELETalentCardCost($cardID)
  {
    switch($cardID)
    {
      case "ELE092": return 1;
      case "ELE097": case "ELE098": case "ELE099": return 1;
      case "ELE100": case "ELE101": case "ELE102": return 0;
      case "ELE103": case "ELE104": case "ELE105": return 0;
      case "ELE112": return 0;
      case "ELE113": return 0;
      case "ELE114": return 2;
      case "ELE117": return 3;
      case "ELE118": return 3;
      case "ELE119": case "ELE120": case "ELE121": return 3;
      case "ELE122": case "ELE123": case "ELE124": return 0;
      case "ELE128": case "ELE129": case "ELE130": return 3;
      case "ELE143": return 0;
      case "ELE146": return 2;
      case "ELE148": case "ELE149": case "ELE150": return 2;
      case "ELE154": case "ELE155": case "ELE156": return 0;
      case "ELE160": case "ELE161": case "ELE162": return 2;
      case "ELE163": case "ELE164": case "ELE165": return 0;
      case "ELE169": case "ELE170": case "ELE171": return 0;
      case "ELE172": return 0;
      case "ELE175": return 1;
      case "ELE176": return 0;
      case "ELE180": case "ELE181": case "ELE182": return 0;
      case "ELE195": case "ELE196": case "ELE197": return 1;
      case "ELE198": case "ELE199": case "ELE200": return 1;
      case "ELE201": return 0;
      default: return 0;
    }
  }

  function ELETalentPitchValue($cardID)
  {
    switch($cardID)
    {
      case "ELE092": return 1;
      case "ELE097": case "ELE100": case "ELE103": return 1;
      case "ELE098": case "ELE101": case "ELE104": return 2;
      case "ELE099": case "ELE102": case "ELE105": return 3;
      case "ELE112": return 1;
      case "ELE113": return 2;
      case "ELE114": return 3;
      case "ELE117": return 1;
      case "ELE118": return 3;
      case "ELE119": case "ELE122": case "ELE128": return 1;
      case "ELE120": case "ELE123": case "ELE129": return 2;
      case "ELE121": case "ELE124": case "ELE130": return 3;
      case "ELE143": return 3;
      case "ELE146": return 3;
      case "ELE148": case "ELE154": case "ELE160": case "ELE163": case "ELE169": return 1;
      case "ELE149": case "ELE155": case "ELE161": case "ELE164": case "ELE170": return 2;
      case "ELE150": case "ELE156": case "ELE162": case "ELE165": case "ELE171": return 3;
      case "ELE172": return 3;
      case "ELE175": return 2;
      case "ELE176": return 3;
      case "ELE180": case "ELE195": case "ELE198": return 1;
      case "ELE181": case "ELE196": case "ELE199": return 2;
      case "ELE182": case "ELE197": case "ELE200": return 3;
      case "ELE201": return 3;
      default: return 0;
    }
  }

  function ELETalentBlockValue($cardID)
  {
    switch($cardID)
    {
      case "ELE113": return 3;
      case "ELE114": return 6;
      case "ELE115": return 0;
      case "ELE117": return 3;
      case "ELE128": case "ELE129": case "ELE130": return 3;
      case "ELE143": return 0;
      case "ELE146": return 3;
      case "ELE160": case "ELE161": case "ELE162": return 3;
      case "ELE172": return 0;
      case "ELE173": return 0;
      case "ELE175": return 3;
      case "ELE176": return 0;
      case "ELE201": return 0;
      default: return 2;
    }
  }

  function ELETalentAttackValue($cardID)
  {
    switch($cardID)
    {
      //Elemental
      case "ELE097": return 5;
      case "ELE098": case "ELE100": return 4;
      case "ELE099": case "ELE101": return 3;
      case "ELE102": return 2;
      //Earth
      case "ELE119": case "ELE128": return 7;
      case "ELE120": case "ELE129": return 6;
      case "ELE121": case "ELE130": return 5;
      //Ice
      case "ELE160": return 6;
      case "ELE148": case "ELE161": return 5;
      case "ELE149": case "ELE162": return 4;
      case "ELE150": return 3;
      //Lightning
      case "ELE195": return 5;
      case "ELE196": return 4;
      case "ELE197": return 3;
      default: return 0;
    }
  }

  function ELETalentPlayAbility($cardID, $from, $resourcesPaid)
  {
    global $currentPlayer, $otherPlayer, $CS_PlayIndex, $mainPlayer, $actionPoints, $combatChainState, $CCS_GoesWhereAfterLinkResolves;
    switch($cardID)
    {
      case "ELE097": case "ELE098": case "ELE099":
        Fuse($cardID, $currentPlayer, "ICE");
        return "";
      case "ELE100": case "ELE101": case "ELE102":
        Fuse($cardID, $currentPlayer, "LIGHTNING");
        return "";
      case "ELE103": case "ELE104": case "ELE105":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Invigorate gives the next attack you Fuse this turn +" . EffectAttackModifier($cardID) . ".";
      case "ELE112":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Pulse of Volthaven gives your next Lightning, Ice, or Elemental attack this turn +4.";
      case "ELE113":
        for($i=0; $i<2; ++$i)
        {
          AddDecisionQueue("FINDINDICES", $currentPlayer, $cardID);
          AddDecisionQueue("MAYCHOOSEDISCARD", $currentPlayer, "<-", 1);
          AddDecisionQueue("REMOVEDISCARD", $currentPlayer, "-", 1);
          AddDecisionQueue("MULTIADDTOPDECK", $currentPlayer, "-", 1);
        }
        return "";
      case "ELE114":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Pulse of Isenloft gives your Ice, Earth, and Elemental action cards +1 defense this turn.";
      case "ELE119": case "ELE120": case "ELE121":
        if($from == "ARS")
        {
          $combatChainState[$CCS_GoesWhereAfterLinkResolves] = "BOTDECK";
          $rv = "Evergreen goes to the bottom of your deck when the combat chain closes.";
        }
        return $rv;
      case "ELE122": case "ELE123": case "ELE124":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Weave Earth gives your next Earth or Elemental attack action card this turn +" . EffectAttackModifier($cardID) .", and +1 if it's fused.";
      case "ELE143":
        if($from == "PLAY")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          if($index != -1)
          {
            AddCurrentTurnEffect($cardID, $currentPlayer);
            DestroyMyItem($index);
          }
          $rv = "Amulet of Earth gives your attack actions cards +1 attack and +1 defense for the rest of the turn.";
        }
        return $rv;
      case "ELE154": case "ELE155": case "ELE156":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Weave Ice gives your next Ice or Elemental attack action card this turn +" . EffectAttackModifier($cardID) . " and Dominate if it's fused.";
      case "ELE163": case "ELE164": case "ELE165":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Chill to the Bone makes your next Ice or Elemental attack create Frostbite tokens for your opponent.";
      case "ELE169": PayOrDiscard($otherPlayer, 3); return "Winter's Bite makes the opponent pay 3 or discard a card.";
      case "ELE170": PayOrDiscard($otherPlayer, 2); return "Winter's Bite makes the opponent pay 2 or discard a card.";
      case "ELE171": PayOrDiscard($otherPlayer, 1); return "Winter's Bite makes the opponent pay 1 or discard a card.";
      case "ELE172":
        if($from == "PLAY")
        {
          $index = GetClassState($currentPlayer, $CS_PlayIndex);
          if($index != -1)
          {
            PayOrDiscard($otherPlayer, 2);
            DestroyMyItem($index);
          }
          $rv = "Amulet of Ice makes your opponent pay 2 resources or discard a card.";
        }
        return $rv;
      //Lightning
      case "ELE173":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return "Shock Charmers makes your next attack action card deal 1 damage if it hits.";
      case "ELE176":
        if($currentPlayer == $mainPlayer) {++$actionPoints; $rv = "Blink grants an action point."; }
        return $rv;
      case "ELE180": case "ELE181": case "ELE182":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        return $rv;
      case "ELE195": case "ELE196": case "ELE197":
        if($from == "PLAY")
        {
          AddCurrentTurnEffect($cardID, $currentPlayer);
          $rv = "Shock Striker deals 1 extra damage if it gets a hero.";
        }
        return $rv;
      case "ELE198": case "ELE199": case "ELE200":
        AddCurrentTurnEffect($cardID, $currentPlayer);
        if($from == "ARS") MyDrawCard();
        return $rv;
      default: return "";
    }
  }

  function ELETalentHitEffect($cardID)
  {
    global $defPlayer;
    switch($cardID)
    {
      case "ELE148": case "ELE149": case "ELE150":
        PayOrDiscard($defPlayer, 2);
        return "";
      default: break;
    }
  }

?>

